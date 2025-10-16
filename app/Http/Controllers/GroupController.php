<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    // основная страница
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
        $groupId = $request->input('group_id');

        $allGroups = Group::all();

        // кол-во по группам
        $productCounts = Product::select('id_group', DB::raw('COUNT(*) as cnt'))
            ->groupBy('id_group')
            ->pluck('cnt', 'id_group')
            ->toArray();
        // Рекурсивный сбор всех подгрупп
        $collectSubgroups = function ($groups, $parentId) use (&$collectSubgroups) {
            $ids = [$parentId];
            foreach ($groups->where('id_parent', $parentId) as $child) {
                $ids = array_merge($ids, $collectSubgroups($groups, $child->id));
            }
            return $ids;
        };

        // Подсчёт товаров с потомками
        $totalCounts = [];
        foreach ($allGroups as $group) {
            $ids = $collectSubgroups($allGroups, $group->id);
            $totalCounts[$group->id] = array_sum(array_intersect_key($productCounts, array_flip($ids)));
        }

        // категории
        $categories = $allGroups->where('id_parent', 0);
        $expanded = [];
        if ($groupId) {
            $current = $allGroups->firstWhere('id', $groupId);
            while ($current) {
                $expanded[$current->id] = true;
                $current = $allGroups->firstWhere('id', $current->id_parent);
            }
        }

        // Запрос на товары с ценами 
        $productsQuery = DB::table('products')
            ->leftJoin('prices', 'products.id', '=', 'prices.id_product')
            ->select('products.*', 'prices.price');

        if ($groupId) {
            $ids = $collectSubgroups($allGroups, (int) $groupId);
            $productsQuery->whereIn('products.id_group', $ids);
        }

        // Сортировка 
        $sortColumn = $sort === 'price' ? 'prices.price' : 'products.name';
        $productsQuery->orderBy($sortColumn, $direction);

        // Пагинация 
        $products = $productsQuery->paginate(6)->appends($request->query());

        return view('groups.index', [
            'categories' => $categories,
            'allGroups' => $allGroups,
            'products' => $products,
            'sort' => $sort,
            'direction' => $direction,
            'groupId' => $groupId,
            'totalCounts' => $totalCounts,
            'expanded' => $expanded,
        ]);
    }

    //страница товара 
    public function showProduct(int $id)
    {
        $product = DB::table('products')
            ->leftJoin('prices', 'products.id', '=', 'prices.id_product')
            ->select('products.*', 'prices.price')
            ->where('products.id', $id)
            ->first();

        abort_if(!$product, 404);

        // Хлебные крошки
        $breadcrumbs = [];
        $current = DB::table('groups')->where('id', $product->id_group)->first();

        while ($current) {
            $breadcrumbs[] = $current;
            $current = DB::table('groups')->where('id', $current->id_parent)->first();
        }

        return view('groups.product', [
            'product' => $product,
            'breadcrumbs' => array_reverse($breadcrumbs),
        ]);
    }
}

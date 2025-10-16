<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог товаров</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            margin: 20px;
        }
        .categories {
            width: 25%;
            padding-right: 20px;
            border-right: 1px solid #ccc;
        }
        .categories ul {
            list-style: none;
            padding-left: 0;
        }
        .categories li {
            margin: 4px 0;
        }
        .categories a {
            color: #0066cc;
            text-decoration: underline;
        }
        .categories .active {
            /* font-weight: bold; */
            color: #7c3da4ff;
            text-decoration: underline;
        }

        /* Мэйн */
        .content {
            width: 75%;
            padding-left: 20px;
        }
        .sort {
            margin-bottom: 10px;
        }
        .sort a {
            color: #0066cc;
            text-decoration: underline;
            margin-right: 8px;
        }

        .product {
            margin: 6px 0;
        }

        /* Пагинация */
        .pagination {
            margin-top: 15px;
        }
        .pagination a {
            color: #0066cc;
            text-decoration: underline;
            margin-right: 6px;
        }
    </style>
</head>

<body>
    <!-- Категории -->
    <aside class="categories">
        <ul>
            @foreach($categories as $category)
                @include('groups.partials.category_node', [
                    'cat' => $category,
                    'allGroups' => $allGroups,
                    'totalCounts' => $totalCounts,
                    'expanded' => $expanded,
                    'groupId' => $groupId
                ])
            @endforeach
        </ul>
    </aside>

    <!-- Мэйн -->
    <main class="content">
        <section class="sort">
            <strong>Сортировать:</strong>
            <a href="{{ route('groups.index', array_merge(request()->except('page'), ['sort' => 'price', 'direction' => 'asc'])) }}">по цене ↑</a> |
            <a href="{{ route('groups.index', array_merge(request()->except('page'), ['sort' => 'price', 'direction' => 'desc'])) }}">по цене ↓</a> |
            <a href="{{ route('groups.index', array_merge(request()->except('page'), ['sort' => 'name', 'direction' => 'asc'])) }}">по названию ↑</a> |
            <a href="{{ route('groups.index', array_merge(request()->except('page'), ['sort' => 'name', 'direction' => 'desc'])) }}">по названию ↓</a>
        </section>

        <hr>

        <section class="products">
            @forelse($products as $product)
                <div class="product">
                    <a href="{{ route('product.show', ['id' => $product->id]) }}">
                        {{ $product->name }}
                    </a>
                    — {{ $product->price ?? '—' }} ₽
                </div>
            @empty
                <p>В этой категории пока нет товаров.</p>
            @endforelse
        </section>

        <hr>

        <nav class="pagination">
            <span>Страница:</span>
            @for ($i = 1; $i <= $products->lastPage(); $i++)
                @if ($i === $products->currentPage())
                    {{ $i }}
                @else
                    <a href="{{ $products->url($i) }}">{{ $i }}</a>
                @endif
            @endfor
        </nav>
    </main>
</body>
</html>

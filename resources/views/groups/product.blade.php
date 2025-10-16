<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .breadcrumbs {
            margin-bottom: 20px;
        }
        .breadcrumbs a {
            color: #7c3da4ff;
            text-decoration: underline;
        }
        .breadcrumbs span {
            color: #000;
        }

        h1 {
            font-size: 35px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .price {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <nav class="breadcrumbs">
        <a href="{{ route('groups.index') }}">Главная</a>
        @foreach($breadcrumbs as $crumb)
            → <a href="{{ route('groups.index', ['group_id' => $crumb->id]) }}">
                {{ $crumb->name }}
            </a>
        @endforeach
    </nav>

    <h1>{{ $product->name }}</h1>

    <div class="price">
        Цена: {{ $product->price ? $product->price . ' руб.' : '—' }}
    </div>
</body>
</html>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @livewireStyles
    <link href="https://cdnjs.cloudflare.com/ajax/libs/vis-timeline/7.7.3/vis-timeline-graph2d.css" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=5e3fc04d-2719-490a-bce1-6c4293455286&lang=ru_RU"
            type="text/javascript"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('custom_css')
    <title>@yield('title')</title>
</head>
<body>
<header class="z-2 auth_header">
    <div class="container">
        <div class="header_row">
            <div class="avatar">
                <a href="#" id="avatar">
                    {{ mb_substr(user()->name, 0, 1, 'UTF-8') }}
                </a>
                <div class="sidebar_info" id="sidebar_info" style="display: none">
                    <p>{{ user()->name }}</p>
                    <a href="{{ route('logout') }}">Выйти</a>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="hamburger" id="hamburger">
    <i class="fa-solid fa-bars"></i>
</div>

<article class="auth_sidebar">
    <div class="sidebar_logo">
        <a href="{{ route('index') }}">
            <img src="{{ asset('img/logo.png') }}" alt="logo">
        </a>
    </div>
    <div class="sidebar_links">
        @if(user()->is_admin)
            <a href="{{ route('admin.receipts.index') }}"
               class="{{ Route::is('admin.receipts.index') ? 'active' : '' }}">
                Все рецепты
            </a>
            <a href="{{ route('admin.bids.index') }}"
               class="{{ Route::is('admin.bids.index') ? 'active' : '' }}">
                Заявки на рецепты
            </a>
            <a href="{{ route('admin.comments.index') }}"
               class="{{ Route::is('admin.comments.index') ? 'active' : '' }}">
                Отзывы на рецепты
            </a>
        @else
            <a href="{{ route('user.receipts.index') }}"
               class="{{ Route::is('user.receipts.index') ? 'active' : '' }}">
                Мои рецепты
            </a>
            <a href="{{ route('user.receipts.favorite.index') }}"
               class="{{ Route::is('user.receipts.favorite.index') ? 'active' : '' }}">
                Избранное
            </a>
            <a href="{{ route('user.comments.index') }}"
               class="{{ Route::is('user.comments.index') ? 'active' : '' }}">
                Мои отзывы
            </a>
            <a href="{{ route('user.menus.index') }}"
               class="{{ Route::is('user.menus.index') ? 'active' : '' }}">
                Мои меню
            </a>
        @endif
    </div>
</article>

@yield('content')
@livewireScripts
<script>
    window.env = {
        PUSHER_PORT: "{{ config('broadcasting.connections.websockets.options.port') }}",
        PUSHER_APP_KEY: "{{ config('broadcasting.connections.websockets.key') }}",
        TIMELINES: @json(\App\Models\Period::all())
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.8/axios.min.js"></script>
<script src="https://unpkg.com/jquery@3.7.0/dist/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis-timeline/7.7.3/vis-timeline-graph2d.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.21.0/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@7.0.3/dist/web/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.10.0/dist/echo.iife.js"></script>
<script src="{{ asset('js/all.min.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/chat.js') }}"></script>
@yield('custom_js')
</body>
</html>

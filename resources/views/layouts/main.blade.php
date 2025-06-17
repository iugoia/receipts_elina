<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/vis-timeline/7.7.3/vis-timeline-graph2d.css" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&display=swap"
        rel="stylesheet">
    <script src="https://api-maps.yandex.ru/2.1/?apikey=5e3fc04d-2719-490a-bce1-6c4293455286&lang=ru_RU"
            type="text/javascript"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @livewireStyles
    @yield('custom_css')
    <title>@yield('title')</title>
</head>
<body>

<header class="site-header">
    <div class="container">
        <div class="header-row">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="logo">
            </div>

            <nav class="desktop-nav">
                <a href="{{ route('index') }}">Главная</a>
                <a href="{{ route('catalog') }}">Рецепты</a>
                <a href="{{ route('map') }}">Лента времени</a>
                <a href="{{ route('menu') }}">Идеи для праздника</a>
            </nav>

            <div class="desktop-auth">
                @if(!auth()->check())
                    <a href="{{ route('login') }}">Вход</a>
                    <a href="{{ route('register') }}">Регистрация</a>
                @else
                    @if(user()->is_admin)
                        <a href="{{ route('user.receipts.index') }}">Админ панель</a>
                    @else
                        <a href="{{ route('user.receipts.index') }}">Личный кабинет</a>
                    @endif
                    <a href="{{ route('logout') }}">Выйти</a>
                @endif
            </div>

            <!-- Бургер -->
            <button class="burger" id="burger">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <!-- Мобильное меню -->
    <div class="mobile-menu" id="mobileMenu">
        <nav class="mobile-nav">
            <a href="{{ route('index') }}">Главная</a>
            <a href="{{ route('catalog') }}">Рецепты</a>
            <a href="{{ route('map') }}">Лента времени</a>
            <a href="{{ route('menu') }}">Подбор рецептов</a>
            @if(!auth()->check())
                <a href="{{ route('login') }}">Вход</a>
                <a href="{{ route('register') }}">Регистрация</a>
            @else
                @if(user()->is_admin)
                    <a href="{{ route('user.receipts.index') }}">Админ панель</a>
                @else
                    <a href="{{ route('user.receipts.index') }}">Личный кабинет</a>
                @endif
                <a href="{{ route('logout') }}">Выйти</a>
            @endif
        </nav>
    </div>
</header>


<div class="content">
    @yield('content')
</div>

<footer class="footer">
    <div class="footer-bottom">
        <p>&copy; Гастрономические путешествия во времени 2025</p>
        <div class="footer-menu">
            <ul class="f-menu">
                <li><a href="{{ route('index') }}">Главная</a></li>
                <li><a href="{{ route('catalog') }}">Рецепты</a></li>
                <li><a href="{{ route('menu') }}">Идеи для праздника</a></li>
            </ul>
        </div>
    </div>

</footer>

<script>
    window.env = {
        PUSHER_PORT: "{{ config('broadcasting.connections.websockets.options.port') }}",
        PUSHER_APP_KEY: "{{ config('broadcasting.connections.websockets.key') }}",
        TIMELINES: @json(\App\Models\Period::all())
    }
    document.addEventListener('DOMContentLoaded', () => {
        const burger = document.getElementById('burger');
        const menu = document.getElementById('mobileMenu');

        burger.addEventListener('click', () => {
            menu.classList.toggle('active');
            burger.classList.toggle('active');
        });
    });
</script>
@livewireScripts
<script src="https://unpkg.com/jquery@3.7.0/dist/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis-timeline/7.7.3/vis-timeline-graph2d.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@7.0.3/dist/web/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.10.0/dist/echo.iife.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="{{ asset('js/all.min.js') }}"></script>
<script src="{{ asset('js/chat.js') }}"></script>
@yield('custom_js')
</body>
</html>

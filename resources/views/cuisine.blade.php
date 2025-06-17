@extends('layouts.main')

@section('title')
    {{ $cuisine->name }}
@endsection

@section('content')
    <style>
        header {
            color: #ffffff;
        }

        .burger span {
            background: #fafafa;
        }

        .burger.active span {
            background: black;
        }
    </style>
    <section class="cuisine_header" style="background-image: url('/img/cuisines/{{ $cuisine->photo }}');">
        <div class="container">
            <h1>{{ $cuisine->name }}</h1>
            <p class="cuisine_desc">
                {{ $cuisine->description }}
            </p>
        </div>
    </section>

    <section class="cuisine_description">
        <div class="container">
            <p>{{ $cuisine->full_description }}</p>
            <p>{{ $cuisine->history }}</p>
        </div>
    </section>

    @php
        $receipts = $cuisine->receipts()->inRandomOrder()->take(3)->get();
    @endphp

    @if(count($receipts) > 0)
        <section class="popular_dishes">
            <div class="container">
                <h2>Популярные блюда</h2>
                <ul class="receipts_list">
                    @foreach($receipts as $receipt)
                        <li class="receipt_item">
                            <a href="{{ route('receipt.show', $receipt) }}" class="receipt_img">
                                <img src="{{ $receipt->getFirstMediaUrl('images') }}" alt="receipt">
                            </a>
                            <div class="receipt_content">
                                <p class="receipt_title">{{ $receipt->title }}</p>
                                <p class="popular_dishes_desc">{{ $receipt->description }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <section class="cuisine-facts">
        <div class="container">
            <h2>Интересные факты</h2>
            <ul class="cuisine_facts_list">
                @foreach(json_decode($cuisine->interesting_facts) as $fact)
                    <li>{{ $fact }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="map">
        <div class="container">
            <h2>Найдите рестораны в вашем городе</h2>
            <div class="mini_hr"></div>
            <p class="form_subtitle">Введите свой город и нажмите "Найти рестораны"</p>
            <div id="search-container">
                <input type="text" id="city-input" class="auth_form_control" placeholder="Введите город">
                <button id="search-button" class="btn btn_auth">Найти рестораны</button>
            </div>
            <div id="map" style="width: 100%; height: 500px;"></div>
        </div>
    </section>

@endsection

@section('custom_js')

    <script>
        ymaps.ready(init);

        function init() {
            var map = new ymaps.Map("map", {
                center: [55.751244, 37.618423], // Москва по умолчанию
                zoom: 10,
                controls: ['zoomControl']
            });

            document.getElementById('search-button').addEventListener('click', function () {
                let city = document.getElementById('city-input').value.trim();
                let cuisine = "{{ $cuisine->name }}";

                if (!city) {
                    alert('Введите город!');
                    return;
                }

                let query = `${cuisine} в ${city}`;

                ymaps.geocode(city, {results: 1}).then(function (res) {
                    let firstGeoObject = res.geoObjects.get(0);
                    let coords = firstGeoObject.geometry.getCoordinates();

                    map.setCenter(coords, 12);

                    let searchControl = new ymaps.control.SearchControl({
                        options: {
                            provider: 'yandex#search',
                            noPlacemark: true
                        }
                    });

                    map.controls.add(searchControl);
                    searchControl.search(query);

                }).catch(function (err) {
                    console.error(err);
                    alert("Не удалось найти рестораны!");
                });
            });
        }
    </script>
@endsection

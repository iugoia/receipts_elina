@extends('layouts.main')

@section('title')
    Лента времени
@endsection

@section('content')
    <section class="timeline">
        <div class="container">
            <h2>Лента времени</h2>
            <div class="mini_hr"></div>
            <p class="subtitle">Выберите исторический период чтобы узнать больше информации и получить рецепты.</p>

            <div class="timeline-container">
                <button id="nav-left" class="nav-btn">←</button>
                <div class="timeline-wrapper">
                    <div id="timeline-strip"></div>
                </div>
                <button id="nav-right" class="nav-btn">→</button>
            </div>
            <div id="event-details"></div>

            <div id="map"></div>
        </div>
    </section>

@endsection

@section('custom_js')
    <script src="{{ asset('js/timeline.js') }}"></script>
@endsection

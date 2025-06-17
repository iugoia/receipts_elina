@extends('layouts.auth')

@section('title')
    Все отзывы
@endsection

@section('content')
    <section class="auth_main">
        <div class="container">
            <div class="auth_main_title_div">
                <h1>Отзывы</h1>
            </div>

            <livewire:admin-comment />
        </div>
    </section>
@endsection

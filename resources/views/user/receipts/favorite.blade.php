@extends('layouts.auth')

@section('title')
    Избранное
@endsection

@section('content')
    <section class="auth_main">
        <div class="container">
            <div class="auth_main_title_div">
                <h1>Избранные рецепты</h1>
            </div>

            <livewire:favorite-table/>
        </div>
    </section>
@endsection

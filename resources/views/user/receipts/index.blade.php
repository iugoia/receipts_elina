@extends('layouts.auth')

@section('title')
    Мои рецепты
@endsection

@section('content')
    <section class="auth_main">
        <div class="container">
            <div class="auth_main_title_div">
                <h1>Рецепты</h1>
                <a href="{{ route('user.receipts.create') }}" class="btn btn_auth">Новый рецепт</a>
            </div>

            @livewire('my-receipts-table')
        </div>
    </section>
@endsection

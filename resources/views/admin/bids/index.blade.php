@extends('layouts.auth')

@section('title')
    Все заявки
@endsection

@section('content')
    <section class="auth_main">
        <div class="container">
            <div class="auth_main_title_div">
                <h1>Заявки, ожидающие решение</h1>
            </div>

            @livewire('admin-bids-table')
        </div>
    </section>
@endsection

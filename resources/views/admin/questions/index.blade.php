@extends('layouts.auth')

@section('title')
    Все заявки на звонок
@endsection

@section('content')
    <section class="auth_main">
        <div class="container">
            <div class="auth_main_title_div">
                <h1>Заявки на звонок, ожидающие решение</h1>
            </div>

            <livewire:admin-question />
        </div>
    </section>
@endsection

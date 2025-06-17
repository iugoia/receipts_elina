@extends('layouts.main')

@section('title')
    Вход
@endsection

@section('content')
    <section class="register">
        <h2>Вход</h2>
        <form action="{{ route('login.store') }}" method="post" class="register_form">
            @csrf
            <label class="form_label">
                <span class="form_label_span">E-mail:</span>
                <input type="text" class="form_control" name="email" placeholder="sample@domain.ru"
                       value="{{ old('email') }}">
                @error('email')
                <p class="red">
                    {{ $message }}
                </p>
                @enderror
            </label>
            <label class="form_label">
                <span class="form_label_span">Пароль:</span>
                <input type="password" class="form_control" name="password" placeholder="*******">
                @error('password')
                <p class="red">
                    {{ $message }}
                </p>
                @enderror
            </label>
            <button class="btn btn_primary" type="submit">Войти</button>
        </form>
    </section>
@endsection

@extends('layouts.auth')

@section('title')
    Редактирование отзыва
@endsection

@section('content')
    <section class="auth_main">
        <div class="container">
            <div class="auth_main_title_div">
                <h1>Редактирование отзыва</h1>
            </div>

            <form action="{{ route('user.receipt.comment.update', $comment) }}" class="form_form" method="post" id="comment">
                @method('PUT')
                <input type="text" name="name" class="form_control" placeholder="Ваше имя*" value="{{ old('name') ?? $comment->name }}">
                @error('name')
                <p class="red">{{ $message }}</p>
                @enderror
                <textarea name="text" cols="30" rows="10" placeholder="Отзыв*"
                          class="form_control form_textarea_medium">{{ old('text') ?? $comment->text }}</textarea>
                @error('text')
                <p class="red">{{ $message }}</p>
                @enderror
                <button class="btn btn_auth" type="submit">Редактировать</button>
            </form>
        </div>
    </section>
@endsection

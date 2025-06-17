@extends('layouts.auth')

@section('title')
    Мои отзывы
@endsection

@section('content')
    <section class="auth_main">
        <div class="container">
            <div class="auth_main_title_div">
                <h1>Мои отзывы</h1>
            </div>

            @if(session('success'))
                <p class="green mb-3">{{ session('success') }}</p>
            @endif

            @if($comments->isEmpty())
                <p class="no-reviews">У вас нет оставленных отзывов.</p>
            @else
                <div class="review-list">
                    @foreach($comments as $comment)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="review-content">
                                    <div class="review-img">
                                        <img src="{{ $comment->receipt->getFirstMediaUrl('images') }}" alt="{{ $comment->receipt->title }}">
                                    </div>
                                    <h3 class="recipe-name">
                                        <a href="{{ route('receipt.show', $comment->receipt) }}"  >{{ $comment->receipt->title }}</a>
                                    </h3>
                                </div>
                                <div class="review-subdescription">
                                    <span class="review-date">{{ $comment->created_at->format('d.m.Y') }}</span>
                                    <span class="review-status @if($comment->status === 'new') blue
                                    @elseif($comment->status === 'success') green @else red @endif">
                                        {{ \App\Enums\StatusEnum::getTitle($comment->status) }}
                                    </span>
                                </div>
                            </div>
                            <p class="review-text">{{ $comment->text }}</p>

                            <div class="review-actions">
                                <a href="{{ route('user.comments.edit', $comment) }}" class="btn btn-md btn-round btn-edit">Редактировать</a>
                                <a href="{{ route('receipt.show', $comment->receipt) }}"   class="btn btn-md btn-round btn-receipt">К рецепту</a>
                                <form action="{{ route('user.receipt.comment.delete', $comment) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-md btn-round btn-delete">Удалить</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection

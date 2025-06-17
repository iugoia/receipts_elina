@extends('layouts.main')

@section('content')
    <section class="profile">
        <div class="container">
            <h3 class="mb-4 mt-2 fw-400">Профиль пользователя: {{ $user->name }}</h3>

            @if($receipts->count() > 0)
                <h4 class="mb-3">Рецепты автора:</h4>
                <ul class="main_products_list">
                    @foreach($receipts as $receipt)
                        <li class="main_product_item">
                            <a class="main_product_img" href="{{ route('receipt.show', $receipt) }}">
                                <img src="{{ $receipt->getFirstMediaUrl('images') }}" alt="{{ $receipt->title }}">
                            </a>

                            <div class="main_product_info">
                                <p class="main_product_title">{{ $receipt->title }}</p>
                                <p class="main_product_subtitle">{{ $receipt->description }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4">
                    {{ $receipts->links() }}
                </div>
            @else
                <p>У этого пользователя пока нет рецептов.</p>
            @endif
        </div>
    </section>
@endsection

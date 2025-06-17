@extends('layouts.auth')

@section('title')
    Сформированные меню
@endsection

@section('content')
    <section class="auth_main auth_menu">
        <div class="container">
            <div class="auth_main_title_div">
                <h1>Сформированные меню</h1>
                <a href="{{ route('menu') }}" class="btn btn_auth">Новое меню</a>
            </div>

            <div>
                <div class="table_search">
                    <div class="search">
                        @if(session()->has('success'))
                            <p class="green">{{ session('success') }}</p>
                        @endif
                    </div>
                    <table class="data_table">
                        <thead>
                        <tr>
                            <th>Описание</th>
                            <th>Рецепты</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        @foreach($menusArr as $menu)
                            <tr>
                                <td style="max-width: 250px">
                                    <div class="receipt_description">
                                        {{ $menu['menu']->text }}
                                    </div>
                                </td>
                                <td style="max-width: 500px">
                                    <div class="menu_receipt_title">
                                        @foreach($menu['receipts'] as $receipt)
                                            <a href="{{ route('receipt.show', $receipt) }}"  >{{ $receipt->title }}</a>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="table_actions">
                                        <form action="{{ route('menu.download', $menu) }}" method="post">
                                            <button type="submit" class="btn btn_icon blue">
                                                <i class="fa-solid fa-download"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

        </div>
    </section>
@endsection

<div class="table_search">
    <div class="search">
        @if(session()->has('success'))
            <p class="green">{{ session('success') }}</p>
        @endif
        <input type="text" wire:model="search" class="auth_form_control" placeholder="Поиск..">
    </div>
    <table class="data_table">
        <thead>
        <tr>
            <th>Название</th>
            <th style="max-width: 250px">Описание</th>
            <th>Действия</th>
        </tr>
        </thead>
        @foreach($receipts as $receipt)
            <tr>
                <td>
                    <div class="receipt_table_img">
                        <div class="receipt_table_img_container">
                            <img src="{{ $receipt->getFirstMediaUrl('images') }}" alt="{{ $receipt->title }}">
                        </div>
                        <span class="receipt_table_title">
                            {{ $receipt->title }}
                        </span>
                    </div>
                </td>
                <td style="max-width: 250px">
                    <div class="receipt_description">
                        {{ $receipt->description }}
                    </div>
                </td>
                <td>
                    <div class="table_actions">
                        <a href="{{ route('receipt.show', $receipt) }}" class="orange"  >
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <form action="{{ route('user.receipt.favorite.store', $receipt) }}">
                            <button type="submit" class="btn_icon red"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    {{ $receipts->links() }}
</div>

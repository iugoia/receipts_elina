<div>
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
                <th>Статус</th>
                <th>Дата создания</th>
                <th>Действия</th>
            </tr>
            </thead>
            @foreach($recipes as $receipt)
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
                        <div
                            class="receipt_status @if($receipt->status == \App\Enums\StatusEnum::NEW->value) blue @elseif($receipt->status == \App\Enums\StatusEnum::REJECTED->value) red @else green @endif">
                            {{ \App\Enums\StatusEnum::getTitle($receipt->status) }}
                        </div>
                    </td>
                    <td>{{ $receipt->created_at->locale('ru')->translatedFormat('M d, Y H:i:s') }}</td>
                    <td>
                        @if($receipt->status == \App\Enums\StatusEnum::NEW->value || $receipt->status == \App\Enums\StatusEnum::SUCCESS->value)
                            <a href="{{ route('user.receipts.edit', $receipt) }}" class="orange"><i
                                    class="fa-solid fa-pen"></i></a>
                        @elseif ($receipt->status == \App\Enums\StatusEnum::REJECTED->value)
                            @php
                                $chat = \App\Models\Chat::where('receipt_id', $receipt->id)->first();
                            @endphp

                            @if ($chat)
                                <a href="{{ route('chats.show', $chat) }}"   class="red">
                                    <i class="fa-solid fa-comment"></i>
                                </a>
                            @endif
                        @endif
                        @if($receipt->status == \App\Enums\StatusEnum::SUCCESS->value)
                            <a href="{{ route('receipt.show', $receipt) }}"   class="blue"><i
                                    class="fa-solid fa-eye"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $recipes->links() }}
    </div>
</div>

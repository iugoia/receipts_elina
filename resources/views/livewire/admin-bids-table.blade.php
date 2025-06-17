<div>
    <div class="table_search">
        <div class="search">
            @if(session()->has('success'))
                <p class="green">{{ session('success') }}</p>
            @endif
            <input type="text" wire:model="search" class="auth_form_control" placeholder="Поиск..">

            <div class="table_filters">
                <select wire:model="statusFilter" class="auth_form_control">
                    <option value="">Все статусы</option>
                    <option value="new">Новый</option>
                    <option value="success">Успешный</option>
                    <option value="rejected">Отклоненный</option>
                </select>
            </div>
        </div>
        <table class="data_table">
            <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
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
                    <td>
                        <div class="receipt_description">{{ $receipt->description }}</div>
                    </td>
                    <td>
                        <p class="@if($receipt->status == 'rejected') red @elseif($receipt->status == 'success') green @else blue @endif">
                            {{ \App\Enums\StatusEnum::getTitle($receipt->status) }}
                        </p>
                    </td>
                    <td>{{ $receipt->created_at->locale('ru')->translatedFormat('M d, Y H:i:s') }}</td>
                    <td>
                        <div class="table_actions">
                            <a href="{{ route('admin.receipts.show', $receipt) }}" class="blue">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($receipt->chat?->last_message)
                                <a href="{{ route('chats.show', $receipt->chat) }}" class="red">
                                    <i class="fa-solid fa-comment"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $recipes->links() }}
    </div>
</div>

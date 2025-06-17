<div>
    <div class="table_search">
        <div class="search">
            <div class="table_filters">
                <select wire:model="statusFilter" class="auth_form_control">
                    <option value="">Все статусы</option>
                    <option value="new">Новые</option>
                    <option value="answered">Завершенные</option>
                </select>
            </div>

            @if(session()->has('success'))
                <p class="green">{{ session('success') }}</p>
            @endif
        </div>
        <table class="data_table">
            <thead>
            <tr>
                <th>Имя</th>
                <th>Номер телефона</th>
                <th>Дата создания</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            </thead>
            @foreach($questions as $question)
                <tr>
                    <td>
                        {{ $question->name }}
                    </td>
                    <td>
                        {{ $question->phone }}
                    </td>
                    <td>
                        {{ $question->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td>
                        {{ $question->getStatusName() }}
                    </td>
                    <td>
                        @if($question->status === 'new')
                            <form action="{{ route('admin.question.update', $question) }}" method="post">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn_icon green">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $questions->links() }}
    </div>
</div>

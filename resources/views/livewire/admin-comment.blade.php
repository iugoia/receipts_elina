<div>
    <div class="table_search admin_comments">
        <div class="search">
            <div class="table_filters">
                <select wire:model="statusFilter" class="auth_form_control">
                    <option value="">Все статусы</option>
                    <option value="new">Новые</option>
                    <option value="success">Одобренные</option>
                    <option value="rejected">Отклоненные</option>
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
                <th>Текст отзыва</th>
                <th>Дата создания</th>
                <th>Статус</th>
                <th>Просмотр/Удаление</th>
            </tr>
            </thead>
            @foreach($comments as $comment)
                <tr>
                    <td>
                        {{ $comment->name }}
                    </td>
                    <td class="td_table_comment_text">
                        <div class="table_comment_text">{{ $comment->text }}</div>
                    </td>
                    <td>
                        {{ $comment->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td>
                        {{ \App\Enums\StatusEnum::getTitle($comment->status) }}
                    </td>
                    <td>
                        <div class="table_actions">
                            <a href="{{ route('receipt.show', $comment->receipt) }}#comment-{{ $comment->id }}"
                                 class="blue">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($comment->status === 'new')
                                <form action="{{ route('admin.comment.update', $comment) }}" class="table_actions"
                                      method="post">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn_icon green" name="status" value="success">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="submit" class="btn_icon red" name="status" value="rejected">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $comments->links() }}
    </div>
</div>

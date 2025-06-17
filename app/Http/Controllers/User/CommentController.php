<?php

namespace App\Http\Controllers\User;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentStoreRequest;
use App\Models\Comment;
use App\Models\Receipt;

class CommentController extends Controller
{
    public function index()
    {
        $comments = user()->comments()->with('receipt')->get();
        return view('user.comments.index', compact('comments'));
    }

    public function store(CommentStoreRequest $request, Receipt $receipt)
    {
        user()->comments()->create([
            'receipt_id' => $receipt->id,
            'text'       => $request->text,
            'name'       => $request->name,
            'status'     => StatusEnum::NEW->value
        ]);

        return redirect()->route('receipt.show', $receipt->id)
            ->with('success', 'Ваш отзыв был успешно отправлен!')
            ->with('scrollToComment', true);
    }

    public function edit(Comment $comment)
    {
        return view('user.comments.edit', compact('comment'));
    }

    public function update(CommentStoreRequest $request, Comment $comment)
    {
        $comment->update([
            'text'   => $request->text,
            'name'   => $request->name,
            'status' => StatusEnum::NEW->value
        ]);
        return redirect()->route('user.comments.index')
            ->with('success', 'Отзыв успешно обновлен');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('user.comments.index')
            ->with('success', 'Отзыв успешно удален');
    }
}

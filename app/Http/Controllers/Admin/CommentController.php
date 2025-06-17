<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return view('admin.comments.index');
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'status' => ['required', 'string']
        ]);

        if ($request->status === StatusEnum::REJECTED->value) {
            $comment->delete();
            return redirect()->back()->with('success', 'Комментарий удален');
        } else {
            $comment->status = $request->status;
            $comment->save();
        }

        return redirect()->back()->with('success', 'Комментарий обновлен');
    }
}

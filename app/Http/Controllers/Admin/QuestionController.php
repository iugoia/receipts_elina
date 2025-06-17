<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;

class QuestionController extends Controller
{
    public function index()
    {
        return view('admin.questions.index');
    }

    public function update(Question $question)
    {
        $question->update([
            'status' => Question::ANSWERED
        ]);
        return redirect()->back()->with('success', 'Вопрос отвечен');
    }
}

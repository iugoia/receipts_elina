<?php

namespace App\Http\Controllers\Commons;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionStoreRequest;
use App\Models\Question;

class QuestionController extends Controller
{
    public function store(QuestionStoreRequest $request)
    {
        Question::create($request->validated());
        return redirect('/#form')->with('success', 'Ваш вопрос отправлен');
    }
}

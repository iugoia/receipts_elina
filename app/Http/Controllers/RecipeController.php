<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Models\Receipt;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('period_id')) {
            return response()->json(
                Receipt::where('period_id', $request->period_id)
                    ->where('status', StatusEnum::SUCCESS->value)
                    ->get()
            );
        }
        return response()->json();
    }

    public function show(Receipt $receipt)
    {
        $favoritesCount = $receipt->favorites()->count();
        $isFavorite = isLogged() && $receipt->favorites()->where('user_id', auth()->id())->exists();
        $comments = $receipt->comments()->whereSuccess()->get();
        $isUserHasComment = isLogged() && $receipt->comments()->where('user_id', auth()->id())->exists();
        $userComment = isLogged() ? $receipt->comments()->where('user_id', auth()->id())->first() : null;
        $receipt = Receipt::with('user')->findOrFail($receipt->id);
        return view('receipt', compact('receipt', 'favoritesCount', 'isFavorite', 'comments', 'isUserHasComment', 'userComment'));
    }
}

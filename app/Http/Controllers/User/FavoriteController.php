<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Receipt;

class FavoriteController extends Controller
{
    public function index()
    {
        $receipts = Receipt::whereIn('id', user()->favorites()->pluck('receipt_id'))->get();
        return view('user.receipts.favorite', compact('receipts'));
    }

    public function store(Receipt $receipt)
    {
        if (user()->favorites()->where('receipt_id', $receipt->id)->exists()) {
            user()->favorites()->detach($receipt->id);
        } else {
            user()->favorites()->attach($receipt->id);
        }
        return redirect()->back();
    }
}

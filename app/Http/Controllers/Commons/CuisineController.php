<?php

namespace App\Http\Controllers\Commons;

use App\Http\Controllers\Controller;
use App\Models\Cuisine;

class CuisineController extends Controller
{
    public function show(Cuisine $cuisine)
    {
        $popularReceipts = $cuisine->receipts()->whereApproved()->inRandomOrder()->limit(3)->get();
        return view('cuisine', compact('cuisine', 'popularReceipts'));
    }
}

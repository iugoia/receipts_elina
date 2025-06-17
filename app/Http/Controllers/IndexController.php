<?php

namespace App\Http\Controllers;

use App\Models\Cuisine;
use App\Models\Period;
use App\Models\Receipt;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $cuisines = Cuisine::all();
        $popularReceipts = Receipt::query()
            ->whereApproved()
            ->withCount('favorites')
            ->orderByDesc('favorites_count')
            ->take(6)
            ->get();
        $randomReceipts = Receipt::query()
            ->whereApproved()
            ->inRandomOrder()
            ->take(6)
            ->get();
        $receiptsCount = Receipt::query()
            ->whereApproved()
            ->count();
        $cuisinesCount = Cuisine::count();
        $periods = Period::all();
        return view('index', compact('cuisines', 'popularReceipts', 'randomReceipts', 'receiptsCount', 'cuisinesCount', 'periods'));
    }
}

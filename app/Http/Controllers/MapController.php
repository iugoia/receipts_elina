<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $periods = Period::with('receipts')->get();
        return view('map', compact('periods'));
    }
}

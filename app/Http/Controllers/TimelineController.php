<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        return view('map');
    }

    public function periods(Request $request)
    {
        if ($request->has('period_id')) {
            return response()->json(Period::findOrFail($request->period_id));
        }
        $periods = Period::all();
        return response()->json($periods);
    }
}

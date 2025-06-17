<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUpdloadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('upload')) {
            $image = $request->file('upload');
            $path = $image->store('uploads', 'public');

            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'Ошибка загрузки файла'], 422);
    }
}

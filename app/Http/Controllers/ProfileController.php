<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $receipts = $user->receipts()->whereApproved()->paginate(6);

        return view('profile.show', compact('user', 'receipts'));
    }
}

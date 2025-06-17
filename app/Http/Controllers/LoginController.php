<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function __invoke(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'), true)) {
            return redirect('/');
        }
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Неправильный email или пароль']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}

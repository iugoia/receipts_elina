<?php

function user(): \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null
{
    return \Illuminate\Support\Facades\Auth::user();
}

function isLogged(): bool
{
    return auth()->check();
}

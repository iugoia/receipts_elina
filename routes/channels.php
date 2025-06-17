<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return $user->canAccessChat($chatId);
});

Broadcast::channel('messages', function () {
    return isLogged();
});

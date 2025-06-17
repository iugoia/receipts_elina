@extends('layouts.auth')

@section('title')
    Чат с администратором
@endsection

@php
    $partner = $chat->getPartnerAttribute();
@endphp

@section('content')
    <section class="auth_main">
        <div class="container">
            <div class="chat-container">
                <div class="chat-partner">
                    <div class="avatar chat_avatar">
                        <div id="avatar">
                            {{ mb_substr($partner->name, 0, 1, 'UTF-8') }}
                        </div>
                        <div class="partner-info">
                            <p>{{ $partner->name }}</p>
                            @if ($partner->isOnline())
                                <span class="green text-small">● Онлайн</span>
                            @else
                                @php
                                    $lastActivity = $partner->last_activity_at;
                                    $diff = $lastActivity ? now()->diff($lastActivity) : null;

                                    if (!$lastActivity) {
                                        $lastSeenText = "Нет данных";
                                    } elseif ($diff->i < 5 && $diff->h === 0) {
                                        $lastSeenText = "Был(а) только что";
                                    } elseif ($diff->h < 1) {
                                        $lastSeenText = "Был(а) $diff->i мин. назад";
                                    } elseif ($diff->h < 24) {
                                        $lastSeenText = "Был(а) $diff->h ч. назад";
                                    } elseif ($diff->d == 1) {
                                        $lastSeenText = "Был(а) вчера в " . $lastActivity->format('H:i');
                                    } else {
                                        $lastSeenText = "Был(а) " . $lastActivity->format('d.m в H:i');
                                    }
                                @endphp

                                <span class="text-small gray">{{ $lastSeenText }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="chat-actions">

                    </div>
                </div>
                <div id="chatBox" class="chat-box">

                </div>

                <div class="chat-input">
                    <input type="text" id="messageInput" placeholder="Введите сообщение..." autofocus>
                    <button id="sendMessageBtn">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let chatBox = document.getElementById("chatBox");
            let chatId = {{ $chat->id }};
            let lastMessageId = null;
            let loading = false;

            function loadMessages(beforeId = null) {
                if (loading) return;
                loading = true;

                let url = `/chats/${chatId}/messages`;
                if (beforeId) {
                    url += `?before=${beforeId}`;
                }

                axios.get(url)
                    .then(response => {
                        let messages = response.data.messages;
                        lastMessageId = response.data.last_message_id;

                        let firstMessage = chatBox.firstElementChild;

                        messages.forEach(message => {
                            let messageDiv = document.createElement("div");
                            messageDiv.classList.add("chat-message");
                            messageDiv.classList.add(message.user_id === {{ auth()->id() }} ? "sent" : "received");
                            messageDiv.id = `message-${message.id}`;

                            const readIcon = message.read_at
                                ? '<div class="double_check"><i class="fa-solid fa-check text-muted"></i><i class="fa-solid fa-check text-muted"></i></div>'
                                : '<i class="fa-solid fa-check text-muted"></i>';

                            messageDiv.innerHTML = `
                            <div class="message-content">
                                <p>${message.message}</p>
                            </div>
                            <div class="message-info">
                                <span class="timestamp">${formatTime(message.created_at)}</span>
                                ${readIcon}
                            </div>
                        `;

                            chatBox.prepend(messageDiv);
                        });

                        if (beforeId) {
                            firstMessage?.scrollIntoView({behavior: "instant"});
                        } else {
                            chatBox.scrollTop = chatBox.scrollHeight;
                        }

                        loading = false;
                    })
                    .catch(error => {
                        console.error("Ошибка при загрузке сообщений:", error);
                        loading = false;
                    });
            }

            function markMessageAsRead() {
                axios.post(`/chats/${chatId}/read`, {}, {
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                }).then(response => {

                }).catch(error => {
                    console.error('Error marking messages as read', error);
                })
            }

            chatBox.addEventListener("scroll", function () {
                if (chatBox.scrollTop === 0 && lastMessageId) {
                    loadMessages(lastMessageId);
                }
            });

            loadMessages();

            chatBox.addEventListener('scroll', function () {
                if (chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight) {
                    markMessageAsRead();
                }
            })
            markMessageAsRead();
        });

        function formatTime(timestamp) {
            let date = new Date(timestamp);
            let now = new Date();

            let options = {day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit'};

            if (date.toDateString() === now.toDateString()) {
                return date.getHours() + ":" + String(date.getMinutes()).padStart(2, '0');
            } else if (new Date(now.setDate(now.getDate() - 1)).toDateString() === date.toDateString()) {
                return "Вчера в " + date.getHours() + ":" + String(date.getMinutes()).padStart(2, '0');
            } else {
                return date.toLocaleDateString('ru-RU', options).replace(',', ' в');
            }
        }

        const chatId = {{ $chat->id }};
        const chatBox = document.getElementById('chatBox');
        const userId = {{ user()->id }};
        const messageInput = document.getElementById('messageInput');
        let audioPlayed = false;

        chatBox.scrollTop = chatBox.scrollHeight;

        echo.private(`chat.${chatId}`)
            .listen('.message.read', (event) => {
                const messageElement = document.querySelector(`#message-${event.message_id}`);

                if (messageElement) {
                    const readIcon = '<div class="double_check"><i class="fa-solid fa-check text-primary"></i><i class="fa-solid fa-check text-primary"></i></div>';

                    messageElement.querySelector('.message-info').innerHTML = `
                <span class="timestamp">${formatTime(event.read_at)}</span>
                ${readIcon}
            `;
                }
            });

        echo.private(`chat.${chatId}`)
            .listen('.message.sent', (event) => {
                const message = document.createElement('div');
                message.classList.add('chat-message', event.message.user_id === userId ? 'sent' : 'received');
                message.id = `message-${event.message.id}`;

                let formattedTime;
                const messageTime = new Date(event.message.created_at);
                const now = new Date();
                const isToday = messageTime.toDateString() === now.toDateString();
                const isYesterday = (now - messageTime) / (1000 * 60 * 60 * 24) === 1;

                if (isToday) {
                    formattedTime = messageTime.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
                } else if (isYesterday) {
                    formattedTime = `Вчера в ${messageTime.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    })}`;
                } else {
                    formattedTime = `${messageTime.getDate()}.${messageTime.getMonth() + 1} в ${messageTime.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    })}`;
                }

                const readIcon = event.message.read_at
                    ? '<i class="fa-solid fa-check-double text-primary"></i>'
                    : '<i class="fa-solid fa-check text-muted"></i>';

                message.innerHTML = `
            <div class="message-content">
                <p>${event.message.message}</p>
            </div>
            <div class="message-info">
                <span class="timestamp">${formattedTime}</span>
                ${readIcon}
            </div>
        `;

                chatBox.appendChild(message);

                chatBox.scrollTop = chatBox.scrollHeight;

                if (event.message.user_id !== userId) {
                    playNotification();
                }
            })
            .error((error) => {
                console.error('Ошибка при подписке на канал:', error);
            });

        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value;

            fetch(`/chats/${chatId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({message})
            })
                .then(response => response.json())
                .then(data => {
                    messageInput.value = '';
                });
        }

        messageInput.addEventListener('keyup', function (e) {
            if (e.keyCode === 13)
                sendMessage();
        })

        document.getElementById('sendMessageBtn').addEventListener('click', () => {
            sendMessage();
        });
    </script>
@endsection

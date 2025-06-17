const websocketsKey = window.env.PUSHER_APP_KEY;
const websocketsPort = window.env.PUSHER_PORT;

const echo = new Echo({
    broadcaster: 'pusher',
    key: websocketsKey,
    cluster: 'mt1',
    wsHost: window.location.hostname,
    wsPort: websocketsPort,
    forceTLS: false,
    disableStats: true,
});

window.Echo = echo;

echo.private('messages')
    .listen('.message.sent', (event) => {

        if (!window.location.pathname.includes(`/chats/${event.chat_id}`)) {
            showMiniChat(event);
            playNotification();
        }
    })

function playNotification() {
    const audio = new Audio('/sounds/notification.mp3');
    audio.volume = 0.1;
    audio.play().catch(error => console.error("Ошибка воспроизведения уведомления", error));
}

function showMiniChat(event) {
    const miniChatContainer = document.createElement('div');
    miniChatContainer.classList.add('mini-chat');
    miniChatContainer.innerHTML = `
        <div class="mini-chat-header">
            <h4>Новое сообщение</h4>
        </div>
        <div class="mini-chat-body">
            <p>${event.message.message}</p>
        </div>
        <div class="mini-chat-footer">
            <button onclick="openChatPage('${event.chat_id}')">Перейти в чат</button>
        </div>
    `;

    document.body.appendChild(miniChatContainer);

    const style = document.createElement('style');
    style.innerHTML = `
        .mini-chat {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            padding: 10px;
            border-radius: 8px;
            z-index: 9999;
        }
        .mini-chat-header {
            font-size: 16px;
            font-weight: bold;
        }
        .mini-chat-body {
            margin-top: 10px;
        }
        .mini-chat-footer {
            margin-top: 10px;
            text-align: center;
        }
        .mini-chat-footer button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .mini-chat-footer button:hover {
            background-color: #0056b3;
        }
    `;
    document.head.appendChild(style);
}

function openChatPage(chatId) {
    window.location.href = `/chats/${chatId}`;
}

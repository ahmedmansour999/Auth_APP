import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});
window.Echo.channel("user-registration").listen("Registerion", (data) => {
    console.log("New user registered:", data.user.name); // You can remove this line if no longer needed

    // Append new notification to the notification list
    const notificationList = document.getElementById("notification-list");
    const newNotification = document.createElement("a");
    newNotification.classList.add("list-group-item");

    newNotification.innerHTML = `
        <div class="media">
            <div class="media-left">
                <span class="badge bg-success">New</span>
            </div>
            <div class="media-body">
                <h6 class="media-heading">${data.user.name}</h6>
                <p>New user registered: ${data.user.email}</p>
            </div>
        </div>
    `;

    notificationList.prepend(newNotification);

    // Update the notification badge count
    const badge = document.querySelector(".badge.bg-danger");
    badge.textContent = parseInt(badge.textContent) + 1;
});
window.Echo.channel("chatApp").listen("Chat", (message) => {
    const ReciverMessage = document.getElementById("reciverMessage");
    const newMessage = document.createElement("div");
    newMessage.classList.add("chat-message-right", "pb-4");
    newMessage.innerHTML = `
        <div >
            <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1"
                alt="Other User's Avatar" width="40" height="40">
            <div class="text-muted small text-nowrap mt-2">${message.date}</div>
        </div>
        <div class="flex-shrink-1 bg-white rounded py-2 px-3 ml-3 ">
            <div class="font-weight-bold mb-1">${message.reciver.name}</div>
            ${message.message}
        </div>`;
    ReciverMessage.appendChild(newMessage);
});

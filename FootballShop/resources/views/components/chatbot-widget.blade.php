<!-- resources/views/components/chatbot-widget.blade.php -->

<div id="chatbot-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; font-family: sans-serif;">

    <!-- Hívó szöveg + gomb -->
    <div onclick="toggleChat()" style="display: flex; align-items: center; cursor: pointer;">
        <div style="margin-right: 10px; background: #dc3545; color: white; padding: 8px 12px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); font-weight: bold;">
            ⚽ Kérdésed van?<br>Kattints ide!
        </div>
        <button style="border-radius: 50%; width: 60px; height: 60px; background: #000; border: none; font-size: 26px; color: white;">
            🤖
        </button>
    </div>

    <!-- Chat panel -->
    <div id="chat-window"
         style="display: none; margin-top: 10px; width: 320px; height: 420px; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.25); padding: 10px; position: relative; border: 2px solid #dc3545;">

        <div style="font-weight: bold; font-size: 16px; color: #dc3545; border-bottom: 1px solid #ccc; padding-bottom: 4px;">FootballShop Asszisztens</div>

        <div id="chat-messages" style="height: 300px; overflow-y: auto; font-size: 14px; margin-top: 10px; padding-top: 10px;">
            <p><strong style="color: #000;">🤖:</strong> Szia! Kérdezz bátran focis termékeinkről, rendelésről vagy bármiről!</p>
        </div>

        <form onsubmit="sendMessage(event)" style="margin-top: 10px;">
            <input type="text" id="chat-input" placeholder="Írd be a kérdésed..."
                   style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc; box-sizing: border-box;">
        </form>
    </div>
</div>

<script>
    function toggleChat() {
        const chatWindow = document.getElementById('chat-window');
        chatWindow.style.display = chatWindow.style.display === 'none' ? 'block' : 'none';
    }

    async function sendMessage(event) {
        event.preventDefault();
        const input = document.getElementById('chat-input');
        const msg = input.value.trim();
        if (!msg) return;

        const chatBox = document.getElementById('chat-messages');
        chatBox.innerHTML += `<p><strong style="color: #000;">Te:</strong> ${msg}</p>`;
        input.value = '';

        const typing = document.createElement('p');
        typing.id = 'typing-indicator';
        typing.innerHTML = `<em style="color: #999;">🤖 gépel...</em>`;
        chatBox.appendChild(typing);
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            const response = await fetch('/api/ask-question', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ question: msg })
            });

            const data = await response.json();
            document.getElementById('typing-indicator')?.remove();
            chatBox.innerHTML += `<p><strong style="color: #dc3545;">🤖:</strong> ${data.answer}</p>`;
            chatBox.scrollTop = chatBox.scrollHeight;
        } catch (error) {
            document.getElementById('typing-indicator')?.remove();
            chatBox.innerHTML += `<p style="color:red;"><strong>🤖:</strong> Hiba történt. Kérlek próbáld újra.</p>`;
        }
    }
</script>

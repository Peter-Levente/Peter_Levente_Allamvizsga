<!-- Chatbot widget: az oldal jobb alsó sarkában lebeg -->
<div id="chatbot-widget"
     style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; font-family: sans-serif;">

    <!-- Kattintható doboz és ikon: megnyitja a chatpanelt -->
    <div onclick="toggleChat()"
         style="display: flex; align-items: center; cursor: pointer; transition: transform 0.3s ease;"
         onmouseover="this.style.transform='translate(-5px, -8px) rotate(-3deg) scale(1.1)'"
         onmouseout="this.style.transform='none'">

        <!-- Felirat: "Kérdésed van?" kis piros dobozban -->
        <div
            style="margin-right: 10px; background: #dc3545; color: white; padding: 8px 12px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); font-weight: bold;">
            ⚽ Kérdésed van?<br>Kattints ide!
        </div>

        <!-- Fehér 🤖 ikon fekete körben -->
        <button
            style="border-radius: 50%; width: 60px; height: 60px; background: #000; border: none; font-size: 26px; color: white;">
            🤖
        </button>
    </div>

    <!-- Chatpanel: alapból rejtve van, kattintásra nyílik ki -->
    <div id="chat-window"
         style="display: none; margin-top: 10px; width: 320px; height: 420px; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.25); padding: 10px; position: relative; border: 2px solid #dc3545;">

        <!-- Bezáró gomb a jobb felső sarokban -->
        <button onclick="toggleChat()"
                style="position: absolute; top: 8px; right: 10px; background: none; border: none; font-size: 18px; color: #dc3545; cursor: pointer;">
            ✖
        </button>

        <!-- Cím: Chatbot fejléce -->
        <div
            style="font-weight: bold; font-size: 16px; color: #dc3545; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
            FootballShop Asszisztens
        </div>

        <!-- Üzenetek listája: a beszélgetés itt jelenik meg -->
        <div id="chat-messages"
             style="height: 300px; overflow-y: auto; font-size: 14px; margin-top: 10px; padding-top: 10px;">
            <!-- Nyitó üzenet a chatbottól -->
            <p><strong style="color: #000;">🤖:</strong> Szia! Kérdezz bátran focis termékeinkről, rendelésről vagy
                bármiről!</p>
        </div>

        <!-- Üzenet beküldő űrlap -->
        <form onsubmit="sendMessage(event)" style="margin-top: 10px;">
            <input type="text" id="chat-input" placeholder="Írd be a kérdésed..."
                   style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc; box-sizing: border-box;">
        </form>
    </div>
</div>

<!-- JavaScript rész: interakciók -->
<script>
    // Megnyitja vagy elrejti a chat ablakot
    function toggleChat() {
        const chatWindow = document.getElementById('chat-window');
        chatWindow.style.display = chatWindow.style.display === 'none' ? 'block' : 'none';
    }

    // Üzenet elküldése a szervernek és válasz megjelenítése
    async function sendMessage(event) {
        event.preventDefault(); // Ne frissítse újra az oldalt

        const input = document.getElementById('chat-input'); // Üzenet mező
        const msg = input.value.trim(); // Levágja a szóközöket
        if (!msg) return; // Ne küldjön üres üzenetet

        const chatBox = document.getElementById('chat-messages'); // Üzenetek doboza

        // Felhasználó üzenetének megjelenítése
        chatBox.innerHTML += `<p><strong style="color: #000;">Te:</strong> ${msg}</p>`;
        input.value = ''; // Üzenet mező törlése

        // "Gépel..." üzenet megjelenítése
        const typing = document.createElement('p');
        typing.id = 'typing-indicator';
        typing.innerHTML = `<em style="color: #999;">🤖 gépel...</em>`;
        chatBox.appendChild(typing);
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            // POST kérés az API-nak
            const response = await fetch('/api/ask-question', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
                },
                body: JSON.stringify({question: msg}) // A kérdés szövege
            });

            const data = await response.json(); // Válasz JSON dekódolása

            // "gépel..." eltávolítása és válasz megjelenítése
            document.getElementById('typing-indicator')?.remove();
            chatBox.innerHTML += `<p><strong style="color: #dc3545;">🤖:</strong> ${data.answer}</p>`;
            chatBox.scrollTop = chatBox.scrollHeight;
        } catch (error) {
            // Hiba esetén üzenet kiírása
            document.getElementById('typing-indicator')?.remove();
            chatBox.innerHTML += `<p style="color:red;"><strong>🤖:</strong> Hiba történt a chatbottal. Kérlek próbáld újra.</p>`;
        }
    }
</script>

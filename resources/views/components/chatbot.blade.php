<div id="ain-chatbot-widget" style="position:fixed;bottom:28px;right:28px;z-index:9999;display:flex;flex-direction:column;align-items:flex-end;">

    <div id="ain-chatbot-window" style="position:absolute;bottom:68px;right:0;width:340px;max-height:500px;background:#fff;border-radius:14px;box-shadow:0 8px 32px rgba(0,0,0,0.18);display:none;flex-direction:column;overflow:hidden;">

        {{-- Header --}}
        <div style="background:var(--ain-primary);padding:14px 16px;display:flex;align-items:center;gap:10px;">
            <i class="bi bi-robot" style="color:#fff;font-size:1.3rem;"></i>
            <div style="flex:1;">
                <div style="color:#fff;font-weight:bold;font-size:13px;">Ain Sheba Legal Assistant</div>
                <div style="color:rgba(255,255,255,0.75);font-size:11px;">Ask about your rights in Bangladesh</div>
            </div>
            <button type="button" class="btn-close btn-close-white btn-sm" aria-label="Close" onclick="ainChatClose()"></button>
        </div>

        {{-- Messages --}}
        <div id="ain-chatbot-messages" style="flex:1;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:10px;min-height:280px;">
            <div class="ain-bubble ain-bubble-bot">👋 Assalamu Alaikum! I am the Ain Sheba Legal Assistant. Ask me anything about your legal rights in Bangladesh — labor law, tenant rights, consumer rights, women's rights, and more. I'm here to help!</div>
        </div>

        {{-- Input --}}
        <div style="padding:10px 12px;border-top:1px solid #e5e7eb;background:#fafafa;">
            <div style="display:flex;gap:8px;align-items:center;">
                <input type="text" id="ain-chatbot-input" class="form-control form-control-sm"
                       placeholder="Ask about your rights..." maxlength="500"
                       onkeydown="if (event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); ainChatSend(); }">
                <button type="button" class="btn btn-sm" style="background:var(--ain-primary);color:#fff;" onclick="ainChatSend()">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
            <small class="text-muted" id="ain-chat-char-count">0 / 500</small>
        </div>
    </div>

    {{-- Toggle button --}}
    <button id="ain-chatbot-toggle" onclick="ainChatToggle()" style="position:relative;width:56px;height:56px;border-radius:50%;background:var(--ain-primary);border:none;box-shadow:0 4px 16px rgba(0,0,0,0.22);transition:transform 0.2s;">
        <i class="bi bi-chat-dots-fill" style="color:#fff;font-size:1.4rem;"></i>
        <span id="ain-chat-notif-dot" style="display:none;position:absolute;top:0;right:0;width:12px;height:12px;background:#ef4444;border-radius:50%;"></span>
    </button>

</div>

<style>
.ain-bubble {
    max-width: 85%;
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 13px;
    line-height: 1.6;
    word-break: break-word;
}
.ain-bubble-bot {
    background: #f0f4f8;
    color: #1a1a2e;
    align-self: flex-start;
    border-bottom-left-radius: 4px;
}
.ain-bubble-user {
    background: var(--ain-primary);
    color: white;
    align-self: flex-end;
    border-bottom-right-radius: 4px;
}
.ain-bubble-typing {
    background: #f0f4f8;
    color: #9ca3af;
    align-self: flex-start;
    font-style: italic;
    font-size: 12px;
    border-bottom-left-radius: 4px;
}
.ain-bubble-error {
    background: #fef2f2;
    color: #dc2626;
    align-self: flex-start;
    border: 1px solid #fecaca;
    border-bottom-left-radius: 4px;
}
#ain-chatbot-toggle:hover {
    transform: scale(1.08);
}
@keyframes ain-pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.7; }
}
.ain-pulse {
    animation: ain-pulse 1.2s infinite;
}
</style>

<script>
const ainChatUrl = "{{ route('citizen.chatbot.ask') }}";
const ainCsrf = document.querySelector('meta[name="csrf-token"]').content;
let ainChatIsOpen = false;
let ainChatHasOpened = false;
let ainChatLoading = false;

function ainChatToggle() {
    ainChatIsOpen = !ainChatIsOpen;
    const win = document.getElementById('ain-chatbot-window');
    const toggle = document.getElementById('ain-chatbot-toggle');

    if (ainChatIsOpen) {
        win.style.display = 'flex';
        document.getElementById('ain-chatbot-input').focus();
    } else {
        win.style.display = 'none';
    }

    toggle.classList.toggle('ain-chat-open');

    const dot = document.getElementById('ain-chat-notif-dot');
    dot.style.display = 'none';
    dot.classList.remove('ain-pulse');

    ainChatHasOpened = true;
}

function ainChatClose() {
    ainChatIsOpen = false;
    document.getElementById('ain-chatbot-window').style.display = 'none';
}

function ainChatAppend(text, type) {
    const bubble = document.createElement('div');
    bubble.className = 'ain-bubble ain-bubble-' + type;
    bubble.textContent = text;

    const messages = document.getElementById('ain-chatbot-messages');
    messages.appendChild(bubble);
    messages.scrollTop = messages.scrollHeight;

    return bubble;
}

function ainChatSend() {
    const input = document.getElementById('ain-chatbot-input');
    const message = input.value.trim();

    if (!message) return;
    if (message.length > 500) return;
    if (ainChatLoading) return;

    ainChatLoading = true;

    const sendBtn = document.querySelector('#ain-chatbot-widget button[onclick="ainChatSend()"]');
    input.disabled = true;
    sendBtn.disabled = true;

    ainChatAppend(message, 'user');

    input.value = '';
    document.getElementById('ain-chat-char-count').textContent = '0 / 500';

    const typingBubble = ainChatAppend('Thinking...', 'typing');

    fetch(ainChatUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': ainCsrf,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ message: message })
    })
        .then(res => res.json())
        .then(data => {
            typingBubble.remove();
            if (data.reply) {
                ainChatAppend(data.reply, 'bot');
            } else {
                ainChatAppend('Sorry, I did not get a valid response. Please try again.', 'error');
            }
        })
        .catch(() => {
            typingBubble.remove();
            ainChatAppend('Network error. Please check your connection and try again.', 'error');
        })
        .finally(() => {
            ainChatLoading = false;
            input.disabled = false;
            sendBtn.disabled = false;
        });
}

document.getElementById('ain-chatbot-input').addEventListener('input', function () {
    document.getElementById('ain-chat-char-count').textContent = this.value.length + ' / 500';
});

setTimeout(() => {
    if (!ainChatHasOpened) {
        const dot = document.getElementById('ain-chat-notif-dot');
        dot.style.display = 'block';
        dot.classList.add('ain-pulse');
    }
}, 3000);
</script>

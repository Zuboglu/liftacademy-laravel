{{-- ── PREMIUM CHAT WIDGET ───────────────────────────────────────────────── --}}

<style>
#chat-widget * { box-sizing: border-box; }

/* Glassmorphism panel */
#chat-box {
  background: rgba(10, 10, 20, 0.92);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border: 1px solid rgba(255, 210, 0, 0.18);
  box-shadow: 0 32px 80px rgba(0,0,0,0.55), 0 0 0 1px rgba(255,255,255,0.04);
}

/* Karşılama balonu */
@keyframes bubbleIn {
  0%   { opacity:0; transform: scale(0.7) translateY(10px); }
  70%  { opacity:1; transform: scale(1.04) translateY(-2px); }
  100% { opacity:1; transform: scale(1) translateY(0); }
}
@keyframes bubbleOut {
  from { opacity:1; transform: scale(1); }
  to   { opacity:0; transform: scale(0.8) translateY(8px); }
}
#chat-bubble {
  animation: bubbleIn 0.4s cubic-bezier(0.34,1.56,0.64,1) 1.5s both;
}
#chat-bubble.hiding {
  animation: bubbleOut 0.25s ease forwards;
}

/* Slide-up aç animasyonu */
@keyframes chatSlideUp {
  from { opacity:0; transform: translateY(20px) scale(0.97); }
  to   { opacity:1; transform: translateY(0)   scale(1);    }
}
#chat-box.chat-open {
  animation: chatSlideUp 0.28s cubic-bezier(0.34,1.56,0.64,1) forwards;
}

/* Toggle buton pulse */
@keyframes pulseRing {
  0%   { box-shadow: 0 0 0 0   rgba(255,210,0,0.5); }
  70%  { box-shadow: 0 0 0 14px rgba(255,210,0,0);  }
  100% { box-shadow: 0 0 0 0   rgba(255,210,0,0);   }
}
#chat-toggle { animation: pulseRing 2.5s ease-out infinite; }
#chat-toggle:hover { animation: none; transform: scale(1.08); }

/* Scrollbar */
#chat-messages::-webkit-scrollbar { width: 4px; }
#chat-messages::-webkit-scrollbar-track { background: transparent; }
#chat-messages::-webkit-scrollbar-thumb { background: rgba(255,210,0,0.25); border-radius:2px; }

/* Yazıyor balonları */
@keyframes typingBounce {
  0%,60%,100% { transform:translateY(0); }
  30%          { transform:translateY(-5px); }
}
.typing-dot { animation: typingBounce 1.2s ease-in-out infinite; }
.typing-dot:nth-child(2) { animation-delay:.15s; }
.typing-dot:nth-child(3) { animation-delay:.30s; }

/* Mesaj fade-in */
@keyframes msgIn {
  from { opacity:0; transform:translateY(8px); }
  to   { opacity:1; transform:translateY(0); }
}
.chat-msg-row { animation: msgIn 0.22s ease forwards; }

/* Quick reply butonları */
.quick-btn {
  background: rgba(255,210,0,0.08);
  border: 1px solid rgba(255,210,0,0.25);
  color: #FFD200;
  font-size: 11px;
  padding: 5px 12px;
  border-radius: 20px;
  cursor: pointer;
  transition: all .18s;
  white-space: nowrap;
}
.quick-btn:hover {
  background: rgba(255,210,0,0.18);
  border-color: rgba(255,210,0,0.6);
}
</style>

<div id="chat-widget" style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;align-items:flex-end;gap:12px;">

  {{-- ── KARŞILAMA BALONU ── --}}
  <div id="chat-bubble" style="display:flex;align-items:center;gap:10px;background:rgba(10,10,20,0.92);backdrop-filter:blur(16px);border:1px solid rgba(255,210,0,0.25);border-radius:20px 20px 4px 20px;padding:11px 16px;box-shadow:0 8px 32px rgba(0,0,0,0.4);max-width:220px;cursor:pointer;position:relative;" onclick="document.getElementById('chat-toggle').click()">
    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#FFD200,#FF9500);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 3px 10px rgba(255,210,0,0.4);">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="2.3" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
    </div>
    <div>
      <p style="font-size:12px;color:#f0f0f0;margin:0;line-height:1.5;font-weight:500;">Size nasıl yardımcı olabilirim? 👋</p>
      <p style="font-size:10px;color:#FFD200;margin:2px 0 0;opacity:0.8;">Hemen yanıt veriyorum</p>
    </div>
    <button id="bubble-close" style="position:absolute;top:-6px;right:-6px;width:18px;height:18px;border-radius:50%;background:#1a1a1a;border:1px solid rgba(255,255,255,0.1);color:#666;font-size:9px;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;" onclick="event.stopPropagation();closeBubble()">✕</button>
  </div>

  {{-- ── CHAT PENCERE ── --}}
  <div id="chat-box" style="display:none;flex-direction:column;width:800px;height:760px;border-radius:20px;overflow:hidden;">

    {{-- Header --}}
    <div style="padding:20px 22px;background:linear-gradient(135deg,#1a1400 0%,#0d0d0d 100%);border-bottom:1px solid rgba(255,210,0,0.15);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
      <div style="display:flex;align-items:center;gap:14px;">
        <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#FFD200,#FF9500);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(255,210,0,0.4);">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
          </svg>
        </div>
        <div>
          <p style="font-weight:700;font-size:16px;color:#fff;margin:0;letter-spacing:0.3px;">LiftAcademy AI</p>
          <div style="display:flex;align-items:center;gap:5px;margin-top:3px;">
            <span style="width:8px;height:8px;border-radius:50%;background:#4ade80;display:inline-block;box-shadow:0 0 6px #4ade80;"></span>
            <span style="font-size:12px;color:#888;font-family:monospace;letter-spacing:0.5px;">Çevrimiçi · Hemen yanıt veriyor</span>
          </div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:6px;">
        <button id="chat-clear" title="Temizle" style="width:30px;height:30px;border-radius:50%;background:rgba(255,255,255,0.05);border:none;color:#555;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .18s;" onmouseover="this.style.background='rgba(255,50,50,0.12)';this.style.color='#ff5555'" onmouseout="this.style.background='rgba(255,255,255,0.05)';this.style.color='#555'">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
        </button>
        <button id="chat-close" style="width:30px;height:30px;border-radius:50%;background:rgba(255,255,255,0.05);border:none;color:#555;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .18s;" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#aaa'" onmouseout="this.style.background='rgba(255,255,255,0.05)';this.style.color='#555'">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
    </div>

    {{-- Mesajlar --}}
    <div id="chat-messages" style="flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:14px;">

      {{-- Karşılama --}}
      <div class="chat-msg-row" style="display:flex;gap:10px;align-items:flex-end;">
        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#FFD200,#FF9500);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="2.5" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div style="max-width:400px;">
          <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);border-radius:18px 18px 18px 4px;padding:13px 18px;font-size:14px;color:#e0e0e0;line-height:1.6;">
            Merhaba! 👋 Ben LiftAcademy AI asistanıyım.<br>Kurslar, sınavlar, sertifikalar veya vinç operatörü eğitimi hakkında yardımcı olabilirim.
          </div>
          <p style="font-size:11px;color:#444;margin:5px 0 0 5px;">Az önce</p>
        </div>
      </div>

      {{-- Hızlı cevap butonları --}}
      <div id="quick-replies" style="display:flex;flex-wrap:wrap;gap:8px;padding-left:46px;">
        <button class="quick-btn" onclick="quickSend(this,'Kurslar hakkında bilgi ver')">📚 Kurslar</button>
        <button class="quick-btn" onclick="quickSend(this,'Sertifikasyon süreci nasıl?')">🏆 Sertifika</button>
        <button class="quick-btn" onclick="quickSend(this,'Sınavlar nasıl çalışır?')">📝 Sınavlar</button>
        <button class="quick-btn" onclick="quickSend(this,'Nasıl kayıt olabilirim?')">✨ Kayıt</button>
      </div>

    </div>

    {{-- Yazıyor göstergesi --}}
    <div id="chat-typing" style="display:none;padding:0 16px 8px;flex-shrink:0;">
      <div style="display:flex;gap:8px;align-items:flex-end;">
        <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#FFD200,#FF9500);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="2.5" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);border-radius:18px 18px 18px 4px;padding:10px 16px;display:flex;gap:5px;align-items:center;">
          <span class="typing-dot" style="width:6px;height:6px;border-radius:50%;background:#FFD200;display:inline-block;"></span>
          <span class="typing-dot" style="width:6px;height:6px;border-radius:50%;background:#FFD200;display:inline-block;"></span>
          <span class="typing-dot" style="width:6px;height:6px;border-radius:50%;background:#FFD200;display:inline-block;"></span>
        </div>
      </div>
    </div>

    {{-- Input --}}
    <div style="padding:14px 18px;background:rgba(0,0,0,0.4);border-top:1px solid rgba(255,255,255,0.06);flex-shrink:0;">
      <div style="display:flex;gap:10px;align-items:center;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:50px;padding:8px 8px 8px 20px;transition:border-color .2s;" id="chat-input-wrap">
        <input id="chat-input" type="text" placeholder="Bir şey sorun..." maxlength="500"
          style="flex:1;background:transparent;border:none;outline:none;color:#f0f0f0;font-size:15px;font-family:inherit;"
          onfocus="document.getElementById('chat-input-wrap').style.borderColor='rgba(255,210,0,0.5)'"
          onblur="document.getElementById('chat-input-wrap').style.borderColor='rgba(255,255,255,0.1)'">
        <button id="chat-send"
          style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#FFD200,#FF9500);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .18s;box-shadow:0 4px 12px rgba(255,210,0,0.35);"
          onmouseover="this.style.transform='scale(1.1)';this.style.boxShadow='0 6px 18px rgba(255,210,0,0.5)'"
          onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 4px 12px rgba(255,210,0,0.35)'">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
          </svg>
        </button>
      </div>
      <p style="text-align:center;font-size:11px;color:#333;margin:8px 0 0;letter-spacing:0.5px;">Powered by DeepSeek AI · LiftAcademy</p>
    </div>

  </div>

  {{-- ── TOGGLE BUTON ── --}}
  <button id="chat-toggle"
    style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#FFD200,#FF9500);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 28px rgba(255,210,0,0.45);transition:transform .2s,box-shadow .2s;position:relative;"
    title="AI Asistan ile konuş">
    <svg id="chat-toggle-icon" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    {{-- Bildirim noktası --}}
    <span id="chat-dot" style="position:absolute;top:-2px;right:-2px;width:16px;height:16px;border-radius:50%;background:#FF2D2D;border:2.5px solid #F5F0E8;display:block;"></span>
  </button>

</div>

<script>
(function () {
  const box      = document.getElementById('chat-box');
  const toggle   = document.getElementById('chat-toggle');
  const closeBtn = document.getElementById('chat-close');
  const clearBtn = document.getElementById('chat-clear');
  const input    = document.getElementById('chat-input');
  const sendBtn  = document.getElementById('chat-send');
  const messages = document.getElementById('chat-messages');
  const typing   = document.getElementById('chat-typing');
  const dot      = document.getElementById('chat-dot');
  const quickReplies = document.getElementById('quick-replies');

  let history = [];
  let isOpen  = false;
  let loading = false;

  const CLOSE_ICON = `<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`;
  const CHAT_ICON  = `<svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>`;

  // Balon kapat
  window.closeBubble = function() {
    const bubble = document.getElementById('chat-bubble');
    if (!bubble) return;
    bubble.classList.add('hiding');
    setTimeout(() => bubble.style.display = 'none', 250);
  };

  // 8 saniye sonra otomatik kapat
  setTimeout(() => {
    const bubble = document.getElementById('chat-bubble');
    if (bubble && bubble.style.display !== 'none' && !isOpen) window.closeBubble();
  }, 8000);

  function openChat() {
    box.style.display = 'flex';
    box.classList.add('chat-open');
    isOpen = true;
    dot.style.display = 'none';
    toggle.innerHTML = CLOSE_ICON;
    window.closeBubble();
    setTimeout(() => input.focus(), 100);
    scrollBottom();
  }

  function closeChat() {
    box.style.display = 'none';
    box.classList.remove('chat-open');
    isOpen = false;
    toggle.innerHTML = CHAT_ICON;
  }

  toggle.addEventListener('click', () => isOpen ? closeChat() : openChat());
  closeBtn.addEventListener('click', closeChat);

  clearBtn.addEventListener('click', () => {
    history = [];
    messages.innerHTML = '';
    appendMsg('assistant', 'Sohbet temizlendi. Size nasıl yardımcı olabilirim?');
    if (quickReplies) messages.appendChild(quickReplies);
  });

  function appendMsg(role, text) {
    const isAi = role === 'assistant';
    const row = document.createElement('div');
    row.className = 'chat-msg-row';
    row.style.cssText = `display:flex;gap:8px;align-items:flex-end;${isAi ? '' : 'flex-direction:row-reverse;'}`;

    const avatar = isAi ? `
      <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#FFD200,#FF9500);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#0A0A0A" stroke-width="2.5" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </div>` : `
      <div style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:17px;">👤</div>`;

    const bubble = isAi
      ? `<div style="max-width:440px;"><div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);border-radius:18px 18px 18px 4px;padding:13px 18px;font-size:14px;color:#e0e0e0;line-height:1.65;">${escHtml(text)}</div></div>`
      : `<div style="max-width:440px;"><div style="background:linear-gradient(135deg,rgba(255,210,0,0.18),rgba(255,149,0,0.12));border:1px solid rgba(255,210,0,0.22);border-radius:18px 18px 4px 18px;padding:13px 18px;font-size:14px;color:#f5f5f5;line-height:1.65;">${escHtml(text)}</div></div>`;

    row.innerHTML = avatar + bubble;
    messages.appendChild(row);
    scrollBottom();
  }

  function scrollBottom() {
    messages.scrollTop = messages.scrollHeight;
  }

  function escHtml(s) {
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
  }

  async function sendMessage(text) {
    text = (text || input.value).trim();
    if (!text || loading) return;
    input.value = '';
    loading = true;
    sendBtn.disabled = true;
    sendBtn.style.opacity = '0.5';

    // Quick reply butonlarını gizle
    if (quickReplies) quickReplies.style.display = 'none';

    appendMsg('user', text);
    history.push({ role: 'user', content: text });

    typing.style.display = 'block';
    scrollBottom();

    try {
      const res = await fetch('{{ route("chat.send") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ message: text, history: history.slice(-10) }),
      });

      const data = await res.json();
      const reply = data.reply || 'Bir hata oluştu.';

      history.push({ role: 'assistant', content: reply });
      typing.style.display = 'none';
      appendMsg('assistant', reply);

      if (!isOpen) {
        dot.style.display = 'block';
      }
    } catch (e) {
      typing.style.display = 'none';
      appendMsg('assistant', 'Bağlantı hatası. Lütfen tekrar deneyin.');
    }

    loading = false;
    sendBtn.disabled = false;
    sendBtn.style.opacity = '1';
    input.focus();
  }

  // Global quick send fonksiyonu
  window.quickSend = function(btn, text) {
    sendMessage(text);
  };

  sendBtn.addEventListener('click', () => sendMessage());
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
  });

})();
</script>

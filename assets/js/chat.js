// chat.js – Final Ultra Version 2.3.0 🚀
// Author: Aver / ChatGPT
// Status: Synced with chat.php, modern UI, new icons, full features

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('chatForm');
  const textarea = document.getElementById('chatInput');
  const chatMessages = document.getElementById('chat-messages');
  const chatList = document.getElementById('chat-list');
  const sendBtn = document.getElementById('sendBtn');
  const webSearchToggle = document.getElementById('webSearchToggle');
  const newChatBtn = document.getElementById('newChatBtn');

  let currentMessages = [];
  let activeChatId = null;
  let webSearchEnabled = false;

  // Utilities
  const fixTokenSpacing = (text) => text
    .replace(/\s+'\s+(s|m|re|ve|d|ll)\b/g, "'$1")
    .replace(/\b(can)\s+not\b/g, "can't")
    .replace(/\b(would|could|should)\s+not\b/g, "$1n't")
    .replace(/\b(\w+)\s+-\s+(\w+)/g, "$1-$2");

  const formatMessage = (raw) => {
    const fixed = fixTokenSpacing(raw);
    const html = marked.parse(fixed);
    const wrapper = document.createElement('div');
    wrapper.innerHTML = html;
    wrapper.querySelectorAll('pre code').forEach(block => Prism.highlightElement(block));
    return wrapper.innerHTML;
  };

  const scrollToBottom = () => {
    chatMessages.scrollTop = chatMessages.scrollHeight;
  };

  const appendMessage = (text, sender = 'user') => {
    const div = document.createElement('div');
    div.className = `relative ${sender === 'user' ? 'self-end bg-blue-600' : 'self-start bg-gray-700'} text-white rounded-xl p-3 w-fit max-w-[80%] whitespace-pre-wrap break-words shadow-md`;
    div.innerHTML = sender === 'user' ? `<div>${text}</div>` : text;

    if (sender === 'bot') {
      const copyBtn = document.createElement('button');
      copyBtn.innerHTML = `<img src="/src/assets/icons/file-export.svg" class="w-4 h-4 inline mr-1" alt="Copy">Copy`;
      copyBtn.className = 'absolute bottom-2 right-3 text-xs text-blue-400 hover:text-white transition';
      copyBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(div.innerText);
        copyBtn.innerHTML = `✅ Copied`;
        setTimeout(() => copyBtn.innerHTML = `<img src="/src/assets/icons/file-export.svg" class="w-4 h-4 inline mr-1" alt="Copy">Copy`, 2000);
      });
      div.appendChild(copyBtn);
    }

    chatMessages.appendChild(div);
    scrollToBottom();
  };

  const appendTyping = () => {
    const div = document.createElement('div');
    div.className = 'self-start bg-gray-700 text-white rounded-xl p-4 w-fit max-w-[80%] shadow-md';
    div.innerHTML = `
      <div class="flex space-x-1 text-2xl text-gray-400 animate-pulse">
        <span class="animate-bounce">.</span>
        <span class="animate-bounce delay-150">.</span>
        <span class="animate-bounce delay-300">.</span>
      </div>`;
    chatMessages.appendChild(div);
    scrollToBottom();
    return div;
  };

  const showMemoryModal = (content) => {
    document.querySelector('[x-show="$store.modals.showMemory"]').style.display = 'flex';
    document.getElementById('memoryContent').value = content;
    document.getElementById('memoryTitle').value = content.slice(0, 50).trim();
  };

  // Memory save
  document.getElementById('memorySaveBtn')?.addEventListener('click', async () => {
    const title = document.getElementById('memoryTitle').value.trim();
    const content = document.getElementById('memoryContent').value.trim();
    const tags = document.getElementById('memoryTags').value.trim();

    if (!title || !content) return alert('Please fill out all fields.');
    try {
      const res = await fetch('/includes/memory_handler.php', {
        method: 'POST',
        body: new URLSearchParams({ action: 'save', title, content, tags })
      });
      const result = await res.json();
      if (result.success) {
        alert('Memory saved!');
        document.querySelector('[x-show="$store.modals.showMemory"]').style.display = 'none';
      }
    } catch (err) {
      console.error('[Memory Save Error]', err);
    }
  });

  document.getElementById('memoryCancelBtn')?.addEventListener('click', () => {
    document.querySelector('[x-show="$store.modals.showMemory"]').style.display = 'none';
  });

  // AI Chat Stream
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const msg = textarea.value.trim();
    if (!msg || sendBtn.disabled) return;

    textarea.value = '';
    textarea.style.height = 'auto';
    sendBtn.disabled = true;

    currentMessages.push({ role: 'user', content: msg });
    appendMessage(msg, 'user');

    const typingBubble = appendTyping();

    try {
      const res = await fetch('/api/chat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ prompt: msg, web_search: webSearchEnabled })
      });

      const reader = res.body.getReader();
      const decoder = new TextDecoder();
      let buffer = '';
      let lastChar = '';
      typingBubble.innerHTML = '';

      while (true) {
        const { done, value } = await reader.read();
        if (done) break;
        const chunk = decoder.decode(value, { stream: true });
        const lines = chunk.split('\n');

        for (const line of lines) {
          const text = line.replace(/^data:\s*/, '').trim();
          if (text) {
            const needsSpace = lastChar && !/\s/.test(lastChar) && !/^[.,!?;:)]/.test(text);
            buffer += (needsSpace ? ' ' : '') + text;
            lastChar = text.slice(-1);
            typingBubble.innerHTML = formatMessage(buffer);
            scrollToBottom();
          }
        }
      }

      currentMessages.push({ role: 'assistant', content: buffer });
      saveChatToHistory();

      if (/remember this|can you remember/i.test(msg)) {
        showMemoryModal(buffer);
      }

      await detectMemoryPatterns(msg);

    } catch (err) {
      typingBubble.innerText = '[AI response failed]';
      console.error('[AI Error]', err);
    } finally {
      sendBtn.disabled = false;
    }
  });

  // Memory Pattern Detection
  const detectMemoryPatterns = async (text) => {
    try {
      await fetch('/src/includes/pattern_detector.php', {
        method: 'POST',
        body: new URLSearchParams({ text })
      });
    } catch (err) {
      console.error('[Pattern Detection Error]', err);
    }
  };

  // Chat History
  const saveChatToHistory = async () => {
    try {
      const title = currentMessages[0]?.content?.slice(0, 50) || 'Untitled';
      await fetch('/includes/chat_handler.php', {
        method: 'POST',
        body: new URLSearchParams({
          action: 'save',
          title,
          messages: JSON.stringify(currentMessages)
        })
      });
      loadHistory();
    } catch (err) {
      console.error('[Save Chat Error]', err);
    }
  };

  const loadHistory = async () => {
    try {
      const res = await fetch('/includes/chat_handler.php', {
        method: 'POST',
        body: new URLSearchParams({ action: 'load' })
      });

      const { success, chats } = await res.json();
      if (!success) return;

      chatList.innerHTML = '';
      chats.forEach(chat => {
        const item = document.createElement('div');
        item.className = 'bg-gray-700 text-white rounded px-3 py-2 mb-1 cursor-pointer hover:bg-gray-600';
        item.textContent = chat.title;
        item.onclick = () => loadChat(chat);
        chatList.appendChild(item);
      });
    } catch (err) {
      console.error('[Load History Error]', err);
    }
  };

  const loadChat = (chat) => {
    currentMessages = JSON.parse(chat.content);
    chatMessages.innerHTML = '';
    currentMessages.forEach(({ role, content }) => {
      appendMessage(role === 'user' ? content : formatMessage(content), role);
    });
  };

  // UI Logic
  webSearchToggle.addEventListener('click', () => {
    webSearchEnabled = !webSearchEnabled;
    webSearchToggle.classList.toggle('bg-blue-600', webSearchEnabled);
    webSearchToggle.classList.toggle('text-white', webSearchEnabled);
  });

  textarea.addEventListener('input', () => {
    textarea.style.height = 'auto';
    textarea.style.height = `${textarea.scrollHeight}px`;
  });

  newChatBtn.addEventListener('click', () => {
    chatMessages.innerHTML = '';
    currentMessages = [];
    activeChatId = null;
  });

  loadHistory();
});

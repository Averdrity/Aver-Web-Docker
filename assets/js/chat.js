// chat.js – Final Ultra Version 💬🧠🚀
// Author: Aver / ChatGPT
// Version: 2.2.0 – Full AI streaming, memory system, pattern detection, uploads, and modern UI

document.addEventListener('DOMContentLoaded', () => {
  // === DOM ELEMENTS ===
  const form = document.getElementById('chatForm');
  const textarea = document.getElementById('chatInput');
  const chatMessages = document.getElementById('chat-messages');
  const chatList = document.getElementById('chat-list');
  const sendBtn = document.getElementById('sendBtn');
  const webSearchToggle = document.getElementById('webSearchToggle');
  const newChatBtn = document.getElementById('newChatBtn');

  const memoryModal = document.getElementById('memoryModal');
  const memoryTitle = document.getElementById('memoryTitle');
  const memoryText = document.getElementById('memoryText');
  const memoryCategory = document.getElementById('memoryCategory');
  const memorySaveBtn = document.getElementById('memorySaveBtn');
  const memoryCancelBtn = document.getElementById('memoryCancelBtn');

  const uploadedFilesBtn = document.getElementById('uploadedFilesBtn');
  const uploadedFilesModal = document.getElementById('uploadedFilesModal');
  const uploadedFilesList = document.getElementById('uploadedFilesList');
  const uploadedFilesClose = document.getElementById('uploadedFilesClose');

  // === STATE ===
  let currentMessages = [];
  let activeChatId = null;
  let webSearchEnabled = false;

  // === UTILITIES ===
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

  // === CHAT UI ===
  const appendMessage = (text, sender = 'user') => {
    const div = document.createElement('div');
    div.className = `relative ${sender === 'user' ? 'self-end bg-blue-600' : 'self-start bg-gray-700'} text-white rounded-xl p-3 w-fit max-w-[80%] whitespace-pre-wrap break-words shadow-md`;
    div.innerHTML = sender === 'user' ? `<div>${text}</div>` : text;

    if (sender === 'bot') {
      const copyBtn = document.createElement('button');
      copyBtn.innerText = '📋 Copy';
      copyBtn.className = 'absolute bottom-2 right-3 text-xs text-blue-400 hover:text-white transition';
      copyBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(div.innerText);
        copyBtn.innerText = '✅ Copied!';
        setTimeout(() => copyBtn.innerText = '📋 Copy', 2000);
      });
      div.appendChild(copyBtn);
    }

    chatMessages.appendChild(div);
    scrollToBottom();
    return div;
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

    // === MEMORY MODAL ===
    const showMemoryModal = (content) => {
      memoryModal.classList.remove('hidden');
      memoryText.value = content;
      memoryTitle.value = content.slice(0, 50).trim();
    };
  
    memoryCancelBtn?.addEventListener('click', () => memoryModal.classList.add('hidden'));
  
    memorySaveBtn?.addEventListener('click', async () => {
      const title = memoryTitle.value.trim();
      const content = memoryText.value.trim();
      const category = memoryCategory.value.trim();
  
      if (!title || !content) return alert('Please fill out all fields.');
      try {
        const res = await fetch('/includes/memory_handler.php', {
          method: 'POST',
          body: new URLSearchParams({ action: 'save', title, content, category })
        });
        const result = await res.json();
        if (result.success) {
          memoryModal.classList.add('hidden');
          alert('Memory saved!');
        }
      } catch (err) {
        console.error('[Memory Save Error]', err);
      }
    });
  
    // === PATTERN DETECTOR ===
    const detectMemoryPatterns = async (text) => {
      try {
        const res = await fetch('/src/includes/pattern_detector.php', {
          method: 'POST',
          body: new URLSearchParams({ text })
        });
        const result = await res.json();
        if (result.success && result.saved > 0) {
          console.log('[Pattern Memory Saved]', result.memories);
        }
      } catch (err) {
        console.error('[Pattern Detector Error]', err);
      }
    };
  
    // === CHAT SUBMISSION ===
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
  
    // === CHAT HISTORY ===
    const saveChatToHistory = async () => {
      try {
        const title = currentMessages[0]?.content?.slice(0, 50) || 'Untitled';
        const res = await fetch('/includes/chat_handler.php', {
          method: 'POST',
          body: new URLSearchParams({
            action: 'save',
            title,
            messages: JSON.stringify(currentMessages)
          })
        });
        const { success, id } = await res.json();
        if (success) {
          activeChatId = id;
          loadHistory();
        }
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
        const grouped = {};
  
        chats.forEach(chat => {
          const date = new Date(chat.created_at).toLocaleDateString();
          if (!grouped[date]) grouped[date] = [];
          grouped[date].push(chat);
        });
  
        for (const date in grouped) {
          const label = document.createElement('div');
          label.className = 'text-xs text-gray-400 mt-4 mb-2 uppercase tracking-wide';
          label.innerText = date;
          chatList.appendChild(label);
  
          grouped[date].forEach(chat => {
            const item = document.createElement('div');
            item.className = 'flex justify-between items-center bg-gray-700 hover:bg-gray-600 rounded px-3 py-2 cursor-pointer group';
            item.innerHTML = `
              <span class="truncate">${chat.title}</span>
              <div class="opacity-0 group-hover:opacity-100 flex gap-2">
                <button onclick="renameChat(${chat.id})" class="text-xs text-blue-400 hover:text-white">✏️</button>
                <button onclick="deleteChat(${chat.id})" class="text-xs text-red-400 hover:text-white">🗑️</button>
              </div>`;
            item.onclick = () => loadChat(chat);
            chatList.appendChild(item);
          });
        }
      } catch (err) {
        console.error('[Load History Error]', err);
      }
    };
  
    const loadChat = (chat) => {
      activeChatId = chat.id;
      currentMessages = JSON.parse(chat.content);
      chatMessages.innerHTML = '';
      currentMessages.forEach(({ role, content }) => {
        appendMessage(role === 'user' ? content : formatMessage(content), role);
      });
    };
  
    window.renameChat = async (id) => {
      const newTitle = prompt('New title:');
      if (!newTitle) return;
      try {
        await fetch('/includes/chat_handler.php', {
          method: 'POST',
          body: new URLSearchParams({ action: 'rename', chat_id: id, title: newTitle })
        });
        loadHistory();
      } catch (err) {
        console.error('[Rename Error]', err);
      }
    };
  
    window.deleteChat = async (id) => {
      if (!confirm('Delete this chat?')) return;
      try {
        await fetch('/includes/chat_handler.php', {
          method: 'POST',
          body: new URLSearchParams({ action: 'delete', chat_id: id })
        });
        loadHistory();
      } catch (err) {
        console.error('[Delete Error]', err);
      }
    };
  
    // === FILE UPLOAD MODAL ===
    uploadedFilesBtn?.addEventListener('click', async () => {
      uploadedFilesModal.classList.remove('hidden');
      await loadUploadedFiles();
    });
  
    uploadedFilesClose?.addEventListener('click', () => {
      uploadedFilesModal.classList.add('hidden');
    });
  
    async function loadUploadedFiles() {
      uploadedFilesList.innerHTML = '<div class="text-sm text-gray-400 italic text-center">Loading...</div>';
      try {
        const res = await fetch('/includes/upload_handler.php?action=list');
        const data = await res.json();
        if (!data.success) return;
  
        if (data.files.length === 0) {
          uploadedFilesList.innerHTML = '<div class="text-sm text-gray-400 italic text-center">No files uploaded.</div>';
          return;
        }
  
        uploadedFilesList.innerHTML = '';
        data.files.forEach(file => {
          const li = document.createElement('div');
          const expiresIn = Math.max(0, file.expires_in);
          const minsLeft = Math.floor(expiresIn / 60);
          li.className = 'bg-gray-700 text-white rounded px-3 py-2 text-sm flex justify-between items-center';
          li.innerHTML = `
            <div class="flex flex-col">
              <span class="font-medium">${file.original_name}</span>
              <span class="text-xs text-gray-400">${minsLeft} min left</span>
            </div>
            <button class="text-red-400 hover:text-white text-sm" onclick="deleteUploadedFile(${file.id})">🗑️</button>`;
          uploadedFilesList.appendChild(li);
        });
      } catch (err) {
        uploadedFilesList.innerHTML = '<div class="text-sm text-red-400 text-center">Failed to load files.</div>';
        console.error('[Files Load Error]', err);
      }
    }
  
    window.deleteUploadedFile = async (id) => {
      if (!confirm('Delete this file?')) return;
      try {
        await fetch('/includes/upload_handler.php', {
          method: 'POST',
          body: new URLSearchParams({ action: 'delete', id })
        });
        await loadUploadedFiles();
      } catch (err) {
        console.error('[File Delete Error]', err);
      }
    };
  
    // === INIT ===
    textarea.addEventListener('input', () => {
      textarea.style.height = 'auto';
      textarea.style.height = `${textarea.scrollHeight}px`;
    });
  
    webSearchToggle.addEventListener('click', () => {
      webSearchEnabled = !webSearchEnabled;
      webSearchToggle.classList.toggle('bg-blue-600', webSearchEnabled);
      webSearchToggle.classList.toggle('text-white', webSearchEnabled);
    });
  
    newChatBtn.addEventListener('click', () => {
      chatMessages.innerHTML = '';
      currentMessages = [];
      activeChatId = null;
    });
  
    loadHistory();
  });
 
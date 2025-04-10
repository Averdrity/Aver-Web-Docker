document.addEventListener('DOMContentLoaded', () => {
  const textarea = document.getElementById('chatInput');
  const form = document.getElementById('chatForm');
  const chatMessages = document.getElementById('chat-messages');

  // Auto-resize textarea
  textarea.addEventListener('input', () => {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
  });

  textarea.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      form.requestSubmit();
    }
  });

  // Marked + Prism config
  marked.setOptions({
    highlight: function (code, lang) {
      const language = Prism.languages[lang] || Prism.languages.markup;
      return Prism.highlight(code, language, lang);
    },
    langPrefix: 'language-',
    breaks: true,
    gfm: true,
  });

  const fixTokenSpacing = (text) => {
    return text
      .replace(/\s+'\s+s\b/g, "'s")
      .replace(/\s+'\s+m\b/g, "'m")
      .replace(/\s+'\s+re\b/g, "'re")
      .replace(/\s+'\s+ve\b/g, "'ve")
      .replace(/\s+'\s+d\b/g, "'d")
      .replace(/\s+'\s+ll\b/g, "'ll")
      .replace(/\bcan\s+not\b/g, "can't")
      .replace(/\b(would|could|should)\s+not\b/g, "$1n't")
      .replace(/\b(\w+)\s+-\s+(\w+)/g, "$1-$2")
      .replace(/\b(\w+)\s+ing\b/g, "$1ing");
  };

  const formatMessage = (raw) => {
    const fixed = fixTokenSpacing(raw);
    const html = marked.parse(fixed);
    const wrapper = document.createElement('div');
    wrapper.innerHTML = html;

    wrapper.querySelectorAll('pre code').forEach((block) => {
      Prism.highlightElement(block);
    });

    return wrapper.innerHTML;
  };

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const msg = textarea.value.trim();
    if (!msg) return;

    textarea.value = '';
    textarea.style.height = 'auto';

    const userBubble = document.createElement('div');
    userBubble.className = 'self-end bg-blue-600 text-white rounded-xl p-3 w-fit max-w-[80%] whitespace-pre-wrap break-words shadow-md';
    userBubble.innerText = msg;
    chatMessages.appendChild(userBubble);

    const botBubble = document.createElement('div');
    botBubble.className = 'relative self-start bg-gray-700 text-white rounded-xl p-4 w-fit max-w-[80%] whitespace-pre-wrap break-words shadow-md';
    botBubble.innerHTML = `
      <div class="flex space-x-1 text-2xl text-gray-400 animate-pulse">
        <span class="animate-bounce">.</span>
        <span class="animate-bounce delay-150">.</span>
        <span class="animate-bounce delay-300">.</span>
      </div>`;
    chatMessages.appendChild(botBubble);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    try {
      const response = await fetch('/api/chat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ prompt: msg }),
      });

      const reader = response.body.getReader();
      const decoder = new TextDecoder();
      botBubble.innerHTML = '';

      let buffer = '';
      let lastChar = '';

      while (true) {
        const { done, value } = await reader.read();
        if (done) break;

        const chunk = decoder.decode(value, { stream: true });
        const lines = chunk.split('\n');

        for (const line of lines) {
          const text = line.replace(/^data:\s*/, '').trim();
          if (text) {
            const needsSpace =
              lastChar &&
              !/\s/.test(lastChar) &&
              !/^[\s.,!?;:)\]}]/.test(text) &&
              !/^['"]/.test(text);

            buffer += (needsSpace ? ' ' : '') + text;
            lastChar = text.slice(-1);

            botBubble.innerHTML = formatMessage(buffer);
            chatMessages.scrollTop = chatMessages.scrollHeight;
          }
        }
      }

      // Copy button
      const copyBtn = document.createElement('button');
      copyBtn.innerText = '📋 Copy';
      copyBtn.className = 'absolute top-2 right-3 text-xs text-blue-400 hover:text-white transition';
      copyBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(botBubble.innerText);
        copyBtn.innerText = '✅ Copied!';
        setTimeout(() => (copyBtn.innerText = '📋 Copy'), 2000);
      });
      botBubble.appendChild(copyBtn);

    } catch (err) {
      botBubble.innerText = '[Error loading AI response]';
      console.error(err);
    }
  });
});

<?php
require_once('components/header.php');
require_once('components/modals/login-modal.php');
require_once('components/modals/register-modal.php');
require_once('components/modals/profile-modal.php');
?>

<main class="flex h-[calc(100vh-64px)] bg-gray-900 text-white">

  <!-- 🕘 Sidebar: Chat History -->
  <aside x-data="{ open: true }" id="chat-history"
         class="bg-gray-800 border-r border-gray-700 p-4 w-64 hidden lg:block transition-all duration-300"
         :class="{ 'hidden': !open }">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-bold text-white flex items-center gap-2">🕘 <span>Chat History</span></h2>
      <button @click="open = false" class="text-gray-400 hover:text-white text-xl leading-none">&times;</button>
    </div>
    <div id="chat-list" class="space-y-4 text-sm overflow-y-auto">
      <!-- Injected via JS -->
      <div class="text-gray-500 text-xs text-center italic">Loading history...</div>
    </div>
  </aside>

  <!-- 🧠 Main Chat Area -->
  <section class="flex-1 flex flex-col items-center relative overflow-hidden">

    <!-- ☰ Mobile toggle -->
    <div class="absolute top-4 left-4 z-20 lg:hidden">
      <button onclick="document.getElementById('chat-history').classList.toggle('hidden')"
              class="bg-gray-700 px-3 py-1 rounded hover:bg-gray-600">
        ☰
      </button>
    </div>

    <!-- 💬 Chat Messages -->
    <div id="chat-messages" class="w-full max-w-3xl px-6 py-8 overflow-y-auto flex flex-col gap-4 h-[calc(100vh-170px)]">
      <div class="text-center text-sm text-gray-400">Start a new conversation...</div>
    </div>

    <!-- ✏️ Chat Input -->
    <div class="w-full max-w-3xl bg-gray-800 border-t border-gray-700 px-4 py-4">
      <form id="chatForm" class="flex items-end gap-3">
        <textarea
          id="chatInput"
          rows="1"
          placeholder="Type your message..."
          class="flex-1 resize-none rounded p-3 bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
        ></textarea>
        <button type="submit"
                class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-700 transition">
          Send
        </button>
      </form>
    </div>
  </section>

  <!-- 🧠 Sidebar: Memory Vault -->
  <aside x-data="{ open: true }" id="memory-vault"
         class="bg-gray-800 border-l border-gray-700 p-4 w-64 hidden xl:block transition-all duration-300"
         :class="{ 'hidden': !open }">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-bold text-white flex items-center gap-2">🧠 <span>Memory Vault</span></h2>
      <button @click="open = false" class="text-gray-400 hover:text-white text-xl leading-none">&times;</button>
    </div>
    <div class="space-y-2 text-sm">
      <div class="text-gray-400">Personal</div>
      <div class="bg-gray-700 rounded px-3 py-2">"I have ADHD"</div>
      <div class="bg-gray-700 rounded px-3 py-2">"Weight loss goals"</div>
    </div>
  </aside>

</main>

<?php require_once('components/footer.php'); ?>
<script src="/assets/js/chat.js" defer></script>
<script src="/assets/js/main.js" defer></script>

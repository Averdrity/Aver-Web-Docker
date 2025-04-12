<?php
require_once('components/header.php');
require_once('components/modals/login-modal.php');
require_once('components/modals/register-modal.php');
require_once('components/modals/profile-modal.php');
require_once('components/modals/memory-modal.php');
require_once('components/modals/uploaded-files-modal.php');
?>

<main x-data="{ sidebar: $store.sidebar }" class="flex h-[calc(100vh-64px)] bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">

  <!-- LEFT SIDEBAR: Chat History -->
  <aside x-show="sidebar.left" x-transition
         class="bg-gray-100 dark:bg-gray-800 border-r border-gray-300 dark:border-gray-700 w-64 p-4 hidden lg:flex flex-col">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-sm font-semibold px-3 py-1 rounded bg-gray-200 dark:bg-gray-700">📜 History</h2>
      <button @click="sidebar.left = false" class="text-lg text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white">&times;</button>
    </div>

    <input type="text" placeholder="Search..." class="w-full mb-3 px-3 py-2 text-sm rounded bg-gray-200 dark:bg-gray-700 focus:outline-none">

    <div id="chat-list" class="overflow-y-auto flex-1 space-y-3 text-sm">
      <div class="text-gray-400 text-xs text-center italic">Loading history...</div>
    </div>
  </aside>

  <!-- MAIN CHAT AREA -->
  <section class="flex-1 flex flex-col relative">

    <!-- MOBILE TOGGLE BUTTON -->
    <div class="absolute top-4 left-4 z-20 lg:hidden">
      <button @click="sidebar.left = !sidebar.left"
              class="bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
        ☰
      </button>
    </div>

    <!-- CHAT MESSAGES -->
    <div id="chat-messages" class="w-full max-w-3xl mx-auto px-6 py-8 flex flex-col gap-4 overflow-y-auto h-[calc(100vh-170px)] scroll-smooth">
      <div class="text-center text-sm text-gray-400">Start a new conversation...</div>
    </div>

    <!-- CHAT INPUT -->
    <div class="w-full max-w-3xl mx-auto border-t border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-4 py-4">
      <form id="chatForm" class="flex items-end gap-3 relative">

        <!-- UPLOAD BUTTON DROPDOWN -->
        <div class="relative group">
          <button type="button" class="text-xl font-bold hover:text-blue-400 transition">＋</button>
          <div class="absolute bottom-10 left-0 hidden group-hover:block bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded shadow-lg py-2 px-3 space-y-2 z-30">
            <label class="block text-sm hover:text-blue-500 cursor-pointer transition">
              📎 Upload File
              <input type="file" name="file" id="fileUpload" class="hidden" />
            </label>
            <label class="block text-sm hover:text-blue-500 cursor-pointer transition">
              🖼️ Upload Image
              <input type="file" name="image" accept="image/*" class="hidden" />
            </label>
            <button type="button"
                    onclick="$store.modal.showUploads = true"
                    class="block w-full text-left text-sm hover:text-blue-500 transition">📁 Manage Uploads</button>
          </div>
        </div>

        <!-- WEB SEARCH -->
        <button type="button" id="webSearchToggle"
                class="text-xs px-3 py-2 border rounded hover:bg-gray-300 dark:hover:bg-gray-700 transition">
          🌐 Web Search
        </button>

        <!-- INPUT TEXTAREA -->
        <textarea id="chatInput" rows="1" placeholder="Type your message..."
                  class="flex-1 resize-none px-4 py-3 bg-gray-200 dark:bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm transition">
        </textarea>

        <!-- NEW CHAT -->
        <button type="button" id="newChatBtn"
                class="text-xl px-3 py-2 hover:text-blue-500 transition">
          ♻️
        </button>

        <!-- SEND -->
        <button type="submit" id="sendBtn"
                class="bg-blue-600 hover:bg-blue-700 transition text-white font-bold text-lg px-4 py-2 rounded-full disabled:opacity-50">
          ➤
        </button>
      </form>
    </div>
  </section>

  <!-- RIGHT SIDEBAR: Memory Vault -->
  <aside x-show="sidebar.right" x-transition
         class="bg-gray-100 dark:bg-gray-800 border-l border-gray-300 dark:border-gray-700 w-64 p-4 hidden xl:flex flex-col">
    <div class="flex items-center justify-between mb-4">
      <button @click="sidebar.right = false" class="text-lg text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white">&times;</button>
      <h2 class="text-sm font-semibold px-3 py-1 rounded bg-gray-200 dark:bg-gray-700">🧠 Memory</h2>
    </div>

    <input type="text" placeholder="Search memories..." class="w-full mb-3 px-3 py-2 text-sm rounded bg-gray-200 dark:bg-gray-700 focus:outline-none">

    <div id="memory-list" class="flex-1 overflow-y-auto space-y-3 text-sm">
      <div class="text-gray-400 text-xs text-center italic">No memories yet...</div>
    </div>
  </aside>

</main>

<?php require_once('components/footer.php'); ?>
<script src="/assets/js/chat.js" defer></script>
<script src="/assets/js/main.js" defer></script>

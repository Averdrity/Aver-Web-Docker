<?php
require_once('includes/session.php');
require_once('components/header.php');
require_once('components/modals/login-modal.php');
require_once('components/modals/register-modal.php');
require_once('components/modals/profile-modal.php');
require_once('components/modals/memory-modal.php');
require_once('components/modals/uploaded-files-modal.php');
?>

<main x-data="{ sidebar: $store.sidebar }"
      class="flex h-[calc(100vh-64px)] bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300 overflow-hidden relative">

  <!-- 🚀 ALWAYS VISIBLE TOGGLE: LEFT SIDEBAR -->
  <button @click="sidebar.left = !sidebar.left"
          class="absolute top-4 z-20 transition-all duration-300 px-2 py-2 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600"
          :class="sidebar.left ? 'left-64' : 'left-4'">
    <img src="/assets/icons/square-toggle.svg" class="w-5 h-5" alt="Toggle Sidebar">
  </button>

  <!-- 🧠 ALWAYS VISIBLE TOGGLE: RIGHT SIDEBAR -->
  <button @click="sidebar.right = !sidebar.right"
          class="absolute top-4 z-20 transition-all duration-300 px-2 py-2 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600"
          :class="sidebar.right ? 'right-64' : 'right-4'">
    <img src="/assets/icons/square-toggle.svg" class="w-5 h-5" alt="Toggle Memory">
  </button>

  <!-- 📜 LEFT SIDEBAR: History -->
  <aside x-show="sidebar.left" x-transition
         class="bg-gray-100 dark:bg-gray-800 border-r border-gray-300 dark:border-gray-700 w-64 p-4 hidden lg:flex flex-col">
    <div class="flex items-center justify-between mb-4">
      <h2 class="flex items-center gap-2 text-sm font-semibold px-3 py-1 rounded bg-gray-200 dark:bg-gray-700">
        <img src="/assets/icons/history.svg" class="w-5 h-5" alt="History Icon">
        History
      </h2>
      <button @click="sidebar.left = false"
              class="text-lg text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white">&times;</button>
    </div>
    <input type="text" placeholder="Search history..." id="historySearch"
           class="w-full mb-3 px-3 py-2 text-sm rounded bg-gray-200 dark:bg-gray-700 focus:outline-none">
    <div id="chat-list" class="overflow-y-auto flex-1 space-y-3 text-sm">
      <div class="text-gray-400 text-xs text-center italic">Loading history...</div>
    </div>
  </aside>

  <!-- 💬 MAIN CHAT AREA -->
  <section class="flex-1 flex flex-col">

    <!-- Chat Messages -->
    <div id="chat-messages" class="w-full max-w-3xl mx-auto px-6 py-8 flex flex-col gap-4 overflow-y-auto h-[calc(100vh-172px)] scroll-smooth">
      <!-- Chat messages render here -->
    </div>

    <!-- Chat Input -->
    <div class="w-full max-w-3xl mx-auto border-t border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-4 py-4">
      <form id="chatForm" class="flex items-end gap-3 relative">

        <!-- Upload Dropdown -->
        <div class="relative group z-20">
          <button type="button" class="hover:text-blue-400 transition">
            <img src="/assets/icons/add.svg" class="w-5 h-5" alt="Add">
          </button>
          <div class="absolute bottom-10 left-0 hidden group-hover:flex flex-col bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded shadow-lg py-2 px-3 space-y-2 z-30">
            <label class="flex items-center gap-2 text-sm hover:text-blue-500 cursor-pointer transition">
              <img src="/assets/icons/attachment.svg" class="w-4 h-4" alt="File">
              File
              <input type="file" name="file" id="fileUpload" class="hidden" />
            </label>
            <label class="flex items-center gap-2 text-sm hover:text-blue-500 cursor-pointer transition">
              <img src="/assets/icons/image.svg" class="w-4 h-4" alt="Image">
              Image
              <input type="file" name="image" accept="image/*" class="hidden" />
            </label>
            <button type="button" @click="$store.modals.showUploads = true"
                    class="flex items-center gap-2 text-sm hover:text-blue-500 transition">
              <img src="/assets/icons/files.svg" class="w-4 h-4" alt="Files">
              Files
            </button>
          </div>
        </div>

        <!-- Web Search Toggle -->
        <button type="button" id="webSearchToggle"
                class="flex items-center gap-1 text-xs px-3 py-2 border rounded hover:bg-gray-300 dark:hover:bg-gray-700 transition">
          <img src="/assets/icons/search.svg" class="w-4 h-4" alt="Search">
          Web
        </button>

        <!-- Text Area -->
        <textarea id="chatInput" rows="1" placeholder="Type your message..."
                  class="flex-1 resize-none px-4 py-3 bg-gray-200 dark:bg-gray-700 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm transition"></textarea>

        <!-- New Chat -->
        <button type="button" id="newChatBtn"
                class="hover:text-blue-500 transition">
          <img src="/assets/icons/refresh.svg" class="w-5 h-5" alt="Refresh">
        </button>

        <!-- Send -->
        <button type="submit" id="sendBtn"
                class="bg-blue-600 hover:bg-blue-700 transition text-white font-bold text-lg px-4 py-2 rounded-full disabled:opacity-50">
          <img src="/assets/icons/send.svg" class="w-5 h-5" alt="Send">
        </button>
      </form>
    </div>
  </section>

  <!-- 📂 RIGHT SIDEBAR: Memory -->
  <aside x-show="sidebar.right" x-transition
         class="bg-gray-100 dark:bg-gray-800 border-l border-gray-300 dark:border-gray-700 w-64 p-4 hidden xl:flex flex-col">
    <div class="flex items-center justify-between mb-4">
      <button @click="sidebar.right = false"
              class="text-lg text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white">&times;</button>
      <h2 class="flex items-center gap-2 text-sm font-semibold px-3 py-1 rounded bg-gray-200 dark:bg-gray-700">
        <img src="/assets/icons/memory.svg" class="w-5 h-5" alt="Memory">
        Memory
      </h2>
    </div>

    <input type="text" id="memorySearch" placeholder="Search memories..."
           class="w-full mb-3 px-3 py-2 text-sm rounded bg-gray-200 dark:bg-gray-700 focus:outline-none">

    <div id="memory-list" class="flex-1 overflow-y-auto space-y-3 text-sm">
      <div class="text-gray-400 text-xs text-center italic">No memories yet...</div>
    </div>
  </aside>
</main>

<?php require_once('components/footer.php'); ?>

<!-- Scripts -->
<script src="/assets/js/chat.js" defer></script>
<script src="/assets/js/main.js" defer></script>

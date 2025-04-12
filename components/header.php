<?php
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/session.php');
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#111827" />
  <title>Aver-Web</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/favicon.png" />

  <!-- 🌗 Init dark mode early -->
  <script>
    if (localStorage.getItem('theme') === 'dark' || !localStorage.getItem('theme')) {
      document.documentElement.classList.add('dark');
    }
  </script>

  <!-- TailwindCSS -->
  <link rel="stylesheet" href="/assets/css/style.css" />

  <!-- Prism.js -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism-tomorrow.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/prismjs/prism.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-javascript.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-json.min.js" defer></script>

  <!-- Marked.js -->
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js" defer></script>

  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body x-data x-init="$store.auth.init(); $store.uploads.init();"
      class="bg-white text-gray-900 dark:bg-gray-900 dark:text-white antialiased transition-colors duration-300"
      data-logged-in="<?= isLoggedIn() ? 'true' : 'false' ?>">

<!-- 🧠 Alpine Global Stores -->
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.store('auth', {
      showLogin: false,
      showRegister: false,
      showProfile: false,
      init() {},
      openLogin() { this.closeAll(); this.showLogin = true; },
      openRegister() { this.closeAll(); this.showRegister = true; },
      openProfile() {
        this.closeAll();
        this.showProfile = true;
        if (typeof loadProfile === 'function') loadProfile();
      },
      closeAll() {
        this.showLogin = false;
        this.showRegister = false;
        this.showProfile = false;
      }
    });

    Alpine.store('uploads', {
      show: false,
      files: [],
      init() { this.load(); },
      async load() {
        try {
          const res = await fetch('/includes/upload_handler.php', {
            method: 'POST',
            body: new URLSearchParams({ action: 'list' })
          });
          const data = await res.json();
          if (data.success) this.files = data.files;
        } catch (err) {
          console.error('[Upload List Error]', err);
        }
      },
      async deleteFile(id) {
        if (!confirm('Delete this file?')) return;
        try {
          const res = await fetch('/includes/upload_handler.php', {
            method: 'POST',
            body: new URLSearchParams({ action: 'delete', id })
          });
          const data = await res.json();
          if (data.success) this.load();
          else alert(data.message || 'Delete failed');
        } catch (err) {
          console.error('[Delete Upload Error]', err);
        }
      }
    });

    Alpine.store('modals', {
      showUploads: false,
      showMemory: false,
    });

    Alpine.store('sidebar', {
      left: true,
      right: true,
    });
  });
</script>

<!-- 🌐 Header Navigation -->
<header class="w-full border-b border-gray-700 dark:border-gray-600 bg-white dark:bg-gray-900 shadow-sm transition-colors duration-300">
  <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-3">

    <!-- 🔽 Left Buttons -->
    <div class="flex gap-2 sm:gap-3 items-center">
      <?php if ($currentPage === 'chat.php'): ?>
        <a href="/index.php" class="flex items-center gap-1 px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800 transition text-sm font-medium">
          <img src="/assets/icons/home.svg" alt="Home" class="w-5 h-5" />
          <span>Home</span>
        </a>
      <?php else: ?>
        <a href="/chat.php" class="flex items-center gap-1 px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800 transition text-sm font-medium">
          <img src="/assets/icons/chat.svg" alt="Chat" class="w-5 h-5" />
          <span>Chat</span>
        </a>
      <?php endif; ?>

      <?php if (isLoggedIn()): ?>
        <button @click="$store.modals.showUploads = true"
                class="flex items-center gap-1 px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800 transition text-sm font-medium">
          <img src="/assets/icons/files.svg" alt="Files" class="w-5 h-5" />
          <span>Files</span>
        </button>
      <?php endif; ?>

      <span class="flex items-center gap-1 px-3 py-2 rounded opacity-40 cursor-not-allowed">
        <img src="/assets/icons/row-remove.svg" alt="Soon" class="w-5 h-5" />
        <span>Soon</span>
      </span>
    </div>

    <!-- 🔰 Center Logo -->
    <div class="animate-pulse">
      <img src="/assets/aw_logo_transparent_64x64.png" alt="Aver-Web Logo" class="w-10 h-10" />
    </div>

    <!-- 🔼 Right Buttons -->
    <div class="flex gap-2 sm:gap-3 items-center">
      <span class="flex items-center gap-1 px-3 py-2 rounded opacity-40 cursor-not-allowed">
        <img src="/assets/icons/row-remove.svg" alt="Soon" class="w-5 h-5" />
        <span>Soon</span>
      </span>

      <?php if (!isLoggedIn()): ?>
        <button @click="$store.auth.openLogin()"
                class="flex items-center gap-1 px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800 transition text-sm font-medium">
          <img src="/assets/icons/login.svg" alt="Login" class="w-5 h-5" />
          <span>Login</span>
        </button>

        <button @click="$store.auth.openRegister()"
                class="flex items-center gap-1 px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800 transition text-sm font-medium">
          <img src="/assets/icons/register.svg" alt="Register" class="w-5 h-5" />
          <span>Register</span>
        </button>
      <?php else: ?>
        <button @click="$store.auth.openProfile()"
                class="flex items-center gap-1 px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800 transition text-sm font-medium">
          <img src="/assets/icons/profile.svg" alt="Profile" class="w-5 h-5" />
          <span>Profile</span>
        </button>

        <a href="/auth/logout.php"
           class="flex items-center gap-1 px-3 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-800 transition text-sm font-medium">
          <img src="/assets/icons/logout.svg" alt="Logout" class="w-5 h-5" />
          <span>Logout</span>
        </a>
      <?php endif; ?>
    </div>
  </div>
</header>

<!-- ✅ Main JS -->
<script src="/assets/js/main.js" defer></script>

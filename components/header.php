<?php
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/session.php');
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en" x-data x-init="$store.auth.init(); $store.uploads.init();">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#111827" />
  <title>Aver-Web</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/favicon.png" />

  <!-- Prism.js -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism-tomorrow.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/prismjs/prism.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-javascript.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-json.min.js" defer></script>

  <!-- Marked.js -->
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js" defer></script>

  <!-- Alpine.js v3 -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Styles -->
  <link rel="stylesheet" href="/assets/css/style.css" />
</head>

<body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-white antialiased transition-colors duration-300" x-data>

<!-- 🌐 Alpine Stores -->
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
      init() {
        this.load();
      },
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

<!-- 📦 Header Bar -->
<header class="w-full p-4 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm border-b border-gray-300 dark:border-gray-700 transition-colors duration-300">
  <div class="max-w-7xl mx-auto flex items-center justify-between">
    <!-- 🔰 Logo / Brand -->
    <div class="text-2xl font-bold tracking-wide">
      Aver-Web
    </div>

    <!-- 🌐 Nav Buttons -->
    <div class="flex flex-wrap items-center gap-2 sm:gap-3">

      <!-- 🔁 Page Switch -->
      <?php if ($currentPage === 'chat.php'): ?>
        <a href="/index.php" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded text-white text-sm font-medium transition">
          Home
        </a>
      <?php else: ?>
        <a href="/chat.php" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded text-white text-sm font-medium transition">
          AI Chat
        </a>
      <?php endif; ?>

      <!-- 📂 Uploads Button (Logged In Only) -->
      <?php if (isLoggedIn()): ?>
        <button @click="$store.modals.showUploads = true"
                class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded font-medium transition">
          Uploaded Files
        </button>
      <?php endif; ?>

      <!-- 🔐 Auth Buttons -->
      <?php if (!isLoggedIn()): ?>
        <button @click="$store.auth.openLogin()"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded font-medium transition">
          Login
        </button>
        <button @click="$store.auth.openRegister()"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded font-medium transition">
          Register
        </button>
      <?php else: ?>
        <button @click="$store.auth.openProfile()"
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded font-medium transition">
          Profile
        </button>
        <a href="/auth/logout.php"
           class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded font-medium transition">
          Logout
        </a>
      <?php endif; ?>
    </div>
  </div>
</header>

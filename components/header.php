<?php
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/session.php');
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en" x-data x-init="$store.auth.init()">
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

<body class="bg-gray-900 text-white antialiased" x-data>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.store('auth', {
        showLogin: false,
        showRegister: false,
        showProfile: false,
        init() {
          // Optional future logic (e.g., load session data)
        },
        openLogin() {
          this.closeAll();
          this.showLogin = true;
        },
        openRegister() {
          this.closeAll();
          this.showRegister = true;
        },
        openProfile() {
          this.closeAll();
          this.showProfile = true;
          if (typeof loadProfile === 'function') loadProfile(); // Load data
        },
        closeAll() {
          this.showLogin = false;
          this.showRegister = false;
          this.showProfile = false;
        }
      });
    });
  </script>

  <!-- Header -->
  <header class="w-full p-4 bg-gray-800 flex justify-between items-center shadow-md">
    <div class="text-xl font-bold tracking-wide text-white">Aver-Web</div>

    <div class="flex space-x-4">
      <!-- Page Switch -->
      <?php if ($currentPage === 'chat.php'): ?>
        <a href="/index.php" class="px-4 py-2 bg-purple-600 rounded hover:bg-purple-700 transition">Home</a>
      <?php else: ?>
        <a href="/chat.php" class="px-4 py-2 bg-purple-600 rounded hover:bg-purple-700 transition">AI Chat</a>
      <?php endif; ?>

      <!-- Auth Buttons -->
      <?php if (!isLoggedIn()): ?>
        <button @click="$store.auth.openLogin()" class="px-4 py-2 bg-blue-600 rounded hover:bg-blue-700 transition">Login</button>
        <button @click="$store.auth.openRegister()" class="px-4 py-2 bg-green-600 rounded hover:bg-green-700 transition">Register</button>
      <?php else: ?>
        <button @click="$store.auth.openProfile()" class="px-4 py-2 bg-gray-600 rounded hover:bg-gray-700 transition">Profile</button>
        <a href="/auth/logout.php" class="px-4 py-2 bg-red-600 rounded hover:bg-red-700 transition">Logout</a>
      <?php endif; ?>
    </div>
  </header>

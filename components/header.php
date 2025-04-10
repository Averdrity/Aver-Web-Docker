<?php
require_once(__DIR__ . '/../includes/config.php');
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en" x-data="{ showLogin: false, showRegister: false, showProfile: false }">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#111827" />
  <title>Aver-Web</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/favicon.png" />

  <!-- Prism.js for syntax highlighting -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism-tomorrow.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/prismjs/prism.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-javascript.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-json.min.js" defer></script>

  <!-- Marked.js for markdown parsing -->
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js" defer></script>

  <!-- Alpine.js for modal toggling -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.x.x/dist/alpine.min.js" defer></script>

  <!-- TailwindCSS Output -->
  <link rel="stylesheet" href="/assets/css/style.css" />
</head>

<body class="bg-gray-900 text-white antialiased">

  <!-- Header -->
  <header class="w-full p-4 bg-gray-800 flex justify-between items-center shadow-md">
    <div class="text-xl font-bold tracking-wide text-white">Aver-Web</div>

    <div class="flex space-x-4">
      <!-- Page Switch Button -->
      <?php if ($currentPage === 'chat.php'): ?>
        <a href="/index.php" class="px-4 py-2 bg-purple-600 rounded hover:bg-purple-700 transition">Home</a>
      <?php else: ?>
        <a href="/chat.php" class="px-4 py-2 bg-purple-600 rounded hover:bg-purple-700 transition">AI Chat</a>
      <?php endif; ?>

      <!-- Auth Buttons -->
      <?php if (!isLoggedIn()): ?>
        <button @click="showLogin = true" class="px-4 py-2 bg-blue-600 rounded hover:bg-blue-700 transition">Login</button>
        <button @click="showRegister = true" class="px-4 py-2 bg-green-600 rounded hover:bg-green-700 transition">Register</button>
      <?php else: ?>
        <!-- Profile Button for logged-in users -->
        <button @click="showProfile = true" class="px-4 py-2 bg-gray-600 rounded hover:bg-gray-700 transition">Profile</button>
        <a href="/auth/logout.php" class="px-4 py-2 bg-red-600 rounded hover:bg-red-700 transition">Logout</a>
      <?php endif; ?>
    </div>
  </header>

  <!-- Profile Modal -->
  <div x-show="showProfile" @click="showProfile = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50">
    <div class="bg-gray-800 p-6 rounded-xl shadow-lg w-full max-w-xl relative">
      
      <!-- Close Button -->
      <button @click="showProfile = false" class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl">&times;</button>
      
      <!-- Modal Title -->
      <h2 class="text-2xl font-bold text-white mb-6 text-center">Edit Profile</h2>
      
      <!-- Profile Form -->
      <form id="profileForm" action="/includes/profile_handler.php" method="POST" class="space-y-4">
        <input type="text" name="nickname" placeholder="Nickname" class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
        <input type="text" name="country" placeholder="Country" class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
        <textarea name="bio" placeholder="Bio" rows="3" class="w-full px-4 py-2 bg-gray-700 text-white rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
        
        <!-- Divider -->
        <h3 class="text-lg font-semibold mt-6 mb-2 text-white border-b border-gray-600 pb-1">🔐 Change Password</h3>
        
        <input type="password" name="old_password" placeholder="Old Password" class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
        <input type="password" name="new_password" placeholder="New Password" class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
        <input type="password" name="confirm_new_password" placeholder="Confirm New Password" class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">

        <!-- Buttons -->
        <div class="flex justify-end pt-4">
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 transition text-white font-semibold px-5 py-2 rounded-md">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

</body>
</html>

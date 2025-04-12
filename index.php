<?php
require_once('components/header.php');
require_once('components/modals/login-modal.php');
require_once('components/modals/register-modal.php');
require_once('components/modals/profile-modal.php');
?>

<main class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300 px-4">
  <div class="text-center space-y-6 max-w-2xl w-full animate-fadeIn">
    
    <!-- Main Title -->
    <h1 class="text-4xl md:text-5xl font-bold tracking-tight">
      Welcome to <span class="text-primary">Aver-Web</span>
    </h1>

    <!-- Description -->
    <p class="text-gray-500 dark:text-gray-300 text-lg leading-relaxed">
      Your personal AI assistant with memory, chat history, and advanced chat tools – all in one place.
    </p>

    <!-- CTA Button -->
    <a href="/chat.php"
       class="inline-flex items-center justify-center bg-primary hover:bg-blue-700 transition-colors duration-300 px-6 py-3 rounded-full text-white font-semibold text-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-400 dark:focus:ring-blue-700">
      🚀 Enter AI Chat
    </a>

  </div>
</main>

<?php require_once('components/footer.php'); ?>

<!-- External Libraries -->
<script src="https://cdn.jsdelivr.net/npm/prismjs/prism.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-javascript.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-json.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Local Scripts -->
<script src="/assets/js/main.js" defer></script>

<?php
require_once('components/header.php');
require_once('components/modals/login-modal.php');
require_once('components/modals/register-modal.php');
require_once('components/modals/profile-modal.php');
?>

<main class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-gray-900 text-white px-4">
  <div class="text-center space-y-4">
    <h1 class="text-4xl md:text-5xl font-bold">
      Welcome to <span class="text-blue-500">Aver-Web</span>
    </h1>
    <p class="text-gray-300 text-lg max-w-xl mx-auto">
      Your personal AI assistant with memory, chat history, and advanced chat tools – all in one place.
    </p>
    <a href="/chat.php" class="inline-block bg-blue-600 hover:bg-blue-700 transition px-6 py-3 rounded text-white font-medium text-lg mt-4">
      Enter AI Chat
    </a>
  </div>
</main>

<?php require_once('components/footer.php'); ?>

<!-- External Scripts -->
<script src="https://cdn.jsdelivr.net/npm/prismjs/prism.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-javascript.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-json.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Local Scripts -->
<script src="/assets/js/main.js" defer></script>

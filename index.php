<?php
require_once('includes/session.php');
require_once('components/header.php');
require_once('components/modals/login-modal.php');
require_once('components/modals/register-modal.php');
require_once('components/modals/profile-modal.php');
?>

<main class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
  <div class="text-center space-y-6 max-w-2xl w-full animate-fadeIn">

    <!-- 🔥 Main Title -->
    <h1 class="text-4xl md:text-5xl font-bold tracking-tight">
      Welcome to <span class="text-primary">Aver-Web</span>
    </h1>

    <!-- 🧠 Description -->
    <p class="text-gray-500 dark:text-gray-300 text-lg leading-relaxed">
      Your personal AI assistant with memory, chat history, and advanced chat tools – all in one place.
    </p>

    <!-- 🧩 Future Section -->
    <div class="mt-12 space-y-8">
      <div class="rounded-lg bg-gray-100 dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
        <p class="text-sm text-gray-400 dark:text-gray-500 italic">
          🧪 More features coming soon — like news cards, sliders, announcements and widgets!
        </p>
      </div>
    </div>

  </div>
</main>

<?php require_once('components/footer.php'); ?>

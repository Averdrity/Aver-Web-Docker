<!-- components/footer.php -->
<footer class="bg-gray-100 dark:bg-gray-900 border-t border-gray-300 dark:border-gray-700 text-gray-800 dark:text-white px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors duration-300">

  <!-- 🌗 Theme Toggle -->
  <div class="flex items-center gap-3">
    <span class="text-sm font-semibold">🌙</span>

    <!-- Switch -->
    <label class="relative inline-flex items-center cursor-pointer">
      <input type="checkbox" id="themeToggle" class="sr-only peer">
      <div class="w-11 h-6 bg-gray-400 peer-focus:outline-none rounded-full dark:bg-gray-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
    </label>

    <span class="text-sm font-semibold">☀️</span>
  </div>

  <!-- 🧠 Footer Text -->
  <div class="text-xs text-gray-500 dark:text-gray-400 text-center sm:text-right transition-all">
    <span class="italic">“Crafted by mind, powered by memory.”</span>
    <span class="ml-1">&copy; Aver-Web <?= date('Y') ?></span>
  </div>
</footer>

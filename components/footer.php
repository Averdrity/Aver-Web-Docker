<!-- components/footer.php -->
<footer class="bg-gray-100 dark:bg-gray-900 border-t border-gray-300 dark:border-gray-700 text-gray-800 dark:text-white px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 transition-all duration-300">

<!-- 🌗 Theme Toggle -->
<div class="flex items-center gap-3">
  <span class="text-sm font-semibold">🌙</span>

  <label class="relative inline-flex items-center cursor-pointer w-11 h-6">
    <input type="checkbox" id="themeToggle" class="sr-only peer" />
    <div class="w-full h-full bg-gray-400 dark:bg-gray-600 rounded-full peer-checked:bg-blue-600 transition-colors duration-300"></div>
    <div class="absolute left-[2px] top-[2px] w-5 h-5 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full z-10"></div>
  </label>

  <span class="text-sm font-semibold">☀️</span>
</div>


  <!-- 📜 Footer Text -->
  <div class="text-xs text-gray-500 dark:text-gray-400 text-center sm:text-right leading-tight">
    <span class="italic">Crafted by mind, powered by memory.</span>
    <span class="ml-1">&copy; Aver-Web <?= date('Y') ?></span>
  </div>
</footer>

<!-- ✅ Main JS -->
<script src="/assets/js/main.js" defer></script>

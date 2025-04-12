<!-- components/modals/uploaded-files-modal.php -->
<div 
  x-show="$store.modals.showUploads"
  x-cloak
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 scale-90"
  x-transition:enter-end="opacity-100 scale-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 scale-100"
  x-transition:leave-end="opacity-0 scale-90"
  class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50"
  role="dialog" aria-modal="true" aria-labelledby="uploaded-files-modal-title"
>
  <div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-xl shadow-2xl max-w-2xl w-full p-6 relative">

    <!-- ❌ Close Button -->
    <button 
      @click="$store.modals.showUploads = false"
      class="absolute top-3 right-4 text-gray-400 dark:text-gray-500 hover:text-black dark:hover:text-white text-lg transition"
      aria-label="Close uploaded files modal"
    >
      &times;
    </button>

    <!-- 📁 Modal Title -->
    <h2 id="uploaded-files-modal-title" class="text-xl font-bold mb-4">
      📁 Uploaded Files
    </h2>

    <!-- 📂 Files List -->
    <div id="uploaded-files-list"
         class="space-y-3 max-h-[400px] overflow-y-auto text-sm bg-gray-100 dark:bg-gray-800 p-3 rounded-md border border-gray-300 dark:border-gray-700">
      <div class="text-center text-gray-400 italic">Loading...</div>
    </div>

    <!-- 🔽 Footer -->
    <div class="mt-4 flex justify-end">
      <button 
        @click="$store.modals.showUploads = false"
        class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-2 text-sm font-medium rounded-md shadow-sm"
      >
        Close
      </button>
    </div>
  </div>
</div>

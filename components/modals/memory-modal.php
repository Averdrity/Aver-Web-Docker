<!-- components/modals/memory-modal.php -->
<div 
  x-data 
  x-show="$store.modal.showMemory"
  x-cloak
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 scale-90"
  x-transition:enter-end="opacity-100 scale-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 scale-100"
  x-transition:leave-end="opacity-0 scale-90"
  class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50"
  role="dialog" aria-modal="true" aria-labelledby="memory-modal-title"
>
  <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl shadow-2xl max-w-xl w-full p-6 relative">

    <!-- ❌ Close Button -->
    <button 
      @click="$store.modal.showMemory = false"
      class="absolute top-3 right-4 text-gray-400 dark:text-gray-500 hover:text-black dark:hover:text-white text-lg transition"
      aria-label="Close memory modal"
    >
      &times;
    </button>

    <!-- 🧠 Title -->
    <h2 id="memory-modal-title" class="text-xl font-bold mb-2 flex items-center gap-2">
      🧠 Save Memory
    </h2>

    <p class="text-sm text-gray-500 dark:text-gray-300 mb-4">
      Confirm and edit the memory before saving to your vault.
    </p>

    <!-- 📥 Memory Form -->
    <form id="memoryForm" class="space-y-4">

      <!-- 🏷 Title -->
      <div>
        <label for="memoryTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
        <input 
          type="text" 
          id="memoryTitle" 
          name="title" 
          required
          class="w-full px-4 py-2 mt-1 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
        />
      </div>

      <!-- 📄 Content -->
      <div>
        <label for="memoryContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
        <textarea 
          id="memoryContent" 
          name="content" 
          rows="4" 
          required
          class="w-full px-4 py-2 mt-1 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
        ></textarea>
      </div>

      <!-- 🏷️ Tags -->
      <div>
        <label for="memoryTags" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags</label>
        <select 
          id="memoryTags" 
          name="tags"
          class="w-full px-4 py-2 mt-1 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
        >
          <option value="">Select a tag...</option>
          <option value="personal">Personal</option>
          <option value="health">Health</option>
          <option value="work">Work</option>
          <option value="tech">Tech</option>
          <option value="misc">Misc</option>
        </select>
      </div>

      <!-- ✅ Buttons -->
      <div class="flex justify-end gap-4 pt-4">
        <button 
          type="button"
          @click="$store.modal.showMemory = false"
          class="text-sm px-4 py-2 text-gray-500 dark:text-gray-300 hover:text-black dark:hover:text-white transition"
        >
          Cancel
        </button>

        <button 
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 text-sm font-medium rounded-md shadow-sm transition"
        >
          💾 Save Memory
        </button>
      </div>
    </form>
  </div>
</div>

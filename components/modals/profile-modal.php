<!-- components/modals/profile-modal.php -->
<div 
  x-show="$store.auth.showProfile" 
  x-cloak 
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 scale-90"
  x-transition:enter-end="opacity-100 scale-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 scale-100"
  x-transition:leave-end="opacity-0 scale-90"
  class="fixed inset-0 bg-black/60 backdrop-blur-sm flex justify-center items-center z-50"
  role="dialog" aria-modal="true" aria-labelledby="profile-title"
>
  <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white p-6 rounded-xl shadow-2xl w-full max-w-xl relative">

    <!-- Close Button -->
    <button 
      @click="$store.auth.closeAll()" 
      class="absolute top-3 right-3 text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white text-xl transition"
      aria-label="Close profile modal"
    >
      &times;
    </button>

    <!-- Title -->
    <h2 id="profile-title" class="text-2xl font-bold mb-6 text-center">
      Edit Profile
    </h2>

    <!-- Profile Form -->
    <form id="profileForm" class="space-y-4">

      <!-- Nickname -->
      <input 
        type="text" 
        name="nickname" 
        placeholder="Nickname" 
        class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
      >

      <!-- Country -->
      <input 
        type="text" 
        name="country" 
        placeholder="Country" 
        class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
      >

      <!-- Bio -->
      <textarea 
        name="bio" 
        placeholder="Bio" 
        rows="3"
        class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
      ></textarea>

      <!-- Divider -->
      <h3 class="text-lg font-semibold mt-6 mb-2 border-b border-gray-300 dark:border-gray-600 pb-1">
        🔐 Change Password
      </h3>

      <!-- Old Password -->
      <input 
        type="password" 
        name="old_password" 
        placeholder="Old Password" 
        class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 transition"
      >

      <!-- New Password -->
      <input 
        type="password" 
        name="new_password" 
        placeholder="New Password" 
        class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 transition"
      >

      <!-- Confirm New Password -->
      <input 
        type="password" 
        name="confirm_new_password" 
        placeholder="Confirm New Password" 
        class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 transition"
      >

      <!-- Submit Button -->
      <div class="flex justify-end pt-4">
        <button 
          type="submit" 
          class="bg-blue-600 hover:bg-blue-700 transition text-white font-semibold px-5 py-2 rounded-md shadow-sm"
        >
          Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

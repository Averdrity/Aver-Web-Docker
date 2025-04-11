<div 
  x-show="$store.auth.showProfile" 
  x-cloak 
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 scale-90"
  x-transition:enter-end="opacity-100 scale-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 scale-100"
  x-transition:leave-end="opacity-0 scale-90"
  class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50"
>
  <div class="bg-gray-800 p-6 rounded-xl shadow-lg w-full max-w-xl relative">

    <!-- Close Button -->
    <button 
      @click="$store.auth.closeAll()" 
      class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl"
    >
      &times;
    </button>

    <!-- Modal Title -->
    <h2 class="text-2xl font-bold text-white mb-6 text-center">
      Edit Profile
    </h2>

    <!-- Profile Form -->
    <form id="profileForm" class="space-y-4">

      <input 
        type="text" 
        name="nickname" 
        placeholder="Nickname" 
        class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
      >

      <input 
        type="text" 
        name="country" 
        placeholder="Country" 
        class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
      >

      <textarea 
        name="bio" 
        placeholder="Bio" 
        rows="3"
        class="w-full px-4 py-2 bg-gray-700 text-white rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-blue-600"
      ></textarea>

      <!-- Divider -->
      <h3 class="text-lg font-semibold mt-6 mb-2 text-white border-b border-gray-600 pb-1">
        🔐 Change Password
      </h3>

      <input 
        type="password" 
        name="old_password" 
        placeholder="Old Password" 
        class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
      >

      <input 
        type="password" 
        name="new_password" 
        placeholder="New Password" 
        class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
      >

      <input 
        type="password" 
        name="confirm_new_password" 
        placeholder="Confirm New Password" 
        class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
      >

      <!-- Buttons -->
      <div class="flex justify-end pt-4">
        <button 
          type="submit" 
          class="bg-blue-600 hover:bg-blue-700 transition text-white font-semibold px-5 py-2 rounded-md"
        >
          Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

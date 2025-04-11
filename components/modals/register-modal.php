<div 
  x-show="$store.auth.showRegister" 
  x-cloak 
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 scale-90"
  x-transition:enter-end="opacity-100 scale-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 scale-100"
  x-transition:leave-end="opacity-0 scale-90"
  class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50"
>
  <div class="bg-gray-800 p-6 rounded-xl shadow-lg w-full max-w-md relative">

    <!-- Close Button -->
    <button 
      @click="$store.auth.closeAll()" 
      class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl"
    >
      &times;
    </button>

    <!-- Modal Title -->
    <h2 class="text-2xl font-bold text-white mb-6 text-center">
      Create Your Account
    </h2>

    <!-- Register Form -->
    <form id="registerForm" class="space-y-4">
      <input 
        type="text" 
        name="username" 
        placeholder="Username" 
        class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" 
        required
      >

      <input 
        type="email" 
        name="email" 
        placeholder="Email" 
        class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" 
        required
      >

      <input 
        type="password" 
        name="password" 
        placeholder="Password" 
        class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" 
        required
      >

      <input 
        type="password" 
        name="confirm_password" 
        placeholder="Confirm Password" 
        class="w-full px-4 py-2 bg-gray-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" 
        required
      >

      <!-- Buttons -->
      <div class="flex justify-end pt-2">
        <button 
          type="submit" 
          class="bg-green-600 hover:bg-green-700 transition text-white font-semibold px-5 py-2 rounded-md"
        >
          Register
        </button>
      </div>
    </form>
  </div>
</div>

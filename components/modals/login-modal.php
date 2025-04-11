<div 
  x-show="$store.auth.showLogin" 
  x-cloak 
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 scale-90"
  x-transition:enter-end="opacity-100 scale-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 scale-100"
  x-transition:leave-end="opacity-0 scale-90"
  class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50"
>
  <div class="bg-gray-800 p-6 rounded-xl shadow-lg w-full max-w-sm relative">

    <!-- Close Button -->
    <button 
      @click="$store.auth.closeAll()" 
      class="absolute top-3 right-3 text-gray-400 hover:text-white text-xl"
    >
      &times;
    </button>

    <!-- Title -->
    <h2 class="text-2xl font-bold text-white mb-6 text-center">Login</h2>

    <!-- Login Form -->
    <form id="loginForm" class="space-y-4">
      <input 
        type="text" 
        name="username" 
        placeholder="Username" 
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

      <!-- Remember + Login Button -->
      <div class="flex items-center justify-between pt-2">
        <label class="text-sm flex items-center text-gray-300">
          <input 
            type="checkbox" 
            name="remember" 
            class="mr-2 accent-blue-500"
          >
          Remember Me
        </label>

        <button 
          type="submit" 
          class="bg-blue-600 hover:bg-blue-700 transition text-white font-semibold px-5 py-2 rounded-md"
        >
          Login
        </button>
      </div>
    </form>

  </div>
</div>

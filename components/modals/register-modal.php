<!-- components/modals/register-modal.php -->
<div 
  x-show="$store.auth.showRegister" 
  x-cloak 
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 scale-90"
  x-transition:enter-end="opacity-100 scale-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 scale-100"
  x-transition:leave-end="opacity-0 scale-90"
  class="fixed inset-0 bg-black/60 backdrop-blur-sm flex justify-center items-center z-50"
  role="dialog" aria-modal="true" aria-labelledby="register-title"
>
  <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white p-6 rounded-xl shadow-2xl w-full max-w-md relative">

    <!-- Close Button -->
    <button 
      @click="$store.auth.closeAll()" 
      class="absolute top-3 right-3 text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white text-xl transition"
      aria-label="Close registration modal"
    >
      &times;
    </button>

    <!-- Title -->
    <h2 id="register-title" class="text-2xl font-bold mb-6 text-center">
      Create Your Account
    </h2>

    <!-- Register Form -->
    <form id="registerForm" class="space-y-4">

      <!-- Username -->
      <input 
        type="text" 
        name="username" 
        placeholder="Username" 
        class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
        required
      >

      <!-- Email -->
      <input 
        type="email" 
        name="email" 
        placeholder="Email" 
        class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
        required
      >

      <!-- Password -->
      <input 
        type="password" 
        name="password" 
        placeholder="Password" 
        class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
        required
      >

      <!-- Confirm Password -->
      <input 
        type="password" 
        name="confirm_password" 
        placeholder="Confirm Password" 
        class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
        required
      >

      <!-- Submit Button -->
      <div class="flex justify-end pt-4">
        <button 
          type="submit" 
          class="bg-green-600 hover:bg-green-700 transition text-white font-semibold px-5 py-2 rounded-md shadow-sm"
        >
          Register
        </button>
      </div>
    </form>
  </div>
</div>

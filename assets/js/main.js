document.addEventListener('DOMContentLoaded', () => {
  // ================================
  // 🌗 Dark/Light Theme Toggle
  // ================================
  const toggle = document.getElementById('themeToggle');
  const html = document.documentElement;
  const isLoggedIn = document.body.dataset.loggedIn === 'true'; // optional

  const applyTheme = (theme) => {
    if (theme === 'dark') {
      html.classList.add('dark');
      toggle.checked = false;
    } else {
      html.classList.remove('dark');
      toggle.checked = true;
    }
  };

  let theme = localStorage.getItem('theme') || 'dark';
  applyTheme(theme);

  if (toggle) {
    toggle.addEventListener('change', async () => {
      theme = toggle.checked ? 'light' : 'dark';
      applyTheme(theme);
      localStorage.setItem('theme', theme);

      if (isLoggedIn) {
        try {
          await fetch('/includes/theme_handler.php', {
            method: 'POST',
            body: new URLSearchParams({ theme }),
          });
        } catch (err) {
          console.error('[Theme Save Error]', err);
        }
      }
    });
  }

  // ================================
  // 🔁 Reusable Form Submission Handler
  // ================================
  const sendForm = (formId, action, endpoint, successMsg = null, reloadOnSuccess = true) => {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      formData.append('action', action);

      try {
        const res = await fetch(endpoint, {
          method: 'POST',
          body: formData
        });

        const text = await res.text();
        let data;
        try {
          data = JSON.parse(text);
        } catch (err) {
          console.error('[Form JSON Parse Error]', text);
          alert("Invalid server response.");
          return;
        }

        if (data.success) {
          if (successMsg) alert(successMsg);
          if (reloadOnSuccess) location.reload();
        } else {
          alert(data.message || "Something went wrong. Try again.");
        }
      } catch (err) {
        console.error('[Form Submit Error]', err);
        alert("Could not connect to the server.");
      }
    });
  };

  // ================================
  // 🔍 Load Profile into Profile Modal Form
  // ================================
  window.loadProfile = async () => {
    try {
      const res = await fetch('/includes/profile_handler.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ action: 'getProfile' }),
      });

      const text = await res.text();
      let data;
      try {
        data = JSON.parse(text);
      } catch (err) {
        console.error('[Profile JSON Parse Error]', text);
        return;
      }

      if (data.success && data.user) {
        document.querySelector('input[name="nickname"]').value = data.user.nickname || '';
        document.querySelector('input[name="country"]').value = data.user.country || '';
        document.querySelector('textarea[name="bio"]').value = data.user.bio || '';
      } else {
        console.warn('[Profile Load Failed]', data.message);
      }
    } catch (err) {
      console.error('[Profile Fetch Error]', err);
    }
  };

  // ================================
  // 🧠 Alpine Global Modal Store
  // ================================
  document.addEventListener('alpine:init', () => {
    Alpine.store('modals', {
      showLogin: false,
      showRegister: false,
      showProfile: false
    });

    Alpine.data('modals', () => ({
      get showLogin() { return Alpine.store('modals').showLogin },
      set showLogin(val) { Alpine.store('modals').showLogin = val },

      get showRegister() { return Alpine.store('modals').showRegister },
      set showRegister(val) { Alpine.store('modals').showRegister = val },

      get showProfile() { return Alpine.store('modals').showProfile },
      set showProfile(val) {
        Alpine.store('modals').showProfile = val;
        if (val && typeof loadProfile === 'function') {
          loadProfile();
        }
      }
    }));
  });

  // ================================
  // 🧾 Bind Modal Form Submissions
  // ================================
  sendForm('loginForm', 'login', '/includes/auth_handler.php', 'Login successful!');
  sendForm('registerForm', 'register', '/includes/auth_handler.php', 'Account created!');
  sendForm('profileForm', 'saveProfile', '/includes/profile_handler.php', 'Profile updated!', false);
});

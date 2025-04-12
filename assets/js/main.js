// main.js – Aver-Web Global UI Logic 🌗🧠🛠️
// Version: 2.2.0 – Fully synced with hybrid theme, Alpine stores, and all modal/form logic

document.addEventListener('DOMContentLoaded', () => {
  // ================================
  // 🌗 Dark/Light Theme Toggle
  // ================================
    const toggle = document.getElementById('themeToggle');
    const html = document.documentElement;
    const isLoggedIn = document.body.dataset.loggedIn === 'true';
  
    const applyTheme = (theme) => {
      html.classList.toggle('dark', theme === 'dark');
      if (toggle) toggle.checked = theme === 'light';
    };
  
    const savedTheme = localStorage.getItem('theme') || 'dark';
    applyTheme(savedTheme);
  
    toggle?.addEventListener('change', async () => {
      const theme = toggle.checked ? 'light' : 'dark';
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

  // ================================
  // 🔁 Reusable Form Submit Handler
  // ================================
  const sendForm = (formId, action, endpoint, successMsg = null, reloadOnSuccess = true) => {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      formData.append('action', action);

      try {
        const res = await fetch(endpoint, { method: 'POST', body: formData });
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
  // 🧠 Load Profile into Modal
  // ================================
  window.loadProfile = async () => {
    try {
      const res = await fetch('/includes/profile_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
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
  // 🧾 Bind Modal Forms
  // ================================
  sendForm('loginForm', 'login', '/includes/auth_handler.php', 'Login successful!');
  sendForm('registerForm', 'register', '/includes/auth_handler.php', 'Account created!');
  sendForm('profileForm', 'saveProfile', '/includes/profile_handler.php', 'Profile updated!', false);
});


// ================================
// 🧠 Alpine Global Store Setup
// ================================
document.addEventListener('alpine:init', () => {
  Alpine.store('auth', {
    showLogin: false,
    showRegister: false,
    showProfile: false,
    init() {},
    openLogin() { this.closeAll(); this.showLogin = true },
    openRegister() { this.closeAll(); this.showRegister = true },
    openProfile() {
      this.closeAll();
      this.showProfile = true;
      if (typeof loadProfile === 'function') loadProfile();
    },
    closeAll() {
      this.showLogin = false;
      this.showRegister = false;
      this.showProfile = false;
    }
  });

  Alpine.store('uploads', {
    show: false,
    files: [],
    init() {
      this.load();
    },
    async load() {
      try {
        const res = await fetch('/includes/upload_handler.php', {
          method: 'POST',
          body: new URLSearchParams({ action: 'list' })
        });
        const data = await res.json();
        if (data.success) this.files = data.files;
      } catch (err) {
        console.error('[Upload List Error]', err);
      }
    },
    async deleteFile(id) {
      if (!confirm('Delete this file?')) return;
      try {
        const res = await fetch('/includes/upload_handler.php', {
          method: 'POST',
          body: new URLSearchParams({ action: 'delete', id })
        });
        const data = await res.json();
        if (data.success) this.load();
        else alert(data.message || 'Delete failed');
      } catch (err) {
        console.error('[Delete Upload Error]', err);
      }
    }
  });

  Alpine.store('modals', {
    showUploads: false,
    showMemory: false,
  });

  Alpine.store('sidebar', {
    left: true,
    right: true,
  });
});

document.addEventListener('DOMContentLoaded', () => {
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
          console.error('[Form JSON Error]', text);
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
        console.error('[Form Error]', err);
        alert("Could not connect to the server.");
      }
    });
  };

  // Load user profile into modal form
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
        console.error('[Profile Fetch Error] Invalid JSON:', text);
        return;
      }

      if (data.success && data.user) {
        document.querySelector('input[name="nickname"]').value = data.user.nickname || '';
        document.querySelector('input[name="country"]').value = data.user.country || '';
        document.querySelector('textarea[name="bio"]').value = data.user.bio || '';
      } else {
        console.warn('[Profile Load] Failed:', data.message);
      }
    } catch (err) {
      console.error('[Profile Fetch Error]', err);
    }
  };

  // Alpine modal state manager
  document.addEventListener('alpine:init', () => {
    Alpine.data('modals', () => ({
      showLogin: false,
      showRegister: false,
      showProfile: false,

      init() {
        // Watch for profile modal open and fetch data
        this.$watch('showProfile', (value) => {
          if (value && typeof loadProfile === 'function') {
            loadProfile();
          }
        });
      }
    }));
  });

  // Bind form submissions
  sendForm('loginForm', 'login', '/includes/auth_handler.php', 'Login successful!');
  sendForm('registerForm', 'register', '/includes/auth_handler.php', 'Account created!');
  sendForm('profileForm', 'saveProfile', '/includes/profile_handler.php', 'Profile updated!', false);
});

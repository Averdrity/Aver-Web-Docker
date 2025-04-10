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
        const data = JSON.parse(text);

        if (data.success) {
          if (successMsg) alert(successMsg);
          if (reloadOnSuccess) location.reload();
        } else {
          alert(data.message || "Unknown error");
        }
      } catch (err) {
        console.error('[Form Error]', err);
        alert("Something went wrong with the request.");
      }
    });
  };

  const loadProfile = async () => {
    try {
      const res = await fetch('/includes/profile_handler.php', {
        method: 'POST',
        body: new URLSearchParams({ action: 'getProfile' }),
      });

      const text = await res.text();
      let data;
      try {
        data = JSON.parse(text); // Parse the response text
      } catch (err) {
        console.error('[Profile Fetch Error] Failed to parse response', err);
        return;
      }

      if (data.success && data.user) {
        // Load profile data into form fields
        document.querySelector('input[name="nickname"]').value = data.user.nickname || '';
        document.querySelector('input[name="country"]').value = data.user.country || '';
        document.querySelector('textarea[name="bio"]').value = data.user.bio || '';
      } else {
        console.error('Error loading profile:', data.message);
      }
    } catch (err) {
      console.error('[Profile Fetch Error]', err);
    }
  };

  document.addEventListener('alpine:init', () => {
    Alpine.data('profileModal', () => ({
      open: false,
      toggle() {
        this.open = !this.open;
        if (this.open) {
          loadProfile(); // Load profile info when modal is opened
        }
      }
    }));
  });

  sendForm('profileForm', 'saveProfile', '/includes/profile_handler.php', 'Profile updated!', false);
});

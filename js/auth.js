// auth.js — toggle de visibilidad de contraseña
(function () {
  document.querySelectorAll('.toggle-pass').forEach(btn => {
    btn.addEventListener('click', () => {
      const id    = btn.dataset.target;
      const input = document.getElementById(id);
      if (!input) return;
      const isPass = input.type === 'password';
      input.type = isPass ? 'text' : 'password';
      const icon = btn.querySelector('i');
      if (icon) {
        icon.className = isPass ? 'fa-solid fa-eye-slash' : 'fa-regular fa-eye';
      }
    });
  });
})();

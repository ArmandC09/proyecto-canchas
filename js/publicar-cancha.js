// publicar-cancha.js — preview de imágenes antes de subir
(function () {
  const input   = document.getElementById('imageInput');
  const gallery = document.getElementById('previewGallery');
  if (!input || !gallery) return;

  input.addEventListener('change', () => {
    const current = gallery.querySelectorAll('.mini-thumb').length;
    const max = 6;
    const files = Array.from(input.files).slice(0, max - current);

    files.forEach(file => {
      if (!file.type.startsWith('image/')) return;
      const reader = new FileReader();
      reader.onload = e => {
        const thumb = document.createElement('div');
        thumb.className = 'mini-thumb';
        thumb.innerHTML = `
          <button type="button" onclick="this.closest('.mini-thumb').remove()" title="Eliminar">✕</button>
          <img src="${e.target.result}" alt="Vista previa">
        `;
        gallery.appendChild(thumb);
      };
      reader.readAsDataURL(file);
    });

    // Reset input para poder volver a seleccionar
    input.value = '';
  });
})();

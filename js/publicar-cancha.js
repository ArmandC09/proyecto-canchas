const imageInput = document.getElementById('imageInput');
const previewGallery = document.getElementById('previewGallery');

if (imageInput && previewGallery) {
  imageInput.addEventListener('change', (e) => {
    const files = Array.from(e.target.files || []).slice(0, 6);
    previewGallery.innerHTML = '';

    files.forEach((file) => {
      const reader = new FileReader();
      reader.onload = (evt) => {
        const item = document.createElement('div');
        item.className = 'mini-thumb';
        item.innerHTML = `
          <button type="button">✕</button>
          <img src="${evt.target.result}" alt="preview">
        `;
        item.querySelector('button').addEventListener('click', () => item.remove());
        previewGallery.appendChild(item);
      };
      reader.readAsDataURL(file);
    });
  });
}

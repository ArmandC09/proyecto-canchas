// nav.js — navbar interactivo
(function () {
  // Navbar pública
  const burger = document.getElementById('navBurger');
  const links  = document.getElementById('navLinks');
  if (burger && links) {
    burger.addEventListener('click', () => {
      burger.classList.toggle('open');
      links.classList.toggle('open');
    });
  }

  // Panel nav
  const pb = document.getElementById('panelBurger');
  const pl = document.getElementById('panelNavLinks');
  if (pb && pl) {
    pb.addEventListener('click', () => {
      pb.classList.toggle('open');
      pl.classList.toggle('open');
    });
  }

  // Marcar enlace activo por URL
  const current = window.location.pathname.split('/').pop();
  document.querySelectorAll('.navbar-links a, .panel-nav-links a').forEach(a => {
    if (a.getAttribute('href') && a.getAttribute('href').includes(current)) {
      a.classList.add('active');
    }
  });
})();

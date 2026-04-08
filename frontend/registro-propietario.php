<?php $base = ''; $php_base = '../php/'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro Propietario | AlquilaTuCancha</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../styles/global.css">
  <link rel="stylesheet" href="../styles/registro.css">
</head>
<body>
  <?php include 'includes/navbar-public.php'; ?>
  <div class="navbar-spacer"></div>

  <main class="register-page">
    <section class="register-wrap">
      <section class="register-panel">
        <div class="register-badge owner">
          <i class="fa-solid fa-house"></i>
          <i class="fa-regular fa-futbol ball"></i>
        </div>
        <h1>Registro Propietario</h1>
        <p class="register-sub">Publica tus canchas y empieza a generar ingresos</p>
        <div class="register-divider"></div>

        <?php if (!empty($_GET['error'])): ?>
          <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form class="reg-form" action="../php/registrar-propietario.php" method="POST" novalidate>
          <div class="reg-input">
            <i class="fa-solid fa-user"></i>
            <input type="text" name="nombre" placeholder="Nombre y Apellido" required autocomplete="name">
          </div>
          <div class="reg-input">
            <i class="fa-solid fa-building"></i>
            <input type="text" name="direccion" placeholder="Dirección" required>
          </div>
          <div class="reg-input">
            <i class="fa-regular fa-envelope"></i>
            <input type="email" name="correo" placeholder="Correo Electrónico" required autocomplete="email">
          </div>
          <div class="reg-input">
            <i class="fa-solid fa-phone"></i>
            <input type="tel" name="telefono" placeholder="Teléfono" required autocomplete="tel">
          </div>
          <div class="reg-input">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Contraseña" required autocomplete="new-password">
            <button type="button" class="toggle-pass" data-target="password" aria-label="Mostrar contraseña">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
          <div class="reg-input">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirmar Contraseña" required autocomplete="new-password">
            <button type="button" class="toggle-pass" data-target="confirmPassword" aria-label="Mostrar contraseña">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
          <button class="btn btn-green btn-full reg-submit" type="submit">Crear Cuenta</button>
          <p class="login-note">¿Ya tienes una cuenta? <a href="login.php">Inicia Sesión</a></p>
        </form>
      </section>
    </section>
  </main>

  <script src="../js/nav.js"></script>
  <script src="../js/auth.js"></script>
</body>
</html>

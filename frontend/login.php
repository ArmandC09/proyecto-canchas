<?php
require_once __DIR__ . '/includes/auth.php';
// UX fix: si ya está logueado, redirigir directamente a su panel
if ($session_usuario) {
    if ($session_usuario['rol'] === 'propietario') {
        header('Location: propietario/inicio-propietario.php');
    } else {
        header('Location: usuario/inicio-usuario.php');
    }
    exit;
}
$base = ''; $php_base = '../php/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ingresar | AlquilaTuCancha</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../styles/global.css">
  <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
  <?php include 'includes/navbar-public.php'; ?>
  <div class="navbar-spacer"></div>

  <main class="auth-page">
    <section class="auth-wrap">
      <div class="auth-panels">

        <!-- Panel Login -->
        <section class="auth-panel">
          <h2>Iniciar Sesión</h2>
          <div class="auth-divider"></div>

          <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
          <?php endif; ?>
          <?php if (!empty($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
          <?php endif; ?>

          <form class="login-form" action="../php/login.php" method="POST">
            <div class="field">
              <label for="correo">Correo Electrónico</label>
              <div class="input-icon">
                <i class="fa-regular fa-envelope"></i>
                <input type="email" id="correo" name="correo" placeholder="tu@correo.com" required autocomplete="email">
              </div>
            </div>
            <div class="field">
              <label for="password">Contraseña</label>
              <div class="input-icon">
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Tu contraseña" required autocomplete="current-password">
                <button type="button" class="toggle-pass" data-target="password" aria-label="Mostrar contraseña">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
            <button class="btn btn-green btn-full btn-lg" type="submit">Iniciar Sesión</button>
            <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
          </form>
        </section>

        <!-- Panel Registro -->
        <section class="auth-panel auth-panel-register">
          <h2>¡Regístrate Ahora!</h2>
          <div class="auth-divider"></div>
          <div class="register-cards">
            <article class="reg-card user">
              <h3>Usuario</h3>
              <div class="line"></div>
              <div class="reg-icon"><i class="fa-solid fa-user"></i></div>
              <p>Busca y reserva canchas <strong>GRATIS</strong></p>
              <a href="registro-usuario.php" class="btn btn-light btn-full">Registrarse como Usuario</a>
            </article>
            <article class="reg-card owner">
              <h3>Propietario</h3>
              <div class="line"></div>
              <div class="reg-icon">
                <i class="fa-solid fa-house"></i>
                <i class="fa-regular fa-futbol ball"></i>
              </div>
              <p>Publica canchas con <strong>PLAN MENSUAL</strong></p>
              <a href="registro-propietario.php" class="btn btn-light btn-full">Registrarse como Propietario</a>
            </article>
          </div>
        </section>

      </div>
    </section>
  </main>

  <script src="../js/nav.js"></script>
  <script src="../js/auth.js"></script>
</body>
</html>

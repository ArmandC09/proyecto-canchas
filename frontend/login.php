<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Alquila tu Cancha</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../styles/global.css">
  <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
  <main class="auth-page">
    <nav class="glass-top-nav">
      <a href="index.php">Inicio</a>
      <a href="buscar-cancha.php" class="active">Buscar Cancha</a>
    </nav>

    <section class="auth-wrap">
      <div class="auth-panels">
        <section class="auth-panel">
          <h2>Iniciar Sesión</h2>
          <div class="auth-divider"></div>

          <form class="login-form" action="../php/login.php" method="POST">
            <div class="field">
              <label for="correo">Correo Electrónico</label>
              <div class="input-icon">
                <i class="fa-regular fa-envelope"></i>
                <input type="email" id="correo" name="correo" placeholder="Ingresa tu correo" required>
              </div>
            </div>

            <div class="field">
              <label for="password">Contraseña</label>
              <div class="input-icon">
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                <button type="button" class="toggle-pass" data-target="password"><i class="fa-regular fa-eye"></i></button>
              </div>
            </div>

            <button class="btn btn-green full-btn" type="submit">Iniciar Sesión</button>
            <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
          </form>
        </section>

        <section class="auth-panel">
          <h2>¡Regístrate Ahora!</h2>
          <div class="auth-divider"></div>

          <div class="register-cards">
            <article class="reg-card user">
              <h3>Registro Usuario</h3>
              <div class="line"></div>
              <div class="reg-icon"><i class="fa-solid fa-user"></i></div>
              <p>Para buscar y reservar canchas <strong>GRATIS</strong></p>
              <a href="registro-usuario.php" class="btn btn-light">Registrarse Como Usuario</a>
            </article>

            <article class="reg-card owner">
              <h3>Registro Propietario</h3>
              <div class="line"></div>
              <div class="reg-icon"><i class="fa-solid fa-house"></i><i class="fa-regular fa-futbol ball"></i></div>
              <p>Para publicar tus canchas <strong>PLAN MENSUAL</strong></p>
              <a href="registro-propietario.php" class="btn btn-light">Registrarse Como Propietario</a>
            </article>
          </div>
        </section>
      </div>
    </section>
  </main>
  <script src="../js/auth.js"></script>
</body>
</html>

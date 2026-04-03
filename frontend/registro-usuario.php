<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro Usuario</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../styles/global.css">
  <link rel="stylesheet" href="../styles/registro.css">
</head>
<body>
  <main class="register-page">
    <nav class="glass-top-nav">
      <a href="index.php">Inicio</a>
      <a href="buscar-cancha.php" class="active">Buscar Cancha</a>
    </nav>

    <section class="register-wrap">
      <section class="register-panel">
        <div class="register-badge user"><i class="fa-solid fa-user"></i></div>
        <h1>Registro Usuario</h1>
        <div class="register-divider"></div>

        <form class="reg-form" action="../php/registrar-usuario.php" method="POST">
          <div class="reg-input"><i class="fa-solid fa-user"></i><input type="text" name="nombre" placeholder="Nombre y Apellido" required></div>
          <div class="reg-input"><i class="fa-regular fa-envelope"></i><input type="email" name="correo" placeholder="Correo Electrónico" required></div>
          <div class="reg-input"><i class="fa-solid fa-phone"></i><input type="tel" name="telefono" placeholder="Teléfono" required></div>
          <div class="reg-input"><i class="fa-solid fa-lock"></i><input type="password" id="password" name="password" placeholder="Contraseña" required><button type="button" class="toggle-pass" data-target="password"><i class="fa-regular fa-eye"></i></button></div>
          <div class="reg-input"><i class="fa-solid fa-lock"></i><input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirmar Contraseña" required><button type="button" class="toggle-pass" data-target="confirmPassword"><i class="fa-regular fa-eye"></i></button></div>
          <button class="btn btn-green reg-submit" type="submit">Crear Cuenta</button>
          <p class="login-note">¿Ya tienes una cuenta? <a href="login.php">Inicia Sesión</a></p>
        </form>
      </section>
    </section>
  </main>
  <script src="../js/auth.js"></script>
</body>
</html>

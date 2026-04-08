-- ============================================================
--  AlquilaTuCancha — Base de datos completa
--  Versión: 2.0 (compatible AWS / local)
--  Charset: utf8mb4 / utf8mb4_unicode_ci
-- ============================================================

CREATE DATABASE IF NOT EXISTS alquilatucancha_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE alquilatucancha_db;

-- =========================
-- USUARIOS
-- =========================
CREATE TABLE IF NOT EXISTS usuarios (
  id            INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  nombre        VARCHAR(120)    NOT NULL,
  correo        VARCHAR(150)    NOT NULL UNIQUE,
  telefono      VARCHAR(30),
  password_hash VARCHAR(255)    NOT NULL,
  rol           ENUM('usuario','propietario') NOT NULL,
  creado_en     TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- PROPIETARIOS
-- =========================
CREATE TABLE IF NOT EXISTS propietarios (
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id           INT UNSIGNED NOT NULL UNIQUE,
  direccion_referencia VARCHAR(180),
  creado_en            TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_prop_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- DEPORTES
-- =========================
CREATE TABLE IF NOT EXISTS deportes (
  id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos base de deportes
INSERT IGNORE INTO deportes (nombre) VALUES
  ('Fútbol'),
  ('Básquet'),
  ('Vóley'),
  ('Tenis'),
  ('Pádel'),
  ('Béisbol'),
  ('Futsal');

-- =========================
-- CANCHAS
-- =========================
-- NOTA: imagen_url guarda la ruta relativa a la raíz del proyecto,
-- por ejemplo: "uploads/canchas/cancha_abc123.jpg"
-- La URL pública completa se construye en PHP con imagen_url() (helpers.php)
-- usando la variable de entorno APP_URL en producción.
-- =========================
CREATE TABLE IF NOT EXISTS canchas (
  id               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  propietario_id   INT UNSIGNED    NOT NULL,
  nombre           VARCHAR(150)    NOT NULL,
  direccion        VARCHAR(200)    NOT NULL,
  precio_por_hora  DECIMAL(10,2)   NOT NULL,
  descripcion      TEXT,
  imagen_url       VARCHAR(255),          -- ruta relativa: "uploads/canchas/xxx.jpg"
  tipo_superficie  VARCHAR(100),          -- grass, cemento, sintético, etc.
  estado           ENUM('disponible','no_disponible','mantenimiento') DEFAULT 'disponible',
  creado_en        TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_cancha_propietario
    FOREIGN KEY (propietario_id) REFERENCES propietarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- RELACIÓN CANCHA — DEPORTE (N:M)
-- =========================
CREATE TABLE IF NOT EXISTS cancha_deporte (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cancha_id  INT UNSIGNED NOT NULL,
  deporte_id INT UNSIGNED NOT NULL,
  CONSTRAINT fk_cd_cancha
    FOREIGN KEY (cancha_id)  REFERENCES canchas(id)  ON DELETE CASCADE,
  CONSTRAINT fk_cd_deporte
    FOREIGN KEY (deporte_id) REFERENCES deportes(id) ON DELETE CASCADE,
  UNIQUE KEY uq_cancha_deporte (cancha_id, deporte_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- HORARIOS DISPONIBLES
-- =========================
CREATE TABLE IF NOT EXISTS horarios (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cancha_id   INT UNSIGNED NOT NULL,
  dia_semana  ENUM('lunes','martes','miercoles','jueves','viernes','sabado','domingo') NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fin    TIME NOT NULL,
  CONSTRAINT fk_horario_cancha
    FOREIGN KEY (cancha_id) REFERENCES canchas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- RESERVAS
-- =========================
CREATE TABLE IF NOT EXISTS reservas (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id  INT UNSIGNED NOT NULL,
  cancha_id   INT UNSIGNED NOT NULL,
  fecha       DATE         NOT NULL,
  hora_inicio TIME         NOT NULL,
  hora_fin    TIME         NOT NULL,
  estado      ENUM('pendiente','confirmada','cancelada','completada') DEFAULT 'pendiente',
  creado_en   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reserva_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  CONSTRAINT fk_reserva_cancha
    FOREIGN KEY (cancha_id)  REFERENCES canchas(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- PAGOS
-- =========================
CREATE TABLE IF NOT EXISTS pagos (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reserva_id  INT UNSIGNED NOT NULL,
  monto       DECIMAL(10,2) NOT NULL,
  metodo_pago VARCHAR(50),                 -- yape, plin, tarjeta, efectivo, etc.
  estado      ENUM('pendiente','pagado','rechazado') DEFAULT 'pendiente',
  fecha_pago  TIMESTAMP    NULL,
  CONSTRAINT fk_pago_reserva
    FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

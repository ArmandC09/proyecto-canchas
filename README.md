# AlquilaTuCancha 🏟️

Sistema de reservas de canchas deportivas. Construido en PHP + MySQL.

## Estructura del Proyecto

```
proyecto-canchas/
├── frontend/           — Páginas PHP de la interfaz
│   ├── index.php       — Página de inicio (redirige según sesión)
│   ├── login.php       — Login (redirige si ya estás logueado)
│   ├── buscar-cancha.php
│   ├── detalle-cancha.php
│   ├── registro-usuario.php
│   ├── registro-propietario.php
│   ├── includes/
│   │   ├── auth.php        — Gestión de sesión
│   │   ├── navbar-public.php
│   │   └── navbar-panel.php
│   ├── propietario/
│   │   ├── inicio-propietario.php
│   │   ├── mis-canchas.php
│   │   ├── publicar-cancha.php
│   │   └── gestion-reservas.php
│   └── usuario/
│       ├── inicio-usuario.php
│       └── mis-reservas.php
├── php/                — Backend (lógica de negocio)
│   ├── conexion.php
│   ├── auth_helpers.php
│   ├── login.php
│   ├── logout.php
│   ├── registrar-usuario.php
│   ├── registrar-propietario.php
│   ├── guardar-cancha.php
│   ├── eliminar-cancha.php
│   ├── reservar-cancha.php
│   ├── confirmar-reserva.php
│   ├── cancelar-reserva.php
│   ├── bloquear-fecha.php
│   ├── listar-canchas.php
│   ├── listar-reservas-usuario.php
│   ├── listar-reservas-propietario.php
│   └── obtener-cancha.php
├── styles/             — CSS por página
├── js/                 — JavaScript
├── uploads/            — Imágenes subidas (canchas)
├── imagenes/           — Imágenes estáticas
├── database/           — Script SQL
└── GUIA-AWS.md         — Guía de despliegue en AWS
```

## Configuración Local

1. Instalar XAMPP/WAMP/Laragon
2. Importar `database/alquilatucancha_db.sql`
3. Editar `php/conexion.php` con tus credenciales de BD
4. Acceder a `http://localhost/proyecto-canchas/frontend/`

## Variables de Entorno (para producción)

```
DB_HOST=localhost
DB_NAME=alquilatucancha_db
DB_USER=tu_usuario
DB_PASS=tu_contraseña
```

## Flujo de la Aplicación

**Propietario:** Registro → Login → Publicar Cancha → Gestionar Reservas

**Cliente:** Registro → Login → Buscar Cancha → Ver Detalle → Reservar

## Correcciones UX Aplicadas

- ✅ Si el usuario está logueado y va a `/index.php`, redirige a su panel automáticamente
- ✅ Si el usuario está logueado y va a `/login.php`, redirige a su panel (no pide login de nuevo)
- ✅ Slots de horarios calculados dinámicamente por fecha
- ✅ Validación de conflictos de horario antes de reservar
- ✅ Mensajes de éxito/error en todas las acciones
- ✅ Botón reservar deshabilitado si no hay horarios disponibles

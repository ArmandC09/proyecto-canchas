# 🚀 Guía de Despliegue en AWS (Capa Gratuita)

## Arquitectura recomendada (100% Free Tier)
- **EC2 t2.micro** — servidor web con Apache + PHP
- **RDS db.t3.micro MySQL** — base de datos (o MySQL en el mismo EC2 para ahorrar)
- **S3** — para imágenes (opcional, más adelante)

---

## OPCIÓN A: Todo en un EC2 (más simple, recomendada para empezar)

### Paso 1 — Crear instancia EC2

1. Ir a **AWS Console → EC2 → Launch Instance**
2. Nombre: `alquilatucancha-server`
3. AMI: **Ubuntu Server 22.04 LTS** (Free Tier eligible ✓)
4. Instance type: **t2.micro** (Free Tier ✓)
5. Key pair: crear uno nuevo, descargarlo (`.pem`)
6. Security Group — abrir puertos:
   - **22** (SSH) — solo tu IP
   - **80** (HTTP) — 0.0.0.0/0
   - **443** (HTTPS) — 0.0.0.0/0
7. Storage: 8 GB gp2 (Free Tier ✓)
8. Click **Launch Instance**

---

### Paso 2 — Conectar por SSH

```bash
chmod 400 tu-key.pem
ssh -i tu-key.pem ubuntu@<IP-PUBLICA-EC2>
```

---

### Paso 3 — Instalar LAMP Stack

```bash
sudo apt update && sudo apt upgrade -y

# Apache
sudo apt install -y apache2

# PHP 8.2
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl libapache2-mod-php8.2

# MySQL
sudo apt install -y mysql-server

# Activar módulos Apache
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

### Paso 4 — Configurar MySQL

```bash
sudo mysql_secure_installation
# (seguir las instrucciones, crear contraseña root)

sudo mysql -u root -p
```

Dentro de MySQL:
```sql
CREATE DATABASE alquilatucancha_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'canchas_user'@'localhost' IDENTIFIED BY 'TuPasswordSeguro123!';
GRANT ALL PRIVILEGES ON alquilatucancha_db.* TO 'canchas_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Importar el esquema:
```bash
sudo mysql -u canchas_user -p alquilatucancha_db < /var/www/html/database/alquilatucancha_db.sql
```

---

### Paso 5 — Subir el proyecto

**Desde tu máquina local:**
```bash
# Comprimir el proyecto (sin .git)
zip -r proyecto-canchas.zip proyecto-canchas/ --exclude "*.git*"

# Subir al servidor
scp -i tu-key.pem proyecto-canchas.zip ubuntu@<IP-EC2>:/tmp/
```

**En el servidor:**
```bash
cd /var/www/html
sudo rm -rf *  # limpiar default
sudo unzip /tmp/proyecto-canchas.zip
sudo mv proyecto-canchas/* .
sudo rm -rf proyecto-canchas/ /tmp/proyecto-canchas.zip

# Permisos
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 775 /var/www/html/uploads
```

---

### Paso 6 — Configurar Apache

```bash
sudo nano /etc/apache2/sites-available/000-default.conf
```

Contenido:
```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

```bash
sudo systemctl restart apache2
```

---

### Paso 7 — Configurar credenciales de BD en el proyecto

```bash
sudo nano /var/www/html/php/conexion.php
```

Cambiar:
```php
define('DB_USER', 'canchas_user');
define('DB_PASS', 'TuPasswordSeguro123!');
```

O mejor, usar variables de entorno en `/etc/apache2/envvars`:
```bash
sudo nano /etc/apache2/envvars
```
Agregar al final:
```bash
export DB_HOST=localhost
export DB_NAME=alquilatucancha_db
export DB_USER=canchas_user
export DB_PASS=TuPasswordSeguro123!
```
```bash
sudo systemctl restart apache2
```

---

### Paso 8 — Configurar la URL de redirección del login

En `php/auth_helpers.php`, las rutas son relativas. Si el sitio estará en la raíz (`http://tu-ip/`), todo debería funcionar. Si estará en una subcarpeta, actualiza las rutas en `redirect_to(...)`.

---

### Paso 9 — Verificar

Abrir en el navegador: `http://<IP-PUBLICA-EC2>/frontend/index.php`

O si configuraste DirectoryIndex:  `http://<IP-PUBLICA-EC2>/`

---

## OPCIÓN B: Con RDS (base de datos separada)

Si prefieres más robustez, usa **RDS db.t3.micro** (también en Free Tier):

1. AWS Console → RDS → Create Database
2. Engine: MySQL 8.0
3. Template: **Free Tier**
4. Credenciales: anótalas bien
5. En Security Group del RDS, abrir puerto **3306** solo desde el Security Group del EC2
6. En `conexion.php` cambiar `DB_HOST` por el **endpoint** de RDS

---

## HTTPS con Let's Encrypt (gratis)

Primero apunta un dominio a la IP de tu EC2 (puedes usar Route 53 o cualquier registrador).

```bash
sudo apt install -y certbot python3-certbot-apache
sudo certbot --apache -d tudominio.com -d www.tudominio.com
```

Certbot configura HTTPS automáticamente. Se renueva solo.

---

## IP Elástica (recomendado)

Para que tu IP no cambie al reiniciar la instancia:

AWS Console → EC2 → **Elastic IPs** → Allocate → Associate a tu instancia.

---

## Backup automático de BD

```bash
# Crear script de backup
sudo nano /home/ubuntu/backup-bd.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M)
mysqldump -u canchas_user -pTuPasswordSeguro123! alquilatucancha_db > /home/ubuntu/backups/bd_$DATE.sql
# Mantener solo últimos 7 backups
ls -t /home/ubuntu/backups/bd_*.sql | tail -n +8 | xargs rm -f
```

```bash
mkdir -p /home/ubuntu/backups
chmod +x /home/ubuntu/backup-bd.sh
crontab -e
# Agregar: 0 2 * * * /home/ubuntu/backup-bd.sh
```

---

## Comandos útiles de mantenimiento

```bash
# Ver logs de errores PHP/Apache
sudo tail -f /var/log/apache2/error.log

# Reiniciar servicios
sudo systemctl restart apache2
sudo systemctl restart mysql

# Estado de servicios
sudo systemctl status apache2 mysql

# Ver uso de disco
df -h
```

---

## Resumen de costos (Free Tier 12 meses)

| Servicio | Free Tier | Costo extra |
|---|---|---|
| EC2 t2.micro | 750 hrs/mes gratis | $0 |
| EBS 8GB | 30GB gratis | $0 |
| RDS db.t3.micro | 750 hrs/mes gratis | $0 |
| Transferencia datos | 15 GB salida gratis | $0 |
| **Total estimado** | | **$0/mes** |

> ⚠️ Después de 12 meses, EC2 t2.micro cuesta ~$8.5/mes y RDS ~$13/mes.
> Alternativa económica post-free tier: usar **Lightsail** ($3.5/mes incluye todo).

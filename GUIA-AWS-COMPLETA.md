# 🚀 Guía de Despliegue en AWS — AlquilaTuCancha
## Arquitectura con 3 servicios gratuitos (Free Tier)

```
┌──────────────────────────────────────────────────────┐
│                     NAVEGADOR                        │
└────────────────────┬─────────────────────────────────┘
                     │ HTTP  http://<tu-ip>/
                     ▼
┌──────────────────────────────────────────────────────┐
│   EC2 t2.micro  (Código PHP + Apache)                │
│   Ubuntu 22.04 — /var/www/html/                      │
│   Free: 750 hrs/mes                                  │
└──────────┬───────────────────────┬───────────────────┘
           │ MySQL TCP 3306        │ HTTPS upload
           ▼                       ▼
┌─────────────────────┐  ┌────────────────────────────┐
│  RDS db.t3.micro    │  │  S3 Bucket                 │
│  MySQL 8.0          │  │  Imágenes de canchas       │
│  Free: 750 hrs/mes  │  │  5 GB gratis               │
└─────────────────────┘  └────────────────────────────┘
```

---

## PARTE 1 — Crear el Bucket S3 (imágenes)

### 1.1 Crear el bucket

1. Ir a **AWS Console → S3 → Create bucket**
2. **Bucket name**: `alquilatucancha-imagenes` *(nombre único global)*
3. **AWS Region**: `us-east-1` (N. Virginia) — usa siempre la misma región para todo
4. **Object Ownership**: marcar `ACLs enabled` → `Bucket owner preferred`
5. **Block Public Access**: desmarcar **"Block all public access"** y confirmar el aviso
6. Dejar todo lo demás por defecto → **Create bucket**

### 1.2 Configurar política pública para lectura

1. Entrar al bucket → pestaña **Permissions**
2. Ir a **Bucket policy** → **Edit** → pegar esto (cambia el nombre si lo cambiaste):

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "PublicReadGetObject",
      "Effect": "Allow",
      "Principal": "*",
      "Action": "s3:GetObject",
      "Resource": "arn:aws:s3:::alquilatucancha-imagenes/*"
    }
  ]
}
```

3. **Save changes** ✅

Esto permite que cualquiera pueda **ver** las imágenes (para mostrarlas en la web),
pero solo tu servidor puede **subirlas**.

### 1.3 Crear usuario IAM para subir desde PHP

1. Ir a **IAM → Users → Create user**
2. Nombre: `canchas-s3-uploader`
3. **Attach policies directly** → buscar y marcar `AmazonS3FullAccess` *(o una política personalizada más restrictiva)*
4. **Create user**
5. Entrar al usuario → pestaña **Security credentials** → **Create access key**
6. Uso: `Application running outside AWS`
7. **Guardar el Access Key ID y Secret** — solo se muestran una vez ⚠️

---

## PARTE 2 — Crear la base de datos RDS

### 2.1 Crear la instancia

1. Ir a **RDS → Create database**
2. **Standard create**
3. Engine: **MySQL** → versión **8.0**
4. Template: **Free Tier** ← importante
5. DB instance identifier: `alquilatucancha-db`
6. Master username: `admin`
7. Master password: algo seguro, anótalo
8. **DB instance class**: `db.t3.micro` (Free Tier ✓)
9. Storage: `20 GB gp2` (Free Tier ✓)
10. **Connectivity**:
    - VPC: default
    - Public access: **No** (el EC2 se conecta por red interna, más seguro)
    - VPC security group: crear uno nuevo → nombre `rds-canchas-sg`
11. **Create database** — tardará 5-10 min

### 2.2 Anotar el endpoint

Cuando el estado sea `Available`, ir a la instancia y copiar el **Endpoint**:
```
alquilatucancha-db.xxxxxxxxx.us-east-1.rds.amazonaws.com
```
Lo necesitarás en el paso de configuración del EC2.

### 2.3 Abrir el puerto MySQL desde EC2

1. Ir a **EC2 → Security Groups** → buscar el SG del EC2 (se crea en el siguiente paso)
2. Después de crear el EC2, volver aquí
3. Editar el Security Group `rds-canchas-sg`
4. **Inbound rules → Add rule**:
   - Type: `MySQL/Aurora`
   - Port: `3306`
   - Source: *el Security Group del EC2* (escribe `sg-` y selecciónalo)
5. **Save rules** ✅

---

## PARTE 3 — Crear el servidor EC2

### 3.1 Lanzar la instancia

1. **EC2 → Launch Instance**
2. Nombre: `alquilatucancha-server`
3. AMI: **Ubuntu Server 22.04 LTS** (Free Tier eligible ✓)
4. Instance type: **t2.micro** (Free Tier ✓)
5. Key pair: **Create new key pair**
   - Nombre: `canchas-key`
   - Type: RSA / .pem
   - **Descargar y guardar bien** — no se puede recuperar ⚠️
6. **Security Group** → Create new:
   - Nombre: `ec2-canchas-sg`
   - Inbound:
     - `SSH` — Port 22 — My IP *(solo tú puedes conectarte)*
     - `HTTP` — Port 80 — 0.0.0.0/0
7. Storage: 8 GB gp2 (Free Tier ✓)
8. **Launch Instance**

### 3.2 IP Elástica (para que la IP no cambie al reiniciar)

1. **EC2 → Elastic IPs → Allocate Elastic IP address**
2. **Allocate**
3. Seleccionar la nueva IP → **Actions → Associate Elastic IP address**
4. Seleccionar tu instancia → **Associate**

Copia la IP pública — esta será tu URL de acceso: `http://<elastic-ip>/`

### 3.3 Conectarse por SSH

```bash
# En tu computadora local
chmod 400 canchas-key.pem

ssh -i canchas-key.pem ubuntu@<ELASTIC-IP>
```

---

## PARTE 4 — Configurar el servidor EC2

### 4.1 Instalar LAMP Stack

```bash
sudo apt update && sudo apt upgrade -y

# Apache
sudo apt install -y apache2

# PHP 8.2
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-mysql php8.2-mbstring php8.2-xml \
     php8.2-curl php8.2-fileinfo libapache2-mod-php8.2

# Activar mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Composer (para el SDK de AWS S3)
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 4.2 Configurar Apache

```bash
sudo nano /etc/apache2/sites-available/000-default.conf
```

Pegar esto:
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

### 4.3 Configurar variables de entorno

```bash
sudo nano /etc/apache2/envvars
```

Agregar al **final** del archivo:
```bash
# Base de datos (RDS)
export DB_HOST=alquilatucancha-db.xxxxxxxxx.us-east-1.rds.amazonaws.com
export DB_NAME=alquilatucancha_db
export DB_USER=admin
export DB_PASS=TuPasswordSeguro123!

# S3 (imágenes)
export AWS_S3_BUCKET=alquilatucancha-imagenes
export AWS_S3_REGION=us-east-1
export AWS_ACCESS_KEY=AKIA...
export AWS_SECRET_KEY=tu-secret-key

# URL base del sitio (para imagen_url())
export APP_URL=http://<ELASTIC-IP>
```

```bash
sudo systemctl restart apache2
```

---

## PARTE 5 — Subir el proyecto

### 5.1 Desde tu computadora local

```bash
# Comprimir sin .git
zip -r proyecto-canchas.zip proyecto-canchas/ --exclude "*.git*"

# Subir al servidor
scp -i canchas-key.pem proyecto-canchas.zip ubuntu@<ELASTIC-IP>:/tmp/
```

### 5.2 En el servidor

```bash
cd /var/www/html
sudo rm -f index.html   # quitar página por defecto de Apache

# Descomprimir en /var/www/html
sudo unzip /tmp/proyecto-canchas.zip
sudo mv proyecto-canchas/* .
sudo mv proyecto-canchas/.htaccess .   # mover también los archivos ocultos
sudo rm -rf proyecto-canchas/ /tmp/proyecto-canchas.zip

# Instalar SDK de AWS para PHP (S3)
cd /var/www/html
sudo composer require aws/aws-sdk-php

# Permisos
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
sudo mkdir -p /var/www/html/uploads/canchas
sudo chmod -R 775 /var/www/html/uploads
```

### 5.3 Importar la base de datos en RDS

```bash
# Conectarse a MySQL en RDS desde el EC2
mysql -h <ENDPOINT-RDS> -u admin -p

# Dentro de MySQL:
CREATE DATABASE IF NOT EXISTS alquilatucancha_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Importar el schema
mysql -h <ENDPOINT-RDS> -u admin -p alquilatucancha_db < /var/www/html/database/alquilatucancha_db.sql
```

---

## PARTE 6 — Verificar

Abrir en el navegador:
```
http://<ELASTIC-IP>/
```

Esto carga `index.php` en la raíz, que redirige automáticamente a `frontend/index.php`.

**Si algo falla, ver los logs:**
```bash
sudo tail -f /var/log/apache2/error.log
```

---

## Resumen de la arquitectura final

| Servicio | Qué hace | URL/Endpoint |
|---|---|---|
| **EC2 t2.micro** | Servidor Apache + PHP + código fuente | `http://<elastic-ip>/` |
| **RDS db.t3.micro** | Base de datos MySQL (usuarios, canchas, reservas) | `*.rds.amazonaws.com:3306` |
| **S3** | Imágenes de canchas (acceso público de lectura) | `https://alquilatucancha-imagenes.s3.amazonaws.com/canchas/...` |

## Costos — Free Tier (primeros 12 meses)

| Servicio | Límite gratuito | Costo |
|---|---|---|
| EC2 t2.micro | 750 hrs/mes | $0 |
| RDS db.t3.micro | 750 hrs/mes + 20 GB | $0 |
| S3 | 5 GB + 20K GETs + 2K PUTs/mes | $0 |
| Transferencia | 15 GB salida/mes | $0 |
| **Total** | | **$0/mes** |

> ⚠️ Después de 12 meses: EC2 ~$8.5/mes + RDS ~$13/mes.
> Alternativa económica: **Lightsail** ($5/mes todo incluido con BD).

---

## Comandos útiles de mantenimiento

```bash
# Ver logs en tiempo real
sudo tail -f /var/log/apache2/error.log

# Reiniciar Apache
sudo systemctl restart apache2

# Verificar estado
sudo systemctl status apache2

# Ver uso de disco
df -h

# Ver cuántas imágenes hay en S3 (desde el EC2)
aws s3 ls s3://alquilatucancha-imagenes/canchas/ --recursive | wc -l
```

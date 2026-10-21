# Forcha Sport — E-commerce completo (Laravel + Blade)

Este repositorio contiene la aplicación **Forcha Sport** lista para producción. La aplicación principal es **Laravel** dentro de `laravel/` (tienda pública + panel de administración). El proyecto raíz contiene configuración de despliegue en Vercel (contenedor Docker + FrankenPHP).

## Arquitectura
- **Tienda pública**: `/` con catálogo, filtros, carrito, checkout y confirmación de pedido
- **Panel administrativo**: `/admin/login` protegido con autenticación y rol `admin`
- **Base de datos**: compartida entre tienda y panel (SQLite por defecto para demo, se recomienda BD externa en producción)
- **Todo funcional**: productos, pedidos, categorías, cupones, configuración, stocks, validaciones transaccionales

## URLs
- Producción (Vercel): usar el dominio asignado por Vercel (ej. `https://mi-tienda.vercel.app`)
- Local: `http://127.0.0.1:8000`

## Requisitos (desarrollo local)
- PHP >= 8.3
- Composer
- Node.js >= 20
- SQLite (o base de datos compatible con Laravel)

## Instalación local (Windows/Linux/macOS)
```sh
cd laravel

# 1) Dependencias PHP
composer install

# 2) Configuración
copy .env.example .env  # Windows
# cp .env.example .env  # Linux/macOS
php artisan key:generate

# 3) Base de datos con datos demo
# Crear archivo SQLite si no existe
# Windows (PowerShell): New-Item database/database.sqlite -ItemType File
# Linux/macOS: touch database/database.sqlite
php artisan migrate:fresh --seed

# 4) Assets
npm install
npm run build

# 5) Servidor
php artisan serve
```

## Acceso al panel
Ruta: `/admin/login`

Credenciales de demostración (solo para desarrollo):
| Campo | Valor |
|-------|-------|
| Email | `admin@forchasport.com` |
| Password | `password` |

> **Importante**: En producción **NO** uses estas credenciales. Crea tu propio administrador de forma segura.

## Crear/actualizar administrador de forma segura
Usa el comando Artisan incluido (sin escribir credenciales en código):
```sh
cd laravel
php artisan admin:create --name="Tu Nombre" --email="tu-email@dominio.com" --password="ContraseñaSegura123" --force
```
O de forma interactiva:
```sh
php artisan admin:create
```

## Configuración para Vercel (despliegue con contenedor)
El repositorio está configurado para despliegue con Docker/FrankenPHP en Vercel.

Archivos relevantes:
- `vercel.json` (raíz) - configuración para Vercel con runtime `container`
- `Dockerfile.vercel` (raíz) - build multi-stage con Node, Composer, PHP/FrankenPHP, migraciones + seeds
- `Caddyfile` (raíz) - configuración de servidor
- `laravel/.env.production.example` - plantilla de variables de entorno

### Variables de entorno (Vercel)
Configura en Vercel → Project Settings → Environment Variables (sin subir `.env`):

| Variable | Requerido | Notas |
|----------|-----------|-------|
| `APP_KEY` | Sí | Genera con `php artisan key:generate --show` |
| `APP_URL` | Sí | URL final de tu proyecto Vercel |
| `APP_ENV` | Sí | `production` |
| `APP_DEBUG` | Sí | `false` |
| `DB_CONNECTION` | Recomendado | Usa BD externa (ej. `mysql`, `pgsql`). Si no se configura, el contenedor usa SQLite copiado a `/tmp/forchasport/database.sqlite` (solo demo, no persistente entre escalados/límites) |
| `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Según BD | Para BD externa |
| `SESSION_DRIVER`, `CACHE_STORE` | Opcional | Por defecto file/sync |
| `FILESYSTEM_DISK` | Opcional | `public`. Para persistir imágenes entre despliegues usa S3/Cloudinary/R2 |
| `LOG_CHANNEL` | Opcional | `stderr` |

### Despliegue
1. Sube el repositorio a GitHub
2. Importa el proyecto en [vercel.com](https://vercel.com)
3. Vercel detectará `vercel.json` (contenedor). Configura las variables de entorno
4. Despliega
5. Crea el administrador con `admin:create` vía SSH/CLI o ajusta el seeder (nunca subas contraseñas al repo)

> **Persistencia**: SQLite en `/tmp` se pierde o no comparte entre instancias en ciertos escenarios de Vercel. Para producción real usa una base de datos gestionada externa.

## Rutas principales
| Ruta | Descripción |
|------|-------------|
| `/` | Portada |
| `/productos` | Catálogo con búsqueda/filtros |
| `/producto/{slug}` | Ficha de producto con variantes |
| `/carrito` | Carrito + cupón |
| `/checkout` | Formulario de compra |
| `/pedido/{order}` | Confirmación de pedido |
| `/admin/login` | Login administrativo |
| `/admin` | Dashboard administrativo |

## Pruebas
```sh
cd laravel
php artisan test --compact
# o
vendor/bin/phpunit
```

## Comandos útiles
```sh
php artisan migrate:fresh --seed  # Reiniciar BD con datos demo
php artisan cache:clear
php artisan config:clear
vendor/bin/pint --format agent    # Formatear código
```

## Seguridad
- Contraseñas hasheadas con bcrypt/Hash
- Autenticación por sesión con cookies HttpOnly/Secure en producción
- Middleware `auth` + `admin` protege panel
- Throttling en login y checkout
- Validación server-side en todas las operaciones
- Precios/stock validados contra BD en checkout (transaccional con locks)
- Sin registro público de administradores

## Notas
- Las imágenes de productos deben almacenarse en un sistema persistente en producción (S3/R2/Cloudinary) si se subirán desde el panel.
- Los pedidos se guardan con código único `FS-...`
- Todos los totales se calculan desde la base de datos.

# Forcha Sport — Laravel

Tienda en línea de ropa deportiva peruana (Forcha Sport) construida con **Laravel 13 + Blade + Tailwind CSS v4 + SQLite**.

> El código original en Next.js se conserva en la raíz del repositorio (`/src`, `/app`, etc.) solo como referencia histórica. El desarrollo activo ocurre en esta carpeta.

## Requisitos

- PHP >= 8.3
- Composer
- Node.js >= 20 (para compilar assets)

## Instalación

```sh
cd laravel

# 1. Dependencias PHP
composer install

# 2. Configuración
copy .env.example .env   # Windows: copy .env.example .env
php artisan key:generate

# 3. Base de datos SQLite con datos de demostración
touch database/database.sqlite   # si no existe
php artisan migrate:fresh --seed

# 4. Assets (Tailwind v4 + fuentes Barlow Condensed/Inter)
npm install
npm run build

# 5. Servidor de desarrollo
php artisan serve
```

La tienda queda en `http://127.0.0.1:8000`.

## Panel de administración

Ruta: `/admin/login`

| Campo    | Valor                |
|----------|----------------------|
| Email    | `admin@forchasport.com` |
| Password | `password`           |

Secciones: dashboard, pedidos (cambio de estado y pago), productos con variantes (talla/color/stock), categorías, cupones y configuración de la tienda (anuncio, whatsapp, envío, etc.).

## Rutas principales

| Ruta | Descripción |
|------|-------------|
| `/` | Portada (héroe, categorías, destacados, ofertas, nuevos, FAQ) |
| `/productos` | Catálogo con búsqueda, filtros, precio y orden |
| `/producto/{slug}` | Ficha de producto con variantes |
| `/carrito` | Carrito de compras + cupón |
| `/checkout` | Formulario de envío y pago |
| `/pedido/{code}` | Confirmación pública del pedido |
| `/admin/...` | Panel de administración (protegido) |

## Estructura

```
app/
  Enums/            OrderStatus, PaymentStatus, CouponType (con label/color)
  Models/           Product, ProductVariant, Category, Order, OrderItem, Coupon, StoreConfig, User
  Http/Controllers/ Store/ (público) y Admin/ (panel)
  Http/Requests/    Form Requests de Store y Admin
  Services/         CartService (sesión), OrderService (pedido transaccional con lock de stock)
database/
  migrations/       Esquema completo (soft deletes en productos/categorías)
  seeders/          18 productos, 5 categorías, 3 cupones, 4 pedidos demo, admin
resources/views/    Blade: store/ (tienda), admin/ (panel), partials/, components/
tests/Feature/      Catalog, Cart, Checkout, AdminPanel (32 tests)
```

## Convenciones

- Dinero en `decimal(10,2)`; formato con la directiva Blade `@money` → `S/ 1,234.00`.
- El carrito solo guarda IDs/cantidades en sesión; precios y stock se leen siempre de BD.
- El pedido descuenta stock con transacción + `lockForUpdate` (código `FS-YYMMDD-XXXXX`).
- Los productos usan el **slug** como clave de ruta.
- El cupón se guarda en la sesión con la clave `coupon` (independiente del carrito).
- Caché de `StoreConfig`: solo valores escalares (la caché de Laravel no deserializa objetos por defecto).

## Comandos útiles

```sh
php artisan test --compact   # 32 tests
vendor\bin\pint --format agent   # formato de código
php artisan migrate:fresh --seed  # reiniciar BD con demo
php artisan cache:clear
```

Durante el desarrollo, usa `npm run dev` en paralelo a `php artisan serve`.

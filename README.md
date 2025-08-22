# 🚚 API de Repartos - Challenge Quadminds

API REST para gestión de repartos, clientes y órdenes desarrollada en Laravel para el challenge técnico de Quadminds.

## 🚀 Instalación y Configuración

### 1. Clonar el repositorio
```bash
git clone https://github.com/gonzalolsk/qm-repartos.git
cd qm-repartos
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configurar variables de entorno
```bash
cp .env.example .env
```

Editar `.env` con tu configuración:
```env
APP_NAME="API Repartos"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qm_repartos
DB_USERNAME=root
DB_PASSWORD=

API_KEY=qm_repartos_2025_secure_key_12345
```

### 4. Generar clave de aplicación
```bash
php artisan key:generate
```

### 5. Ejecutar migraciones
```bash
php artisan migrate
```

### 6. Ejecutar seeders para vehicles y clients
```bash
php artisan db:seed
```

### 7. Iniciar servidor
```bash
php artisan serve
```

## Documentación de la API

### Swagger

La API incluye documentación completa generada con Swagger. Para acceder a ella:

1. **Generar documentación**: `php artisan l5-swagger:generate`
2. **URL de documentación**: `http://localhost:8000/api/documentation`

### 🔑 Autenticación

La API utiliza **API Key** para autenticación. Debes incluir el header `X-API-Key` en todas las peticiones.


### Endpoints Principales

#### Clientes
- `POST /api/clientes` - Alta de cliente

Content-Type: application/json
```json
{
    "codigo": "CLI0031",
    "razon_social": "Nueva Empresa S.R.L.",
    "email": "contacto@nueva.com",
    "direccion": "Av. Belgrano 999, CABA",
    "latitud": -34.6118,
    "longitud": -58.3960
}
```

#### Órdenes
- `POST /api/ordenes` - Alta de orden asociada a un cliente

Content-Type: application/json
```json
{
    "client_id": 1,
    "codigo_de_orden": "ORD0031",
    "fecha_creacion": "2025-08-20 10:00:00"
}
```
- `PATCH /api/ordenes/{id}/asignar-reparto` - Asignar una orden a un reparto

Content-Type: application/json

```json
{
    "reparto_id": 1
}
```

#### Repartos
- `POST /api/repartos` - Alta de un reparto con vehículo asignado

Content-Type: application/json

```json
{
    "codigo_de_reparto": "REP002",
    "fecha_entrega": "2025-08-22",
    "estado": "pendiente",
    "vehicle_id": 1
}
```

- `GET /api/repartos/por-fecha?fecha=2025-08-22` - Listar los repartos de un día, mostrando las órdenes y los clientes asociados.
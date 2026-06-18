# Estado del Sistema - Docker

**Fecha:** 2026-02-23  
**Estado:** ✅ Operativo

## Servicios Activos

### 1. MySQL Database
- **Contenedor:** `mysql_automatizacion`
- **Imagen:** `mysql:8.0`
- **Puerto Host:** `3307` → Puerto Container: `3306`
- **Estado:** ✅ Running
- **Base de datos:** `automatizacion`
- **Usuario:** `jesus`
- **Contraseña:** `1234jesus`

### 2. PHP-FPM + Laravel
- **Contenedor:** `php_automatizacion`
- **Imagen:** `automatizacion-php`
- **Puerto Host:** `5174` → Puerto Container: `5174` (Vite HMR)
- **Estado:** ✅ Running
- **PHP Version:** 8.3.30
- **Laravel Version:** 12.40.2
- **Supervisor:** Activo (gestiona workers de cola)

### 3. Nginx Web Server
- **Contenedor:** `nginx_automatizacion`
- **Imagen:** `nginx:latest`
- **Puerto Host:** `7654` → Puerto Container: `80`
- **Estado:** ✅ Running
- **Document Root:** `/var/www/public`

### 4. Vite Dev Server
- **Estado:** ✅ Running (dentro del contenedor PHP)
- **Puerto Host:** `5174`
- **Hot Module Replacement:** ✅ Activo
- **Laravel Vite Plugin:** v2.0.1
- **Vite Version:** 7.1.9

## URLs de Acceso

- **Aplicación Principal:** http://localhost:7654
- **Vite Dev Server:** http://localhost:5174
- **MySQL:** localhost:3307

## Configuración Docker

### docker-compose.yml
```yaml
services:
  mysql:
    - Port: 3307:3306
    - Memory Limit: 512M
    - CPU Limit: 1.0
    
  php:
    - Port: 5174:5174
    - Memory Limit: 512M
    - CPU Limit: 1.0
    - Volumes: .:/var/www:cached
    
  nginx:
    - Port: 7654:80
    - Memory Limit: 512M
    - CPU Limit: 1.0
```

### vite.config.ts
```typescript
server: {
  host: '0.0.0.0',      // Escucha en todas las interfaces
  port: 5174,
  strictPort: true,
  hmr: {
    host: 'localhost',  // HMR accesible desde el host
  },
}
```

## Comandos Útiles

### Iniciar el sistema
```bash
docker-compose up -d
docker exec -w /var/www php_automatizacion npm run dev
```

### Detener el sistema
```bash
docker-compose down
```

### Ver logs
```bash
docker-compose logs -f
docker-compose logs -f php
docker-compose logs -f nginx
docker-compose logs -f mysql
```

### Reiniciar un servicio
```bash
docker-compose restart php
docker-compose restart nginx
docker-compose restart mysql
```

### Ejecutar comandos Artisan
```bash
docker exec php_automatizacion php artisan migrate
docker exec php_automatizacion php artisan cache:clear
docker exec php_automatizacion php artisan queue:work
```

### Acceder al contenedor
```bash
docker exec -it php_automatizacion bash
docker exec -it mysql_automatizacion bash
docker exec -it nginx_automatizacion bash
```

## Verificación de Funcionamiento

✅ MySQL: Conectado y operativo  
✅ PHP-FPM: Procesando solicitudes  
✅ Nginx: Sirviendo aplicación en puerto 7654  
✅ Vite: HMR activo y conectado  
✅ Supervisor: Workers de cola ejecutándose  
✅ Laravel: Responde correctamente (HTTP 200)

## Notas

- El sistema está configurado para desarrollo con HMR (Hot Module Replacement)
- Los contenedores tienen límites de recursos configurados
- Supervisor gestiona automáticamente los workers de Laravel
- Vite está configurado para escuchar en todas las interfaces (0.0.0.0)
- El puerto de Vite (5174) está expuesto para desarrollo

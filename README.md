# CRUD Empresas - Sistema de Gestión

Sistema completo de gestión de empresas desarrollado con PHP, MySQL y Bootstrap. Incluye operaciones CRUD completas, búsqueda avanzada, validaciones del lado servidor, exportación a JSON y generación de reportes PDF.

## 🚀 Características

- **CRUD Completo**: Crear, leer, actualizar y eliminar empresas (borrado lógico)
- **Búsqueda Inteligente**: Por ID numérico o razón social (case-insensitive)
- **Validaciones Robustas**: Del lado servidor con mensajes claros
- **Exportación**: JSON con metadatos y filtros aplicados
- **Reportes PDF**: Tabla completa con estadísticas y fecha de generación
- **Interfaz Moderna**: Bootstrap 5 con diseño responsive
- **API REST**: Endpoints bien estructurados con manejo de errores
- **Seguridad**: PDO con sentencias preparadas, validación de entrada

## 📋 Requisitos

- **PHP**: 8.0 o superior
- **MySQL**: 5.7 o superior / MariaDB 10.2+
- **Servidor Web**: Apache/Nginx con mod_rewrite
- **Composer**: Para gestión de dependencias
- **Extensiones PHP**: PDO, PDO_MySQL, JSON, mbstring

## 🛠️ Instalación

### 1. Clonar el repositorio
```bash
git clone <url-del-repositorio>
cd empresa-crud
```

### 2. Instalar dependencias
```bash
composer install
```

### 3. Configurar base de datos
```bash
# Crear base de datos y tabla
mysql -u root -p < database.sql
```

### 4. Configurar variables de entorno
```bash
# Copiar archivo de configuración
cp .env.example .env

# Editar configuración
nano .env
```

Configurar las siguientes variables en `.env`:
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=empresa_db
DB_USER=root
DB_PASS=tu_password
APP_URL=http://localhost/empresa-crud/public
```

### 5. Configurar servidor web

#### Apache (.htaccess)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^api/(.*)$ api/$1 [L]
```

#### Nginx
```nginx
location /api/ {
    try_files $uri $uri/ /api/$1;
}
```

### 6. Acceder a la aplicación
```
http://localhost/empresa-crud/public
```

## 📁 Estructura del Proyecto

```
empresa-crud/
├── public/
│   ├── index.php              # Interfaz principal
│   └── api/                   # Endpoints REST
│       ├── helpers.php        # Funciones auxiliares
│       ├── empresas_list.php  # GET /api/empresas
│       ├── empresas_get.php   # GET /api/empresas/:id
│       ├── empresas_create.php # POST /api/empresas
│       ├── empresas_update.php # PUT /api/empresas/:id
│       ├── empresas_delete.php # DELETE /api/empresas/:id
│       ├── empresas_export_json.php # GET /api/empresas/export.json
│       └── empresas_report_pdf.php  # GET /api/empresas/report.pdf
├── src/
│   ├── Db/
│   │   └── Conn.php           # Conexión PDO Singleton
│   ├── Repos/
│   │   └── EmpresaRepo.php    # Repositorio de datos
│   ├── Validators/
│   │   └── Empresa.php        # Validaciones
│   └── Pdf/
│       └── EmpresaReport.php  # Generador de PDF
├── composer.json              # Dependencias
├── .env.example              # Configuración ejemplo
├── database.sql              # Script de base de datos
└── README.md                 # Documentación
```

## 🔌 API Endpoints

### Listar Empresas
```http
GET /api/empresas?search=termino
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Empresas obtenidas exitosamente",
  "data": {
    "empresas": [...],
    "total": 5,
    "search": "termino"
  }
}
```

### Obtener Empresa por ID
```http
GET /api/empresas/:id
```

### Crear Empresa
```http
POST /api/empresas
Content-Type: application/json

{
  "rif": "J-12345678-9",
  "razon_social": "Empresa Ejemplo S.A.",
  "direccion": "Av. Principal, Caracas",
  "telefono": "+58 212 123-4567"
}
```

### Actualizar Empresa
```http
PUT /api/empresas/:id
Content-Type: application/json

{
  "rif": "J-12345678-9",
  "razon_social": "Empresa Actualizada S.A.",
  "direccion": "Nueva dirección",
  "telefono": "+58 212 999-9999"
}
```

### Eliminar Empresa
```http
DELETE /api/empresas/:id
```

### Exportar JSON
```http
GET /api/empresas/export.json?search=termino
```

### Generar Reporte PDF
```http
GET /api/empresas/report.pdf?search=termino
```

## 📊 Modelo de Datos

```sql
CREATE TABLE empresa (
    id_empresa INT AUTO_INCREMENT PRIMARY KEY,
    rif VARCHAR(20) NOT NULL UNIQUE,
    razon_social VARCHAR(150) NOT NULL,
    direccion TEXT NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## ✅ Validaciones

### RIF
- **Requerido**: Sí
- **Formato**: `J-########-#` (alfanumérico)
- **Único**: No puede repetirse en la base de datos

### Razón Social
- **Requerido**: Sí
- **Longitud**: 3-150 caracteres

### Dirección
- **Requerido**: Sí
- **Longitud**: 10-500 caracteres

### Teléfono
- **Requerido**: Sí
- **Formato**: Números, guiones, paréntesis, espacios, +
- **Longitud**: 7-15 dígitos

## 🔍 Funcionalidades de Búsqueda

- **Parámetro único**: `search`
- **Búsqueda numérica**: Filtra por `id_empresa`
- **Búsqueda de texto**: Filtra por `razon_social` (case-insensitive)
- **Ordenamiento**: Por `fecha_creacion DESC`

## 📄 Exportaciones

### JSON
- Incluye metadatos (fecha, total, filtros)
- Respeta filtros de búsqueda aplicados
- Descarga directa con nombre de archivo único

### PDF
- Tabla completa de empresas
- Fecha/hora de generación
- Total de registros
- Ordenado por fecha de creación DESC
- Diseño profesional con TCPDF

## 🎨 Interfaz de Usuario

- **Bootstrap 5**: Diseño moderno y responsive
- **Búsqueda en tiempo real**: Con debounce de 500ms
- **Modales**: Para crear/editar empresas
- **Validación frontend**: Feedback inmediato
- **Estadísticas**: Total de empresas y resultados
- **Acciones**: Exportar JSON, generar PDF
- **Confirmaciones**: Para eliminación

## 🧪 Pruebas con Postman

### Colección de Pruebas
```json
{
  "info": {
    "name": "CRUD Empresas API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Listar Empresas",
      "request": {
        "method": "GET",
        "url": "{{base_url}}/api/empresas"
      }
    },
    {
      "name": "Crear Empresa",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n  \"rif\": \"J-99999999-9\",\n  \"razon_social\": \"Empresa Test S.A.\",\n  \"direccion\": \"Dirección de prueba\",\n  \"telefono\": \"+58 212 000-0000\"\n}"
        },
        "url": "{{base_url}}/api/empresas"
      }
    }
  ]
}
```

## 🚀 Despliegue en Railway

1. **Conectar repositorio** a Railway
2. **Configurar variables de entorno**:
   - `DB_HOST`: Host de la base de datos
   - `DB_NAME`: Nombre de la base de datos
   - `DB_USER`: Usuario de la base de datos
   - `DB_PASS`: Contraseña de la base de datos
3. **Ejecutar migraciones**:
   ```bash
   railway run mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME < database.sql
   ```
4. **Instalar dependencias**:
   ```bash
   railway run composer install
   ```

## 📝 Notas de Desarrollo

- **Patrón Singleton**: Para conexión a base de datos
- **PSR-4**: Autoloading de clases
- **Prepared Statements**: Protección contra SQL injection
- **Manejo de errores**: Respuestas JSON estructuradas
- **Borrado lógico**: Las empresas se marcan como inactivas
- **Validación dual**: Frontend y backend
- **Responsive design**: Compatible con dispositivos móviles

## 🐛 Solución de Problemas

### Error de conexión a base de datos
- Verificar credenciales en `.env`
- Confirmar que MySQL esté ejecutándose
- Verificar permisos de usuario

### Error 500 en API
- Revisar logs de PHP
- Verificar que todas las dependencias estén instaladas
- Confirmar configuración de servidor web

### Problemas con PDF
- Verificar que la extensión GD esté habilitada
- Confirmar permisos de escritura en directorio temporal

## 📞 Soporte

Para reportar problemas o solicitar funcionalidades, crear un issue en el repositorio.

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver archivo `LICENSE` para más detalles.

---

**Desarrollado con ❤️ usando PHP, MySQL y Bootstrap**

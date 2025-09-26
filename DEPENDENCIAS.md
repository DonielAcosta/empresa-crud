# 📦 Dependencias del Proyecto Empresa CRUD

## 🎯 Dependencias principales (composer.json)

| Paquete | Versión | Descripción |
|---------|---------|-------------|
| **php** | >=8.0 | Versión mínima de PHP requerida |
| **vlucas/phpdotenv** | ^5.5 | Carga variables de entorno desde archivo .env |
| **tecnickcom/tcpdf** | ^6.6 | Generación de documentos PDF |

## 📚 Dependencias instaladas (con subdependencias)

| Paquete | Versión | Descripción |
|---------|---------|-------------|
| **tecnickcom/tcpdf** | 6.10.0 | Generación de PDFs y códigos de barras |
| **vlucas/phpdotenv** | 5.6.2 | Carga automática de variables de entorno |
| **graham-campbell/result-type** | 1.1.3 | Implementación del tipo Result |
| **phpoption/phpoption** | 1.9.4 | Tipo Option para PHP |
| **symfony/polyfill-ctype** | 1.33.0 | Polyfill para funciones ctype |
| **symfony/polyfill-mbstring** | 1.33.0 | Polyfill para extensión Mbstring |
| **symfony/polyfill-php80** | 1.33.0 | Polyfill para características PHP 8.0 |

## 🔧 Extensiones PHP habilitadas

| Extensión | Estado | Descripción |
|-----------|--------|-------------|
| **curl** | ✅ | Cliente HTTP |
| **json** | ✅ | Manejo de JSON |
| **libxml** | ✅ | Librería XML |
| **mbstring** | ✅ | Manipulación de strings multibyte |
| **mysqli** | ✅ | Extensión MySQL mejorada |
| **mysqlnd** | ✅ | Driver nativo MySQL |
| **pdo_mysql** | ✅ | PDO para MySQL |
| **xml** | ✅ | Soporte XML |
| **xmlreader** | ✅ | Lector XML |
| **xmlwriter** | ✅ | Escritor XML |

## 🌳 Árbol de dependencias

```
tecnickcom/tcpdf 6.10.0
├── ext-curl *
└── php >=7.1.0

vlucas/phpdotenv v5.6.2
├── ext-pcre *
├── graham-campbell/result-type ^1.1.3
│   ├── php ^7.2.5 || ^8.0
│   └── phpoption/phpoption ^1.9.3
│       └── php ^7.2.5 || ^8.0
├── php ^7.2.5 || ^8.0
├── phpoption/phpoption ^1.9.3
│   └── php ^7.2.5 || ^8.0
├── symfony/polyfill-ctype ^1.24
│   └── php >=7.2
├── symfony/polyfill-mbstring ^1.24
│   ├── ext-iconv *
│   └── php >=7.2
└── symfony/polyfill-php80 ^1.24
    └── php >=7.2
```

## 📊 Resumen

- **Total de paquetes**: 7
- **Dependencias directas**: 3
- **Dependencias transitivas**: 4
- **Extensiones PHP**: 10 habilitadas
- **Versión PHP**: 8.3.25 (compatible con >=8.0)

## 🚀 Comandos útiles

### Ver todas las dependencias
```bash
composer show
```

### Ver árbol de dependencias
```bash
composer show --tree
```

### Verificar extensiones PHP
```bash
php -m | grep -E "(mysql|pdo|mbstring|curl|json|xml)"
```

### Actualizar dependencias
```bash
composer update
```

### Instalar dependencias
```bash
composer install
```

## 📝 Notas importantes

- El proyecto requiere **PHP 8.0 o superior**
- Las dependencias están optimizadas para **PHP 8.3**
- Todas las extensiones necesarias están habilitadas
- El proyecto usa **Composer** para gestión de dependencias

---
*Generado el: $(date)*
*Proyecto: Empresa CRUD*

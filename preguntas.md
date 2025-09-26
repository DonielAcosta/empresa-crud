# MVC en PHP (1 pto) Explica MVC y cómo lo aplicarías sin framework usando Composer/autoload (carpetas src/Controller, src/Model, public/index.php como front controller).Menciona PDO y vistas con templates.

es un patron de diseno que separa la logica del negocio en el modelo las interfaces que son las vistas y la estructura logica en el controlador todas las acciones, para aplicarlo sin usar framework crearia la estructura para las vistas y los controladores apartes y el modelo se puede usar helper y asi se puede llamar en cualuier lugar instanciando, si no se crewa un dirtorio de modelo y funcionaria igual un modelo para cada controlador 

##POO en PHP 8 (1 pto) Define POO y muestra una clase con constructor con property promotion, tipos estrictos, y método que retorna array. Incluye namespace y cómo se autoload con Composer.
la POO es un paradigma donde organiza codigo en objetos, combinan datos en propiedades,  soporta clases, herencia, encapsulamiento, polimorfismo, etc.
declare(strict_types=1);
namespace App\Models;

class Product {
    public function __construct(
        private string $name,
        private float $price
    ) {}

    public function getDetails(): array {
        return [
            'name' => $this->name,
            'price' => $this->price
        ];
    }
}
un ejemplo de uso 
require 'vendor/autoload.php';
use App\Models\Product;
$product = new Product("Laptop", 100);
print_r($product->getDetails()); salida

## Scrum (1 pto) Describe roles (PO, SM, Devs), eventos (planning, daily, review, retro), artefactos (product backlog, sprint backlog, increment) y cómo medir (velocidad, burndown) en un proyecto web simple.
es una metodologia agil para gestionar proyectos es muy ideal para proyectos web por su estructura y como organiza las tareas
Roles son los siguientes

Product Owner PO Define la visión del producto prioriza el Product Backlog
Scrum Master SM Facilita el proceso, elimina bloqueos
Development Team (Devs) Equipo multifuncional que desarrolla el producto el ejemplo programadores diseñadores etc


## 4. Git (1 pto)¿Qué es Git? Explica flujo mínimo: ramas main/feature, pull request, code review, tags para releases y mensajes de commit útiles. Menciona git stash y git rebase vs merge

# Rama principal (producción)
main (o master)
    ↑
    └── feature/nueva-funcionalidad
    └── feature/correccion-bug
    └── hotfix/error-critico
    
main: Código estable en producción
feature/nombre: Nuevas funcionalidades
hotfix/nombre: Correcciones urgentes
develop: Integración (opcional)

# Crear y cambiar a nueva rama feature
git checkout -b feature/login-usuarios
git switch -c feature/login-usuarios  # (Git 2.23+)

# Trabajar en la funcionalidad
git add .
git commit -m "feat: agregar sistema de login"

# Subir rama al repositorio remoto
git push -u origin feature/login-usuarios

# Actualizar rama con cambios de main
git checkout main
git pull origin main
git checkout feature/login-usuarios
git merge main  # o git rebase main

Pull Request (PR) / Merge Request (MR)
Proceso:

Crear PR desde la rama feature hacia main
Descripción detallada del cambio
Asignar revisores del código
Revisar y discutir cambios
Aprobar y mergear tras correcciones

## Descripción
Implementa sistema de autenticación de usuarios con JWT

## Cambios realizados
- [ ] Login con email/password
- [ ] Registro de nuevos usuarios
- [ ] Middleware de autenticación
- [ ] Tests unitarios

## Como probar
1. Ejecutar `npm test`
2. Probar endpoint `/api/login`
3. Verificar token JWT generado
Code Review
Aspectos a revicar:

Funcionalidad: ¿El código hace lo que debe?
Calidad: ¿Está bien estructurado y documentado?
Seguridad: ¿Hay vulnerabilidades potenciales?
Performance: ¿Es eficiente el código?
Tests: ¿Están cubiertos los casos importantes?

# Comentarios en PR
"Considera usar prepared statements para evitar SQL injection"
"Esta función podría ser más eficiente usando array_map"
"Falta validación de entrada en este endpoint"

Tags para Releases
# Crear tag anotado para release
git tag -a v1.0.0 -m "Release versión 1.0.0 - Sistema de usuarios completo"

# Subir tag al repositorio
git push origin v1.0.0

# Listar tags existentes
git tag -l

# Ver información del tag
git show v1.0.0

# Crear release desde tag específico
git checkout v1.0.0

Convención de versionado (SemVer):

v1.0.0: Major.Minor.Patch
v1.1.0: Nueva funcionalidad (compatible)
v1.0.1: Corrección de bugs
v2.0.0: Cambios incompatibles

 Mensajes de Commit Útiles
Formato recomendado (Conventional Commits):

tipo(ámbito): descripción breve

# Ejemplos buenos:
git commit -m "feat(auth): agregar login con JWT"
git commit -m "fix(api): corregir validación de email"
git commit -m "docs(readme): actualizar instrucciones de instalación"
git commit -m "refactor(models): simplificar clase Usuario"
git commit -m "test(auth): agregar tests para login"

# Ejemplos malos:
git commit -m "cambios"
git commit -m "fix bug"
git commit -m "working"

Tipos de commit:

feat: Nueva funcionalidad
fix: Corrección de bug
docs: Documentación
style: Formato (sin cambio de lógica)
refactor: Reestructuración de código
test: Agregar/modificar tests
chore: Tareas de mantenimiento

Git Stash
¿Qué es? Guarda temporalmente cambios no commitados para poder cambiar de rama sin perderlos.

# Guardar cambios actuales
git stash
git stash save "mensaje descriptivo"

# Ver lista de stashes
git stash list

# Aplicar último stash
git stash pop        # Aplica y elimina del stash
git stash apply      # Aplica pero mantiene en stash

# Aplicar stash específico
git stash pop stash@{1}

# Eliminar stash
git stash drop stash@{0}
git stash clear      # Elimina todos los stashes

Casos de uso:

Cambiar de rama con trabajo a medio hacer
Probar algo rápido sin commitear
Limpiar el working directory temporalmente

Git Rebase vs Merge
Git Merge
Conserva el historial creando un commit de merge que une dos ramas.

git checkout main
git merge feature/nueva-funcionalidad

# Resultado: historial con bifurcaciones
*   a1b2c3d (main) Merge branch 'feature/nueva-funcionalidad'
|\  
| * d4e5f6g (feature) feat: agregar nueva funcionalidad
| * h7i8j9k fix: corregir validación
|/  
* k1l2m3n commit anterior en main

Ventajas:

✅ Preserva contexto histórico completo
✅ Muestra cuándo se integraron las funcionalidades
✅ Más seguro (no reescribe historial)

Git Rebase
Reescribe el historial aplicando commits de una rama sobre otra, creando una línea temporal lineal.

git checkout feature/nueva-funcionalidad
git rebase main

# Resultado: historial lineal
* d4e5f6g (feature) feat: agregar nueva funcionalidad
* h7i8j9k fix: corregir validación  
* k1l2m3n (main) commit anterior en main
Ventajas:

✅ Historial más limpio y lineal
✅ Más fácil de leer y seguir
✅ No genera commits de merge innecesarios

Desventajas:

❌ Reescribe historial (problemático si ya está en remoto)
❌ Puede generar conflictos múltiples
❌ Pierde contexto de cuándo se integraron cambios

¿Cuándo usar cada uno?
Usar Merge cuando:

La rama ya está en repositorio remoto
Quieres preservar el contexto histórico
Trabajas en equipo y otros pueden tener la rama
Es una funcionalidad importante que merece estar marcada

Usar Rebase cuando:

La rama es local y no ha sido compartida
Quieres un historial limpio antes del merge
Son commits pequeños y experimentales
Quieres actualizar tu rama con cambios de main

Flujo Recomendado
bash# 1. Crear feature branch
git checkout -b feature/nueva-funcionalidad

# 2. Trabajar y commitear localmente
git add .
git commit -m "feat: implementar funcionalidad"

# 3. Antes de crear PR, rebase con main para historial limpio
git fetch origin
git rebase origin/main

# 4. Resolver conflictos si existen
git add .
git rebase --continue

# 5. Push de la rama (forzado si ya existía)
git push -f origin feature/nueva-funcionalidad

# 6. Crear Pull Request
# 7. Tras aprobación, merge (no rebase) en main
Esta estrategia combina lo mejor de ambos: historial limpio en features (rebase) y contexto preservado en main (merge).



## API REST con PHP “puro” (1 pto) Define API REST. Diseña brevemente un endpoint GET /api/market que retorna JSON; indica status codes, headers (Content-Type, CORS) y manejo de errores.

Una API REST es una interfaz que permite la comunicación entre sistemas usando HTTP siguiendo los principios de REST (Representational State Transfer). Se basa en recursos (URLs), usa verbos HTTP (GET, POST, PUT, DELETE) y responde en formatos estándar como JSON.
<?php
// Encabezados HTTP
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *"); // CORS abierto
header("Access-Control-Allow-Methods: GET");

// Manejo de método
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Método no permitido."]);
    exit;
}

// Datos de ejemplo (normalmente vendrían de una BD)
$market = [
    ["id" => 1, "producto" => "Manzana", "precio" => 1.2],
    ["id" => 2, "producto" => "Banana",  "precio" => 0.8],
    ["id" => 3, "producto" => "Uva",     "precio" => 2.5]
];

// Simular error (ej: sin datos)
if (empty($market)) {
    http_response_code(404); // Not Found
    echo json_encode(["error" => "No se encontraron productos"]);
    exit;
}

// Respuesta exitosa
http_response_code(200); // OK
echo json_encode(["data" => $market]);

Status codes:

200 OK → cuando devuelve productos.

404 Not Found → si no hay productos.

405 Method Not Allowed → si no se usa GET.

Headers:

Content-Type: application/json; charset=UTF-8

Access-Control-Allow-Origin: * (habilita CORS)

Access-Control-Allow-Methods: GET

Salida JSON ejemplo (200 OK):



## .HTML5/Bootstrap/jQuery (1 pto) ¿Cuál atributo HTML identifica unívocamente un elemento y no debe repetirse?¿Cómo seleccionar ese elemento con jQuery y cambiar su texto? (ejemplo corto).

Atributo HTML

El atributo que identifica unívocamente un elemento y no debe repetirse en la página es id.

Ejemplo HTML: <p id="mensaje">Texto original</p>

Selección con jQuery y cambio de texto

En jQuery, para seleccionar un id se usa $("#id").

Ejemplo: $("#mensaje").text("Texto actualizado con jQuery");

Resultado

Antes:

<p id="mensaje">Texto original</p>


Después de ejecutar jQuery:

<p id="mensaje">Texto actualizado con jQuery</p>
##JavaScript(1 pto) Dos formas de convertir string a entero y diferencias (p. ej. parseInt, Number). Agrega una validación simple antes de enviar un formulario con jQuery.

parseInt(string, base)

Convierte hasta donde pueda leer un número válido.

Permite indicar la base (10 para decimal, 16 para hexadecimal, etc.).

parseInt("42");        // 42
parseInt("42px");      // 42
parseInt("101", 2);    // 5  (interpreta en base 2)


Number(string)

Convierte todo el string, debe ser un número válido completo.

No acepta sufijos como "42px".

Number("42");     // 42
Number("42px");   // NaN


Diferencia: parseInt es más flexible (ignora caracteres no numéricos al final), mientras que Number es más estricto.

###Composer y autoload (1 pto) ¿Qué es Composer? Explica PSR-4 autoload con un composer.json mínimo y cómo cargar src/Services/Market.php automáticamente.
¿Qué es Composer?

Composer es el gestor de dependencias de PHP. Permite instalar librerías externas y manejar el autoload de clases según estándares (PSR).
es como en javascrip que su gestor es npm 

PSR-4 Autoload

PSR-4 define cómo mapear namespaces de PHP a directorios en el sistema de archivos.
Ejemplo: el namespace App\Services\Market apunta a src/Services/Market.php.

Ejemplo de composer.json mínimo
{
  "autoload": {
    "psr-4": {
      "App\\": "src/"
    }
  }
}

Estructura de archivos
project/
 ├── composer.json
 ├── vendor/
 └── src/
     └── Services/
         └── Market.php

Clase Market.php
<?php
namespace App\Services;

class Market {
    public function getData() {
        return ["producto" => "Manzana", "precio" => 1.2];
    }
}

Cómo usarla

Generar el autoload:

composer dump-autoload


Usar en tu proyecto (index.php):

<?php
require __DIR__ . '/vendor/autoload.php';

use App\Services\Market;

$market = new Market();
print_r($market->getData());

### PHP 8 + errores/seguridad (1pto)¿Cómo habilitar error reporting en desarrollo? ¿Cómo deshabilitar en producción?
En desarrollo (ver todos los errores)

En tu php.ini o al inicio del script:

// Mostrar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
Esto permite ver warnings, notices y errores fatales mientras desarrollas.

En producción (ocultar errores por seguridad)

En php.ini o al inicio:

// Registrar pero no mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php_errors.log'); // ruta al log

Así los errores se guardan en un log privado, pero no se muestran al usuario, evitando exponer información sensible (paths, queries, etc.).
### MySQL + JSON/PDF (1 pto) Específica consulta para listar registros ordenados por fecha; cómo exportar JSON (encabezados correctos) y cómo generar un PDF con Dompdf (pasos clave, no código largo
Consulta SQL (listar registros ordenados por fecha)
SELECT id, nombre, fecha
FROM clientes
ORDER BY fecha DESC;
DESC = más recientes primero (usar ASC para más antiguos).

Exportar resultados en JSON con PHP
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

echo json_encode($rows); // $rows es array de resultados MySQL

Encabezado clave: Content-Type: application/json.

Generar PDF con Dompdf (pasos clave)

Instalar:

composer require dompdf/dompdf


Incluir autoload:

require 'vendor/autoload.php';
use Dompdf\Dompdf;


Crear instancia:

$dompdf = new Dompdf();

Cargar HTML:

$dompdf->loadHtml("<h1>Reporte</h1>");

Opcional: configurar papel:

$dompdf->setPaper('A4', 'portrait');

Renderizar y enviar al navegador:

$dompdf->render();
$dompdf->stream("reporte.pdf", ["Attachment" => false]); // mostrar en navegador
Con esto:

MySQL → consulta ordenada por fecha.
JSON → usar Content-Type: application/json.
PDF con Dompdf → cargar HTML → renderizar → stream().án optimizadas para **PHP 8.3**
- Todas las extensiones necesarias están habilitadas
- El proyecto usa **Composer** para gestión de dependencias


<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuración de la base de datos
$config = [
    'host' => 'localhost',
    'port' => '3306',
    'dbname' => 'empresa_db',
    'user' => 'datasis',
    'pass' => '1q2w3e..-',
    'charset' => 'utf8mb4'
];

function getConnection($config) {
    try {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['dbname'],
            $config['charset']
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];

        return new PDO($dsn, $config['user'], $config['pass'], $options);
    } catch (PDOException $e) {
        throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
    }
}

function sendResponse($success, $data = null, $message = '', $code = 200) {
    http_response_code($code);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}

function validateSearch($search) {
    if (empty($search)) return true;
    return strlen($search) >= 2 && strlen($search) <= 100;
}

try {
    // Validar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        sendResponse(false, null, 'Método no permitido', 405);
    }
    
    // Obtener parámetro de búsqueda
    $search = $_GET['search'] ?? '';
    
    // Validar parámetro de búsqueda
    if (!validateSearch($search)) {
        sendResponse(false, null, 'Parámetro de búsqueda inválido', 400);
    }
    
    // Conectar a la base de datos
    $pdo = getConnection($config);
    
    // Construir consulta
    if (!empty($search)) {
        $sql = "SELECT * FROM empresa WHERE activo = 1 AND (id_empresa LIKE :search1 OR razon_social LIKE :search2 OR rif LIKE :search3) ORDER BY fecha_creacion DESC";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%{$search}%";
        $stmt->bindParam(':search1', $searchParam);
        $stmt->bindParam(':search2', $searchParam);
        $stmt->bindParam(':search3', $searchParam);
    } else {
        $sql = "SELECT * FROM empresa WHERE activo = 1 ORDER BY fecha_creacion DESC";
        $stmt = $pdo->prepare($sql);
    }
    
    $stmt->execute();
    $empresas = $stmt->fetchAll();
    
    // Contar total
    if (!empty($search)) {
        $countSql = "SELECT COUNT(*) as total FROM empresa WHERE activo = 1 AND (id_empresa LIKE :search1 OR razon_social LIKE :search2 OR rif LIKE :search3)";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->bindParam(':search1', $searchParam);
        $countStmt->bindParam(':search2', $searchParam);
        $countStmt->bindParam(':search3', $searchParam);
    } else {
        $countSql = "SELECT COUNT(*) as total FROM empresa WHERE activo = 1";
        $countStmt = $pdo->prepare($countSql);
    }
    
    $countStmt->execute();
    $total = $countStmt->fetch()['total'];
    
    sendResponse(true, [
        'empresas' => $empresas,
        'total' => $total,
        'search' => $search
    ], 'Empresas obtenidas exitosamente');
    
} catch (Exception $e) {
    sendResponse(false, null, 'Error interno del servidor: ' . $e->getMessage(), 500);
}

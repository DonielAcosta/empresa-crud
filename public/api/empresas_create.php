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

function validateRif($rif) {
    return !empty($rif) && preg_match('/^[A-Za-z]-\d{8}-\d$/', $rif);
}

function validateRazonSocial($razonSocial) {
    return !empty($razonSocial) && strlen($razonSocial) >= 2 && strlen($razonSocial) <= 150;
}

function validateDireccion($direccion) {
    return !empty($direccion) && strlen($direccion) >= 10 && strlen($direccion) <= 500;
}

function validateTelefono($telefono) {
    return !empty($telefono) && preg_match('/^[\d\s\-\(\)\+]+$/', $telefono) && strlen($telefono) >= 7;
}

function validateData($data) {
    $errors = [];
    
    if (!validateRif($data['rif'] ?? '')) {
        $errors[] = 'RIF inválido (formato: J-12345678-9)';
    }
    
    if (!validateRazonSocial($data['razon_social'] ?? '')) {
        $errors[] = 'Razón social inválida (2-150 caracteres)';
    }
    
    if (!validateDireccion($data['direccion'] ?? '')) {
        $errors[] = 'Dirección inválida (10-500 caracteres)';
    }
    
    if (!validateTelefono($data['telefono'] ?? '')) {
        $errors[] = 'Teléfono inválido';
    }
    
    return $errors;
}

try {
    // Validar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse(false, null, 'Método no permitido', 405);
    }
    
    // Obtener datos JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendResponse(false, null, 'JSON inválido', 400);
    }
    
    if (empty($data)) {
        sendResponse(false, null, 'Datos requeridos', 400);
    }
    
    // Validar datos
    $errors = validateData($data);
    if (!empty($errors)) {
        sendResponse(false, null, 'Datos inválidos: ' . implode(', ', $errors), 422);
    }
    
    // Conectar a la base de datos
    $pdo = getConnection($config);
    
    // Verificar si el RIF ya existe
    $checkSql = "SELECT id_empresa FROM empresa WHERE rif = :rif AND activo = 1";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->bindParam(':rif', $data['rif']);
    $checkStmt->execute();
    
    if ($checkStmt->fetch()) {
        sendResponse(false, null, 'El RIF ya existe', 409);
    }
    
    // Insertar nueva empresa
    $sql = "INSERT INTO empresa (rif, razon_social, direccion, telefono, activo) VALUES (:rif, :razon_social, :direccion, :telefono, 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':rif', $data['rif']);
    $stmt->bindParam(':razon_social', $data['razon_social']);
    $stmt->bindParam(':direccion', $data['direccion']);
    $stmt->bindParam(':telefono', $data['telefono']);
    
    if ($stmt->execute()) {
        $id = $pdo->lastInsertId();
        
        // Obtener la empresa creada
        $getSql = "SELECT * FROM empresa WHERE id_empresa = :id";
        $getStmt = $pdo->prepare($getSql);
        $getStmt->bindParam(':id', $id);
        $getStmt->execute();
        $empresa = $getStmt->fetch();
        
        sendResponse(true, ['empresa' => $empresa], 'Empresa creada exitosamente', 201);
    } else {
        sendResponse(false, null, 'Error al crear empresa', 500);
    }
    
} catch (Exception $e) {
    sendResponse(false, null, 'Error interno del servidor: ' . $e->getMessage(), 500);
}

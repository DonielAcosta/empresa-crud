<?php


//   Helper functions para la API y probar en postman
 

//envio json
function sendJsonResponse(array $data, int $statusCode = 200): void{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

//error
 
function sendErrorResponse(string $message, int $statusCode = 400, string $code = 'BAD_REQUEST'): void{
    sendJsonResponse([
        'error' => [
            'code' => $code,
            'message' => $message
        ]
    ], $statusCode);
}

function sendSuccessResponse(array $data = [], string $message = 'Operación exitosa'): void{
    $response = ['success' => true, 'message' => $message];
    if (!empty($data)) {
        $response['data'] = $data;
    }
    sendJsonResponse($response);
}

function getJsonInput(): array{
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendErrorResponse('JSON inválido en el request');
    }
    
    return $data ?: [];
}


function validateHttpMethod(string $expectedMethod): void{
    if ($_SERVER['REQUEST_METHOD'] !== $expectedMethod) {
        sendErrorResponse('Método HTTP no permitido', 405, 'METHOD_NOT_ALLOWED');
    }
}


function getSearchParam(): ?string{
    return $_GET['search'] ?? null;
}

function getIdFromUrl(): ?int{
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));
    
    // Buscar el último segmento que sea numérico
    for ($i = count($segments) - 1; $i >= 0; $i--) {
        if (is_numeric($segments[$i])) {
            return (int)$segments[$i];
        }
    }
    
    return null;
}

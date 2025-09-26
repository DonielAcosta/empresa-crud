<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/helpers.php';

use App\Repos\EmpresaRepo;
use App\Validators\Empresa;

try {
    validateHttpMethod('GET');
    
    $id = getIdFromUrl();
    if (!$id) {
        sendErrorResponse('ID de empresa requerido');
    }
    
    $validator = new Empresa();
    if (!$validator->validateId($id)) {
        $errors = $validator->getErrors();
        sendErrorResponse($errors['id'], 404, 'NOT_FOUND');
    }
    
    $empresaRepo = new EmpresaRepo();
    $empresa = $empresaRepo->getById($id);
    
    if (!$empresa) {
        sendErrorResponse('Empresa no encontrada', 404, 'NOT_FOUND');
    }
    
    sendSuccessResponse(['empresa' => $empresa], 'Empresa obtenida exitosamente');
    
} catch (Exception $e) {
    sendErrorResponse('Error interno del servidor: ' . $e->getMessage(), 500, 'INTERNAL_ERROR');
}

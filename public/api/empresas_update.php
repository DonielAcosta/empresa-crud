<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/helpers.php';

use App\Repos\EmpresaRepo;
use App\Validators\Empresa;

try {
    validateHttpMethod('PUT');
    
    $id = getIdFromUrl();
    if (!$id) {
        sendErrorResponse('ID de empresa requerido');
    }
    
    $data = getJsonInput();
    if (empty($data)) {
        sendErrorResponse('Datos requeridos en el body del request');
    }
    
    $validator = new Empresa();
    
    // Validar ID
    if (!$validator->validateId($id)) {
        $errors = $validator->getErrors();
        sendErrorResponse($errors['id'], 404, 'NOT_FOUND');
    }
    
    // Validar datos
    if (!$validator->validate($data, $id)) {
        $errors = $validator->getErrors();
        sendErrorResponse('Datos inválidos: ' . implode(', ', $errors), 422, 'VALIDATION_ERROR');
    }
    
    $empresaRepo = new EmpresaRepo();
    $success = $empresaRepo->update($id, $data);
    
    if (!$success) {
        sendErrorResponse('No se pudo actualizar la empresa', 500, 'UPDATE_ERROR');
    }
    
    $empresa = $empresaRepo->getById($id);
    
    sendSuccessResponse(['empresa' => $empresa], 'Empresa actualizada exitosamente');
    
} catch (Exception $e) {
    sendErrorResponse('Error interno del servidor: ' . $e->getMessage(), 500, 'INTERNAL_ERROR');
}

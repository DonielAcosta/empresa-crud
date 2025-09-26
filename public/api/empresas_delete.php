<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/helpers.php';

use App\Repos\EmpresaRepo;
use App\Validators\Empresa;

try {
    validateHttpMethod('DELETE');
    
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
    $success = $empresaRepo->delete($id);
    
    if (!$success) {
        sendErrorResponse('No se pudo eliminar la empresa', 500, 'DELETE_ERROR');
    }
    
    sendSuccessResponse([], 'Empresa eliminada exitosamente');
    
} catch (Exception $e) {
    sendErrorResponse('Error interno del servidor: ' . $e->getMessage(), 500, 'INTERNAL_ERROR');
}

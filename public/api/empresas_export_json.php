<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/helpers.php';

use App\Repos\EmpresaRepo;
use App\Validators\Empresa;

try {
    validateHttpMethod('GET');
    
    $search = getSearchParam();
    $validator = new Empresa();
    
    // Validar parámetro de búsqueda
    if (!$validator->validateSearch($search)) {
        $errors = $validator->getErrors();
        sendErrorResponse($errors['search']);
    }
    
    $empresaRepo = new EmpresaRepo();
    $empresas = $empresaRepo->getAll($search);
    $total = $empresaRepo->count($search);
    
    // Preparar datos para exportación
    $exportData = [
        'metadata' => [
            'export_date' => date('Y-m-d H:i:s'),
            'total_records' => $total,
            'search_filter' => $search,
            'format' => 'JSON'
        ],
        'empresas' => $empresas
    ];
    
    // Configurar headers para descarga
    $filename = 'empresas_' . date('Y-m-d_H-i-s') . '.json';
    if ($search) {
        $filename = 'empresas_filtradas_' . date('Y-m-d_H-i-s') . '.json';
    }
    
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    
    echo json_encode($exportData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
    
} catch (Exception $e) {
    sendErrorResponse('Error interno del servidor: ' . $e->getMessage(), 500, 'INTERNAL_ERROR');
}

<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/helpers.php';

use App\Repos\EmpresaRepo;
use App\Validators\Empresa;
use App\Pdf\EmpresaReport;

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
    $total = $empresaRepo->count($search);
    
    if ($total === 0) {
        sendErrorResponse('No hay empresas para generar el reporte', 404, 'NO_DATA');
    }
    
    // Generar PDF
    $pdfGenerator = new EmpresaReport();
    $pdfContent = $pdfGenerator->generateReport($search);
    
    // Configurar headers para descarga
    $filename = 'reporte_empresas_' . date('Y-m-d_H-i-s') . '.pdf';
    if ($search) {
        $filename = 'reporte_empresas_filtradas_' . date('Y-m-d_H-i-s') . '.pdf';
    }
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($pdfContent));
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    
    echo $pdfContent;
    exit;
    
} catch (Exception $e) {
    sendErrorResponse('Error interno del servidor: ' . $e->getMessage(), 500, 'INTERNAL_ERROR');
}

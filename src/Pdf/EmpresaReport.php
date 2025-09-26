<?php

namespace App\Pdf;

use TCPDF;
use App\Repos\EmpresaRepo;

class EmpresaReport extends TCPDF{
    private EmpresaRepo $empresaRepo;

    public function __construct(){
        parent::__construct();
        $this->empresaRepo = new EmpresaRepo();
        
        // Configuración del documento
        $this->SetCreator('Empresa CRUD');
        $this->SetAuthor('Empresa CRUD');
        $this->SetTitle('Reporte de Empresas');
        $this->SetSubject('Listado de Empresas');
        
        // Configuración de márgenes
        $this->SetMargins(15, 20, 15);
        $this->SetHeaderMargin(10);
        $this->SetFooterMargin(10);
        
        // Configuración de fuente
        $this->SetFont('helvetica', '', 10);
        
        // Configuración de página
        $this->SetAutoPageBreak(true, 20);
    }

    //metodo para generar el reporte
    public function generateReport(?string $search = null): string{
        $this->AddPage();
        
        // Obtener datos
        $empresas = $this->empresaRepo->getAll($search);
        $total = $this->empresaRepo->count($search);
        
        // Título del reporte
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 10, 'REPORTE DE EMPRESAS', 0, 1, 'C');
        $this->Ln(5);
        
        // Información del reporte
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 6, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1);
        $this->Cell(0, 6, 'Total de registros: ' . $total, 0, 1);
        
        if ($search) {
            $this->Cell(0, 6, 'Filtro aplicado: ' . $search, 0, 1);
        }
        
        $this->Ln(10);
        
        if (empty($empresas)) {
            $this->SetFont('helvetica', 'I', 12);
            $this->Cell(0, 10, 'No se encontraron empresas que coincidan con los criterios de búsqueda.', 0, 1, 'C');
            return $this->Output('reporte_empresas.pdf', 'S');
        }
        
        // Crear tabla
        $this->createTable($empresas);
        
        return $this->Output('reporte_empresas.pdf', 'S');
    }


    private function createTable(array $empresas): void{
        // Encabezados de la tabla
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(200, 200, 200);
        
        $this->Cell(15, 8, 'ID', 1, 0, 'C', true);
        $this->Cell(30, 8, 'RIF', 1, 0, 'C', true);
        $this->Cell(60, 8, 'Razón Social', 1, 0, 'C', true);
        $this->Cell(50, 8, 'Teléfono', 1, 0, 'C', true);
        $this->Cell(0, 8, 'Fecha Creación', 1, 1, 'C', true);
        
        // Datos de la tabla
        $this->SetFont('helvetica', '', 8);
        $this->SetFillColor(245, 245, 245);
        $fill = false;
        
        foreach ($empresas as $empresa) {
            $this->Cell(15, 6, $empresa['id_empresa'], 1, 0, 'C', $fill);
            $this->Cell(30, 6, $empresa['rif'], 1, 0, 'C', $fill);
            
            // Truncar razón social si es muy larga
            $razonSocial = strlen($empresa['razon_social']) > 25 
                ? substr($empresa['razon_social'], 0, 22) . '...' 
                : $empresa['razon_social'];
            $this->Cell(60, 6, $razonSocial, 1, 0, 'L', $fill);
            
            $this->Cell(50, 6, $empresa['telefono'], 1, 0, 'C', $fill);
            $this->Cell(0, 6, date('d/m/Y', strtotime($empresa['fecha_creacion'])), 1, 1, 'C', $fill);
            
            $fill = !$fill;
        }
        
        $this->Ln(5);
        
        // Agregar información adicional
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 6, 'Nota: Las direcciones completas no se muestran en este reporte por limitaciones de espacio.', 0, 1);
    }

    // Header personalizado puedo cambiar el nombre
    public function Header(): void{
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, 'Sistema de Gestión de Empresas', 0, 1, 'C');
        $this->Line(15, 25, 195, 25);
    }


    public function Footer(): void{
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

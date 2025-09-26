<?php

namespace App\Validators;

use App\Repos\EmpresaRepo;

class Empresa{
    private EmpresaRepo $empresaRepo;
    private array $errors = [];

    public function __construct(){
        $this->empresaRepo = new EmpresaRepo();
    }

    //aqui valido datos de la empresa probado desde postman 
    public function validate(array $data, ?int $excludeId = null): bool{
        $this->errors = [];

        // valido RIF
        $this->validateRif($data['rif'] ?? '', $excludeId);

        // valido descripcion de la razon
        $this->validateRazonSocial($data['razon_social'] ?? '');

        // valido dirc
        $this->validateDireccion($data['direccion'] ?? '');

        //telefono
        $this->validateTelefono($data['telefono'] ?? '');

        return empty($this->errors);
    }


    private function validateRif(string $rif, ?int $excludeId = null): void{
        if (empty($rif)) {
            $this->errors['rif'] = 'El RIF es requerido';
            return;
        }

        //  J-########-#
        if (!preg_match('/^[A-Za-z]-\d{8}-\d$/', $rif)) {
            $this->errors['rif'] = 'El RIF debe tener el formato correcto (ej. J-12345678-9)';
            return;
        }

        // si ya esta guardado
        if ($this->empresaRepo->rifExists($rif, $excludeId)) {
            $this->errors['rif'] = 'El RIF ya existe en la base de datos';
        }
    }


    private function validateRazonSocial(string $razonSocial): void{
        if (empty($razonSocial)) {
            $this->errors['razon_social'] = 'La razón social es requerida';
            return;
        }
    
        if (strlen($razonSocial) < 3) {
            $this->errors['razon_social'] = 'La razón social debe tener al menos 3 caracteres';
            return;
        }

        if (strlen($razonSocial) > 150) {
            $this->errors['razon_social'] = 'La razón social no puede exceder 150 caracteres';
        }
    }

    private function validateDireccion(string $direccion): void{
        if (empty($direccion)) {
            $this->errors['direccion'] = 'La dirección es requerida';
            return;
        }

        if (strlen($direccion) < 10) {
            $this->errors['direccion'] = 'La dirección debe tener al menos 10 caracteres';
            return;
        }

        if (strlen($direccion) > 500) {
            $this->errors['direccion'] = 'La dirección no puede exceder 500 caracteres';
        }
    }


    private function validateTelefono(string $telefono): void{
        if (empty($telefono)) {
            $this->errors['telefono'] = 'El teléfono es requerido';
            return;
        }

        
        if (!preg_match('/^[\d\s\-\(\)\+]+$/', $telefono)) {
            $this->errors['telefono'] = 'El teléfono contiene caracteres inválidos';
            return;
        }

        // Remover caracteres no numéricos para validar longitud
        $digitsOnly = preg_replace('/[^\d]/', '', $telefono);
        
        if (strlen($digitsOnly) < 7) {
            $this->errors['telefono'] = 'El teléfono debe tener al menos 7 dígitos';
            return;
        }

        if (strlen($digitsOnly) > 15) {
            $this->errors['telefono'] = 'El teléfono no puede exceder 15 dígitos';
        }
    }

    //uso para los errores eso lo uso en cualquier lugar 
    public function getErrors(): array{
        return $this->errors;
    }

    public function getFirstError(): ?string{
        return !empty($this->errors) ? reset($this->errors) : null;
    }

    public function validateId($id): bool{
        if (!is_numeric($id) || $id <= 0) {
            $this->errors['id'] = 'ID de empresa inválido';
            return false;
        }

        $empresa = $this->empresaRepo->getById((int)$id);
        if (!$empresa) {
            $this->errors['id'] = 'Empresa no encontrada en Base de Datos';
            return false;
        }

        return true;
    }

    public function validateSearch(?string $search): bool{
        if ($search === null || $search === '') {
            return true; //buscar sin filtro
        }

        // Limpiar y validar longitud
        $search = trim($search);
        
        if (strlen($search) > 100) {
            $this->errors['search'] = 'El término de búsqueda no puede exceder 100 caracteres';
            return false;
        }

        return true;
    }
}

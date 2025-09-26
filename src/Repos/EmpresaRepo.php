<?php

namespace App\Repos;

use App\Db\Conn;
use PDO;
use PDOException;

class EmpresaRepo{
    private PDO $db;

    public function __construct(){
        $this->db = Conn::getInstance();
    }
    //puedo tener este enpoid por busqueda id o por razon
    public function getAll(?string $search = null): array{
        try {
            $sql = "SELECT * FROM empresa WHERE activo = 1";
            $params = [];

            if ($search !== null && $search !== '') {
            
                if (is_numeric($search)) {
                    $sql .= " AND id_empresa = :search";
                    $params['search'] = (int)$search;
                } else {
                   
                    $sql .= " AND razon_social LIKE :search";
                    $params['search'] = '%' . $search . '%';
                }
            }

            $sql .= " ORDER BY fecha_creacion DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener empresas: " . $e->getMessage());
        }
    }

    public function getById(int $id): ?array{
        try {
            $sql = "SELECT * FROM empresa WHERE id_empresa = :id AND activo = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);

            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener empresa: " . $e->getMessage());
        }
    }


    public function create(array $data): int{
        try {
            $sql = "INSERT INTO empresa (rif, razon_social, direccion, telefono) 
                    VALUES (:rif, :razon_social, :direccion, :telefono)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'rif'          => $data['rif'],
                'razon_social' => $data['razon_social'],
                'direccion'    => $data['direccion'],
                'telefono'     => $data['telefono']
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // Verificar si es error de duplicado
            if ($e->getCode() == 23000) {
                throw new PDOException("El RIF ya existe en la base de datos");
            }
            throw new PDOException("Error al crear empresa: " . $e->getMessage());
        }
    }

    public function update(int $id, array $data): bool{
        try {
            $sql = "UPDATE empresa SET 
                    rif = :rif, 
                    razon_social = :razon_social, 
                    direccion = :direccion, 
                    telefono = :telefono 
                    WHERE id_empresa = :id AND activo = 1";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'id'           => $id,
                'rif'          => $data['rif'],
                'razon_social' => $data['razon_social'],
                'direccion'    => $data['direccion'],
                'telefono'     => $data['telefono']
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // Verificar si es error de duplicado
            if ($e->getCode() == 23000) {
                throw new PDOException("El RIF ya existe en la base de datos");
            }
            throw new PDOException("Error al actualizar empresa: " . $e->getMessage());
        }
    }


    public function delete(int $id): bool{
        try {
            $sql = "UPDATE empresa SET activo = 0 WHERE id_empresa = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new PDOException("Error al eliminar empresa: " . $e->getMessage());
        }
    }

    public function rifExists(string $rif, ?int $excludeId = null): bool{
        try {
            $sql = "SELECT COUNT(*) FROM empresa WHERE rif = :rif AND activo = 1";
            $params = ['rif' => $rif];

            if ($excludeId !== null) {
                $sql .= " AND id_empresa != :exclude_id";
                $params['exclude_id'] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new PDOException("Error al verificar RIF: " . $e->getMessage());
        }
    }

    public function count(?string $search = null): int{
        try {
            $sql = "SELECT COUNT(*) FROM empresa WHERE activo = 1";
            $params = [];

            if ($search !== null && $search !== '') {
                if (is_numeric($search)) {
                    $sql .= " AND id_empresa = :search";
                    $params['search'] = (int)$search;
                } else {
                    $sql .= " AND razon_social LIKE :search";
                    $params['search'] = '%' . $search . '%';
                }
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new PDOException("Error al contar empresas: " . $e->getMessage());
        }
    }
}

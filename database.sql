-- Script SQL para crear la base de datos y tabla empresa
-- Ejecutar en MySQL/MariaDB

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS empresa_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos
USE empresa_db;

-- Crear tabla empresa
CREATE TABLE IF NOT EXISTS empresa (
    id_empresa INT AUTO_INCREMENT PRIMARY KEY,
    rif VARCHAR(20) NOT NULL UNIQUE,
    razon_social VARCHAR(150) NOT NULL,
    direccion TEXT NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Crear índices para optimizar búsquedas
CREATE INDEX idx_empresa_rif ON empresa(rif);
CREATE INDEX idx_empresa_razon_social ON empresa(razon_social);
CREATE INDEX idx_empresa_fecha_creacion ON empresa(fecha_creacion DESC);
CREATE INDEX idx_empresa_activo ON empresa(activo);

-- Insertar datos de ejemplo
INSERT INTO empresa (rif, razon_social, direccion, telefono) VALUES
('J-12345678-9', 'Empresa Ejemplo S.A.', 'Av. Principal, Edificio Central, Piso 5, Caracas, Venezuela', '+58 212 123-4567'),
('G-87654321-0', 'Comercial Los Andes C.A.', 'Calle 5 de Julio, Local 12, Maracaibo, Zulia', '+58 261 987-6543'),
('V-11223344-5', 'Servicios Integrales Ltda.', 'Av. Libertador, Torre Empresarial, Oficina 301, Valencia, Carabobo', '+58 241 555-1234'),
('E-55667788-9', 'Distribuidora Nacional S.R.L.', 'Zona Industrial, Galpón 45, Barquisimeto, Lara', '+58 251 777-8888'),
('J-99887766-5', 'Consultoría Profesional C.A.', 'Centro Comercial, Oficina 205, Mérida, Mérida', '+58 274 333-2222');

-- Verificar datos insertados
SELECT COUNT(*) as total_empresas FROM empresa WHERE activo = 1;

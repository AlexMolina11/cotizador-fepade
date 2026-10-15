-- FEPADE Cotizador
-- Migración: 20261014_01
-- Agregar visibilidad de cantidades de insumos

ALTER TABLE cot_insumos
ADD COLUMN MOSTRAR_CANTIDAD TINYINT(1) NOT NULL DEFAULT 0
COMMENT '1 = Mostrar cantidad en PDF de salas; 0 = Ocultar';
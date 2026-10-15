-- FEPADE Cotizador
-- Rollback de migración 20261014_01
-- ADVERTENCIA: elimina los valores configurados

ALTER TABLE cot_insumos
DROP COLUMN MOSTRAR_CANTIDAD;
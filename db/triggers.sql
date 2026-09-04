	
/*Trigger al momento de crear una cotización injecta a la tabla cot_estado_historial el inicio del pipeline*/
DELIMITER $$

DROP TRIGGER IF EXISTS trg_cotizacion_historial$$
CREATE TRIGGER trg_cotizacion_historial
AFTER INSERT ON cot_cotizaciones
FOR EACH ROW
BEGIN
 INSERT INTO cot_estado_historial (IDCOTIZACION, ESTADOCOT, FECHAEVENTO, USUARIO)
 VALUES (NEW.IDCOTIZACION, NEW.ESTADOCOT, NOW(), NEW.USURECOT);
END$$

DELIMITER ;

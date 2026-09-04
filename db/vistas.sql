/*Vista Detalle cotización*/

CREATE OR REPLACE VIEW vw_cotizacion_detalle AS

SELECT

    d.IDDETALLECOT,

    c.IDCOTIZACION,

    c.CODREFERENCIA,

    c.NRC,

    c.EMPRESA,

    c.NOMBRECONTACTO,

    c.TELCONTACTO,

    c.CORREOCONTACTO,

    c.IDTIPOEVENTO,

    te.NOMBRETIPOEVENTO,

    c.IDTIPOALQUILER,

    ta.NOMBRETIPOALQUILER,

    c.ESTADOCOT,

    c.IDAREARESPONSABLE,

    ar.NOMBREAREARES,

    c.USURECOT,

    c.IDORGEJE,

    oe.NOMBREORGEJE,

    c.FECHAREGCOT,

    d.CANTIDADCOT,

    d.FECHACOT,

    d.HORAINICOT,

    d.HORAFINCOT,

    d.DURACIONCOT,

    d.ESTADOCOT AS ESTADODETALLE,

    d.USUREGCOT AS USUDETALLE,

    d.FECHAREGCOT AS FECHAREGDETALLE,

    di.IDINSUMO,

    i.NOMBREINSUMO,

    i.DESCRIPCIONINS,

    ti.NOMBRETIPOINSUMO,

    di.CANTIDAD,

    di.PRECIO,

    di.TOTAL,

    di.FECHAREGDETINS,

    di.USUREGDETINS

FROM cot_detalle_cotizaciones d

INNER JOIN cot_cotizaciones c ON d.IDCOTIZACION = c.IDCOTIZACION

INNER JOIN cot_tipo_evento te ON c.IDTIPOEVENTO = te.IDTIPOEVENTO

INNER JOIN cot_tipo_alquiler ta ON c.IDTIPOALQUILER = ta.IDTIPOALQUILER

INNER JOIN arearesponsable ar ON c.IDAREARESPONSABLE = ar.IDAREARES

INNER JOIN organizacionejecutora oe ON c.IDORGEJE = oe.IDORGEJE

LEFT JOIN cot_detalle_insumos di ON d.IDDETALLECOT = di.IDDETALLECOT

LEFT JOIN cot_insumos i ON di.IDINSUMO = i.IDINSUMO

INNER JOIN cot_tipo_insumo ti ON i.IDTIPOINSUMO = ti.IDTIPOINSUMO



/*Vista Cotización reporte final*/

CREATE OR REPLACE VIEW vw_cotizacion_final AS

SELECT 

	c.IDCOTIZACION,

    c.CODREFERENCIA,

    c.EMPRESA,

    c.NOMBRECONTACTO,

    c.TELCONTACTO,

    c.DESCRIPCIONCOT,

    c.IDTIPOEVENTO,

    te.NOMBRETIPOEVENTO,

    c.IDTIPOALQUILER,

    ta.NOMBRETIPOALQUILER,

    c.EXENTACOT,

    c.ESTADOCOT,

    c.IDAREARESPONSABLE,

    ar.NOMBREAREARES,

    c.IDORGEJE,

    oe.NOMBREORGEJE,

    dc.IDDETALLECOT,

    dc.CANTIDADCOT,

    dc.FECHACOT,

    dc.HORAINICOT,

    dc.HORAFINCOT,

    dc.DURACIONCOT,

    di.IDDETALLEINSUMO,

    di.IDINSUMO,

    i.IDTIPOINSUMO,

    ti.NOMBRETIPOINSUMO,

    i.ALIASINSUMO,

    i.DESCRIPCIONINS,

    di.CANTIDAD,

    di.PRECIO,

    di.TOTAL,

    c.FECHAREGCOT

FROM cot_cotizaciones as c

INNER JOIN cot_detalle_cotizaciones as dc ON c.IDCOTIZACION = dc.IDCOTIZACION

INNER JOIN cot_tipo_evento as te ON te.IDTIPOEVENTO = c.IDTIPOEVENTO

INNER JOIN cot_tipo_alquiler as ta ON ta.IDTIPOALQUILER = c.IDTIPOALQUILER

INNER JOIN arearesponsable as ar ON ar.IDAREARES = c.IDAREARESPONSABLE

INNER JOIN organizacionejecutora as oe ON oe.IDORGEJE = c.IDORGEJE

LEFT OUTER JOIN cot_detalle_insumos as di ON di.IDDETALLECOT = dc.IDDETALLECOT

LEFT OUTER JOIN cot_insumos as i ON i.IDINSUMO = di.IDINSUMO

LEFT OUTER JOIN cot_tipo_insumo as ti ON ti.IDTIPOINSUMO = i.IDTIPOINSUMO


/*Vista para cotización final - validando que muestre los detalles de cotizaciones estén activados*/

CREATE OR REPLACE VIEW vw_cotizacion_final AS
SELECT 
    c.IDCOTIZACION,
    c.CODREFERENCIA,
    c.EMPRESA,
    c.NOMBRECONTACTO,
    c.TELCONTACTO,
    c.DESCRIPCIONCOT,
    c.IDTIPOEVENTO,
    te.NOMBRETIPOEVENTO,
    c.IDTIPOALQUILER,
    ta.NOMBRETIPOALQUILER,
    c.EXENTACOT,
    c.ESTADOCOT,
    c.IDAREARESPONSABLE,
    ar.NOMBREAREARES,
    c.IDORGEJE,
    oe.NOMBREORGEJE,
    dc.IDDETALLECOT,
    dc.CANTIDADCOT,
    dc.FECHACOT,
    dc.HORAINICOT,
    dc.HORAFINCOT,
    dc.DURACIONCOT,
    di.IDDETALLEINSUMO,
    di.IDINSUMO,
    i.IDTIPOINSUMO,
    ti.NOMBRETIPOINSUMO,
    i.ALIASINSUMO,
    i.DESCRIPCIONINS,
    di.CANTIDAD,
    di.PRECIO,
    di.TOTAL,
    c.FECHAREGCOT
FROM cot_cotizaciones c
INNER JOIN cot_detalle_cotizaciones dc 
    ON c.IDCOTIZACION = dc.IDCOTIZACION
INNER JOIN cot_tipo_evento te 
    ON te.IDTIPOEVENTO = c.IDTIPOEVENTO
INNER JOIN cot_tipo_alquiler ta 
    ON ta.IDTIPOALQUILER = c.IDTIPOALQUILER
INNER JOIN arearesponsable ar 
    ON ar.IDAREARES = c.IDAREARESPONSABLE
INNER JOIN organizacionejecutora oe 
    ON oe.IDORGEJE = c.IDORGEJE
LEFT JOIN cot_detalle_insumos di 
    ON di.IDDETALLECOT = dc.IDDETALLECOT
LEFT JOIN cot_insumos i 
    ON i.IDINSUMO = di.IDINSUMO
LEFT JOIN cot_tipo_insumo ti 
    ON ti.IDTIPOINSUMO = i.IDTIPOINSUMO
WHERE 
    c.ESTADOCOT <> 0
    AND dc.ESTADOCOT <> 0;
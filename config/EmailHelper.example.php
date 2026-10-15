<?php

require_once __DIR__ . '/../librerias/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../librerias/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../librerias/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function crearMailer()
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.office365.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'TU_CORREO_SMTP';
    $mail->Password   = 'TU_PASSWORD_SMTP';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->CharSet = 'UTF-8';
    $mail->setFrom('TU_CORREO_SMTP', 'Sistema de Cotizaciones');
    $mail->isHTML(true);

    return $mail;
}

function plantillaCorreoCotizacion($titulo, $codigoReferencia, $mensaje, $url)
{
    return "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    </head>
    <body style='margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,\"Helvetica Neue\",Arial,sans-serif;'>
        <div style='background:#fff3cd;color:#856404;padding:12px 16px;margin-bottom:20px;border-radius:6px;font-weight:600;'>
           AMBIENTE LOCAL - CORREO DE PRUEBA
        </div>
        <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#000000;padding:40px 20px;'>
            <tr>
                <td align='center'>
                    <table width='600' cellpadding='0' cellspacing='0' style='max-width:600px;width:100%;background:linear-gradient(135deg, rgba(12, 233, 139, 0.05) 0%, rgba(0, 0, 0, 0.95) 100%);border:1px solid rgba(255, 255, 255, 0.15);border-radius:16px;overflow:hidden;box-shadow:0 0 40px rgba(12, 233, 139, 0.15);'>
                        
                        <tr>
                            <td style='background:linear-gradient(90deg, rgba(12, 233, 139, 0.2) 0%, rgba(12, 233, 139, 0.05) 100%);padding:30px;border-bottom:2px solid rgba(12, 233, 139, 0.3);'>
                                <h1 style='margin:0;color:#0ce98b;font-size:24px;font-weight:600;letter-spacing:-0.5px;'>
                                    {$titulo}
                                </h1>
                            </td>
                        </tr>
                        
                        <tr>
                            <td style='padding:40px 30px;'>
                                <div style='background:rgba(255, 255, 255, 0.05);border-left:3px solid #0ce98b;padding:20px;border-radius:8px;margin-bottom:30px;'>
                                    <p style='margin:0 0 8px 0;color:rgba(255, 255, 255, 0.7);font-size:14px;'>
                                        Código de referencia
                                    </p>
                                    <p style='margin:0;color:#ffffff;font-size:28px;font-weight:700;letter-spacing:-0.5px;'>
                                        #{$codigoReferencia}
                                    </p>
                                </div>
                                
                                <p style='margin:0 0 30px 0;color:rgba(255, 255, 255, 0.9);font-size:16px;line-height:1.6;'>
                                    {$mensaje}
                                </p>
                                
                                <table width='100%' cellpadding='0' cellspacing='0'>
                                    <tr>
                                        <td align='center' style='padding:10px 0;'>
                                            <a href='{$url}' 
                                            style='display:inline-block;padding:16px 40px;background:linear-gradient(135deg, #0ce98b 0%, #0ab872 100%);color:white;text-decoration:none;border-radius:8px;font-weight:600;font-size:16px;box-shadow:0 4px 15px rgba(12, 233, 139, 0.3);'>
                                                Ver Cotización →
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <tr>
                            <td style='background:rgba(255, 255, 255, 0.03);padding:25px 30px;border-top:1px solid rgba(255, 255, 255, 0.1);'>
                                <p style='margin:0;color:rgba(255, 255, 255, 0.5);font-size:13px;line-height:1.5;'>
                                    Este es un mensaje automático del sistema de cotizaciones.<br>
                                    Por favor, no responda a este correo.
                                </p>
                            </td>
                        </tr>
                        
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>";
}

function enviarCorreoCotizacion($email, $asunto, $titulo, $codigoReferencia, $mensaje)
{
    try {
        $mail = crearMailer();

        $url = "http://localhost/Cotizador/vistas/cotizacion.php?buscar=" . urlencode($codigoReferencia);

        //STAGING:
        // Todos los correos se redirigen al correo de pruebas.
        $mail->addAddress('TU_CORREO_DE_PRUEBAS');
        // Identificar claramente los correos de prueba.
        $mail->Subject = '[LOCAL] ' . $asunto;
        $mail->Body = plantillaCorreoCotizacion(
            $titulo,
            $codigoReferencia,
            $mensaje,
            $url
        );

        return $mail->send();

    } catch (Exception $e) {
        error_log('PHPMailer: ' . $e->getMessage());
        return false;
    }
}

function enviarCorreoSupervisor($email, $idcotizacion, $codreferencia)
{
    return enviarCorreoCotizacion(
        $email,
        'Cotización finalizada',
        '✓ Cotización Finalizada',
        $codreferencia,
        'La cotización ha sido finalizada y está lista para su revisión. Por favor, ingrese al sistema para autorizarla.'
    );
}

function enviarCorreoVendedor($correoDestino, $idCotizacion, $codigoReferencia)
{
    return enviarCorreoCotizacion(
        $correoDestino,
        'Cotización autorizada',
        '✓ Cotización Autorizada',
        $codigoReferencia,
        'La cotización ha sido revisada y autorizada por el supervisor. Ahora ya puede ser enviada al cliente.'
    );
}

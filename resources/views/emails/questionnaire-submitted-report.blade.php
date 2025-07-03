<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cuestionario Completado - {{ $response->questionnaire->title ?? 'N/A' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@800&family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@800&family=Montserrat:wght@400;700&display=swap');

        /* Client-specific Styles */
        div, p, a, li, td {-webkit-text-size-adjust:none;}
        #outlook a {padding:0;}
        .ReadMsgBody {width:100%;} .ExternalClass {width:100%;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
        body, table, td, p, a, li, blockquote {-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%;}
        table, td {mso-table-lspace:0pt; mso-table-rspace:0pt;}
        img {-ms-interpolation-mode:bicubic;}
        /* End reset */

        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; /* Color de fondo suave */
            color: #333333;
            font-family: 'Montserrat', Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
        table { border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
        td { vertical-align: top; }
        p { margin: 0 0 10px 0; }
        a { color: #ff9c00; text-decoration: none; } /* Color de acento de tu guía de estilo */
        .container {
            max-width: 600px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        .header {
            background-color: #2c3856; /* Color oscuro de tu guía de estilo */
            padding: 20px;
            text-align: center;
        }
        /* Estilos CSS para el logo (como fallback, pero el inline es el principal) */
        .header img {
            max-width: 180px; /* Tamaño máximo del logo para el correo */
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .content {
            padding: 30px 25px;
            color: #333333;
            font-size: 15px;
            line-height: 1.8;
        }
        .content h1 {
            font-family: 'Raleway', Helvetica, Arial, sans-serif;
            font-weight: 800; /* Raleway ExtraBold */
            font-size: 24px;
            color: #2c3856;
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }
        .content p strong {
            color: #2c3856;
        }
        .button-wrapper {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #ff9c00; /* Color de acento de tu guía de estilo */
            color: #ffffff;
            font-family: 'Montserrat', Helvetica, Arial, sans-serif;
            font-size: 16px;
            font-weight: 700; /* Montserrat Bold */
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #e08c00; /* Tono más oscuro al hover */
        }
        .footer {
            background-color: #f8f8f8;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #888888;
            border-top: 1px solid #e0e0e0;
        }
        .footer p {
            margin: 0;
        }
        .footer a {
            color: #888888;
            text-decoration: underline;
        }

        /* Responsive Styles */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }
            .header, .content, .footer {
                padding: 15px !important;
            }
            /* Estilos responsivos para el logo */
            .header img {
                max-width: 60px !important; /* Más pequeño en móvil */
            }
            .content h1 {
                font-size: 20px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; color: #333333; font-family: 'Montserrat', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6;">
    <center style="width: 100%; background-color: #f4f4f4;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color: #f4f4f4;">
            <tr>
                <td align="center" style="padding: 20px 0;">
                    <table class="container" align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden; border: 1px solid #e0e0e0;">
                        <!-- Header -->
                        <tr>
                            <td class="header" style="background-color:rgb(255, 255, 255); padding: 20px; text-align: center;">
                                @if ($logoBase64)
                                    <img src="{{ $logoBase64 }}" alt="{{ config('app.name') }} Logo" style="max-width: 120px; height: auto; display: block; margin: 0 auto; border: 0;" width="120" /> <!-- <-- AJUSTADO: Estilos en línea y atributos width/height -->
                                @else
                                    <h1 style="font-family: 'Raleway', Helvetica, Arial, sans-serif; font-weight: 800; font-size: 28px; color: #ffffff; margin: 0;">{{ config('app.name') }}</h1>
                                @endif
                            </td>
                        </tr>
                        <!-- Content -->
                        <tr>
                            <td class="content" style="padding: 30px 25px; color: #333333; font-size: 15px; line-height: 1.8;">
                                <h1 style="font-family: 'Raleway', Helvetica, Arial, sans-serif; font-weight: 800; font-size: 24px; color: #2c3856; margin-top: 0; margin-bottom: 20px; text-align: center;">Cuestionario Completado</h1>
                                <p style="margin: 0 0 10px 0;">El cuestionario <strong style="color: #2c3856;">"{{ $response->questionnaire->title ?? 'N/A' }}"</strong> ha sido completado por <strong style="color: #2c3856;">{{ $response->user->name ?? 'N/A' }} ({{ $response->user->email ?? 'N/A' }})</strong>.</p>
                                <p style="margin: 0 0 10px 0;">Fecha de envío: <strong style="color: #2c3856;">{{ $response->submitted_at ? $response->submitted_at->format('d/m/Y H:i') : 'N/A' }}</strong></p>
                                <p style="margin: 0 0 10px 0;">Adjunto encontrarás el reporte en formato PDF.</p>
                                
                                <p style="margin: 20px 0 0 0; font-weight: bold; color: #2c3856;">Gracias,</p>
                                <p style="margin: 0; font-weight: bold; color: #2c3856;">Estrategias y Soluciones Minmer Global.</p>
                            </td>
                        </tr>
                        <!-- Footer -->
                        <tr>
                            <td class="footer" style="background-color: #f8f8f8; padding: 20px; text-align: center; font-size: 12px; color: #888888; border-top: 1px solid #e0e0e0;">
                                <p style="margin: 0;">&copy; {{ date('Y') }} Estrategias y Soluciones Minmer Global. Todos los derechos reservados.</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>

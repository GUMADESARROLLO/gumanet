<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="x-apple-disable-message-reformatting">
<meta name="format-detection" content="telephone=no,address=no,email=no,date=no">
<!--[if mso]>
<noscript><xml><o:OfficeDocumentSettings>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings></xml></noscript>
<![endif]-->
<title>Estado de cuenta – {{ $cliente }}</title>
<!--[if mso]>
<style>
  table, td { mso-table-lspace:0pt; mso-table-rspace:0pt; }
  img { -ms-interpolation-mode:bicubic; }
</style>
<![endif]-->
</head>
<body style="margin:0;padding:0;background-color:#f4f4f0;width:100%">

<!-- Preheader (se ve en preview del inbox pero no en el body) -->
<div style="display:none;font-size:1px;color:#f4f4f0;line-height:1px;max-height:0px;max-width:0px">
  Estado de cuenta de acciones registradas para {{ $cliente }} · Unimark S.A
</div>

<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f4f4f0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%">
<tr>
<td align="center" class="wrapper" style="padding:32px 16px">

  <!-- Contenedor principal -->
  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:580px">

    <!-- ── LOGO / MARCA ── -->
    <tr>
      <td align="center" style="padding-bottom:20px">
        <table cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
            <img src="{{ url('img/unimark.png') }}" alt="Unimark S.A." width="160" style="display:block;border:0;outline:none;max-width:160px;height:auto" />
          </td>
        </tr>
        </table>
      </td>
    </tr>

    <!-- ── ENCABEZADO ── -->
    <tr>
      <td class="card" style="background:#ffffff;border-radius:12px;padding:28px 32px;border:1px solid #e2e2de;margin-bottom:16px">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
          <td align="center" style="padding-bottom:20px;border-bottom:1px solid #eeeeea">
            <p style="margin:0 0 6px;font-family:Arial, sans-serif;font-size:11px;font-weight:600;color:#185fa5;text-transform:uppercase;letter-spacing:0.08em">
              Estado de cuenta
            </p>
            <h1 style="margin:0;font-family:Arial, sans-serif;font-size:22px;font-weight:bold;color:#1a1a18;line-height:1.3">
              Acciones Registradas
            </h1>
            <p style="margin:8px 0 0;font-family:Arial, sans-serif;font-size:13px;color:#6b6b67">
              Resumen de acciones y facturas asociadas a su cuenta
            </p>
          </td>
        </tr>

        <!-- Datos del cliente -->
        <tr>
          <td style="padding-top:20px">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">

              <!-- Fila: Cédula / ID -->
              <tr>
                <td style="padding:10px 0;border-bottom:1px solid #f0f0ec">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%">
                  <tr>
                    <td style="font-family:Arial, sans-serif;font-size:13px;color:#6b6b67">
                      CLIENTE
                    </td>
                    <td style="font-family:Arial, sans-serif;font-size:13px;font-weight:600;color:#1a1a18;text-align:right">
                      {{ $cliente }}
                    </td>
                  </tr>
                  </table>
                </td>
              </tr>

              <!-- Fila: Nombre -->
              <tr>
                <td style="padding:10px 0;border-bottom:1px solid #f0f0ec">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%">
                  <tr>
                    <td style="font-family:Arial, sans-serif;font-size:13px;color:#6b6b67">
                      Nombre del titular
                    </td>
                    <td style="font-family:Arial, sans-serif;font-size:13px;font-weight:600;color:#1a1a18;text-align:right">
                      {{ $nombre ?? 'No disponible' }}
                    </td>
                  </tr>
                  </table>
                </td>
              </tr>

              <!-- Fila: Total -->
              <tr>
                <td style="padding:10px 0">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%">
                  <tr>
                    <td style="font-family:Arial, sans-serif;font-size:13px;color:#6b6b67">
                      Total de acciones
                    </td>
                    <td style="text-align:right">
                      <span style="background:#eaf3de;color:#3b6d11;font-family:Arial, sans-serif;font-size:13px;font-weight:bold;padding:3px 12px;border-radius:99px">
                        {{ count($acciones) }} acciones
                      </span>
                    </td>
                  </tr>
                  </table>
                </td>
              </tr>

            </table>
          </td>
        </tr>
        </table>
      </td>
    </tr>

    <!-- ESPACIO -->
    <tr><td style="line-height:12px;font-size:1px;mso-line-height-rule:exactly">&nbsp;</td></tr>

    <!-- ── TABLA DE ACCIONES ── -->
    <tr>
      <td class="card" style="background:#ffffff;border-radius:12px;padding:28px 32px;border:1px solid #e2e2de">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">

          <!-- Título sección -->
          <tr>
            <td style="padding-bottom:16px;border-bottom:1px solid #eeeeea">
              <p style="margin:0;font-family:Arial, sans-serif;font-size:14px;font-weight:bold;color:#1a1a18">
                Detalle de facturas y acciones
              </p>
            </td>
          </tr>

          <!-- Encabezado tabla -->
          <tr>
            <td style="padding-top:14px">
              <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <thead>
                <tr style="background:#f8f8f6;border-radius:6px">
                  <td style="font-family:Arial, sans-serif;font-size:11px;font-weight:600;color:#6b6b67;text-transform:uppercase;letter-spacing:0.05em;padding:8px 10px;border-radius:6px 0 0 6px">
                    Factura
                  </td>
                  <td style="font-family:Arial, sans-serif;font-size:11px;font-weight:600;color:#6b6b67;text-transform:uppercase;letter-spacing:0.05em;padding:8px 10px">
                    N° Acción
                  </td>
                  <td style="font-family:Arial, sans-serif;font-size:11px;font-weight:600;color:#6b6b67;text-transform:uppercase;letter-spacing:0.05em;padding:8px 10px;border-radius:0 6px 6px 0;text-align:right">
                    Fecha
                  </td>
                </tr>
              </thead>
              </table>
            </td>
          </tr>

          <!-- Filas de datos -->
          @forelse($acciones as $a)
          <tr>
            <td style="padding:0 0 0">
              <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr style="border-bottom:1px solid #f0f0ec">
                <td style="padding:10px 10px;font-family:Arial, sans-serif;font-size:13px;color:#1a1a18;width:35%">
                  <span style="background:#e6f1fb;color:#185fa5;padding:3px 9px;border-radius:99px;font-size:12px;font-weight:600">
                    {{ $a->FACTURA }}
                  </span>
                </td>
                <td style="padding:10px 10px;font-family:Arial, sans-serif;font-size:13px;font-weight:bold;color:#1a1a18;width:30%">
                  {{ str_pad($a->NUMERO, 5, '0', STR_PAD_LEFT) }}
                </td>
                <td style="padding:10px 10px;font-family:Arial, sans-serif;font-size:13px;color:#6b6b67;text-align:right;width:35%">
                  {{ isset($a->FECHA_ASIGNACION) ? date('d M Y', strtotime($a->FECHA_ASIGNACION)) : 'No disponible' }}
                </td>
              </tr>
              </table>
            </td>
          </tr>
          @empty
          <tr>
            <td style="text-align:center;font-family:Arial, sans-serif;font-size:13px;color:#6b6b67;padding:32px 0">
              No se encontraron acciones registradas.
            </td>
          </tr>
          @endforelse

        </table>
      </td>
    </tr>

    <!-- ESPACIO -->
    <tr><td style="line-height:12px;font-size:1px;mso-line-height-rule:exactly">&nbsp;</td></tr>

    <!-- ── AVISO LEGAL / FOOTER ── -->
    <tr>
      <td style="padding:16px 0;text-align:center">
        <p style="margin:0 0 6px;font-family:Arial, sans-serif;font-size:12px;color:#6b6b67">
          Este correo fue generado automáticamente para
          <strong style="color:#1a1a18">{{ $nombre ?? $cliente }}</strong>.
        </p>
        <p style="margin:0 0 6px;font-family:Arial, sans-serif;font-size:12px;color:#6b6b67">
          Si usted no solicitó esta información, puede ignorar este mensaje.
        </p>
        <p style="margin:12px 0 0;font-family:Arial, sans-serif;font-size:12px;color:#9b9b96">
          &copy; {{ date('Y') }} Unimark S.A. &mdash; Supliendo Salud
        </p>
      </td>
    </tr>

  </table><!-- /contenedor principal -->
</td>
</tr>
</table>

</body>
</html>

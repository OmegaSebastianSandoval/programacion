<?php

/**
 *
 */
use Dompdf\Dompdf;
use Dompdf\Options;
class Page_indexController extends Page_mainController
{

  public function indexAction()
  {
    $this->_view->banner = $this->template->bannerPrincipal("1");
    $this->_view->contenido = $this->template->getContentseccion("1");

    $sedesModel = new Administracion_Model_DbTable_Sedes();
    $sedesRaw = $sedesModel->getList("sede_estado='1'", "sede_id ASC");

    $sedesForJs = [];
    foreach ($sedesRaw as $sede) {
      $sedesForJs[] = [
        'id' => (int) $sede->sede_id,
        'nombre' => $sede->sede_nombre,
        'direccion' => $sede->sede_direccion,
        'color' => $sede->sede_color ?: '#888888',
      ];
    }

    $eventosModel = new Administracion_Model_DbTable_Eventos();
    $eventosRaw = $eventosModel->getList(
      "evento_activo='1' AND evento_estado='activo'",
      "evento_fecha ASC"
    );

    $eventosPorFecha = [];
    foreach ($eventosRaw as $ev) {
      $dateKey = substr($ev->evento_fecha, 0, 10);
      $timeStr = substr($ev->evento_fecha, 11, 5);
      $eventosPorFecha[$dateKey][] = [
        'id' => (int) $ev->evento_id,
        'nombre' => $ev->evento_nombre,
        'descripcion' => $ev->evento_descripcion,
        'imagen' => $ev->evento_imagen,
        'fecha' => $dateKey,
        'hora' => $timeStr,
        'lugar' => (int) $ev->evento_lugar,
        'costo' => (int) $ev->evento_costo,
        'aforomaximo' => (int) $ev->evento_aforomaximo,
      ];
    }

    $this->_view->eventosJson = json_encode($eventosPorFecha, JSON_UNESCAPED_UNICODE);
    $this->_view->sedesJson = json_encode($sedesForJs, JSON_UNESCAPED_UNICODE);

  }

  public function pruebaenvioAction()
  {
    $this->setLayout('blanco');
    $emailModel = new Core_Model_Mail();
    $asunto = "PRUEBA DE ENVIO ";
    $tabla = "<table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Edad</th>
          <th>Relación</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Juan Pérez</td>
          <td>30</td>
          <td>Amigo</td>
        </tr>
        <tr>
          <td>María López</td>
          <td>25</td>
          <td>Hermana</td>
        </tr>
      </tbody>
    </table>";

    $content = $tabla;

    $bccs = [
      "desarrollo8@omegawebsystems.com",
    ];

    $emailModel->getMail()->Subject = $asunto;
    $emailModel->getMail()->msgHTML($content);
    $emailModel->getMail()->AltBody = $content;
    $emailModel->getMail()->SMTPDebug = 1;

    foreach ($bccs as $bcc) {
      $emailModel->getMail()->addBCC($bcc);
    }
    //$emailModel->getMail()->addAddress($email);

    // Intentar enviar
    $enviado = $emailModel->sed();
    if (!$enviado) {
      // Si falla, reintentar con Gmail
      $mail = $emailModel->getMail();
      // Reconfigurar
      $mail->isSMTP();
      $mail->SMTPDebug = 1;
      $mail->SMTPSecure = "ssl";
      $mail->Host = "mail.omegasolucionesweb.com";
      $mail->Port = 465;
      $mail->SMTPAuth = true;
      $mail->Username = "notificaciones@omegasolucionesweb.com";
      $mail->Password = "Admin.2008";
      $mail->setFrom("notificaciones@omegasolucionesweb.com", "Notificaciones");
      // Limpiar destinatarios y volver a agregarlos
      $mail->clearAddresses();
      $mail->clearBCCs();
      foreach ($bccs as $bcc) {
        $mail->addBCC($bcc);
      }
      //$mail->addAddress($email);
      $mail->Subject = $asunto;
      $mail->msgHTML($content);
      $mail->AltBody = $content;
      $enviado = $mail->send();
    }
    echo $emailModel->getMail()->ErrorInfo;
  }
  public function pruebapdfAction()
  {

    // Genera un PDF de prueba con varios elementos HTML
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    $html = <<<HTML
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Prueba PDF</title>
  <style>
    body{font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color:#333}
    .header{background:#0d6efd;color:#fff;padding:12px;border-radius:6px}
    h1{margin:0}
    .lead{font-size:14px;color:#666}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{border:1px solid #ddd;padding:8px;text-align:left}
    th{background:#f4f4f4}
    .two-col{display:flex;gap:12px;margin-top:12px}
    .box{flex:1;padding:10px;border:1px solid #e6e6e6;border-radius:6px}
    ul{margin:0;padding-left:18px}
    .small{font-size:12px;color:#666}
  </style>
</head>
<body>
  <div class="header"><h1>Documento de Prueba</h1></div>
  <p class="lead">Este PDF contiene varios elementos HTML para verificar renderizado: encabezados, listas, tablas, imágenes SVG y estilos inline.</p>

  <div class="two-col">
    <div class="box">
      <h3>Lista de elementos</h3>
      <ul>
        <li>Encabezados (h1,h2,h3)</li>
        <li>Tabla con datos</li>
        <li>Imagen SVG inline</li>
        <li>Estilos CSS básicos</li>
      </ul>
    </div>
    <div class="box">
      <h3>Imagen SVG</h3>
      <svg xmlns="http://www.w3.org/2000/svg" width="180" height="100" viewBox="0 0 180 100">
        <rect width="180" height="100" rx="8" fill="#f8f9fa" stroke="#ced4da"/>
        <g font-family="DejaVu Sans, Arial" font-size="14" fill="#212529">
          <text x="12" y="30">Omega</text>
          <text x="12" y="52">Soluciones Web</text>
        </g>
      </svg>
      <p class="small">SVG inline incluido para probar gráficos vectoriales.</p>
    </div>
  </div>

  <h2>Tabla de ejemplo</h2>
  <table>
    <thead>
      <tr><th>Nombre</th><th>Edad</th><th>Rol</th></tr>
    </thead>
    <tbody>
      <tr><td>Juan Pérez</td><td>30</td><td>Administrador</td></tr>
      <tr><td>María López</td><td>25</td><td>Editor</td></tr>
      <tr><td>Carlos Gómez</td><td>28</td><td>Consultor</td></tr>
    </tbody>
  </table>

  <h3>Notas</h3>
  <p class="small">Generado por Dompdf. Prueba de estilos y elementos HTML. Fecha: <!--DATE--></p>
</body>
</html>
HTML;

    // reemplaza marcador de fecha
    $html = str_replace('<!--DATE-->', date('Y-m-d H:i:s'), $html);

    $dompdf->loadHtml($html);

    // Opcional: orientación y tamaño
    $dompdf->setPaper('A4', 'portrait');

    // Renderiza el PDF
    $dompdf->render();
    // Nombre del archivo
    $filename = 'prueba-html-' . date('YmdHis') . '.pdf';

    // Guardar copia en /public/files dentro del proyecto
    $output = $dompdf->output();
    $projectRoot = dirname(__DIR__, 4);
    $filesDir = $projectRoot . '/public/files';
    if (!is_dir($filesDir)) {
      mkdir($filesDir, 0755, true);
    }
    $filePath = $filesDir . '/' . $filename;
    @file_put_contents($filePath, $output);

    // Si viene ?download=1 fuerza la descarga, si no lo muestra en el navegador
    $attachment = (isset($_GET['download']) && $_GET['download'] == '1') ? 1 : 0;

    // Envía el PDF al navegador
    $dompdf->stream($filename, ['Attachment' => $attachment]);
  }
}


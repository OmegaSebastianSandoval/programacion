<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->_titlepage ?></title>
  <?php $infopageModel = new Page_Model_DbTable_Informacion();
  $infopage = $infopageModel->getById(1);
  ?>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="/components/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/components/Font-Awesome/css/all.css">
  <!-- CSS Global -->
  <link rel="stylesheet" href="/skins/administracion/css/global.css">

  <link rel="shortcut icon" href="/images/<?= $infopage->info_pagina_favicon; ?>">

</head>

<body class="auth-page">
  <div class="auth-background"></div>

  <div class="auth-layout">
    <main class="auth-main">
      <div class="auth-card">
        <div class="auth-card-header">
          <div class="auth-logo">
            <img src="/skins/administracion/images/logo-horizontal.png" alt="Logo">
          </div>
        </div>

        <div class="auth-card-content">
          <?= $this->_content ?>
        </div>
      </div>

      <footer class="auth-footer">
        <p>&copy; <?php echo date('Y') ?> Todos los derechos reservados | Diseñado por <a
            href="https://omegasolucionesweb.com" target="_blank">OMEGA SOLUCIONES WEB</a></p>
        <p>info@omegawebsystems.com | 318 642 5229 | 350 708 7228</p>
      </footer>
    </main>
  </div>

  <!-- jQuery -->
  <script src="/components/jquery/jquery-4.0.0.min.js"></script>

  <!-- Bootstrap JS -->
  <script src="/components/bootstrap/js/bootstrap.bundle.min.js"></script>
  <?php include APP_PATH . 'layout/partials/cookies-consent.php'; ?>

</body>

</html>
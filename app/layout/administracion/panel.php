<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>
    <?= $this->_titlepage ?>
  </title>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWYVxdF4VwIPfmB65X2kMt342GbUXApwQ&sensor=true">
  </script>
  <?php $infopageModel = new Page_Model_DbTable_Informacion();
  $infopage = $infopageModel->getById(1);
  ?>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="/components/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/components/bootstrap-toggle/bootstrap5-toggle.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/components/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.min.css">
  <!-- Fileinput -->
  <link rel="stylesheet" href="/components/bootstrap-fileinput/css/fileinput.css">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="/components/Font-Awesome/css/all.css">
  <!-- Colorpicker -->
  <link rel="stylesheet" href="/components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="/components/select2/css/select2.min.css">
  <!-- Global CSS -->
  <link rel="stylesheet" href="/skins/administracion/css/global.css">
  <link rel="shortcut icon" href="/images/<?= $infopage->info_pagina_favicon; ?>">

 
</head>

<body>
  <header>
    <?= $this->_data['panel_header']; ?>
  </header>
  <div class="container-fluid panel p-0">
    <div class="d-flex justify-content-start">
      <nav id="panel-botones">
        <?= $this->_data['panel_botones']; ?>
      </nav>
      <article id="contenido_panel" class="w-100
      ">
        <section id="contenido_general">
          <div class="panel-titulo"><b>Dashboard</b> Versión 6.0</div>
          <?= $this->_content ?>
        </section>
      </article>
    </div>
  </div>
  <footer class="panel-derechos col-md-12">&copy;Todos los Derechos Reservados <?php echo date('Y'); ?> - Diseñado por
    Omega Soluciones Web
  </footer>
  <!-- Jquery -->
  <script src="/components/jquery/jquery-4.0.0.min.js"></script>
  <!-- Bootstrap Js -->
  <script src="/components/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/components/bootstrap-datepicker/js/bootstrap-datepicker.min.js">
  </script>
  <script src="/components/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js">
  </script>
  <!-- File Input -->
  <script src="/components/bootstrap-fileinput/js/fileinput.min.js"></script>
  <script src="/components/bootstrap-fileinput/js/locales/es.js"></script>

  <script src="/components/bootstrap-toggle/bootstrap5-toggle.jquery.min.js"></script>
  <script src="/components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>

  <script src="/skins/administracion/js/form-validator.js"></script>

  <!-- CKEditor 5 -->
  <script src="/components/ckeditor5/ckeditor.js"></script>

  <!-- Select2 -->
  <script src="/components/select2/js/select2.min.js"></script>
  <!-- main Js -->
  <script src="/skins/administracion/js/main.js"></script>
  <?php include APP_PATH . 'layout/partials/cookies-consent.php'; ?>
</body>

</html>
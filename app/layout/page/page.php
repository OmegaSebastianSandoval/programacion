<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title><?= $this->_titlepage ?></title>
  <?php $infopageModel = new Page_Model_DbTable_Informacion();
  $infopage = $infopageModel->getById(1);
  ?>
  <!-- Jquery -->
  <script src="/components/jquery/jquery-4.0.0.min.js"></script>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="/components/bootstrap/css/bootstrap.min.css">

  <!-- Global CSS -->
  <link rel="stylesheet" href="/skins/page/css/global.css?v=2">
  <link rel="stylesheet" href="/skins/page/css/responsive.css?v=2">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="/components/Font-Awesome/css/all.css">

  <link rel="shortcut icon" href="/images/<?= $infopage->info_pagina_favicon; ?>">

  <link rel="stylesheet" type="text/css" href="/components/slick/slick.css" />
  <link rel="stylesheet" type="text/css" href="/components/slick/slick-theme.css"/>

  <script type="text/javascript" src="/components/slick/slick.min.js"></script>

  <script type="text/javascript" id="www-widgetapi-script"
    src="https://s.ytimg.com/yts/jsbin/www-widgetapi-vflS50iB-/www-widgetapi.js" async=""></script>


  <!-- Bootstrap Js -->
  <script src="/components/bootstrap/js/bootstrap.bundle.min.js"></script>


  <!-- <script src="/components/jquery-knob/js/jquery.knob.js"></script> -->

  <!-- SweetAlert -->


  <!-- Main Js -->
  <script src="/skins/page/js/main.js?v=2"></script>

  <!-- Recaptcha -->
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <meta name="description" content="<?= $this->_data['meta_description']; ?>" />
  <meta name=" keywords" content="<?= $this->_data['meta_keywords']; ?>" />
  <?php echo $this->_data['scripts']; ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
  <?= $this->_data['header']; ?>
  <main class="main-general"><?= $this->_content ?></main>
  <?= $this->_data['footer']; ?>
  <?= $this->_data['adicionales']; ?>
  <?php include APP_PATH . 'layout/partials/cookies-consent.php'; ?>

</body>

</html>
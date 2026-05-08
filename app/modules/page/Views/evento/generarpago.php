<?php /* sessionId y epaycoTest son asignados por generarpagoAction */ ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Procesando pago — Galería Café Libro</title>
  <style>
    :root {
      --rosa:    #dd1279;
      --amarillo: #F7CE04;
      --verde:   #0b4b3e;
      --verde-dark: #083830;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Open Sans', sans-serif;
      overflow: hidden;
      background: var(--verde-dark);
    }

    .bg-canvas {
      position: fixed;
      inset: 0;
      z-index: 0;
      background: linear-gradient(150deg, #083830 0%, #0b4b3e 45%, #062e25 100%);
    }

    .bg-shape {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      animation: float 14s ease-in-out infinite;
    }
    /* Rosa arriba-izquierda */
    .s1 { width: 650px; height: 650px; background: var(--rosa);    opacity: .13; top: -220px; left: -160px; animation-delay: 0s; }
    /* Amarillo abajo-derecha */
    .s2 { width: 500px; height: 500px; background: var(--amarillo); opacity: .10; bottom: -180px; right: -140px; animation-delay: 5s; }
    /* Rosa suave centro */
    .s3 { width: 320px; height: 320px; background: var(--rosa);    opacity: .06; top: 48%; left: 54%; transform: translate(-50%,-50%); animation-delay: 9s; }

    .bg-grid {
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(247,206,4,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(247,206,4,.04) 1px, transparent 1px);
      background-size: 64px 64px;
    }

    .bg-lines {
      position: absolute;
      inset: 0;
      overflow: hidden;
    }
    .bg-lines span {
      position: absolute;
      display: block;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(247,206,4,.30), transparent);
      animation: scan 9s linear infinite;
      opacity: 0;
    }
    .bg-lines span:nth-child(1) { top: 22%; width: 55%; left: 22%; animation-delay: 0s; }
    .bg-lines span:nth-child(2) { top: 48%; width: 75%; left: 12%; animation-delay: 3s; }
    .bg-lines span:nth-child(3) { top: 72%; width: 45%; left: 32%; animation-delay: 6s; }

    @keyframes float {
      0%, 100% { transform: translateY(0) scale(1); }
      50%       { transform: translateY(-26px) scale(1.04); }
    }
    @keyframes scan {
      0%   { opacity: 0; transform: scaleX(0); transform-origin: left; }
      40%  { opacity: 1; }
      60%  { opacity: 1; }
      100% { opacity: 0; transform: scaleX(1); }
    }

    .card-pago {
      position: relative;
      z-index: 1;
      background: rgba(255,255,255,.05);
      border: 1px solid rgba(247,206,4,.20);
      border-radius: 22px;
      padding: 52px 44px 44px;
      text-align: center;
      max-width: 430px;
      width: 92%;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      box-shadow: 0 0 70px rgba(11,75,62,.6), 0 0 30px rgba(247,206,4,.05);
    }

    .logo-wrap { margin-bottom: 36px; }
    .logo-wrap img {
      height: 60px;
      object-fit: contain;
      /* logo sobre fondo verde oscuro — sin inversión para mantener colores propios */
      filter: drop-shadow(0 0 12px rgba(247,206,4,.25));
      opacity: .95;
    }

    .spinner-ring {
      width: 56px; height: 56px;
      margin: 0 auto 30px;
      position: relative;
    }
    .spinner-ring::before,
    .spinner-ring::after {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: 50%;
      border: 3px solid transparent;
    }
    .spinner-ring::before {
      border-top-color: var(--amarillo);
      animation: spin .85s linear infinite;
    }
    .spinner-ring::after {
      border-bottom-color: rgba(221,18,121,.40);
      animation: spin 1.4s linear infinite reverse;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    h1 {
      font-size: 1.1rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 10px;
      letter-spacing: -.01em;
    }
    p.sub {
      font-size: .82rem;
      color: rgba(255,255,255,.45);
      line-height: 1.7;
    }

    .badge-secure {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      margin-top: 30px;
      padding: 6px 16px;
      background: rgba(247,206,4,.08);
      border: 1px solid rgba(247,206,4,.22);
      border-radius: 100px;
      font-size: .73rem;
      color: rgba(255,255,255,.60);
      letter-spacing: .02em;
    }
    .dot-live {
      width: 7px; height: 7px;
      background: var(--amarillo);
      border-radius: 50%;
      animation: pulse 1.6s ease-in-out infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50%       { opacity: .4; transform: scale(.75); }
    }
  </style>
</head>

<body>

  <div class="bg-canvas">
    <div class="bg-shape s1"></div>
    <div class="bg-shape s2"></div>
    <div class="bg-shape s3"></div>
    <div class="bg-grid"></div>
    <div class="bg-lines">
      <span></span><span></span><span></span>
    </div>
  </div>

  <div class="card-pago">
    <div class="logo-wrap">
      <img src="/images/logogaleria.png" alt="Galería Café Libro">
    </div>
    <div class="spinner-ring"></div>
    <h1>Abriendo pasarela de pago</h1>
    <p class="sub">Estamos cargando el formulario seguro.<br>Por favor no cierres esta ventana.</p>
    <div class="badge-secure">
      <span class="dot-live"></span>
      Conexión segura con ePayco
    </div>
  </div>

  <script src="https://checkout.epayco.co/checkout-v2.js"></script>
  <script>
    window.addEventListener('load', function () {
      var checkout = ePayco.checkout.configure({
        sessionId: '<?= htmlspecialchars($this->sessionId ?? '', ENT_QUOTES, 'UTF-8') ?>',
        type: 'onpage',
        test: <?= ($this->epaycoTest) ? 'true' : 'false' ?>
      });
      checkout.onErrors(function (errors) {
        console.error('ePayco error:', errors);
      });
      checkout.open();
    });
  </script>

</body>

</html>
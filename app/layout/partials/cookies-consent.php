<?php
$cookieConsentAccepted = isset($_COOKIE['cookie_consent']) && $_COOKIE['cookie_consent'] === 'accepted';

if (!$cookieConsentAccepted):
  $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
  $currentPath = strtok($requestUri, '?');
  $currentQuery = [];

  if (!empty($_SERVER['QUERY_STRING'])) {
    parse_str($_SERVER['QUERY_STRING'], $currentQuery);
  }

  $currentQuery['cookie_consent'] = 'accept';
  $acceptUrl = $currentPath . '?' . http_build_query($currentQuery);
  ?>
  <div id="cookie-consent-overlay" role="dialog" aria-modal="true" aria-labelledby="cookie-consent-title">
    <div class="cookie-consent-card">
      <h2 id="cookie-consent-title">Uso de cookies</h2>
      <p>
        Este sitio utiliza cookies necesarias para operar correctamente y mejorar la experiencia de navegacion.
        Debes aceptar el uso de cookies para continuar.
      </p>
      <a class="cookie-consent-button" href="<?= htmlspecialchars($acceptUrl, ENT_QUOTES, 'UTF-8') ?>">Aceptar cookies</a>
    </div>
  </div>

  <style>
    #cookie-consent-overlay {
      position: fixed;
      inset: 0;
      z-index: 2147483647;
      background: rgba(15, 23, 42, 0.78);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    #cookie-consent-overlay .cookie-consent-card {
      width: min(100%, 560px);
      background: #ffffff;
      border-radius: 14px;
      padding: 28px;
      box-shadow: 0 24px 60px rgba(0, 0, 0, 0.3);
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      color: #1f2937;
    }

    #cookie-consent-overlay h2 {
      margin: 0 0 12px;
      font-size: 1.5rem;
      line-height: 1.2;
    }

    #cookie-consent-overlay p {
      margin: 0;
      line-height: 1.55;
      font-size: 1rem;
    }

    #cookie-consent-overlay .cookie-consent-button {
      display: inline-block;
      margin-top: 20px;
      background: #111827;
      color: #ffffff;
      text-decoration: none;
      border-radius: 10px;
      padding: 12px 20px;
      font-weight: 600;
    }

    #cookie-consent-overlay .cookie-consent-button:hover {
      background: #000000;
    }
  </style>
<?php endif; ?>
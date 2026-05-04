<?php
if (!isset($title)) {
  $title = 'Error';
}
if (!isset($message)) {
  $message = 'Ha ocurrido un error inesperado.';
}

// Determinar destino del botón "Ir al inicio": si la URL contiene 'administracion' vamos a /administracion
$homeUrl = '/';
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'administracion') !== false) {
  $homeUrl = '/administracion';
}
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($title); ?></title>
  <style>
    :root {
      --bg: #0f1724;
      --card: #0b1220;
      --accent: #ff6b6b;
      --muted: #9aa4b2
    }

    html,
    body {
      height: 100%;
      margin: 0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial
    }

    body {
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(180deg, #071025 0%, #0a1a2b 100%);
      color: #e6eef6;
      flex-direction: column;
    }

    .card {
      max-width: 820px;
      width: 92%;
      background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.01));
      border-radius: 12px;
      box-shadow: 0 6px 30px rgba(2, 6, 23, 0.7);
      padding: 28px;
      display: flex;
      gap: 24px;
      align-items: flex-start
    }

    .icon {
      flex: 0 0 72px;
      height: 72px;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--accent), #ff8a8a);
      display: grid;
      place-items: center;
      font-weight: 700
    }

    h1 {
      margin: 0;
      font-size: 20px
    }

    p {
      margin: 8px 0 0;
      color: var(--muted)
    }

    .meta {
      margin-top: 14px;
      background: rgba(255, 255, 255, 0.02);
      padding: 12px;
      border-radius: 8px;
      font-family: monospace;
      color: #cbe0ff;
      font-size: 13px
    }

    .actions {
      margin-left: auto;
      display: flex;
      gap: 8px;
      min-width: 100px;

    }

    a.btn {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 8px 12px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.02);
      border: 1px solid rgba(255, 255, 255, 0.04);
      color: #dff0ff;
      text-decoration: none;
      font-size: 13px;
      min-height: 38px;
      text-align: center;
      justify-content: center;
    }

    .btn .icon {
      display: inline-grid;
      place-items: center;
      width: 28px;
      height: 28px;
      border-radius: 8px;
      background: rgba(255, 107, 107, 0.14);
      box-shadow: 0 2px 8px rgba(2, 6, 23, 0.45);
    }

    @media(max-width:640px) {
      .card {
        flex-direction: column;
        align-items: stretch
      }

      .actions {
        justify-content: flex-end
      }
    }
  </style>
</head>

<body>
  <div class="card">
    <div class="icon">⚠️</div>
    <div>
      <h1><?php echo htmlspecialchars($title); ?></h1>
      <p><?php echo htmlspecialchars($message); ?></p>
      <?php if (!empty($details)): ?>
        <div class="meta"><?php echo $details; ?></div>
      <?php endif; ?>
    </div>
    <div class="actions">
      <a class="btn" href="<?php echo htmlspecialchars($homeUrl); ?>">

        Ir al inicio
      </a>
    </div>
  </div>
</body>

</html>
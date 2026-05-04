<?php
/*
 * Diseño 10 — Testimonial / Quote Card
 * Cita con comillas decorativas, estrellas, foto circular del autor y nombre/rol.
 * Ideal para: testimonios de clientes, reseñas, frases destacadas.
 *
 * contenido_descripcion → el texto de la cita / testimonio
 * contenido_titulo      → nombre del autor (si contenido_titulo_ver = 1)
 * contenido_imagen      → foto del autor (circular, 52×52)
 * contenido_fondo_color → color de acento / estrellas (hex); default acento global
 * contenido_borde       → 1 = variante oscura (fondo oscuro, texto blanco)
 * contenido_vermas      → cargo / empresa del autor (opcional)
 *                         Si es un número del 1 al 5, muestra esa cantidad de estrellas.
 *                         Ej: "5" → ★★★★★  |  "Directora de Marketing" → cargo
 */
$d10id     = $contenido->contenido_id;
$d10accent = $contenido->contenido_fondo_color ?: 'var(--color-acento, #4f46e5)';
$d10dark   = $contenido->contenido_borde == '1';

// ¿contenido_vermas es un número (estrellas) o un cargo?
$d10vermas  = trim($contenido->contenido_vermas ?? '');
$d10stars   = 0;
$d10role    = '';
if ($d10vermas !== '') {
    if (is_numeric($d10vermas) && $d10vermas >= 1 && $d10vermas <= 5) {
        $d10stars = (int) $d10vermas;
    } else {
        $d10role = $d10vermas;
    }
}

$d10bg       = $d10dark ? '#1f2a37'              : '#fff';
$d10cardBg   = $d10dark ? 'rgba(255,255,255,.05)' : '#f8f9fc';
$d10textCol  = $d10dark ? 'rgba(255,255,255,.88)' : 'var(--color-texto-suave, #444)';
$d10nameCol  = $d10dark ? '#fff'                  : 'var(--color-texto, #1f2a37)';
$d10roleCol  = $d10dark ? 'rgba(255,255,255,.5)'  : '#888';
$d10quoteCol = $d10dark ? 'rgba(255,255,255,.08)' : 'rgba(79,70,229,.07)';
?>
<style>
  .d10-<?= $d10id ?> {
    background: <?= $d10bg ?>;
    border-radius: 14px;
    padding: 32px 30px 28px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    gap: 18px;
    transition: box-shadow .3s ease, transform .3s ease;
  }
  .d10-<?= $d10id ?>:hover {
    box-shadow: 0 12px 36px rgba(0,0,0,.12);
    transform: translateY(-3px);
  }
  .d10-qmark-<?= $d10id ?> {
    position: absolute;
    top: 12px;
    right: 20px;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 8rem;
    line-height: 1;
    color: <?= $d10quoteCol ?>;
    pointer-events: none;
    user-select: none;
  }
  .d10-stars-<?= $d10id ?> {
    display: flex;
    gap: 2px;
    color: <?= $d10accent ?>;
    font-size: 1rem;
  }
  .d10-stars-<?= $d10id ?> .d10-star-empty {
    color: <?= $d10dark ? 'rgba(255,255,255,.2)' : '#ddd' ?>;
  }
  .d10-text-<?= $d10id ?> {
    position: relative;
    font-size: .95rem;
    font-style: italic;
    color: <?= $d10textCol ?>;
    line-height: 1.75;
    margin: 0;
  }
  .d10-author-<?= $d10id ?> {
    display: flex;
    align-items: center;
    gap: 14px;
    padding-top: 16px;
    border-top: 1px solid <?= $d10dark ? 'rgba(255,255,255,.1)' : 'rgba(0,0,0,.07)' ?>;
  }
  .d10-photo-<?= $d10id ?> {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    border: 2.5px solid <?= $d10accent ?>;
    flex-shrink: 0;
  }
  .d10-avatar-<?= $d10id ?> {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: <?= $d10accent ?>;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.3rem;
    flex-shrink: 0;
  }
  .d10-info-<?= $d10id ?> {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }
  .d10-name-<?= $d10id ?> {
    font-weight: 800;
    font-size: .92rem;
    color: <?= $d10nameCol ?>;
    margin: 0;
  }
  .d10-role-<?= $d10id ?> {
    font-size: .78rem;
    color: <?= $d10roleCol ?>;
    margin: 0;
  }
  .d10-sep-<?= $d10id ?> {
    width: 28px;
    height: 2.5px;
    background: <?= $d10accent ?>;
    border-radius: 2px;
    margin-bottom: 4px;
  }
</style>

<div class="caja-contenido-simple design-ten d10-<?= $d10id ?>">
  <span class="d10-qmark-<?= $d10id ?>" aria-hidden="true">&ldquo;</span>

  <?php if ($d10stars > 0): ?>
    <div class="d10-stars-<?= $d10id ?>" aria-label="<?= $d10stars ?> de 5 estrellas">
      <?php for ($i = 1; $i <= 5; $i++): ?>
        <i class="fas fa-star<?= $i > $d10stars ? ' d10-star-empty' : '' ?>" aria-hidden="true"></i>
      <?php endfor; ?>
    </div>
  <?php endif; ?>

  <?php if ($contenido->contenido_descripcion): ?>
    <p class="d10-text-<?= $d10id ?>"><?= strip_tags($contenido->contenido_descripcion) ?></p>
  <?php endif; ?>

  <?php if ($contenido->contenido_titulo_ver == 1 && $contenido->contenido_titulo): ?>
    <div class="d10-author-<?= $d10id ?>">
      <?php if ($contenido->contenido_imagen): ?>
        <img class="d10-photo-<?= $d10id ?>"
          src="/images/<?= htmlspecialchars($contenido->contenido_imagen) ?>"
          alt="<?= htmlspecialchars($contenido->contenido_titulo) ?>"
          loading="lazy">
      <?php else: ?>
        <div class="d10-avatar-<?= $d10id ?>" aria-hidden="true">
          <i class="fas fa-user"></i>
        </div>
      <?php endif; ?>
      <div class="d10-info-<?= $d10id ?>">
        <div class="d10-sep-<?= $d10id ?>" aria-hidden="true"></div>
        <p class="d10-name-<?= $d10id ?>"><?= htmlspecialchars($contenido->contenido_titulo) ?></p>
        <?php if ($d10role): ?>
          <p class="d10-role-<?= $d10id ?>"><?= htmlspecialchars($d10role) ?></p>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</div>

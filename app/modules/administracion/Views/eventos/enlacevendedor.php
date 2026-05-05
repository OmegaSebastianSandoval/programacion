<?php
$enlace     = $this->enlace;
$enlaceHtml = htmlspecialchars($enlace, ENT_QUOTES);
$eventoNombre   = htmlspecialchars($this->evento->evento_nombre);
$vendedorNombre = htmlspecialchars($this->vendedor_nombre);
$eventoId   = (int)$this->evento->evento_id;
$vendedorId = (int)$this->vendedor_id;
?>
<h1 class="titulo-principal"><i class="fas fa-link"></i> <?= $this->titlesection; ?></h1>

<div class="container-fluid">
	<div class="content-dashboard" style="max-width:600px; margin-left:auto; margin-right:auto;">

		<div class="row g-0 mb-4 pb-3" style="border-bottom:1px solid var(--border);">
			<div class="col-auto me-3 d-flex align-items-center">
				<span style="width:42px;height:42px;border-radius:50%;background:var(--brand-green-bg);display:flex;align-items:center;justify-content:center;">
					<i class="fas fa-calendar-alt" style="color:var(--brand-green-dk);font-size:.95rem;"></i>
				</span>
			</div>
			<div class="col">
				<div style="font-size:.72rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--text-4);margin-bottom:2px;">Evento</div>
				<div style="font-size:.95rem;font-weight:600;color:var(--text-1);"><?= $eventoNombre; ?></div>
			</div>
			<div class="col-auto ms-4 d-flex align-items-center">
				<span style="width:42px;height:42px;border-radius:50%;background:var(--surface-3);display:flex;align-items:center;justify-content:center;">
					<i class="fas fa-user" style="color:var(--text-3);font-size:.95rem;"></i>
				</span>
			</div>
			<div class="col">
				<div style="font-size:.72rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--text-4);margin-bottom:2px;">Vendedor</div>
				<div style="font-size:.95rem;font-weight:600;color:var(--text-1);"><?= $vendedorNombre; ?></div>
			</div>
		</div>

		<div class="mb-4">
			<label style="font-size:.72rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--text-4);display:block;margin-bottom:8px;">
				<i class="fas fa-link" style="margin-right:4px;color:var(--brand-green);"></i>Enlace de afiliado
			</label>
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text input-icono"><i class="fas fa-globe"></i></span>
				</div>
				<input type="text" id="enlace-url" class="form-control" value="<?= $enlaceHtml; ?>" readonly
					style="font-size:.82rem;color:var(--text-3);background:var(--surface-2);">
				<button class="btn btn-guardar" type="button" id="btn-copiar" onclick="copiarEnlace()" style="padding:8px 18px;border-radius:0 var(--r-sm) var(--r-sm) 0;">
					<i class="fas fa-copy"></i> Copiar
				</button>
			</div>
			<div id="msg-copiado" style="display:none;font-size:.78rem;color:var(--success);margin-top:6px;">
				<i class="fas fa-check-circle"></i> Enlace copiado al portapapeles
			</div>
		</div>

		<div class="mb-4">
			<label style="font-size:.72rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--text-4);display:block;margin-bottom:12px;">
				<i class="fas fa-qrcode" style="margin-right:4px;color:var(--brand-green);"></i>Código QR
			</label>
			<div style="display:flex;justify-content:center;">
				<div style="background:#fff;border:1px solid var(--border);border-radius:var(--r-lg);padding:20px;box-shadow:var(--shadow-sm);display:inline-block;">
					<div id="qr-container"></div>
				</div>
			</div>
		</div>

	</div>

	<div class="botones-acciones">
		<button class="btn btn-guardar" type="button" onclick="descargarQR()">
			<i class="fas fa-download"></i> Descargar QR
		</button>
		<a href="<?= $this->route; ?>/manage?id=<?= $eventoId; ?>" class="btn btn-cancelar">
			<i class="fas fa-arrow-left"></i> Volver
		</a>
	</div>
</div>

<script src="/components/qrcode/qrcode.min.js"></script>
<script>
	const enlaceUrl = <?= json_encode($enlace); ?>;

	new QRCode(document.getElementById('qr-container'), {
		text: enlaceUrl,
		width: 200,
		height: 200,
		correctLevel: QRCode.CorrectLevel.M
	});

	function copiarEnlace() {
		navigator.clipboard.writeText(enlaceUrl).then(function () {
			mostrarCopiado();
		}).catch(function () {
			const input = document.getElementById('enlace-url');
			input.select();
			document.execCommand('copy');
			mostrarCopiado();
		});
	}

	function mostrarCopiado() {
		const msg = document.getElementById('msg-copiado');
		const btn = document.getElementById('btn-copiar');
		msg.style.display = 'block';
		btn.innerHTML = '<i class="fas fa-check"></i> Copiado';
		setTimeout(function () {
			msg.style.display = 'none';
			btn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
		}, 2500);
	}

	function descargarQR() {
		const canvas = document.querySelector('#qr-container canvas');
		if (!canvas) return;
		const a = document.createElement('a');
		a.download = 'qr-vendedor-<?= $vendedorId; ?>-evento-<?= $eventoId; ?>.png';
		a.href = canvas.toDataURL('image/png');
		a.click();
	}
</script>

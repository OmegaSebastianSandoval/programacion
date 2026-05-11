<?php
$esReservaGratuita = ($this->reservaGratuita);
$compra = $this->compra ?? null;
$reserva = $this->reserva ?? null;
$evento = $this->evento ?? null;
$sede = $this->sede ?? null;
$correoRes = isset($this->correoRes) ? (int) $this->correoRes : 0;
?>

<div class="resp-page">
	<div class="container">
		<div class="resp-card">

			<!-- Cabecera -->
			<div class="resp-card-header">
				<div class="resp-card-header-icon">
					<i class="fas <?= $esReservaGratuita ? 'fa-calendar-check' : 'fa-credit-card' ?>"></i>
				</div>
				<div>
					<h1 class="resp-card-header-title">
						<?= $esReservaGratuita ? 'Reserva confirmada' : 'Respuesta de transacción' ?>
					</h1>
					<p class="resp-card-header-sub">
						<?= $esReservaGratuita ? 'Tu reserva gratuita ha sido registrada.' : 'Verificando el estado de tu pago…' ?>
					</p>
				</div>
			</div>

			<?php if ($esReservaGratuita): ?>

				<!-- Reserva gratuita confirmada -->
				<div id="resp-content">

					<?php if ($correoRes === 1): ?>
						<div class="alert alert-success d-flex align-items-center gap-2 mb-3" role="alert">
							<i class="fas fa-envelope-circle-check"></i>
							<span>Te enviamos un correo de confirmación con los detalles de tu reserva.</span>
						</div>
					<?php elseif ($correoRes === 2): ?>
						<div class="alert alert-warning d-flex align-items-center gap-2 mb-3" role="alert">
							<i class="fas fa-triangle-exclamation"></i>
							<span>Tu reserva fue registrada, pero no pudimos enviarte el correo de confirmación. Guarda el número de reserva.</span>
						</div>
					<?php endif; ?>

					<div class="resp-status-block">
						<div class="resp-status-icon resp-status-icon--ok">
							<i class="fas fa-check-circle"></i>
						</div>
						<h2 class="resp-status-title">¡Reserva confirmada!</h2>
						<p class="resp-status-msg">Tu reserva gratuita ha sido registrada con éxito. Recibirás un correo con los
							detalles.</p>
					</div>

					<?php if ($evento): ?>
						<div class="resp-event-card">
							<?php if ($evento->evento_imagen): ?>
								<img class="resp-event-img" src="/images/<?= ($evento->evento_imagen) ?>"
									alt="<?= ($evento->evento_nombre) ?>">
							<?php endif; ?>
							<div class="resp-event-info">
								<h3 class="resp-event-title"><?= ($evento->evento_nombre) ?></h3>
								<div class="resp-event-meta">
									<?php if ($evento->evento_fecha): ?>
										<span><i class="fas fa-calendar-alt"></i>
											<?php
											$ts = strtotime($evento->evento_fecha);
											$meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
											echo date('j', $ts) . ' de ' . $meses[(int) date('n', $ts) - 1] . ' de ' . date('Y', $ts);
											?>
										</span>
									<?php endif; ?>
									<?php if ($evento->evento_hora): ?>
										<span><i class="fas fa-clock"></i> <?= ($evento->evento_hora) ?></span>
									<?php endif; ?>
									<?php if ($sede && $sede->sede_nombre): ?>
										<span><i class="fas fa-map-marker-alt"></i> <?= ($sede->sede_nombre) ?></span>
									<?php endif; ?>
									<?php if ($sede && $sede->sede_direccion): ?>
										<span><i class="fas fa-road"></i> <?= ($sede->sede_direccion) ?></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<?php if ($compra): ?>
						<div class="resp-details">
							<div class="resp-detail-grid">
								<div class="resp-detail-card">
									<div class="resp-detail-icon resp-detail-icon--ref"><i class="fas fa-hashtag"></i></div>
									<div class="resp-detail-body">
										<span class="resp-detail-label">N° de reserva</span>
										<span class="resp-detail-value"><?= ($compra->boleta_compra_id) ?></span>
									</div>
								</div>
								<?php if ($reserva && $reserva->reserva_cantidad_personas): ?>
									<div class="resp-detail-card">
										<div class="resp-detail-icon resp-detail-icon--ref"><i class="fas fa-users"></i></div>
										<div class="resp-detail-body">
											<span class="resp-detail-label">Personas</span>
											<span class="resp-detail-value"><?= (int) $reserva->reserva_cantidad_personas ?></span>
										</div>
									</div>
								<?php endif; ?>
								<div class="resp-detail-card">
									<div class="resp-detail-icon resp-detail-icon--ref"><i class="fas fa-user"></i></div>
									<div class="resp-detail-body">
										<span class="resp-detail-label">Nombre</span>
										<span class="resp-detail-value"><?= ($compra->boleta_compra_nombre) ?></span>
									</div>
								</div>
								<div class="resp-detail-card">
									<div class="resp-detail-icon resp-detail-icon--ref"><i class="fas fa-envelope"></i></div>
									<div class="resp-detail-body">
										<span class="resp-detail-label">Correo</span>
										<span class="resp-detail-value"><?= ($compra->boleta_compra_email) ?></span>
									</div>
								</div>
								<div class="resp-detail-card">
									<div class="resp-detail-icon resp-detail-icon--total"><i class="fas fa-dollar-sign"></i></div>
									<div class="resp-detail-body">
										<span class="resp-detail-label">Total</span>
										<span class="resp-detail-value resp-detail-value--total">Gratuito</span>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<div class="resp-actions">
						<a href="/" class="resp-btn-primary">
							<i class="fas fa-calendar-alt"></i> Ver programación
						</a>
						<a href="/" class="resp-btn-secondary">
							<i class="fas fa-home"></i> Volver al inicio
						</a>
					</div>
				</div>

			<?php else: ?>

				<!-- Datos del evento (cargados desde servidor, siempre visibles) -->
				<?php if ($evento): ?>
				<div class="resp-event-card">
					<?php if ($evento->evento_imagen): ?>
						<img class="resp-event-img" src="/images/<?= ($evento->evento_imagen) ?>" alt="<?= ($evento->evento_nombre) ?>">
					<?php endif; ?>
					<div class="resp-event-info">
						<h3 class="resp-event-title"><?= ($evento->evento_nombre) ?></h3>
						<div class="resp-event-meta">
							<?php if ($evento->evento_fecha): ?>
								<span><i class="fas fa-calendar-alt"></i>
									<?php
									$ts = strtotime($evento->evento_fecha);
									$meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
									echo date('j', $ts) . ' de ' . $meses[(int) date('n', $ts) - 1] . ' de ' . date('Y', $ts);
									?>
								</span>
							<?php endif; ?>
							<?php if ($sede && $sede->sede_nombre): ?>
								<span><i class="fas fa-map-marker-alt"></i> <?= ($sede->sede_nombre) ?></span>
							<?php endif; ?>
							<?php if ($sede && $sede->sede_direccion): ?>
								<span><i class="fas fa-road"></i> <?= ($sede->sede_direccion) ?></span>
							<?php endif; ?>
							<?php if ($compra): ?>
								<span><i class="fas fa-user"></i> <?= ($compra->boleta_compra_nombre) ?></span>
								<span><i class="fas fa-envelope"></i> <?= ($compra->boleta_compra_email) ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<!-- Estado de carga -->
				<div class="resp-loading" id="resp-loading">
					<div class="resp-spinner"></div>
					<p class="resp-loading-text">Consultando la transacción con el banco…</p>
				</div>

				<!-- Contenido principal (oculto hasta que carga) -->
				<div id="resp-content" style="display:none">

					<!-- Ícono + título de estado -->
					<div class="resp-status-block">
						<div class="resp-status-icon" id="resp-status-icon">
							<i id="resp-icon-i" class="fas fa-circle-notch fa-spin"></i>
						</div>
						<h2 class="resp-status-title" id="resp-status-title"></h2>
						<p class="resp-status-msg" id="resp-status-msg"></p>
					</div>

					<!-- Detalle de la transacción -->
					<div class="resp-details">
						<div class="resp-detail-grid" id="resp-detail-grid">
							<!-- Generado por JS -->
						</div>
					</div>

					<!-- Acciones -->
					<div class="resp-actions">
						<a href="/page/eventos" class="resp-btn-primary">
							<i class="fas fa-calendar-alt"></i> Ver programación
						</a>
						<a href="/" class="resp-btn-secondary">
							<i class="fas fa-home"></i> Volver al inicio
						</a>
					</div>

				</div>

				<!-- Error de carga -->
				<div class="resp-error-block" id="resp-error-block" style="display:none">
					<div class="resp-error-icon"><i class="fas fa-exclamation-triangle"></i></div>
					<h3 class="resp-error-title">No pudimos verificar tu transacción</h3>
					<p class="resp-error-sub">Revisa tu correo o contáctanos para confirmar el estado de tu compra.</p>
				</div>

			<?php endif; ?>

		</div>
	</div>
</div>

<?php if (!$esReservaGratuita): ?>
	<script>
		(() => {
			'use strict';

			function getQueryParam (param) {
				const match = location.search.slice(1).split('&')
					.find(p => p.split('=')[0] === param);
				return match ? decodeURIComponent(match.split('=')[1] || '') : null;
			}

			const ESTADOS = {
				1: { cls: 'ok', icon: 'fa-check-circle', title: 'Pago aprobado', msg: 'Tu transacción fue procesada con éxito. Recibirás un correo con los detalles de tu compra.' },
				2: { cls: 'fail', icon: 'fa-times-circle', title: 'Pago rechazado', msg: 'La transacción fue rechazada. Revisa los datos de tu medio de pago e inténtalo nuevamente.' },
				3: { cls: 'pending', icon: 'fa-clock', title: 'Pago pendiente', msg: 'Tu pago está pendiente de aprobación. Este proceso puede tardar hasta 20 minutos.' },
				4: { cls: 'fail', icon: 'fa-exclamation-circle', title: 'Transacción fallida', msg: 'Ocurrió un error y el pago no pudo completarse. Por favor intenta de nuevo.' },
				6: { cls: 'info', icon: 'fa-undo', title: 'Dinero reversado', msg: 'El dinero ha sido reintegrado a tu medio de pago. Contáctanos si tienes preguntas.' },
				7: { cls: 'pending', icon: 'fa-shield-alt', title: 'En revisión de auditoría', msg: 'La transacción está retenida para validación. Te notificaremos pronto.' },
				8: { cls: 'info', icon: 'fa-hourglass-start', title: 'Transacción iniciada', msg: 'Tu transacción ha sido iniciada. Estamos procesando tu solicitud.' },
				9: { cls: 'neutral', icon: 'fa-calendar-times', title: 'Transacción expirada', msg: 'El tiempo para completar el pago venció. Puedes iniciar una nueva compra.' },
				10: { cls: 'neutral', icon: 'fa-window-close', title: 'Navegador cerrado', msg: 'Parece que cerraste el navegador antes de terminar. Puedes intentar nuevamente.' },
				11: { cls: 'neutral', icon: 'fa-ban', title: 'Pago cancelado', msg: 'Cancelaste el proceso antes de concluir. Puedes iniciar una nueva compra cuando quieras.' },
			};

			function makeDetailCard (iconCls, label, value, valueCls) {
				return `
					<div class="resp-detail-card">
						<div class="resp-detail-icon resp-detail-icon--${iconCls}">
							${iconCls === 'ref' ? '<i class="fas fa-hashtag"></i>' :
						iconCls === 'date' ? '<i class="fas fa-calendar-alt"></i>' :
							iconCls === 'bank' ? '<i class="fas fa-university"></i>' :
								iconCls === 'total' ? '<i class="fas fa-dollar-sign"></i>' :
									iconCls === 'receipt' ? '<i class="fas fa-receipt"></i>' :
										'<i class="fas fa-info-circle"></i>'}
						</div>
						<div class="resp-detail-body">
							<span class="resp-detail-label">${label}</span>
							<span class="resp-detail-value${valueCls ? ' ' + valueCls : ''}">${value || '—'}</span>
						</div>
					</div>`;
			}

			const refPayco = getQueryParam('ref_payco');

			if (!refPayco) {
				document.getElementById('resp-loading').style.display = 'none';
				document.getElementById('resp-error-block').style.display = 'block';
				return;
			}

			fetch('https://secure.epayco.co/validation/v1/reference/' + refPayco)
				.then(r => r.json())
				.then(response => {
					document.getElementById('resp-loading').style.display = 'none';

					if (!response.success) {
						document.getElementById('resp-error-block').style.display = 'block';
						return;
					}

					const d = response.data;
					const cod = parseInt(d.x_cod_transaction_state, 10);
					const estado = ESTADOS[cod] || { cls: 'neutral', icon: 'fa-question-circle', title: 'Estado desconocido', msg: 'No pudimos determinar el estado de tu transacción.' };

					// Ícono de estado
					const iconEl = document.getElementById('resp-status-icon');
					iconEl.className = `resp-status-icon resp-status-icon--${estado.cls}`;
					document.getElementById('resp-icon-i').className = `fas ${estado.icon}`;

					document.getElementById('resp-status-title').textContent = estado.title;
					document.getElementById('resp-status-msg').textContent = estado.msg;

					// Detalle cards
					const grid = document.getElementById('resp-detail-grid');
					grid.innerHTML =
						makeDetailCard('ref', 'Referencia', d.x_id_invoice, '') +
						makeDetailCard('date', 'Fecha', d.x_transaction_date, '') +
						makeDetailCard('bank', 'Banco', d.x_bank_name, '') +
						makeDetailCard('receipt', 'Recibo', d.x_transaction_id, '') +
						makeDetailCard('status', 'Respuesta', d.x_response, '') +
						makeDetailCard('total', 'Total', d.x_amount ? new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }).format(d.x_amount) : '', 'resp-detail-value--total');

					document.getElementById('resp-content').style.display = '';

					// Notificar al servidor
					fetch('/page/eventos/respuesta2/', {
						method: 'POST',
						headers: { 'Content-Type': 'application/json' },
						body: JSON.stringify(d),
					})
						.then(r => r.json())
						.then(data => console.log('Servidor:', data))
						.catch(err => console.error('Error servidor:', err));
				})
				.catch(() => {
					document.getElementById('resp-loading').style.display = 'none';
					document.getElementById('resp-error-block').style.display = 'block';
				});
		})();
	</script>
<?php endif; ?>
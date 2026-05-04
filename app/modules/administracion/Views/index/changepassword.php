<div class="auth-form-container">
    <?php if ($this->error != '') { ?>
        <div class="auth-form-header">
            <h1 class="auth-title">Error al cambiar contraseña</h1>
            <p class="auth-subtitle">Ha ocurrido un problema al procesar tu solicitud.</p>
        </div>
        <div class="auth-alert auth-alert-error" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $this->error; ?></span>
        </div>
        <div class="text-center">
            <a href="/administracion" class="auth-link-secondary">
                <i class="fas fa-chevron-left"></i> Volver al Login
            </a>
        </div>
    <?php } else { ?>
        <?php if ($this->message != '') { ?>
            <div class="auth-form-header">
                <h1 class="auth-title">Solicitud completada</h1>
                <p class="auth-subtitle">La operación se ha realizado con éxito.</p>
            </div>
            <div class="auth-alert" style="background-color: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32;" role="alert">
                <i class="fas fa-check-circle" style="color: #2e7d32;"></i>
                <span><?php echo $this->message; ?></span>
            </div>
            <div class="text-center">
                <a href="/administracion" class="auth-link-secondary">
                    <i class="fas fa-chevron-left"></i> Volver al Login
                </a>
            </div>
        <?php } else { ?>
            <div class="auth-form-header">
                <h1 class="auth-title">Nueva contraseña</h1>
                <p class="auth-subtitle">Crea una nueva contraseña segura para tu cuenta.</p>
            </div>

            <form class="auth-form" data-bs-toggle="validator" role="form" method="post"
                action="/administracion/index/changepassword" novalidate>
                <input type="hidden" name="code" value="<?php echo $this->code; ?>" />

                <div class="auth-form-group">
                    <div
                        style="background-color: #f0f4f8; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;">
                        <strong style="color: #334e68;">USUARIO:</strong>
                        <span style="font-weight: 600;"><?php echo $this->usuario; ?></span>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="inputPassword" class="auth-label">Nueva Contraseña</label>
                    <input type="password" name="password" id="inputPassword" class="auth-input"
                        placeholder="Ingresa tu contraseña" required autocomplete="new-password" />
                    <div class="help-block with-errors"></div>
                </div>

                <div class="auth-form-group">
                    <label for="re_password" class="auth-label">Repetir Contraseña</label>
                    <input type="password" id="re_password" name="re_password" class="auth-input" data-match="#inputPassword"
                        data-match-error="Las dos Contraseñas no son iguales" placeholder="Confirma tu contraseña" required
                        autocomplete="new-password" />
                    <div class="help-block with-errors"></div>
                </div>

                <button class="auth-button-primary" type="submit">
                    <span>Cambiar Contraseña</span>
                    <i class="fas fa-key"></i>
                </button>
            </form>
        <?php } ?>
    <?php } ?>
</div>
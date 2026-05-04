<div class="auth-form-container">
    <div class="auth-form-header">
        <h1 class="auth-title">¿Olvidaste tu contraseña?</h1>
        <p class="auth-subtitle">Ingresa tu correo electrónico y te enviaremos un enlace para restablecerla.</p>
    </div>

    <form class="auth-form" autocomplete="off" action="/administracion/loginuser/forgotpassword" method="post"
        novalidate>
        <?php if ($this->error_olvido) { ?>
            <div class="auth-alert auth-alert-error" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $this->error_olvido; ?></span>
            </div>
        <?php } ?>

        <?php if ($this->mensaje_olvido) { ?>
            <div class="auth-alert" style="background-color: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32;"
                role="alert">
                <i class="fas fa-check-circle" style="color: #2e7d32;"></i>
                <span><?php echo $this->mensaje_olvido; ?></span>
            </div>
        <?php } ?>

        <div class="auth-form-group">
            <label for="email" class="auth-label">Correo electrónico</label>
            <input type="email" class="auth-input" id="email" name="email" placeholder="ejemplo@correo.com" required
                aria-label="Correo electrónico">
        </div>

        <input type="hidden" id="csrf" name="csrf" value="<?php echo $this->csrf; ?>" />

        <div class="auth-form-footer" style="text-align: center; margin-bottom: 20px;">
            <a href="/administracion" class="auth-link-secondary">
                <i class="fas fa-chevron-left"></i> Volver al Inicio de Sesión
            </a>
        </div>

        <button type="submit" class="auth-button-primary">
            <span>Enviar enlace</span>
            <i class="fas fa-paper-plane"></i>
        </button>
    </form>
</div>
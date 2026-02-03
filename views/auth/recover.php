<?php
$pageTitle = "Recuperar Contraseña - " . SITE_NAME;
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/nav.php';
?>

<div class="auth-container">
    <div class="auth-box">
        <h1>🔓 Recuperar Contraseña</h1>
        
        <p class="text-center">Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña.</p>
        
        <form method="POST" action="<?php echo BASE_URL; ?>index.php?page=recover" class="auth-form">
            <div class="form-group">
                <label for="email">📧 Email</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Enviar enlace</button>
        </form>
        
        <div class="auth-links">
            <a href="<?php echo BASE_URL; ?>index.php?page=login">Volver a iniciar sesión</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

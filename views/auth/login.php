<?php
$pageTitle = "Iniciar Sesión - " . SITE_NAME;
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/nav.php';
?>

<div class="auth-container">
    <div class="auth-box">
        <h1>🔐 Iniciar Sesión</h1>
        
        <form method="POST" action="<?php echo BASE_URL; ?>index.php?page=login" class="auth-form">
            <div class="form-group">
                <label for="username">👤 Usuario</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">🔑 Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>
        
        <div class="auth-links">
            <a href="<?php echo BASE_URL; ?>index.php?page=recover">¿Olvidaste tu contraseña?</a>
            <a href="<?php echo BASE_URL; ?>index.php?page=register">Crear cuenta nueva</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

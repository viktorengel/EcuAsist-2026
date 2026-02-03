<?php
$pageTitle = "Registro - " . SITE_NAME;
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/nav.php';
?>

<div class="auth-container">
    <div class="auth-box">
        <h1>📝 Crear Cuenta</h1>
        
        <form method="POST" action="<?php echo BASE_URL; ?>index.php?page=register" class="auth-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">Nombres *</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Apellidos *</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="identification">Cédula/Identificación *</label>
                <input type="text" id="identification" name="identification" required>
            </div>
            
            <div class="form-group">
                <label for="email">📧 Email *</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="phone">📱 Teléfono</label>
                <input type="text" id="phone" name="phone">
            </div>
            
            <div class="form-group">
                <label for="username">👤 Usuario *</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">🔑 Contraseña *</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
        </form>
        
        <div class="auth-links">
            <a href="<?php echo BASE_URL; ?>index.php?page=login">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

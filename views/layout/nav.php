<nav class="navbar">
    <div class="nav-brand">
        <a href="<?php echo BASE_URL; ?>index.php?page=dashboard"><?php echo SITE_NAME; ?></a>
    </div>
    <div class="nav-menu">
        <?php if (isLoggedIn()): ?>
            <span class="nav-user">👤 <?php echo getUserData('first_name'); ?></span>
            
            <?php if (hasRole('docente')): ?>
                <a href="<?php echo BASE_URL; ?>index.php?page=attendance-register">📝 Registrar Asistencia</a>
            <?php endif; ?>
            
            <?php if (hasRole('estudiante')): ?>
                <a href="<?php echo BASE_URL; ?>index.php?page=attendance-list">📋 Mi Asistencia</a>
            <?php endif; ?>
            
            <?php if (hasRole('autoridad')): ?>
                <a href="<?php echo BASE_URL; ?>index.php?page=users">👥 Usuarios</a>
                <a href="<?php echo BASE_URL; ?>index.php?page=reports">📊 Reportes</a>
            <?php endif; ?>
            
            <a href="<?php echo BASE_URL; ?>index.php?page=logout">🚪 Salir</a>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>index.php?page=login">Iniciar Sesión</a>
            <a href="<?php echo BASE_URL; ?>index.php?page=register">Registrarse</a>
        <?php endif; ?>
    </div>
</nav>

<?php 
$success = getFlashMessage('success');
$error = getFlashMessage('error');
$warning = getFlashMessage('warning');
$info = getFlashMessage('info');
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($warning): ?>
    <div class="alert alert-warning"><?php echo $warning; ?></div>
<?php endif; ?>

<?php if ($info): ?>
    <div class="alert alert-info"><?php echo $info; ?></div>
<?php endif; ?>

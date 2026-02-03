<?php
$pageTitle = "Panel Principal - " . SITE_NAME;
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/nav.php';
?>

<div class="dashboard">
    <h1>👋 Bienvenido, <?php echo getUserData('first_name'); ?></h1>
    
    <div class="dashboard-cards">
        <?php if (in_array('docente', $data['roles'])): ?>
            <div class="card">
                <h3>📝 Registrar Asistencia</h3>
                <p>Registra la asistencia de tus estudiantes</p>
                <a href="<?php echo BASE_URL; ?>index.php?page=attendance-register" class="btn btn-primary">Ir</a>
            </div>
        <?php endif; ?>
        
        <?php if (in_array('estudiante', $data['roles'])): ?>
            <div class="card">
                <h3>📋 Mi Asistencia</h3>
                <p>Revisa tu registro de asistencia</p>
                <a href="<?php echo BASE_URL; ?>index.php?page=attendance-list" class="btn btn-primary">Ver</a>
            </div>
        <?php endif; ?>
        
        <?php if (in_array('autoridad', $data['roles'])): ?>
            <div class="card">
                <h3>👥 Gestión de Usuarios</h3>
                <p>Administra usuarios y roles</p>
                <a href="<?php echo BASE_URL; ?>index.php?page=users" class="btn btn-primary">Gestionar</a>
            </div>
            
            <div class="card">
                <h3>📊 Reportes</h3>
                <p>Genera reportes de asistencia</p>
                <a href="<?php echo BASE_URL; ?>index.php?page=reports" class="btn btn-primary">Generar</a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if (in_array('estudiante', $data['roles']) && !empty($data['recent_attendance'])): ?>
        <div class="recent-section">
            <h2>📅 Asistencia Reciente</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Asignatura</th>
                        <th>Hora</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($data['recent_attendance'], 0, 10) as $att): ?>
                        <tr>
                            <td><?php echo formatDate($att['attendance_date']); ?></td>
                            <td><?php echo $att['subject_name']; ?></td>
                            <td><?php echo $att['class_hour']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $att['status']; ?>">
                                    <?php echo ucfirst($att['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

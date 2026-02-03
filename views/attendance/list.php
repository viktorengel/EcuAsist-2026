<?php
$pageTitle = "Mi Asistencia - " . SITE_NAME;
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/nav.php';
?>

<div class="attendance-list">
    <h1>📋 Mi Registro de Asistencia</h1>
    
    <?php if (empty($attendances)): ?>
        <p class="text-center">No hay registros de asistencia aún.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Asignatura</th>
                    <th>Curso</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Docente</th>
                    <th>Notas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendances as $att): ?>
                    <tr>
                        <td><?php echo formatDate($att['attendance_date']); ?></td>
                        <td><?php echo $att['subject_name']; ?></td>
                        <td><?php echo $att['course_name']; ?></td>
                        <td><?php echo $att['class_hour']; ?></td>
                        <td>
                            <span class="badge badge-<?php echo $att['status']; ?>">
                                <?php 
                                $statusLabels = [
                                    'present' => '✅ Presente',
                                    'absent' => '❌ Ausente',
                                    'late' => '⏰ Tarde',
                                    'justified' => '📝 Justificado'
                                ];
                                echo $statusLabels[$att['status']];
                                ?>
                            </span>
                        </td>
                        <td><?php echo $att['teacher_first_name'] . ' ' . $att['teacher_last_name']; ?></td>
                        <td><?php echo $att['notes'] ?? '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

<?php
$pageTitle = "Generar Reportes - " . SITE_NAME;
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/nav.php';
?>

<div class="reports-generate">
    <h1>📊 Generar Reportes de Asistencia</h1>
    
    <form method="POST" action="<?php echo BASE_URL; ?>index.php?page=reports" class="report-form">
        <div class="form-group">
            <label for="report_type">Tipo de Reporte *</label>
            <select id="report_type" name="report_type" required>
                <option value="">Seleccionar tipo</option>
                <option value="student">Por Estudiante</option>
                <option value="course">Por Curso</option>
            </select>
        </div>
        
        <div id="student-select" class="form-group" style="display: none;">
            <label for="student_id">Estudiante *</label>
            <select id="student_id" name="student_id">
                <option value="">Seleccionar estudiante</option>
                <?php foreach ($students as $student): ?>
                    <option value="<?php echo $student['id']; ?>">
                        <?php echo $student['first_name'] . ' ' . $student['last_name'] . ' - ' . $student['identification']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div id="course-select" class="form-group" style="display: none;">
            <label for="course_id">Curso *</label>
            <select id="course_id" name="course_id">
                <option value="">Seleccionar curso</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>">
                        <?php echo $course['name'] . ' - ' . $course['schedule_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="start_date">Fecha Inicio *</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>
            
            <div class="form-group">
                <label for="end_date">Fecha Fin *</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="format">Formato *</label>
            <select id="format" name="format" required>
                <option value="">Seleccionar formato</option>
                <option value="pdf">PDF</option>
                <option value="excel">Excel (CSV)</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">📥 Generar Reporte</button>
    </form>
</div>

<script>
document.getElementById('report_type').addEventListener('change', function() {
    const studentSelect = document.getElementById('student-select');
    const courseSelect = document.getElementById('course-select');
    
    studentSelect.style.display = 'none';
    courseSelect.style.display = 'none';
    
    if (this.value === 'student') {
        studentSelect.style.display = 'block';
        document.getElementById('student_id').required = true;
        document.getElementById('course_id').required = false;
    } else if (this.value === 'course') {
        courseSelect.style.display = 'block';
        document.getElementById('course_id').required = true;
        document.getElementById('student_id').required = false;
    }
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

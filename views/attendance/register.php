<?php
$pageTitle = "Registrar Asistencia - " . SITE_NAME;
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/nav.php';
?>

<div class="attendance-register">
    <h1>📝 Registrar Asistencia</h1>
    
    <form method="POST" action="<?php echo BASE_URL; ?>index.php?page=attendance-register" class="attendance-form">
        <div class="form-row">
            <div class="form-group">
                <label for="course_id">Curso *</label>
                <select id="course_id" name="course_id" required>
                    <option value="">Seleccionar curso</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['id']; ?>">
                            <?php echo $course['name'] . ' - ' . $course['schedule_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="subject_id">Asignatura *</label>
                <select id="subject_id" name="subject_id" required>
                    <option value="">Seleccionar asignatura</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo $subject['id']; ?>">
                            <?php echo $subject['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="attendance_date">Fecha *</label>
                <input type="date" id="attendance_date" name="attendance_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="class_hour">Hora de Clase *</label>
                <select id="class_hour" name="class_hour" required>
                    <option value="">Seleccionar</option>
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?php echo $i; ?>">Hora <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        
        <button type="button" id="load-students" class="btn btn-secondary">Cargar Estudiantes</button>
        
        <div id="students-list" style="display: none;">
            <h3>Lista de Estudiantes</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Presente</th>
                        <th>Ausente</th>
                        <th>Tarde</th>
                        <th>Justificado</th>
                        <th>Notas</th>
                    </tr>
                </thead>
                <tbody id="students-tbody">
                </tbody>
            </table>
            
            <button type="submit" class="btn btn-primary btn-block">Guardar Asistencia</button>
        </div>
    </form>
</div>

<script>
document.getElementById('load-students').addEventListener('click', function() {
    const courseId = document.getElementById('course_id').value;
    
    if (!courseId) {
        alert('Por favor selecciona un curso');
        return;
    }
    
    fetch('<?php echo BASE_URL; ?>index.php?page=get-students&course_id=' + courseId)
        .then(response => response.json())
        .then(students => {
            const tbody = document.getElementById('students-tbody');
            tbody.innerHTML = '';
            
            students.forEach(student => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${student.first_name} ${student.last_name}</td>
                    <td><input type="radio" name="students[${student.id}]" value="present" required></td>
                    <td><input type="radio" name="students[${student.id}]" value="absent"></td>
                    <td><input type="radio" name="students[${student.id}]" value="late"></td>
                    <td><input type="radio" name="students[${student.id}]" value="justified"></td>
                    <td><input type="text" name="notes[${student.id}]" placeholder="Observaciones"></td>
                `;
                tbody.appendChild(row);
            });
            
            document.getElementById('students-list').style.display = 'block';
        })
        .catch(error => {
            alert('Error al cargar estudiantes');
            console.error(error);
        });
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

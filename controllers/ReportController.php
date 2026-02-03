<?php
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/session.php';

class ReportController {
    private $attendanceModel;
    private $courseModel;
    private $userModel;

    public function __construct() {
        $this->attendanceModel = new Attendance();
        $this->courseModel = new Course();
        $this->userModel = new User();
    }

    public function generate() {
        requireLogin();

        $institutionId = getUserData('institution_id');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['report_type'];
            $studentId = $_POST['student_id'] ?? null;
            $courseId = $_POST['course_id'] ?? null;
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $format = $_POST['format'];

            if ($type === 'student' && $studentId) {
                $this->generateStudentReport($studentId, $startDate, $endDate, $format);
            } elseif ($type === 'course' && $courseId) {
                $this->generateCourseReport($courseId, $startDate, $endDate, $format);
            }
        }

        $courses = $this->courseModel->getAll($institutionId);
        $students = $this->userModel->getAll($institutionId);

        require_once __DIR__ . '/../views/reports/generate.php';
    }

    private function generateStudentReport($studentId, $startDate, $endDate, $format) {
        $student = $this->userModel->findById($studentId);
        $attendances = $this->attendanceModel->getByStudent($studentId, $startDate, $endDate);

        if ($format === 'pdf') {
            $this->generatePDF($student, $attendances, $startDate, $endDate);
        } else {
            $this->generateExcel($student, $attendances, $startDate, $endDate);
        }
    }

    private function generateCourseReport($courseId, $startDate, $endDate, $format) {
        setFlashMessage('info', 'Reporte por curso en desarrollo');
        redirect('index.php?page=reports');
    }

    private function generatePDF($student, $attendances, $startDate, $endDate) {
        header('Content-Type: text/html; charset=utf-8');
        
        echo "<!DOCTYPE html>";
        echo "<html><head><meta charset='UTF-8'><title>Reporte de Asistencia</title></head><body>";
        echo "<h1>Reporte de Asistencia</h1>";
        echo "<h2>Estudiante: {$student['first_name']} {$student['last_name']}</h2>";
        echo "<p>Período: " . formatDate($startDate) . " - " . formatDate($endDate) . "</p>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Fecha</th><th>Asignatura</th><th>Hora</th><th>Estado</th></tr>";
        
        foreach ($attendances as $att) {
            echo "<tr>";
            echo "<td>" . formatDate($att['attendance_date']) . "</td>";
            echo "<td>{$att['subject_name']}</td>";
            echo "<td>{$att['class_hour']}</td>";
            echo "<td>{$att['status']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "</body></html>";
        exit;
    }

    private function generateExcel($student, $attendances, $startDate, $endDate) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="asistencia_' . $student['identification'] . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Reporte de Asistencia']);
        fputcsv($output, ['Estudiante', $student['first_name'] . ' ' . $student['last_name']]);
        fputcsv($output, ['Período', formatDate($startDate) . ' - ' . formatDate($endDate)]);
        fputcsv($output, []);
        fputcsv($output, ['Fecha', 'Asignatura', 'Hora', 'Estado', 'Notas']);
        
        foreach ($attendances as $att) {
            fputcsv($output, [
                formatDate($att['attendance_date']),
                $att['subject_name'],
                $att['class_hour'],
                $att['status'],
                $att['notes'] ?? ''
            ]);
        }
        
        fclose($output);
        exit;
    }
}

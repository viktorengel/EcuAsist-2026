<?php
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/SchoolYear.php';
require_once __DIR__ . '/../helpers/session.php';

class AttendanceController {
    private $attendanceModel;
    private $courseModel;
    private $subjectModel;
    private $schoolYearModel;

    public function __construct() {
        $this->attendanceModel = new Attendance();
        $this->courseModel = new Course();
        $this->subjectModel = new Subject();
        $this->schoolYearModel = new SchoolYear();
    }

    public function register() {
        requireLogin();
        requireRole('docente');

        $institutionId = getUserData('institution_id');
        $teacherId = getUserId();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseId = $_POST['course_id'];
            $subjectId = $_POST['subject_id'];
            $date = $_POST['attendance_date'];
            $classHour = $_POST['class_hour'];
            $students = $_POST['students'] ?? [];

            $schoolYear = $this->schoolYearModel->getActive($institutionId);
            $course = $this->courseModel->findById($courseId);

            foreach ($students as $studentId => $status) {
                $data = [
                    'student_id' => $studentId,
                    'course_id' => $courseId,
                    'subject_id' => $subjectId,
                    'school_year_id' => $schoolYear['id'],
                    'schedule_id' => $course['schedule_id'],
                    'teacher_id' => $teacherId,
                    'attendance_date' => $date,
                    'class_hour' => $classHour,
                    'status' => $status,
                    'notes' => $_POST['notes'][$studentId] ?? null,
                    'registered_by' => $teacherId
                ];

                $this->attendanceModel->register($data);
            }

            setFlashMessage('success', 'Asistencia registrada exitosamente');
            redirect('index.php?page=attendance-register');
        }

        $courses = $this->courseModel->getAll($institutionId);
        $subjects = $this->subjectModel->getAll($institutionId);

        require_once __DIR__ . '/../views/attendance/register.php';
    }

    public function getStudentsByCourse() {
        requireLogin();
        
        $courseId = $_GET['course_id'] ?? 0;
        $students = $this->courseModel->getStudents($courseId);
        
        header('Content-Type: application/json');
        echo json_encode($students);
        exit;
    }

    public function list() {
        requireLogin();

        $userId = getUserId();
        $roles = getUserRoles();

        if (in_array('estudiante', $roles)) {
            $attendances = $this->attendanceModel->getByStudent($userId);
        } elseif (in_array('docente', $roles)) {
            $attendances = [];
        } else {
            $attendances = [];
        }

        require_once __DIR__ . '/../views/attendance/list.php';
    }

    public function edit() {
        requireLogin();
        requireRole('docente');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['attendance_id'];
            $status = $_POST['status'];
            $notes = $_POST['notes'] ?? null;

            if ($this->attendanceModel->canEdit($id)) {
                $this->attendanceModel->update($id, $status, $notes);
                setFlashMessage('success', 'Asistencia actualizada exitosamente');
            } else {
                setFlashMessage('error', 'No se puede editar. Han pasado más de 48 horas');
            }

            redirect('index.php?page=attendance-list');
        }
    }
}

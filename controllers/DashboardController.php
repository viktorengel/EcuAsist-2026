<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../helpers/session.php';

class DashboardController {
    private $userModel;
    private $attendanceModel;
    private $courseModel;

    public function __construct() {
        $this->userModel = new User();
        $this->attendanceModel = new Attendance();
        $this->courseModel = new Course();
    }

    public function index() {
        requireLogin();

        $userId = getUserId();
        $roles = getUserRoles();
        $institutionId = getUserData('institution_id');

        $data = [
            'user' => $this->userModel->findById($userId),
            'roles' => $roles
        ];

        if (in_array('estudiante', $roles)) {
            $data['recent_attendance'] = $this->attendanceModel->getByStudent($userId, null, null);
        }

        if (in_array('docente', $roles)) {
            require_once __DIR__ . '/../models/Subject.php';
            $subjectModel = new Subject();
            $data['my_subjects'] = $subjectModel->getTeacherSubjects($userId);
        }

        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}

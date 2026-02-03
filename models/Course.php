<?php
require_once __DIR__ . '/Database.php';

class Course {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll($institutionId, $schoolYearId = null) {
        $sql = "SELECT c.*, s.name as schedule_name, sy.name as school_year_name
                FROM courses c
                INNER JOIN schedules s ON c.schedule_id = s.id
                INNER JOIN school_years sy ON c.school_year_id = sy.id
                WHERE c.institution_id = :institution_id";
        
        $params = [':institution_id' => $institutionId];
        
        if ($schoolYearId) {
            $sql .= " AND c.school_year_id = :school_year_id";
            $params[':school_year_id'] = $schoolYearId;
        }
        
        $sql .= " ORDER BY c.grade_level, c.parallel";
        
        return $this->db->fetchAll($sql, $params);
    }

    public function findById($id) {
        $sql = "SELECT c.*, s.name as schedule_name, sy.name as school_year_name
                FROM courses c
                INNER JOIN schedules s ON c.schedule_id = s.id
                INNER JOIN school_years sy ON c.school_year_id = sy.id
                WHERE c.id = :id";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    public function create($data) {
        $sql = "INSERT INTO courses (institution_id, school_year_id, schedule_id, name, grade_level, parallel) 
                VALUES (:institution_id, :school_year_id, :schedule_id, :name, :grade_level, :parallel)";
        
        return $this->db->execute($sql, [
            ':institution_id' => $data['institution_id'],
            ':school_year_id' => $data['school_year_id'],
            ':schedule_id' => $data['schedule_id'],
            ':name' => $data['name'],
            ':grade_level' => $data['grade_level'],
            ':parallel' => $data['parallel']
        ]);
    }

    public function getStudents($courseId) {
        $sql = "SELECT u.*, se.enrollment_date
                FROM users u
                INNER JOIN student_enrollments se ON u.id = se.student_id
                WHERE se.course_id = :course_id AND se.is_active = 1
                ORDER BY u.last_name, u.first_name";
        return $this->db->fetchAll($sql, [':course_id' => $courseId]);
    }

    public function enrollStudent($studentId, $courseId) {
        $sql = "INSERT INTO student_enrollments (student_id, course_id, enrollment_date) 
                VALUES (:student_id, :course_id, CURDATE())";
        
        return $this->db->execute($sql, [
            ':student_id' => $studentId,
            ':course_id' => $courseId
        ]);
    }
}

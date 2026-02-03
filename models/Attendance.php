<?php
require_once __DIR__ . '/Database.php';

class Attendance {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function register($data) {
        $canEditUntil = date('Y-m-d H:i:s', strtotime('+48 hours'));
        
        $sql = "INSERT INTO attendance 
                (student_id, course_id, subject_id, school_year_id, schedule_id, teacher_id, 
                 attendance_date, class_hour, status, notes, registered_by, can_edit_until) 
                VALUES 
                (:student_id, :course_id, :subject_id, :school_year_id, :schedule_id, :teacher_id, 
                 :attendance_date, :class_hour, :status, :notes, :registered_by, :can_edit_until)";
        
        return $this->db->execute($sql, [
            ':student_id' => $data['student_id'],
            ':course_id' => $data['course_id'],
            ':subject_id' => $data['subject_id'],
            ':school_year_id' => $data['school_year_id'],
            ':schedule_id' => $data['schedule_id'],
            ':teacher_id' => $data['teacher_id'],
            ':attendance_date' => $data['attendance_date'],
            ':class_hour' => $data['class_hour'],
            ':status' => $data['status'],
            ':notes' => $data['notes'] ?? null,
            ':registered_by' => $data['registered_by'],
            ':can_edit_until' => $canEditUntil
        ]);
    }

    public function update($id, $status, $notes = null) {
        $sql = "UPDATE attendance SET status = :status, notes = :notes 
                WHERE id = :id AND can_edit_until > NOW()";
        
        return $this->db->execute($sql, [
            ':id' => $id,
            ':status' => $status,
            ':notes' => $notes
        ]);
    }

    public function getByDateAndCourse($date, $courseId, $subjectId, $classHour) {
        $sql = "SELECT a.*, 
                u.first_name, u.last_name, u.identification,
                s.name as subject_name
                FROM attendance a
                INNER JOIN users u ON a.student_id = u.id
                INNER JOIN subjects s ON a.subject_id = s.id
                WHERE a.attendance_date = :date 
                AND a.course_id = :course_id 
                AND a.subject_id = :subject_id
                AND a.class_hour = :class_hour
                ORDER BY u.last_name, u.first_name";
        
        return $this->db->fetchAll($sql, [
            ':date' => $date,
            ':course_id' => $courseId,
            ':subject_id' => $subjectId,
            ':class_hour' => $classHour
        ]);
    }

    public function getByStudent($studentId, $startDate = null, $endDate = null) {
        $sql = "SELECT a.*, 
                s.name as subject_name, 
                c.name as course_name,
                t.first_name as teacher_first_name, 
                t.last_name as teacher_last_name
                FROM attendance a
                INNER JOIN subjects s ON a.subject_id = s.id
                INNER JOIN courses c ON a.course_id = c.id
                INNER JOIN users t ON a.teacher_id = t.id
                WHERE a.student_id = :student_id";
        
        $params = [':student_id' => $studentId];
        
        if ($startDate) {
            $sql .= " AND a.attendance_date >= :start_date";
            $params[':start_date'] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND a.attendance_date <= :end_date";
            $params[':end_date'] = $endDate;
        }
        
        $sql .= " ORDER BY a.attendance_date DESC, a.class_hour";
        
        return $this->db->fetchAll($sql, $params);
    }

    public function getStatsByStudent($studentId, $schoolYearId) {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status = 'justified' THEN 1 ELSE 0 END) as justified
                FROM attendance
                WHERE student_id = :student_id 
                AND school_year_id = :school_year_id";
        
        return $this->db->fetchOne($sql, [
            ':student_id' => $studentId,
            ':school_year_id' => $schoolYearId
        ]);
    }

    public function canEdit($id) {
        $sql = "SELECT can_edit_until > NOW() as can_edit FROM attendance WHERE id = :id";
        $result = $this->db->fetchOne($sql, [':id' => $id]);
        return $result && $result['can_edit'];
    }
}

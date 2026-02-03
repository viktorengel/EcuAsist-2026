<?php
require_once __DIR__ . '/Database.php';

class Subject {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll($institutionId) {
        $sql = "SELECT * FROM subjects WHERE institution_id = :institution_id ORDER BY name";
        return $this->db->fetchAll($sql, [':institution_id' => $institutionId]);
    }

    public function findById($id) {
        $sql = "SELECT * FROM subjects WHERE id = :id";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    public function create($data) {
        $sql = "INSERT INTO subjects (institution_id, name, code) 
                VALUES (:institution_id, :name, :code)";
        
        return $this->db->execute($sql, [
            ':institution_id' => $data['institution_id'],
            ':name' => $data['name'],
            ':code' => $data['code'] ?? null
        ]);
    }

    public function assignToTeacher($teacherId, $subjectId, $courseId, $isTutor = 0) {
        $sql = "INSERT INTO teacher_subject_course (teacher_id, subject_id, course_id, is_tutor) 
                VALUES (:teacher_id, :subject_id, :course_id, :is_tutor)";
        
        return $this->db->execute($sql, [
            ':teacher_id' => $teacherId,
            ':subject_id' => $subjectId,
            ':course_id' => $courseId,
            ':is_tutor' => $isTutor
        ]);
    }

    public function getTeacherSubjects($teacherId) {
        $sql = "SELECT s.*, c.name as course_name, tsc.is_tutor
                FROM subjects s
                INNER JOIN teacher_subject_course tsc ON s.id = tsc.subject_id
                INNER JOIN courses c ON tsc.course_id = c.id
                WHERE tsc.teacher_id = :teacher_id
                ORDER BY c.name, s.name";
        return $this->db->fetchAll($sql, [':teacher_id' => $teacherId]);
    }
}

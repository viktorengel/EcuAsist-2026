<?php
require_once __DIR__ . '/Database.php';

class SchoolYear {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getActive($institutionId) {
        $sql = "SELECT * FROM school_years 
                WHERE institution_id = :institution_id AND is_active = 1 
                LIMIT 1";
        return $this->db->fetchOne($sql, [':institution_id' => $institutionId]);
    }

    public function getAll($institutionId) {
        $sql = "SELECT * FROM school_years 
                WHERE institution_id = :institution_id 
                ORDER BY start_date DESC";
        return $this->db->fetchAll($sql, [':institution_id' => $institutionId]);
    }

    public function create($data) {
        $sql = "INSERT INTO school_years (institution_id, name, start_date, end_date, is_active) 
                VALUES (:institution_id, :name, :start_date, :end_date, :is_active)";
        
        return $this->db->execute($sql, [
            ':institution_id' => $data['institution_id'],
            ':name' => $data['name'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':is_active' => $data['is_active'] ?? 0
        ]);
    }

    public function setActive($id, $institutionId) {
        $this->db->execute(
            "UPDATE school_years SET is_active = 0 WHERE institution_id = :institution_id",
            [':institution_id' => $institutionId]
        );
        
        return $this->db->execute(
            "UPDATE school_years SET is_active = 1 WHERE id = :id",
            [':id' => $id]
        );
    }
}

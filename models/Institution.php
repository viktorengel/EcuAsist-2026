<?php
require_once __DIR__ . '/Database.php';

class Institution {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getActive() {
        $sql = "SELECT * FROM institutions WHERE is_active = 1 LIMIT 1";
        return $this->db->fetchOne($sql);
    }

    public function findById($id) {
        $sql = "SELECT * FROM institutions WHERE id = :id";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    public function getAll() {
        $sql = "SELECT * FROM institutions ORDER BY name";
        return $this->db->fetchAll($sql);
    }
}

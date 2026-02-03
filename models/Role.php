<?php
require_once __DIR__ . '/Database.php';

class Role {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM roles ORDER BY name";
        return $this->db->fetchAll($sql);
    }

    public function findById($id) {
        $sql = "SELECT * FROM roles WHERE id = :id";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    public function assignToUser($userId, $roleId, $assignedBy) {
        $sql = "INSERT INTO user_roles (user_id, role_id, assigned_by) 
                VALUES (:user_id, :role_id, :assigned_by)
                ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP";
        
        return $this->db->execute($sql, [
            ':user_id' => $userId,
            ':role_id' => $roleId,
            ':assigned_by' => $assignedBy
        ]);
    }

    public function removeFromUser($userId, $roleId) {
        $sql = "DELETE FROM user_roles WHERE user_id = :user_id AND role_id = :role_id";
        return $this->db->execute($sql, [':user_id' => $userId, ':role_id' => $roleId]);
    }

    public function getUserRoles($userId) {
        $sql = "SELECT r.* FROM roles r
                INNER JOIN user_roles ur ON r.id = ur.role_id
                WHERE ur.user_id = :user_id";
        return $this->db->fetchAll($sql, [':user_id' => $userId]);
    }
}

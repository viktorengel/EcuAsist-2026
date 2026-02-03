<?php
require_once __DIR__ . '/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        $sql = "INSERT INTO users (institution_id, username, email, password, first_name, last_name, identification, phone) 
                VALUES (:institution_id, :username, :email, :password, :first_name, :last_name, :identification, :phone)";
        
        $params = [
            ':institution_id' => $data['institution_id'],
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':identification' => $data['identification'],
            ':phone' => $data['phone'] ?? null
        ];

        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }

    public function findByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username AND is_active = 1";
        return $this->db->fetchOne($sql, [':username' => $username]);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email AND is_active = 1";
        return $this->db->fetchOne($sql, [':email' => $email]);
    }

    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function updateResetToken($userId, $token) {
        $sql = "UPDATE users SET reset_token = :token, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) 
                WHERE id = :id";
        return $this->db->execute($sql, [':token' => $token, ':id' => $userId]);
    }

    public function verifyResetToken($token) {
        $sql = "SELECT * FROM users WHERE reset_token = :token AND reset_token_expiry > NOW()";
        return $this->db->fetchOne($sql, [':token' => $token]);
    }

    public function updatePassword($userId, $newPassword) {
        $sql = "UPDATE users SET password = :password, reset_token = NULL, reset_token_expiry = NULL 
                WHERE id = :id";
        return $this->db->execute($sql, [
            ':password' => password_hash($newPassword, PASSWORD_DEFAULT),
            ':id' => $userId
        ]);
    }

    public function getRoles($userId) {
        $sql = "SELECT r.* FROM roles r
                INNER JOIN user_roles ur ON r.id = ur.role_id
                WHERE ur.user_id = :user_id";
        return $this->db->fetchAll($sql, [':user_id' => $userId]);
    }

    public function hasRole($userId, $roleName) {
        $sql = "SELECT COUNT(*) as count FROM user_roles ur
                INNER JOIN roles r ON ur.role_id = r.id
                WHERE ur.user_id = :user_id AND r.name = :role_name";
        $result = $this->db->fetchOne($sql, [':user_id' => $userId, ':role_name' => $roleName]);
        return $result['count'] > 0;
    }

    public function getAll($institutionId = null) {
        $sql = "SELECT u.*, GROUP_CONCAT(r.name) as roles 
                FROM users u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.id";
        
        if ($institutionId) {
            $sql .= " WHERE u.institution_id = :institution_id";
        }
        
        $sql .= " GROUP BY u.id ORDER BY u.created_at DESC";
        
        return $institutionId 
            ? $this->db->fetchAll($sql, [':institution_id' => $institutionId])
            : $this->db->fetchAll($sql);
    }
}

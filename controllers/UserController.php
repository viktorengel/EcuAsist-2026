<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../helpers/session.php';

class UserController {
    private $userModel;
    private $roleModel;

    public function __construct() {
        $this->userModel = new User();
        $this->roleModel = new Role();
    }

    public function list() {
        requireLogin();
        requireRole('autoridad');

        $institutionId = getUserData('institution_id');
        $users = $this->userModel->getAll($institutionId);

        require_once __DIR__ . '/../views/users/list.php';
    }

    public function assignRole() {
        requireLogin();
        requireRole('autoridad');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'];
            $roleId = $_POST['role_id'];
            $assignedBy = getUserId();

            try {
                $this->roleModel->assignToUser($userId, $roleId, $assignedBy);
                setFlashMessage('success', 'Rol asignado exitosamente');
            } catch (Exception $e) {
                setFlashMessage('error', 'Error al asignar rol');
            }

            redirect('index.php?page=users');
        }
    }

    public function removeRole() {
        requireLogin();
        requireRole('autoridad');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'];
            $roleId = $_POST['role_id'];

            try {
                $this->roleModel->removeFromUser($userId, $roleId);
                setFlashMessage('success', 'Rol removido exitosamente');
            } catch (Exception $e) {
                setFlashMessage('error', 'Error al remover rol');
            }

            redirect('index.php?page=users');
        }
    }
}

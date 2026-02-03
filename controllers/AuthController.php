<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/session.php';

class AuthController {
    private $userModel;
    private $roleModel;

    public function __construct() {
        $this->userModel = new User();
        $this->roleModel = new Role();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitize($_POST['username']);
            $password = $_POST['password'];

            $user = $this->userModel->findByUsername($username);

            if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
                setUserSession($user);
                
                $roles = $this->userModel->getRoles($user['id']);
                setUserRoles($roles);

                setFlashMessage('success', '¡Bienvenido ' . $user['first_name'] . '!');
                redirect('index.php?page=dashboard');
            } else {
                setFlashMessage('error', 'Usuario o contraseña incorrectos');
                redirect('index.php?page=login');
            }
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'institution_id' => 1,
                'username' => sanitize($_POST['username']),
                'email' => sanitize($_POST['email']),
                'password' => $_POST['password'],
                'first_name' => sanitize($_POST['first_name']),
                'last_name' => sanitize($_POST['last_name']),
                'identification' => sanitize($_POST['identification']),
                'phone' => sanitize($_POST['phone'])
            ];

            if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
                setFlashMessage('error', 'Todos los campos obligatorios deben ser completados');
                redirect('index.php?page=register');
            }

            if (!isValidEmail($data['email'])) {
                setFlashMessage('error', 'Email inválido');
                redirect('index.php?page=register');
            }

            if ($this->userModel->findByUsername($data['username'])) {
                setFlashMessage('error', 'El nombre de usuario ya existe');
                redirect('index.php?page=register');
            }

            if ($this->userModel->findByEmail($data['email'])) {
                setFlashMessage('error', 'El email ya está registrado');
                redirect('index.php?page=register');
            }

            try {
                $userId = $this->userModel->create($data);
                
                $studentRole = $this->roleModel->findById(2);
                if ($studentRole) {
                    $this->roleModel->assignToUser($userId, $studentRole['id'], $userId);
                }

                setFlashMessage('success', 'Registro exitoso. Por favor inicia sesión');
                redirect('index.php?page=login');
            } catch (Exception $e) {
                setFlashMessage('error', 'Error al registrar usuario');
                redirect('index.php?page=register');
            }
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function recover() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email']);
            $user = $this->userModel->findByEmail($email);

            if ($user) {
                $token = generateToken();
                $this->userModel->updateResetToken($user['id'], $token);

                $resetLink = BASE_URL . 'index.php?page=reset&token=' . $token;
                
                $subject = 'Recuperación de contraseña - ' . SITE_NAME;
                $message = "Hola {$user['first_name']},\n\n";
                $message .= "Haz solicitado restablecer tu contraseña.\n";
                $message .= "Haz clic en el siguiente enlace para crear una nueva contraseña:\n\n";
                $message .= $resetLink . "\n\n";
                $message .= "Este enlace expirará en 1 hora.\n";
                $message .= "Si no solicitaste este cambio, ignora este mensaje.\n\n";
                $message .= "Saludos,\n" . SITE_NAME;

                $headers = "From: " . SMTP_FROM . "\r\n";
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                if (mail($email, $subject, $message, $headers)) {
                    setFlashMessage('success', 'Se ha enviado un enlace de recuperación a tu email');
                } else {
                    setFlashMessage('warning', 'Token generado pero el email no pudo ser enviado. Contacta al administrador.');
                }
            } else {
                setFlashMessage('error', 'No se encontró ningún usuario con ese email');
            }
            
            redirect('index.php?page=login');
        }

        require_once __DIR__ . '/../views/auth/recover.php';
    }

    public function reset() {
        $token = $_GET['token'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $newPassword = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword !== $confirmPassword) {
                setFlashMessage('error', 'Las contraseñas no coinciden');
                redirect('index.php?page=reset&token=' . $token);
            }

            $user = $this->userModel->verifyResetToken($token);

            if ($user) {
                $this->userModel->updatePassword($user['id'], $newPassword);
                setFlashMessage('success', 'Contraseña actualizada exitosamente');
                redirect('index.php?page=login');
            } else {
                setFlashMessage('error', 'Token inválido o expirado');
                redirect('index.php?page=recover');
            }
        }

        $user = $this->userModel->verifyResetToken($token);
        if (!$user) {
            setFlashMessage('error', 'Token inválido o expirado');
            redirect('index.php?page=recover');
        }

        require_once __DIR__ . '/../views/auth/reset.php';
    }

    public function logout() {
        destroySession();
        setFlashMessage('success', 'Sesión cerrada exitosamente');
        redirect('index.php?page=login');
    }
}

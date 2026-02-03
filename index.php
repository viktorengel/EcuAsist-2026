<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/helpers/session.php';

$page = $_GET['page'] ?? 'login';

if (!isLoggedIn() && !in_array($page, ['login', 'register', 'recover', 'reset'])) {
    redirect('index.php?page=login');
}

switch ($page) {
    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;
        
    case 'register':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;
        
    case 'recover':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->recover();
        break;
        
    case 'reset':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->reset();
        break;
        
    case 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;
        
    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case 'attendance-register':
        require_once __DIR__ . '/controllers/AttendanceController.php';
        $controller = new AttendanceController();
        $controller->register();
        break;
        
    case 'attendance-list':
        require_once __DIR__ . '/controllers/AttendanceController.php';
        $controller = new AttendanceController();
        $controller->list();
        break;
        
    case 'get-students':
        require_once __DIR__ . '/controllers/AttendanceController.php';
        $controller = new AttendanceController();
        $controller->getStudentsByCourse();
        break;
        
    case 'users':
        require_once __DIR__ . '/controllers/UserController.php';
        $controller = new UserController();
        $controller->list();
        break;
        
    case 'assign-role':
        require_once __DIR__ . '/controllers/UserController.php';
        $controller = new UserController();
        $controller->assignRole();
        break;
        
    case 'reports':
        require_once __DIR__ . '/controllers/ReportController.php';
        $controller = new ReportController();
        $controller->generate();
        break;
        
    default:
        redirect('index.php?page=login');
}

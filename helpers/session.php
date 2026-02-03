<?php

function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('index.php?page=login');
    }
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUserData($key = null) {
    if ($key) {
        return $_SESSION['user_data'][$key] ?? null;
    }
    return $_SESSION['user_data'] ?? null;
}

function setUserSession($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_data'] = [
        'username' => $user['username'],
        'email' => $user['email'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'institution_id' => $user['institution_id']
    ];
}

function destroySession() {
    session_destroy();
    $_SESSION = [];
}

function hasRole($roleName) {
    return isset($_SESSION['roles']) && in_array($roleName, $_SESSION['roles']);
}

function setUserRoles($roles) {
    $_SESSION['roles'] = array_column($roles, 'name');
}

function getUserRoles() {
    return $_SESSION['roles'] ?? [];
}

function requireRole($roleName) {
    if (!hasRole($roleName)) {
        setFlashMessage('error', 'No tienes permisos para acceder a esta página');
        redirect('index.php?page=dashboard');
    }
}

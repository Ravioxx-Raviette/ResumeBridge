<?php
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php');
        exit;
    }
}

function currentUser() {
    return $_SESSION ?? [];
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

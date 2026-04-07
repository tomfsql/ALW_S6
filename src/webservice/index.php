<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
}
$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'dashboard':
        require_once 'dashboard.php';
        break;

    case 'login':
        require_once 'login.php';
        break;

    case 'logout':
        require_once 'logout.php';
        break;

    default:
        require_once 'login.php';
        break;
}
?>
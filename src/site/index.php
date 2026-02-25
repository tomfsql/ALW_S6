<?php
require_once 'login.php';
require_once 'dashboard.php';
$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'dashboard':
        require_once 'dashboard.php';
        break;

    case 'login':
        require_once 'login.php';
        break;

    default:
        require_once 'login.php';
        break;
}
?>
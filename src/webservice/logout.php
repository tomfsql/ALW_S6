<?php
// 1. Clear all session variables
$_SESSION = [];

// 2. Destroy the session on the server
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}
exit;
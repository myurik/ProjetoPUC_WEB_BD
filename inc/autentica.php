<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function isLoggedIn(): bool {
        return !empty($_SESSION['user_id']);
    }

    function requireLogin(): void {
        if (!isLoggedIn()) {
            header('Location: ../pages/index.php');
            exit;
        }
    }

    function logout(): void {
        session_unset();
        session_destroy();
    }

?>
<?php
ob_start();
session_start();

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../admin/login.php');
        exit;
    }
}

function checkPermission() {
    requireLogin();
}

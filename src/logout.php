<?php
require_once __DIR__ . '/helpers/SessionHelper.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

// Destroy the session and redirect to the login page
session_unset();
session_destroy();
header("Location: /login.php");
exit();
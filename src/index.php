<?php

require_once __DIR__ . '/helpers/SessionHelper.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}

if (isset($_SESSION['user'])) {
    header("Location: /home.php");
} else {
    header("Location: /login.php");
}
exit();
?>

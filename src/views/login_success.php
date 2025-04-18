<?php
require_once __DIR__ . '/../helpers/SessionHelper.php';

if (!SessionHelper::isSessionStarted()) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Success</title>
</head>
<body>
    <h1>Login Successful</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['user']['nome']); ?>!</p>
</body>
</html>
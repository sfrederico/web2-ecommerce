<?php

include_once('init.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test</title>
</head>
<body>
    <h1>Database Connection Status</h1>
    <p>
        <?php
        if ($connection) {
            echo "Database connection successful!";
        } else {
            echo "Failed to connect to the database.";
        }
        ?>
    </p>
</body>
</html>


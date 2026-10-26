<?php
session_start();

session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <h1>Student Portal</h1>

    <nav>
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="preferences.php">Preferences</a>
    </nav>

    <h2>Logged Out</h2>

    <p>You have been successfully logged out.</p>

    <p>Your session has been destroyed.</p>

    <a class="button-link" href="index.php">
        Login Again
    </a>

</div>

</body>
</html>
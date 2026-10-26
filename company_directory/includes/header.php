
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('h')) {
    function h($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Directory</title>
    <link rel="stylesheet" href="/company_directory/css/style.css">
</head>
<body>
<div class="container">

    <header class="site-header">
        <h1>Company Directory</h1>
        <p>Discover, add, and manage company information.</p>
    </header>

    <nav class="navbar">
        <a href="/company_directory/index.php">Home</a>
        <a href="/company_directory/add_company.php">Add Company</a>
    </nav>

    <main>

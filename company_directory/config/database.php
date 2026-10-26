<?php
// Repository packaging: keep machine-specific credentials outside tracked code.
$configFile = dirname(__DIR__) . '/database.local.php';
if (!is_file($configFile)) {
    http_response_code(500);
    exit('Setup needed: copy database.example.php to database.local.php and edit its settings.');
}
$config = require $configFile;
try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
        $config['username'], $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check your local settings and MySQL.');
}

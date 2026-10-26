
<?php
require_once __DIR__ . "/config/database.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die("Delete requests must use POST.");
}

$token = $_POST["csrf_token"] ?? "";

if (
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    http_response_code(403);
    die("Invalid request.");
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    die("Invalid company ID.");
}

try {
    $stmt = $pdo->prepare(
        "DELETE FROM companies WHERE id = ?"
    );

    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        die("Company not found.");
    }

    header("Location: index.php?status=deleted");
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    die("Unable to delete the company.");
}

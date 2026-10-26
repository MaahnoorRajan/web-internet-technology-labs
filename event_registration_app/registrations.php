
<?php
require_once __DIR__ . "/db.php";

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

session_start();

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {
        http_response_code(403);
        die("Invalid request.");
    }

    $registrationId = filter_input(
        INPUT_POST, "registration_id", FILTER_VALIDATE_INT
    );

    if ($registrationId) {
        $stmt = $pdo->prepare(
            "DELETE FROM registrations WHERE id = ?"
        );

        $stmt->execute([$registrationId]);
    }

    header("Location: registrations.php?deleted=1");
    exit;
}

$sql = "SELECT r.id, u.name AS user_name,
               e.title AS event_title,
               r.registration_date
        FROM registrations r
        JOIN users u ON r.user_id = u.id
        JOIN events e ON r.event_id = e.id
        ORDER BY r.registration_date DESC";

$registrations = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Registrations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <header>
        <h1>Event Registrations</h1>
        <p>View and manage registered students.</p>
    </header>

    <nav>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="add_event.php">Add Event</a>
        <a class="active" href="registrations.php">Registrations</a>
    </nav>

    <h2>Registered Students</h2>

    <?php if (isset($_GET["deleted"])): ?>
        <div class="message success">
            Registration removal request processed.
        </div>
    <?php endif; ?>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Event</th>
                    <th>Registration Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($registrations) > 0): ?>
                    <?php foreach ($registrations as $row): ?>
                        <tr>
                            <td><?= (int)$row["id"] ?></td>
                            <td><?= h($row["user_name"]) ?></td>
                            <td><?= h($row["event_title"]) ?></td>
                            <td><?= h($row["registration_date"]) ?></td>
                            <td>
                                <form method="POST"
                                      action="registrations.php"
                                      onsubmit="return confirm('Delete this registration?');">

                                    <input type="hidden"
                                           name="registration_id"
                                           value="<?= (int)$row["id"] ?>">

                                    <input type="hidden"
                                           name="csrf_token"
                                           value="<?= h($_SESSION["csrf_token"]) ?>">

                                    <button type="submit" class="btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No registrations found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>

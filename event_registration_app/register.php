
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once __DIR__ . "/db.php";

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$users = $pdo->query(
    "SELECT id, name FROM users ORDER BY name"
)->fetchAll();

$events = $pdo->query(
    "SELECT id, title, event_date FROM events ORDER BY event_date"
)->fetchAll();

$message = "";
$messageType = "";

$selectedEvent = (int)($_GET["event_id"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = filter_input(INPUT_POST, "user_id", FILTER_VALIDATE_INT);
    $eventId = filter_input(INPUT_POST, "event_id", FILTER_VALIDATE_INT);

    $selectedEvent = (int)$eventId;

    if (!$userId || !$eventId) {
        $message = "Please select a user and an event.";
        $messageType = "error";
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO registrations
                 (user_id, event_id, registration_date)
                 VALUES (?, ?, NOW())"
            );

            $stmt->execute([$userId, $eventId]);

            $message = "Registration completed successfully!";
            $messageType = "success";

        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $message = "This registration already exists, or the selected record is invalid.";
            } else {
                $message = "Unable to complete registration.";
                error_log($e->getMessage());
            }

            $messageType = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <header>
        <h1>Event Registration</h1>
        <p>Choose a student and an event.</p>
    </header>

    <nav>
        <a href="index.php">Home</a>
        <a class="active" href="register.php">Register</a>
        <a href="add_event.php">Add Event</a>
        <a href="registrations.php">Registrations</a>
    </nav>

    <div class="form-card">
        <h2>Register for an Event</h2>

        <?php if ($message): ?>
            <div class="message <?= h($messageType) ?>">
                <?= h($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">

            <label for="user_id">Select User</label>
            <select name="user_id" id="user_id" required>
                <option value="">-- Select User --</option>

                <?php foreach ($users as $user): ?>
                    <option value="<?= (int)$user["id"] ?>"
                        <?= (string)($user["id"]) === (string)($_POST["user_id"] ?? "") ? "selected" : "" ?>>
                        <?= h($user["name"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="event_id">Select Event</label>
            <select name="event_id" id="event_id" required>
                <option value="">-- Select Event --</option>

                <?php foreach ($events as $event): ?>
                    <option value="<?= (int)$event["id"] ?>"
                        <?= (int)$event["id"] === $selectedEvent ? "selected" : "" ?>>
                        <?= h($event["title"]) ?>
                        (<?= h($event["event_date"]) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn">
                Register
            </button>

        </form>

        <p class="hint">
            Note: A user cannot register twice for the same event.
        </p>
    </div>

</div>
</body>
</html>

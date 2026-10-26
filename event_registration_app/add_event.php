<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . "/db.php";

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$categories = $pdo->query(
    "SELECT id, name FROM categories ORDER BY name"
)->fetchAll();

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $eventDate = trim($_POST["event_date"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $categoryId = filter_input(
        INPUT_POST, "category_id", FILTER_VALIDATE_INT
    );

    $date = DateTime::createFromFormat("!Y-m-d\TH:i", $eventDate);
    $validDate = $date && $date->format("Y-m-d\TH:i") === $eventDate;

    if ($title === "" || $location === "" || !$categoryId || !$validDate) {
        $message = "Please complete all required fields correctly.";
        $messageType = "error";
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO events
                 (title, description, event_date, location, category_id)
                 VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $title,
                $description,
                $date->format("Y-m-d H:i:s"),
                $location,
                $categoryId
            ]);

            $message = "Event added successfully!";
            $messageType = "success";

            $_POST = [];

        } catch (PDOException $e) {
            error_log($e->getMessage());
            $message = "Unable to add the event.";
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
    <title>Add Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <header>
        <h1>Add New Event</h1>
        <p>Create an event and save it to MySQL.</p>
    </header>

    <nav>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a class="active" href="add_event.php">Add Event</a>
        <a href="registrations.php">Registrations</a>
    </nav>

    <div class="form-card">
        <h2>Event Details</h2>

        <?php if ($message): ?>
            <div class="message <?= h($messageType) ?>">
                <?= h($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="add_event.php">

            <label for="title">Event Title</label>
            <input type="text" name="title" id="title"
                   maxlength="150"
                   value="<?= h($_POST["title"] ?? "") ?>"
                   required>

            <label for="description">Description</label>
            <textarea name="description" id="description"
                      rows="4"><?= h($_POST["description"] ?? "") ?></textarea>

            <label for="event_date">Event Date and Time</label>
            <input type="datetime-local" name="event_date"
                   id="event_date"
                   value="<?= h($_POST["event_date"] ?? "") ?>"
                   required>

            <label for="location">Location</label>
            <input type="text" name="location" id="location"
                   maxlength="150"
                   value="<?= h($_POST["location"] ?? "") ?>"
                   required>

            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" required>
                <option value="">-- Select Category --</option>

                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int)$category["id"] ?>"
                        <?= (string)$category["id"] === (string)($_POST["category_id"] ?? "") ? "selected" : "" ?>>
                        <?= h($category["name"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn">
                Add Event
            </button>

        </form>
    </div>

</div>
</body>
</html>

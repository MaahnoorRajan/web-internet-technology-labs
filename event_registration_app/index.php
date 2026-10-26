
<?php
require_once "db.php";

if (!isset($pdo)) {
    die("ERROR: PDO connection was not created!");
}

echo "Connection successful!";

$sql = "SELECT e.id, e.title, e.description,
               e.event_date, e.location,
               c.name AS category
        FROM events e
        JOIN categories c ON e.category_id = c.id
        ORDER BY e.event_date ASC";

$stmt = $pdo->query($sql);
$events = $stmt->fetchAll();

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <header>
        <h1>Event Registration System</h1>
        <p>Discover events and register for your favourites.</p>
    </header>

    <nav>
        <a class="active" href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="add_event.php">Add Event</a>
        <a href="registrations.php">Registrations</a>
    </nav>

    <h2>Available Events</h2>

    <div class="event-grid">
        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <span class="category">
                        <?= h($event["category"]) ?>
                    </span>

                    <h3><?= h($event["title"]) ?></h3>

                    <p><?= h($event["description"] ?? "") ?></p>

                    <p>
                        <strong>Date:</strong>
                        <?= h(date("d M Y, h:i A", strtotime($event["event_date"]))) ?>
                    </p>

                    <p>
                        <strong>Location:</strong>
                        <?= h($event["location"]) ?>
                    </p>

                    <a class="btn"
                       href="register.php?event_id=<?= (int)$event["id"] ?>">
                        Register Now
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No events available.</p>
        <?php endif; ?>
    </div>

</div>
</body>
</html>

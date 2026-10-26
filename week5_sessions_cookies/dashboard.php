<?php
session_start();

if (!isset($_SESSION["first_name"]) || !isset($_SESSION["last_name"])) {
    header("Location: index.php");
    exit();
}

$firstName = $_SESSION["first_name"];
$lastName = $_SESSION["last_name"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <h1>Student Portal</h1>

    <nav>
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="preferences.php">Preferences</a>
        <a href="logout.php">Logout</a>
    </nav>

    <h2>
        Welcome,
        <?php
        echo htmlspecialchars($firstName) . " " . htmlspecialchars($lastName);
        ?>!
    </h2>

    <p>You are now logged into the Student Portal.</p>

    <?php
    if (isset($_COOKIE["Language"])) {
        echo "<p><strong>Preferred Language:</strong> "
            . htmlspecialchars($_COOKIE["Language"])
            . "</p>";
    }
    ?>

    <p>
        Your name is stored in a PHP session, so you can move between
        pages without entering it again.
    </p>

    <a class="button-link" href="preferences.php">
        Set Language Preference
    </a>

</div>

</body>
</html>
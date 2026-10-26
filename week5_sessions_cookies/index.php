<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = trim($_POST["first_name"] ?? "");
    $lastName = trim($_POST["last_name"] ?? "");

    if (!empty($firstName) && !empty($lastName)) {

        $_SESSION["first_name"] = $firstName;
        $_SESSION["last_name"] = $lastName;

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Please enter both your first and last name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>
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

    <h2>Student Login</h2>

    <?php
    if (isset($error)) {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
    ?>

    <form method="POST" action="">

        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name">

        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name">

        <button type="submit">Login</button>

    </form>

</div>

</body>
</html>
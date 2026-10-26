<?php
session_start();

if (!isset($_SESSION["first_name"]) || !isset($_SESSION["last_name"])) {
    header("Location: index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $language = $_POST["language"] ?? "";

    if (!empty($language)) {

        setcookie(
            "Language",
            $language,
            time() + (30 * 24 * 60 * 60),
            "/"
        );

        $_COOKIE["Language"] = $language;

        $message = "Language preference saved successfully.";

    } else {
        $message = "Please select a language.";
    }
}

$currentLanguage = $_COOKIE["Language"] ?? "Not selected";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Preferences</title>
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

    <h2>Language Preferences</h2>

    <p>
        Student:
        <strong>
            <?php
            echo htmlspecialchars($_SESSION["first_name"])
                . " "
                . htmlspecialchars($_SESSION["last_name"]);
            ?>
        </strong>
    </p>

    <?php
    if (!empty($message)) {
        echo "<p class='success'>" . htmlspecialchars($message) . "</p>";
    }
    ?>

    <form method="POST" action="">

        <label for="language">Preferred Language</label>

        <select id="language" name="language">
            <option value="">Select a language</option>
            <option value="English">English</option>
            <option value="Urdu">Urdu</option>
            <option value="Russian">Russian</option>
            <option value="Kyrgyz">Kyrgyz</option>
        </select>

        <button type="submit">Save Preference</button>

    </form>

    <p>
        <strong>Current Preferred Language:</strong>
        <?php echo htmlspecialchars($currentLanguage); ?>
    </p>

</div>

</body>
</html>
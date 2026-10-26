<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registration Result</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Receive form data
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $school = trim($_POST["school"] ?? "");
    $occupation = trim($_POST["occupation"] ?? "");
    $city = trim($_POST["city"] ?? "");

    // Validation
    if (
        empty($first_name) ||
        empty($last_name) ||
        empty($email) ||
        empty($password) ||
        empty($school) ||
        empty($occupation) ||
        empty($city)
    ) {

        echo "<h2>Error</h2>";
        echo "<p>All fields are required.</p>";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "<h2>Error</h2>";
        echo "<p>Please enter a valid email address.</p>";

    } else {

        // Display submitted information safely
        echo "<h2>Registration Successful!</h2>";

        echo "<p><strong>First Name:</strong> " .
            htmlspecialchars($first_name) . "</p>";

        echo "<p><strong>Last Name:</strong> " .
            htmlspecialchars($last_name) . "</p>";

        echo "<p><strong>Email:</strong> " .
            htmlspecialchars($email) . "</p>";

        echo "<p><strong>School Graduated From:</strong> " .
            htmlspecialchars($school) . "</p>";

        echo "<p><strong>Occupation:</strong> " .
            htmlspecialchars($occupation) . "</p>";

        echo "<p><strong>City:</strong> " .
            htmlspecialchars($city) . "</p>";
    }

}

?>

</body>
</html>
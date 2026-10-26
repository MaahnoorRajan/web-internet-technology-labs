
<?php
require_once __DIR__ . "/config/database.php";

session_start();

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$errors = [];
$values = [
    "name" => "",
    "founding_date" => "",
    "office_address" => "",
    "phone" => "",
    "website_url" => "",
    "description" => "",
    "manager_name" => ""
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!hash_equals(
        $_SESSION["csrf_token"],
        $_POST["csrf_token"] ?? ""
    )) {
        die("Invalid form request.");
    }

    foreach ($values as $field => $value) {
        $values[$field] = trim($_POST[$field] ?? "");
    }

    $required = [
        "name", "founding_date", "office_address",
        "phone", "description", "manager_name"
    ];

    foreach ($required as $field) {
        if ($values[$field] === "") {
            $errors[] = "All required fields must be filled.";
            break;
        }
    }

    $date = DateTime::createFromFormat(
        "!Y-m-d",
        $values["founding_date"]
    );

    if (!$date || $date->format("Y-m-d") !== $values["founding_date"]) {
        $errors[] = "Enter a valid founding date.";
    }

    if (
        $values["website_url"] !== "" &&
        (!filter_var($values["website_url"], FILTER_VALIDATE_URL) ||
         !in_array(strtolower(parse_url($values["website_url"], PHP_URL_SCHEME) ?? ""), ["http", "https"]))
    ) {
        $errors[] = "Enter a valid HTTP or HTTPS website URL.";
    }

    $limits = [
        "name" => 150,
        "office_address" => 255,
        "phone" => 30,
        "website_url" => 255,
        "manager_name" => 150
    ];

    foreach ($limits as $field => $max) {
        if (strlen($values[$field]) > $max) {
            $errors[] = "The $field field is too long.";
        }
    }

    $logoName = null;
    $upload = $_FILES["logo"] ?? null;

    if ($upload && $upload["error"] !== UPLOAD_ERR_NO_FILE) {

        if ($upload["error"] !== UPLOAD_ERR_OK) {
            $errors[] = "Logo upload failed.";
        } elseif ($upload["size"] > 2 * 1024 * 1024) {
            $errors[] = "Logo must be smaller than 2 MB.";
        } else {

            $allowed = [
                "image/jpeg" => "jpg",
                "image/png" => "png",
                "image/gif" => "gif",
                "image/webp" => "webp"
            ];

            $mime = (new finfo(FILEINFO_MIME_TYPE))
                ->file($upload["tmp_name"]);

            $imageInfo = @getimagesize($upload["tmp_name"]);

            if (!isset($allowed[$mime]) || $imageInfo === false ||
                $imageInfo["mime"] !== $mime) {
                $errors[] = "Only valid JPG, PNG, GIF, or WEBP images are allowed.";
            } else {
                $logoName = bin2hex(random_bytes(16)) .
                    "." . $allowed[$mime];
            }
        }
    }

    if (empty($errors)) {
        $uploadedPath = null;

        try {
            if ($logoName !== null) {
                $folder = __DIR__ . "/uploads/logos/";

                if (!is_dir($folder)) {
                    mkdir($folder, 0755, true);
                }

                $uploadedPath = $folder . $logoName;

                if (!move_uploaded_file(
                    $upload["tmp_name"],
                    $uploadedPath
                )) {
                    throw new RuntimeException("Could not save logo.");
                }
            }

            $sql = "INSERT INTO companies
                (name, founding_date, office_address, phone,
                 website_url, description, logo, manager_name)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $values["name"],
                $values["founding_date"],
                $values["office_address"],
                $values["phone"],
                $values["website_url"] ?: null,
                $values["description"],
                $logoName,
                $values["manager_name"]
            ]);

            header("Location: index.php?status=added");
            exit;

        } catch (Throwable $e) {
            if ($uploadedPath && is_file($uploadedPath)) {
                unlink($uploadedPath);
            }

            error_log($e->getMessage());
            $errors[] = "Unable to save company. Please try again.";
        }
    }
}

require_once __DIR__ . "/includes/header.php";
?>

<h2>Add New Company</h2>

<div class="form-card">

    <?php foreach ($errors as $error): ?>
        <div class="error"><?= h($error) ?></div>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data">

        <input type="hidden" name="csrf_token"
               value="<?= h($_SESSION["csrf_token"]) ?>">

        <label>Company Name *</label>
        <input type="text" name="name" maxlength="150"
               value="<?= h($values["name"]) ?>" required>

        <label>Founding Date *</label>
        <input type="date" name="founding_date"
               value="<?= h($values["founding_date"]) ?>" required>

        <label>Office Address *</label>
        <input type="text" name="office_address" maxlength="255"
               value="<?= h($values["office_address"]) ?>" required>

        <label>Phone Number *</label>
        <input type="tel" name="phone" maxlength="30"
               value="<?= h($values["phone"]) ?>" required>

        <label>Website URL (Optional)</label>
        <input type="url" name="website_url" maxlength="255"
               value="<?= h($values["website_url"]) ?>">

        <label>Description of Activities *</label>
        <textarea name="description" required><?= h($values["description"]) ?></textarea>

        <label>Company Logo (Optional, Max 2 MB)</label>
        <input type="file" name="logo"
               accept=".jpg,.jpeg,.png,.gif,.webp">

        <label>Manager's Full Name *</label>
        <input type="text" name="manager_name" maxlength="150"
               value="<?= h($values["manager_name"]) ?>" required>

        <button class="btn" type="submit">Save Company</button>
        <a class="btn" href="index.php">Cancel</a>

    </form>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>

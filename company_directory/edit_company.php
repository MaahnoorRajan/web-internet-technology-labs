
<?php
require_once __DIR__ . "/config/database.php";

session_start();

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    die("Invalid company ID. <a href='index.php'>Back</a>");
}

$stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
$stmt->execute([$id]);
$company = $stmt->fetch();

if (!$company) {
    http_response_code(404);
    die("Company not found. <a href='index.php'>Back</a>");
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!hash_equals(
        $_SESSION["csrf_token"],
        $_POST["csrf_token"] ?? ""
    )) {
        die("Invalid form request.");
    }

    $fields = [
        "name", "founding_date", "office_address",
        "phone", "website_url", "description",
        "manager_name"
    ];

    foreach ($fields as $field) {
        $company[$field] = trim($_POST[$field] ?? "");
    }

    foreach ([
        "name", "founding_date", "office_address",
        "phone", "description", "manager_name"
    ] as $field) {
        if ($company[$field] === "") {
            $errors[] = "All required fields must be filled.";
            break;
        }
    }

    $date = DateTime::createFromFormat(
        "!Y-m-d",
        $company["founding_date"]
    );

    if (!$date || $date->format("Y-m-d") !== $company["founding_date"]) {
        $errors[] = "Enter a valid founding date.";
    }

    if (
        $company["website_url"] !== "" &&
        (!filter_var($company["website_url"], FILTER_VALIDATE_URL) ||
         !in_array(strtolower(parse_url($company["website_url"], PHP_URL_SCHEME) ?? ""), ["http", "https"]))
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
        if (strlen($company[$field]) > $max) {
            $errors[] = "The $field field is too long.";
        }
    }

    $newLogo = null;
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
                $newLogo = bin2hex(random_bytes(16)) .
                    "." . $allowed[$mime];
            }
        }
    }

    if (empty($errors)) {
        $uploadedPath = null;

        try {
            if ($newLogo !== null) {
                $folder = __DIR__ . "/uploads/logos/";

                if (!is_dir($folder)) {
                    mkdir($folder, 0755, true);
                }

                $uploadedPath = $folder . $newLogo;

                if (!move_uploaded_file(
                    $upload["tmp_name"],
                    $uploadedPath
                )) {
                    throw new RuntimeException("Could not save new logo.");
                }
            }

            $logoToSave = $newLogo ?? $company["logo"];

            $sql = "UPDATE companies SET
                    name = ?,
                    founding_date = ?,
                    office_address = ?,
                    phone = ?,
                    website_url = ?,
                    description = ?,
                    logo = ?,
                    manager_name = ?
                    WHERE id = ?";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $company["name"],
                $company["founding_date"],
                $company["office_address"],
                $company["phone"],
                $company["website_url"] ?: null,
                $company["description"],
                $logoToSave,
                $company["manager_name"],
                $id
            ]);

            header("Location: index.php?status=updated");
            exit;

        } catch (Throwable $e) {
            if ($uploadedPath && is_file($uploadedPath)) {
                unlink($uploadedPath);
            }

            error_log($e->getMessage());
            $errors[] = "Unable to update company.";
        }
    }
}

require_once __DIR__ . "/includes/header.php";
?>

<h2>Edit Company</h2>

<div class="form-card">

    <?php foreach ($errors as $error): ?>
        <div class="error"><?= h($error) ?></div>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data"
          action="edit_company.php?id=<?= (int)$id ?>">

        <input type="hidden" name="csrf_token"
               value="<?= h($_SESSION["csrf_token"]) ?>">

        <label>Company Name *</label>
        <input type="text" name="name" maxlength="150"
               value="<?= h($company["name"]) ?>" required>

        <label>Founding Date *</label>
        <input type="date" name="founding_date"
               value="<?= h($company["founding_date"]) ?>" required>

        <label>Office Address *</label>
        <input type="text" name="office_address" maxlength="255"
               value="<?= h($company["office_address"]) ?>" required>

        <label>Phone Number *</label>
        <input type="tel" name="phone" maxlength="30"
               value="<?= h($company["phone"]) ?>" required>

        <label>Website URL (Optional)</label>
        <input type="url" name="website_url" maxlength="255"
               value="<?= h($company["website_url"] ?? "") ?>">

        <label>Description of Activities *</label>
        <textarea name="description" required><?= h($company["description"]) ?></textarea>

        <label>Current Logo</label>
        <?php if (!empty($company["logo"])): ?>
            <p>
                <img class="company-logo"
                     src="uploads/logos/<?= rawurlencode($company["logo"]) ?>"
                     alt="Current company logo">
            </p>
        <?php else: ?>
            <p>No logo uploaded.</p>
        <?php endif; ?>

        <label>Replace Logo (Optional, Max 2 MB)</label>
        <input type="file" name="logo"
               accept=".jpg,.jpeg,.png,.gif,.webp">

        <p>Leave the logo field empty to keep the current logo.</p>

        <label>Manager's Full Name *</label>
        <input type="text" name="manager_name" maxlength="150"
               value="<?= h($company["manager_name"]) ?>" required>

        <button class="btn" type="submit">Update Company</button>
        <a class="btn" href="index.php">Cancel</a>

    </form>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>

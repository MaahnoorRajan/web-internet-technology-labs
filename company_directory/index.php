
<?php
require_once __DIR__ . "/config/database.php";

$stmt = $pdo->query(
    "SELECT * FROM companies ORDER BY id DESC"
);
$companies = $stmt->fetchAll();

require_once __DIR__ . "/includes/header.php";
?>

<h2>Registered Companies</h2>

<?php if (isset($_GET['status'])): ?>
    <?php
    $messages = [
        'added' => 'Company added successfully!',
        'updated' => 'Company updated successfully!',
        'deleted' => 'Company deleted successfully!'
    ];
    ?>
    <?php if (isset($messages[$_GET['status']])): ?>
        <div class="success">
            <?= h($messages[$_GET['status']]) ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="company-grid">
    <?php if (!$companies): ?>
        <p>No companies found. Add your first company!</p>
    <?php endif; ?>

    <?php foreach ($companies as $company): ?>
        <article class="company-card">

            <?php if (!empty($company['logo'])): ?>
                <img class="company-logo"
                     src="uploads/logos/<?= rawurlencode($company['logo']) ?>"
                     alt="Logo of <?= h($company['name']) ?>">
            <?php else: ?>
                <div class="logo-placeholder">No Logo</div>
            <?php endif; ?>

            <h3><?= h($company['name']) ?></h3>

            <p><strong>Founded:</strong>
                <?= h($company['founding_date']) ?></p>

            <p><strong>Address:</strong>
                <?= h($company['office_address']) ?></p>

            <p><strong>Phone:</strong>
                <?= h($company['phone']) ?></p>

            <p><strong>Manager:</strong>
                <?= h($company['manager_name']) ?></p>

            <p><strong>Description:</strong>
                <?= nl2br(h($company['description'])) ?></p>

            <?php if (!empty($company['website_url'])): ?>
                <p>
                    <a href="<?= h($company['website_url']) ?>"
                       target="_blank" rel="noopener noreferrer">
                        Visit Website
                    </a>
                </p>
            <?php endif; ?>

            <div class="actions">
                <a class="btn"
                   href="edit_company.php?id=<?= (int)$company['id'] ?>">
                    Edit
                </a>

                <form method="POST" action="delete_company.php"
                      onsubmit="return confirm('Are you sure you want to delete this company?');">
                    <input type="hidden" name="id"
                           value="<?= (int)$company['id'] ?>">
                    <input type="hidden" name="csrf_token"
                           value="<?= h($_SESSION['csrf_token']) ?>">
                    <button type="submit" class="btn-danger">
                        Delete
                    </button>
                </form>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>

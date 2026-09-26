<?php
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card">
        <div class="dashboard-header">
            <h1>Dashboard</h1>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div class="user-info">
            <p>Nama: <span><?= htmlspecialchars($_SESSION['nama']) ?></span></p>
            <p>Email: <span><?= htmlspecialchars($_SESSION['email']) ?></span></p>
        </div>

        <div class="actions">
            <a href="edit_profile.php" class="btn btn-secondary">Edit Profile</a>
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>
</body>
</html>

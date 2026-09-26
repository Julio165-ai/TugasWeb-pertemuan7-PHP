<?php
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$errors = [];
$users = loadUsers();
$currentUser = null;

foreach ($users as $u) {
    if ($u['id'] === $_SESSION['user_id']) {
        $currentUser = $u;
        break;
    }
}

if (!$currentUser) {
    header('Location: logout.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitize($_POST['nama'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($nama)) {
        $errors[] = 'Nama tidak boleh kosong.';
    }

    if (!empty($newPassword)) {
        if (strlen($newPassword) < 6) {
            $errors[] = 'Password baru minimal 6 karakter.';
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = 'Konfirmasi password baru tidak cocok.';
        }
    }

    if (empty($errors)) {
        foreach ($users as &$u) {
            if ($u['id'] === $_SESSION['user_id']) {
                $u['nama'] = $nama;
                if (!empty($newPassword)) {
                    $u['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
            }
        }
        unset($u);

        if (saveUsers($users)) {
            $_SESSION['nama'] = $nama;
            redirectWith('dashboard.php', 'success', 'Profile berhasil diperbarui.');
        } else {
            $errors[] = 'Gagal menyimpan perubahan.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card">
        <h1>Edit Profile</h1>
        <p class="subtitle">Perbarui informasi akun kamu</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="edit_profile.php">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($currentUser['nama']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email (tidak bisa diubah)</label>
                <input type="email" id="email" value="<?= htmlspecialchars($currentUser['email']) ?>" disabled>
            </div>

            <div class="form-group">
                <label for="new_password">Password Baru (kosongkan jika tidak diubah)</label>
                <input type="password" id="new_password" name="new_password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password Baru</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>

            <button type="submit">Simpan Perubahan</button>
        </form>

        <div class="link-row">
            <a href="dashboard.php">← Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>

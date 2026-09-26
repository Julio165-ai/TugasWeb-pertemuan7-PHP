<?php
require_once __DIR__ . '/includes/functions.php';

// Hapus remember token dari data user jika ada
if (isset($_SESSION['user_id'])) {
    $users = loadUsers();
    foreach ($users as &$u) {
        if ($u['id'] === $_SESSION['user_id']) {
            $u['remember_token'] = null;
        }
    }
    unset($u);
    saveUsers($users);
}

// Hapus cookie remember me
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Hapus semua data session dan hancurkan session
$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;

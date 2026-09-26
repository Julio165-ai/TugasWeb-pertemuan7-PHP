<?php
session_start();

define('USERS_FILE', __DIR__ . '/../data/users.json');

/**
 * Baca semua user dari file JSON
 */
function loadUsers(): array {
    if (!file_exists(USERS_FILE)) {
        file_put_contents(USERS_FILE, json_encode([]));
    }
    $json = file_get_contents(USERS_FILE);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

/**
 * Simpan array user ke file JSON
 */
function saveUsers(array $users): bool {
    return file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT)) !== false;
}

/**
 * Cari user berdasarkan email
 */
function findUserByEmail(string $email): ?array {
    $users = loadUsers();
    foreach ($users as $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            return $user;
        }
    }
    return null;
}

/**
 * Generate ID unik sederhana untuk user baru
 */
function generateUserId(array $users): int {
    if (empty($users)) return 1;
    $ids = array_column($users, 'id');
    return max($ids) + 1;
}

/**
 * Sanitasi input string
 */
function sanitize(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Cek apakah user sedang login (termasuk cek cookie "remember me")
 */
function isLoggedIn(): bool {
    if (isset($_SESSION['user_id'])) {
        return true;
    }

    // Cek remember me cookie
    if (isset($_COOKIE['remember_token'])) {
        $users = loadUsers();
        foreach ($users as $user) {
            if (isset($user['remember_token']) && $user['remember_token'] === $_COOKIE['remember_token']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['email'] = $user['email'];
                return true;
            }
        }
    }

    return false;
}

/**
 * Wajib login, redirect ke login.php jika belum
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Redirect helper
 */
function redirectWith(string $location, string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    header("Location: $location");
    exit;
}

/**
 * Ambil dan hapus flash message
 */
function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

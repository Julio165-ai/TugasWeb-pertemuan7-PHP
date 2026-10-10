# Sistem Login & Registrasi PHP

Aplikasi autentikasi pengguna sederhana berbasis **PHP native** tanpa database. Data pengguna disimpan dalam file JSON. Mencakup registrasi, login, fitur "Ingat saya", dashboard, edit profil, dan logout.

## Fitur

- **Registrasi** dengan validasi nama, format email, email unik, dan password minimal 6 karakter beserta konfirmasinya
- **Login** dengan verifikasi password ter-hash (`password_verify`)
- **Ingat saya (Remember Me)**: cookie token acak yang berlaku 30 hari
- **Dashboard** yang hanya bisa diakses setelah login, menampilkan nama dan email
- **Edit profil**: ubah nama dan password (email tidak bisa diubah)
- **Logout** yang menghapus session, cookie, dan token remember me
- **Flash message** untuk notifikasi sukses/gagal antar halaman
- Tampilan tema gelap yang responsif

## Struktur Proyek

```
.
├── index.php           # Pengarah: ke dashboard jika sudah login, jika belum ke login
├── register.php        # Halaman pendaftaran
├── login.php           # Halaman login
├── dashboard.php       # Halaman utama setelah login
├── edit_profile.php    # Ubah nama dan password
├── logout.php          # Keluar dan hapus session/cookie
├── style.css           # Tampilan
├── includes/
│   └── functions.php   # Fungsi bantu: session, load/save user, auth, flash
└── data/
    └── users.json      # Penyimpanan data pengguna (awalnya berisi [])
```

> `functions.php` harus berada di folder `includes/` dan `users.json` di folder `data/`, karena path-nya dirujuk seperti itu oleh kode (`includes/functions.php` dan `../data/users.json`).

## Cara Menjalankan

**Kebutuhan**: PHP 7.4 atau lebih baru (memakai type hint dan `?array`).

1. Susun file sesuai struktur di atas.
2. Pastikan folder `data/` dapat ditulis oleh PHP.
3. Jalankan server bawaan PHP dari folder proyek:

   ```bash
   php -S localhost:8000
   ```

4. Buka `http://localhost:8000` di browser, daftar akun baru, lalu login.

Alternatif: letakkan folder proyek di `htdocs` (XAMPP) atau `www` (Laragon/WAMP) dan akses lewat `http://localhost/nama-folder`.

## Alur Aplikasi

```
index.php ─┬─ sudah login ──▶ dashboard.php ─┬─▶ edit_profile.php
           └─ belum login ──▶ login.php      └─▶ logout.php
                                 └──▶ register.php
```

## Konsep yang Dipakai

- **Session** PHP untuk status login
- **`password_hash` / `password_verify`** untuk penyimpanan password aman
- **`random_bytes`** untuk token remember me
- **`htmlspecialchars`** saat menampilkan data untuk mencegah XSS
- **Pola Post/Redirect/Get** dengan flash message
- Penyimpanan berbasis **file JSON** (`json_encode` / `json_decode`)

## Catatan Keamanan dan Pengembangan

Proyek ini cocok untuk pembelajaran. Sebelum dipakai di produksi, perhatikan hal berikut:

- **Lindungi folder `data/`** agar tidak bisa diakses lewat browser (misalnya dengan `.htaccess` `Deny from all`, atau pindahkan di luar document root), karena berisi hash password dan token.
- Token remember me disimpan **apa adanya** di JSON. Sebaiknya simpan versi hash-nya.
- Belum ada **proteksi CSRF** pada form.
- Belum memanggil `session_regenerate_id(true)` setelah login (risiko session fixation).
- `sanitize()` memakai `htmlspecialchars` saat menyimpan input, lalu di-escape lagi saat ditampilkan, sehingga karakter seperti `&` atau `'` pada nama bisa tampil ganda ter-encode.
- Penyimpanan JSON tidak aman untuk akses bersamaan (tidak ada file locking) dan tidak cocok untuk banyak pengguna. Untuk skala lebih besar, gunakan database seperti MySQL dengan PDO.

---

&copy; 2026 Sistem Login & Registrasi PHP

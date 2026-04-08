# StomaCare — Setup Guide (XAMPP)

## Struktur Folder Final
```
stomacare/
├── assets/
│   ├── css/
│   │   ├── style.css          ← CSS global (Tailwind custom vars + komponen)
│   │   └── food-diary.css     ← CSS khusus halaman food diary
│   ├── js/
│   │   ├── script.js          ← JS global (hamburger, fade-in)
│   │   ├── auth.js            ← Toggle login/register panel
│   │   └── food-diary.js      ← Logic food diary (validasi, render, slider)
│   └── img/
│       ├── logo.png           ← ⚠️ Copy manual dari Assets lama
│       ├── blob.png           ← ⚠️ Copy manual dari Assets lama
│       └── stomach.png        ← ⚠️ Copy manual dari Assets lama
├── config/
│   ├── db.php                 ← Koneksi MySQL (edit user/pass jika perlu)
│   └── session.php            ← Helper session (requireLogin, isLoggedIn, dll)
├── includes/
│   ├── header.php             ← Navbar dinamis (deteksi login state)
│   └── footer.php             ← Footer + load script.js
├── api/
│   ├── login.php              ← POST: proses login
│   ├── register.php           ← POST: proses registrasi
│   ├── logout.php             ← Destroy session, redirect
│   ├── save_diary.php         ← POST: simpan food diary
│   ├── get_diary.php          ← GET: ambil diary by tanggal
│   └── delete_diary.php       ← POST: hapus diary entry
├── index.php                  ← Landing page
├── about.php                  ← Halaman About Us + Tim
├── auth.php                   ← Login & Register (Tailwind)
├── food-diary.php             ← Halaman Food Diary (butuh login)
└── stomacare_db.sql           ← Schema database
```

---

## Langkah Setup

### 1. Copy ke htdocs
```
C:\xampp\htdocs\stomacare\
```

### 2. Copy gambar dari proyek lama
```
Assets/image 1 (logo_stomacare).png  →  assets/img/logo.png
Assets/fluent_shape-organic-16-filled (1).png  →  assets/img/blob.png
Assets/Vector (1).png  →  assets/img/stomach.png
```

### 3. Import Database
- Buka **phpMyAdmin** → http://localhost/phpmyadmin
- Klik **Import** → pilih file `stomacare_db.sql`
- Klik **Go**

### 4. Jalankan
```
http://localhost/stomacare/
```

---

## Alur Aplikasi

```
Buka index.php
    ↓
Klik Login/Register → auth.php
    ├── Register → POST /api/register.php → redirect auth.php?success=...
    └── Login    → POST /api/login.php    → redirect index.php (session aktif)
         ↓
    Navbar muncul: Food Diary + nama user + tombol Logout
         ↓
    food-diary.php (protected, redirect ke auth jika belum login)
         ├── Load riwayat hari ini dari DB (PHP server-side)
         ├── Simpan catatan → fetch POST /api/save_diary.php
         ├── Hapus catatan  → fetch POST /api/delete_diary.php
         └── Filter tanggal → fetch GET /api/get_diary.php?tanggal=YYYY-MM-DD
```

---

## Catatan Penting

- `config/db.php` → password default XAMPP kosong `''`, ubah jika berbeda
- Semua halaman yang butuh login sudah pakai `requireLogin()` dari `session.php`
- Data food diary tersimpan permanen di MySQL, tidak hilang saat refresh
- CSS konsisten 100% Tailwind (auth.css lama sudah dihapus)

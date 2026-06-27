# PawSpa - Sistem Reservasi Grooming Pet

Aplikasi PHP MVC manual untuk reservasi grooming pet.

## Jalankan

1. Pastikan Apache dan MySQL XAMPP aktif.
2. Buka `http://localhost/pwl_uas/public/`.
3. Database `pwl_uas_db` akan dibuat otomatis jika belum ada.

## Akun Demo

- Admin: `admin@pawspa.com` / `admin123`
- User: `user@demo.com` / `user123`

## Aset

Gambar dari `D:\kuliyah\SEMESTER 4\PWL\UAS\GAMBAR` sudah disalin ke:

- `public/assets/images`
- `public/uploads/services`

Perintah salin manual:

```powershell
Copy-Item -Path 'D:\kuliyah\SEMESTER 4\PWL\UAS\GAMBAR\*' -Destination 'C:\xampp\htdocs\pwl_uas\public\assets\images' -Force
Copy-Item -Path 'D:\kuliyah\SEMESTER 4\PWL\UAS\GAMBAR\*' -Destination 'C:\xampp\htdocs\pwl_uas\public\uploads\services' -Force
```

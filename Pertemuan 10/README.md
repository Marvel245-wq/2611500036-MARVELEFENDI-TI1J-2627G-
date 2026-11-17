# Pertemuan 09 – Larik, Fungsi, dan Modularisasi PHP

## Perubahan pada P9

1. Menggunakan larik asosiatif `$_SESSION["form_data"]` untuk menyimpan seluruh data formulir dalam satu variabel session.
2. Menggunakan larik yang dikirim dari checkbox `name="minat[]"` untuk menampung beberapa topik yang dipilih pengguna.
3. Menggunakan `foreach` untuk menampilkan data formulir berdasarkan larik asosiatif `$labelMap` dan menampilkan setiap topik yang tersimpan pada larik `minat`.
4. Memisahkan fungsi bersihkan() dan tebal() ke dalam berkas fungsi.php, kemudian memuatnya dari index.php menggunakan require_once().
5. Fungsi `bersihkan()` menerima parameter teks dan mengembalikan hasil pemrosesan menggunakan `htmlspecialchars()`. Fungsi `tebal()` menerima teks dan mengembalikan teks yang dibungkus elemen `<strong>`.

## Berkas

- `index.php`: halaman profil, pemrosesan formulir, penyimpanan data formulir ke session, serta penyajian data larik.
- `fungsi.php`: fungsi `bersihkan()` dan `tebal()`.
- `script.js`: navigasi responsif dan validasi formulir sisi klien.
- `style.css`: pengaturan tampilan halaman.
- `img/foto-profil.jpg`: foto profil.

## Pengujian

1. Jalankan aplikasi melalui localhost menggunakan lingkungan peladen lokal yang digunakan pada pertemuan sebelumnya.
2. Buka halaman profil dan pastikan halaman tampil tanpa pesan uji/debug.
3. Isi formulir Kontak dan pilih satu atau lebih topik yang diminati.
4. Klik **Kirim**.
5. Pastikan halaman kembali ke bagian Kontak dan data formulir ditampilkan.
6. Periksa apakah setiap topik yang dipilih ditampilkan sebagai satu butir daftar.
7. Muat ulang halaman dan periksa bahwa data session masih ditampilkan.
8. Periksa kode `index.php` untuk melihat pemanggilan `include()`, penggunaan larik, `foreach`, dan pemanggilan fungsi dari `fungsi.php`.

Catatan: pengujian melalui peramban perlu dilakukan pada lingkungan localhost. Pemeriksaan sintaks PHP dapat dilakukan secara terpisah menggunakan `php -l`.

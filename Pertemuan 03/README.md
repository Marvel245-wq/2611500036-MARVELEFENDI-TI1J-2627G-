# Pertemuan 3 - Formulir HTML dan CSS Dasar

## Baseline

- Menggunakan hasil P2 sebagai dasar pengembangan P3.
- Menyalin `index.html` dan `img/foto-profile.png` ke folder `Pertemuan 03/`.

## Implementasi Formulir

- Elemen form yang digunakan: `form`, `label`, `input`, `select`, `option`, `textarea`, dan `button`.
- Tipe input yang digunakan: `text`, `email`, `number`, `date`, `radio`, dan `checkbox`.
- Atribut validasi yang digunakan: `required`, `minlength="3"`, `maxlength="50"`, `min="1"`, `max="14"`, dan `maxlength="300"` pada textarea. Email juga divalidasi oleh `type="email"`.

## Pengujian GET dan POST

- Hasil pengujian GET: Form menggunakan `method="get"` dan mengirim data ke `index.html`. Nilai form dikirim sebagai query string pada URL.
- Contoh URL encoding yang ditemukan: Spasi pada nama dikodekan sebagai `+` dan karakter `@` sebagai `%40`, misalnya `?nama=Marvel+Efendi&email=marvel%40email.com`.
- Hasil pengujian POST: Belum diterapkan atau diuji. Form saat ini menggunakan GET; pemrosesan POST memerlukan perubahan metode dan server yang dapat menerima data.

## CSS Dasar

- Selector elemen: `h2`, `h3`, `p`, `ol`, `label`, dan `button`, digunakan bersama selector ID seperti `#about h2` dan `#contact label`.
- Selector class: `.form-group` dan `.input-form`.
- Selector ID: `#about` dan `#contact`.
- Properti CSS dasar yang digunakan: `background-color`, `color`, `border`, `border-bottom`, `padding`, `margin`, `font-family`, dan `font-weight`.

## Pengujian dan Perbaikan

- Galat yang ditemukan: Path foto sebelumnya memakai `IMG/foto-profile.png`, padahal nama folder adalah `img`. Selain itu, teks `required` pada textarea berada di dalam atribut `placeholder`, sehingga textarea tidak wajib diisi; `for` pada label radio juga tidak sama persis dengan `id` input.
- Penyebab galat: GitHub Pages membedakan huruf besar dan kecil pada nama path. Atribut HTML harus ditulis terpisah agar dikenali sebagai validasi, dan nilai `for` harus cocok dengan `id`.
- Perbaikan yang dilakukan: Path foto diubah menjadi `img/foto-profile.png`. Catatan: validasi textarea dan pasangan label radio masih perlu diperbaiki di `index.html`.
- Hasil pengujian ulang: Foto berhasil dimuat pada GitHub Pages; browser melaporkan ukuran gambar 472 × 591 piksel. Form POST belum diuji karena belum diterapkan.

## GitHub Pages

URL: [https://marvel245-wq.github.io/2611500036-MARVELEFENDI-TI1J-2627G-/Pertemuan%2003/]

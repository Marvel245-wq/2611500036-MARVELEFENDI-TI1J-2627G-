# P5 - JavaScript Dasar, DOM, Event, dan Validasi Sisi Klien

## Deskripsi
Halaman Profil Mahasiswa menerapkan JavaScript dasar untuk memanipulasi DOM, menangani event
navigasi dan formulir, serta melakukan validasi sisi klien.

## Fitur dan Pengembangan
- **Struktur halaman (`index.html`):** menampilkan profil, navigasi Beranda/Tentang/Kontak, dan formulir kontak. Formulir menyediakan isian nama, email, semester, tanggal kunjungan, jenis pesan, topik minat, program studi, dan pesan.
- **Tampilan responsif (`style.css`):** menggunakan CSS Grid untuk menyusun konten menjadi satu kolom pada layar kecil dan dua kolom mulai lebar 768 piksel. Pada layar hingga 767 piksel, menu navigasi dapat dibuka dengan tombol Menu.
- **Interaksi navigasi (`script.js`):** event `click` pada tombol menu menampilkan/menyembunyikan navigasi, memperbarui atribut `aria-expanded`, dan mengganti label aksesibilitas tombol.
- **Validasi formulir (`script.js`):** event `submit` memeriksa nama (3–50 karakter), format email, semester (1–14), tanggal kunjungan, pilihan jenis pesan, minimal satu topik, program studi, serta pesan (wajib diisi dan maksimal 300 karakter). Pesan hasil validasi ditampilkan pada elemen `#formFeedback`.
- **Reset dan input (`script.js`):** tombol Batal mereset formulir; event `reset` dan `input` mengosongkan pesan feedback.

## Pengujian
- **Validasi data lengkap:** halaman dibuka di browser dengan isian valid, satu jenis pesan, dan minimal satu topik dipilih; hasil yang terlihat adalah “Form berhasil divalidasi.”
- **Validasi data tidak lengkap/tidak sesuai:** kode menampilkan pesan kesalahan yang sesuai untuk setiap pemeriksaan. Pengujian manual kasus-kasus kesalahan tersebut belum dicatat.
- **Navigasi responsif:** CSS mengatur tombol menu untuk layar kecil (maksimum 767 piksel); pengujian interaksi pada ukuran layar kecil belum dicatat.

## Riwayat Pengembangan
- `94a8f3b` — Memperbaiki JavaScript dan menambahkan perubahan pada CSS serta HTML.
- `dc5b977` — Menambahkan kode JavaScript.
- `5f389b8` — Menghubungkan JavaScript eksternal ke HTML.
- `78e1973` — Menyalin halaman HTML dan CSS ke P5.

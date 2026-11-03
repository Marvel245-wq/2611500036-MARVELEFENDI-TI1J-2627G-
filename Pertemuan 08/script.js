// Navigasi responsif.
const navToggle = document.getElementById("navToggle");
const navMenu = document.getElementById("navMenu");

if (navToggle && navMenu) {
  navToggle.addEventListener("click", () => {
    const isOpen = navMenu.classList.toggle("nav-open");
    navToggle.setAttribute("aria-expanded", String(isOpen));
    navToggle.setAttribute(
      "aria-label",
      isOpen ? "Tutup navigasi" : "Buka navigasi",
    );
  });
}

// Validasi formulir Peminjaman Fasilitas di sisi klien.
const loanForms = document.querySelectorAll(
  'form[data-validation="peminjaman"]',
);

loanForms.forEach((loanForm) => {
  const formFeedback = loanForm.querySelector("[data-form-feedback]");
  const fields = {
    nama: loanForm.elements.namedItem("loan_nama"),
    email: loanForm.elements.namedItem("loan_email"),
    fasilitas: loanForm.elements.namedItem("loan_fasilitas"),
    tanggal: loanForm.elements.namedItem("loan_tanggal"),
    waktu: loanForm.elements.namedItem("loan_waktu"),
    keperluan: loanForm.elements.namedItem("loan_keperluan"),
  };

  const showError = (message) => {
    if (formFeedback) {
      formFeedback.textContent = message;
      formFeedback.className = "form-feedback-error";
    }
  };

  loanForm.addEventListener("submit", (event) => {
    event.preventDefault();

    fields.nama.value = fields.nama.value.trim();
    fields.email.value = fields.email.value.trim();
    fields.keperluan.value = fields.keperluan.value.trim();

    if (formFeedback) {
      formFeedback.textContent = "";
      formFeedback.className = "form-feedback";
    }

    const namaLength = Array.from(fields.nama.value).length;
    if (namaLength < 3 || namaLength > 50) {
      showError("Nama penanggung jawab harus terdiri dari 3 sampai 50 karakter.");
      return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fields.email.value)) {
      showError("Masukkan alamat email yang valid.");
      return;
    }

    const fasilitasValid = [
      "ruang-kelas",
      "laboratorium",
      "aula",
      "Proyektor",
    ].includes(fields.fasilitas.value);
    if (!fasilitasValid) {
      showError("Pilih fasilitas yang tersedia.");
      return;
    }

    if (
      !/^\d{4}-\d{2}-\d{2}$/.test(fields.tanggal.value) ||
      Number.isNaN(fields.tanggal.valueAsNumber)
    ) {
      showError("Masukkan tanggal peminjaman yang valid.");
      return;
    }

    if (!/^(?:[01]\d|2[0-3]):[0-5]\d$/.test(fields.waktu.value)) {
      showError("Masukkan waktu peminjaman yang valid.");
      return;
    }

    const keperluanLength = Array.from(fields.keperluan.value).length;
    if (keperluanLength < 1 || keperluanLength > 300) {
      showError("Keperluan wajib diisi dan maksimal 300 karakter.");
      return;
    }

    loanForm.submit();
  });

  loanForm.addEventListener("reset", () => {
    if (formFeedback) {
      formFeedback.textContent = "";
      formFeedback.className = "form-feedback";
    }
  });

  loanForm.addEventListener("input", () => {
    if (formFeedback) {
      formFeedback.textContent = "";
      formFeedback.className = "form-feedback";
    }
  });
});

// Validasi formulir Kontak.
// Selector menggunakan atribut/class, bukan ID formulir tertentu,
// sehingga formulir lain dapat ditambahkan dengan ID yang berbeda.
const contactForms = document.querySelectorAll(
  'form[data-validation="contact"]',
);

contactForms.forEach((contactForm) => {
  const formFeedback = contactForm.querySelector("[data-form-feedback]");

  const ambilInput = (nama) => contactForm.elements.namedItem(nama);

  contactForm.addEventListener("submit", (event) => {
    // Mencegah pengiriman otomatis sebelum seluruh validasi selesai.
    event.preventDefault();

    const namaInput = ambilInput("nama");
    const emailInput = ambilInput("email");
    const semesterInput = ambilInput("semester");
    const tanggalInput = ambilInput("tanggal");
    const prodiInput = ambilInput("prodi");
    const pesanInput = ambilInput("pesan");
    const jenisPesan = contactForm.querySelector(
      'input[name="jenis_pesan"]:checked',
    );
    const topikDipilih = contactForm.querySelectorAll(
      'input[name="minat[]"]:checked',
    );

    const nama = namaInput.value.trim();
    const email = emailInput.value.trim();
    const semester = Number(semesterInput.value);
    const tanggal = tanggalInput.value;
    const prodi = prodiInput.value;
    const pesan = pesanInput.value.trim();

    const tampilkanKesalahan = (pesanKesalahan) => {
      if (formFeedback) {
        formFeedback.textContent = pesanKesalahan;
        formFeedback.className = "form-feedback-error";
      }
    };

    if (formFeedback) {
      formFeedback.textContent = "";
      formFeedback.className = "form-feedback";
    }

    if (nama.length < 3 || nama.length > 50) {
      tampilkanKesalahan("Nama lengkap harus diisi 3 sampai 50 karakter.");
      return;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      tampilkanKesalahan("Masukkan alamat email yang valid.");
      return;
    }

    if (
      semesterInput.value.trim() === "" ||
      !Number.isInteger(semester) ||
      semester < 1 ||
      semester > 14
    ) {
      tampilkanKesalahan("Semester harus diisi dengan angka 1 sampai 14.");
      return;
    }

    if (tanggal === "") {
      tampilkanKesalahan("Tanggal kunjungan harus diisi.");
      return;
    }

    if (!jenisPesan) {
      tampilkanKesalahan("Pilih jenis pesan.");
      return;
    }

    if (topikDipilih.length === 0) {
      tampilkanKesalahan("Pilih minimal satu topik yang diminati.");
      return;
    }

    if (prodi === "") {
      tampilkanKesalahan("Pilih program studi.");
      return;
    }

    if (pesan.length === 0 || pesan.length > 300) {
      tampilkanKesalahan("Pesan harus diisi dan maksimal 300 karakter.");
      return;
    }

    // Mengirim formulir setelah seluruh validasi JavaScript berhasil.
    // HTMLFormElement.submit() tidak memicu event submit kembali.
    contactForm.submit();
  });

  contactForm.addEventListener("reset", () => {
    if (formFeedback) {
      formFeedback.textContent = "";
      formFeedback.className = "form-feedback";
    }
  });

  contactForm.addEventListener("input", () => {
    if (formFeedback) {
      formFeedback.textContent = "";
      formFeedback.className = "form-feedback";
    }
  });
});

<?php
session_start();

function normalizeFormValue(mixed $value): string
{
	if (!is_string($value)) {
		return "";
	}

	$value = str_replace(["\r\n", "\r"], "\n", $value);
	$value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', "", $value);

	return trim($value ?? "");
}

function formValueLength(string $value): int
{
	$length = preg_match_all('/./us', $value, $matches);

	return $length === false ? PHP_INT_MAX : $length;
}

$facilityLabels = [
	"ruang-kelas" => "Ruang Kelas",
	"laboratorium" => "Laboratorium",
	"aula" => "Aula",
	"Proyektor" => "Proyektor",
];

// P7: Menyimpan hasil formulir sementara agar tidak terkirim ulang saat refresh.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$formType = normalizeFormValue($_POST["form_type"] ?? null);

	if ($formType === "contact") {
		$minat = $_POST["minat"] ?? [];
		if (!is_array($minat)) {
			$minat = [];
		}

		$_SESSION["form_submission"] = [
			"type" => "contact",
			"nama" => trim((string) ($_POST["nama"] ?? "")),
			"email" => trim((string) ($_POST["email"] ?? "")),
			"semester" => trim((string) ($_POST["semester"] ?? "")),
			"tanggal" => trim((string) ($_POST["tanggal"] ?? "")),
			"jenis_pesan" => trim((string) ($_POST["jenis_pesan"] ?? "")),
			"minat" => array_values(array_filter($minat, "is_string")),
			"prodi" => trim((string) ($_POST["prodi"] ?? "")),
			"pesan" => trim((string) ($_POST["pesan"] ?? "")),
		];
		header("Location: index.php#hasilFormulir");
		exit;
	} elseif ($formType === "peminjaman") {
		$loanData = [
			"nama" => normalizeFormValue($_POST["loan_nama"] ?? null),
			"email" => normalizeFormValue($_POST["loan_email"] ?? null),
			"fasilitas" => normalizeFormValue($_POST["loan_fasilitas"] ?? null),
			"tanggal" => normalizeFormValue($_POST["loan_tanggal"] ?? null),
			"waktu" => normalizeFormValue($_POST["loan_waktu"] ?? null),
			"keperluan" => normalizeFormValue($_POST["loan_keperluan"] ?? null),
		];

		$loanErrors = [];
		if (formValueLength($loanData["nama"]) < 3 || formValueLength($loanData["nama"]) > 50) {
			$loanErrors["nama"] = "Nama penanggung jawab harus terdiri dari 3 sampai 50 karakter.";
		}
		if (filter_var($loanData["email"], FILTER_VALIDATE_EMAIL) === false) {
			$loanErrors["email"] = "Masukkan alamat email yang valid.";
		}
		if (!array_key_exists($loanData["fasilitas"], $facilityLabels)) {
			$loanErrors["fasilitas"] = "Pilih fasilitas yang tersedia.";
		}

		$date = DateTime::createFromFormat("!Y-m-d", $loanData["tanggal"]);
		$dateErrors = DateTime::getLastErrors();
		if (
			$date === false
			|| ($dateErrors !== false && ($dateErrors["warning_count"] > 0 || $dateErrors["error_count"] > 0))
			|| $date->format("Y-m-d") !== $loanData["tanggal"]
		) {
			$loanErrors["tanggal"] = "Masukkan tanggal peminjaman yang valid.";
		}
		if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/D', $loanData["waktu"])) {
			$loanErrors["waktu"] = "Masukkan waktu peminjaman yang valid.";
		}
		if (formValueLength($loanData["keperluan"]) < 1 || formValueLength($loanData["keperluan"]) > 300) {
			$loanErrors["keperluan"] = "Keperluan wajib diisi dan maksimal 300 karakter.";
		}

		if ($loanErrors !== []) {
			$_SESSION["loan_form_errors"] = $loanErrors;
			$_SESSION["loan_form_old"] = $loanData;
			header("Location: index.php#Permohonan");
			exit;
		}

		unset($_SESSION["loan_form_errors"], $_SESSION["loan_form_old"]);
		$_SESSION["loan_submission"] = $loanData;
		header("Location: index.php#hasilPeminjaman");
		exit;
	} else {
		http_response_code(400);
		exit("Jenis formulir tidak dikenal.");
	}
}

// Data hasil tetap tersimpan di session sampai ada pengiriman formulir baru.
$submittedForm = $_SESSION["form_submission"] ?? null;
$submittedLoan = $_SESSION["loan_submission"] ?? null;
$loanErrors = $_SESSION["loan_form_errors"] ?? [];
$loanOld = $_SESSION["loan_form_old"] ?? [];
unset($_SESSION["loan_form_errors"], $_SESSION["loan_form_old"]);

// Mengamankan data sebelum ditampilkan kembali ke halaman HTML.
// P6: PHP dasar dan pemrograman sisi peladen
// Bagian 1: Variabel dan konstanta
$nim = "2611500036";
$namaMahasiswa = "Marvel Efendi";
$programStudi = "TI";
$tahunAkademik = "2026/2027";
$semester = 4;
$ipk = 3.75;
$statusAktif = true;
$catatanTambahan = null;

const NAMA_MATA_KULIAH = "Pemrograman Web Dasar";
const PROFIL_GITHUB = "Marvel245-wq";

// Bagian 2: Operator
$batasSemester = 8;
$sisaSemester = $batasSemester - $semester;
$semesterValid = $semester >= 1 && $semester <= $batasSemester;
$statusSemester = ($semesterValid && $statusAktif) ? "Sesuai rentang" : "Perlu diperiksa";

// Bagian 3: Percabangan if-elseif-else
if ($semester < 3) {
	$keteranganSemester = "Semester awal";
} elseif ($semester <= 6) {
	$keteranganSemester = "Semester menengah";
} else {
	$keteranganSemester = "Semester akhir";
}

$namaProdi = ($programStudi == "SI") ? "Sistem Informasi" : (($programStudi == "TI") ? "Teknik Informatika" : "Lainnya");

// Bagian 4: Percabangan switch-case-default
switch ($programStudi) {
	case "SI":
		$fokusProgramStudi = "Sistem informasi dan proses bisnis";
		break;
	case "TI":
		$fokusProgramStudi = "Komputasi dan pengembangan perangkat lunak";
		break;
	default:
		$fokusProgramStudi = "Belum ditentukan";
}
?>
<!doctype html>
<html lang="id">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Profil Mahasiswa</title>
	<link rel="stylesheet" href="style.css?v=<?= filemtime(__DIR__ . '/style.css') ?>" />
</head>

<body>
	<header>
		<div class="header-top">
			<h1>Profil Mahasiswa</h1>

			<button
				class="nav-toggle"
				id="navToggle"
				type="button"
				aria-label="Buka navigasi"
				aria-controls="navMenu"
				aria-expanded="false">
				&#9776;
			</button>
		</div>

		<nav>
			<ul id="navMenu">
				<li><a href="#home">Beranda</a></li>
				<li><a href="#about">Tentang</a></li>
				<li><a href="#contact">Kontak</a></li>
				<li><a href="#Permohonan">Peminjaman Fasilitas</a></li>
			</ul>
		</nav>
	</header>

	<main>
		<section id="home">
			<h2>Selamat Datang</h2>
			<p>Halo, saya <strong><?php echo $namaMahasiswa; ?></strong>. Saya sedang mempelajari matakuliah <strong><?php print NAMA_MATA_KULIAH; ?></strong> pada tahun akademik <?php echo $tahunAkademik; ?>.</p>

			<img src="img/foto-profile.png" alt="Foto profil Nama Mahasiswa" />
		</section>

		<section id="about">
			<h2>Tentang Saya</h2>

			<article>
				<h3>Profil Singkat</h3>
				<p>Program Studi: <?php echo $namaProdi . " (" . $programStudi . ")"; ?></p>
				<p>Fokus program studi: <?php echo $fokusProgramStudi; ?></p>
				<p>Status mahasiswa: <?php echo $statusAktif ? "Aktif" : "Tidak aktif"; ?></p>
				<p>Semester: <?php echo $semester . " (" . $keteranganSemester . ")"; ?></p>
				<p>Sisa semester hingga batas: <?php echo $sisaSemester; ?></p>
				<p>Pemeriksaan rentang semester: <?php echo $statusSemester; ?></p>
				<p>IPK: <?php echo number_format($ipk, 2, ',', '.'); ?></p>
				<p>Catatan tambahan: <?php echo $catatanTambahan === null ? "Belum ada" : $catatanTambahan; ?></p>

				<h3>Target Belajar (Perulangan for)</h3>
				<ol>
					<?php
					// Perulangan for tanpa array; materi larik dibahas pada P9.
					for ($i = 1; $i <= 5; $i++) {
						echo "<li>Target belajar ke-" . $i . ": ";
						switch ($i) {
							case 1:
								echo "Memahami sintaks dasar PHP.";
								break;
							case 2:
								echo "Menggunakan variabel dan konstanta.";
								break;
							case 3:
								echo "Menerapkan operator dan percabangan.";
								break;
							case 4:
								echo "Menggunakan perulangan for dan while.";
								break;
							default:
								echo "Belum dipetakan";
								break;
						}
						echo "</li>";
					}
					?>
				</ol>

				<h3>Semester (Perulangan while):</h3>
				<ol>
					<?php
					$nomorSemester = 1;
					while ($nomorSemester <= 3) {
						echo "<li>Semester " . $nomorSemester . "</li>";
						$nomorSemester++; // operator penugasan
					}
					?>
				</ol>
			</article>
		</section>
		<section id="Permohonan">
			<h2>Permohonan Peminjaman Fasilitas Kampus</h2>

			<article>
				<h3>Formulir Peminjaman</h3>
				<form id="peminjamanForm" class="js-peminjaman-form" data-validation="peminjaman" action="index.php" method="post" novalidate>
					<input type="hidden" name="form_type" value="peminjaman" />
					<?php if ($loanErrors !== []): ?>
						<div class="form-feedback-error" role="alert">
							<p>Periksa kembali data formulir:</p>
							<ul>
								<?php foreach ($loanErrors as $error): ?>
									<li><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label for="loan_nama">Nama Penanggung Jawab</label><br />

						<input
							class="input-form"
							type="text"
							id="loan_nama"
							name="loan_nama"
							value="<?= htmlspecialchars($loanOld["nama"] ?? "", ENT_QUOTES, "UTF-8") ?>"
							placeholder="Masukkan nama lengkap"
							minlength="3"
							maxlength="30"
							required />
					</div>

					<div class="form-group">
						<label for="loan_email">Email Penanggung Jawab</label><br />

						<input class="input-form" type="email" id="loan_email" name="loan_email" value="<?= htmlspecialchars($loanOld["email"] ?? "", ENT_QUOTES, "UTF-8") ?>" placeholder="nama@email.com" required />
					</div>

					<div class="form-group">
						<label for="loan_fasilitas">Fasilitas yang Diminta</label><br />

						<select class="input-form" id="loan_fasilitas" name="loan_fasilitas" required>
							<option value="">-- Pilih Fasilitas --</option>
							<option value="ruang-kelas" <?= ($loanOld["fasilitas"] ?? "") === "ruang-kelas" ? "selected" : "" ?>>Ruang Kelas</option>
							<option value="laboratorium" <?= ($loanOld["fasilitas"] ?? "") === "laboratorium" ? "selected" : "" ?>>Laboratorium</option>
							<option value="aula" <?= ($loanOld["fasilitas"] ?? "") === "aula" ? "selected" : "" ?>>Aula</option>
							<option value="Proyektor" <?= ($loanOld["fasilitas"] ?? "") === "Proyektor" ? "selected" : "" ?>>Proyektor</option>
						</select>
					</div>

					<div class="form-group">
						<label for="loan_tanggal">Tanggal Peminjaman</label><br />

						<input class="input-form" type="date" id="loan_tanggal" name="loan_tanggal" value="<?= htmlspecialchars($loanOld["tanggal"] ?? "", ENT_QUOTES, "UTF-8") ?>" required />
					</div>

					<div class="form-group">
						<label for="loan_waktu">Waktu Peminjaman</label><br />

						<input class="input-form" type="time" id="loan_waktu" name="loan_waktu" value="<?= htmlspecialchars($loanOld["waktu"] ?? "", ENT_QUOTES, "UTF-8") ?>" required />
					</div>
					<div class="form-group">
						<label for="loan_keperluan">Keperluan Peminjaman</label><br />

						<textarea
							class="input-form"
							id="loan_keperluan"
							name="loan_keperluan"
							maxlength="300"
							placeholder="Tulis keperluan Anda"
							required><?= htmlspecialchars($loanOld["keperluan"] ?? "", ENT_QUOTES, "UTF-8") ?></textarea>
					</div>
					<button type="submit">Kirim Permohonan</button>
					<button type="reset">Batal</button>
					<p class="form-feedback" data-form-feedback aria-live="polite"></p>
				</form>
			</article>
			<?php if ($submittedLoan !== null): ?>
				<article id="hasilPeminjaman" class="submission-result" aria-live="polite">
					<h3>Data Permohonan yang Berhasil Dikirim</h3>
					<p><strong>Nama Penanggung Jawab:</strong> <?= htmlspecialchars($submittedLoan["nama"], ENT_QUOTES, "UTF-8") ?></p>
					<p><strong>Email:</strong> <?= htmlspecialchars($submittedLoan["email"], ENT_QUOTES, "UTF-8") ?></p>
					<p><strong>Fasilitas:</strong> <?= htmlspecialchars($facilityLabels[$submittedLoan["fasilitas"]] ?? "", ENT_QUOTES, "UTF-8") ?></p>
					<p><strong>Tanggal Peminjaman:</strong> <?= htmlspecialchars($submittedLoan["tanggal"], ENT_QUOTES, "UTF-8") ?></p>
					<p><strong>Waktu Peminjaman:</strong> <?= htmlspecialchars($submittedLoan["waktu"], ENT_QUOTES, "UTF-8") ?></p>
					<p><strong>Keperluan:</strong> <?= nl2br(htmlspecialchars($submittedLoan["keperluan"], ENT_QUOTES, "UTF-8")) ?></p>
				</article>
			<?php endif; ?>
		</section>

		<section id="contact">

			<h2>Kontak</h2>

			<p>
				Kunjungi
				<a target="_blank" href="https://github.com/Marvel245-wq/2611500036-MARVELEFENDI-TI1J-2627G-.git<?= PROFIL_GITHUB ?>">profil GitHub saya</a>.
			</p>

			<form id="contactForm" class="js-contact-form" data-validation="contact" action="index.php" method="post" novalidate>
				<input type="hidden" name="form_type" value="contact" />
				<div class="form-group">
					<label for="nama">Nama Lengkap</label><br />

					<input
						class="input-form"
						type="text"
						id="nama"
						name="nama"
						placeholder="Masukkan nama lengkap"
						minlength="3"
						maxlength="50"
						required />
				</div>

				<div class="form-group">
					<label for="email">Email</label><br />

					<input class="input-form" type="email" id="email" name="email" placeholder="nama@email.com" required />
				</div>

				<div class="form-group">
					<label for="semester">Semester</label><br />

					<input class="input-form" type="number" id="semester" name="semester" min="1" max="14" required />
				</div>

				<div class="form-group">
					<label for="tanggal">Tanggal Kunjungan</label><br />

					<input class="input-form" type="date" id="tanggal" name="tanggal" required />
				</div>

				<div class="form-group">
					<p>Jenis Pesan</p>

					<input type="radio" id="pertanyaan" name="jenis_pesan" value="pertanyaan" required />
					<label for="pertanyaan">Pertanyaan</label>

					<input type="radio" id="saran" name="jenis_pesan" value="saran" />
					<label for="saran">Saran</label>
				</div>

				<div class="form-group">
					<p>Topik yang Diminati</p>

					<input type="checkbox" id="minat-html" name="minat[]" value="HTML" />
					<label for="minat-html">HTML</label>

					<input type="checkbox" id="minat-css" name="minat[]" value="CSS" />
					<label for="minat-css">CSS</label>
				</div>

				<div class="form-group">
					<label for="prodi">Program Studi</label><br />

					<select class="input-form" id="prodi" name="prodi" required>
						<option value="">-- Pilih Program Studi --</option>
						<option value="SI">Sistem Informasi</option>
						<option value="TI">Teknik Informatika</option>
					</select>
				</div>

				<div class="form-group">
					<label for="pesan">Pesan</label><br />

					<textarea
						class="input-form"
						id="pesan"
						name="pesan"
						maxlength="300"
						placeholder="Tulis pesan Anda"
						required></textarea>
				</div>

				<button type="submit">Kirim</button>
				<button type="reset">Batal</button>
				<p class="form-feedback" data-form-feedback aria-live="polite"></p>
			</form>

		</section>

		<?php if ($submittedForm !== null && ($submittedForm["type"] ?? "") === "contact"): ?>
			<section id="hasilFormulir" class="submission-result" aria-live="polite">
				<h2>Data Formulir yang Dikirim</h2>
				<p><strong>Jenis Formulir:</strong> Kontak</p>
				<p><strong>Nama Lengkap:</strong> <?= htmlspecialchars($submittedForm["nama"], ENT_QUOTES, "UTF-8") ?></p>
				<p><strong>Email:</strong> <?= htmlspecialchars($submittedForm["email"], ENT_QUOTES, "UTF-8") ?></p>
				<p><strong>Semester:</strong> <?= htmlspecialchars($submittedForm["semester"], ENT_QUOTES, "UTF-8") ?></p>
				<p><strong>Tanggal Kunjungan:</strong> <?= htmlspecialchars($submittedForm["tanggal"], ENT_QUOTES, "UTF-8") ?></p>
				<p><strong>Jenis Pesan:</strong> <?= htmlspecialchars($submittedForm["jenis_pesan"], ENT_QUOTES, "UTF-8") ?></p>
				<p><strong>Topik yang Diminati:</strong> <?= htmlspecialchars(implode(", ", $submittedForm["minat"]), ENT_QUOTES, "UTF-8") ?></p>
				<p><strong>Program Studi:</strong> <?= htmlspecialchars($submittedForm["prodi"], ENT_QUOTES, "UTF-8") ?></p>
				<p><strong>Pesan:</strong> <?= nl2br(htmlspecialchars($submittedForm["pesan"], ENT_QUOTES, "UTF-8")) ?></p>
			</section>
		<?php endif; ?>
	</main>

	<footer>
		<p>&copy; <?= date("Y") ?> <?php echo $namaMahasiswa; ?> [<?= $nim ?>]</p>
	</footer>
	<script src="script.js"></script>
</body>

</html>
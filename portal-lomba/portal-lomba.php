<?php

include "../koneksi.php";
session_start();
if (!isset($_SESSION['username'])) {
    echo "
    <script>
        alert('Silahkan Login Terlebih Dahulu!');
        window.location.href = 'index.php';
    </script>
    ";
}

// Ambil username dari session untuk ditampilkan
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Unknown';
$input_lomba = mysqli_query($koneksi, "SELECT * FROM tb_input_lomba WHERE status='aktif' ORDER BY id ASC");


// Query Ambil Data dari table about
$sql = "SELECT * FROM tb_main_content";
$ambilMain = mysqli_query($koneksi, $sql);
$tampilMain = mysqli_fetch_assoc($ambilMain);

// Proses submit jawaban peserta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim'])) {
    $kelas = isset($_POST['kelas']) ? mysqli_real_escape_string($koneksi, $_POST['kelas']) : '';
    $jurusan = isset($_POST['jurusan']) ? mysqli_real_escape_string($koneksi, $_POST['jurusan']) : '';

    // Ambil semua field aktif agar struktur insert dinamis
    $input_lomba_all = mysqli_query($koneksi, "SELECT * FROM tb_input_lomba WHERE status='aktif' ORDER BY id ASC");

    // ambil daftar kolom yang sudah ada di tb_jawaban_lomba
    $existing_cols = [];
    $col_q = mysqli_query($koneksi, "DESCRIBE tb_jawaban_lomba");
    if ($col_q) {
        while ($c = mysqli_fetch_assoc($col_q)) {
            $existing_cols[] = $c['Field'];
        }
    }

    // gunakan backticks untuk nama kolom dan quote + escape semua nilai untuk menghindari bareword di VALUES
    $cols = ['`kelas`', '`jurusan`'];
    $vals = ["'" . mysqli_real_escape_string($koneksi, $kelas) . "'", "'" . mysqli_real_escape_string($koneksi, $jurusan) . "'"];

    while ($f = mysqli_fetch_assoc($input_lomba_all)) {
        $col_name = 'input_' . $f['id'];
        $field_post = 'input_' . $f['id'];

        // jika kolom belum ada di tb_jawaban_lomba, coba tambahkan sesuai tipe
        if (!in_array($col_name, $existing_cols)) {
            if ($f['jenis_input'] === 'number') {
                $alter_sql = "ALTER TABLE tb_jawaban_lomba ADD `$col_name` INT NULL";
            } else {
                $alter_sql = "ALTER TABLE tb_jawaban_lomba ADD `$col_name` VARCHAR(255) NULL";
            }
            @mysqli_query($koneksi, $alter_sql);
            // perbarui daftar kolom lokal meskipun ALTER gagal, agar percobaan insert tidak melewatkan logika
            $existing_cols[] = $col_name;
        }

        // hanya sertakan kolom jika sekarang ada di tabel
        if (in_array($col_name, $existing_cols)) {
            $raw = isset($_POST[$field_post]) ? $_POST[$field_post] : null;
            $cols[] = "`$col_name`";
            if ($f['jenis_input'] === 'file' && isset($_FILES[$field_post])) {
                $file = $_FILES[$field_post];
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi');
                    
                    if (in_array($file_ext, $allowed_ext) && $file['size'] <= 10485760) { // 10MB limit
                        $new_filename = uniqid() . '_' . $file['name'];
                        $upload_path = '../assets/img/portlom/' . $new_filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                            $vals[] = "'" . mysqli_real_escape_string($koneksi, $new_filename) . "'";
                        } else {
                            echo "<script>alert('Gagal mengupload file!');</script>";
                            $vals[] = "NULL";
                        }
                    } else {
                        echo "<script>alert('File tidak valid atau terlalu besar!');</script>";
                        $vals[] = "NULL";
                    }
                } else {
                    $vals[] = "NULL";
                }
            } else if ($raw === null || $raw === '') {
                $vals[] = "NULL";
            } else {
                $safe = mysqli_real_escape_string($koneksi, $raw);
                $vals[] = "'" . $safe . "'";
            }
        }
        // jika kolom masih tidak ada, lewati (tidak dimasukkan ke INSERT)
    }

    $col_list = implode(', ', $cols);
    $val_list = implode(', ', $vals);

    $insert_sql = "INSERT INTO tb_jawaban_lomba ($col_list) VALUES ($val_list)";
    $ok = mysqli_query($koneksi, $insert_sql);

    if ($ok) {
        echo "<script>alert('Terima kasih, pendaftaran berhasil dikirim.'); window.location.href='portal-lomba.php';</script>";
        exit;
    } else {
        $err = mysqli_error($koneksi);
        echo "<script>alert('Gagal mengirim: " . addslashes($err) . "');</script>";
    }
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portal Lomba - Website Osis 2025 - 2026</title>

    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <link href="../styles/output.css" rel="stylesheet" />

    <!-- Aos -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Website Osis SMK Bi icon -->
    <link rel="shortcut icon" href="../assets/img/logo-osis.png" type="image/x-icon" />

    <!-- Font - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
</head>

<body class="font-[poppins]">
    <!-- Navbar -->

    <nav class="bg-[var(--bg-primary)] fixed top-0 left-0 right-0 z-50 transition duration-500">
        <div class="flex flex-wrap items-center justify-between mx-auto p-4 md:p-6 lg:px-10 lg:py-8">
            <a href="" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../assets/img/logo-osis.png" class="h-10 md:h-10 lg:h-15" alt="Flowbite Logo" />
                <div class="flex flex-col">
                    <span
                        class="hidden md:text-2xl lg:text-3xl md:block 2xl:block font-bold whitespace-nowrap text-[var(--txt-primary)]">
                        OSIS SMK BINA INFORMATIKA
                    </span>
                    <span
                        class="hidden md:text-md md:block 2xl:block font-normal whitespace-nowrap text-[var(--txt-primary)]">
                        Organisasi Siswa Intra Sekolah Periode 2025/2026
                    </span>
                </div>
            </a>
            <button data-collapse-toggle="navbar-sticky" type="button"
                class="inline-flex items-center p-2 w-12 h-12 justify-center text-sm text-[var(--txt-primary)]/80 rounded-lg lg:hidden hover:bg-[var(--txt-primary)]/10 border border-[var(--bg-secondary)]/80 hover:cursor-pointer"
                aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="hidden w-full lg:block lg:w-auto" id="navbar-sticky">
                <ul
                    class="font-bold flex flex-col p-4 md:p-0 mt-8 border border-[var(--txt-primary)]/30 rounded-lg bg-[var(--bg-navbar-mobile)] md:bg-transparent md:flex-row md:space-x-14 rtl:space-x-reverse md:space-y-0 space-y-2 md:mt-0 md:border-0 items-center me-0 md:me-10">
                    <li>
                        <a href="../index.php"
                            class="block py-2 px-3 text-[var(--txt-primary)] rounded-sm md:p-0 hover:text-[var(--bg-secondary)] text-md md:text-xl transition duration-300"
                            aria-current="page">HOME</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Tutup Navbar -->

    <!-- Hero Section -->

    <section id="heroSectionPortalLomba" class="flex flex-col items-center bg-[var(--bg-secondary3)]">
        <div class="px-4 container mx-auto pt-52">
            <h2 class="text-xl md:text-2xl lg:text-3xl text-center underline text-[var(--txt-primary2)]">
                Halo <?php echo $username; ?>! Selamat Datang di,
            </h2>
            <h1 class="text-2xl md:text-4xl lg:text-6xl text-center my-4 md:my-8 text-[var(--bg-secondary2)] font-bold">
                <?= $tampilMain['judul_portal']; ?>
            </h1>
            <p
                class="text-md md:text-xl lg:text-2xl text-justify text-[var(--txt-primary2)] w-full lg:w-3/4 mx-auto leading-6 md:leading-8 lg:leading-11 font-light">
                <?= $tampilMain['deskripsi']; ?>.
            </p>
            <div class="flex flex-col lg:flex-row items-center justify-center mt-12 gap-4 lg:gap-10">
                <a href="<?= $tampilMain['link_teknis']; ?>"
                    class="w-full text-center py-2 md:px-8 md:py-4 rounded-full bg-[var(--bg-secondary2)] text-[var(--txt-primary)] font-bold text-md md:text-2xl cursor-pointer hover:bg-[var(--bg-secondary2)]/20 hover:text-[var(--txt-primary2)] border border-[var(--bg-secondary2)] transition duration-500 shadow-md hover:shadow-none" target="_blank">
                    TEKNIS LOMBA
                </a>
                <a href="<?= $tampilMain['link_reels']; ?>"
                    class="w-full text-center py-2 md:px-8 md:py-4 rounded-full bg-[var(--bg-secondary2)] text-[var(--txt-primary)] font-bold text-md md:text-2xl cursor-pointer hover:bg-[var(--bg-secondary2)]/20 hover:text-[var(--txt-primary2)] border border-[var(--bg-secondary2)] transition duration-500 shadow-md hover:shadow-none" target="_blank">
                    REELS INFORMASI LOMBA
                </a>
                <a href="<?= $tampilMain['link_contact']; ?>"
                    class="w-full text-center py-2 md:px-8 md:py-4 rounded-full bg-[var(--bg-secondary2)] text-[var(--txt-primary)] font-bold text-md md:text-2xl cursor-pointer hover:bg-[var(--bg-secondary2)]/20 hover:text-[var(--txt-primary2)] border border-[var(--bg-secondary2)] transition duration-500 shadow-md hover:shadow-none" target="_blank">
                    CONTACT PERSON
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 mt-12 md:mt-18 mb-32 gap-4">
                <img src="../assets/img/galeri/<?= $tampilMain['gambar_1']; ?>"
                    class="mx-auto p-2 sm:p-4 md:p-6 lg:p-6 bg-[var(--bg-secondary)] rounded-3xl hover:bg-[var(--bg-secondary)]/40 transition duration-500 border-2 border-[var(--bg-secondary)] cursor-pointer hover:scale-101 shadow-md"
                    alt="Galeri" />
                <img src="../assets/img/galeri/<?= $tampilMain['gambar_2']; ?>"
                    class="mx-auto p-2 sm:p-4 md:p-6 lg:p-6 bg-[var(--bg-secondary)] rounded-3xl hover:bg-[var(--bg-secondary)]/40 transition duration-500 border-2 border-[var(--bg-secondary)] cursor-pointer hover:scale-101 shadow-md"
                    alt="Galeri" />
                <img src="../assets/img/galeri/<?= $tampilMain['gambar_3']; ?>"
                    class="mx-auto p-2 sm:p-4 md:p-6 lg:p-6 bg-[var(--bg-secondary)] rounded-3xl hover:bg-[var(--bg-secondary)]/40 transition duration-500 border-2 border-[var(--bg-secondary)] cursor-pointer hover:scale-101 shadow-md"
                    alt="Galeri" />
                <img src="../assets/img/galeri/<?= $tampilMain['gambar_4']; ?>"
                    class="mx-auto p-2 sm:p-4 md:p-6 lg:p-6 bg-[var(--bg-secondary)] rounded-3xl hover:bg-[var(--bg-secondary)]/40 transition duration-500 border-2 border-[var(--bg-secondary)] cursor-pointer hover:scale-101 shadow-md"
                    alt="Galeri" />
            </div>
        </div>
    </section>

    <!-- Tutup Hero Section -->

    <!-- S&K Section -->

    <section id="syaratLomba" class="bg-[var(--bg-secondary)]">
        <div class="px-4 container mx-auto py-20">

            <h1
                class="w-fit text-xl font-bold text-center text-[var(--txt-primary2)] md:text-2xl lg:text-4xl bg-[var(--bg-secondary3)] py-2 md:py-4 px-12 mx-auto rounded-3xl shadow-lg">
                SYARAT DAN KETENTUAN
            </h1>
            <p
                class="w-full lg:w-3/4 mx-auto text-md md:text-xl mt-8 md:mt-12 bg-[var(--bg-secondary3)] p-8 md:px-8 md:py-8 lg:px-12 lg:py-8 xl:px-18 xl:py-10 rounded-3xl leading-6 md:leading-10 font-light shadow-xl">
                <span class="font-bold text-lg md:text-2xl">📌 Ketentuan Pengisian Formulir:</span><br>
                1. Peserta adalah Siswa dan Siswi SMK Bina Informatika <br>
                2. Isi data peserta lomba dengan lengkap dan benar. <br>
                3. Pastikan nomor HP dan email aktif untuk keperluan konfirmasi. <br>
                4. Pendaftaran dilakukan kepada panitia OSIS sebelum batas waktu yang ditentukan. <br>
                5. Data pendaftaran meliputi: <br>
                - Nama <br>
                - Kelas <br>
                6. Perubahan anggota tim hanya diperbolehkan dengan alasan darurat (izin panitia). <br>
                7. Peserta wajib sudah menyelesaikan remedial sebelum mendaftar lomba. <br>
                <span class="font-bold">
                    Tunjukkan kemampuan terbaikmu dan jadilah bagian dari sejarah BI Classica 2025!
                </span>
            </p>

        </div>
    </section>

    <!-- Tutup S&K Section -->

    <!-- Form Input Data Lomba Section -->

    <section id="inputDataLomba" class="bg-[var(--bg-secondary2)]">
        <div class="px-4 container mx-auto py-20 md:py-32">
            <form method="POST" enctype="multipart/form-data" class="max-w-6xl flex flex-col justify-center mx-auto">
                <label for="kelas" class="block text-xl md:text-2xl lg:text-3xl font-bold text-[var(--txt-primary)]">
                    Kelas
                </label>
                <select id="kelas" name="kelas"
                    class="bg-[var(--bg-secondary)] border border-[var(--bg-primary)] text-[var(--txt-primary2)] text-md md:text-lg rounded-2xl md:rounded-3xl block w-full px-6 py-2 md:py-4 mt-3 hover:cursor-pointer hover:bg-[var(--bg-secondary)]/90 transition duration-500">
                    <option value=""></option>
                    <option value="X">X</option>
                    <option value="XI">XI</option>
                    <option value="XII">XII</option>
                </select>

                <label for="jurusan" class="block mt-8 md:mt-14 text-xl md:text-2xl lg:text-3xl font-bold text-[var(--txt-primary)]">
                    Jurusan
                </label>
                <select id="jurusan" name="jurusan"
                    class="bg-[var(--bg-secondary)] border border-[var(--bg-primary)] text-[var(--txt-primary2)] text-md md:text-lg rounded-2xl md:rounded-3xl block w-full px-6 py-2 md:py-4 mt-3 hover:cursor-pointer hover:bg-[var(--bg-secondary)]/90 transition duration-500">
                    <option value=""></option>
                    <option value="Desain Komunikasi Visual">Desain Komunikasi Visual</option>
                    <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                    <option value="Teknik Komputer Jaringan">Teknik Komputer Jaringan</option>
                    <option value="Animasi">Animasi</option>
                    <option value="Broadcasting TV">Broadcasting TV</option>
                    <option value="Game Development">Game Development</option>
                </select>

                <?php while ($tampil_input_lomba = mysqli_fetch_assoc($input_lomba)) : ?>
                    <?php $fld_id = 'input_' . $tampil_input_lomba['id']; ?>
                    <label for="<?= $fld_id; ?>"
                        class="block mt-8 md:mt-14 text-xl md:text-2xl lg:text-3xl font-bold text-[var(--txt-primary)]">
                        <?= $tampil_input_lomba['label_lomba'] . ' ' . $tampil_input_lomba['emoji']; ?>
                    </label>
                    <?php if ($tampil_input_lomba['jenis_input'] === 'file'): ?>
                    <input
                        type="file"
                        id="<?= $fld_id; ?>"
                        name="<?= $fld_id; ?>"
                        accept="image/*,video/*"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none mt-3"
                        required
                    />
                    <p class="mt-1 text-sm text-[var(--txt-primary)]/70">Upload gambar atau video (max 10MB)</p>
                    <?php else: ?>
                    <input
                        type="<?= $tampil_input_lomba['jenis_input']; ?>"
                        id="<?= $fld_id; ?>"
                        name="<?= $fld_id; ?>"
                        class="bg-[var(--bg-secondary3)] border border-[var(--txt-primary2)]/80 text-[var(--txt-primary2)] text-md md:text-lg rounded-2xl md:rounded-3xl focus:ring-[var(--txt-primary2)] mt-3 focus:border-[var(--txt-primary2)] block w-full px-4 py-2.5 md:py-4"
                        placeholder="..." required 
                    />
                    <?php endif; ?>
                <?php endwhile; ?>

                <button type="submit" name="kirim"
                    class="text-[var(--txt-primary2)] bg-[var(--bg-secondary)] hover:bg-[var(--bg-secondary)]/80 focus:ring-3 focus:outline-none focus:ring-[var(--bg-secondary)] ms-auto font-bold rounded-full text-md md:text-xl w-full md:w-fit px-8 py-2.5 mt-12 cursor-pointer transition duration-500 shadow-md">
                    Kirim
                </button>

            </form>
        </div>
    </section>

    <!-- Tutup Form Input Data Lomba Section -->

    <!-- Footer -->

    <footer class="bg-[var(--bg-primary)] text-[var(--txt-primary)]">
        <div class="container mx-auto px-6 py-32 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-6 items-center">

            <!-- Kiri -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start space-x-2 md:space-x-4 mb-2 md:mb-4">
                    <!-- Logo -->
                    <img src="../assets/img/logo-osis-putih.png" alt="Logo OSIS - Putih" class="w-10 md:w-20">
                    <h2 class="text-3xl md:text-6xl font-bold">OSIS</h2>
                </div>

                <h3 class="text-3xl md:text-5xl font-bold leading-relaxed">
                    SMK BINA <br> INFORMATIKA
                </h3>

                <!-- Kontak -->
                <div class="flex flex-col md:flex-row gap-4 md:gap-12 mt-8">
                    <div class="flex items-center justify-center md:justify-start space-x-1 md:space-x-2">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-[var(--bg-secondary)]" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M3.559 4.544c.355-.35.834-.544 1.33-.544H19.11c.496 0 .975.194 1.33.544.356.35.559.829.559 1.331v9.25c0 .502-.203.981-.559 1.331-.355.35-.834.544-1.33.544H15.5l-2.7 3.6a1 1 0 0 1-1.6 0L8.5 17H4.889c-.496 0-.975-.194-1.33-.544A1.868 1.868 0 0 1 3 15.125v-9.25c0-.502.203-.981.559-1.331ZM7.556 7.5a1 1 0 1 0 0 2h8a1 1 0 0 0 0-2h-8Zm0 3.5a1 1 0 1 0 0 2H12a1 1 0 1 0 0-2H7.556Z"
                                clip-rule="evenodd" />
                        </svg>
                        <a href="mailto:osisbph2526@gmail.com" target="_blank"
                            class="hover:underline text-md md:text-lg">osisbph2526@gmail.com</a>
                    </div>
                    <div class="flex items-center justify-center md:justify-start space-x-1 md:space-x-2">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-[var(--bg-secondary)]" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                clip-rule="evenodd" />
                        </svg>
                        <a href="https://www.instagram.com/osissmkbi" target="_blank"
                            class="hover:underline text-md md:text-lg">@osissmkbi</a>
                    </div>
                </div>

                <p class="text-[var(--txt-primary)]/50 text-sm md:text-md mt-10">@2025 All right reserved.</p>
            </div>

            <!-- Kanan -->
            <div class="flex justify-center md:justify-end">
                <a href="https://linktr.ee/bph2526" target="_blank"
                    class="bg-[var(--bg-secondary)] text-[var(--txt-primary2)] font-bold py-3 px-8 lg:py-6 lg:px-22 rounded-2xl lg:rounded-4xl shadow-md hover:bg-[var(--bg-secondary)]/90 text-md md:text-lg lg:text-3xl hover:underline transition duration-500">
                    CONTACT US
                </a>
            </div>

        </div>
    </footer>

    <!-- Tutup Footer -->

    <!-- Flowbite Script -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
<?php

session_start();
include "../../koneksi.php";

if (!isset($_SESSION['username'])) {
    echo "
        <script>
            alert('Silahkan Login Terlebih Dahulu!');
            window.location.href = '../index.php';
        </script>
        ";
}

// Ambil username dari session untuk ditampilkan
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Unknown';

// Tambah Data Aspirasi
if (isset($_POST['tambah'])) {

    // Ambil dan amankan input
    $tujuan_aspirasi = mysqli_real_escape_string($koneksi, $_POST['tujuan_aspirasi']);
    $komentar_forum = mysqli_real_escape_string($koneksi, $_POST['komentar_forum']);

    $result = mysqli_query($koneksi, "INSERT INTO tb_forum_aspirasi SET
          tujuan_aspirasi = '$tujuan_aspirasi',
          komentar_forum = '$komentar_forum'
      ");

    if ($result) {
        // Simpan pesan sukses ke dalam session
        $_SESSION['alert_message'] = '
        <div id="alert-2" class="flex items-center p-4 text-[var(--text-success)] rounded-2xl bg-[var(--bg-success)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                    Data Aspirasi berhasil di tambahkan!
                </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-[var(--bg-success)]/30 text-[var(--text-success)] rounded-lg cursor-pointer focus:ring-2 p-1.5 transition duration-300 border border-[var(--bg-success)] inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-2" aria-label="Close">
                <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>';
    } else {
        // Simpan pesan error ke dalam session
        $_SESSION['alert_message'] = '
        <div id="alert-2" class="flex items-center p-4 text-[var(--text-danger)] rounded-2xl bg-[var(--bg-danger)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                    Data Aspirasi gagal ditambahkan. (Harap Coba lagi). gagal terus? hubungi operator/admin!
                </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-[var(--bg-danger)]/30 text-[var(--text-danger)] rounded-lg cursor-pointer focus:ring-2 p-1.5 transition duration-300 border border-[var(--bg-danger)] inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-2" aria-label="Close">
                <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>';
    }

    // Redirect ke halaman yang sama
    header("Location: ../interaksi/aspirasi.php");
    exit;
}

function selamatkanWaktu()
{
    date_default_timezone_set('Asia/Jakarta');
    $jam = date("G"); // 0-23

    if ($jam >= 0 && $jam < 12) {
        return "Selamat Pagi";
    } elseif ($jam >= 12 && $jam < 15) {
        return "Selamat Siang";
    } elseif ($jam >= 15 && $jam < 18) {
        return "Selamat Sore";
    } else {
        return "Selamat Malam";
    }
}

// Memanggil fungsi dan menampilkan hasilnya
$sapaan = selamatkanWaktu();

// Ambil username dari session untuk ditampilkan
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Unknown';
?>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Aspirasi - Admin Dashboard</title>

    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <link href="../../styles/output.css" rel="stylesheet" />

    <!-- Aos -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Website Osis SMK Bi icon -->
    <link rel="shortcut icon" href="../../assets/img/logo-osis.png" type="image/x-icon" />

    <!-- Font - Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body class="font-[montserrat]">

    <!-- Main Content Dashboard -->
    <div class="px-4 md:px-4 lg:px-8">
        <div class="grid grid-cols-2 gap-4 mt-6 sm:mt-4">
            <div class="flex items-center justify-start h-10 md:h-20">
                <h1 class="text-lg md:text-2xl lg:text-2xl font-bold text-[var(--txt-primary2)]">
                    Interaksi
                </h1>
            </div>
            <div class="flex items-center justify-end h-10 md:h-20">
                <h1 class="text-end text-md md:text-lg lg:text-xl font-light text-[var(--txt-primary2)]/80">
                    <?php echo $sapaan; ?>,
                    <?php echo $username; ?>!
                </h1>
            </div>
        </div>

        <hr class="w-full border border-[var(--txt-primary2)]/20 mt-6 sm:mt-2 mb-10">

        <div class="flex flex-col gap-6">
            <div class="grid grid-cols-1 md:grid-cols-2 items-center justify-center gap-4">
                <a href="../interaksi/aspirasi.php"
                    class="w-fit focus:outline-none text-[var(--txt-primary)] bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm md:text-md px-5 py-2.5 shadow-lg hover:shadow-none transition duration-300 cursor-pointer">
                    Kembali
                </a>
                <h1 class="text-md md:text-lg lg:text-xl xl:text-2xl font-semibold text-[var(--txt-primary2)] text-center md:text-end mt-4 lg:mt-0">
                    Forum Aspirasi - Tambah data
                </h1>
            </div>

            <div class="flex w-full items-center justify-center mt-4">
                <form class="space-y-4 w-full lg:w-2/5" action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-6">
                        <label for="tujuan_aspirasi"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Kategori
                        </label>
                        <select id="tujuan_aspirasi" name="tujuan_aspirasi"
                            class="bg-transparent border border-txt-primary2/50 text-txt-primary2 text-md md:text-lg rounded-xl focus:ring-bg-primary focus:border-bg-primary block w-full px-4 cursor-pointer hover:bg-[var(--bg-primary)]/10 transition duration-300" required>
                            <option value="" disabled selected>Pilih Kategori...</option>
                            <option value="Pihak Sekolah">Pihak Sekolah</option>
                            <option value="Seluruh OSIS">Seluruh OSIS</option>
                            <option value="OSIS Divisi BPH (Badan Pengurus Harian)">OSIS Divisi BPH (Badan Pengurus Harian)</option>
                            <option value="OSIS Divisi Bela Negara">OSIS Divisi Bela Negara</option>
                            <option value="OSIS Divisi Budi Pekerti">OSIS Divisi Budi Pekerti</option>
                            <option value="OSIS Divisi Kesehatan">OSIS Divisi Kesehatan</option>
                            <option value="OSIS Divisi Ketaqwaan">OSIS Divisi Ketaqwaan</option>
                            <option value="OSIS Divisi Seniora (Seni dan Olahraga)">OSIS Divisi Seniora (Seni dan Olahraga)</option>
                            <option value="OSIS Divisi TIK (Teknologi Informasi dan Komunikasi)">OSIS Divisi TIK (Teknologi Informasi dan Komunikasi)</option>
                        </select>
                    </div>
                    <div>
                        <label for="komentarForum"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Komentar Forum
                        </label>
                        <textarea id="komentarForum" rows="4" name="komentar_forum"
                            class="bg-transparent border border-[var(--bg-primary)]/50 text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--txt-primary2)]/80 focus:border-[var(--txt-primary2)]/80 block w-full p-2.5"></textarea>
                    </div>
                    <button type="submit" name="tambah"
                        class="w-full text-[var(--txt-primary)] bg-[var(--text-success)] hover:bg-[var(--text-success)]/80 focus:ring-4 focus:outline-none focus:ring-[var(--txt-primary2)]/60 font-bold rounded-xl text-lg cursor-pointer px-5 py-2.5 text-center mt-4 transition duration-500">
                        Tambah
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Flowbite Script -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
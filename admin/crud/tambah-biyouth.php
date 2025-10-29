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

// Tambah Data Program

if (isset($_POST['tambah'])) {

    // Ambil dan amankan input
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul_biyouth']);
    $peserta = mysqli_real_escape_string($koneksi, $_POST['nama_peserta']);

    $gambar_biyouth = $_FILES['gambar_biyouth']['name'];
    $tmp = $_FILES['gambar_biyouth']['tmp_name'];
    $ukuran_gambar = $_FILES['gambar_biyouth']['size'];

    // Lokasi folder tujuan
    $folder = "../../assets/img/biyouth_creation/";
    $newName = time() . '-' . $gambar_biyouth;
    $pathBaru = $folder . $newName;

    // Ekstensi yang diperbolehkan
    $ekstensi_valid = ['jpg', 'jpeg', 'png'];
    $ekstensi = strtolower(pathinfo($gambar_biyouth, PATHINFO_EXTENSION));

    if (!in_array($ekstensi, $ekstensi_valid)) {
        echo "<script>alert('File harus berupa JPG, JPEG, atau PNG'); window.location.href='tambah-biyouth.php';</script>";
        exit;
    }

    // Validasi ukuran file (maks 2MB)
    if ($ukuran_gambar > 2 * 1024 * 1024) {
        echo "<script>alert('Ukuran file maks 2MB'); window.location.href='tambah-biyouth.php';</script>";
        exit;
    }

    if (move_uploaded_file($tmp, $pathBaru)) {
        // Simpan data ke database dengan nama file baru
        mysqli_query($koneksi, "INSERT INTO tb_biyouth SET
        gambar_biyouth = '$newName',
        judul_biyouth = '$judul',
        nama_peserta = '$peserta'
    ");

        echo "<script>alert('Data berhasil ditambahkan'); window.location.href='../interaksi/biyouth.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal mengunggah gambar!'); window.location.href='tambah-biyouth.php';</script>";
        exit;
    }
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
    <title>Biyouth - Admin Dashboard</title>

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
                <a href="../interaksi/biyouth.php"
                    class="w-fit focus:outline-none text-[var(--txt-primary)] bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm md:text-md px-5 py-2.5 shadow-lg hover:shadow-none transition duration-300 cursor-pointer">
                    Kembali
                </a>
                <h1 class="text-md md:text-lg lg:text-xl xl:text-2xl font-semibold text-[var(--txt-primary2)] text-center md:text-end mt-4 lg:mt-0">
                    Biyouth - Tambah data
                </h1>
            </div>

            <div class="flex w-full items-center justify-center mt-4">
                <form class="space-y-4 w-full lg:w-2/5" action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-6">
                        <label class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]"
                            for="file_input">
                            Unggah Gambar (.jpg, .png, .jpeg)
                        </label>
                        <input
                            class="block w-full text-md text-[var(--txt-primary2)] border border-[var(--bg-primary)]/50 rounded-xl cursor-pointer bg-transparent"
                            id="file_input" type="file" accept=".jpg, .png, .jpeg" name="gambar_biyouth" required>
                    </div>
                    <div class="mb-6">
                        <label for="judul_biyouth"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Judul Karya/Biyouth
                        </label>
                        <input type="text" name="judul_biyouth" id="judul_biyouth"
                            class="bg-transparent border border-[var(--bg-primary)]/50 text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--txt-primary2)]/80 focus:border-[var(--txt-primary2)]/80 block w-full p-2.5" required />
                    </div>
                    <div class="mb-6">
                        <label for="nama_peserta"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Nama Peserta
                        </label>
                        <input type="text" name="nama_peserta" id="nama_peserta"
                            class="bg-transparent border border-[var(--bg-primary)]/50 text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--txt-primary2)]/80 focus:border-[var(--txt-primary2)]/80 block w-full p-2.5" required />
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
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

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Unknown';

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_lomba'];
    $label = $_POST['label_lomba'];
    $jenis = $_POST['jenis_input'];

    $emoji = mysqli_real_escape_string($koneksi, $_POST['emoji']);

    $query1 = mysqli_query($koneksi, "INSERT INTO tb_input_lomba 
        (nama_lomba, label_lomba, emoji, jenis_input, status) 
        VALUES ('$nama', '$label', '$emoji', '$jenis', 'aktif')");

    if ($query1) {
        $id_baru = mysqli_insert_id($koneksi);

        $nama_kolom = "input_" . $id_baru;
        
        if ($jenis == 'number') {
            $query2 = mysqli_query($koneksi, "ALTER TABLE tb_jawaban_lomba ADD $nama_kolom INT NULL");
        } else if ($jenis == 'file') {
            
            $query2 = mysqli_query($koneksi, "ALTER TABLE tb_jawaban_lomba ADD $nama_kolom VARCHAR(500) NULL");
        } else {
            $query2 = mysqli_query($koneksi, "ALTER TABLE tb_jawaban_lomba ADD $nama_kolom VARCHAR(255) NULL");
        }

        if ($query2) {
            $_SESSION['alert_message'] = "✅ Berhasil tambah input '$nama'!";
        } else {
            $_SESSION['alert_message'] = "⚠️ Input dibuat tapi gagal buat kolom";
        }
    } else {
        $_SESSION['alert_message'] = "❌ Gagal menyimpan data";
    }

    header("Location: ../portal-lomba/atur-form.php");
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

$sapaan = selamatkanWaktu();

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Unknown';
?>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Input Lomba - Admin Dashboard</title>

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
                <a href="../portal-lomba/atur-form.php"
                    class="w-fit focus:outline-none text-[var(--txt-primary)] bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm md:text-md px-5 py-2.5 shadow-lg hover:shadow-none transition duration-300 cursor-pointer">
                    Kembali
                </a>
                <h1 class="text-md md:text-lg lg:text-xl xl:text-2xl font-semibold text-[var(--txt-primary2)] text-center md:text-end mt-4 lg:mt-0">
                    Input Lomba - Tambah data
                </h1>
            </div>

            <div class="flex w-full items-center justify-center mt-4">
                <form class="space-y-4 w-full lg:w-2/5" action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-6">
                        <label for="nama_lomba"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Nama Lomba
                        </label>
                        <input type="text" name="nama_lomba" id="nama_lomba"
                            class="bg-transparent border border-[var(--bg-primary)]/50 text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--txt-primary2)]/80 focus:border-[var(--txt-primary2)]/80 block w-full p-2.5" required />
                    </div>
                    <div class="mb-6">
                        <label for="label_lomba"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Label Lomba
                        </label>
                        <input type="text" name="label_lomba" id="label_lomba"
                            class="bg-transparent border border-[var(--bg-primary)]/50 text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--txt-primary2)]/80 focus:border-[var(--txt-primary2)]/80 block w-full p-2.5" required />
                    </div>
                    <div class="mb-6">
                        <label for="emoji"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Emoji
                        </label>
                        <input type="text" name="emoji" id="emoji"
                            class="bg-transparent border border-[var(--bg-primary)]/50 text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--txt-primary2)]/80 focus:border-[var(--txt-primary2)]/80 block w-full p-2.5" required />
                    </div>
                    <div class="mb-6">
                        <label for="jenis_input"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Jenis Input
                        </label>
                        <select id="jenis_input" name="jenis_input"
                            class="bg-transparent border border-txt-primary2/50 text-txt-primary2 text-md md:text-lg rounded-xl focus:ring-bg-primary focus:border-bg-primary block w-full px-4 cursor-pointer hover:bg-[var(--bg-primary)]/10 transition duration-300" required>
                            <option value="" disabled selected>Pilih Jenis Input</option>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="file">File Upload (Image/Video)</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="status"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Status Input
                        </label>
                        <select id="status" name="status"
                            class="bg-transparent border border-txt-primary2/50 text-txt-primary2 text-md md:text-lg rounded-xl focus:ring-bg-primary focus:border-bg-primary block w-full px-4 cursor-pointer hover:bg-[var(--bg-primary)]/10 transition duration-300" required>
                            <option value="" disabled selected>Pilih Status Input</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non Aktif</option>
                        </select>
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
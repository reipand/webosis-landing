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

// Panggil Data
$ambilInput = mysqli_query($koneksi, "SELECT * FROM tb_input_lomba WHERE id='$_GET[kode]'");
$tampilInput = mysqli_fetch_array($ambilInput);

// Ambil username dari session untuk ditampilkan
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Unknown';

// Proses UPDATE data - INI YANG DIPERBAIKI
if (isset($_POST['ubah'])) {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lomba']);
    $label = mysqli_real_escape_string($koneksi, $_POST['label_lomba']);
    $emoji = mysqli_real_escape_string($koneksi, $_POST['emoji']);
    $jenis = mysqli_real_escape_string($koneksi, $_POST['jenis_input']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    $update_query = mysqli_query($koneksi, "UPDATE tb_input_lomba SET
        nama_lomba = '$nama',
        label_lomba = '$label',
        emoji = '$emoji',
        jenis_input = '$jenis',
        status = '$status'
        WHERE id = '$id'
    ");

    if ($update_query) {
        // Jika jenis input berubah atau kolom belum ada, sesuaikan kolom di tb_jawaban_lomba
        $id_int = (int) $id;
        $nama_kolom = 'input_' . $id_int;
        $col_check = mysqli_query($koneksi, "SHOW COLUMNS FROM tb_jawaban_lomba LIKE '$nama_kolom'");

        if ($col_check && mysqli_num_rows($col_check) > 0) {
            // Kolom ada -> ubah tipe jika diperlukan
            if ($jenis === 'number') {
                mysqli_query($koneksi, "ALTER TABLE tb_jawaban_lomba MODIFY `$nama_kolom` INT NULL");
            } else if ($jenis === 'file') {
                mysqli_query($koneksi, "ALTER TABLE tb_jawaban_lomba MODIFY `$nama_kolom` VARCHAR(500) NULL");
            } else {
                mysqli_query($koneksi, "ALTER TABLE tb_jawaban_lomba MODIFY `$nama_kolom` VARCHAR(255) NULL");
            }
        } else {
            // Kolom belum ada (mis. sebelumnya dibuat tidak sempurna) -> tambahkan kolom
            if ($jenis === 'number') {
                mysqli_query($koneksi, "ALTER TABLE tb_jawaban_lomba ADD `$nama_kolom` INT NULL");
            } else {
                mysqli_query($koneksi, "ALTER TABLE tb_jawaban_lomba ADD `$nama_kolom` VARCHAR(255) NULL");
            }
        }
        $_SESSION['alert_message'] = '
        <div id="alert-2" class="flex items-center p-4 text-green-800 rounded-2xl bg-green-50" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div class="ms-3 me-4 text-sm md:text-md font-medium">
                Data lomba berhasil diubah!  🎉
            </div>
        </div>';
        header("Location: ../portal-lomba/atur-form.php");
        exit();
    } else {
        $error_message = mysqli_error($koneksi);
        $_SESSION['alert_message'] = '
        <div id="alert-2" class="flex items-center p-4 text-red-800 rounded-2xl bg-red-50" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM8.93 6.588a.75.75 0 0 1 1.06 0L10 6.88l.47-.292a.75.75 0 1 1 .79 1.28L10.53 8.12l.73.44a.75.75 0 1 1-.79 1.28L10 9.12l-.47.292a.75.75 0 0 1-.79-1.28l.73-.44-.73-.44a.75.75 0 0 1 0-1.06Z" />
            </svg>
            <div class="ms-3 me-4 text-sm md:text-md font-medium">
                Gagal mengubah data lomba 😢<br>
                Error: ' . htmlspecialchars($error_message) . '
            </div>
        </div>';
        header("Location: ubah-input-lomba.php?kode=" . $id);
        exit();
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
?>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ubah Input Lomba - Admin Dashboard</title>

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
                    Input Lomba - Ubah Data
                </h1>
            </div>

            <div class="flex w-full items-center justify-center mt-4">
                <form class="space-y-4 w-full lg:w-2/5" action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $tampilInput['id']; ?>">
                    <div class="mb-6">
                        <label for="nama_lomba"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Nama Lomba
                        </label>
                        <input type="text" name="nama_lomba" id="nama_lomba" value="<?= $tampilInput['nama_lomba']; ?>"
                            class="bg-transparent border border-[var(--bg-primary)]/50 text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--txt-primary2)]/80 focus:border-[var(--txt-primary2)]/80 block w-full p-2.5" required />
                    </div>
                    <div class="mb-6">
                        <label for="label_lomba"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Label Lomba
                        </label>
                        <input type="text" name="label_lomba" id="label_lomba" value="<?= $tampilInput['nama_lomba']; ?>"
                            class="bg-transparent border border-[var(--bg-primary)]/50 text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--txt-primary2)]/80 focus:border-[var(--txt-primary2)]/80 block w-full p-2.5" required />
                    </div>
                    <div class="mb-6">
                        <label for="emoji"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Emoji
                        </label>
                        <input type="text" name="emoji" id="emoji" value="<?= $tampilInput['emoji']; ?>"
                            class="bg-transparent border border-[var(--bg-primary)]/50 text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--txt-primary2)]/80 focus:border-[var(--txt-primary2)]/80 block w-full p-2.5" required />
                    </div>
                    <div class="mb-6">
                        <label for="jenis_input"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Jenis Input
                        </label>
                        <select id="jenis_input" name="jenis_input"
                            class="bg-transparent border border-txt-primary2/50 text-txt-primary2 text-md md:text-lg rounded-xl focus:ring-bg-primary focus:border-bg-primary block w-full px-4 cursor-pointer hover:bg-[var(--bg-primary)]/10 transition duration-300" required>
                            <?php
                            // Asumsi data program ada di variabel $tampildata
                            $jenis_input_sekarang = isset($tampilInput['jenis_input']) ? $tampilInput['jenis_input'] : '';
                            $input_options = ['text', 'number', 'file'];

                            foreach ($input_options as $option) {
                                $selected = ($jenis_input_sekarang == $option) ? 'selected' : '';
                                echo "<option value=\"$option\" $selected>$option</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="status"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Status Input
                        </label>
                        <select id="status" name="status"
                            class="bg-transparent border border-txt-primary2/50 text-txt-primary2 text-md md:text-lg rounded-xl focus:ring-bg-primary focus:border-bg-primary block w-full px-4 cursor-pointer hover:bg-[var(--bg-primary)]/10 transition duration-300" required>
                            <?php
                            // Asumsi data program ada di variabel $tampildata
                            $status_sekarang = isset($tampilInput['status']) ? $tampilInput['status'] : '';
                            $status_options = ['aktif', 'nonaktif'];

                            foreach ($status_options as $option) {
                                $selected_status = ($status_sekarang == $option) ? 'selected' : '';
                                echo "<option value=\"$option\" $selected_status>$option</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="ubah"
                        class="w-full text-[var(--txt-primary2)] bg-[var(--bg-secondary)] hover:bg-[var(--bg-secondary)]/80 focus:ring-4 focus:outline-none focus:ring-[var(--txt-primary2)]/60 font-bold rounded-xl text-lg cursor-pointer px-5 py-2.5 text-center mt-4 transition duration-500">
                        Ubah
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Flowbite Script -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
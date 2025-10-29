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
$ambilGaleri = mysqli_query($koneksi, "SELECT * FROM tb_galeri WHERE id_galeri='$_GET[kode]'");
$tampilGaleri = mysqli_fetch_array($ambilGaleri);

// Proses Update Data Tentang Kami
if (isset($_POST['ubah'])) {
    $id_galeri = $_POST['id_galeri'];
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);

    $gambar_lama = $_POST['gambar_lama']; // dari input hidden
    $gambar_baru = $_FILES['img_galeri_update']['name'];
    $tmp = $_FILES['img_galeri_update']['tmp_name'];
    $ukuran = $_FILES['img_galeri_update']['size'];
    $error = $_FILES['img_galeri_update']['error'];

    // Lokasi folder tujuan - pastikan path benar
    $folder = '../../assets/img/galeri/';

    // Jika user upload gambar baru
    if (!empty($gambar_baru) && $error === 0) {

        // Ekstensi yang diperbolehkan
        $ekstensi_valid = ['jpg', 'jpeg', 'png'];
        $ekstensi = strtolower(pathinfo($gambar_baru, PATHINFO_EXTENSION));

        // Validasi ekstensi dan ukuran file
        if (!in_array($ekstensi, $ekstensi_valid)) {
            echo "<script>alert('❌ File harus berupa JPG, JPEG, atau PNG!'); window.location.href='ubah-galeri.php?kode=$id_galeri';</script>";
            exit;
        }

        if ($ukuran > 2 * 1024 * 1024) {
            echo "<script>alert('⚠️ Ukuran file terlalu besar! Maksimal 2MB'); window.location.href='ubah-galeri.php?kode=$id_galeri';</script>";
            exit;
        }

        // Generate nama file baru
        $newName = time() . '_' . uniqid() . '.' . $ekstensi;
        $pathBaru = $folder . $newName;

        // Hapus gambar lama (kalau ada dan bukan gambar default)
        if (!empty($gambar_lama) && file_exists($folder . $gambar_lama)) {
            unlink($folder . $gambar_lama);
        }

        // Upload file baru ke folder tujuan
        if (move_uploaded_file($tmp, $pathBaru)) {
            // Update database dengan gambar baru
            $query = "UPDATE tb_galeri SET 
                      kategori_galeri='$kategori',
                      img_galeri='$newName'
                      WHERE id_galeri='$id_galeri'";

            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('✅ Data berhasil diperbarui dengan gambar baru!'); window.location.href='../kelola-konten/galeri.php';</script>";
                exit;
            } else {
                // Jika query gagal, hapus file yang sudah diupload
                unlink($pathBaru);
                echo "<script>alert('❌ Gagal update database: " . mysqli_error($koneksi) . "'); window.location.href='ubah-galeri.php?kode=$id_galeri';</script>";
                exit;
            }
        } else {
            echo "<script>alert('❌ Gagal memindahkan file ke folder tujuan!'); window.location.href='ubah-galeri.php?kode=$id_galeri';</script>";
            exit;
        }
    } else {
        // Jika tidak upload gambar baru atau ada error upload
        if ($error !== 0 && $error !== 4) { // Error 4 = No file uploaded
            echo "<script>alert('❌ Error upload file: $error'); window.location.href='ubah-galeri.php?kode=$id_galeri';</script>";
            exit;
        }

        // Update tanpa mengubah gambar
        $query = "UPDATE tb_galeri SET 
                  kategori_galeri='$kategori'
                  WHERE id_galeri='$id_galeri'";

        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('✅ Data berhasil diperbarui tanpa ubah gambar!'); window.location.href='../kelola-konten/galeri.php';</script>";
            exit;
        } else {
            echo "<script>alert('❌ Gagal update database: " . mysqli_error($koneksi) . "'); window.location.href='ubah-divisi.php?kode=$id_galeri';</script>";
            exit;
        }
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
    <title>Galeri - Admin Dashboard</title>

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
                    Kelola Konten
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
                <a href="../kelola-konten/galeri.php"
                    class="w-fit focus:outline-none text-[var(--txt-primary)] bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm md:text-md px-5 py-2.5 shadow-lg hover:shadow-none transition duration-300 cursor-pointer">
                    Kembali
                </a>
                <h1 class="text-lg lg:text-xl xl:text-2xl font-semibold text-[var(--txt-primary2)] text-center md:text-end mt-4 lg:mt-0">
                    Galeri - Ubah Data
                </h1>
            </div>

            <div class="flex w-full items-center justify-center mt-4">
                <form class="space-y-4 w-full lg:w-2/5 mb-32" action="" method="POST" enctype="multipart/form-data">
                    <!-- Input Tersembunyi untuk ID dan gambar lama -->
                    <input type="hidden" name="id_galeri" value="<?= $tampilGaleri['id_galeri']; ?>">
                    <input type="hidden" name="gambar_lama" value="<?= $tampilGaleri['img_galeri']; ?>">
                    <div class="mb-6">
                        <small class="block text-sm text-[var(--txt-primary2)]">Gambar saat ini:
                            <?= $tampilGaleri['img_galeri']; ?>
                        </small>
                        <?php if (!empty($tampilGaleri['img_galeri']) && file_exists("../../assets/img/galeri/" . $tampilGaleri['img_galeri'])): ?>
                            <img src="../../assets/img/galeri/<?= $tampilGaleri['img_galeri']; ?>" alt="Preview Gambar" width="200"
                                class="mt-2 img-thumbnail d-block">
                        <?php endif; ?>
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]"
                            for="img_galeri_update">
                            Ubah Gambar (Opsional)
                        </label>
                        <input
                            class="block w-full text-md text-[var(--txt-primary2)] border border-[var(--bg-primary)]/50 rounded-xl cursor-pointer bg-transparent"
                            id="img_galeri_update" type="file" accept=".jpg, .png, .jpeg" name="img_galeri_update">
                    </div>
                    <div class="mb-6">
                        <label for="kategori"
                            class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Kategori
                        </label>
                        <select id="kategori" name="kategori"
                            class="bg-transparent border border-txt-primary2/50 text-txt-primary2 text-md md:text-lg rounded-xl focus:ring-bg-primary focus:border-bg-primary block w-full px-4 cursor-pointer hover:bg-[var(--bg-primary)]/10 transition duration-300" required>
                            <?php
                            // Asumsi data program ada di variabel $tampildata
                            $kategori_sekarang = isset($tampilGaleri['kategori_galeri']) ? $tampilGaleri['kategori_galeri'] : '';
                            $kategori_options = ['Landscape', 'Kotak'];

                            foreach ($kategori_options as $option) {
                                // Membuat perbandingan case-insensitive dan menambahkan atribut 'selected' jika cocok
                                $selected = (strtolower($kategori_sekarang) == strtolower($option)) ? 'selected' : '';
                                echo "<option value=\"$option\" $selected>$option</option>";
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
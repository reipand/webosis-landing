<?php

include "../../koneksi.php";
session_start();
if ( !isset($_SESSION['username']) )
{
    echo "
    <script>
        alert('Silahkan Login Terlebih Dahulu!');
        window.location.href = '../index.php';
    </script>
    ";
}

// Ambil username dari session untuk ditampilkan
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Unknown';

// Query Ambil Data dari table about
$sql = "SELECT * FROM tb_about";
$ambilTentang = mysqli_query($koneksi, $sql);
$tampilTentang = mysqli_fetch_assoc($ambilTentang);

// Menyiapkan variabel untuk pesan alert dari session
$alert_message = '';
if (isset($_SESSION['alert_message'])) {
    $alert_message = $_SESSION['alert_message'];
    unset($_SESSION['alert_message']);
}

// Proses Update Data Tentang Kami
if (isset($_POST['update_data'])) {
    $id = $_POST['id_about'];
    $hashtag = mysqli_real_escape_string($koneksi, $_POST['hashtag_about']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi_about']);
    $gambar_lama = $_POST['gambar_lama'];
    $nama_gambar_baru = $gambar_lama;

    // Cek apakah ada gambar baru yang diunggah
    if (isset($_FILES['img_about']) && $_FILES['img_about']['error'] == 0) {
        $gambar = $_FILES['img_about']['name'];
        $tmp = $_FILES['img_about']['tmp_name'];
        $ukuran_gambar = $_FILES['img_about']['size'];
        $ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
        $tipe_valid = ['jpg', 'jpeg', 'png'];
        $upload_dir = "/assets/img";

        if (!in_array($ext, $tipe_valid)) {
            $_SESSION['alert_message'] = '
            <div id="alert-2" class="flex items-center p-4 text-[var(--text-danger)] rounded-2xl bg-[var(--bg-danger)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                    File harus berupa JPG, JPEG, atau PNG
                </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-[var(--bg-danger)]/30 text-[var(--text-danger)] rounded-lg cursor-pointer focus:ring-2 p-1.5 transition duration-300 border border-[var(--bg-danger)] inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-2" aria-label="Close">
                    <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>';
            header("Location: tentang.php");
            exit;
        }

        if ($ukuran_gambar > 2 * 1024 * 1024) { // Maks 2MB
             $_SESSION['alert_message'] = '<div id="alert-2" class="flex items-center p-4 text-[var(--text-danger)] rounded-2xl bg-[var(--bg-danger)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                    Ukuran Gambar Maksimal 2MB!
                </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-[var(--bg-danger)]/30 text-[var(--text-danger)] rounded-lg cursor-pointer focus:ring-2 p-1.5 transition duration-300 border border-[var(--bg-danger)] inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-2" aria-label="Close">
                    <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>';
            header("Location: tentang.php");
            exit;
        }
        
        // Buat nama unik dan pindahkan file
        $nama_gambar_baru = uniqid() . '.' . $ext;
        
        // Hapus gambar lama jika ada
        if (!empty($gambar_lama) && file_exists($upload_dir . $gambar_lama)) {
            unlink($upload_dir . $gambar_lama);
        }

        move_uploaded_file($tmp, $upload_dir . $nama_gambar_baru);
    }

    // Query untuk update data
    $query = "UPDATE tb_about SET 
              hashtag_about = '$hashtag', 
              deskripsi_about = '$deskripsi', 
              img_about = '$nama_gambar_baru' 
              WHERE id_about = '$id'";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $_SESSION['alert_message'] = '<div id="alert-2" class="flex items-center p-4 text-[var(--text-success)] rounded-2xl bg-[var(--bg-success)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                    Data berhasil di Perbarui
                </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-[var(--bg-success)]/30 text-[var(--text-success)] rounded-lg cursor-pointer focus:ring-2 p-1.5 transition duration-300 border border-[var(--bg-success)] inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-2" aria-label="Close">
                    <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>';
    } else {
        $_SESSION['alert_message'] = '<div id="alert-2" class="flex items-center p-4 text-[var(--text-danger)] rounded-2xl bg-[var(--bg-danger)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                    Terjadi Kesalahan, Mohon Coba Lagi!
                </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-[var(--bg-danger)]/30 text-[var(--text-danger)] rounded-lg cursor-pointer focus:ring-2 p-1.5 transition duration-300 border border-[var(--bg-danger)] inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-2" aria-label="Close">
                    <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>';
    }
    header("Location: tentang.php");
    exit;
}

function selamatkanWaktu() {
    date_default_timezone_set('Asia/Jakarta');
    $jam = date("G"); // 0-23

    if ($jam >= 0 && $jam < 12) {
        return "Selamat pagi";
    } elseif ($jam >= 12 && $jam < 15) {
        return "Selamat siang";
    } elseif ($jam >= 15 && $jam < 18) {
        return "Selamat sore";
    } else {
        return "Selamat malam";
    }
}
  
  // Memanggil fungsi dan menampilkan hasilnya
  $sapaan = selamatkanWaktu(); 



?>
<?php
// FILE: export_pdf.php

// 1. Inklusi dan Autoload Dompdf
// SESUAIKAN PATH INI jika Anda tidak menggunakan Composer atau struktur foldernya berbeda.
require_once __DIR__ . '/../../vendor/autoload.php'; 

include "../../koneksi.php";

use Dompdf\Dompdf;
use Dompdf\Options;

if (!$koneksi) {
    die("Koneksi database gagal.");
}

// Ambil parameter filter dari URL (escape string untuk keamanan)
$jenis_input = isset($_GET['jenis_input']) ? mysqli_real_escape_string($koneksi, $_GET['jenis_input']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($koneksi, $_GET['status']) : '';
$search   = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$urutkan_data = isset($_GET['urutkan_data']) ? $_GET['urutkan_data'] : '';

// 2. Siapkan Query SQL
$sql = "SELECT * FROM tb_jawaban_lomba WHERE 1=1";

if ($jenis_input != '') {
    $sql .= " AND jenis_input = '$jenis_input'";
}
if ($status != '') {
    $sql .= " AND status = '$status'";
}
if ($search != '') {
    $sql .= " AND (id LIKE '%$search%' or nama_lomba LIKE '%$search%' or jenis_input LIKE '%$search%' OR status LIKE '%$search%' or emoji LIKE '%$search%')";
}
if ($urutkan_data == 'terbaru') {
    $sql .= " ORDER BY id DESC";
} elseif ($urutkan_data == 'terlama') {
    $sql .= " ORDER BY id ASC";
} else {
    $sql .= " ORDER BY id DESC";
}

$data_query = mysqli_query($koneksi, $sql);

// Ambil Header Kolom
$columns_query = mysqli_query($koneksi, "DESCRIBE tb_jawaban_lomba");
$columns = [];
while ($col = mysqli_fetch_assoc($columns_query)) {
    $columns[] = $col['Field'];
}

// 3. Bangun Struktur HTML
$html = '
<!DOCTYPE html>
<html>
<head>
    <title>Data Peserta - Export PDF</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
            font-size: 10pt;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
            word-wrap: break-word; /* Untuk mencegah data yang terlalu panjang merusak tabel */
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
        }
        .foto-preview { 
            max-width: 50px; 
            max-height: 50px; 
            display: block; 
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA PESERTA LOMBA</h1>
        <p>Tanggal Export: ' . date('d F Y H:i:s') . '</p>
    </div>
    
    <table>
        <thead>
            <tr>';
// Buat Header Tabel
foreach ($columns as $col_name) {
    $html .= '<th>' . strtoupper(str_replace('_', ' ', $col_name)) . '</th>';
}
$html .= '</tr>
        </thead>
        <tbody>';

// Isi Baris Data
while ($row = mysqli_fetch_assoc($data_query)) {
    $html .= '<tr>';
    foreach ($columns as $col_name) {
        $value = htmlspecialchars($row[$col_name]);

        // Logika untuk menampilkan foto/file (Dompdf tidak bisa merender foto dari path lokal dengan mudah)
        if (strpos($col_name, 'foto') !== false || strpos($col_name, 'file') !== false || strpos($col_name, 'upload') !== false) {
            // Untuk mempermudah, tampilkan path/nama file di PDF
            $html .= '<td>' . $value . '</td>';
            
            /* // OPSIONAL: Jika ingin menampilkan gambar (harus diuji):
            if (!empty($value)) {
                $image_path = __DIR__ . '/../../uploads/lomba/' . $value;
                // Dompdf memerlukan path absolut yang dapat diakses atau di-base64 encode
                // Untuk kesederhanaan, kita tampilkan path-nya saja.
                //$html .= '<td><img src="' . $image_path . '" class="foto-preview"></td>'; 
            } else {
                //$html .= '<td>N/A</td>';
            }
            */

        } else {
            $html .= '<td>' . $value . '</td>';
        }
    }
    $html .= '</tr>';
}

$html .= '</tbody>
    </table>
</body>
</html>';

// 4. Inisialisasi dan Render PDF
$options = new Options();
// Jika Anda mengalami error 'font not found', Anda mungkin perlu menambahkan ini:
// $options->set('defaultFont', 'Arial'); 
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Kirim file ke browser
$dompdf->stream("Data_Peserta_Lomba_" . date('Ymd_His') . ".pdf", array("Attachment" => true));

mysqli_close($koneksi);
exit; // Penting: Hentikan eksekusi setelah export
?>
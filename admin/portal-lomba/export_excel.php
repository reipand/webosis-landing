<?php
// FILE: export_excel.php

include "../../koneksi.php";

// 1. Header untuk Export ke Excel (CSV sederhana)
// Pastikan tidak ada output lain SEBELUM header ini dikirim.
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_Peserta_Lomba_" . date('Ymd_His') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Ambil parameter filter dari URL (escape string untuk keamanan)
$jenis_input = isset($_GET['jenis_input']) ? mysqli_real_escape_string($koneksi, $_GET['jenis_input']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($koneksi, $_GET['status']) : '';
$search   = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$urutkan_data = isset($_GET['urutkan_data']) ? $_GET['urutkan_data'] : '';

// Query dasar
$sql = "SELECT * FROM tb_jawaban_lomba WHERE 1=1";

// Terapkan Filter
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
    $sql .= " ORDER BY id DESC"; // Default
}

$data_query = mysqli_query($koneksi, $sql);

// Ambil kolom (Header)
$columns_query = mysqli_query($koneksi, "DESCRIBE tb_jawaban_lomba");
$columns = [];
while ($col = mysqli_fetch_assoc($columns_query)) {
    $columns[] = $col['Field'];
}

echo "<table>";
echo "<tr>";
// Tampilkan Header Kolom
foreach ($columns as $col_name) {
    echo "<th>" . strtoupper(str_replace('_', ' ', $col_name)) . "</th>";
}
echo "</tr>";

// Ambil dan Tampilkan Data
while ($row = mysqli_fetch_assoc($data_query)) {
    echo "<tr>";
    foreach ($columns as $col_name) {
        $value = $row[$col_name];
        
        // Cek dan tampilkan data
        echo "<td>" . $value . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

mysqli_close($koneksi);
exit; // Penting: Hentikan eksekusi setelah export
?>
<?php

include "../../koneksi.php";

session_start();
if (!isset($_SESSION['username'])) {
    echo "
    <script>
        alert('Silahkan Login Terlebih Dahulu!');
        window.location.href = '../index.php';
    </script>
    ";
}

// Get the ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query to get data
$sql = "SELECT * FROM tb_jawaban_lomba WHERE id_jawaban = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    $_SESSION['alert_message'] = "Data tidak ditemukan!";
    header("Location: data-peserta.php");
    exit;
}

// Get input fields configuration
$input_fields = mysqli_query($koneksi, "SELECT * FROM tb_input_lomba WHERE status='aktif' ORDER BY id ASC");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    
    $updates = array();
    $updates[] = "kelas = '$kelas'";
    $updates[] = "jurusan = '$jurusan'";
    
    // Process each input field
    while ($field = mysqli_fetch_assoc($input_fields)) {
        $field_id = 'input_' . $field['id'];
        if (isset($_POST[$field_id])) {
            if ($field['jenis_input'] === 'file' && isset($_FILES[$field_id]) && $_FILES[$field_id]['error'] === UPLOAD_ERR_OK) {
                // Handle file upload
                $file = $_FILES[$field_id];
                $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi');
                
                if (in_array($file_ext, $allowed_ext) && $file['size'] <= 10485760) { // 10MB limit
                    $new_filename = uniqid() . '_' . $file['name'];
                    $upload_path = '../../assets/img/portlom/' . $new_filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                        $updates[] = "`$field_id` = '" . mysqli_real_escape_string($koneksi, $new_filename) . "'";
                    }
                }
            } else {
                // Handle regular input
                $value = mysqli_real_escape_string($koneksi, $_POST[$field_id]);
                $updates[] = "`$field_id` = '$value'";
            }
        }
    }
    
    $update_sql = "UPDATE tb_jawaban_lomba SET " . implode(", ", $updates) . " WHERE id_jawaban = $id";
    
    if (mysqli_query($koneksi, $update_sql)) {
        $_SESSION['alert_message'] = "Data berhasil diupdate!";
        header("Location: data-peserta.php");
        exit;
    } else {
        $error = "Error updating data: " . mysqli_error($koneksi);
    }
}

// Reset result pointer for form display
mysqli_data_seek($input_fields, 0);

function selamatkanWaktu() {
    date_default_timezone_set('Asia/Jakarta');
    $jam = date("G");
    
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ubah Data Peserta - Admin Dashboard</title>

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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body class="font-[montserrat]">
    <div class="px-4 md:px-4 lg:px-8">
        <div class="grid grid-cols-2 gap-4 mt-6 sm:mt-4">
            <div class="flex items-center justify-start h-10 md:h-20">
                <h1 class="text-lg md:text-2xl lg:text-2xl font-bold text-[var(--txt-primary2)]">
                    Portal Lomba
                </h1>
            </div>
            <div class="flex items-center justify-end h-10 md:h-20">
                <h1 class="text-end text-md md:text-lg lg:text-xl font-light text-[var(--txt-primary2)]/80">
                    <?php echo $sapaan; ?>, <?php echo $username; ?>!
                </h1>
            </div>
        </div>

        <hr class="w-full border border-[var(--txt-primary2)]/20 mt-6 sm:mt-2 mb-10">

        <div class="flex flex-col gap-6">
            <div class="grid grid-cols-1 md:grid-cols-2 items-center justify-center gap-4">
                <a href="data-peserta.php"
                    class="w-fit focus:outline-none text-[var(--txt-primary)] bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm md:text-md px-5 py-2.5 shadow-lg hover:shadow-none transition duration-300 cursor-pointer">
                    Kembali
                </a>
                <h1 class="text-md md:text-lg lg:text-xl xl:text-2xl font-semibold text-[var(--txt-primary2)] text-center md:text-end mt-4 lg:mt-0">
                    Ubah Data Peserta
                </h1>
            </div>

            <?php if (isset($error)): ?>
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <div class="flex w-full items-center justify-center mt-4">
                <form class="space-y-4 w-full lg:w-2/5" action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-6">
                        <label for="kelas" class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Kelas
                        </label>
                        <select id="kelas" name="kelas"
                            class="bg-transparent border border-txt-primary2/50 text-txt-primary2 text-md md:text-lg rounded-xl focus:ring-bg-primary focus:border-bg-primary block w-full px-4 cursor-pointer" required>
                            <option value="X" <?php echo ($data['kelas'] == 'X') ? 'selected' : ''; ?>>X</option>
                            <option value="XI" <?php echo ($data['kelas'] == 'XI') ? 'selected' : ''; ?>>XI</option>
                            <option value="XII" <?php echo ($data['kelas'] == 'XII') ? 'selected' : ''; ?>>XII</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="jurusan" class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                            Jurusan
                        </label>
                        <select id="jurusan" name="jurusan"
                            class="bg-transparent border border-txt-primary2/50 text-txt-primary2 text-md md:text-lg rounded-xl focus:ring-bg-primary focus:border-bg-primary block w-full px-4 cursor-pointer" required>
                            <option value="Desain Komunikasi Visual" <?php echo ($data['jurusan'] == 'Desain Komunikasi Visual') ? 'selected' : ''; ?>>Desain Komunikasi Visual</option>
                            <option value="Rekayasa Perangkat Lunak" <?php echo ($data['jurusan'] == 'Rekayasa Perangkat Lunak') ? 'selected' : ''; ?>>Rekayasa Perangkat Lunak</option>
                            <option value="Teknik Komputer Jaringan" <?php echo ($data['jurusan'] == 'Teknik Komputer Jaringan') ? 'selected' : ''; ?>>Teknik Komputer Jaringan</option>
                            <option value="Animasi" <?php echo ($data['jurusan'] == 'Animasi') ? 'selected' : ''; ?>>Animasi</option>
                            <option value="Broadcasting TV" <?php echo ($data['jurusan'] == 'Broadcasting TV') ? 'selected' : ''; ?>>Broadcasting TV</option>
                            <option value="Game Development" <?php echo ($data['jurusan'] == 'Game Development') ? 'selected' : ''; ?>>Game Development</option>
                        </select>
                    </div>

                    <?php while ($field = mysqli_fetch_assoc($input_fields)): ?>
                        <?php $field_id = 'input_' . $field['id']; ?>
                        <div class="mb-6">
                            <label for="<?php echo $field_id; ?>" class="block mb-2 text-lg font-normal text-[var(--txt-primary2)]">
                                <?php echo htmlspecialchars($field['label_lomba']) . ' ' . $field['emoji']; ?>
                            </label>
                            <?php if ($field['jenis_input'] === 'file'): ?>
                                <input type="file" 
                                    id="<?php echo $field_id; ?>" 
                                    name="<?php echo $field_id; ?>"
                                    accept="image/*,video/*"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                />
                                <?php if (!empty($data[$field_id])): ?>
                                    <p class="mt-1 text-sm text-[var(--txt-primary)]/70">File saat ini: <?php echo htmlspecialchars($data[$field_id]); ?></p>
                                <?php endif; ?>
                                <p class="mt-1 text-sm text-[var(--txt-primary)]/70">Upload gambar atau video (max 10MB)</p>
                            <?php else: ?>
                                <input 
                                    type="<?php echo $field['jenis_input']; ?>" 
                                    id="<?php echo $field_id; ?>" 
                                    name="<?php echo $field_id; ?>" 
                                    value="<?php echo htmlspecialchars($data[$field_id] ?? ''); ?>"
                                    class="bg-transparent border border-[var(--bg-primary)]/50 text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--txt-primary2)]/80 focus:border-[var(--txt-primary2)]/80 block w-full p-2.5"
                                    required
                                />
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>

                    <button type="submit" name="update"
                        class="w-full text-[var(--txt-primary2)] bg-[var(--bg-secondary)] hover:bg-[var(--bg-secondary)]/80 focus:ring-4 focus:outline-none focus:ring-[var(--txt-primary2)]/60 font-bold rounded-xl text-lg cursor-pointer px-5 py-2.5 text-center mt-4 transition duration-500">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Flowbite Script -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
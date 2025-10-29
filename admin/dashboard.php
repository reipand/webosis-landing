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

// Query statistik (contoh, sesuaikan dengan tabel Anda)
$total_program = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_promdat"));
$total_divisi = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_divisi"));
$total_news = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_news"));
$total_galeri = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_galeri"));
$total_biyouth = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_biyouth"));
$total_forum = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_forum_aspirasi"));

// Fungsi sapaan
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

// Panggil fungsi
$sapaan = selamatkanWaktu();

?>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin</title>

    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <link href="../styles/output.css" rel="stylesheet" />

    <!-- Aos -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Website Osis SMK Bi icon -->
    <link rel="shortcut icon" href="../assets/img/logo-osis.png" type="image/x-icon" />

    <!-- Font - Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body class="font-[montserrat]">

    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
        type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-[var(--txt-primary2)]/80 rounded-lg sm:hidden hover:bg-[var(--bg-primary)]/20 focus:outline-none focus:ring-2 focus:ring-[var(--bg-primary)]/80">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>

    <!-- Sidebar -->
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-4 py-8 overflow-y-auto bg-[var(--bg-primary)]">
            <a href="#" class="flex items-center ps-2">
                <img src="../assets/img/logo-osis.png" class="h-10 me-3" alt="Flowbite Logo" />
                <span class="self-center text-xl font-semibold whitespace-nowrap text-[var(--txt-primary)]">Admin
                    Panel</span>
            </a>
            <hr class="borer border-[var(--txt-primary)]/30 mx-2 my-8">
            <ul class="space-y-3 font-medium">
                <li>
                    <a href="dashboard.php"
                        class="flex items-center px-4 py-2.5 text-[var(--txt-primary)] rounded-xl bg-[var(--bg-secondary3)]/20 group">
                        <svg class="w-5 h-5 transition duration-75 text-[var(--txt-primary)]" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <!-- Kelola Konten -->
                <li>
                    <button type="button"
                        class="flex items-center w-full px-4 py-2.5 text-base text-[var(--txt-primary)] transition duration-100 rounded-xl cursor-pointer group hover:bg-[var(--bg-secondary3)]/10"
                        aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                        <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Zm4.996 2a1 1 0 0 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 8a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 11a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 14a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Kelola Konten</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <ul id="dropdown-example" class="hidden py-2 space-y-2">
                        <li>
                            <a href="kelola-konten/tentang.php"
                                class="flex items-center w-full px-4 py-2.5 text-[var(--txt-primary)] transition duration-300 rounded-xl pl-8 group hover:bg-[var(--bg-secondary3)]/10">

                                <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M3 4.92857C3 3.90506 3.80497 3 4.88889 3H19.1111C20.195 3 21 3.90506 21 4.92857V13h-3v-2c0-.5523-.4477-1-1-1h-4c-.5523 0-1 .4477-1 1v2H3V4.92857ZM3 15v1.0714C3 17.0949 3.80497 18 4.88889 18h3.47608L7.2318 19.3598c-.35356.4243-.29624 1.0548.12804 1.4084.42428.3536 1.05484.2962 1.40841-.128L10.9684 18h2.0632l2.2002 2.6402c.3535.4242.9841.4816 1.4084.128.4242-.3536.4816-.9841.128-1.4084L15.635 18h3.4761C20.195 18 21 17.0949 21 16.0714V15H3Z" />
                                    <path d="M16 12v1h-2v-1h2Z" />
                                </svg>

                                <span class="ms-3">Tentang Kami</span></a>
                        </li>

                        <li>
                            <a href="kelola-konten/program.php"
                                class="flex items-center w-full px-4 py-2.5 text-[var(--txt-primary)] transition duration-300 rounded-xl pl-8 group hover:bg-[var(--bg-secondary3)]/10">

                                <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12.8638 3.49613C12.6846 3.18891 12.3557 3 12 3s-.6846.18891-.8638.49613l-3.49998 6c-.18042.30929-.1817.69147-.00336 1.00197S8.14193 11 8.5 11h7c.3581 0 .6888-.1914.8671-.5019.1784-.3105.1771-.69268-.0033-1.00197l-3.5-6ZM4 13c-.55228 0-1 .4477-1 1v6c0 .5523.44772 1 1 1h6c.5523 0 1-.4477 1-1v-6c0-.5523-.4477-1-1-1H4Zm12.5-1c-2.4853 0-4.5 2.0147-4.5 4.5s2.0147 4.5 4.5 4.5 4.5-2.0147 4.5-4.5-2.0147-4.5-4.5-4.5Z" />
                                </svg>

                                <span class="ms-3">Program</span></a>
                        </li>

                        <li>
                            <a href="#"
                                class="flex items-center w-full px-4 py-2.5 text-[var(--txt-primary)] transition duration-300 rounded-xl pl-8 group hover:bg-[var(--bg-secondary3)]/10">

                                <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z"
                                        clip-rule="evenodd" />
                                </svg>

                                <span class="ms-3">Divisi</span></a>
                        </li>

                        <li>
                            <a href="kelola-konten/news.php"
                                class="flex items-center w-full px-4 py-2.5 text-[var(--txt-primary)] transition duration-300 rounded-xl pl-8 group hover:bg-[var(--bg-secondary3)]/10">

                                <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h11.5c.07 0 .14-.007.207-.021.095.014.193.021.293.021h2a2 2 0 0 0 2-2V7a1 1 0 0 0-1-1h-1a1 1 0 1 0 0 2v11h-2V5a2 2 0 0 0-2-2H5Zm7 4a1 1 0 0 1 1-1h.5a1 1 0 1 1 0 2H13a1 1 0 0 1-1-1Zm0 3a1 1 0 0 1 1-1h.5a1 1 0 1 1 0 2H13a1 1 0 0 1-1-1Zm-6 4a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H7a1 1 0 0 1-1-1Zm0 3a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H7a1 1 0 0 1-1-1ZM7 6a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H7Zm1 3V8h1v1H8Z"
                                        clip-rule="evenodd" />
                                </svg>


                                <span class="ms-3">News</span></a>
                        </li>

                        <li>
                            <a href="kelola-konten/galeri.php"
                                class="flex items-center w-full px-4 py-2.5 text-[var(--txt-primary)] transition duration-300 rounded-xl pl-8 group hover:bg-[var(--bg-secondary3)]/10">

                                <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M4.857 3A1.857 1.857 0 0 0 3 4.857v4.286C3 10.169 3.831 11 4.857 11h4.286A1.857 1.857 0 0 0 11 9.143V4.857A1.857 1.857 0 0 0 9.143 3H4.857Zm10 0A1.857 1.857 0 0 0 13 4.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 9.143V4.857A1.857 1.857 0 0 0 19.143 3h-4.286Zm-10 10A1.857 1.857 0 0 0 3 14.857v4.286C3 20.169 3.831 21 4.857 21h4.286A1.857 1.857 0 0 0 11 19.143v-4.286A1.857 1.857 0 0 0 9.143 13H4.857Zm10 0A1.857 1.857 0 0 0 13 14.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 19.143v-4.286A1.857 1.857 0 0 0 19.143 13h-4.286Z"
                                        clip-rule="evenodd" />
                                </svg>

                                <span class="ms-3">Galeri</span></a>
                        </li>

                    </ul>
                </li>

                <!-- Portal Lomba -->
                <li>
                    <button type="button"
                        class="flex items-center w-full px-4 py-2.5 text-base text-[var(--txt-primary)] transition duration-100 rounded-xl cursor-pointer group hover:bg-[var(--bg-secondary3)]/10 mt-2"
                        aria-controls="dropdownPortalLomba" data-collapse-toggle="dropdownPortalLomba">
                        <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9 6c0-1.65685 1.3431-3 3-3s3 1.34315 3 3-1.3431 3-3 3-3-1.34315-3-3Zm2 3.62992c-.1263-.04413-.25-.08799-.3721-.13131-1.33928-.47482-2.49256-.88372-4.77995-.8482C4.84875 8.66593 4 9.46413 4 10.5v7.2884c0 1.0878.91948 1.8747 1.92888 1.8616 1.283-.0168 2.04625.1322 2.79671.3587.29285.0883.57733.1863.90372.2987l.00249.0008c.11983.0413.24534.0845.379.1299.2989.1015.6242.2088.9892.3185V9.62992Zm2-.00374V20.7551c.5531-.1678 1.0379-.3374 1.4545-.4832.2956-.1034.5575-.1951.7846-.2653.7257-.2245 1.4655-.3734 2.7479-.3566.5019.0065.9806-.1791 1.3407-.4788.3618-.3011.6723-.781.6723-1.3828V10.5c0-.58114-.2923-1.05022-.6377-1.3503-.3441-.29904-.8047-.49168-1.2944-.49929-2.2667-.0352-3.386.36906-4.6847.83812-.1256.04539-.253.09138-.3832.13765Z" />
                        </svg>

                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Portal Lomba</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <ul id="dropdownPortalLomba" class="hidden py-2 space-y-2">
                        <li>
                            <a href="portal-lomba/main-content.php"
                                class="flex items-center w-full px-4 py-2.5 text-[var(--txt-primary)] transition duration-300 rounded-xl pl-8 group hover:bg-[var(--bg-secondary3)]/10">

                                <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M3 4.92857C3 3.90506 3.80497 3 4.88889 3H19.1111C20.195 3 21 3.90506 21 4.92857V13h-3v-2c0-.5523-.4477-1-1-1h-4c-.5523 0-1 .4477-1 1v2H3V4.92857ZM3 15v1.0714C3 17.0949 3.80497 18 4.88889 18h3.47608L7.2318 19.3598c-.35356.4243-.29624 1.0548.12804 1.4084.42428.3536 1.05484.2962 1.40841-.128L10.9684 18h2.0632l2.2002 2.6402c.3535.4242.9841.4816 1.4084.128.4242-.3536.4816-.9841.128-1.4084L15.635 18h3.4761C20.195 18 21 17.0949 21 16.0714V15H3Z" />
                                    <path d="M16 12v1h-2v-1h2Z" />
                                </svg>

                                <span class="ms-3">Main Content</span></a>
                        </li>

                        <li>
                            <a href="portal-lomba/data-peserta.php"
                                class="flex items-center w-full px-4 py-2.5 text-[var(--txt-primary)] transition duration-300 rounded-xl pl-8 group hover:bg-[var(--bg-secondary3)]/10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-person-lines-fill w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]" viewBox="0 0 16 16">
                                    <path
                                        d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z" />
                                </svg>

                                <span class="ms-3">Data Peserta</span></a>
                        </li>

                        <li>
                            <a href="portal-lomba/atur-form.php"
                                class="flex items-center w-full px-4 py-2.5 text-[var(--txt-primary)] transition duration-300 rounded-xl pl-8 group hover:bg-[var(--bg-secondary3)]/10">

                                <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M18 3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1V9a4 4 0 0 0-4-4h-3a1.99 1.99 0 0 0-1 .267V5a2 2 0 0 1 2-2h7Z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M8 7.054V11H4.2a2 2 0 0 1 .281-.432l2.46-2.87A2 2 0 0 1 8 7.054ZM10 7v4a2 2 0 0 1-2 2H4v6a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ms-3">Atur Form</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Interaksi -->
                <li>
                    <button type="button"
                        class="flex items-center w-full px-4 py-2.5 text-base text-[var(--txt-primary)] transition duration-100 rounded-xl cursor-pointer group hover:bg-[var(--bg-secondary3)]/10 mt-2"
                        aria-controls="dropdownInteraksi" data-collapse-toggle="dropdownInteraksi">

                        <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Interaksi</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <ul id="dropdownInteraksi" class="hidden py-2 space-y-2">
                        <li>
                            <a href="interaksi/biyouth.php"
                                class="flex items-center w-full px-4 py-2.5 text-[var(--txt-primary)] transition duration-300 rounded-xl pl-8 group hover:bg-[var(--bg-secondary3)]/10">

                                <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M12 2a10 10 0 1 0 10 10A10.009 10.009 0 0 0 12 2Zm6.613 4.614a8.523 8.523 0 0 1 1.93 5.32 20.093 20.093 0 0 0-5.949-.274c-.059-.149-.122-.292-.184-.441a23.879 23.879 0 0 0-.566-1.239 11.41 11.41 0 0 0 4.769-3.366ZM10 3.707a8.82 8.82 0 0 1 2-.238 8.5 8.5 0 0 1 5.664 2.152 9.608 9.608 0 0 1-4.476 3.087A45.755 45.755 0 0 0 10 3.707Zm-6.358 6.555a8.57 8.57 0 0 1 4.73-5.981 53.99 53.99 0 0 1 3.168 4.941 32.078 32.078 0 0 1-7.9 1.04h.002Zm2.01 7.46a8.51 8.51 0 0 1-2.2-5.707v-.262a31.641 31.641 0 0 0 8.777-1.219c.243.477.477.964.692 1.449-.114.032-.227.067-.336.1a13.569 13.569 0 0 0-6.942 5.636l.009.003ZM12 20.556a8.508 8.508 0 0 1-5.243-1.8 11.717 11.717 0 0 1 6.7-5.332.509.509 0 0 1 .055-.02 35.65 35.65 0 0 1 1.819 6.476 8.476 8.476 0 0 1-3.331.676Zm4.772-1.462A37.232 37.232 0 0 0 15.113 13a12.513 12.513 0 0 1 5.321.364 8.56 8.56 0 0 1-3.66 5.73h-.002Z"
                                        clip-rule="evenodd" />
                                </svg>

                                <span class="ms-3">Biyouth Creation</span></a>
                        </li>

                        <li>
                            <a href="interaksi/aspirasi.php"
                                class="flex items-center w-full px-4 py-2.5 text-[var(--txt-primary)] transition duration-300 rounded-xl pl-8 group hover:bg-[var(--bg-secondary3)]/10">

                                <svg class="w-5 h-5 text-[var(--txt-primary)]/50 group-hover:text-[var(--txt-primary)]"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M2.038 5.61A2.01 2.01 0 0 0 2 6v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6c0-.12-.01-.238-.03-.352l-.866.65-7.89 6.032a2 2 0 0 1-2.429 0L2.884 6.288l-.846-.677Z" />
                                    <path
                                        d="M20.677 4.117A1.996 1.996 0 0 0 20 4H4c-.225 0-.44.037-.642.105l.758.607L12 10.742 19.9 4.7l.777-.583Z" />
                                </svg>
                                <span class="ms-3">Aspirasi</span></a>
                        </li>
                    </ul>
                </li>

                <hr class="border-b border-[var(--txt-primary)]/20 mx-2 my-6">

                <li>
                    <button data-modal-target="modalLogout" data-modal-toggle="modalLogout"
                        class="flex items-center px-4 py-2.5 text-[var(--txt-primary)] rounded-xl hover:bg-[var(--bg-secondary3)]/10 group w-full cursor-pointer">
                        <svg class="w-5 h-5 text-[var(--txt-primary)]/50 transition duration-75 group-hover:text-[var(--txt-primary)]"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2" />
                        </svg>
                        <span class="ms-3">Logout</span>
                    </button>
                </li>

            </ul>
        </div>
    </aside>
    <!-- Tutup Sidebar -->

    <!-- Modal Logout -->
    <div id="modalLogout" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-[var(--bg-primary)] rounded-lg shadow-sm">
                <button type="button" class="absolute top-3 end-2.5 text-[var(--txt-primary)]/50 bg-transparent hover:bg-[var(--txt-primary)]/30 hover:text-[var(--txt-primary)]/80 rounded-xl text-sm w-8 h-8 ms-auto inline-flex justify-center items-center cursor-pointer" data-modal-hide="modalLogout">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-[var(--txt-primary)]/60">
                        Yakin ingin Logout?
                    </h3>
                    <button data-modal-hide="modalLogout" type="button" class="cursor-pointer py-2.5 px-5 text-sm font-medium text-[var(--txt-primary)] focus:outline-none bg-[var(--bg-secondary3)]/0 rounded-lg border border-[var(--bg-secondary3)]/30 hover:bg-[var(--bg-secondary3)]/10 hover:text-[var(--txt-primary)] focus:z-10 ">Cancel</button>
                    <a type="button" href="logout.php" data-modal-hide="modalLogout" type="button" class="ms-2 text-[var(--txt-primary2)] bg-[var(--bg-secondary3)]/80 hover:bg-[var(--bg-secondary3)] font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center cursor-pointer">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- Main Content Dashboard -->
    <div class="px-4 md:px-4 lg:px-8 sm:ml-64">
        <div class="grid grid-cols-2 gap-4 mt-6 sm:mt-4">
            <div class="flex items-center justify-start h-10 md:h-20">
                <h1 class="text-lg md:text-2xl lg:text-2xl font-bold text-[var(--txt-primary2)]">
                    Dashboard Admin
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

        <div class="grid grid-cols-1 xl:grid-cols-[2fr_1fr] gap-10">

            <!-- Statistik Data -->
            <div>
                <h1 class="text-lg md:text-xl lg:text-2xl font-semibold mb-4">
                    Statistik Data
                </h1>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 lg:gap-6">
                    <!-- Card: Total Program -->
                    <div
                        class="flex flex-col items-center justify-center p-6 bg-[var(--txt-primary)] hover:bg-[var(--bg-primary)]/10 cursor-pointer rounded-xl border border-[var(--bg-primary)]/60 shadow-md hover:shadow-none transition duration-300">
                        <svg class="w-10 h-10 sm:w-15 sm:h-15 text-[#fc355a] mb-3" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12.8638 3.49613C12.6846 3.18891 12.3557 3 12 3s-.6846.18891-.8638.49613l-3.49998 6c-.18042.30929-.1817.69147-.00336 1.00197S8.14193 11 8.5 11h7c.3581 0 .6888-.1914.8671-.5019.1784-.3105.1771-.69268-.0033-1.00197l-3.5-6ZM4 13c-.55228 0-1 .4477-1 1v6c0 .5523.44772 1 1 1h6c.5523 0 1-.4477 1-1v-6c0-.5523-.4477-1-1-1H4Zm12.5-1c-2.4853 0-4.5 2.0147-4.5 4.5s2.0147 4.5 4.5 4.5 4.5-2.0147 4.5-4.5-2.0147-4.5-4.5-4.5Z" />
                        </svg>
                        <h1 class="text-lg md:text-xl font-medium text-[var(--txt-primary2)]/80">Total Program</h1>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[var(--txt-primary2)] mt-2">
                            <?= $total_program; ?>
                        </h1>
                    </div>

                    <!-- Card: Total Divisi -->
                    <div
                        class="flex flex-col items-center justify-center p-6 bg-[var(--txt-primary)] hover:bg-[var(--bg-primary)]/10 cursor-pointer rounded-xl border border-[var(--bg-primary)]/60 shadow-md hover:shadow-none transition duration-300">
                        <svg class="w-10 h-10 sm:w-15 sm:h-15 text-[#ff824d] mb-3" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z"
                                clip-rule="evenodd" />
                        </svg>
                        <h1 class="text-lg md:text-xl font-medium text-[var(--txt-primary2)]/80">Total Divisi</h1>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[var(--txt-primary2)] mt-2">
                            <?= $total_divisi; ?>
                        </h1>
                    </div>

                    <!-- Card: Total News -->
                    <div
                        class="flex flex-col items-center justify-center p-6 bg-[var(--txt-primary)] hover:bg-[var(--bg-primary)]/10 cursor-pointer rounded-xl border border-[var(--bg-primary)]/60 shadow-md hover:shadow-none transition duration-300">
                        <svg class="w-10 h-10 sm:w-15 sm:h-15 text-[#e4c200] mb-3" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h11.5c.07 0 .14-.007.207-.021.095.014.193.021.293.021h2a2 2 0 0 0 2-2V7a1 1 0 0 0-1-1h-1a1 1 0 1 0 0 2v11h-2V5a2 2 0 0 0-2-2H5Zm7 4a1 1 0 0 1 1-1h.5a1 1 0 1 1 0 2H13a1 1 0 0 1-1-1Zm0 3a1 1 0 0 1 1-1h.5a1 1 0 1 1 0 2H13a1 1 0 0 1-1-1Zm-6 4a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H7a1 1 0 0 1-1-1Zm0 3a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H7a1 1 0 0 1-1-1ZM7 6a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H7Zm1 3V8h1v1H8Z"
                                clip-rule="evenodd" />
                        </svg>
                        <h1 class="text-lg md:text-xl font-medium text-[var(--txt-primary2)]/80">Total News</h1>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[var(--txt-primary2)] mt-2">
                            <?= $total_news; ?>
                        </h1>
                    </div>

                    <!-- Card: Total Galeri -->
                    <div
                        class="flex flex-col items-center justify-center p-6 bg-[var(--txt-primary)] hover:bg-[var(--bg-primary)]/10 cursor-pointer rounded-xl border border-[var(--bg-primary)]/60 shadow-md hover:shadow-none transition duration-300">
                        <svg class="w-10 h-10 sm:w-15 sm:h-15 text-[#2beb71] mb-3" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M4.857 3A1.857 1.857 0 0 0 3 4.857v4.286C3 10.169 3.831 11 4.857 11h4.286A1.857 1.857 0 0 0 11 9.143V4.857A1.857 1.857 0 0 0 9.143 3H4.857Zm10 0A1.857 1.857 0 0 0 13 4.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 9.143V4.857A1.857 1.857 0 0 0 19.143 3h-4.286Zm-10 10A1.857 1.857 0 0 0 3 14.857v4.286C3 20.169 3.831 21 4.857 21h4.286A1.857 1.857 0 0 0 11 19.143v-4.286A1.857 1.857 0 0 0 9.143 13H4.857Zm10 0A1.857 1.857 0 0 0 13 14.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 19.143v-4.286A1.857 1.857 0 0 0 19.143 13h-4.286Z"
                                clip-rule="evenodd" />
                        </svg>
                        <h1 class="text-lg md:text-xl font-medium text-[var(--txt-primary2)]/80">Total Galeri</h1>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[var(--txt-primary2)] mt-2">
                            <?= $total_galeri; ?>
                        </h1>
                    </div>

                    <!-- Card: Total Biyouth Creation -->
                    <div
                        class="flex flex-col items-center justify-center p-6 bg-[var(--txt-primary)] hover:bg-[var(--bg-primary)]/10 cursor-pointer rounded-xl border border-[var(--bg-primary)]/60 shadow-md hover:shadow-none transition duration-300">
                        <svg class="w-10 h-10 sm:w-15 sm:h-15 text-[#6075fc] mb-3" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 2a10 10 0 1 0 10 10A10.009 10.009 0 0 0 12 2Zm6.613 4.614a8.523 8.523 0 0 1 1.93 5.32 20.093 20.093 0 0 0-5.949-.274c-.059-.149-.122-.292-.184-.441a23.879 23.879 0 0 0-.566-1.239 11.41 11.41 0 0 0 4.769-3.366ZM10 3.707a8.82 8.82 0 0 1 2-.238 8.5 8.5 0 0 1 5.664 2.152 9.608 9.608 0 0 1-4.476 3.087A45.755 45.755 0 0 0 10 3.707Zm-6.358 6.555a8.57 8.57 0 0 1 4.73-5.981 53.99 53.99 0 0 1 3.168 4.941 32.078 32.078 0 0 1-7.9 1.04h.002Zm2.01 7.46a8.51 8.51 0 0 1-2.2-5.707v-.262a31.641 31.641 0 0 0 8.777-1.219c.243.477.477.964.692 1.449-.114.032-.227.067-.336.1a13.569 13.569 0 0 0-6.942 5.636l.009.003ZM12 20.556a8.508 8.508 0 0 1-5.243-1.8 11.717 11.717 0 0 1 6.7-5.332.509.509 0 0 1 .055-.02 35.65 35.65 0 0 1 1.819 6.476 8.476 8.476 0 0 1-3.331.676Zm4.772-1.462A37.232 37.232 0 0 0 15.113 13a12.513 12.513 0 0 1 5.321.364 8.56 8.56 0 0 1-3.66 5.73h-.002Z"
                                clip-rule="evenodd" />
                        </svg>

                        <h1 class="text-lg md:text-xl font-medium text-[var(--txt-primary2)]/80">Total Biyouth Creation
                        </h1>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[var(--txt-primary2)] mt-2">
                            <?= $total_biyouth; ?>
                        </h1>
                    </div>

                    <!-- Card: Total Forum Aspirasi -->
                    <div
                        class="flex flex-col items-center justify-center p-6 bg-[var(--txt-primary)] hover:bg-[var(--bg-primary)]/10 cursor-pointer rounded-xl border border-[var(--bg-primary)]/60 shadow-md hover:shadow-none transition duration-300">
                        <svg class="w-10 h-10 sm:w-15 sm:h-15 text-[#ca47fd] mb-3" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M18 3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1V9a4 4 0 0 0-4-4h-3a1.99 1.99 0 0 0-1 .267V5a2 2 0 0 1 2-2h7Z"
                                clip-rule="evenodd" />
                            <path fill-rule="evenodd"
                                d="M8 7.054V11H4.2a2 2 0 0 1 .281-.432l2.46-2.87A2 2 0 0 1 8 7.054ZM10 7v4a2 2 0 0 1-2 2H4v6a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3Z"
                                clip-rule="evenodd" />
                        </svg>
                        <h1 class="text-lg md:text-xl font-medium text-[var(--txt-primary2)]/80">Total Forum Aspirasi
                        </h1>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[var(--txt-primary2)] mt-2">
                            <?= $total_forum; ?>
                        </h1>
                    </div>

                </div>
            </div>

            <!-- Akses Cepat -->
            <div class="mb-32 xl:mb-0">
                <h1 class="text-lg md:text-xl lg:text-2xl font-semibold mb-4">Akses Cepat</h1>
                <div class="flex flex-col gap-2">
                    <a href=""
                        class="border border-red-500 text-[var(--txt-primary2)] text-center rounded-2xl py-3 hover:bg-red-100 transition">
                        Tinjau Program Mendatang
                    </a>
                    <a href=""
                        class="border border-yellow-500 text-[var(--txt-primary2)] text-center rounded-2xl py-3 hover:bg-yellow-100 transition">
                        Kelola Berita
                    </a>
                    <a href=""
                        class="border border-green-500 text-[var(--txt-primary2)] text-center rounded-2xl py-3 hover:bg-green-100 transition">
                        Ubah Galeri
                    </a>
                    <a href=""
                        class="border border-blue-500 text-[var(--txt-primary2)] text-center rounded-2xl py-3 hover:bg-blue-100 transition">
                        Tinjau Aspirasi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tutup Main Content Dashboard -->

    <script>
        // Sapaan dari PHP
        const sapaan = "<?= $sapaan; ?>";
        const username = "<?= $username; ?>";

        // Setelah 5 detik, ganti teks greeting
        setTimeout(() => {
            document.getElementById("adminGreeting").innerText = sapaan + " " + username + "!";
        }, 5000);
    </script>

    <!-- Script Internal - Dropdown -->

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const dropdownButtons = document.querySelectorAll("[data-collapse-toggle]");

            dropdownButtons.forEach((button) => {
                const targetId = button.getAttribute("data-collapse-toggle");
                const target = document.getElementById(targetId);
                const arrow = button.querySelector("svg:last-of-type"); // ambil panah kecil di kanan

                // 1️⃣ Saat halaman dimuat, cek status dari localStorage
                const isOpen = localStorage.getItem(targetId) === "true";
                if (isOpen) {
                    target.classList.remove("hidden");
                    if (arrow) arrow.classList.add("rotate-180");
                }

                // 2️⃣ Klik tombol -> toggle dropdown + simpan status
                button.addEventListener("click", () => {
                    target.classList.toggle("hidden");
                    if (arrow) arrow.classList.toggle("rotate-180");

                    const openNow = !target.classList.contains("hidden");
                    localStorage.setItem(targetId, openNow);
                });
            });
        });
    </script>

    <!-- Flowbite Script -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
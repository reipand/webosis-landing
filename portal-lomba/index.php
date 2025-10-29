<?php

session_start();
include '../koneksi.php';

$alert = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == "" || $password == "") {
        $alert = '
        <div id="alert-2" class="flex items-center p-4 text-[var(--text-warning)] rounded-2xl bg-[var(--bg-warning)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                    Username atau Password tidak boleh Kosong!
                </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-[var(--bg-secondary)]/30 text-[var(--bg-secondary)] rounded-lg cursor-pointer focus:ring-2 p-1.5 hover:bg-[var(--bg-secondary)]/0 transition duration-300 border border-[var(--bg-secondary)] inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-2" aria-label="Close">
                    <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>';
    } else {
        // Mengamankan input dan membuat query case-sensitive
        $username_secure = mysqli_real_escape_string($koneksi, $username);
        $sql = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE BINARY username = '$username_secure'");
        $data = mysqli_fetch_assoc($sql);

        if ($data) {
            if (password_verify($password, $data['password'])) {
                // Menggunakan username dari DB untuk konsistensi session
                $_SESSION['username'] = $data['username'];
                echo "<script>
                  alert('Login Berhasil! Selamat Datang!');
                  window.location.href = 'portal-lomba.php';
                </script>";
            } else {
                $alert = '<div id="alert-3" class="flex items-center p-4 text-[var(--text-danger)] rounded-2xl bg-[var(--bg-danger)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                   Password Salah!
                </div>
        </div>';
            }
        } else {
            $alert = '<div id="alert-3" class="flex items-center p-4 text-[var(--text-info)] rounded-2xl border border-[var(--border-info)] bg-[var(--bg-info)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                   Username tidak Di temukan
                </div>
        </div>';
        }
    }
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Portal Lomba</title>

    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <link href="../styles/output.css" rel="stylesheet" />

    <!-- Aos -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Website Osis SMK Bi icon -->
    <link rel="shortcut icon" href="../assets/img/logo-osis.png" type="image/x-icon" />

    <!-- Font - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
</head>

<body class="font-[poppins]">
    <!-- Navbar -->

    <nav class="bg-[var(--bg-primary)] fixed top-0 left-0 right-0 z-50 transition duration-500">
        <div class="flex flex-wrap items-center justify-between mx-auto p-4 md:p-6 lg:px-10 lg:py-8">
            <a href="" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../assets/img/logo-osis.png" class="h-10 md:h-10 lg:h-15" alt="Flowbite Logo" />
                <div class="flex flex-col">
                    <span
                        class="hidden md:text-2xl lg:text-3xl md:block 2xl:block font-bold whitespace-nowrap text-[var(--txt-primary)]">
                        OSIS SMK BINA INFORMATIKA
                    </span>
                    <span
                        class="hidden md:text-md md:block 2xl:block font-normal whitespace-nowrap text-[var(--txt-primary)]">
                        Organisasi Siswa Intra Sekolah Periode 2025/2026
                    </span>
                </div>
            </a>
            <button data-collapse-toggle="navbar-sticky" type="button"
                class="inline-flex items-center p-2 w-12 h-12 justify-center text-sm text-[var(--txt-primary)]/80 rounded-lg lg:hidden hover:bg-[var(--txt-primary)]/10 border border-[var(--bg-secondary)]/80 hover:cursor-pointer"
                aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="hidden w-full lg:block lg:w-auto" id="navbar-sticky">
                <ul
                    class="font-bold flex flex-col p-4 md:p-0 mt-8 border border-[var(--txt-primary)]/30 rounded-lg bg-[var(--bg-navbar-mobile)] md:bg-transparent md:flex-row md:space-x-14 rtl:space-x-reverse md:space-y-0 space-y-2 md:mt-0 md:border-0 items-center me-0 md:me-10">
                    <li>
                        <a href="../index.php"
                            class="block py-2 px-3 text-[var(--txt-primary)] rounded-sm md:p-0 hover:text-[var(--bg-secondary)] text-md md:text-xl transition duration-300"
                            aria-current="page">HOME</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Tutup Navbar -->

    <!-- Hero Section -->

    <section id="heroLoginSection"
        class="flex items-center justify-center h-screen bg-[var(--bg-secondary2)] bg-cover bg-center">
        <div class="px-4 container mx-auto py-24 lg:py-56">
            <form class="max-w-md mx-auto bg-white/90 p-6 md:p-8 rounded-2xl" action="" method="POST">
                <div class="mx-auto text-center mb-6">
                    <div class="flex mx-auto mb-4 items-center justify-center gap-2 md:gap-4">
                        <img src="../assets/img/portlom/logo-bi-portlom.png" class="w-12 md:w-15" alt="">
                        <img src="../assets/img/portlom/logo-osis-portlom.png" class="w-14 md:w-18" alt="">
                    </div>
                    <h1 class="text-[var(--txt-primary2)] text-sm sm:text-lg md:text-xl">
                        Silahkan Login Untuk <br> Mengakses Portal Lomba
                    </h1>
                </div>
                <div class="mb-6">
                    <label for="username" class="block mb-2 text-sm sm:text-md md:text-lg font-medium text-[var(--txt-primary2)]">
                        Username:
                    </label>
                    <input type="text" id="username"
                        class="bg-transparent border border-[var(--bg-primary)] text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--bg-primary)] focus:border-[var(--bg-primary)]/50 block w-full px-3 py-2.5" name="username" required />
                </div>
                <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm sm:text-md md:text-lg font-medium text-[var(--txt-primary2)]">
                        Password:
                    </label>
                    <input type="password" id="password" name="password"
                        class="bg-transparent border border-[var(--bg-primary)] text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--bg-primary)] focus:border-[var(--bg-primary)]/50 block w-full px-3 py-2.5"
                        required />
                </div>
                <button type="submit" name="login"
                    class="mt-4 text-[var(--txt-primary2)] bg-[var(--bg-secondary)] hover:bg-[var(--bg-secondary)]/80 focus:ring-3 focus:outline-none focus:ring-[var(--bg-primary)] font-bold rounded-xl text-lg w-full px-5 py-2 text-center cursor-pointer transition duration-500">
                    LOGIN
                </button>
            </form>

        </div>
    </section>

    <!-- Tutup Hero Section -->

    <!-- Footer -->

    <footer class="bg-[var(--bg-primary)] text-[var(--txt-primary)]">
        <div class="container mx-auto px-6 py-32 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-6 items-center">

            <!-- Kiri -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start space-x-2 md:space-x-4 mb-2 md:mb-4">
                    <!-- Logo -->
                    <img src="../assets/img/logo-osis-putih.png" alt="Logo OSIS - Putih" class="w-10 md:w-20">
                    <h2 class="text-3xl md:text-6xl font-bold">OSIS</h2>
                </div>

                <h3 class="text-3xl md:text-5xl font-bold leading-relaxed">
                    SMK BINA <br> INFORMATIKA
                </h3>

                <!-- Kontak -->
                <div class="flex flex-col md:flex-row gap-4 md:gap-12 mt-8">
                    <div class="flex items-center justify-center md:justify-start space-x-1 md:space-x-2">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-[var(--bg-secondary)]" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M3.559 4.544c.355-.35.834-.544 1.33-.544H19.11c.496 0 .975.194 1.33.544.356.35.559.829.559 1.331v9.25c0 .502-.203.981-.559 1.331-.355.35-.834.544-1.33.544H15.5l-2.7 3.6a1 1 0 0 1-1.6 0L8.5 17H4.889c-.496 0-.975-.194-1.33-.544A1.868 1.868 0 0 1 3 15.125v-9.25c0-.502.203-.981.559-1.331ZM7.556 7.5a1 1 0 1 0 0 2h8a1 1 0 0 0 0-2h-8Zm0 3.5a1 1 0 1 0 0 2H12a1 1 0 1 0 0-2H7.556Z"
                                clip-rule="evenodd" />
                        </svg>
                        <a href="mailto:osisbph2526@gmail.com" target="_blank"
                            class="hover:underline text-md md:text-lg">osisbph2526@gmail.com</a>
                    </div>
                    <div class="flex items-center justify-center md:justify-start space-x-1 md:space-x-2">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-[var(--bg-secondary)]" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                clip-rule="evenodd" />
                        </svg>
                        <a href="https://www.instagram.com/osissmkbi" target="_blank"
                            class="hover:underline text-md md:text-lg">@osissmkbi</a>
                    </div>
                </div>

                <p class="text-[var(--txt-primary)]/50 text-sm md:text-md mt-10">@2025 All right reserved.</p>
            </div>

            <!-- Kanan -->
            <div class="flex justify-center md:justify-end">
                <a href="https://linktr.ee/bph2526" target="_blank"
                    class="bg-[var(--bg-secondary)] text-[var(--txt-primary2)] font-bold py-3 px-8 lg:py-6 lg:px-22 rounded-2xl lg:rounded-4xl shadow-md hover:bg-[var(--bg-secondary)]/90 text-md md:text-lg lg:text-3xl hover:underline transition duration-500">
                    CONTACT US
                </a>
            </div>

        </div>
    </footer>

    <!-- Tutup Footer -->

    <!-- Flowbite Script -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
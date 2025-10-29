 <?php
    session_start();
    include '../koneksi.php';

    $alert = "";

    if (isset($_POST['daftar'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Validasi input
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
        } else if (strlen($username) > 20) {
            $alert = '
        <div id="alert-2" class="flex items-center p-4 text-[var(--text-warning)] rounded-2xl bg-[var(--bg-warning)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                    Username Maksimal 20 Karakter!
                </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-[var(--bg-secondary)]/30 text-[var(--bg-secondary)] rounded-lg cursor-pointer focus:ring-2 p-1.5 hover:bg-[var(--bg-secondary)]/0 transition duration-300 border border-[var(--bg-secondary)] inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-2" aria-label="Close">
                    <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>';
        } else if (strlen($password) > 8) {
            $alert = '
        <div id="alert-2" class="flex items-center p-4 text-[var(--text-warning)] rounded-2xl bg-[var(--bg-warning)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                    Password Maksimal 8 Karakter!
                </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-[var(--bg-secondary)]/30 text-[var(--bg-secondary)] rounded-lg cursor-pointer focus:ring-2 p-1.5 hover:bg-[var(--bg-secondary)]/0 transition duration-300 border border-[var(--bg-secondary)] inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-2" aria-label="Close">
                    <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>';
        } else {
            // Cek username sudah ada atau belum
            $cek = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE username='$username'");
            if (mysqli_num_rows($cek) > 0) {
                $alert = '<div id="alert-3" class="flex items-center p-4 text-[var(--text-info)] rounded-2xl border border-[var(--border-info)] bg-[var(--bg-info)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                   Username sudah digunakan, silakan pilih username lain.
                </div>
        </div>';
            } else {
                $enkripPassword = password_hash($password, PASSWORD_DEFAULT);
                $insert = mysqli_query($koneksi, "INSERT INTO tb_user (username, password) VALUES ('$username', '$enkripPassword')");
                if ($insert) {
                    echo '<script>
                  alert("Daftar berhasil, silakan login!");
                  window.location.href = "index.php";
                  </script>';
                    exit;
                } else {
                    $alert = '<div id="alert-3" class="flex items-center p-4 text-[var(--text-danger)] rounded-2xl bg-[var(--bg-danger)]" role="alert">
            <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
                <div class="ms-3 me-4 text-sm md:text-md font-medium">
                   Terjadi kesalahan, silakan coba lagi.
                </div>
            </div>';
                }
            }
        }
    }
    ?>
 <html lang="en">

 <head>
     <meta charset="UTF-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <title>Register User - Portal Lomba</title>

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

         </div>
     </nav>

     <!-- Tutup Navbar -->

     <!-- Hero Section -->

     <section id="heroLoginSection"
         class="flex items-center justify-center h-screen bg-[var(--bg-secondary2)] bg-cover bg-center">
         <div class="px-4 container mx-auto py-24 lg:py-56">
             <form class="max-w-md mx-auto bg-white/90 p-6 md:p-8 rounded-2xl" method="POST" action="">
                 <div class="mx-auto text-center">
                     <div class="flex mx-auto mb-4 items-center justify-center gap-2 md:gap-4">
                         <img src="../assets/img/portlom/logo-bi-portlom.png" class="w-12 md:w-15" alt="">
                         <img src="../assets/img/portlom/logo-osis-portlom.png" class="w-14 md:w-18" alt="">
                     </div>
                     <h1 class="text-[var(--txt-primary2)] text-md sm:text-lg md:text-xl mb-4">
                         Silahkan Daftar Untuk <br>
                         Membuat Akun Kelas Portal Lomba
                     </h1>
                 </div>
                 <div class="my-6">
                     <?php if ($alert != "") echo $alert; ?>
                 </div>
                 <div class="mb-6">
                     <label for="username"
                         class="block mb-2 text-sm sm:text-md md:text-lg font-medium text-[var(--txt-primary2)]">
                         Username:
                     </label>
                     <input type="text" id="username"
                         class="bg-transparent border border-[var(--bg-primary)] text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--bg-primary)] focus:border-[var(--bg-primary)]/50 block w-full px-3 py-2.5"
                         name="username" />
                 </div>
                 <div class="mb-5">
                     <label for="password"
                         class="block mb-2 text-sm sm:text-md md:text-lg font-medium text-[var(--txt-primary2)]">
                         Password:
                     </label>
                     <input type="password" id="password" name="password"
                         class="bg-transparent border border-[var(--bg-primary)] text-[var(--txt-primary2)] text-md rounded-xl focus:ring-[var(--bg-primary)] focus:border-[var(--bg-primary)]/50 block w-full px-3 py-2.5" />
                 </div>
                 <button type="submit" name="daftar"
                     class="mt-4 text-[var(--txt-primary)] bg-[var(--text-success)] hover:bg-[var(--text-success)]/80 focus:ring-3 focus:outline-none focus:ring-[var(--bg-primary)] font-bold rounded-xl text-lg w-full px-5 py-2 text-center cursor-pointer transition duration-500">
                     DAFTAR
                 </button>
                 <div class="text-md font-medium text-[var(--txt-primary2)] mt-6 text-center">
                     Sudah punya Akun? <a href="index.php" class="text-[var(--text-info)] hover:underline">Login</a>
                 </div>
             </form>

         </div>
     </section>

     <!-- Tutup Hero Section -->

     <!-- Flowbite Script -->
     <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

 </body>

 </html>
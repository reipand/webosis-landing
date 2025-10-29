<?php

// PHP Connection Include
include "koneksi.php";

// Select Data -> Tentang Kami
$ambilTentang = mysqli_query($koneksi, "SELECT * FROM tb_about");

// Select Data -> Program Mendatang
$ambilProgram = mysqli_query($koneksi, "SELECT * FROM tb_promdat");

// Select Data -> Divisi
$ambilDivisi = mysqli_query($koneksi, "SELECT * FROM tb_divisi");

// Select Data -> News
$ambilNews = mysqli_query($koneksi, "SELECT * FROM tb_news");
// Select Data -> Galeri
// Galeri Landscape
$galeriLandscape = mysqli_query($koneksi, "SELECT * FROM tb_galeri WHERE kategori_galeri = 'landscape'");

// Galeri Kotak
$galeriKotak = mysqli_query($koneksi, "SELECT * FROM tb_galeri WHERE kategori_galeri = 'kotak'");

// Select Data - Biyouth
$ambilBiyouth = mysqli_query($koneksi, "SELECT * FROM tb_biyouth");

// Kirim Data
if (isset($_POST['kirim'])) {

  // Ambil dan amankan input
  $tujuan_aspirasi = mysqli_real_escape_string($koneksi, $_POST['tujuan_aspirasi']);
  $komentar_forum = mysqli_real_escape_string($koneksi, $_POST['komentar_forum']);

  mysqli_query($koneksi, "INSERT INTO tb_forum_aspirasi SET
      tujuan_aspirasi = '$tujuan_aspirasi',
      komentar_forum = '$komentar_forum'
  ");

  echo "<script>
    alert('Terima kasih sudah berkomentar ya! Kami telah menerima komentar kamu! 😁👍');
    window.location.href = 'index.php#forum-aspirasi';
  </script>";
  exit;
}

?>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Website Osis 2025 - 2026</title>

  <!-- Flowbite CSS -->
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

  <!-- Tailwind CSS -->
  <link href="styles/output.css" rel="stylesheet" />

  <!-- Aos -->
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

  <!-- Website Osis SMK Bi icon -->
  <link rel="shortcut icon" href="assets/img/logo-osis.png" type="image/x-icon" />

  <!-- Font - Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
</head>

<body class="font-[poppins]">
  <!-- Navbar -->

  <nav class="bg-transparent fixed top-0 left-0 right-0 z-50 transition duration-500">
    <div class="flex flex-wrap items-center justify-between mx-auto p-4 md:p-6 lg:px-10 lg:py-8">
      <a href="" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="assets/img/logo-osis.png" class="h-10 md:h-15 lg:h-20" alt="Flowbite Logo" />
        <div class="flex flex-col">
          <span class="hidden xl:hidden 2xl:text-4xl 2xl:block font-bold whitespace-nowrap text-[var(--txt-primary)]">
            OSIS SMK BINA INFORMATIKA
          </span>
          <span class="hidden xl:hidden 2xl:text-xl 2xl:block font-normal whitespace-nowrap text-[var(--txt-primary)]">
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
            <a href="#"
              class="block py-2 px-3 text-[var(--txt-primary)] rounded-sm md:p-0 hover:text-[var(--bg-secondary)] text-xl transition duration-300"
              aria-current="page">HOME</a>
          </li>
          <li>
            <a href="#forumAspirasi"
              class="block py-2 px-3 text-[var(--txt-primary)] rounded-sm md:p-0 hover:text-[var(--bg-secondary)] text-xl transition duration-300"
              aria-current="page">FORASI</a>
          </li>
          <li>
            <a href="#biyouth-creation"
              class="block py-2 px-3 text-[var(--txt-primary)] rounded-sm md:p-0 hover:text-[var(--bg-secondary)] text-xl transition duration-300"
              aria-current="page">BIYOUTH CREATION</a>
          </li>
          <li>
            <a href="portal-lomba/"
              class="block py-2 px-3 text-[var(--txt-primary)] rounded-sm md:p-0 hover:text-[var(--bg-secondary)] text-xl transition duration-300"
              aria-current="page">PORTAL LOMBA</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Tutup Navbar -->

  <!-- Hero Section -->

  <section id="heroSection"
    class="flex items-center justify-center h-[60vh] md:h-screen bg-[url('/assets/img/bg-hero.jpg')] bg-cover bg-center">
    <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-56">
      <h1 class="mb-2 text-xl font-semibold md:text-2xl lg:text-4xl text-[var(--txt-primary)]">
        Welcome To
      </h1>
      <h1 class="text-2xl font-bold md:text-5xl lg:text-6xl text-[var(--txt-primary)]">
        OSIS SMK BINA INFORMATIKA
      </h1>
      <h1 class="mt-2 text-xl font-semibold md:text-2xl lg:text-4xl text-[var(--txt-primary)]">
        Official Website
      </h1>
    </div>
  </section>

  <!-- Tutup Hero Section -->

  <!-- About Us Section -->

  <section id="about-us" class="bg-[var(--bg-secondary2)] text-[var(--txt-primary2)]">
    <div class="container mx-auto py-14 md:py-24 px-6">

      <?php
      while ($tampilTentang = mysqli_fetch_array($ambilTentang)) {
      ?>

        <div class="flex flex-col w-full bg-[var(--bg-secondary3)] p-4 md:p-6 rounded-2xl md:rounded-3xl">
          <h1 class="font-bold text-xl md:text-2xl lg:text-4xl text-center md:text-start ms-0 md:ms-5">
            <?= $tampilTentang['head_about']; ?>
          </h1>
        </div>

        <div
          class="grid grid-cols-1 xl:grid-cols-2 mt-6 md:mt-10 w-full bg-[var(--bg-secondary3)] p-6 md:p-14 rounded-2xl md:rounded-3xl gap-8 xl:gap-20">
          <div class="flex flex-col">
            <img src="assets/img/<?= $tampilTentang['img_about']; ?>" alt="Image About Us" />
            <p class="text-xl lg:text-2xl font-bold mt-4">
              <?= $tampilTentang['hashtag_about']; ?>
            </p>
          </div>

          <p class="text-md lg:text-2xl xl:text-3xl 2xl:text-[34px] font-light leading-relaxed text-justify">
            <?= $tampilTentang['deskripsi_about']; ?>
          </p>
        </div>

      <?php
      }
      ?>

    </div>
  </section>

  <!-- Tutup About Us Section -->

  <!-- Program mendatang -->

  <section id="about-us" class="bg-[var(--bg-secondary)] text-[var(--txt-primary2)]">
    <div class="container mx-auto py-14 md:py-24 px-6">
      <h1 class="font-bold text-center text-2xl md:text-5xl lg:text-6xl">
        PROGRAM MENDATANG
      </h1>
      <p
        class="text-justify md:text-center text-md md:text-xl mt-4 lg:mt-8 w-full md:w-10/12 mx-auto leading-6 md:leading-10">
        Dengan dukungan OSIS, SMK Bina Informatika hadir dengan
        program-program terbaik untuk mengembangkan potensi, memberikan
        pengalaman yang seru, dan membantu peserta didik agar siap menghadapi
        masa depan!
      </p>

      <div class="grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-3 gap-10 md:gap-20 mt-10 md:mt-20">

        <!-- Perulangan Logic Promdat -->
        <?php

        while ($tampilPromdat = mysqli_fetch_array($ambilProgram)) {

        ?>

          <div
            class="flex flex-col item-center justify-center p-4 md:p-8 rounded-2xl md:rounded-4xl bg-[var(--bg-secondary3)] shadow-lg hover:bg-[var(--bg-secondary3)]/60 hover:cursor-pointer border-2 border-[var(--txt-primary)] transition duration-300 hover:shadow-none">
            <img src="assets/img/promdat/<?= $tampilPromdat['img_card']; ?>" alt="Program Mendatang 1" class="mb-4 rounded-2xl" />
            <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-start">
              <?= $tampilPromdat['judul_card']; ?>
            </h1>
            <p class="text-sm sm:text-md md:text-lg">
              <?= $tampilPromdat['tanggal_card']; ?>
            </p>
            <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700" />
            <p class="text-md md:text-xl text-justify">
              <?= $tampilPromdat['deskripsi_card']; ?>
            </p>
          </div>

        <?php

        }

        ?>

      </div>
    </div>
  </section>

  <!-- Tutup Program mendatang -->

  <!-- Divisi -->

  <section id="about-us" class="bg-[var(--bg-primary)] text-[var(--bg-secondary3)]">
    <div class="container mx-auto py-14 md:py-34 px-6">
      <h1 class="font-bold text-center text-2xl md:text-5xl lg:text-6xl">
        DIVISI
      </h1>
      <p
        class="text-justify md:text-center text-md md:text-xl mt-4 lg:mt-8 w-full lg:w-1/2 mx-auto leading-6 md:leading-10">
        Divisi OSIS SMK Bina Informatika hadir dengan program-program terbaik
        Klik untuk mempelajari lebih lanjut mengenai divisi kami!
      </p>

      <div
        class="grid lg:flex flex-wrap justify-center grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-20 mt-12 md:mt-20">

        <!-- Perulangan Logic - Divisi -->
        <?php

        while ($tampilDivisi = mysqli_fetch_array($ambilDivisi)) {

        ?>

          <div class="flex flex-col gap-2 md:gap-4 items-center hover:cursor-pointer">
            <img src="assets/img/divisi/<?= $tampilDivisi['img_divisi'] ?>" alt="Divisi"
              class="w-full bg-[var(--bg-secondary3)] p-3 md:p-10 rounded-2xl md:rounded-4xl hover:bg-[var(--bg-secondary3)]/60 border-2 border-[var(--bg-secondary3)] transition duration-500" />
            <span class="font-bold text-center text-sm sm:text-xl md:text-2xl lg:text-4xl">
              <?= $tampilDivisi['tagline_divisi']; ?>
            </span>
          </div>

        <?php

        }

        ?>

      </div>
    </div>
  </section>

  <!-- Tutup Divisi -->

  <!-- Bismart News -->

  <section id="about-us" class="bg-[var(--bg-secondary2)] text-[var(--bg-secondary3)]">
    <div class="container mx-auto py-14 md:py-34 px-6">
      <h1 class="font-bold text-center text-2xl md:text-5xl lg:text-6xl">
        BISmart NEWS
      </h1>
      <p
        class="text-justify md:text-center text-md md:text-xl mt-4 lg:mt-8 w-full lg:w-[80%] mx-auto leading-6 md:leading-10 mb-12 xl:mb-0">
        Tetap terhubung dengan berita terbaru, cerita inspiratif, dan update
        menarik dari OSIS SMK Bina Informatika! Kami menghadirkan informasi
        terkini seputar kegiatan sekolah, prestasi siswa, dan berbagai event
        seru.
      </p>

      <!-- Carousel -->
      <div id="default-carousel" class="relative w-full" data-carousel="slide">
        <!-- Carousel wrapper -->
        <div class="relative h-56 sm:h-72 md:h-96 lg:h-[32rem] xl:h-screen overflow-hidden rounded-lg">

          <!-- Logic Loop News -->
          <?php

          while ($tampilNews = mysqli_fetch_array($ambilNews)) {

          ?>

            <div class="hidden duration-700 ease-in-out" data-carousel-item>
              <img src="assets/img/news/<?= $tampilNews['gambar'] ?>"
                class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>

          <?php

          }

          ?>

        </div>
        <!-- Slider controls -->
        <button type="button"
          class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
          data-carousel-prev>
          <span
            class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 md:w-15 md:h-15 rounded-full bg-[var(--bg-secondary3)] group-hover:bg-[var(--bg-secondary3)]/80 group-focus:ring-4 group-focus:ring-[var(--bg-secondary)] group-focus:outline-none">
            <svg class="w-3 h-3 sm:w-4 sm:h-4  md:w-6 md:h-6 text-[var(--txt-primary2)] rtl:rotate-180"
              aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 1 1 5l4 4" />
            </svg>
            <span class="sr-only">Previous</span>
          </span>
        </button>
        <button type="button"
          class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
          data-carousel-next>
          <span
            class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 md:w-15 md:h-15 rounded-full bg-[var(--bg-secondary3)] group-hover:bg-[var(--bg-secondary3)]/80 group-focus:ring-4 group-focus:ring-[var(--bg-secondary)] group-focus:outline-none">
            <svg class="w-3 h-3 sm:w-4 sm:h-4  md:w-6 md:h-6 text-[var(--txt-primary2)] rtl:rotate-180"
              aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 9 4-4-4-4" />
            </svg>
            <span class="sr-only">Next</span>
          </span>
        </button>
      </div>
    </div>
  </section>

  <!-- Tutup Bismart News -->

  <!-- Galeri -->

  <section id="galeri" class="bg-[var(--bg-secondary)] text-[var(--txt-primary2)]">
    <div class="container mx-auto py-14 md:py-34 px-6">
      <h1 class="font-bold text-center text-2xl md:text-5xl lg:text-6xl">
        GALERI
      </h1>
      <p
        class="text-justify md:text-center text-md md:text-xl mt-4 lg:mt-8 w-full lg:w-[80%] mx-auto leading-6 md:leading-10">
        Kumpulan potret seru perjalanan OSIS SMK Bina Informatika! Jelajahi momen-momen berharga dari berbagai kegiatan,
        mulai
        dari program unggulan, acara sekolah, hingga keseruan di balik layar.
      </p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 lg:gap-12 mt-12">

        <!-- Galeri Landscape Logic Loop -->
        <?php
        while ($tampilLandscape = mysqli_fetch_array($galeriLandscape)) {
        ?>

          <img src="assets/img/galeri/<?= $tampilLandscape['img_galeri']; ?>"
            class="p-3 sm:p-4 md:p-4 lg:p-6 xl:p-8 bg-[var(--bg-secondary3)] rounded-xl md:rounded-4xl hover:bg-[var(--bg-secondary3)]/40 transition duration-500 border-2 border-[var(--bg-secondary3)] cursor-pointer hover:scale-101"
            alt="Galeri Landscape 1">

        <?php
        }
        ?>

      </div>

      <div
        class="grid grid-cols-2 [350px]:grid-cols-2 [351px]:grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-8 md:gap-20 lg:gap-8 mt-6 md:mt-10 justify-center">

        <!-- Galeri Kotak Logic Loop -->
        <?php
        while ($tampilKotak = mysqli_fetch_array($galeriKotak)) {
        ?>

          <img src="assets/img/galeri/<?= $tampilKotak['img_galeri']; ?>"
            class="p-2 sm:p-4 md:p-6 lg:p-6 xl:p-8 bg-[var(--bg-secondary3)] rounded-3xl hover:bg-[var(--bg-secondary3)]/40 transition duration-500 border-2 border-[var(--bg-secondary3)] cursor-pointer hover:scale-101"
            alt="Galeri">

        <?php
        }
        ?>

      </div>
    </div>
  </section>

  <!-- Tutup Galeri -->

  <!-- Forum Aspirasi -->

  <section id="forumAspirasi" class="bg-[var(--bg-secondary3)] text-[var(--txt-primary2)]">
    <div class="container mx-auto py-14 md:py-34 px-6">
      <h1 class="font-bold text-center text-2xl md:text-5xl lg:text-6xl text-[var(--bg-secondary2)]">
        FORUM ASPIRASI
      </h1>
      <p
        class="text-justify md:text-center text-md md:text-xl mt-4 lg:mt-8 w-full lg:w-[80%] mx-auto leading-6 md:leading-10">
        Kami selalu terbuka untuk masukan! Berikan saran, ide, harapan atau kritik Anda agar OSIS SMK Bina Informatika
        bisa
        terus berkembang dan menghadirkan program terbaik bagi seluruh Peserta DIdik.
      </p>

      <img src="assets/img/forum-aspirasi-img.png"
        class="mx-auto bg-[var(--bg-secondary)] p-4 sm:p-5 md:p-6 lg:p-10 mt-12 rounded-2xl md:rounded-4xl border-2 border-[var(--bg-secondary)] hover:bg-[var(--bg-secondary)]/50 cursor-pointer transition duration-500 w-full"
        alt="Forum Aspirasi Image">

      <h1 class="font-bold text-[var(--txt-primary2)] text-lg md:text-xl lg:text-3xl mt-8 md:mt-12">Ditujukan ke...</h1>

      <form class="w-full mx-auto" action="" method="POST">
        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        </label>
        <select id="tujuanForumAspirasi" name="tujuan_aspirasi"
          class="bg-[var(--bg-secondary)] border border-[var(--bg-primary)] text-gray-900 text-md md:text-lg rounded-2xl md:rounded-3xl block w-full  px-6 md:px-8 py-2 md:py-4 mt-4 hover:cursor-pointer hover:bg-[var(--bg-secondary)]/60 transition duration-500">
          <option value="">Semua Tujuan</option>
          <option value="OSIS Divisi Bela Negara">OSIS Divisi Bela Negara</option>
          <option value="OSIS Divisi BPH (Badan Pengurus Harian)">OSIS Divisi BPH (Badan Pengurus Harian)</option>
          <option value="OSIS Divisi Budi Pekerti">OSIS Divisi Budi Pekerti</option>
          <option value="OSIS Divisi Kesehatan">OSIS Divisi Kesehatan</option>
          <option value="OSIS Divisi Ketaqwaan">OSIS Divisi Ketaqwaan</option>
          <option value="OSIS Divisi Seniora (Seni dan Olahraga)">OSIS Divisi Seniora (Seni dan Olahraga)</option>
          <option value="OSIS Divisi TIK (Teknologi Informasi dan Komunikasi)">OSIS Divisi TIK (Teknologi Informasi dan Komunikasi)</option>
          <option value="Pihak Sekolah">Pihak Sekolah</option>
          <option value="Seluruh OSIS">Seluruh OSIS</option>

        </select>

        <div class="mt-8 md:mt-12">
          <label for="message" class="block mb-4 font-bold text-[var((--txt-primary2))] text-lg md:text-xl lg:text-3xl">
            Aspirasi / Saran / Kritik (max. 300 words)
          </label>
          <textarea id="message" rows="4" name="komentar_forum"
            class="block p-3 md:p-4 w-full text-md md:text-xl text-[var(--txt-primary2)] bg-white rounded-2xl md:rounded-3xl border border-[var(--bg-primary)]/50 focus:ring-[var(--bg-primary)]/20 focus:border-[var(--bg-primary)]"
            placeholder="Berikan aspirasi, saran, atau kritikmu di kolom ini. Pastikan menggunakan bahasa yang sopan, ya!"></textarea>
        </div>

        <button type="submit" name="kirim"
          class="text-[var(--txt-primary)] bg-[var(--bg-secondary2)] hover:bg-[var(--bg-secondary2)]/90 focus:ring-3 focus:outline-none focus:ring-[var(--bg-secondary)] font-bold rounded-xl text-md md:text-lg w-full px-5 py-2.5 text-center mt-6 cursor-pointer transition duration-500 shadow-md">
          Kirim
        </button>

      </form>
    </div>
  </section>

  <!-- Tutup Forum Aspirasi -->

  <!-- Biyouth Creation -->
  <section id="biyouth-creation" class="bg-[var(--bg-secondary2)] text-[var(--txt-primary)]">
    <div class="container mx-auto py-14 md:py-34 px-6">
      <h1 class="font-bold text-center text-2xl md:text-5xl lg:text-6xl text-[var(--txt-primary)]">
        BIYOUTH CREATION
      </h1>
      <p class="text-justify md:text-center text-base md:text-xl mt-4 lg:mt-8 w-full mx-auto leading-6 md:leading-10">
        BIYOUTH CREATION adalah wadah untuk menampilkan karya terbaik peserta didik SMK Bina Informatika. Submit karyamu
        melalui
        email: osisbph2526@gmail.com dengan subject Nama_Kelas_Judul Karya_BIYOUTH CREATION. Kami tunggu karya
        terbaikmu!
      </p>

      <!-- Biyouth - Lomba -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-12 mt-12">

        <!-- Logic Loop Biyouth -->
        <?php
        while ($tampilBiyouth = mysqli_fetch_array($ambilBiyouth)) {
        ?>

          <div class="flex flex-col gap-4 md:gap-6 items-center hover:cursor-pointer">
            <img src="assets/img/biyouth_creation/<?= $tampilBiyouth['gambar_biyouth'] ?>" alt="Divisi"
              class="w-full bg-[var(--bg-secondary3)] p-4 sm:p-6 md:p-8 rounded-xl md:rounded-2xl hover:bg-[var(--bg-secondary3)]/60 border-2 border-[var(--bg-secondary3)] transition duration-500 shadow-lg" />
            <div
              class="bg-[var(--bg-secondary3)] p-2 md:p-4 w-full text-center rounded-xl md:rounded-2xl hover:bg-[var(--bg-secondary3)]/80 border-2 border-[var(--bg-secondary3)] transition duration-500 shadow-lg">
              <h1 class="font-bold text-base md:text-xl lg:text-2xl text-[var(--txt-primary2)]">
                <?= $tampilBiyouth['judul_biyouth']; ?>
              </h1>
              <h1 class="font-light italic text-sm md:text-md lg:text-xl text-[var(--txt-primary2)]">
                <?= $tampilBiyouth['nama_peserta']; ?>
              </h1>
            </div>
          </div>

        <?php
        }
        ?>

      </div>
    </div>
  </section>
  <!-- Tutup Biyouth Creation -->

  <!-- Footer -->

  <footer class="bg-[var(--bg-primary)] text-[var(--txt-primary)]">
    <div class="container mx-auto px-6 py-32 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-6 items-center">

      <!-- Kiri -->
      <div class="flex flex-col items-center md:items-start text-center md:text-left">
        <div class="flex items-center justify-center md:justify-start space-x-2 md:space-x-4 mb-2 md:mb-4">
          <!-- Logo -->
          <img src="assets/img/logo-osis-putih.png" alt="Logo OSIS - Putih" class="w-10 md:w-20">
          <h2 class="text-3xl md:text-6xl font-bold">OSIS</h2>
        </div>

        <h3 class="text-3xl md:text-5xl font-bold leading-relaxed">
          SMK BINA <br> INFORMATIKA
        </h3>

        <!-- Kontak -->
        <div class="flex flex-col md:flex-row gap-4 md:gap-12 mt-8">
          <div class="flex items-center justify-center md:justify-start space-x-1 md:space-x-2">
            <svg class="w-6 h-6 md:w-8 md:h-8 text-[var(--bg-secondary)]" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
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

  <!-- Scroll Logic Navbar -->
  <script>
    window.addEventListener("scroll", function() {
      const navbar = document.querySelector("nav");

      if (window.scrollY > 0) {
        navbar.classList.add("bg-[var(--bg-primary)]", "shadow-md");
        navbar.classList.remove("bg-transparent");
      } else {
        navbar.classList.remove("bg-[var(--bg-primary)]", "shadow-md");
        navbar.classList.add("bg-transparent");
      }
    });
  </script>

  <!-- Flowbite Script -->
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
<?php
$pageTitle = 'Beranda | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.';
$metaDescription = 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.';
$metaKeywords = 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia';
$canonicalUrl = base_url();
$bodyClass = 'index-page';
$activePage = 'index';

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

$navItems = [
  ['slug' => 'index', 'label' => 'Beranda', 'path' => base_url()],
  ['slug' => 'tentang', 'label' => 'Tentang', 'path' => base_url('tentang')],
  ['slug' => 'info', 'label' => 'Info', 'path' => base_url('info')],
  ['slug' => 'kontak', 'label' => 'Kontak', 'path' => base_url('kontak')],
  ['slug' => 'jadwal', 'label' => 'Jadwal', 'path' => base_url('jadwal')],
  ['slug' => 'sertifikat', 'label' => 'Sertifikat', 'path' => base_url('sertifikat')],
  ['slug' => 'bonus', 'label' => 'Bonus', 'path' => base_url('bonus')],
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title><?= $escape($pageTitle) ?></title>
  <meta name="description" content="<?= $escape($metaDescription) ?>" />
  <meta name="keywords" content="<?= $escape($metaKeywords) ?>" />
  <link rel="canonical" href="<?= $escape($canonicalUrl) ?>" />
  <meta name="robots" content="index, follow" />
  <meta property="og:locale" content="id_ID" />
  <meta property="og:type" content="website" />
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= base_url() ?>" />
  <meta property="og:site_name" content="EQIYU INDONESIA" />
  <meta property="og:locale" content="id_ID" />
  <meta
    property="og:title"
    content="EQIYU INDONESIA - Lembaga Pendidikan Profesional Barista &amp; Bisnis Kuliner" />
  <meta
    property="og:description"
    content="Kursus dan pelatihan profesional untuk menjadi Barista handal dan membangun bisnis kuliner sukses." />
  <meta property="og:image" content="<?= base_url('assets/img/eqiyu-logo.png') ?>" />
  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:site" content="@Eqiyu_Indonesia" />
  <meta property="twitter:url" content="<?= base_url() ?>" />
  <meta
    property="twitter:title"
    content="EQIYU INDONESIA - Lembaga Pendidikan Profesional Barista &amp; Bisnis Kuliner" />
  <meta
    property="twitter:description"
    content="Kursus dan pelatihan profesional untuk menjadi Barista handal dan membangun bisnis kuliner sukses." />
  <meta property="twitter:image" content="<?= base_url('assets/img/eqiyu-logo.png') ?>" />
  <!-- Canonical URL -->
  <link rel="canonical" href="<?= $escape($canonicalUrl) ?>" />

  <!-- Favicons -->
  <link href="<?= base_url('assets/img/favicon.png') ?>" rel="icon" />
  <link href="<?= base_url('assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Vendor CSS Files -->
  <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/vendor/aos/aos.css') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/vendor/swiper/swiper-bundle.min.css') ?>" rel="stylesheet" />

  <!-- Template Main CSS Files -->
  <link href="<?= base_url('assets/css/main.css') ?>" rel="stylesheet" />
</head>

<body class="<?= $escape($bodyClass) ?>">
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
      <a href="<?= base_url() ?>" class="logo d-flex align-items-center me-auto">
        <img src="<?= base_url('assets/img/logo.webp') ?>" alt="logo Eqiyu Indonesia" />
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <?php foreach ($navItems as $item): ?>
            <?php $isActive = $activePage === $item['slug']; ?>
            <li>
              <a href="<?= $item['path'] ?>" <?= $isActive ? ' class="active"' : '' ?>><?= $item['label'] ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="<?= base_url('kursus') ?>">Semua Kursus</a>
    </div>
  </header>

  <main class="main">
    <!-- Courses Hero Section -->
  <section id="courses-hero" class="courses-hero section light-background">
    <div class="hero-content">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="hero-text">
              <h1>Lembaga Pendidikan Profesional</h1>
              <p>
                Kursus Barista, Mixology, Tea & Tea Blending, Roastery,
                serta Pelatihan dan Konsultan Membangun Bisnis Caffe &
                Coffeshop.
              </p>

              <div class="hero-stats">
                <div class="stat-item">
                  <span
                    class="number purecounter"
                    data-purecounter-start="0"
                    data-purecounter-end="2<?= $total_alumni ?? 50000 ?>"
                    data-purecounter-duration="2"></span>
                  <span class="label">Total Alumni</span>
                </div>
                <div class="stat-item">
                  <span
                    class="number purecounter"
                    data-purecounter-start="0"
                    data-purecounter-end="<?= $jumlah_kelas ?? 11 ?>"
                    data-purecounter-duration="2"></span>
                  <span class="label">Kelas Regular</span>
                </div>
                <div class="stat-item">
                  <span
                    class="number purecounter"
                    data-purecounter-start="0"
                    data-purecounter-end="<?= $jumlah_kota ?? 2 ?>"
                    data-purecounter-duration="2"></span>
                  <span class="label">Kota Kelas</span>
                </div>
              </div>

              <div class="hero-buttons">
                <a href="<?= base_url('kursus') ?>" class="btn btn-primary">Lihat Semua Kursus</a>
                <a href="<?= base_url('jadwal') ?>" class="btn btn-outline">Lihat Jadwal</a>
              </div>

              <div class="hero-features">
                <div class="feature">
                  <i class="bi bi-shield-check"></i>
                  <span>Sertifikat Kelas</span>
                </div>
                <div class="feature">
                  <i class="bi bi-clock"></i>
                  <span>Akses Modul Seumur Hidup</span>
                </div>
                <div class="feature">
                  <i class="bi bi-people"></i>
                  <span>Trainer Ahli</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="hero-image">
              <div class="main-image">
                <img
                  src="assets/img/education/courses-13.webp"
                  alt="Online Learning"
                  class="img-fluid" />
              </div>

              <div class="floating-cards">
                <div
                  class="course-card"
                  data-aos="fade-up"
                  data-aos-delay="300">
                  <div class="card-icon">
                    <i class="bi bi-emoji-sunglasses"></i>
                  </div>
                  <div class="card-content">
                    <h6>Kelas Barista</h6>
                    <span>1<?= number_format($kelas_barista ?? 2450) ?> alumni</span>
                  </div>
                </div>

                <div
                  class="course-card"
                  data-aos="fade-up"
                  data-aos-delay="400">
                  <div class="card-icon">
                    <i class="bi bi-briefcase"></i>
                  </div>
                  <div class="card-content">
                    <h6>Kelas Bisnis</h6>
                    <span><?= number_format($kelas_bisnis ?? 1890) ?> alumni</span>
                  </div>
                </div>

                <div
                  class="course-card"
                  data-aos="fade-up"
                  data-aos-delay="500">
                  <div class="card-icon">
                    <i class="bi bi-airplane"></i>
                  </div>
                  <div class="card-content">
                    <h6>Kelas Private</h6>
                    <span>10<?= number_format($kelas_private ?? 3200) ?> alumni</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="hero-background">
      <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
      </div>
    </div>
  </section>
  <!-- /Courses Hero Section -->

    <!-- Featured Courses Section -->
  <section id="featured-courses" class="featured-courses section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4">
        <div
          class="col-lg-4 col-md-6"
          data-aos="fade-up"
          data-aos-delay="200">
          <div class="course-card">
            <div class="course-image">
              <img
                src="assets/img/education/1.jpg"
                alt="Jadwal kelas pelatihan Eqiyu Indonesia"
                class="img-fluid" />
            </div>
            <div class="course-content">
              <h3><a href="<?= base_url('jadwal') ?>">Jadwal Kelas</a></h3>
              <p>
                Temukan jadwal pelatihan terbaru dari EQIYU INDONESIA.
                Wujudkan karier dan bisnis kulinermu dengan langkah pasti!
              </p>
              <a href="<?= base_url('jadwal') ?>" class="btn-course">Lihat</a>
            </div>
          </div>
        </div>
        <!-- End Course Item -->

        <div
          class="col-lg-4 col-md-6"
          data-aos="fade-up"
          data-aos-delay="300">
          <div class="course-card">
            <div class="course-image">
              <img
                src="assets/img/education/2.jpg"
                alt="Informasi dan artikel terbaru Eqiyu Indonesia"
                class="img-fluid" />
            </div>
            <div class="course-content">
              <h3><a href="<?= base_url('info') ?>">Informasi & Artikel</a></h3>
              <p>
                Ikuti informasi terkini seputar dunia kopi, bisnis kuliner,
                tips usaha, hingga cerita inspiratif alumni hanya di Info &
                Artikel EQIYU INDONESIA.
              </p>
              <a href="<?= base_url('info') ?>" class="btn-course">Lihat</a>
            </div>
          </div>
        </div>
        <!-- End Course Item -->

        <div
          class="col-lg-4 col-md-6"
          data-aos="fade-up"
          data-aos-delay="400">
          <div class="course-card">
            <div class="course-image">
              <img
                src="assets/img/education/3.jpg"
                alt="Cek sertifikat peserta Eqiyu Indonesia"
                class="img-fluid" />
            </div>
            <div class="course-content">
              <h3><a href="<?= base_url('sertifikat') ?>">Check Sertifikat</a></h3>
              <p>
                Sudah mengikuti kelas di EQIYU INDONESIA? Verifikasi
                keikutsertaan dan validasi sertifikatmu dengan mudah melalui
                fitur ini.
              </p>
              <a href="<?= base_url('sertifikat') ?>" class="btn-course">Lihat</a>
            </div>
          </div>
        </div>
        <!-- End Course Item -->
      </div>
    </div>
  </section>
  <!-- /Featured Courses Section -->

    <!-- pricing Section -->
  <section id="pricing" class="pricing section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Kursus & Pelatihan Unggulan</h2>
    </div>
    <!-- End Section Title -->
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <!-- Pricing Plans -->
      <div class="row gy-4 justify-content-center">
        <!-- Plus Plan -->
        <div
          class="col-lg-4 col-md-6 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="200">
          <div class="pricing-item">
            <div class="pricing-header">
              <h6 class="pricing-category">
                Private Class Beverage & Business
              </h6>
              <div class="price-wrap">
                <div class="price">
                  <sup>Rp.</sup>9.500.000<span>,-</span>
                </div>
              </div>
            </div>

            <div class="pricing-cta">
              <a href="#" class="btn btn-primary w-100">Lihat Detail</a>
            </div>

            <div class="pricing-features">
              <ul class="feature-list">
                <li>
                  <i class="bi bi-check"></i> Kelas privat eksklusif dengan
                  materi yang disesuaikan 100% dengan kebutuhan dan target
                  peserta—baik untuk skill, konsep bisnis, maupun
                  pengembangan usaha kuliner.
                </li>
                <li>
                  <i class="bi bi-check"></i> Cocok untuk individu atau tim
                  yang ingin solusi spesifik, cepat diaplikasikan, dan
                  langsung menuju goal usaha.
                </li>
                <li>
                  <i class="bi bi-check"></i> Materi fleksibel: bisnis,
                  produk, manajemen, pemasaran.
                </li>
                <li><i class="bi bi-check"></i> Sertifikat + modul.</li>
                <li>
                  <i class="bi bi-check"></i> Free konsultasi & bar
                  experience.
                </li>
              </ul>
              <h6>
                Solusi personal untuk kamu yang serius membangun bisnis
                kuliner sesuai visi dan tujuan sendiri.
              </h6>
            </div>
          </div>
        </div>
        <!-- End Plus Plan -->

        <!-- Business Plan -->
        <div
          class="col-lg-4 col-md-6 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="300">
          <div class="pricing-item popular">
            <div class="popular-badge">Populer</div>
            <div class="pricing-header">
              <h6 class="pricing-category">
                Basic Barista Class & Latte Art
              </h6>
              <div class="price-wrap">
                <div class="price">
                  <sup>Rp.</sup>3.200.000<span>,-</span>
                </div>
              </div>
              <p class="pricing-description">
                Kelas yang banyak di ikuti dan diminati ditiap tahun. Dan
                paling banyak jumlah alumni di EQIYU INDONESIA.
              </p>
            </div>

            <div class="pricing-cta">
              <a href="#" class="btn btn-primary w-100">Lihat Detail</a>
            </div>

            <div class="pricing-features">
              <ul class="feature-list">
                <li>
                  <i class="bi bi-check"></i>
                  Kelas yang menantang namun menyenangkan untuk pemula atau
                  yang ingin mempelajari dasar-dasar kopi.
                </li>
                <li>
                  <i class="bi bi-check"></i> Pelatihan intensif selama 3
                  hari untuk menjadi barista profesional atau membuka kedai
                  kopi sendiri.
                </li>
                <li>
                  <i class="bi bi-check"></i> Materi lengkap mulai dari
                  espresso, latte art, manual brew, hingga manajemen bar &
                  F&B hospitality.
                </li>
                <li><i class="bi bi-check"></i> Full praktek + teori</li>
                <li><i class="bi bi-check"></i> Sertifikat + modul</li>
                <li>
                  <i class="bi bi-check"></i> Free konsultasi & bar
                  experience
                </li>
              </ul>
              <h6>
                Langkah awal ideal untuk kamu yang ingin terjun ke industri
                kopi dengan percaya diri!
              </h6>
            </div>
          </div>
        </div>
        <!-- End Business Plan -->

        <!-- Enterprise Plan -->
        <div
          class="col-lg-4 col-md-6 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="400">
          <div class="pricing-item">
            <div class="pricing-header">
              <h6 class="pricing-category">
                Workshop Bisnis Cafe & Coffeshop
              </h6>
              <div class="price-wrap">
                <div class="price">
                  <sup>Rp.</sup>3.600.000<span>,-</span>
                </div>
              </div>
            </div>

            <div class="pricing-cta">
              <a href="#" class="btn btn-primary w-100">Lihat Detail</a>
            </div>

            <div class="pricing-features">
              <ul class="feature-list">
                <li>
                  <i class="bi bi-check"></i> Pelatihan intensif untuk
                  merancang, membangun, dan menjalankan bisnis F&B seperti
                  cafe, resto, franchise, atau bar.
                </li>
                <li>
                  <i class="bi bi-check"></i> Fokus pada efisiensi
                  investasi, studi kelayakan, perhitungan ROI, SOP, hingga
                  strategi promosi dan manajemen operasional.
                </li>
                <li>
                  <i class="bi bi-check"></i> Simulasi dan praktek langsung
                  mulai dari business plan, RAB, alat & bahan, hingga
                  ratusan resep minuman kekinian.
                </li>
                <li><i class="bi bi-check"></i> Sertifikat + modul.</li>
                <li>
                  <i class="bi bi-check"></i> Free konsultasi & bar
                  experience.
                </li>
              </ul>
              <h6>
                Kelas ideal untuk calon pengusaha kuliner yang ingin memulai
                bisnis dengan perencanaan matang dan terarah.
              </h6>
            </div>
          </div>
        </div>
        <!-- End Enterprise Plan -->
      </div>
      <div
        class="more-pricing text-center"
        data-aos="fade-up"
        data-aos-delay="500">
        <a href="<?= base_url('kursus') ?>" class="btn-more">Lihat Semua Kursus</a>
      </div>
    </div>
  </section>
  <!-- /pricing Section -->

    <!-- Fasilitas Section -->
    <section id="about" class="about section light-background">
    <div
      class="container section-title aos-init aos-animate"
      data-aos="fade-up">
      <h2>Fasilitas</h2>
    </div>
    <div
      class="container aos-init aos-animate"
      data-aos="fade-up"
      data-aos-delay="100">
      <div class="row gy-4">
        <div
          class="col-lg-3 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="200">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-award"></i>
            </div>
            <h3>Sertifikat Resmi</h3>
            <p>
              Sertifikat yang dapat Anda dapatkan setelah menyelesaikan
              program pelatihan di EQIYU INDONESIA. Dan bisa di check
              validasi di website resmi EQIYU INDONESIA.
            </p>
          </div>
        </div>
        <div
          class="col-lg-3 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="300">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-file-earmark-text"></i>
            </div>
            <h3>Softcopy Modul</h3>
            <p>
              Materi pelatihan lengkap dalam bentuk digital, sehingga Anda
              dapat belajar kembali kapan saja dan di mana saja. Dan dapat
              di download ulang kapan pun.
            </p>
          </div>
        </div>
        <div
          class="col-lg-3 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-journal-text"></i>
            </div>
            <h3>Recipe Beverage</h3>
            <p>
              Kumpulan resep minuman spesial yang bisa langsung Anda
              praktikkan dan kembangkan untuk bisnis atau kreasi pribadi.
            </p>
          </div>
        </div>
        <div
          class="col-lg-3 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-egg-fried"></i>
            </div>
            <h3>Makan Siang</h3>
            <p>
              Menikmati sajian makan siang lezat selama program berlangsung
              untuk menjaga energi Anda tetap prima.
            </p>
          </div>
        </div>
        <div
          class="col-lg-3 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-cup-hot"></i>
            </div>
            <h3>Snack</h3>
            <p>
              Camilan istimewa disediakan untuk menemani waktu istirahat
              Anda selama sesi pelatihan.
            </p>
          </div>
        </div>
        <div
          class="col-lg-3 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-camera"></i>
            </div>
            <h3>Dokumentasi Peserta</h3>
            <p>
              Foto dan video dokumentasi kegiatan Anda selama pelatihan,
              sebagai kenangan sekaligus materi promosi personal.
            </p>
          </div>
        </div>
        <div
          class="col-lg-3 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-cup-straw"></i>
            </div>
            <h3>Bar Experience</h3>
            <p>
              Praktik langsung di area bar berstandar industri, dengan alat
              dan bahan baku yang telah disediakan secara gratis.
            </p>
          </div>
        </div>
        <div
          class="col-lg-3 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-person-lines-fill"></i>
            </div>
            <h3>Free Konsultasi</h3>
            <p>
              Praktik langsung di area bar berstandar industri, dengan alat
              dan bahan baku yang telah disediakan secara gratis.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

    <!-- Artikel Section -->
    <section id="blog-posts" class="blog-posts section">
      <div
      class="container section-title aos-init aos-animate"
      data-aos="fade-up">
        <h2>Informasi & Artikel Terbaru</h2>
      </div>
      <div
        class="container aos-init aos-animate"
        data-aos="fade-up"
        data-aos-delay="100">

          <div class="row gy-4">
            <?php if (!empty($berita) && is_array($berita)): ?>
              <?php foreach ($berita as $artikel): ?>
                <div class="col-lg-4">
                  <article class="position-relative h-100">
                    <div class="post-img position-relative overflow-hidden">
                      <?php if (!empty($artikel['gambar_utama'])): ?>
                        <img src="<?= base_url($artikel['gambar_utama']) ?>" alt="<?= esc($artikel['judul']) ?>" class="img-fluid">
                      <?php else: ?>
                        <img src="<?= base_url('assets/img/no-image.jpg') ?>" alt="No Image" class="img-fluid">
                      <?php endif; ?>
                    </div>

                    <div class="meta d-flex align-items-end">
                      <span class="post-date"><span><?= date('d', strtotime($artikel['tanggal_terbit'])) ?></span><?= date('M', strtotime($artikel['tanggal_terbit'])) ?></span>
                      <div class="d-flex align-items-center">
                        <i class="bi bi-person"></i>
                        <span class="ps-2"><?= esc($artikel['penulis']) ?></span>
                      </div>
                      <span class="px-3 text-black-50">/</span>
                      <div class="d-flex align-items-center">
                        <i class="bi bi-folder2"></i>
                        <span class="ps-2"><?= esc($artikel['kategori_nama'] ?? 'Uncategorized') ?></span>
                      </div>
                    </div>

                    <div class="post-content d-flex flex-column">
                      <h3 class="post-title">
                        <?= esc($artikel['judul']) ?>
                      </h3>
                      <a href="blog-details.php" class="readmore stretched-link"><span>Selengkapnya</span><i class="bi bi-arrow-right"></i></a>
                    </div>
                  </article>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="col-12 text-center">
                <p>Belum ada artikel terbaru.</p>
              </div>
            <?php endif; ?>
            <div class="more-blog text-center aos-init aos-animate" data-aos="fade-up" data-aos-delay="500">
              <a href="<?= base_url('info') ?>" class="btn-more">Lihat Semua Info</a>
            </div>
          </div>
      </div>
    </section>

  <!-- Testimonials Section -->
  <section id="testimonials" class="testimonials section light-background">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Testimonials</h2>
    </div>
    <!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row">
        <div class="col-12">
          <div class="testimonials-container">
            <h3 class="text-center mb-4">
              Review Gmaps Eqiyu Indonesia (Malang)
            </h3>
            <div
              class="swiper testimonials-slider init-swiper"
              data-aos="fade-up"
              data-aos-delay="400">
              <script type="application/json" class="swiper-config">
                {
                  "loop": true,
                  "speed": 600,
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 1,
                  "spaceBetween": 30,
                  "pagination": {
                    "el": ".swiper-pagination",
                    "type": "bullets",
                    "clickable": true
                  },
                  "breakpoints": {
                    "768": {
                      "slidesPerView": 2
                    },
                    "992": {
                      "slidesPerView": 3
                    }
                  }
                }
              </script>

              <div class="swiper-wrapper">
                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                    </div>
                    <p>
                      Proin eget tortor risus. Vestibulum ac diam sit amet
                      quam vehicula elementum sed sit amet dui. Nulla quis
                      lorem ut libero malesuada feugiat.
                    </p>
                    <div class="testimonial-profile">
                      <img
                        src="assets/img/person/person-f-1.webp"
                        alt="Reviewer"
                        class="img-fluid rounded-circle" />
                      <div>
                        <h3>Jane Smith</h3>
                        <h4>Book Enthusiast</h4>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End testimonial item -->

                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                    </div>
                    <p>
                      Curabitur arcu erat, accumsan id imperdiet et,
                      porttitor at sem. Cras ultricies ligula sed magna
                      dictum porta. Vestibulum ante ipsum primis in faucibus
                      orci luctus.
                    </p>
                    <div class="testimonial-profile">
                      <img
                        src="assets/img/person/person-m-2.webp"
                        alt="Reviewer"
                        class="img-fluid rounded-circle" />
                      <div>
                        <h3>Michael Johnson</h3>
                        <h4>Sci-Fi Blogger</h4>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End testimonial item -->

                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-half"></i>
                    </div>
                    <p>
                      Quisque velit nisi, pretium ut lacinia in, elementum
                      id enim. Cras ultricies ligula sed magna dictum porta.
                      Donec sollicitudin molestie malesuada.
                    </p>
                    <div class="testimonial-profile">
                      <img
                        src="assets/img/person/person-f-3.webp"
                        alt="Reviewer"
                        class="img-fluid rounded-circle" />
                      <div>
                        <h3>Emily Davis</h3>
                        <h4>Book Club President</h4>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End testimonial item -->

                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                    </div>
                    <p>
                      Mauris blandit aliquet elit, eget tincidunt nibh
                      pulvinar a. Curabitur aliquet quam id dui posuere
                      blandit. Lorem ipsum dolor sit amet, consectetur.
                    </p>
                    <div class="testimonial-profile">
                      <img
                        src="assets/img/person/person-m-4.webp"
                        alt="Reviewer"
                        class="img-fluid rounded-circle" />
                      <div>
                        <h3>Robert Wilson</h3>
                        <h4>Literary Reviewer</h4>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End testimonial item -->
              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="testimonials-container">
            <h3 class="text-center mb-4">
              Review Gmaps Eqiyu Indonesia (Jogja)
            </h3>
            <div
              class="swiper testimonials-slider init-swiper"
              data-aos="fade-up"
              data-aos-delay="400">
              <script type="application/json" class="swiper-config">
                {
                  "loop": true,
                  "speed": 600,
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 1,
                  "spaceBetween": 30,
                  "pagination": {
                    "el": ".swiper-pagination",
                    "type": "bullets",
                    "clickable": true
                  },
                  "breakpoints": {
                    "768": {
                      "slidesPerView": 2
                    },
                    "992": {
                      "slidesPerView": 3
                    }
                  }
                }
              </script>

              <div class="swiper-wrapper">
                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                    </div>
                    <p>
                      Proin eget tortor risus. Vestibulum ac diam sit amet
                      quam vehicula elementum sed sit amet dui. Nulla quis
                      lorem ut libero malesuada feugiat.
                    </p>
                    <div class="testimonial-profile">
                      <img
                        src="assets/img/person/person-f-1.webp"
                        alt="Reviewer"
                        class="img-fluid rounded-circle" />
                      <div>
                        <h3>Jane Smith</h3>
                        <h4>Book Enthusiast</h4>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End testimonial item -->

                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                    </div>
                    <p>
                      Curabitur arcu erat, accumsan id imperdiet et,
                      porttitor at sem. Cras ultricies ligula sed magna
                      dictum porta. Vestibulum ante ipsum primis in faucibus
                      orci luctus.
                    </p>
                    <div class="testimonial-profile">
                      <img
                        src="assets/img/person/person-m-2.webp"
                        alt="Reviewer"
                        class="img-fluid rounded-circle" />
                      <div>
                        <h3>Michael Johnson</h3>
                        <h4>Sci-Fi Blogger</h4>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End testimonial item -->

                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-half"></i>
                    </div>
                    <p>
                      Quisque velit nisi, pretium ut lacinia in, elementum
                      id enim. Cras ultricies ligula sed magna dictum porta.
                      Donec sollicitudin molestie malesuada.
                    </p>
                    <div class="testimonial-profile">
                      <img
                        src="assets/img/person/person-f-3.webp"
                        alt="Reviewer"
                        class="img-fluid rounded-circle" />
                      <div>
                        <h3>Emily Davis</h3>
                        <h4>Book Club President</h4>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End testimonial item -->

                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                    </div>
                    <p>
                      Mauris blandit aliquet elit, eget tincidunt nibh
                      pulvinar a. Curabitur aliquet quam id dui posuere
                      blandit. Lorem ipsum dolor sit amet, consectetur.
                    </p>
                    <div class="testimonial-profile">
                      <img
                        src="assets/img/person/person-m-4.webp"
                        alt="Reviewer"
                        class="img-fluid rounded-circle" />
                      <div>
                        <h3>Robert Wilson</h3>
                        <h4>Literary Reviewer</h4>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End testimonial item -->
              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-6 text-center" data-aos="fade-up">
          <div class="overall-rating">
            <div class="rating-number">4.8</div>
            <div class="rating-stars">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
            </div>
            <p>Based on 230+ reviews</p>
            <div class="rating-platforms">
              <span>Review Gmaps Eqiyu Indonesia (Malang)</span>
            </div>
          </div>
        </div>
        <div class="col-6 text-center" data-aos="fade-up">
          <div class="overall-rating">
            <div class="rating-number">4.8</div>
            <div class="rating-stars">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
            </div>
            <p>Based on 230+ reviews</p>
            <div class="rating-platforms">
              <span>Review Gmaps Eqiyu Indonesia (Jogja)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /Testimonials Section -->

</main>

  <footer id="footer" class="footer accent-background">
      <div class="support section light-background">
        <div class="container aos-init aos-animate" data-aos="zoom-in">
          <div class="swiper init-swiper swiper-initialized swiper-horizontal swiper-backface-hidden">
            <script type="application/json" class="swiper-config">
              {
                "loop": true,
                "speed": 600,
                "autoplay": {
                  "delay": 5000
                },
                "slidesPerView": "auto",
                "pagination": {
                  "el": ".swiper-pagination",
                  "type": "bullets",
                  "clickable": true
                },
                "breakpoints": {
                  "320": {
                    "slidesPerView": 2,
                    "spaceBetween": 40
                  },
                  "480": {
                    "slidesPerView": 3,
                    "spaceBetween": 60
                  },
                  "640": {
                    "slidesPerView": 4,
                    "spaceBetween": 80
                  },
                  "992": {
                    "slidesPerView": 5,
                    "spaceBetween": 120
                  },
                  "1200": {
                    "slidesPerView": 6,
                    "spaceBetween": 120
                  }
                }
              }
            </script>
            <div class="swiper-wrapper align-items-center" id="swiper-wrapper-c4bc11742d10cf612" aria-live="off" style="transition-duration: 0ms; transform: translate3d(-236px, 0px, 0px); transition-delay: 0ms;">


              <div class="swiper-slide swiper-slide-prev" style="width: 120px; margin-right: 120px; " role="group" aria-label="3 / 7" data-swiper-slide-index="2">
                <a href="https://mbowis.id/" target="_blank">
                  <img src="assets/img/support/mbowis.webp" class="img-fluid" alt="mbowis" title="mbowis syrup">
                </a>
              </div>
              <div class="swiper-slide swiper-slide-active" style="width: 120px; margin-right: 120px;" role="group" aria-label="4 / 7" data-swiper-slide-index="3">
                <a href="https://atourwis.id/" target="_blank">
                  <img src="assets/img/support/atourwis.webp" class="img-fluid" alt="atourwis" title="Atourwis Travel">
                </a>
              </div>
              <div class="swiper-slide swiper-slide-next" style="width: 120px; margin-right: 120px;" role="group" aria-label="5 / 7" data-swiper-slide-index="4">
                <a href="https://mlgcoffee.com/" target="_blank">
                  <img src="assets/img/support/mlg.webp" class="img-fluid" alt="MLG management" title="MLG coffee">
                </a>
              </div>
              <div class="swiper-slide" style="width: 120px; margin-right: 120px;" role="group" aria-label="6 / 7" data-swiper-slide-index="5">
                <a href="https://www.instagram.com/pressolve/" target="_blank">
                  <img src="assets/img/support/pressolve.webp" class="img-fluid" alt="pressolve" title="pressolve Website">
                </a>
              </div>
              <div class="swiper-slide" style="width: 120px; margin-right: 120px;" role="group" aria-label="7 / 7" data-swiper-slide-index="6">
                <a href="https://overhaul.id/" target="_blank">
                  <img src="assets/img/support/overhaul.webp" class="img-fluid" alt="overhaul" title="Overhaul Website">
                </a>
              </div>
              <div class="swiper-slide" style="width: 120px; margin-right: 120px;" role="group" aria-label="1 / 7" data-swiper-slide-index="0">
                <a href="https://bekasbaru.com/" target="_blank">
                  <img src="assets/img/support/bekasbaru.webp" class="img-fluid" alt="EQIYU" title="BekasBaru.com - Toko alat kopi Bekas dan Baru">
                </a>
              </div>
              <div class="swiper-slide" style="width: 120px; margin-right: 120px;" role="group" aria-label="2 / 7" data-swiper-slide-index="1">
                <a href="https://azkaindiesatu.id/" target="_blank">
                  <img src="assets/img/support/ais.webp" class="img-fluid" alt="AIS" title="Azka Indie Satu">
                </a>
              </div>
            </div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
          </div>
          <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
        </div>
      </div>
      <div class="container footer-top">
        <div class="row gy-4">
          <div class="col-lg-6 col-md-12 footer-about">
            <a href="index.php" class="logo d-flex align-items-center">
              <img src="assets/img/logofooter.webp" alt="Eqiyu Indonesia" />
            </a>
            <h3>Lembaga Pendidikan Profesional</h3>
            <p>
              Kursus Barista, Mixology, Tea &amp; Tea Blending, Roastery, Pelatihan &amp; Konsultan
              Membangun Bisnis Caffe &amp; Coffeshop.
            </p>
            <div class="social-links d-flex mt-4">
              <a href="https://x.com/Eqiyu_Indonesia/"><i class="bi bi-twitter-x"></i></a>
              <a href="https://www.facebook.com/eqiyu.indonesia"><i class="bi bi-facebook"></i></a>
              <a href="https://www.instagram.com/eqiyu.indonesia"><i class="bi bi-instagram"></i></a>
              <a href="https://www.youtube.com/c/TokoBekasBaru"><i class="bi bi-youtube"></i></a>
              <a href="https://www.tiktok.com/@eqiyu.indonesia"><i class="bi bi-tiktok"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6 footer-links">
            <h4>Eqiyu Malang</h4>
            <div class="d-flex align-items-start mb-2">
              <i class="bi bi-geo-alt me-2 mt-1"></i>
              <span>
                Jl. Brigjend Slamet Riadi No.76,<br />
                Oro-oro Dowo, Kec. Klojen,<br />
                Kota Malang, Jawa Timur 65119.
              </span>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-pin-map me-2 mt-1"></i>
              <span>
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.2903049907245!2d112.62654827588874!3d-7.968920479432775!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd62974117b5143%3A0xac0a6f7e8b6c0c35!2sEqiyu%20Indonesia%20Malang%20(Kursus%20Barista%20%26%20Bisnis%20Kuliner)!5e0!3m2!1sen!2sid!4v1746086179700!5m2!1sen!2sid"
                  width="100%"
                  height="100%"
                  style="border: 0; border-radius: 10px"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </span>
            </div>
          </div>

          <div class="col-lg-3 col-6 footer-links">
            <h4>Eqiyu Jogja</h4>
            <div class="d-flex align-items-start mb-2">
              <i class="bi bi-geo-alt me-2 mt-1"></i>
              <span>
                Jl. Pugeran.11 - 15,<br />
                Suryodiningratan, Kec. Mantrijeron,<br />
                Kota Yogyakarta, DIY 55141.
              </span>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-pin-map me-2 mt-1"></i>
              <span>
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.7700450055027!2d110.35300158485491!3d-7.814149257479901!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a57f7da45014d%3A0x6c71f9c11a0f935!2sEqiyu%20Indonesia%20Jogja!5e0!3m2!1sen!2sid!4v1746086112162!5m2!1sen!2sid"
                  width="100%"
                  height="100%"
                  style="border: 0; border-radius: 10px"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="container copyright text-center mt-4">
        <p>
          © <span>Copyright</span>
          <strong class="px-1 sitename"><a href="<?= base_url() ?>">Eqiyu Indonesia</a></strong>
          <span>All Rights Reserved</span>
        </p>
        <div class="credits">
          Dibangun dengan ❤️ oleh <a href="https://azkaindiesatu.id">AIS</a>
        </div>
      </div>
    </footer>
    <!-- WhatsApp Float Button -->
    <a
      href="https://wa.me/6285852229959?text=Hallo%2C%20Saya%20tahu%20info%20dari%20web%20https%3A%2F%2Feqiyu.id%20ingin%20bertanya%20tentang%20kelas%2Fkursus"
      class="whatsapp-float"
      target="_blank">
      <i class="bi bi-whatsapp"></i>
    </a>
    <!-- Scroll Top -->
    <a
      href="#"
      id="scroll-top"
      class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
    </body>

    </html>
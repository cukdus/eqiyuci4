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

      <a class="btn-getstarted" href="<?= base_url('courses') ?>">Semua Kursus</a>
    </div>
  </header>

<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Tentang Kami</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Beranda</a></li>
          <li class="current">Tentang</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <!-- About Section -->
  <section id="about" class="about section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row align-items-center">
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
          <img
            src="assets/img/education/education-square-2.webp"
            alt="About Us"
            class="img-fluid rounded-4" />
        </div>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
          <div class="about-content">
            <span class="subtitle">Tentang Eqiyu Indonesia</span>
            <h2>Train to Grow, Build to Lead</h2>
            <p>
              <strong>Eqiyu Indonesia</strong> adalah ruang belajar untuk
              siapa pun yang ingin tumbuh dan berkembang. Dari keterampilan
              teknis hingga soft skill, kami membantu Anda menjadi versi
              terbaik diri Anda.
            </p>
            <div class="stats-row">
              <div class="stats-item">
                <span class="count">15+</span>
                <p>Tahun Pengalaman</p>
              </div>
              <div class="stats-item">
                <span class="count">11</span>
                <p>Kelas Reguler</p>
              </div>
              <div class="stats-item">
                <span class="count">2k+</span>
                <p>Peserta Didik</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-5 pt-4">
        <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="300">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-eye"></i>
            </div>
            <h3>Visi</h3>
            <p>
              Lembaga Pendidikan Belajar Profesional yang berbasis
              ke-khusus-an / ketrampilan & Spesialisasi Profesi yang
              berorientasi pada Mindset Profesional, Business &
              Entreprenuership.
            </p>
          </div>
        </div>
        <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="200">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-bullseye"></i>
            </div>
            <h3>Misi</h3>
            <ul class="about-list">
              <li>
                <i class="bi bi-check"></i> Short Class (Kursus) Untuk
                Bisnis Kuliner & Pemasaran.
              </li>
              <li>
                <i class="bi bi-check"></i> Short Class (Kursus) Untuk Man.
                Power yang siap bekerja di Bidang Food & Beverage.
              </li>
              <li>
                <i class="bi bi-check"></i> Mitra Strategis Bagi para
                Peserta / Siswa.
              </li>
              <li>
                <i class="bi bi-check"></i> Lembaga Pendidikan Formal, Non
                Formal dan Pusat Sertifikasi Profesi (LSP).
              </li>
            </ul>
          </div>
        </div>

        <div
          class="col-lg-8 mb-4 mx-auto"
          data-aos="fade-up"
          data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-award"></i>
            </div>
            <h3>Nilai</h3>
            <ul class="about-list">
              <li>
                <i class="bi bi-check"></i> Culture : Nilai, keyakinan, dan
                sikap yang dianut oleh perusahaan & tim.
              </li>
              <li>
                <i class="bi bi-check"></i> Integrity : Menjunjung tinggi
                kejujuran, transparansi, dan praktik etis dalam semua
                transaksi.
              </li>
              <li>
                <i class="bi bi-check"></i> Innovation : Mendorong
                kreativitas dan ide-ide baru untuk mendorong kemajuan.
              </li>
              <li>
                <i class="bi bi-check"></i> Transparency : Memastikan
                keterbukaan dan kejelasan dalam komunikasi dan tindakan.
              </li>
              <li>
                <i class="bi bi-check"></i> Respect : Memperlakukan setiap
                orang dengan bermartabat dan menghargai kontribusi mereka.
              </li>
              <li>
                <i class="bi bi-check"></i> Focus Customer : Memprioritaskan
                kebutuhan konsumen dan berupaya mencapai kepuasan mereka.
              </li>
              <li>
                <i class="bi bi-check"></i> Excellence : Berkomitmen pada
                standar tinggi dalam semua aspek pekerjaan.
              </li>
              <li>
                <i class="bi bi-check"></i> Accountability : Mengambil
                tanggung jawab atas tindakan dan hasil.
              </li>
              <li>
                <i class="bi bi-check"></i> Diversity and Inclusion :
                Merangkul dan menghargai keberagaman perspektif dan latar
                belakang.
              </li>
              <li>
                <i class="bi bi-check"></i> Sustainability : Mempromosikan
                praktik ramah lingkungan dan keseimbangan ekologi jangka
                panjang.
              </li>
              <li>
                <i class="bi bi-check"></i> Quality : Menyediakan produk dan
                layanan dengan kualitas tertinggi.
              </li>
              <li>
                <i class="bi bi-check"></i> Initiative : Mendorong karyawan
                untuk mengambil inisiatif dan membuat keputusan.
              </li>
              <li>
                <i class="bi bi-check"></i> Community Involvement : Terlibat
                dan berkontribusi terhadap masyarakat setempat.
              </li>
              <li>
                <i class="bi bi-check"></i> Learning and Development :
                Membina pertumbuhan pribadi dan profesional yang
                berkelanjutan.
              </li>
              <li>
                <i class="bi bi-check"></i> Efficiency : Berusaha mencapai
                alur kerja yang optimal dan berdaya guna.
              </li>
              <li>
                <i class="bi bi-check"></i> Safety : Mengutamakan kesehatan
                dan keselamatan karyawan dan pelanggan.
              </li>
              <li>
                <i class="bi bi-check"></i> Trust : Membangun dan memelihara
                kepercayaan dengan para pemangku kepentingan.
              </li>
              <li>
                <i class="bi bi-check"></i> Collaboration : Bekerja sama
                dengan mitra dan tim untuk hasil yang lebih baik.
              </li>
              <li>
                <i class="bi bi-check"></i> Attitude & Modesty :
                Mempertahankan kesederhanaan dan keterbukaan terhadap
                masukan.
              </li>
              <li>
                <i class="bi bi-check"></i> Honesty : Berani untuk selalu
                mengatakan kebenaran.
              </li>
              <li>
                <i class="bi bi-check"></i> Accountability : Mengambil
                tanggung jawab atas tindakan, keputusan, dan hasil.
              </li>
              <li>
                <i class="bi bi-check"></i> Justice : Memastikan kesempatan
                yang sama untuk semua.
              </li>
              <li>
                <i class="bi bi-check"></i> Social Responsibility :
                Bertindak dengan cara yang memberi manfaat bagi masyarakat
                luas.
              </li>
              <li>
                <i class="bi bi-check"></i> Continuous Improvement : Selalu
                meningkatkan proses dan keterampilan.
              </li>
              <li>
                <i class="bi bi-check"></i> Adaptability : Selalu terbuka
                terhadap ide dan cara kerja baru.
              </li>
              <li>
                <i class="bi bi-check"></i> Impact : Selalu fokus pada
                pekerjaan yang akan menciptakan dampak terbesar.
              </li>
              <li>
                <i class="bi bi-check"></i> Empathy : Memahami dan berbagi
                perasaan orang lain.
              </li>
              <li>
                <i class="bi bi-check"></i> Global Perspective : Mengenali
                dan menghargai keberagaman dan interkonektivitas global.
              </li>
              <li>
                <i class="bi bi-check"></i> Lembaga Pendidikan : Lembaga
                Pendidikan Formal, Non Formal dan Pusat Sertifikasi Profesi
                (LSP).
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="row mt-5 pt-3 align-items-center">
        <div
          class="col-lg-6 order-lg-2"
          data-aos="fade-up"
          data-aos-delay="300">
          <div class="achievements">
            <span class="subtitle">Kenapa Memilih Kami?</span>
            <h2>
              Membangun Kompetensi F&B yang Profesional dan Berdaya Saing.
            </h2>
            <p>
              Eqiyu Indonesia berkomitmen menghadirkan pendidikan dan
              pelatihan di bidang Food & Beverage (F&B) yang terarah,
              berkualitas, dan sesuai dengan kebutuhan industri. Setiap
              peserta didorong untuk tidak hanya menguasai keterampilan
              teknis, tetapi juga memahami strategi bisnis dan pengembangan
              karier di dunia kuliner modern.
            </p>
            <ul class="achievements-list">
              <li>
                <i class="bi bi-check-circle-fill"></i> Trainer
                Berpengalaman di Industri F&B.
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i> Fasilitas dan
                Peralatan Berstandar Internasional.
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i> Free Konsultasi
                Bisnis Pasca Pelatihan.
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i> Biaya Pelatihan
                Terjangkau dengan Kualitas Optimal.
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i> Kurikulum Bisnis &
                Entrepreneur yang Aplikatif.
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i> Fokus pada
                Pengembangan Kompetensi Food & Beverage Sesuai Tren
                Industri.
              </li>
            </ul>
          </div>
        </div>
        <div
          class="col-lg-6 order-lg-1"
          data-aos="fade-up"
          data-aos-delay="200">
          <div class="about-gallery">
            <div class="row g-3">
              <div class="col-6">
                <img
                  src="assets/img/education/education-1.webp"
                  alt="Campus Life"
                  class="img-fluid rounded-3" />
              </div>
              <div class="col-6">
                <img
                  src="assets/img/education/students-3.webp"
                  alt="Student Achievement"
                  class="img-fluid rounded-3" />
              </div>
              <div class="col-12 mt-3">
                <img
                  src="assets/img/education/campus-8.webp"
                  alt="Our Campus"
                  class="img-fluid rounded-3" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="featured-instructors" class="featured-instructors section">
    <!-- Section Title -->
    <div
      class="container section-title aos-init aos-animate"
      data-aos="fade-up">
      <h2>Team</h2>
    </div>
    <!-- End Section Title -->

    <div
      class="container aos-init aos-animate"
      data-aos="fade-up"
      data-aos-delay="100">
      <div class="row gy-4">
        <div
          class="col-xl-4 col-lg-4 col-md-6 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="200">
          <div class="instructor-card">
            <div class="instructor-image">
              <img
                src="assets/img/trainer/faris.webp"
                class="img-fluid"
                alt="" />
            </div>
            <div class="instructor-info">
              <h5>Fariz Chamim Udien</h5>
              <p class="specialty">CEO, Research & Development</p>
              <div class="action-buttons">
                <div class="social-links">
                  <a href="https://x.com/fariz_chamim"><i class="bi bi-twitter-x"></i></a>
                  <a href="https://www.instagram.com/farizchamim/"><i class="bi bi-instagram"></i></a>
                  <a href="https://www.facebook.com/farizchamim/"><i class="bi bi-facebook"></i></a>
                  <a href="https://id.linkedin.com/in/fariz-chamim-udien-10a36b95/"><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div
          class="col-xl-4 col-lg-4 col-md-6 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="250">
          <div class="instructor-card">
            <div class="instructor-image">
              <img
                src="assets/img/trainer/atha.webp"
                class="img-fluid"
                alt="" />
            </div>
            <div class="instructor-info">
              <h5>Athalia Martha</h5>
              <p class="specialty">Trainer Digital Marketing</p>
              <div class="action-buttons">
                <div class="social-links">
                  <a href="https://www.instagram.com/athaliamartha/"><i class="bi bi-instagram"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div
          class="col-xl-4 col-lg-4 col-md-6 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="300">
          <div class="instructor-card">
            <div class="instructor-image">
              <img
                src="assets/img/trainer/feni.webp"
                class="img-fluid"
                alt="" />
            </div>
            <div class="instructor-info">
              <h5>Fenny Fitria Dewi</h5>
              <p class="specialty">Admin & Finance</p>
              <div class="action-buttons">
                <div class="social-links">
                  <a href="#"><i class="bi bi-instagram"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div
          class="col-xl-4 col-lg-4 col-md-6 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="350">
          <div class="instructor-card">
            <div class="instructor-image">
              <img
                src="assets/img/trainer/tommy.webp"
                class="img-fluid"
                alt="" />
            </div>
            <div class="instructor-info">
              <h5>Tomi Nugroho</h5>
              <p class="specialty">
                Senior Trainer Barista, Tea & Mixology
              </p>
              <div class="action-buttons">
                <div class="social-links">
                  <a href="https://www.instagram.com/skinnynug/"><i class="bi bi-instagram"></i></a>
                  <a href="https://id.linkedin.com/in/tomi-nugroho-b5412245"><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div
          class="col-xl-4 col-lg-4 col-md-6 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="350">
          <div class="instructor-card">
            <div class="instructor-image">
              <img
                src="assets/img/trainer/radit.webp"
                class="img-fluid"
                alt="" />
            </div>
            <div class="instructor-info">
              <h5>Radit Lesmana</h5>
              <p class="specialty">Trainer Barista</p>
              <div class="action-buttons">
                <div class="social-links">
                  <a href="https://www.instagram.com/lesmanaradit/"><i class="bi bi-instagram"></i></a>
                  <a href="#"><i class="bi bi-facebook"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div
          class="col-xl-4 col-lg-4 col-md-6 aos-init aos-animate"
          data-aos="fade-up"
          data-aos-delay="350">
          <div class="instructor-card">
            <div class="instructor-image">
              <img
                src="assets/img/trainer/ulum.webp"
                class="img-fluid"
                alt="" />
            </div>
            <div class="instructor-info">
              <h5>Ulum Novianti</h5>
              <p class="specialty">Trainer Food & Kitchen</p>
              <div class="action-buttons">
                <div class="social-links">
                  <a href="https://www.instagram.com/ulumnovianto/"><i class="bi bi-instagram"></i></a>
                  <a href="#"><i class="bi bi-facebook"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /About Section -->
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
          <strong class="px-1 sitename">Learner</strong>
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
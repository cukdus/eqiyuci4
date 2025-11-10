<?php
$this->setVar('pageTitle', 'Beranda | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
$this->setVar('metaDescription', 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.');
$this->setVar('metaKeywords', 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia');
$this->setVar('canonicalUrl', base_url());
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'index');

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>

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
              <a href="<?= base_url('kursus/private-class-beverage---business-culinary') ?>" class="btn btn-primary w-100">Lihat Detail</a>
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
              <a href="<?= base_url('kursus/basic-barista---latte-art') ?>" class="btn btn-primary w-100">Lihat Detail</a>
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
              <a href="<?= base_url('kursus/workshop-membangun-bisnis-cafe-kedai-kopi') ?>" class="btn btn-primary w-100">Lihat Detail</a>
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
                <?php
                // Helper: fetch SerpApi Google Maps reviews via file_get_contents
                $fetchSerpApi = static function (string $placeId): array {
                  $params = [
                    'api_key' => 'deb1a14aa5c97198b7be2c9d42ce9f0b218de58bbf81f3c02ceb6478995062a7',
                    'engine' => 'google_maps_reviews',
                    'hl' => 'id',
                    'place_id' => $placeId,
                    'sort_by' => 'newestFirst',
                  ];
                  $url = 'https://serpapi.com/search?' . http_build_query($params);
                  $context = stream_context_create([
                    'http' => [
                      'method' => 'GET',
                      'timeout' => 10,
                    ],
                    'ssl' => [
                      'verify_peer' => true,
                      'verify_peer_name' => true,
                    ],
                  ]);
                  $resp = @file_get_contents($url, false, $context);
                  if ($resp === false || $resp === null) {
                    return [];
                  }
                  $j = json_decode($resp, true);
                  if (is_array($j) && isset($j['reviews']) && is_array($j['reviews'])) {
                    return $j['reviews'];
                  }
                  return [];
                };

                // Helper: fetch raw SerpApi response (untuk meta rating & total reviews)
                $fetchSerpApiRaw = static function (string $placeId): ?array {
                  $params = [
                    'api_key' => 'deb1a14aa5c97198b7be2c9d42ce9f0b218de58bbf81f3c02ceb6478995062a7',
                    'engine' => 'google_maps_reviews',
                    'hl' => 'id',
                    'place_id' => $placeId,
                    'sort_by' => 'newestFirst',
                  ];
                  $url = 'https://serpapi.com/search?' . http_build_query($params);
                  $context = stream_context_create([
                    'http' => [
                      'method' => 'GET',
                      'timeout' => 10,
                    ],
                    'ssl' => [
                      'verify_peer' => true,
                      'verify_peer_name' => true,
                    ],
                  ]);
                  $resp = @file_get_contents($url, false, $context);
                  if ($resp === false || $resp === null) {
                    return null;
                  }
                  $j = json_decode($resp, true);
                  return is_array($j) ? $j : null;
                };

                // Cache: Ambil data sebulan sekali setiap tanggal 1, simpan di writable/cache
                $getSerpApiCached = static function (string $placeId, string $cacheKey) use ($fetchSerpApi, $fetchSerpApiRaw): array {
                  $cacheDir = WRITEPATH . 'cache';
                  if (!is_dir($cacheDir)) {
                    @mkdir($cacheDir, 0775, true);
                  }
                  $cacheFile = $cacheDir . '/serpapi_' . preg_replace('/[^a-z0-9_-]/i', '', $cacheKey) . '.json';
                  $todayDay = (int) date('j');

                  $cachedPayload = null;
                  if (is_file($cacheFile)) {
                    $str = @file_get_contents($cacheFile);
                    $cachedPayload = $str ? json_decode($str, true) : null;
                  }

                  // Refresh jika:
                  // - Tanggal 1 (pembaruan bulanan), atau
                  // - Belum ada cache, atau
                  // - Cache ada tetapi meta rating/total ulasan belum tersimpan
                  $missingMeta = false;
                  if (is_array($cachedPayload)) {
                    $hasRating = isset($cachedPayload['rating_overall']) && is_numeric($cachedPayload['rating_overall']);
                    $hasTotal = isset($cachedPayload['reviews_total']) && is_numeric($cachedPayload['reviews_total']);
                    $missingMeta = (!$hasRating || !$hasTotal);
                  }
                  $shouldRefresh = ($todayDay === 1) || ($cachedPayload === null) || $missingMeta;
                  if ($shouldRefresh) {
                    $raw = $fetchSerpApiRaw($placeId);
                    $reviews = (is_array($raw) && isset($raw['reviews']) && is_array($raw['reviews'])) ? $raw['reviews'] : [];
                    $ratingOverall = is_array($raw) && isset($raw['place_info']['rating']) ? (float) $raw['place_info']['rating'] : null;
                    $reviewsTotal = null;
                    if (is_array($raw)) {
                      if (isset($raw['place_info']['reviews_count'])) {
                        $reviewsTotal = (int) $raw['place_info']['reviews_count'];
                      } elseif (isset($raw['place_info']['user_ratings_total'])) {
                        $reviewsTotal = (int) $raw['place_info']['user_ratings_total'];
                      } elseif (isset($raw['place_info']['reviews'])) {
                        $reviewsTotal = (int) $raw['place_info']['reviews'];
                      }
                    }
                    // Jika SerpApi tidak mengembalikan array reviews namun meta tersedia,
                    // pertahankan reviews cache lama agar konten slider tidak kosong.
                    if (empty($reviews) && is_array($cachedPayload) && isset($cachedPayload['reviews']) && is_array($cachedPayload['reviews'])) {
                      $reviews = $cachedPayload['reviews'];
                    }

                    $payload = [
                      'updated_at' => date('c'),
                      'month' => date('Y-m'),
                      'reviews' => $reviews,
                      'rating_overall' => $ratingOverall,
                      'reviews_total' => $reviewsTotal,
                    ];
                    @file_put_contents($cacheFile, json_encode($payload), LOCK_EX);
                    return $reviews;
                  }

                  // Gunakan cache yang ada di hari selain tanggal 1
                  if (is_array($cachedPayload) && isset($cachedPayload['reviews']) && is_array($cachedPayload['reviews'])) {
                    return $cachedPayload['reviews'];
                  }
                  // Tidak ada cache, fallback fetch untuk menghindari tampilan kosong
                  return $fetchSerpApi($placeId);
                };

                // Helper: Ambil ringkasan rating & total ulasan dari cache
                $getSerpApiSummaryCached = static function (string $cacheKey, array $reviewsFallback = []): array {
                  $cacheDir = WRITEPATH . 'cache';
                  $cacheFile = $cacheDir . '/serpapi_' . preg_replace('/[^a-z0-9_-]/i', '', $cacheKey) . '.json';
                  $rating = null;
                  $total = null;
                  if (is_file($cacheFile)) {
                    $str = @file_get_contents($cacheFile);
                    $payload = $str ? json_decode($str, true) : null;
                    if (is_array($payload)) {
                      if (isset($payload['rating_overall']) && is_numeric($payload['rating_overall'])) {
                        $rating = (float) $payload['rating_overall'];
                      }
                      if (isset($payload['reviews_total']) && is_numeric($payload['reviews_total'])) {
                        $total = (int) $payload['reviews_total'];
                      } elseif (isset($payload['reviews']) && is_array($payload['reviews'])) {
                        $total = count($payload['reviews']);
                      }
                    }
                  }
                  // Fallback jika meta tidak tersedia: hitung dari reviews yang terambil
                  if ($rating === null && !empty($reviewsFallback)) {
                    $sum = 0.0;
                    $cnt = 0;
                    foreach ($reviewsFallback as $rv) {
                      if (isset($rv['rating']) && is_numeric($rv['rating'])) {
                        $sum += (float) $rv['rating'];
                        $cnt++;
                      }
                    }
                    if ($cnt > 0) {
                      $rating = $sum / $cnt;
                    }
                    $total = $total ?? $cnt;
                  }
                  return ['rating' => $rating, 'count' => $total];
                };

                // Malang place_id (dengan cache bulanan)
                $reviews = $getSerpApiCached('ChIJQ1F7EXQp1i0RNQxsi35vCqw', 'malang');
                $max = min(9, count($reviews));
                for ($i = 0; $i < $max; $i++) {
                  $r = $reviews[$i];
                  $rating = (int) round($r['rating'] ?? 0);
                  $snippet = '';
                  if (!empty($r['snippet'])) {
                    $snippet = $r['snippet'];
                  } elseif (!empty($r['extracted_snippet']['original'])) {
                    $snippet = $r['extracted_snippet']['original'];
                  }
                  $name = $r['user']['name'] ?? 'Anonim';
                  $thumb = $r['user']['thumbnail'] ?? base_url('assets/img/person/person-m-2.webp');
                  ?>
                    <div class="swiper-slide">
                      <div class="testimonial-item">
                        <div class="stars">
                          <?php for ($s = 1; $s <= 5; $s++): ?>
                            <?php if ($s <= $rating): ?>
                              <i class="bi bi-star-fill"></i>
                            <?php else: ?>
                              <i class="bi bi-star"></i>
                            <?php endif; ?>
                          <?php endfor; ?>
                        </div>
                        <p><?= esc($snippet ?: 'Tidak ada ulasan.') ?></p>
                        <div class="testimonial-profile">
                          <img src="<?= esc($thumb) ?>" alt="Reviewer" class="img-fluid rounded-circle" />
                          <div>
                            <h3><?= esc($name) ?></h3>
                            <h4>Google Maps Reviewer</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php }
                if ($max === 0): ?>
                  <div class="swiper-slide">
                    <div class="testimonial-item">
                      <div class="stars">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                      </div>
                      <p>Tidak dapat memuat review Google saat ini.</p>
                      <div class="testimonial-profile">
                        <img src="<?= base_url('assets/img/person/person-m-2.webp') ?>" alt="Reviewer" class="img-fluid rounded-circle" />
                        <div>
                          <h3>Review tidak tersedia</h3>
                          <h4>Google Maps</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
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
                <?php
                // Jogja place_id (dengan cache bulanan)
                $reviewsJogja = $getSerpApiCached('ChIJTQFF2vdXei4RNfmgEZwfxwY', 'jogja');
                $maxJ = min(9, count($reviewsJogja));
                for ($i = 0; $i < $maxJ; $i++) {
                  $r = $reviewsJogja[$i];
                  $rating = (int) round($r['rating'] ?? 0);
                  $snippet = '';
                  if (!empty($r['snippet'])) {
                    $snippet = $r['snippet'];
                  } elseif (!empty($r['extracted_snippet']['original'])) {
                    $snippet = $r['extracted_snippet']['original'];
                  }
                  $name = $r['user']['name'] ?? 'Anonim';
                  $thumb = $r['user']['thumbnail'] ?? base_url('assets/img/person/person-m-2.webp');
                  ?>
                    <div class="swiper-slide">
                      <div class="testimonial-item">
                        <div class="stars">
                          <?php for ($s = 1; $s <= 5; $s++): ?>
                            <?php if ($s <= $rating): ?>
                              <i class="bi bi-star-fill"></i>
                            <?php else: ?>
                              <i class="bi bi-star"></i>
                            <?php endif; ?>
                          <?php endfor; ?>
                        </div>
                        <p><?= esc($snippet ?: 'Tidak ada ulasan.') ?></p>
                        <div class="testimonial-profile">
                          <img src="<?= esc($thumb) ?>" alt="Reviewer" class="img-fluid rounded-circle" />
                          <div>
                            <h3><?= esc($name) ?></h3>
                            <h4>Google Maps Reviewer</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php }
                if ($maxJ === 0): ?>
                  <div class="swiper-slide">
                    <div class="testimonial-item">
                      <div class="stars">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                      </div>
                      <p>Tidak dapat memuat review Google saat ini.</p>
                      <div class="testimonial-profile">
                        <img src="<?= base_url('assets/img/person/person-m-2.webp') ?>" alt="Reviewer" class="img-fluid rounded-circle" />
                        <div>
                          <h3>Review tidak tersedia</h3>
                          <h4>Google Maps</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <?php
        // Ringkasan untuk Malang
        $summaryM = $getSerpApiSummaryCached('malang', $reviews);
        $ratingM = isset($summaryM['rating']) ? (float) $summaryM['rating'] : 0.0;
        $countM = isset($summaryM['count']) ? (int) $summaryM['count'] : count($reviews);

        // Ringkasan untuk Jogja
        $summaryJ = $getSerpApiSummaryCached('jogja', $reviewsJogja);
        $ratingJ = isset($summaryJ['rating']) ? (float) $summaryJ['rating'] : 0.0;
        $countJ = isset($summaryJ['count']) ? (int) $summaryJ['count'] : count($reviewsJogja);
        ?>
        <div class="col-6 text-center" data-aos="fade-up">
          <div class="overall-rating">
            <div class="rating-number"><?= number_format($ratingM, 1) ?></div>
            <div class="rating-stars">
              <?php for ($s = 1; $s <= 5; $s++): ?>
                <?php if ($ratingM >= $s): ?>
                  <i class="bi bi-star-fill"></i>
                <?php elseif ($ratingM >= ($s - 0.5)): ?>
                  <i class="bi bi-star-half"></i>
                <?php else: ?>
                  <i class="bi bi-star"></i>
                <?php endif; ?>
              <?php endfor; ?>
            </div>
            <p>Based on <?= esc($countM) ?> reviews</p>
            <div class="rating-platforms">
              <span>Review Gmaps Eqiyu Indonesia (Malang)</span>
            </div>
          </div>
        </div>
        <div class="col-6 text-center" data-aos="fade-up">
          <div class="overall-rating">
            <div class="rating-number"><?= number_format($ratingJ, 1) ?></div>
            <div class="rating-stars">
              <?php for ($s = 1; $s <= 5; $s++): ?>
                <?php if ($ratingJ >= $s): ?>
                  <i class="bi bi-star-fill"></i>
                <?php elseif ($ratingJ >= ($s - 0.5)): ?>
                  <i class="bi bi-star-half"></i>
                <?php else: ?>
                  <i class="bi bi-star"></i>
                <?php endif; ?>
              <?php endfor; ?>
            </div>
            <p>Based on <?= esc($countJ) ?> reviews</p>
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
<?= $this->endSection() ?>
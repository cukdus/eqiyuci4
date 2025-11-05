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
<?= $this->endSection() ?>
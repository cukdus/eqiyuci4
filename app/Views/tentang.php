<?php
$this->setVar('pageTitle', 'Tentang Kami | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
$this->setVar('metaDescription', 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.');
$this->setVar('metaKeywords', 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia');
$this->setVar('canonicalUrl', base_url());
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'tentang');

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>

<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Tentang Kami</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
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
            <div class="stats-row mb-4">
              <div class="stats-item">
                <span class="count">15+</span>
                <p>Tahun Pengalaman</p>
              </div>
              <div class="stats-item">
                <span class="count">11</span>
                <p>Kelas Reguler</p>
              </div>
              <div class="stats-item">
                <span class="count">20k+</span>
                <p>Peserta Didik</p>
              </div>
            </div>
            <p><strong>Bekerja sama dengan :</strong></p>
            <div class="stats-row mb-4">
              <div class="stats-item">
                <span class="count">200+</span>
                <p>Brand UMKM & Start Up</p>
              </div>
              <div class="stats-item">
                <span class="count">10</span>
                <p>Lembaga Pendidikan</p>
              </div>
              <div class="stats-item">
                <span class="count">5</span>
                <p>Perusahaan BUMN & Multinasional</p>
              </div>
            </div>
            <p><strong>output :</strong></p>
            <div class="stats-row">
              <div class="stats-item">
                <span class="count">300+</span>
                <p>Bisnis Baru</p>
              </div>
              <div class="stats-item">
                <span class="count">20+</span>
                <p>Brand Startup</p>
              </div>
              <div class="stats-item">
                <span class="count">6+</span>
                <p>Juara Kompetisi Bisnis & Kompetensi</p>
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
      <p>6 Man Power Expert F & B Departement Minimal 5 tahun. Semua Team adalah Ex. Trainer di Perusahaan Konsultan F&B dengan pengalaman Min 2 tahun.</p>
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
              <h5>Ulum Novianto</h5>
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
<?= $this->endSection() ?>
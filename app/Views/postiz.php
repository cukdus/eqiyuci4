<?php
$this->setVar('pageTitle', 'Postiz | Kelola Sosial Media Anda');
$this->setVar('metaDescription', 'Postiz membantu menjadwalkan konten dan mengelola sosial media dengan aman dan efisien.');
$this->setVar('metaKeywords', 'postiz, sosial media, jadwal konten, tiktok, instagram, facebook');
$this->setVar('canonicalUrl', base_url('postiz'));
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'index');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>
<main class="main">

  <section class="courses-hero section light-background">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
          <h1 class="mb-3">Kelola Sosial Media Anda dengan Postiz</h1>
          <p class="mb-4">Tools untuk menjadwalkan, mempublikasikan, dan mengelola konten lintas platform secara mudah.</p>
          <div class="hero-buttons">
            <a href="https://posting.eqiyu.id/" class="btn btn-primary">Mulai Sekarang</a>
          </div>
            <div class="mt-4">
                <a href="<?= base_url('terms') ?>" class="me-3">Terms</a>
                <a href="<?= base_url('privacy') ?>">Privacy</a>
            </div>
        </div>
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
          <div class="hero-image">
              <div class="main-image">
                <img src="assets/img/education/courses-13.webp" alt="Online Learning" class="img-fluid" loading="lazy">
              </div>
            </div>
        </div>
      </div>
    </div>
  </section>

  <section id="about" class="about section light-background">
    <div class="container section-title aos-init aos-animate" data-aos="fade-up">
      <h2>Feature</h2>
    </div>
    <div class="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4">
        <div class="col-lg-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-award"></i>
            </div>
            <h3>Jadwal otomatis</h3>
            <p>
              Atur kalender konten dan publikasi otomatis sesuai jadwal yang Anda tentukan.
            </p>
          </div>
        </div>
        <div class="col-lg-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-file-earmark-text"></i>
            </div>
            <h3>Workflow aman & efisien</h3>
            <p>
              Kelola semua proses publikasi dengan mudah dan efisien,
              dari pengaturan konten hingga pemantauan performa.
            </p>
          </div>
        </div>
        <div class="col-lg-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-journal-text"></i>
            </div>
            <h3>Integrasi Social Media</h3>
            <p>
              Integrasikan konten Anda ke berbagai platform sosial media
              seperti TikTok, Instagram, dan Facebook dengan mudah.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
<?= $this->endSection() ?>

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

  <section class="hero section light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <div>
        <h1 class="mb-3">Kelola Sosial Media Anda dengan Postiz</h1>
        <p class="mb-4">Tools untuk menjadwalkan, mempublikasikan, dan mengelola konten lintas platform secara mudah.</p>
        <a href="https://posting.eqiyu.id/" class="btn btn-primary">Mulai Sekarang</a>
      </div>
    </div>
  </section>

  <section id="features" class="section">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="feature-box p-4 border rounded h-100">
            <div class="icon mb-2"><i class="bi bi-calendar-check"></i></div>
            <h3 class="h5">Jadwal otomatis</h3>
            <p>Atur kalender konten dan publikasi otomatis sesuai jadwal yang Anda tentukan.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-box p-4 border rounded h-100">
            <div class="icon mb-2"><i class="bi bi-share"></i></div>
            <h3 class="h5">Integrasi TikTok, Instagram, Facebook</h3>
            <p>Kelola dan distribusikan konten ke beberapa platform sekaligus dari satu tempat.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-box p-4 border rounded h-100">
            <div class="icon mb-2"><i class="bi bi-shield-check"></i></div>
            <h3 class="h5">Workflow aman & efisien</h3>
            <p>Proses terstruktur dengan kontrol akses untuk menjaga keamanan dan produktivitas tim.</p>
          </div>
        </div>
      </div>
      <div class="mt-4">
        <a href="<?= base_url('terms') ?>" class="me-3">Terms</a>
        <a href="<?= base_url('privacy') ?>">Privacy</a>
      </div>
    </div>
  </section>

</main>
<?= $this->endSection() ?>

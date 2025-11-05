<?php
$this->setVar('pageTitle', 'Bonus Modul | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
$this->setVar('metaDescription', 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.');
$this->setVar('metaKeywords', 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia');
$this->setVar('canonicalUrl', base_url());
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'bonus');

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>
<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Bonus Modul</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
          <li class="current">Bonus</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <!-- Starter Section Section -->
  <section id="starter-section" class="starter-section section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Softmodul Eksklusif</h2>
      <p>
        Sebagai peserta program pelatihan ini, Anda akan mendapatkan akses
        ke BONUS MODUL khusus yang dirancang untuk memperkuat aspek personal
        dan profesional Anda di dunia hospitality, F&B, dan wirausaha. Modul
        tambahan ini tidak hanya mengajarkan teknik, tetapi juga membentuk
        mentalitas dan kebiasaan sukses yang akan membantu Anda bertahan dan
        bersinar di industri yang kompetitif ini.
      </p>
    </div>
    <!-- End Section Title -->

    <div class="container" data-aos="fade-up">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card shadow-lg">
            <div class="card-body p-4">
              <h5 class="card-title text-center mb-4">
                Masukkan Nomor Sertifikat
              </h5>
              <form action="" method="POST" class="certificate-form">
                <div class="input-group mb-3">
                  <input
                    type="text"
                    class="form-control form-control-lg"
                    placeholder="Contoh: EQxxxx"
                    aria-label="Nomor Sertifikat"
                    name="certificate_number"
                    required="" />
                  <button class="btn btn-primary" type="submit">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                  </button>
                </div>
                <div class="text-center text-muted">
                  <small>Masukkan nomor sertifikat untuk melihat Softmodul
                    Eksklusif</small>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /Starter Section Section -->
</main>
<?= $this->endSection() ?>
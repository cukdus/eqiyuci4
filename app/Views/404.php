<?php
$this->setVar('pageTitle', 'Hayolo... mau ngapain ini? | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
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

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">404</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">404</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Error 404 Section -->
    <section id="error-404" class="error-404 section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="error-wrapper">
          <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
              <div class="error-illustration">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
                <div class="circle circle-3"></div>
              </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
              <div class="error-content">
                <span class="error-badge" data-aos="zoom-in" data-aos-delay="400">Error</span>
                <h1 class="error-code" data-aos="fade-up" data-aos-delay="500">404</h1>
                <h2 class="error-title" data-aos="fade-up" data-aos-delay="600">Page Not Found</h2>
                <p class="error-description" data-aos="fade-up" data-aos-delay="700">
                  The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                </p>

                <div class="error-actions" data-aos="fade-up" data-aos-delay="800">
                  <a href="/" class="btn-home">
                    <i class="bi bi-house-door"></i> Back to Home
                  </a>
                  <a href="#" class="btn-help">
                    <i class="bi bi-question-circle"></i> Help Center
                  </a>
                </div>

                <div class="error-suggestions" data-aos="fade-up" data-aos-delay="900">
                  <h3>You might want to:</h3>
                  <ul>
                    <li><a href="#"><i class="bi bi-arrow-right-circle"></i> Check our sitemap</a></li>
                    <li><a href="#"><i class="bi bi-arrow-right-circle"></i> Contact support</a></li>
                    <li><a href="#"><i class="bi bi-arrow-right-circle"></i> Return to previous page</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Error 404 Section -->

  </main>
<?= $this->endSection() ?>

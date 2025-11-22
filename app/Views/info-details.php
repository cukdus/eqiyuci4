<?php
$this->setVar('pageTitle', 'Detail Artikel & Informasi | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
$this->setVar('metaDescription', 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.');
$this->setVar('metaKeywords', 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia');
$this->setVar('canonicalUrl', base_url());
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'info');

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
// Format tanggal Indonesia untuk tampilan meta
$bulanIndo = [
  '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
  '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
  '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
$tglTerbit = isset($artikel['tanggal_terbit']) ? (string) $artikel['tanggal_terbit'] : date('Y-m-d');
$day = date('d', strtotime($tglTerbit));
$monthNum = date('m', strtotime($tglTerbit));
$monthName = $bulanIndo[$monthNum] ?? date('F', strtotime($tglTerbit));
$year = date('Y', strtotime($tglTerbit));
$penulis = isset($artikel['penulis']) ? (string) $artikel['penulis'] : 'Anonim';
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>
<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Detail Artikel</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
          <li class="current">Detail Artikel</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <!-- Blog Details Section -->
  <section id="blog-details" class="blog-details section">
    <div class="container" data-aos="fade-up">
      <article class="article">
        <div class="article-header">
          <div class="meta-categories" data-aos="fade-up">
            <a href="#" class="category"><?= esc($artikel['kategori_nama'] ?? 'Uncategorized') ?></a>
          </div>

          <h1 class="title" data-aos="fade-up" data-aos-delay="100">
            <?= esc($artikel['judul'] ?? 'Detail Artikel') ?>
          </h1>

          <div class="article-meta" data-aos="fade-up" data-aos-delay="200">
            <div class="post-info">
              <span><i class="bi bi-calendar4-week"></i> <?= esc($day) ?> <?= esc($monthName) ?> <?= esc($year) ?></span>
              <span><i class="bi bi-person"></i> <?= esc($penulis) ?></span>
            </div>
          </div>
        </div>

        <div class="article-featured-image" data-aos="zoom-in">
          <img
            src="<?= esc($imgUrl ?? base_url('assets/img/blog/blog-hero-1.webp')) ?>"
            alt="<?= esc($artikel['judul'] ?? 'Artikel') ?>"
            class="img-fluid" />
        </div>

        <div class="article-wrapper">
          <div class="article-content" data-aos="fade-up">
            <?= isset($artikel['konten']) ? $artikel['konten'] : '' ?>
            <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="mt-4">
            <a href="<?= base_url('info') ?>" class="btn btn-sm btn-secondary d-inline-flex align-items-center">
              <i class="bi bi-arrow-left me-2"></i>
              <span>Kembali</span>
            </a>
          </div>
        </div>
          </div>
        </div>
        <!-- Back Button: placed under article content, aligned with theme -->
        

        <div class="article-footer" data-aos="fade-up">

          <div class="article-tags">
            <h4>Related Topics</h4>
            <div class="tags">
              <?php if (!empty($tags) && is_array($tags)): ?>
                <?php foreach ($tags as $t): ?>
                  <?php $label = ucwords($t); ?>
                  <a href="<?= base_url('info?tag=' . urlencode($t)) ?>" class="tag"><?= esc($label) ?></a>
                <?php endforeach; ?>
              <?php else: ?>
                <span class="text-muted">Tidak ada tag terkait.</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </article>
    </div>
  </section>
  <!-- /Blog Details Section -->
</main>
<?= $this->endSection() ?>

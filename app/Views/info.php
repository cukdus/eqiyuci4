<?php
$this->setVar('pageTitle', 'Artikel & Informasi | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
$this->setVar('metaDescription', 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.');
$this->setVar('metaKeywords', 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia');
$this->setVar('canonicalUrl', base_url());
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'info');

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>

<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Informasi & Artikel</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
          <li class="current">Info</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <?php if (!empty($currentTag)): ?>
    <div class="container" data-aos="fade-up" data-aos-delay="50">
      <p class="text-black-50 small mb-3">
        Menampilkan artikel dengan tag:
        <span class="badge bg-secondary text-light"><?= esc(ucwords($currentTag)) ?></span>
        <a href="<?= base_url('info') ?>" class="ms-2">Reset</a>
      </p>
    </div>
  <?php endif; ?>

  <!-- Blog Posts Section -->
  <section id="blog-posts" class="blog-posts section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4">
        <?php if (!empty($berita) && is_array($berita)): ?>
          <?php 
            $bulanNama = [
              '01' => 'Januari','02' => 'Februari','03' => 'Maret','04' => 'April',
              '05' => 'Mei','06' => 'Juni','07' => 'Juli','08' => 'Agustus',
              '09' => 'September','10' => 'Oktober','11' => 'November','12' => 'Desember'
            ];
          ?>
          <?php foreach ($berita as $item): ?>
            <?php 
              $tanggal = $item['tanggal_terbit'] ?? date('Y-m-d');
              $day = date('d', strtotime($tanggal));
              $monthNum = date('m', strtotime($tanggal));
              $monthName = $bulanNama[$monthNum] ?? date('F', strtotime($tanggal));
              $penulis = $item['penulis'] ?? 'Anonim';
              $kategori = $item['kategori_nama'] ?? 'Uncategorized';
              $judul = $item['judul'] ?? '';
              $slug = $item['slug'] ?? '';
              $img = !empty($item['gambar_utama']) ? base_url('uploads/artikel/'.$item['gambar_utama']) : base_url('assets/img/blog/blog-post-1.webp');
              $detailUrl = !empty($slug) ? base_url('info/'.$slug) : base_url('info');
            ?>
            <div class="col-lg-4">
              <article class="position-relative h-100">
                <div class="post-img position-relative overflow-hidden">
                  <img src="<?= esc($img) ?>" class="img-fluid" alt="<?= esc($judul) ?>" />
                </div>

                <div class="meta d-flex align-items-end">
                  <span class="post-date"><span><?= esc($day) ?></span><?= esc($monthName) ?></span>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-person"></i>
                    <span class="ps-2"><?= esc($penulis) ?></span>
                  </div>
                  <span class="px-3 text-black-50">/</span>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-folder2"></i>
                    <span class="ps-2"><?= esc($kategori) ?></span>
                  </div>
                </div>

                <div class="post-content d-flex flex-column">
                  <h3 class="post-title"><?= esc($judul) ?></h3>
                  <a href="<?= esc($detailUrl) ?>" class="readmore stretched-link"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
                </div>
              </article>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12">
            <p class="text-center text-muted">
              <?= !empty($currentTag) ? 'Tidak ada artikel untuk tag ini.' : 'Belum ada berita.' ?>
            </p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <!-- /Blog Posts Section -->

  <!-- Pagination 2 Section -->
  <?php if (!empty($berita) && is_array($berita) && count($berita) > 0): ?>
    <?= isset($pager) ? $pager->links('default', 'front_pagination_2') : '' ?>
  <?php endif; ?>
  <!-- /Pagination 2 Section -->
</main>
<?= $this->endSection() ?>
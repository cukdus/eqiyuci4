<?php
$pageTitle = 'Cek Sertifikat | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending,
      Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.';
$metaDescription = 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.';
$metaKeywords = 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia';
$canonicalUrl = 'https://eqiyu.id/';
$bodyClass = 'index-page';
$activePage = 'sertifikat';
include __DIR__ . '/includes/header.php';
?>
<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Cek Sertifikat</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Beranda</a></li>
          <li class="current">Sertifikat</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <!-- Starter Section Section -->
  <section id="starter-section" class="starter-section section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Check Sertifikat</h2>
      <p>
        Anda bisa mencari data keaslian sertifikat dari penomoran sertifikat
        yang dikeluarkan secara resmi oleh EQIYU Indonesia. Jika tidak
        terdaftar dalam database EQIYU, maka keaslian dokumen sertifikat
        tersebut adalah PALSU. Untuk peserta kelas sebelum tahun 2025 dapat
        mengunduh kembali e-sertifikat melalui nomor sertifikat yang
        didapatkan melalui WhatsApp Admin.
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
                    <i class="bi bi-search me-2"></i>Cari
                  </button>
                </div>
                <div class="text-center text-muted">
                  <small>Masukkan nomor sertifikat yang tertera pada sertifikat
                    Anda untuk validasi keasliannya</small>
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
<?php include __DIR__ . '/includes/footer.php'; ?>
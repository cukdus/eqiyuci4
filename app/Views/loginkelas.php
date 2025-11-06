<?php
$this->setVar('pageTitle', 'Beranda | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
$this->setVar('metaDescription', 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.');
$this->setVar('metaKeywords', 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia');
$this->setVar('canonicalUrl', base_url());
$this->setVar('bodyClass', 'index-page');

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>
<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Kelas Online</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
          <li class="current">Kelas Online</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <!-- Starter Section Section -->
  <section id="starter-section" class="starter-section section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Kelas Online</h2>
    </div>
    <!-- End Section Title -->

    <div class="container" data-aos="fade-up">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card shadow-lg">
            <div class="card-body p-4">
              <h5 class="card-title text-center mb-4">
                Masukkan Nomor Telepon
              </h5>
              <form action="" method="POST" class="certificate-form">
                <div class="input-group mb-3">
                  <input
                    type="text"
                    class="form-control form-control-lg"
                    placeholder="Contoh: 08xxxx"
                    aria-label="Nomor Telepon"
                    name="phone_number"
                    required="" />
                  <button class="btn btn-primary" type="submit">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                  </button>
                </div>
                <div class="text-center text-muted">
                  <small>Masukkan nomor telepon yang terdaftar untuk melihat akses Kelas Online</small>
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
<script>
  (function() {
    const form = document.querySelector('.certificate-form');
    if (!form) return;
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      const input = form.querySelector('input[name="phone_number"]');
      const phone = (input?.value || '').trim();
      if (!phone) {
        alert('Nomor telepon wajib diisi');
        return;
      }
      try {
        const url = '<?= site_url('api/kelasonline/login') ?>' + '?phone=' + encodeURIComponent(phone);
        const res = await fetch(url, { method: 'GET', headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        if (data && data.ok) {
          // Session has been set server-side; redirect to kelasonline
          window.location.href = '<?= site_url('kelasonline') ?>';
        } else {
          alert((data && data.message) ? data.message : 'Login gagal. Pastikan nomor telepon terdaftar dan akses aktif.');
        }
      } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan jaringan saat login.');
      }
    });
  })();
</script>
<?= $this->endSection() ?>
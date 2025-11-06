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
              <form action="" method="POST" class="certificate-form" id="certificate-form">
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
              <div id="js-bonus-result" class="mt-3"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /Starter Section Section -->

  <?php if (!empty($message_success)): ?>
    <div class="container" data-aos="fade-up">
      <div class="alert alert-success" role="alert">
        <?= $escape($message_success) ?>
      </div>
    </div>
  <?php endif; ?>
  <?php if (!empty($message_error)): ?>
    <div class="container" data-aos="fade-up">
      <div class="alert alert-danger" role="alert">
        <?= $escape($message_error) ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if (!empty($bonusFiles) && is_array($bonusFiles)): ?>
    <section class="section" id="bonus-content">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Bonus Kelas</h2>
          <?php if (!empty($cert_data)): ?>
            <p>
            Kelas: <strong><?= $escape($cert_data['nama_kelas'] ?? '') ?></strong>
            </p>
          <?php endif; ?>
        </div>

        <div class="accordion" id="bonusAccordion">
          <?php foreach ($bonusFiles as $idx => $b): ?>
            <?php
            $collapseId = 'bonusItem' . ($idx + 1);
            $isFirst = ($idx === 0);
            $judul = (string) ($b['nama_file'] ?? 'Bonus');
            $deskripsi = (string) ($b['deskripsi'] ?? '');
            $path = (string) ($b['path_file'] ?? '');
            ?>
            <div class="accordion-item curriculum-module">
              <h2 class="accordion-header">
                <button
                  class="accordion-button<?= $isFirst ? '' : ' collapsed' ?>"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#<?= $escape($collapseId) ?>">
                  <div class="module-info">
                    <span class="module-title"><?= $escape($judul) ?></span>
                    <?php if ($deskripsi !== ''): ?>
                      <span class="module-meta"><?= $escape($deskripsi) ?></span>
                    <?php endif; ?>
                  </div>
                </button>
              </h2>
              <div
                id="<?= $escape($collapseId) ?>"
                class="accordion-collapse collapse<?= $isFirst ? ' show' : '' ?>"
                data-bs-parent="#bonusAccordion">
                <div class="accordion-body">
                  <div class="d-flex align-items-center justify-content-between">
                    <div>
                      <i class="bi bi-file-earmark-text me-2"></i>
                      <span><?= $escape($deskripsi !== '' ? $deskripsi : 'File bonus untuk kelas ini.') ?></span>
                    </div>
                    <?php if ($path !== ''): ?>
                      <a class="btn btn-sm btn-outline-primary" href="<?= base_url($path) ?>" target="_blank" rel="noopener">
                        <i class="bi bi-cloud-arrow-down me-1"></i> Download
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>
</main>
<script>
  (function(){
    const form = document.getElementById('certificate-form');
    const resultEl = document.getElementById('js-bonus-result');
    if (!form || !resultEl) return;

    form.addEventListener('submit', async function(e){
      e.preventDefault();
      const btn = form.querySelector('button[type="submit"]');
      const input = form.querySelector('input[name="certificate_number"]');
      const number = (input?.value || '').trim();
      if (!number) {
        resultEl.innerHTML = '<div class="alert alert-danger"><h6 class="alert-heading">Pengecekan Gagal <i class="bi bi-exclamation-triangle-fill ms-2"></i></h6><p class="mb-0">Nomor sertifikat wajib diisi.</p></div>';
        return;
      }
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mencari...';
      }
      resultEl.innerHTML = '';

      try {
        const url = '<?= site_url('api/bonus') ?>' + '?certificate_number=' + encodeURIComponent(number);
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();

        if (!data.ok) {
          resultEl.innerHTML = '<div class="alert alert-danger"><h6 class="alert-heading">Pengecekan Gagal <i class="bi bi-exclamation-triangle-fill ms-2"></i></h6><p class="mb-0">' + (data.message || 'Sertifikat tidak ditemukan atau tidak valid.') + '</p></div>';
          return;
        }

        const d = data.data || {};
        const files = Array.isArray(d.files) ? d.files : [];

        if (files.length === 0) {
          resultEl.innerHTML = '<div class="alert alert-info"><h6 class="alert-heading">Tidak Ada Bonus Kelas <i class="bi bi-info-circle-fill ms-2"></i></h6><p class="mb-0">Tidak ada bonus yang tersedia untuk kelas ini.</p></div>';
          return;
        }

        // Build accordion HTML mirip Curriculum di oncourse.php
        let html = `
          <div class="alert alert-success">
            <h6 class="alert-heading">Bonus Kelas Tersedia<i class="bi bi-check-circle-fill ms-2"></i></h6>
            <p class="mb-0">Kelas: <strong>${d.nama_kelas || ''}</strong></p>
          </div>
          <div class="accordion" id="bonusAccordion">`;

        files.forEach((b, idx) => {
          const collapseId = 'bonusItem' + (idx + 1);
          const isFirst = idx === 0;
          const judul = (b.nama_file && b.nama_file.trim() !== '') ? b.nama_file : 'Bonus';
          const deskripsi = b.deskripsi || '';
          const path = b.path_file || '';
          const orderNum = (b.urutan !== undefined && b.urutan !== null && !isNaN(parseInt(b.urutan)))
            ? parseInt(b.urutan)
            : (idx + 1);
          html += `
            <div class="accordion-item curriculum-module">
              <h2 class="accordion-header">
                <button class="accordion-button${isFirst ? '' : ' collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                  <div class="module-info">
                    <span class="module-title">Bonus Modul ${orderNum}</span>
                  </div>
                </button>
              </h2>
              <div id="${collapseId}" class="accordion-collapse collapse${isFirst ? ' show' : ''}" data-bs-parent="#bonusAccordion">
                <div class="accordion-body">
                  <div class="d-flex align-items-center justify-content-between">
                    <div>
                      <i class="bi bi-file-earmark-text me-2"></i>
                      <span>${judul}</span>
                    </div>
                    ${path ? `<a class="btn btn-sm btn-outline-primary" href="<?= base_url() ?>/${path}" target="_blank" rel="noopener"><i class="bi bi-cloud-arrow-down me-1"></i> Download</a>` : ''}
                  </div>
                </div>
              </div>
            </div>`;
        });

        html += '</div>';
        resultEl.innerHTML = html;
      } catch (err) {
        resultEl.innerHTML = '<div class="alert alert-danger"><h6 class="alert-heading">Pengecekan Gagal</h6><p class="mb-0">Terjadi kesalahan jaringan atau server. Coba lagi.</p></div>';
      } finally {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i>Masuk';
        }
      }
    });
  })();
</script>
<?= $this->endSection() ?>
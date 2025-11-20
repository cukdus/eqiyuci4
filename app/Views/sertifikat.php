<?php
$this->setVar('pageTitle', 'Check Sertifikat | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
$this->setVar('metaDescription', 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.');
$this->setVar('metaKeywords', 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia');
$this->setVar('canonicalUrl', base_url());
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'sertifikat');

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>
<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Cek Sertifikat</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
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
              <form action="<?= site_url('sertifikat') ?>" method="POST" class="certificate-form" id="certificate-form">
                <div class="input-group mb-3">
                  <input
                    type="text"
                    class="form-control form-control-lg"
                    placeholder="Contoh: EQxxxx"
                    aria-label="Nomor Sertifikat"
                    name="certificate_number"
                    value="<?= esc($_POST['certificate_number'] ?? '') ?>"
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

              <div id="js-result" class="mt-4"></div>

              <?php if (!empty($message_success)): ?>
                <div class="mt-4">
                  <div class="alert alert-success">
                    <h6 class="alert-heading">Sertifikat Valid!</h6>
                    <p class="mb-0"><?= esc($message_success) ?></p>
                  </div>
                </div>
              <?php endif; ?>

              <?php if (!empty($message_error)): ?>
                <div class="mt-4">
                  <div class="alert alert-danger">
                    <h6 class="alert-heading">Nomor Sertifikat Tidak Valid</h6>
                    <p class="mb-0"><?= esc($message_error) ?><i class="bi bi-exclamation-triangle-fill ms-2"></i></p>
                  </div>
                </div>
              <?php endif; ?>

              <?php if (!empty($cert_data) && is_array($cert_data)): ?>
                <div class="mt-4">
                  <div class="certificate-details mt-4">
                    <h6>Detail Sertifikat:</h6>
                    <table class="table table-bordered">
                      <tr>
                        <th>No. Sertifikat</th>
                        <td><?= esc($cert_data['nomor_sertifikat'] ?? '') ?></td>
                      </tr>
                      <tr>
                        <th>Nama</th>
                        <td><?= esc($cert_data['nama_pemilik'] ?? '') ?></td>
                      </tr>
                      <tr>
                        <th>Kursus</th>
                        <td><?= esc($cert_data['nama_kelas'] ?? '') ?></td>
                      </tr>
                      <tr>
                        <th>Tgl. Terbit</th>
                        <td><?= !empty($cert_data['tanggal_terbit']) ? date('d F Y', strtotime($cert_data['tanggal_terbit'])) : '' ?></td>
                      </tr>
                    </table>

                    <div class="text-center mt-4">
                      <a href="<?= base_url('lihatsertifikat') ?>?nomor=<?= urlencode($cert_data['nomor_sertifikat'] ?? '') ?>" class="btn btn-primary js-download">
                        <i class="bi bi-download"></i> Download E-Sertifikat
                      </a>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
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
    const form = document.getElementById('certificate-form');
    const resultEl = document.getElementById('js-result');
    if (!form || !resultEl) return;

    form.addEventListener('submit', async function(e) {
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
        const url = '<?= site_url('api/sertifikat') ?>' + '?certificate_number=' + encodeURIComponent(number);
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();

        if (!data.ok) {
          resultEl.innerHTML = '<div class="alert alert-danger"><h6 class="alert-heading">Pengecekan Gagal <i class="bi bi-exclamation-triangle-fill ms-2"></i></h6><p class="mb-0">' + (data.message || 'Sertifikat tidak ditemukan atau tidak valid.') + '</p></div>';
          return;
        }

        const d = data.data || {};
        const tanggal = d.tanggal_terbit ? new Date(d.tanggal_terbit) : null;
        const dateStr = tanggal ? tanggal.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '';

        resultEl.innerHTML = `
          <div class="alert alert-success">
            <h6 class="alert-heading">Sertifikat Valid<i class="bi bi-check-circle-fill ms-2"></i></h6>
            <p class="mb-0">Sertifikat ini terdaftar secara resmi di EQIYU Indonesia.</p>
          </div>
          <div class="certificate-details mt-3">
            <h6>Detail Sertifikat:</h6>
            <table class="table table-bordered">
              <tr><th>No. Sertifikat</th><td>${d.nomor_sertifikat || ''}</td></tr>
              <tr><th>Nama Peserta</th><td>${d.nama_pemilik || ''}</td></tr>
              <tr><th>Program Pelatihan</th><td>${d.nama_kelas || ''}</td></tr>
              <tr><th>Kota Kelas</th><td>${d.kota_kelas || ''}</td></tr>
              <tr><th>Tanggal Terbit</th><td>${dateStr}</td></tr>
              <tr><th>Status</th><td>${d.status || ''}</td></tr>
            </table>
            <div class="text-center mt-3">
              <a href="<?= base_url('lihatsertifikat') ?>?nomor=` + encodeURIComponent(d.nomor_sertifikat || '') + `" class="btn btn-primary js-download">
                <i class="bi bi-download"></i> Download E-Sertifikat
              </a>
            </div>
          </div>
        `;
      } catch (err) {
        resultEl.innerHTML = '<div class="alert alert-danger"><h6 class="alert-heading">Pengecekan Gagal</h6><p class="mb-0">Terjadi kesalahan jaringan atau server. Coba lagi.</p></div>';
      } finally {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = '<i class="bi bi-search me-2"></i>Cari';
        }
      }
    });
  })();
</script>
<script>
  (function(){
    function createOverlay(){
      const overlay = document.createElement('div');
      overlay.id = 'download-overlay';
      overlay.style.position = 'fixed';
      overlay.style.top = '0';
      overlay.style.left = '0';
      overlay.style.right = '0';
      overlay.style.bottom = '0';
      overlay.style.background = 'rgba(0,0,0,0.4)';
      overlay.style.zIndex = '1050';
      overlay.style.display = 'flex';
      overlay.style.alignItems = 'center';
      overlay.style.justifyContent = 'center';
      const box = document.createElement('div');
      box.style.background = '#fff';
      box.style.padding = '16px 20px';
      box.style.borderRadius = '8px';
      box.style.boxShadow = '0 10px 30px rgba(0,0,0,0.2)';
      const spinner = document.createElement('span');
      spinner.className = 'spinner-border spinner-border-sm me-2';
      spinner.setAttribute('role', 'status');
      spinner.setAttribute('aria-hidden', 'true');
      const text = document.createElement('span');
      text.textContent = 'Menyiapkan unduhan sertifikat...';
      box.appendChild(spinner);
      box.appendChild(text);
      overlay.appendChild(box);
      return overlay;
    }

    document.addEventListener('click', async function(e){
      const link = e.target.closest('a.js-download');
      if (!link) return;
      e.preventDefault();
      const originalHtml = link.innerHTML;
      link.disabled = true;
      link.classList.add('disabled');
      link.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengunduh...';
      const overlay = createOverlay();
      document.body.appendChild(overlay);
      try {
        const res = await fetch(link.href, { headers: { 'Accept': 'application/pdf' } });
        if (!res.ok) throw new Error('Gagal mengunduh');
        const blob = await res.blob();
        const url = window.URL.createObjectURL(blob);
        const disposition = res.headers.get('Content-Disposition') || '';
        let filename = 'sertifikat.pdf';
        const match = /filename="?([^";]+)"?/i.exec(disposition);
        if (match && match[1]) filename = match[1];
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        setTimeout(function(){ window.URL.revokeObjectURL(url); }, 1000);
      } catch (err) {
        alert('Unduhan gagal. Silakan coba lagi.');
      } finally {
        if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
        link.disabled = false;
        link.classList.remove('disabled');
        link.innerHTML = originalHtml;
      }
    });
  })();
</script>
<?= $this->endSection() ?>

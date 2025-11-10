<?php
$this->setVar('pageTitle', 'Kelas Online | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
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
      <h1 class="mb-2 mb-lg-0">Modul Online</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
          <li class="current">Modul Online</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <!-- Starter Section dihapus: akses dilakukan via halaman Login Kelas -->

  <!-- Course Details Section -->
  <section id="course-details" class="course-details section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row">
        <div class="col-lg-12">
          <!-- Course Hero -->
          <div class="course-hero" data-aos="fade-up" data-aos-delay="200">
            <div class="hero-content">
              <h1><?= esc(($kelas['nama_kelas'] ?? 'Modul Online')) ?></h1>
              <!-- <p class="course-subtitle"></p> -->

              <!-- <div class="instructor-card">
                    <div class="instructor-details">
                      <h5>1900 Alumni</h5>
                    </div>
                    <button class="btn-primary d-md-none">
                      Daftar Sekarang
                    </button>
                  </div>-->
            </div>
          </div>
          <!-- End Course Hero -->

          <!-- Course Navigation Tabs -->
          <div
            class="course-nav-tabs"
            data-aos="fade-up"
            data-aos-delay="300">
            <ul
              class="nav nav-tabs"
              id="course-detailsCourseTab"
              role="tablist">
              <li class="nav-item">
                <button
                  class="nav-link active"
                  id="course-detailsoverview-tab"
                  data-bs-toggle="tab"
                  data-bs-target="#course-detailsoverview"
                  type="button"
                  role="tab">
                  <i class="bi bi-layout-text-window-reverse"></i>
                  Ringkasan
                </button>
              </li>
              <li class="nav-item">
                <button
                  class="nav-link"
                  id="course-detailscurriculum-tab"
                  data-bs-toggle="tab"
                  data-bs-target="#course-detailscurriculum"
                  type="button"
                  role="tab">
                  <i class="bi bi-list-ul"></i>
                  Materi
                </button>
              </li>
              <li class="nav-item">
                <a
                  href="<?= site_url('kelasonline/logout') ?>"
                  class="nav-link"
                  id="course-detailsreviews-tab"
                  role="button">
                  <i class="bi bi-box-arrow-right"></i>
                  Keluar
                </a>
              </li>
            </ul>

            <div class="tab-content" id="course-detailsCourseTabContent">
              <!-- Overview Tab -->
              <div
                class="tab-pane fade show active"
                id="course-detailsoverview"
                role="tabpanel">
                <div class="skills-grid">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="skill-item">
                        <div class="skill-icon">
                          <i class="bi bi-code-slash"></i>
                        </div>
                        <div class="skill-content">
                          <p>
                            <?= esc(($kelas['deskripsi_singkat'] ?? $kelas['deskripsi'] ?? 'Deskripsi kelas belum tersedia.')) ?>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                
              </div>
              <!-- End Overview Tab -->

              <!-- Curriculum Tab -->
              <div
                class="tab-pane fade"
                id="course-detailscurriculum"
                role="tabpanel">
                <div class="curriculum-overview">
                  <div class="curriculum-stats">
                    <div class="stat">
                      <i class="bi bi-collection-play"></i>
                      <span>0 Sections</span>
                    </div>
                    <div class="stat">
                      <i class="bi bi-play-circle"></i>
                      <span>0 video</span>
                    </div>
                    <div class="stat">
                      <i class="bi bi-clock"></i>
                      <span>0 file</span>
                    </div>
                  </div>
                </div>

                <div class="accordion" id="curriculumAccordion">
                  <div class="accordion-item curriculum-module">
                    <h2 class="accordion-header">
                      <button
                        class="accordion-button"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#module1">
                        <div class="module-info">
                          <span class="module-title">Pengenalan Diri Sendiri</span>
                          <span class="module-meta">Sebelum Memulai Hal di Bidang Apapun, Lebih Tepatnya Kita Mengenali diri kita sendiri tentang apa Tujuan & Mimpi kita. Setelah itu Tinggal Take Action & Fokus.</span>
                        </div>
                      </button>
                    </h2>
                    <div
                      id="module1"
                      class="accordion-collapse collapse show"
                      data-bs-parent="#curriculumAccordion">
                      <div class="accordion-body">
                        <div class="lessons-list">
                          <div class="lesson">
                            <i class="bi bi-play-circle"></i>
                            <span class="lesson-title">Pengenalan Diri Sendiri</span>
                            <span class="lesson-time">tonton</span>
                          </div>
                          <div class="lesson">
                            <i class="bi bi-file-earmark-text"></i>
                            <span class="lesson-title">Pengenalan Diri Sendiri</span>
                            <span class="lesson-time">download</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="accordion-item curriculum-module">
                    <h2 class="accordion-header">
                      <button
                        class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#module2">
                        <div class="module-info">
                          <span class="module-title">Lima Pilar Utama Dalam Membangun Bisnis</span>
                          <span class="module-meta">Setelah Di Materi 1 Kita bicara tentang apa Mimpi dan Tujuan Kita, Di Materi 2 kita akan membahas tentang Blue Print / Green Design dari Bisnis Kita. Tujuannya agar saat membangun bisnis, sesuai dengan Visi & Misi Kita. Hal ini harus dibuat, agar kita tidak keluar dari Design Yang sudah kita buat sendiri.</span>
                        </div>
                      </button>
                    </h2>
                    <div
                      id="module2"
                      class="accordion-collapse collapse"
                      data-bs-parent="#curriculumAccordion">
                      <div class="accordion-body">
                        <div class="lessons-list">
                          <div class="lesson">
                            <i class="bi bi-play-circle"></i>
                            <span class="lesson-title">Lima Pilar Utama Dalam Membangun Sebuah Bisnis</span>
                            <span class="lesson-time">tonton</span>
                          </div>
                          <div class="lesson">
                            <i class="bi bi-file-earmark-text"></i>
                            <span class="lesson-title">Module 5 Pilar Utama Dalam Membangun Sebuah Bisnis</span>
                            <span class="lesson-time">download</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <!-- End Curriculum Tab -->

              <!-- Reviews Tab -->
              <div
                class="tab-pane fade"
                id="course-detailsreviews"
                role="tabpanel">
                <div class="reviews-summary">
                  <div class="rating-overview">
                    <div class="overall-rating">
                      <div class="rating-number">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                      </div>
                      <div class="rating-text">
                        Maaf! Tidak ada bonus untuk kelas ini.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Reviews Tab -->
            </div>
          </div>
          <!-- End Course Navigation Tabs -->
        </div>
      </div>
    </div>
  </section>
  <!-- /Course Details Section -->
</main>
<script>
  // Muat modul dari sesi login (dibuat via halaman Login Kelas)
  (function(){
    document.addEventListener('DOMContentLoaded', async function(){
      const acc = document.getElementById('curriculumAccordion');
      if (!acc) return;
      try {
        const url = '<?= site_url('api/kelasonline/modules') ?>';
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) {
          if (res.status === 401) {
            window.location.href = '<?= site_url('loginkelas') ?>';
            return;
          }
        }
        const data = await res.json();
        if (!data || !data.ok) {
          acc.innerHTML = '<div class="alert alert-danger"><h6 class="alert-heading">Gagal memuat modul</h6><p class="mb-0">' + ((data && data.message) || 'Terjadi kesalahan. Silakan coba lagi.') + '</p></div>';
          return;
        }
        const modules = Array.isArray(data.modules) ? data.modules : [];
        if (modules.length === 0) {
          acc.innerHTML = '<div class="alert alert-info"><h6 class="alert-heading">Belum ada modul</h6><p class="mb-0">Modul online untuk kelas ini belum tersedia.</p></div>';
          return;
        }

        let html = '';
        modules.forEach(function(m, idx){
          const collapseId = 'module_' + (idx + 1);
          const isFirst = idx === 0;
          const judul = (m.judul_modul || 'Modul').replace(/[<>]/g,'');
          const deskripsi = (m.deskripsi || '').replace(/[<>]/g,'');
          const orderNum = (typeof m.urutan === 'number' && !Number.isNaN(m.urutan)) ? m.urutan : (idx + 1);
          html += `
            <div class="accordion-item curriculum-module">
              <h2 class="accordion-header">
                <button class="accordion-button${isFirst ? '' : ' collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                  <div class="module-info">
                    <span class="module-title">Modul ${orderNum}: ${judul}</span>
                    ${(Array.isArray(m.files) && m.files.length) ? `<span class="module-meta">${deskripsi ? '' + deskripsi : ''}</span>` : (deskripsi ? `<span class="module-meta">${deskripsi}</span>` : '')}
                  </div>
                </button>
              </h2>
              <div id="${collapseId}" class="accordion-collapse collapse${isFirst ? ' show' : ''}" data-bs-parent="#curriculumAccordion">
                <div class="accordion-body">
                  <div class="lessons-list">
                    ${Array.isArray(m.files) && m.files.length > 0 ? m.files.map(function(f){
                      const tipe = (f.tipe || '').toLowerCase();
                      const title = (f.judul_file || '').replace(/[<>]/g,'');
                      let url = (f.file_url || '').trim();
                      if (tipe === 'youtube' && !/^https?:\/\//i.test(url)) {
                        url = 'https://www.youtube.com/watch?v=' + url;
                      }
                      const safeUrl = url.replace(/"/g, '');
                      let icon = 'bi-file-earmark-text';
                      if (tipe === 'video' || tipe === 'youtube') {
                        icon = 'bi-play-circle';
                      } else if (tipe === 'document' || tipe === 'pdf') {
                        icon = 'bi-file-earmark-text';
                      } else if (tipe === 'excel') {
                        icon = 'bi-file-earmark-spreadsheet';
                      }
                      return `
                        <div class="lesson">
                          <i class="bi ${icon}"></i>
                          <span class="lesson-title">${title || 'File'}</span>
                          ${tipe === 'youtube' ? `<button type="button" class="btn btn-sm btn-outline-primary btn-play-youtube" data-url="${safeUrl}">Lihat</button>` 
                          : (tipe === 'video' ? `<button type="button" class="btn btn-sm btn-outline-primary btn-play-video" data-url="${safeUrl}">Lihat</button>` 
                          : (safeUrl ? `<a href="${safeUrl}" class="btn btn-sm btn-outline-secondary" download>Download</a>` : `<span class="lesson-time"></span>`))}
                        </div>`;
                    }).join('') : `<div class="lesson"><span class="text-muted">Belum ada file untuk modul ini.</span></div>`}
                  </div>
                </div>
              </div>
            </div>`;
        });
        acc.innerHTML = html;
        // Event handlers for play buttons and dynamic YouTube modal
        function ensureYouTubeModal(){
          let m = document.getElementById('videoPlayerModal');
          if (!m){
            const t = document.createElement('div');
            t.innerHTML = '<div class="modal fade" id="videoPlayerModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Pemutaran Video</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div><div class="modal-body"><div class="ratio ratio-16x9"><iframe id="youtubePlayer" src="" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div></div></div></div></div>';
            document.body.appendChild(t.firstChild);
            m = document.getElementById('videoPlayerModal');
          }
          return m;
        }
        function toEmbedUrl(s){
          const x = (s || '').trim();
          const r = x.match(/(?:youtu\.be\/|v=|embed\/)([A-Za-z0-9_-]{6,})/);
          return r ? ('https://www.youtube.com/embed/' + r[1]) : (/^https?:\/\//i.test(x) ? x : ('https://www.youtube.com/embed/' + x));
        }
        document.querySelectorAll('#curriculumAccordion .btn-play-youtube').forEach(function(btn){
          btn.addEventListener('click', function(){
            const m = ensureYouTubeModal();
            const iframe = document.getElementById('youtubePlayer');
            iframe.src = toEmbedUrl(this.dataset.url || '');
            const modal = new bootstrap.Modal(m);
            modal.show();
            m.addEventListener('hidden.bs.modal', function(){ iframe.src = ''; }, { once: true });
          });
        });
        document.querySelectorAll('#curriculumAccordion .btn-play-video').forEach(function(btn){
          btn.addEventListener('click', function(){
            const u = this.dataset.url || '';
            if (u) { window.open(u, '_blank', 'noopener'); }
          });
        });

        // Update curriculum-stats based on loaded modules/files
        const statsEl = document.querySelector('.curriculum-stats');
        if (statsEl){
          const sections = modules.length;
          let videos = 0;
          let files = 0;
          modules.forEach(function(m){
            if (Array.isArray(m.files)){
              m.files.forEach(function(f){
                const tipe = (f.tipe || '').toLowerCase();
                const url = (f.file_url || '').trim();
                if (tipe === 'youtube' || tipe === 'video') {
                  videos += 1;
                } else if (url) {
                  files += 1;
                }
              });
            }
          });
          const spans = statsEl.querySelectorAll('.stat span');
          if (spans[0]) spans[0].textContent = sections + ' Sections';
          if (spans[1]) spans[1].textContent = videos + ' video';
          if (spans[2]) spans[2].textContent = files + ' file';
        }
      } catch (err) {
        console.error(err);
        acc.innerHTML = '<div class="alert alert-danger"><h6 class="alert-heading">Kesalahan Jaringan</h6><p class="mb-0">Tidak dapat memuat modul. Periksa koneksi Anda.</p></div>';
      }
    });
  })();
</script>
<?= $this->endSection() ?>
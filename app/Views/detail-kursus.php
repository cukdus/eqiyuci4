<?php
$this->setVar('pageTitle', 'Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
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
      <h1 class="mb-2 mb-lg-0">Detail Kursus</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li class="current">Detail Kursus</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <!-- Course Details Section -->
  <section id="course-details" class="course-details section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row">
        <div class="col-lg-8">
          <!-- Course Hero -->
          <div class="course-hero" data-aos="fade-up" data-aos-delay="200">
            <div class="hero-content">
              <div class="course-badge">
                <span class="category"><?= esc($kategoriLabel ?? '') ?></span>
              </div>
              <h1><?= esc($kelas['nama_kelas'] ?? '') ?></h1>
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
            <div class="hero-image">
              <?php $images = is_array($heroImages ?? null) ? $heroImages : [($heroImageUrl ?? '')]; ?>
              <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true">
                <div class="carousel-inner">
                  <?php foreach ($images as $i => $url): ?>
                    <?php if (trim((string) $url) === '') continue; ?>
                    <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                      <img src="<?= esc($url) ?>" alt="Course Image" class="d-block w-100 img-fluid" />
                    </div>
                  <?php endforeach; ?>
                </div>
                <?php if (count($images) > 1): ?>
                  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <i class="bi bi-arrow-left-circle-fill text-warning fs-1" aria-hidden="true"></i>
                    <span class="visually-hidden">Sebelumnya</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <i class="bi bi-arrow-right-circle-fill text-warning fs-1" aria-hidden="true"></i>
                    <span class="visually-hidden">Berikutnya</span>
                  </button>
                <?php endif; ?>
              </div>
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
            </ul>

            <div class="tab-content" id="course-detailsCourseTabContent">
              <!-- Overview Tab -->
              <div
                class="tab-pane fade show active"
                id="course-detailsoverview"
                role="tabpanel">
                <div class="overview-section">
                  <div class="accordion" id="curriculumAccordion">
                    <div class="accordion-item curriculum-module">
                      <h2 class="accordion-header">
                        <button
                          class="accordion-button"
                          type="button"
                          data-bs-toggle="collapse"
                          data-bs-target="#module1">
                          <div class="module-info">
                            <span class="module-title">Deskripsi Singkat</span>
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
                              <p><?= nl2br(esc($deskripsiSingkat ?? '')) ?></p>
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
                            <span class="module-title">Detail Kursus</span>
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
                              <div><?= $deskripsiHtml ?? '' ?></div>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Overview Tab -->
            </div>
          </div>
          <!-- End Course Navigation Tabs -->
        </div>

        <div class="col-lg-4">
          <!-- Enrollment Card -->
          <div
            class="enrollment-card"
            data-aos="fade-up"
            data-aos-delay="200">
            <div class="card-header">
              <div class="price-display">
                <span class="current-price"><?= esc($hargaFormatted ?? '') ?></span>
              </div>
              <!-- <div class="enrollment-count">
                    <i class="bi bi-people"></i>
                    <span>3,892 students enrolled</span>
                  </div> -->
            </div>

            <div class="card-body">
              <div class="course-highlights">
                <div class="highlight-item">
                  <i class="bi bi-geo-alt-fill"></i>
                  Lokasi Kota : <span><?= esc($kotaString ?? '') ?></span>
                </div>
                <div class="highlight-item">
                  <i class="bi bi-calendar"></i>
                  Durasi : <span><?= esc($kelas['durasi'] ?? '') ?></span>
                </div>
                <div class="highlight-item">
                  <i class="bi bi-bookmark-heart"></i>
                  Kategori : <span><?= esc($kategoriLabel ?? '') ?></span>
                </div>
              </div>

              <div class="action-buttons">
                <?php $isSegera = strtolower((string) ($kelas['status_kelas'] ?? '')) === 'segera'; ?>
                <?php if ($isSegera): ?>
                  <button class="btn-primary" disabled>Contact Admin</button>
                <?php else: ?>
                  <a href="<?= esc($daftarUrl ?? '#') ?>">
                    <button class="btn-primary">Daftar Sekarang</button>
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <!-- End Enrollment Card -->

          <!-- Share Course -->
          <div
            class="share-course-card"
            data-aos="fade-up"
            data-aos-delay="400">
            <h4>Share This Course</h4>
            <div class="social-links">
              <a href="#" class="social-link facebook" data-network="facebook" aria-label="Bagikan ke Facebook" title="Bagikan ke Facebook">
                <i class="bi bi-facebook"></i>
              </a>
              <a href="#" class="social-link twitter" data-network="twitter" aria-label="Bagikan ke Twitter/X" title="Bagikan ke Twitter/X">
                <i class="bi bi-twitter-x"></i>
              </a>
              <a href="#" class="social-link whatsapp" data-network="whatsapp" aria-label="Bagikan ke WhatsApp" title="Bagikan ke WhatsApp">
                <i class="bi bi-whatsapp"></i>
              </a>
              <a href="#" class="social-link email" data-network="email" aria-label="Bagikan via Email" title="Bagikan via Email">
                <i class="bi bi-envelope"></i>
              </a>
            </div>
          </div>
          <!-- End Share Course -->
        </div>
      </div>
    </div>
  </section>
  <!-- /Course Details Section -->
</main>
<script>
  (function(){
    try {
      var kode = '<?= isset($kelas['kode_kelas']) ? esc($kelas['kode_kelas']) : '' ?>';
      if (kode) {
        localStorage.setItem('lastViewedKelas', kode);
      }
    } catch (e) {}
  })();

  // Share buttons functionality: open native apps on mobile, fallback to web share
  (function(){
    var title = '<?= esc($kelas['nama_kelas'] ?? 'Kursus Eqiyu Indonesia') ?>';
    var detailSlug = '<?= esc((string) (isset($kelas['slug']) && trim((string)$kelas['slug']) !== '' ? $kelas['slug'] : ($kelas['kode_kelas'] ?? ''))) ?>';
    var pageUrl = '<?= esc(base_url('kursus')) ?>' + '/' + detailSlug;
    var shareText = 'Lihat kursus "' + title + '" di Eqiyu Indonesia';

    function isMobile(){
      return /(android|iphone|ipad|ipod|windows phone)/i.test(navigator.userAgent || '');
    }

    function openUrl(url){
      try {
        window.location.href = url;
      } catch(e) {
        // noop
      }
    }

    function openInNew(url){
      try {
        window.open(url, '_blank', 'noopener');
      } catch(e) {
        // noop
      }
    }

    function handleShare(network){
      var mobile = isMobile();
      var textEnc = encodeURIComponent(shareText + ' ' + pageUrl);
      var urlEnc = encodeURIComponent(pageUrl);
      var titleEnc = encodeURIComponent(title);

      // Prefer the Web Share API on supported mobile browsers
      if (mobile && navigator.share) {
        navigator.share({ title: title, text: shareText, url: pageUrl })
          .catch(function(){
            // If user cancels or it fails, continue to deep-link fallback
            doNetworkShare(network, mobile, textEnc, urlEnc, titleEnc);
          });
        return;
      }

      doNetworkShare(network, mobile, textEnc, urlEnc, titleEnc);
    }

    function doNetworkShare(network, mobile, textEnc, urlEnc, titleEnc){
      if (network === 'whatsapp') {
        var deep = 'whatsapp://send?text=' + textEnc;
        var web = 'https://wa.me/?text=' + textEnc;
        if (mobile) {
          openUrl(deep);
          setTimeout(function(){ openInNew(web); }, 800);
        } else {
          openInNew(web);
        }
        return;
      }

      if (network === 'facebook') {
        var deepFb = 'fb://facewebmodal/f?href=' + encodeURIComponent('https://www.facebook.com/sharer/sharer.php?u=' + pageUrl);
        var webFb = 'https://www.facebook.com/sharer/sharer.php?u=' + urlEnc + '&quote=' + textEnc;
        if (mobile) {
          openUrl(deepFb);
          setTimeout(function(){ openInNew(webFb); }, 800);
        } else {
          openInNew(webFb);
        }
        return;
      }

      if (network === 'twitter') {
        var deepTw = 'twitter://post?message=' + textEnc;
        var webTw = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(shareText) + '&url=' + urlEnc;
        if (mobile) {
          openUrl(deepTw);
          setTimeout(function(){ openInNew(webTw); }, 800);
        } else {
          openInNew(webTw);
        }
        return;
      }

      if (network === 'email') {
        var mail = 'mailto:?subject=' + titleEnc + '&body=' + encodeURIComponent(shareText + '\n' + pageUrl);
        openUrl(mail);
        return;
      }
    }

    var links = document.querySelectorAll('.share-course-card .social-link');
    links.forEach(function(link){
      link.addEventListener('click', function(ev){
        ev.preventDefault();
        var network = (link.getAttribute('data-network') || '').toLowerCase();
        if (!network) return;
        handleShare(network);
      });
    });
  })();
</script>
<?= $this->endSection() ?>
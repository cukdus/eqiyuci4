<?php
$this->setVar('pageTitle', 'Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
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
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Course Details</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li class="current">Course Details</li>
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
              <img
                src="<?= esc($heroImageUrl ?? '') ?>"
                alt="Course Preview"
                class="img-fluid" />
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
                <a href="<?= esc($daftarUrl ?? '#') ?>">
                  <button class="btn-primary">Daftar Sekarang</button></a>
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
              <a href="#" class="social-link facebook">
                <i class="bi bi-facebook"></i>
              </a>
              <a href="#" class="social-link twitter">
                <i class="bi bi-twitter"></i>
              </a>
              <a href="#" class="social-link linkedin">
                <i class="bi bi-linkedin"></i>
              </a>
              <a href="#" class="social-link email">
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
</script>
<?= $this->endSection() ?>
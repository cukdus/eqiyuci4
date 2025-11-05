<?php
$this->setVar('pageTitle', 'Kursus Online | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
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
      <h1 class="mb-2 mb-lg-0">Modul Online</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
          <li class="current">Kursus Online</li>
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
                <span class="category">Web Development</span>
                <span class="level">Advanced</span>
              </div>
              <h1>Basic Barista & Latte Art</h1>
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
                src="assets/img/education/courses-8.webp"
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
                <button
                  class="nav-link"
                  id="course-detailsreviews-tab"
                  data-bs-toggle="tab"
                  data-bs-target="#course-detailsreviews"
                  type="button"
                  role="tab">
                  <i class="bi bi-star"></i>
                  Bonus Kelas
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
                  <h3>Deskripsi Kursus</h3>
                  <p>
                    Sed ut perspiciatis unde omnis iste natus error sit
                    voluptatem accusantium doloremque laudantium, totam rem
                    aperiam, eaque ipsa quae ab illo inventore veritatis et
                    quasi architecto beatae vitae dicta sunt explicabo.
                  </p>
                  <p>
                    Nemo enim ipsam voluptatem quia voluptas sit aspernatur
                    aut odit aut fugit, sed quia consequuntur magni dolores
                    eos qui ratione voluptatem sequi nesciunt.
                  </p>
                </div>

                <div class="skills-grid">
                  <h3>Skills You'll Gain</h3>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="skill-item">
                        <div class="skill-icon">
                          <i class="bi bi-code-slash"></i>
                        </div>
                        <div class="skill-content">
                          <h5>Frontend Development</h5>
                          <p>React, JavaScript ES6+, HTML5 &amp; CSS3</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="skill-item">
                        <div class="skill-icon">
                          <i class="bi bi-server"></i>
                        </div>
                        <div class="skill-content">
                          <h5>Backend Development</h5>
                          <p>Node.js, Express.js, RESTful APIs</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="skill-item">
                        <div class="skill-icon">
                          <i class="bi bi-database"></i>
                        </div>
                        <div class="skill-content">
                          <h5>Database Management</h5>
                          <p>MongoDB, Mongoose, Data Modeling</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="skill-item">
                        <div class="skill-icon">
                          <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="skill-content">
                          <h5>Security &amp; Testing</h5>
                          <p>Authentication, JWT, Unit Testing</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="requirements-section">
                  <h3>Requirements</h3>
                  <ul class="requirements-list">
                    <li>
                      <i class="bi bi-check2"></i>Basic understanding of
                      HTML and CSS
                    </li>
                    <li>
                      <i class="bi bi-check2"></i>Familiarity with
                      JavaScript fundamentals
                    </li>
                    <li>
                      <i class="bi bi-check2"></i>Computer with internet
                      connection
                    </li>
                    <li>
                      <i class="bi bi-check2"></i>Text editor or IDE
                      installed
                    </li>
                  </ul>
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
                      <span>12 Sections</span>
                    </div>
                    <div class="stat">
                      <i class="bi bi-play-circle"></i>
                      <span>89 Lectures</span>
                    </div>
                    <div class="stat">
                      <i class="bi bi-clock"></i>
                      <span>45h 32m</span>
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
                          <span class="module-title">JavaScript Fundamentals &amp; ES6+</span>
                          <span class="module-meta">8 lessons • 4h 15m</span>
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
                            <span class="lesson-title">Variables, Functions and Scope</span>
                            <span class="lesson-time">28 min</span>
                          </div>
                          <div class="lesson">
                            <i class="bi bi-play-circle"></i>
                            <span class="lesson-title">Arrow Functions and Destructuring</span>
                            <span class="lesson-time">35 min</span>
                          </div>
                          <div class="lesson">
                            <i class="bi bi-file-earmark-text"></i>
                            <span class="lesson-title">Promises and Async/Await</span>
                            <span class="lesson-time">42 min</span>
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
                          <span class="module-title">React Development Deep Dive</span>
                          <span class="module-meta">12 lessons • 7h 45m</span>
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
                            <span class="lesson-title">Components and JSX Syntax</span>
                            <span class="lesson-time">32 min</span>
                          </div>
                          <div class="lesson">
                            <i class="bi bi-play-circle"></i>
                            <span class="lesson-title">State Management with Hooks</span>
                            <span class="lesson-time">48 min</span>
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
                        data-bs-target="#module3">
                        <div class="module-info">
                          <span class="module-title">Node.js &amp; Server Development</span>
                          <span class="module-meta">15 lessons • 8h 20m</span>
                        </div>
                      </button>
                    </h2>
                    <div
                      id="module3"
                      class="accordion-collapse collapse"
                      data-bs-parent="#curriculumAccordion">
                      <div class="accordion-body">
                        <div class="lessons-list">
                          <div class="lesson">
                            <i class="bi bi-play-circle"></i>
                            <span class="lesson-title">Express.js Server Setup</span>
                            <span class="lesson-time">25 min</span>
                          </div>
                          <div class="lesson">
                            <i class="bi bi-file-earmark-text"></i>
                            <span class="lesson-title">Building RESTful APIs</span>
                            <span class="lesson-time">55 min</span>
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

                <div class="reviews-list">
                  <div class="review-item">
                    <div class="reviewer-info">
                      <img
                        src="assets/img/person/person-f-12.webp"
                        alt="Reviewer"
                        class="reviewer-avatar" />
                      <div class="reviewer-details">
                        <h6>Jessica Chen</h6>
                        <div class="review-rating">
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                        </div>
                      </div>
                      <span class="review-date">2 weeks ago</span>
                    </div>
                    <p class="review-text">
                      Excepteur sint occaecat cupidatat non proident, sunt
                      in culpa qui officia deserunt mollit anim id est
                      laborum. The instructor explains complex concepts very
                      clearly.
                    </p>
                  </div>

                  <div class="review-item">
                    <div class="reviewer-info">
                      <img
                        src="assets/img/person/person-m-5.webp"
                        alt="Reviewer"
                        class="reviewer-avatar" />
                      <div class="reviewer-details">
                        <h6>David Thompson</h6>
                        <div class="review-rating">
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star"></i>
                        </div>
                      </div>
                      <span class="review-date">1 month ago</span>
                    </div>
                    <p class="review-text">
                      Lorem ipsum dolor sit amet, consectetur adipiscing
                      elit. Great practical examples and real-world projects
                      that helped me understand the concepts better.
                    </p>
                  </div>
                </div>
              </div>
              <!-- End Reviews Tab -->
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
                <span class="current-price">Rp. 2.499.000</span>
              </div>
              <div class="enrollment-count">
                <i class="bi bi-people"></i>
                <span>3.892 siswa terdaftar</span>
              </div>
            </div>

            <div class="card-body">
              <div class="course-highlights">
                <div class="highlight-item">
                  <i class="bi bi-trophy"></i>
                  Lokasi Kota : <span>Malang, Jogja</span>
                </div>
                <div class="highlight-item">
                  <i class="bi bi-clock-history"></i>
                  Durasi : <span>3 Hari</span>
                </div>
                <div class="highlight-item">
                  <i class="bi bi-download"></i>
                  Kategori : <span>Kursus</span>
                </div>
              </div>

              <div class="action-buttons">
                <button class="btn-primary">Enroll Now</button>
              </div>

              <div class="guarantee">
                <i class="bi bi-shield-check"></i>
                <span>30-day money-back guarantee</span>
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
<footer id="footer" class="footer accent-background">
      <div class="support section light-background">
        <div class="container aos-init aos-animate" data-aos="zoom-in">
          <div class="swiper init-swiper swiper-initialized swiper-horizontal swiper-backface-hidden">
            <script type="application/json" class="swiper-config">
              {
                "loop": true,
                "speed": 600,
                "autoplay": {
                  "delay": 5000
                },
                "slidesPerView": "auto",
                "pagination": {
                  "el": ".swiper-pagination",
                  "type": "bullets",
                  "clickable": true
                },
                "breakpoints": {
                  "320": {
                    "slidesPerView": 2,
                    "spaceBetween": 40
                  },
                  "480": {
                    "slidesPerView": 3,
                    "spaceBetween": 60
                  },
                  "640": {
                    "slidesPerView": 4,
                    "spaceBetween": 80
                  },
                  "992": {
                    "slidesPerView": 5,
                    "spaceBetween": 120
                  },
                  "1200": {
                    "slidesPerView": 6,
                    "spaceBetween": 120
                  }
                }
              }
            </script>
            <div class="swiper-wrapper align-items-center" id="swiper-wrapper-c4bc11742d10cf612" aria-live="off" style="transition-duration: 0ms; transform: translate3d(-236px, 0px, 0px); transition-delay: 0ms;">


              <div class="swiper-slide swiper-slide-prev" style="width: 120px; margin-right: 120px; " role="group" aria-label="3 / 7" data-swiper-slide-index="2">
                <a href="https://mbowis.id/" target="_blank">
                  <img src="assets/img/support/mbowis.webp" class="img-fluid" alt="mbowis" title="mbowis syrup">
                </a>
              </div>
              <div class="swiper-slide swiper-slide-active" style="width: 120px; margin-right: 120px;" role="group" aria-label="4 / 7" data-swiper-slide-index="3">
                <a href="https://atourwis.id/" target="_blank">
                  <img src="assets/img/support/atourwis.webp" class="img-fluid" alt="atourwis" title="Atourwis Travel">
                </a>
              </div>
              <div class="swiper-slide swiper-slide-next" style="width: 120px; margin-right: 120px;" role="group" aria-label="5 / 7" data-swiper-slide-index="4">
                <a href="https://mlgcoffee.com/" target="_blank">
                  <img src="assets/img/support/mlg.webp" class="img-fluid" alt="MLG management" title="MLG coffee">
                </a>
              </div>
              <div class="swiper-slide" style="width: 120px; margin-right: 120px;" role="group" aria-label="6 / 7" data-swiper-slide-index="5">
                <a href="https://www.instagram.com/pressolve/" target="_blank">
                  <img src="assets/img/support/pressolve.webp" class="img-fluid" alt="pressolve" title="pressolve Website">
                </a>
              </div>
              <div class="swiper-slide" style="width: 120px; margin-right: 120px;" role="group" aria-label="7 / 7" data-swiper-slide-index="6">
                <a href="https://overhaul.id/" target="_blank">
                  <img src="assets/img/support/overhaul.webp" class="img-fluid" alt="overhaul" title="Overhaul Website">
                </a>
              </div>
              <div class="swiper-slide" style="width: 120px; margin-right: 120px;" role="group" aria-label="1 / 7" data-swiper-slide-index="0">
                <a href="https://bekasbaru.com/" target="_blank">
                  <img src="assets/img/support/bekasbaru.webp" class="img-fluid" alt="EQIYU" title="BekasBaru.com - Toko alat kopi Bekas dan Baru">
                </a>
              </div>
              <div class="swiper-slide" style="width: 120px; margin-right: 120px;" role="group" aria-label="2 / 7" data-swiper-slide-index="1">
                <a href="https://azkaindiesatu.id/" target="_blank">
                  <img src="assets/img/support/ais.webp" class="img-fluid" alt="AIS" title="Azka Indie Satu">
                </a>
              </div>
            </div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
          </div>
          <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
        </div>
      </div>
      <div class="container footer-top">
        <div class="row gy-4">
          <div class="col-lg-6 col-md-12 footer-about">
            <a href="index.php" class="logo d-flex align-items-center">
              <img src="assets/img/logofooter.webp" alt="Eqiyu Indonesia" />
            </a>
            <h3>Lembaga Pendidikan Profesional</h3>
            <p>
              Kursus Barista, Mixology, Tea &amp; Tea Blending, Roastery, Pelatihan &amp; Konsultan
              Membangun Bisnis Caffe &amp; Coffeshop.
            </p>
            <div class="social-links d-flex mt-4">
              <a href="https://x.com/Eqiyu_Indonesia/"><i class="bi bi-twitter-x"></i></a>
              <a href="https://www.facebook.com/eqiyu.indonesia"><i class="bi bi-facebook"></i></a>
              <a href="https://www.instagram.com/eqiyu.indonesia"><i class="bi bi-instagram"></i></a>
              <a href="https://www.youtube.com/c/TokoBekasBaru"><i class="bi bi-youtube"></i></a>
              <a href="https://www.tiktok.com/@eqiyu.indonesia"><i class="bi bi-tiktok"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6 footer-links">
            <h4>Eqiyu Malang</h4>
            <div class="d-flex align-items-start mb-2">
              <i class="bi bi-geo-alt me-2 mt-1"></i>
              <span>
                Jl. Brigjend Slamet Riadi No.76,<br />
                Oro-oro Dowo, Kec. Klojen,<br />
                Kota Malang, Jawa Timur 65119.
              </span>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-pin-map me-2 mt-1"></i>
              <span>
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.2903049907245!2d112.62654827588874!3d-7.968920479432775!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd62974117b5143%3A0xac0a6f7e8b6c0c35!2sEqiyu%20Indonesia%20Malang%20(Kursus%20Barista%20%26%20Bisnis%20Kuliner)!5e0!3m2!1sen!2sid!4v1746086179700!5m2!1sen!2sid"
                  width="100%"
                  height="100%"
                  style="border: 0; border-radius: 10px"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </span>
            </div>
          </div>

          <div class="col-lg-3 col-6 footer-links">
            <h4>Eqiyu Jogja</h4>
            <div class="d-flex align-items-start mb-2">
              <i class="bi bi-geo-alt me-2 mt-1"></i>
              <span>
                Jl. Pugeran.11 - 15,<br />
                Suryodiningratan, Kec. Mantrijeron,<br />
                Kota Yogyakarta, DIY 55141.
              </span>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-pin-map me-2 mt-1"></i>
              <span>
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.7700450055027!2d110.35300158485491!3d-7.814149257479901!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a57f7da45014d%3A0x6c71f9c11a0f935!2sEqiyu%20Indonesia%20Jogja!5e0!3m2!1sen!2sid!4v1746086112162!5m2!1sen!2sid"
                  width="100%"
                  height="100%"
                  style="border: 0; border-radius: 10px"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="container copyright text-center mt-4">
        <p>
          © <span>Copyright</span>
          <strong class="px-1 sitename">Learner</strong>
          <span>All Rights Reserved</span>
        </p>
        <div class="credits">
          Dibangun dengan ❤️ oleh <a href="https://azkaindiesatu.id">AIS</a>
        </div>
      </div>
    </footer>
    <!-- WhatsApp Float Button -->
    <a
      href="https://wa.me/6285852229959?text=Hallo%2C%20Saya%20tahu%20info%20dari%20web%20https%3A%2F%2Feqiyu.id%20ingin%20bertanya%20tentang%20kelas%2Fkursus"
      class="whatsapp-float"
      target="_blank">
      <i class="bi bi-whatsapp"></i>
    </a>
    <!-- Scroll Top -->
    <a
      href="#"
      id="scroll-top"
      class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
    </body>

    </html>
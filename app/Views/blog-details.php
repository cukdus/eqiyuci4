<?php
$pageTitle = 'Beranda | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.';
$metaDescription = 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.';
$metaKeywords = 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia';
$canonicalUrl = base_url();
$bodyClass = 'index-page';
$activePage = 'index';

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

$navItems = [
  ['slug' => 'index', 'label' => 'Beranda', 'path' => base_url()],
  ['slug' => 'about', 'label' => 'Tentang', 'path' => base_url('about')],
  ['slug' => 'blog', 'label' => 'Info', 'path' => base_url('blog')],
  ['slug' => 'contact', 'label' => 'Kontak', 'path' => base_url('contact')],
  ['slug' => 'schedule', 'label' => 'Jadwal', 'path' => base_url('schedule')],
  ['slug' => 'sertifikat', 'label' => 'Sertifikat', 'path' => base_url('sertifikat')],
  ['slug' => 'bonus', 'label' => 'Bonus', 'path' => base_url('bonus')],
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title><?= $escape($pageTitle) ?></title>
  <meta name="description" content="<?= $escape($metaDescription) ?>" />
  <meta name="keywords" content="<?= $escape($metaKeywords) ?>" />
  <link rel="canonical" href="<?= $escape($canonicalUrl) ?>" />
  <meta name="robots" content="index, follow" />
  <meta property="og:locale" content="id_ID" />
  <meta property="og:type" content="website" />
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= base_url() ?>" />
  <meta property="og:site_name" content="EQIYU INDONESIA" />
  <meta property="og:locale" content="id_ID" />
  <meta
    property="og:title"
    content="EQIYU INDONESIA - Lembaga Pendidikan Profesional Barista &amp; Bisnis Kuliner" />
  <meta
    property="og:description"
    content="Kursus dan pelatihan profesional untuk menjadi Barista handal dan membangun bisnis kuliner sukses." />
  <meta property="og:image" content="<?= base_url('assets/img/eqiyu-logo.png') ?>" />
  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:site" content="@Eqiyu_Indonesia" />
  <meta property="twitter:url" content="<?= base_url() ?>" />
  <meta
    property="twitter:title"
    content="EQIYU INDONESIA - Lembaga Pendidikan Profesional Barista &amp; Bisnis Kuliner" />
  <meta
    property="twitter:description"
    content="Kursus dan pelatihan profesional untuk menjadi Barista handal dan membangun bisnis kuliner sukses." />
  <meta property="twitter:image" content="<?= base_url('assets/img/eqiyu-logo.png') ?>" />
  <!-- Canonical URL -->
  <link rel="canonical" href="<?= $escape($canonicalUrl) ?>" />

  <!-- Favicons -->
  <link href="<?= base_url('assets/img/favicon.png') ?>" rel="icon" />
  <link href="<?= base_url('assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Vendor CSS Files -->
  <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/vendor/aos/aos.css') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/vendor/swiper/swiper-bundle.min.css') ?>" rel="stylesheet" />

  <!-- Template Main CSS Files -->
  <link href="<?= base_url('assets/css/main.css') ?>" rel="stylesheet" />
</head>

<body class="<?= $escape($bodyClass) ?>">
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
      <a href="<?= base_url() ?>" class="logo d-flex align-items-center me-auto">
        <img src="<?= base_url('assets/img/logo.webp') ?>" alt="logo Eqiyu Indonesia" />
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <?php foreach ($navItems as $item): ?>
            <?php $isActive = $activePage === $item['slug']; ?>
            <li>
              <a href="<?= $item['path'] ?>" <?= $isActive ? ' class="active"' : '' ?>><?= $item['label'] ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="<?= base_url('courses') ?>">Semua Kursus</a>
    </div>
  </header>
<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Blog Details</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li class="current">Blog Details</li>
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
            <a href="#" class="category">Technology</a>
            <a href="#" class="category">Innovation</a>
          </div>

          <h1 class="title" data-aos="fade-up" data-aos-delay="100">
            The Evolution of User Interface Design: From Skeuomorphism to
            Neumorphism
          </h1>

          <div class="article-meta" data-aos="fade-up" data-aos-delay="200">
            <div class="post-info">
              <span><i class="bi bi-calendar4-week"></i> April 15, 2025</span>
              <span><i class="bi bi-person"></i> Nama Penulis</span>
            </div>
          </div>
        </div>

        <div class="article-featured-image" data-aos="zoom-in">
          <img
            src="assets/img/blog/blog-hero-1.webp"
            alt="UI Design Evolution"
            class="img-fluid" />
        </div>

        <div class="article-wrapper">
          <aside class="table-of-contents" data-aos="fade-left">
            <h3>Kategori</h3>
            <nav>
              <ul>
                <li>
                  <a href="#" class="active">Kategori 1 (40)</a>
                </li>
                <li><a href="#">Kategori 2 (10)</a></li>
                <li><a href="#">Kategori 3 (8)</a></li>
                <li><a href="#">Kategori 4 (12)</a></li>
              </ul>
            </nav>
          </aside>

          <div class="article-content">
            <div
              class="content-section"
              id="introduction"
              data-aos="fade-up">
              <p class="lead">
                The journey of user interface design has been marked by
                significant shifts in aesthetic approaches, each era
                bringing its own unique perspective on how digital
                interfaces should look and feel.
              </p>

              <p>
                From the early days of graphical user interfaces to today's
                sophisticated design systems, the evolution of UI design
                reflects not just technological advancement, but also
                changing user expectations and cultural shifts in how we
                interact with digital products.
              </p>

              <div class="highlight-quote">
                <blockquote>
                  <p>
                    Design is not just what it looks like and feels like.
                    Design is how it works.
                  </p>
                  <cite>Steve Jobs</cite>
                </blockquote>
              </div>
            </div>

            <div
              class="content-section"
              id="skeuomorphism"
              data-aos="fade-up">
              <h2>The Skeuomorphic Era</h2>
              <div class="image-with-caption right">
                <img
                  src="assets/img/blog/blog-hero-2.webp"
                  alt="Skeuomorphic Design Example"
                  class="img-fluid"
                  loading="lazy" />
                <figcaption>
                  Early iOS design showcasing skeuomorphic elements
                </figcaption>
              </div>
              <p>
                Skeuomorphic design dominated the early years of digital
                interfaces, attempting to mirror real-world objects in
                digital form. This approach helped users transition from
                physical to digital interactions through familiar visual
                metaphors.
              </p>

              <div class="feature-points">
                <div class="point">
                  <i class="bi bi-layers"></i>
                  <div>
                    <h4>Realistic Textures</h4>
                    <p>
                      Detailed representations of materials like leather,
                      metal, and paper
                    </p>
                  </div>
                </div>
                <div class="point">
                  <i class="bi bi-lightbulb"></i>
                  <div>
                    <h4>Familiar Metaphors</h4>
                    <p>
                      Digital elements mimicking their physical counterparts
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div
              class="content-section"
              id="flat-design"
              data-aos="fade-up">
              <h2>The Flat Design Revolution</h2>
              <p>
                As users became more comfortable with digital interfaces,
                design began moving towards simplification. Flat design
                emerged as a reaction to the ornate details of
                skeuomorphism, emphasizing clarity and efficiency.
              </p>

              <div class="comparison-grid">
                <div class="row g-4">
                  <div class="col-md-6">
                    <div class="comparison-card">
                      <div class="icon">
                        <i class="bi bi-check-circle"></i>
                      </div>
                      <h4>Advantages</h4>
                      <ul>
                        <li>Improved loading times</li>
                        <li>Better scalability</li>
                        <li>Cleaner visual hierarchy</li>
                      </ul>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="comparison-card">
                      <div class="icon">
                        <i class="bi bi-exclamation-circle"></i>
                      </div>
                      <h4>Challenges</h4>
                      <ul>
                        <li>Reduced visual cues</li>
                        <li>Potential usability issues</li>
                        <li>Limited depth perception</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div
              class="content-section"
              id="material-design"
              data-aos="fade-up">
              <h2>Material Design: Finding Balance</h2>
              <p>
                Google's Material Design emerged as a comprehensive design
                system that combined the simplicity of flat design with
                subtle depth cues, creating a more intuitive user experience
                while maintaining modern aesthetics.
              </p>

              <div class="key-principles">
                <div class="principle">
                  <span class="number">01</span>
                  <h4>Physical Properties</h4>
                  <p>
                    Surfaces and edges provide meaningful interaction cues
                  </p>
                </div>
                <div class="principle">
                  <span class="number">02</span>
                  <h4>Bold Graphics</h4>
                  <p>
                    Deliberate color choices and intentional white space
                  </p>
                </div>
                <div class="principle">
                  <span class="number">03</span>
                  <h4>Meaningful Motion</h4>
                  <p>Animation informs and reinforces user actions</p>
                </div>
              </div>
            </div>

            <div
              class="content-section"
              id="neumorphism"
              data-aos="fade-up">
              <h2>The Rise of Neumorphism</h2>
              <p>
                Neumorphism represents the latest evolution in UI design,
                combining aspects of skeuomorphism with modern minimal
                aesthetics. This style creates soft, extruded surfaces that
                appear to emerge from the background.
              </p>

              <div class="info-box">
                <div class="icon">
                  <i class="bi bi-info-circle"></i>
                </div>
                <div class="content">
                  <h4>Key Characteristics</h4>
                  <p>
                    Neumorphic design relies on subtle shadow work to create
                    the illusion of elements either protruding from or being
                    pressed into their background surface.
                  </p>
                </div>
              </div>
            </div>

            <div class="content-section" id="future" data-aos="fade-up">
              <h2>Looking to the Future</h2>
              <p>
                As we look ahead, UI design continues to evolve with new
                technologies and user expectations. The future may bring
                more personalized, adaptive interfaces that respond to
                individual user preferences and contexts.
              </p>

              <div class="future-trends">
                <div class="trend">
                  <i class="bi bi-phone"></i>
                  <h4>Adaptive Interfaces</h4>
                  <p>
                    Interfaces that automatically adjust based on user
                    behavior and preferences
                  </p>
                </div>
                <div class="trend">
                  <i class="bi bi-eye"></i>
                  <h4>Immersive Experiences</h4>
                  <p>
                    Integration of AR and VR elements in everyday interfaces
                  </p>
                </div>
                <div class="trend">
                  <i class="bi bi-hand-index"></i>
                  <h4>Gesture Controls</h4>
                  <p>Advanced motion and gesture-based interactions</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="article-footer" data-aos="fade-up">
          <div class="share-article">
            <h4>Share this article</h4>
            <div class="share-buttons">
              <a href="#" class="share-button twitter">
                <i class="bi bi-twitter-x"></i>
                <span>Share on X</span>
              </a>
              <a href="#" class="share-button facebook">
                <i class="bi bi-facebook"></i>
                <span>Share on Facebook</span>
              </a>
              <a href="#" class="share-button linkedin">
                <i class="bi bi-linkedin"></i>
                <span>Share on LinkedIn</span>
              </a>
            </div>
          </div>

          <div class="article-tags">
            <h4>Related Topics</h4>
            <div class="tags">
              <a href="#" class="tag">UI Design</a>
              <a href="#" class="tag">User Experience</a>
              <a href="#" class="tag">Design Trends</a>
              <a href="#" class="tag">Innovation</a>
              <a href="#" class="tag">Technology</a>
            </div>
          </div>
        </div>
      </article>
    </div>
  </section>
  <!-- /Blog Details Section -->
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
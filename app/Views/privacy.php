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
  ['slug' => 'tentang', 'label' => 'Tentang', 'path' => base_url('tentang')],
  ['slug' => 'info', 'label' => 'Info', 'path' => base_url('info')],
  ['slug' => 'kontak', 'label' => 'Kontak', 'path' => base_url('kontak')],
  ['slug' => 'jadwal', 'label' => 'Jadwal', 'path' => base_url('jadwal')],
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
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Privacy Policy</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li class="current">Privacy Policy</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <!-- Privacy Section -->
  <section id="privacy" class="privacy section">

    <div class="container" data-aos="fade-up">

      <!-- Header -->
      <div class="privacy-header" data-aos="fade-up">
        <div class="header-content">
          <div class="last-updated">Effective Date: November 1, 2025</div>
          <h1>Privacy Policy</h1>
          <p class="intro-text">
            This Privacy Policy explains how Eqiyu Indonesia collects, uses, and protects your information across all of our digital services, including
            our main website (<a href="https://eqiyu.id" target="_blank">eqiyu.id</a>) and subdomains such as
            <a href="https://posting.eqiyu.id" target="_blank">posting.eqiyu.id</a> (Eqiyu Postiz Platform).
          </p>
        </div>
      </div>

      <!-- Main Content -->
      <div class="privacy-content" data-aos="fade-up">

        <!-- Introduction -->
        <div class="content-section">
          <h2>1. Introduction</h2>
          <p>
            Eqiyu Indonesia is committed to protecting your personal data and privacy. This policy describes what information we collect, how we use it, and the rights you have to control your data.
          </p>
          <p>
            This policy applies to all websites, platforms, and online services operated by Eqiyu Indonesia, including but not limited to
            eqiyu.id and posting.eqiyu.id.
          </p>
        </div>

        <!-- Information Collection -->
        <div class="content-section">
          <h2>2. Information We Collect</h2>

          <h3>2.1 Information You Provide</h3>
          <p>We collect personal information that you voluntarily provide when you:</p>
          <ul>
            <li>Register for an account or subscribe to our services</li>
            <li>Submit a form, questionnaire, or feedback</li>
            <li>Connect your social media accounts to Postiz</li>
            <li>Communicate with us via email or support chat</li>
          </ul>

          <h3>2.2 Automatic Information</h3>
          <p>We automatically collect technical data such as:</p>
          <ul>
            <li>IP address and browser type</li>
            <li>Device information and usage statistics</li>
            <li>Cookies and similar technologies</li>
            <li>Login and activity timestamps</li>
          </ul>
        </div>

        <!-- Use of Information -->
        <div class="content-section">
          <h2>3. How We Use Your Information</h2>
          <p>We use your data to:</p>
          <ul>
            <li>Operate and manage our online learning and social media management platforms</li>
            <li>Personalize user experiences</li>
            <li>Provide customer support and respond to inquiries</li>
            <li>Improve the quality and functionality of our services</li>
            <li>Ensure compliance with applicable laws and regulations</li>
          </ul>
        </div>

        <!-- Information Sharing -->
        <div class="content-section">
          <h2>4. Information Sharing and Disclosure</h2>
          <p>Eqiyu Indonesia does not sell or rent your data. We only share information in these cases:</p>
          <ul>
            <li>With your consent</li>
            <li>To comply with legal obligations or law enforcement requests</li>
            <li>To trusted third-party service providers (e.g., cloud hosting, analytics, or payment processors) under strict confidentiality agreements</li>
          </ul>
        </div>

        <!-- Data Security -->
        <div class="content-section">
          <h2>5. Data Security</h2>
          <p>We employ appropriate administrative, technical, and physical measures to safeguard your data, including:</p>
          <ul>
            <li>SSL/TLS encryption for data transmission</li>
            <li>Access control limited to authorized personnel</li>
            <li>Regular system audits and backups</li>
          </ul>
        </div>

        <!-- Rights -->
        <div class="content-section">
          <h2>6. Your Rights</h2>
          <p>You have the right to:</p>
          <ul>
            <li>Request access to your personal data</li>
            <li>Request corrections or updates</li>
            <li>Request deletion of your data</li>
            <li>Withdraw consent or restrict processing</li>
          </ul>
          <p>Requests can be made through our official contact email listed below.</p>
        </div>

        <!-- Third-Party Services -->
        <div class="content-section">
          <h2>7. Third-Party Integrations</h2>
          <p>
            Eqiyu Postiz may integrate with third-party platforms such as Facebook, Instagram, LinkedIn, and others via official APIs.
            We only access information you explicitly authorize, and all actions comply with each platform's privacy policy.
          </p>
        </div>

        <!-- Updates -->
        <div class="content-section">
          <h2>8. Changes to This Policy</h2>
          <p>
            We may revise this policy periodically. Updates will be posted on this page, and the “Effective Date” will reflect the latest revision.
          </p>
        </div>
      </div>

      <!-- Contact -->
      <div class="privacy-contact" data-aos="fade-up">
        <h2>Contact Us</h2>
        <p>If you have any questions or concerns regarding this Privacy Policy, please contact us:</p>
        <div class="contact-details">
          <p><strong>Email:</strong> contact@eqiyu.id</p>
          <p><strong>Website:</strong> <a href="https://eqiyu.id">https://eqiyu.id</a></p>
          <p><strong>Organization:</strong> Eqiyu Indonesia</p>
        </div>
      </div>

    </div>

  </section><!-- /Privacy Section -->

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
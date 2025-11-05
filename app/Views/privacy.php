<?php
$this->setVar('pageTitle', 'Privacy Policy | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
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
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Privacy Policy</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
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
<?= $this->endSection() ?>
<?php
$this->setVar('pageTitle', 'Terms of Service | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
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
      <h1 class="mb-2 mb-lg-0">Terms of Service</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
          <li class="current">Terms of Service</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <!-- Terms Of Service Section -->
  <section id="terms-of-service" class="terms-of-service section">

    <div class="container" data-aos="fade-up">

      <!-- Header -->
      <div class="tos-header text-center" data-aos="fade-up">
        <span class="last-updated">Last Updated: November 1, 2025</span>
        <h2>Terms of Service</h2>
        <p>Please read these Terms of Service carefully before using our websites and digital platforms operated by Eqiyu Indonesia.</p>
      </div>

      <!-- Content -->
      <div class="tos-content" data-aos="fade-up" data-aos-delay="200">

        <!-- Agreement -->
        <div id="agreement" class="content-section">
          <h3>1. Agreement to Terms</h3>
          <p>By accessing and using our websites, including <a href="https://eqiyu.id" target="_blank">eqiyu.id</a> and its subdomains such as <a href="https://posting.eqiyu.id" target="_blank">posting.eqiyu.id</a> (Eqiyu Postiz), you agree to be bound by these Terms of Service and our Privacy Policy. If you disagree with any part of these terms, please discontinue use of our services.</p>
          <div class="info-box">
            <i class="bi bi-info-circle"></i>
            <p>These Terms apply to all visitors, users, and others who access or use Eqiyu Indonesia’s services.</p>
          </div>
        </div>

        <!-- Intellectual Property -->
        <div id="intellectual-property" class="content-section">
          <h3>2. Intellectual Property Rights</h3>
          <p>All content, branding, and materials available on Eqiyu Indonesia’s websites and platforms, including logos, designs, and text, are the intellectual property of Eqiyu Indonesia or its licensors.</p>
          <ul class="list-items">
            <li>Reproduction or redistribution of content without written consent is prohibited.</li>
            <li>All trademarks and trade names remain the property of their respective owners.</li>
            <li>You may use our content only for personal, non-commercial purposes.</li>
          </ul>
        </div>

        <!-- User Accounts -->
        <div id="user-accounts" class="content-section">
          <h3>3. User Accounts</h3>
          <p>When creating an account with Eqiyu Indonesia or through the Eqiyu Postiz platform, you must provide accurate and current information. Failure to do so may result in suspension or termination of your account.</p>
          <div class="alert-box">
            <i class="bi bi-exclamation-triangle"></i>
            <div class="alert-content">
              <h5>Important Notice</h5>
              <p>You are responsible for safeguarding your login credentials and for all activities under your account. Eqiyu Indonesia is not responsible for any loss resulting from unauthorized use of your account.</p>
            </div>
          </div>
        </div>

        <!-- Acceptable Use -->
        <div id="prohibited" class="content-section">
          <h3>4. Acceptable Use and Prohibited Activities</h3>
          <p>You agree to use our services in a lawful and ethical manner. The following activities are strictly prohibited:</p>
          <ul class="prohibited-list">
            <li>Unauthorized access, hacking, or interference with system integrity</li>
            <li>Uploading malicious code, viruses, or harmful software</li>
            <li>Using Eqiyu services for spam, fraud, or illegal purposes</li>
            <li>Copying or redistributing content without permission</li>
            <li>Using the Postiz platform to violate the terms of third-party APIs (e.g., Meta, Instagram, Facebook)</li>
          </ul>
        </div>

        <!-- Third-Party Integrations -->
        <div id="third-party" class="content-section">
          <h3>5. Third-Party Services</h3>
          <p>Our services, including Eqiyu Postiz, may integrate with third-party platforms such as Facebook, Instagram, LinkedIn, and others. By connecting these accounts, you grant Eqiyu Indonesia permission to access limited information as allowed by those platforms.</p>
          <p>Eqiyu Indonesia is not responsible for the content, policies, or actions of third-party services.</p>
        </div>

        <!-- Limitation of Liability -->
        <div id="liability" class="content-section">
          <h3>6. Limitation of Liability</h3>
          <p>Eqiyu Indonesia is not liable for any direct, indirect, incidental, or consequential damages resulting from your use or inability to use our services, including but not limited to data loss, service interruption, or unauthorized access.</p>
        </div>

        <!-- Disclaimer -->
        <div id="disclaimer" class="content-section">
          <h3>7. Disclaimer</h3>
          <p>Our services are provided “as is” and “as available.” We make no warranties or representations of any kind, express or implied, regarding the operation, reliability, or availability of our services.</p>
        </div>

        <!-- Termination -->
        <div id="termination" class="content-section">
          <h3>8. Termination</h3>
          <p>We may suspend or terminate your access to our services immediately, without prior notice, for any breach of these Terms or for actions that may harm Eqiyu Indonesia’s integrity, users, or systems.</p>
        </div>

        <!-- Governing Law -->
        <div id="law" class="content-section">
          <h3>9. Governing Law</h3>
          <p>These Terms shall be governed by and construed in accordance with the laws of the Republic of Indonesia. Any disputes shall be resolved through the competent court located in Indonesia.</p>
        </div>

        <!-- Changes -->
        <div id="changes" class="content-section">
          <h3>10. Changes to Terms</h3>
          <p>Eqiyu Indonesia reserves the right to revise or replace these Terms at any time. Updated versions will be posted on this page, and the “Last Updated” date will reflect the latest revision.</p>
          <div class="notice-box">
            <i class="bi bi-bell"></i>
            <p>By continuing to use our services after such changes, you agree to be bound by the updated Terms of Service.</p>
          </div>
        </div>

      </div>

      <!-- Contact -->
      <div class="tos-contact" data-aos="fade-up" data-aos-delay="300">
        <div class="contact-box">
          <div class="contact-icon">
            <i class="bi bi-envelope"></i>
          </div>
          <div class="contact-content">
            <h4>Contact Us</h4>
            <p>If you have any questions about these Terms, please reach out to us:</p>
            <p><strong>Email:</strong> contact@eqiyu.id</p>
            <p><strong>Website:</strong> <a href="https://eqiyu.id" target="_blank">https://eqiyu.id</a></p>
            <p><strong>Organization:</strong> Eqiyu Indonesia</p>
          </div>
        </div>
      </div>

    </div>
  </section><!-- /Terms Of Service Section -->

</main>
<?= $this->endSection() ?>
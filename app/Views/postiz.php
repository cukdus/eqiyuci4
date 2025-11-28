<?php
$this->setVar('pageTitle', 'Postiz | Kelola Sosial Media Anda');
$this->setVar('metaDescription', 'Postiz membantu menjadwalkan konten dan mengelola sosial media dengan aman dan efisien.');
$this->setVar('metaKeywords', 'postiz, sosial media, jadwal konten, tiktok, instagram, facebook');
$this->setVar('canonicalUrl', base_url('postiz'));
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'index');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>
<main class="main">

  <section class="courses-hero section light-background">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
          <h1 class="mb-3">Kelola Sosial Media Anda dengan Postiz</h1>
          <p class="mb-4">Tools untuk menjadwalkan, mempublikasikan, dan mengelola konten lintas platform secara mudah.</p>
          <div class="hero-buttons">
            <a href="https://posting.eqiyu.id/" class="btn btn-primary">Mulai Sekarang</a>
          </div>
            <div class="mt-4">
                <a href="<?= base_url('terms') ?>" class="me-3">Terms</a>
                <a href="<?= base_url('privacy') ?>">Privacy</a>
            </div>
        </div>
      </div>
    </div>
  </section>

  <section id="about" class="about section light-background">
    <div class="container section-title aos-init aos-animate" data-aos="fade-up">
      <h2>Fasilitas</h2>
    </div>
    <div class="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4">
        <div class="col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-award"></i>
            </div>
            <h3>Sertifikat Resmi</h3>
            <p>
              Sertifikat yang dapat Anda dapatkan setelah menyelesaikan
              program pelatihan di EQIYU INDONESIA. Dan bisa di check
              validasi di website resmi EQIYU INDONESIA.
            </p>
          </div>
        </div>
        <div class="col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-file-earmark-text"></i>
            </div>
            <h3>Softcopy Modul</h3>
            <p>
              Materi pelatihan lengkap dalam bentuk digital, sehingga Anda
              dapat belajar kembali kapan saja dan di mana saja. Dan dapat
              di download ulang kapan pun.
            </p>
          </div>
        </div>
        <div class="col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-journal-text"></i>
            </div>
            <h3>Recipe Beverage</h3>
            <p>
              Kumpulan resep minuman spesial yang bisa langsung Anda
              praktikkan dan kembangkan untuk bisnis atau kreasi pribadi.
            </p>
          </div>
        </div>
        <div class="col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-egg-fried"></i>
            </div>
            <h3>Makan Siang</h3>
            <p>
              Menikmati sajian makan siang lezat selama program berlangsung
              untuk menjaga energi Anda tetap prima.
            </p>
          </div>
        </div>
        <div class="col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-cup-hot"></i>
            </div>
            <h3>Snack</h3>
            <p>
              Camilan istimewa disediakan untuk menemani waktu istirahat
              Anda selama sesi pelatihan.
            </p>
          </div>
        </div>
        <div class="col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-camera"></i>
            </div>
            <h3>Dokumentasi Peserta</h3>
            <p>
              Foto dan video dokumentasi kegiatan Anda selama pelatihan,
              sebagai kenangan sekaligus materi promosi personal.
            </p>
          </div>
        </div>
        <div class="col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-cup-straw"></i>
            </div>
            <h3>Bar Experience</h3>
            <p>
              Praktik langsung di area bar berstandar industri, dengan alat
              dan bahan baku yang telah disediakan secara gratis.
            </p>
          </div>
        </div>
        <div class="col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">
          <div class="mission-card">
            <div class="icon-box">
              <i class="bi bi-person-lines-fill"></i>
            </div>
            <h3>Free Konsultasi</h3>
            <p>
              Praktik langsung di area bar berstandar industri, dengan alat
              dan bahan baku yang telah disediakan secara gratis.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
<?= $this->endSection() ?>

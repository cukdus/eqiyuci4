<?php
$this->setVar('pageTitle', 'Jadwal Kelas | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
$this->setVar('metaDescription', 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.');
$this->setVar('metaKeywords', 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia');
$this->setVar('canonicalUrl', base_url());
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'jadwal');

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>
<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Jadwal Kelas</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
          <li class="current">Jadwal</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <!-- Courses Events Section -->
  <section id="courses-events" class="courses-events section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row">
        <div class="col-lg-8">
          <!-- Event Item -->
          <article
            class="event-card"
            data-aos="fade-up"
            data-aos-delay="400">
            <div class="row g-0">
              <div class="col-md-4">
                <div class="event-image">
                  <img
                    src="assets/img/education/events-5.webp"
                    class="img-fluid"
                    alt="Event Image" />
                  <div class="date-badge">
                    <span class="day">10</span>
                    <span class="month">Okt</span>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="event-content">
                  <div class="event-meta">
                    <span class="time"><i class="bi bi-clock"></i> 10 Okt - 12 Okt
                      2025</span>
                    <span class="location"><i class="bi bi-geo-alt"></i> Jogja</span>
                  </div>
                  <h3 class="event-title">
                    <a href="#">Mixology Class</a>
                  </h3>
                  <div class="event-footer">
                    <div class="instructor">
                      <i class="bi bi-people"></i><span>3 Orang Terdaftar | (Max 8 Orang)</span>
                    </div>
                    <div class="event-price">
                      <span class="price">Rp. 500.000,-</span>
                    </div>
                  </div>
                  <div class="event-actions">
                    <a href="#" class="btn btn-primary">Daftar Sekarang</a>
                    <a href="#" class="btn btn-outline">Pelajari Lebih Lanjut</a>
                  </div>
                </div>
              </div>
            </div>
          </article>
          <!-- End Event Item -->

          <!-- Event Item -->
          <article
            class="event-card"
            data-aos="fade-up"
            data-aos-delay="400">
            <div class="row g-0">
              <div class="col-md-4">
                <div class="event-image">
                  <img
                    src="assets/img/education/events-5.webp"
                    class="img-fluid"
                    alt="Event Image" />
                  <div class="date-badge">
                    <span class="day">10</span>
                    <span class="month">Okt</span>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="event-content">
                  <div class="event-meta">
                    <span class="time"><i class="bi bi-clock"></i> 10 Okt - 12 Okt
                      2025</span>
                    <span class="location"><i class="bi bi-geo-alt"></i> Jogja</span>
                  </div>
                  <h3 class="event-title">
                    <a href="#">Basic Barista & Latte Art</a>
                  </h3>
                  <div class="event-footer">
                    <div class="instructor">
                      <i class="bi bi-people"></i><span>3 Orang Terdaftar | (Max 8 Orang)</span>
                    </div>
                    <div class="event-price">
                      <span class="price">Rp. 500.000,-</span>
                    </div>
                  </div>
                  <div class="event-actions">
                    <a href="#" class="btn btn-primary">Daftar Sekarang</a>
                    <a href="#" class="btn btn-outline">Pelajari Lebih Lanjut</a>
                  </div>
                </div>
              </div>
            </div>
          </article>
          <!-- End Event Item -->

          <!-- Event Item -->
          <article
            class="event-card"
            data-aos="fade-up"
            data-aos-delay="400">
            <div class="row g-0">
              <div class="col-md-4">
                <div class="event-image">
                  <img
                    src="assets/img/education/events-5.webp"
                    class="img-fluid"
                    alt="Event Image" />
                  <div class="date-badge">
                    <span class="day">10</span>
                    <span class="month">Okt</span>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="event-content">
                  <div class="event-meta">
                    <span class="time"><i class="bi bi-clock"></i> 10 Okt - 12 Okt
                      2025</span>
                    <span class="location"><i class="bi bi-geo-alt"></i> Malang</span>
                  </div>
                  <h3 class="event-title">
                    <a href="#">Basic Barista & Latte Art</a>
                  </h3>
                  <div class="event-footer">
                    <div class="instructor">
                      <i class="bi bi-people"></i><span>3 Orang Terdaftar | (Max 8 Orang)</span>
                    </div>
                    <div class="event-price">
                      <span class="price">Rp. 500.000,-</span>
                    </div>
                  </div>
                  <div class="event-actions">
                    <a href="#" class="btn btn-primary">Daftar Sekarang</a>
                    <a href="#" class="btn btn-outline">Pelajari Lebih Lanjut</a>
                  </div>
                </div>
              </div>
            </div>
          </article>
          <!-- End Event Item -->

          <!-- Event Item -->
          <article
            class="event-card"
            data-aos="fade-up"
            data-aos-delay="400">
            <div class="row g-0">
              <div class="col-md-4">
                <div class="event-image">
                  <img
                    src="assets/img/education/events-5.webp"
                    class="img-fluid"
                    alt="Event Image" />
                  <div class="date-badge">
                    <span class="day">10</span>
                    <span class="month">Okt</span>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="event-content">
                  <div class="event-meta">
                    <span class="time"><i class="bi bi-clock"></i> 10 Okt - 12 Okt
                      2025</span>
                    <span class="location"><i class="bi bi-geo-alt"></i> Malang</span>
                  </div>
                  <h3 class="event-title">
                    <a href="#">Espresso And Latte Art Class</a>
                  </h3>
                  <div class="event-footer">
                    <div class="instructor">
                      <i class="bi bi-people"></i><span>3 Orang Terdaftar | (Max 8 Orang)</span>
                    </div>
                    <div class="event-price">
                      <span class="price">Rp. 500.000,-</span>
                    </div>
                  </div>
                  <div class="event-actions">
                    <a href="#" class="btn btn-primary">Daftar Sekarang</a>
                    <a href="#" class="btn btn-outline">Pelajari Lebih Lanjut</a>
                  </div>
                </div>
              </div>
            </div>
          </article>
          <!-- End Event Item -->

          <!-- Pagination -->
          <nav
            class="pagination-wrapper"
            data-aos="fade-up"
            data-aos-delay="500">
            <ul class="pagination justify-content-center">
              <li class="page-item disabled">
                <a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a>
              </li>
              <li class="page-item active">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">2</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">3</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
              </li>
            </ul>
          </nav>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
          <!-- Filter Widget -->
          <div
            class="sidebar-widget filter-widget"
            data-aos="fade-up"
            data-aos-delay="300">
            <h4 class="widget-title">Filter Jadwal</h4>
            <div class="filter-content">
              <div class="filter-group">
                <label class="filter-label">Jenis Kursus</label>
                <select class="form-select">
                  <option value="">Semua Kursus</option>
                  <option value="kode kelas">Basic barista & Latte Art</option>
                  <option value="kode kelas">Workshop Membangun Bisnis Cafe & Kedai Kopi</option>
                  <option value="kode kelas">Private Class Beverage & Bisnis Culinary</option>
                  <option value="kode kelas">Masterclass</option>
                </select>
              </div>
              <div class="filter-group">
                <label class="filter-label">Jadwal Bulan</label>
                <select class="form-select">
                  <option value="">Semua</option>
                  <option value="Januari">Januari</option>
                  <option value="Februari">Februari</option>
                  <option value="Maret">Maret</option>
                  <option value="April">April</option>
                </select>
              </div>
              <div class="filter-group">
                <label class="filter-label">Kota Kelas</label>
                <select class="form-select">
                  <option value="">Semua Kota</option>
                  <option value="malang">Malang</option>
                  <option value="jogja">Jogja</option>
                </select>
              </div>
              <button class="btn btn-primary filter-apply-btn">
                Terapkan
              </button>
            </div>
          </div>
          <!-- End Filter Widget -->

          <!-- Upcoming Events Widget -->
          <div
            class="sidebar-widget upcoming-widget"
            data-aos="fade-up"
            data-aos-delay="400">
            <h4 class="widget-title">Jadwal Mendatang</h4>
            <div class="upcoming-list">
              <div class="upcoming-item">
                <div class="upcoming-date">
                  <span class="day">18</span>
                  <span class="month">Dec</span>
                </div>
                <div class="upcoming-content">
                  <h5 class="upcoming-title">
                    <a href="#">Basic Barista & Latte Art</a>
                  </h5>
                  <div class="upcoming-meta">
                    <span class="time"><i class="bi bi-geo-alt"></i> Malang</span>
                    <span class="price">Rp. 1.500.000,-</span>
                  </div>
                </div>
              </div>

              <div class="upcoming-item">
                <div class="upcoming-date">
                  <span class="day">25</span>
                  <span class="month">Dec</span>
                </div>
                <div class="upcoming-content">
                  <h5 class="upcoming-title">
                    <a href="#">Mixology Class</a>
                  </h5>
                  <div class="upcoming-meta">
                    <span class="time"><i class="bi bi-geo-alt"></i> Jogja</span>
                    <span class="price">Rp. 500.000,-</span>
                  </div>
                </div>
              </div>

              <div class="upcoming-item">
                <div class="upcoming-date">
                  <span class="day">02</span>
                  <span class="month">Jan</span>
                </div>
                <div class="upcoming-content">
                  <h5 class="upcoming-title">
                    <a href="#">Basic Barista & Latte Art</a>
                  </h5>
                  <div class="upcoming-meta">
                    <span class="time"><i class="bi bi-geo-alt"></i> Malang</span>
                    <span class="price">Rp. 1.500.000,-</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Upcoming Events Widget -->

          <!-- Newsletter Widget -->
          <div
            class="sidebar-widget newsletter-widget"
            data-aos="fade-up"
            data-aos-delay="500">
            <h4 class="widget-title">Stay Updated</h4>
            <p>
              Berlangganan buletin kami dan jangan lewatkan jadwal penting
              dan pengumuman kursus.
            </p>
            <form
              action="forms/newsletter.php"
              method="post"
              class="php-email-form newsletter-form">
              <input
                type="email"
                name="email"
                placeholder="alamat email anda"
                required="" />
              <button type="submit">Berlangganan</button>
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">
                Your subscription request has been sent. Thank you!
              </div>
            </form>
          </div>
          <!-- End Newsletter Widget -->
        </div>
      </div>
    </div>
  </section>
  <!-- /Courses Events Section -->
</main>
<?= $this->endSection() ?>
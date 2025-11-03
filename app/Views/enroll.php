<?php
$pageTitle = 'Form Pendaftaran | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea
      Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe &
      Coffeshop.';
$metaDescription = 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.';
$metaKeywords = 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia';
$canonicalUrl = 'https://eqiyu.id/';
$bodyClass = 'index-page';
$activePage = '';
include __DIR__ . '/includes/header.php';
?>
<main class="main">
      <!-- Page Title -->
      <div class="page-title light-background">
        <div
          class="container d-lg-flex justify-content-between align-items-center">
          <h1 class="mb-2 mb-lg-0">Formulir Pendaftaran</h1>
          <nav class="breadcrumbs">
            <ol>
              <li><a href="index.php">Home</a></li>
              <li class="current">Pendaftaran</li>
            </ol>
          </nav>
        </div>
      </div>
      <!-- End Page Title -->

      <!-- Enroll Section -->
      <section id="enroll" class="enroll section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <div class="enrollment-form-wrapper">
                <div
                  class="enrollment-header text-center mb-5"
                  data-aos="fade-up"
                  data-aos-delay="200">
                  <h2>Form Pendaftaran</h2>
                  <p>
                    Mohon di isi dengan benar, karena data yang di isi akan di
                    gunakan untuk pembuatan sertifikat.
                  </p>
                </div>

                <form
                  class="enrollment-form"
                  data-aos="fade-up"
                  data-aos-delay="300">
                  <div class="row mb-4">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="course" class="form-label"
                          >Pilihan Kursus *</label
                        >
                        <select
                          id="course"
                          name="course"
                          class="form-select"
                          required="">
                          <option value="">Pilih kursus...</option>
                          <option value="web-development">
                            Full Stack Web Development
                          </option>
                          <option value="data-science">
                            Data Science &amp; Analytics
                          </option>
                          <option value="digital-marketing">
                            Digital Marketing Mastery
                          </option>
                          <option value="ui-ux-design">
                            UI/UX Design Fundamentals
                          </option>
                          <option value="cybersecurity">
                            Cybersecurity Essentials
                          </option>
                          <option value="mobile-development">
                            Mobile App Development
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="location" class="form-label"
                          >Lokasi kelas *</label
                        >
                        <select
                          id="location"
                          name="location"
                          class="form-select"
                          required="">
                          <option value="">Pilih lokasi kelas...</option>
                          <option value="web-development">
                            Full Stack Web Development
                          </option>
                          <option value="data-science">
                            Data Science &amp; Analytics
                          </option>
                          <option value="digital-marketing">
                            Digital Marketing Mastery
                          </option>
                          <option value="ui-ux-design">
                            UI/UX Design Fundamentals
                          </option>
                          <option value="cybersecurity">
                            Cybersecurity Essentials
                          </option>
                          <option value="mobile-development">
                            Mobile App Development
                          </option>
                        </select>
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="form-group">
                        <label for="schedule" class="form-label"
                          >Jadwal *</label
                        >
                        <select
                          id="schedule"
                          name="schedule"
                          class="form-select"
                          required="">
                          <option value="">Pilih jadwal...</option>
                          <option value="web-development">
                            Full Stack Web Development
                          </option>
                          <option value="data-science">
                            Data Science &amp; Analytics
                          </option>
                          <option value="digital-marketing">
                            Digital Marketing Mastery
                          </option>
                          <option value="ui-ux-design">
                            UI/UX Design Fundamentals
                          </option>
                          <option value="cybersecurity">
                            Cybersecurity Essentials
                          </option>
                          <option value="mobile-development">
                            Mobile App Development
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="location" class="form-label"
                          >Voucher *</label
                        >
                        <div class="input-group" style="position: relative">
                          <input
                            type="text"
                            class="form-control"
                            id="kodeVoucher"
                            name="kode_voucher"
                            placeholder="Kosongkan jika tidak ada" /><img
                            src="chrome-extension://cohpigckifhbbggfedenhafihmmpidkm/icon.png"
                            class="fa"
                            width="0"
                            style="
                              position: absolute;
                              top: 5px;
                              right: 137px;
                              width: 25px;
                            " />
                          <button
                            class="btn btn-warning"
                            type="button"
                            id="checkVoucherBtn"
                            style="min-width: 120px">
                            Check Voucher
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="schedule" class="form-label"
                          >Model Pembayaran *</label
                        >
                        <select
                          id="Pembayaran"
                          name="Pembayaran"
                          class="form-select"
                          required="">
                          <option value="">Pilih model pembayaran...</option>
                          <option value="web-development">
                            Full Stack Web Development
                          </option>
                          <option value="data-science">
                            Data Science &amp; Analytics
                          </option>
                          <option value="digital-marketing">
                            Digital Marketing Mastery
                          </option>
                          <option value="ui-ux-design">
                            UI/UX Design Fundamentals
                          </option>
                          <option value="cybersecurity">
                            Cybersecurity Essentials
                          </option>
                          <option value="mobile-development">
                            Mobile App Development
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="alert alert-info">
                      <small>
                        <strong>Detail Pembayaran:</strong><br />
                        <div id="paymentDetails">
                          Pilih kelas untuk melihat detail pembayaran.
                        </div>
                      </small>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="firstName" class="form-label"
                          >Nama Lengkap</label
                        >
                        <input
                          type="text"
                          id="firstName"
                          name="firstName"
                          class="form-control"
                          required=""
                          autocomplete="given-name" />
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email" class="form-label">Email *</label>
                        <input
                          type="email"
                          id="email"
                          name="email"
                          class="form-control"
                          required=""
                          autocomplete="email" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="phone" class="form-label"
                          >Nomor Tlp WA</label
                        >
                        <input
                          type="tel"
                          id="phone"
                          name="phone"
                          class="form-control"
                          autocomplete="tel" />
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="motivation" class="form-label"
                          >Alamat *</label
                        >
                        <textarea
                          id="address"
                          name="address"
                          class="form-control"
                          rows="3"
                          placeholder="Share your goals and what you hope to achieve..."></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="kecamatan" class="form-label"
                          >Kecamatan *</label
                        >
                        <input
                          type="text"
                          id="kecamatan"
                          name="kecamatan"
                          class="form-control"
                          required=""
                          autocomplete="email" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="phone" class="form-label"
                          >Kab. / Kota *</label
                        >
                        <input
                          type="text"
                          id="Kota"
                          name="Kota"
                          class="form-control" />
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="provinsi" class="form-label"
                          >Provinsi *</label
                        >
                        <input
                          type="text"
                          id="provinsi"
                          name="provinsi"
                          class="form-control"
                          required=""
                          autocomplete="email" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="kodepos" class="form-label"
                          >Kode Pos *</label
                        >
                        <input
                          type="text"
                          id="kodepos"
                          name="kodepos"
                          class="form-control" />
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-12">
                      <div class="form-group">
                        <div class="agreement-section">
                          <div class="form-check">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="terms"
                              name="terms"
                              required="" />
                            <label class="form-check-label" for="terms">
                              Saya setuju dengan
                              <a href="#">Syarat dan Ketentuan</a> dan
                              <a href="#">Kebijakan Privasi</a> *
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-12 text-center">
                      <button type="submit" class="btn btn-enroll">
                        <i class="bi bi-check-circle me-2"></i>
                        Daftar Sekarang
                      </button>
                      <p class="enrollment-note mt-3">
                        <i class="bi bi-shield-check"></i>
                        Informasi anda aman dan tidak akan pernah dibagikan
                        kepada pihak ketiga.
                      </p>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <!-- End Form Column -->

            <div class="col-lg-4 d-none d-lg-block">
              <div
                class="enrollment-benefits"
                data-aos="fade-left"
                data-aos-delay="400">
                <h3>Kenapa Memilih Kami?</h3>
                <div class="benefit-item">
                  <div class="benefit-icon">
                    <i class="bi bi-trophy"></i>
                  </div>
                  <div class="benefit-content">
                    <h4>Trainer Ahli</h4>
                    <p>
                      Belajar dari para profesional industri dengan pengalaman
                      dunia nyata selama bertahun-tahun.
                    </p>
                  </div>
                </div>
                <!-- End Benefit Item -->

                <div class="benefit-item">
                  <div class="benefit-icon">
                    <i class="bi bi-clock"></i>
                  </div>
                  <div class="benefit-content">
                    <h4>Fasilitas Terbaik</h4>
                    <p>
                      Belajar dengan fasilitas dan peralatan terkini yang
                      mendukung pengalaman belajar Anda.
                    </p>
                  </div>
                </div>
                <!-- End Benefit Item -->

                <div class="benefit-item">
                  <div class="benefit-icon">
                    <i class="bi bi-award"></i>
                  </div>
                  <div class="benefit-content">
                    <h4>Sertifikat Kelas</h4>
                    <p>
                      Bukti sah bahwa seseorang telah mengikuti dan
                      menyelesaikan kelas tertentu.
                    </p>
                  </div>
                </div>
                <!-- End Benefit Item -->

                <div class="enrollment-stats mt-4">
                  <div class="stat-item">
                    <span class="stat-number">1298</span>
                    <span class="stat-label">Lulusan Kelas Barista</span>
                  </div>
                  <div class="stat-item">
                    <span class="stat-number">2515</span>
                    <span class="stat-label">Total Lulusan</span>
                  </div>
                  <div class="stat-item">
                    <span class="stat-number">4.9/5</span>
                    <span class="stat-label">Rating Rata-rata</span>
                  </div>
                </div>
                <!-- End Stats -->
              </div>
            </div>
            <!-- End Benefits Column -->
          </div>
        </div>
      </section>
      <!-- /Enroll Section -->
    </main>
<?php include __DIR__ . '/includes/footer.php'; ?>

<?php
$this->setVar('pageTitle', 'Daftar Kursus | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
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
                  id="publicEnrollForm"
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
                          <?php if (!empty($kelasList)): ?>
                            <?php foreach ($kelasList as $k): ?>
                              <option
                                value="<?= esc($k['kode_kelas']) ?>"
                                data-lokasi="<?= isset($k['kota_tersedia']) ? esc($k['kota_tersedia']) : '' ?>"
                                data-harga="<?= isset($k['harga']) ? esc($k['harga']) : '0' ?>"
                                data-kategori="<?= isset($k['kategori']) ? esc($k['kategori']) : '' ?>"
                              >
                                <?= esc($k['kode_kelas']) ?> - <?= esc($k['nama_kelas']) ?>
                              </option>
                            <?php endforeach; ?>
                          <?php endif; ?>
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
                          >Pilih Jadwal *</label
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
                          <div id="voucherFeedback" class="form-text"></div>
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
                          <option value="">-- Pilih Pembayaran --</option>
                          <option value="lunas">Pembayaran penuh</option>
                          <option value="DP 50%">DP 50%</option>
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
                        <input type="hidden" id="biaya_dibayar" name="biaya_dibayar" value="" />
                        <input type="hidden" id="biaya_tagihan" name="biaya_tagihan" value="" />
                        <input type="hidden" id="biaya_total" name="biaya_total" value="" />
                        <input type="hidden" id="kode_unik" name="kode_unik" value="" />
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
<!-- Loading Modal -->
<div id="loadingModal" class="modal" tabindex="-1" style="display:none; background: rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Memproses Pendaftaran</h5>
      </div>
      <div class="modal-body">
        <p>Mohon tunggu, data sedang disimpan...</p>
      </div>
    </div>
  </div>
  </div>

<!-- Success Modal -->
<div id="successModal" class="modal" tabindex="-1" style="display:none; background: rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pendaftaran Berhasil</h5>
      </div>
      <div class="modal-body">
        <p>Terima kasih. Pendaftaran Anda berhasil disimpan.</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="successCloseBtn" class="btn btn-primary">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  (function () {
    const courseSelect = document.getElementById('course');
    const locationSelect = document.getElementById('location');
    const scheduleSelect = document.getElementById('schedule');
    const paymentSelect = document.getElementById('Pembayaran');
    const paymentDetails = document.getElementById('paymentDetails');
    // Kode unik pembayaran: siapkan dua kode untuk DP dan Tagihan (berbeda)
    let kodeUnikDP = Math.floor(100 + Math.random() * 900);
    let kodeUnikTagihan = Math.floor(100 + Math.random() * 900);
    if (kodeUnikTagihan === kodeUnikDP) {
      kodeUnikTagihan = (kodeUnikTagihan % 999) + 1;
      if (kodeUnikTagihan < 100) kodeUnikTagihan += 100;
    }

    // Kota map injected from PHP
    const allCities = (function () {
      const map = {};
      <?php if (!empty($kotaOptions)): ?>
        <?php
        foreach ($kotaOptions as $k):
          $kode = (string) ($k['kode'] ?? '');
          $nama = (string) ($k['nama'] ?? '');
          ?>
          map['<?= addslashes($kode) ?>'] = '<?= addslashes($nama) ?>';
        <?php endforeach; ?>
      <?php endif; ?>
      return map;
    })();

    function formatDate(isoStr) {
      if (!isoStr) return '';
      const d = new Date(isoStr);
      if (isNaN(d.getTime())) return isoStr;
      return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function isOnlineSelected() {
      const opt = courseSelect.options[courseSelect.selectedIndex];
      if (!opt) return false;
      const kategori = (opt.getAttribute('data-kategori') || '').toLowerCase();
      return kategori === 'kelasonline' || kategori === 'kursusonline' || kategori === 'online';
    }

    function isPrivateSelected() {
      const opt = courseSelect.options[courseSelect.selectedIndex];
      if (!opt) return false;
      const label = (opt.textContent || '').toLowerCase();
      return label.includes('private');
    }

    function clearSelect(sel, placeholder) {
      sel.innerHTML = '';
      if (placeholder) {
        const ph = document.createElement('option');
        ph.value = '';
        ph.textContent = placeholder;
        sel.appendChild(ph);
      }
      sel.value = '';
    }

    async function populateLokasiForSelectedCourse() {
      clearSelect(locationSelect, 'Pilih kota...');
      const opt = courseSelect.options[courseSelect.selectedIndex];
      if (!opt) return;
      const lokasiRaw = (opt.getAttribute('data-lokasi') || '').trim().toLowerCase();
      // Hanya tampilkan kota_tersedia dari tabel kelas (abaikan token lama seperti 'se-dunia')
      const parts = lokasiRaw
        .split(',')
        .map(s => s.trim())
        .filter(Boolean)
        .filter(code => code !== 'se-dunia');
      parts.forEach(kode => {
        const nama = allCities[kode] || kode;
        const o = document.createElement('option');
        o.value = kode;
        o.textContent = nama;
        locationSelect.appendChild(o);
      });
      locationSelect.disabled = false;
      // Reset schedule when course changes
      clearSelect(scheduleSelect, 'Pilih jadwal...');
      scheduleSelect.disabled = true;
    }

    async function loadJadwalForSelected() {
      clearSelect(scheduleSelect, 'Memuat jadwal...');
      scheduleSelect.disabled = true;
      const kode = courseSelect.value ? courseSelect.value.trim() : '';
      const kota = locationSelect.value ? locationSelect.value.trim().toLowerCase() : '';
      if (!kode || !kota) {
        clearSelect(scheduleSelect, 'Pilih jadwal...');
        return;
      }
      if (isOnlineSelected()) {
        clearSelect(scheduleSelect, 'Setiap Saat');
        scheduleSelect.disabled = true;
        scheduleSelect.required = false;
        return;
      }
      if (isPrivateSelected()) {
        clearSelect(scheduleSelect, 'Jadwal akan ditentukan kemudian');
        scheduleSelect.disabled = true;
        scheduleSelect.required = false;
        return;
      }
      try {
        const url = '<?= base_url('api/jadwal/by-kode') ?>' + '?kode_kelas=' + encodeURIComponent(kode);
        const res = await fetch(url);
        const json = await res.json();
        clearSelect(scheduleSelect, 'Pilih jadwal...');
        if (!json || !json.success || !Array.isArray(json.data)) {
          const msg = document.createElement('option');
          msg.value = '';
          msg.textContent = 'Jadwal belum tersedia';
          scheduleSelect.appendChild(msg);
          return;
        }
        const rows = Array.isArray(json.data) ? json.data : [];
        const matched = rows.filter(j => {
          const jl = (j.lokasi || '').trim().toLowerCase();
          const slName = (allCities && allCities[kota]) ? String(allCities[kota]).trim().toLowerCase() : '';
          return jl === kota || (slName && jl === slName);
        });
        if (matched.length === 0) {
          const msg = document.createElement('option');
          msg.value = '';
          msg.textContent = 'Belum ada jadwal untuk kelas dan lokasi ini.';
          scheduleSelect.appendChild(msg);
          return;
        }
        matched.forEach(j => {
          const o = document.createElement('option');
          o.value = String(j.id);
          const mulai = formatDate(j.tanggal_mulai);
          const selesai = formatDate(j.tanggal_selesai);
          o.textContent = `${mulai}${selesai ? ' s/d ' + selesai : ''}`;
          scheduleSelect.appendChild(o);
        });
        scheduleSelect.disabled = false;
      } catch (err) {
        clearSelect(scheduleSelect, 'Gagal memuat jadwal');
      }
    }

    function parseHargaFromSelected() {
      const opt = courseSelect.options[courseSelect.selectedIndex];
      if (!opt) return 0;
      const hargaStr = opt.getAttribute('data-harga') || '0';
      const h = parseInt(hargaStr, 10);
      return Number.isFinite(h) ? h : 0;
    }

    let currentDiskonPersen = 0; // Integrasi voucher dapat mengubah ini ke >0
    // Hook seperti admin: window.__setVoucherDiscountPercent(persen, apply)
    window.__setVoucherDiscountPercent = function (persen, apply) {
      const v = parseInt(persen, 10);
      currentDiskonPersen = (apply === true && Number.isFinite(v) && v > 0) ? Math.min(v, 100) : 0;
      updatePaymentDetails();
    };

    // Check voucher seperti admin (public API)
    (function voucherModule(){
      const kodeInput = document.getElementById('kodeVoucher');
      const checkBtn = document.getElementById('checkVoucherBtn');
      const feedback = document.getElementById('voucherFeedback');
      let appliedVoucher = null;

      function setFeedback(text, type) {
        if (!feedback) return;
        feedback.textContent = text || '';
        feedback.className = 'form-text' + (type ? ' text-' + type : '');
      }

      async function doCheck() {
        const kode = (kodeInput && kodeInput.value || '').trim();
        const kodeKelas = (courseSelect && courseSelect.value || '').trim();
        if (!kode) {
          setFeedback('Masukkan kode voucher terlebih dahulu', 'warning');
          if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
          appliedVoucher = null; return;
        }
        if (!kodeKelas) {
          setFeedback('Pilih kelas terlebih dahulu sebelum cek voucher', 'warning');
          if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
          appliedVoucher = null; return;
        }
        checkBtn.disabled = true;
        const originalText = checkBtn.textContent;
        checkBtn.textContent = 'Checking...';
        try {
          const res = await fetch('<?= base_url('api/voucher/check') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ kode_voucher: kode, kode_kelas: kodeKelas }),
          });
          const data = await res.json();
          if (data && data.found) {
            const persen = data.diskon_persen;
            const voucherNamaKelas = data.voucher_kelas_nama || '';
            const voucherKodeKelas = data.voucher_kelas_kode || '';
            const validDate = data.validDate !== false;
            const validForClass = (data.validForClass === true);

            if (data.validForClass === false) {
              const kelasInfo = voucherNamaKelas || voucherKodeKelas || 'kelas tertentu';
              setFeedback(`Voucher untuk ${kelasInfo}. Tidak bisa digunakan pada kelas yang berbeda.`, 'danger');
              if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
              appliedVoucher = null;
            } else if (!validDate) {
              setFeedback('Voucher ditemukan namun di luar masa berlaku.', 'warning');
              if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
              appliedVoucher = null;
            } else {
              const msg = `Voucher ditemukan${persen ? ' • Diskon ' + persen + '%' : ''}` + (voucherNamaKelas ? ` • Untuk kelas: ${voucherNamaKelas}` : (voucherKodeKelas ? ` • Kode kelas: ${voucherKodeKelas}` : ''));
              setFeedback(msg, 'success');
              if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(persen || 0, true);
              appliedVoucher = { code: kode, kelasKode: voucherKodeKelas, kelasNama: voucherNamaKelas, persen: persen || 0 };
            }
          } else {
            setFeedback((data && data.message) ? data.message : 'Voucher tidak ditemukan', 'danger');
            if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
            appliedVoucher = null;
          }
        } catch (e) {
          setFeedback('Gagal cek voucher. Coba lagi.', 'danger');
          if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
          appliedVoucher = null;
        } finally {
          checkBtn.disabled = false;
          checkBtn.textContent = originalText;
        }
      }

      if (checkBtn) { checkBtn.addEventListener('click', doCheck); }
      if (kodeInput) {
        kodeInput.addEventListener('input', function(){
          setFeedback('', '');
          if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
          appliedVoucher = null;
          updatePaymentDetails();
        });
      }
      // Reset voucher if course changes
      courseSelect.addEventListener('change', function(){
        setFeedback('', '');
        if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
        appliedVoucher = null;
      });
    })();

    function updatePaymentDetails() {
      if (!paymentDetails) return;
      const harga = parseHargaFromSelected();
      const diskon = Math.round(harga * currentDiskonPersen / 100);
      const subtotal = Math.max(0, harga - diskon);

      // Metode pembayaran: default ke lunas kecuali value == 'DP 50%'
      const metode = paymentSelect ? (paymentSelect.value || '').trim() : '';
      const isDP = (metode.toLowerCase() === 'dp 50%');
      const nominal = isDP ? Math.round(subtotal * 0.5) : subtotal;
      // Total akan dihitung spesifik sesuai metode

      paymentDetails.classList.remove('d-none');
      if (isDP) {
        const dpAmount = Math.round(subtotal * 0.5);
        const sisaAmount = subtotal - dpAmount;
        const totalDPBayar = dpAmount + kodeUnikDP;
        const totalTagihan = sisaAmount + kodeUnikTagihan;
        // Set hidden fields untuk backend
        document.getElementById('biaya_dibayar').value = String(totalDPBayar);
        document.getElementById('biaya_tagihan').value = String(totalTagihan);
        document.getElementById('biaya_total').value = String(totalDPBayar + totalTagihan);
        // Untuk DP, gunakan kode tagihan sebagai kode_unik jika backend membutuhkannya
        document.getElementById('kode_unik').value = String(kodeUnikTagihan);
        paymentDetails.innerHTML = `
          <div>Harga: Rp ${harga.toLocaleString('id-ID')}</div>
          ${currentDiskonPersen > 0 ? `<div>Diskon: Rp ${diskon.toLocaleString('id-ID')} (${currentDiskonPersen}% voucher)</div><div>Harga Setelah Diskon: Rp ${subtotal.toLocaleString('id-ID')}</div>` : `<div>Subtotal: Rp ${subtotal.toLocaleString('id-ID')}</div>`}
          <div>Metode: DP 50%</div>
          <div>DP 50%: Rp ${dpAmount.toLocaleString('id-ID')}</div>
          <div>Kode Unik: Rp ${kodeUnikDP.toLocaleString('id-ID')}</div>
          <div><strong>Total DP Yang Harus Dibayar: Rp ${totalDPBayar.toLocaleString('id-ID')}</strong></div>
        `;
      } else {
        // Full payment: tagihan = 0; dibayar = subtotal; total = subtotal + kode unik
        const kodeUnikFull = Math.floor(100 + Math.random() * 900);
        document.getElementById('biaya_dibayar').value = String(subtotal + kodeUnikFull);
        document.getElementById('biaya_tagihan').value = String(0);
        document.getElementById('biaya_total').value = String(subtotal + kodeUnikFull);
        document.getElementById('kode_unik').value = String(kodeUnikFull);
        paymentDetails.innerHTML = `
          <div>Harga: Rp ${harga.toLocaleString('id-ID')}</div>
          ${currentDiskonPersen > 0 ? `<div>Diskon: Rp ${diskon.toLocaleString('id-ID')} (${currentDiskonPersen}% voucher)</div><div>Harga Setelah Diskon: Rp ${subtotal.toLocaleString('id-ID')}</div>` : `<div>Subtotal: Rp ${subtotal.toLocaleString('id-ID')}</div>`}
          <div>Metode: Pembayaran penuh</div>
          <div>Kode unik: Rp ${kodeUnikFull.toLocaleString('id-ID')}</div>
          <div><strong>Total Pembayaran Penuh: Rp ${(subtotal + kodeUnikFull).toLocaleString('id-ID')}</strong></div>
        `;
      }
    }

    function applyPreselection() {
      const selectedKodeServer = '<?= addslashes($selectedKode ?? '') ?>';
      if (selectedKodeServer) {
        courseSelect.value = selectedKodeServer;
      } else {
        const last = localStorage.getItem('lastViewedKelas') || '';
        if (last) {
          courseSelect.value = last;
        }
      }
    }

    // Event wiring
    courseSelect.addEventListener('change', function () {
      populateLokasiForSelectedCourse();
      // Untuk kelas online, jadwal bebas dan select jadwal dinonaktifkan
      if (isOnlineSelected()) {
        clearSelect(scheduleSelect, 'Setiap Saat');
        scheduleSelect.disabled = true;
        scheduleSelect.required = false;
      }
      if (isPrivateSelected()) {
        clearSelect(scheduleSelect, 'Jadwal akan ditentukan kemudian');
        scheduleSelect.disabled = true;
        scheduleSelect.required = false;
      }
      updatePaymentDetails();
    });
    locationSelect.addEventListener('change', function () {
      loadJadwalForSelected();
    });
    if (paymentSelect) {
      paymentSelect.addEventListener('change', function () {
        updatePaymentDetails();
      });
    }

    // Init on load
    applyPreselection();
    populateLokasiForSelectedCourse();
    if (isOnlineSelected()) {
      clearSelect(scheduleSelect, 'Setiap Saat');
      scheduleSelect.disabled = true;
      scheduleSelect.required = false;
    }
    updatePaymentDetails();
    // Jadwal will be loaded when kota is chosen
  })();
</script>
<script>
  (function(){
    const form = document.getElementById('publicEnrollForm');
    const loadingModal = document.getElementById('loadingModal');
    const successModal = document.getElementById('successModal');
    const successCloseBtn = document.getElementById('successCloseBtn');

    function show(el){ el.style.display = 'block'; }
    function hide(el){ el.style.display = 'none'; }

    if (form) {
      form.addEventListener('submit', async function(e){
        e.preventDefault();
        // Ensure hidden fields reflect latest calculation
        try { updatePaymentDetails(); } catch (err) {}

        // Build payload mapping to backend expected fields
        const fd = new FormData(form);
        const payload = new URLSearchParams();
        payload.set('kode_kelas', fd.get('course') || '');
        payload.set('course', fd.get('course') || ''); // keep original for redundancy
        payload.set('location', fd.get('location') || '');
        payload.set('schedule', fd.get('schedule') || '');
        payload.set('Pembayaran', fd.get('Pembayaran') || '');
        payload.set('status_pembayaran', fd.get('Pembayaran') || '');
        payload.set('kode_voucher', fd.get('kode_voucher') || '');
        payload.set('firstName', fd.get('firstName') || '');
        payload.set('email', fd.get('email') || '');
        payload.set('phone', fd.get('phone') || '');
        payload.set('address', fd.get('address') || '');
        payload.set('kecamatan', fd.get('kecamatan') || '');
        payload.set('Kota', fd.get('Kota') || '');
        payload.set('provinsi', fd.get('provinsi') || '');
        payload.set('kodepos', fd.get('kodepos') || '');
        // hidden payment fields
        payload.set('biaya_dibayar', document.getElementById('biaya_dibayar').value || '');
        payload.set('biaya_tagihan', document.getElementById('biaya_tagihan').value || '');
        payload.set('biaya_total', document.getElementById('biaya_total').value || '');
        payload.set('kode_unik', document.getElementById('kode_unik').value || '');

        show(loadingModal);
        try {
          const res = await fetch('<?= base_url('daftar/submit') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: payload.toString(),
          });
          const data = await res.json();
          hide(loadingModal);
          if (data && data.ok) {
            show(successModal);
          } else {
            alert((data && data.error) ? data.error : 'Gagal menyimpan pendaftaran');
          }
        } catch(err) {
          hide(loadingModal);
          alert('Terjadi kesalahan jaringan. Coba lagi.');
        }
      });
    }

    if (successCloseBtn) {
      successCloseBtn.addEventListener('click', function(){
        hide(successModal);
        window.location.href = '<?= base_url('/') ?>';
      });
    }
  })();
</script>
                
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
                    <span class="stat-number"><?= number_format($total_alumni ?? 0) ?></span>
                    <span class="stat-label">Total Alumni</span>
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
<?= $this->endSection() ?>

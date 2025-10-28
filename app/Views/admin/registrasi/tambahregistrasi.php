<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Tambah Registrasi</h5>
      <a href="<?= base_url('admin/registrasi') ?>" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card card-outline card-success">
          <div class="card-header">
            <h3 class="card-title">Form Registrasi</h3>
          </div>
          <div class="card-body">
            <?php if (session('errors')): ?>
              <div class="alert alert-danger">
                Terjadi kesalahan:
                <ul class="mb-0">
                  <?php foreach (session('errors') as $e): ?>
                    <li><?= esc($e) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/registrasi/tambah') ?>" method="post">
              <?= csrf_field() ?>
              <div class="mb-3">
                <div class="row g-2">
                  <div class="col-md-6">
                    <label for="kode_kelas" class="form-label">Pilih Kelas</label>
                    <select id="kode_kelas" name="kode_kelas" class="form-select" required>
                      <option value="">-- Pilih Kelas --</option>
                      <?php if (!empty($kelasList)): ?>
                        <?php foreach ($kelasList as $k): ?>
                          <option 
                            value="<?= esc($k['kode_kelas']) ?>"
                            data-lokasi="<?= isset($k['kota_tersedia']) ? esc($k['kota_tersedia']) : '' ?>"
                            data-harga="<?= isset($k['harga']) ? esc($k['harga']) : '0' ?>"
                          >
                            <?= esc($k['kode_kelas']) ?> - <?= esc($k['nama_kelas']) ?>
                          </option>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="lokasi" class="form-label">Lokasi Kursus</label>
                    <select id="lokasi" name="lokasi" class="form-select">
                      <option value="">-- Pilih Lokasi --</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                
              </div>
              <div class="mb-3">
                <div class="row g-2">
                  <div class="col-md-6">
                    <label for="kodeVoucher" class="form-label">Voucher</label>
                      <div class="input-group">
                        <input type="text" class="form-control" id="kodeVoucher" name="kode_voucher" placeholder="Kosongkan jika tidak ada">
                        <button class="btn btn-warning" type="button" id="checkVoucherBtn" style="min-width: 120px;">Check Voucher</button>
                      </div>
                    <div id="voucherFeedback" class="form-text"></div>
                  </div>
                  <div class="col-md-6">
                    <label for="status_pembayaran" class="form-label">Pembayaran</label>
                    <select id="status_pembayaran" name="status_pembayaran" class="form-select" required>
                      <option value="">-- Pilih Pembayaran --</option>
                      <option value="lunas">Pembayaran penuh</option>
                      <option value="DP 50%">DP 50%</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <div class="alert alert-info">
                  <small>
                    <strong>Detail Pembayaran:</strong><br>
                    <div id="paymentDetails">Pilih kelas dan opsi pembayaran untuk melihat detail.</div>
                  </small>
                </div>
                <input type="hidden" id="biaya_total" name="biaya_total" value="0">
                <input type="hidden" id="kode_unik" name="kode_unik" value="">
              </div>
              <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
              </div>
              <div class="mb-3">
                <div class="row g-2">
                  <div class="col-md-6">
                    <label for="no_telp" class="form-label">Nomor Tlp WA</label>
                    <input type="text" id="no_telp" name="no_telp" class="form-control" placeholder="Masukkan nomor WhatsApp">
                  </div>
                  <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email">
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
              </div>

              <div class="mb-3">
                <div class="row g-2">
                  <div class="col-md-6">
                    <label for="kecamatan" class="form-label">Kecamatan</label>
                    <input type="text" id="kecamatan" name="kecamatan" class="form-control" placeholder="Kecamatan">
                  </div>
                  <div class="col-md-6">
                    <label for="kabupaten" class="form-label">Kabupaten</label>
                    <input type="text" id="kabupaten" name="kabupaten" class="form-control" placeholder="Kabupaten">
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <div class="row g-2">
                  <div class="col-md-6">
                    <label for="provinsi" class="form-label">Provinsi</label>
                    <input type="text" id="provinsi" name="provinsi" class="form-control" placeholder="Provinsi">
                  </div>
                  <div class="col-md-6">
                    <label for="kodepos" class="form-label">Kode Pos</label>
                    <input type="text" id="kodepos" name="kodepos" class="form-control" placeholder="Kode Pos">
                  </div>
                </div>
              </div>

             <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Simpan</button>
              </div>
            </form>
            <script>
              (function() {
                const kelasSelect = document.getElementById('kode_kelas');
                const lokasiSelect = document.getElementById('lokasi');

                function capitalize(text) {
                  if (!text) return '';
                  return text.charAt(0).toUpperCase() + text.slice(1);
                }

                function populateLokasi() {
                  const selected = kelasSelect.options[kelasSelect.selectedIndex];
                  const raw = selected ? (selected.getAttribute('data-lokasi') || '') : '';
                  // Dukungan daftar lokasi dipisahkan koma (",") atau titik koma (";")
                  const cities = raw
                    .split(/[,;]+/)
                    .map(s => s.trim())
                    .filter(Boolean);

                  // reset options
                  lokasiSelect.innerHTML = '<option value="">-- Pilih Lokasi --</option>';

                  cities.forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city;
                    opt.textContent = capitalize(city);
                    lokasiSelect.appendChild(opt);
                  });
                }

                if (kelasSelect) {
                  kelasSelect.addEventListener('change', populateLokasi);
                  // Inisialisasi saat halaman pertama kali dibuka
                  populateLokasi();
                }
              })();
            </script>
            <script>
              (function() {
                const checkBtn = document.getElementById('checkVoucherBtn');
                const kodeInput = document.getElementById('kodeVoucher');
                const feedbackEl = document.getElementById('voucherFeedback');
                const kelasSelect = document.getElementById('kode_kelas');
                const csrfHeaderName = 'X-CSRF-TOKEN';
                const csrfToken = '<?= csrf_hash() ?>';

                // Simpan metadata voucher yang sudah diverifikasi agar bisa dicek saat kelas berubah
                let appliedVoucher = null; // { code, kelasKode, kelasNama, persen }

                function setFeedback(text, type) {
                  feedbackEl.textContent = text || '';
                  const base = 'small';
                  const color = type === 'success' ? 'text-success' : type === 'danger' ? 'text-danger' : type === 'warning' ? 'text-warning' : 'text-muted';
                  feedbackEl.className = base + ' ' + color;
                }

                async function checkVoucher() {
                  const kode = (kodeInput.value || '').trim();
                  const kodeKelas = kelasSelect ? (kelasSelect.value || '').trim() : '';
                  if (!kode) {
                    setFeedback('Masukkan kode voucher terlebih dahulu', 'warning');
                    if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
                    appliedVoucher = null;
                    return;
                  }
                  if (!kodeKelas) {
                    setFeedback('Pilih kelas terlebih dahulu sebelum cek voucher', 'warning');
                    if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
                    appliedVoucher = null;
                    return;
                  }
                  checkBtn.disabled = true;
                  const originalText = checkBtn.textContent;
                  checkBtn.textContent = 'Checking...';
                  try {
                    const res = await fetch('<?= base_url('admin/registrasi/check-voucher') ?>', {
                      method: 'POST',
                      headers: {
                        'Content-Type': 'application/json',
                        [csrfHeaderName]: csrfToken,
                      },
                      body: JSON.stringify({ kode_voucher: kode, kode_kelas: kodeKelas }),
                    });
                    const data = await res.json();
                    if (data && data.found) {
                      const persen = data.diskon_persen ?? (data.voucher && data.voucher.diskon_persen);
                      const voucherNamaKelas = data.voucher_kelas_nama || '';
                      const voucherKodeKelas = data.voucher_kelas_kode || '';
                      const validDate = data.validDate !== false;
                      const validForClass = (data.validForClass === true);

                      if (data.validForClass === false) {
                        const kelasInfo = voucherNamaKelas || voucherKodeKelas || 'kelas tertentu';
                        setFeedback(`Voucher untuk kelas ${kelasInfo}. Tidak bisa digunakan pada kelas yang berbeda.`, 'danger');
                        if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
                        appliedVoucher = null;
                      } else if (!validDate) {
                        setFeedback(`Voucher ditemukan namun di luar masa berlaku.`, 'warning');
                        if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
                        appliedVoucher = null;
                      } else {
                        const msg = `Voucher ditemukan${persen ? ' • Diskon ' + persen + '%' : ''}` + (voucherNamaKelas ? ` • Untuk kelas: ${voucherNamaKelas}` : (voucherKodeKelas ? ` • Kode kelas: ${voucherKodeKelas}` : ''));
                        setFeedback(msg, 'success');
                        if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(persen || 0, true);
                        appliedVoucher = {
                          code: kode,
                          kelasKode: voucherKodeKelas,
                          kelasNama: voucherNamaKelas,
                          persen: parseFloat(persen) || 0
                        };
                      }
                    } else {
                      setFeedback((data && data.message) || 'Voucher tidak ditemukan', 'danger');
                      if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
                      appliedVoucher = null;
                    }
                  } catch (err) {
                    setFeedback('Gagal mengecek voucher. Coba lagi.', 'danger');
                    appliedVoucher = null;
                  } finally {
                    checkBtn.disabled = false;
                    checkBtn.textContent = originalText;
                  }
                }

                if (checkBtn) {
                  checkBtn.addEventListener('click', checkVoucher);
                }
                // Reset diskon saat pengguna mengubah input voucher sebelum mengecek
                if (kodeInput) {
                  kodeInput.addEventListener('input', function() {
                    if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
                    appliedVoucher = null;
                  });
                }
                // Jika kelas diganti setelah voucher diterapkan, hapus diskon dan beri feedback
                if (kelasSelect) {
                  kelasSelect.addEventListener('change', function() {
                    const selectedKode = (kelasSelect.value || '').trim();
                    if (appliedVoucher && appliedVoucher.kelasKode && appliedVoucher.kelasKode !== selectedKode) {
                      setFeedback(`Voucher untuk kelas ${appliedVoucher.kelasNama || appliedVoucher.kelasKode}. Silakan cek ulang voucher untuk kelas baru.`, 'danger');
                      if (window.__setVoucherDiscountPercent) window.__setVoucherDiscountPercent(0, false);
                      appliedVoucher = null;
                    }
                  });
                }
              })();
            </script>
            <script>
              (function() {
                const kelasSelect = document.getElementById('kode_kelas');
                const pembayaranSelect = document.getElementById('status_pembayaran');
                const paymentDetailsDiv = document.getElementById('paymentDetails');
                const biayaTotalInput = document.getElementById('biaya_total');
                const kodeUnikInput = document.getElementById('kode_unik');

                let originalBiayaKursus = 0;
                let kodeUnik = Math.floor(100 + Math.random() * 900);
                let currentDiskonPersen = 0;

                function formatNumber(number) {
                  return new Intl.NumberFormat('id-ID').format(Math.round(number));
                }

                function parseHarga(val) {
                  const s = String(val || '').trim();
                  if (s === '') return 0;
                  // Jika ada keduanya, asumsi '.' sebagai ribuan dan ',' sebagai desimal
                  if (s.includes('.') && s.includes(',')) {
                    return parseFloat(s.replace(/\./g, '').replace(',', '.')) || 0;
                  }
                  // Hanya ada koma: anggap sebagai desimal (lokal Indonesia)
                  if (s.includes(',') && !s.includes('.')) {
                    const countComma = (s.match(/,/g) || []).length;
                    if (countComma > 1) return parseFloat(s.replace(/,/g, '')) || 0; // ribuan
                    return parseFloat(s.replace(',', '.')) || 0;
                  }
                  // Hanya ada titik: satu titik = desimal, lebih dari satu = ribuan
                  if (s.includes('.') && !s.includes(',')) {
                    const countDot = (s.match(/\./g) || []).length;
                    if (countDot > 1) return parseFloat(s.replace(/\./g, '')) || 0;
                    return parseFloat(s) || 0;
                  }
                  // Hanya digit
                  return parseFloat(s) || 0;
                }

                function readHargaFromSelected() {
                  const opt = kelasSelect ? kelasSelect.options[kelasSelect.selectedIndex] : null;
                  const hRaw = opt ? (opt.getAttribute('data-harga') || '0') : '0';
                  originalBiayaKursus = parseHarga(hRaw);
                }

                function updatePaymentDisplay() {
                  readHargaFromSelected();
                  if (!originalBiayaKursus) {
                    paymentDetailsDiv.innerHTML = 'Pilih kelas untuk melihat detail pembayaran.';
                    biayaTotalInput && (biayaTotalInput.value = '0');
                    kodeUnikInput && (kodeUnikInput.value = '');
                    return;
                  }
                  let biayaSetelahDiskon = Math.max(0, originalBiayaKursus - (originalBiayaKursus * (currentDiskonPersen / 100)));
                  let displayText = `Harga Asli: Rp. ${formatNumber(originalBiayaKursus)}<br>`;
                  if (currentDiskonPersen > 0) {
                    displayText += `Diskon Voucher (${currentDiskonPersen}%): -Rp. ${formatNumber(originalBiayaKursus * (currentDiskonPersen / 100))}<br>`;
                    displayText += `Harga Setelah Diskon: Rp. ${formatNumber(biayaSetelahDiskon)}<br>`;
                  }

                  if (currentDiskonPersen === 100 && biayaSetelahDiskon <= 0) {
                    const finalAmountToPay = 0;
                    displayText += `<strong>Total Pembayaran: Rp. ${formatNumber(finalAmountToPay)} (GRATIS)</strong>`;
                    biayaTotalInput && (biayaTotalInput.value = '0');
                    kodeUnikInput && (kodeUnikInput.value = '');
                  } else {
                    const bayarDP = pembayaranSelect && pembayaranSelect.value === 'DP 50%';
                    if (bayarDP) {
                      const dpAmount = biayaSetelahDiskon * 0.5;
                      const finalAmountToPay = dpAmount + kodeUnik;
                      displayText += `DP 50%: Rp. ${formatNumber(dpAmount)}<br>`;
                      displayText += `Kode Pembayaran: Rp. ${formatNumber(kodeUnik)}<br>`;
                      displayText += `<strong>Total DP Yang Harus Dibayar: Rp. ${formatNumber(finalAmountToPay)}</strong><br>`;
                      displayText += `<small class="text-muted">Sisa pembayaran (dilunasi saat kursus): Rp. ${formatNumber(dpAmount)}</small>`;
                    } else {
                      const finalAmountToPay = biayaSetelahDiskon + kodeUnik;
                      displayText += `Kode Pembayaran: Rp. ${formatNumber(kodeUnik)}<br>`;
                      displayText += `<strong>Total Pembayaran Penuh: Rp. ${formatNumber(finalAmountToPay)}</strong>`;
                    }
                    biayaTotalInput && (biayaTotalInput.value = String(biayaSetelahDiskon));
                    kodeUnikInput && (kodeUnikInput.value = String(kodeUnik));
                  }

                  paymentDetailsDiv.innerHTML = displayText;
                }

                if (kelasSelect) kelasSelect.addEventListener('change', updatePaymentDisplay);
                if (pembayaranSelect) pembayaranSelect.addEventListener('change', updatePaymentDisplay);

                window.__setVoucherDiscountPercent = function(persen, apply) {
                  currentDiskonPersen = apply ? (parseFloat(persen) || 0) : 0;
                  updatePaymentDisplay();
                };

                // Initialize after DOM loaded to ensure select states are ready
                document.addEventListener('DOMContentLoaded', updatePaymentDisplay);
              })();
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Edit Registrasi</h5>
      <a href="<?= base_url('admin/registrasi') ?>" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card card-outline card-success">
          <div class="card-header">
            <h3 class="card-title">Form Edit Registrasi</h3>
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

            <?php
              // Tentukan nama kelas dari daftar kelas
              $namaKelas = '';
              if (!empty($kelasList) && !empty($registrasi['kode_kelas'])) {
                foreach ($kelasList as $k) {
                  if (($k['kode_kelas'] ?? '') === ($registrasi['kode_kelas'] ?? '')) {
                    $namaKelas = $k['nama_kelas'] ?? '';
                    break;
                  }
                }
              }
              $statusPembayaran = $registrasi['status_pembayaran'] ?? '';
              $biayaTotal = (float)($registrasi['biaya_total'] ?? 0);
              $biayaDP = (float)($registrasi['biaya_dibayar'] ?? 0);
              $biayaTagihan = (float)($registrasi['biaya_tagihan'] ?? 0);
              $kotaKelas = $registrasi['lokasi'] ?? '';
              // Map kode -> nama kota menggunakan kotaOptions dari controller
              $kotaName = $kotaKelas;
              $code = strtolower(trim((string) $kotaKelas));
              if (!empty($kotaOptions)) {
                foreach ($kotaOptions as $ko) {
                  $koCode = strtolower((string) ($ko['kode'] ?? ''));
                  $koName = (string) ($ko['nama'] ?? '');
                  if ($koCode !== '' && $koCode === $code) {
                    $kotaName = $koName;
                    break;
                  }
                  // fallback jika lokasi tersimpan sebagai nama langsung
                  if ($koName !== '' && strtolower($koName) === strtolower((string) $kotaKelas)) {
                    $kotaName = $koName;
                    break;
                  }
                }
              }
              if ($code === 'se-dunia') {
                $kotaName = 'Se-Dunia';
              }
            ?>

            <div class="mb-3">
              <div class="row g-2">
                <div class="col-md-4">
                  <label class="form-label">Kelas yang Diikuti</label>
                  <input type="text" class="form-control" value="<?= esc($namaKelas ?: ($registrasi['kode_kelas'] ?? '')) ?>" disabled>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Kota Kelas</label>
                  <input type="text" class="form-control" value="<?= esc($kotaName) ?>" disabled>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Metode Pembayaran</label>
                  <input type="text" class="form-control" value="<?= esc($statusPembayaran) ?>" disabled>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <div class="row g-2">
                <div class="col-md-4">
                  <label class="form-label">Biaya Total</label>
                  <input type="text" class="form-control" value="<?= 'Rp ' . number_format($biayaTotal, 0, ',', '.') ?>" disabled>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Biaya DP</label>
                  <input type="text" class="form-control" value="<?= 'Rp ' . number_format($biayaDP, 0, ',', '.') ?>" disabled>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Biaya Tagihan</label>
                  <input type="text" class="form-control" value="<?= 'Rp ' . number_format($biayaTagihan, 0, ',', '.') ?>" disabled>
                </div>
              </div>
            </div>

            <form action="<?= base_url('admin/registrasi/' . ($registrasi['id'] ?? 0) . '/update') ?>" method="post">
              <?= csrf_field() ?>

              <!-- Hidden fields to satisfy validation but not editable here -->
              <input type="hidden" name="kode_kelas" value="<?= esc($registrasi['kode_kelas'] ?? '') ?>">
              <input type="hidden" name="lokasi" value="<?= esc($registrasi['lokasi'] ?? '') ?>">
              <input type="hidden" name="status_pembayaran" value="<?= esc($registrasi['status_pembayaran'] ?? '') ?>">
              <input type="hidden" name="biaya_total" value="<?= esc($registrasi['biaya_total'] ?? '0') ?>">
              <input type="hidden" name="biaya_dibayar" value="<?= esc($registrasi['biaya_dibayar'] ?? '0') ?>">
              <input type="hidden" name="akses_aktif" value="<?= (isset($registrasi['akses_aktif']) && $registrasi['akses_aktif']) ? 1 : 0 ?>">

              <!-- Fields allowed to edit -->
              <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama lengkap" value="<?= esc($registrasi['nama'] ?? '') ?>" required>
              </div>
              <div class="mb-3">
                <div class="row g-2">
                  <div class="col-md-6">
                    <label for="no_telp" class="form-label">Nomor Tlp WA</label>
                    <input type="text" id="no_telp" name="no_telp" class="form-control" placeholder="Masukkan nomor WhatsApp" value="<?= esc($registrasi['no_telp'] ?? '') ?>">
                  </div>
                  <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" value="<?= esc($registrasi['email'] ?? '') ?>">
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="2" placeholder="Masukkan alamat lengkap"><?= esc($registrasi['alamat'] ?? '') ?></textarea>
              </div>

              <div class="mb-3">
                <div class="row g-2">
                  <div class="col-md-6">
                    <label for="kecamatan" class="form-label">Kecamatan</label>
                    <input type="text" id="kecamatan" name="kecamatan" class="form-control" placeholder="Kecamatan" value="<?= esc($registrasi['kecamatan'] ?? '') ?>">
                  </div>
                  <div class="col-md-6">
                    <label for="kabupaten" class="form-label">Kabupaten</label>
                    <input type="text" id="kabupaten" name="kabupaten" class="form-control" placeholder="Kabupaten" value="<?= esc($registrasi['kabupaten'] ?? '') ?>">
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <div class="row g-2">
                  <div class="col-md-6">
                    <label for="provinsi" class="form-label">Provinsi</label>
                    <input type="text" id="provinsi" name="provinsi" class="form-control" placeholder="Provinsi" value="<?= esc($registrasi['provinsi'] ?? '') ?>">
                  </div>
                  <div class="col-md-6">
                    <label for="kodepos" class="form-label">Kode Pos</label>
                    <input type="text" id="kodepos" name="kodepos" class="form-control" placeholder="Kode Pos" value="<?= esc($registrasi['kodepos'] ?? '') ?>">
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
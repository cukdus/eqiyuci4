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
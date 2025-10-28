<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Tambah Kelas</h5>
      <a href="<?= base_url('admin/kelas') ?>" class="btn btn-sm btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>

    <?php if (session()->has('message')): ?>
      <div class="alert alert-success"><?= session('message') ?></div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
      <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif; ?>
    <?php if (session()->has('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach (session('errors') as $err): ?>
            <li><?= esc($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-12">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <form action="<?= base_url('admin/kelas/tambah') ?>" method="post" enctype="multipart/form-data">
              <?= csrf_field() ?>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="nama_kelas" class="form-label">Nama Kelas</label>
                  <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" value="<?= esc(old('nama_kelas')) ?>" required>
                </div>
                <div class="col-md-6">
                  <label for="kode_kelas" class="form-label">Kode Kelas</label>
                  <input type="text" class="form-control" id="kode_kelas" name="kode_kelas" value="<?= esc(old('kode_kelas')) ?>" required>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="kategori" class="form-label">Kategori</label>
                  <select class="form-select" id="kategori" name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Kursus" <?= old('kategori') === 'Kursus' ? 'selected' : '' ?>>Kelas Offline</option>
                    <option value="kursusonline" <?= old('kategori') === 'kursusonline' ? 'selected' : '' ?>>Kelas Online</option>
                    <option value="Jasa" <?= old('kategori') === 'Jasa' ? 'selected' : '' ?>>Jasa</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="status_kelas" class="form-label">Status Kelas</label>
                  <select class="form-select" id="status_kelas" name="status_kelas" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="aktif" <?= old('status_kelas') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= old('status_kelas') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    <option value="segera" <?= old('status_kelas') === 'segera' ? 'selected' : '' ?>>Segera</option>
                  </select>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="harga" class="form-label">Harga</label>
                  <input type="number" step="0.01" class="form-control" id="harga" name="harga" value="<?= esc(old('harga')) ?>" required>
                </div>
                <div class="col-md-6">
                  <label for="durasi" class="form-label">Durasi</label>
                  <input type="text" class="form-control" id="durasi" name="durasi" value="<?= esc(old('durasi')) ?>" placeholder="Misal: 3 hari">
                </div>
              </div>

              <div class="mb-3">
                <label for="deskripsi_singkat" class="form-label">Deskripsi Singkat</label>
                <textarea class="form-control" id="deskripsi_singkat" name="deskripsi_singkat" rows="3" placeholder="Ringkasan singkat kelas..."><?= esc(old('deskripsi_singkat')) ?></textarea>
              </div>

              <div class="mb-3">
                <label for="summernote" class="form-label">Detail Kelas</label>
                <textarea class="form-control" id="summernote" name="deskripsi" rows="10"><?= old('deskripsi') ?></textarea>
              </div>

              <div class="mb-3">
                <label for="gambar_utama" class="form-label">Gambar Utama</label>
                <input type="file" class="form-control" id="gambar_utama" name="gambar_utama" accept="image/*">
                <small class="text-muted">Format: JPG/PNG, ukuran maks 2MB.</small>
              </div>

              <div class="mb-3">
                <label for="gambar_tambahan" class="form-label">Gambar Tambahan</label>
                <input type="file" class="form-control" id="gambar_tambahan" name="gambar_tambahan[]" accept="image/*" multiple>
                <small class="text-muted">Anda dapat memilih lebih dari satu gambar.</small>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label d-block">Kota Tersedia</label>
                  <?php $oldKota = (array) old('kota_tersedia', []); ?>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="kota_malang" name="kota_tersedia[]" value="malang" <?= in_array('malang', array_map('strtolower', $oldKota)) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="kota_malang">Malang</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="kota_jogja" name="kota_tersedia[]" value="jogja" <?= in_array('jogja', array_map('strtolower', $oldKota)) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="kota_jogja">Jogja</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="kota_world" name="kota_tersedia[]" value="seluruh dunia" <?= in_array('seluruh dunia', array_map('strtolower', $oldKota)) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="kota_world">Se-Dunia</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="badge" class="form-label">Badge</label>
                  <select class="form-select" id="badge" name="badge">
                    <option value="">-- Pilih Badge --</option>
                    <option value="nobadge" <?= old('badge') === 'nobadge' ? 'selected' : '' ?>>Tanpa Badge</option>
                    <option value="hot" <?= old('badge') === 'hot' ? 'selected' : '' ?>>Hot</option>
                    <option value="sale" <?= old('badge') === 'sale' ? 'selected' : '' ?>>Sale</option>
                  </select>
                </div>
              </div>

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="<?= base_url('admin/kelas') ?>" class="btn btn-outline-secondary">Batal</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
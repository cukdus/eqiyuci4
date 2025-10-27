<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Tambah Artikel</h5>
      <a href="<?= base_url('admin/artikel') ?>" class="btn btn-secondary btn-sm">
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
            <form action="<?= base_url('admin/artikel/tambah') ?>" method="post" enctype="multipart/form-data">
              <?= csrf_field() ?>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="<?= esc(old('judul')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori_id" name="kategori_id">
                        <option value="">-- Pilih Kategori --</option>
                        <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $kat): ?>
                            <option value="<?= (int) $kat['id'] ?>" <?= old('kategori_id') == $kat['id'] ? 'selected' : '' ?>>
                            <?= esc($kat['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input class="form-control" type="text" id="penulis" name="penulis" value="<?= esc(old('penulis', $penulisDefault ?? '')) ?>" aria-label="Disabled input example" disabled>
                </div>
                <div class="col-md-6">
                    <label for="tanggal_terbit" class="form-label">Tanggal Terbit <small class="text-muted">(jam default 11:00)</small></label>
                <input type="date" class="form-control" id="tanggal_terbit" name="tanggal_terbit" value="<?= esc(old('tanggal_terbit')) ?>">
                </div>
            </div>
            <div class="mb-3">
                <label for="gambar_utama" class="form-label">Gambar Utama</label>
                    <input type="file" class="form-control" id="gambar_utama" name="gambar_utama" accept="image/*">
                    <small class="text-muted">Format: JPG/PNG, ukuran maks 2MB.</small>
              </div>

              <div class="mb-3">
                <label for="summernote" class="form-label">Konten</label>
                <textarea class="form-control" id="summernote" name="konten" rows="10"><?= old('konten') ?></textarea>
              </div>

              <div class="mb-3">
                <label for="tags" class="form-label">Tag</label>
                <input type="text" class="form-control" id="tags" name="tags" value="<?= esc(old('tags')) ?>" placeholder="Masukkan tag, pisahkan dengan koma (contoh: edukasi, eqiyu, belajar)">
                <small class="text-muted">Gunakan koma untuk memisahkan beberapa tag. Tag akan disimpan ke tabel pivot <code>berita_tag</code>.</small>
              </div>

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="<?= base_url('admin/artikel') ?>" class="btn btn-outline-secondary">Batal</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
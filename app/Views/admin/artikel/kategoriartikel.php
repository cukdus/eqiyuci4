<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Kategori Artikel</h5>
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
            <!-- Form Tambah Kategori -->
            <form action="<?= base_url('admin/artikel/kategori/store') ?>" method="post" class="mb-4">
              <?= csrf_field() ?>
              <div class="row g-2 align-items-end">
                <div class="col-md-6">
                  <label for="nama_kategori" class="form-label">Nama Kategori</label>
                  <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="<?= esc(old('nama_kategori')) ?>" placeholder="Misal: Pengumuman" required>
                </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah</button>
                </div>
              </div>
            </form>

            <!-- Form Edit Kategori (opsional, tampil jika editCategory ada) -->
            <?php if (!empty($editCategory)): ?>
              <form action="<?= base_url('admin/artikel/kategori/' . (int) $editCategory['id'] . '/update') ?>" method="post" class="mb-4">
                <?= csrf_field() ?>
                <div class="row g-2 align-items-end">
                  <div class="col-md-6">
                    <label for="edit_nama_kategori" class="form-label">Edit Kategori</label>
                    <input type="text" class="form-control" id="edit_nama_kategori" name="nama_kategori" value="<?= esc($editCategory['nama_kategori']) ?>" required>
                  </div>
                  <div class="col-auto">
                    <button type="submit" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Simpan Perubahan</button>
                    <a href="<?= base_url('admin/artikel/kategori') ?>" class="btn btn-outline-secondary">Batal</a>
                  </div>
                </div>
              </form>
            <?php endif; ?>

            <!-- Tabel Kategori -->
            <div class="table-responsive">
              <table class="table table-sm table-striped align-middle">
                <thead>
                  <tr>
                    <th style="width: 60px">#</th>
                    <th>Nama Kategori</th>
                    <th style="width: 220px">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($categories)): ?>
                    <?php $no = 1; foreach ($categories as $kat): ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($kat['nama_kategori']) ?></td>
                        <td>
                          <a href="<?= base_url('admin/artikel/kategori/' . (int) $kat['id'] . '/edit') ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                          </a>
                          <form action="<?= base_url('admin/artikel/kategori/' . (int) $kat['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus kategori ini?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                              <i class="bi bi-trash"></i> Delete
                            </button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="3" class="text-muted">Belum ada kategori.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Kota Kelas</h5>
    </div>

    <?php if (session('message')): ?>
      <div class="alert alert-success"><?= esc(session('message')) ?></div>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <div class="alert alert-danger"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <div class="row">
      <div class="col-md-5">
        <div class="card card-outline card-primary mb-3">
          <div class="card-header"><h3 class="card-title">Tambah Kota</h3></div>
          <div class="card-body">
            <form method="POST" action="<?= base_url('admin/kelas/kota/store') ?>">
              <?= csrf_field() ?>
              <div class="mb-3">
                <label class="form-label">Kode Kota</label>
                <input type="text" name="kode" class="form-control" placeholder="misal: malang, jogja, se-dunia" required>
                <small class="text-muted">Gunakan huruf kecil tanpa spasi.</small>
              </div>
              <div class="mb-3">
                <label class="form-label">Nama Kota</label>
                <input type="text" name="nama" class="form-control" placeholder="Misal: Malang" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                  <option value="aktif">Aktif</option>
                  <option value="nonaktif">Nonaktif</option>
                </select>
              </div>
              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-7">
        <div class="card card-outline card-success">
          <div class="card-header"><h3 class="card-title">Daftar Kota</h3></div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm mb-0">
                <thead>
                  <tr>
                    <th style="width:80px">Kode</th>
                    <th>Nama</th>
                    <th style="width:120px">Status</th>
                    <th style="width:100px">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                <?php if (!empty($kotaList)): ?>
                  <?php foreach ($kotaList as $k): ?>
                    <tr>
                      <td><code><?= esc($k['kode'] ?? '') ?></code></td>
                      <td><?= esc($k['nama'] ?? '') ?></td>
                      <td><span class="badge bg-<?= (($k['status'] ?? '') === 'aktif') ? 'success' : 'secondary' ?>"><?= esc($k['status'] ?? '-') ?></span></td>
                      <td>
                        <form method="POST" action="<?= base_url('admin/kelas/kota/' . (int) ($k['id'] ?? 0) . '/delete') ?>" onsubmit="return confirm('Hapus kota ini?')">
                          <?= csrf_field() ?>
                          <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada data kota.</td>
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
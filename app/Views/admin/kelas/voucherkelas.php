<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Voucher Kelas</h5>
      <a href="<?= base_url('admin/kelas') ?>" class="btn btn-sm btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>

    <?php if (session()->has('message')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc(session('message')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc(session('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    <?php if (session()->has('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ((array) session('errors') as $err): ?>
            <li><?= esc($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-12">
        <div class="card card-outline card-primary mb-4">
          <div class="card-header">
            <h6 class="card-title mb-0">Buat Voucher Baru</h6>
          </div>
          <div class="card-body">
            <form action="<?= base_url('admin/kelas/voucher/store') ?>" method="post">
              <?= csrf_field() ?>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="kelas_id" class="form-label">Kelas</label>
                  <select class="form-select" id="kelas_id" name="kelas_id" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ((array) ($kelasList ?? []) as $k): ?>
                      <option value="<?= (int) ($k['id'] ?? 0) ?>">
                        <?= esc($k['nama_kelas'] ?? '') ?> (<?= esc($k['kode_kelas'] ?? '') ?>)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="kode_voucher" class="form-label">Kode Voucher</label>
                  <input type="text" class="form-control" id="kode_voucher" name="kode_voucher" value="<?= esc(old('kode_voucher')) ?>" placeholder="Misal: EQIYU50" required>
                  <small class="form-text text-muted">Pastikan unik; gunakan huruf/angka tanpa spasi.</small>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="diskon_persen" class="form-label">Diskon (%)</label>
                  <input type="number" step="0.01" min="0" max="100" class="form-control" id="diskon_persen" name="diskon_persen" value="<?= esc(old('diskon_persen')) ?>" placeholder="Contoh: 15" required>
                  <small class="form-text text-muted">Nilai antara 0 hingga 100.</small>
                </div>
                <div class="col-md-3">
                  <label for="tanggal_berlaku_mulai" class="form-label">Tanggal Mulai</label>
                  <input type="date" class="form-control" id="tanggal_berlaku_mulai" name="tanggal_berlaku_mulai" value="<?= esc(old('tanggal_berlaku_mulai')) ?>">
                </div>
                <div class="col-md-3">
                  <label for="tanggal_berlaku_sampai" class="form-label">Tanggal Selesai</label>
                  <input type="date" class="form-control" id="tanggal_berlaku_sampai" name="tanggal_berlaku_sampai" value="<?= esc(old('tanggal_berlaku_sampai')) ?>">
                </div>
              </div>

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-ticket"></i> Buat Voucher</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
              </div>
            </form>
          </div>
        </div>

        <div class="card card-outline card-primary">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0">Daftar Voucher</h6>
            <small class="text-muted ms-auto">Klik hapus untuk menghapus voucher</small>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped mb-0">
                <thead>
                  <tr>
                    <th style="width: 40px">#</th>
                    <th>Kode</th>
                    <th>Kelas</th>
                    <th>Diskon (%)</th>
                    <th>Berlaku</th>
                    <th style="width: 100px">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($vouchers)): ?>
                    <?php $no = 1;
                    foreach ($vouchers as $v): ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><code><?= esc($v['kode_voucher']) ?></code></td>
                        <td><?= esc($v['nama_kelas'] ?? '-') ?></td>
                        <td><?= number_format((float) ($v['diskon_persen'] ?? 0), 2) ?></td>
                        <td>
                          <?php
                          $mulai = $v['tanggal_berlaku_mulai'] ?? null;
                          $sampai = $v['tanggal_berlaku_sampai'] ?? null;
                          if ($mulai && $sampai) {
                            echo esc($mulai) . ' s/d ' . esc($sampai);
                          } elseif ($mulai) {
                            echo 'Mulai ' . esc($mulai);
                          } elseif ($sampai) {
                            echo 'Sampai ' . esc($sampai);
                          } else {
                            echo '<span class="text-muted">Tanpa batas tanggal</span>';
                          }
                          ?>
                        </td>
                        <td>
                          <form action="<?= base_url('admin/kelas/voucher/' . (int) ($v['id'] ?? 0) . '/delete') ?>" method="post" onsubmit="return confirm('Hapus voucher ini?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center text-muted py-4">Belum ada voucher</td>
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
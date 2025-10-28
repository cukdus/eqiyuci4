<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Data Kelas</h5>
    </div>

    <?php if (session()->has('message')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-12">
        <div class="card card-outline card-success">
          <div class="card-header">
              <div class="d-flex justify-content-between align-items-center">
                  <h3 class="card-title">Daftar Kelas</h3>
                  <div class="d-flex">
                      <a href="<?= base_url('admin/kelas/tambah') ?>" class="btn btn-sm btn-primary me-2 d-inline-flex align-items-center justify-content-center">
                          <i class="fas fa-plus me-1"></i>
                          <span class="text-center">Tambah Kelas</span>
                      </a>
                      <form action="" method="GET" class="input-group input-group-sm" style="width: 250px;">
                          <input type="text" name="search" class="form-control float-right" placeholder="Cari kelas..." value="<?= esc($search ?? '') ?>">
                          <div class="input-group-append">
                              <button type="submit" class="btn btn-default">
                                  <i class="fas fa-search"></i>
                              </button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
          <div class="card-body table-responsive p-1">
            <table class="table table-bordered table-hover text-nowrap">
              <thead>
                <tr>
                  <th style="width:50px">Kode</th>
                  <th>Nama Kelas</th>
                  <th style="width:140px">Kategori</th>
                  <th style="width:120px">Gambar</th>
                  <th style="width:150px">Harga</th>
                  <th style="width:120px">Durasi</th>
                  <th style="width:120px">Status</th>
                  <th style="width:100px">Aksi</th>
                </tr>
              </thead>
              <tbody>
              <?php if (!empty($classes)): ?>
                <?php foreach ($classes as $k): ?>
                  <tr>
                    <td><?= esc($k['kode_kelas'] ?? '-') ?></td>
                    <td><?= esc($k['nama_kelas'] ?? '-') ?></td>
                    <td>
                      <span class="text-capitalize"><?= esc($k['kategori'] ?? '-') ?></span>
                    </td>
                    <td>
                      <?php $img = (string) ($k['gambar_utama'] ?? ''); ?>
                      <?php if ($img !== ''): ?>
                        <img src="<?= base_url($img) ?>" alt="Gambar Kelas" style="width:60px;height:60px;object-fit:cover" class="rounded border">
                      <?php else: ?>
                        <span class="text-muted">-</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php
                      $harga = $k['harga'] ?? null;
                      echo $harga !== null ? 'Rp ' . number_format((float) $harga, 0, ',', '.') : '-';
                      ?>
                    </td>
                    <td><?= esc($k['durasi'] ?? '-') ?></td>
                    <td>
                      <?php
                      $status = (string) ($k['status_kelas'] ?? '');
                      $icon = 'text-secondary';
                      if ($status === 'aktif') {
                        $icon = 'text-success';
                      } elseif ($status === 'segera') {
                        $icon = 'text-warning';
                      } elseif ($status === 'nonaktif') {
                        $icon = 'text-danger';
                      }
                      ?>
                      <i class="nav-icon bi bi-check-circle-fill <?= $icon ?>"></i>
                      <span class="ms-1 text-capitalize"><?= esc($status ?: '-') ?></span>
                    </td>
                    <td>
                      <div class="btn-group" role="group">
                        <a href="<?= base_url('admin/kelas/' . ($k['id'] ?? 0) . '/edit') ?>" class="btn btn-sm btn-warning rounded-0 rounded-start" title="Edit">
                          <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="<?= base_url('admin/kelas/' . ($k['id'] ?? 0) . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus kelas ini?')">
                          <?= csrf_field() ?>
                          <button type="submit" class="btn btn-sm btn-danger rounded-0 rounded-end" title="Delete">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-center text-muted">Belum ada data kelas.</td>
                </tr>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
          <div class="card-footer clearfix">
            <?= isset($pager) ? $pager->links('default', 'admin_pagination') : '' ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
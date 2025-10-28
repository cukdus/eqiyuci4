<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Data Artikel</h5>
    </div>

    <?php if (session()->has('message')): ?>
      <div class="alert alert-success"><?= session('message') ?></div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
      <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif; ?>

    <div class="row">
      <div class="col-12">
        <div class="card card-outline card-success">
          <div class="card-header">
              <div class="d-flex justify-content-between align-items-center">
                  <h3 class="card-title">Daftar Artikel</h3>
                  <div class="d-flex">
                      <a href="<?= base_url('admin/artikel/tambah') ?>" class="btn btn-sm btn-primary me-2 d-inline-flex align-items-center justify-content-center">
                          <i class="fas fa-plus me-1"></i>
                          <span class="text-center">Tambah Artikel</span>
                      </a>
                      <form action="" method="GET" class="input-group input-group-sm" style="width: 250px;">
                          <input type="text" name="search" class="form-control float-right" placeholder="Cari artikel..." value="">
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
                  <th style="width:150px">Tanggal Terbit</th>
                  <th>Judul</th>
                  <th style="width:150px">Penulis</th>
                  <th style="width:150px">Kategori</th>
                  <th style="width:100px">Aksi</th>
                </tr>
              </thead>
              <tbody>
              <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $a): ?>
                  <tr>
                    <td>
                      <?php
                      $tgl = $a['tanggal_terbit'] ?? null;
                      $iconClass = 'text-secondary';
                      $dateText = '-';
                      if ($tgl) {
                        try {
                          $dt = new \DateTime((string) $tgl);
                          $now = new \DateTime('now');
                          $iconClass = ($dt <= $now) ? 'text-success' : 'text-warning';
                          $dateText = $dt->format('d-m-Y');
                        } catch (\Throwable $e) {
                          $iconClass = 'text-secondary';
                        }
                      }
                      ?>
                      <i class="nav-icon bi bi-hand-thumbs-up-fill <?= $iconClass ?>"></i>
                      <span class="ms-1"><?= esc($dateText) ?></span>
                    </td>  
                    <td><?= esc($a['judul']) ?></td>
                    <td><?= esc($a['penulis'] ?? '-') ?></td>
                    <td><?= esc($a['kategori_nama'] ?? '-') ?></td>
                    <td>
                      <div class="btn-group" role="group">
                        <form action="<?= base_url('admin/artikel/' . $a['id'] . '/duplicate') ?>" method="post" class="d-inline">
                          <?= csrf_field() ?>
                          <button type="submit" class="btn btn-sm btn-info rounded-0 rounded-start" title="Duplikat">
                            <i class="bi bi-files"></i>
                          </button>
                        </form>

                        <a href="<?= base_url('admin/artikel/' . $a['id'] . '/edit') ?>" class="btn btn-sm btn-warning rounded-0" title="Edit">
                          <i class="bi bi-pencil-square"></i>
                        </a>

                        <form action="<?= base_url('admin/artikel/' . $a['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus artikel ini?')">
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
                  <td colspan="6" class="text-center text-muted">Belum ada data artikel.</td>
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
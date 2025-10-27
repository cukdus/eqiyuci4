<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Data Artikel</h5>
        <a href="<?= base_url('admin/artikel/tambah') ?>" class="btn btn-primary btn-sm">
          <i class="bi bi-plus-lg"></i> Tambah Artikel
        </a>
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
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th style="width:220px">Tanggal Terbit</th>
                  <th>Judul</th>
                  <th>Penulis</th>
                  <th>Kategori</th>
                  <th>Status</th>
                  <th style="width:220px">Aksi</th>
                </tr>
              </thead>
              <tbody>
              <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $a): ?>
                  <tr>
                    <td><?= esc($a['tanggal_terbit'] ?? '-') ?></td>  
                    <td><?= esc($a['judul']) ?></td>
                    <td><?= esc($a['penulis'] ?? '-') ?></td>
                    <td><?= esc($a['kategori_nama'] ?? '-') ?></td>
                    <td>
                      <?php if (($a['status'] ?? 'draft') === 'publish'): ?>
                        <span class="badge bg-success">Publish</span>
                      <?php else: ?>
                        <span class="badge bg-secondary">Draft</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <form action="<?= base_url('admin/artikel/' . $a['id'] . '/duplicate') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-info" title="Duplikat">
                          <i class="bi bi-files"></i> Duplikat
                        </button>
                      </form>

                      <a href="<?= base_url('admin/artikel/tambah') ?>" class="btn btn-sm btn-warning" title="Edit">
                        <i class="bi bi-pencil-square"></i> Edit
                      </a>

                      <form action="<?= base_url('admin/artikel/' . $a['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus artikel ini?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                          <i class="bi bi-trash"></i> Delete
                        </button>
                      </form>
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
            <?= isset($pager) ? $pager->links() : '' ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Data Registrasi</h5>
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
                  <h3 class="card-title">Daftar Registrasi</h3>
                  <div class="d-flex">
                      <a href="<?= base_url('admin/registrasi/tambah') ?>" class="btn btn-sm btn-primary me-2 d-inline-flex align-items-center justify-content-center">
                          <i class="fas fa-plus me-1"></i>
                          <span class="text-center">Tambah Registrasi</span>
                      </a>
                      <form action="" method="GET" class="input-group input-group-sm" style="width: 250px;">
                          <input type="text" name="search" class="form-control float-right" placeholder="Cari registrasi..." value="<?= esc($search ?? '') ?>">
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
                  <th style="width:50px">ID</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th style="width:100px">No. Telepon</th>
                  <th style="width:80px">Lokasi</th>
                  <th>Kelas</th>
                  <th style="width:50px">Akses</th>
                  <th style="width:160px">Status</th>
                  <th style="width:220px">Aksi</th>
                </tr>
              </thead>
              <tbody>
              <?php if (!empty($registrations)): ?>
                <?php foreach ($registrations as $r): ?>
                  <tr>
                    <td><?= esc($r['id'] ?? '-') ?></td>
                    <td><?= esc($r['nama'] ?? '-') ?></td>
                    <td><?= esc($r['email'] ?? '-') ?></td>
                    <td>
                      <?php
        $phoneRaw = preg_replace('/[^0-9]/', '', (string) ($r['no_telp'] ?? ''));
        $waUrl = '#';
        if ($phoneRaw !== '') {
            $waNumber = ($phoneRaw[0] === '0') ? ('62' . substr($phoneRaw, 1)) : $phoneRaw;
            $waUrl = 'https://wa.me/' . $waNumber;
        }
        ?>
                      <div class="btn-group">
                      <a href="<?= esc($waUrl) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-success">
                        <i class="bi bi-whatsapp me-1"></i>
                      </a>
                      <a href="<?= esc($waUrl) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-warning">
                        <i class="bi bi-whatsapp me-1"></i>
                      </a>
                      </div>
                    </td>
                    <td><?= esc($r['lokasi'] ?? '-') ?></td>
                    <td><?= esc($r['nama_kelas'] ?? '-') ?></td>
                    <td>
                      <div class="form-check form-switch">
                        <?php $isOn = !!($r['akses_aktif'] ?? false);
                        $rowId = (int) ($r['id'] ?? 0); ?>
                        <input class="form-check-input akses-toggle" type="checkbox" id="akses_<?= $rowId ?>" <?= $isOn ? 'checked' : '' ?> data-id="<?= $rowId ?>" data-url="<?= base_url('admin/registrasi/' . $rowId . '/toggle-akses') ?>">
                        <label class="form-check-label" for="akses_<?= $rowId ?>"></label>
                      </div>
                    </td>
                    <td>
                      <?php $status = strtolower((string) ($r['status_pembayaran'] ?? '')); ?>
                      <span class="badge <?= $status === 'dp 50%' ? 'bg-warning' : ($status === 'lunas' ? 'bg-success' : 'bg-secondary') ?>">
                        <?= esc(ucfirst($status ?: 'unknown')) ?>
                      </span>
                    </td>
                    <td>
                      <a href="<?= base_url('admin/registrasi/' . ($r['id'] ?? 0) . '/edit') ?>" class="btn btn-sm btn-warning" title="Edit">
                        <i class="bi bi-pencil-square"></i> Edit
                      </a>
                      <form action="<?= base_url('admin/registrasi/' . ($r['id'] ?? 0) . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus registrasi ini?')">
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
                  <td colspan="9" class="text-center text-muted">Belum ada data registrasi.</td>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle akses aktif
    document.querySelectorAll('.akses-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const isChecked = this.checked;
            
            const url = this.dataset.url || ('<?= base_url('admin/registrasi') ?>/' + id + '/toggle-akses');
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    id: id,
                    akses_aktif: isChecked ? 1 : 0
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    // Revert toggle if failed
                    this.checked = !isChecked;
                    alert('Gagal mengupdate akses: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                // Revert toggle if error
                this.checked = !isChecked;
                alert('Error: ' + error.message);
            });
        });
    });
});
</script>
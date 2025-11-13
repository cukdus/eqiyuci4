<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Data Transaksi / Saldo (KlikBCA)</h5>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card card-success card-outline">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Data Transaksi / Saldo (KlikBCA)</h3>
            <form method="post" action="<?= site_url('admin/setting/import-bca'); ?>" onsubmit="return confirm('Jalankan impor Mutasi BCA sekarang?');" class="d-inline ms-auto">
                  <?= csrf_field(); ?>
                  <input type="hidden" name="run_parser" value="1">
                  <button type="submit" class="btn btn-sm btn-primary ms-auto"><i class="bi bi-cloud-download"></i> Impor Mutasi BCA</button>
                </form>
          </div>
          <div class="card-body">
            <?php if (session('message')): ?>
              <div class="alert alert-success"><?= esc(session('message')); ?></div>
            <?php endif; ?>
            <?php if (session('error')): ?>
              <div class="alert alert-danger"><?= esc(session('error')); ?></div>
            <?php endif; ?>
            <?php if (empty($rows ?? [])): ?>
              <div class="alert alert-info">Belum ada transaksi tersimpan untuk hari ini.</div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Periode</th>
                      <th>Info</th>
                      <th class="text-end">Nominal</th>
                      <th>Type</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach (($rows ?? []) as $r): ?>
                      <tr>
                        <td class="text-nowrap"><?= esc($r['period'] ?? ''); ?></td>
                        <td><?= esc($r['info'] ?? ''); ?></td>
                        <td class="text-end"><?= esc($r['amount_formatted'] ?? ''); ?></td>
                        <td><?= esc($r['type'] ?? ''); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</section>
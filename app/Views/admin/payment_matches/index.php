<?php /** @var array $rows */ /** @var array $filters */ /** @var array $types */ /** @var string $title */ ?>
<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0"><?= esc($title ?? 'Audit Payment Matches'); ?></h5>
      <a href="<?= site_url('admin/registrasi'); ?>" class="btn btn-sm btn-outline-secondary">Kembali Registrasi</a>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card card-success card-outline mb-3">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0">Filter</h3>
            <button type="submit" form="filterForm" class="btn btn-sm btn-primary ms-auto"><i class="bi bi-funnel"></i> Terapkan</button>
          </div>
          <div class="card-body">
            <form id="filterForm" method="get">
              <div class="row g-2">
                <div class="col-md-3">
                  <label class="form-label">Tipe</label>
                  <select name="type" class="form-select form-select-sm">
                    <option value="">(semua)</option>
                    <?php foreach ($types as $t): $sel = (($filters['type'] ?? '') === $t) ? 'selected' : ''; ?>
                      <option value="<?= esc($t); ?>" <?= $sel; ?>><?= esc(ucfirst($t)); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Periode Mulai</label>
                  <input type="date" name="start" class="form-control form-control-sm" value="<?= esc($filters['start'] ?? ''); ?>" />
                </div>
                <div class="col-md-3">
                  <label class="form-label">Periode Selesai</label>
                  <input type="date" name="end" class="form-control form-control-sm" value="<?= esc($filters['end'] ?? ''); ?>" />
                </div>
                <div class="col-md-3">
                  <label class="form-label">Cari</label>
                  <input type="text" name="q" class="form-control form-control-sm" placeholder="Nama/Email/Kelas" value="<?= esc($filters['q'] ?? ''); ?>" />
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="card card-success card-outline">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0">Daftar Payment Matches</h3>
            <span class="badge bg-secondary ms-2"><?= count($rows); ?> data</span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped align-middle mb-0">
                <thead>
                  <tr>
                    <th style="width: 52px;">#</th>
                    <th>Registrasi</th>
                    <th>Kelas</th>
                    <th>Tipe</th>
                    <th>Amount</th>
                    <th>Periode</th>
                    <th>Matched At</th>
                    <th>Bank Tx</th>
                    <th>Catatan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($rows)): ?>
                    <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                  <?php else: $i=1; foreach ($rows as $r): ?>
                    <?php
                      $badge = 'bg-secondary';
                      switch ($r['type'] ?? '') {
                        case 'dp': $badge = 'bg-warning text-dark'; break;
                        case 'pelunasan': $badge = 'bg-success'; break;
                        case 'full': $badge = 'bg-primary'; break;
                        case 'dibayar': $badge = 'bg-info text-dark'; break;
                      }
                    ?>
                    <tr>
                      <td class="text-muted"><?= $i++; ?></td>
                      <td>
                        <div class="fw-semibold"><?= esc($r['registrasi_nama'] ?? ''); ?></div>
                        <div class="small text-muted"><?= esc($r['registrasi_email'] ?? ''); ?></div>
                      </td>
                      <td><?= esc($r['nama_kelas'] ?? ''); ?></td>
                      <td><span class="badge <?= $badge; ?>"><?= esc(ucfirst($r['type'] ?? '')); ?></span></td>
                      <td class="text-nowrap"><?= esc($r['amount_formatted'] ?? $r['bank_amount'] ?? ''); ?></td>
                      <td class="text-nowrap"><?= esc($r['period'] ?? $r['bank_period'] ?? ''); ?></td>
                      <td class="text-nowrap"><?= esc($r['matched_at'] ?? ''); ?></td>
                      <td class="text-nowrap">BT#<?= esc((string)($r['bank_transaction_id'] ?? '')); ?></td>
                      <td><?= esc($r['notes'] ?? ''); ?></td>
                    </tr>
                  <?php endforeach; endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
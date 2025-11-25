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
                    <?php foreach ($types as $t):
                      $sel = (($filters['type'] ?? '') === $t) ? 'selected' : ''; ?>
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
                <div class="col-md-2">
                  <label class="form-label">Per halaman</label>
                  <select name="perPage" id="perPage" class="form-select form-select-sm">
                    <?php $defaultPer = 10;
                    $perOptions = [10, 20, 50, 100];
                    foreach ($perOptions as $pp):
                      $sel = ($pp === $defaultPer) ? 'selected' : ''; ?>
                      <option value="<?= $pp ?>" <?= $sel ?>><?= $pp ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="card card-success card-outline">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0">Daftar Payment Matches</h3>
            <span id="pmCount" class="badge bg-secondary ms-2">0 data</span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped align-middle mb-0" id="pmTable">
                <thead>
                  <tr>
                    <th style="width: 52px;">#</th>
                    <th>Registrasi</th>
                    <th>Kelas</th>
                    <th>Jadwal Kelas</th>
                    <th>Tipe</th>
                    <th>Amount</th>
                    <th>Bank Tx</th>
                    <th>Catatan</th>
                  </tr>
                </thead>
                <tbody id="pmTbody"></tbody>
              </table>
            </div>
            <div class="p-2 border-top">
              <nav>
                <ul class="pagination pagination-sm mb-0" id="pmPagination"></ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  (function(){
    const tbody = document.getElementById('pmTbody');
    const pagination = document.getElementById('pmPagination');
    const countBadge = document.getElementById('pmCount');
    const form = document.getElementById('filterForm');
    const perPageSel = document.getElementById('perPage');
    let state = { page: 1, perPage: parseInt(perPageSel ? perPageSel.value : '10', 10) };

    function badgeClass(type){
      switch ((type||'').toLowerCase()) {
        case 'dp': return 'bg-warning text-dark';
        case 'pelunasan': return 'bg-success';
        case 'full': return 'bg-primary';
        case 'dibayar': return 'bg-info text-dark';
        default: return 'bg-secondary';
      }
    }

    function fmtDate(d){
      const ts = Date.parse(d);
      if (!isNaN(ts)) {
        const dt = new Date(ts);
        return `${String(dt.getDate()).padStart(2,'0')}/${String(dt.getMonth()+1).padStart(2,'0')}/${dt.getFullYear()}`;
      }
      return d || '';
    }

    function buildJadwalLabel(row){
      const jm = row.jadwal_mulai || null;
      const js = row.jadwal_selesai || null;
      const jl = row.jadwal_lokasi || '';
      const base = jm ? fmtDate(jm) : '-';
      const range = js ? (' s/d ' + fmtDate(js)) : '';
      const loc = jl ? (' - ' + (String(jl).charAt(0).toUpperCase() + String(jl).slice(1))) : '';
      return base + range + loc;
    }

    function getFilters(){
      const fd = new FormData(form);
      const params = new URLSearchParams();
      for (const [k,v] of fd.entries()) {
        if (v !== '') params.append(k, v);
      }
      params.set('page', String(state.page));
      params.set('perPage', String(state.perPage));
      return params;
    }

    async function load(){
      const params = getFilters();
      const url = '<?= base_url('admin/setting/payment-matches.json') ?>?' + params.toString();
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      const j = await res.json();
      tbody.innerHTML = '';
      const rows = (j && j.data) ? j.data : [];
      const meta = (j && j.meta) ? j.meta : { total: 0, total_pages: 1, page: state.page, per_page: state.perPage };
      countBadge.textContent = `${meta.total} data`;
      if (!rows.length) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 8; td.className = 'text-center text-muted py-4';
        td.textContent = 'Tidak ada data.';
        tr.appendChild(td);
        tbody.appendChild(tr);
      } else {
        let i = (meta.page - 1) * meta.per_page + 1;
        rows.forEach(r => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td class="text-muted">${i++}</td>
            <td>
              <div class="fw-semibold">${(r.registrasi_nama||'')}</div>
              <div class="small text-muted">${(r.registrasi_email||'')}</div>
            </td>
            <td>${(r.nama_kelas||'')}</td>
            <td class="text-nowrap">${buildJadwalLabel(r)}</td>
            <td><span class="badge ${badgeClass(r.type)}">${String(r.type||'').charAt(0).toUpperCase()+String(r.type||'').slice(1)}</span></td>
            <td class="text-nowrap">${(r.amount_formatted||r.bank_amount||'')}</td>
            <td class="text-nowrap">BT#${String(r.bank_transaction_id||'')}</td>
            <td>${(r.notes||'')}</td>
          `;
          tbody.appendChild(tr);
        });
      }
      renderPagination(meta);
    }

    function renderPagination(meta){
      pagination.innerHTML = '';
      const totalPages = meta.total_pages || 1;
      const page = meta.page || 1;
      function addItem(label, target, disabled=false, active=false){
        const li = document.createElement('li');
        li.className = `page-item ${disabled?'disabled':''} ${active?'active':''}`;
        const a = document.createElement('a');
        a.className = 'page-link'; a.href = '#'; a.textContent = label;
        a.addEventListener('click', (e)=>{ e.preventDefault(); if (disabled || target===page) return; state.page = target; load(); });
        li.appendChild(a); pagination.appendChild(li);
      }
      addItem('«', page-1, page<=1, false);
      for (let i=1;i<=totalPages;i++){ addItem(String(i), i, false, i===page); }
      addItem('»', page+1, page>=totalPages, false);
    }

    form.addEventListener('submit', function(e){ e.preventDefault(); state.page = 1; state.perPage = parseInt(perPageSel.value,10)||10; load(); });
    perPageSel.addEventListener('change', function(){ state.page = 1; state.perPage = parseInt(perPageSel.value,10)||10; load(); });
    document.addEventListener('DOMContentLoaded', load);
  })();
</script>

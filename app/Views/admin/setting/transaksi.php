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
            <div class="d-flex align-items-center ms-auto">
              <form id="txFilterForm" method="get" action="<?= site_url('admin/setting/transaksi'); ?>" class="d-flex align-items-center me-2">
                <select name="month" class="form-select form-select-sm me-2" style="width:auto;">
                  <option value="">Bulan</option>
                  <?php foreach (($months ?? []) as $mVal => $mName): ?>
                    <option value="<?= (int) $mVal ?>" <?= ((int) ($filters['month'] ?? 0) === (int) $mVal) ? 'selected' : '' ?>><?= esc($mName) ?></option>
                  <?php endforeach; ?>
                </select>
                <select name="year" class="form-select form-select-sm me-2" style="width:auto;">
                  <option value="">Tahun</option>
                  <?php foreach (($years ?? []) as $yy): ?>
                    <option value="<?= (int) $yy ?>" <?= ((int) ($filters['year'] ?? 0) === (int) $yy) ? 'selected' : '' ?>><?= (int) $yy ?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
                <a href="<?= site_url('admin/setting/transaksi'); ?>" class="btn btn-sm btn-outline-dark ms-2">Reset</a>
                <select id="txPerPage" class="form-select form-select-sm ms-3" style="width:auto;">
                  <option value="10" selected>Per halaman: 10</option>
                  <option value="20">Per halaman: 20</option>
                  <option value="50">Per halaman: 50</option>
                  <option value="100">Per halaman: 100</option>
                </select>
              </form>
              <form method="post" action="<?= site_url('admin/setting/import-bca'); ?>" onsubmit="return confirm('Jalankan impor Mutasi BCA sekarang?');" class="d-inline">
                  <?= csrf_field(); ?>
                  <input type="hidden" name="run_parser" value="1">
                  <button type="submit" class="btn btn-sm btn-danger ms-auto"><i class="bi bi-cloud-download"></i> Jangan Dipencet Ngawur</button>
              </form>
            </div>
          </div>
          <div class="card-body">
            <?php if (session('message')): ?>
              <div class="alert alert-success"><?= esc(session('message')); ?></div>
            <?php endif; ?>
            <?php if (session('error')): ?>
              <div class="alert alert-danger"><?= esc(session('error')); ?></div>
            <?php endif; ?>
              <div class="table-responsive">
                <table class="table table-striped" id="txTable">
                  <thead>
                    <tr>
                      <th>Waktu</th>
                      <th>Periode</th>
                      <th>Info</th>
                      <th class="text-end">Nominal</th>
                      <th>Type</th>
                    </tr>
                  </thead>
                  <tbody id="txTbody"></tbody>
                </table>
              </div>
              <div class="p-2 border-top">
                <nav>
                  <ul class="pagination pagination-sm mb-0" id="txPagination"></ul>
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
    const form = document.getElementById('txFilterForm');
    const perSel = document.getElementById('txPerPage');
    const tbody = document.getElementById('txTbody');
    const pag = document.getElementById('txPagination');
    let state = { page: 1, perPage: parseInt(perSel ? perSel.value : '10', 10) };

    function getParams(){
      const fd = new FormData(form);
      const p = new URLSearchParams();
      for (const [k,v] of fd.entries()) { if (v !== '') p.append(k, v); }
      p.set('page', String(state.page));
      p.set('perPage', String(state.perPage));
      return p;
    }

    async function load(){
      const url = '<?= base_url('admin/setting/transaksi.json') ?>?' + getParams().toString();
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      const j = await res.json();
      const rows = (j && j.data) ? j.data : [];
      const meta = (j && j.meta) ? j.meta : { total: 0, total_pages: 1, page: state.page, per_page: state.perPage };
      tbody.innerHTML = '';
      if (!rows.length) {
        const tr = document.createElement('tr');
        const td = document.createElement('td'); td.colSpan = 5; td.className = 'text-center text-muted py-4'; td.textContent = 'Belum ada data untuk filter yang dipilih.';
        tr.appendChild(td); tbody.appendChild(tr);
      } else {
        rows.forEach(r => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td class="text-nowrap">${(r.created_at||'')}</td>
            <td class="text-nowrap">${(r.period||'')}</td>
            <td>${(r.info||'')}</td>
            <td class="text-end">${(r.amount_formatted||'')}</td>
            <td>${(r.type||'')}</td>
          `;
          tbody.appendChild(tr);
        });
      }
      renderPagination(meta);
    }

    function renderPagination(meta){
      pag.innerHTML = '';
      const totalPages = meta.total_pages || 1;
      const page = meta.page || 1;
      function add(label, target, disabled=false, active=false){
        const li = document.createElement('li'); li.className = `page-item ${disabled?'disabled':''} ${active?'active':''}`;
        const a = document.createElement('a'); a.className = 'page-link'; a.href = '#'; a.textContent = label;
        a.addEventListener('click', function(e){ e.preventDefault(); if (disabled || target===page) return; state.page = target; load(); });
        li.appendChild(a); pag.appendChild(li);
      }
      add('«', page-1, page<=1, false);
      for (let i=1;i<=totalPages;i++){ add(String(i), i, false, i===page); }
      add('»', page+1, page>=totalPages, false);
    }

    form.addEventListener('submit', function(e){ e.preventDefault(); state.page = 1; state.perPage = parseInt(perSel.value,10)||10; load(); });
    perSel.addEventListener('change', function(){ state.page = 1; state.perPage = parseInt(perSel.value,10)||10; load(); });
    document.addEventListener('DOMContentLoaded', load);
  })();
</script>

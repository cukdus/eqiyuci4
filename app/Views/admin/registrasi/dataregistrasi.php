<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Data Registrasi</h5>
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
            <table class="table table-bordered table-hover text-nowrap" id="registrasi-table">
              <thead>
                <tr>
                  <th style="width:60px" data-sort="id">ID</th>
                  <th data-sort="nama">Nama</th>
                  <th style="width:180px" data-sort="email">Email</th>
                  <th style="width:100px" data-sort="no_telp">No. Telepon</th>
                  <th style="width:80px" data-sort="lokasi">Lokasi</th>
                  <th style="width:350px" data-sort="nama_kelas">Kelas</th>
                  <th style="width:50px" data-sort="akses_aktif">Akses OC</th>
                  <th style="width:100px" data-sort="status_pembayaran">Status $</th>
                  <th style="width:100px">Aksi</th>
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
                    <?php
                    // Pemetaan kode kota -> nama kota (SSR)
                    $kotaMap = [];
                    if (!empty($kotaOptions)) {
                      foreach ($kotaOptions as $ko) {
                        $code = strtolower((string) ($ko['kode'] ?? ''));
                        $name = (string) ($ko['nama'] ?? $code);
                        if ($code !== '') {
                          $kotaMap[$code] = $name;
                        }
                      }
                    }
                    $lokRaw = (string) ($r['lokasi'] ?? '');
                    $lokKey = strtolower(trim($lokRaw));
                    $lokNama = $kotaMap[$lokKey] ?? $lokRaw;
                    ?>
                    <td><?= esc($lokNama !== '' ? $lokNama : '-') ?></td>
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
                      <i class="bi bi-cash <?= $status === 'dp 50%' ? 'text-warning' : ($status === 'lunas' ? 'text-success' : 'text-secondary') ?>">
                      </i>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="<?= base_url('admin/registrasi/' . ($r['id'] ?? 0) . '/edit') ?>" class="btn btn-sm btn-warning rounded-0 rounded-start" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="<?= base_url('admin/registrasi/' . ($r['id'] ?? 0) . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus registrasi ini?')">
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
                  <td colspan="9" class="text-center text-muted">Belum ada data registrasi.</td>
                </tr>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
          <div class="card-footer clearfix">
            
            <div id="ajaxPager" class="d-flex justify-content-between align-items-center mt-2" style="display:none;">
              <button class="btn btn-sm btn-outline-secondary" id="prevPageBtn">Prev</button>
              <div class="small text-muted" id="pageInfo">Page</div>
              <button class="btn btn-sm btn-outline-secondary" id="nextPageBtn">Next</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Peta kota pusat: kode -> nama (AJAX)
    const ALL_CITIES = <?php
$map = [];
if (!empty($kotaOptions)) {
  foreach ($kotaOptions as $ko) {
    $code = strtolower((string) ($ko['kode'] ?? ''));
    $name = (string) ($ko['nama'] ?? $code);
    if ($code !== '') {
      $map[$code] = $name;
    }
  }
}
echo json_encode($map, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
?>;
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

    // Hybrid: AJAX fetch untuk pencarian/pagination/sorting
    const table = document.getElementById('registrasi-table');
    const tbody = table.querySelector('tbody');
    const searchForm = document.querySelector('form.input-group');
    const searchInput = searchForm ? searchForm.querySelector('input[name="search"]') : null;
    const ajaxPager = document.getElementById('ajaxPager');
    const prevBtn = document.getElementById('prevPageBtn');
    const nextBtn = document.getElementById('nextPageBtn');
    const pageInfo = document.getElementById('pageInfo');

    let state = {
      page: 1,
      per_page: 10,
      search: (searchInput && searchInput.value) ? searchInput.value : '',
      sort: 'tanggal_daftar',
      order: 'desc',
      ajaxMode: false,
    };

    function formatWhatsAppLink(raw) {
      const digits = (raw || '').replace(/[^0-9]/g, '');
      if (!digits) return '#';
      const waNumber = digits[0] === '0' ? ('62' + digits.slice(1)) : digits;
      return 'https://wa.me/' + waNumber;
    }

    function renderRows(data) {
      tbody.innerHTML = '';
      if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Belum ada data registrasi.</td></tr>';
        return;
      }
      const rowsHtml = data.map(function(r) {
        const id = r.id || 0;
        const waUrl = formatWhatsAppLink(r.no_telp || '');
        const status = String(r.status_pembayaran || '').toLowerCase();
        const badgeClass = status === 'dp 50%' ? 'bg-warning' : (status === 'lunas' ? 'bg-success' : 'bg-secondary');
        const isOn = !!r.akses_aktif;
        return (
          '<tr>' +
            '<td>' + id + '</td>' +
            '<td>' + (r.nama || '-') + '</td>' +
            '<td>' + (r.email || '-') + '</td>' +
            '<td>' +
              '<div class="btn-group">' +
                '<a href="' + waUrl + '" target="_blank" rel="noopener" class="btn btn-sm btn-success"><i class="bi bi-whatsapp me-1"></i></a>' +
                '<a href="' + waUrl + '" target="_blank" rel="noopener" class="btn btn-sm btn-warning"><i class="bi bi-whatsapp me-1"></i></a>' +
              '</div>' +
            '</td>' +
            '<td>' + (function(){
                const key = String(r.lokasi || '').trim().toLowerCase();
                const name = ALL_CITIES[key] || (r.lokasi || '-');
                return name;
            })() + '</td>' +
            '<td>' + (r.nama_kelas || '-') + '</td>' +
            '<td>' +
              '<div class="form-check form-switch">' +
                '<input class="form-check-input akses-toggle" type="checkbox" ' + (isOn ? 'checked' : '') + ' data-id="' + id + '" data-url="<?= base_url('admin/registrasi') ?>/' + id + '/toggle-akses">' +
                '<label class="form-check-label"></label>' +
              '</div>' +
            '</td>' +
            '<td><span class="badge ' + badgeClass + '">' + (status ? (status.charAt(0).toUpperCase() + status.slice(1)) : 'unknown') + '</span></td>' +
            '<td>' +
              '<div class="btn-group" role="group">' +
                '<a href="<?= base_url('admin/registrasi') ?>/' + id + '/edit" class="btn btn-sm btn-warning rounded-0 rounded-start" title="Edit"><i class="bi bi-pencil-square"></i></a>' +
                '<form action="<?= base_url('admin/registrasi') ?>/' + id + '/delete" method="post" class="d-inline" onsubmit="return confirm(\'Yakin hapus registrasi ini?\')">' +
                  '<?= csrf_field() ?>' +
                  '<button type="submit" class="btn btn-sm btn-danger rounded-0 rounded-end" title="Delete"><i class="bi bi-trash"></i></button>' +
                '</form>' +
              '</div>' +
            '</td>' +
          '</tr>'
        );
      }).join('');
      tbody.innerHTML = rowsHtml;

      // Re-bind toggle handlers after re-render
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
              body: JSON.stringify({ id: id, akses_aktif: isChecked ? 1 : 0 })
          })
          .then(r => r.json())
          .then(data => { if (!data.success) { this.checked = !isChecked; alert('Gagal mengupdate akses: ' + (data.message || 'Unknown error')); } })
          .catch(err => { this.checked = !isChecked; alert('Error: ' + err.message); });
        });
      });
    }

    function updatePager(meta) {
      if (!meta) return;
      ajaxPager.style.display = 'flex';
      prevBtn.disabled = !meta.has_prev;
      nextBtn.disabled = !meta.has_next;
      pageInfo.textContent = 'Halaman ' + meta.page + ' dari ' + meta.total_pages + ' • Total ' + meta.total;
    }

    function fetchData() {
      const params = new URLSearchParams({
        page: String(state.page),
        per_page: String(state.per_page),
        search: state.search || '',
        sort: state.sort || 'tanggal_daftar',
        order: state.order || 'desc',
      });
      const url = '<?= base_url('admin/registrasi.json') ?>?' + params.toString();
      return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json());
    }

    function goAjax() {
      state.ajaxMode = true;
      // Sembunyikan pager SSR jika ada
      const ssrPager = document.querySelector('.card-footer .pagination');
      if (ssrPager) ssrPager.style.display = 'none';
      fetchData().then(json => { renderRows(json.data || []); updatePager(json.meta || null); });
    }

    // Bind search submit to AJAX
    if (searchForm) {
      searchForm.addEventListener('submit', function(ev) {
        ev.preventDefault();
        state.search = searchInput ? searchInput.value : '';
        state.page = 1;
        goAjax();
      });
    }

    // Bind sorting on headers
    table.querySelectorAll('thead th[data-sort]').forEach(function(th) {
      th.style.cursor = 'pointer';
      th.addEventListener('click', function() {
        const key = this.getAttribute('data-sort');
        if (!key) return;
        if (state.sort === key) {
          state.order = state.order === 'asc' ? 'desc' : 'asc';
        } else {
          state.sort = key;
          state.order = 'asc';
        }
        state.page = 1;
        goAjax();
      });
    });

    // Bind pagination buttons
    prevBtn.addEventListener('click', function() { if (state.page > 1) { state.page -= 1; goAjax(); } });
    nextBtn.addEventListener('click', function() { state.page += 1; goAjax(); });
});
</script>
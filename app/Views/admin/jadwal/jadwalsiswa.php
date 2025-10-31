<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Data Jadwal Peserta</h5>
    </div>
    <?php if (session()->has('alert')):
      $alert = session('alert'); ?>
      <div class="alert alert-<?= esc($alert['type']) ?> alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <h5><i class="icon fas <?= $alert['type'] === 'success' ? 'fa-check' : 'fa-ban'; ?>"></i> <?= $alert['type'] === 'success' ? 'Sukses!' : 'Error!'; ?></h5>
        <?= esc($alert['message']) ?>
      </div>
    <?php endif; ?>

    <!-- Filter Jadwal Kelas -->
    <div class="card card-outline card-primary mb-3">
      <div class="card-header">
        <h3 class="card-title">Filter Jadwal Kelas</h3>
      </div>
      <div class="card-body">
        <form id="filterForm" class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Bulan</label>
            <select name="month" id="filterMonth" class="form-select">
              <option value="0">-- Semua Bulan --</option>
              <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>"><?= date('F', mktime(0, 0, 0, $m, 10)) ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Tahun</label>
            <select name="year" id="filterYear" class="form-select">
              <?php $startYear = 2023;
              $endYear = (int) date('Y') + 1; ?>
              <?php for ($y = $startYear; $y <= $endYear; $y++): ?>
                <option value="<?= $y ?>" <?= $y === (int) date('Y') ? 'selected' : '' ?>><?= $y ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Jenis Kelas</label>
            <select name="kelas_id" id="filterKelas" class="form-select">
              <option value="0">-- Semua Kelas --</option>
              <?php foreach (($kelasOptions ?? []) as $kelas): ?>
                <option value="<?= esc($kelas['id']) ?>"><?= esc($kelas['nama_kelas']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Pencarian</label>
            <input type="text" id="filterSearch" class="form-control" placeholder="Cari nama, telepon, kelas, lokasi, trainer">
          </div>
          <div class="col-md-6 d-flex align-items-end gap-2">
            <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Terapkan</button>
            <button class="btn btn-secondary" type="button" id="btnReset"><i class="fas fa-sync"></i> Reset</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Daftar Peserta -->
    <div class="card card-outline card-success">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="card-title">Daftar Peserta</h3>
        </div>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap" id="tabelPeserta">
          <thead>
            <tr>
              <th style="width:50px;">No</th>
              <th data-sort="nama" class="sortable">Nama Peserta</th>
              <th>No. Telepon</th>
              <th>Kelas</th>
              <th data-sort="tanggal_mulai" class="sortable">Tanggal</th>
              <th>Lokasi</th>
              <th>Reschedule</th>
              <th data-sort="status_pembayaran" class="sortable">Status $</th>
              <th style="width:80px;">Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="card-footer clearfix">
        <ul id="pagination" class="pagination pagination-sm m-0 float-end"></ul>
      </div>
    </div>
  </div>

  <script>
    (function() {
      const endpoint = '<?= base_url('admin/jadwal/siswa.json') ?>';
      const endpointAvailable = '<?= base_url('admin/jadwal/siswa/available.json') ?>';
      const endpointReschedule = '<?= base_url('admin/registrasi/reschedule') ?>';
      const csrfToken = '<?= csrf_token() ?>';
      const csrfHash = '<?= csrf_hash() ?>';
      let state = {
        page: 1,
        per_page: 10,
        sort: 'tanggal_mulai',
        order: 'desc',
        filtered: false
      };
      let availableSchedules = [];

      const tbody = document.querySelector('#tabelPeserta tbody');
      const pagination = document.getElementById('pagination');

      function getFilters() {
        return {
          month: document.getElementById('filterMonth').value || 0,
          year: document.getElementById('filterYear').value || new Date().getFullYear(),
          kelas_id: document.getElementById('filterKelas').value || 0,
          search: document.getElementById('filterSearch').value.trim()
        };
      }

      function buildQuery(params) {
        const usp = new URLSearchParams(params);
        return usp.toString();
      }

      async function loadData() {
        const filters = getFilters();
        const q = buildQuery({ ...filters, ...state });
        const res = await fetch(`${endpoint}?${q}`);
        const json = await res.json();
        if (!availableSchedules.length) {
          try {
            const res2 = await fetch(endpointAvailable);
            const json2 = await res2.json();
            availableSchedules = json2.data || [];
          } catch (e) {
            availableSchedules = [];
          }
        }
        renderTable(json.data || [], json.meta || { page: 1, total_pages: 1 });
      }

      function renderTable(rows, meta) {
        tbody.innerHTML = '';
        const startIndex = (meta.page - 1) * state.per_page;
        if (!rows.length) {
          const tr = document.createElement('tr');
          const td = document.createElement('td');
          td.colSpan = 8;
          td.className = 'text-center text-muted';
          td.textContent = 'Tidak ada data peserta';
          tr.appendChild(td);
          tbody.appendChild(tr);
        } else {
          rows.forEach((row, idx) => {
            const tr = document.createElement('tr');
            // Build reschedule select options
            const currentOption = buildCurrentOption(row);
            const options = buildAvailableOptions(row);

            tr.innerHTML = `
              <td>${startIndex + idx + 1}.</td>
              <td>${escapeHtml(row.nama ?? '')}</td>
              <td>${escapeHtml(row.no_telp ?? '')}</td>
              <td>${escapeHtml(row.nama_kelas ?? '-')}</td>
              <td>${formatRangeDate(row.tanggal_mulai, row.tanggal_selesai)}</td>
              <td>${escapeHtml((row.lokasi ?? '').charAt(0).toUpperCase() + (row.lokasi ?? '').slice(1))}</td>
              <td>
                <div class="input-group input-group-sm">
                  <select class="form-select form-select-sm" id="jadwal-${row.id}">
                    ${currentOption}
                    ${options}
                  </select>
                  <button class="btn btn-sm btn-primary" data-id="${row.id}" data-current="${row.jadwal_id ?? ''}">
                    <i class="fas fa-calendar-alt"></i> Ubah
                  </button>
                </div>
              </td>
              <td><span class="badge ${badgeClass(row.status_pembayaran)}">${escapeHtml(row.status_pembayaran ?? '-')}</span></td>
              <td>
                <a class="btn btn-sm btn-info" href="<?= base_url('admin/registrasi') ?>?search=${encodeURIComponent(row.nama ?? '')}">
                  <i class="fas fa-eye"></i> Detail
                </a>
              </td>
            `;
            // Attach click handler for reschedule
            const btn = tr.querySelector('button.btn-primary');
            btn.addEventListener('click', async (e) => {
              e.preventDefault();
              const regId = btn.getAttribute('data-id');
              const currentId = btn.getAttribute('data-current');
              const select = tr.querySelector(`#jadwal-${row.id}`);
              const newId = select.value;
              if (!newId || newId === currentId) {
                alert('Pilih jadwal baru yang berbeda.');
                return;
              }
              try {
                const formData = new URLSearchParams();
                formData.append(csrfToken, csrfHash);
                formData.append('registrasi_id', regId);
                formData.append('new_jadwal_id', newId);
                const resp = await fetch(endpointReschedule, {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                  body: formData.toString(),
                });
                const j = await resp.json();
                if (j.success) {
                  alert('Reschedule berhasil');
                  loadData();
                } else {
                  alert(j.message || 'Reschedule gagal');
                }
              } catch (err) {
                alert('Terjadi kesalahan jaringan');
              }
            });
            tbody.appendChild(tr);
          });
        }

        // Render pagination
        pagination.innerHTML = '';
        const totalPages = meta.total_pages || 1;
        const page = meta.page || 1;
        function addPageItem(label, targetPage, disabled = false, active = false) {
          const li = document.createElement('li');
          li.className = `page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}`;
          const a = document.createElement('a');
          a.className = 'page-link';
          a.href = '#';
          a.textContent = label;
          a.addEventListener('click', (e) => {
            e.preventDefault();
            if (disabled || targetPage === state.page) return;
            state.page = targetPage;
            loadData();
          });
          li.appendChild(a);
          pagination.appendChild(li);
        }
        addPageItem('«', page - 1, page <= 1, false);
        for (let i = 1; i <= totalPages; i++) {
          addPageItem(String(i), i, false, i === page);
        }
        addPageItem('»', page + 1, page >= totalPages, false);
      }

      function escapeHtml(str) {
        return String(str).replace(/[&<>"]+/g, function (s) {
          const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' };
          return map[s] || s;
        });
      }

      function formatRangeDate(start, end) {
        if (!start) return '-';
        const s = new Date(start);
        const e = end ? new Date(end) : null;
        const fmt = (d) => `${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
        return e ? `${fmt(s)} s/d ${fmt(e)}` : fmt(s);
      }

      function badgeClass(status) {
        switch (status) {
          case 'lunas': return 'bg-success';
          case 'DP 50%': return 'bg-warning';
          default: return 'bg-danger';
        }
      }

      // Build <option> for current schedule selection
      function buildCurrentOption(row) {
        if (!row.jadwal_id) return '<option value="">-- Pilih Jadwal --</option>';
        const tanggal = row.tanggal_mulai ? new Date(row.tanggal_mulai) : null;
        const labelTanggal = tanggal ? `${String(tanggal.getDate()).padStart(2,'0')}/${String(tanggal.getMonth()+1).padStart(2,'0')}/${tanggal.getFullYear()}` : '-';
        const lokasi = (row.lokasi ?? '').toLowerCase();
        const labelLokasi = lokasi ? lokasi.charAt(0).toUpperCase() + lokasi.slice(1) : '-';
        return `<option value="${row.jadwal_id}" selected>${labelTanggal} - ${escapeHtml(labelLokasi)}</option>`;
      }

      // Build <option> list for available schedules excluding current
      function buildAvailableOptions(row) {
        const curId = row.jadwal_id ?? null;
        return availableSchedules
          .filter(j => String(j.id) !== String(curId))
          .map(j => {
            const d = new Date(j.tanggal_mulai);
            const labelTanggal = `${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
            const lokasi = (j.lokasi ?? '').toLowerCase();
            const labelLokasi = lokasi ? lokasi.charAt(0).toUpperCase() + lokasi.slice(1) : '-';
            const namaKelas = j.nama_kelas ?? '';
            const info = `(${j.jumlah_peserta ?? 0}/${j.kapasitas ?? 0})`;
            return `<option value="${j.id}">${labelTanggal} - ${escapeHtml(namaKelas)} - ${escapeHtml(labelLokasi)} ${info}</option>`;
          })
          .join('');
      }

      // Filter form handlers
      document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        state.page = 1;
        state.filtered = true;
        loadData();
      });
      document.getElementById('btnReset').addEventListener('click', function() {
        document.getElementById('filterMonth').value = 0;
        document.getElementById('filterYear').value = new Date().getFullYear();
        document.getElementById('filterKelas').value = 0;
        document.getElementById('filterSearch').value = '';
        state.page = 1;
        state.sort = 'tanggal_mulai';
        state.order = 'desc';
        state.filtered = false;
        loadData();
      });

      // Sorting handlers
      document.querySelectorAll('#tabelPeserta thead th.sortable').forEach((th) => {
        th.style.cursor = 'pointer';
        th.addEventListener('click', () => {
          const sortKey = th.getAttribute('data-sort');
          if (!sortKey) return;
          if (state.sort === sortKey) {
            state.order = state.order === 'asc' ? 'desc' : 'asc';
          } else {
            state.sort = sortKey;
            state.order = 'asc';
          }
          state.page = 1;
          loadData();
        });
      });

      // Initial load
      loadData();
    })();
  </script>
</section>
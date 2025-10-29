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
                      <form id="formSearch" class="input-group input-group-sm" style="width: 250px;">
                          <input type="text" name="search" id="searchInput" class="form-control float-right" placeholder="Cari artikel..." value="">
                          <button type="submit" class="btn btn-default">
                              <i class="fas fa-search"></i>
                          </button>
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
                  <th style="width:120px">Aksi</th>
                </tr>
              </thead>
              <tbody id="articleTbody"></tbody>
            </table>
            <div id="emptyState" class="text-center text-muted d-none">Belum ada data artikel.</div>
          </div>
          <div class="card-footer clearfix">
            <div id="paginationControls" class="d-flex justify-content-end align-items-center gap-2"></div>
          </div>
        </div>
      </div>
    </div>

    <script>
      (function(){
        const endpointList = '<?= base_url('admin/artikel.json') ?>';
        const endpointDup = (id) => '<?= base_url('admin/artikel') ?>/' + id + '/duplicate';
        const endpointDel = (id) => '<?= base_url('admin/artikel') ?>/' + id + '/delete';
        const endpointEdit = (id) => '<?= base_url('admin/artikel') ?>/' + id + '/edit';
        const csrfToken = '<?= csrf_token() ?>';
        const csrfHash = '<?= csrf_hash() ?>';

        const formSearch = document.getElementById('formSearch');
        const inputSearch = document.getElementById('searchInput');
        const tbody = document.getElementById('articleTbody');
        const emptyState = document.getElementById('emptyState');
        const pagerEl = document.getElementById('paginationControls');

        let currentPage = 1;
        const perPage = 10;

        formSearch.addEventListener('submit', function(e){
          e.preventDefault();
          currentPage = 1;
          loadArticles();
        });

        async function loadArticles(){
          tbody.innerHTML = '';
          emptyState.classList.add('d-none');
          pagerEl.innerHTML = '';
          const q = encodeURIComponent(inputSearch.value.trim());
          const url = `${endpointList}?page=${currentPage}&per_page=${perPage}&search=${q}`;
          try {
            const res = await fetch(url);
            const j = await res.json();
            const data = Array.isArray(j.data) ? j.data : [];
            renderRows(data);
            renderPager(j.meta || {page:1,totalPages:1});
          } catch (err) {
            emptyState.classList.remove('d-none');
            emptyState.textContent = 'Gagal memuat data.';
          }
        }

        function renderRows(rows){
          if (!rows.length) {
            emptyState.classList.remove('d-none');
            return;
          }
          const html = rows.map(function(a){
            const dtInfo = formatDateInfo(a.tanggal_terbit);
            return `
              <tr>
                <td><i class="nav-icon bi bi-hand-thumbs-up-fill ${dtInfo.iconClass}"></i><span class="ms-1">${dtInfo.dateText}</span></td>
                <td>${escapeHtml(a.judul)}</td>
                <td>${escapeHtml(a.penulis || '-')}</td>
                <td>${escapeHtml(a.kategori_nama || '-')}</td>
                <td>
                  <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info rounded-0 rounded-start" title="Duplikat" data-act="dup" data-id="${a.id}"><i class="bi bi-files"></i></button>
                    <a href="${endpointEdit(a.id)}" class="btn btn-sm btn-warning rounded-0" title="Edit"><i class="bi bi-pencil-square"></i></a>
                    <button class="btn btn-sm btn-danger rounded-0 rounded-end" title="Delete" data-act="del" data-id="${a.id}"><i class="bi bi-trash"></i></button>
                  </div>
                </td>
              </tr>
            `;
          }).join('');
          tbody.innerHTML = html;
          tbody.querySelectorAll('[data-act="dup"]').forEach(btn => {
            btn.addEventListener('click', async function(){
              const id = this.getAttribute('data-id');
              const fd = new URLSearchParams(); fd.append(csrfToken, csrfHash);
              const res = await fetch(endpointDup(id), { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
              if (res.ok) { loadArticles(); } else { alert('Gagal menduplikasi'); }
            });
          });
          tbody.querySelectorAll('[data-act="del"]').forEach(btn => {
            btn.addEventListener('click', async function(){
              if (!confirm('Yakin hapus artikel ini?')) return;
              const id = this.getAttribute('data-id');
              const fd = new URLSearchParams(); fd.append(csrfToken, csrfHash);
              const res = await fetch(endpointDel(id), { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
              if (res.ok) { loadArticles(); } else { alert('Gagal menghapus'); }
            });
          });
        }

        function renderPager(meta){
          const page = Number(meta.page || 1);
          const totalPages = Number(meta.totalPages || 1);
          const prevDisabled = page <= 1 ? 'disabled' : '';
          const nextDisabled = page >= totalPages ? 'disabled' : '';
          const prevBtn = document.createElement('button');
          prevBtn.className = `btn btn-sm btn-outline-secondary ${prevDisabled}`;
          prevBtn.textContent = '«';
          prevBtn.disabled = !!prevDisabled;
          prevBtn.addEventListener('click', function(){ if (currentPage > 1) { currentPage--; loadArticles(); } });
          const pageInfo = document.createElement('span');
          pageInfo.className = 'small text-muted';
          pageInfo.textContent = `Page ${page} / ${totalPages}`;
          const nextBtn = document.createElement('button');
          nextBtn.className = `btn btn-sm btn-outline-secondary ${nextDisabled}`;
          nextBtn.textContent = '»';
          nextBtn.disabled = !!nextDisabled;
          nextBtn.addEventListener('click', function(){ if (currentPage < totalPages) { currentPage++; loadArticles(); } });
          pagerEl.appendChild(prevBtn);
          pagerEl.appendChild(pageInfo);
          pagerEl.appendChild(nextBtn);
        }

        function formatDateInfo(dtStr){
          let iconClass = 'text-secondary';
          let dateText = '-';
          if (dtStr) {
            try {
              // Expect format YYYY-MM-DD HH:MM:SS
              const parts = dtStr.split(' ');
              const d = parts[0] || '';
              const [y,m,dn] = d.split('-').map(Number);
              if (y && m && dn) {
                const dateObj = new Date(y, m-1, dn);
                const now = new Date();
                iconClass = (dateObj.getTime() <= now.getTime()) ? 'text-success' : 'text-warning';
                dateText = `${String(dn).padStart(2,'0')}-${String(m).padStart(2,'0')}-${y}`;
              }
            } catch (e) {
              iconClass = 'text-secondary';
            }
          }
          return { iconClass, dateText };
        }

        function escapeHtml(str){
          str = String(str == null ? '' : str);
          return str.replace(/[&<>"']/g, function(m){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'})[m]; });
        }

        // Initial load
        loadArticles();
      })();
    </script>
  </div>
</section>
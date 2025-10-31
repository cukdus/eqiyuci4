<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Data Sertifikat</h5>
    </div>

    <div class="card card-outline card-primary mb-3">
      <div class="card-header"><h3 class="card-title">Filter & Generate Sertifikat</h3></div>
      <div class="card-body">
        <form id="formFilter" class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Cari Nama/Kelas</label>
            <input type="text" id="searchInput" class="form-control" placeholder="Ketik nama atau kelas">
          </div>
          <div class="col-md-4">
            <label class="form-label">Pilih Kelas</label>
            <select id="kelasSelect" class="form-select">
              <option value="">-- Semua Kelas --</option>
              <?php foreach (($kelasList ?? []) as $k): ?>
                <option value="<?= esc($k['kode_kelas']) ?>">[<?= esc($k['kode_kelas']) ?>] <?= esc($k['nama_kelas']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4 d-flex align-items-end justify-content-end">
            <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Terapkan</button>
          </div>
        </form>
        <hr>
        <div class="table-responsive">
          <table class="table table-bordered table-hover text-nowrap">
            <thead>
              <tr>
                <th style="width:150px">Tanggal Daftar</th>
                <th>Nama</th>
                <th style="width:160px">Kelas</th>
                <th style="width:120px">Aksi</th>
              </tr>
            </thead>
            <tbody id="registrasiTbody"></tbody>
          </table>
          <div id="registrasiEmpty" class="text-muted text-center d-none">Belum ada data registrasi.</div>
        </div>
        <div class="d-flex justify-content-end align-items-center gap-2" id="registrasiPager"></div>
      </div>
    </div>

    <div class="card card-outline card-success">
      <div class="card-header"><h3 class="card-title">Daftar Sertifikat</h3></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover text-nowrap">
            <thead>
              <tr>
                <th style="width:150px">Tanggal Terbit</th>
                <th>Nomor Sertifikat</th>
                <th>Nama</th>
                <th style="width:160px">Kelas</th>
                <th style="width:120px">Aksi</th>
              </tr>
            </thead>
            <tbody id="sertifikatTbody"></tbody>
          </table>
          <div id="sertifikatEmpty" class="text-muted text-center d-none">Belum ada sertifikat.</div>
        </div>
        <div class="d-flex justify-content-end align-items-center gap-2" id="sertifikatPager"></div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      const endpointReg = '<?= base_url('admin/sertifikat/registrasi.json') ?>';
      const endpointCert = '<?= base_url('admin/sertifikat.json') ?>';
      const endpointGenerate = '<?= base_url('admin/sertifikat/generate') ?>';
      const endpointDelete = (id) => '<?= base_url('admin/sertifikat') ?>/' + id + '/delete';
      const csrfToken = '<?= csrf_token() ?>';
      const csrfHash = '<?= csrf_hash() ?>';

      const formFilter = document.getElementById('formFilter');
      const inputSearch = document.getElementById('searchInput');
      const kelasSelect = document.getElementById('kelasSelect');

      const regBody = document.getElementById('registrasiTbody');
      const regEmpty = document.getElementById('registrasiEmpty');
      const regPager = document.getElementById('registrasiPager');
      const certBody = document.getElementById('sertifikatTbody');
      const certEmpty = document.getElementById('sertifikatEmpty');
      const certPager = document.getElementById('sertifikatPager');

      let regPage = 1, regPerPage = 10;
      let certPage = 1, certPerPage = 10;

      formFilter.addEventListener('submit', function(e){ e.preventDefault(); regPage = 1; loadRegistrasi(); });

      async function loadRegistrasi(){
        regBody.innerHTML = '';
        regEmpty.classList.add('d-none');
        regPager.innerHTML = '';
        const params = new URLSearchParams({ page: String(regPage), per_page: String(regPerPage) });
        const s = inputSearch.value.trim(); if (s) params.append('search', s);
        const kk = kelasSelect.value.trim(); if (kk) params.append('kode_kelas', kk);
        try {
          const res = await fetch(endpointReg + '?' + params.toString());
          const j = await res.json();
          const rows = Array.isArray(j.data) ? j.data : [];
          renderRegistrasi(rows);
          renderPager(regPager, j.meta, function(dir){ regPage += dir; loadRegistrasi(); });
        } catch (e) {
          regEmpty.classList.remove('d-none');
          regEmpty.textContent = 'Gagal memuat data registrasi';
        }
      }

      function renderRegistrasi(rows){
        if (!rows.length){ regEmpty.classList.remove('d-none'); return; }
        const html = rows.map(function(r){
          const dtInfo = formatDate(r.tanggal_daftar);
          return `
            <tr>
              <td><span class="small text-muted">${dtInfo}</span></td>
              <td>${escapeHtml(r.nama)}<div class="small text-muted">${escapeHtml(r.email || '')}</div></td>
              <td>${escapeHtml(r.nama_kelas || '-')}</td>
              <td>
                <button class="btn btn-sm btn-success" data-act="gen" data-id="${r.id}"><i class="fas fa-award"></i> Generate</button>
              </td>
            </tr>
          `;
        }).join('');
        regBody.innerHTML = html;
        regBody.querySelectorAll('[data-act="gen"]').forEach(function(btn){
          btn.addEventListener('click', async function(){
            const id = this.getAttribute('data-id');
            const fd = new URLSearchParams(); fd.append(csrfToken, csrfHash); fd.append('registrasi_id', id);
            const res = await fetch(endpointGenerate, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
            const j = await res.json(); if (j.success) { loadSertifikat(); loadRegistrasi(); } else { alert(j.message || 'Gagal generate'); }
          });
        });
      }

      async function loadSertifikat(){
        certBody.innerHTML = '';
        certEmpty.classList.add('d-none');
        certPager.innerHTML = '';
        const params = new URLSearchParams({ page: String(certPage), per_page: String(certPerPage) });
        try {
          const res = await fetch(endpointCert + '?' + params.toString());
          const j = await res.json();
          const rows = Array.isArray(j.data) ? j.data : [];
          renderSertifikat(rows);
          renderPager(certPager, j.meta, function(dir){ certPage += dir; loadSertifikat(); });
        } catch (e) {
          certEmpty.classList.remove('d-none');
          certEmpty.textContent = 'Gagal memuat data sertifikat';
        }
      }

      function renderSertifikat(rows){
        if (!rows.length){ certEmpty.classList.remove('d-none'); return; }
        const html = rows.map(function(s){
          const dtInfo = formatDate(s.tanggal_terbit);
          return `
            <tr>
              <td><span class="small text-muted">${dtInfo}</span></td>
              <td>${escapeHtml(s.nomor_sertifikat)}</td>
              <td>${escapeHtml(s.nama_pemilik)}</td>
              <td>${escapeHtml(s.nama_kelas || '-')}</td>
              <td>
                <div class="btn-group">
                  <a class="btn btn-sm btn-info" href="<?= base_url('admin/sertifikat') ?>/${s.id}/view" target="_blank" title="Lihat Sertifikat"><i class="fas fa-eye"></i></a>
                  <button class="btn btn-sm btn-outline-danger" data-act="del" data-id="${s.id}"><i class="fas fa-trash"></i></button>
                </div>
              </td>
            </tr>
          `;
        }).join('');
        certBody.innerHTML = html;
        certBody.querySelectorAll('[data-act="del"]').forEach(function(btn){
          btn.addEventListener('click', async function(){
            if (!confirm('Hapus sertifikat ini?')) return;
            const id = this.getAttribute('data-id');
            const fd = new URLSearchParams(); fd.append(csrfToken, csrfHash);
            const res = await fetch(endpointDelete(id), { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
            const j = await res.json(); if (j.success) { loadSertifikat(); } else { alert(j.message || 'Gagal menghapus'); }
          });
        });
      }

      function renderPager(container, meta, onMove){
        const page = Number(meta?.page || 1);
        const totalPages = Number(meta?.total_pages || meta?.totalPages || 1);
        const prev = document.createElement('button');
        prev.className = 'btn btn-sm btn-outline-secondary';
        prev.textContent = '«'; prev.disabled = page <= 1;
        prev.addEventListener('click', function(){ if (page > 1) onMove(-1); });
        const info = document.createElement('span'); info.className = 'small text-muted'; info.textContent = `Page ${page} / ${totalPages}`;
        const next = document.createElement('button');
        next.className = 'btn btn-sm btn-outline-secondary';
        next.textContent = '»'; next.disabled = page >= totalPages;
        next.addEventListener('click', function(){ if (page < totalPages) onMove(+1); });
        container.appendChild(prev); container.appendChild(info); container.appendChild(next);
      }

      function formatDate(dt){
        if (!dt) return '-';
        try {
          const [y,m,dtime] = dt.split('-');
          const dn = (dtime || '').split(' ')[0];
          const dd = String(dn || '').padStart(2,'0');
          return `${dd}-${String(m).padStart(2,'0')}-${y}`;
        } catch(e){ return dt; }
      }

      function escapeHtml(str){
        str = String(str == null ? '' : str);
        return str.replace(/[&<>"']/g, function(m){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'})[m]; });
      }
      function escapeAttr(str){
        return escapeHtml(str).replace(/"/g, '&quot;');
      }

      // Initial load
      loadRegistrasi();
      loadSertifikat();
    })();
  </script>
</section>
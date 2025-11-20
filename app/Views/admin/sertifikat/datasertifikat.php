<?php $me = service('authentication')->user();
$authz = service('authorization');
$isAdmin = $me && $authz->inGroup('admin', $me->id);
$isStaff = $me && $authz->inGroup('staff', $me->id);
$canEditCert = $isAdmin;
$showFilterGenerate = $isAdmin; ?>
<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Data Sertifikat</h5>
    </div>

    <?php if ($showFilterGenerate): ?>
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
    <?php endif; ?>

    <div class="card card-outline card-success">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Sertifikat</h3>
        <div class="ms-auto" style="max-width: 320px;">
          <div class="input-group input-group-sm">
            <input type="text" id="certSearch" class="form-control" placeholder="Cari nama / nomor sertifikat">
            <button class="btn btn-primary" type="button" id="certSearchBtn"><i class="fas fa-search"></i> Cari</button>
          </div>
        </div>
      </div>
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

  <!-- Modal: Generate Sertifikat (tanggal terbit) -->
  <div class="modal fade" id="modalGenerate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Generate Sertifikat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formGenerate">
            <input type="hidden" name="registrasi_id" id="genRegistrasiId">
            <div class="mb-3">
              <label class="form-label">Tanggal Terbit</label>
              <input type="date" class="form-control" name="tanggal_terbit" id="genTanggalTerbit" required>
              <div class="form-text">Format: YYYY-MM-DD</div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="btnConfirmGenerate"><i class="fas fa-award me-1"></i> Generate</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Edit Sertifikat -->
  <div class="modal fade" id="modalEditCert" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Sertifikat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formEditCert">
            <input type="hidden" name="id" id="editCertId">
            <div class="mb-3">
              <label class="form-label">Status Sertifikat</label>
              <select class="form-select" name="status" id="editStatus">
                <option value="">-- Pilih Status --</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
                <option value="dicabut">Dicabut</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal Terbit</label>
              <input type="date" class="form-control" name="tanggal_terbit" id="editTanggalTerbit">
            </div>
            <div class="mb-3">
              <label class="form-label">Nama Kelas</label>
              <input type="text" class="form-control" name="nama_kelas" id="editNamaKelas" placeholder="contoh: English Conversation">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-success" id="btnConfirmEdit"><i class="fas fa-save me-1"></i> Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      const endpointReg = '<?= base_url('admin/sertifikat/registrasi.json') ?>';
      const endpointCert = '<?= base_url('admin/sertifikat.json') ?>';
      const endpointGenerate = '<?= base_url('admin/sertifikat/generate') ?>';
      const endpointUpdate = '<?= base_url('admin/sertifikat/update') ?>';
      const endpointDelete = (id) => '<?= base_url('admin/sertifikat') ?>/' + id + '/delete';
      const csrfToken = '<?= csrf_token() ?>';
      const csrfHash = '<?= csrf_hash() ?>';

      const formFilter = document.getElementById('formFilter');
      const inputSearch = document.getElementById('searchInput');
      const kelasSelect = document.getElementById('kelasSelect');
      const canEditCert = <?= $canEditCert ? 'true' : 'false' ?>;

      const regBody = document.getElementById('registrasiTbody');
      const regEmpty = document.getElementById('registrasiEmpty');
      const regPager = document.getElementById('registrasiPager');
      const certBody = document.getElementById('sertifikatTbody');
      const certEmpty = document.getElementById('sertifikatEmpty');
      const certPager = document.getElementById('sertifikatPager');
      const certSearch = document.getElementById('certSearch');
      const certSearchBtn = document.getElementById('certSearchBtn');

      // Modal refs
      const modalGenerateEl = document.getElementById('modalGenerate');
      const genRegistrasiId = document.getElementById('genRegistrasiId');
      const genTanggalTerbit = document.getElementById('genTanggalTerbit');
      const btnConfirmGenerate = document.getElementById('btnConfirmGenerate');

      const modalEditEl = document.getElementById('modalEditCert');
      const editCertId = document.getElementById('editCertId');
      const editStatus = document.getElementById('editStatus');
      const editTanggalTerbit = document.getElementById('editTanggalTerbit');
      const editNamaKelas = document.getElementById('editNamaKelas');
      const btnConfirmEdit = document.getElementById('btnConfirmEdit');

      // Bootstrap modal helper (fallback to simple show)
      function showModal(el){
        try {
          if (window.bootstrap && typeof window.bootstrap.Modal === 'function') {
            const m = window.bootstrap.Modal.getOrCreateInstance(el);
            m.show();
            return;
          }
        } catch(_){}
        el.classList.add('show'); el.style.display = 'block'; el.removeAttribute('aria-hidden');
      }
      function hideModal(el){
        try {
          if (window.bootstrap && typeof window.bootstrap.Modal === 'function') {
            const m = window.bootstrap.Modal.getOrCreateInstance(el);
            m.hide();
            return;
          }
        } catch(_){}
        el.classList.remove('show'); el.style.display = 'none'; el.setAttribute('aria-hidden','true');
      }

      let regPage = 1, regPerPage = 10;
      let certPage = 1, certPerPage = 10;

      if (formFilter) {
        formFilter.addEventListener('submit', function(e){ e.preventDefault(); regPage = 1; loadRegistrasi(); });
      }

      async function loadRegistrasi(){
        if (!formFilter) return;
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
          btn.addEventListener('click', function(){
            const id = this.getAttribute('data-id');
            genRegistrasiId.value = id;
            // default tanggal ke hari ini
            const today = new Date();
            const y = today.getFullYear();
            const m = String(today.getMonth()+1).padStart(2,'0');
            const d = String(today.getDate()).padStart(2,'0');
            genTanggalTerbit.value = `${y}-${m}-${d}`;
            showModal(modalGenerateEl);
          });
        });

        btnConfirmGenerate.addEventListener('click', async function(){
          const id = genRegistrasiId.value;
          const tgl = (genTanggalTerbit.value || '').trim();
          if (!tgl) { alert('Tanggal terbit wajib diisi'); return; }
          const fd = new URLSearchParams(); fd.append(csrfToken, csrfHash); fd.append('registrasi_id', id); fd.append('tanggal_terbit', tgl);
          try {
            const res = await fetch(endpointGenerate, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
            const j = await res.json();
            if (j.success) { hideModal(modalGenerateEl); loadSertifikat(); loadRegistrasi(); }
            else { alert(j.message || 'Gagal generate'); }
          } catch(e){ alert('Gagal generate'); }
        });
      }

      async function loadSertifikat(){
        certBody.innerHTML = '';
        certEmpty.classList.add('d-none');
        certPager.innerHTML = '';
        const params = new URLSearchParams({ page: String(certPage), per_page: String(certPerPage) });
        const q = (certSearch && certSearch.value || '').trim();
        if (q) params.append('search', q);
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
          const editBtn = canEditCert ? `<button class="btn btn-sm btn-warning" data-act="edit" data-id="${s.id}" title="Edit Sertifikat"><i class="fas fa-edit"></i></button>` : '';
          return `
            <tr>
              <td><span class="small text-muted">${dtInfo}</span></td>
              <td>${escapeHtml(s.nomor_sertifikat)}</td>
              <td>${escapeHtml(s.nama_pemilik)}</td>
              <td>${escapeHtml(s.nama_kelas || '-')}</td>
              <td>
                <div class="btn-group">
                  <a class="btn btn-sm btn-info" href="<?= base_url('admin/sertifikat') ?>/${s.id}/view" target="_blank" title="Lihat Sertifikat"><i class="fas fa-eye"></i></a>
                  ${editBtn}
                  <button class="btn btn-sm btn-outline-danger" data-act="del" data-id="${s.id}"><i class="fas fa-trash"></i></button>
                </div>
              </td>
            </tr>
          `;
        }).join('');
        certBody.innerHTML = html;
        certBody.querySelectorAll('[data-act="edit"]').forEach(function(btn){
          btn.addEventListener('click', function(){
            const id = this.getAttribute('data-id');
            // Cari data baris dari array rows untuk prefilling
            const item = rows.find(function(x){ return String(x.id) === String(id); }) || {};
            editCertId.value = id;
            editStatus.value = item.status || '';
            // tanggal_terbit format dari server: YYYY-MM-DD
            editTanggalTerbit.value = (item.tanggal_terbit || '').split(' ')[0];
            editNamaKelas.value = item.nama_kelas || '';
            showModal(modalEditEl);
          });
        });
        certBody.querySelectorAll('[data-act="del"]').forEach(function(btn){
          btn.addEventListener('click', async function(){
            if (!confirm('Hapus sertifikat ini?')) return;
            const id = this.getAttribute('data-id');
            const fd = new URLSearchParams(); fd.append(csrfToken, csrfHash);
            const res = await fetch(endpointDelete(id), { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
            const j = await res.json(); if (j.success) { loadSertifikat(); } else { alert(j.message || 'Gagal menghapus'); }
          });
        });

        btnConfirmEdit.addEventListener('click', async function(){
          const id = editCertId.value;
          const status = (editStatus.value || '').trim();
          const tgl = (editTanggalTerbit.value || '').trim();
          const nama = (editNamaKelas.value || '').trim();
          const fd = new URLSearchParams(); fd.append(csrfToken, csrfHash); fd.append('id', id);
          if (status) fd.append('status', status);
          if (tgl) fd.append('tanggal_terbit', tgl);
          if (nama) fd.append('nama_kelas', nama);
          try {
            const res = await fetch(endpointUpdate, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
            const j = await res.json();
            if (j.success) { hideModal(modalEditEl); loadSertifikat(); }
            else { alert(j.message || 'Gagal menyimpan'); }
          } catch(e){ alert('Gagal menyimpan'); }
        });

        // Search handlers
        if (certSearchBtn) {
          certSearchBtn.addEventListener('click', function(){ certPage = 1; loadSertifikat(); });
        }
        if (certSearch) {
          certSearch.addEventListener('keydown', function(e){ if (e.key === 'Enter') { e.preventDefault(); certPage = 1; loadSertifikat(); } });
        }
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
      if (formFilter) { loadRegistrasi(); }
      loadSertifikat();
    })();
  </script>
</section>

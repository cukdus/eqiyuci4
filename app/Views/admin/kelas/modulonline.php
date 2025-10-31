<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Modul Kelas Online</h5>
    </div>

    <div class="card card-outline card-primary mb-3">
      <div class="card-header"><h3 class="card-title">Filter & Tambah Modul</h3></div>
      <div class="card-body">
        <form id="formFilter" class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Pilih Kelas</label>
            <select id="kelasSelect" class="form-select">
              <option value="0">-- Semua Kelas --</option>
              <?php foreach (($classes ?? []) as $k): ?>
                <option value="<?= esc($k['id']) ?>"><?= esc($k['nama_kelas']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Pencarian</label>
            <input type="text" id="searchInput" class="form-control" placeholder="Cari judul/deskripsi modul">
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Terapkan</button>
          </div>
        </form>
        <hr>
        <form id="formCreate" class="row g-3">
          <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
          <div class="col-md-4">
            <label class="form-label">Kelas</label>
            <select name="kelas_id" class="form-select" id="createKelas">
              <?php foreach (($classes ?? []) as $k): ?>
                <option value="<?= esc($k['id']) ?>"><?= esc($k['nama_kelas']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Judul Modul</label>
            <input type="text" name="judul_modul" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" class="form-control" min="0" placeholder="opsional">
          </div>
          <div class="col-md-12">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="2"></textarea>
          </div>
          <div class="col-md-12 d-flex justify-content-end">
            <button class="btn btn-success" type="submit"><i class="fas fa-plus"></i> Tambah Modul</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card card-outline card-success">
      <div class="card-header"><h3 class="card-title">Daftar Modul</h3></div>
      <div class="card-body">
        <div id="moduleList"></div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      const endpointList = '<?= base_url('admin/modulonline.json') ?>';
      const endpointStore = '<?= base_url('admin/modulonline/store') ?>';
      const endpointUpdate = (id) => '<?= base_url('admin/modulonline') ?>/' + id + '/update';
      const endpointDelete = (id) => '<?= base_url('admin/modulonline') ?>/' + id + '/delete';
      const endpointFiles = '<?= base_url('admin/modulonline/files.json') ?>';
      const endpointUpload = (courseId) => '<?= base_url('admin/modulonline') ?>/' + courseId + '/file/upload';
      const endpointDeleteFile = (fileId) => '<?= base_url('admin/modulonline/file') ?>/' + fileId + '/delete';
      const endpointUpdateFile = (fileId) => '<?= base_url('admin/modulonline/file') ?>/' + fileId + '/update';
      const csrfToken = '<?= csrf_token() ?>';
      const csrfHash = '<?= csrf_hash() ?>';

      const moduleList = document.getElementById('moduleList');

      document.getElementById('formFilter').addEventListener('submit', function(e){
        e.preventDefault();
        loadModules();
      });

      document.getElementById('formCreate').addEventListener('submit', async function(e){
        e.preventDefault();
        const fd = new URLSearchParams();
        fd.append(csrfToken, csrfHash);
        const kelas = document.getElementById('createKelas').value;
        fd.append('kelas_id', kelas);
        fd.append('judul_modul', this.judul_modul.value.trim());
        fd.append('deskripsi', this.deskripsi.value.trim());
        if (this.urutan.value) fd.append('urutan', this.urutan.value);
        const res = await fetch(endpointStore, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
        const j = await res.json();
        if (j.success) {
          this.reset();
          loadModules();
        } else {
          alert(j.message || 'Gagal menambah modul');
        }
      });

      async function loadModules(){
        const kelasId = document.getElementById('kelasSelect').value || 0;
        const search = document.getElementById('searchInput').value.trim();
        const usp = new URLSearchParams({ kelas_id: kelasId, search });
        const res = await fetch(endpointList + '?' + usp.toString());
        const j = await res.json();
        renderModules(j.data || []);
      }

      function renderModules(rows){
        moduleList.innerHTML = '';
        if (!rows.length) {
          moduleList.innerHTML = '<div class="text-muted">Tidak ada modul</div>';
          return;
        }
        rows.forEach(row => {
          const card = document.createElement('div');
          card.className = 'border rounded p-3 mb-3';
          card.innerHTML = `
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <div class="fw-bold">${escapeHtml(row.judul_modul)}</div>
                <div class="small text-muted">Kelas: ${escapeHtml(row.nama_kelas || '')} • Urutan: ${row.urutan ?? '-'}</div>
                <div class="mt-1">${escapeHtml(row.deskripsi || '')}</div>
              </div>
              <div class="btn-group">
                <button class="btn btn-sm btn-warning" data-act="edit"><i class="bi bi-pencil-square"></i></button>
                <button class="btn btn-sm btn-danger" data-act="delete"><i class="bi bi-trash"></i></button>
              </div>
            </div>
            <div class="mt-2">
              <div class="fw-semibold mb-2">File & Link</div>
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead><tr><th>Jenis</th><th>Judul</th><th>Link/File</th><th>Urutan</th><th style="width:80px">Aksi</th></tr></thead>
                  <tbody></tbody>
                </table>
              </div>
              <form class="row g-2 mt-2" data-upload>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="col-md-2">
                  <select name="tipe" class="form-select form-select-sm" required>
                    <option value="youtube">YouTube</option>
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                  </select>
                </div>
                <div class="col-md-3"><input type="text" name="judul_file" class="form-control form-control-sm" placeholder="Judul file/link"></div>
                <div class="col-md-4" data-fileinput>
                  <input type="url" name="link_url" class="form-control form-control-sm" placeholder="Link YouTube">
                </div>
                <div class="col-md-2"><input type="number" name="urutan" min="0" class="form-control form-control-sm" placeholder="Urutan"></div>
                <div class="col-md-1 d-grid"><button class="btn btn-sm btn-success" type="submit"><i class="fas fa-upload"></i></button></div>
              </form>
            </div>
          `;

          // Fill files
          const tbody = card.querySelector('tbody');
          (row.files || []).forEach(f => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
              <td><span class="badge bg-secondary">${escapeHtml(f.tipe)}</span></td>
              <td>${escapeHtml(f.judul_file || '')}</td>
              <td>${renderLinkCell(f)}</td>
              <td>${f.urutan ?? '-'}</td>
              <td>
                <div class="btn-group btn-group-sm" role="group">
                  <button class="btn btn-primary" data-file-edit="${f.id}"><i class="bi bi-pencil-square"></i></button>
                  <button class="btn btn-danger" data-file-id="${f.id}"><i class="bi bi-trash"></i></button>
                </div>
              </td>
            `;
            const editBtn = tr.querySelector('[data-file-edit]');
            editBtn.addEventListener('click', () => {
              openEditFileModal(f);
            });
            const del = tr.querySelector('[data-file-id]');
            del.addEventListener('click', async () => {
              const fd = new URLSearchParams(); fd.append(csrfToken, csrfHash);
              const res = await fetch(endpointDeleteFile(f.id), { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
              const j = await res.json();
              if (j.success) loadModules(); else alert('Gagal menghapus file');
            });
            tbody.appendChild(tr);
          });

          // Toggle input type based on tipe selection
          const uploadForm = card.querySelector('[data-upload]');
          const tipeSelect = uploadForm.querySelector('select[name="tipe"]');
          const fileInputWrap = uploadForm.querySelector('[data-fileinput]');
          tipeSelect.addEventListener('change', () => {
            const v = tipeSelect.value;
            fileInputWrap.innerHTML = (v === 'youtube')
              ? '<input type="url" name="link_url" class="form-control form-control-sm" placeholder="Link YouTube">'
              : '<input type="file" name="file" class="form-control form-control-sm" accept="application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">';
          });

          // Edit/Delete actions for module
          const btnEdit = card.querySelector('[data-act="edit"]');
          const btnDelete = card.querySelector('[data-act="delete"]');
          btnEdit.addEventListener('click', () => openEditModal(row));
          btnDelete.addEventListener('click', async () => {
            if (!confirm('Hapus modul ini beserta semua file?')) return;
            const fd = new URLSearchParams(); fd.append(csrfToken, csrfHash);
            const res = await fetch(endpointDelete(row.id), { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
            const j = await res.json(); if (j.success) loadModules(); else alert('Gagal menghapus');
          });

          // Upload submit
          uploadForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const tipe = uploadForm.querySelector('select[name="tipe"]').value;
            const judul = uploadForm.querySelector('input[name="judul_file"]').value.trim();
            const urutan = uploadForm.querySelector('input[name="urutan"]').value;

            let body, headers;
            if (tipe === 'youtube') {
              body = new URLSearchParams();
              body.append(csrfToken, csrfHash);
              body.append('tipe', 'youtube');
              if (judul) body.append('judul_file', judul);
              const link = uploadForm.querySelector('input[name="link_url"]').value.trim();
              body.append('link_url', link);
              if (urutan) body.append('urutan', urutan);
              headers = { 'Content-Type': 'application/x-www-form-urlencoded' };
            } else {
              body = new FormData();
              body.append(csrfToken, csrfHash);
              body.append('tipe', tipe);
              if (judul) body.append('judul_file', judul);
              const fileInput = uploadForm.querySelector('input[name="file"]');
              if (!fileInput || !fileInput.files.length) { alert('Pilih file'); return; }
              body.append('file', fileInput.files[0]);
              if (urutan) body.append('urutan', urutan);
              headers = undefined;
            }
            const res = await fetch(endpointUpload(row.id), { method: 'POST', body, headers });
            const j = await res.json();
            if (j.success) { loadModules(); uploadForm.reset(); tipeSelect.dispatchEvent(new Event('change')); } else { alert(j.message || 'Gagal upload'); }
          });

          moduleList.appendChild(card);
        });
      }

      function escapeHtml(str){
        return String(str || '').replace(/[&<>\"]+/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;'}[s]||s));
      }
      function renderLinkCell(f){
        if (f.tipe === 'youtube') {
          const u = escapeHtml(f.file_url || '');
          return `<a href="${u}" target="_blank" rel="noopener">${u}</a>`;
        }
        const u = escapeHtml(f.file_url || '');
        return u ? `<a href="${u}" target="_blank" rel="noopener">Unduh</a>` : '-';
      }

      // Initial load
      loadModules();
    })();
  </script>
</section>
<!-- Edit Module Modal -->
<div class="modal fade" id="editModuleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Modul</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formEditModule">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Judul Modul</label>
              <input type="text" class="form-control" name="judul_modul" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Urutan</label>
              <input type="number" class="form-control" name="urutan" min="0" placeholder="opsional">
            </div>
            <div class="col-md-3">
              <label class="form-label">Kelas</label>
              <input type="text" class="form-control" name="nama_kelas" readonly>
            </div>
            <div class="col-md-12">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="deskripsi" rows="3"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSaveEdit">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
  <script>
    (function(){
      const modalEl = document.getElementById('editModuleModal');
      let editId = null;
      let modal;
      function ensureModal(){
        if (typeof bootstrap !== 'undefined' && !modal) {
          modal = new bootstrap.Modal(modalEl);
        }
      }
      window.openEditModal = function(row){
        ensureModal();
        editId = row.id;
        const form = document.getElementById('formEditModule');
        form.judul_modul.value = row.judul_modul || '';
        form.deskripsi.value = row.deskripsi || '';
        form.urutan.value = row.urutan ?? '';
        form.nama_kelas.value = row.nama_kelas || '';
        if (modal) modal.show();
      };

      document.getElementById('formEditModule').addEventListener('submit', async function(e){
        e.preventDefault();
        if (!editId) return;
        const btn = document.getElementById('btnSaveEdit');
        btn.disabled = true; btn.innerText = 'Menyimpan...';
        try {
          const fd = new URLSearchParams();
          fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
          fd.append('judul_modul', this.judul_modul.value.trim());
          fd.append('deskripsi', this.deskripsi.value.trim());
          if (this.urutan.value !== '') fd.append('urutan', this.urutan.value);
          const res = await fetch('<?= base_url('admin/modulonline') ?>/' + editId + '/update', {
            method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString()
          });
          const j = await res.json();
          if (j.success) {
            if (modal) modal.hide();
            // Reload module list
            const evt = new Event('modules-reload');
            window.dispatchEvent(evt);
          } else {
            alert(j.message || 'Gagal update modul');
          }
        } catch (err) {
          alert('Terjadi kesalahan jaringan');
        } finally {
          btn.disabled = false; btn.innerText = 'Simpan Perubahan';
        }
      });

      // Listen for reload request and call existing loadModules
      window.addEventListener('modules-reload', function(){
        if (typeof loadModules === 'function') {
          loadModules();
        } else {
          // Fallback: trigger filter submit
          const f = document.getElementById('formFilter');
          if (f) f.dispatchEvent(new Event('submit'));
        }
      });
    })();
  </script>
</div>

<!-- Edit File Modal -->
<div class="modal fade" id="editFileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit File/Link</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formEditFile">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Jenis</label>
            <select class="form-select" name="tipe" id="editFileType">
              <option value="youtube">YouTube</option>
              <option value="pdf">PDF</option>
              <option value="excel">Excel</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" class="form-control" name="judul_file" id="editFileTitle" required>
          </div>
          <div class="mb-3" id="editFileLinkGroup" style="display:none">
            <label class="form-label">Link</label>
            <input type="url" class="form-control" name="link_url" id="editFileLink" placeholder="https://...">
          </div>
          <div class="mb-3" id="editFileUploadGroup" style="display:none">
            <label class="form-label">Upload File (opsional)</label>
            <input type="file" class="form-control" name="file" id="editFileUpload" accept=".pdf,.xls,.xlsx">
            <div class="form-text">Kosongkan bila tidak mengganti file.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Urutan</label>
            <input type="number" class="form-control" name="urutan" id="editFileOrder" min="0">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSaveEditFile">Simpan</button>
        </div>
      </form>
    </div>
  </div>
  <script>
    (function(){
      const modalEl = document.getElementById('editFileModal');
      let modal, currentFile;
      function ensureModal(){ if (typeof bootstrap !== 'undefined' && !modal) modal = new bootstrap.Modal(modalEl); }
      function toggleInputs(){
        const t = document.getElementById('editFileType').value;
        document.getElementById('editFileLinkGroup').style.display = (t === 'youtube') ? '' : 'none';
        document.getElementById('editFileUploadGroup').style.display = (t === 'youtube') ? 'none' : '';
      }
      window.openEditFileModal = function(f){
        ensureModal();
        currentFile = f;
        document.getElementById('editFileType').value = f.tipe;
        document.getElementById('editFileTitle').value = f.judul_file || '';
        document.getElementById('editFileOrder').value = f.urutan ?? '';
        document.getElementById('editFileLink').value = (f.tipe === 'youtube') ? (f.file_url || '') : '';
        document.getElementById('editFileUpload').value = '';
        toggleInputs();
        if (modal) modal.show();
      };
      document.getElementById('editFileType').addEventListener('change', toggleInputs);
      document.getElementById('formEditFile').addEventListener('submit', async function(e){
        e.preventDefault();
        if (!currentFile) return;
        const btn = document.getElementById('btnSaveEditFile');
        btn.disabled = true; btn.innerText = 'Menyimpan...';
        try {
          const tipe = document.getElementById('editFileType').value;
          const judul = document.getElementById('editFileTitle').value.trim();
          const urutan = document.getElementById('editFileOrder').value;
          let body, headers;
          if (tipe === 'youtube') {
            body = new URLSearchParams();
            body.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            body.append('tipe', 'youtube');
            if (judul) body.append('judul_file', judul);
            const link = document.getElementById('editFileLink').value.trim();
            body.append('link_url', link);
            if (urutan) body.append('urutan', urutan);
            headers = { 'Content-Type': 'application/x-www-form-urlencoded' };
          } else {
            body = new FormData();
            body.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            body.append('tipe', tipe);
            if (judul) body.append('judul_file', judul);
            const fileInput = document.getElementById('editFileUpload');
            if (fileInput.files.length > 0) {
              body.append('file', fileInput.files[0]);
            }
            if (urutan) body.append('urutan', urutan);
            headers = undefined;
          }
          const res = await fetch('<?= base_url('admin/modulonline/file') ?>/' + currentFile.id + '/update', { method: 'POST', body, headers });
          const j = await res.json();
          if (j.success) {
            if (modal) modal.hide();
            const evt = new Event('modules-reload');
            window.dispatchEvent(evt);
          } else {
            alert(j.message || 'Gagal menyimpan perubahan');
          }
        } catch (err) {
          alert('Terjadi kesalahan jaringan');
        } finally {
          btn.disabled = false; btn.innerText = 'Simpan';
        }
      });
    })();
  </script>
</div>
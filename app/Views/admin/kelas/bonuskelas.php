<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Bonus Kelas</h5>
    </div>

      <div class="card card-outline card-primary mb-3">
        <div class="card-header"><h3 class="card-title">Filter & Upload Bonus</h3></div>
        <div class="card-body">
        <form id="formFilter" class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Pilih Kelas</label>
            <select id="filterKelas" class="form-select">
              <option value="0">-- Pilih Kelas --</option>
              <?php foreach (($classes ?? []) as $c): ?>
                <option value="<?= (int) $c['id'] ?>">[<?= esc($c['kode_kelas']) ?>] <?= esc($c['nama_kelas']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Terapkan</button>
          </div>
        </form>
        <hr>
        <form id="formUploadBonus" class="row g-3">
          <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
          <div class="col-md-4">
            <label class="form-label">Judul File</label>
            <input type="text" name="judul_file" class="form-control" placeholder="opsional">
          </div>
          <div class="col-md-3">
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" class="form-control" min="0" placeholder="opsional">
          </div>
          <div class="col-md-5">
            <label class="form-label">File (PDF/XLS/XLSX)</label>
            <input type="file" name="file" class="form-control" accept=".pdf,.xls,.xlsx">
          </div>
          <div class="col-md-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Upload</button>
          </div>
        </form>

        <hr>
        <div class="mt-3">
          <h6 class="mb-2">Gunakan File yang Sudah Ada</h6>
          <form id="formAttachExisting" class="row g-3">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="col-md-8">
              <label class="form-label">Pilih File dari Kelas Lain</label>
              <select id="existingFileSelect" class="form-select">
                <option value="0">-- Pilih File --</option>
              </select>
              <div class="form-text">File fisik tidak akan digandakan, hanya direlasikan ke kelas ini.</div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Judul (opsional, override)</label>
              <input type="text" name="judul_file" class="form-control" placeholder="biarkan kosong untuk pakai judul asli">
            </div>
            <div class="col-md-3">
              <label class="form-label">Urutan (opsional)</label>
              <input type="number" name="urutan" class="form-control" min="0" placeholder="opsional">
            </div>
            <div class="col-md-12 d-flex justify-content-end">
              <button type="submit" class="btn btn-primary"><i class="fas fa-link"></i> Attach</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="card card-outline card-success">
      <div class="card-header"><h3 class="card-title">Daftar File Bonus</h3></div>
      <div class="card-body">
        <div id="bonusList"></div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      const base = '<?= base_url('admin') ?>';
      const csrfToken = '<?= csrf_token() ?>';
      const csrfHash = '<?= csrf_hash() ?>';

      const elFilter = document.getElementById('filterKelas');
      const elList = document.getElementById('bonusList');
      const formUpload = document.getElementById('formUploadBonus');
      const formAttach = document.getElementById('formAttachExisting');
      const elExistingSelect = document.getElementById('existingFileSelect');

      function endpointList(kelasId){
        const p = new URLSearchParams(); p.append('kelas_id', kelasId);
        return base + '/bonuskelas.json?' + p.toString();
      }
      function endpointUpload(kelasId){
        return base + '/bonuskelas/' + kelasId + '/file/upload';
      }
      function endpointDelete(fileId){
        return base + '/bonuskelas/file/' + fileId + '/delete';
      }
      function endpointListAll(){
        return base + '/bonuskelas/files.json';
      }
      function endpointAttach(kelasId){
        return base + '/bonuskelas/' + kelasId + '/file/attach';
      }

      async function loadBonus(){
        const id = parseInt(elFilter.value || '0', 10);
        elList.innerHTML = '<div class="text-muted">Memuat data...</div>';
        if (!id){ elList.innerHTML = '<div class="text-muted">Pilih kelas terlebih dahulu</div>'; return; }
        const res = await fetch(endpointList(id));
        const j = await res.json();
        const data = Array.isArray(j.data) ? j.data : [];
        if (data.length === 0){
          elList.innerHTML = '<div class="text-muted">Belum ada file bonus</div>';
          return;
        }
        const container = document.createElement('div');
        data.forEach(function(row){
          const card = document.createElement('div');
          card.className = 'border rounded p-3 mb-2';
          card.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="fw-bold">${escapeHtml(row.judul_file || (row.tipe === 'pdf' ? 'PDF' : 'Excel'))}</div>
                <div class="small text-muted">Tipe: ${escapeHtml(row.tipe)} • Urutan: ${row.urutan ?? '-'}</div>
                <div class="mt-1">${renderLinkCell(row)}</div>
              </div>
              <div class="btn-group">
                <button class="btn btn-sm btn-danger" data-id="${row.id}" data-act="delete"><i class="bi bi-trash"></i></button>
              </div>
            </div>
          `;
          const btnDel = card.querySelector('[data-act="delete"]');
          btnDel.addEventListener('click', async function(){
            if (!confirm('Hapus file ini?')) return;
            const fileId = this.getAttribute('data-id');
            const fd = new URLSearchParams(); fd.append(csrfToken, csrfHash);
            const res = await fetch(endpointDelete(fileId), { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
            const j = await res.json(); if (j.success) loadBonus(); else alert('Gagal menghapus');
          });
          container.appendChild(card);
        });
        elList.innerHTML = '';
        elList.appendChild(container);
      }

      formUpload.addEventListener('submit', async function(e){
        e.preventDefault();
        const kelasId = parseInt(elFilter.value || '0', 10);
        if (!kelasId){ alert('Pilih kelas terlebih dahulu'); return; }
        const btn = formUpload.querySelector('button[type="submit"]');
        btn.disabled = true; btn.innerText = 'Mengunggah...';
        try {
          const fd = new FormData(formUpload);
          fd.set('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
          const res = await fetch(endpointUpload(kelasId), { method: 'POST', body: fd });
          const j = await res.json();
          if (j.success){
            formUpload.reset();
            loadBonus();
          } else {
            alert(j.message || 'Gagal mengunggah file');
          }
        } catch (err) {
          alert('Terjadi kesalahan jaringan');
        } finally {
          btn.disabled = false; btn.innerText = 'Upload';
        }
      });

      async function loadAllExistingFiles(){
        try {
          const res = await fetch(endpointListAll());
          const j = await res.json();
          const data = Array.isArray(j.data) ? j.data : [];
          const opts = ['<option value="0">-- Pilih File --</option>'];
          data.forEach(function(row){
            const label = `[${escapeHtml(row.kode_kelas || '')}] ${escapeHtml(row.nama_kelas || '-')}: ${escapeHtml(row.judul_file || (row.tipe === 'pdf' ? 'PDF' : 'Excel'))}`;
            opts.push(`<option value="${row.id}">${label}</option>`);
          });
          elExistingSelect.innerHTML = opts.join('');
        } catch (err) {
          elExistingSelect.innerHTML = '<option value="0">Gagal memuat daftar file</option>';
        }
      }

      formAttach.addEventListener('submit', async function(e){
        e.preventDefault();
        const kelasId = parseInt(elFilter.value || '0', 10);
        if (!kelasId){ alert('Pilih kelas terlebih dahulu'); return; }
        const sourceId = parseInt(elExistingSelect.value || '0', 10);
        if (!sourceId){ alert('Pilih file yang akan di-attach'); return; }
        const btn = formAttach.querySelector('button[type="submit"]');
        btn.disabled = true; btn.innerText = 'Memproses...';
        try {
          const fd = new URLSearchParams();
          fd.append(csrfToken, csrfHash);
          fd.append('source_file_id', String(sourceId));
          const judul = (formAttach.querySelector('input[name="judul_file"]')?.value || '').trim();
          const urutan = (formAttach.querySelector('input[name="urutan"]')?.value || '').trim();
          if (judul !== '') fd.append('judul_file', judul);
          if (urutan !== '') fd.append('urutan', urutan);
          const res = await fetch(endpointAttach(kelasId), { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: fd.toString() });
          const j = await res.json();
          if (j.success){
            formAttach.reset();
            elExistingSelect.value = '0';
            loadBonus();
          } else {
            alert(j.message || 'Gagal attach file');
          }
        } catch (err) {
          alert('Terjadi kesalahan jaringan');
        } finally {
          btn.disabled = false; btn.innerText = 'Attach';
        }
      });

      elFilter.addEventListener('change', loadBonus);
      window.addEventListener('DOMContentLoaded', function(){
        loadAllExistingFiles();
        if (parseInt(elFilter.value || '0', 10) > 0) loadBonus();
      });

      function escapeHtml(str){
        str = String(str == null ? '' : str);
        return str.replace(/[&<>"']/g, function(m){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'})[m]; });
      }
      function renderLinkCell(f){
        const u = escapeHtml(f.file_url || '');
        return u ? `<a href="${u}" target="_blank" rel="noopener">Unduh</a>` : '-';
      }
    })();
  </script>
</section>
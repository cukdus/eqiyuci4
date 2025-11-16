<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Konfigurasi Waha</h5>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card card-success card-outline">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">WAHA Message Templates</h3>
            <button class="btn btn-sm btn-primary ms-auto" id="btnAddTemplate"><i class="bi bi-plus-lg"></i> Tambah Template</button>
          </div>
          <div class="card-body">
            <div class="row g-4">
              <div class="col-md-7">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Template Pesan</strong> 
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-striped mb-0" id="tblTemplates">
                        <thead>
                          <tr>
                            <th>Key</th>
                            <th>Nama</th>
                            <th>Aktif</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="card mt-4">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Log Pengiriman</strong>
                    <div class="d-flex ms-auto">
                      <button class="btn btn-sm btn-outline-secondary" id="btnRefreshLogs">Refresh</button>
                      <button class="btn btn-sm btn-outline-danger ms-2" id="btnClearLogs">Hapus Log</button>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-bordered mb-0" id="tblLogs">
                        <thead>
                          <tr>
                            <th>Waktu</th>
                            <th>Scenario</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Message</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 border-top" id="logsPager" style="display:flex;">
                      <button class="btn btn-sm btn-outline-secondary" id="prevLogsBtn">Prev</button>
                      <div class="small text-muted" id="logsPageInfo">Page</div>
                      <button class="btn btn-sm btn-outline-secondary" id="nextLogsBtn">Next</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-5">
                <div class="card mb-4">
                  <div class="card-header"><strong>Preview Template</strong></div>
                  <div class="card-body">
                    <div class="mb-2">
                      <label class="form-label">Pilih Template</label>
                      <select class="form-select" id="previewTemplateSelect"></select>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Hasil Render</label>
                      <textarea class="form-control" id="previewResult" rows="6" readonly></textarea>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" id="btnPreview">Render</button>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header"><strong>Test Kirim Pesan</strong></div>
                  <div class="card-body">
                    <div class="mb-2">
                      <label class="form-label">Pilih Penerima (data nyata)</label>
                      <select class="form-select" id="testRecipientSelect"></select>
                      <div class="form-text">Daftar terbatas 20 registrasi terbaru.</div>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Nomor Tujuan</label>
                      <input class="form-control" id="testPhone" placeholder="62812xxxxxxx" />
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Pilih Template</label>
                      <select class="form-select" id="testTemplateSelect"></select>
                    </div>
                    <button class="btn btn-sm btn-success" id="btnTestSend">Kirim Test</button>
                    <span class="ms-2" id="testStatus"></span>
                  </div>
                </div>

                <div class="card mt-4">
                  <div class="card-header"><strong>Queue Tools</strong></div>
                  <div class="card-body">
                    <button class="btn btn-sm btn-outline-warning" id="btnProcessQueue">Proses Queue</button>
                    <button class="btn btn-sm btn-outline-info ms-2" id="btnRunReminders">Queue Reminder H-3</button>
                    <span class="ms-2" id="queueStatus"></span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Template Form -->
            <div class="modal" tabindex="-1" id="templateModal">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="templateModalTitle">Tambah Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Key</label>
                        <select class="form-select" id="tplKeySelect"></select>
                        <div class="form-text">Pilih key yang tersedia. Key yang sudah dipakai disembunyikan.</div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input class="form-control" id="tplName" />
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">Template</label>
                        <textarea class="form-control" rows="6" id="tplContent" placeholder="Gunakan {{nama}}, {{nama_kelas}}, {{jadwal}}, {{kota}}, {{kabupaten}}, {{provinsi}}, {{no_tlp}}, {{email}}, {{status_pembayaran}}, {{jumlah_tagihan}}, {{jumlah_dibayar}}, {{no_sertifikat}}"></textarea>
                        <div class="form-text" id="tplHint"></div>
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="2" id="tplDesc"></textarea>
                      </div>
                      <div class="col-md-12">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="tplEnabled" checked />
                          <label class="form-check-label" for="tplEnabled">Aktif</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="btnSaveTemplate">Simpan</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Konfirmasi Test Kirim -->
            <div class="modal" tabindex="-1" id="confirmSendModal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pengiriman Test</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-2"><strong>Penerima:</strong> <span id="confirmRecipient"></span></div>
                    <div class="mb-2"><strong>Nomor:</strong> <span id="confirmPhone"></span></div>
                    <div class="mb-2">
                      <label class="form-label">Isi Pesan</label>
                      <textarea class="form-control" id="confirmMessage" rows="6" readonly></textarea>
                    </div>
                    <div class="alert alert-warning mb-0">Ini adalah pengujian. Tidak mempengaruhi data produksi.</div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success" id="btnConfirmSendNow">Kirim Sekarang</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid -->
</section>

<script>
const API = {
  list: '<?= site_url('admin/setting/waha/templates-json') ?>',
  logs: '<?= site_url('admin/setting/waha/logs-json') ?>',
  clearLogs: '<?= site_url('admin/setting/waha/logs/clear') ?>',
  add: '<?= site_url('admin/setting/waha/template/store') ?>',
  update: (id) => '<?= site_url('admin/setting/waha/template') ?>/' + id + '/update',
  del: (id) => '<?= site_url('admin/setting/waha/template') ?>/' + id + '/delete',
  preview: '<?= site_url('admin/setting/waha/preview') ?>',
  test: '<?= site_url('admin/setting/waha/test-send') ?>',
  recipients: '<?= site_url('admin/setting/waha/test-recipients-json') ?>',
  processQueue: '<?= site_url('admin/setting/waha/process-queue') ?>',
  runReminders: '<?= site_url('admin/setting/waha/run-reminders') ?>',
};

let templates = [];
let editingId = null;

async function fetchTemplates() {
  const res = await fetch(API.list);
  const json = await res.json();
  templates = json.data || [];
  renderTemplates();
  fillTemplateSelects();
}

function renderTemplates() {
  const tbody = document.querySelector('#tblTemplates tbody');
  tbody.innerHTML = '';
  templates.forEach(t => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${t.key}</td>
      <td>${t.name}</td>
      <td>${t.enabled == 1 ? 'Ya' : 'Tidak'}</td>
      <td>
        <div class="btn-group" role="group">
          <button class=\"btn btn-sm btn-warning rounded-0 rounded-start\" onclick=\"onEdit(${t.id})\"><i class=\"bi bi-pencil-square\"></i></button>
          <button class=\"btn btn-sm btn-danger rounded-0 rounded-end\" onclick=\"onDelete(${t.id})\"><i class=\"bi bi-trash\"></i></button>
        </div>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

function fillTemplateSelects() {
  const selects = [document.getElementById('previewTemplateSelect'), document.getElementById('testTemplateSelect')];
  selects.forEach(sel => {
    sel.innerHTML = '';
    templates.forEach(t => {
      const opt = document.createElement('option');
      opt.value = t.key;
      opt.textContent = `${t.name} (${t.key})`;
      sel.appendChild(opt);
    });
  });
}

async function fetchRecipients() {
  const sel = document.getElementById('testRecipientSelect');
  sel.innerHTML = '';
  try {
    const res = await fetch(API.recipients);
    const j = await res.json();
    const rows = j.data || [];
    // Tambahkan pilihan kosong
    const optEmpty = document.createElement('option');
    optEmpty.value = '';
    optEmpty.textContent = '-- pilih registrasi --';
    sel.appendChild(optEmpty);
    rows.forEach(r => {
      const opt = document.createElement('option');
      opt.value = r.id;
      const label = `${r.nama} (${r.nama_kelas || 'Tanpa kelas'}) - ${r.no_telp || ''}`;
      opt.textContent = label;
      opt.dataset.phone = r.no_telp || '';
      sel.appendChild(opt);
    });
    sel.addEventListener('change', () => {
      const v = sel.value;
      const optSel = sel.options[sel.selectedIndex];
      const p = optSel?.dataset?.phone || '';
      document.getElementById('testPhone').value = p;
    });
  } catch (e) {
    // silently ignore; UI still allows manual phone
  }
}

function openConfirmModal(data, onConfirm) {
  document.getElementById('confirmRecipient').textContent = data.nameLabel || '';
  document.getElementById('confirmPhone').textContent = data.phone || '';
  document.getElementById('confirmMessage').value = data.message || '';
  const el = document.getElementById('confirmSendModal');
  const modal = new bootstrap.Modal(el);
  modal.show();
  const btn = document.getElementById('btnConfirmSendNow');
  const handler = async () => {
    btn.removeEventListener('click', handler);
    modal.hide();
    if (typeof onConfirm === 'function') {
      await onConfirm();
    }
  };
  btn.addEventListener('click', handler);
}

function openTemplateModal(title) {
  document.getElementById('templateModalTitle').textContent = title;
  const el = document.getElementById('templateModal');
  const modal = new bootstrap.Modal(el);
  modal.show();
}

function fillKeyDropdownAvailable() {
  const usedKeys = new Set(templates.map(t => t.key));
  const available = PREDEFINED_KEYS.filter(k => !usedKeys.has(k));
  const sel = document.getElementById('tplKeySelect');
  sel.innerHTML = '';
  if (available.length === 0) {
    const opt = document.createElement('option');
    opt.value = '';
    opt.textContent = 'Semua key telah digunakan';
    sel.appendChild(opt);
    sel.disabled = true;
  } else {
    available.forEach(k => {
      const opt = document.createElement('option');
      opt.value = k;
      opt.textContent = k;
      sel.appendChild(opt);
    });
    sel.disabled = false;
  }
}

document.getElementById('btnAddTemplate').addEventListener('click', () => {
  editingId = null;
  fillKeyDropdownAvailable();
  document.getElementById('tplName').value = '';
  document.getElementById('tplContent').value = '';
  document.getElementById('tplDesc').value = '';
  document.getElementById('tplEnabled').checked = true;
  openTemplateModal('Tambah Template');
  setTimeout(updateTplHint, 50);
});

window.onEdit = (id) => {
  const t = templates.find(x => x.id == id);
  if (!t) return;
  editingId = id;
  const sel = document.getElementById('tplKeySelect');
  sel.innerHTML = '';
  const opt = document.createElement('option');
  opt.value = t.key;
  opt.textContent = t.key;
  sel.appendChild(opt);
  sel.disabled = true;
  document.getElementById('tplName').value = t.name;
  document.getElementById('tplContent').value = t.template;
  document.getElementById('tplDesc').value = t.description || '';
  document.getElementById('tplEnabled').checked = t.enabled == 1;
  openTemplateModal('Edit Template');
  setTimeout(updateTplHint, 50);
};

window.onDelete = async (id) => {
  if (!confirm('Hapus template ini?')) return;
  const res = await fetch(API.del(id), { method: 'POST' });
  const j = await res.json();
  if (j.success) { fetchTemplates(); } else { alert('Gagal menghapus'); }
};

document.getElementById('btnSaveTemplate').addEventListener('click', async () => {
  const payload = {
    key: document.getElementById('tplKeySelect').value,
    name: document.getElementById('tplName').value,
    template: document.getElementById('tplContent').value,
    description: document.getElementById('tplDesc').value,
    enabled: document.getElementById('tplEnabled').checked ? 1 : 0,
  };
  let res;
  if (editingId) {
    res = await fetch(API.update(editingId), { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams(payload) });
  } else {
    res = await fetch(API.add, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams(payload) });
  }
  const j = await res.json();
  if (j.success) {
    bootstrap.Modal.getInstance(document.getElementById('templateModal')).hide();
    fetchTemplates();
  } else {
    alert('Gagal menyimpan template');
  }
});

document.getElementById('btnPreview').addEventListener('click', async () => {
  const key = document.getElementById('previewTemplateSelect').value;
  if (!key) return;
  const res = await fetch(API.preview, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams({ key }) });
  const j = await res.json();
  if (j.success) {
    document.getElementById('previewResult').value = j.rendered || '';
  } else {
    document.getElementById('previewResult').value = 'Gagal render: ' + (j.message || '');
  }
});

// Test send with confirmation using real data
document.getElementById('btnTestSend').addEventListener('click', async () => {
  const key = document.getElementById('testTemplateSelect').value;
  const sel = document.getElementById('testRecipientSelect');
  const rid = sel.value;
  const phoneInput = document.getElementById('testPhone').value;
  if (!key) return alert('Pilih template terlebih dahulu');
  if (rid) {
    // Render preview with selected registrasi for confirmation
    const resPrev = await fetch(API.preview, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams({ key, registrasi_id: rid }) });
    const jPrev = await resPrev.json();
    if (!jPrev.success) return alert('Gagal menyiapkan preview: ' + (jPrev.message || ''));
    const recipientText = sel.options[sel.selectedIndex]?.textContent || '';
    openConfirmModal({
      nameLabel: recipientText,
      phone: document.getElementById('testPhone').value,
      message: jPrev.rendered || ''
    }, async () => {
      document.getElementById('testStatus').textContent = 'Mengirim...';
      const res = await fetch(API.test, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams({ key, registrasi_id: rid, phone: phoneInput }) });
      const j = await res.json();
      document.getElementById('testStatus').textContent = j.success ? 'Terkirim' : ('Gagal: ' + (j.message || ''));
      fetchLogs();
    });
  } else {
    // Fallback: manual phone + template content
    const t = templates.find(x => x.key === key);
    if (!t || !phoneInput) return alert('Isi nomor tujuan dan pilih template');
    openConfirmModal({
      nameLabel: 'Manual: ' + (phoneInput || ''),
      phone: phoneInput,
      message: t.template
    }, async () => {
      document.getElementById('testStatus').textContent = 'Mengirim...';
      const res = await fetch(API.test, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams({ phone: phoneInput, template: t.template }) });
      const j = await res.json();
      document.getElementById('testStatus').textContent = j.success ? 'Terkirim' : ('Gagal: ' + (j.message || ''));
      fetchLogs();
    });
  }
});

document.getElementById('btnProcessQueue').addEventListener('click', async () => {
  document.getElementById('queueStatus').textContent = 'Memproses...';
  const res = await fetch(API.processQueue, { method: 'POST' });
  const j = await res.json();
  document.getElementById('queueStatus').textContent = 'Diproses: ' + (j.processed || 0);
});

document.getElementById('btnRunReminders').addEventListener('click', async () => {
  document.getElementById('queueStatus').textContent = 'Menyiapkan reminder...';
  const res = await fetch(API.runReminders);
  const j = await res.json();
  document.getElementById('queueStatus').textContent = 'Diantrikan: ' + (j.queued || 0);
});

let LOGS_STATE = { page: 1, per_page: 5 };

function updateLogsPager(meta) {
  const prevBtn = document.getElementById('prevLogsBtn');
  const nextBtn = document.getElementById('nextLogsBtn');
  const pageInfo = document.getElementById('logsPageInfo');
  if (!meta) return;
  prevBtn.disabled = !meta.has_prev;
  nextBtn.disabled = !meta.has_next;
  pageInfo.textContent = 'Halaman ' + meta.page + ' dari ' + (meta.total_pages || 1) + ' • Total ' + (meta.total || 0);
}

async function fetchLogs() {
  const params = new URLSearchParams({ page: String(LOGS_STATE.page), per_page: String(LOGS_STATE.per_page) });
  const res = await fetch(API.logs + '?' + params.toString());
  const j = await res.json();
  const tbody = document.querySelector('#tblLogs tbody');
  tbody.innerHTML = '';
  (j.data || []).forEach(l => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${l.created_at || ''}</td>
      <td>${l.scenario || ''}</td>
      <td>${l.phone || ''}</td>
      <td>${l.status || ''}${l.error ? (' - ' + l.error) : ''}</td>
      <td style=\"max-width:360px\">${(l.message || '').substring(0,360)}</td>
    `;
    tbody.appendChild(tr);
  });
  updateLogsPager(j.meta || null);
}

document.getElementById('btnRefreshLogs').addEventListener('click', fetchLogs);
document.getElementById('prevLogsBtn').addEventListener('click', () => { if (LOGS_STATE.page > 1) { LOGS_STATE.page -= 1; fetchLogs(); } });
document.getElementById('nextLogsBtn').addEventListener('click', () => { LOGS_STATE.page += 1; fetchLogs(); });
document.getElementById('btnClearLogs').addEventListener('click', async () => {
  if (!confirm('Hapus semua log pengiriman?')) return;
  const res = await fetch(API.clearLogs, { method: 'POST' });
  const j = await res.json();
  if (j.success) {
    fetchLogs();
  } else {
    alert('Gagal menghapus log: ' + (j.message || ''));
  }
});

// init
fetchTemplates();
fetchLogs();
fetchRecipients();

// ====== Scenario-specific placeholder hint ======
// ====== Predefined keys & hints ======
// Daftar key bawaan aplikasi
const PREDEFINED_KEYS = [
  'registrasi_peserta',
  'registrasi_admin',
  'tagihan_dp50_peserta',
  'tagihan_dp50_admin',
  'tagihan_lunas_peserta',
  'lulus_peserta',
  'akses_kelas',
];

const scenarioHints = {
  registration: 'Placeholder: {{nama}}, {{nama_kelas}}, {{jadwal}}, {{kota}}, {{kabupaten}}, {{provinsi}}, {{no_tlp}}, {{email}}, {{status_pembayaran}}, {{jumlah_tagihan}}, {{jumlah_dibayar}}',
  online_access: 'Placeholder: {{nama}}, {{nama_kelas}}',
  graduation: 'Placeholder: {{nama}}, {{nama_kelas}}, {{no_sertifikat}}',
  generic: 'Placeholder umum: {{nama}}, {{nama_kelas}}, {{jadwal}}, {{kota}}, {{kabupaten}}, {{provinsi}}, {{no_tlp}}, {{email}}, {{status_pembayaran}}, {{jumlah_tagihan}}, {{jumlah_dibayar}}, {{no_sertifikat}}',
};

function detectScenarioFromKey(key) {
  const k = (key || '').toLowerCase();
  if (k.includes('graduation') || k.includes('lulus') || k.includes('sertifikat')) return 'graduation';
  if (k.includes('online') || k.includes('access') || k.includes('akses')) return 'online_access';
  if (k.includes('registration') || k.includes('registrasi')) return 'registration';
  return 'generic';
}

function updateTplHint() {
  const key = document.getElementById('tplKeySelect').value;
  const scenario = detectScenarioFromKey(key);
  document.getElementById('tplHint').textContent = scenarioHints[scenario] || scenarioHints.generic;
}

// Hook up events
document.getElementById('tplKeySelect').addEventListener('change', updateTplHint);
document.getElementById('tplContent').addEventListener('input', () => {}); // reserved; hint depends on key

// Ensure hint shows when opening modal handled in add/edit handlers above
</script>
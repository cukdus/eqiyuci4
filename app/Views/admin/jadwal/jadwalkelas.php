<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Data Jadwal Kelas</h5>
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
    <?php if (session()->has('errors')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
          <?php foreach (session('errors') as $err): ?>
            <li><?= esc($err) ?></li>
          <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    <?php if (session()->has('alert')):
      $alert = session('alert'); ?>
      <div class="alert alert-<?= esc($alert['type'] ?? 'info') ?> alert-dismissible fade show" role="alert">
        <?= esc($alert['message'] ?? '') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="card card-outline card-success">
      <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
              <h3 class="card-title">Daftar Jadwal Kelas</h3>
              <div class="d-flex">
                  <button id="btnTambahJadwal" class="btn btn-sm btn-primary me-2 d-inline-flex align-items-center justify-content-center" type="button">
                      <i class="fas fa-plus me-1"></i>
                      <span class="text-center">Tambah Jadwal</span>
                  </button>
              </div>
          </div>
      </div>
      <div class="card-body table-responsive p-1">
        <table class="table table-bordered table-hover text-nowrap">
          <thead>
            <tr>
              <th style="width:50px;">No</th>
              <th>Nama Kelas</th>
              <th style="width:120px;">Tanggal Mulai</th>
              <th style="width:120px;">Tanggal Selesai</th>
              <th style="width:80px;">Lokasi</th>
              <th style="width:180px;">Trainer</th>
              <th style="width:80px;">Kapasitas</th>
              <th style="width:80px;">Durasi</th>
              <th style="width:50px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php $no = $start + 1; ?>
          <?php foreach (($jadwal ?? []) as $row): ?>
            <tr data-kelas-id="<?= esc($row['kelas_id']) ?>"
                data-tanggal-mulai="<?= esc($row['tanggal_mulai']) ?>"
                data-tanggal-selesai="<?= esc($row['tanggal_selesai']) ?>"
                data-lokasi="<?= esc($row['lokasi']) ?>"
                data-instruktur="<?= esc($row['instruktur']) ?>"
                data-kapasitas="<?= esc($row['kapasitas']) ?>">
              <td><?= $no++ ?>.</td>
              <td><?= esc($row['nama_kelas']) ?></td>
              <td><?= date('d/m/Y', strtotime((string) $row['tanggal_mulai'])) ?></td>
              <td><?= date('d/m/Y', strtotime((string) $row['tanggal_selesai'])) ?></td>
              <td><?= esc($row['lokasi']) ?></td>
              <td><?= esc($row['instruktur']) ?></td>
              <td><?= (int) $row['kapasitas'] ?> orang</td>
              <td><?= esc($row['durasi']) ?></td>
              <td>
                <div class="btn-group">
                  <button class="btn btn-sm btn-warning btn-edit" data-id="<?= esc($row['id']) ?>">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button class="btn btn-danger btn-sm" onclick="deleteJadwal(<?= esc($row['id']) ?>, '<?= esc($row['nama_kelas']) ?>')">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($jadwal)): ?>
            <tr>
              <td colspan="10" class="text-center text-muted">Data tidak ditemukan.</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-end">
          <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= ($page - 1) ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?>">«</a>
            </li>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($page < $totalPages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= ($page + 1) ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?>">»</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Jadwal -->
  <div class="modal fade" id="modalTambahJadwal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Jadwal Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="<?= base_url('admin/jadwal/store') ?>">
          <?= csrf_field() ?>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Kelas</label>
              <select class="form-select" name="kelas_id" id="create_kelas_id" required>
                <?php foreach (($kelasOptions ?? []) as $kelas): ?>
                  <option value="<?= esc($kelas['id']) ?>" data-kota="<?= esc($kelas['kota_tersedia'] ?? '') ?>" data-kategori="<?= esc($kelas['kategori'] ?? '') ?>"><?= esc($kelas['nama_kelas']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal Mulai</label>
              <input type="date" class="form-control" name="tanggal_mulai" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal Selesai</label>
              <input type="date" class="form-control" name="tanggal_selesai" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Lokasi</label>
              <select class="form-select" name="lokasi" id="create_lokasi" required>
                <option value="">-- Pilih Lokasi --</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Trainer</label>
              <select class="form-select" name="instruktur" required>
                <option value="Fariz Chamim Udien">Fariz Chamim Udien</option>
                <option value="Tomi Nugroho">Tomi Nugroho</option>
                <option value="Radit Lesmana">Radit Lesmana</option>
                <option value="Ulum Novianti">Ulum Novianti</option>
                <option value="Muhammad Zakaria">Muhammad Zakaria</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Kapasitas</label>
              <input type="number" class="form-control" name="kapasitas" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Edit Jadwal -->
  <div class="modal fade" id="modalEditJadwal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Jadwal Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="<?= base_url('admin/jadwal/update') ?>">
          <?= csrf_field() ?>
          <div class="modal-body">
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-3">
              <label class="form-label">Kelas</label>
              <select class="form-select" name="kelas_id" id="edit_kelas_id" required>
                <?php foreach (($kelasOptions ?? []) as $kelas): ?>
                  <option value="<?= esc($kelas['id']) ?>" data-kota="<?= esc($kelas['kota_tersedia'] ?? '') ?>" data-kategori="<?= esc($kelas['kategori'] ?? '') ?>"><?= esc($kelas['nama_kelas']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal Mulai</label>
              <input type="date" class="form-control" name="tanggal_mulai" id="edit_tanggal_mulai" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal Selesai</label>
              <input type="date" class="form-control" name="tanggal_selesai" id="edit_tanggal_selesai" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Lokasi</label>
              <select class="form-select" name="lokasi" id="edit_lokasi" required>
                <option value="">-- Pilih Lokasi --</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Trainer</label>
              <select class="form-select" name="instruktur" id="edit_instruktur" required>
                <option value="Fariz Chamim Udien">Fariz Chamim Udien</option>
                <option value="Tomi Nugroho">Tomi Nugroho</option>
                <option value="Radit Lesmana">Radit Lesmana</option>
                <option value="Ulum Novianti">Ulum Novianti</option>
                <option value="Muhammad Zakaria">Muhammad Zakaria</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Kapasitas</label>
              <input type="number" class="form-control" name="kapasitas" id="edit_kapasitas" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script>
  // Peta kota pusat: kode -> nama
  const ALL_CITIES = <?php
$map = [];
foreach (($kotaOptions ?? []) as $ko) {
  $code = strtolower((string) ($ko['kode'] ?? ''));
  $name = (string) ($ko['nama'] ?? $code);
  if ($code !== '') {
    $map[$code] = $name;
  }
}
echo json_encode($map, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
?>;

  function populateLokasi(selectKelasEl, selectLokasiEl, presetValue) {
    if (!selectKelasEl || !selectLokasiEl) return;
    const opt = selectKelasEl.options[selectKelasEl.selectedIndex];
    const raw = opt ? (opt.getAttribute('data-kota') || '') : '';
    const kategori = opt ? (opt.getAttribute('data-kategori') || '').toLowerCase() : '';
    const codes = raw.split(/[,;]+/).map(s => s.trim().toLowerCase()).filter(Boolean);
    selectLokasiEl.innerHTML = '<option value="">-- Pilih Lokasi --</option>';
    let entries = [];
    if (kategori === 'kursusonline') {
      entries = Object.entries(ALL_CITIES);
    } else {
      entries = codes.filter(c => Object.prototype.hasOwnProperty.call(ALL_CITIES, c)).map(c => [c, ALL_CITIES[c]]);
    }
    entries.forEach(([code, name]) => {
      const optEl = document.createElement('option');
      optEl.value = code;
      optEl.textContent = name;
      selectLokasiEl.appendChild(optEl);
    });
    if (presetValue) {
      selectLokasiEl.value = presetValue;
      if (selectLokasiEl.value !== presetValue) {
        // fallback jika value tidak tersedia
        selectLokasiEl.selectedIndex = 0;
      }
    }
  }

  // Trigger modal tambah jadwal
  document.getElementById('btnTambahJadwal')?.addEventListener('click', function(e) {
    e.preventDefault();
    var modal = new bootstrap.Modal(document.getElementById('modalTambahJadwal'));
    modal.show();
    // Populate lokasi sesuai kelas terpilih
    populateLokasi(document.getElementById('create_kelas_id'), document.getElementById('create_lokasi'));
  });

  // Ubah lokasi saat kelas dipilih pada modal tambah
  document.getElementById('create_kelas_id')?.addEventListener('change', function() {
    populateLokasi(this, document.getElementById('create_lokasi'));
  });

  // Handler edit jadwal
  document.querySelectorAll('.btn-edit').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var tr = this.closest('tr');
      document.getElementById('edit_id').value = this.dataset.id;
      document.getElementById('edit_kelas_id').value = tr.dataset.kelasId;
      document.getElementById('edit_tanggal_mulai').value = tr.dataset.tanggalMulai;
      document.getElementById('edit_tanggal_selesai').value = tr.dataset.tanggalSelesai;
      // Populate lokasi sesuai kelas terpilih, lalu set value
      populateLokasi(document.getElementById('edit_kelas_id'), document.getElementById('edit_lokasi'), tr.dataset.lokasi);
      document.getElementById('edit_instruktur').value = tr.dataset.instruktur;
      document.getElementById('edit_kapasitas').value = tr.dataset.kapasitas;

      var modal = new bootstrap.Modal(document.getElementById('modalEditJadwal'));
      modal.show();
    });
  });

  // Hapus jadwal (konfirmasi)
  function deleteJadwal(id, namaKelas) {
    if (confirm(`Apakah Anda yakin ingin menghapus jadwal kelas "${namaKelas}"?`)) {
      window.location.href = '<?= base_url('admin/jadwal/delete') ?>/' + id;
    }
  }
</script>
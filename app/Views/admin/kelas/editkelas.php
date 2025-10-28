<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Edit Kelas</h5>
      <a href="<?= esc($backUrl ?? base_url('admin/kelas')) ?>" class="btn btn-sm btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>

    <?php if (session()->has('message')): ?>
      <div class="alert alert-success"><?= session('message') ?></div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
      <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif; ?>
    <?php if (session()->has('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach (session('errors') as $err): ?>
            <li><?= esc($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php
    $oldKota = [];
    $rawKota = old('kota_tersedia') ?: ($kelas['kota_tersedia'] ?? '');
    if (is_array($rawKota)) {
      $oldKota = array_map('strtolower', $rawKota);
    } else {
      $oldKota = array_filter(array_map('strtolower', explode(',', (string) $rawKota)), static function ($v) {
        return trim($v) !== '';
      });
    }

    $extraImages = [];
    $rawExtra = $kelas['gambar_tambahan'] ?? null;
    if (is_string($rawExtra) && trim($rawExtra) !== '') {
      $decoded = json_decode($rawExtra, true);
      if (is_array($decoded)) {
        $extraImages = $decoded;
      }
    }
    ?>

    <div class="row">
      <div class="col-12">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <form action="<?= base_url('admin/kelas/' . ($kelas['id'] ?? 0) . '/update') ?>" method="post" enctype="multipart/form-data">
              <?= csrf_field() ?>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="nama_kelas" class="form-label">Nama Kelas</label>
                  <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" value="<?= esc(old('nama_kelas', $kelas['nama_kelas'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6">
                  <label for="kode_kelas" class="form-label">Kode Kelas</label>
                  <input type="text" class="form-control" id="kode_kelas" value="<?= esc(old('kode_kelas', $kelas['kode_kelas'] ?? '')) ?>" disabled>
                  <input type="hidden" name="kode_kelas" value="<?= esc(old('kode_kelas', $kelas['kode_kelas'] ?? '')) ?>">
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="kategori" class="form-label">Kategori</label>
                  <select class="form-select" id="kategori" name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php $kategoriVal = old('kategori', $kelas['kategori'] ?? ''); ?>
                    <option value="Kursus" <?= $kategoriVal === 'Kursus' ? 'selected' : '' ?>>Kelas Offline</option>
                    <option value="kursusonline" <?= $kategoriVal === 'kursusonline' ? 'selected' : '' ?>>Kelas Online</option>
                    <option value="Jasa" <?= $kategoriVal === 'Jasa' ? 'selected' : '' ?>>Jasa</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="status_kelas" class="form-label">Status Kelas</label>
                  <select class="form-select" id="status_kelas" name="status_kelas" required>
                    <?php $statusVal = old('status_kelas', $kelas['status_kelas'] ?? ''); ?>
                    <option value="">-- Pilih Status --</option>
                    <option value="aktif" <?= $statusVal === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= $statusVal === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    <option value="segera" <?= $statusVal === 'segera' ? 'selected' : '' ?>>Segera</option>
                  </select>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="harga" class="form-label">Harga</label>
                  <input type="number" step="0.01" class="form-control" id="harga" name="harga" value="<?= esc(old('harga', $kelas['harga'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6">
                  <label for="durasi" class="form-label">Durasi</label>
                  <input type="text" class="form-control" id="durasi" name="durasi" value="<?= esc(old('durasi', $kelas['durasi'] ?? '')) ?>" placeholder="Misal: 3 hari">
                </div>
              </div>

              <div class="mb-3">
                <label for="deskripsi_singkat" class="form-label">Deskripsi Singkat</label>
                <textarea class="form-control" id="deskripsi_singkat" name="deskripsi_singkat" rows="3" placeholder="Ringkasan singkat kelas..."><?= esc(old('deskripsi_singkat', $kelas['deskripsi_singkat'] ?? '')) ?></textarea>
              </div>

              <div class="mb-3">
                <label for="summernote" class="form-label">Detail Kelas</label>
                <textarea id="summernote" name="deskripsi" rows="6"><?= old('deskripsi', $kelas['deskripsi'] ?? '') ?></textarea>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="gambar_utama" class="form-label">Gambar Utama</label>
                  <?php $img = (string) ($kelas['gambar_utama'] ?? ''); ?>
                  <?php if ($img !== ''): ?>
                    <div class="mb-2"><img src="<?= base_url($img) ?>" alt="Gambar Utama" style="max-width:200px;object-fit:cover" class="rounded border"></div>
                  <?php endif; ?>
                  <input type="file" class="form-control" id="gambar_utama" name="gambar_utama" accept="image/*">
                  <small class="text-muted">Biarkan kosong jika tidak mengganti gambar utama.</small>
                </div>
                <div class="col-md-6">
                  <label for="gambar_tambahan" class="form-label">Gambar Tambahan</label>
                  <?php if (!empty($extraImages)): ?>
                    <div class="extra-slider mb-2" id="extraSlider">
                      <button type="button" class="btn btn-light btn-sm extra-nav extra-prev" aria-label="Sebelumnya">&lsaquo;</button>
                      <div class="extra-viewport" id="extraViewport">
                        <div class="extra-track" id="extraTrack">
                          <?php foreach ($extraImages as $idx => $p): ?>
                            <div class="extra-item">
                              <div class="image-wrap rounded border">
                                <img src="<?= base_url($p) ?>" alt="Gambar Tambahan">
                                <button type="button" class="btn btn-danger btn-sm btn-image-delete" title="Hapus gambar"
                                        onclick="hapusGambarTambahan(<?= (int) ($kelas['id'] ?? 0) ?>, <?= (int) $idx ?>)">
                                  <i class="bi bi-trash"></i>
                                </button>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      </div>
                      <button type="button" class="btn btn-light btn-sm extra-nav extra-next" aria-label="Berikutnya">&rsaquo;</button>
                    </div>
                  <?php endif; ?>
                  <input type="file" class="form-control" id="gambar_tambahan" name="gambar_tambahan[]" accept="image/*" multiple>
                  <small class="text-muted">Anda dapat menambahkan gambar tambahan baru; gambar lama tetap tersimpan.</small>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label d-block">Kota Tersedia</label>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="kota_malang" name="kota_tersedia[]" value="malang" <?= in_array('malang', $oldKota) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="kota_malang">Malang</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="kota_jogja" name="kota_tersedia[]" value="jogja" <?= in_array('jogja', $oldKota) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="kota_jogja">Jogja</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="kota_world" name="kota_tersedia[]" value="seluruh dunia" <?= in_array('seluruh dunia', $oldKota) || (($kelas['kategori'] ?? '') === 'kursusonline') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="kota_world">Seluruh Dunia</label>
                  </div>
                  <small class="text-muted d-block mt-1">Untuk kelas online, opsi ini akan otomatis ditambahkan.</small>
                </div>
                <div class="col-md-6">
                  <label for="badge" class="form-label">Badge</label>
                  <?php $badgeVal = old('badge', $kelas['badge'] ?? ''); ?>
                  <select class="form-select" id="badge" name="badge">
                    <option value="">-- Pilih Badge --</option>
                    <option value="nobadge" <?= $badgeVal === 'nobadge' ? 'selected' : '' ?>>Tanpa Badge</option>
                    <option value="hot" <?= $badgeVal === 'hot' ? 'selected' : '' ?>>Hot</option>
                    <option value="sale" <?= $badgeVal === 'sale' ? 'selected' : '' ?>>Sale</option>
                  </select>
                </div>
              </div>

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="<?= esc($backUrl ?? base_url('admin/kelas')) ?>" class="btn btn-outline-secondary">Batal</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  function hapusGambarTambahan(kelasId, index) {
    if (!confirm('Hapus gambar tambahan ini?')) return;
    var url = '<?= base_url('admin/kelas') ?>/' + encodeURIComponent(kelasId) + '/image/delete/' + encodeURIComponent(index);
    fetch(url, { method: 'GET' })
      .then(function(res) { return res.json(); })
      .then(function(data) {
        if (data && data.success) {
          window.location.reload();
        } else {
          alert((data && data.message) ? data.message : 'Gagal menghapus gambar');
        }
      })
      .catch(function() {
        alert('Terjadi kesalahan saat menghapus gambar');
      });
  }

  // Initialize Summernote if available
  document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && typeof jQuery.fn.summernote === 'function') {
      jQuery('#summernote').summernote({
        height: 250,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link']],
          ['view', ['codeview']]
        ]
      });
    }

    // Auto-check "Seluruh Dunia" when kategori is set to kelas online
    var kategoriSelect = document.getElementById('kategori');
    var kotaWorld = document.getElementById('kota_world');
    if (kategoriSelect && kotaWorld) {
      var ensureWorld = function() {
        if (kategoriSelect.value === 'kursusonline') {
          kotaWorld.checked = true;
        }
      };
      ensureWorld();
      kategoriSelect.addEventListener('change', ensureWorld);
    }
  });
</script>
<style>
  .image-wrap { position: relative; display: inline-block; }
  .image-wrap .btn-image-delete {
    position: absolute;
    top: 0.25rem;
    right: 0.25rem;
    opacity: 0;
    transition: opacity 0.3s ease;
  }
  .image-wrap:hover .btn-image-delete { opacity: 1; }

  /* Slider consistent sizing matching main image preview */
  .extra-slider { display: flex; align-items: center; gap: 8px; }
  .extra-viewport { overflow: hidden; flex: 1; }
  .extra-track { display: flex; gap: 12px; }
  .extra-item { flex: 0 0 auto; }
  .extra-item .image-wrap { max-width: 200px; height: auto; overflow: hidden; }
  .extra-item .image-wrap img { width: 100%; height: auto; object-fit: cover; }
  .extra-nav { line-height: 1; }
</style>
<script>
  // Simple horizontal slider navigation
  (function(){
    const viewport = document.getElementById('extraViewport');
    const prevBtn = document.querySelector('#extraSlider .extra-prev');
    const nextBtn = document.querySelector('#extraSlider .extra-next');
    if (!viewport || !prevBtn || !nextBtn) return;
    const step = 212 + 12; // item width + gap
    prevBtn.addEventListener('click', function(){
      viewport.scrollBy({ left: -step, behavior: 'smooth' });
    });
    nextBtn.addEventListener('click', function(){
      viewport.scrollBy({ left: step, behavior: 'smooth' });
    });
  })();
</script>
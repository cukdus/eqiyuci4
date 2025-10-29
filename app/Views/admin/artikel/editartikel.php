<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Edit Artikel</h5>
      <a href="<?= base_url('admin/artikel') ?>" class="btn btn-secondary btn-sm">
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

    <div class="row">
      <div class="col-12">
        <div class="card card-outline card-warning">
          <div class="card-body">
            <form action="<?= base_url('admin/artikel/' . (int) $article['id'] . '/update') ?>" method="post" enctype="multipart/form-data">
              <?= csrf_field() ?>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="<?= esc(old('judul', $article['judul'] ?? '')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori_id" name="kategori_id">
                    <option value="">-- Pilih Kategori --</option>
                    <?php if (!empty($categories)): ?>
                      <?php foreach ($categories as $kat): ?>
                        <?php $sel = (string) old('kategori_id', $article['kategori_id'] ?? '') === (string) $kat['id'] ? 'selected' : ''; ?>
                        <option value="<?= (int) $kat['id'] ?>" <?= $sel ?>>
                          <?= esc($kat['nama_kategori']) ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input class="form-control" type="text" id="penulis" name="penulis" value="<?= esc(old('penulis', $article['penulis'] ?? '')) ?>" aria-label="Disabled input example" disabled>
                </div>
                <div class="col-md-6">
                    <label for="tanggal_terbit" class="form-label">Tanggal Terbit</label>
                <?php
                $tt = $article['tanggal_terbit'] ?? '';
                $ttVal = $tt ? date('Y-m-d\TH:i', strtotime($tt)) : '';
                ?>
                <input type="datetime-local" class="form-control" id="tanggal_terbit" name="tanggal_terbit" value="<?= esc(old('tanggal_terbit', $ttVal)) ?>">
                </div>
            </div>

             

              <!-- Gambar Utama: Preview & Upload baru opsional -->
              <div class="mb-3">
                <label class="form-label">Gambar Utama</label>
                
                <input type="file" class="form-control" id="gambar_utama" name="gambar_utama" accept="image/*">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Format: JPG/PNG, ukuran maks 2MB.</small>
                <?php if (!empty($article['gambar_utama'])): ?>
                  <div class="mb-2">
                    <img src="<?= base_url($article['gambar_utama']) ?>" alt="Gambar Utama" style="max-height: 140px;" class="border rounded">
                  </div>
                <?php endif; ?>
                <div id="cropPreview" class="mt-2"></div>
              </div>          

              <div class="mb-3">
                <label for="summernote" class="form-label">Konten</label>
                <textarea class="form-control" id="summernote" name="konten" rows="10"><?= old('konten', $article['konten'] ?? '') ?></textarea>
              </div>

              <div class="mb-3">
                <label for="tags" class="form-label">Tag</label>
                <input type="text" class="form-control" id="tags" name="tags" value="<?= esc(old('tags', $tagsStr ?? '')) ?>" placeholder="Masukkan tag, pisahkan dengan koma">
                <small class="text-muted">Gunakan koma untuk memisahkan beberapa tag. Tag akan disimpan ke tabel pivot <code>berita_tag</code>.</small>
              </div>

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Simpan Perubahan</button>
                <a href="<?= base_url('admin/artikel') ?>" class="btn btn-outline-secondary">Batal</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal Cropper -->
  <div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crop Gambar (1024 x 683)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="border rounded p-2">
            <img id="cropImage" src="" alt="Crop Source" style="max-width:100%;display:block;">
          </div>
          <div class="small text-muted mt-2">Gunakan scroll untuk zoom, drag untuk geser (mode pindah). Dobel-klik untuk ganti mode Crop/Move. Rasio terkunci 1024/683.</div>
        </div>
        <div class="modal-footer">
          <button id="btnCropApply" type="button" class="btn btn-primary">Crop & Terapkan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Cropper.js dari CDN (bisa diganti ke aset lokal) -->
  <link rel="stylesheet" href="https://unpkg.com/cropperjs@1.6.1/dist/cropper.min.css">
  <script src="https://unpkg.com/cropperjs@1.6.1/dist/cropper.min.js"></script>

  <script>
    (function(){
      const input = document.getElementById('gambar_utama');
      const cropModalEl = document.getElementById('cropModal');
      const cropImg = document.getElementById('cropImage');
      const btnApply = document.getElementById('btnCropApply');
      const btnClose = cropModalEl ? cropModalEl.querySelector('[data-bs-dismiss="modal"]') : null;
      const preview = document.getElementById('cropPreview');

      let cropper = null;
      let selectedFile = null;
      let bsModal = null;
      let dragIsMove = true;

      // Init Bootstrap modal object jika tersedia
      if (typeof bootstrap !== 'undefined' && cropModalEl) {
        bsModal = new bootstrap.Modal(cropModalEl, { backdrop: 'static', keyboard: false });
      }

      // Fallback tampilkan "modal" secara manual jika Bootstrap tidak tersedia
      function showFallbackModal(){
        if (!cropModalEl) return;
        cropModalEl.style.display = 'block';
        cropModalEl.classList.add('show');
        cropModalEl.removeAttribute('aria-hidden');
        cropModalEl.setAttribute('aria-modal', 'true');
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
      }
      function hideFallbackModal(){
        if (!cropModalEl) return;
        cropModalEl.style.display = 'none';
        cropModalEl.classList.remove('show');
        cropModalEl.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
      }
      function cleanupCropper(){
        if (cropper) { cropper.destroy(); cropper = null; }
        cropImg.src = '';
      }

      cropModalEl.addEventListener('hidden.bs.modal', function(){
        cleanupCropper();
      });
      if (btnClose) {
        btnClose.addEventListener('click', function(){
          if (!bsModal) {
            hideFallbackModal();
            cleanupCropper();
          }
        });
      }

      // Toggle drag mode via double-click
      cropImg.addEventListener('dblclick', function(){
        if (!cropper) return;
        dragIsMove = !dragIsMove;
        cropper.setDragMode(dragIsMove ? 'move' : 'crop');
      });

      // Saat file dipilih: buka modal crop
      input.addEventListener('change', function(){
        const f = input.files && input.files[0] ? input.files[0] : null;
        if (!f) return;
        selectedFile = f;

        const reader = new FileReader();
        reader.onload = function(e){
          cropImg.src = e.target.result;
          if (bsModal) { bsModal.show(); } else { showFallbackModal(); }
          setTimeout(function(){
            if (typeof Cropper === 'undefined') {
              alert('Cropper.js tidak dimuat. Pastikan file JS tersedia.');
              return;
            }
            if (cropper) cropper.destroy();
            cropper = new Cropper(cropImg, {
              aspectRatio: 1024 / 683,
              viewMode: 1,
              autoCropArea: 1,
              responsive: true,
              background: false,
              zoomOnWheel: true,
              wheelZoomRatio: 0.1,
              dragMode: 'move',
              movable: true,
              cropBoxMovable: true,
              cropBoxResizable: true,
            });
          }, 100);
        };
        reader.readAsDataURL(f);
      });

      // Terapkan hasil crop ke input file
      btnApply.addEventListener('click', function(){
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({ width: 1024, height: 683 });
        const quality = 0.85;
        canvas.toBlob(function(jpegBlob){
          const baseName = (selectedFile?.name || 'gambar').replace(/\.[^.]+$/, '');
          const newFileName = baseName + '-cropped.jpg';
          const croppedFile = new File([jpegBlob], newFileName, { type: 'image/jpeg' });

          const dt = new DataTransfer();
          dt.items.add(croppedFile);
          input.files = dt.files;

          const url = URL.createObjectURL(croppedFile);
          preview.innerHTML = '<div class="small text-muted mb-1">Preview hasil crop:</div><img src="'+url+'" alt="Preview" class="border rounded" style="max-height:160px;object-fit:cover;">';

          if (bsModal) { bsModal.hide(); } else { hideFallbackModal(); }
        }, 'image/jpeg', quality);
      });
    })();
  </script>
</section>
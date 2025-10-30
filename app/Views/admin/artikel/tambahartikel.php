<section class="content">
  <div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Tambah Artikel</h5>
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
        <div class="card card-outline card-primary">
          <div class="card-body">
            <form action="<?= base_url('admin/artikel/tambah') ?>" method="post" enctype="multipart/form-data">
              <?= csrf_field() ?>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="<?= esc(old('judul')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori_id" name="kategori_id">
                        <option value="">-- Pilih Kategori --</option>
                        <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $kat): ?>
                            <option value="<?= (int) $kat['id'] ?>" <?= old('kategori_id') == $kat['id'] ? 'selected' : '' ?>>
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
                    <input class="form-control" type="text" id="penulis" name="penulis" value="<?= esc(old('penulis', $penulisDefault ?? '')) ?>" aria-label="Disabled input example" disabled>
                </div>
                <div class="col-md-6">
                    <label for="tanggal_terbit" class="form-label">Tanggal Terbit <small class="text-muted">(jam default 11:00)</small></label>
                <input type="date" class="form-control" id="tanggal_terbit" name="tanggal_terbit" value="<?= esc(old('tanggal_terbit')) ?>">
                </div>
            </div>
            <div class="mb-3">
                <label for="gambar_utama" class="form-label">Gambar Utama</label>
                <input type="file" class="form-control" id="gambar_utama" name="gambar_utama" accept="image/*">
                <small class="text-muted">Format: JPG/PNG, ukuran maks 2MB.</small>
                <div id="cropPreview" class="mt-2"></div>
              </div>

              <div class="mb-3">
                <label for="summernote" class="form-label">Konten</label>
                <textarea class="form-control" id="summernote" name="konten" rows="10"><?= old('konten') ?></textarea>
              </div>

              <div class="mb-3">
                <label for="tags" class="form-label">Tag</label>
                <input type="text" class="form-control" id="tags" name="tags" value="<?= esc(old('tags')) ?>" placeholder="Masukkan tag, pisahkan dengan koma (contoh: edukasi, eqiyu, belajar)">
                <small class="text-muted">Gunakan koma untuk memisahkan beberapa tag. Tag akan disimpan ke tabel pivot <code>berita_tag</code>.</small>
              </div>

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
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
          <div class="small text-muted mt-2">Gunakan scroll untuk zoom, drag untuk geser. Rasio terkunci 1024/683.</div>
        </div>
        <div class="modal-footer">
          <button id="btnCropApply" type="button" class="btn btn-primary">Crop & Terapkan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>


  <link rel="stylesheet" href="<?= base_url('assets/css/cropper.min.css') ?>">
  <script src="<?= base_url('assets/js/cropper.min.js') ?>"></script>

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

      // Bersihkan cropper saat modal ditutup
      cropModalEl.addEventListener('hidden.bs.modal', function(){
        cleanupCropper();
      });
      if (btnClose) {
        btnClose.addEventListener('click', function(){
          if (!bsModal) {
            // fallback close
            hideFallbackModal();
            cleanupCropper();
          }
        });
      }

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
        const canvas = cropper.getCroppedCanvas({
          width: 1024,
          height: 683,
        });
        const quality = 0.85; // jaga agar <= 2MB umumnya aman
        canvas.toBlob(function(jpegBlob){
          const baseName = (selectedFile?.name || 'gambar').replace(/\.[^.]+$/, '');
          const newFileName = baseName + '-cropped.jpg';
          const croppedFile = new File([jpegBlob], newFileName, { type: 'image/jpeg' });

          // Masukkan file hasil crop ke input
          const dt = new DataTransfer();
          dt.items.add(croppedFile);
          input.files = dt.files;

          // Preview kecil
          const url = URL.createObjectURL(croppedFile);
          preview.innerHTML = '<div class="small text-muted mb-1">Preview hasil crop:</div><img src="'+url+'" alt="Preview" class="border rounded" style="max-height:160px;object-fit:cover;">';

          if (bsModal) { bsModal.hide(); } else { hideFallbackModal(); }
        }, 'image/jpeg', quality);
      });
    })();
  </script>

  <script>
    // Ambil daftar tag dari endpoint JSON untuk dropdown kustom (token-aware)
    (function(){
      const endpoint = '<?= base_url('admin/artikel/tags.json') ?>';
      fetch(endpoint, { headers: { 'Accept': 'application/json' } })
        .then(function(res){ return res.json(); })
        .then(function(json){
          if (!json || !json.ok || !Array.isArray(json.tags)) return;
          window.__allTags = json.tags.slice();
        })
        .catch(function(err){
          console.warn('Gagal memuat daftar tag:', err);
        });
    })();
  </script>

  <style>
    /* Dropdown saran kustom untuk Tag (tidak bergantung pada Bootstrap) */
    .tag-suggest-box {
      position: absolute;
      z-index: 1055;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 6px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      padding: 4px;
      max-height: 220px;
      overflow-y: auto;
      display: none;
    }
    .tag-suggest-item {
      padding: 6px 10px;
      cursor: pointer;
      border-radius: 4px;
    }
    .tag-suggest-item:hover,
    .tag-suggest-item.active {
      background: #f0f4ff;
    }
    .tag-suggest-empty {
      padding: 6px 10px;
      color: #888;
    }
  </style>

  <script>
    // Dropdown saran kustom berbasis token terakhir (setelah koma)
    (function(){
      const input = document.getElementById('tags');
      if (!input) return;

      const box = document.createElement('div');
      box.id = 'tagSuggestBox';
      box.className = 'tag-suggest-box';
      document.body.appendChild(box);

      let items = [];
      let activeIndex = -1;

      function rect(el){ return el.getBoundingClientRect(); }
      function positionBox(){
        const r = rect(input);
        box.style.minWidth = r.width + 'px';
        box.style.left = (window.scrollX + r.left) + 'px';
        box.style.top = (window.scrollY + r.bottom + 2) + 'px';
      }

      function getTokens(){
        return input.value.split(',').map(function(t){ return t.trim(); }).filter(Boolean);
      }
      function getLastTermRaw(){
        const parts = input.value.split(',');
        return parts[parts.length - 1] ?? '';
      }
      function getLastTerm(){
        return getLastTermRaw().trim().toLowerCase();
      }
      function normalize(s){ return (s || '').trim().toLowerCase(); }

      function buildList(){
        const all = Array.isArray(window.__allTags) ? window.__allTags : [];
        const tokens = getTokens().map(normalize);
        const term = getLastTerm();
        let candidates = all
          .filter(function(t){ return !tokens.includes(normalize(t)); })
          .filter(function(t){ return term ? normalize(t).startsWith(term) : true; })
          .slice(0, 30);

        box.innerHTML = '';
        activeIndex = -1;
        items = [];

        if (candidates.length === 0) {
          const empty = document.createElement('div');
          empty.className = 'tag-suggest-empty';
          empty.textContent = term ? 'Tidak ada saran yang cocok' : 'Tidak ada saran';
          box.appendChild(empty);
          return;
        }

        candidates.forEach(function(name, idx){
          const div = document.createElement('div');
          div.className = 'tag-suggest-item';
          div.textContent = name;
          div.dataset.value = name;
          div.addEventListener('mousedown', function(e){
            // gunakan mousedown agar eksekusi sebelum blur
            e.preventDefault();
            applyValue(name);
          });
          box.appendChild(div);
          items.push(div);
        });
      }

      function show(){ positionBox(); box.style.display = 'block'; }
      function hide(){ box.style.display = 'none'; activeIndex = -1; }
      function isVisible(){ return box.style.display !== 'none'; }

      function applyValue(val){
        const parts = input.value.split(',');
        // ganti token terakhir dengan nilai terpilih
        const head = parts.slice(0, -1);
        const newTokens = head.concat([val]);
        // tambahkan koma spasi untuk memudahkan tambah tag berikutnya
        input.value = newTokens.join(', ') + ', ';
        // setelah insert, tampilkan saran untuk token kosong berikutnya
        buildList();
        show();
        input.focus();
      }

      function setActive(i){
        items.forEach(function(el){ el.classList.remove('active'); });
        activeIndex = i;
        if (items[activeIndex]) items[activeIndex].classList.add('active');
      }

      input.addEventListener('input', function(){
        buildList();
        show();
      });

      input.addEventListener('focus', function(){
        buildList();
        show();
      });

      input.addEventListener('keydown', function(e){
        if (!isVisible()) return;
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          if (items.length) setActive((activeIndex + 1) % items.length);
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          if (items.length) setActive((activeIndex - 1 + items.length) % items.length);
        } else if (e.key === 'Enter' || e.key === 'Tab') {
          if (items.length && activeIndex >= 0) {
            e.preventDefault();
            applyValue(items[activeIndex].dataset.value);
          }
        } else if (e.key === 'Escape') {
          hide();
        }
      });

      input.addEventListener('blur', function(){
        // beri waktu untuk klik mousedown
        setTimeout(hide, 120);
      });

      window.addEventListener('resize', function(){ if (isVisible()) positionBox(); });
      window.addEventListener('scroll', function(){ if (isVisible()) positionBox(); }, true);
    })();
  </script>
</section>
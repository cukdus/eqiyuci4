<?php
$this->setVar('pageTitle', 'Jadwal Kelas | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
$this->setVar('metaDescription', 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.');
$this->setVar('metaKeywords', 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia');
$this->setVar('canonicalUrl', base_url());
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'jadwal');

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>
<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Jadwal Kelas</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
          <li class="current">Jadwal</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <!-- Courses Events Section -->
  <section id="courses-events" class="courses-events section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row">
        <div class="col-lg-8">
          <!-- Calendar Legend (Mobile) -->
          <div class="calendar-legend d-md-none" data-aos="fade-up" data-aos-delay="200">
            <div class="legend-item"><span class="legend-dot legend-malang"></span> Malang</div>
            <div class="legend-item"><span class="legend-dot legend-jogja"></span> Jogja</div>
          </div>

          <!-- Month Navigation (Mobile) -->
          <div class="mobile-month-nav d-md-none" data-aos="fade-up" data-aos-delay="220">
            <button id="msPrev" class="btn btn-light btn-sm" type="button" aria-label="Bulan sebelumnya"><i class="bi bi-arrow-left-circle-fill"></i></button>
            <span id="msLabel" class="ms-label">Bulan</span>
            <button id="msNext" class="btn btn-light btn-sm" type="button" aria-label="Bulan berikutnya"><i class="bi bi-arrow-right-circle-fill"></i></button>
          </div>

          <!-- Mobile Schedule List -->
          <div id="mobileSchedule" class="mobile-schedule d-md-none" data-aos="fade-up" data-aos-delay="250"></div>

          <!-- Event List (dynamic) -->
          <div id="eventsList"></div>
          <!-- Infinite Scroll Sentinel -->
          <div id="eventsSentinel" style="height: 1px;"></div>

          <!-- Pagination removed: using infinite scroll -->
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
          <!-- Filter Widget -->
          <div
            class="sidebar-widget filter-widget"
            data-aos="fade-up"
            data-aos-delay="300">
            <h4 class="widget-title">Filter Jadwal</h4>
            <div class="filter-content">
              <div class="filter-group">
                <label class="filter-label">Jenis Kursus</label>
                <select class="form-select" id="filterKelas">
                  <option value="">Semua Kursus</option>
                  <?php foreach (($kelasOptions ?? []) as $k): ?>
                    <option value="<?= esc($k['slug'] ?? ($k['nama_kelas'] ?? '')) ?>">
                      <?= esc($k['nama_kelas'] ?? '-') ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="filter-group">
                <label class="filter-label">Jadwal Bulan</label>
                <select class="form-select">
                  <option value="">Semua</option>
                  <option value="Januari">Januari</option>
                  <option value="Februari">Februari</option>
                  <option value="Maret">Maret</option>
                  <option value="April">April</option>
                </select>
              </div>
              <div class="filter-group">
                <label class="filter-label">Kota Kelas</label>
                <select class="form-select">
                  <option value="">Semua Kota</option>
                  <option value="malang">Malang</option>
                  <option value="jogja">Jogja</option>
                </select>
              </div>
              <button class="btn btn-primary filter-apply-btn">
                Terapkan
              </button>
            </div>
          </div>
          <!-- End Filter Widget -->

          <!-- Upcoming Events Widget -->
          <div
            class="sidebar-widget upcoming-widget"
            data-aos="fade-up"
            data-aos-delay="400">
            <h4 class="widget-title">Jadwal Mendatang</h4>
            <div class="upcoming-list" id="upcomingList"></div>
          </div>
          <!-- End Upcoming Events Widget -->

          <!-- Newsletter Widget -->
          <div
            class="sidebar-widget newsletter-widget"
            data-aos="fade-up"
            data-aos-delay="500">
            <h4 class="widget-title">Stay Updated</h4>
            <p>
              Berlangganan buletin kami dan jangan lewatkan jadwal penting
              dan pengumuman kursus.
            </p>
            <form
              action="forms/newsletter.php"
              method="post"
              class="php-email-form newsletter-form">
              <input
                type="email"
                name="email"
                placeholder="alamat email anda"
                required="" />
              <button type="submit">Berlangganan</button>
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">
                Your subscription request has been sent. Thank you!
              </div>
            </form>
          </div>
          <!-- End Newsletter Widget -->
        </div>
      </div>
    </div>
  </section>
  <!-- /Courses Events Section -->
</main>
<style>
  /* Legend styles */
  .calendar-legend { display: none; margin-bottom: 12px; }
  .calendar-legend .legend-item { display: inline-flex; align-items: center; margin-right: 14px; font-size: 14px; }
  .calendar-legend .legend-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 6px; }
  .calendar-legend .legend-malang { background: #198754; }
  .calendar-legend .legend-jogja { background: #0d6efd; }

  /* Mobile schedule styles */
  .mobile-schedule { display: none; }
  .mobile-schedule .ms-item { border: 1px solid #eee; border-left: 4px solid #dee2e6; border-radius: 10px; padding: 12px; margin-bottom: 10px; background: #fff; }
  .mobile-schedule .ms-title { font-weight: 600; margin: 0 0 6px; font-size: 15px; }
  .mobile-schedule .ms-title a { text-decoration: none; }
  .mobile-schedule .ms-meta { font-size: 13px; color: #6c757d; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
  .mobile-schedule .ms-loading { text-align: center; color: #6c757d; font-size: 14px; padding: 8px 0; }

  /* Month navigation (mobile) */
  .mobile-month-nav { display: none; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 10px; flex-wrap: nowrap; }
  .mobile-month-nav .ms-label { font-size: 24px; font-weight: 600; white-space: nowrap; }
  .mobile-month-nav .btn { font-size: 24px; padding: 4px 8px; color: var(--accent-color); }

  /* Mobile-only visibility */
  @media (max-width: 767.98px) {
    .sidebar-widget { display: none !important; }
    #eventsList, #eventsSentinel { display: none !important; }
    .calendar-legend, .mobile-schedule { display: block !important; }
    .mobile-month-nav { display: flex; }
  }

  /* Border color by lokasi (match legend) */
  .mobile-schedule .ms-item.loc-malang { border-left-color: #198754; }
  .mobile-schedule .ms-item.loc-jogja { border-left-color: #0d6efd; }
</style>
<script>
  (function(){
    const API_MONTH = '<?= base_url('api/jadwal') ?>';
    const API_UPCOMING = '<?= base_url('api/jadwal/upcoming') ?>';

    // Peta bulan Indonesia ke nomor
    const monthMap = {
      'Januari': 1,
      'Februari': 2,
      'Maret': 3,
      'April': 4,
      'Mei': 5,
      'Juni': 6,
      'Juli': 7,
      'Agustus': 8,
      'September': 9,
      'Oktober': 10,
      'November': 11,
      'Desember': 12
    };

    function pad2(n){ return String(n).padStart(2, '0'); }
    function bulanShort(m){
      const arr = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
      return arr[m-1] || '';
    }
    function fmtDate(dStr){
      const d = new Date(dStr);
      if (isNaN(d.getTime())) return '-';
      return `${pad2(d.getDate())} ${bulanShort(d.getMonth()+1)} ${d.getFullYear()}`;
    }
    function rupiah(n){
      if (n == null) return '';
      const num = typeof n === 'number' ? n : parseInt(n, 10);
      if (!isFinite(num)) return '';
      return 'Rp. ' + num.toLocaleString('id-ID') + ',-';
    }
    function escapeHtml(str){
      const div = document.createElement('div');
      div.innerText = str || '';
      return div.innerHTML;
    }

    // Ambil filter dari sidebar
    function getFilters(){
      const selects = document.querySelectorAll('.filter-widget .form-select');
      const kelasText = selects[0] ? selects[0].value : '';
      const monthText = selects[1] ? selects[1].value : '';
      const kota = selects[2] ? selects[2].value : '';
      const now = new Date();
      const month = monthMap[monthText] || (now.getMonth() + 1);
      const year = now.getFullYear();
      return { month, year, kelas: kelasText, lokasi: kota };
    }

    function monthNameFull(m){
      const arr = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
      return arr[m-1] || '';
    }
    function prevMonth(year, month){
      month -= 1; if (month < 1) { month = 12; year -= 1; }
      return { year, month };
    }
    let mCurrentMonth = null;
    let mCurrentYear = null;
    function updateMobileMonthLabel(){
      const lbl = document.getElementById('msLabel');
      if (lbl && mCurrentMonth && mCurrentYear) {
        lbl.textContent = monthNameFull(mCurrentMonth) + ' ' + String(mCurrentYear);
      }
    }

    // Render daftar jadwal versi mobile (bulan tertentu)
    async function renderMobileSchedule(year, month){
      const cont = document.getElementById('mobileSchedule');
      if (!cont) return;
      const filters = getFilters();
      if (!year || !month) {
        year = mCurrentYear ?? filters.year;
        month = mCurrentMonth ?? filters.month;
      }
      // Simpan state saat ini dan update label
      mCurrentYear = year; mCurrentMonth = month;
      updateMobileMonthLabel();
      const usp = new URLSearchParams({ month, year, kelas: filters.kelas || '', lokasi: filters.lokasi || '' });
      cont.innerHTML = '<div class="ms-loading">Memuat jadwal...</div>';
      try {
        const res = await fetch(API_MONTH + '?' + usp.toString(), { headers: { 'Accept': 'application/json' } });
        const json = await res.json();
        const rows = (json && json.ok && Array.isArray(json.data)) ? json.data : [];
        cont.innerHTML = '';
        if (!rows.length) {
          const empty = document.createElement('p');
          empty.className = 'text-center text-muted';
          empty.textContent = 'Tidak ada jadwal bulan ini.';
          cont.appendChild(empty);
          return;
        }
        rows.forEach(item => {
          const start = fmtDate(item.tanggal_mulai);
          const end = fmtDate(item.tanggal_selesai);
          const loc = (item.lokasi || '').charAt(0).toUpperCase() + (item.lokasi || '').slice(1);
          const detailUrl = '<?= base_url('kursus') ?>/' + encodeURIComponent(item.slug || '');
          const locClass = 'loc-' + String((item.lokasi || '')).toLowerCase();
          const html = `
            <div class="ms-item ${locClass}">
              <h6 class="ms-title"><a href="${detailUrl}">${escapeHtml(item.nama_kelas || '')}</a></h6>
              <div class="ms-meta">
                <span class="ms-date"><i class="bi bi-clock"></i> ${start} - ${end}</span>
                <span class="ms-lokasi"><i class="bi bi-geo-alt"></i> ${loc}</span>
              </div>
            </div>`;
          const wrapper = document.createElement('div');
          wrapper.innerHTML = html;
          cont.appendChild(wrapper.firstElementChild);
        });
      } catch (e) {
        cont.innerHTML = '<div class="ms-loading">Gagal memuat jadwal.</div>';
        console.error('Gagal memuat jadwal (mobile):', e);
      }
    }

    function buildEventCard(item){
      const start = fmtDate(item.tanggal_mulai);
      const end = fmtDate(item.tanggal_selesai);
      const loc = (item.lokasi || '').charAt(0).toUpperCase() + (item.lokasi || '').slice(1);
      const hargaHtml = item.harga ? `<span class="price">${rupiah(item.harga)}</span>` : '';
      const kapasitasTxt = (item.kapasitas != null && item.kapasitas !== '') ? `${item.kapasitas} orang` : '-';
      const d = new Date(item.tanggal_mulai);
      const day = pad2(d.getDate());
      const month = bulanShort(d.getMonth()+1);
      const detailUrl = '<?= base_url('kursus') ?>/' + encodeURIComponent(item.slug || '');
      const imgSrc = item.gambar_utama ? '<?= base_url('') ?>' + item.gambar_utama : '<?= base_url('assets/img/no-image.jpg') ?>';
      const imgAlt = escapeHtml(item.nama_kelas || 'Gambar Kelas');
      return `
        <article class="event-card" data-aos="fade-up" data-aos-delay="400">
          <div class="row g-0">
            <div class="col-md-4">
              <div class="event-image">
                <img src="${imgSrc}" class="img-fluid" alt="${imgAlt}" />
                <div class="date-badge">
                  <span class="day">${day}</span>
                  <span class="month">${month}</span>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="event-content">
                <div class="event-meta">
                  <span class="time"><i class="bi bi-clock"></i> ${start} - ${end}</span>
                  <span class="location"><i class="bi bi-geo-alt"></i> ${loc}</span>
                </div>
                <h3 class="event-title">
                  <a href="${detailUrl}">${escapeHtml(item.nama_kelas || '')}</a>
                </h3>
                <div class="event-footer">
                  <div class="instructor">
                    <i class="bi bi-people"></i><span> Kapasitas: ${kapasitasTxt}</span>
                  </div>
                  <div class="event-price">${hargaHtml}</div>
                </div>
                <div class="event-actions">
                  <a href="<?= base_url('daftar') ?>" class="btn btn-primary">Daftar Sekarang</a>
                  <a href="${detailUrl}" class="btn btn-outline">Pelajari Lebih Lanjut</a>
                </div>
              </div>
            </div>
          </div>
        </article>`;
    }

    // ===== Infinite Scroll State =====
    let currentMonth = null;
    let currentYear = null;
    let currentKelas = '';
    let currentLokasi = '';
    let loading = false;
    let monthsLoaded = new Set();
    let observer = null;

    function nextMonth(year, month){
      month += 1;
      if (month > 12) { month = 1; year += 1; }
      return { year, month };
    }

    async function loadMonth(year, month){
      if (loading) return;
      const key = `${year}-${month}`;
      if (monthsLoaded.has(key)) return;
      loading = true;
      monthsLoaded.add(key);
      const list = document.getElementById('eventsList');
      const loadingEl = document.createElement('div');
      loadingEl.className = 'text-center text-muted my-3';
      loadingEl.textContent = '...';
      list.appendChild(loadingEl);
      try {
        const usp = new URLSearchParams({ month, year, kelas: currentKelas, lokasi: currentLokasi });
        const res = await fetch(API_MONTH + '?' + usp.toString(), { headers: { 'Accept': 'application/json' } });
        const json = await res.json();
        const rows = (json && json.ok && Array.isArray(json.data)) ? json.data : [];
        loadingEl.remove();
        if (!rows.length) {
          // Jika ini bulan awal dan kosong, tampilkan pesan kosong satu kali
          if (month === currentMonth && year === currentYear && list.children.length === 0) {
            const p = document.createElement('p');
            p.className = 'text-center text-muted';
            p.textContent = 'Tidak ada jadwal untuk filter ini.';
            list.appendChild(p);
          }
          // Tetap lanjut: jika sentinel sudah terlihat, muat bulan berikutnya
          // return; // Lanjut bulan berikut saat sentinel terlihat
        }
        rows.forEach(item => {
          const wrapper = document.createElement('div');
          wrapper.innerHTML = buildEventCard(item);
          list.appendChild(wrapper.firstElementChild);
        });
      } catch (e) {
        loadingEl.remove();
        console.error('Gagal memuat jadwal:', e);
      } finally {
        loading = false;
        // Prefetch: jika sentinel sudah berada dalam viewport, muat bulan berikutnya
        const sentinel = document.getElementById('eventsSentinel');
        if (sentinel) {
          const rect = sentinel.getBoundingClientRect();
          if (rect.top <= (window.innerHeight + 200)) {
            const nm = nextMonth(currentYear, currentMonth);
            currentYear = nm.year; currentMonth = nm.month;
            loadMonth(currentYear, currentMonth);
          }
        }
      }
    }

    function initInfiniteScroll(){
      // Setup initial state from filters
      const filters = getFilters();
      currentMonth = filters.month;
      currentYear = filters.year;
      // Clamp: jangan mulai dari bulan yang sudah terlewati
      const now = new Date();
      const nowMonth = now.getMonth() + 1;
      const nowYear = now.getFullYear();
      if (currentYear < nowYear || (currentYear === nowYear && currentMonth < nowMonth)) {
        currentYear = nowYear;
        currentMonth = nowMonth;
      }
      currentKelas = filters.kelas || '';
      currentLokasi = filters.lokasi || '';
      monthsLoaded.clear();
      const list = document.getElementById('eventsList');
      list.innerHTML = '';

      // Load initial month
      loadMonth(currentYear, currentMonth);

      // Observer to load next month when reaching bottom
      const sentinel = document.getElementById('eventsSentinel');
      if (observer) observer.disconnect();
      observer = new IntersectionObserver(entries => {
        for (const entry of entries) {
          if (entry.isIntersecting && !loading) {
            const nm = nextMonth(currentYear, currentMonth);
            currentYear = nm.year; currentMonth = nm.month;
            loadMonth(currentYear, currentMonth);
          }
        }
      }, { root: null, rootMargin: '200px', threshold: 0 });
      observer.observe(sentinel);
    }

    async function loadUpcoming(){
      const filters = getFilters();
      const usp = new URLSearchParams({ limit: 5, kelas: filters.kelas || '', lokasi: filters.lokasi || '' });
      try {
        const res = await fetch(API_UPCOMING + '?' + usp.toString(), { headers: { 'Accept': 'application/json' } });
        const json = await res.json();
        const cont = document.getElementById('upcomingList');
        cont.innerHTML = '';
        const rows = (json && json.ok && Array.isArray(json.data)) ? json.data : [];
        if (!rows.length) {
          const p = document.createElement('p');
          p.className = 'text-center text-muted';
          p.textContent = 'Belum ada jadwal mendatang.';
          cont.appendChild(p);
          return;
        }
        rows.forEach(r => {
          const d = new Date(r.tanggal_mulai);
          const day = pad2(d.getDate());
          const month = bulanShort(d.getMonth()+1);
          const hargaHtml = r.harga ? `<span class="price">${rupiah(r.harga)}</span>` : '';
          const detailUrl = '<?= base_url('kursus') ?>/' + encodeURIComponent(r.slug || '');
          const html = `
            <div class="upcoming-item">
              <div class="upcoming-date">
                <span class="day">${day}</span>
                <span class="month">${month}</span>
              </div>
              <div class="upcoming-content">
                <h5 class="upcoming-title"><a href="${detailUrl}">${escapeHtml(r.nama_kelas || '')}</a></h5>
                <div class="upcoming-meta">
                  <span class="time"><i class="bi bi-geo-alt"></i> ${(r.lokasi || '').charAt(0).toUpperCase() + (r.lokasi || '').slice(1)}</span>
                  ${hargaHtml}
                </div>
              </div>
            </div>`;
          const wrapper = document.createElement('div');
          wrapper.innerHTML = html;
          cont.appendChild(wrapper.firstElementChild);
        });
      } catch (e) {
        console.error('Gagal memuat upcoming:', e);
      }
    }

    // Tombol Terapkan filter
    const applyBtn = document.querySelector('.filter-apply-btn');
    if (applyBtn) {
      applyBtn.addEventListener('click', function(e){
        e.preventDefault();
        initInfiniteScroll();
        loadUpcoming();
        // Reset bulan mobile mengikuti filter
        const f = getFilters();
        const now = new Date();
        const nowMonth = now.getMonth() + 1;
        const nowYear = now.getFullYear();
        let mm = f.month; let yy = f.year;
        if (yy < nowYear || (yy === nowYear && mm < nowMonth)) { yy = nowYear; mm = nowMonth; }
        renderMobileSchedule(yy, mm);
      });
    }

    // Init saat halaman dibuka
    initInfiniteScroll();
    loadUpcoming();
    // Inisialisasi bulan/tahun mobile mengikuti filter (clamp ke saat ini)
    const f0 = getFilters();
    const now0 = new Date();
    const nowM0 = now0.getMonth() + 1;
    const nowY0 = now0.getFullYear();
    let mm0 = f0.month; let yy0 = f0.year;
    if (yy0 < nowY0 || (yy0 === nowY0 && mm0 < nowM0)) { yy0 = nowY0; mm0 = nowM0; }
    renderMobileSchedule(yy0, mm0);

    // Event navigasi bulan (mobile)
    const prevBtn = document.getElementById('msPrev');
    const nextBtn = document.getElementById('msNext');
    if (prevBtn) {
      prevBtn.addEventListener('click', function(){
        const r = prevMonth(mCurrentYear, mCurrentMonth);
        renderMobileSchedule(r.year, r.month);
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function(){
        const r = nextMonth(mCurrentYear, mCurrentMonth);
        renderMobileSchedule(r.year, r.month);
      });
    }
  })();
</script>
<?= $this->endSection() ?>
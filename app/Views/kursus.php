<?php
$this->setVar('pageTitle', 'Kursus | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
$this->setVar('metaDescription', 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.');
$this->setVar('metaKeywords', 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia');
$this->setVar('canonicalUrl', base_url());
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'index');

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>
<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Lihat Kursus</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
          <li class="current">Kursus</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <!-- Courses 2 Section -->
  <section id="courses-2" class="courses-2 section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row">
        <div class="col-lg-3">
          <div
            class="course-filters"
            data-aos="fade-right"
            data-aos-delay="100">
            <h4 class="filter-title">Filter Kursus</h4>

            <div class="filter-group">
              <h5>Kategori</h5>
              <div class="filter-options">
                <label class="filter-checkbox">
                  <input type="checkbox" checked="" />
                  <span class="checkmark"></span>
                  Semua Kategori
                </label>
                <label class="filter-checkbox">
                  <input type="checkbox" />
                  <span class="checkmark"></span>
                  Kursus Offline
                </label>
                <label class="filter-checkbox">
                  <input type="checkbox" />
                  <span class="checkmark"></span>
                  Kursus Online
                </label>
                <label class="filter-checkbox">
                  <input type="checkbox" />
                  <span class="checkmark"></span>
                  Jasa
                </label>
              </div>
            </div>
          </div>
          <!-- End Course Filters -->
        </div>

        <div class="col-lg-9">
          <div class="courses-grid" data-aos="fade-up" data-aos-delay="200">
            <div class="row">
              <div class="col-lg-6 col-md-6 mb-4">
                <div class="course-card">
                  <div class="course-image">
                    <img
                      src="assets/img/education/courses-3.webp"
                      alt="Course"
                      class="img-fluid" />
                    <div class="course-badge">Best Seller</div>
                    <div class="course-price">Rp. 1.200.000,-</div>
                  </div>
                  <div class="course-content">
                    <div class="course-meta">
                      Tersedia di:
                      <span class="category">Malang</span>
                      <span class="category">Jogja</span>
                    </div>
                    <h3>Basic Barista & Latte Art</h3>
                    <div class="course-stats">
                      <div class="stat">
                        <i class="bi bi-clock"></i>
                        <span>3 hari</span>
                      </div>
                      <div class="stat">
                        <i class="bi bi-people"></i>
                        <span>Kursus Offline</span>
                      </div>
                    </div>
                    <a href="course-details.php" class="btn-course">Lihat Detail</a>
                  </div>
                </div>
                <!-- End Course Card -->
              </div>

              <div class="col-lg-6 col-md-6 mb-4">
                <div class="course-card">
                  <div class="course-image">
                    <img
                      src="assets/img/education/courses-7.webp"
                      alt="Course"
                      class="img-fluid" />
                    <div class="course-badge badge-free">Free</div>
                  </div>
                  <div class="course-content">
                    <div class="course-meta">
                      Tersedia di:
                      <span class="category">Malang</span>
                      <span class="category">Jogja</span>
                    </div>
                    <h3>Workshop Membangun Bisnis Cafe & Kedai Kopi</h3>
                    <div class="course-stats">
                      <div class="stat">
                        <i class="bi bi-clock"></i>
                        <span>3 hari</span>
                      </div>
                      <div class="stat">
                        <i class="bi bi-people"></i>
                        <span>Kursus Offline</span>
                      </div>
                    </div>
                    <a href="course-details.php" class="btn-course">Lihat Detail</a>
                  </div>
                </div>
                <!-- End Course Card -->
              </div>

              <div class="col-lg-6 col-md-6 mb-4">
                <div class="course-card">
                  <div class="course-image">
                    <img
                      src="assets/img/education/courses-12.webp"
                      alt="Course"
                      class="img-fluid" />
                    <div class="course-badge badge-new">New</div>
                    <div class="course-price">Rp. 1.200.000,-</div>
                  </div>
                  <div class="course-content">
                    <div class="course-meta">
                      Tersedia di:
                      <span class="category">Malang</span>
                      <span class="category">Jogja</span>
                    </div>
                    <h3>Private Class Beverage & Bisnis Culinary</h3>
                    <div class="course-stats">
                      <div class="stat">
                        <i class="bi bi-clock"></i>
                        <span>3 hari</span>
                      </div>
                      <div class="stat">
                        <i class="bi bi-people"></i>
                        <span>Kursus Offline</span>
                      </div>
                    </div>
                    <a href="course-details.php" class="btn-course">Lihat Detail</a>
                  </div>
                </div>
                <!-- End Course Card -->
              </div>

              <div class="col-lg-6 col-md-6 mb-4">
                <div class="course-card">
                  <div class="course-image">
                    <img
                      src="assets/img/education/courses-5.webp"
                      alt="Course"
                      class="img-fluid" />
                    <div class="course-price">Rp. 1.200.000,-</div>
                  </div>
                  <div class="course-content">
                    <div class="course-meta">
                      Tersedia di:
                      <span class="category">Malang</span>
                      <span class="category">Jogja</span>
                    </div>
                    <h3>Mixology Class</h3>
                    <div class="course-stats">
                      <div class="stat">
                        <i class="bi bi-clock"></i>
                        <span>3 hari</span>
                      </div>
                      <div class="stat">
                        <i class="bi bi-people"></i>
                        <span>Kursus Offline</span>
                      </div>
                    </div>
                    <a href="course-details.php" class="btn-course">Lihat Detail</a>
                  </div>
                </div>
                <!-- End Course Card -->
              </div>

              <div class="col-lg-6 col-md-6 mb-4">
                <div class="course-card">
                  <div class="course-image">
                    <img
                      src="assets/img/education/courses-9.webp"
                      alt="Course"
                      class="img-fluid" />
                    <div class="course-badge">Popular</div>
                    <div class="course-price">Rp. 1.200.000,-</div>
                  </div>
                  <div class="course-content">
                    <div class="course-meta">
                      Tersedia di:
                      <span class="category">Malang</span>
                      <span class="category">Jogja</span>
                    </div>
                    <h3>[EQIYU x MBOWIS] Pengusaha Siap Buka Usaha</h3>
                    <div class="course-stats">
                      <div class="stat">
                        <i class="bi bi-clock"></i>
                        <span>3 hari</span>
                      </div>
                      <div class="stat">
                        <i class="bi bi-people"></i>
                        <span>Kursus Offline</span>
                      </div>
                    </div>
                    <a href="course-details.php" class="btn-course">Lihat Detail</a>
                  </div>
                </div>
                <!-- End Course Card -->
              </div>

              <div class="col-lg-6 col-md-6 mb-4">
                <div class="course-card">
                  <div class="course-image">
                    <img
                      src="assets/img/education/courses-14.webp"
                      alt="Course"
                      class="img-fluid" />
                    <div class="course-badge badge-certificate">
                      Certificate
                    </div>
                    <div class="course-price">Rp. 1.200.000,-</div>
                  </div>
                  <div class="course-content">
                    <div class="course-meta">
                      Tersedia di:
                      <span class="category">Se-Dunia</span>
                    </div>
                    <h3>
                      Tahapan Membuat Bisnis Cafe, Resto, Coffeeshop &
                      Bisnis Kuliner
                    </h3>
                    <div class="course-stats">
                      <div class="stat">
                        <i class="bi bi-clock"></i>
                        <span>3 hari</span>
                      </div>
                      <div class="stat">
                        <i class="bi bi-people"></i>
                        <span>Kursus Online</span>
                      </div>
                    </div>
                    <a href="course-details.php" class="btn-course">Lihat Detail</a>
                  </div>
                </div>
                <!-- End Course Card -->
              </div>
            </div>
          </div>
          <!-- End Courses Grid -->

          <div
            class="pagination-wrapper"
            data-aos="fade-up"
            data-aos-delay="300">
            <nav aria-label="Courses pagination">
              <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                  <a
                    class="page-link"
                    href="#"
                    tabindex="-1"
                    aria-disabled="true">
                    <i class="bi bi-chevron-left"></i>
                  </a>
                </li>
                <li class="page-item active">
                  <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="#">3</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="#">
                    <i class="bi bi-chevron-right"></i>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
          <!-- End Pagination -->
        </div>
      </div>
    </div>
  </section>
  <!-- /Courses 2 Section -->
</main>
<script>
  (function() {
    const API_URL = '<?= base_url('api/kursus') ?>';
    const gridEl = document.querySelector('.courses-grid');
    const listEl = document.querySelector('.courses-grid .row');
    const paginationEl = document.querySelector('.pagination-wrapper');
    const filters = document.querySelectorAll('.course-filters .filter-options input[type="checkbox"]');

    if (paginationEl) paginationEl.style.display = 'none';
    if (!gridEl || !listEl || filters.length < 4) return;

    // Create sentinel for infinite scroll
    const sentinelEl = document.createElement('div');
    sentinelEl.id = 'infinite-sentinel';
    sentinelEl.style.height = '1px';
    gridEl.appendChild(sentinelEl);

    // Filter mapping: index 0=semua, 1=offline, 2=online, 3=jasa
    const filterSemua = filters[0];
    const filterOffline = filters[1];
    const filterOnline = filters[2];
    const filterJasa = filters[3];

    let offset = 0;
    const limit = 6;
    let loading = false;
    let hasMore = true;
    let currentType = 'semua'; // offline|online|semua
    let currentCategory = 'semua'; // kursus|jasa|semua

    function resetList() {
      offset = 0;
      hasMore = true;
      listEl.innerHTML = '';
    }

    function formatIDR(n) {
      if (n == null) return '';
      const s = new Intl.NumberFormat('id-ID').format(n);
      return 'Rp. ' + s + ',-';
    }

    function createBadge(item) {
      if (!item.badge_text) return '';
      let cls = 'course-badge';
      if (item.badge_type === 'free') cls = 'course-badge badge-free';
      if (item.badge_type === 'new') cls = 'course-badge badge-new';
      if (item.badge_type === 'certificate') cls = 'course-badge badge-certificate';
      return '<div class="' + cls + '">' + item.badge_text + '</div>';
    }

    function renderItem(item) {
      const metaCities = (item.kota || []).map(n => '<span class="category">' + n + '</span>').join(' ');
      const priceHtml = item.harga ? '<div class="course-price">' + formatIDR(item.harga) + '</div>' : '';
      const typeLabel = item.is_online ? 'Kursus Online' : 'Kursus Offline';
      const detailUrl = item.detail_url || '#';
      const html = `
        <div class="col-lg-6 col-md-6 mb-4">
          <div class="course-card">
            <div class="course-image">
              <img src="${item.image_url || item.image_path || ''}" alt="Course" class="img-fluid" />
              ${createBadge(item)}
              ${priceHtml}
            </div>
            <div class="course-content">
              <div class="course-meta">
                Tersedia di: ${metaCities}
              </div>
              <h3>${item.nama_kelas}</h3>
              <div class="course-stats">
                <div class="stat">
                  <i class="bi bi-clock"></i>
                  <span>${item.durasi || ''}</span>
                </div>
                <div class="stat">
                  <i class="bi bi-people"></i>
                  <span>${typeLabel}</span>
                </div>
              </div>
              <a href="${detailUrl}" class="btn-course">Lihat Detail</a>
            </div>
          </div>
        </div>`;
      const div = document.createElement('div');
      div.innerHTML = html;
      listEl.appendChild(div.firstElementChild);
    }

    async function loadMore() {
      if (loading || !hasMore) return;
      loading = true;
      try {
        const params = new URLSearchParams({ offset: String(offset), limit: String(limit), type: currentType, category: currentCategory });
        const res = await fetch(API_URL + '?' + params.toString(), { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('Gagal mengambil data');
        const json = await res.json();
        (json.items || []).forEach(renderItem);
        offset = json.next_offset || (offset + (json.items || []).length);
        hasMore = !!json.has_more;
      } catch (e) {
        console.error(e);
      } finally {
        loading = false;
      }
    }

    function applyFilterState() {
      const anyActive = filterOffline.checked || filterOnline.checked || filterJasa.checked;
      filterSemua.checked = !anyActive;
      if (filterJasa.checked) {
        currentCategory = 'jasa';
        currentType = 'semua';
      } else {
        currentCategory = anyActive ? 'kursus' : 'semua';
        if (filterOffline.checked && !filterOnline.checked) currentType = 'offline';
        else if (!filterOffline.checked && filterOnline.checked) currentType = 'online';
        else currentType = 'semua';
      }
      resetList();
      loadMore();
    }

    [filterSemua, filterOffline, filterOnline, filterJasa].forEach(el => {
      el.addEventListener('change', () => {
        if (el === filterSemua && filterSemua.checked) {
          filterOffline.checked = false;
          filterOnline.checked = false;
          filterJasa.checked = false;
        }
        if (el !== filterSemua && el.checked) {
          filterSemua.checked = false;
          if (el === filterJasa) {
            filterOffline.checked = false;
            filterOnline.checked = false;
          } else {
            filterJasa.checked = false;
          }
        }
        applyFilterState();
      });
    });

    const io = new IntersectionObserver(entries => {
      entries.forEach(entry => { if (entry.isIntersecting) loadMore(); });
    });
    io.observe(sentinelEl);

    // Clear static sample cards and load initial
    resetList();
    loadMore();
  })();
</script>
<?= $this->endSection() ?>
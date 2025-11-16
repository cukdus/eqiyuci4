<?php
$this->setVar('pageTitle', 'Artikel & Informasi | Eqiyu Indonesia | Kursus Barista, Mixology, Tea & Tea Blending, Roastery, Pelatihan & Konsultan Membangun Bisnis Caffe & Coffeshop.');
$this->setVar('metaDescription', 'kursus dan pelatihan Barista, Mixology, Tea & Tea Blending, Roastery, serta pelatihan dan konsultasi untuk membangun bisnis Cafe & Coffeeshop di Malang dan Jogja.');
$this->setVar('metaKeywords', 'kursus barista, kursus barista malang, kursus barista jogja, pelatihan barista, sekolah kopi, bisnis cafe, kursus mixology, tea blending, roastery, konsultan cafe, pelatihan bisnis kuliner, eqiyu indonesia');
$this->setVar('canonicalUrl', base_url());
$this->setVar('bodyClass', 'index-page');
$this->setVar('activePage', 'info');

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<?= $this->extend('layout/main_home') ?>
<?= $this->section('content') ?>

<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div
      class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Informasi & Artikel</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?= base_url() ?>">Beranda</a></li>
          <li class="current">Info</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <?php if (!empty($currentTag)): ?>
    <div class="container" data-aos="fade-up" data-aos-delay="50">
      <p class="text-black-50 small mb-3">
        Menampilkan artikel dengan tag:
        <span class="badge bg-secondary text-light"><?= esc(ucwords($currentTag)) ?></span>
        <a href="<?= base_url('info') ?>" class="ms-2">Reset</a>
      </p>
    </div>
  <?php endif; ?>

  <!-- Blog Posts Section -->
  <section id="blog-posts" class="blog-posts section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4" id="postsList">
        <?php if (!empty($berita) && is_array($berita)): ?>
          <?php
          $bulanNama = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
          ];
          ?>
          <?php foreach ($berita as $item): ?>
            <?php
            $tanggal = $item['tanggal_terbit'] ?? date('Y-m-d');
            $day = date('d', strtotime($tanggal));
            $monthNum = date('m', strtotime($tanggal));
            $monthName = $bulanNama[$monthNum] ?? date('F', strtotime($tanggal));
            $penulis = $item['penulis'] ?? 'Anonim';
            $kategori = $item['kategori_nama'] ?? 'Uncategorized';
            $judul = $item['judul'] ?? '';
            $slug = $item['slug'] ?? '';
            $img = !empty($item['gambar_utama']) ? base_url($item['gambar_utama']) : base_url('assets/img/blog/blog-post-1.webp');
            $detailUrl = !empty($slug) ? base_url('info/' . $slug) : base_url('info');
            ?>
            <div class="col-lg-4">
              <article class="position-relative h-100">
                <div class="post-img position-relative overflow-hidden">
                  <img src="<?= esc($img) ?>" class="img-fluid" alt="<?= esc($judul) ?>" />
                </div>

                <div class="meta d-flex align-items-end">
                  <span class="post-date"><span><?= esc($day) ?></span><?= esc($monthName) ?></span>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-person"></i>
                    <span class="ps-2"><?= esc($penulis) ?></span>
                  </div>
                  <span class="px-3 text-black-50">/</span>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-folder2"></i>
                    <span class="ps-2"><?= esc($kategori) ?></span>
                  </div>
                </div>

                <div class="post-content d-flex flex-column">
                  <h3 class="post-title"><?= esc($judul) ?></h3>
                  <a href="<?= esc($detailUrl) ?>" class="readmore stretched-link"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
                </div>
              </article>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12">
            <p class="text-center text-muted">
              <?= !empty($currentTag) ? 'Tidak ada artikel untuk tag ini.' : 'Belum ada berita.' ?>
            </p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <!-- /Blog Posts Section -->

  <div id="postsLoading" class="text-center text-muted my-3" style="display:none">Memuat...</div>
  <div id="postsSentinel" style="height:1px"></div>
</main>
<script>
(function(){
  const API = '<?= base_url('api/info') ?>';
  const list = document.getElementById('postsList');
  const sentinel = document.getElementById('postsSentinel');
  const loadingEl = document.getElementById('postsLoading');
  let page = 1; let loading = false; const limit = 9; const tag = '<?= esc($currentTag ?? '') ?>';
  function fmtDate(dStr){ const d=new Date(dStr); if(isNaN(d.getTime())) return {day:'',month:''}; const day=String(d.getDate()).padStart(2,'0'); const m=d.getMonth()+1; const months=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; return {day,month:months[m-1]}; }
  function cardHtml(item){ const t=item.tanggal_terbit||''; const dm=fmtDate(t); const penulis=item.penulis||'Anonim'; const kategori=item.kategori_nama||'Uncategorized'; const judul=item.judul||''; const slug=item.slug||''; const img=(item.gambar_utama? '<?= base_url('') ?>'+item.gambar_utama : '<?= base_url('assets/img/blog/blog-post-1.webp') ?>'); const detail='<?= base_url('info') ?>/'+encodeURIComponent(slug); return `<div class="col-lg-4"><article class="position-relative h-100"><div class="post-img position-relative overflow-hidden"><img src="${img}" class="img-fluid" alt="${judul}" /></div><div class="meta d-flex align-items-end"><span class="post-date"><span>${dm.day}</span>${dm.month}</span><div class="d-flex align-items-center"><i class="bi bi-person"></i><span class="ps-2">${penulis}</span></div><span class="px-3 text-black-50">/</span><div class="d-flex align-items-center"><i class="bi bi-folder2"></i><span class="ps-2">${kategori}</span></div></div><div class="post-content d-flex flex-column"><h3 class="post-title">${judul}</h3><a href="${detail}" class="readmore stretched-link"><span>Read More</span><i class="bi bi-arrow-right"></i></a></div></article></div>`; }
  async function loadNext(){ if(loading) return; loading=true; if(loadingEl) loadingEl.style.display='block'; try{ const usp=new URLSearchParams({ page: page+1, limit, tag }); const res=await fetch(API+'?'+usp.toString(),{ headers:{ 'Accept':'application/json' }}); const json=await res.json(); const rows=Array.isArray(json.data)? json.data:[]; if(rows.length){ page++; rows.forEach(r=>{ const w=document.createElement('div'); w.innerHTML=cardHtml(r); list.appendChild(w.firstElementChild); }); }
  }catch(e){} finally{ loading=false; if(loadingEl) loadingEl.style.display='none'; }}
  const io=new IntersectionObserver((entries)=>{ for(const en of entries){ if(en.isIntersecting && !loading){ loadNext(); } } },{ root:null, rootMargin:'200px', threshold:0 }); io.observe(sentinel);
})();
</script>
<?= $this->endSection() ?>
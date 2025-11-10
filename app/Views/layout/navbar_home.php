<?php
// Ambil nav items dari helper terpusat; izinkan override jika $navItems disuplai dari view/controller
if (empty($navItems) || !is_array($navItems)) {
  $navItems = function_exists('get_nav_items') ? get_nav_items() : [];
}

// Logika untuk menentukan slug aktif berdasarkan URL
$currentSlug = '';
if (!isset($activePage)) {
  $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
  // Hilangkan prefix path base_url (misal: /eqiyuci4/public) agar segmen pertama adalah slug halaman
  $basePath = '/' . trim(parse_url(base_url(), PHP_URL_PATH) ?? '', '/');
  if ($basePath !== '/' && strpos($currentPath, $basePath) === 0) {
    $currentPath = substr($currentPath, strlen($basePath));
  }
  $segments = explode('/', trim($currentPath, '/'));
  $currentSlug = $segments[0] ?? 'index';
  if ($currentSlug === '')
    $currentSlug = 'index';  // Untuk root path
}
?>
<div class="container-fluid container-xl position-relative d-flex align-items-center">
  <a href="<?= base_url() ?>" class="logo d-flex align-items-center me-auto">
    <img src="assets/img/logo.webp" alt="logo Eqiyu Indonesia" />
  </a>

  <nav id="navmenu" class="navmenu">
    <ul>
      <?php if (!empty($navItems) && is_array($navItems)): ?>
        <?php foreach ($navItems as $item): ?>
          <?php
          $slug = $item['slug'] ?? '';
          // Prioritaskan $activePage jika ada, jika tidak, bandingkan dengan $currentSlug dari URL
          $isActive = isset($activePage) ? ($activePage === $slug) : ($currentSlug === $slug);
          ?>
          <li>
            <a href="<?= esc($item['path'] ?? '#') ?>"<?= $isActive ? ' class="active"' : '' ?>><?= esc($item['label'] ?? '') ?></a>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>
    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
  </nav>

  <a class="btn-getstarted" href="kursus">Semua Kursus</a>
</div>
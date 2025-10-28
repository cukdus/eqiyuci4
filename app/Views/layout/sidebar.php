<?php
$uri = service('uri');
$segments = $uri->getSegments();
$seg2 = $segments[1] ?? null;  // setelah 'admin'
$seg3 = $segments[2] ?? null;  // segmen ketiga

$isAdminRoot = ($seg2 === null || $seg2 === '' || $seg2 === 'dashboard');

// Artikel
$isArtikel = ($seg2 === 'artikel');
$isDataArtikel = ($isArtikel && $seg3 === null);
$isKategoriArtikel = ($isArtikel && $seg3 === 'kategori');

// Kelas
$isKelas = ($seg2 === 'kelas');
$isKelasOnline = ($isKelas && $seg3 === null);
$isKelasOffline = ($isKelas && $seg3 === 'offline');
$isBonusKelas = ($isKelas && $seg3 === 'bonus');
$isVoucherKelas = ($isKelas && $seg3 === 'voucher');

// Jadwal
$isJadwal = ($seg2 === 'jadwal');
$isJadwalKelas = ($isJadwal && $seg3 === null);
$isJadwalSiswa = ($isJadwal && $seg3 === 'siswa');

// Single items
$isRegistrasi = ($seg2 === 'registrasi');
$isSertifikat = ($seg2 === 'sertifikat');
$isSetting = ($seg2 === 'setting');
// Submenu Setting
$isSettingRoot = ($isSetting && ($seg3 === null));
$isWaha = ($isSetting && ($seg3 === 'waha'));
// Role-based visibility
$me = service('authentication')->user();
$authz = service('authorization');
$isAdmin = $me ? $authz->inGroup('admin', $me->id) : false;
?>

<aside class="app-sidebar bg-dark" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="<?= base_url('admin') ?>" class="brand-link">
      <!--begin::Brand Text-->
      <span class="brand-text fw-light">Panel Eqiyu</span>
      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!--end::Sidebar Brand-->
  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <!--begin::Sidebar Menu-->
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="navigation"
        aria-label="Main navigation"
        data-accordion="false"
        id="navigation"
      >
        <li class="nav-item">
          <a href="<?= base_url('admin') ?>" class="nav-link <?= $isAdminRoot ? 'active' : '' ?>">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-header">Artikel/Info</li>
        <li class="nav-item <?= $isArtikel ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= $isArtikel ? 'active' : '' ?>">
            <i class="nav-icon fas fa-newspaper"></i>
            <p>
              Manage Artikel
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('admin/artikel') ?>" class="nav-link <?= $isDataArtikel ? 'active' : '' ?>">
                <i class="nav-icon bi bi-file-earmark-check"></i>
                <p><small>Data Artikel</small></p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/artikel/kategori') ?>" class="nav-link <?= $isKategoriArtikel ? 'active' : '' ?>">
                <i class="nav-icon bi bi-journal-bookmark-fill"></i>
                <p><small>Kategori Artikel</small></p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header">Kelas</li>
        <li class="nav-item <?= $isKelas ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= $isKelas ? 'active' : '' ?>">
            <i class="nav-icon bi bi-box-seam-fill"></i>
            <p>
              Manage Kelas
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('admin/kelas') ?>" class="nav-link <?= $isKelasOnline ? 'active' : '' ?>">
                <i class="nav-icon fa fa-users-between-lines"></i>
                <p><small>Data Kelas</small></p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/kelas/bonus') ?>" class="nav-link <?= $isBonusKelas ? 'active' : '' ?>">
                <i class="nav-icon fa fa-gift"></i>
                <p><small>Bonus Kelas</small></p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/kelas/voucher') ?>" class="nav-link <?= $isVoucherKelas ? 'active' : '' ?>">
                <i class="nav-icon fa fa-ticket"></i>
                <p><small>Voucher Kelas</small></p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header">Jadwal</li>
        <li class="nav-item <?= $isJadwal ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= $isJadwal ? 'active' : '' ?>">
            <i class="nav-icon fas fa-calendar-days"></i>
            <p>
              Manage Jadwal
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('admin/jadwal') ?>" class="nav-link <?= $isJadwalKelas ? 'active' : '' ?>">
                <i class="nav-icon far fa-calendar"></i>
                <p><small>Jadwal Kelas</small></p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/jadwal/siswa') ?>" class="nav-link <?= $isJadwalSiswa ? 'active' : '' ?>">
                <i class="nav-icon fas fa-book-open-reader"></i>
                <p><small>Jadwal Siswa</small></p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header">Registrasi</li>
        <li class="nav-item">
          <a href="<?= base_url('admin/registrasi') ?>" class="nav-link <?= $isRegistrasi ? 'active' : '' ?>">
            <i class="nav-icon fas fa-address-card"></i>
            <p>Data Registrasi</p>
          </a>
        </li>
        <li class="nav-header">Sertifikat</li>
        <li class="nav-item">
          <a href="<?= base_url('admin/sertifikat') ?>" class="nav-link <?= $isSertifikat ? 'active' : '' ?>">
            <i class="nav-icon fas fa-graduation-cap"></i>
            <p>Data Sertifikat</p>
          </a>
        </li>
        <li class="nav-header">Setting</li>
        <li class="nav-item <?= $isSetting ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= $isSetting ? 'active' : '' ?>">
            <i class="nav-icon fas fa-gear"></i>
            <p>
              Setting
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('admin/setting') ?>" class="nav-link <?= $isSettingRoot ? 'active' : '' ?>">
                <i class="nav-icon fas fa-user"></i>
                <p><small>Users</small></p>
              </a>
            </li>
            <?php if ($isAdmin): ?>
            <li class="nav-item">
              <a href="<?= base_url('admin/setting/waha') ?>" class="nav-link <?= $isWaha ? 'active' : '' ?>">
                <i class="nav-icon bi bi-whatsapp"></i>
                <p><small>WA-API</small></p>
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </li>
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
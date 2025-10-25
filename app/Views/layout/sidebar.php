<aside class="app-sidebar bg-dark" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="./index.html" class="brand-link">
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
          <a href="<?= base_url('admin') ?>" class="nav-link">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-header">Artikel/Info</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-box-seam-fill"></i>
            <p>
              Manage Artikel
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('admin/artikel') ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Artikel</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/artikel/tambah') ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Tambah Artikel</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/artikel/kategori') ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kategori Artikel</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header">Kelas</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-box-seam-fill"></i>
            <p>
              Manage Kelas
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('admin/kelas') ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kelas Online</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/kelas/offline') ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kelas Offline</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/kelas/bonus') ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Bonus Kelas</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/kelas/voucher') ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Voucher Kelas</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header">Jadwal</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-clipboard-fill"></i>
            <p>
              Manage Jadwal
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('admin/jadwal') ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Jadwal Kelas</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/jadwal/siswa') ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Jadwal Siswa</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header">Registrasi</li>
        <li class="nav-item">
          <a href="<?= base_url('admin/registrasi') ?>" class="nav-link">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Data Registrasi</p>
          </a>
        </li>
        <li class="nav-header">Sertifikat</li>
        <li class="nav-item">
          <a href="<?= base_url('admin/sertifikat') ?>" class="nav-link">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Data Sertifikat</p>
          </a>
        </li>
        <li class="nav-header">Setting</li>
        <li class="nav-item">
          <a href="<?= base_url('admin/setting') ?>" class="nav-link">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Setting</p>
          </a>
        </li>
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
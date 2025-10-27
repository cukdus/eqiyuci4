<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
        <i class="bi bi-list"></i>
        </a>
    </li>
</ul>
<!--end::Start Navbar Links-->
<!--begin::End Navbar Links-->
<ul class="navbar-nav ms-auto">
    <!--begin::Fullscreen Toggle-->
    <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
        <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
        <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
        </a>
    </li>
    <!--end::Fullscreen Toggle-->
    <!--begin::User Menu Dropdown-->
    <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            <?php $u = service('authentication')->user();
            $photo = $u ? ($u->photo ?? null) : null; ?>
            <img src="<?= esc(base_url($photo ?: 'assets/img/user2-160x160.jpg')) ?>" class="user-image rounded-circle" alt="User Image">
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">

        <!--begin::Menu Footer-->
        <li class="user-footer">
            <a href="<?= site_url('admin/setting/users/' . $u->id . '/edit') ?>" class="btn btn-success btn-flat"><?= esc($u ? ($u->nama_lengkap ?? 'User') : 'User') ?></a>
            <a href="<?= url_to('logout') ?>" class="btn btn-warning btn-flat float-end"><i class="bi bi-box-arrow-right"></i> Sign out</a>
        </li>
        <!--end::Menu Footer-->
        </ul>
    </li>
<!--end::User Menu Dropdown-->
</ul>
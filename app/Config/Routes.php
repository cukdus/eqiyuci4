<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Auth routes
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

// Admin routes
$routes->group('admin', ['filter' => ['login', 'idle']], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Sidebar: Artikel/Info
    $routes->get('artikel', 'Admin\\Artikel::index');
    $routes->get('artikel/tambah', 'Admin\\Artikel::create');
    $routes->post('artikel/tambah', 'Admin\\Artikel::store');
    // Artikel: Edit & Update
    $routes->get('artikel/(:num)/edit', 'Admin\\Artikel::edit/$1');
    $routes->post('artikel/(:num)/update', 'Admin\\Artikel::update/$1');
    // Artikel: Summernote Image Upload
    $routes->post('artikel/upload-image', 'Admin\\Artikel::uploadImage');
    // Artikel: Kategori
    $routes->get('artikel/kategori', 'Admin\\KategoriArtikel::index');
    $routes->post('artikel/kategori/store', 'Admin\\KategoriArtikel::store');
    $routes->get('artikel/kategori/(:num)/edit', 'Admin\\KategoriArtikel::edit/$1');
    $routes->post('artikel/kategori/(:num)/update', 'Admin\\KategoriArtikel::update/$1');
    $routes->post('artikel/kategori/(:num)/delete', 'Admin\\KategoriArtikel::delete/$1');

    // Artikel actions
    $routes->post('artikel/(:num)/duplicate', 'Admin\\Artikel::duplicate/$1');
    $routes->post('artikel/(:num)/delete', 'Admin\\Artikel::delete/$1');

    // Sidebar: Kelas
    $routes->get('kelas', 'Admin\\Kelas::online');
    // Akses khusus modul online
    $routes->get('modulonline', 'Admin\\Kelas::online');
    $routes->get('kelas/tambah', 'Admin\\Kelas::create');
    $routes->post('kelas/tambah', 'Admin\\Kelas::store');
    $routes->get('kelas/(:num)/edit', 'Admin\\Kelas::edit/$1');
    $routes->post('kelas/(:num)/image/delete', 'Admin\\Kelas::deleteImage/$1');
    $routes->get('kelas/(:num)/image/delete/(:num)', 'Admin\\Kelas::deleteImageByIndex/$1/$2');
    $routes->post('kelas/(:num)/update', 'Admin\\Kelas::update/$1');
    $routes->post('kelas/(:num)/delete', 'Admin\\Kelas::delete/$1');
    $routes->get('kelas/bonus', static function () {
        return view('layout/admin_layout', [
            'title' => 'Bonus Kelas',
            'content' => view('admin/kelas/bonuskelas')
        ]);
    });
    $routes->get('kelas/voucher', 'Admin\\Voucher::index');
    $routes->post('kelas/voucher/store', 'Admin\\Voucher::store');
    $routes->post('kelas/voucher/(:num)/delete', 'Admin\\Voucher::delete/$1');

    // Sidebar: Jadwal
        $routes->get('jadwal', 'Admin\\Jadwal::index');
        // API: list schedules by kode_kelas
        $routes->get('jadwal/by-kode', 'Admin\\Jadwal::forKelas');
        $routes->post('jadwal/store', 'Admin\\Jadwal::store');
        $routes->post('jadwal/update', 'Admin\\Jadwal::update');
        $routes->get('jadwal/delete/(:num)', 'Admin\\Jadwal::delete/$1');

    // Jadwal Siswa page + JSON endpoint
    $routes->get('jadwal/siswa', 'Admin\\Jadwal::siswa');
    $routes->get('jadwal/siswa.json', 'Admin\\Jadwal::siswaJson');
    $routes->get('jadwal/siswa/available.json', 'Admin\\Jadwal::availableSchedulesJson');

    // Reschedule registrasi
    $routes->post('registrasi/reschedule', 'Admin\\Registrasi::reschedule');

    // Sidebar: Registrasi
    $routes->get('registrasi', 'Admin\\Registrasi::index');
    // JSON endpoint for Data Registrasi (search/sort/pagination)
    $routes->get('registrasi.json', 'Admin\\Registrasi::listJson');
    $routes->get('registrasi/tambah', 'Admin\\Registrasi::tambah');
    $routes->post('registrasi/tambah', 'Admin\\Registrasi::store');
    $routes->get('registrasi/(:num)/edit', 'Admin\\Registrasi::edit/$1');
    $routes->post('registrasi/(:num)/update', 'Admin\\Registrasi::update/$1');
    $routes->post('registrasi/(:num)/delete', 'Admin\\Registrasi::delete/$1');
    $routes->post('registrasi/(:num)/toggle-akses', 'Admin\\Registrasi::toggleAkses/$1');
    $routes->post('registrasi/check-voucher', 'Admin\\Registrasi::checkVoucher');

    // Sidebar: Sertifikat
    $routes->get('sertifikat', static function () {
        return view('layout/admin_layout', [
            'title' => 'Data Sertifikat',
            'content' => view('admin/sertifikat/datasertifikat')
        ]);
    });

    // Sidebar: Setting
    $routes->get('setting', 'Admin\Setting::index');
    $routes->get('setting/waha', 'Admin\Setting::waha');
    $routes->get('setting/users/create', 'Admin\Setting::create');
    $routes->post('setting/users/store', 'Admin\Setting::store');
    $routes->get('setting/users/(:num)', 'Admin\Setting::show/$1');
    $routes->get('setting/users/(:num)/edit', 'Admin\Setting::edit/$1');
    $routes->post('setting/users/(:num)/update', 'Admin\Setting::updateUser/$1');
    $routes->post('setting/users/(:num)/delete', 'Admin\Setting::delete/$1');
    $routes->get('setting/edit', static function () {
        return view('layout/admin_layout', [
            'title' => 'Edit Profile',
            'content' => view('admin/setting/editprofile')
        ]);
    });
    $routes->post('setting/update', 'Admin\Setting::update');
});

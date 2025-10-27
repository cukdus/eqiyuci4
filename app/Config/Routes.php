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
    $routes->get('kelas', static function () {
        return view('layout/admin_layout', [
            'title' => 'Kelas Online',
            'content' => view('admin/kelas/kelasonline')
        ]);
    });
    $routes->get('kelas/offline', static function () {
        return view('layout/admin_layout', [
            'title' => 'Kelas Offline',
            'content' => view('admin/kelas/kelasoffline')
        ]);
    });
    $routes->get('kelas/bonus', static function () {
        return view('layout/admin_layout', [
            'title' => 'Bonus Kelas',
            'content' => view('admin/kelas/bonuskelas')
        ]);
    });
    $routes->get('kelas/voucher', static function () {
        return view('layout/admin_layout', [
            'title' => 'Voucher Kelas',
            'content' => view('admin/kelas/voucherkelas')
        ]);
    });

    // Sidebar: Jadwal
    $routes->get('jadwal', static function () {
        return view('layout/admin_layout', [
            'title' => 'Jadwal Kelas',
            'content' => view('admin/jadwal/jadwalkelas')
        ]);
    });
    $routes->get('jadwal/siswa', static function () {
        return view('layout/admin_layout', [
            'title' => 'Jadwal Siswa',
            'content' => view('admin/jadwal/jadwalsiswa')
        ]);
    });

    // Sidebar: Registrasi
    $routes->get('registrasi', static function () {
        return view('layout/admin_layout', [
            'title' => 'Data Registrasi',
            'content' => view('admin/registrasi/dataregistrasi')
        ]);
    });

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

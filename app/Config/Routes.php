<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('tentang', 'Home::tentang');
$routes->get('info', 'Home::info');
// Detail artikel berdasarkan slug
$routes->get('info/(:segment)', 'Home::infoDetail/$1');
// Legacy route for static template (kept for backward compatibility)
$routes->get('blog-details.php', 'Home::infoDetail');
$routes->get('kontak', 'Home::kontak');
$routes->get('jadwal', 'Home::jadwal');
$routes->match(['GET', 'POST'], 'sertifikat', 'Home::sertifikat');
// Endpoint JSON untuk cek sertifikat
$routes->get('api/sertifikat', 'Home::sertifikatJson');
// Endpoint JSON untuk bonus berdasarkan nomor sertifikat
$routes->get('api/bonus', 'Home::bonusJson');
// Endpoint JSON publik untuk daftar kursus (filter + infinite scroll)
$routes->get('api/kursus', 'Home::kursusJson');
// Endpoint publik untuk download sertifikat berdasarkan nomor
$routes->get('lihatsertifikat', 'Home::lihatsertifikat');
$routes->match(['GET', 'POST'], 'bonus', 'Home::bonus');
$routes->get('kursus', 'Home::kursus');
// Halaman detail kursus berdasarkan slug
$routes->get('kursus/(:segment)', 'Home::kursusDetail/$1');
$routes->get('daftar', 'Home::daftar');
// Public API: list schedules by kode_kelas (for daftar page)
$routes->get('api/jadwal/by-kode', 'Home::jadwalByKode');
// Public API: list schedules per month (filters: month, year, kelas, lokasi)
$routes->get('api/jadwal', 'Home::jadwalJson');
// Public API: upcoming schedules
$routes->get('api/jadwal/upcoming', 'Home::jadwalUpcomingJson');
// Public API: check voucher validity and metadata
$routes->match(['GET', 'POST'], 'api/voucher/check', 'Home::voucherCheck');

// Kelas Online: login bridge and content page
$routes->get('loginkelas', 'Home::loginkelas');
$routes->get('kelasonline', 'Home::kelasonline');
$routes->get('kelasonline/logout', 'Home::kelasonlineLogout');
// Kelas Online: JSON endpoints for login and content
$routes->get('api/kelasonline/login', 'Home::kelasonlineLoginJson');
$routes->get('api/kelasonline/modules', 'Home::kelasonlineModulesJson');

// Auth routes
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

// Admin routes
$routes->group('admin', ['filter' => ['login', 'idle']], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Sidebar: Artikel/Info
    $routes->get('artikel', 'Admin\Artikel::index');
    // Artikel: JSON list endpoint
    $routes->get('artikel.json', 'Admin\Artikel::listJson');
    // Artikel: Tags JSON (untuk auto-complete input tag)
    $routes->get('artikel/tags.json', 'Admin\Artikel::tagsJson');
    $routes->get('artikel/tambah', 'Admin\Artikel::create');
    $routes->post('artikel/tambah', 'Admin\Artikel::store');
    // Artikel: Edit & Update
    $routes->get('artikel/(:num)/edit', 'Admin\Artikel::edit/$1');
    $routes->post('artikel/(:num)/update', 'Admin\Artikel::update/$1');
    // Artikel: Summernote Image Upload
    $routes->post('artikel/upload-image', 'Admin\Artikel::uploadImage');
    // Artikel: Kategori
    $routes->get('artikel/kategori', 'Admin\KategoriArtikel::index');
    $routes->post('artikel/kategori/store', 'Admin\KategoriArtikel::store');
    $routes->get('artikel/kategori/(:num)/edit', 'Admin\KategoriArtikel::edit/$1');
    $routes->post('artikel/kategori/(:num)/update', 'Admin\KategoriArtikel::update/$1');
    $routes->post('artikel/kategori/(:num)/delete', 'Admin\KategoriArtikel::delete/$1');

    // Artikel actions
    $routes->post('artikel/(:num)/duplicate', 'Admin\Artikel::duplicate/$1');
    $routes->post('artikel/(:num)/delete', 'Admin\Artikel::delete/$1');

    // Sidebar: Kelas
    $routes->get('kelas', 'Admin\Kelas::online');
    // Akses khusus modul online
    $routes->get('modulonline', 'Admin\Kelas::online');
    // Modul Online: JSON & Actions
    $routes->get('modulonline.json', 'Admin\ModulOnline::list');
    $routes->post('modulonline/store', 'Admin\ModulOnline::store');
    $routes->post('modulonline/(:num)/update', 'Admin\ModulOnline::update/$1');
    $routes->post('modulonline/(:num)/delete', 'Admin\ModulOnline::delete/$1');
    $routes->get('modulonline/files.json', 'Admin\ModulOnline::files');
    $routes->post('modulonline/(:num)/file/upload', 'Admin\ModulOnline::uploadFile/$1');
    $routes->post('modulonline/file/(:num)/delete', 'Admin\ModulOnline::deleteFile/$1');
    $routes->post('modulonline/file/(:num)/update', 'Admin\ModulOnline::updateFile/$1');
    // Bonus Kelas Offline: Page & JSON
    $routes->get('bonuskelas', 'Admin\BonusKelas::index');
    $routes->get('bonuskelas.json', 'Admin\BonusKelas::list');
    $routes->post('bonuskelas/(:num)/file/upload', 'Admin\BonusKelas::uploadFile/$1');
    $routes->post('bonuskelas/file/(:num)/delete', 'Admin\BonusKelas::deleteFile/$1');
    $routes->get('kelas/tambah', 'Admin\Kelas::create');
    $routes->post('kelas/tambah', 'Admin\Kelas::store');
    $routes->get('kelas/(:num)/edit', 'Admin\Kelas::edit/$1');
    $routes->post('kelas/(:num)/image/delete', 'Admin\Kelas::deleteImage/$1');
    $routes->get('kelas/(:num)/image/delete/(:num)', 'Admin\Kelas::deleteImageByIndex/$1/$2');
    $routes->post('kelas/(:num)/update', 'Admin\Kelas::update/$1');
    $routes->post('kelas/(:num)/delete', 'Admin\Kelas::delete/$1');
    // Bonus Kelas Offline page (use controller to provide kelas list)
    $routes->get('kelas/bonus', 'Admin\BonusKelas::index');
    // Bonus Kelas: JSON list per kelas dan semua file lintas kelas
    $routes->get('bonuskelas.json', 'Admin\BonusKelas::list');
    $routes->get('bonuskelas/files.json', 'Admin\BonusKelas::listAll');
    $routes->post('bonuskelas/(:num)/file/upload', 'Admin\BonusKelas::uploadFile/$1');
    $routes->post('bonuskelas/(:num)/file/attach', 'Admin\BonusKelas::attachExisting/$1');
    $routes->post('bonuskelas/file/(:num)/delete', 'Admin\BonusKelas::deleteFile/$1');
    $routes->get('kelas/voucher', 'Admin\Voucher::index');
    $routes->post('kelas/voucher/store', 'Admin\Voucher::store');
    $routes->post('kelas/voucher/(:num)/delete', 'Admin\Voucher::delete/$1');

    // Kota Kelas: halaman dan aksi
    $routes->get('kelas/kota', 'Admin\Kelas::kota');
    $routes->post('kelas/kota/store', 'Admin\Kelas::storeKota');
    $routes->post('kelas/kota/(:num)/delete', 'Admin\Kelas::deleteKota/$1');

    // Sidebar: Jadwal
    $routes->get('jadwal', 'Admin\Jadwal::index');
    // API: list schedules by kode_kelas
    $routes->get('jadwal/by-kode', 'Admin\Jadwal::forKelas');
    $routes->post('jadwal/store', 'Admin\Jadwal::store');
    $routes->post('jadwal/update', 'Admin\Jadwal::update');
    $routes->get('jadwal/delete/(:num)', 'Admin\Jadwal::delete/$1');

    // Jadwal Siswa page + JSON endpoint
    $routes->get('jadwal/siswa', 'Admin\Jadwal::siswa');
    $routes->get('jadwal/siswa.json', 'Admin\Jadwal::siswaJson');
    $routes->get('jadwal/siswa/available.json', 'Admin\Jadwal::availableSchedulesJson');

    // Reschedule registrasi
    $routes->post('registrasi/reschedule', 'Admin\Registrasi::reschedule');

    // Sidebar: Registrasi
    $routes->get('registrasi', 'Admin\Registrasi::index');
    // JSON endpoint for Data Registrasi (search/sort/pagination)
    $routes->get('registrasi.json', 'Admin\Registrasi::listJson');
    $routes->get('registrasi/tambah', 'Admin\Registrasi::tambah');
    $routes->post('registrasi/tambah', 'Admin\Registrasi::store');
    $routes->get('registrasi/(:num)/edit', 'Admin\Registrasi::edit/$1');
    $routes->post('registrasi/(:num)/update', 'Admin\Registrasi::update/$1');
    $routes->post('registrasi/(:num)/delete', 'Admin\Registrasi::delete/$1');
    $routes->post('registrasi/(:num)/toggle-akses', 'Admin\Registrasi::toggleAkses/$1');
    $routes->post('registrasi/check-voucher', 'Admin\Registrasi::checkVoucher');

    // Sidebar: Sertifikat (controller-based)
    $routes->get('sertifikat', 'Admin\Sertifikat::index');
    // Sertifikat: JSON endpoints & actions
    $routes->get('sertifikat/registrasi.json', 'Admin\Sertifikat::registrationsJson');
    $routes->get('sertifikat.json', 'Admin\Sertifikat::listJson');
    // Sertifikat: View certificate page
    $routes->get('sertifikat/(:num)/view', 'Admin\Sertifikat::view/$1');
    // Sertifikat: Download certificate PDF (server-side)
    $routes->get('sertifikat/(:num)/download', 'Admin\Sertifikat::download/$1');
    $routes->post('sertifikat/generate', 'Admin\Sertifikat::generate');
    // Auto-generate H-1 sebelum kelas berakhir
    $routes->get('sertifikat/auto-generate-due', 'Admin\Sertifikat::autoGenerateDue');
    $routes->post('sertifikat/(:num)/delete', 'Admin\Sertifikat::delete/$1');

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

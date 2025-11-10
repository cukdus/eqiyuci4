<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $beritaModel = new \App\Models\Berita();
        $kategoriModel = new \App\Models\KategoriBerita();
        $kelasModel = new \App\Models\Kelas();
        $kotaKelasModel = new \App\Models\KotaKelas();
        $registrasiModel = new \App\Models\Registrasi();
        $sertfikatModel = new \App\Models\Sertifikat();

        // Ambil 3 berita terbaru yang dipublish, sudah melewati tanggal terbit, dan urutkan berdasarkan tanggal terbaru
        $now = date('Y-m-d H:i:s');
        $berita = $beritaModel
            ->where('status', 'publish')
            ->where('tanggal_terbit <=', $now)
            ->orderBy('tanggal_terbit', 'DESC')
            ->limit(3)
            ->find();

        // Tambahkan informasi kategori untuk setiap berita
        foreach ($berita as $key => $artikel) {
            if (!empty($artikel['kategori_id'])) {
                $kategori = $kategoriModel->find($artikel['kategori_id']);
                $berita[$key]['kategori_nama'] = $kategori ? $kategori['nama_kategori'] : 'Uncategorized';
            } else {
                $berita[$key]['kategori_nama'] = 'Uncategorized';
            }
        }

        // Hitung total alumni (dari tabel registrasi)
        $data['total_alumni'] = $sertfikatModel->countAllResults();

        // Hitung jumlah kelas regular (kelas offline)
        $data['jumlah_kelas'] = $kelasModel->where('kategori', 'kursus')->countAllResults();

        // Hitung jumlah kota kelas yang tersedia
        // Hitung jumlah kota dengan status aktif, kecuali nama kota "Se-Dunia"
        $data['jumlah_kota'] = $kotaKelasModel
            ->where('status', 'aktif')
            ->where('nama !=', 'Se-Dunia')
            ->countAllResults();

        // Data statistik kelas berdasarkan isi kolom nama_kelas (kode: 01=Barista, 02=Bisnis, 03=Private)
        $data['kelas_barista'] = $sertfikatModel
            ->where('status', 'aktif')
            ->where('nama_kelas', '01')
            ->countAllResults();
        $data['kelas_bisnis'] = $sertfikatModel
            ->where('status', 'aktif')
            ->where('nama_kelas', '02')
            ->countAllResults();
        $data['kelas_private'] = $sertfikatModel
            ->where('status', 'aktif')
            ->where('nama_kelas', '03')
            ->countAllResults();

        $data['berita'] = $berita;

        return view('home', $data);
    }

    public function tentang()
    {
        return view('tentang');
    }

    public function info()
    {
        $beritaModel = new \App\Models\Berita();
        $kategoriModel = new \App\Models\KategoriBerita();

        $now = date('Y-m-d H:i:s');
        $tagParamRaw = (string) ($this->request->getGet('tag') ?? '');
        $tagParam = trim(mb_strtolower($tagParamRaw));

        // Builder untuk daftar berita
        $builder = $beritaModel
            ->select('berita.*')
            ->where('status', 'publish')
            ->where('tanggal_terbit <=', $now);

        // Filter berdasarkan tag jika ada
        if ($tagParam !== '') {
            $builder = $builder
                ->join('berita_tag bt', 'bt.berita_id = berita.id', 'left')
                ->join('tag t', 't.id = bt.tag_id', 'left')
                ->where('t.nama_tag', $tagParam)
                ->groupBy('berita.id');
        }

        $berita = $builder
            ->orderBy('tanggal_terbit', 'DESC')
            ->paginate(9);

        // Tambahkan kategori_nama untuk tiap item
        foreach ($berita as $key => $artikel) {
            if (!empty($artikel['kategori_id'])) {
                $kategori = $kategoriModel->find($artikel['kategori_id']);
                $berita[$key]['kategori_nama'] = $kategori ? $kategori['nama_kategori'] : 'Uncategorized';
            } else {
                $berita[$key]['kategori_nama'] = 'Uncategorized';
            }
        }

        // Siapkan pager path agar mempertahankan query ?tag=...
        $pager = $beritaModel->pager;
        $path = site_url('info');
        if ($tagParam !== '') {
            $path .= '?tag=' . urlencode($tagParam);
        }
        $pager->setPath($path);

        $data = [
            'berita' => $berita,
            'pager' => $pager,
            'currentTag' => $tagParam,
        ];

        return view('info', $data);
    }

    public function infoDetail(string $slug = '')
    {
        $beritaModel = new \App\Models\Berita();
        $kategoriModel = new \App\Models\KategoriBerita();

        $now = date('Y-m-d H:i:s');
        $artikel = $beritaModel
            ->where('slug', $slug)
            ->where('status', 'publish')
            ->where('tanggal_terbit <=', $now)
            ->first();

        if (!$artikel) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Artikel tidak ditemukan');
        }

        // Kategori nama untuk tampilan
        $artikel['kategori_nama'] = 'Uncategorized';
        if (!empty($artikel['kategori_id'])) {
            $kategori = $kategoriModel->find($artikel['kategori_id']);
            if ($kategori) {
                $artikel['kategori_nama'] = (string) ($kategori['nama_kategori'] ?? 'Uncategorized');
            }
        }

        // URL gambar utama
        $imgUrl = !empty($artikel['gambar_utama'])
            ? base_url('uploads/artikel/' . $artikel['gambar_utama'])
            : base_url('assets/img/blog/blog-hero-1.webp');

        // Ambil tags terkait artikel dari pivot berita_tag
        $db = \Config\Database::connect();
        $rows = $db
            ->table('berita_tag bt')
            ->select('t.nama_tag')
            ->join('tag t', 't.id = bt.tag_id', 'left')
            ->where('bt.berita_id', (int) ($artikel['id'] ?? 0))
            ->get()
            ->getResultArray();
        $tags = array_values(array_filter(array_map(static function ($r) {
            return trim((string) ($r['nama_tag'] ?? ''));
        }, $rows), static function ($v) {
            return $v !== '';
        }));

        return view('info-details', [
            'artikel' => $artikel,
            'imgUrl' => $imgUrl,
            'tags' => $tags,
        ]);
    }

    public function kontak()
    {
        return view('kontak');
    }

    public function jadwal()
    {
        // Tampilkan semua kelas offline (kategori = 'Kursus') berstatus aktif
        $kelasModel = new \App\Models\Kelas();
        $kelasOptions = $kelasModel
            ->select('nama_kelas, slug, kode_kelas, kategori, status_kelas')
            ->where('kategori', 'Kursus')
            ->where('status_kelas', 'aktif')
            ->orderBy('nama_kelas', 'ASC')
            ->findAll();

        return view('jadwal', [
            'kelasOptions' => $kelasOptions,
        ]);
    }

    /**
     * Public JSON: List jadwal per bulan dengan filter.
     * GET /api/jadwal?month=MM&year=YYYY&kelas=...&lokasi=...
     * Returns: { ok, data: [ { id, kelas_id, nama_kelas, slug, tanggal_mulai, tanggal_selesai, lokasi, harga } ] }
     */
    public function jadwalJson()
    {
        $month = (int) ($this->request->getGet('month') ?? 0);
        $year = (int) ($this->request->getGet('year') ?? 0);
        $kelas = trim((string) ($this->request->getGet('kelas') ?? ''));
        $lokasi = trim(mb_strtolower((string) ($this->request->getGet('lokasi') ?? '')));

        // Default ke bulan & tahun sekarang jika tidak diisi
        if ($month < 1 || $month > 12) {
            $month = (int) date('n');
        }
        if ($year < 2000 || $year > 2100) {
            $year = (int) date('Y');
        }

        $db = \Config\Database::connect();
        $builder = $db
            ->table('jadwal_kelas jk')
            ->select('jk.id, jk.kelas_id, jk.tanggal_mulai, jk.tanggal_selesai, jk.lokasi, jk.kapasitas, k.nama_kelas, k.slug, k.harga, k.gambar_utama')
            ->join('kelas k', 'k.id = jk.kelas_id', 'left')
            ->where('MONTH(jk.tanggal_mulai)', $month)
            ->where('YEAR(jk.tanggal_mulai)', $year)
            ->orderBy('jk.tanggal_mulai', 'ASC');

        if ($kelas !== '') {
            $builder
                ->groupStart()
                ->like('k.nama_kelas', $kelas)
                ->orLike('k.slug', $kelas)
                ->groupEnd();
        }
        if ($lokasi !== '') {
            $builder->where('LOWER(jk.lokasi) =', $lokasi);
        }

        $rows = $builder->get()->getResultArray();
        return $this->response->setJSON([
            'ok' => true,
            'data' => array_map(static function ($r) {
                // Normalisasi harga ke integer jika numeric
                $harga = $r['harga'] ?? null;
                if (!is_null($harga) && is_numeric($harga)) {
                    $r['harga'] = (int) $harga;
                } else {
                    $r['harga'] = null;
                }
                return $r;
            }, $rows),
        ]);
    }

    /**
     * Public JSON: Jadwal mendatang (upcoming) global.
     * GET /api/jadwal/upcoming?limit=N&lokasi=&kelas=
     */
    public function jadwalUpcomingJson()
    {
        $limit = (int) ($this->request->getGet('limit') ?? 5);
        if ($limit < 1) {
            $limit = 5;
        }
        if ($limit > 50) {
            $limit = 50;
        }

        $kelas = trim((string) ($this->request->getGet('kelas') ?? ''));
        $lokasi = trim(mb_strtolower((string) ($this->request->getGet('lokasi') ?? '')));

        $db = \Config\Database::connect();
        $builder = $db
            ->table('jadwal_kelas jk')
            ->select('jk.id, jk.kelas_id, jk.tanggal_mulai, jk.tanggal_selesai, jk.lokasi, jk.kapasitas, k.nama_kelas, k.slug, k.harga, k.gambar_utama')
            ->join('kelas k', 'k.id = jk.kelas_id', 'left')
            ->where('jk.tanggal_selesai >= CURDATE()', null, false)
            ->orderBy('jk.tanggal_mulai', 'ASC')
            ->limit($limit);

        if ($kelas !== '') {
            $builder
                ->groupStart()
                ->like('k.nama_kelas', $kelas)
                ->orLike('k.slug', $kelas)
                ->groupEnd();
        }
        if ($lokasi !== '') {
            $builder->where('LOWER(jk.lokasi) =', $lokasi);
        }

        $rows = $builder->get()->getResultArray();
        return $this->response->setJSON([
            'ok' => true,
            'data' => array_map(static function ($r) {
                $harga = $r['harga'] ?? null;
                if (!is_null($harga) && is_numeric($harga)) {
                    $r['harga'] = (int) $harga;
                } else {
                    $r['harga'] = null;
                }
                return $r;
            }, $rows),
        ]);
    }

    public function daftar()
    {
        $kelasModel = new \App\Models\Kelas();
        $kotaModel = new \App\Models\KotaKelas();

        // Ambil daftar kelas dengan status_kelas 'aktif' saja
        $kelasList = $kelasModel
            ->select('kode_kelas, nama_kelas, kota_tersedia, harga, kategori, slug, status_kelas')
            ->where('status_kelas', 'aktif')
            ->orderBy('kode_kelas', 'ASC')
            ->findAll();

        // Pusat kota: kode->nama
        $kotaOptions = $kotaModel
            ->select('kode, nama')
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->findAll();

        // Preselect berdasarkan query param "kelas" (kode_kelas atau slug)
        $requested = trim((string) ($this->request->getGet('kelas') ?? ''));
        $selectedKode = '';
        if ($requested !== '') {
            foreach ($kelasList as $k) {
                $kode = (string) ($k['kode_kelas'] ?? '');
                $slug = (string) ($k['slug'] ?? '');
                if ($requested === $kode || $requested === $slug) {
                    $selectedKode = $kode;
                    break;
                }
            }
        }

        return view('daftar', [
            'kelasList' => $kelasList,
            'kotaOptions' => $kotaOptions,
            'selectedKode' => $selectedKode,
        ]);
    }

    /**
     * Public JSON endpoint: upcoming schedules by kode_kelas
     * Mirrors admin/Jadwal::forKelas but accessible publicly.
     * Query: ?kode_kelas=XXX
     */
    public function jadwalByKode()
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => '',
        ];

        $kode = trim((string) ($this->request->getGet('kode_kelas') ?? ''));
        if ($kode === '') {
            $response['message'] = 'Parameter kode_kelas wajib diisi';
            return $this->response->setJSON($response);
        }

        $db = \Config\Database::connect();

        // Find kelas by kode_kelas
        $kelas = $db
            ->table('kelas')
            ->select('id, nama_kelas')
            ->where('kode_kelas', $kode)
            ->get()
            ->getRowArray();

        if (!$kelas) {
            $response['message'] = 'Kelas tidak ditemukan';
            return $this->response->setJSON($response);
        }

        // Get upcoming schedules for kelas_id
        $jadwal = $db
            ->table('jadwal_kelas jk')
            ->select('jk.id, jk.tanggal_mulai, jk.tanggal_selesai, jk.lokasi, jk.instruktur, jk.kapasitas')
            ->where('jk.kelas_id', (int) $kelas['id'])
            ->where('jk.tanggal_selesai >= CURDATE()', null, false)
            ->orderBy('jk.tanggal_mulai', 'ASC')
            ->get()
            ->getResultArray();

        $response['success'] = true;
        $response['data'] = $jadwal;
        return $this->response->setJSON($response);
    }

    /**
     * Public JSON endpoint: check voucher validity and metadata
     * Query via GET or POST JSON: { kode_voucher, kode_kelas? }
     * Returns: { found, diskon_persen, voucher_kelas_kode, voucher_kelas_nama, validDate, validForClass }
     */
    public function voucherCheck()
    {
        // Read from form or JSON body (support both)
        $kode = '';
        $requestedKodeKelas = '';

        $postKode = $this->request->getPost('kode_voucher');
        if ($postKode !== null) {
            $kode = trim((string) $postKode);
        }
        $postKodeKelas = $this->request->getPost('kode_kelas');
        if ($postKodeKelas !== null) {
            $requestedKodeKelas = trim((string) $postKodeKelas);
        }

        if ($kode === '') {
            try {
                $json = $this->request->getJSON(true);
                if (is_array($json)) {
                    if (isset($json['kode_voucher'])) {
                        $kode = trim((string) $json['kode_voucher']);
                    }
                    if (isset($json['kode_kelas'])) {
                        $requestedKodeKelas = trim((string) $json['kode_kelas']);
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }

        if ($kode === '') {
            return $this->response->setJSON(['found' => false, 'message' => 'Kode voucher kosong']);
        }

        $db = \Config\Database::connect();
        $row = $db->table('voucher')->where('kode_voucher', $kode)->get()->getRowArray();
        if (!$row) {
            return $this->response->setJSON(['found' => false, 'message' => 'Kode voucher tidak ditemukan']);
        }

        $diskonPersen = (int) ($row['diskon_persen'] ?? 0);
        $kelasId = $row['kelas_id'] ?? null;
        $voucherKelasKode = null;
        $voucherKelasNama = null;
        if (!empty($kelasId)) {
            $kelasRow = $db->table('kelas')->select('kode_kelas, nama_kelas')->where('id', (int) $kelasId)->get()->getRowArray();
            $voucherKelasKode = $kelasRow['kode_kelas'] ?? null;
            $voucherKelasNama = $kelasRow['nama_kelas'] ?? null;
        }

        // Date validity
        $mulai = $row['tanggal_berlaku_mulai'] ?? null;
        $sampai = $row['tanggal_berlaku_sampai'] ?? null;
        $today = date('Y-m-d');
        $validDate = true;
        if ($mulai && $today < $mulai) {
            $validDate = false;
        }
        if ($sampai && $today > $sampai) {
            $validDate = false;
        }

        // Class validity: if voucher ties to specific class and the requested differs
        $validForClass = true;
        if ($voucherKelasKode !== null && $requestedKodeKelas !== '' && $voucherKelasKode !== $requestedKodeKelas) {
            $validForClass = false;
        }

        return $this->response->setJSON([
            'found' => true,
            'diskon_persen' => $diskonPersen,
            'voucher_kelas_kode' => $voucherKelasKode,
            'voucher_kelas_nama' => $voucherKelasNama,
            'validDate' => $validDate,
            'validForClass' => $validForClass,
        ]);
    }

    /**
     * Public submit: simpan data pendaftaran dari halaman daftar
     * Expects form-data from daftar.php. Responds JSON.
     */
    public function daftarSubmit()
    {
        $resp = function ($ok, $payload = []) {
            if ($ok) {
                return $this->response->setJSON(array_merge(['ok' => true], $payload));
            }
            return $this->response->setJSON(array_merge(['ok' => false], $payload));
        };

        $model = new \App\Models\Registrasi();
        $kelasModel = new \App\Models\Kelas();

        // Map input from public form to registrasi fields
        $kodeKelas = trim((string) ($this->request->getPost('course') ?? ''));
        $lokasi = trim((string) ($this->request->getPost('location') ?? ''));
        $jadwalIdStr = (string) ($this->request->getPost('schedule') ?? '');
        $statusPembayaran = trim((string) ($this->request->getPost('Pembayaran') ?? ''));
        $kodeVoucher = trim((string) ($this->request->getPost('kode_voucher') ?? ''));

        $nama = trim((string) ($this->request->getPost('firstName') ?? ''));
        $email = trim((string) ($this->request->getPost('email') ?? ''));
        $noTelp = trim((string) ($this->request->getPost('phone') ?? ''));
        $alamat = trim((string) ($this->request->getPost('address') ?? ''));
        $kecamatan = trim((string) ($this->request->getPost('kecamatan') ?? ''));
        $kabupaten = trim((string) ($this->request->getPost('Kota') ?? ''));
        $provinsi = trim((string) ($this->request->getPost('provinsi') ?? ''));
        $kodepos = trim((string) ($this->request->getPost('kodepos') ?? ''));

        // Basic validation
        if ($kodeKelas === '' || $nama === '' || $statusPembayaran === '') {
            return $resp(false, ['error' => 'Data wajib tidak lengkap']);
        }

        // Determine private/online class to relax jadwal
        $kelasRow = $kelasModel->where('kode_kelas', $kodeKelas)->first();
        $isPrivate = false;
        $isOnline = false;
        if ($kelasRow) {
            $nm = strtolower((string) ($kelasRow['nama_kelas'] ?? ''));
            $kategori = strtolower((string) ($kelasRow['kategori'] ?? ''));
            $isPrivate = (strpos($nm, 'private') !== false);
            $isOnline = in_array($kategori, ['kelasonline', 'kursusonline', 'online'], true);
        }

        // jadwal_id required unless private/online
        $jadwalId = null;
        if ($jadwalIdStr !== '' && ctype_digit($jadwalIdStr)) {
            $jadwalId = (int) $jadwalIdStr;
        }
        if (!$isPrivate && !$isOnline && !$jadwalId) {
            return $resp(false, ['error' => 'Silakan pilih jadwal untuk kelas ini']);
        }

        // Price calculation server-side (trust but verify)
        $hargaKelas = isset($kelasRow['harga']) && is_numeric($kelasRow['harga']) ? (float) $kelasRow['harga'] : 0.0;
        $diskonPersen = 0;
        if ($kodeVoucher !== '') {
            $db = \Config\Database::connect();
            $voucherRow = $db->table('voucher')->where('kode_voucher', $kodeVoucher)->get()->getRowArray();
            if ($voucherRow) {
                // Check class binding
                $voucherKelasKode = null;
                if (!empty($voucherRow['kelas_id'])) {
                    $vk = $db->table('kelas')->select('kode_kelas')->where('id', (int) $voucherRow['kelas_id'])->get()->getRowArray();
                    $voucherKelasKode = $vk['kode_kelas'] ?? null;
                }
                if ($voucherKelasKode !== null && $voucherKelasKode !== $kodeKelas) {
                    // Not valid for selected class: ignore discount
                    $diskonPersen = 0;
                } else {
                    // Date validity
                    $diskonPersen = (int) ($voucherRow['diskon_persen'] ?? 0);
                    $mulai = $voucherRow['tanggal_berlaku_mulai'] ?? null;
                    $sampai = $voucherRow['tanggal_berlaku_sampai'] ?? null;
                    $today = date('Y-m-d');
                    $validDate = true;
                    if ($mulai && $today < $mulai) {
                        $validDate = false;
                    }
                    if ($sampai && $today > $sampai) {
                        $validDate = false;
                    }
                    if (!$validDate) {
                        $diskonPersen = 0;
                    }
                }
            }
        }

        $biayaSetelahDiskon = max(0.0, $hargaKelas - ($hargaKelas * ($diskonPersen / 100)));

        // Kode unik: gunakan dua kode berbeda untuk DP/tagihan jika DP
        $postedKodeUnik = (int) ($this->request->getPost('kode_unik') ?? 0);
        $kodeUnikDP = random_int(100, 999);
        $kodeUnikTagihan = random_int(100, 999);
        if ($postedKodeUnik >= 100 && $postedKodeUnik <= 999) {
            $kodeUnikTagihan = $postedKodeUnik;
        }
        if ($kodeUnikTagihan === $kodeUnikDP) {
            $kodeUnikTagihan = ($kodeUnikTagihan % 999) + 1;
            if ($kodeUnikTagihan < 100) {
                $kodeUnikTagihan += 100;
            }
        }

        $isDP = strtolower($statusPembayaran) === 'dp 50%';
        $biayaDibayar = 0.0;
        $biayaTagihan = 0.0;
        $biayaTotal = 0.0;
        if ($isDP) {
            $dpAmount = round($biayaSetelahDiskon * 0.5, 2);
            $sisa = round($biayaSetelahDiskon - $dpAmount, 2);
            $biayaDibayar = round($dpAmount + $kodeUnikDP, 2);
            $biayaTagihan = round($sisa + $kodeUnikTagihan, 2);
            $biayaTotal = round($biayaDibayar + $biayaTagihan, 2);
        } else {
            $kodeUnikFull = random_int(100, 999);
            $biayaDibayar = round($biayaSetelahDiskon + $kodeUnikFull, 2);
            $biayaTagihan = 0.0;
            $biayaTotal = $biayaDibayar;
        }

        $data = [
            'nama' => $nama,
            'email' => $email,
            'no_telp' => $noTelp,
            'alamat' => $alamat,
            'kecamatan' => $kecamatan,
            'kabupaten' => $kabupaten,
            'provinsi' => $provinsi,
            'kodepos' => $kodepos,
            'kode_kelas' => $kodeKelas,
            'lokasi' => $lokasi,
            'jadwal_id' => $jadwalId,
            'status_pembayaran' => $isDP ? 'DP 50%' : 'lunas',
            'biaya_total' => $biayaTotal,
            'biaya_dibayar' => $biayaDibayar,
            'biaya_tagihan' => $biayaTagihan,
            'akses_aktif' => 0,
            'kode_voucher' => $kodeVoucher,
            'tanggal_daftar' => date('Y-m-d H:i:s'),
        ];

        if (!$model->save($data)) {
            return $resp(false, ['error' => 'Gagal menyimpan data', 'detail' => $model->errors()]);
        }
        // Enqueue WAHA message and attempt immediate send
        try {
            $newId = (int) ($model->getInsertID() ?? 0);
            // Siapkan payload template
            $db = \Config\Database::connect();
            $kelasNama = '';
            $k = $db->table('kelas')->select('nama_kelas')->where('kode_kelas', $kodeKelas)->get()->getRowArray();
            if ($k) {
                $kelasNama = (string) ($k['nama_kelas'] ?? '');
            }
            // Tampilkan nama kota dari kode lokasi
            $kotaName = '';
            if (!empty($lokasi)) {
                $kk = $db->table('kota_kelas')->select('nama')->where('kode', (string) $lokasi)->get()->getRowArray();
                $kotaName = (string) ($kk['nama'] ?? '');
            }
            if ($kotaName === '') {
                $kotaName = ucfirst((string) $lokasi);
            }
            // Bangun label jadwal: "dd MMMM yy - dd MMMM yy"
            $jadwalLabel = '';
            if (!empty($jadwalId)) {
                $jr = $db
                    ->table('jadwal_kelas')
                    ->select('tanggal_mulai, tanggal_selesai')
                    ->where('id', (int) $jadwalId)
                    ->get()
                    ->getRowArray();
                $mulai = $jr['tanggal_mulai'] ?? null;
                $selesai = $jr['tanggal_selesai'] ?? null;
                $bulanMap = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                $fmt = function ($d) use ($bulanMap) {
                    $ts = strtotime((string) $d);
                    if (!$ts)
                        return '';
                    $day = date('j', $ts);
                    $m = (int) date('n', $ts);
                    $yy = date('y', $ts);
                    $bulan = $bulanMap[$m] ?? date('F', $ts);
                    return $day . ' ' . $bulan . ' ' . $yy;
                };
                if (!empty($mulai)) {
                    $jadwalLabel = $fmt($mulai);
                    if (!empty($selesai)) {
                        $jadwalLabel .= ' - ' . $fmt($selesai);
                    }
                }
            }
            // Format rupiah: 3.200.696 (tanpa desimal)
            $fmtRp = function ($v) {
                $n = (float) ($v ?? 0);
                return number_format($n, 0, ',', '.');
            };
            $payload = [
                'nama' => (string) $nama,
                'nama_kelas' => $kelasNama,
                'jadwal' => (string) ($jadwalLabel ?? ''),
                'kota' => (string) $kotaName,
                'kabupaten' => (string) ($kabupaten ?? ''),
                'provinsi' => (string) ($provinsi ?? ''),
                'no_tlp' => (string) ($noTelp ?? ''),
                'email' => (string) ($email ?? ''),
                'status_pembayaran' => (string) ($isDP ? 'DP 50%' : 'lunas'),
                'jumlah_tagihan' => (string) $fmtRp($biayaTagihan),
                'jumlah_dibayar' => (string) $fmtRp($biayaDibayar),
            ];
            // Enqueue ke waha_queue: peserta
            model(\App\Models\WahaQueue::class)->insert([
                'registrasi_id' => $newId,
                'scenario' => 'registrasi_peserta',
                'recipient' => 'user',
                'phone' => (string) $noTelp,
                'template_key' => 'registrasi_peserta',
                'payload' => json_encode($payload),
                'status' => 'queued',
                'attempts' => 0,
                'next_attempt_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            // Enqueue ke waha_queue: admin (opsional)
            $adminPhone = (string) (env('WAHA_ADMIN_PHONE') ?? '');
            if ($adminPhone !== '') {
                $payloadAdmin = [
                    'nama' => (string) $nama,
                    'nama_kelas' => $kelasNama,
                    'jadwal' => (string) ($jadwalLabel ?? ''),
                    'kota' => (string) $kotaName,
                    'kabupaten' => (string) ($kabupaten ?? ''),
                    'provinsi' => (string) ($provinsi ?? ''),
                    'no_tlp' => (string) ($noTelp ?? ''),
                    'email' => (string) ($email ?? ''),
                    'status_pembayaran' => (string) ($isDP ? 'DP 50%' : 'lunas'),
                    'jumlah_tagihan' => (string) $fmtRp($biayaTagihan),
                    'jumlah_dibayar' => (string) $fmtRp($biayaDibayar),
                ];
                model(\App\Models\WahaQueue::class)->insert([
                    'registrasi_id' => $newId,
                    'scenario' => 'registrasi_admin',
                    'recipient' => 'admin',
                    'phone' => $adminPhone,
                    'template_key' => 'registrasi_admin',
                    'payload' => json_encode($payloadAdmin),
                    'status' => 'queued',
                    'attempts' => 0,
                    'next_attempt_at' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Coba kirim langsung agar user menerima notifikasi segera
            try {
                $ws = new \App\Libraries\WahaService();
                // Ambil template dari DB berdasarkan key
                $tpl = model(\App\Models\WahaTemplate::class)->where('key', 'registrasi_peserta')->first();
                $message = '';
                if ($tpl) {
                    $message = $ws->renderTemplate((string) ($tpl['template'] ?? ''), $payload);
                } else {
                    // fallback: rakit pesan sederhana bila template belum ada
                    $message = $ws->renderTemplate('${registrasi_peserta}: {{nama}} mendaftar {{nama_kelas}} di {{kota}}. Status {{status_pembayaran}}. Tagihan {{jumlah_tagihan}}. Dibayar {{jumlah_dibayar}}. Jadwal {{jadwal}}', $payload);
                }
                $res = $ws->sendMessage((string) $noTelp, $message);
                // Tulis log hasil
                model(\App\Models\WahaLog::class)->insert([
                    'registrasi_id' => $newId,
                    'scenario' => 'registrasi_peserta',
                    'recipient' => 'user',
                    'phone' => (string) $noTelp,
                    'message' => $message,
                    'status' => $res['success'] ? 'success' : 'failed',
                    'error' => $res['success'] ? null : ($res['message'] ?? ''),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                // Update status queue bila sukses
                if (!empty($res['success'])) {
                    $qm = model(\App\Models\WahaQueue::class);
                    // cari item teratas untuk registrasi ini
                    $qi = $qm->where('registrasi_id', $newId)->where('scenario', 'registrasi_peserta')->orderBy('id', 'DESC')->first();
                    if ($qi && isset($qi['id'])) {
                        $qm->update($qi['id'], ['status' => 'done', 'attempts' => ((int) ($qi['attempts'] ?? 0)) + 1]);
                    }
                }
                // Kirim admin segera bila tersedia
                if ($adminPhone !== '') {
                    $tplA = model(\App\Models\WahaTemplate::class)->where('key', 'registrasi_admin')->first();
                    $messageA = '';
                    $payloadAdmin2 = [
                        'nama' => (string) $nama,
                        'nama_kelas' => $kelasNama,
                        'jadwal' => (string) ($jadwalLabel ?? ''),
                        'kota' => (string) $kotaName,
                        'kabupaten' => (string) ($kabupaten ?? ''),
                        'provinsi' => (string) ($provinsi ?? ''),
                        'no_tlp' => (string) ($noTelp ?? ''),
                        'email' => (string) ($email ?? ''),
                        'status_pembayaran' => (string) ($isDP ? 'DP 50%' : 'lunas'),
                        'jumlah_tagihan' => (string) $fmtRp($biayaTagihan),
                        'jumlah_dibayar' => (string) $fmtRp($biayaDibayar),
                    ];
                    if ($tplA && !empty($tplA['template'])) {
                        $messageA = $ws->renderTemplate((string) $tplA['template'], $payloadAdmin2);
                    } else {
                        $messageA = $ws->renderTemplate('${registrasi_admin}: pendaftaran baru {{nama}} untuk {{nama_kelas}} {{kota}} jadwal {{jadwal}}. Status {{status_pembayaran}}, tagihan {{jumlah_tagihan}}', $payloadAdmin2);
                    }
                    $resA = $ws->sendMessage($adminPhone, $messageA);
                    model(\App\Models\WahaLog::class)->insert([
                        'registrasi_id' => $newId,
                        'scenario' => 'registrasi_admin',
                        'recipient' => 'admin',
                        'phone' => $adminPhone,
                        'message' => $messageA,
                        'status' => $resA['success'] ? 'success' : 'failed',
                        'error' => $resA['success'] ? null : ($resA['message'] ?? ''),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    if (!empty($resA['success'])) {
                        $qmA = model(\App\Models\WahaQueue::class);
                        $qiA = $qmA->where('registrasi_id', $newId)->where('scenario', 'registrasi_admin')->orderBy('id', 'DESC')->first();
                        if ($qiA && isset($qiA['id'])) {
                            $qmA->update($qiA['id'], ['status' => 'done', 'attempts' => ((int) ($qiA['attempts'] ?? 0)) + 1]);
                        }
                    }
                }
            } catch (\Throwable $we) {
                // Catat error pengiriman tapi jangan blok pendaftaran
                log_message('error', 'WAHA immediate send gagal: ' . $we->getMessage());
            }
        } catch (\Throwable $e) {
            // Abaikan jika enqueue gagal, tidak memblokir pendaftaran
            log_message('error', 'WAHA enqueue dari publik gagal: ' . $e->getMessage());
        }

        return $resp(true, ['message' => 'Pendaftaran berhasil', 'id' => $model->getInsertID()]);
    }

    public function sertifikat()
    {
        $data = [];

        if ($this->request->getMethod() === 'post') {
            $number = trim((string) $this->request->getPost('certificate_number'));

            if ($number !== '') {
                $sertifikatModel = new \App\Models\Sertifikat();
                $cert = $sertifikatModel->where('nomor_sertifikat', $number)->first();

                if ($cert) {
                    $data['cert_data'] = $cert;
                    $data['message_success'] = 'Sertifikat valid dan terdaftar resmi di EQIYU Indonesia.';
                } else {
                    $data['message_error'] = 'Sertifikat tidak ditemukan atau tidak valid.';
                }
            } else {
                $data['message_error'] = 'Nomor sertifikat wajib diisi.';
            }
        }

        return view('sertifikat', $data);
    }

    /**
     * Endpoint JSON: Cek sertifikat berdasarkan nomor.
     * GET /api/sertifikat?certificate_number=XXXX
     */
    public function sertifikatJson()
    {
        $number = trim((string) $this->request->getGet('certificate_number'));

        if ($number === '') {
            return $this
                ->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,
                    'message' => 'Nomor sertifikat wajib diisi.',
                ]);
        }

        $model = new \App\Models\Sertifikat();
        $cert = $model->where('nomor_sertifikat', $number)->first();

        if (!$cert) {
            return $this->response->setJSON([
                'ok' => false,
                'message' => 'Nomor sertifikat tidak ditemukan atau tidak valid.',
            ]);
        }

        return $this->response->setJSON([
            'ok' => true,
            'data' => [
                'nomor_sertifikat' => $cert['nomor_sertifikat'] ?? null,
                'nama_pemilik' => $cert['nama_pemilik'] ?? null,
                'nama_kelas' => $cert['nama_kelas'] ?? null,
                'kota_kelas' => $cert['kota_kelas'] ?? null,
                'tanggal_terbit' => $cert['tanggal_terbit'] ?? null,
                'status' => $cert['status'] ?? null,
            ],
        ]);
    }

    /**
     * Endpoint publik untuk download PDF sertifikat berdasarkan nomor.
     * Contoh: /lihatsertifikat?nomor=EQxxxx
     */
    public function lihatsertifikat()
    {
        $nomor = trim((string) $this->request->getGet('nomor'));
        if ($nomor === '') {
            return $this->response->setStatusCode(400)->setBody('Nomor sertifikat wajib diisi');
        }

        $db = \Config\Database::connect();
        $row = $db
            ->table('sertifikat s')
            ->select('s.nomor_sertifikat, s.tanggal_terbit, s.kota_kelas, r.nama AS nama_pemilik, k.nama_kelas AS nama_kelas, s.id AS id')
            ->join('registrasi r', 'r.id = s.registrasi_id', 'left')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->where('s.nomor_sertifikat', $nomor)
            ->get()
            ->getRowArray();

        if (!$row) {
            return $this->response->setStatusCode(404)->setBody('Sertifikat tidak ditemukan');
        }

        // Render HTML menggunakan template publik untuk PDF
        $html = view('pdf_certificate', [
            'data' => [
                'id' => (int) ($row['id'] ?? 0),
                'nomor_sertifikat' => (string) ($row['nomor_sertifikat'] ?? ''),
                'tanggal_terbit' => (string) ($row['tanggal_terbit'] ?? ''),
                'kota_kelas' => (string) ($row['kota_kelas'] ?? ''),
                'nama_pemilik' => (string) ($row['nama_pemilik'] ?? ''),
                'nama_kelas' => (string) ($row['nama_kelas'] ?? ''),
            ],
            'title' => 'Sertifikat ' . (string) ($row['nomor_sertifikat'] ?? ''),
        ]);

        // Gunakan Dompdf untuk menghasilkan PDF, sama seperti admin
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'sertifikat-' . ((string) ($row['nomor_sertifikat'] ?? '')) . '.pdf';
        return $this
            ->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

    public function bonus()
    {
        $data = [];

        if ($this->request->getMethod() === 'post') {
            $number = trim((string) $this->request->getPost('certificate_number'));

            if ($number !== '') {
                $db = \Config\Database::connect();
                // Cari sertifikat lalu join ke registrasi untuk ambil kode_kelas
                $row = $db
                    ->table('sertifikat s')
                    ->select('s.nomor_sertifikat, r.kode_kelas, k.id as kelas_id, k.nama_kelas')
                    ->join('registrasi r', 'r.id = s.registrasi_id', 'left')
                    ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
                    ->where('s.nomor_sertifikat', $number)
                    ->get()
                    ->getRowArray();

                if ($row && !empty($row['kode_kelas'])) {
                    $data['cert_data'] = [
                        'nomor_sertifikat' => (string) ($row['nomor_sertifikat'] ?? ''),
                        'kode_kelas' => (string) ($row['kode_kelas'] ?? ''),
                        'nama_kelas' => (string) ($row['nama_kelas'] ?? ''),
                    ];

                    // Ambil bonus berdasarkan kelas_id dari tabel bonus_file
                    $bonusModel = new \App\Models\BonusFile();
                    $files = $bonusModel
                        ->where('kelas_id', (int) ($row['kelas_id'] ?? 0))
                        ->orderBy('urutan', 'ASC')
                        ->orderBy('id', 'DESC')
                        ->findAll();

                    $data['bonusFiles'] = $files;  // tidak dipakai langsung di UI JS
                    if (!empty($files)) {
                        $data['message_success'] = 'Bonus kelas ditemukan untuk sertifikat ini.';
                    } else {
                        $data['message_error'] = 'Tidak ada bonus yang tersedia untuk kode kelas ini.';
                    }
                } else {
                    $data['message_error'] = 'Sertifikat tidak ditemukan atau tidak valid.';
                }
            } else {
                $data['message_error'] = 'Nomor sertifikat wajib diisi.';
            }
        }

        return view('bonus', $data);
    }

    /**
     * Endpoint JSON: Ambil bonus kelas berdasarkan nomor sertifikat.
     * GET /api/bonus?certificate_number=EQxxxx
     */
    public function bonusJson()
    {
        $number = trim((string) $this->request->getGet('certificate_number'));
        if ($number === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'ok' => false,
                'message' => 'Nomor sertifikat wajib diisi.',
            ]);
        }

        $db = \Config\Database::connect();
        $row = $db
            ->table('sertifikat s')
            ->select('s.nomor_sertifikat, r.kode_kelas, k.id as kelas_id, k.nama_kelas')
            ->join('registrasi r', 'r.id = s.registrasi_id', 'left')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->where('s.nomor_sertifikat', $number)
            ->get()
            ->getRowArray();

        if (!$row || empty($row['kode_kelas'])) {
            return $this->response->setJSON([
                'ok' => false,
                'message' => 'Sertifikat tidak ditemukan atau tidak valid.',
            ]);
        }

        // Ambil dari bonus_file berdasarkan kelas_id
        $bonusModel = new \App\Models\BonusFile();
        $filesRaw = $bonusModel
            ->where('kelas_id', (int) ($row['kelas_id'] ?? 0))
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'ok' => true,
            'data' => [
                'nomor_sertifikat' => (string) ($row['nomor_sertifikat'] ?? ''),
                'kode_kelas' => (string) ($row['kode_kelas'] ?? ''),
                'nama_kelas' => (string) ($row['nama_kelas'] ?? ''),
                'files' => array_map(static function (array $bf) {
                    return [
                        'nama_file' => (string) ($bf['judul_file'] ?? ($bf['tipe'] ?? 'Bonus')),
                        'path_file' => ltrim((string) ($bf['file_url'] ?? ''), '/'),
                        'deskripsi' => '',
                        'created_at' => (string) ($bf['created_at'] ?? ''),
                    ];
                }, $filesRaw),
            ],
        ]);
    }

    public function kursus()
    {
        return view('kursus');
    }

    /**
     * Endpoint JSON: Daftar kursus dengan filter dan paginasi.
     * GET /api/kursus?offset=0&limit=6&type=offline|online|semua&category=kursus|jasa|semua
     */
    public function kursusJson()
    {
        try {
            $offset = (int) ($this->request->getGet('offset') ?? 0);
            $limit = (int) ($this->request->getGet('limit') ?? 6);
            if ($limit <= 0 || $limit > 50) {
                $limit = 6;
            }

            $type = strtolower(trim((string) ($this->request->getGet('type') ?? 'semua')));
            $category = strtolower(trim((string) ($this->request->getGet('category') ?? 'semua')));

            $kelasModel = new \App\Models\Kelas();
            $builder = $kelasModel->builder();
            // Builder dari model Kelas sudah menetapkan tabel default 'kelas',
            // jadi tidak perlu memanggil ->from('kelas') lagi.
            // Urutkan berdasarkan kode_kelas (ascending)
            $builder->select('*')->orderBy('kode_kelas', 'ASC');
            // Jangan tampilkan kelas nonaktif
            $builder->where('status_kelas !=', 'nonaktif');

            // Filter kategori & tipe
            if ($category === 'jasa') {
                // Tampilkan hanya Jasa
                $builder->where('kategori', 'Jasa');
            } else {
                // Bukan Jasa: tentukan berdasarkan tipe
                if ($type === 'online') {
                    $builder->where('kategori', 'kursusonline');
                } elseif ($type === 'offline') {
                    $builder->where('kategori', 'Kursus');
                } else {
                    // type=semua
                    if ($category === 'kursus') {
                        // Hanya kursus (offline+online), exclude jasa
                        $builder
                            ->groupStart()
                            ->where('kategori', 'Kursus')
                            ->orWhere('kategori', 'kursusonline')
                            ->groupEnd();
                    }
                    // category=semua -> tidak ada filter kategori, tampilkan semuanya (jasa+kursus)
                }
            }

            // Paginate
            $builder->limit($limit, $offset);
            $rows = $builder->get()->getResultArray();

            // Ambil mapping kota
            $kotaModel = new \App\Models\KotaKelas();
            $items = [];

            foreach ($rows as $row) {
                $codesRaw = (string) ($row['kota_tersedia'] ?? '');
                $codes = array_values(array_filter(array_map('trim', explode(',', $codesRaw)), static fn($c) => $c !== ''));
                $kotaNames = [];
                if ($codes) {
                    // Coba cocokkan dengan kolom kode; jika gagal, cocokkan dengan nama (lowercase)
                    $kotaRows = $kotaModel->whereIn('kode', $codes)->findAll();
                    if (empty($kotaRows)) {
                        $kotaRows = $kotaModel->whereIn('nama', array_map('ucwords', $codes))->findAll();
                    }
                    foreach ($kotaRows as $kr) {
                        $kotaNames[] = $kr['nama'];
                    }
                    // Jika tetap kosong, gunakan nilai mentah (capitalize) agar tetap tampil
                    if (empty($kotaNames)) {
                        $kotaNames = array_map(static function ($c) {
                            return $c === 'se-dunia' ? 'Se-Dunia' : ucwords($c);
                        }, $codes);
                    }
                }
                // Online: berdasarkan kategori 'kursusonline'
                $isOnline = strtolower((string) ($row['kategori'] ?? '')) === 'kursusonline';

                // Sesuaikan badge dengan nilai di tabel kelas
                $badgeRaw = strtolower(trim((string) ($row['badge'] ?? '')));
                $badgeText = (string) ($row['badge'] ?? '');
                // Tentukan tipe style berdasarkan beberapa nilai yang didukung
                if ($badgeRaw === 'free') {
                    $badgeType = 'free';
                } elseif ($badgeRaw === 'new') {
                    $badgeType = 'new';
                } elseif ($badgeRaw === 'certificate') {
                    $badgeType = 'certificate';
                } else {
                    $badgeType = 'default';
                }

                $imageFile = (string) ($row['gambar_utama'] ?? '');
                $imageUrl = $imageFile !== ''
                    ? base_url($imageFile)
                    : base_url('assets/img/education/courses-3.webp');

                $detailSlug = (string) ($row['slug'] ?? '');
                if ($detailSlug === '') {
                    $detailSlug = (string) ($row['kode_kelas'] ?? '');
                }

                $items[] = [
                    'id' => (int) $row['id'],
                    'kode_kelas' => (string) ($row['kode_kelas'] ?? ''),
                    'slug' => (string) ($row['slug'] ?? ''),
                    'nama_kelas' => (string) ($row['nama_kelas'] ?? ''),
                    'deskripsi_singkat' => (string) ($row['deskripsi_singkat'] ?? ''),
                    'durasi' => (string) ($row['durasi'] ?? ''),
                    'kategori' => (string) ($row['kategori'] ?? ''),
                    'harga' => is_numeric($row['harga'] ?? null) ? (int) $row['harga'] : null,
                    'badge_text' => $badgeText,
                    'badge_type' => $badgeType,
                    'image_url' => $imageUrl,
                    'kota' => $kotaNames,
                    'is_online' => $isOnline,
                    'detail_url' => base_url('kursus/' . $detailSlug),
                ];
            }

            return $this->response->setJSON([
                'ok' => true,
                'items' => $items,
                'next_offset' => $offset + count($items),
                'has_more' => count($items) === $limit,
            ]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'ok' => false,
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function kursusDetail(string $slug)
    {
        $kelasModel = new \App\Models\Kelas();
        $kotaModel = new \App\Models\KotaKelas();

        // Cari berdasarkan slug; jika tidak ada, coba berdasarkan kode_kelas
        $kelas = $kelasModel->where('slug', $slug)->first();
        if (!$kelas) {
            $kelas = $kelasModel->where('kode_kelas', $slug)->first();
        }
        if (!$kelas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kelas tidak ditemukan');
        }

        // Bangun data tampilan
        $imageFile = (string) ($kelas['gambar_utama'] ?? '');
        $heroImageUrl = $imageFile !== ''
            ? base_url($imageFile)
            : base_url('assets/img/education/courses-8.webp');
        // Build hero images array: main image followed by any additional images
        $heroImages = [$heroImageUrl];
        $extraRaw = $kelas['gambar_tambahan'] ?? null;
        if (is_string($extraRaw) && trim($extraRaw) !== '') {
            $decoded = json_decode($extraRaw, true);
            if (is_array($decoded)) {
                foreach ($decoded as $p) {
                    if (is_string($p) && trim($p) !== '') {
                        $heroImages[] = base_url($p);
                    }
                }
            }
        }

        $kategori = (string) ($kelas['kategori'] ?? '');
        $kategoriLabel = $kategori === 'kursusonline' ? 'Kursus Online' : ($kategori ?: '');

        // Kota tersedia
        $codesRaw = (string) ($kelas['kota_tersedia'] ?? '');
        $codes = array_values(array_filter(array_map('trim', explode(',', $codesRaw)), static fn($c) => $c !== ''));
        $kotaNames = [];
        if ($codes) {
            $kotaRows = $kotaModel->whereIn('kode', $codes)->findAll();
            if (empty($kotaRows)) {
                $kotaRows = $kotaModel->whereIn('nama', array_map('ucwords', $codes))->findAll();
            }
            foreach ($kotaRows as $kr) {
                $kotaNames[] = $kr['nama'];
            }
            if (empty($kotaNames)) {
                $kotaNames = array_map(static function ($c) {
                    return $c === 'se-dunia' ? 'Se-Dunia' : ucwords($c);
                }, $codes);
            }
        }
        $kotaString = implode(', ', $kotaNames);

        $harga = $kelas['harga'] ?? null;
        $hargaFormatted = null;
        if (is_numeric($harga)) {
            $hargaFormatted = 'Rp. ' . number_format((float) $harga, 0, ',', '.') . ',-';
        }

        $deskripsiSingkat = (string) ($kelas['deskripsi_singkat'] ?? '');
        $deskripsiHtml = (string) ($kelas['deskripsi'] ?? '');

        $daftarUrl = base_url('daftar') . '?kelas=' . urlencode((string) ($kelas['kode_kelas'] ?? ''));

        return view('detail-kursus', [
            'kelas' => $kelas,
            'heroImageUrl' => $heroImageUrl,
            'heroImages' => $heroImages,
            'kategoriLabel' => $kategoriLabel,
            'kotaString' => $kotaString,
            'hargaFormatted' => $hargaFormatted,
            'deskripsiSingkat' => $deskripsiSingkat,
            'deskripsiHtml' => $deskripsiHtml,
            'daftarUrl' => $daftarUrl,
        ]);
    }

    /**
     * Halaman jembatan untuk akses kelas online, login via no_telp.
     * UI dipertahankan sesuai template yang ada di Views/loginkelas.php
     */
    public function loginkelas()
    {
        return view('loginkelas');
    }

    /**
     * Halaman kelas online. Jika belum login via loginkelas, redirect ke loginkelas.
     */
    public function kelasonline()
    {
        $auth = session()->get('kelasonline_auth');
        if (empty($auth)) {
            return redirect()->to(site_url('loginkelas'));
        }

        // Optional: cek expiry manual jika diset
        if (!empty($auth['expires_at']) && time() > (int) $auth['expires_at']) {
            session()->remove('kelasonline_auth');
            return redirect()->to(site_url('loginkelas'));
        }

        // Ambil data kelas untuk deskripsi singkat
        $kelas = null;
        try {
            $kelasModel = model(\App\Models\Kelas::class);
            $kelasId = (int) ($auth['kelas_id'] ?? 0);
            if ($kelasId > 0) {
                $kelas = $kelasModel->find($kelasId);
            }
        } catch (\Throwable $e) {
            // abaikan bila terjadi error
            $kelas = null;
        }

        return view('kelasonline', ['kelas' => $kelas]);
    }

    /**
     * Logout khusus Kelas Online: hapus session kelasonline_auth
     * lalu arahkan kembali ke halaman loginkelas.
     */
    public function kelasonlineLogout()
    {
        session()->remove('kelasonline_auth');
        return redirect()->to(site_url('loginkelas'));
    }

    /**
     * JSON login untuk Kelas Online.
     * GET /api/kelasonline/login?phone=08xxxx
     * Valid jika ditemukan di registrasi dengan akses_aktif=1 dan join kelas untuk kelas_id.
     * Meng-set session 'kelasonline_auth' dengan masa berlaku 2 jam.
     */
    public function kelasonlineLoginJson()
    {
        $phone = trim((string) ($this->request->getGet('phone') ?? $this->request->getGet('no_tlp') ?? ''));
        if ($phone === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'ok' => false,
                'message' => 'Nomor telepon wajib diisi',
            ]);
        }

        $db = \Config\Database::connect();
        $row = $db
            ->table('registrasi r')
            ->select('r.id as registrasi_id, r.no_telp, r.kode_kelas, r.akses_aktif, k.id as kelas_id, k.nama_kelas')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->where('r.no_telp', $phone)
            ->where('r.akses_aktif', 1)
            ->where('r.deleted_at IS NULL', null, false)
            ->orderBy('r.id', 'DESC')
            ->get()
            ->getRowArray();

        if (!$row || empty($row['kelas_id'])) {
            return $this->response->setJSON([
                'ok' => false,
                'message' => 'Akun tidak ditemukan atau akses tidak aktif untuk kelas online',
            ]);
        }

        // Set session login 2 jam (7200 detik)
        $authData = [
            'no_telp' => (string) ($row['no_telp'] ?? $phone),
            'registrasi_id' => (int) ($row['registrasi_id'] ?? 0),
            'kelas_id' => (int) ($row['kelas_id'] ?? 0),
            'kode_kelas' => (string) ($row['kode_kelas'] ?? ''),
            'nama_kelas' => (string) ($row['nama_kelas'] ?? ''),
            'expires_at' => time() + 7200,
        ];
        session()->set('kelasonline_auth', $authData);

        return $this->response->setJSON([
            'ok' => true,
            'data' => [
                'kode_kelas' => $authData['kode_kelas'],
                'nama_kelas' => $authData['nama_kelas'],
                'kelas_id' => $authData['kelas_id'],
            ],
        ]);
    }

    /**
     * JSON modul Kelas Online berdasarkan session login.
     * GET /api/kelasonline/modules
     * Returns: { ok, modules: [ { id, judul_modul, deskripsi, urutan } ] }
     */
    public function kelasonlineModulesJson()
    {
        $auth = session()->get('kelasonline_auth');
        if (empty($auth)) {
            return $this->response->setStatusCode(401)->setJSON([
                'ok' => false,
                'message' => 'Belum login kelas online',
            ]);
        }
        if (!empty($auth['expires_at']) && time() > (int) $auth['expires_at']) {
            session()->remove('kelasonline_auth');
            return $this->response->setStatusCode(401)->setJSON([
                'ok' => false,
                'message' => 'Sesi login berakhir. Silakan login ulang.',
            ]);
        }

        $kelasId = (int) ($auth['kelas_id'] ?? 0);
        if ($kelasId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'ok' => false,
                'message' => 'Kelas tidak valid pada sesi',
            ]);
        }

        $courseModel = new \App\Models\CourseOnline();
        $fileModel = new \App\Models\ModulFile();

        $modules = $courseModel
            ->where('kelas_id', $kelasId)
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'DESC')
            ->findAll();

        // Ambil semua file modul untuk course_id yang relevan
        $courseIds = array_values(array_filter(array_map(static function ($m) {
            return (int) ($m['id'] ?? 0);
        }, $modules), static function ($v) {
            return $v > 0;
        }));

        $filesByCourse = [];
        if (!empty($courseIds)) {
            $db = \Config\Database::connect();
            $rows = $db
                ->table('modul_file')
                ->whereIn('course_id', $courseIds)
                ->orderBy('urutan', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()
                ->getResultArray();
            foreach ($rows as $r) {
                $cid = (int) ($r['course_id'] ?? 0);
                if (!isset($filesByCourse[$cid]))
                    $filesByCourse[$cid] = [];
                $filesByCourse[$cid][] = [
                    'id' => (int) ($r['id'] ?? 0),
                    'tipe' => (string) ($r['tipe'] ?? ''),
                    'judul_file' => (string) ($r['judul_file'] ?? ''),
                    'file_url' => (string) ($r['file_url'] ?? ''),
                    'urutan' => is_numeric($r['urutan'] ?? null) ? (int) $r['urutan'] : null,
                ];
            }
        }

        return $this->response->setJSON([
            'ok' => true,
            'kelas' => [
                'id' => $kelasId,
                'nama_kelas' => (string) ($auth['nama_kelas'] ?? ''),
                'kode_kelas' => (string) ($auth['kode_kelas'] ?? ''),
            ],
            'modules' => array_map(static function (array $m) use ($filesByCourse) {
                $cid = (int) ($m['id'] ?? 0);
                return [
                    'id' => $cid,
                    'judul_modul' => (string) ($m['judul_modul'] ?? ''),
                    'deskripsi' => (string) ($m['deskripsi'] ?? ''),
                    'urutan' => is_numeric($m['urutan'] ?? null) ? (int) $m['urutan'] : null,
                    'files' => $filesByCourse[$cid] ?? [],
                ];
            }, $modules),
        ]);
    }
}

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

        // Data untuk statistik kelas berdasarkan kode_kelas
        // Jumlah sertifikat aktif untuk kelas "Basic Barista & Latte Art" (kode_kelas '01')
        $data['kelas_barista'] = $sertfikatModel
            ->where('status', 'aktif')
            ->where('nama_kelas', 'Basic Barista & Latte Art')
            ->countAllResults();
        $data['kelas_bisnis'] = $sertfikatModel
            ->where('status', 'aktif')
            ->where('nama_kelas', 'Workshop Membangun Bisnis Cafe & Kedai Kopi')
            ->countAllResults();
        $data['kelas_private'] = $sertfikatModel
            ->where('status', 'aktif')
            ->where('nama_kelas', 'Private Class Beverage & Bisnis Culinary')
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
        $berita = $beritaModel
            ->where('status', 'publish')
            ->where('tanggal_terbit <=', $now)
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

        $data = [
            'berita' => $berita,
            'pager' => $beritaModel->pager,
        ];

        return view('info', $data);
    }

    public function kontak()
    {
        return view('kontak');
    }

    public function jadwal()
    {
        return view('jadwal');
    }

    public function daftar()
    {
        $kelasModel = new \App\Models\Kelas();
        $kotaModel = new \App\Models\KotaKelas();

        // Ambil daftar kelas aktif untuk pilihan
        $kelasList = $kelasModel
            ->select('kode_kelas, nama_kelas, kota_tersedia, harga, kategori, slug, status_kelas')
            ->where('status_kelas !=', 'nonaktif')
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
        $kelas = $db->table('kelas')
            ->select('id, nama_kelas')
            ->where('kode_kelas', $kode)
            ->get()
            ->getRowArray();

        if (!$kelas) {
            $response['message'] = 'Kelas tidak ditemukan';
            return $this->response->setJSON($response);
        }

        // Get upcoming schedules for kelas_id
        $jadwal = $db->table('jadwal_kelas jk')
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
        if ($postKode !== null) { $kode = trim((string) $postKode); }
        $postKodeKelas = $this->request->getPost('kode_kelas');
        if ($postKodeKelas !== null) { $requestedKodeKelas = trim((string) $postKodeKelas); }

        if ($kode === '') {
            try {
                $json = $this->request->getJSON(true);
                if (is_array($json)) {
                    if (isset($json['kode_voucher'])) { $kode = trim((string) $json['kode_voucher']); }
                    if (isset($json['kode_kelas'])) { $requestedKodeKelas = trim((string) $json['kode_kelas']); }
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
        if ($mulai && $today < $mulai) { $validDate = false; }
        if ($sampai && $today > $sampai) { $validDate = false; }

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
        $row = $db->table('sertifikat s')
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
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

    public function bonus()
    {
        return view('bonus');
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
                        $builder->groupStart()
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
                        $kotaNames = array_map(static function ($c) { return $c === 'se-dunia' ? 'Se-Dunia' : ucwords($c); }, $codes);
                    }
                }
                // Online: berdasarkan kategori 'kursusonline'
                $isOnline = strtolower((string)($row['kategori'] ?? '')) === 'kursusonline';

                // Tentukan badge text berdasarkan field 'badge'
                $badgeRaw = strtolower(trim((string) ($row['badge'] ?? '')));
                $badgeText = '';
                $badgeType = '';
                if ($badgeRaw === 'hot') {
                    $badgeText = 'Best Seller';
                    $badgeType = 'default';
                } elseif ($badgeRaw === 'free') {
                    $badgeText = 'Free';
                    $badgeType = 'free';
                } elseif ($badgeRaw === 'new') {
                    $badgeText = 'New';
                    $badgeType = 'new';
                } elseif ($badgeRaw === 'popular') {
                    $badgeText = 'Popular';
                    $badgeType = 'default';
                } elseif ($badgeRaw === 'certificate') {
                    $badgeText = 'Certificate';
                    $badgeType = 'certificate';
                }

                $imageFile = (string) ($row['gambar_utama'] ?? '');
                $imageUrl = $imageFile !== ''
                    ? base_url('uploads/kelas/' . $imageFile)
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
            ? base_url('uploads/kelas/' . $imageFile)
            : base_url('assets/img/education/courses-8.webp');

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
                $kotaNames = array_map(static function ($c) { return $c === 'se-dunia' ? 'Se-Dunia' : ucwords($c); }, $codes);
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
            'kategoriLabel' => $kategoriLabel,
            'kotaString' => $kotaString,
            'hargaFormatted' => $hargaFormatted,
            'deskripsiSingkat' => $deskripsiSingkat,
            'deskripsiHtml' => $deskripsiHtml,
            'daftarUrl' => $daftarUrl,
        ]);
    }
}

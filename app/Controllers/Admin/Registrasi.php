<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Kelas as KelasModel;
use App\Models\Registrasi as RegistrasiModel;

class Registrasi extends BaseController
{
    public function listJson()
    {
        $model = model(RegistrasiModel::class);

        $request = $this->request;
        $page = max(1, (int) ($request->getGet('page') ?? 1));
        $perPage = (int) ($request->getGet('per_page') ?? 10);
        if ($perPage < 1) {
            $perPage = 10;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }
        $search = trim((string) ($request->getGet('search') ?? ''));
        $sort = strtolower(trim((string) ($request->getGet('sort') ?? 'tanggal_daftar')));
        $order = strtolower(trim((string) ($request->getGet('order') ?? 'desc')));
        $order = in_array($order, ['asc', 'desc'], true) ? $order : 'desc';

        $allowedSorts = [
            'id' => 'registrasi.id',
            'nama' => 'registrasi.nama',
            'email' => 'registrasi.email',
            'no_telp' => 'registrasi.no_telp',
            'lokasi' => 'registrasi.lokasi',
            'nama_kelas' => 'kelas.nama_kelas',
            'status_pembayaran' => 'registrasi.status_pembayaran',
            'tanggal_daftar' => 'registrasi.tanggal_daftar',
        ];
        $sortColumn = $allowedSorts[$sort] ?? 'registrasi.tanggal_daftar';

        // Gunakan Query Builder langsung untuk stabilitas
        $db = \Config\Database::connect();
        $qb = $db
            ->table('registrasi')
            ->select('registrasi.id, registrasi.nama, registrasi.email, registrasi.no_telp, registrasi.lokasi, registrasi.status_pembayaran, registrasi.akses_aktif, registrasi.tanggal_daftar, registrasi.biaya_dibayar, registrasi.biaya_tagihan, registrasi.jadwal_id, kelas.nama_kelas')
            ->join('kelas', 'kelas.kode_kelas = registrasi.kode_kelas', 'left')
            ->where('registrasi.deleted_at', null);

        if ($search !== '') {
            $qb
                ->groupStart()
                ->like('registrasi.nama', $search)
                ->orLike('registrasi.email', $search)
                ->orLike('registrasi.no_telp', $search)
                ->orLike('kelas.nama_kelas', $search)
                ->groupEnd();
        }

        // Hitung total tanpa mereset builder utama
        $countQB = clone $qb;
        $total = (int) $countQB->countAllResults();

        $offset = ($page - 1) * $perPage;
        $rows = $qb
            ->orderBy($sortColumn, $order)
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        // Format angka sesuai database bank_transactions: "10353.00"
        $formatPlain = static function($num): string {
            if (!is_numeric($num)) { return ''; }
            return number_format((float)$num, 2, '.', '');
        };

        // Batasi kolom yang dikirim ke client
        $data = array_map(function (array $r) use ($formatPlain): array {
            // Ambil periode jadwal untuk batasi pencocokan
            $dbLocal = \Config\Database::connect();
            $jadwalId = (int) ($r['jadwal_id'] ?? 0);
            $mulai = null; $selesai = null;
            if ($jadwalId > 0) {
                $jr = $dbLocal->table('jadwal_kelas')->select('tanggal_mulai, tanggal_selesai')->where('id', $jadwalId)->get()->getRowArray();
                $mulai = $jr['tanggal_mulai'] ?? null;
                $selesai = $jr['tanggal_selesai'] ?? null;
            }
            // Bangun himpunan amount_formatted terbatas periode jadwal
            $btQB = $dbLocal->table('bank_transactions')->select('amount_formatted');
            if (!empty($selesai)) {
                $btQB->where('period <=', $selesai);
            }
            // Sesuai kebutuhan: batasi hanya sampai tanggal_selesai (tanpa batas bawah)
            $bankAmountsRaw = $btQB->get()->getResultArray();
            $bankAmountSet = [];
            foreach ($bankAmountsRaw as $ba) {
                $val = trim((string)($ba['amount_formatted'] ?? ''));
                if ($val !== '') { $bankAmountSet[$val] = true; }
            }

            $dibayar = $r['biaya_dibayar'] ?? 0;
            $tagihan = $r['biaya_tagihan'] ?? 0;
            $formattedDibayar = $formatPlain($dibayar);
            $formattedTagihan = $formatPlain($tagihan);
            $matchDibayar = ($formattedDibayar !== '' && isset($bankAmountSet[$formattedDibayar]));
            $matchTagihan = ($formattedTagihan !== '' && isset($bankAmountSet[$formattedTagihan]));
            $dp50 = (is_numeric($dibayar) && is_numeric($tagihan) && (float)$tagihan > 0 && abs(((float)$dibayar) - 0.5 * (float)$tagihan) < 0.01);
            return [
                'id' => (int) ($r['id'] ?? 0),
                'nama' => (string) ($r['nama'] ?? ''),
                'email' => (string) ($r['email'] ?? ''),
                'no_telp' => (string) ($r['no_telp'] ?? ''),
                'lokasi' => (string) ($r['lokasi'] ?? ''),
                'nama_kelas' => (string) ($r['nama_kelas'] ?? ''),
                'status_pembayaran' => (string) ($r['status_pembayaran'] ?? ''),
                'akses_aktif' => !!($r['akses_aktif'] ?? false),
                'tanggal_daftar' => (string) ($r['tanggal_daftar'] ?? ''),
                'paid_match' => $matchDibayar,
                'paid_match_dibayar' => $matchDibayar,
                'paid_match_tagihan' => $matchTagihan,
                'dp50' => $dp50,
            ];
        }, $rows);

        $totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 0;

        return $this->response->setJSON([
            'success' => true,
            'data' => $data,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => $totalPages,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1,
                'sort' => $sort,
                'order' => $order,
            ],
        ]);
    }

    public function index()
    {
        $model = model(RegistrasiModel::class);

        $search = trim((string) $this->request->getGet('search'));
        if ($search !== '') {
            $model = $model->searchRegistrations($search);
            $registrations = $model->paginate(10);
        } else {
            $registrations = $model->getPaginatedRegistrationsWithClass(10);
        }

        $pager = $model->pager;

        // Ambil daftar kota pusat untuk pemetaan kode->nama pada tampilan
        $kotaOptions = model(\App\Models\KotaKelas::class)
            ->select('kode, nama')
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->findAll();

        $formatPlain = static function($num): string {
            if (!is_numeric($num)) { return ''; }
            return number_format((float)$num, 2, '.', '');
        };

        // Tambahkan flag indikator ke setiap row SSR
        if (is_array($registrations)) {
            foreach ($registrations as &$row) {
                // Batasi pencocokan ke periode jadwal registrasi
                $dbLocal = \Config\Database::connect();
                $jadwalId = (int) ($row['jadwal_id'] ?? 0);
                $mulai = null; $selesai = null;
                if ($jadwalId > 0) {
                    $jr = $dbLocal->table('jadwal_kelas')->select('tanggal_mulai, tanggal_selesai')->where('id', $jadwalId)->get()->getRowArray();
                    $mulai = $jr['tanggal_mulai'] ?? null;
                    $selesai = $jr['tanggal_selesai'] ?? null;
                }
                $btQB = $dbLocal->table('bank_transactions')->select('amount_formatted');
                if (!empty($selesai)) { $btQB->where('period <=', $selesai); }
                // Batasi hanya sampai tanggal_selesai (tanpa batas bawah)
                $bankAmountsRaw = $btQB->get()->getResultArray();
                $bankAmountSet = [];
                foreach ($bankAmountsRaw as $ba) {
                    $val = trim((string)($ba['amount_formatted'] ?? ''));
                    if ($val !== '') { $bankAmountSet[$val] = true; }
                }
                $dibayar = $row['biaya_dibayar'] ?? 0;
                $tagihan = $row['biaya_tagihan'] ?? 0;
                $formattedDibayar = $formatPlain($dibayar);
                $formattedTagihan = $formatPlain($tagihan);
                $matchDibayar = ($formattedDibayar !== '' && isset($bankAmountSet[$formattedDibayar]));
                $matchTagihan = ($formattedTagihan !== '' && isset($bankAmountSet[$formattedTagihan]));
                $row['paid_match'] = $matchDibayar;
                $row['paid_match_dibayar'] = $matchDibayar;
                $row['paid_match_tagihan'] = $matchTagihan;
                $row['dp50'] = (is_numeric($dibayar) && is_numeric($tagihan) && (float)$tagihan > 0 && abs(((float)$dibayar) - 0.5 * (float)$tagihan) < 0.01);
            }
            unset($row);
        }

        return view('layout/admin_layout', [
            'title' => 'Data Registrasi',
            'content' => view('admin/registrasi/dataregistrasi', [
                'registrations' => $registrations,
                'pager' => $pager,
                'search' => $search,
                'kotaOptions' => $kotaOptions,
            ]),
        ]);
    }

    public function tambah()
    {
        $kelasModel = model(KelasModel::class);
        $kelasList = $kelasModel
            ->select('kode_kelas, nama_kelas, kota_tersedia, harga, kategori')
            ->where('status_kelas', 'aktif')
            ->orderBy('kode_kelas', 'ASC')
            ->findAll();

        // Ambil daftar kota dari tabel pusat kota_kelas
        $kotaOptions = model(\App\Models\KotaKelas::class)
            ->select('kode, nama')
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->findAll();

        return view('layout/admin_layout', [
            'title' => 'Tambah Registrasi',
            'content' => view('admin/registrasi/tambahregistrasi', [
                'kelasList' => $kelasList,
                'kotaOptions' => $kotaOptions,
            ]),
        ]);
    }

    public function store()
    {
        // Tentukan apakah kelas yang dipilih adalah kelas private (Jasa atau nama mengandung 'Private')
        $submittedKodeKelas = (string) ($this->request->getPost('kode_kelas') ?? '');
        $kelasModel = model(KelasModel::class);
        $kelasForRule = $submittedKodeKelas !== ''
            ? $kelasModel->where('kode_kelas', $submittedKodeKelas)->first()
            : null;
        $isPrivate = false;
        if ($kelasForRule) {
            $nm = strtolower((string) ($kelasForRule['nama_kelas'] ?? ''));
            $isPrivate = (strpos($nm, 'private') !== false);
        }

        // Bangun rules validasi secara dinamis: jadwal_id opsional untuk kelas private
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'no_telp' => 'permit_empty|max_length[20]',
            'kode_kelas' => 'required|max_length[20]',
            'lokasi' => 'required|max_length[100]',
            'jadwal_id' => ($isPrivate ? 'permit_empty|is_natural_no_zero' : 'required|is_natural_no_zero'),
            'status_pembayaran' => 'required|in_list[DP 50%,lunas]',
            'akses_aktif' => 'permit_empty|in_list[0,1]',
            'kode_voucher' => 'permit_empty|max_length[50]',
            'alamat' => 'permit_empty',
            'kecamatan' => 'permit_empty|max_length[100]',
            'kabupaten' => 'permit_empty|max_length[100]',
            'provinsi' => 'permit_empty|max_length[100]',
            'kodepos' => 'permit_empty|max_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = model(RegistrasiModel::class);
        $kelasModel = model(KelasModel::class);

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_telp' => $this->request->getPost('no_telp'),
            'kode_kelas' => $this->request->getPost('kode_kelas'),
            'lokasi' => $this->request->getPost('lokasi'),
            'jadwal_id' => (int) $this->request->getPost('jadwal_id'),
            'kode_voucher' => $this->request->getPost('kode_voucher'),
            'alamat' => $this->request->getPost('alamat'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'kabupaten' => $this->request->getPost('kabupaten'),
            'provinsi' => $this->request->getPost('provinsi'),
            'kodepos' => $this->request->getPost('kodepos'),
            'biaya_dibayar' => $this->request->getPost('biaya_dibayar') ?: 0,
            'status_pembayaran' => $this->request->getPost('status_pembayaran'),
            'akses_aktif' => $this->request->getPost('akses_aktif') ? 1 : 0,
            'tanggal_daftar' => date('Y-m-d H:i:s'),
            'tanggal_update' => date('Y-m-d H:i:s'),
        ];

        // Validasi server-side voucher & siapkan harga setelah diskon
        $submittedKodeKelas = (string) ($data['kode_kelas'] ?? '');
        $submittedVoucher = trim((string) ($data['kode_voucher'] ?? ''));
        $db = \Config\Database::connect();

        $kelas = $kelasModel->where('kode_kelas', $data['kode_kelas'])->first();
        $hargaKelas = isset($kelas['harga']) && is_numeric($kelas['harga']) ? (float) $kelas['harga'] : 0.0;

        $diskonPersen = 0;
        if ($submittedVoucher !== '') {
            $voucherRow = $db->table('voucher')->where('kode_voucher', $submittedVoucher)->get()->getRowArray();
            if (!$voucherRow) {
                return redirect()->back()->withInput()->with('errors', ['Kode voucher tidak ditemukan']);
            }
            // Ambil kelas dari voucher
            $voucherKelasKode = null;
            if (!empty($voucherRow['kelas_id'])) {
                $kelasRow = $db->table('kelas')->select('kode_kelas')->where('id', (int) $voucherRow['kelas_id'])->get()->getRowArray();
                $voucherKelasKode = $kelasRow['kode_kelas'] ?? null;
            }
            // Jika voucher terkait kelas tertentu dan berbeda dengan yang dipilih, blok simpan
            if ($voucherKelasKode !== null && $submittedKodeKelas !== '' && $voucherKelasKode !== $submittedKodeKelas) {
                return redirect()->back()->withInput()->with('errors', [
                    'Voucher tidak berlaku untuk kelas yang dipilih (' . esc($submittedKodeKelas) . ').'
                ]);
            }

            // Validasi masa berlaku dan ambil diskon_persen
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
                // Jika di luar masa berlaku, anggap tanpa diskon
                $diskonPersen = 0;
            }
        }

        $biayaSetelahDiskon = max(0.0, $hargaKelas - ($hargaKelas * ($diskonPersen / 100)));

        // Kode unik: siapkan kode unik untuk DP dan Tagihan (berbeda)
        $postedKodeUnik = (int) ($this->request->getPost('kode_unik') ?? 0);
        $kodeUnikDP = random_int(100, 999);
        $kodeUnikTagihan = random_int(100, 999);
        // Gunakan posted untuk tagihan jika valid, namun pastikan berbeda dengan DP
        if ($postedKodeUnik >= 100 && $postedKodeUnik <= 999) {
            $kodeUnikTagihan = $postedKodeUnik;
        }
        if ($kodeUnikTagihan === $kodeUnikDP) {
            // paksa berbeda
            $kodeUnikTagihan = ($kodeUnikTagihan % 999) + 1;
            if ($kodeUnikTagihan < 100) {
                $kodeUnikTagihan += 100;
            }
        }

        // Hitung pembayaran sesuai status
        $isDP = strtolower((string) $data['status_pembayaran']) === 'dp 50%';
        if ($isDP) {
            $dpAmount = round($biayaSetelahDiskon * 0.5, 2);
            $sisa = round($biayaSetelahDiskon - $dpAmount, 2);  // sama dengan 0.5 * biayaSetelahDiskon
            $data['biaya_dibayar'] = round($dpAmount + $kodeUnikDP, 2);  // Total DP yang harus dibayar (DP + kode unik DP)
            $data['biaya_tagihan'] = round($sisa + $kodeUnikTagihan, 2);  // Biaya Tagihan (sisa + kode unik Tagihan)
            $data['biaya_total'] = round($data['biaya_dibayar'] + $data['biaya_tagihan'], 2);  // subtotal + kedua kode unik
        } else {
            // Pembayaran penuh: tagihan 0, dibayar = subtotal + satu kode unik
            $kodeUnikFull = random_int(100, 999);
            $data['biaya_tagihan'] = 0.0;
            $data['biaya_dibayar'] = round($biayaSetelahDiskon + $kodeUnikFull, 2);
            $data['biaya_total'] = $data['biaya_dibayar'];
        }

        // Validasi jadwal: hanya lakukan jika jadwal dipilih atau kelas bukan private
        // Validasi jadwal: hanya lakukan jika jadwal dipilih atau kelas bukan private
        $db = \Config\Database::connect();
        $selectedJadwalId = (int) ($data['jadwal_id'] ?? 0);
        $isPrivateNow = false;
        if ($kelas) {
            $nm2 = strtolower((string) ($kelas['nama_kelas'] ?? ''));
            $isPrivateNow = (strpos($nm2, 'private') !== false);
        }
        if ($selectedJadwalId > 0) {
            // Jika ada jadwal dipilih, wajib validasi keterkaitan dan lokasi
            $jadwalRow = $db
                ->table('jadwal_kelas')
                ->select('id, kelas_id, lokasi')
                ->where('id', $selectedJadwalId)
                ->get()
                ->getRowArray();
            if (!$jadwalRow) {
                return redirect()->back()->withInput()->with('errors', ['Jadwal tidak ditemukan']);
            }
            if ($kelas && isset($kelas['id']) && (int) $jadwalRow['kelas_id'] !== (int) $kelas['id']) {
                return redirect()->back()->withInput()->with('errors', ['Jadwal tidak sesuai dengan kelas yang dipilih']);
            }
            // Pastikan lokasi jadwal sama dengan lokasi yang dipilih di form
            $lokasiForm = strtolower(trim((string) $data['lokasi']));
            $lokasiJadwal = strtolower(trim((string) ($jadwalRow['lokasi'] ?? '')));
            // Izinkan kecocokan baik dengan kode kota maupun nama kota dari pusat
            $kotaMap = [];
            try {
                $rows = model(\App\Models\KotaKelas::class)
                    ->select('kode, nama')
                    ->where('status', 'aktif')
                    ->findAll();
                foreach ($rows as $r) {
                    $code = strtolower((string) ($r['kode'] ?? ''));
                    $name = strtolower((string) ($r['nama'] ?? ''));
                    if ($code !== '') {
                        $kotaMap[$code] = $name ?: $code;
                    }
                }
            } catch (\Throwable $e) {
                // Jika gagal memuat, lanjutkan tanpa peta
            }
            $lokasiFormName = isset($kotaMap[$lokasiForm]) ? $kotaMap[$lokasiForm] : '';
            $match = ($lokasiForm !== '' && ($lokasiForm === $lokasiJadwal || ($lokasiFormName !== '' && $lokasiFormName === $lokasiJadwal)));
            if (!$match) {
                return redirect()->back()->withInput()->with('errors', ['Jadwal tidak sesuai dengan lokasi yang dipilih']);
            }
        } else {
            // Tidak ada jadwal dipilih: izinkan hanya untuk kelas private
            if (!$isPrivateNow) {
                return redirect()->back()->withInput()->with('errors', ['Silakan pilih jadwal untuk kelas non-private']);
            }
        }

        $db = \Config\Database::connect();
        $db->transStart();
        if (!$model->save($data)) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', $model->errors() ?? ['Gagal menyimpan data registrasi']);
        }
        $newId = (int) ($model->getInsertID() ?? 0);

        try {
            // Queue pesan: sukses registrasi
            $kelasNama = (string) ($kelas['nama_kelas'] ?? '');
            // Tampilkan nama kota dari kode lokasi
            $dbKota = \Config\Database::connect();
            $kotaName = '';
            $lokasiCode = (string) ($data['lokasi'] ?? '');
            if ($lokasiCode !== '') {
                $kk = $dbKota->table('kota_kelas')->select('nama')->where('kode', $lokasiCode)->get()->getRowArray();
                $kotaName = (string) ($kk['nama'] ?? '');
            }
            if ($kotaName === '') {
                $kotaName = ucfirst($lokasiCode);
            }
            // Bangun label jadwal: "dd MMMM yy - dd MMMM yy"
            $jadwalLabel = '';
            if (!empty($data['jadwal_id'])) {
                $jr = $db
                    ->table('jadwal_kelas')
                    ->select('tanggal_mulai, tanggal_selesai')
                    ->where('id', (int) $data['jadwal_id'])
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
            // Label voucher untuk pesan WAHA: (diskon N%) jika voucher digunakan
            $voucherLabel = '';
            if ($submittedVoucher !== '') {
                $voucherLabel = 'diskon ' . (int) $diskonPersen . '%';
            }

            $payload = [
                'nama' => (string) ($data['nama'] ?? ''),
                'nama_kelas' => $kelasNama,
                'jadwal' => (string) ($jadwalLabel ?? ''),
                'kota' => (string) $kotaName,
                'kabupaten' => (string) ($data['kabupaten'] ?? ''),
                'provinsi' => (string) ($data['provinsi'] ?? ''),
                'no_tlp' => (string) ($data['no_telp'] ?? ''),
                'email' => (string) ($data['email'] ?? ''),
                'status_pembayaran' => (string) ($data['status_pembayaran'] ?? ''),
                'jumlah_tagihan' => (string) $fmtRp(($data['biaya_tagihan'] ?? '')),
                'jumlah_dibayar' => (string) $fmtRp(($data['biaya_dibayar'] ?? '')),
                'diskon_persen' => (int) $diskonPersen,
                'voucher' => (string) $voucherLabel,
            ];
            // Peserta
            model(\App\Models\WahaQueue::class)->insert([
                'registrasi_id' => $newId,
                'scenario' => 'registrasi_peserta',
                'recipient' => 'user',
                'phone' => (string) ($data['no_telp'] ?? ''),
                'template_key' => 'registrasi_peserta',
                'payload' => json_encode($payload),
                'status' => 'queued',
                'attempts' => 0,
                'next_attempt_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            // Admin (opsional)
            $adminPhone = (string) (env('WAHA_ADMIN_PHONE') ?? '');
            if ($adminPhone !== '') {
                $payloadAdmin = [
                    'nama' => (string) ($data['nama'] ?? ''),
                    'nama_kelas' => $kelasNama,
                    'jadwal' => (string) ($jadwalLabel ?? ''),
                    'kota' => (string) $kotaName,
                    'kabupaten' => (string) ($data['kabupaten'] ?? ''),
                    'provinsi' => (string) ($data['provinsi'] ?? ''),
                    'no_tlp' => (string) ($data['no_telp'] ?? ''),
                    'email' => (string) ($data['email'] ?? ''),
                    'status_pembayaran' => (string) ($data['status_pembayaran'] ?? ''),
                    'jumlah_tagihan' => (string) $fmtRp(($data['biaya_tagihan'] ?? '')),
                    'jumlah_dibayar' => (string) $fmtRp(($data['biaya_dibayar'] ?? '')),
                    'diskon_persen' => (int) $diskonPersen,
                    'voucher' => (string) $voucherLabel,
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

            // Coba kirim langsung agar peserta menerima notifikasi segera
            try {
                $ws = new \App\Libraries\WahaService();
                $tpl = model(\App\Models\WahaTemplate::class)->where('key', 'registrasi_peserta')->first();
                $message = '';
                if ($tpl) {
                    $message = $ws->renderTemplate((string) ($tpl['template'] ?? ''), $payload);
                } else {
                    $message = $ws->renderTemplate('${registrasi_peserta}: {{nama}} mendaftar {{nama_kelas}} di {{kota}}. Status {{status_pembayaran}}. Tagihan {{jumlah_tagihan}}. Dibayar {{jumlah_dibayar}}. Jadwal {{jadwal}}. Voucher {{voucher}}', $payload);
                }
                $res = $ws->sendMessage((string) ($data['no_telp'] ?? ''), $message);
                model(\App\Models\WahaLog::class)->insert([
                    'registrasi_id' => $newId,
                    'scenario' => 'registrasi_peserta',
                    'recipient' => 'user',
                    'phone' => (string) ($data['no_telp'] ?? ''),
                    'message' => $message,
                    'status' => $res['success'] ? 'success' : 'failed',
                    'error' => $res['success'] ? null : ($res['message'] ?? ''),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                if (!empty($res['success'])) {
                    $qm = model(\App\Models\WahaQueue::class);
                    $qi = $qm->where('registrasi_id', $newId)->where('scenario', 'registrasi_peserta')->orderBy('id', 'DESC')->first();
                    if ($qi && isset($qi['id'])) {
                        $qm->update($qi['id'], ['status' => 'done', 'attempts' => ((int) ($qi['attempts'] ?? 0)) + 1]);
                    }
                }
                // Kirim admin segera bila tersedia
                if ($adminPhone !== '') {
                    $tplA = model(\App\Models\WahaTemplate::class)->where('key', 'registrasi_admin')->first();
                    $messageA = '';
                    // Gunakan label jadwal terformat dan nama kota, serta format rupiah
                    $payloadAdmin2 = [
                        'nama' => (string) ($data['nama'] ?? ''),
                        'nama_kelas' => $kelasNama,
                        'jadwal' => (string) ($jadwalLabel ?? ''),
                        'kota' => (string) $kotaName,
                        'kabupaten' => (string) ($data['kabupaten'] ?? ''),
                        'provinsi' => (string) ($data['provinsi'] ?? ''),
                        'no_tlp' => (string) ($data['no_telp'] ?? ''),
                        'email' => (string) ($data['email'] ?? ''),
                        'status_pembayaran' => (string) ($data['status_pembayaran'] ?? ''),
                        'jumlah_tagihan' => (string) $fmtRp(($data['biaya_tagihan'] ?? '')),
                        'jumlah_dibayar' => (string) $fmtRp(($data['biaya_dibayar'] ?? '')),
                        'diskon_persen' => (int) $diskonPersen,
                        'voucher' => (string) $voucherLabel,
                    ];
                    if ($tplA && !empty($tplA['template'])) {
                        $messageA = $ws->renderTemplate((string) $tplA['template'], $payloadAdmin2);
                    } else {
                        // Tambahkan placeholder dibayar pada fallback agar konsisten dan voucher
                        $messageA = $ws->renderTemplate('${registrasi_admin}: pendaftaran baru {{nama}} untuk {{nama_kelas}} {{kota}} jadwal {{jadwal}}. Status {{status_pembayaran}}, tagihan {{jumlah_tagihan}}, dibayar {{jumlah_dibayar}}, voucher {{voucher}}', $payloadAdmin2);
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
                log_message('error', 'WAHA immediate send (admin) gagal: ' . $we->getMessage());
            }

            // Tandai whatsapp_sent untuk mencegah duplikasi enqueue
            $model->update($newId, ['whatsapp_sent' => 1, 'tanggal_update' => date('Y-m-d H:i:s')]);
        } catch (\Throwable $e) {
            // Abaikan error duplicate unique, rollback jika gagal simpan
            // Catat log non-fatal
            log_message('error', 'WAHA enqueue registration_success gagal: ' . $e->getMessage());
        }

        $db->transComplete();
        return redirect()->to(base_url('admin/registrasi'))->with('message', 'Data registrasi berhasil ditambahkan');
    }

    /**
     * Kirim pesan WAHA secara manual untuk satu registrasi.
     * Default: registrasi_peserta, opsi: registrasi_admin.
     */
    public function sendWahaRegistrasi($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'registrasi_id tidak valid']);
        }

        try {
            $db = \Config\Database::connect();
            $row = $db
                ->table('registrasi r')
                ->select('r.*, k.nama_kelas, jk.tanggal_mulai, jk.tanggal_selesai, jk.id AS jadwal_id')
                ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
                ->join('jadwal_kelas jk', 'jk.id = r.jadwal_id', 'left')
                ->where('r.id', $id)
                ->where('r.deleted_at', null)
                ->get()->getRowArray();

            if (!$row) {
                return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Registrasi tidak ditemukan']);
            }

            $payload = $this->buildTemplatePayload($row, $db);

            $ws = new \App\Libraries\WahaService();
            if (!$ws->isConfigured()) {
                return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'WAHA belum dikonfigurasi']);
            }
            // Tentukan key template: default peserta
            $reqJson = $this->request->getJSON(true) ?? [];
            $key = (string) ($reqJson['key'] ?? 'registrasi_peserta');

            if ($key === 'registrasi_admin') {
                // Kirim ke admin
                $tplRow = model(\App\Models\WahaTemplate::class)->where('key', 'registrasi_admin')->first();
                $template = '';
                if ($tplRow && ($tplRow['enabled'] ?? false)) {
                    $template = (string) ($tplRow['template'] ?? '');
                }
                if ($template === '') {
                    $template = '${registrasi_admin}: pendaftaran baru {{nama}} untuk {{nama_kelas}} {{kota}} jadwal {{jadwal}}. Status {{status_pembayaran}}, tagihan {{jumlah_tagihan}}';
                }
                $adminPhone = (string) (env('WAHA_ADMIN_PHONE') ?? '');
                if ($adminPhone === '') {
                    $cfg = model(\App\Models\WahaConfig::class)->where('key', 'admin_phone')->first();
                    $adminPhone = (string) ($cfg['value'] ?? '');
                }
                if ($adminPhone === '') {
                    return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Nomor admin belum dikonfigurasi']);
                }
                $message = $ws->renderTemplate($template, $payload);
                $res = $ws->sendMessage($adminPhone, $message);
                $this->upsertWahaLog($id, 'registrasi_admin', 'admin', $adminPhone, $message, (($res['success'] ?? false) ? 'success' : 'failed'), (($res['success'] ?? false) ? null : ($res['message'] ?? '')));
                return $this->response->setJSON([
                    'success' => (bool) ($res['success'] ?? false),
                    'message' => $res['message'] ?? (($res['success'] ?? false) ? 'Terkirim' : 'Gagal mengirim'),
                ]);
            } elseif ($key === 'registrasi_both') {
                // Kirim ke peserta lalu admin
                // Peserta
                $tplPeserta = model(\App\Models\WahaTemplate::class)->where('key', 'registrasi_peserta')->first();
                $templatePeserta = '';
                if ($tplPeserta && ($tplPeserta['enabled'] ?? false)) {
                    $templatePeserta = (string) ($tplPeserta['template'] ?? '');
                }
                if ($templatePeserta === '') {
                    $templatePeserta = '${registrasi_peserta}: {{nama}} mendaftar {{nama_kelas}} di {{kota}}. Status {{status_pembayaran}}. Tagihan {{jumlah_tagihan}}. Dibayar {{jumlah_dibayar}}. Jadwal {{jadwal}}. Voucher {{voucher}}';
                }
                $messagePeserta = $ws->renderTemplate($templatePeserta, $payload);
                $phonePeserta = (string) ($row['no_telp'] ?? '');
                $resPeserta = $ws->sendMessage($phonePeserta, $messagePeserta);
                $this->upsertWahaLog($id, 'registrasi_peserta', 'user', $phonePeserta, $messagePeserta, (($resPeserta['success'] ?? false) ? 'success' : 'failed'), (($resPeserta['success'] ?? false) ? null : ($resPeserta['message'] ?? '')));

                // Admin
                $tplAdmin = model(\App\Models\WahaTemplate::class)->where('key', 'registrasi_admin')->first();
                $templateAdmin = '';
                if ($tplAdmin && ($tplAdmin['enabled'] ?? false)) {
                    $templateAdmin = (string) ($tplAdmin['template'] ?? '');
                }
                if ($templateAdmin === '') {
                    $templateAdmin = '${registrasi_admin}: pendaftaran baru {{nama}} untuk {{nama_kelas}} {{kota}} jadwal {{jadwal}}. Status {{status_pembayaran}}, tagihan {{jumlah_tagihan}}, voucher {{voucher}}';
                }
                $adminPhone = (string) (env('WAHA_ADMIN_PHONE') ?? '');
                if ($adminPhone === '') {
                    $cfg = model(\App\Models\WahaConfig::class)->where('key', 'admin_phone')->first();
                    $adminPhone = (string) ($cfg['value'] ?? '');
                }
                if ($adminPhone === '') {
                    // tetap kembalikan hasil peserta, tapi beri pesan admin belum dikonfig
                    $combinedSuccess = (bool) ($resPeserta['success'] ?? false);
                    $msg = ($resPeserta['success'] ?? false) ? 'Peserta terkirim; admin belum dikonfigurasi' : ('Gagal peserta: ' . ($resPeserta['message'] ?? '')); 
                    return $this->response->setStatusCode($combinedSuccess ? 200 : 400)->setJSON(['success' => $combinedSuccess, 'message' => $msg]);
                }
                $messageAdmin = $ws->renderTemplate($templateAdmin, $payload);
                $resAdmin = $ws->sendMessage($adminPhone, $messageAdmin);
                $this->upsertWahaLog($id, 'registrasi_admin', 'admin', $adminPhone, $messageAdmin, (($resAdmin['success'] ?? false) ? 'success' : 'failed'), (($resAdmin['success'] ?? false) ? null : ($resAdmin['message'] ?? '')));

                $combinedSuccess = ((bool) ($resPeserta['success'] ?? false)) && ((bool) ($resAdmin['success'] ?? false));
                $message = 'Peserta: ' . (($resPeserta['success'] ?? false) ? 'OK' : ('ERR ' . ($resPeserta['message'] ?? ''))) . ', Admin: ' . (($resAdmin['success'] ?? false) ? 'OK' : ('ERR ' . ($resAdmin['message'] ?? '')));
                return $this->response->setStatusCode($combinedSuccess ? 200 : 400)->setJSON(['success' => $combinedSuccess, 'message' => $message]);
            } else {
                // Default: kirim ke peserta
                $tplRow = model(\App\Models\WahaTemplate::class)->where('key', 'registrasi_peserta')->first();
                $template = '';
                if ($tplRow && ($tplRow['enabled'] ?? false)) {
                    $template = (string) ($tplRow['template'] ?? '');
                }
                if ($template === '') {
                    $template = '${registrasi_peserta}: {{nama}} mendaftar {{nama_kelas}} di {{kota}}. Status {{status_pembayaran}}. Tagihan {{jumlah_tagihan}}. Dibayar {{jumlah_dibayar}}. Jadwal {{jadwal}}. Voucher {{voucher}}';
                }
                $message = $ws->renderTemplate($template, $payload);
                $phone = (string) ($row['no_telp'] ?? '');
                $res = $ws->sendMessage($phone, $message);
                $this->upsertWahaLog($id, 'registrasi_peserta', 'user', $phone, $message, (($res['success'] ?? false) ? 'success' : 'failed'), (($res['success'] ?? false) ? null : ($res['message'] ?? '')));
                return $this->response->setJSON([
                    'success' => (bool) ($res['success'] ?? false),
                    'message' => $res['message'] ?? (($res['success'] ?? false) ? 'Terkirim' : 'Gagal mengirim'),
                ]);
            }
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    protected function buildTemplatePayload(array $r, $db): array
    {
        $namaKelas = (string) ($r['nama_kelas'] ?? '');
        $kota = (string) ($r['lokasi'] ?? '');
        $kabupaten = (string) ($r['kabupaten'] ?? '');
        $provinsi = (string) ($r['provinsi'] ?? '');
        $noTelp = (string) ($r['no_telp'] ?? '');
        $email = (string) ($r['email'] ?? '');
        $statusPembayaran = (string) ($r['status_pembayaran'] ?? '');
        $jumlahTagihan = (float) ($r['biaya_tagihan'] ?? 0);
        $jumlahDibayar = (float) ($r['biaya_dibayar'] ?? 0);
        $jadwalMulai = (string) ($r['tanggal_mulai'] ?? '');
        $jadwalSelesai = (string) ($r['tanggal_selesai'] ?? '');
        $jadwalLabel = trim($jadwalMulai . ($jadwalSelesai ? (' s/d ' . $jadwalSelesai) : ''));

        // Voucher payload
        $voucherCode = (string) ($r['kode_voucher'] ?? '');
        $voucherLabel = '';
        $diskonPersen = null;
        if ($voucherCode !== '') {
            try {
                $vrow = $db->table('voucher')->where('kode_voucher', $voucherCode)->get()->getRowArray();
                if ($vrow) {
                    $diskonPersen = isset($vrow['diskon_persen']) ? (int) $vrow['diskon_persen'] : null;
                }
            } catch (\Throwable $e) {
                // ignore
            }
            $voucherLabel = $voucherCode . (($diskonPersen !== null && $diskonPersen > 0) ? (' (Diskon ' . $diskonPersen . '%)') : '');
        }

        return [
            'registrasi_id' => (int) ($r['id'] ?? 0),
            'nama' => (string) ($r['nama'] ?? ''),
            'nama_kelas' => $namaKelas,
            'jadwal' => $jadwalLabel,
            'kota' => $kota,
            'kabupaten' => $kabupaten,
            'provinsi' => $provinsi,
            'no_tlp' => $noTelp,
            'email' => $email,
            'status_pembayaran' => $statusPembayaran,
            'jumlah_tagihan' => number_format($jumlahTagihan, 0, ',', '.'),
            'jumlah_dibayar' => number_format($jumlahDibayar, 0, ',', '.'),
            'voucher' => $voucherLabel,
            'diskon_persen' => $diskonPersen,
        ];
    }

    /**
     * Upsert log WAHA untuk menghindari duplicate key pada (registrasi_id, scenario, recipient)
     */
    protected function upsertWahaLog(int $registrasiId, string $scenario, string $recipient, string $phone, string $message, string $status, ?string $error): void
    {
        $lm = model(\App\Models\WahaLog::class);
        $existing = $lm->where('registrasi_id', $registrasiId)->where('scenario', $scenario)->where('recipient', $recipient)->first();
        $data = [
            'registrasi_id' => $registrasiId,
            'scenario' => $scenario,
            'recipient' => $recipient,
            'phone' => $phone,
            'message' => $message,
            'status' => $status,
            'error' => $error,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if ($existing && isset($existing['id'])) {
            $lm->update((int) $existing['id'], $data);
        } else {
            $lm->insert($data);
        }
    }

    public function edit($id)
    {
        $model = model(RegistrasiModel::class);
        $registrasi = $model->find($id);

        if (!$registrasi) {
            return redirect()->to(base_url('admin/registrasi'))->with('error', 'Data registrasi tidak ditemukan');
        }

        $kelasModel = model(KelasModel::class);
        $kelasList = $kelasModel
            ->select('kode_kelas, nama_kelas')
            ->where('status_kelas', 'aktif')
            ->orderBy('nama_kelas', 'ASC')
            ->findAll();

        // Ambil daftar kota aktif untuk mapping kode -> nama
        $kotaOptions = model(\App\Models\KotaKelas::class)
            ->select('kode, nama')
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->findAll();

        return view('layout/admin_layout', [
            'title' => 'Edit Registrasi',
            'content' => view('admin/registrasi/editregistrasi', [
                'registrasi' => $registrasi,
                'kelasList' => $kelasList,
                'kotaOptions' => $kotaOptions,
            ]),
        ]);
    }

    public function update($id)
    {
        $model = model(RegistrasiModel::class);
        $existing = $model->find($id);

        if (!$existing) {
            return redirect()->to(base_url('admin/registrasi'))->with('error', 'Data registrasi tidak ditemukan');
        }

        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'no_telp' => 'permit_empty|max_length[20]',
            'kode_kelas' => 'required|max_length[20]',
            'lokasi' => 'permit_empty|max_length[100]',
            'biaya_total' => 'required|decimal',
            'status_pembayaran' => 'required|in_list[DP 50%,lunas]',
            'akses_aktif' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_telp' => $this->request->getPost('no_telp'),
            'kode_kelas' => $this->request->getPost('kode_kelas'),
            'lokasi' => $this->request->getPost('lokasi'),
            'biaya_total' => $this->request->getPost('biaya_total'),
            'biaya_dibayar' => $this->request->getPost('biaya_dibayar') ?: 0,
            'status_pembayaran' => $this->request->getPost('status_pembayaran'),
            'akses_aktif' => $this->request->getPost('akses_aktif') ? 1 : 0,
            'tanggal_update' => date('Y-m-d H:i:s'),
        ];

        if (!$model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors() ?? ['Gagal memperbarui data registrasi']);
        }

        return redirect()->to(base_url('admin/registrasi'))->with('message', 'Data registrasi berhasil diperbarui');
    }

    public function delete($id)
    {
        $model = model(RegistrasiModel::class);

        if (!$model->find($id)) {
            return redirect()->to(base_url('admin/registrasi'))->with('error', 'Data registrasi tidak ditemukan');
        }

        if (!$model->delete($id)) {
            return redirect()->back()->with('error', 'Gagal menghapus data registrasi');
        }

        return redirect()->to(base_url('admin/registrasi'))->with('message', 'Data registrasi berhasil dihapus');
    }

    public function toggleAkses($id)
    {
        $model = model(RegistrasiModel::class);
        $registrasi = $model->find($id);

        if (!$registrasi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $newStatus = $registrasi['akses_aktif'] ? 0 : 1;

        if ($model->update($id, ['akses_aktif' => $newStatus, 'tanggal_update' => date('Y-m-d H:i:s')])) {
            // Jika akses diaktifkan, kirim notifikasi akses online
            if ($newStatus === 1) {
                try {
                    $db = \Config\Database::connect();
                    $kelasNama = '';
                    if (!empty($registrasi['kode_kelas'])) {
                        $k = $db->table('kelas')->select('nama_kelas')->where('kode_kelas', $registrasi['kode_kelas'])->get()->getRowArray();
                        $kelasNama = (string) ($k['nama_kelas'] ?? '');
                    }
                    $payload = [
                        'nama' => (string) ($registrasi['nama'] ?? ''),
                        'nama_kelas' => $kelasNama,
                    ];
                    model(\App\Models\WahaQueue::class)->insert([
                        'registrasi_id' => (int) $registrasi['id'],
                        'scenario' => 'akses_kelas',
                        'recipient' => 'user',
                        'phone' => (string) ($registrasi['no_telp'] ?? ''),
                        'template_key' => 'akses_kelas',
                        'payload' => json_encode($payload),
                        'status' => 'queued',
                        'attempts' => 0,
                        'next_attempt_at' => null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    // Kirim langsung agar peserta menerima akses segera
                    try {
                        $ws = new \App\Libraries\WahaService();
                        $tpl = model(\App\Models\WahaTemplate::class)->where('key', 'akses_kelas')->first();
                        $message = '';
                        if ($tpl && !empty($tpl['template'])) {
                            $message = $ws->renderTemplate((string) $tpl['template'], $payload);
                        } else {
                            // Fallback sederhana bila template belum dibuat
                            $message = $ws->renderTemplate('${akses_kelas}: Hai {{nama}}, akses Kelas {{nama_kelas}} telah diaktifkan.', $payload);
                        }
                        $res = $ws->sendMessage((string) ($registrasi['no_telp'] ?? ''), $message);
                        model(\App\Models\WahaLog::class)->insert([
                            'registrasi_id' => (int) $registrasi['id'],
                            'scenario' => 'akses_kelas',
                            'recipient' => 'user',
                            'phone' => (string) ($registrasi['no_telp'] ?? ''),
                            'message' => $message,
                            'status' => !empty($res['success']) ? 'success' : 'failed',
                            'error' => !empty($res['success']) ? null : ($res['message'] ?? ''),
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                        if (!empty($res['success'])) {
                            // Tandai item queue terakhir untuk skenario ini sebagai selesai
                            $qm = model(\App\Models\WahaQueue::class);
                            $qi = $qm
                                ->where('registrasi_id', (int) $registrasi['id'])
                                ->where('scenario', 'akses_kelas')
                                ->orderBy('id', 'DESC')
                                ->first();
                            if ($qi && isset($qi['id'])) {
                                $qm->update($qi['id'], ['status' => 'done', 'attempts' => ((int) ($qi['attempts'] ?? 0)) + 1]);
                            }
                        }
                    } catch (\Throwable $we) {
                        log_message('error', 'WAHA immediate send (akses_kelas) gagal: ' . $we->getMessage());
                    }
                } catch (\Throwable $e) {
                    log_message('error', 'WAHA enqueue online_access gagal: ' . $e->getMessage());
                }
            }
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status akses berhasil diubah',
                'newStatus' => $newStatus
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status akses']);
        }
    }

    /**
     * Reschedule: pindahkan registrasi ke jadwal baru jika kapasitas masih tersedia
     * Input: registrasi_id, new_jadwal_id
     */
    public function reschedule()
    {
        $registrasiId = (int) $this->request->getPost('registrasi_id');
        $newJadwalId = (int) $this->request->getPost('new_jadwal_id');

        if ($registrasiId <= 0 || $newJadwalId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Input tidak valid']);
        }

        $model = model(RegistrasiModel::class);
        $row = $model->find($registrasiId);
        if (!$row) {
            return $this->response->setJSON(['success' => false, 'message' => 'Registrasi tidak ditemukan']);
        }

        $db = \Config\Database::connect();

        // Ambil info kelas dari registrasi (by kode_kelas)
        $kelas = $db
            ->table('kelas')
            ->select('id, nama_kelas')
            ->where('kode_kelas', (string) $row['kode_kelas'])
            ->get()
            ->getRowArray();
        if (!$kelas) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kelas registrasi tidak valid']);
        }

        // Ambil jadwal baru dan cek kapasitas
        $jadwalBaru = $db
            ->table('jadwal_kelas')
            ->select('id, kelas_id, lokasi, kapasitas, tanggal_mulai, tanggal_selesai')
            ->where('id', $newJadwalId)
            ->get()
            ->getRowArray();
        if (!$jadwalBaru) {
            return $this->response->setJSON(['success' => false, 'message' => 'Jadwal baru tidak ditemukan']);
        }

        // Pastikan jadwal baru sesuai kelas registrasi
        if ((int) $jadwalBaru['kelas_id'] !== (int) $kelas['id']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Jadwal baru tidak sesuai dengan kelas registrasi']);
        }

        // Hitung peserta pada jadwal baru
        $jumlahPeserta = (int) $db
            ->table('registrasi')
            ->selectCount('id', 'cnt')
            ->where('jadwal_id', $newJadwalId)
            ->get()
            ->getRow('cnt');

        $kapasitas = (int) ($jadwalBaru['kapasitas'] ?? 0);
        if ($kapasitas <= 0 || $jumlahPeserta >= $kapasitas) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kapasitas jadwal baru sudah penuh']);
        }

        // Lakukan update: pindahkan registrasi ke jadwal baru, sinkronkan lokasi
        $updateData = [
            'jadwal_id' => $newJadwalId,
            'lokasi' => (string) $jadwalBaru['lokasi'],
            'tanggal_update' => date('Y-m-d H:i:s'),
        ];

        if (!$model->update($registrasiId, $updateData)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal melakukan reschedule']);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Reschedule berhasil',
            'data' => [
                'registrasi_id' => $registrasiId,
                'new_jadwal_id' => $newJadwalId,
                'lokasi' => (string) $jadwalBaru['lokasi'],
                'tanggal_mulai' => (string) $jadwalBaru['tanggal_mulai'] ?? '',
                'tanggal_selesai' => (string) $jadwalBaru['tanggal_selesai'] ?? '',
                'nama_kelas' => (string) $kelas['nama_kelas'],
            ],
        ]);
    }

    public function checkVoucher()
    {
        // Ambil kode voucher dari POST form maupun JSON body
        $kode = '';
        // Coba baca dari form/urlencoded terlebih dahulu
        $postKode = $this->request->getPost('kode_voucher');
        if ($postKode !== null) {
            $kode = trim((string) $postKode);
        }
        // Ambil kode_kelas yang dipilih (opsional untuk validasi kelas)
        $requestedKodeKelas = '';
        $postKodeKelas = $this->request->getPost('kode_kelas');
        if ($postKodeKelas !== null) {
            $requestedKodeKelas = trim((string) $postKodeKelas);
        }
        // Jika kosong, coba dari JSON body
        if ($kode === '') {
            try {
                $json = $this->request->getJSON(true);
                if (is_array($json) && isset($json['kode_voucher'])) {
                    $kode = trim((string) $json['kode_voucher']);
                }
                if (is_array($json) && isset($json['kode_kelas'])) {
                    $requestedKodeKelas = trim((string) $json['kode_kelas']);
                }
            } catch (\Throwable $e) {
                // Abaikan error parsing JSON, lanjutkan validasi kosong
            }
        }
        if ($kode === '') {
            return $this->response->setJSON(['found' => false, 'message' => 'Kode voucher kosong']);
        }

        $db = \Config\Database::connect();
        $row = $db->table('voucher')->where('kode_voucher', $kode)->get()->getRowArray();

        if (!$row) {
            return $this->response->setJSON(['found' => false, 'message' => 'Voucher tidak ditemukan']);
        }

        $today = date('Y-m-d');
        $validDate = true;
        if (!empty($row['tanggal_berlaku_mulai']) && $row['tanggal_berlaku_mulai'] > $today) {
            $validDate = false;
        }
        if (!empty($row['tanggal_berlaku_sampai']) && $row['tanggal_berlaku_sampai'] < $today) {
            $validDate = false;
        }

        $diskon = isset($row['diskon_persen']) ? (float) $row['diskon_persen'] : null;

        // Dapatkan informasi kelas dari voucher (kelas_id) untuk validasi dengan kode_kelas yang dipilih
        $voucherKelasKode = null;
        $voucherKelasNama = null;
        $validForClass = null;  // null jika kode_kelas tidak dikirim, boolean jika tersedia
        if (!empty($row['kelas_id'])) {
            $kelasRow = $db->table('kelas')->select('id, kode_kelas, nama_kelas')->where('id', (int) $row['kelas_id'])->get()->getRowArray();
            if ($kelasRow) {
                $voucherKelasKode = $kelasRow['kode_kelas'] ?? null;
                $voucherKelasNama = $kelasRow['nama_kelas'] ?? null;
                if ($requestedKodeKelas !== '') {
                    $validForClass = ($voucherKelasKode === $requestedKodeKelas);
                }
            }
        }

        return $this->response->setJSON([
            'found' => true,
            'validDate' => $validDate,
            'diskon_persen' => $diskon,
            'voucher' => $row,
            'voucher_kelas_kode' => $voucherKelasKode,
            'voucher_kelas_nama' => $voucherKelasNama,
            'validForClass' => $validForClass,
            'requested_kode_kelas' => $requestedKodeKelas,
        ]);
    }
}

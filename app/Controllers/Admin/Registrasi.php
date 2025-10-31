<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Registrasi as RegistrasiModel;
use App\Models\Kelas as KelasModel;

class Registrasi extends BaseController
{
    public function listJson()
    {
        $model = model(RegistrasiModel::class);

        $request = $this->request;
        $page = max(1, (int) ($request->getGet('page') ?? 1));
        $perPage = (int) ($request->getGet('per_page') ?? 10);
        if ($perPage < 1) { $perPage = 10; }
        if ($perPage > 100) { $perPage = 100; }
        $search = trim((string) ($request->getGet('search') ?? ''));
        $sort = strtolower(trim((string) ($request->getGet('sort') ?? 'tanggal_daftar')));
        $order = strtolower(trim((string) ($request->getGet('order') ?? 'desc')));
        $order = in_array($order, ['asc','desc'], true) ? $order : 'desc';

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
        $qb = $db->table('registrasi')
                 ->select('registrasi.id, registrasi.nama, registrasi.email, registrasi.no_telp, registrasi.lokasi, registrasi.status_pembayaran, registrasi.akses_aktif, registrasi.tanggal_daftar, kelas.nama_kelas')
                 ->join('kelas', 'kelas.kode_kelas = registrasi.kode_kelas', 'left')
                 ->where('registrasi.deleted_at', null);

        if ($search !== '') {
            $qb->groupStart()
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
        $rows = $qb->orderBy($sortColumn, $order)
                   ->limit($perPage, $offset)
                   ->get()
                   ->getResultArray();

        // Batasi kolom yang dikirim ke client
        $data = array_map(static function(array $r): array {
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
        $kelasList = $kelasModel->select('kode_kelas, nama_kelas, kota_tersedia, harga, kategori')
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
            $nm  = strtolower((string) ($kelasForRule['nama_kelas'] ?? ''));
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

        // Validasi server-side: jika ada kode_voucher, pastikan voucher cocok dengan kelas yang dipilih
        $submittedKodeKelas = (string) ($data['kode_kelas'] ?? '');
        $submittedVoucher = trim((string) ($data['kode_voucher'] ?? ''));
        if ($submittedVoucher !== '') {
            $db = \Config\Database::connect();
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
        }

        // Isi biaya_total dari harga kelas yang dipilih
        $kelas = $kelasModel->where('kode_kelas', $data['kode_kelas'])->first();
        $data['biaya_total'] = isset($kelas['harga']) ? (float) $kelas['harga'] : 0.0;

        // Validasi jadwal: hanya lakukan jika jadwal dipilih atau kelas bukan private
        $db = \Config\Database::connect();
        $selectedJadwalId = (int) ($data['jadwal_id'] ?? 0);
        $isPrivateNow = false;
        if ($kelas) {
            $nm2  = strtolower((string) ($kelas['nama_kelas'] ?? ''));
            $isPrivateNow = (strpos($nm2, 'private') !== false);
        }
        if ($selectedJadwalId > 0) {
            // Jika ada jadwal dipilih, wajib validasi keterkaitan dan lokasi
            $jadwalRow = $db->table('jadwal_kelas')
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
                    if ($code !== '') { $kotaMap[$code] = $name ?: $code; }
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

        if (!$model->save($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors() ?? ['Gagal menyimpan data registrasi']);
        }

        return redirect()->to(base_url('admin/registrasi'))->with('message', 'Data registrasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $model = model(RegistrasiModel::class);
        $registrasi = $model->find($id);
        
        if (!$registrasi) {
            return redirect()->to(base_url('admin/registrasi'))->with('error', 'Data registrasi tidak ditemukan');
        }

        $kelasModel = model(KelasModel::class);
        $kelasList = $kelasModel->select('kode_kelas, nama_kelas')
                                ->where('status_kelas', 'aktif')
                                ->orderBy('nama_kelas', 'ASC')
                                ->findAll();

        return view('layout/admin_layout', [
            'title' => 'Edit Registrasi',
            'content' => view('admin/registrasi/editregistrasi', [
                'registrasi' => $registrasi,
                'kelasList' => $kelasList,
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
        $kelas = $db->table('kelas')->select('id, nama_kelas')
            ->where('kode_kelas', (string) $row['kode_kelas'])
            ->get()->getRowArray();
        if (!$kelas) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kelas registrasi tidak valid']);
        }

        // Ambil jadwal baru dan cek kapasitas
        $jadwalBaru = $db->table('jadwal_kelas')
            ->select('id, kelas_id, lokasi, kapasitas, tanggal_mulai, tanggal_selesai')
            ->where('id', $newJadwalId)
            ->get()->getRowArray();
        if (!$jadwalBaru) {
            return $this->response->setJSON(['success' => false, 'message' => 'Jadwal baru tidak ditemukan']);
        }

        // Pastikan jadwal baru sesuai kelas registrasi
        if ((int) $jadwalBaru['kelas_id'] !== (int) $kelas['id']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Jadwal baru tidak sesuai dengan kelas registrasi']);
        }

        // Hitung peserta pada jadwal baru
        $jumlahPeserta = (int) $db->table('registrasi')
            ->selectCount('id', 'cnt')
            ->where('jadwal_id', $newJadwalId)
            ->get()->getRow('cnt');

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
        $validForClass = null; // null jika kode_kelas tidak dikirim, boolean jika tersedia
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
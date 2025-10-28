<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Kelas as KelasModel;

class Jadwal extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $perPage = 10;
        $page = max(1, (int) $this->request->getGet('page'));
        $start = ($page - 1) * $perPage;
        $search = trim((string) $this->request->getGet('search'));

        $builder = $db
            ->table('jadwal_kelas jk')
            ->select('jk.*, k.nama_kelas, k.durasi, k.harga')
            ->join('kelas k', 'jk.kelas_id = k.id', 'left');

        // Only show schedules that haven't finished yet
        $builder->where('jk.tanggal_selesai >= CURDATE()', null, false);

        if ($search !== '') {
            $builder
                ->groupStart()
                ->like('k.nama_kelas', $search)
                ->orLike('jk.lokasi', $search)
                ->groupEnd();
        }

        // Count total for pagination
        $countBuilder = clone $builder;
        $totalData = (int) $countBuilder->countAllResults(false);
        $totalPages = (int) ceil($totalData / $perPage);

        // Get paginated data
        $jadwal = $builder
            ->orderBy('jk.tanggal_mulai', 'DESC')
            ->limit($perPage, $start)
            ->get()
            ->getResultArray();

        // Kelas options for modal select
        $kelasOptions = model(KelasModel::class)
            ->select('id, nama_kelas')
            ->orderBy('nama_kelas', 'ASC')
            ->findAll();

        return view('layout/admin_layout', [
            'title' => 'Jadwal Kelas',
            'content' => view('admin/jadwal/jadwalkelas', [
                'jadwal' => $jadwal,
                'kelasOptions' => $kelasOptions,
                'perPage' => $perPage,
                'page' => $page,
                'start' => $start,
                'totalPages' => $totalPages,
                'search' => $search,
            ]),
        ]);
    }

    /**
     * Halaman: Jadwal Siswa (SSR kerangka + JS hybrid JSON loading)
     */
    public function siswa()
    {
        // Options kelas untuk filter
        $kelasOptions = model(KelasModel::class)
            ->select('id, nama_kelas')
            ->orderBy('nama_kelas', 'ASC')
            ->findAll();

        return view('layout/admin_layout', [
            'title' => 'Jadwal Siswa',
            'content' => view('admin/jadwal/jadwalsiswa', [
                'kelasOptions' => $kelasOptions,
            ]),
        ]);
    }

    /**
     * Return schedules for a given kelas code as JSON
     */
    public function forKelas()
    {
        $kode = trim((string) $this->request->getGet('kode_kelas'));
        if ($kode === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'kode_kelas diperlukan',
                'data' => [],
            ]);
        }

        $db = \Config\Database::connect();
        $kelas = $db->table('kelas')->select('id')->where('kode_kelas', $kode)->get()->getRowArray();
        if (!$kelas) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Kelas tidak ditemukan',
                'data' => [],
            ]);
        }

        $builder = $db->table('jadwal_kelas')
            ->select('id, tanggal_mulai, tanggal_selesai, lokasi, instruktur, kapasitas')
            ->where('kelas_id', (int) $kelas['id'])
            ->orderBy('tanggal_mulai', 'ASC');

        // Optional: only upcoming schedules
        $builder->where('tanggal_selesai >= CURDATE()', null, false);

        $rows = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $rows,
        ]);
    }

    /**
     * JSON: daftar peserta (registrasi) dengan jadwal & kelas, mendukung filter + paginasi
     */
    public function siswaJson()
    {
        $db = \Config\Database::connect();

        $month = (int) $this->request->getGet('month');
        $year = (int) $this->request->getGet('year');
        $kelasId = (int) $this->request->getGet('kelas_id');
        $jadwalId = (int) $this->request->getGet('jadwal_id');

        $page = max(1, (int) $this->request->getGet('page'));
        $perPage = max(1, min(100, (int) $this->request->getGet('per_page') ?: 10));
        $start = ($page - 1) * $perPage;

        $search = trim((string) $this->request->getGet('search'));
        $sort = strtolower((string) $this->request->getGet('sort'));
        $order = strtolower((string) $this->request->getGet('order')) === 'asc' ? 'ASC' : 'DESC';

        $builder = $db->table('registrasi r')
            ->select('r.id, r.nama, r.no_telp, r.status_pembayaran, r.jadwal_id, r.biaya_total, r.biaya_dibayar, k.nama_kelas, jk.tanggal_mulai, jk.tanggal_selesai, jk.lokasi, jk.instruktur')
            ->join('jadwal_kelas jk', 'r.jadwal_id = jk.id', 'left')
            ->join('kelas k', 'jk.kelas_id = k.id', 'left');

        // Filter tanggal berdasarkan bulan/tahun (gunakan range untuk efisiensi index)
        if ($month > 0 && $year > 0) {
            $startDate = date('Y-m-01', strtotime(sprintf('%04d-%02d-01', $year, $month)));
            $endDate = date('Y-m-t', strtotime($startDate));
            $builder->where('jk.tanggal_mulai >=', $startDate)
                    ->where('jk.tanggal_mulai <=', $endDate);
        } elseif ($year > 0 && $month === 0) {
            $startDate = sprintf('%04d-01-01', $year);
            $endDate = sprintf('%04d-12-31', $year);
            $builder->where('jk.tanggal_mulai >=', $startDate)
                    ->where('jk.tanggal_mulai <=', $endDate);
        }

        if ($kelasId > 0) {
            $builder->where('jk.kelas_id', $kelasId);
        }
        if ($jadwalId > 0) {
            $builder->where('r.jadwal_id', $jadwalId);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('r.nama', $search)
                ->orLike('r.no_telp', $search)
                ->orLike('k.nama_kelas', $search)
                ->orLike('jk.lokasi', $search)
                ->orLike('jk.instruktur', $search)
            ->groupEnd();
        }

        // Sorting whitelist
        $sortMap = [
            'nama' => 'r.nama',
            'tanggal_mulai' => 'jk.tanggal_mulai',
            'nama_kelas' => 'k.nama_kelas',
            'lokasi' => 'jk.lokasi',
            'status_pembayaran' => 'r.status_pembayaran',
        ];
        if (!array_key_exists($sort, $sortMap)) {
            // Default sort
            $builder->orderBy('jk.tanggal_mulai', 'DESC')->orderBy('r.nama', 'ASC');
        } else {
            $builder->orderBy($sortMap[$sort], $order)->orderBy('r.nama', 'ASC');
        }

        // Count total
        $countBuilder = clone $builder;
        $total = (int) $countBuilder->countAllResults(false);
        $totalPages = (int) ceil($total / $perPage);

        // Data paginated
        $rows = $builder->limit($perPage, $start)->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $rows,
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => $totalPages,
                'total' => $total,
            ],
        ]);
    }

    /**
     * JSON: daftar jadwal tersedia (upcoming) dengan kapasitas tersisa
     * Fields: id, tanggal_mulai, tanggal_selesai, lokasi, nama_kelas, kapasitas, jumlah_peserta
     */
    public function availableSchedulesJson()
    {
        $db = \Config\Database::connect();

        // Ambil daftar jadwal upcoming
        $builder = $db->table('jadwal_kelas jk')
            ->select('jk.id, jk.tanggal_mulai, jk.tanggal_selesai, jk.lokasi, jk.kapasitas, k.nama_kelas, COUNT(r.id) AS jumlah_peserta')
            ->join('kelas k', 'jk.kelas_id = k.id', 'left')
            ->join('registrasi r', 'r.jadwal_id = jk.id', 'left')
            ->where('jk.tanggal_mulai > CURDATE()', null, false)
            ->groupBy('jk.id')
            ->orderBy('jk.tanggal_mulai', 'ASC');

        $rows = $builder->get()->getResultArray();

        // Hanya sertakan jadwal dengan slot tersedia
        $available = array_values(array_filter($rows, function ($row) {
            $kap = (int) ($row['kapasitas'] ?? 0);
            $cnt = (int) ($row['jumlah_peserta'] ?? 0);
            return $kap === 0 ? false : ($cnt < $kap);
        }));

        return $this->response->setJSON([
            'success' => true,
            'data' => $available,
        ]);
    }

    public function store()
    {
        $rules = [
            'kelas_id' => 'required|is_natural_no_zero',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
            'lokasi' => 'required|in_list[malang,jogja]',
            'instruktur' => 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('alert', [
                'type' => 'danger',
                'message' => 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()),
            ]);
        }

        $db = \Config\Database::connect();
        $data = [
            'kelas_id' => (int) $this->request->getPost('kelas_id'),
            'tanggal_mulai' => (string) $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => (string) $this->request->getPost('tanggal_selesai'),
            'lokasi' => (string) $this->request->getPost('lokasi'),
            'instruktur' => (string) $this->request->getPost('instruktur'),
            'kapasitas' => (int) $this->request->getPost('kapasitas'),
        ];

        try {
            $db->table('jadwal_kelas')->insert($data);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('alert', [
                'type' => 'danger',
                'message' => 'Gagal menyimpan jadwal: ' . $e->getMessage(),
            ]);
        }

        return redirect()
            ->to(base_url('admin/jadwal'))
            ->with('alert', ['type' => 'success', 'message' => 'Jadwal berhasil ditambahkan']);
    }

    public function update()
    {
        $id = (int) $this->request->getPost('id');
        if ($id <= 0) {
            return redirect()->back()->withInput()->with('alert', [
                'type' => 'danger',
                'message' => 'ID jadwal tidak valid',
            ]);
        }

        $rules = [
            'kelas_id' => 'required|is_natural_no_zero',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
            'lokasi' => 'required|in_list[malang,jogja]',
            'instruktur' => 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('alert', [
                'type' => 'danger',
                'message' => 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()),
            ]);
        }

        $db = \Config\Database::connect();
        $data = [
            'kelas_id' => (int) $this->request->getPost('kelas_id'),
            'tanggal_mulai' => (string) $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => (string) $this->request->getPost('tanggal_selesai'),
            'lokasi' => (string) $this->request->getPost('lokasi'),
            'instruktur' => (string) $this->request->getPost('instruktur'),
            'kapasitas' => (int) $this->request->getPost('kapasitas'),
        ];

        try {
            $db->table('jadwal_kelas')->where('id', $id)->update($data);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('alert', [
                'type' => 'danger',
                'message' => 'Gagal memperbarui jadwal: ' . $e->getMessage(),
            ]);
        }

        return redirect()
            ->to(base_url('admin/jadwal'))
            ->with('alert', ['type' => 'success', 'message' => 'Jadwal berhasil diperbarui']);
    }

    public function delete($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'message' => 'ID jadwal tidak valid',
            ]);
        }

        $db = \Config\Database::connect();
        try {
            $db->table('jadwal_kelas')->where('id', $id)->delete();
        } catch (\Throwable $e) {
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage(),
            ]);
        }

        return redirect()
            ->to(base_url('admin/jadwal'))
            ->with('alert', ['type' => 'success', 'message' => 'Jadwal berhasil dihapus']);
    }
}

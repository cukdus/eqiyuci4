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

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Kelas as KelasModel;
use App\Models\KotaKelas as KotaKelasModel;

class Kelas extends BaseController
{
    public function online()
    {
        $model = model(KelasModel::class);

        $search = trim((string) $this->request->getGet('search'));
        if ($search !== '') {
            $model
                ->groupStart()
                ->like('nama_kelas', $search)
                ->orLike('kode_kelas', $search)
                ->groupEnd();
        }

        // Gabungan: kelas Online, Offline, dan Jasa
        $classes = $model
            ->groupStart()
            ->where('kategori', 'kursusonline')
            ->orWhere('kategori', 'Kursus')
            ->orWhere('kategori', 'Jasa')
            ->groupEnd()
            ->orderBy('kode_kelas', 'ASC')
            ->paginate(10);
        $pager = $model->pager;

        $uri = service('uri');
        $seg2 = $uri->getSegment(2);
        $isModulOnline = ($seg2 === 'modulonline');
        // Jika akses halaman modulonline, batasi dropdown hanya kelas online
        if ($isModulOnline) {
            $classes = $model
                ->where('kategori', 'kursusonline')
                ->orderBy('kode_kelas', 'ASC')
                ->paginate(10);
            $pager = $model->pager;
        }
        $title = $isModulOnline ? 'Modul Kelas Online' : 'Data Kelas';
        $contentView = $isModulOnline ? 'admin/kelas/modulonline' : 'admin/kelas/kelas';

        return view('layout/admin_layout', [
            'title' => $title,
            'content' => view($contentView, [
                'classes' => $classes,
                'pager' => $pager,
                'search' => $search,
            ]),
        ]);
    }

    public function offline()
    {
        // Halaman offline tidak dipakai, arahkan ke halaman gabungan
        return redirect()->to(base_url('admin/kelas'));
    }

    public function create()
    {
        // Form tambah kelas
        $kotaOptions = model(KotaKelasModel::class)
            ->select('kode, nama, status')
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->findAll();
        return view('layout/admin_layout', [
            'title' => 'Tambah Kelas Offline',
            'content' => view('admin/kelas/tambahkelas', [
                'kotaOptions' => $kotaOptions,
            ]),
        ]);
    }

    public function store()
    {
        $rules = [
            'nama_kelas' => 'required|min_length[3]|max_length[100]',
            'kode_kelas' => 'required|min_length[2]|max_length[20]|is_unique[kelas.kode_kelas]',
            'deskripsi_singkat' => 'permit_empty|string',
            'deskripsi' => 'permit_empty|string',
            'harga' => 'required|decimal',
            'durasi' => 'permit_empty|max_length[50]',
            'kategori' => 'required|in_list[Kursus,Jasa,kursusonline]',
            'status_kelas' => 'required|in_list[aktif,nonaktif,segera]',
            'badge' => 'permit_empty|in_list[nobadge,hot,sale]',
            // kota_tersedia dikirim sebagai array checkbox; validasi cukup permit_empty,
            // kemudian diolah menjadi string setelah validasi
            'kota_tersedia' => 'permit_empty',
            'gambar_utama' => 'permit_empty|uploaded[gambar_utama]|is_image[gambar_utama]|max_size[gambar_utama,2048]',
            // gambar_tambahan multiple optional images
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = model(KelasModel::class);

        $namaKelas = (string) $this->request->getPost('nama_kelas');
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', preg_replace('/\s+/', '-', $namaKelas))));

        // kota_tersedia from checkboxes -> comma separated (codes-only)
        $kotaList = $this->request->getPost('kota_tersedia');
        if (is_array($kotaList)) {
            $lower = array_map('strtolower', $kotaList);
            $kotaStr = implode(',', array_values(array_unique($lower)));
        } else {
            $kotaStr = strtolower((string) $kotaList);
            $arr = array_filter(array_map('trim', explode(',', $kotaStr)), static function ($v) {
                return $v !== '';
            });
            $arr = array_map('strtolower', $arr);
            $kotaStr = implode(',', array_values(array_unique($arr)));
        }

        // Handle uploads
        $gambarUtamaPath = null;
        $fileUtama = $this->request->getFile('gambar_utama');
        if ($fileUtama && $fileUtama->isValid() && !$fileUtama->hasMoved()) {
            $targetDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'kelas';
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }
            $newName = 'utama_' . time() . '_' . $fileUtama->getRandomName();
            $fileUtama->move($targetDir, $newName);
            $gambarUtamaPath = 'uploads/kelas/' . $newName;
        }

        // Multiple additional images
        $gambarTambahanPaths = [];
        $filesTambahan = $this->request->getFiles();
        if (!empty($filesTambahan['gambar_tambahan'])) {
            $targetDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'kelas';
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }
            foreach ($filesTambahan['gambar_tambahan'] as $f) {
                if ($f && $f->isValid() && !$f->hasMoved()) {
                    $newName = 'extra_' . time() . '_' . $f->getRandomName();
                    $f->move($targetDir, $newName);
                    $gambarTambahanPaths[] = 'uploads/kelas/' . $newName;
                }
            }
        }

        $now = date('Y-m-d H:i:s');
        $data = [
            'nama_kelas' => $namaKelas,
            'kode_kelas' => (string) $this->request->getPost('kode_kelas'),
            'slug' => $slug,
            'deskripsi_singkat' => (string) $this->request->getPost('deskripsi_singkat'),
            'deskripsi' => (string) $this->request->getPost('deskripsi'),
            'harga' => (string) $this->request->getPost('harga'),
            'durasi' => (string) $this->request->getPost('durasi'),
            'kategori' => (string) $this->request->getPost('kategori'),
            'status_kelas' => (string) $this->request->getPost('status_kelas'),
            'badge' => (string) $this->request->getPost('badge'),
            'kota_tersedia' => $kotaStr,
            'gambar_utama' => $gambarUtamaPath,
            'gambar_tambahan' => !empty($gambarTambahanPaths) ? json_encode($gambarTambahanPaths) : null,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        try {
            $model->insert($data);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan kelas: ' . $e->getMessage());
        }

        // Halaman gabungan: selalu kembali ke admin/kelas
        return redirect()->to(base_url('admin/kelas'))->with('message', 'Kelas berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $model = model(KelasModel::class);
        $kelas = $model->find($id);
        if (!$kelas) {
            return redirect()
                ->to(base_url('admin/kelas'))
                ->with('error', 'Kelas tidak ditemukan');
        }

        // Halaman gabungan: selalu kembali ke admin/kelas
        $backUrl = base_url('admin/kelas');
        $title = 'Edit Kelas';

        $kotaOptions = model(KotaKelasModel::class)
            ->select('kode, nama, status')
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->findAll();

        return view('layout/admin_layout', [
            'title' => $title,
            'content' => view('admin/kelas/editkelas', [
                'kelas' => $kelas,
                'backUrl' => $backUrl,
                'kotaOptions' => $kotaOptions,
            ]),
        ]);
    }

    /**
     * Kota Kelas: halaman daftar & tambah kota
     */
    public function kota()
    {
        $model = model(KotaKelasModel::class);
        $kotaList = $model->orderBy('nama', 'ASC')->findAll();
        return view('layout/admin_layout', [
            'title' => 'Kota Kelas',
            'content' => view('admin/kelas/kotakelas', [
                'kotaList' => $kotaList,
            ]),
        ]);
    }

    public function storeKota()
    {
        $kode = trim((string) $this->request->getPost('kode'));
        $nama = trim((string) $this->request->getPost('nama'));
        $status = trim((string) $this->request->getPost('status'));
        if ($kode === '' || $nama === '' || !in_array($status, ['aktif', 'nonaktif'], true)) {
            return redirect()->back()->with('error', 'Data kota tidak valid');
        }
        $model = model(KotaKelasModel::class);
        try {
            $model->insert([
                'kode' => strtolower($kode),
                'nama' => $nama,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan kota: ' . $e->getMessage());
        }
        return redirect()->to(base_url('admin/kelas/kota'))->with('message', 'Kota berhasil ditambahkan');
    }

    public function deleteKota(int $id)
    {
        $model = model(KotaKelasModel::class);
        if (!$model->find($id)) {
            return redirect()->back()->with('error', 'Kota tidak ditemukan');
        }
        $model->delete($id);
        return redirect()->to(base_url('admin/kelas/kota'))->with('message', 'Kota dihapus');
    }

    public function update(int $id)
    {
        $model = model(KelasModel::class);
        $existing = $model->find($id);
        if (!$existing) {
            return redirect()
                ->to(base_url('admin/kelas'))
                ->with('error', 'Kelas tidak ditemukan');
        }

        $rules = [
            'nama_kelas' => 'required|min_length[3]|max_length[100]',
            // allow same kode_kelas for current record
            'kode_kelas' => 'permit_empty|min_length[2]|max_length[20]|is_unique[kelas.kode_kelas,id,' . $id . ']',
            'deskripsi_singkat' => 'permit_empty|string',
            'deskripsi' => 'permit_empty|string',
            'harga' => 'required|decimal',
            'durasi' => 'permit_empty|max_length[50]',
            'kategori' => 'required|in_list[Kursus,Jasa,kursusonline]',
            'status_kelas' => 'required|in_list[aktif,nonaktif,segera]',
            'badge' => 'permit_empty|in_list[nobadge,hot,sale]',
            'kota_tersedia' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $namaKelas = (string) $this->request->getPost('nama_kelas');
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', preg_replace('/\s+/', '-', $namaKelas))));

        // kota_tersedia from checkboxes -> comma separated (codes-only)
        $kotaList = $this->request->getPost('kota_tersedia');
        if (is_array($kotaList)) {
            $lower = array_map('strtolower', $kotaList);
            $kotaStr = implode(',', array_values(array_unique($lower)));
        } else {
            $kotaStr = strtolower((string) $kotaList);
            $arr = array_filter(array_map('trim', explode(',', $kotaStr)), static function ($v) {
                return $v !== '';
            });
            $arr = array_map('strtolower', $arr);
            $kotaStr = implode(',', array_values(array_unique($arr)));
        }

        // Handle uploads
        $gambarUtamaPath = (string) ($existing['gambar_utama'] ?? '');
        $fileUtama = $this->request->getFile('gambar_utama');
        if ($fileUtama && $fileUtama->isValid() && !$fileUtama->hasMoved()) {
            $targetDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'kelas';
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }
            $newName = 'utama_' . time() . '_' . $fileUtama->getRandomName();
            $fileUtama->move($targetDir, $newName);
            $gambarUtamaPath = 'uploads/kelas/' . $newName;
        }

        // Existing additional images
        $existingExtra = [];
        $rawExtra = $existing['gambar_tambahan'] ?? null;
        if (is_string($rawExtra) && trim($rawExtra) !== '') {
            $decoded = json_decode($rawExtra, true);
            if (is_array($decoded)) {
                $existingExtra = $decoded;
            }
        }

        // Multiple additional images (append)
        $filesTambahan = $this->request->getFiles();
        if (!empty($filesTambahan['gambar_tambahan'])) {
            $targetDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'kelas';
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }
            foreach ($filesTambahan['gambar_tambahan'] as $f) {
                if ($f && $f->isValid() && !$f->hasMoved()) {
                    $newName = 'extra_' . time() . '_' . $f->getRandomName();
                    $f->move($targetDir, $newName);
                    $existingExtra[] = 'uploads/kelas/' . $newName;
                }
            }
        }

        $now = date('Y-m-d H:i:s');
        $postedKode = (string) $this->request->getPost('kode_kelas');
        $finalKode = $postedKode !== '' ? $postedKode : (string) ($existing['kode_kelas'] ?? '');
        $data = [
            'nama_kelas' => $namaKelas,
            'kode_kelas' => $finalKode,
            'slug' => $slug,
            'deskripsi_singkat' => (string) $this->request->getPost('deskripsi_singkat'),
            'deskripsi' => (string) $this->request->getPost('deskripsi'),
            'harga' => (string) $this->request->getPost('harga'),
            'durasi' => (string) $this->request->getPost('durasi'),
            'kategori' => (string) $this->request->getPost('kategori'),
            'status_kelas' => (string) $this->request->getPost('status_kelas'),
            'badge' => (string) $this->request->getPost('badge'),
            'kota_tersedia' => $kotaStr,
            'gambar_utama' => $gambarUtamaPath ?: null,
            'gambar_tambahan' => !empty($existingExtra) ? json_encode($existingExtra) : null,
            'updated_at' => $now,
        ];

        try {
            $model->update($id, $data);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kelas: ' . $e->getMessage());
        }

        // Halaman gabungan: selalu kembali ke admin/kelas
        return redirect()->to(base_url('admin/kelas'))->with('message', 'Kelas berhasil diperbarui');
    }

    public function delete(int $id)
    {
        $model = model(KelasModel::class);
        $exists = $model->find($id);
        if (!$exists) {
            return redirect()
                ->to(base_url('admin/kelas'))
                ->with('error', 'Kelas tidak ditemukan');
        }
        if (!$model->delete($id)) {
            return redirect()
                ->to(base_url('admin/kelas'))
                ->with('error', 'Gagal menghapus kelas.');
        }

        return redirect()
            ->to(base_url('admin/kelas'))
            ->with('message', 'Kelas berhasil dihapus');
    }

    public function deleteImage(int $id)
    {
        $model = model(KelasModel::class);
        $kelas = $model->find($id);
        if (!$kelas) {
            return redirect()
                ->to(base_url('admin/kelas/offline'))
                ->with('error', 'Kelas tidak ditemukan');
        }

        $path = (string) $this->request->getPost('path');
        if ($path === '' || strpos($path, 'uploads/kelas/') !== 0) {
            return redirect()->back()->with('error', 'Path gambar tidak valid');
        }

        // Decode existing additional images
        $images = [];
        $raw = $kelas['gambar_tambahan'] ?? null;
        if (is_string($raw) && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $images = $decoded;
            }
        }

        $idx = array_search($path, $images, true);
        if ($idx === false) {
            return redirect()->back()->with('error', 'Gambar tidak ditemukan pada data');
        }

        // Remove from array
        array_splice($images, $idx, 1);

        // Update record
        $update = [
            'gambar_tambahan' => !empty($images) ? json_encode($images) : null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $model->update($id, $update);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }

        // Attempt file unlink (best-effort)
        $full = FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        if (is_file($full)) {
            @unlink($full);
        }

        // Redirect to list page depending on category
        return redirect()
            ->to(base_url('admin/kelas/' . $id . '/edit'))
            ->with('message', 'Gambar tambahan berhasil dihapus');
    }

    public function deleteImageByIndex(int $id, int $index)
    {
        $model = model(KelasModel::class);
        $kelas = $model->find($id);
        if (!$kelas) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kelas tidak ditemukan'])->setStatusCode(404);
        }

        // Decode existing additional images
        $images = [];
        $raw = $kelas['gambar_tambahan'] ?? null;
        if (is_string($raw) && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $images = $decoded;
            }
        }

        if (!is_int($index) || $index < 0 || $index >= count($images)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Indeks gambar tidak valid'])->setStatusCode(400);
        }

        $path = (string) $images[$index];

        // Remove by index and reindex array
        unset($images[$index]);
        $images = array_values($images);

        // Update record
        $update = [
            'gambar_tambahan' => !empty($images) ? json_encode($images) : null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $model->update($id, $update);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui data: ' . $e->getMessage()])->setStatusCode(500);
        }

        // Attempt file unlink (best-effort)
        if ($path !== '') {
            $full = FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
            if (is_file($full)) {
                @unlink($full);
            }
        }

        return $this->response->setJSON(['success' => true]);
    }
}

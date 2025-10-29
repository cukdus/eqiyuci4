<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Kelas as KelasModel;
use App\Models\BonusFile;
use CodeIgniter\HTTP\ResponseInterface;

class BonusKelas extends BaseController
{
    public function index()
    {
        // Ambil semua kelas tanpa filter kategori agar semua terlihat
        $classes = model(KelasModel::class)
            ->select('id, nama_kelas, kode_kelas')
            ->orderBy('nama_kelas', 'ASC')
            ->findAll();

        return view('layout/admin_layout', [
            'title' => 'Bonus Kelas',
            'content' => view('admin/kelas/bonuskelas', [
                'classes' => $classes,
            ]),
        ]);
    }

    /**
     * List semua bonus file lintas kelas untuk keperluan attach ulang
     */
    public function listAll(): ResponseInterface
    {
        $db = \Config\Database::connect();
        $files = $db->table('bonus_file bf')
            ->select('bf.id, bf.tipe, bf.judul_file, bf.file_url, bf.urutan, k.id as kelas_id, k.nama_kelas, k.kode_kelas')
            ->join('kelas k', 'k.id = bf.kelas_id', 'left')
            ->orderBy('bf.id', 'DESC')
            ->limit(300)
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $files]);
    }

    /**
     * Attach file bonus yang sudah ada (dari kelas lain) ke kelas yang dipilih
     */
    public function attachExisting(int $kelasId): ResponseInterface
    {
        $kelas = model(\App\Models\Kelas::class)->find($kelasId);
        if (! $kelas) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kelas tidak ditemukan']);
        }

        $sourceId = (int) $this->request->getPost('source_file_id');
        if ($sourceId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'File sumber tidak valid']);
        }

        $src = model(BonusFile::class)->find($sourceId);
        if (! $src || empty($src['file_url'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'File sumber tidak ditemukan']);
        }

        $judulOverride = trim((string) $this->request->getPost('judul_file'));
        $urutanPosted = $this->request->getPost('urutan');
        $urutan = null;
        if ($urutanPosted !== null && $urutanPosted !== '') {
            $urutan = (int) $urutanPosted;
        }

        $newId = model(BonusFile::class)->insert([
            'kelas_id'   => $kelasId,
            'tipe'       => (string) ($src['tipe'] ?? 'file'),
            'judul_file' => $judulOverride !== '' ? $judulOverride : (string) ($src['judul_file'] ?? ''),
            'file_url'   => (string) $src['file_url'],
            'urutan'     => $urutan,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['success' => true, 'id' => $newId, 'file_url' => (string) $src['file_url']]);
    }

    public function list(): ResponseInterface
    {
        $kelasId = (int) $this->request->getGet('kelas_id');
        if ($kelasId <= 0) {
            return $this->response->setJSON(['data' => []]);
        }
        $files = model(BonusFile::class)
            ->where('kelas_id', $kelasId)
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'DESC')
            ->findAll();
        return $this->response->setJSON(['data' => $files]);
    }

    public function uploadFile(int $kelasId): ResponseInterface
    {
        $kelas = model(KelasModel::class)->find($kelasId);
        if (! $kelas) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kelas tidak ditemukan']);
        }

        $judul = trim((string) $this->request->getPost('judul_file'));
        $urutan = $this->request->getPost('urutan') !== null ? (int) $this->request->getPost('urutan') : null;
        $upload = $this->request->getFile('file');
        if (! $upload || ! $upload->isValid() || $upload->hasMoved()) {
            return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid atau sudah dipindah']);
        }

        $ext = strtolower((string) $upload->getClientExtension());
        $allowedExt = ['pdf', 'xls', 'xlsx'];
        if (! in_array($ext, $allowedExt, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Hanya file PDF atau Excel (xls/xlsx) yang diizinkan']);
        }

        $targetDir = FCPATH . 'uploads/kelas/bonus';
        if (! is_dir($targetDir)) {
            @mkdir($targetDir, 0775, true);
        }
        $newName = $upload->getRandomName();
        try {
            $upload->move($targetDir, $newName);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menyimpan file']);
        }
        $publicPath = '/uploads/kelas/bonus/' . $newName;

        $id = model(BonusFile::class)->insert([
            'kelas_id'   => $kelasId,
            'tipe'       => $ext === 'pdf' ? 'pdf' : 'excel',
            'judul_file' => $judul !== '' ? $judul : $upload->getClientName(),
            'file_url'   => $publicPath,
            'urutan'     => $urutan,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['success' => true, 'id' => $id, 'file_url' => $publicPath]);
    }

    public function deleteFile(int $fileId): ResponseInterface
    {
        $model = model(BonusFile::class);
        $file = $model->find($fileId);
        if (! $file) {
            return $this->response->setJSON(['success' => false, 'message' => 'File tidak ditemukan']);
        }
        // Jika file fisik juga dipakai oleh record lain, jangan hapus fisiknya
        $shouldDeletePhysical = false;
        if (! empty($file['file_url'])) {
            $sameCount = $model
                ->where('file_url', (string) $file['file_url'])
                ->where('id !=', $fileId)
                ->countAllResults();
            if ($sameCount === 0) {
                $path = FCPATH . ltrim((string) $file['file_url'], '/');
                if (is_file($path)) {
                    $shouldDeletePhysical = true;
                    @unlink($path);
                }
            }
        }
        $model->delete($fileId);
        return $this->response->setJSON(['success' => true, 'deleted_physical' => $shouldDeletePhysical]);
    }
}
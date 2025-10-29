<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseOnline;
use App\Models\ModulFile;
use CodeIgniter\HTTP\ResponseInterface;

class ModulOnline extends BaseController
{
    public function list(): ResponseInterface
    {
        $kelasId = (int) $this->request->getGet('kelas_id');
        $search = trim((string) $this->request->getGet('search'));
        $db = \Config\Database::connect();
        $builder = $db->table('course_online co')
            ->select('co.*, k.nama_kelas')
            ->join('kelas k', 'k.id = co.kelas_id', 'left');

        if ($kelasId > 0) {
            $builder->where('co.kelas_id', $kelasId);
        }
        if ($search !== '') {
            $builder->groupStart()
                ->like('co.judul_modul', $search)
                ->orLike('co.deskripsi', $search)
            ->groupEnd();
        }
        $builder->orderBy('co.kelas_id', 'ASC')->orderBy('co.urutan', 'ASC')->orderBy('co.id', 'DESC');
        $modules = $builder->get()->getResultArray();

        // Attach files for each module
        $fileModel = model(ModulFile::class);
        foreach ($modules as &$m) {
            $m['files'] = $fileModel->where('course_id', $m['id'])->orderBy('urutan', 'ASC')->findAll();
        }

        return $this->response->setJSON(['data' => $modules]);
    }

    public function store(): ResponseInterface
    {
        $data = [
            'kelas_id'    => (int) $this->request->getPost('kelas_id'),
            'judul_modul' => trim((string) $this->request->getPost('judul_modul')),
            'deskripsi'   => trim((string) $this->request->getPost('deskripsi')),
            'urutan'      => $this->request->getPost('urutan') !== null ? (int) $this->request->getPost('urutan') : null,
        ];
        if ($data['kelas_id'] <= 0 || $data['judul_modul'] === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'kelas_id dan judul_modul wajib diisi']);
        }
        $model = model(CourseOnline::class);
        $id = $model->insert($data);
        return $this->response->setJSON(['success' => true, 'id' => $id]);
    }

    public function update(int $id): ResponseInterface
    {
        $model = model(CourseOnline::class);
        $exists = $model->find($id);
        if (! $exists) {
            return $this->response->setJSON(['success' => false, 'message' => 'Modul tidak ditemukan']);
        }
        $data = [
            'judul_modul' => trim((string) $this->request->getPost('judul_modul')),
            'deskripsi'   => trim((string) $this->request->getPost('deskripsi')),
            'urutan'      => $this->request->getPost('urutan') !== null ? (int) $this->request->getPost('urutan') : null,
        ];
        $model->update($id, $data);
        return $this->response->setJSON(['success' => true]);
    }

    public function delete(int $id): ResponseInterface
    {
        $model = model(CourseOnline::class);
        $fileModel = model(ModulFile::class);
        $module = $model->find($id);
        if (! $module) {
            return $this->response->setJSON(['success' => false, 'message' => 'Modul tidak ditemukan']);
        }
        // Delete files and unlink physical files if inside uploads
        $files = $fileModel->where('course_id', $id)->findAll();
        foreach ($files as $f) {
            if ($f['tipe'] !== 'youtube' && ! empty($f['file_url'])) {
                $path = FCPATH . ltrim($f['file_url'], '/');
                if (is_file($path)) {
                    @unlink($path);
                }
            }
        }
        $fileModel->where('course_id', $id)->delete();
        $model->delete($id);
        return $this->response->setJSON(['success' => true]);
    }

    public function files(): ResponseInterface
    {
        $courseId = (int) $this->request->getGet('course_id');
        if ($courseId <= 0) {
            return $this->response->setJSON(['data' => []]);
        }
        $files = model(ModulFile::class)->where('course_id', $courseId)->orderBy('urutan', 'ASC')->findAll();
        return $this->response->setJSON(['data' => $files]);
    }

    public function uploadFile(int $courseId): ResponseInterface
    {
        $course = model(CourseOnline::class)->find($courseId);
        if (! $course) {
            return $this->response->setJSON(['success' => false, 'message' => 'Modul tidak ditemukan']);
        }

        $tipe = strtolower(trim((string) $this->request->getPost('tipe')));
        $judul = trim((string) $this->request->getPost('judul_file'));
        $urutan = $this->request->getPost('urutan') !== null ? (int) $this->request->getPost('urutan') : null;

        $fileModel = model(ModulFile::class);

        if ($tipe === 'youtube') {
            $url = trim((string) $this->request->getPost('link_url'));
            if ($url === '') {
                return $this->response->setJSON(['success' => false, 'message' => 'Link YouTube wajib diisi']);
            }
            $id = $fileModel->insert([
                'course_id'  => $courseId,
                'tipe'       => 'youtube',
                'judul_file' => $judul !== '' ? $judul : 'YouTube',
                'file_url'   => $url,
                'urutan'     => $urutan,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->response->setJSON(['success' => true, 'id' => $id]);
        }

        // Handle file upload untuk PDF/Excel tanpa fileinfo
        $upload = $this->request->getFile('file');
        if (! $upload || ! $upload->isValid() || $upload->hasMoved()) {
            return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid atau sudah dipindah']);
        }

        // Validasi berdasarkan ekstensi untuk hindari finfo_file di Windows tmp
        $ext = strtolower((string) $upload->getClientExtension());
        $allowedExt = ['pdf', 'xls', 'xlsx'];
        if (! in_array($ext, $allowedExt, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Hanya file PDF atau Excel (xls/xlsx) yang diizinkan']);
        }

        $targetDir = FCPATH . 'uploads/kelas/modul';
        if (! is_dir($targetDir)) {
            @mkdir($targetDir, 0775, true);
        }
        $newName = $upload->getRandomName();
        try {
            $upload->move($targetDir, $newName);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menyimpan file']);
        }
        $publicPath = '/uploads/kelas/modul/' . $newName;

        $id = $fileModel->insert([
            'course_id'  => $courseId,
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
        $fileModel = model(ModulFile::class);
        $file = $fileModel->find($fileId);
        if (! $file) {
            return $this->response->setJSON(['success' => false, 'message' => 'File tidak ditemukan']);
        }
        if ($file['tipe'] !== 'youtube' && ! empty($file['file_url'])) {
            $path = FCPATH . ltrim($file['file_url'], '/');
            if (is_file($path)) {
                @unlink($path);
            }
        }
        $fileModel->delete($fileId);
        return $this->response->setJSON(['success' => true]);
    }
}
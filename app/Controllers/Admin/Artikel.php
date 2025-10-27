<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\Tag;
use CodeIgniter\HTTP\RedirectResponse;

class Artikel extends BaseController
{
    public function index()
    {
        $model = model(Berita::class);
        $articles = $model
            ->select('berita.*, kategori_berita.nama_kategori AS kategori_nama')
            ->join('kategori_berita', 'kategori_berita.id = berita.kategori_id', 'left')
            ->orderBy('tanggal_terbit', 'DESC')
            ->paginate(10);
        $pager = $model->pager;

        return view('layout/admin_layout', [
            'title' => 'Data Artikel',
            'content' => view('admin/artikel/dataartikel', [
                'articles' => $articles,
                'pager' => $pager,
            ]),
        ]);
    }

    public function create()
    {
        $kategoriModel = model(KategoriBerita::class);
        $categories = $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        // Ambil nama penulis dari user yang sedang login
        $me = service('authentication')->user();
        $penulisDefault = '';
        if ($me) {
            // Prioritaskan nama lengkap jika ada, fallback ke username
            $penulisDefault = (string) ($me->nama_lengkap ?? $me->username ?? '');
        }

        return view('layout/admin_layout', [
            'title' => 'Tambah Artikel',
            'content' => view('admin/artikel/tambahartikel', [
                'categories' => $categories,
                'penulisDefault' => $penulisDefault,
            ]),
        ]);
    }

    public function store(): RedirectResponse
    {
        $model = model(Berita::class);
        helper('text');

        $judul = trim((string) $this->request->getPost('judul'));
        $konten = (string) $this->request->getPost('konten');
        // Paksa nilai penulis dari user yang login untuk konsistensi
        $me = service('authentication')->user();
        $penulis = '';
        if ($me) {
            $penulis = trim((string) ($me->nama_lengkap ?? $me->username ?? ''));
        } else {
            // Fallback jika tidak ada sesi user (seharusnya terlindungi oleh filter login)
            $penulis = trim((string) $this->request->getPost('penulis'));
        }
        $statusInput = $this->request->getPost('status');
        $kategoriId = $this->request->getPost('kategori_id');
        $tanggalTerbitRaw = (string) $this->request->getPost('tanggal_terbit');

        // Normalisasi tanggal terbit: gunakan jam default 11:00
        // Jika input hanya berisi tanggal (YYYY-MM-DD), gabungkan dengan 11:00:00
        // Jika kosong, set ke hari ini pukul 11:00:00
        if ($tanggalTerbitRaw) {
            // Deteksi format: jika mengandung 'T' atau spasi berarti punya waktu, jika tidak hanya tanggal
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalTerbitRaw)) {
                $tanggalTerbit = $tanggalTerbitRaw . ' 11:00:00';
            } else {
                // Format lain (misal datetime-local), tetap pakai jam bagian input namun fallback menit/detik
                $tanggalTerbit = str_replace('T', ' ', $tanggalTerbitRaw);
                if (!preg_match('/:\d{2}$/', $tanggalTerbit)) {
                    $tanggalTerbit .= ':00';
                }
            }
        } else {
            $tanggalTerbit = date('Y-m-d') . ' 11:00:00';
        }

        // Tentukan status otomatis berdasarkan tanggal terbit
        $statusAuto = 'draft';
        try {
            $dtTarget = new \DateTime($tanggalTerbit);
            $now = new \DateTime('now');
            $statusAuto = ($dtTarget <= $now) ? 'publish' : 'draft';
        } catch (\Throwable $e) {
            // Jika parsing gagal, fallback ke draft
            $statusAuto = 'draft';
        }

        // Generate slug unik dari judul
        $baseSlug = url_title($judul, '-', true);
        $slug = $baseSlug ?: uniqid('artikel-');
        $i = 2;
        while ($model->where('slug', $slug)->countAllResults() > 0) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        $data = [
            'judul' => $judul,
            'slug' => $slug,
            'konten' => $konten,
            'penulis' => $penulis,
            'tanggal_terbit' => $tanggalTerbit,
            'status' => $statusAuto,
            'kategori_id' => $kategoriId ?: null,
        ];

        // Proses upload gambar utama jika ada
        $file = $this->request->getFile('gambar_utama');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $mime = $file->getMimeType();
            $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (in_array($mime, $allowed, true)) {
                // Batasi ukuran ~2MB
                if ($file->getSize() <= (2 * 1024 * 1024)) {
                    $targetDir = FCPATH . 'uploads/artikel';
                    if (!is_dir($targetDir)) {
                        @mkdir($targetDir, 0755, true);
                    }
                    $newName = $file->getRandomName();
                    try {
                        $file->move($targetDir, $newName);
                        $data['gambar_utama'] = 'uploads/artikel/' . $newName;
                    } catch (\Throwable $e) {
                        // Jika gagal memindahkan, abaikan tanpa menggagalkan simpan artikel
                        // Bisa ditingkatkan dengan logging
                    }
                }
            }
        }

        if (!$model->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan artikel.')
                ->with('errors', $model->errors());
        }

        // Simpan relasi tag ke pivot berita_tag
        $beritaId = $model->getInsertID();
        $tagsInput = (string) $this->request->getPost('tags');
        if ($beritaId && $tagsInput !== '') {
            $raw = array_filter(array_map('trim', explode(',', $tagsInput)), function($v){ return $v !== ''; });
            if (!empty($raw)) {
                $db = \Config\Database::connect();
                $pivot = $db->table('berita_tag');
                $tagModel = model(Tag::class);

                foreach ($raw as $nama) {
                    // Normalisasi sederhana: huruf kecil, hapus spasi berlebih
                    $norm = preg_replace('/\s+/', ' ', mb_strtolower($nama));
                    if ($norm === '') { continue; }
                    // Cari tag, jika belum ada buat
                    $exists = $tagModel->where('nama_tag', $norm)->first();
                    if (!$exists) {
                        $tagModel->insert(['nama_tag' => $norm]);
                        $tagId = $tagModel->getInsertID();
                    } else {
                        $tagId = is_array($exists) ? ($exists['id'] ?? null) : (is_object($exists) ? ($exists->id ?? null) : null);
                    }
                    if ($tagId) {
                        // Hindari duplikasi di pivot
                        $already = $pivot->where(['berita_id' => $beritaId, 'tag_id' => $tagId])->countAllResults();
                        if ($already == 0) {
                            $pivot->insert(['berita_id' => $beritaId, 'tag_id' => $tagId]);
                        }
                        // Reset builder untuk query berikutnya
                        $pivot->resetQuery();
                    }
                }
            }
        }

        return redirect()->to(base_url('admin/artikel'))
            ->with('message', 'Artikel berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $model = model(Berita::class);
        $artikel = $model->find($id);
        if (!$artikel) {
            return redirect()->to(base_url('admin/artikel'))
                ->with('error', 'Artikel tidak ditemukan');
        }

        // Ambil kategori untuk dropdown
        $kategoriModel = model(KategoriBerita::class);
        $categories = $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        // Ambil tag terkait artikel dan gabungkan sebagai string koma
        $db = \Config\Database::connect();
        $rows = $db->table('berita_tag bt')
            ->select('t.nama_tag')
            ->join('tag t', 't.id = bt.tag_id', 'left')
            ->where('bt.berita_id', $id)
            ->get()->getResultArray();
        $tagNames = array_map(static function($r){ return (string) ($r['nama_tag'] ?? ''); }, $rows);
        $tagsStr = implode(', ', array_filter($tagNames, static function($v){ return trim($v) !== ''; }));

        return view('layout/admin_layout', [
            'title' => 'Edit Artikel',
            'content' => view('admin/artikel/editartikel', [
                'article' => $artikel,
                'categories' => $categories,
                'tagsStr' => $tagsStr,
            ]),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $model = model(Berita::class);
        $original = $model->find($id);
        if (!$original) {
            return redirect()->to(base_url('admin/artikel'))
                ->with('error', 'Artikel tidak ditemukan');
        }

        helper('text');

        $judul = trim((string) $this->request->getPost('judul'));
        $konten = (string) $this->request->getPost('konten');
        $kategoriId = $this->request->getPost('kategori_id');
        $tanggalTerbitRaw = $this->request->getPost('tanggal_terbit');

        // Paksa nilai penulis dari user yang login untuk konsistensi
        $me = service('authentication')->user();
        $penulis = '';
        if ($me) {
            $penulis = trim((string) ($me->nama_lengkap ?? $me->username ?? ''));
        } else {
            $penulis = trim((string) $this->request->getPost('penulis'));
        }

        // Normalisasi datetime-local (YYYY-MM-DDTHH:MM) ke format DB (YYYY-MM-DD HH:MM:SS)
        if ($tanggalTerbitRaw) {
            $tanggalTerbit = str_replace('T', ' ', $tanggalTerbitRaw);
            if (!preg_match('/:\\d{2}$/', $tanggalTerbit)) {
                $tanggalTerbit .= ':00';
            }
        } else {
            $tanggalTerbit = (string) ($original['tanggal_terbit'] ?? date('Y-m-d H:i:s'));
        }

        // Tentukan status otomatis berdasarkan tanggal terbit
        $statusAuto = 'draft';
        try {
            if ($tanggalTerbitRaw) {
                $dtTarget = new \DateTime($tanggalTerbitRaw);
                $now = new \DateTime('now');
                $statusAuto = ($dtTarget <= $now) ? 'publish' : 'draft';
            } else {
                // Jika tidak diisi, gunakan status saat ini
                $statusAuto = (string) ($original['status'] ?? 'draft');
            }
        } catch (\Throwable $e) {
            $statusAuto = (string) ($original['status'] ?? 'draft');
        }

        // Slug: update jika judul berubah, dengan memastikan unik (kecuali id saat ini)
        $slug = (string) ($original['slug'] ?? '');
        if ($judul !== (string) ($original['judul'] ?? '')) {
            $baseSlug = url_title($judul, '-', true);
            $slug = $baseSlug ?: uniqid('artikel-');
            $i = 2;
            while ($model->where('slug', $slug)->where('id !=', $id)->countAllResults() > 0) {
                $slug = $baseSlug . '-' . $i;
                $i++;
            }
        }

        $data = [
            'judul' => $judul,
            'slug' => $slug,
            'konten' => $konten,
            'penulis' => $penulis,
            'tanggal_terbit' => $tanggalTerbit,
            'status' => $statusAuto,
            'kategori_id' => $kategoriId ?: null,
        ];

        // Proses upload gambar utama jika ada file baru
        $file = $this->request->getFile('gambar_utama');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $mime = $file->getMimeType();
            $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (in_array($mime, $allowed, true)) {
                if ($file->getSize() <= (2 * 1024 * 1024)) {
                    $targetDir = FCPATH . 'uploads/artikel';
                    if (!is_dir($targetDir)) {
                        @mkdir($targetDir, 0755, true);
                    }
                    $newName = $file->getRandomName();
                    try {
                        $file->move($targetDir, $newName);
                        $data['gambar_utama'] = 'uploads/artikel/' . $newName;
                    } catch (\Throwable $e) {
                        // Abaikan kegagalan move
                    }
                }
            }
        }

        // Atur aturan validasi slug agar mengabaikan ID saat ini
        try {
            if (method_exists($model, 'getValidationRules') && method_exists($model, 'setValidationRules')) {
                $rules = $model->getValidationRules();
                if ($judul === (string) ($original['judul'] ?? '')) {
                    // Judul tidak berubah: cukup cek panjang slug, tanpa is_unique
                    $rules['slug'] = 'required|min_length[5]|max_length[150]';
                } else {
                    // Judul berubah: pastikan unik dengan mengabaikan baris saat ini
                    $rules['slug'] = "required|min_length[5]|max_length[150]|is_unique[berita.slug,id,{$id}]";
                }
                $model->setValidationRules($rules);
            }
        } catch (\Throwable $e) {
            // Abaikan jika metode tidak tersedia; fallback ke 'id' di $data sudah ditambahkan
        }

        if (!$model->update($id, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui artikel.')
                ->with('errors', $model->errors());
        }

        // Sinkronisasi tag di pivot berita_tag
        $tagsInput = (string) $this->request->getPost('tags');
        $db = \Config\Database::connect();
        $pivot = $db->table('berita_tag');
        $tagModel = model(Tag::class);

        $targetTagIds = [];
        $raw = array_filter(array_map('trim', explode(',', $tagsInput)), static function($v){ return $v !== ''; });
        foreach ($raw as $nama) {
            $norm = preg_replace('/\s+/', ' ', mb_strtolower($nama));
            if ($norm === '') { continue; }
            $exists = $tagModel->where('nama_tag', $norm)->first();
            if (!$exists) {
                $tagModel->insert(['nama_tag' => $norm]);
                $tagId = $tagModel->getInsertID();
            } else {
                $tagId = is_array($exists) ? ($exists['id'] ?? null) : (is_object($exists) ? ($exists->id ?? null) : null);
            }
            if ($tagId) {
                $targetTagIds[] = (int) $tagId;
            }
        }

        // Ambil tag_id yang sudah terhubung
        $existingRows = $pivot->where('berita_id', $id)->get()->getResultArray();
        $pivot->resetQuery();
        $existingTagIds = array_map(static function($r){ return (int) ($r['tag_id'] ?? 0); }, $existingRows);

        // Hapus relasi yang tidak ada lagi
        if (!empty($existingTagIds)) {
            foreach ($existingTagIds as $tid) {
                if (!in_array($tid, $targetTagIds, true)) {
                    $pivot->where(['berita_id' => $id, 'tag_id' => $tid])->delete();
                    $pivot->resetQuery();
                }
            }
        }

        // Tambah relasi yang baru
        foreach ($targetTagIds as $tid) {
            $already = $pivot->where(['berita_id' => $id, 'tag_id' => $tid])->countAllResults();
            $pivot->resetQuery();
            if ($already == 0) {
                $pivot->insert(['berita_id' => $id, 'tag_id' => $tid]);
                $pivot->resetQuery();
            }
        }

        return redirect()->to(base_url('admin/artikel'))
            ->with('message', 'Artikel berhasil diperbarui');
    }

    /**
     * Handle Summernote image uploads and return URL as JSON.
     */
    public function uploadImage()
    {
        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => 'File tidak valid']);
        }

        $mime = $file->getMimeType();
        $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!in_array($mime, $allowed, true)) {
            return $this->response->setStatusCode(415)
                ->setJSON(['error' => 'Format gambar tidak didukung']);
        }

        // Batas ukuran 3MB
        if ($file->getSize() > (3 * 1024 * 1024)) {
            return $this->response->setStatusCode(413)
                ->setJSON(['error' => 'Ukuran gambar terlalu besar (maks 3MB)']);
        }

        $targetDir = FCPATH . 'uploads/artikel/konten';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $newName = $file->getRandomName();
        try {
            $file->move($targetDir, $newName);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)
                ->setJSON(['error' => 'Gagal menyimpan gambar']);
        }

        $relativePath = 'uploads/artikel/konten/' . $newName;
        $url = base_url($relativePath);

        return $this->response->setJSON([
            'url' => $url,
            'path' => $relativePath,
            'filename' => $newName,
        ]);
    }

    public function duplicate($id)
    {
        $model = model(Berita::class);
        $original = $model->find($id);
        if (!$original) {
            return redirect()->back()->with('error', 'Artikel tidak ditemukan');
        }

        $newData = $original;
        unset($newData['id']);
        $newData['judul'] = ($original['judul'] ?? 'Artikel') . ' (Copy)';
        $suffix = date('YmdHis');
        $newData['slug'] = ($original['slug'] ?? 'artikel') . '-' . $suffix;
        // Optional: set status draft
        $newData['status'] = 'draft';

        if (!$model->insert($newData)) {
            return redirect()->back()->with('error', 'Gagal menduplikasi artikel');
        }

        return redirect()->to(site_url('admin/artikel'))->with('message', 'Artikel berhasil diduplikasi');
    }

    public function delete($id)
    {
        $model = model(Berita::class);
        if (!$model->delete($id)) {
            return redirect()->back()->with('error', 'Gagal menghapus artikel');
        }
        return redirect()->to(site_url('admin/artikel'))->with('message', 'Artikel berhasil dihapus');
    }
}
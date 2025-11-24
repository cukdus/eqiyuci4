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

    /**
     * Mengembalikan daftar tag (nama_tag) sebagai JSON untuk auto-complete.
     */
    public function tagsJson()
    {
        $this->response->setContentType('application/json');
        try {
            $tagModel = model(Tag::class);
            $rows = $tagModel
                ->select('nama_tag')
                ->orderBy('nama_tag', 'ASC')
                ->findAll();
            $tags = array_values(array_filter(array_map(static function ($r) {
                $v = (string) ($r['nama_tag'] ?? '');
                return trim($v) !== '' ? $v : null;
            }, $rows)));
            return $this->response->setJSON([
                'ok' => true,
                'count' => count($tags),
                'tags' => $tags,
            ]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'ok' => false,
                'error' => 'Gagal mengambil data tag',
            ]);
        }
    }

    /**
     * JSON endpoint: list articles with pagination and optional search.
     * GET params: page (int), per_page (int), search (string)
     */
    public function listJson()
    {
        $model = model(Berita::class);

        $perPage = (int) ($this->request->getGet('per_page') ?? 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }
        if ($perPage > 50) {
            $perPage = 50;
        }
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $start = ($page - 1) * $perPage;
        $search = trim((string) ($this->request->getGet('search') ?? ''));

        $builder = $model
            ->select('berita.*, kategori_berita.nama_kategori AS kategori_nama')
            ->join('kategori_berita', 'kategori_berita.id = berita.kategori_id', 'left');

        if ($search !== '') {
            $builder
                ->groupStart()
                ->like('berita.judul', $search)
                ->orLike('berita.penulis', $search)
                ->orLike('kategori_berita.nama_kategori', $search)
                ->groupEnd();
        }

        // Count total results (without resetting builder state)
        $countBuilder = clone $builder;
        $totalData = (int) $countBuilder->countAllResults(false);
        $totalPages = (int) ceil($totalData / $perPage);

        // Fetch paginated rows
        $rows = $builder
            ->orderBy('berita.tanggal_terbit', 'DESC')
            ->limit($perPage, $start)
            ->get()
            ->getResultArray();

        // Normalize output
        $data = array_map(static function (array $r) {
            return [
                'id' => (int) ($r['id'] ?? 0),
                'judul' => (string) ($r['judul'] ?? ''),
                'penulis' => (string) ($r['penulis'] ?? ''),
                'kategori_nama' => (string) ($r['kategori_nama'] ?? ''),
                'tanggal_terbit' => (string) ($r['tanggal_terbit'] ?? ''),
            ];
        }, $rows);

        return $this->response->setJSON([
            'success' => true,
            'data' => $data,
            'meta' => [
                'perPage' => $perPage,
                'page' => $page,
                'totalPages' => $totalPages,
                'totalData' => $totalData,
            ],
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

        $statusAuto = 'publish';

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

        // Proses upload gambar utama dengan kompresi jika ada
        $file = $this->request->getFile('gambar_utama');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $mime = (string) $file->getMimeType();
            $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (in_array($mime, $allowed, true)) {
                // Batas ukuran file mentah sebelum kompresi: 2MB
                if ($file->getSize() <= (2 * 1024 * 1024)) {
                    $saved = $this->saveCompressedImage($file, 'uploads/artikel', 1280, 1280);
                    if ($saved) {
                        $data['gambar_utama'] = $saved;
                    }
                }
            }
        }

        if (!$model->insert($data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan artikel.')
                ->with('errors', $model->errors());
        }

        // Simpan relasi tag ke pivot berita_tag
        $beritaId = $model->getInsertID();
        $tagsInput = (string) $this->request->getPost('tags');
        if ($beritaId && $tagsInput !== '') {
            $raw = array_filter(array_map('trim', explode(',', $tagsInput)), function ($v) {
                return $v !== '';
            });
            if (!empty($raw)) {
                $db = \Config\Database::connect();
                $pivot = $db->table('berita_tag');
                $tagModel = model(Tag::class);

                foreach ($raw as $nama) {
                    // Normalisasi sederhana: huruf kecil, hapus spasi berlebih
                    $norm = preg_replace('/\s+/', ' ', mb_strtolower($nama));
                    if ($norm === '') {
                        continue;
                    }
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

        return redirect()
            ->to(base_url('admin/artikel'))
            ->with('message', 'Artikel berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $model = model(Berita::class);
        $artikel = $model->find($id);
        if (!$artikel) {
            return redirect()
                ->to(base_url('admin/artikel'))
                ->with('error', 'Artikel tidak ditemukan');
        }

        // Ambil kategori untuk dropdown
        $kategoriModel = model(KategoriBerita::class);
        $categories = $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll();

        // Ambil tag terkait artikel dan gabungkan sebagai string koma
        $db = \Config\Database::connect();
        $rows = $db
            ->table('berita_tag bt')
            ->select('t.nama_tag')
            ->join('tag t', 't.id = bt.tag_id', 'left')
            ->where('bt.berita_id', $id)
            ->get()
            ->getResultArray();
        $tagNames = array_map(static function ($r) {
            return (string) ($r['nama_tag'] ?? '');
        }, $rows);
        $tagsStr = implode(', ', array_filter($tagNames, static function ($v) {
            return trim($v) !== '';
        }));

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
            return redirect()
                ->to(base_url('admin/artikel'))
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
            if (!preg_match('/:\d{2}$/', $tanggalTerbit)) {
                $tanggalTerbit .= ':00';
            }
        } else {
            $tanggalTerbit = (string) ($original['tanggal_terbit'] ?? date('Y-m-d H:i:s'));
        }

        $statusAuto = 'publish';

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

        // Proses upload gambar utama dengan kompresi jika ada file baru
        $file = $this->request->getFile('gambar_utama');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $mime = (string) $file->getMimeType();
            $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (in_array($mime, $allowed, true)) {
                // Batas ukuran file mentah sebelum kompresi: 2MB
                if ($file->getSize() <= (2 * 1024 * 1024)) {
                    $saved = $this->saveCompressedImage($file, 'uploads/artikel', 1280, 1280);
                    if ($saved) {
                        $data['gambar_utama'] = $saved;
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
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui artikel.')
                ->with('errors', $model->errors());
        }

        // Sinkronisasi tag di pivot berita_tag
        $tagsInput = (string) $this->request->getPost('tags');
        $db = \Config\Database::connect();
        $pivot = $db->table('berita_tag');
        $tagModel = model(Tag::class);

        $targetTagIds = [];
        $raw = array_filter(array_map('trim', explode(',', $tagsInput)), static function ($v) {
            return $v !== '';
        });
        foreach ($raw as $nama) {
            $norm = preg_replace('/\s+/', ' ', mb_strtolower($nama));
            if ($norm === '') {
                continue;
            }
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
        $existingTagIds = array_map(static function ($r) {
            return (int) ($r['tag_id'] ?? 0);
        }, $existingRows);

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

        return redirect()
            ->to(base_url('admin/artikel'))
            ->with('message', 'Artikel berhasil diperbarui');
    }

    /**
     * Handle Summernote image uploads and return URL as JSON.
     */
    public function uploadImage()
    {
        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            return $this
                ->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'File tidak valid']);
        }

        $mime = (string) $file->getMimeType();
        $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!in_array($mime, $allowed, true)) {
            return $this
                ->response
                ->setStatusCode(415)
                ->setJSON(['error' => 'Format gambar tidak didukung']);
        }

        // Batas ukuran file mentah hingga 2MB, lalu kompres
        if ($file->getSize() > (2 * 1024 * 1024)) {
            return $this
                ->response
                ->setStatusCode(413)
                ->setJSON(['error' => 'Ukuran gambar terlalu besar (maks 2MB)']);
        }

        $saved = $this->saveCompressedImage($file, 'uploads/artikel/konten', 1280, 1280);
        if (!$saved) {
            return $this
                ->response
                ->setStatusCode(500)
                ->setJSON(['error' => 'Gagal memproses gambar']);
        }

        $url = base_url($saved);

        return $this->response->setJSON([
            'url' => $url,
            'path' => $saved,
            'filename' => basename($saved),
        ]);
    }

    /**
     * Kompres dan simpan gambar upload (resize dan re-encode ke WEBP/JPEG/PNG).
     * Mengembalikan path relatif yang bisa diakses publik.
     */
    protected function saveCompressedImage(\CodeIgniter\HTTP\Files\UploadedFile $file, string $subdir, int $maxWidth = 1280, int $maxHeight = 1280): ?string
    {
        try {
            $mime = (string) $file->getMimeType();
            $tmp = (string) $file->getTempName();
            if ($tmp === '' || !is_file($tmp)) {
                return null;
            }

            $src = null;
            $outExt = 'jpg';
            if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                $src = imagecreatefromjpeg($tmp);
                $outExt = 'jpg';
            } elseif ($mime === 'image/png') {
                $src = imagecreatefrompng($tmp);
                $outExt = 'png';
            } elseif ($mime === 'image/gif') {
                $src = imagecreatefromgif($tmp);
                $outExt = 'gif';
            } elseif ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
                $src = imagecreatefromwebp($tmp);
                $outExt = 'jpg';
            } else {
                return null;
            }
            if (!$src) {
                return null;
            }

            $w = imagesx($src);
            $h = imagesy($src);
            if ($w <= 0 || $h <= 0) {
                imagedestroy($src);
                return null;
            }
            $scale = min(($maxWidth > 0 ? ($maxWidth / $w) : 1), ($maxHeight > 0 ? ($maxHeight / $h) : 1), 1);
            $newW = max(1, (int) floor($w * $scale));
            $newH = max(1, (int) floor($h * $scale));

            $dst = imagecreatetruecolor($newW, $newH);
            if ($outExt === 'png' || $outExt === 'gif') {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);
            imagedestroy($src);

            $dir = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $subdir);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }

            $base = 'img_' . time() . '_' . bin2hex(random_bytes(4));
            $useWebp = function_exists('imagewebp');
            $finalExt = $useWebp ? 'webp' : ($outExt === 'jpg' ? 'jpg' : ($outExt === 'png' ? 'png' : 'jpg'));
            $full = $dir . DIRECTORY_SEPARATOR . $base . '.' . $finalExt;
            $ok = false;
            if ($useWebp) {
                $ok = imagewebp($dst, $full, 80);
            } else {
                if ($finalExt === 'jpg') {
                    $ok = imagejpeg($dst, $full, 75);
                } elseif ($finalExt === 'png') {
                    $ok = imagepng($dst, $full, 6);
                } else {
                    $ok = imagejpeg($dst, $full, 75);
                }
            }
            imagedestroy($dst);
            if (!$ok) {
                return null;
            }

            $relative = rtrim(str_replace(['\\'], '/', $subdir), '/') . '/' . basename($full);
            return $relative;
        } catch (\Throwable $e) {
            return null;
        }
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

    public function deleteMainImage($id)
    {
        $this->response->setContentType('application/json');
        $me = service('authentication')->user();
        if (!$me) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'error' => 'Unauthorized']);
        }
        $model = model(Berita::class);
        $row = $model->find($id);
        if (!$row) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'error' => 'Not found']);
        }
        $path = (string) ($row['gambar_utama'] ?? '');
        if ($path !== '') {
            $full = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
            if (is_file($full)) {
                @unlink($full);
            }
        }
        $ok = $model->update($id, ['gambar_utama' => null]);
        if (!$ok) {
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'error' => 'Gagal menghapus gambar']);
        }
        return $this->response->setJSON(['ok' => true]);
    }
}

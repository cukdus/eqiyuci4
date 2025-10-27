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
        $tanggalTerbitRaw = $this->request->getPost('tanggal_terbit');

        // Normalisasi datetime-local (YYYY-MM-DDTHH:MM) ke format DB (YYYY-MM-DD HH:MM:SS)
        if ($tanggalTerbitRaw) {
            $tanggalTerbit = str_replace('T', ' ', $tanggalTerbitRaw);
            if (!preg_match('/:\d{2}$/', $tanggalTerbit)) {
                $tanggalTerbit .= ':00';
            }
        } else {
            $tanggalTerbit = date('Y-m-d H:i:s');
        }

        // Tentukan status otomatis berdasarkan tanggal terbit
        $statusAuto = 'draft';
        try {
            if ($tanggalTerbitRaw) {
                $dtTarget = new \DateTime($tanggalTerbitRaw);
                $now = new \DateTime('now');
                $statusAuto = ($dtTarget <= $now) ? 'publish' : 'draft';
            } else {
                // Jika tidak diisi, anggap terbit sekarang
                $statusAuto = 'publish';
            }
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
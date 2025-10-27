<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriBerita;
use CodeIgniter\HTTP\RedirectResponse;

class KategoriArtikel extends BaseController
{
    public function index()
    {
        $model = model(KategoriBerita::class);
        $categories = $model->orderBy('nama_kategori', 'ASC')->findAll();

        return view('layout/admin_layout', [
            'title' => 'Kategori Artikel',
            'content' => view('admin/artikel/kategoriartikel', [
                'categories' => $categories,
                'editCategory' => null,
            ]),
        ]);
    }

    public function store(): RedirectResponse
    {
        $name = trim((string) $this->request->getPost('nama_kategori'));

        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]|is_unique[kategori_berita.nama_kategori]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambah kategori.')
                ->with('errors', $this->validator->getErrors());
        }

        $model = model(KategoriBerita::class);
        if (!$model->insert(['nama_kategori' => $name])) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambah kategori.')
                ->with('errors', $model->errors());
        }

        return redirect()->to(base_url('admin/artikel/kategori'))
            ->with('message', 'Kategori berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $model = model(KategoriBerita::class);
        $editCategory = $model->find($id);
        if (!$editCategory) {
            return redirect()->to(base_url('admin/artikel/kategori'))
                ->with('error', 'Kategori tidak ditemukan');
        }

        $categories = $model->orderBy('nama_kategori', 'ASC')->findAll();

        return view('layout/admin_layout', [
            'title' => 'Edit Kategori Artikel',
            'content' => view('admin/artikel/kategoriartikel', [
                'categories' => $categories,
                'editCategory' => $editCategory,
            ]),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $name = trim((string) $this->request->getPost('nama_kategori'));

        $rules = [
            'nama_kategori' => "required|min_length[3]|max_length[100]|is_unique[kategori_berita.nama_kategori,id,{$id}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah kategori.')
                ->with('errors', $this->validator->getErrors());
        }

        $model = model(KategoriBerita::class);
        if (!$model->update($id, ['nama_kategori' => $name])) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah kategori.')
                ->with('errors', $model->errors());
        }

        return redirect()->to(base_url('admin/artikel/kategori'))
            ->with('message', 'Kategori berhasil diubah');
    }

    public function delete(int $id): RedirectResponse
    {
        $model = model(KategoriBerita::class);
        if (!$model->delete($id)) {
            return redirect()->back()->with('error', 'Gagal menghapus kategori.')
                ->with('errors', $model->errors());
        }

        return redirect()->to(base_url('admin/artikel/kategori'))
            ->with('message', 'Kategori berhasil dihapus');
    }
}
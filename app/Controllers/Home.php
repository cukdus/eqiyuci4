<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $beritaModel = new \App\Models\Berita();
        $kategoriModel = new \App\Models\KategoriBerita();
        
        // Ambil 3 berita terbaru yang dipublish dan urutkan berdasarkan tanggal terbaru
        $berita = $beritaModel->where('status', 'publish')
                             ->orderBy('tanggal_terbit', 'DESC')
                             ->limit(3)
                             ->find();
        
        // Tambahkan informasi kategori untuk setiap berita
        foreach ($berita as $key => $artikel) {
            if (!empty($artikel['kategori_id'])) {
                $kategori = $kategoriModel->find($artikel['kategori_id']);
                $berita[$key]['kategori_nama'] = $kategori ? $kategori['nama_kategori'] : 'Uncategorized';
            } else {
                $berita[$key]['kategori_nama'] = 'Uncategorized';
            }
        }
        
        $data['berita'] = $berita;
        
        return view('home', $data);
    }
}

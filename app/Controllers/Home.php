<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $beritaModel = new \App\Models\Berita();
        $kategoriModel = new \App\Models\KategoriBerita();
        $kelasModel = new \App\Models\Kelas();
        $kotaKelasModel = new \App\Models\KotaKelas();
        $registrasiModel = new \App\Models\Registrasi();
        $sertfikatModel = new \App\Models\Sertifikat();

        // Ambil 3 berita terbaru yang dipublish, sudah melewati tanggal terbit, dan urutkan berdasarkan tanggal terbaru
        $now = date('Y-m-d H:i:s');
        $berita = $beritaModel
            ->where('status', 'publish')
            ->where('tanggal_terbit <=', $now)
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

        // Hitung total alumni (dari tabel registrasi)
        $data['total_alumni'] = $sertfikatModel->countAllResults();

        // Hitung jumlah kelas regular (kelas offline)
        $data['jumlah_kelas'] = $kelasModel->where('kategori', 'kursus')->countAllResults();

        // Hitung jumlah kota kelas yang tersedia
        // Hitung jumlah kota dengan status aktif, kecuali nama kota "Se-Dunia"
        $data['jumlah_kota'] = $kotaKelasModel
            ->where('status', 'aktif')
            ->where('nama !=', 'Se-Dunia')
            ->countAllResults();

        // Data untuk statistik kelas berdasarkan kode_kelas
        // Jumlah sertifikat aktif untuk kelas "Basic Barista & Latte Art" (kode_kelas '01')
        $data['kelas_barista'] = $sertfikatModel
            ->where('status', 'aktif')
            ->where('nama_kelas', 'Basic Barista & Latte Art')
            ->countAllResults();
        $data['kelas_bisnis'] = $sertfikatModel
            ->where('status', 'aktif')
            ->where('nama_kelas', 'Workshop Membangun Bisnis Cafe & Kedai Kopi')
            ->countAllResults();
        $data['kelas_private'] = $sertfikatModel
            ->where('status', 'aktif')
            ->where('nama_kelas', 'Private Class Beverage & Bisnis Culinary')
            ->countAllResults();

        $data['berita'] = $berita;

        return view('home', $data);
    }
}

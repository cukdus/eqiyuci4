<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Kelas as KelasModel;

class Voucher extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Ambil daftar kelas untuk pilihan
        $kelasList = model(KelasModel::class)
            ->select('id, kode_kelas, nama_kelas')
            ->orderBy('nama_kelas', 'ASC')
            ->findAll();

        // Ambil daftar voucher dengan join nama kelas
        $vouchers = $db->table('voucher v')
            ->select('v.id, v.kode_voucher, v.diskon_persen, v.tanggal_berlaku_mulai, v.tanggal_berlaku_sampai, k.nama_kelas')
            ->join('kelas k', 'k.id = v.kelas_id', 'left')
            ->orderBy('v.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('layout/admin_layout', [
            'title' => 'Voucher Kelas',
            'content' => view('admin/kelas/voucherkelas', [
                'kelasList' => $kelasList,
                'vouchers' => $vouchers,
            ]),
        ]);
    }

    public function store()
    {
        $rules = [
            'kelas_id' => 'required|integer',
            'kode_voucher' => 'required|min_length[3]|max_length[50]|is_unique[voucher.kode_voucher]',
            'diskon_persen' => 'required|decimal',
            'tanggal_berlaku_mulai' => 'permit_empty|valid_date[Y-m-d]',
            'tanggal_berlaku_sampai' => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kelasId = (int) $this->request->getPost('kelas_id');
        $kode = trim((string) $this->request->getPost('kode_voucher'));
        $diskon = (float) $this->request->getPost('diskon_persen');
        $mulai = $this->request->getPost('tanggal_berlaku_mulai');
        $sampai = $this->request->getPost('tanggal_berlaku_sampai');

        if ($diskon < 0 || $diskon > 100) {
            return redirect()->back()->withInput()->with('errors', ['Diskon harus di antara 0 hingga 100']);
        }

        if (!empty($mulai) && !empty($sampai) && $mulai > $sampai) {
            return redirect()->back()->withInput()->with('errors', ['Tanggal mulai tidak boleh lebih besar dari tanggal selesai']);
        }

        $db = \Config\Database::connect();
        $ok = $db->table('voucher')->insert([
            'kode_voucher' => $kode,
            'kelas_id' => $kelasId,
            'diskon_persen' => $diskon,
            'tanggal_berlaku_mulai' => $mulai ?: null,
            'tanggal_berlaku_sampai' => $sampai ?: null,
        ]);

        if (!$ok) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan voucher');
        }

        return redirect()->to(base_url('admin/kelas/voucher'))->with('message', 'Voucher berhasil dibuat');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $exists = $db->table('voucher')->where('id', (int) $id)->get()->getRowArray();
        if (!$exists) {
            return redirect()->to(base_url('admin/kelas/voucher'))->with('error', 'Voucher tidak ditemukan');
        }

        $del = $db->table('voucher')->where('id', (int) $id)->delete();
        if (!$del) {
            return redirect()->to(base_url('admin/kelas/voucher'))->with('error', 'Gagal menghapus voucher');
        }

        return redirect()->to(base_url('admin/kelas/voucher'))->with('message', 'Voucher berhasil dihapus');
    }
}
<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Registrasi as RegistrasiModel;
use App\Models\Kelas as KelasModel;

class Registrasi extends BaseController
{
    public function index()
    {
        $model = model(RegistrasiModel::class);

        $search = trim((string) $this->request->getGet('search'));
        if ($search !== '') {
            $model = $model->searchRegistrations($search);
            $registrations = $model->paginate(10);
        } else {
            $registrations = $model->getPaginatedRegistrationsWithClass(10);
        }
        
        $pager = $model->pager;

        return view('layout/admin_layout', [
            'title' => 'Data Registrasi',
            'content' => view('admin/registrasi/dataregistrasi', [
                'registrations' => $registrations,
                'pager' => $pager,
                'search' => $search,
            ]),
        ]);
    }

    public function tambah()
    {
        $kelasModel = model(KelasModel::class);
        $kelasList = $kelasModel->select('kode_kelas, nama_kelas, kota_tersedia, harga')
                                ->where('status_kelas', 'aktif')
                                ->orderBy('kode_kelas', 'ASC')
                                ->findAll();

        // Ambil daftar lokasi (kota) dari field kota_tersedia pada tabel kelas
        $lokasiOptions = [];
        foreach ($kelasList as $k) {
            if (!empty($k['kota_tersedia'])) {
                $parts = array_map('trim', explode(',', (string) $k['kota_tersedia']));
                foreach ($parts as $p) {
                    if ($p !== '') {
                        $lokasiOptions[] = $p;
                    }
                }
            }
        }
        $lokasiOptions = array_values(array_unique($lokasiOptions));

        return view('layout/admin_layout', [
            'title' => 'Tambah Registrasi',
            'content' => view('admin/registrasi/tambahregistrasi', [
                'kelasList' => $kelasList,
                'lokasiOptions' => $lokasiOptions,
            ]),
        ]);
    }

    public function store()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'no_telp' => 'permit_empty|max_length[20]',
            'kode_kelas' => 'required|max_length[20]',
            'lokasi' => 'permit_empty|max_length[100]',
            'status_pembayaran' => 'required|in_list[DP 50%,lunas]',
            'akses_aktif' => 'permit_empty|in_list[0,1]',
            'kode_voucher' => 'permit_empty|max_length[50]',
            'alamat' => 'permit_empty',
            'kecamatan' => 'permit_empty|max_length[100]',
            'kabupaten' => 'permit_empty|max_length[100]',
            'provinsi' => 'permit_empty|max_length[100]',
            'kodepos' => 'permit_empty|max_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = model(RegistrasiModel::class);
        $kelasModel = model(KelasModel::class);

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_telp' => $this->request->getPost('no_telp'),
            'kode_kelas' => $this->request->getPost('kode_kelas'),
            'lokasi' => $this->request->getPost('lokasi'),
            'kode_voucher' => $this->request->getPost('kode_voucher'),
            'alamat' => $this->request->getPost('alamat'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'kabupaten' => $this->request->getPost('kabupaten'),
            'provinsi' => $this->request->getPost('provinsi'),
            'kodepos' => $this->request->getPost('kodepos'),
            'biaya_dibayar' => $this->request->getPost('biaya_dibayar') ?: 0,
            'status_pembayaran' => $this->request->getPost('status_pembayaran'),
            'akses_aktif' => $this->request->getPost('akses_aktif') ? 1 : 0,
            'tanggal_daftar' => date('Y-m-d H:i:s'),
            'tanggal_update' => date('Y-m-d H:i:s'),
        ];

        // Validasi server-side: jika ada kode_voucher, pastikan voucher cocok dengan kelas yang dipilih
        $submittedKodeKelas = (string) ($data['kode_kelas'] ?? '');
        $submittedVoucher = trim((string) ($data['kode_voucher'] ?? ''));
        if ($submittedVoucher !== '') {
            $db = \Config\Database::connect();
            $voucherRow = $db->table('voucher')->where('kode_voucher', $submittedVoucher)->get()->getRowArray();
            if (!$voucherRow) {
                return redirect()->back()->withInput()->with('errors', ['Kode voucher tidak ditemukan']);
            }
            // Ambil kelas dari voucher
            $voucherKelasKode = null;
            if (!empty($voucherRow['kelas_id'])) {
                $kelasRow = $db->table('kelas')->select('kode_kelas')->where('id', (int) $voucherRow['kelas_id'])->get()->getRowArray();
                $voucherKelasKode = $kelasRow['kode_kelas'] ?? null;
            }
            // Jika voucher terkait kelas tertentu dan berbeda dengan yang dipilih, blok simpan
            if ($voucherKelasKode !== null && $submittedKodeKelas !== '' && $voucherKelasKode !== $submittedKodeKelas) {
                return redirect()->back()->withInput()->with('errors', [
                    'Voucher tidak berlaku untuk kelas yang dipilih (' . esc($submittedKodeKelas) . ').'
                ]);
            }
        }

        // Isi biaya_total dari harga kelas yang dipilih
        $kelas = $kelasModel->where('kode_kelas', $data['kode_kelas'])->first();
        $data['biaya_total'] = isset($kelas['harga']) ? (float) $kelas['harga'] : 0.0;

        if (!$model->save($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors() ?? ['Gagal menyimpan data registrasi']);
        }

        return redirect()->to(base_url('admin/registrasi'))->with('message', 'Data registrasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $model = model(RegistrasiModel::class);
        $registrasi = $model->find($id);
        
        if (!$registrasi) {
            return redirect()->to(base_url('admin/registrasi'))->with('error', 'Data registrasi tidak ditemukan');
        }

        $kelasModel = model(KelasModel::class);
        $kelasList = $kelasModel->select('kode_kelas, nama_kelas')
                                ->where('status_kelas', 'aktif')
                                ->orderBy('nama_kelas', 'ASC')
                                ->findAll();

        return view('layout/admin_layout', [
            'title' => 'Edit Registrasi',
            'content' => view('admin/registrasi/editregistrasi', [
                'registrasi' => $registrasi,
                'kelasList' => $kelasList,
            ]),
        ]);
    }

    public function update($id)
    {
        $model = model(RegistrasiModel::class);
        $existing = $model->find($id);
        
        if (!$existing) {
            return redirect()->to(base_url('admin/registrasi'))->with('error', 'Data registrasi tidak ditemukan');
        }

        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'no_telp' => 'permit_empty|max_length[20]',
            'kode_kelas' => 'required|max_length[20]',
            'lokasi' => 'permit_empty|max_length[100]',
            'biaya_total' => 'required|decimal',
            'status_pembayaran' => 'required|in_list[DP 50%,lunas]',
            'akses_aktif' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_telp' => $this->request->getPost('no_telp'),
            'kode_kelas' => $this->request->getPost('kode_kelas'),
            'lokasi' => $this->request->getPost('lokasi'),
            'biaya_total' => $this->request->getPost('biaya_total'),
            'biaya_dibayar' => $this->request->getPost('biaya_dibayar') ?: 0,
            'status_pembayaran' => $this->request->getPost('status_pembayaran'),
            'akses_aktif' => $this->request->getPost('akses_aktif') ? 1 : 0,
            'tanggal_update' => date('Y-m-d H:i:s'),
        ];

        if (!$model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors() ?? ['Gagal memperbarui data registrasi']);
        }

        return redirect()->to(base_url('admin/registrasi'))->with('message', 'Data registrasi berhasil diperbarui');
    }

    public function delete($id)
    {
        $model = model(RegistrasiModel::class);
        
        if (!$model->find($id)) {
            return redirect()->to(base_url('admin/registrasi'))->with('error', 'Data registrasi tidak ditemukan');
        }

        if (!$model->delete($id)) {
            return redirect()->back()->with('error', 'Gagal menghapus data registrasi');
        }

        return redirect()->to(base_url('admin/registrasi'))->with('message', 'Data registrasi berhasil dihapus');
    }

    public function toggleAkses($id)
    {
        $model = model(RegistrasiModel::class);
        $registrasi = $model->find($id);
        
        if (!$registrasi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $newStatus = $registrasi['akses_aktif'] ? 0 : 1;
        
        if ($model->update($id, ['akses_aktif' => $newStatus, 'tanggal_update' => date('Y-m-d H:i:s')])) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Status akses berhasil diubah',
                'newStatus' => $newStatus
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status akses']);
        }
    }

    public function checkVoucher()
    {
        // Ambil kode voucher dari POST form maupun JSON body
        $kode = '';
        // Coba baca dari form/urlencoded terlebih dahulu
        $postKode = $this->request->getPost('kode_voucher');
        if ($postKode !== null) {
            $kode = trim((string) $postKode);
        }
        // Ambil kode_kelas yang dipilih (opsional untuk validasi kelas)
        $requestedKodeKelas = '';
        $postKodeKelas = $this->request->getPost('kode_kelas');
        if ($postKodeKelas !== null) {
            $requestedKodeKelas = trim((string) $postKodeKelas);
        }
        // Jika kosong, coba dari JSON body
        if ($kode === '') {
            try {
                $json = $this->request->getJSON(true);
                if (is_array($json) && isset($json['kode_voucher'])) {
                    $kode = trim((string) $json['kode_voucher']);
                }
                if (is_array($json) && isset($json['kode_kelas'])) {
                    $requestedKodeKelas = trim((string) $json['kode_kelas']);
                }
            } catch (\Throwable $e) {
                // Abaikan error parsing JSON, lanjutkan validasi kosong
            }
        }
        if ($kode === '') {
            return $this->response->setJSON(['found' => false, 'message' => 'Kode voucher kosong']);
        }

        $db = \Config\Database::connect();
        $row = $db->table('voucher')->where('kode_voucher', $kode)->get()->getRowArray();

        if (!$row) {
            return $this->response->setJSON(['found' => false, 'message' => 'Voucher tidak ditemukan']);
        }

        $today = date('Y-m-d');
        $validDate = true;
        if (!empty($row['tanggal_berlaku_mulai']) && $row['tanggal_berlaku_mulai'] > $today) {
            $validDate = false;
        }
        if (!empty($row['tanggal_berlaku_sampai']) && $row['tanggal_berlaku_sampai'] < $today) {
            $validDate = false;
        }

        $diskon = isset($row['diskon_persen']) ? (float) $row['diskon_persen'] : null;

        // Dapatkan informasi kelas dari voucher (kelas_id) untuk validasi dengan kode_kelas yang dipilih
        $voucherKelasKode = null;
        $voucherKelasNama = null;
        $validForClass = null; // null jika kode_kelas tidak dikirim, boolean jika tersedia
        if (!empty($row['kelas_id'])) {
            $kelasRow = $db->table('kelas')->select('id, kode_kelas, nama_kelas')->where('id', (int) $row['kelas_id'])->get()->getRowArray();
            if ($kelasRow) {
                $voucherKelasKode = $kelasRow['kode_kelas'] ?? null;
                $voucherKelasNama = $kelasRow['nama_kelas'] ?? null;
                if ($requestedKodeKelas !== '') {
                    $validForClass = ($voucherKelasKode === $requestedKodeKelas);
                }
            }
        }

        return $this->response->setJSON([
            'found' => true,
            'validDate' => $validDate,
            'diskon_persen' => $diskon,
            'voucher' => $row,
            'voucher_kelas_kode' => $voucherKelasKode,
            'voucher_kelas_nama' => $voucherKelasNama,
            'validForClass' => $validForClass,
            'requested_kode_kelas' => $requestedKodeKelas,
        ]);
    }
}
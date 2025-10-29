<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Registrasi as RegistrasiModel;
use App\Models\Sertifikat as SertifikatModel;
use App\Models\Kelas as KelasModel;

class Sertifikat extends BaseController
{
    public function index()
    {
        // Kelas untuk filter (opsional)
        $kelasList = model(KelasModel::class)
            ->select('kode_kelas, nama_kelas')
            ->orderBy('nama_kelas', 'ASC')
            ->findAll();

        return view('layout/admin_layout', [
            'title' => 'Data Sertifikat',
            'content' => view('admin/sertifikat/datasertifikat', [
                'kelasList' => $kelasList,
            ]),
        ]);
    }

    /**
     * JSON: list registrations (for generating certificates)
     * Filters: search, kode_kelas
     */
    public function registrationsJson()
    {
        $request = $this->request;
        $page = max(1, (int) ($request->getGet('page') ?? 1));
        $perPage = (int) ($request->getGet('per_page') ?? 10);
        if ($perPage < 1) { $perPage = 10; }
        if ($perPage > 100) { $perPage = 100; }
        $search = trim((string) ($request->getGet('search') ?? ''));
        $kodeKelas = trim((string) ($request->getGet('kode_kelas') ?? ''));

        $db = \Config\Database::connect();
        $qb = $db->table('registrasi r')
            ->select('r.id, r.nama, r.kode_kelas, r.lokasi, r.tanggal_daftar, k.nama_kelas')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            // Jangan tampilkan yang sudah punya sertifikat
            ->join('sertifikat s', 's.registrasi_id = r.id', 'left')
            ->where('s.id IS NULL');

        if ($search !== '') {
            $qb->groupStart()
               ->like('r.nama', $search)
               ->orLike('k.nama_kelas', $search)
               ->groupEnd();
        }
        if ($kodeKelas !== '') {
            $qb->where('r.kode_kelas', $kodeKelas);
        }

        $countQb = clone $qb;
        $total = (int) $countQb->countAllResults(false);
        $rows = $qb->orderBy('r.tanggal_daftar', 'DESC')
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()->getResultArray();

        $data = array_map(static function(array $r){
            return [
                'id' => (int) ($r['id'] ?? 0),
                'nama' => (string) ($r['nama'] ?? ''),
                'kode_kelas' => (string) ($r['kode_kelas'] ?? ''),
                'nama_kelas' => (string) ($r['nama_kelas'] ?? ''),
                'lokasi' => (string) ($r['lokasi'] ?? ''),
                'tanggal_daftar' => (string) ($r['tanggal_daftar'] ?? ''),
            ];
        }, $rows);

        return $this->response->setJSON([
            'success' => true,
            'data' => $data,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => $perPage > 0 ? (int) ceil($total / $perPage) : 0,
            ],
        ]);
    }

    /**
     * JSON: list certificates
     */
    public function listJson()
    {
        $request = $this->request;
        $page = max(1, (int) ($request->getGet('page') ?? 1));
        $perPage = (int) ($request->getGet('per_page') ?? 10);
        if ($perPage < 1) { $perPage = 10; }
        if ($perPage > 100) { $perPage = 100; }
        $search = trim((string) ($request->getGet('search') ?? ''));

        $db = \Config\Database::connect();
        $qb = $db->table('sertifikat s')
            ->select('s.*, r.nama AS nama_registrasi')
            ->join('registrasi r', 'r.id = s.registrasi_id', 'left');

        if ($search !== '') {
            $qb->groupStart()
               ->like('s.nomor_sertifikat', $search)
               ->orLike('s.nama_pemilik', $search)
               ->orLike('s.nama_kelas', $search)
               ->groupEnd();
        }

        $countQb = clone $qb;
        $total = (int) $countQb->countAllResults(false);
        $rows = $qb->orderBy('s.tanggal_terbit', 'DESC')
            ->orderBy('s.id', 'DESC')
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()->getResultArray();

        $data = array_map(static function(array $r){
            return [
                'id' => (int) ($r['id'] ?? 0),
                'registrasi_id' => (int) ($r['registrasi_id'] ?? 0),
                'nomor_sertifikat' => (string) ($r['nomor_sertifikat'] ?? ''),
                'nama_pemilik' => (string) ($r['nama_pemilik'] ?? ''),
                'nama_kelas' => (string) ($r['nama_kelas'] ?? ''),
                'kota_kelas' => (string) ($r['kota_kelas'] ?? ''),
                'tanggal_terbit' => (string) ($r['tanggal_terbit'] ?? ''),
                'status' => (string) ($r['status'] ?? ''),
            ];
        }, $rows);

        return $this->response->setJSON([
            'success' => true,
            'data' => $data,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => $perPage > 0 ? (int) ceil($total / $perPage) : 0,
            ],
        ]);
    }

    /**
     * Generate certificate for a registration
     * POST: registrasi_id
     */
    public function generate()
    {
        $registrasiId = (int) $this->request->getPost('registrasi_id');
        if ($registrasiId <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'registrasi_id tidak valid']);
        }

        $regModel = model(RegistrasiModel::class);
        $row = $regModel->find($registrasiId);
        if (!$row) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Registrasi tidak ditemukan']);
        }

        // Ambil nama_kelas dari kode_kelas (jika ada)
        $namaKelas = '';
        $kodeKelas = (string) ($row['kode_kelas'] ?? '');
        if ($kodeKelas !== '') {
            $kelasModel = model(KelasModel::class);
            $kelasRow = $kelasModel->select('nama_kelas')->where('kode_kelas', $kodeKelas)->first();
            if ($kelasRow) { $namaKelas = (string) ($kelasRow['nama_kelas'] ?? ''); }
        }

        // Cek apakah sudah punya sertifikat
        $sertModel = model(SertifikatModel::class);
        $exists = $sertModel->where('registrasi_id', $registrasiId)->first();
        if ($exists) {
            return $this->response->setJSON(['success' => true, 'message' => 'Sertifikat sudah ada', 'data' => $exists]);
        }
        // Generate nomor sertifikat sesuai format: EQYYKKNNCC
        // YY: tahun pembelajaran (jadwal jika ada, jika tidak dari tanggal_daftar)
        $yearYY = '';
        $jadwalId = isset($row['jadwal_id']) ? (int) $row['jadwal_id'] : 0;
        if ($jadwalId > 0) {
            $db = \Config\Database::connect();
            $jadwal = $db->table('jadwal_kelas')->select('tanggal_mulai, lokasi')->where('id', $jadwalId)->get()->getRowArray();
            if ($jadwal && !empty($jadwal['tanggal_mulai'])) {
                $yearYY = substr(date('Y', strtotime($jadwal['tanggal_mulai'])), -2);
            }
        }
        if ($yearYY === '') {
            $tgl = (string) ($row['tanggal_daftar'] ?? date('Y-m-d'));
            $yearYY = substr(date('Y', strtotime($tgl)), -2);
        }

        // KK: ambil 2 digit dari kode_kelas (numeric), padding kiri 0
        $kodeKelasNumeric = preg_replace('/\D/', '', $kodeKelas);
        if ($kodeKelasNumeric === '') { $kodeKelasNumeric = '0'; }
        $kk = str_pad(substr($kodeKelasNumeric, -2), 2, '0', STR_PAD_LEFT);

        // CC: kode kota (01=malang, 02=jogja, 0N=online -> gunakan 03 sebagai default online)
        $lokasi = '';
        // lokasi dari jadwal jika ada, jika tidak gunakan dari registrasi
        if (isset($jadwal) && is_array($jadwal) && !empty($jadwal['lokasi'])) {
            $lokasi = strtolower((string) $jadwal['lokasi']);
        } else {
            $lokasi = strtolower((string) ($row['lokasi'] ?? ''));
        }
        $cc = '03'; // default untuk online/other
        if ($lokasi === 'malang') { $cc = '01'; }
        elseif ($lokasi === 'jogja') { $cc = '02'; }
        elseif (strpos($lokasi, 'online') !== false) { $cc = '03'; }

        // NNNN: nomor urut tahunan, reset tiap tahun (global per tahun)
        // Ambil MAX NNNN untuk prefix tahun agar tidak bergantung kelas/kota
        $db = isset($db) ? $db : \Config\Database::connect();
        $prefix = 'EQ' . $yearYY;
        $rowMax = $db->query(
            "SELECT MAX(CAST(SUBSTRING(nomor_sertifikat, 7, 4) AS UNSIGNED)) AS max_seq\n             FROM sertifikat\n             WHERE nomor_sertifikat LIKE ?",
            [$prefix . '%']
        )->getRowArray();
        $seq = ((int) ($rowMax['max_seq'] ?? 0)) + 1;
        $nnnn = str_pad((string) $seq, 4, '0', STR_PAD_LEFT);

        // Susun nomor dan coba insert dengan retry jika bentrok
        $attempts = 0;
        $maxAttempts = 5;
        do {
            $kode = 'EQ' . $yearYY . $kk . $nnnn . $cc;
            // Jika sudah terpakai, lanjutkan ke nomor berikutnya
            if ($sertModel->where('nomor_sertifikat', $kode)->countAllResults() > 0) {
                $seq++;
                $nnnn = str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
                $attempts++;
                continue;
            }
            break;
        } while ($attempts < $maxAttempts);

        $data = [
            'registrasi_id' => $registrasiId,
            'nomor_sertifikat' => $kode,
            'nama_pemilik' => (string) ($row['nama'] ?? ''),
            'nama_kelas' => $namaKelas,
            'kota_kelas' => $lokasi,
            'tanggal_terbit' => date('Y-m-d'),
        ];

        // Insert dan jika gagal karena duplikat, naikkan seq dan coba lagi beberapa kali
        $inserted = false; $attempts = 0;
        while (!$inserted && $attempts < $maxAttempts) {
            try {
                if ($sertModel->insert($data) !== false) {
                    $inserted = true;
                    break;
                }
            } catch (\Throwable $e) {
                // Kemungkinan duplicate key jika ada unique index; naikkan seq dan coba lagi
            }
            $seq++;
            $nnnn = str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
            $kode = 'EQ' . $yearYY . $kk . $nnnn . $cc;
            $data['nomor_sertifikat'] = $kode;
            $attempts++;
        }
        if (!$inserted) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Gagal membuat sertifikat (duplikasi nomor)']);
        }

        $created = $sertModel->find($sertModel->getInsertID());
        return $this->response->setJSON(['success' => true, 'message' => 'Sertifikat berhasil dibuat', 'data' => $created]);
    }

    public function delete($id)
    {
        $model = model(SertifikatModel::class);
        if (!$model->find($id)) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Sertifikat tidak ditemukan']);
        }
        if (!$model->delete($id)) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Gagal menghapus sertifikat']);
        }
        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Auto-generate sertifikat H-1 sebelum kelas berakhir.
     * Cari jadwal yang tanggal_selesai = besok, untuk setiap registrasi di jadwal itu,
     * generate sertifikat jika belum ada.
     */
    public function autoGenerateDue()
    {
        $db = \Config\Database::connect();
        $targetDate = date('Y-m-d', strtotime('+1 day'));

        $jadwalIds = $db->table('jadwal_kelas')
            ->select('id')
            ->where('tanggal_selesai', $targetDate)
            ->get()->getResultArray();

        $ids = array_map(static fn(array $r) => (int) ($r['id'] ?? 0), $jadwalIds);
        $ids = array_filter($ids);

        if (empty($ids)) {
            return $this->response->setJSON([
                'success' => true,
                'created' => 0,
                'skipped' => 0,
                'message' => 'Tidak ada jadwal yang berakhir besok.',
            ]);
        }

        $registrasiRows = $db->table('registrasi')->select('*')->whereIn('jadwal_id', $ids)->get()->getResultArray();

        $created = 0;
        $skipped = 0;
        $errors = [];

        foreach ($registrasiRows as $row) {
            try {
                $res = $this->createCertificateForRegistrasi($row);
                if (!empty($res['created'])) {
                    $created++;
                } else {
                    $skipped++;
                }
            } catch (\Throwable $e) {
                $errors[] = $e->getMessage();
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'created' => $created,
            'skipped' => $skipped,
            'errors' => $errors,
        ]);
    }

    /**
     * Helper: buat sertifikat untuk satu registrasi dengan logika sama seperti generate().
     */
    protected function createCertificateForRegistrasi(array $row): array
    {
        $registrasiId = (int) ($row['id'] ?? 0);
        if ($registrasiId <= 0) {
            return ['created' => false, 'message' => 'registrasi_id tidak valid'];
        }

        $regModel = model(RegistrasiModel::class);
        $current = $regModel->find($registrasiId);
        if (!$current) {
            return ['created' => false, 'message' => 'Registrasi tidak ditemukan'];
        }

        $sertModel = model(SertifikatModel::class);
        $exists = $sertModel->where('registrasi_id', $registrasiId)->first();
        if ($exists) {
            return ['created' => false, 'data' => $exists];
        }

        // Ambil nama_kelas dari kode_kelas
        $namaKelas = '';
        $kodeKelas = (string) ($current['kode_kelas'] ?? '');
        if ($kodeKelas !== '') {
            $kelasModel = model(KelasModel::class);
            $kelasRow = $kelasModel->select('nama_kelas')->where('kode_kelas', $kodeKelas)->first();
            if ($kelasRow) {
                $namaKelas = (string) ($kelasRow['nama_kelas'] ?? '');
            }
        }

        // YY dari tanggal_mulai jadwal (jika ada), fallback tanggal_daftar
        $db = \Config\Database::connect();
        $yearYY = '';
        $jadwalId = isset($current['jadwal_id']) ? (int) $current['jadwal_id'] : 0;
        $jadwal = null;
        if ($jadwalId > 0) {
            $jadwal = $db->table('jadwal_kelas')->select('tanggal_mulai, lokasi')->where('id', $jadwalId)->get()->getRowArray();
            if ($jadwal && !empty($jadwal['tanggal_mulai'])) {
                $yearYY = substr(date('Y', strtotime($jadwal['tanggal_mulai'])), -2);
            }
        }
        if ($yearYY === '') {
            $tgl = (string) ($current['tanggal_daftar'] ?? date('Y-m-d'));
            $yearYY = substr(date('Y', strtotime($tgl)), -2);
        }

        // KK: ambil 2 digit numeric dari kode_kelas (padding)
        $kodeKelasNumeric = preg_replace('/\D/', '', $kodeKelas);
        if ($kodeKelasNumeric === '') {
            $kodeKelasNumeric = '0';
        }
        $kk = str_pad(substr($kodeKelasNumeric, -2), 2, '0', STR_PAD_LEFT);

        // CC: peta lokasi -> kode (tetap mengikuti logika existing)
        $lokasi = '';
        if (is_array($jadwal) && !empty($jadwal['lokasi'])) {
            $lokasi = strtolower((string) $jadwal['lokasi']);
        } else {
            $lokasi = strtolower((string) ($current['lokasi'] ?? ''));
        }
        $cc = '03'; // default online/other
        if ($lokasi === 'malang') {
            $cc = '01';
        } elseif ($lokasi === 'jogja') {
            $cc = '02';
        } elseif (strpos($lokasi, 'online') !== false) {
            $cc = '03';
        }

        // NNNN: sequence tahunan global per tahun, gunakan MAX untuk hindari duplikat
        $prefix = 'EQ' . $yearYY;
        $rowMax = $db->query(
            "SELECT MAX(CAST(SUBSTRING(nomor_sertifikat, 7, 4) AS UNSIGNED)) AS max_seq\n             FROM sertifikat\n             WHERE nomor_sertifikat LIKE ?",
            [$prefix . '%']
        )->getRowArray();
        $seq = ((int) ($rowMax['max_seq'] ?? 0)) + 1;
        $nnnn = str_pad((string) $seq, 4, '0', STR_PAD_LEFT);

        $attempts = 0; $maxAttempts = 5;
        do {
            $kode = 'EQ' . $yearYY . $kk . $nnnn . $cc;
            if ($sertModel->where('nomor_sertifikat', $kode)->countAllResults() > 0) {
                $seq++;
                $nnnn = str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
                $attempts++;
                continue;
            }
            break;
        } while ($attempts < $maxAttempts);

        $data = [
            'registrasi_id' => $registrasiId,
            'nomor_sertifikat' => $kode,
            'nama_pemilik' => (string) ($current['nama'] ?? ''),
            'nama_kelas' => $namaKelas,
            'kota_kelas' => $lokasi,
            'tanggal_terbit' => date('Y-m-d'),
        ];

        $inserted = false; $attempts = 0;
        while (!$inserted && $attempts < $maxAttempts) {
            try {
                if ($sertModel->insert($data) !== false) {
                    $inserted = true;
                    break;
                }
            } catch (\Throwable $e) {
                // kemungkinan duplicate key; retry dengan nomor berikutnya
            }
            $seq++;
            $nnnn = str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
            $kode = 'EQ' . $yearYY . $kk . $nnnn . $cc;
            $data['nomor_sertifikat'] = $kode;
            $attempts++;
        }
        if (!$inserted) {
            throw new \RuntimeException('Gagal membuat sertifikat (duplikasi nomor) untuk registrasi ' . $registrasiId);
        }

        return ['created' => true, 'data' => $sertModel->find($sertModel->getInsertID())];
    }
}
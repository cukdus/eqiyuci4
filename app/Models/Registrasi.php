<?php

namespace App\Models;

use CodeIgniter\Model;

class Registrasi extends Model
{
    protected $table = 'registrasi';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'kode_kelas',
        'kode_voucher',
        'nama',
        'no_telp',
        'alamat',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kodepos',
        'email',
        'lokasi',
        'biaya_total',
        'biaya_dibayar',
        'biaya_tagihan',
        'status_pembayaran',
        'tanggal_daftar',
        'tanggal_update',
        'jadwal_id',
        'akses_aktif',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        // Use float to avoid invalid cast handler errors for decimals
        'biaya_total' => 'float',
        'biaya_dibayar' => '?float',
        'biaya_tagihan' => '?float',
        'akses_aktif' => 'boolean',
    ];

    protected array $castHandlers = [];
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nama' => 'required|min_length[3]|max_length[100]',
        'email' => 'permit_empty|valid_email|max_length[100]',
        'kode_kelas' => 'required|max_length[20]',
        'biaya_total' => 'required|decimal',
        'status_pembayaran' => 'required|in_list[DP 50%,lunas]',
    ];

    protected $validationMessages = [
        'nama' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama minimal 3 karakter',
            'max_length' => 'Nama maksimal 100 karakter',
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid',
            'max_length' => 'Email maksimal 100 karakter',
        ],
        'kode_kelas' => [
            'required' => 'Kode kelas harus diisi',
            'max_length' => 'Kode kelas maksimal 20 karakter',
        ],
        'biaya_total' => [
            'required' => 'Biaya total harus diisi',
            'decimal' => 'Biaya total harus berupa angka',
        ],
        'status_pembayaran' => [
            'required' => 'Status pembayaran harus diisi',
            'in_list' => 'Status pembayaran harus DP 50% atau lunas',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get registrations with class information
     */
    public function getRegistrationsWithClass()
    {
        return $this
            ->select('registrasi.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.kode_kelas = registrasi.kode_kelas', 'left')
            ->orderBy('registrasi.tanggal_daftar', 'DESC')
            ->findAll();
    }

    /**
     * Get paginated registrations with class information
     */
    public function getPaginatedRegistrationsWithClass($perPage = 10)
    {
        return $this
            ->select('registrasi.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.kode_kelas = registrasi.kode_kelas', 'left')
            ->orderBy('registrasi.tanggal_daftar', 'DESC')
            ->paginate($perPage);
    }

    /**
     * Search registrations
     */
    public function searchRegistrations($search)
    {
        return $this
            ->select('registrasi.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.kode_kelas = registrasi.kode_kelas', 'left')
            ->groupStart()
            ->like('registrasi.nama', $search)
            ->orLike('registrasi.email', $search)
            ->orLike('registrasi.no_telp', $search)
            ->orLike('kelas.nama_kelas', $search)
            ->groupEnd()
            ->orderBy('registrasi.tanggal_daftar', 'DESC');
    }
}

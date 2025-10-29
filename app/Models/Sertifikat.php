<?php

namespace App\Models;

use CodeIgniter\Model;

class Sertifikat extends Model
{
    protected $table            = 'sertifikat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'registrasi_id',
        'nomor_sertifikat',
        'nama_pemilik',
        'kota_kelas',
        'tanggal_terbit',
        'nama_kelas',
        'status',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class BonusKelas extends Model
{
    protected $table = 'bonus_kelas';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'kode_kelas',
        'nama_file',
        'path_file',
        'deskripsi',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
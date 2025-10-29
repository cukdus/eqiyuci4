<?php

namespace App\Models;

use CodeIgniter\Model;

class KotaKelas extends Model
{
    protected $table = 'kota_kelas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode', 'nama', 'status', 'created_at', 'updated_at'];
    protected $useTimestamps = false;
}
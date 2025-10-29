<?php

namespace App\Models;

use CodeIgniter\Model;

class BonusFile extends Model
{
    protected $table = 'bonus_file';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kelas_id', 'tipe', 'judul_file', 'file_url', 'urutan', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
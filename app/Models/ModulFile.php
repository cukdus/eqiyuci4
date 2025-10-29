<?php

namespace App\Models;

use CodeIgniter\Model;

class ModulFile extends Model
{
    protected $table = 'modul_file';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['course_id', 'tipe', 'judul_file', 'file_url', 'urutan', 'created_at'];
    protected $useTimestamps = false;
}
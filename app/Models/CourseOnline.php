<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseOnline extends Model
{
    protected $table = 'course_online';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['kelas_id', 'judul_modul', 'deskripsi', 'urutan', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
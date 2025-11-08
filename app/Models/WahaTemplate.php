<?php

namespace App\Models;

use CodeIgniter\Model;

class WahaTemplate extends Model
{
    protected $table = 'waha_templates';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'key', 'name', 'template', 'enabled', 'description', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
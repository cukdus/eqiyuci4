<?php

namespace App\Models;

use CodeIgniter\Model;

class WahaQueue extends Model
{
    protected $table = 'waha_queue';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'registrasi_id', 'scenario', 'recipient', 'phone', 'template_key', 'payload', 'status', 'attempts', 'next_attempt_at', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
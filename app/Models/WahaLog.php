<?php

namespace App\Models;

use CodeIgniter\Model;

class WahaLog extends Model
{
    protected $table = 'waha_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'registrasi_id', 'scenario', 'recipient', 'phone', 'message', 'status', 'error', 'created_at'
    ];
    protected $useTimestamps = false;
}
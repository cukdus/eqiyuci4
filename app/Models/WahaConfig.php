<?php

namespace App\Models;

use CodeIgniter\Model;

class WahaConfig extends Model
{
    protected $table = 'waha_config';
    protected $primaryKey = 'key';
    protected $returnType = 'array';
    protected $allowedFields = ['key', 'value', 'description'];
    protected $useTimestamps = false;

    public function getValue(string $key, $default = null)
    {
        $row = $this->where('key', $key)->first();
        if (!$row || !array_key_exists('value', $row)) return $default;
        return $row['value'];
    }
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class Tag extends Model
{
    protected $table            = 'tag';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_tag'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nama_tag' => 'required|min_length[2]|max_length[100]|is_unique[tag.nama_tag,id,{id}]'
    ];
    protected $validationMessages   = [
        'nama_tag' => [
            'required' => 'Nama tag harus diisi',
            'min_length' => 'Nama tag minimal 2 karakter',
            'max_length' => 'Nama tag maksimal 100 karakter',
            'is_unique' => 'Nama tag sudah ada'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}

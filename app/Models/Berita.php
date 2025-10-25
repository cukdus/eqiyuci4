<?php

namespace App\Models;

use CodeIgniter\Model;

class Berita extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'berita';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['judul', 'slug', 'konten', 'gambar_utama', 'penulis', 'tanggal_terbit', 'status', 'kategori_id'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'judul' => 'required|min_length[5]|max_length[150]',
        'slug' => 'required|min_length[5]|max_length[150]|is_unique[berita.slug,id,{id}]',
        'konten' => 'required',
        'status' => 'required|in_list[draft,publish]'
    ];
    protected $validationMessages   = [];
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
    
    // Relasi dengan KategoriBerita
    public function kategori()
    {
        return $this->belongsTo(KategoriBerita::class, 'kategori_id', 'id');
    }
    
    // Relasi dengan Tag (many-to-many)
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'berita_tag', 'berita_id', 'tag_id');
    }
}

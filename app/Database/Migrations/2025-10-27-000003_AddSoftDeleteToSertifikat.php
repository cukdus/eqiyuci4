<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;
use Config\Database;

class AddSoftDeleteToSertifikat extends Migration
{
    /** @var BaseConnection */
    protected $db;
    /** @var Forge */
    protected $forge;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->forge = \Config\Database::forge();
    }

    public function up()
    {
        // Tambah kolom deleted_at untuk soft delete jika tabel ada
        if ($this->db->tableExists('sertifikat')) {
            $fields = $this->db->getFieldNames('sertifikat');
            if (!in_array('deleted_at', $fields, true)) {
                $this->forge->addColumn('sertifikat', [
                    'deleted_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                        'comment' => 'Soft delete timestamp',
                    ],
                ]);
            }
        }
    }

    public function down()
    {
        // Hapus kolom deleted_at jika ada
        if ($this->db->tableExists('sertifikat')) {
            $fields = $this->db->getFieldNames('sertifikat');
            if (in_array('deleted_at', $fields, true)) {
                $this->forge->dropColumn('sertifikat', 'deleted_at');
            }
        }
    }
}
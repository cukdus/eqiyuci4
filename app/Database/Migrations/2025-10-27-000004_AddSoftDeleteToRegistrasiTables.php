<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;
use Config\Database;

class AddSoftDeleteToRegistrasiTables extends Migration
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
        // registrasi
        if ($this->db->tableExists('registrasi')) {
            $fields = $this->db->getFieldNames('registrasi');
            if (!in_array('deleted_at', $fields, true)) {
                $this->forge->addColumn('registrasi', [
                    'deleted_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                        'comment' => 'Soft delete timestamp',
                    ],
                ]);
            }
        }

        // registrasi_jasa
        if ($this->db->tableExists('registrasi_jasa')) {
            $fields = $this->db->getFieldNames('registrasi_jasa');
            if (!in_array('deleted_at', $fields, true)) {
                $this->forge->addColumn('registrasi_jasa', [
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
        // registrasi
        if ($this->db->tableExists('registrasi')) {
            $fields = $this->db->getFieldNames('registrasi');
            if (in_array('deleted_at', $fields, true)) {
                $this->forge->dropColumn('registrasi', 'deleted_at');
            }
        }

        // registrasi_jasa
        if ($this->db->tableExists('registrasi_jasa')) {
            $fields = $this->db->getFieldNames('registrasi_jasa');
            if (in_array('deleted_at', $fields, true)) {
                $this->forge->dropColumn('registrasi_jasa', 'deleted_at');
            }
        }
    }
}
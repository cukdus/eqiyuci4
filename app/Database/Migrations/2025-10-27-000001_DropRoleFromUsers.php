<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Forge; 
use CodeIgniter\Database\Migration;
use Config\Database;

class DropRoleFromUsers extends Migration
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
        // Hapus kolom 'role' jika ada, karena peran dikelola via auth_groups
        $fields = $this->db->getFieldNames('users');
        if (in_array('role', $fields, true)) {
            $this->forge->dropColumn('users', 'role');
        }
    }

    public function down()
    {
        // Kembalikan kolom 'role' (sebagai VARCHAR) jika diperlukan
        $fields = $this->db->getFieldNames('users');
        if (!in_array('role', $fields, true)) {
            $this->forge->addColumn('users', [
                'role' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => true,
                    'after' => 'password_hash',
                ],
            ]);
        }
    }
}
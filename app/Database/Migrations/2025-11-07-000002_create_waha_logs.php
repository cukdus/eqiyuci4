<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWahaLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'unsigned' => true, 'auto_increment' => true ],
            'registrasi_id' => [ 'type' => 'INT', 'unsigned' => true, 'null' => true ],
            'scenario' => [ 'type' => 'VARCHAR', 'constraint' => 100 ],
            'recipient' => [ 'type' => 'VARCHAR', 'constraint' => 30 ],
            'phone' => [ 'type' => 'VARCHAR', 'constraint' => 30 ],
            'message' => [ 'type' => 'TEXT' ],
            'status' => [ 'type' => 'VARCHAR', 'constraint' => 20 ],
            'error' => [ 'type' => 'TEXT', 'null' => true ],
            'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['registrasi_id', 'scenario', 'recipient']);
        $this->forge->createTable('waha_logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('waha_logs', true);
    }
}
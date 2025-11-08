<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWahaQueue extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'unsigned' => true, 'auto_increment' => true ],
            'registrasi_id' => [ 'type' => 'INT', 'unsigned' => true, 'null' => true ],
            'scenario' => [ 'type' => 'VARCHAR', 'constraint' => 100 ],
            'recipient' => [ 'type' => 'VARCHAR', 'constraint' => 30 ],
            'phone' => [ 'type' => 'VARCHAR', 'constraint' => 30 ],
            'template_key' => [ 'type' => 'VARCHAR', 'constraint' => 100, 'null' => true ],
            'payload' => [ 'type' => 'TEXT', 'null' => true ],
            'status' => [ 'type' => 'VARCHAR', 'constraint' => 20, 'default' => 'queued' ],
            'attempts' => [ 'type' => 'INT', 'unsigned' => true, 'default' => 0 ],
            'next_attempt_at' => [ 'type' => 'DATETIME', 'null' => true ],
            'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['registrasi_id', 'scenario', 'recipient']);
        $this->forge->createTable('waha_queue', true);
    }

    public function down()
    {
        $this->forge->dropTable('waha_queue', true);
    }
}
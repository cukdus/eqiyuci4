<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BankTransactions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'account_no' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '18,2',
                'null' => false,
                'default' => '0.00',
            ],
            'crdb' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
                'null' => false,
            ],
            'txn_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'hash' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => false,
            ],
            'raw_html' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('hash', false, true); // unique
        $this->forge->createTable('bank_transactions', true);
    }

    public function down()
    {
        $this->forge->dropTable('bank_transactions', true);
    }
}
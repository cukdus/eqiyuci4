<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentMatches extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'registrasi_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false,
            ],
            'bank_transaction_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 20, // dp, pelunasan, full
                'null' => false,
            ],
            'amount_formatted' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'amount_numeric' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'period' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'matched_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'notes' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->addKey('registrasi_id');
        $this->forge->addKey('bank_transaction_id');
        $this->forge->addKey(['type', 'period']);

        // Foreign keys
        $this->forge->addForeignKey('registrasi_id', 'registrasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('bank_transaction_id', 'bank_transactions', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('payment_matches', true);
    }

    public function down()
    {
        $this->forge->dropTable('payment_matches', true);
    }
}
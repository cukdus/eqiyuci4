<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBcaJsonColumns extends Migration
{
    public function up()
    {
        // Tambah kolom-kolom yang dibutuhkan untuk menampung hasil parsingbca JSON
        $this->forge->addColumn('bank_transactions', [
            'account_number' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
                'after' => 'account_no',
            ],
            'period' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => true,
                'after' => 'account_number',
            ],
            'amount_formatted' => [
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => true,
                'after' => 'amount',
            ],
            'info' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'description',
            ],
            'opening_balance_formatted' => [
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => true,
                'after' => 'period',
            ],
            'credit_total_formatted' => [
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => true,
                'after' => 'opening_balance_formatted',
            ],
            'debit_total_formatted' => [
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => true,
                'after' => 'credit_total_formatted',
            ],
            'closing_balance_formatted' => [
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => true,
                'after' => 'debit_total_formatted',
            ],
        ]);
    }

    public function down()
    {
        // Hapus kolom-kolom yang ditambahkan jika rollback
        $this->forge->dropColumn('bank_transactions', 'account_number');
        $this->forge->dropColumn('bank_transactions', 'period');
        $this->forge->dropColumn('bank_transactions', 'amount_formatted');
        $this->forge->dropColumn('bank_transactions', 'info');
        $this->forge->dropColumn('bank_transactions', 'opening_balance_formatted');
        $this->forge->dropColumn('bank_transactions', 'credit_total_formatted');
        $this->forge->dropColumn('bank_transactions', 'debit_total_formatted');
        $this->forge->dropColumn('bank_transactions', 'closing_balance_formatted');
    }
}
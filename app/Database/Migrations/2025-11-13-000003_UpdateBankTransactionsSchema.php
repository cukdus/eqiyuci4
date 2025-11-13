<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateBankTransactionsSchema extends Migration
{
    public function up()
    {
        // Tambah kolom baru 'type'
        $this->forge->addColumn('bank_transactions', [
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
                'null' => false,
                'default' => '',
                'after' => 'amount_formatted',
            ],
        ]);

        // Migrasi nilai dari kolom lama 'crdb' ke 'type' jika ada
        $this->db->query("UPDATE `bank_transactions` SET `type` = `crdb` WHERE `type` = '' OR `type` IS NULL");

        // Hapus kolom lama yang tidak digunakan lagi
        $dropColumns = [
            'account_no',
            'description',
            'amount',
            'crdb',
            'raw_html',
            'hash',
            'txn_date',
        ];
        foreach ($dropColumns as $col) {
            // Gunakan try-catch agar migrasi tetap lanjut jika kolom sudah tidak ada
            try { $this->forge->dropColumn('bank_transactions', $col); } catch (\Throwable $e) {}
        }

        // Tambah unique index komposit untuk mencegah duplikasi
        // CodeIgniter Forge tidak mendukung addKey pada tabel existing, gunakan query langsung.
        $this->db->query("ALTER TABLE `bank_transactions` ADD UNIQUE KEY `uniq_bca_comp` (`account_number`,`period`,`amount_formatted`,`info`)");
    }

    public function down()
    {
        // Hapus unique index komposit
        $this->db->query("ALTER TABLE `bank_transactions` DROP INDEX `uniq_bca_comp`");

        // Tambahkan kembali kolom-kolom lama
        $this->forge->addColumn('bank_transactions', [
            'account_no' => [ 'type' => 'VARCHAR', 'constraint' => 64, 'null' => true ],
            'description' => [ 'type' => 'TEXT', 'null' => false ],
            'amount' => [ 'type' => 'DECIMAL', 'constraint' => '18,2', 'null' => false, 'default' => '0.00' ],
            'crdb' => [ 'type' => 'VARCHAR', 'constraint' => 2, 'null' => false ],
            'txn_date' => [ 'type' => 'DATE', 'null' => false ],
            'hash' => [ 'type' => 'VARCHAR', 'constraint' => 64, 'null' => false ],
            'raw_html' => [ 'type' => 'TEXT', 'null' => true ],
        ]);

        // Hapus kolom baru 'type'
        $this->forge->dropColumn('bank_transactions', 'type');
    }
}
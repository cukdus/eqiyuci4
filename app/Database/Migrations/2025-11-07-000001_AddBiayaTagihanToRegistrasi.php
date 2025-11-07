<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBiayaTagihanToRegistrasi extends Migration
{
    public function up()
    {
        $fields = [
            'biaya_tagihan' => [
                'type'       => 'decimal',
                'constraint' => '12,2',
                'null'       => true,
                'default'    => null,
                'after'      => 'biaya_dibayar',
            ],
        ];

        $this->forge->addColumn('registrasi', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('registrasi', 'biaya_tagihan');
    }
}
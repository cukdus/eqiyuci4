<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWhatsappSentToRegistrasi extends Migration
{
    public function up()
    {
        $fields = [
            'whatsapp_sent' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => false,
            ],
        ];
        $this->forge->addColumn('registrasi', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('registrasi', 'whatsapp_sent');
    }
}
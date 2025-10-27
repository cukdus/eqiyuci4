<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhotoToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Profile photo path',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'photo');
    }
}
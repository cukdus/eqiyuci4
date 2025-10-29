<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSertifikatTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'registrasi_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
            'kode_sertifikat' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'nama_siswa' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'kelas_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'kode_kelas' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'tanggal_terbit' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'file_url' => [
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
        $this->forge->createTable('sertifikat', true);
    }

    public function down()
    {
        $this->forge->dropTable('sertifikat', true);
    }
}
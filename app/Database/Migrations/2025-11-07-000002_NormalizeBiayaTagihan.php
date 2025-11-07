<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeBiayaTagihan extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        // Set semua biaya_tagihan NULL menjadi 0.00 untuk normalisasi data historis
        $db->query('UPDATE registrasi SET biaya_tagihan = 0.00 WHERE biaya_tagihan IS NULL');
    }

    public function down()
    {
        $db = \Config\Database::connect();
        // Kembalikan 0.00 menjadi NULL (hati-hati: ini bisa mempengaruhi data yang memang 0.00 secara valid)
        $db->query('UPDATE registrasi SET biaya_tagihan = NULL WHERE biaya_tagihan = 0.00');
    }
}
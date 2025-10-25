<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAllTables extends Migration
{
    public function up()
    {
        // Tabel kategori_berita
        $this->forge->addField([
            'id'            => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'nama_kategori' => ['type' => 'varchar', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nama_kategori');
        $this->forge->createTable('kategori_berita', true);

        // Tabel tag
        $this->forge->addField([
            'id'       => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'nama_tag' => ['type' => 'varchar', 'constraint' => 50],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nama_tag');
        $this->forge->createTable('tag', true);

        // Tabel berita
        $this->forge->addField([
            'id'            => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'judul'         => ['type' => 'varchar', 'constraint' => 150],
            'slug'          => ['type' => 'varchar', 'constraint' => 150],
            'konten'        => ['type' => 'text'],
            'gambar_utama'  => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'penulis'       => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'tanggal_terbit' => ['type' => 'datetime', 'null' => true, 'default' => null],
            'status'        => ['type' => 'enum', 'constraint' => ['draft', 'publish'], 'default' => 'draft'],
            'kategori_id'   => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addForeignKey('kategori_id', 'kategori_berita', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('berita', true);

        // Tabel berita_tag
        $this->forge->addField([
            'berita_id' => ['type' => 'int', 'constraint' => 10, 'unsigned' => true],
            'tag_id'    => ['type' => 'int', 'constraint' => 10, 'unsigned' => true],
        ]);
        $this->forge->addKey(['berita_id', 'tag_id'], true);
        $this->forge->addForeignKey('berita_id', 'berita', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id', 'tag', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('berita_tag', true);

        // Tabel kelas
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'kode_kelas'        => ['type' => 'varchar', 'constraint' => 20],
            'nama_kelas'        => ['type' => 'varchar', 'constraint' => 100],
            'slug'              => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'deskripsi_singkat' => ['type' => 'text', 'null' => true],
            'deskripsi'         => ['type' => 'text', 'null' => true],
            'harga'             => ['type' => 'decimal', 'constraint' => '12,2'],
            'durasi'            => ['type' => 'varchar', 'constraint' => 50, 'null' => true],
            'kategori'          => ['type' => 'enum', 'constraint' => ['Kursus', 'Jasa', 'kursusonline'], 'null' => true],
            'gambar_utama'      => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'gambar_tambahan'   => ['type' => 'text', 'null' => true],
            'status_kelas'      => ['type' => 'enum', 'constraint' => ['aktif', 'nonaktif', 'segera'], 'default' => 'aktif'],
            'badge'             => ['type' => 'varchar', 'constraint' => 50, 'null' => true],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'kota_tersedia'     => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode_kelas');
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('kelas', true);

        // Tabel jadwal_kelas
        $this->forge->addField([
            'id'              => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'kelas_id'        => ['type' => 'int', 'constraint' => 10, 'unsigned' => true],
            'tanggal_mulai'   => ['type' => 'date'],
            'tanggal_selesai' => ['type' => 'date'],
            'lokasi'          => ['type' => 'enum', 'constraint' => ['malang', 'jogja'], 'null' => true],
            'instruktur'      => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'kapasitas'       => ['type' => 'int', 'constraint' => 11, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kelas_id', 'kelas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('jadwal_kelas', true);

        // Tabel bonus_kelas
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'kode_kelas' => ['type' => 'varchar', 'constraint' => 20],
            'nama_file'  => ['type' => 'varchar', 'constraint' => 255],
            'path_file'  => ['type' => 'text'],
            'deskripsi'  => ['type' => 'text', 'null' => true],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kode_kelas', 'kelas', 'kode_kelas', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bonus_kelas', true);

        // Tabel course_online
        $this->forge->addField([
            'id'          => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'kelas_id'    => ['type' => 'int', 'constraint' => 10, 'unsigned' => true],
            'judul_modul' => ['type' => 'varchar', 'constraint' => 255],
            'deskripsi'   => ['type' => 'text', 'null' => true],
            'urutan'      => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'created_at'  => ['type' => 'datetime', 'null' => true],
            'updated_at'  => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('course_online', true);

        // Tabel modul_file
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'course_id'  => ['type' => 'int', 'constraint' => 10, 'unsigned' => true],
            'tipe'       => ['type' => 'varchar', 'constraint' => 20],
            'judul_file' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'file_url'   => ['type' => 'text'],
            'urutan'     => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'created_at' => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('modul_file', true);

        // Tabel voucher
        $this->forge->addField([
            'id'                    => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'kode_voucher'          => ['type' => 'varchar', 'constraint' => 50],
            'kelas_id'              => ['type' => 'int', 'constraint' => 10, 'unsigned' => true],
            'diskon_persen'         => ['type' => 'decimal', 'constraint' => '5,2'],
            'tanggal_berlaku_mulai' => ['type' => 'date', 'null' => true],
            'tanggal_berlaku_sampai' => ['type' => 'date', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode_voucher');
        $this->forge->addForeignKey('kelas_id', 'kelas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('voucher', true);

        // Tabel registrasi
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'kode_kelas'        => ['type' => 'varchar', 'constraint' => 20],
            'kode_voucher'      => ['type' => 'varchar', 'constraint' => 50, 'null' => true],
            'nama'              => ['type' => 'varchar', 'constraint' => 100],
            'no_telp'           => ['type' => 'varchar', 'constraint' => 20, 'null' => true],
            'alamat'            => ['type' => 'text', 'null' => true],
            'kecamatan'         => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'kabupaten'         => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'provinsi'          => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'kodepos'           => ['type' => 'varchar', 'constraint' => 10, 'null' => true],
            'email'             => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'lokasi'            => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'biaya_total'       => ['type' => 'decimal', 'constraint' => '12,2'],
            'biaya_dibayar'     => ['type' => 'decimal', 'constraint' => '12,2', 'default' => 0.00, 'null' => true],
            'status_pembayaran' => ['type' => 'enum', 'constraint' => ['DP 50%', 'lunas'], 'default' => 'lunas'],
            'tanggal_daftar'    => ['type' => 'datetime', 'null' => true],
            'tanggal_update'    => ['type' => 'datetime', 'null' => true],
            'jadwal_id'         => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'akses_aktif'       => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('jadwal_id', 'jadwal_kelas', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('kode_voucher', 'voucher', 'kode_voucher', 'SET NULL', 'CASCADE');
        $this->forge->createTable('registrasi', true);

        // Tabel registrasi_jasa
        $this->forge->addField([
            'id'                 => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'jasa_id'            => ['type' => 'int', 'constraint' => 10, 'unsigned' => true],
            'nama_pendaftar'     => ['type' => 'varchar', 'constraint' => 100],
            'no_telp'            => ['type' => 'varchar', 'constraint' => 20, 'null' => true],
            'email'              => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'alamat'             => ['type' => 'text', 'null' => true],
            'deskripsi_kebutuhan' => ['type' => 'text', 'null' => true],
            'kode_voucher'       => ['type' => 'varchar', 'constraint' => 50, 'null' => true],
            'tanggal_daftar'     => ['type' => 'datetime', 'null' => true],
            'status_pembayaran'  => ['type' => 'enum', 'constraint' => ['lunas'], 'default' => 'lunas'],
            'biaya_total'        => ['type' => 'decimal', 'constraint' => '12,2', 'null' => true],
            'catatan_admin'      => ['type' => 'text', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('jasa_id', 'kelas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kode_voucher', 'voucher', 'kode_voucher', 'SET NULL', 'CASCADE');
        $this->forge->createTable('registrasi_jasa', true);

        // Tabel sertifikat
        $this->forge->addField([
            'id'              => ['type' => 'int', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'registrasi_id'   => ['type' => 'int', 'constraint' => 10, 'unsigned' => true],
            'nama_pemilik'    => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'kota_kelas'      => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'nomor_sertifikat' => ['type' => 'varchar', 'constraint' => 50],
            'tanggal_terbit'  => ['type' => 'date', 'null' => true],
            'nama_kelas'      => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
            'status'          => ['type' => 'enum', 'constraint' => ['aktif', 'dibatalkan'], 'default' => 'aktif'],
            'created_at'      => ['type' => 'timestamp', 'null' => false],
            'updated_at'      => ['type' => 'timestamp', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nomor_sertifikat');
        $this->forge->addForeignKey('registrasi_id', 'registrasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sertifikat', true);

        // Tabel folders
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'name'             => ['type' => 'varchar', 'constraint' => 255],
            'parent_id'        => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'google_photos_url' => ['type' => 'text', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('parent_id', 'folders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('folders', true);

        // Tambahkan kolom nama_lengkap ke tabel users (Myth Auth)
        $fields = [
            'nama_lengkap' => ['type' => 'varchar', 'constraint' => 100, 'null' => true, 'after' => 'username'],
            'role'         => ['type' => 'enum', 'constraint' => ['superadmin', 'admin', 'staff'], 'default' => 'staff', 'after' => 'nama_lengkap'],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        // Hapus tabel dalam urutan terbalik untuk menghindari masalah foreign key
        $this->forge->dropTable('sertifikat', true);
        $this->forge->dropTable('registrasi_jasa', true);
        $this->forge->dropTable('registrasi', true);
        $this->forge->dropTable('voucher', true);
        $this->forge->dropTable('modul_file', true);
        $this->forge->dropTable('course_online', true);
        $this->forge->dropTable('bonus_kelas', true);
        $this->forge->dropTable('jadwal_kelas', true);
        $this->forge->dropTable('kelas', true);
        $this->forge->dropTable('berita_tag', true);
        $this->forge->dropTable('berita', true);
        $this->forge->dropTable('tag', true);
        $this->forge->dropTable('kategori_berita', true);
        $this->forge->dropTable('folders', true);
        
        // Hapus kolom yang ditambahkan ke tabel users
        $this->forge->dropColumn('users', ['nama_lengkap', 'role']);
    }
}

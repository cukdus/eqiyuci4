<?php

namespace App\Commands;

use App\Libraries\BcaImporter;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class BankScrape extends BaseCommand
{
    protected $group       = 'bank';
    protected $name        = 'bank:scrape';
    protected $description = 'Jalankan parser mobile KlikBCA (scripts/parsingbca.php) untuk menghasilkan JSON hari ini dan impor ke tabel bank_transactions';

    public function run(array $params)
    {
        // Gantikan scraper lama: jalankan parser mobile KlikBCA dan impor JSON
        $importer = new BcaImporter();
        $result = $importer->importFromJson(true);

        if (!($result['success'] ?? false)) {
            CLI::error('Scrape/impor gagal: ' . ($result['message'] ?? 'unknown error'));
            return;
        }

        CLI::write("Scrape & impor selesai. Inserted: {$result['inserted']}, Skipped (duplikat): {$result['skipped']}");
    }
}
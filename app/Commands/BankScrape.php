<?php

namespace App\Commands;

use App\Libraries\BcaImporter;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class BankScrape extends BaseCommand
{
    protected $group = 'bank';
    protected $name = 'bank:scrape';
    protected $description = 'Jalankan parser mobile KlikBCA (scripts/parsingbca.php) untuk menghasilkan JSON hari ini dan impor ke tabel bank_transactions';

    public function run(array $params)
    {
        $importer = new BcaImporter();
        $skip = CLI::getOption('skip-run');
        $json = (string) (CLI::getOption('json') ?? '');
        $parser = (string) (CLI::getOption('parser') ?? '');
        $runParser = ($skip === null);
        $result = $importer->importFromJson($runParser, $json, ($parser !== '' ? $parser : null));
        if (!($result['success'] ?? false) && !$runParser) {
            $result = $importer->importFromJson(true, $json, ($parser !== '' ? $parser : null));
        }

        if (!($result['success'] ?? false)) {
            CLI::error('Scrape/impor gagal: ' . ($result['message'] ?? 'unknown error'));
            return;
        }

        if (isset($result['message']) && is_string($result['message']) && $result['message'] !== '') {
            CLI::write($result['message']);
        }

        CLI::write("Scrape & impor selesai. Inserted: {$result['inserted']}, Skipped (duplikat): {$result['skipped']}");
    }
}

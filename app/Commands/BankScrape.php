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

        // Always import YESTERDAY then TODAY to cover overnight transactions
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        // Import yesterday
        $resY = $importer->importFromJson($runParser, $json, ($parser !== '' ? $parser : null), $yesterday, $yesterday);
        if (!($resY['success'] ?? false) && !$runParser) {
            $resY = $importer->importFromJson(true, $json, ($parser !== '' ? $parser : null), $yesterday, $yesterday);
        }
        // Import today
        $resT = $importer->importFromJson($runParser, $json, ($parser !== '' ? $parser : null), $today, $today);
        if (!($resT['success'] ?? false) && !$runParser) {
            $resT = $importer->importFromJson(true, $json, ($parser !== '' ? $parser : null), $today, $today);
        }

        if (!($resY['success'] ?? false) && !($resT['success'] ?? false)) {
            CLI::error('Scrape/impor gagal: ' . ($resT['message'] ?? $resY['message'] ?? 'unknown error'));
            return;
        }

        if (isset($resY['message']) && is_string($resY['message']) && $resY['message'] !== '') {
            CLI::write('[Yesterday] ' . $resY['message']);
        }
        if (isset($resT['message']) && is_string($resT['message']) && $resT['message'] !== '') {
            CLI::write('[Today] ' . $resT['message']);
        }

        $inserted = (int) ($resY['inserted'] ?? 0) + (int) ($resT['inserted'] ?? 0);
        $skipped = (int) ($resY['skipped'] ?? 0) + (int) ($resT['skipped'] ?? 0);
        CLI::write("Scrape & impor selesai. Inserted: {$inserted}, Skipped (duplikat): {$skipped}");
    }
}

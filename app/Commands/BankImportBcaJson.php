<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\BcaImporter;

class BankImportBcaJson extends BaseCommand
{
    protected $group = 'bank';
    protected $name = 'bank:import-bca';
    protected $description = 'Jalankan parsingbca.php dan impor hasil JSON ke tabel bank_transactions (deduplikasi otomatis).';
    protected $usage = 'bank:import-bca [--skip-run] [--json="C:/wamp64/www/test/KlikBCA/hasil/mutasirekening.json"] [--script="C:/wamp64/www/test/KlikBCA/parsingbca.php"]';

    public function run(array $params)
    {
        $skipRun = CLI::getOption('skip-run') !== null;
        $jsonPath = CLI::getOption('json') ?? 'C:/wamp64/www/test/KlikBCA/hasil/mutasirekening.json';
        $scriptPath = CLI::getOption('script') ?? 'C:/wamp64/www/test/KlikBCA/parsingbca.php';

        $importer = new BcaImporter();
        $result = $importer->importFromJson(!$skipRun ? true : false, $jsonPath, $scriptPath);

        if (!$result['success']) {
            CLI::error($result['message']);
            return;
        }

        CLI::write("Impor selesai. Inserted: {$result['inserted']}, Skipped (duplikat): {$result['skipped']}");
    }
}
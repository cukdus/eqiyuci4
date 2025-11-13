<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\BcaImporter;

class BankImportBcaJson extends BaseCommand
{
    protected $group = 'bank';
    protected $name = 'bank:import-bca';
    protected $description = 'Jalankan parsingbca.php (opsional) dan impor hasil JSON ke tabel bank_transactions (deduplikasi otomatis). Path dibatasi ke dalam aplikasi (ROOTPATH/WRITEPATH). Mendukung ENV BCA_JSON_PATH dan BCA_PARSER_PATH.';
    protected $usage = 'bank:import-bca [--skip-run] [--json="writable/klikbca/hasil/mutasirekening.json"] [--script="scripts/parsingbca.php"]';

    public function run(array $params)
    {
        $skipRun = CLI::getOption('skip-run') !== null;

        // Defaults strictly inside the application
        $defaultJson = rtrim(WRITEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'klikbca' . DIRECTORY_SEPARATOR . 'hasil' . DIRECTORY_SEPARATOR . 'mutasirekening.json';
        $defaultScript = rtrim(ROOTPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'parsingbca.php';

        $jsonEnv = env('BCA_JSON_PATH');
        $scriptEnv = env('BCA_PARSER_PATH');

        $jsonPath = CLI::getOption('json') ?? ($jsonEnv ?: $defaultJson);
        $scriptPath = CLI::getOption('script') ?? ($scriptEnv ?: $defaultScript);

        // Enforce paths to be inside ROOTPATH or WRITEPATH
        $normalize = static function(string $p): string {
            return str_replace('\\', '/', $p);
        };
        $allowedBases = [
            rtrim($normalize(ROOTPATH), '/'),
            rtrim($normalize(WRITEPATH), '/'),
        ];
        $isAllowed = static function(string $p) use ($allowedBases, $normalize): bool {
            $np = $normalize($p);
            foreach ($allowedBases as $base) {
                if (str_starts_with($np, $base)) { return true; }
            }
            return false;
        };
        if (!$isAllowed($jsonPath)) {
            CLI::error('Path JSON di luar aplikasi tidak diizinkan. Gunakan lokasi di writable/ atau scripts/');
            return;
        }
        if (!$skipRun && !$isAllowed((string)$scriptPath)) {
            CLI::error('Path script parser di luar aplikasi tidak diizinkan. Gunakan scripts/parsingbca.php di ROOTPATH.');
            return;
        }

        $importer = new BcaImporter();
        $result = $importer->importFromJson(!$skipRun ? true : false, (string)$jsonPath, (string)$scriptPath);

        if (!$result['success']) {
            CLI::error($result['message']);
            return;
        }

        CLI::write("Impor selesai. Inserted: {$result['inserted']}, Skipped (duplikat): {$result['skipped']}");
    }
}
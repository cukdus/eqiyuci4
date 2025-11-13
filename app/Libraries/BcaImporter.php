<?php
namespace App\Libraries;

use App\Models\BankTransaction;

class BcaImporter
{
    /**
     * Import dari JSON hasil parsingbca
     * @param bool $runParser Jalankan skrip parsing terlebih dahulu
     * @param string $jsonPath Path JSON hasil
     * @param string|null $scriptPath Path skrip parsingbca.php (jika runParser=true)
     * @return array [success=>bool, inserted=>int, skipped=>int, message=>string]
     */
    public function importFromJson(bool $runParser = false, string $jsonPath = '', ?string $scriptPath = null): array
    {
        // Resolve defaults for Docker/Linux or Windows via ENV and OS detection
        // Defaults strictly inside the application
        $defaultJson = rtrim(WRITEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'klikbca' . DIRECTORY_SEPARATOR . 'hasil' . DIRECTORY_SEPARATOR . 'mutasirekening.json';
        $defaultScript = rtrim(ROOTPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'parsingbca.php';

        $jsonPath = $jsonPath !== '' ? $jsonPath : ((string)(env('BCA_JSON_PATH') ?? $defaultJson));
        $scriptPath = $scriptPath ?? (env('BCA_PARSER_PATH') ?: $defaultScript);

        // Enforce paths to be inside ROOTPATH or WRITEPATH
        $normalize = static function(string $p): string { return str_replace('\\', '/', $p); };
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
            return ['success' => false, 'inserted' => 0, 'skipped' => 0, 'message' => 'Path JSON di luar aplikasi tidak diizinkan'];
        }

        if ($runParser) {
            if (!function_exists('shell_exec')) {
                return ['success' => false, 'inserted' => 0, 'skipped' => 0, 'message' => 'shell_exec tidak tersedia, tidak bisa menjalankan parser'];
            }
            if (!is_file((string)$scriptPath)) {
                return ['success' => false, 'inserted' => 0, 'skipped' => 0, 'message' => 'Script parsingbca.php tidak ditemukan'];
            }
            if (!$isAllowed((string)$scriptPath)) {
                return ['success' => false, 'inserted' => 0, 'skipped' => 0, 'message' => 'Path script parser di luar aplikasi tidak diizinkan'];
            }
            $cmd = 'php ' . escapeshellarg((string)$scriptPath);
            @shell_exec($cmd);
        }

        if (!is_file($jsonPath)) {
            return ['success' => false, 'inserted' => 0, 'skipped' => 0, 'message' => 'File JSON hasil tidak ditemukan'];
        }
        $json = file_get_contents($jsonPath);
        if ($json === false) {
            return ['success' => false, 'inserted' => 0, 'skipped' => 0, 'message' => 'Gagal membaca file JSON'];
        }
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return ['success' => false, 'inserted' => 0, 'skipped' => 0, 'message' => 'Format JSON tidak valid'];
        }

        $header = $data['header'] ?? [];
        $summary = $data['summary'] ?? [];
        $transactions = $data['transactions'] ?? [];

        $accountNumber = (string)($header['account_number'] ?? '');
        $period = (string)($header['period'] ?? '');
        $openingFmt = (string)($summary['opening_balance_formatted'] ?? '');
        $creditFmt = (string)($summary['credit_total_formatted'] ?? '');
        $debitFmt = (string)($summary['debit_total_formatted'] ?? '');
        $closingFmt = (string)($summary['closing_balance_formatted'] ?? '');

        if (!is_array($transactions) || empty($transactions)) {
            return ['success' => true, 'inserted' => 0, 'skipped' => 0, 'message' => 'Tidak ada transaksi untuk diimpor'];
        }

        $model = model(BankTransaction::class);
        $inserted = 0; $skipped = 0;

        foreach ($transactions as $tx) {
            $amountFormatted = (string)($tx['amount_formatted'] ?? '');
            $info = (string)($tx['info'] ?? '');
            $type = strtoupper((string)($tx['type'] ?? ''));

            // Dedup via komposit keys (sesuai unique index tabel)
            $exists = $model->where([
                'account_number' => $accountNumber ?: null,
                'period' => $period ?: null,
                'amount_formatted' => $amountFormatted ?: null,
                'info' => $info ?: null,
            ])->first();
            if ($exists) { $skipped++; continue; }

            $ok = $model->insert([
                'account_number' => $accountNumber ?: null,
                'period' => $period ?: null,
                'amount_formatted' => $amountFormatted ?: null,
                'info' => $info ?: null,
                'type' => $type ?: '',
                'opening_balance_formatted' => $openingFmt ?: null,
                'credit_total_formatted' => $creditFmt ?: null,
                'debit_total_formatted' => $debitFmt ?: null,
                'closing_balance_formatted' => $closingFmt ?: null,
            ], true);
            if ($ok !== false) $inserted++; else $skipped++;
        }

        return ['success' => true, 'inserted' => $inserted, 'skipped' => $skipped, 'message' => 'Impor selesai'];
    }
}
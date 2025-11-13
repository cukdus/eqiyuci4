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
    public function importFromJson(bool $runParser = false, string $jsonPath = 'C:/wamp64/www/test/KlikBCA/hasil/mutasirekening.json', ?string $scriptPath = 'C:/wamp64/www/test/KlikBCA/parsingbca.php'): array
    {
        if ($runParser) {
            if (!is_file((string)$scriptPath)) {
                return ['success' => false, 'inserted' => 0, 'skipped' => 0, 'message' => 'Script parsingbca.php tidak ditemukan'];
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
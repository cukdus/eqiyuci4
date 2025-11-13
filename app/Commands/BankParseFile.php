<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\BankBcaScraper;
use App\Models\BankTransaction;

class BankParseFile extends BaseCommand
{
    protected $group = 'bank';
    protected $name = 'bank:parsefile';
    protected $description = 'Parse HTML Account Statement KlikBCA lokal dan simpan ke database';
    protected $usage = 'bank:parsefile <path_to_html> [YYYY-MM-DD]';

    public function run(array $params)
    {
        $path = $params[0] ?? null;
        if (!$path) {
            CLI::error('Path file HTML wajib diisi.');
            return;
        }
        if (!is_file($path)) {
            CLI::error('File tidak ditemukan: ' . $path);
            return;
        }
        $dateStr = $params[1] ?? date('Y-m-d');
        $date = \DateTime::createFromFormat('Y-m-d', $dateStr) ?: new \DateTime();
        $dateStr = $date->format('Y-m-d');

        $html = file_get_contents($path);
        if ($html === false) {
            CLI::error('Gagal membaca file: ' . $path);
            return;
        }

        $scraper = new BankBcaScraper();
        $parsed = $scraper->parseStatementHtml($html);
        $accountNo = $parsed['account_no'] ?? null;
        $txns = $parsed['transactions'] ?? [];

        if (empty($txns)) {
            CLI::write('Tidak menemukan baris transaksi dari file HTML ini.', 'yellow');
            return;
        }

        $model = model(BankTransaction::class);
        $inserted = 0; $skipped = 0;
        foreach ($txns as $tx) {
            $desc = (string) ($tx['description'] ?? '');
            $amount = (float) ($tx['amount'] ?? 0);
            $crdb = (string) ($tx['crdb'] ?? '');
            if ($desc === '' && $amount == 0) { $skipped++; continue; }
            $hash = sha1(($accountNo ?: '-') . '|' . $dateStr . '|' . $desc . '|' . number_format($amount, 2, '.', '') . '|' . ($crdb ?: '-'));

            $exists = $model->where('hash', $hash)->first();
            if ($exists) { $skipped++; continue; }
            $ok = $model->insert([
                'account_no' => $accountNo ?: null,
                'description' => $desc,
                'amount' => $amount,
                'crdb' => $crdb ?: '',
                'txn_date' => $dateStr,
                'hash' => $hash,
                'raw_html' => $html,
            ], true);
            if ($ok !== false) $inserted++; else $skipped++;
        }

        CLI::write("Parse file selesai. Inserted: {$inserted}, Skipped: {$skipped} untuk tanggal {$dateStr}.", 'green');
    }
}
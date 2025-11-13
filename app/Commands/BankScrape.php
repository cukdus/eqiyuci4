<?php

namespace App\Commands;

use App\Libraries\BankBcaScraper;
use App\Models\BankTransaction;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class BankScrape extends BaseCommand
{
    protected $group       = 'bank';
    protected $name        = 'bank:scrape';
    protected $description = 'Scrape KlikBCA account statement for today and store to DB';

    public function run(array $params)
    {
        $scraper = new BankBcaScraper();
        $dateParam = $params[0] ?? null;
        if ($dateParam) {
            try {
                $dt = new \DateTime($dateParam);
                $result = $scraper->fetchAccountStatementForDate($dt);
            } catch (\Throwable $e) {
                $result = $scraper->fetchAccountStatementForToday();
            }
        } else {
            $result = $scraper->fetchAccountStatementForToday();
        }

        if (!($result['success'] ?? false)) {
            CLI::error('Scrape gagal: ' . ($result['message'] ?? 'unknown error'));
            $rawFail = (string)($result['raw'] ?? '');
            if ($rawFail !== '') {
                $dateFail = (string)($result['date'] ?? date('Y-m-d'));
                $dir = WRITEPATH . 'klikbca';
                if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
                $file = $dir . DIRECTORY_SEPARATOR . 'statement_' . $dateFail . '.html';
                @file_put_contents($file, $rawFail);
                CLI::write('Snapshot HTML disimpan: ' . $file);
            }
            return;
        }

        $date = (string) ($result['date'] ?? date('Y-m-d'));
        $accountNo = (string) ($result['account_no'] ?? '');
        $transactions = (array) ($result['transactions'] ?? []);

        $model = model(BankTransaction::class);
        $inserted = 0; $skipped = 0;
        $raw = (string)($result['raw'] ?? '');
        foreach ($transactions as $tx) {
            $desc = (string) ($tx['description'] ?? '');
            $amount = (float) ($tx['amount'] ?? 0);
            $crdb = (string) ($tx['crdb'] ?? '');
            if ($desc === '' && $amount == 0) { $skipped++; continue; }
            $hash = sha1(($accountNo ?: '-') . '|' . $date . '|' . $desc . '|' . number_format($amount, 2, '.', '') . '|' . ($crdb ?: '-'));

            $exists = $model->where('hash', $hash)->first();
            if ($exists) { $skipped++; continue; }
            $ok = $model->insert([
                'account_no' => $accountNo ?: null,
                'description' => $desc,
                'amount' => $amount,
                'crdb' => $crdb ?: '',
                'txn_date' => $date,
                'hash' => $hash,
                'raw_html' => $raw ?: null,
            ], true);
            if ($ok !== false) $inserted++; else $skipped++;
        }

        CLI::write("Scrape selesai untuk {$date}. Inserted: {$inserted}, Skipped: {$skipped}");
        if ($inserted === 0 && $skipped === 0) {
            CLI::write('Tidak ada transaksi baru untuk disimpan.');
        }
    }
}
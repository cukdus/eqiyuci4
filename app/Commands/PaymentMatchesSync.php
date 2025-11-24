<?php

namespace App\Commands;

use App\Models\PaymentMatch;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class PaymentMatchesSync extends BaseCommand
{
    protected $group = 'Bank';
    protected $name = 'payment:sync-matches';
    protected $description = 'Sinkronisasi payment_matches dari data registrasi dan mutasi bank (dibatasi periode jadwal).';

    public function run(array $params)
    {
        $db = Database::connect();
        $pmModel = new PaymentMatch();

        $registrasiId = CLI::getOption('registrasiId');
        $jadwalId = CLI::getOption('jadwalId');
        $from = CLI::getOption('from');  // YYYY-MM-DD
        $to = CLI::getOption('to');  // YYYY-MM-DD
        $verbose = CLI::getOption('verbose');  // any truthy value enables verbose

        // Format angka sesuai database: "10353.00" (desimal titik, tanpa pemisah ribuan)
        $formatPlain = static function ($num): string {
            if (!is_numeric($num)) {
                return '';
            }
            return number_format((float) $num, 2, '.', '');
        };
        $toNumeric = static function ($str): float {
            // Database menyimpan amount_formatted seperti "10353.00"
            // Jika ada varian, normalisasi: buang spasi, pastikan titik desimal
            $s = trim((string) $str);
            // Jika format Indonesia (1.234,56) pernah muncul, konversi:
            if (preg_match('/^\d{1,3}(\.\d{3})*,\d{2}$/', $s)) {
                $s = str_replace('.', '', $s);
                $s = str_replace(',', '.', $s);
            }
            return is_numeric($s) ? (float) $s : 0.0;
        };

        $parsePeriod = static function ($periodStr): array {
            $s = trim((string) $periodStr);
            if ($s === '')
                return ['start' => null, 'end' => null];
            $parts = preg_split('/\s*-\s*/', $s);
            $normalize = static function ($v): ?string {
                $v = trim((string) $v);
                if ($v === '')
                    return null;
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $v))
                    return $v;
                if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $v, $m)) {
                    return $m[3] . '-' . $m[2] . '-' . $m[1];
                }
                if (preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $v, $m)) {
                    return $m[3] . '-' . $m[2] . '-' . $m[1];
                }
                return null;
            };
            $start = isset($parts[0]) ? $normalize($parts[0]) : null;
            $end = isset($parts[1]) ? $normalize($parts[1]) : $start;
            return ['start' => $start, 'end' => $end];
        };

        $rQB = $db
            ->table('registrasi r')
            ->select('r.id, r.nama, r.email, r.biaya_dibayar, r.biaya_tagihan, r.jadwal_id, k.nama_kelas')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->where('r.deleted_at', null);

        if (!empty($registrasiId)) {
            $rQB->where('r.id', (int) $registrasiId);
        }
        if (!empty($jadwalId)) {
            $rQB->where('r.jadwal_id', (int) $jadwalId);
        }

        $registrations = $rQB->get()->getResultArray();
        $countProcessed = 0;
        $countInserted = 0;
        $countSkipped = 0;

        foreach ($registrations as $r) {
            $rid = (int) ($r['id'] ?? 0);
            $dibayar = $r['biaya_dibayar'] ?? 0;
            $tagihan = $r['biaya_tagihan'] ?? 0;
            $jid = (int) ($r['jadwal_id'] ?? 0);

            // Ambil periode jadwal
            $mulai = null;
            $selesai = null;
            if ($jid > 0) {
                $jr = $db->table('jadwal_kelas')->select('tanggal_mulai, tanggal_selesai')->where('id', $jid)->get()->getRowArray();
                $mulai = $jr['tanggal_mulai'] ?? null;
                $selesai = $jr['tanggal_selesai'] ?? null;
            }
            // Override dari CLI option jika diset
            if (!empty($from)) {
                $mulai = $from;
            }
            if (!empty($to)) {
                $selesai = $to;
            }

            $formattedDibayar = $formatPlain($dibayar);
            $formattedTagihan = $formatPlain($tagihan);
            $dp50 = (is_numeric($dibayar) && is_numeric($tagihan) && (float) $tagihan > 0 && abs(((float) $dibayar) - 0.5 * (float) $tagihan) < 0.01);

            $btQB = $db->table('bank_transactions')->select('id, amount_formatted, credit_total_formatted, period');
            $nums = array_values(array_filter([$formattedDibayar, $formattedTagihan]));
            $btQB
                ->groupStart()
                ->whereIn('credit_total_formatted', $nums)
                ->orWhereIn('amount_formatted', $nums)
                ->groupEnd();
            $bankTxs = $btQB->get()->getResultArray();

            // Terapkan filter periode: default hanya batas atas (<= tanggal_selesai jadwal).
            // Jika --from/--to diberikan, gunakan keduanya.
            if (!empty($from) || !empty($to) || !empty($selesai)) {
                $bankTxs = array_values(array_filter($bankTxs, function ($bt) use ($from, $to, $selesai, $parsePeriod) {
                    $p = $parsePeriod($bt['period'] ?? '');
                    $start = $p['start'];
                    $end = $p['end'];
                    if (!empty($from)) {
                        if ($start === null || $start < $from)
                            return false;
                    }
                    if (!empty($to)) {
                        if ($end === null || $end > $to)
                            return false;
                    }
                    if (empty($to) && !empty($selesai)) {
                        if ($end === null || $end > $selesai)
                            return false;
                    }
                    return true;
                }));
            }

            $countProcessed++;
            if ($verbose) {
                CLI::write("[INFO] R#$rid jadwal=($mulai .. $selesai) dibayar={$formattedDibayar} tagihan={$formattedTagihan}", 'white');
                CLI::write('       Kandidat BT: ' . implode(', ', array_map(function ($bt) use ($parsePeriod) {
                    $p = $parsePeriod($bt['period'] ?? '');
                    return 'BT#' . $bt['id'] . '=' . (($bt['credit_total_formatted'] ?? '')) . '|' . (($bt['amount_formatted'] ?? '')) . '@' . $bt['period'] . ' [' . ($p['start'] ?? '?') . '..' . ($p['end'] ?? '?') . ']';
                }, $bankTxs)), 'white');
            }
            if (empty($bankTxs)) {
                $periodLabel = (!empty($from) || !empty($to) || !empty($selesai))
                    ? ('[' . ($from ?: ($mulai ?: '?')) . '..' . (($to ?: $selesai) ?: '?') . ']')
                    : '(tanpa filter periode)';
                CLI::write("[SKIP] R#$rid tidak ada mutasi cocok dalam periode $periodLabel", 'yellow');
                $countSkipped++;
                continue;
            }

            foreach ($bankTxs as $bt) {
                $type = null;
                $matchDibayar = ($formattedDibayar !== '') && (((string) ($bt['credit_total_formatted'] ?? '') === $formattedDibayar) || ((string) ($bt['amount_formatted'] ?? '') === $formattedDibayar));
                $matchTagihan = ($formattedTagihan !== '') && (((string) ($bt['credit_total_formatted'] ?? '') === $formattedTagihan) || ((string) ($bt['amount_formatted'] ?? '') === $formattedTagihan));
                if ($matchDibayar) {
                    if ($dibayar == $tagihan) {
                        $type = 'full';
                    } elseif ($dp50) {
                        $type = 'dp';
                    } else {
                        $type = 'dibayar';
                    }
                } elseif ($matchTagihan) {
                    $type = 'pelunasan';
                }

                if ($type === null) {
                    continue;
                }

                // Cek duplikasi
                $exists = $db
                    ->table('payment_matches')
                    ->where('registrasi_id', $rid)
                    ->where('bank_transaction_id', (int) $bt['id'])
                    ->where('type', $type)
                    ->countAllResults();

                if ($exists > 0) {
                    CLI::write("[EXIST] R#$rid + BT#{$bt['id']} ($type)", 'blue');
                    continue;
                }

                $amtFmt = (string) ($bt['amount_formatted'] ?? '');
                if ($amtFmt === '') {
                    $amtFmt = (string) ($bt['credit_total_formatted'] ?? '');
                }
                $pmModel->insert([
                    'registrasi_id' => $rid,
                    'bank_transaction_id' => (int) $bt['id'],
                    'type' => $type,
                    'amount_formatted' => $amtFmt,
                    'amount_numeric' => $toNumeric($amtFmt),
                    'period' => (string) ($bt['period'] ?? null),
                    'matched_at' => date('Y-m-d H:i:s'),
                    'notes' => 'Auto-sync by CLI',
                ]);
                CLI::write("[ADD ] R#$rid + BT#{$bt['id']} ($type) amount={$amtFmt} period={$bt['period']}", 'green');
                $countInserted++;
            }
        }

        CLI::write("Selesai. processed=$countProcessed inserted=$countInserted skipped=$countSkipped", 'green');
    }
}

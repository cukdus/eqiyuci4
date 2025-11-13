<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use App\Libraries\WahaService;
use App\Models\WahaTemplate;
use App\Models\WahaLog;

class ReminderSendH3 extends BaseCommand
{
    protected $group = 'reminder';
    protected $name = 'reminder:send-h3';
    protected $description = 'Kirim pengingat H-3 untuk pelunasan dan/atau jadwal kelas melalui WAHA.';

    public function run(array $params)
    {
        $scenario = strtolower((string)(CLI::getOption('scenario') ?? 'all')); // pelunasan|jadwal|all
        $forDate = (string)(CLI::getOption('for') ?? date('Y-m-d', strtotime('+3 days'))); // tanggal_mulai target
        $verbose = CLI::getOption('verbose') !== null;

        $waha = new WahaService();
        if (!$waha->isConfigured()) {
            CLI::error('WAHA belum dikonfigurasi (set WAHA_BASE_URL dan WAHA_API_TOKEN).');
            return;
        }

        $db = Database::connect();
        $tplModel = new WahaTemplate();
        $logModel = new WahaLog();

        $tplPelunasan = $this->getTemplate($tplModel, 'reminder_pelunasan_h3',
            'Halo {{nama}}, ini pengingat H-3 sebelum kelas {{nama_kelas}} mulai pada {{tanggal_mulai}}. Sisa pembayaran: Rp {{sisa_bayar}}. Mohon pelunasan sebelum hari H. Terima kasih.');
        $tplJadwal = $this->getTemplate($tplModel, 'reminder_jadwal_h3',
            'Halo {{nama}}, pengingat H-3: kelas {{nama_kelas}} akan mulai pada {{tanggal_mulai}}. Sampai jumpa!');

        $countSent = 0; $countFail = 0; $countSkip = 0;

        // Query registrasi yg jadwalnya mulai pada forDate
        $qb = $db->table('registrasi r')
            ->select('r.id, r.nama, r.email, r.no_telp, r.biaya_tagihan, r.biaya_dibayar, r.status_pembayaran, r.kode_kelas, k.nama_kelas, j.tanggal_mulai')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->join('jadwal_kelas j', 'j.id = r.jadwal_id', 'left')
            ->where('r.deleted_at', null)
            ->where('j.tanggal_mulai', $forDate);

        $rows = $qb->get()->getResultArray();
        if ($verbose) {
            CLI::write('Target tanggal_mulai: ' . $forDate . '; kandidat: ' . count($rows));
        }

        foreach ($rows as $r) {
            $rid = (int)($r['id'] ?? 0);
            $nama = (string)($r['nama'] ?? '');
            $kelas = (string)($r['nama_kelas'] ?? '');
            $tglMulai = (string)($r['tanggal_mulai'] ?? $forDate);
            $phone = (string)($r['no_telp'] ?? '');
            $tagihan = (float)($r['biaya_tagihan'] ?? 0);
            $dibayar = (float)($r['biaya_dibayar'] ?? 0);
            $statusBayar = (string)($r['status_pembayaran'] ?? '');
            $sisa = max(0.0, $tagihan - $dibayar);

            $data = [
                'nama' => $nama,
                'nama_kelas' => $kelas,
                'tanggal_mulai' => $tglMulai,
                'sisa_bayar' => $this->formatRupiah($sisa),
                'total_tagihan' => $this->formatRupiah($tagihan),
                'dibayar' => $this->formatRupiah($dibayar),
            ];

            $sendPelunasan = ($scenario === 'pelunasan' || $scenario === 'all');
            $sendJadwal = ($scenario === 'jadwal' || $scenario === 'all');

            // Pelunasan: hanya jika belum lunas
            if ($sendPelunasan) {
                $eligiblePelunasan = ($sisa > 0.0) && (strtolower($statusBayar) !== 'lunas');
                if ($eligiblePelunasan) {
                    $msg = $waha->renderTemplate($tplPelunasan, $data);
                    $res = $waha->sendMessage($phone, $msg);
                    $ok = (bool)($res['success'] ?? false);
                    $this->log($logModel, $rid, 'pelunasan_h3', $nama, $phone, $msg, $ok, $ok ? null : ($res['message'] ?? ''));
                    if ($ok) { $countSent++; } else { $countFail++; }
                    if ($verbose) { CLI::write(($ok ? '[SENT ] ' : '[FAIL ] ') . 'R#' . $rid . ' pelunasan_h3 to ' . $phone); }
                } else {
                    $countSkip++;
                    if ($verbose) { CLI::write('[SKIP ] R#' . $rid . ' tidak memenuhi syarat pelunasan_h3'); }
                }
            }

            if ($sendJadwal) {
                $msg = $waha->renderTemplate($tplJadwal, $data);
                $res = $waha->sendMessage($phone, $msg);
                $ok = (bool)($res['success'] ?? false);
                $this->log($logModel, $rid, 'jadwal_h3', $nama, $phone, $msg, $ok, $ok ? null : ($res['message'] ?? ''));
                if ($ok) { $countSent++; } else { $countFail++; }
                if ($verbose) { CLI::write(($ok ? '[SENT ] ' : '[FAIL ] ') . 'R#' . $rid . ' jadwal_h3 to ' . $phone); }
            }
        }

        CLI::write('Selesai H-3: sent=' . $countSent . ' fail=' . $countFail . ' skip=' . $countSkip, 'green');
    }

    private function getTemplate(WahaTemplate $tplModel, string $key, string $default): string
    {
        $row = $tplModel->where('key', $key)->first();
        if ($row && ($row['enabled'] ?? false)) {
            $tpl = (string)($row['template'] ?? '');
            if ($tpl !== '') { return $tpl; }
        }
        return $default;
    }

    private function log(WahaLog $logModel, int $rid, string $scenario, string $recipient, string $phone, string $message, bool $success, ?string $error): void
    {
        $logModel->insert([
            'registrasi_id' => $rid,
            'scenario' => $scenario,
            'recipient' => $recipient,
            'phone' => $phone,
            'message' => $message,
            'status' => $success ? 'sent' : 'failed',
            'error' => $error,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function formatRupiah(float $amount): string
    {
        return number_format($amount, 0, ',', '.');
    }
}
<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use App\Libraries\WahaService;
use App\Models\WahaTemplate;
use App\Models\WahaLog;
use App\Models\WahaConfig;

class ReminderSendTagihanH3 extends BaseCommand
{
    protected $group = 'reminder';
    protected $name = 'reminder:send-tagihan-h3';
    protected $description = 'Kirim pengingat H-3 sesuai template: tagihan_dp50_peserta, tagihan_dp50_admin, tagihan_lunas_peserta.';

    public function run(array $params)
    {
        $forDate = (string)(CLI::getOption('for') ?? date('Y-m-d', strtotime('+3 days'))); // H-3
        $verbose = CLI::getOption('verbose') !== null;
        $delay = (int) (env('WAHA_SEND_DELAY_SECONDS') ?? 60);
        if ($delay < 1) { $delay = 60; }

        $waha = new WahaService();
        if (!$waha->isConfigured()) {
            CLI::error('WAHA belum dikonfigurasi (set WAHA_BASE_URL dan WAHA_API_TOKEN).');
            return;
        }

        $db = Database::connect();
        $tplModel = new WahaTemplate();
        $logModel = new WahaLog();
        $cfgModel = new WahaConfig();

        $tplDpPeserta = $this->getTemplate($tplModel, 'tagihan_dp50_peserta', 'Halo {{nama}}, pengingat H-3: Anda masih DP 50% untuk kelas {{nama_kelas}} mulai {{tanggal_mulai}}. Sisa bayar: Rp {{sisa_bayar}}.');
        $tplDpAdmin = $this->getTemplate($tplModel, 'tagihan_dp50_admin', '[ADMIN] Peserta {{nama}} ({{phone}}) DP 50%. Kelas {{nama_kelas}} mulai {{tanggal_mulai}}. Sisa: Rp {{sisa_bayar}}.');
        $tplLunasPeserta = $this->getTemplate($tplModel, 'tagihan_lunas_peserta', 'Halo {{nama}}, pengingat H-3: kelas {{nama_kelas}} mulai {{tanggal_mulai}}. Pembayaran Anda sudah lunas. Sampai jumpa!');

        $adminPhoneEnv = (string)(env('WAHA_ADMIN_PHONE') ?? '');
        $adminPhoneCfg = (string)($cfgModel->getValue('admin_phone', '') ?? '');
        $adminPhone = $adminPhoneEnv !== '' ? $adminPhoneEnv : $adminPhoneCfg;

        $countSent = 0; $countFail = 0; $countSkip = 0;

        // Registrasi yang jadwalnya start di forDate
        $rows = $db->table('registrasi r')
            ->select('r.id, r.nama, r.no_telp, r.status_pembayaran, r.biaya_tagihan, r.biaya_dibayar, r.kode_kelas, k.nama_kelas, j.tanggal_mulai')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->join('jadwal_kelas j', 'j.id = r.jadwal_id', 'left')
            ->where('r.deleted_at', null)
            ->where('j.tanggal_mulai', $forDate)
            ->get()->getResultArray();

        if ($verbose) {
            CLI::write('Target H-3 tanggal_mulai=' . $forDate . '; kandidat=' . count($rows));
        }

        foreach ($rows as $r) {
            $rid = (int)($r['id'] ?? 0);
            $nama = (string)($r['nama'] ?? '');
            $kelas = (string)($r['nama_kelas'] ?? '');
            $tglMulai = (string)($r['tanggal_mulai'] ?? $forDate);
            $phone = (string)($r['no_telp'] ?? '');
            $statusBayar = strtolower((string)($r['status_pembayaran'] ?? ''));
            $tagihan = (float)($r['biaya_tagihan'] ?? 0);
            $dibayar = (float)($r['biaya_dibayar'] ?? 0);
            $sisa = max(0.0, $tagihan - $dibayar);

            $data = [
                'nama' => $nama,
                'nama_kelas' => $kelas,
                'tanggal_mulai' => $tglMulai,
                'sisa_bayar' => $this->formatRupiah($sisa),
                'total_tagihan' => $this->formatRupiah($tagihan),
                'dibayar' => $this->formatRupiah($dibayar),
                'phone' => $phone,
            ];

            if ($statusBayar === 'dp 50%') {
                // Peserta DP 50% -> kirim ke peserta
                $msgPeserta = $waha->renderTemplate($tplDpPeserta, $data);
                $res = $waha->sendMessage($phone, $msgPeserta);
                $ok = (bool)($res['success'] ?? false);
                $this->log($logModel, $rid, 'tagihan_dp50_peserta', $nama, $phone, $msgPeserta, $ok, $ok ? null : ($res['message'] ?? ''));
                if ($ok) { $countSent++; } else { $countFail++; }
                if ($verbose) { CLI::write(($ok ? '[SENT ] ' : '[FAIL ] ') . 'R#' . $rid . ' tagihan_dp50_peserta to ' . $phone); }
                sleep($delay);

                // Admin notification
                if ($adminPhone !== '') {
                    $msgAdmin = $waha->renderTemplate($tplDpAdmin, $data);
                    $resA = $waha->sendMessage($adminPhone, $msgAdmin);
                    $okA = (bool)($resA['success'] ?? false);
                    $this->log($logModel, $rid, 'tagihan_dp50_admin', 'ADMIN', $adminPhone, $msgAdmin, $okA, $okA ? null : ($resA['message'] ?? ''));
                    // Tidak dihitung ke peserta; tetap log
                    if ($verbose) { CLI::write(($okA ? '[SENT ] ' : '[FAIL ] ') . 'ADMIN tagihan_dp50_admin to ' . $adminPhone); }
                    sleep($delay);
                } else {
                    $countSkip++;
                    if ($verbose) { CLI::write('[SKIP ] admin_phone kosong, tidak kirim tagihan_dp50_admin'); }
                }
            } elseif ($statusBayar === 'lunas') {
                // Peserta lunas -> kirim info H-3
                $msgLunas = $waha->renderTemplate($tplLunasPeserta, $data);
                $res = $waha->sendMessage($phone, $msgLunas);
                $ok = (bool)($res['success'] ?? false);
                $this->log($logModel, $rid, 'tagihan_lunas_peserta', $nama, $phone, $msgLunas, $ok, $ok ? null : ($res['message'] ?? ''));
                if ($ok) { $countSent++; } else { $countFail++; }
                if ($verbose) { CLI::write(($ok ? '[SENT ] ' : '[FAIL ] ') . 'R#' . $rid . ' tagihan_lunas_peserta to ' . $phone); }
                sleep($delay);
            } else {
                $countSkip++;
                if ($verbose) { CLI::write('[SKIP ] R#' . $rid . ' status_pembayaran=' . $statusBayar); }
            }
        }

        CLI::write('Selesai H-3 tagihan: sent=' . $countSent . ' fail=' . $countFail . ' skip=' . $countSkip, 'green');
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
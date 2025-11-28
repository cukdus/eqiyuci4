<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use App\Libraries\WahaService;
use App\Models\WahaTemplate;
use App\Models\WahaLog;

class ReminderSendLulus extends BaseCommand
{
    protected $group = 'reminder';
    protected $name = 'reminder:send-lulus';
    protected $description = 'Kirim pesan lulus_peserta pada akhir jadwal kelas (jam 21:00).';

    public function run(array $params)
    {
        $forDate = (string)(CLI::getOption('for') ?? date('Y-m-d'));
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

        $tplLulus = $this->getTemplate($tplModel, 'lulus_peserta', 'Selamat {{nama}}! Anda telah menyelesaikan kelas {{nama_kelas}} hari ini. Terima kasih telah belajar bersama kami.');

        $countSent = 0; $countFail = 0; $countSkip = 0;

        // Ambil registrasi yang end-date kelasnya == forDate
        // Asumsi: end-date = tanggal_mulai + durasi - 1 (durasi dalam hari)
        $rows = $db->table('registrasi r')
            ->select('r.id, r.nama, r.no_telp, r.kode_kelas, k.nama_kelas, k.durasi, j.tanggal_mulai')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->join('jadwal_kelas j', 'j.id = r.jadwal_id', 'left')
            ->where('r.deleted_at', null)
            ->get()->getResultArray();

        foreach ($rows as $r) {
            $rid = (int)($r['id'] ?? 0);
            $nama = (string)($r['nama'] ?? '');
            $kelas = (string)($r['nama_kelas'] ?? '');
            $phone = (string)($r['no_telp'] ?? '');
            $durasi = (int)($r['durasi'] ?? 0);
            $tglMulai = (string)($r['tanggal_mulai'] ?? '');
            if ($tglMulai === '' || $durasi <= 0) { $countSkip++; if ($verbose) CLI::write('[SKIP ] R#'.$rid.' durasi/tanggal_mulai tidak valid'); continue; }

            $endDate = date('Y-m-d', strtotime($tglMulai . ' +' . ($durasi - 1) . ' days'));
            if ($endDate !== $forDate) { $countSkip++; continue; }
            if ($this->alreadySent($logModel, $rid, $forDate)) { $countSkip++; if ($verbose) CLI::write('[SKIP ] R#'.$rid.' already sent'); continue; }

            $certRow = $db->table('sertifikat')->select('no_sertifikat')->where('registrasi_id', $rid)->orderBy('created_at', 'DESC')->get()->getRowArray();
            $noSertifikat = (string)($certRow['no_sertifikat'] ?? '');

            $msg = $waha->renderTemplate($tplLulus, [
                'nama' => $nama,
                'nama_kelas' => $kelas,
                'tanggal_mulai' => $tglMulai,
                'tanggal_selesai' => $endDate,
                'phone' => $phone,
                'no_sertifikat' => $noSertifikat,
            ]);

            $res = $waha->sendMessage($phone, $msg);
            $ok = (bool)($res['success'] ?? false);
            $this->log($logModel, $rid, 'lulus_peserta', $nama, $phone, $msg, $ok, $ok ? null : ($res['message'] ?? ''));
            if ($ok) { $countSent++; } else { $countFail++; }
            if ($verbose) { CLI::write(($ok ? '[SENT ] ' : '[FAIL ] ') . 'R#' . $rid . ' lulus_peserta to ' . $phone); }
            sleep($delay);
        }

        CLI::write('Selesai lulus_peserta: sent=' . $countSent . ' fail=' . $countFail . ' skip=' . $countSkip, 'green');
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

    private function alreadySent(WahaLog $logModel, int $rid, string $forDate): bool
    {
        $start = $forDate . ' 00:00:00';
        $end = $forDate . ' 23:59:59';
        return $logModel
            ->where('registrasi_id', $rid)
            ->where('scenario', 'lulus_peserta')
            ->where('status', 'sent')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->first() !== null;
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
}

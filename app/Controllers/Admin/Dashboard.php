<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('admin/dashboard', [
            'title' => 'Dashboard Admin',
            'user' => service('authentication')->user()
        ]);
    }

    public function downloadReport()
    {
        $month = (int) ($this->request->getGet('month') ?? 0);
        $year = (int) ($this->request->getGet('year') ?? 0);

        if ($month < 1 || $month > 12 || $year < 2000) {
            return $this->response->setStatusCode(400)->setBody('Parameter bulan/tahun tidak valid');
        }

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $bulanName = $months[$month];
        $yy = substr((string) $year, -2);
        $filename = 'Laporan ' . $bulanName . ' ' . $yy . '.xlsx';

        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $db = Database::connect();
        $rows = $db->table('registrasi r')
            ->select('r.tanggal_daftar, r.nama, r.email, r.no_telp, r.lokasi, r.status_pembayaran, r.biaya_dibayar, r.biaya_tagihan, k.nama_kelas')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->where('r.tanggal_daftar >=', $startDate)
            ->where('r.tanggal_daftar <=', $endDate)
            ->orderBy('r.tanggal_daftar', 'ASC')
            ->get()
            ->getResultArray();

        $sheetTitle = 'Laporan ' . $bulanName . ' ' . $year;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($sheetTitle);

        $headers = ['Tanggal Daftar', 'Nama', 'Email', 'No Telp', 'Lokasi', 'Kelas', 'Status Pembayaran', 'Jumlah Dibayar', 'Jumlah Tagihan', 'Total'];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        $rowIndex = 2;
        $sumDibayar = 0.0;
        $sumTagihan = 0.0;
        foreach ($rows as $r) {
            $dibayar = (float) ($r['biaya_dibayar'] ?? 0);
            $tagihan = (float) ($r['biaya_tagihan'] ?? 0);
            $total = $dibayar + $tagihan;
            $sumDibayar += $dibayar;
            $sumTagihan += $tagihan;

            $data = [
                (string) ($r['tanggal_daftar'] ?? ''),
                (string) ($r['nama'] ?? ''),
                (string) ($r['email'] ?? ''),
                (string) ($r['no_telp'] ?? ''),
                (string) ($r['lokasi'] ?? ''),
                (string) ($r['nama_kelas'] ?? ''),
                (string) ($r['status_pembayaran'] ?? ''),
                $dibayar,
                $tagihan,
                $total,
            ];
            $sheet->fromArray($data, null, 'A' . $rowIndex);
            $rowIndex++;
        }

        $totalLabelRow = $rowIndex + 1;
        $sheet->setCellValue('G' . $totalLabelRow, 'TOTAL');
        $sheet->setCellValue('H' . $totalLabelRow, $sumDibayar);
        $sheet->setCellValue('I' . $totalLabelRow, $sumTagihan);
        $sheet->setCellValue('J' . $totalLabelRow, $sumDibayar + $sumTagihan);
        $sheet->getStyle('G' . $totalLabelRow . ':J' . $totalLabelRow)->getFont()->setBold(true);

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getStyle('H2:J' . $totalLabelRow)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $binary = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($binary);
    }
}

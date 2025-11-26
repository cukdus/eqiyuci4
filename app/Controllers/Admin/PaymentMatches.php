<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;

class PaymentMatches extends BaseController
{
    public function index()
    {
        $me = service('authentication')->user();
        if (!$me) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }
        $authz = service('authorization');
        if (!$authz->inGroup('admin', $me->id)) {
            return redirect()->to(site_url('admin/setting'))
                ->with('error', 'Akses ditolak: hanya admin yang dapat melihat audit pembayaran.');
        }

        $req = $this->request;
        $type = trim((string)$req->getGet('type') ?? $req->getPost('type') ?? '');
        $start = trim((string)$req->getGet('start') ?? $req->getPost('start') ?? '');
        $end = trim((string)$req->getGet('end') ?? $req->getPost('end') ?? '');
        $q = trim((string)$req->getGet('q') ?? $req->getPost('q') ?? '');

        $db = Database::connect();
        $qb = $db->table('payment_matches pm')
            ->select('pm.*, r.nama as registrasi_nama, r.email as registrasi_email, k.nama_kelas, bt.amount_formatted as bank_amount, bt.period as bank_period')
            ->join('registrasi r', 'r.id = pm.registrasi_id', 'left')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->join('bank_transactions bt', 'bt.id = pm.bank_transaction_id', 'left')
            ->orderBy('pm.matched_at', 'DESC');

        if ($type !== '') {
            $qb->where('pm.type', $type);
        }
        if ($start !== '') {
            $qb->where('pm.period >=', $start);
        }
        if ($end !== '') {
            $qb->where('pm.period <=', $end);
        }
        if ($q !== '') {
            $qb->groupStart()
                ->like('r.nama', $q)
                ->orLike('r.email', $q)
                ->orLike('k.nama_kelas', $q)
                ->groupEnd();
        }

        $rows = $qb->get()->getResultArray();

        return view('layout/admin_layout', [
            'title' => 'Audit Payment Matches',
            'content' => view('admin/payment_matches/index', [
                'title' => 'Audit Payment Matches',
                'rows' => $rows,
                'filters' => [
                    'type' => $type,
                    'start' => $start,
                    'end' => $end,
                    'q' => $q,
                ],
                'types' => ['dp', 'pelunasan', 'full', 'dibayar'],
            ]),
        ]);
    }
}
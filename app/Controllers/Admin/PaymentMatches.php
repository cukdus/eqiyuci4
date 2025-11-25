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
            return redirect()
                ->to(site_url('admin/setting'))
                ->with('error', 'Akses ditolak: hanya admin yang dapat melihat audit pembayaran.');
        }

        $req = $this->request;
        $type = trim((string) $req->getGet('type') ?? $req->getPost('type') ?? '');
        $start = trim((string) $req->getGet('start') ?? $req->getPost('start') ?? '');
        $end = trim((string) $req->getGet('end') ?? $req->getPost('end') ?? '');
        $q = trim((string) $req->getGet('q') ?? $req->getPost('q') ?? '');

        $db = Database::connect();
        $qb = $db
            ->table('payment_matches pm')
            ->select('pm.*, r.nama as registrasi_nama, r.email as registrasi_email, k.nama_kelas, jk.tanggal_mulai as jadwal_mulai, jk.tanggal_selesai as jadwal_selesai, jk.lokasi as jadwal_lokasi, bt.amount_formatted as bank_amount, bt.period as bank_period')
            ->join('registrasi r', 'r.id = pm.registrasi_id', 'left')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->join('jadwal_kelas jk', 'jk.id = r.jadwal_id', 'left')
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
            $qb
                ->groupStart()
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

    public function listJson()
    {
        $me = service('authentication')->user();
        if (!$me) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }
        $authz = service('authorization');
        if (!$authz->inGroup('admin', $me->id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Forbidden'])->setStatusCode(403);
        }

        $req = $this->request;
        $type = trim((string) $req->getGet('type') ?? '');
        $start = trim((string) $req->getGet('start') ?? '');
        $end = trim((string) $req->getGet('end') ?? '');
        $q = trim((string) $req->getGet('q') ?? '');
        $page = (int) ($req->getGet('page') ?? 1);
        $perPage = (int) ($req->getGet('perPage') ?? 10);
        if ($page < 1)
            $page = 1;
        $allowedPer = [10, 20, 50, 100];
        if (!in_array($perPage, $allowedPer, true))
            $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $db = Database::connect();
        $qb = $db
            ->table('payment_matches pm')
            ->select('pm.*, r.nama as registrasi_nama, r.email as registrasi_email, k.nama_kelas, jk.tanggal_mulai as jadwal_mulai, jk.tanggal_selesai as jadwal_selesai, jk.lokasi as jadwal_lokasi, bt.amount_formatted as bank_amount, bt.period as bank_period')
            ->join('registrasi r', 'r.id = pm.registrasi_id', 'left')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->join('jadwal_kelas jk', 'jk.id = r.jadwal_id', 'left')
            ->join('bank_transactions bt', 'bt.id = pm.bank_transaction_id', 'left');

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
            $qb
                ->groupStart()
                ->like('r.nama', $q)
                ->orLike('r.email', $q)
                ->orLike('k.nama_kelas', $q)
                ->groupEnd();
        }

        // Count total
        $countQB = clone $qb;
        $total = (int) $countQB->countAllResults();

        // Fetch page
        $rows = $qb->orderBy('pm.matched_at', 'DESC')->limit($perPage, $offset)->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $rows,
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => max(1, (int) ceil($total / $perPage)),
                'filters' => [
                    'type' => $type,
                    'start' => $start,
                    'end' => $end,
                    'q' => $q,
                ],
            ],
        ]);
    }
}

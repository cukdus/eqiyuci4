<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\BankBcaScraper;
use App\Libraries\WahaService;
use App\Models\BankTransaction;
use App\Models\UserModel;
use App\Models\WahaLog;
use App\Models\WahaQueue;
use App\Models\WahaTemplate;
use Myth\Auth\Authorization\Authorization;  // if used elsewhere
use Myth\Auth\Models\GroupModel;

class Setting extends BaseController
{
    public function update()
    {
        $user = service('authentication')->user();
        if (!$user) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $users = model(UserModel::class);

        // Validasi dasar untuk username & email
        $rules = [
            'username' => 'required|alpha_numeric_punct|min_length[3]|max_length[30]',
            'email' => 'required|valid_email',
        ];

        // Jika ada permintaan ganti password, tambahkan validasi
        $new = trim((string) $this->request->getPost('new_password'));
        $confirm = trim((string) $this->request->getPost('new_password_confirm'));

        $changePassword = ($new !== '' || $confirm !== '');
        if ($changePassword) {
            // Hindari strong_password Myth\Auth yang bermasalah dengan versi CI.
            // Gunakan validasi bawaan: minimal 8 dan kombinasi huruf besar, kecil, angka.
            $rules['new_password'] = 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/]';
            $rules['new_password_confirm'] = 'required|matches[new_password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan nilai original untuk deteksi perubahan
        $originalUsername = $user->username;
        $originalEmail = $user->email;
        $originalFullName = $user->nama_lengkap ?? null;

        // Update data profil
        $data = [
            'id' => $user->id,
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
        ];

        // Nama lengkap (opsional)
        $fullNamePost = trim((string) $this->request->getPost('full_name'));
        if ($fullNamePost !== '') {
            // Gunakan kolom 'nama_lengkap' sesuai skema database
            $user->nama_lengkap = $fullNamePost;
        }

        // Upload foto jika ada
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                $newName = $file->getRandomName();
                $targetDir = FCPATH . 'uploads/avatars';  // public/uploads/avatars
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $file->move($targetDir, $newName);
                $data['photo'] = 'uploads/avatars/' . $newName;
            }
        }

        // Proses ganti password bila diminta (tanpa verifikasi password saat ini)
        if ($changePassword) {
            // Set password baru via entity agar di-hash
            $user->password = $new;
        }

        // Simpan perubahan
        // Gunakan entity agar update aman; namun kita merge data $data ke entity
        foreach ($data as $key => $val) {
            $user->$key = $val;
        }

        $changedUsername = ($originalUsername !== $user->username);
        $changedEmail = ($originalEmail !== $user->email);
        $changedFullName = ($originalFullName !== ($user->nama_lengkap ?? null));

        // Jika tidak ada perubahan sama sekali, hindari error "There is no data to update"
        if (!$changedUsername && !$changedEmail && !$changedFullName && !$changePassword && !isset($data['photo'])) {
            return redirect()
                ->to(site_url('admin/setting'))
                ->with('message', 'Tidak ada perubahan untuk disimpan.');
        }

        $um = model(UserModel::class);
        $um->skipValidation(true);  // Hindari aturan model yang mewajibkan password_hash saat update
        if (!$um->save($user)) {
            return redirect()->back()->withInput()->with('errors', $um->errors() ?? ['Gagal menyimpan perubahan']);
        }

        return redirect()
            ->to(site_url('admin/setting'))
            ->with('message', $changePassword ? 'Profil & password berhasil diperbarui' : 'Profil berhasil diperbarui');
    }

    // ====== Users Management (Settings page) ======
    public function index()
    {
        $users = model(UserModel::class)->findAll();
        return view('layout/admin_layout', [
            'title' => 'Setting',
            'content' => view('admin/setting/settings', ['users' => $users]),
        ]);
    }

    public function waha()
    {
        // Batasi hanya admin yang boleh mengakses
        $me = service('authentication')->user();
        if (!$me) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }
        $authz = service('authorization');
        if (!$authz->inGroup('admin', $me->id)) {
            return redirect()
                ->to(site_url('admin/setting'))
                ->with('error', 'KNTL! mau ngapain, inget dosa!');
        }

        return view('layout/admin_layout', [
            'title' => 'WA-API',
            'content' => view('admin/setting/waha'),
        ]);
    }

    public function transaksi()
    {
        $me = service('authentication')->user();
        if (!$me) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }
        $authz = service('authorization');
        if (!$authz->inGroup('admin', $me->id)) {
            return redirect()
                ->to(site_url('admin/setting'))
                ->with('error', 'Akses ditolak: hanya admin yang dapat melihat transaksi.');
        }
        $req = $this->request;
        $month = (int) ($req->getGet('month') ?? $req->getPost('month') ?? 0);
        $year = (int) ($req->getGet('year') ?? $req->getPost('year') ?? 0);

        $tm = model(BankTransaction::class);
        if ($month >= 1 && $month <= 12 && $year >= 2000) {
            $startDate = sprintf('%04d-%02d-01', $year, $month);
            $endDate = date('Y-m-t', strtotime($startDate));
            $rows = $tm
                ->where('created_at >=', $startDate . ' 00:00:00')
                ->where('created_at <=', $endDate . ' 23:59:59')
                ->orderBy('created_at', 'DESC')
                ->findAll();
        } else {
            // Tampilkan semua data transaksi bila filter tidak diberikan
            $rows = $tm->orderBy('created_at', 'DESC')->findAll();
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

        return view('layout/admin_layout', [
            'title' => 'Transaksi',
            'content' => view('admin/setting/transaksi', [
                'rows' => $rows,
                'filters' => ['month' => $month, 'year' => $year],
                'months' => $months,
                'years' => range((int) date('Y') - 5, (int) date('Y') + 1),
            ]),
        ]);
    }

    public function imporBca()
    {
        $me = service('authentication')->user();
        if (!$me) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }
        // Batasi hanya admin
        $authz = service('authorization');
        if (!$authz->inGroup('admin', $me->id)) {
            return redirect()->to(site_url('admin/setting/transaksi'))->with('error', 'Akses ditolak: hanya admin yang dapat mengimpor mutasi.');
        }

        $spark = rtrim(ROOTPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'spark';
        $cmd = 'php ' . escapeshellarg($spark) . ' bank:scrape';
        $out = function_exists('shell_exec') ? @shell_exec($cmd . ' 2>&1') : '';
        if (is_string($out) && $out !== '') {
            $ins = 0;
            $sk = 0;
            $ok = false;
            if (preg_match('/Inserted:\s*(\d+),\s*Skipped:\s*(\d+)/', $out, $m)) {
                $ins = (int) ($m[1] ?? 0);
                $sk = (int) ($m[2] ?? 0);
                $ok = true;
            }
            $result = ['success' => $ok ?: true, 'inserted' => $ins, 'skipped' => $sk, 'message' => trim($out)];
        } else {
            $importer = new \App\Libraries\BcaImporter();
            $result = $importer->importFromJson(true);
        }

        $flashType = $result['success'] ? 'message' : 'error';
        $msg = $result['success']
            ? ("Impor Mutasi BCA selesai. Inserted: {$result['inserted']}, Skipped: {$result['skipped']}")
            : ('Gagal impor: ' . $result['message']);

        return redirect()->to(site_url('admin/setting/transaksi'))->with($flashType, $msg);
    }

    public function create()
    {
        $me = service('authentication')->user();
        if (!$me) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }
        $authz = service('authorization');
        // Hanya admin yang bisa menambah user
        if (!$authz->inGroup('admin', $me->id)) {
            return redirect()->to(site_url('admin/setting'))->with('error', 'Akses ditolak: hanya admin yang dapat menambah user.');
        }

        $groupModel = model(GroupModel::class);
        $groups = $groupModel->whereIn('name', ['admin', 'staff'])->findAll();

        return view('layout/admin_layout', [
            'title' => 'Tambah User',
            'content' => view('admin/setting/user_form', ['mode' => 'create', 'user' => null, 'groups' => $groups]),
        ]);
    }

    public function store()
    {
        $me = service('authentication')->user();
        if (!$me) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }
        $authz = service('authorization');
        // Hanya admin yang bisa menambah user
        if (!$authz->inGroup('admin', $me->id)) {
            return redirect()
                ->to(site_url('admin/setting'))
                ->with('error', 'Akses ditolak: hanya admin yang dapat menambah user.');
        }

        $rules = [
            'username' => 'required|alpha_numeric_punct|min_length[3]|max_length[30]',
            'email' => 'required|valid_email',
            'password' => 'required|strong_password',
            'role' => 'required|in_list[admin,staff]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $roleName = (string) $this->request->getPost('role');
        $groupModel = model(GroupModel::class);
        $group = $groupModel->where('name', $roleName)->first();
        if (!$group) {
            return redirect()->back()->withInput()->with('errors', ['Role tidak valid']);
        }

        $user = new \App\Entities\User();
        // Simpan nilai original untuk deteksi perubahan
        $originalUsername = $user->username;
        $originalEmail = $user->email;

        $user->username = $this->request->getPost('username');
        $user->email = $this->request->getPost('email');
        $user->setPassword((string) $this->request->getPost('password'));
        $user->active = 1;

        $um = model(UserModel::class)->withGroup($roleName);
        if (!$um->save($user)) {
            return redirect()->back()->withInput()->with('errors', $um->errors() ?? ['Gagal menambah user']);
        }

        return redirect()->to(site_url('admin/setting'))->with('message', 'User baru berhasil ditambahkan');
    }

    public function show($id)
    {
        // Halaman profil user dinonaktifkan; arahkan kembali ke daftar settings
        return redirect()
            ->to(site_url('admin/setting'))
            ->with('message', 'Halaman profil pengguna tidak tersedia.');
    }

    public function edit($id)
    {
        $me = service('authentication')->user();
        if (!$me) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }
        $authz = service('authorization');

        $um = model(UserModel::class);
        $user = $um->find($id);
        if (!$user) {
            return redirect()->to(site_url('admin/setting'))->with('error', 'User tidak ditemukan');
        }
        // Hanya pemilik akun atau admin yang boleh edit
        $isAdmin = $authz->inGroup('admin', $me->id);
        $meId = (int) ($me->id ?? 0);
        $target = (int) $id;
        if ($meId !== $target && !$isAdmin) {
            return redirect()->to(site_url('admin/setting'))->with('error', 'Akses ditolak: Anda tidak berhak mengedit user ini.');
        }

        $groupModel = model(GroupModel::class);
        $groups = $groupModel->whereIn('name', ['admin', 'staff'])->findAll();

        return view('layout/admin_layout', [
            'title' => 'Edit User',
            'content' => view('admin/setting/editprofile', [
                'user' => $user,
                'groups' => $groups,
                'canManageRoles' => $isAdmin,
            ]),
        ]);
    }

    public function updateUser($id)
    {
        $me = service('authentication')->user();
        if (!$me) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }
        $authz = service('authorization');
        $isAdmin = $authz->inGroup('admin', $me->id);
        $meId = (int) ($me->id ?? 0);
        $target = (int) $id;
        if ($meId !== $target && !$isAdmin) {
            return redirect()->to(site_url('admin/setting'))->with('error', 'Akses ditolak: Anda tidak berhak mengedit user ini.');
        }

        $um = model(UserModel::class);
        $user = $um->find($id);
        if (!$user) {
            return redirect()->to(site_url('admin/setting'))->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'username' => 'required|alpha_numeric_punct|min_length[3]|max_length[30]',
            'email' => 'required|valid_email',
        ];
        $password = trim((string) $this->request->getPost('password'));
        if ($password !== '') {
            // Hindari strong_password Myth\Auth. Terapkan aturan minimal dan kompleksitas.
            $rules['password'] = 'min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/]';
            $rules['pass_confirm'] = 'required|matches[password]';
        }
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan nilai original untuk deteksi perubahan
        $originalUsername = $user->username;
        $originalEmail = $user->email;
        $originalPhoto = $user->photo ?? null;
        $originalFullName = $user->nama_lengkap ?? null;

        $user->username = $this->request->getPost('username');
        $user->email = $this->request->getPost('email');

        // Nama lengkap (opsional)
        $fullNamePost = trim((string) $this->request->getPost('full_name'));
        if ($fullNamePost !== '') {
            $user->nama_lengkap = $fullNamePost;
        }

        // Upload foto jika ada (samakan dengan update())
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                $newName = $file->getRandomName();
                $targetDir = FCPATH . 'uploads/avatars';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $file->move($targetDir, $newName);
                $user->photo = 'uploads/avatars/' . $newName;
            }
        }

        if ($password !== '') {
            $user->setPassword($password);
        }

        $changedUsername = ($originalUsername !== $user->username);
        $changedEmail = ($originalEmail !== $user->email);
        $hasPhotoChange = (($user->photo ?? null) !== $originalPhoto);
        $changedFullName = ($originalFullName !== ($user->nama_lengkap ?? null));

        // Jika tidak ada perubahan profil sama sekali, jangan panggil save() untuk menghindari error empty dataset
        if ($changedUsername || $changedEmail || $changedFullName || $hasPhotoChange || $password !== '') {
            $um->skipValidation(true);
            if (!$um->save($user)) {
                return redirect()->back()->withInput()->with('errors', $um->errors() ?? ['Gagal memperbarui user']);
            }
        }

        // Update role jika admin
        if ($isAdmin) {
            $roleName = trim((string) $this->request->getPost('role'));
            if ($roleName !== '') {
                if (!in_array($roleName, ['admin', 'staff'], true)) {
                    return redirect()->back()->withInput()->with('errors', ['Role tidak valid. Hanya admin atau staff.']);
                }
                $groupModel = model(GroupModel::class);
                $group = $groupModel->where('name', $roleName)->first();
                if ($group) {
                    // Hapus semua grup lama, lalu set grup baru
                    $groupModel->removeUserFromAllGroups((int) $id);
                    $groupModel->addUserToGroup((int) $id, (int) $group->id);
                }
            }
        }

        return redirect()->to(site_url('admin/setting'))->with('message', 'User berhasil diperbarui');
    }

    public function delete($id)
    {
        $me = service('authentication')->user();
        if (!$me) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }
        $authz = service('authorization');
        // Hanya admin yang boleh menghapus user
        if (!$authz->inGroup('admin', $me->id)) {
            return redirect()->to(site_url('admin/setting'))->with('error', 'Akses ditolak: hanya admin yang dapat menghapus user.');
        }

        $um = model(UserModel::class);
        // Soft delete: Model UserModel sudah useSoftDeletes=true
        if (!$um->delete($id)) {
            return redirect()->back()->with('error', $um->errors() ? implode(', ', (array) $um->errors()) : 'Gagal menghapus user');
        }
        return redirect()->to(site_url('admin/setting'))->with('message', 'User berhasil dihapus');
    }

    // ====== WAHA Management ======
    public function wahaTemplatesJson()
    {
        $tm = model(WahaTemplate::class);
        $templates = $tm->orderBy('name', 'ASC')->findAll();
        return $this->response->setJSON(['success' => true, 'data' => $templates]);
    }

    public function wahaLogsJson()
    {
        $request = $this->request;
        $perPage = (int) ($request->getGet('per_page') ?? 5);
        if ($perPage <= 0) {
            $perPage = 5;
        }
        if ($perPage > 50) {
            $perPage = 50;
        }
        $page = max(1, (int) ($request->getGet('page') ?? 1));
        $start = ($page - 1) * $perPage;

        $db = \Config\Database::connect();
        // Sesuai dengan Model WahaLog: protected $table = 'waha_logs'
        $builder = $db->table('waha_logs');
        $countBuilder = clone $builder;
        $totalData = (int) $countBuilder->countAllResults(false);
        $totalPages = (int) ceil(($totalData > 0 ? $totalData : 0) / $perPage);

        $rows = $builder
            ->orderBy('created_at', 'DESC')
            ->limit($perPage, $start)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $rows,
            'meta' => [
                'total' => $totalData,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => $totalPages,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1,
            ],
        ]);
    }

    public function wahaLogsClear()
    {
        try {
            $lm = model(WahaLog::class);
            // Truncate all logs
            $lm->builder()->truncate();
            return $this->response->setJSON(['success' => true]);
        } catch (\Throwable $e) {
            log_message('error', 'WAHA clear logs error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()])->setStatusCode(500);
        }
    }

    public function wahaTemplateStore()
    {
        $rules = [
            'key' => 'required|max_length[100]',
            'name' => 'required|max_length[150]',
            'template' => 'required',
            'enabled' => 'permit_empty|in_list[0,1]',
        ];
        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()])->setStatusCode(422);
        }

        // Validasi server-side: key harus termasuk daftar bawaan dan belum dipakai
        $allowedKeys = [
            'registrasi_peserta',
            'registrasi_admin',
            'tagihan_dp50_peserta',
            'tagihan_dp50_admin',
            'tagihan_lunas_peserta',
            'lulus_peserta',
            'akses_kelas',
        ];

        $key = (string) $this->request->getPost('key');
        if (!in_array($key, $allowedKeys, true)) {
            return $this->response->setJSON(['success' => false, 'errors' => ['key' => 'Key tidak diizinkan']])->setStatusCode(422);
        }

        $tm = model(WahaTemplate::class);
        $existingKey = $tm->where('key', $key)->first();
        if ($existingKey) {
            return $this->response->setJSON(['success' => false, 'errors' => ['key' => 'Key sudah digunakan']])->setStatusCode(422);
        }

        $data = [
            'key' => $key,
            'name' => $this->request->getPost('name'),
            'template' => $this->request->getPost('template'),
            'enabled' => (int) ($this->request->getPost('enabled') ?? 1),
            'description' => $this->request->getPost('description'),
        ];
        if (!$tm->insert($data)) {
            return $this->response->setJSON(['success' => false, 'errors' => $tm->errors()])->setStatusCode(400);
        }
        return $this->response->setJSON(['success' => true]);
    }

    public function wahaTemplateUpdate($id)
    {
        $tm = model(WahaTemplate::class);
        $existing = $tm->find($id);
        if (!$existing) {
            return $this->response->setJSON(['success' => false, 'message' => 'Template tidak ditemukan'])->setStatusCode(404);
        }
        $data = [
            'name' => $this->request->getPost('name'),
            'template' => $this->request->getPost('template'),
            'enabled' => (int) ($this->request->getPost('enabled') ?? 1),
            'description' => $this->request->getPost('description'),
        ];
        if (!$tm->update($id, $data)) {
            return $this->response->setJSON(['success' => false, 'errors' => $tm->errors()])->setStatusCode(400);
        }
        return $this->response->setJSON(['success' => true]);
    }

    public function wahaTemplateDelete($id)
    {
        $tm = model(WahaTemplate::class);
        if (!$tm->delete($id)) {
            return $this->response->setJSON(['success' => false, 'errors' => $tm->errors()])->setStatusCode(400);
        }
        return $this->response->setJSON(['success' => true]);
    }

    public function wahaPreview()
    {
        // Support preview using actual latest registrasi data, with optional specific registrasi_id
        $key = (string) ($this->request->getPost('key') ?? '');
        $template = (string) ($this->request->getPost('template') ?? '');
        $registrasiId = $this->request->getPost('registrasi_id');
        $db = \Config\Database::connect();
        // Ambil registrasi terakhir (non-deleted) jika tidak spesifik
        $r = null;
        if ($registrasiId) {
            $r = $db
                ->table('registrasi r')
                ->select('r.*, k.nama_kelas, jk.tanggal_mulai, jk.tanggal_selesai, jk.id as jadwal_id')
                ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
                ->join('jadwal_kelas jk', 'jk.id = r.jadwal_id', 'left')
                ->where('r.id', (int) $registrasiId)
                ->where('r.deleted_at IS NULL')
                ->get()
                ->getRowArray();
        } else {
            $r = $db
                ->table('registrasi r')
                ->select('r.*, k.nama_kelas, jk.tanggal_mulai, jk.tanggal_selesai, jk.id as jadwal_id')
                ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
                ->join('jadwal_kelas jk', 'jk.id = r.jadwal_id', 'left')
                ->where('r.deleted_at IS NULL')
                ->orderBy('r.tanggal_daftar', 'DESC')
                ->get()
                ->getRowArray();
        }
        if (!$r) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada data registrasi aktif untuk preview'])->setStatusCode(404);
        }
        $data = $this->buildTemplateDataFromRegistrasi($r, $db);
        // Jika key diberikan, muat template dari DB, jika tidak gunakan konten langsung
        if ($key !== '') {
            $tpl = model(WahaTemplate::class)->where('key', $key)->first();
            if (!$tpl || empty($tpl['template'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Template dengan key tersebut tidak ditemukan'])->setStatusCode(404);
            }
            $template = (string) $tpl['template'];
        }
        $ws = new WahaService();
        $rendered = $ws->renderTemplate($template, $data);
        return $this->response->setJSON([
            'success' => true,
            'rendered' => $rendered,
            'meta' => [
                'registrasi_id' => $r['id'] ?? null,
                'template_key' => $key,
            ],
        ]);
    }

    public function wahaTestSend()
    {
        // Support sending test using actual registrasi data and template key
        $registrasiId = $this->request->getPost('registrasi_id');
        $key = (string) ($this->request->getPost('key') ?? '');
        $phone = (string) ($this->request->getPost('phone') ?? '');
        $template = (string) ($this->request->getPost('template') ?? '');
        $db = \Config\Database::connect();
        $ws = new WahaService();

        $message = '';
        $logRegistrasiId = null;
        $recipientType = 'manual';
        // Jika registrasi & key diberikan, render dari DB
        if ($registrasiId && $key !== '') {
            $r = $db
                ->table('registrasi r')
                ->select('r.*, k.nama_kelas, jk.tanggal_mulai, jk.tanggal_selesai, jk.id as jadwal_id')
                ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
                ->join('jadwal_kelas jk', 'jk.id = r.jadwal_id', 'left')
                ->where('r.id', (int) $registrasiId)
                ->where('r.deleted_at IS NULL')
                ->get()
                ->getRowArray();
            if (!$r) {
                return $this->response->setJSON(['success' => false, 'message' => 'Registrasi tidak ditemukan'])->setStatusCode(404);
            }
            $tpl = model(WahaTemplate::class)->where('key', $key)->first();
            if (!$tpl || empty($tpl['template'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Template tidak ditemukan'])->setStatusCode(404);
            }
            $data = $this->buildTemplateDataFromRegistrasi($r, $db);
            $message = $ws->renderTemplate((string) $tpl['template'], $data);
            // Gunakan nomor dari registrasi bila tidak ada override
            if ($phone === '') {
                $phone = (string) ($r['no_telp'] ?? '');
            }
            $logRegistrasiId = (int) $r['id'];
            $recipientType = 'user';
        } else {
            // fallback lama: gunakan phone + template langsung
            if ($phone === '' || $template === '') {
                return $this->response->setJSON(['success' => false, 'message' => 'Phone & template wajib diisi'])->setStatusCode(422);
            }
            $message = $ws->renderTemplate($template, [
                'nama' => 'Test User',
                'nama_kelas' => 'Contoh Kelas',
                'jadwal' => 'Besok 09:00',
                'kota' => 'Bandung',
                'kabupaten' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'no_tlp' => '6281122233344',
                'email' => 'test.user@example.com',
                'jumlah_dibayar' => '500.000',
                'no_sertifikat' => 'EQI-TEST-0001',
            ]);
        }

        if ($phone === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Nomor tujuan tidak tersedia'])->setStatusCode(422);
        }
        $result = $ws->sendMessage($phone, $message);
        // log hasil (tipe test)
        model(WahaLog::class)->insert([
            'registrasi_id' => $logRegistrasiId,
            'scenario' => 'test_send',
            'recipient' => $recipientType,
            'phone' => $phone,
            'message' => $message,
            'status' => $result['success'] ? 'success' : 'failed',
            'error' => $result['success'] ? null : ($result['message'] ?? ''),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return $this->response->setJSON(['success' => $result['success'], 'message' => $result['message'] ?? '', 'rendered' => $message]);
    }

    /**
     * Daftar penerima test: ambil registrasi terbaru (non-deleted)
     */
    public function wahaTestRecipientsJson()
    {
        $db = \Config\Database::connect();
        $rows = $db
            ->table('registrasi r')
            ->select('r.id, r.nama, r.no_telp, r.email, k.nama_kelas')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->where('r.deleted_at IS NULL')
            ->orderBy('r.tanggal_daftar', 'DESC')
            ->limit(20)
            ->get()
            ->getResultArray();
        return $this->response->setJSON(['success' => true, 'data' => $rows]);
    }

    /**
     * Bangun data placeholder dari satu baris registrasi.
     */
    protected function buildTemplateDataFromRegistrasi(array $r, $db): array
    {
        // Format tanggal dalam bahasa Indonesia H-3 logic reusable
        $bulanMap = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $fmtTgl = function ($d) use ($bulanMap) {
            $ts = strtotime((string) $d);
            if (!$ts)
                return '';
            $day = date('j', $ts);
            $m = (int) date('n', $ts);
            $yy = date('y', $ts);
            $bulan = $bulanMap[$m] ?? date('F', $ts);
            return $day . ' ' . $bulan . ' ' . $yy;
        };
        $jadwalLabel = '';
        if (!empty($r['tanggal_mulai'])) {
            $jadwalLabel = $fmtTgl($r['tanggal_mulai']);
            if (!empty($r['tanggal_selesai'])) {
                $jadwalLabel .= ' - ' . $fmtTgl($r['tanggal_selesai']);
            }
        }
        $fmtRp = function ($v) {
            $n = (float) ($v ?? 0);
            return 'Rp ' . number_format($n, 0, ',', '.');
        };
        // Nama kota dari kode lokasi
        $kotaName = '';
        $lokasiCode = (string) ($r['lokasi'] ?? '');
        if ($lokasiCode !== '') {
            $kk = $db->table('kota_kelas')->select('nama')->where('kode', $lokasiCode)->get()->getRowArray();
            $kotaName = (string) ($kk['nama'] ?? '');
        }
        if ($kotaName === '') {
            $kotaName = ucfirst($lokasiCode);
        }

        // Sertifikat (opsional)
        $noSertifikat = '';
        if (!empty($r['id'])) {
            $cert = $db
                ->table('sertifikat')
                ->select('nomor_sertifikat')
                ->where('registrasi_id', (int) $r['id'])
                ->orderBy('created_at', 'DESC')
                ->get()
                ->getRowArray();
            $noSertifikat = (string) ($cert['nomor_sertifikat'] ?? '');
        }

        // Voucher (opsional): gabungkan kode dan diskon persen jika ada
        $voucherLabel = '';
        $diskonPersen = null;
        $kodeVoucher = trim((string) ($r['kode_voucher'] ?? ''));
        if ($kodeVoucher !== '') {
            $vRow = $db->table('voucher')->select('diskon_persen')->where('kode_voucher', $kodeVoucher)->get()->getRowArray();
            $dp = isset($vRow['diskon_persen']) ? (float) $vRow['diskon_persen'] : 0.0;
            $diskonPersen = (int) $dp;
            $voucherLabel = 'diskon ' . $diskonPersen . '%';
        }

        return [
            'nama' => $r['nama'] ?? '',
            'nama_kelas' => $r['nama_kelas'] ?? '',
            'jadwal' => $jadwalLabel,
            'kota' => $kotaName,
            'kabupaten' => $r['kabupaten'] ?? '',
            'provinsi' => $r['provinsi'] ?? '',
            'no_tlp' => $r['no_telp'] ?? '',
            'email' => $r['email'] ?? '',
            'status_pembayaran' => $r['status_pembayaran'] ?? '',
            'jumlah_tagihan' => $fmtRp($r['biaya_tagihan'] ?? 0),
            'jumlah_dibayar' => $fmtRp($r['biaya_dibayar'] ?? 0),
            'no_sertifikat' => $noSertifikat,
            'diskon_persen' => $diskonPersen ?? 0,
            'voucher' => $voucherLabel,
        ];
    }

    public function processWahaQueue()
    {
        $qm = model(WahaQueue::class);
        $ws = new WahaService();
        $now = date('Y-m-d H:i:s');
        $sendDelaySeconds = (int) (env('WAHA_SEND_DELAY_SECONDS') ?? 60);
        if ($sendDelaySeconds < 1) {
            $sendDelaySeconds = 60;
        }
        // Ambil konfigurasi retry dari tabel waha_config atau .env fallback
        $cfg = model(\App\Models\WahaConfig::class);
        // Ubah strategi retry: default 6 kali, interval 5 menit
        $maxRetry = (int) ($cfg->getValue('max_retry', env('WAHA_MAX_RETRY') ?? 6));
        $retryInterval = (int) ($cfg->getValue('retry_interval', env('WAHA_RETRY_INTERVAL') ?? 5));  // menit
        $items = $qm
            ->where('status', 'queued')
            ->groupStart()
            ->where('next_attempt_at IS NULL')
            ->orWhere('next_attempt_at <=', $now)
            ->groupEnd()
            ->orderBy('id', 'ASC')
            ->findAll(50);
        $processed = 0;
        foreach ($items as $item) {
            $processed++;
            $message = '';
            $payload = json_decode((string) ($item['payload'] ?? '{}'), true) ?: [];
            // Validasi template: wajib ada bila template_key diset
            if (!empty($item['template_key'])) {
                $tpl = model(WahaTemplate::class)->where('key', $item['template_key'])->first();
                if (!$tpl || empty($tpl['template'])) {
                    // Template tidak ditemukan: tandai gagal permanen, tanpa retry
                    $qm->update($item['id'], ['status' => 'failed', 'attempts' => ((int) ($item['attempts'] ?? 0)) + 1]);
                    model(WahaLog::class)->insert([
                        'registrasi_id' => $item['registrasi_id'] ?? null,
                        'scenario' => $item['scenario'] ?? '',
                        'recipient' => $item['recipient'] ?? 'user',
                        'phone' => $item['phone'] ?? '',
                        'message' => '',
                        'status' => 'failed',
                        'error' => 'template_not_found:' . (string) ($item['template_key'] ?? ''),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    continue;
                }
                $message = $ws->renderTemplate((string) ($tpl['template'] ?? ''), $payload);
            } else {
                $message = (string) ($payload['message'] ?? '');
            }

            $res = $ws->sendMessage((string) $item['phone'], $message);
            $attempts = (int) ($item['attempts'] ?? 0) + 1;
            if ($res['success']) {
                $qm->update($item['id'], ['status' => 'done', 'attempts' => $attempts]);
                model(WahaLog::class)->insert([
                    'registrasi_id' => $item['registrasi_id'] ?? null,
                    'scenario' => $item['scenario'] ?? '',
                    'recipient' => $item['recipient'] ?? 'user',
                    'phone' => $item['phone'] ?? '',
                    'message' => $message,
                    'status' => 'success',
                    'error' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                // Retry berbasis konfigurasi: batas maksimal dan interval menit
                if ($attempts >= $maxRetry) {
                    $qm->update($item['id'], ['status' => 'failed', 'attempts' => $attempts, 'next_attempt_at' => null]);
                } else {
                    $next = date('Y-m-d H:i:s', time() + ($retryInterval * 60));
                    $qm->update($item['id'], ['status' => 'queued', 'attempts' => $attempts, 'next_attempt_at' => $next]);
                }
                model(WahaLog::class)->insert([
                    'registrasi_id' => $item['registrasi_id'] ?? null,
                    'scenario' => $item['scenario'] ?? '',
                    'recipient' => $item['recipient'] ?? 'user',
                    'phone' => $item['phone'] ?? '',
                    'message' => $message,
                    'status' => ($attempts >= $maxRetry) ? 'failed_final' : 'failed',
                    'error' => $res['message'] ?? '',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            sleep($sendDelaySeconds);
        }
        return $this->response->setJSON(['success' => true, 'processed' => $processed]);
    }

    public function runReminders()
    {
        // H-3 sebelum kelas dimulai
        $db = \Config\Database::connect();
        $qm = model(WahaQueue::class);
        $targetDate = date('Y-m-d', strtotime('+3 days'));
        $rows = $db
            ->table('registrasi r')
            ->select('r.id, r.nama, r.no_telp, r.status_pembayaran, r.kode_kelas, r.lokasi, r.biaya_tagihan, jk.tanggal_mulai, jk.tanggal_selesai, jk.id as jadwal_id, k.nama_kelas')
            ->join('kelas k', 'k.kode_kelas = r.kode_kelas', 'left')
            ->join('jadwal_kelas jk', 'jk.id = r.jadwal_id', 'left')
            ->where('DATE(jk.tanggal_mulai)', $targetDate)
            ->where('r.deleted_at IS NULL')
            ->get()
            ->getResultArray();
        $count = 0;
        foreach ($rows as $r) {
            $isDP = (strtolower($r['status_pembayaran'] ?? '') === 'dp 50%');
            // Format jadwal: "dd Bulan yy - dd Bulan yy"
            $bulanMap = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
            $fmtTgl = function ($d) use ($bulanMap) {
                $ts = strtotime((string) $d);
                if (!$ts)
                    return '';
                $day = date('j', $ts);
                $m = (int) date('n', $ts);
                $yy = date('y', $ts);
                $bulan = $bulanMap[$m] ?? date('F', $ts);
                return $day . ' ' . $bulan . ' ' . $yy;
            };
            $jadwalLabel = '';
            if (!empty($r['tanggal_mulai'])) {
                $jadwalLabel = $fmtTgl($r['tanggal_mulai']);
                if (!empty($r['tanggal_selesai'])) {
                    $jadwalLabel .= ' - ' . $fmtTgl($r['tanggal_selesai']);
                }
            }
            // Format rupiah dengan prefix Rp
            $fmtRp = function ($v) {
                $n = (float) ($v ?? 0);
                return 'Rp ' . number_format($n, 0, ',', '.');
            };
            // Tampilkan nama kota dari kode lokasi
            $kotaName = '';
            $lokasiCode = (string) ($r['lokasi'] ?? '');
            if ($lokasiCode !== '') {
                $kk = $db->table('kota_kelas')->select('nama')->where('kode', $lokasiCode)->get()->getRowArray();
                $kotaName = (string) ($kk['nama'] ?? '');
            }
            if ($kotaName === '') {
                $kotaName = ucfirst($lokasiCode);
            }

            $payload = [
                'nama' => $r['nama'] ?? '',
                'jumlah_tagihan' => $fmtRp($isDP ? ($r['biaya_tagihan'] ?? 0) : 0),
                'nama_kelas' => $r['nama_kelas'] ?? '',
                'jadwal' => $jadwalLabel,
                'kota' => $kotaName,
            ];
            // Peserta: DP50 atau Lunas
            $qm->insert([
                'registrasi_id' => $r['id'],
                'scenario' => $isDP ? 'tagihan_dp50_peserta' : 'tagihan_lunas_peserta',
                'recipient' => 'user',
                'phone' => $r['no_telp'] ?? '',
                'template_key' => $isDP ? 'tagihan_dp50_peserta' : 'tagihan_lunas_peserta',
                'payload' => json_encode($payload),
                'status' => 'queued',
                'attempts' => 0,
                'next_attempt_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $count++;
            // Admin: hanya DP50
            if ($isDP) {
                $adminPhone = (string) (env('WAHA_ADMIN_PHONE') ?? '');
                if ($adminPhone !== '') {
                    $payloadAdmin = [
                        'nama' => $r['nama'] ?? '',
                        'jumlah_tagihan' => $fmtRp($r['biaya_tagihan'] ?? 0),
                        'nama_kelas' => $r['nama_kelas'] ?? '',
                        'jadwal' => $jadwalLabel,
                        'kota' => $kotaName,
                    ];
                    $qm->insert([
                        'registrasi_id' => $r['id'],
                        'scenario' => 'tagihan_dp50_admin',
                        'recipient' => 'admin',
                        'phone' => $adminPhone,
                        'template_key' => 'tagihan_dp50_admin',
                        'payload' => json_encode($payloadAdmin),
                        'status' => 'queued',
                        'attempts' => 0,
                        'next_attempt_at' => null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $count++;
                }
            }
        }
        return $this->response->setJSON(['success' => true, 'queued' => $count]);
    }
}

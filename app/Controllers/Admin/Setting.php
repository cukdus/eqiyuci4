<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
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
        // Halaman WA-API sederhana
        return view('layout/admin_layout', [
            'title' => 'WA-API',
            'content' => view('admin/setting/waha'),
        ]);
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
}

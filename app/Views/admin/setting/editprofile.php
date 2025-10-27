<?php
$authUser = service('authentication')->user();
$u = isset($user) && $user ? $user : $authUser;
$fullName = $u ? ($u->full_name ?? $u->fullname ?? $u->nama_lengkap ?? $u->name ?? $u->nama ?? null) : null;
$email = $u->email ?? '';
$username = $u->username ?? '';
$photo = $u->photo ?? null; // path relatif, jika ada
$errors = session('errors') ?? [];
$message = session('message') ?? null;

$formAction = isset($user) && $user ? site_url('admin/setting/users/' . $u->id . '/update') : site_url('admin/setting/update');
$heading = isset($user) && $user ? 'Edit User' : 'Edit Profil';

$groups = $groups ?? [];
$canManageRoles = $canManageRoles ?? false;
$currentRoles = (array) $u->getRoles();
$currentRoleName = $currentRoles ? reset($currentRoles) : '';
?>

<div class="container-fluid py-3">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <?php if ($message): ?>
        <div class="alert alert-success" role="alert">
          <?= esc($message) ?>
        </div>
      <?php endif; ?>
      <?php if (! empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
          <div class="fw-semibold mb-1">Terjadi kesalahan:</div>
          <ul class="mb-0">
            <?php foreach ((array) $errors as $err): ?>
              <li><?= esc($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <div class="row align-items-center g-3">
        <div class="col-auto">
          <div style="width:96px;height:96px;border-radius:50%;background:#f0f2f5;display:flex;align-items:center;justify-content:center;overflow:hidden;">
            <?php if ($photo): ?>
              <img src="<?= esc(base_url($photo)) ?>" alt="Foto Profil" style="width:100%;height:100%;object-fit:cover;" />
            <?php else: ?>
              <i class="bi bi-person-circle" style="font-size:64px;color:#6c757d;"></i>
            <?php endif; ?>
          </div>
        </div>
        <div class="col">
          <h5 class="mb-1"><?= esc($heading) ?></h5>
          <div class="text-muted">Sesuaikan data akun Anda, dan unggah foto jika tersedia.</div>
        </div>
      </div>

      <hr class="my-4" />

      <form method="post" action="<?= $formAction ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="full_name" class="form-control" value="<?= esc(old('full_name', $fullName ?? '')) ?>" placeholder="Masukkan nama lengkap" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?= esc(old('username', $username)) ?>" placeholder="Masukkan username" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= esc(old('email', $email)) ?>" placeholder="Masukkan email" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Foto Profil</label>
            <input type="file" name="photo" class="form-control" accept="image/*" />
            <small class="text-muted">Format gambar disarankan: JPG/PNG, ukuran maksimal 2MB.</small>
          </div>

          <?php if ($canManageRoles): ?>
          <div class="col-md-6">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
              <option value="" disabled <?= old('role', $currentRoleName) === '' ? 'selected' : '' ?>>-- Pilih Role --</option>
              <?php foreach ($groups as $g): ?>
                <?php $gName = is_object($g) ? ($g->name ?? '') : ($g['name'] ?? ''); ?>
                <option value="<?= esc($gName) ?>" <?= old('role', $currentRoleName) === $gName ? 'selected' : '' ?>><?= esc($gName) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php endif; ?>
        </div>

        <div class="mt-4">
          <h6 class="mb-2">Ganti Password</h6>
          <div class="row g-3">
            <?php if (isset($user) && $user): ?>
              <div class="col-md-6">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password baru" autocomplete="new-password" />
              </div>
              <div class="col-md-6">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="pass_confirm" class="form-control" placeholder="Ulangi password baru" autocomplete="new-password" />
              </div>
            <?php else: ?>
              <div class="col-md-6">
                <label class="form-label">Password Baru</label>
                <input type="password" name="new_password" class="form-control" placeholder="Masukkan password baru" autocomplete="new-password" />
              </div>
              <div class="col-md-6">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirm" class="form-control" placeholder="Ulangi password baru" autocomplete="new-password" />
              </div>
            <?php endif; ?>
          </div>
          <small class="text-muted d-block mt-1">Isi dua field di atas jika ingin mengganti password.</small>
          <small class="text-muted d-block">Kriteria password kuat: kombinasi huruf besar/kecil, angka, dan simbol; tidak umum; tidak mirip dengan username/email.</small>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
          <a href="<?= site_url('admin/setting') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
      </form>
    </div>
  </div>
</div>
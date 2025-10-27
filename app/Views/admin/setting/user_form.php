<?php
/** @var string $mode */
/** @var App\Entities\User|null $user */
$mode = $mode ?? 'create';
$isEdit = $mode === 'edit';
$title  = $isEdit ? 'Edit User' : 'Tambah User';
$action = $isEdit ? site_url('admin/setting/users/' . ($user ? $user->id : '') . '/update') : site_url('admin/setting/users/store');

$username = $user ? ($user->username ?? '') : '';
$email    = $user ? ($user->email ?? '') : '';
$photo    = $user ? ($user->photo ?? null) : null; // path relatif jika ada
$groups   = $groups ?? [];
$currentRoles = $user ? (array) $user->getRoles() : [];
$currentRoleName = $currentRoles ? reset($currentRoles) : '';
?>

<div class="container-fluid py-3">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="row align-items-center g-3">
        <div class="col-auto">
          <div style="width:96px;height:96px;border-radius:50%;background:#f0f2f5;display:flex;align-items:center;justify-content:center;overflow:hidden;">
            <?php if ($photo): ?>
              <img src="<?= esc(base_url($photo)) ?>" alt="Foto User" style="width:100%;height:100%;object-fit:cover;" />
            <?php else: ?>
              <i class="bi bi-person-circle" style="font-size:64px;color:#6c757d;"></i>
            <?php endif; ?>
          </div>
        </div>
        <div class="col">
          <h5 class="mb-1"><?= esc($title) ?></h5>
          <div class="text-muted">Sesuaikan data akun user. Password opsional saat edit.</div>
        </div>
        <div class="col-auto">
          <a href="<?= site_url('admin/setting') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
          </a>
        </div>
      </div>

      <hr class="my-4" />

      <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
          <?php foreach ((array) session()->getFlashdata('errors') as $e): ?>
            <div>- <?= esc($e) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('message')) ?></div>
      <?php endif; ?>

      <form action="<?= $action ?>" method="post">
        <?= csrf_field() ?>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?= esc(old('username', $username)) ?>" placeholder="Masukkan username" required minlength="3" maxlength="30" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= esc(old('email', $email)) ?>" placeholder="Masukkan email" required />
          </div>

          <div class="col-md-6">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
              <option value="" disabled <?= old('role', $currentRoleName) === '' ? 'selected' : '' ?>>-- Pilih Role --</option>
              <?php foreach ($groups as $g): ?>
                <?php $gName = is_object($g) ? ($g->name ?? '') : ($g['name'] ?? ''); ?>
                <option value="<?= esc($gName) ?>" <?= old('role', $currentRoleName) === $gName ? 'selected' : '' ?>><?= esc($gName) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Password<?= $isEdit ? ' (opsional)' : '' ?></label>
            <input type="password" name="password" class="form-control" placeholder="<?= $isEdit ? 'Biarkan kosong jika tidak mengganti' : 'Masukkan password' ?>" <?= $isEdit ? '' : 'required' ?> minlength="8" autocomplete="new-password" />
            <?php if ($isEdit): ?>
              <small class="text-muted">Biarkan kosong jika tidak ingin mengganti password.</small>
            <?php endif; ?>
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
          <a href="<?= site_url('admin/setting') ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
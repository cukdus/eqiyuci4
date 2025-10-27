<?php
/** @var array<int, App\Entities\User> $users */
?>
<div class="container-fluid py-3">
  <?php
$me = service('authentication')->user();
$authz = service('authorization');
$isAdmin = $me ? $authz->inGroup('admin', $me->id) : false;
?>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar Users</h5>
    <?php if ($isAdmin): ?>
      <a href="<?= site_url('admin/setting/users/create') ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Tambah User
      </a>
    <?php endif; ?>
  </div>

  <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= esc(session()->getFlashdata('message')) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= esc(session()->getFlashdata('error')) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (!empty($users)): ?>
    <div class="row g-3">
      <?php foreach ($users as $u): ?>
        <?php
        $fullName = $u->full_name ?? $u->fullname ?? $u->nama_lengkap ?? $u->name ?? $u->nama ?? '';
        if ($fullName === '') {
            $fullName = ($u->username ?? '') ?: ($u->email ?? '');
        }
        $username = $u->username ?? '';
        $email = $u->email ?? '';
        $photo = $u->photo ?? null;
        $active = (bool) ($u->active ?? false);
        $createdRaw = $u->created_at ?? null;
        if (is_object($createdRaw) && method_exists($createdRaw, 'format')) {
            $joined = $createdRaw->format('Y-m-d');
        } elseif (!empty($createdRaw)) {
            $joined = date('Y-m-d', strtotime((string) $createdRaw));
        } else {
            $joined = '-';
        }
        // Kelas badge pada nama sesuai role
        $isUserAdmin = $authz->inGroup('admin', $u->id);
        $isUserStaff = $authz->inGroup('staff', $u->id);
        $nameClass = 'fw-semibold';
        if ($isUserAdmin) {
            $nameClass .= ' badge bg-success';
        } elseif ($isUserStaff) {
            $nameClass .= ' badge bg-warning text-dark';
        }
        ?>
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <div class="row align-items-center g-3">
                <div class="col-auto">
                  <div style="width:72px;height:72px;border-radius:50%;background:#f0f2f5;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                    <?php if ($photo): ?>
                      <img src="<?= esc(base_url($photo)) ?>" alt="Foto User" style="width:100%;height:100%;object-fit:cover;" />
                    <?php else: ?>
                      <i class="bi bi-person-circle" style="font-size:48px;color:#6c757d;"></i>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="col">
                  <div class="d-flex align-items-center gap-2">
                    <p class="<?= esc($nameClass) ?>"><?= esc($fullName) ?></p>
                  </div>
                  <div class="text-muted" style="font-size:0.9rem;">
                    <?php if ($username): ?>
                      <span class="me-3"><i class="bi bi-person"></i> <?= esc($username) ?></span>
                    <?php endif; ?>
                    <?php if ($email): ?>
                      <span class="me-3"><i class="bi bi-envelope"></i> <?= esc($email) ?></span>
                    <?php endif; ?>
                    <span class="me-3"><i class="bi bi-calendar"></i> <?= esc($joined) ?></span>
                  </div>
                </div>
              </div>

              <div class="mt-3 d-flex gap-2">
                <?php $canEdit = $isAdmin || ($me && (int) $me->id === (int) $u->id); ?>
                <?php if ($canEdit): ?>
                  <a href="<?= site_url('admin/setting/users/' . $u->id . '/edit') ?>" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                  </a>
                <?php endif; ?>
                <?php if ($isAdmin): ?>
                  <form action="<?= site_url('admin/setting/users/' . $u->id . '/delete') ?>" method="post" onsubmit="return confirm('Yakin hapus user ini? Tindakan ini akan melakukan soft delete.');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="bi bi-trash"></i> Hapus
                    </button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="card border-0 shadow-sm">
      <div class="card-body text-center text-muted">Belum ada data user.</div>
    </div>
  <?php endif; ?>
</div>
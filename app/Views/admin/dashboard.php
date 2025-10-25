<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Dashboard Admin</h4>
                </div>
                <div class="card-body">
                    <h5>Selamat datang, <?= $user->nama_lengkap ?? $user->username ?></h5>
                    <p>Anda login sebagai: <strong><?= $user->role ?></strong></p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Kelas</h5>
                                    <p class="card-text">Kelola data kelas</p>
                                    <a href="<?= base_url('admin/kelas') ?>" class="btn btn-light">Kelola</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Berita</h5>
                                    <p class="card-text">Kelola data berita</p>
                                    <a href="<?= base_url('admin/berita') ?>" class="btn btn-light">Kelola</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Registrasi</h5>
                                    <p class="card-text">Kelola data registrasi</p>
                                    <a href="<?= base_url('admin/registrasi') ?>" class="btn btn-light">Kelola</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
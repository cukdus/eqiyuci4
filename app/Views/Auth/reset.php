<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="login-container">
    <div class="login-card">
        <div class="login-content">
            <div class="login-image">
                <img src="<?= base_url('assets/images/beach.svg') ?>" alt="Beach Image">
            </div>
            
            <div class="login-form">
                <div class="form-header">
                    <h3><?= lang('Auth.resetYourPassword') ?></h3>
                </div>

            <?= view('App\Views\Auth\_message_block') ?>

            <p class="text-center mb-3"><?= lang('Auth.enterCodeEmailPassword') ?></p>

            <form action="<?= url_to('reset-password') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group mb-3">
                    <input type="text" class="form-control <?php if (session('errors.token')): ?>is-invalid<?php endif ?>"
                           name="token" placeholder="<?= lang('Auth.token') ?>" value="<?= old('token', $token ?? '') ?>">
                    <div class="invalid-feedback">
                        <?= session('errors.token') ?>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <input type="email" class="form-control <?php if (session('errors.email')): ?>is-invalid<?php endif ?>"
                           name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>">
                    <div class="invalid-feedback">
                        <?= session('errors.email') ?>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <input type="password" class="form-control <?php if (session('errors.password')): ?>is-invalid<?php endif ?>"
                           name="password" placeholder="<?= lang('Auth.newPassword') ?>">
                    <div class="invalid-feedback">
                        <?= session('errors.password') ?>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <input type="password" class="form-control <?php if (session('errors.pass_confirm')): ?>is-invalid<?php endif ?>"
                           name="pass_confirm" placeholder="<?= lang('Auth.newPasswordRepeat') ?>">
                    <div class="invalid-feedback">
                        <?= session('errors.pass_confirm') ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-signin"><?= lang('Auth.resetPassword') ?></button>
                
                <div class="form-footer">
                    <a href="<?= url_to('login') ?>" class="forgot-password">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

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
                    <h3><?= lang('Auth.forgotPassword') ?></h3>
                </div>

            <?= view('App\Views\Auth\_message_block') ?>

            <p class="text-center mb-3"><?= lang('Auth.enterEmailForInstructions') ?></p>

            <form action="<?= url_to('forgot') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group mb-3">
                    <input type="email" class="form-control <?php if (session('errors.email')): ?>is-invalid<?php endif ?>"
                           name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>">
                    <div class="invalid-feedback">
                        <?= session('errors.email') ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-signin"><?= lang('Auth.sendInstructions') ?></button>
                
                <div class="form-footer">
                    <a href="<?= url_to('login') ?>" class="forgot-password">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

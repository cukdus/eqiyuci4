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
                    <h3><?= lang('Auth.register') ?></h3>
                </div>

            <?= view('App\Views\Auth\_message_block') ?>

            <form action="<?= url_to('register') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group mb-3">
                    <input type="email" class="form-control <?php if (session('errors.email')): ?>is-invalid<?php endif ?>"
                           name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>">
                    <small id="emailHelp" class="form-text text-muted"><?= lang('Auth.weNeverShare') ?></small>
                </div>

                <div class="form-group mb-3">
                    <input type="text" class="form-control <?php if (session('errors.username')): ?>is-invalid<?php endif ?>" 
                           name="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>">
                </div>

                <div class="form-group mb-3">
                    <input type="password" name="password" 
                           class="form-control <?php if (session('errors.password')): ?>is-invalid<?php endif ?>" 
                           placeholder="<?= lang('Auth.password') ?>" autocomplete="off">
                </div>

                <div class="form-group mb-3">
                    <input type="password" name="pass_confirm" 
                           class="form-control <?php if (session('errors.pass_confirm')): ?>is-invalid<?php endif ?>" 
                           placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off">
                </div>

                <button type="submit" class="btn btn-signin"><?= lang('Auth.register') ?></button>
                
                <div class="form-footer">
                    <p class="text-center"><?= lang('Auth.alreadyRegistered') ?> <a href="<?= url_to('login') ?>" class="sign-up"><?= lang('Auth.signIn') ?></a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

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
                    <h3><?= lang('Auth.loginTitle') ?></h3>
                </div>

                <?= view('Myth\Auth\Views\_message_block') ?>

                <form action="<?= url_to('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <?php if ($config->validFields === ['email']): ?>
                        <div class="form-group">
                            <input type="email" class="form-control <?php if (session('errors.login')): ?>is-invalid<?php endif ?>"
                                name="login" placeholder="Email">
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <input type="text" class="form-control <?php if (session('errors.login')): ?>is-invalid<?php endif ?>"
                                name="login" placeholder="Username">
                        </div>
                    <?php endif; ?>

                    <div class="form-group password-field">
                        <input type="password" name="password" class="form-control <?php if (session('errors.password')): ?>is-invalid<?php endif ?>" placeholder="Password">
                        <span class="password-toggle"><i class="fa fa-eye"></i></span>
                    </div>

                    <button type="submit" class="btn btn-signin">Masukkan Lur...</button>

                    <div class="form-footer">
                        <?php if ($config->allowRemembering): ?>
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember" <?php if (old('remember')): ?> checked <?php endif ?>>
                            <label for="remember">Aku gampang pikun</label>
                        </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('.password-toggle');
    const passwordInput = document.querySelector('input[name="password"]');
    
    togglePassword.addEventListener('click', function() {
        // Toggle type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icon
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
});
</script>

<?= $this->endSection() ?>

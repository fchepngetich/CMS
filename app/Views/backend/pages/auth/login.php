<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="brand-logo mx-auto mb-3">
        <a href="<?= route_to('admin.home') ?>">
            <img src="/backend/vendors/images/logo.png" alt="Logo" />
        </a>
    </div>
    <div class="login-title">
        <h2 class="text-center">Login To Change Management System</h2>
    </div>
    <?php $validations = Config\Services::validation(); ?>

    <form action="<?= route_to('admin.login.handler') ?>" method="POST">
        <?= csrf_field() ?>

        <?php if (!empty(session()->getFlashData('success'))): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
        <?php if (!empty(session()->getFlashData('fail'))): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('fail') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>

        <div class="input-group custom">
            <input type="text" class="form-control form-control-lg" placeholder="Enter Email" name="login_id"
                value="<?= set_value('login_id') ?>">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
            </div>
        </div>
        <?php if ($validations->getError('login_id')): ?>
            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px;">
                <?= $validations->getError('login_id') ?>
            </div>
        <?php endif; ?>

        <div class="input-group custom">
            <input type="password" id="password" class="form-control form-control-lg" placeholder="**********" name="password" value="<?= set_value('password') ?>">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
            </div>
        </div>
        <?php if ($validations->getError('password')): ?>
            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px;">
                <?= $validations->getError('password') ?>
            </div>
        <?php endif; ?>

       

        <div class="row pb-30">
        <div class="col-md-6">
        <div class="form-group">
            <input type="checkbox" id="show_password" onclick="togglePasswordVisibility()"> 
            <label for="show_password">Show Password</label>
        </div>
        </div>

            <div class="col-md-6">
                <div class="forgot-password">
                    <a href="<?= route_to('admin.forgot.form') ?>">Forgot Password</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="input-group mb-0">
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility() {
        var passwordField = document.getElementById('password');
        var showPassword = document.getElementById('show_password');

        if (showPassword.checked) {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }
</script>

<?= $this->endSection() ?>

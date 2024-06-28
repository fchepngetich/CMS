<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center">Change Password</h2>
    </div>
    <form action="<?= route_to('change_password') ?>" method="POST">
        <?= csrf_field() ?>
        
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('fail')) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('fail') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="input-group custom">
            <input type="password" class="form-control form-control-lg" id="current_password" name="current_password" placeholder="Current Password" required>
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
            </div>
        </div>

        <div class="input-group custom">
            <input type="password" class="form-control form-control-lg" id="new_password" name="new_password" placeholder="New Password" required>
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
            </div>
        </div>

        <div class="input-group custom">
            <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
            </div>
        </div>

        <div class="form-group mt-2">
            <input type="checkbox" id="show_passwords" onclick="togglePasswordVisibility()"> 
            <label for="show_passwords">Show Passwords</label>
        </div>

        <div class="row align-items-center">
            <div class="col-12">
                <div class="input-group mb-0">
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Change Password">
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility() {
        var currentPassword = document.getElementById('current_password');
        var newPassword = document.getElementById('new_password');
        var confirmPassword = document.getElementById('confirm_password');
        var showPasswords = document.getElementById('show_passwords');

        if (showPasswords.checked) {
            currentPassword.type = 'text';
            newPassword.type = 'text';
            confirmPassword.type = 'text';
        } else {
            currentPassword.type = 'password';
            newPassword.type = 'password';
            confirmPassword.type = 'password';
        }
    }
</script>

<?= $this->endSection() ?>

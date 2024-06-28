<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-box bg-white box-shadow border-radius-10 p-4">
                <div class="profile-title mb-4">
                    <h2 class="text-center">Profile</h2>
                </div>
                
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-6 profile-item mb-2">
                            <label class="font-weight-bold">Full Name:</label>
                            <p class="text-muted"><?= esc($user['full_name']) ?></p>
                        </div>
                        <div class="col-md-6 profile-item mb-2">
                            <label class="font-weight-bold">Email:</label>
                            <p class="text-muted"><?= esc($user['email']) ?></p>
                        </div>
                        <div class="col-md-6 profile-item mb-2">
                            <label class="font-weight-bold">Role:</label>
                            <p class="text-muted"><?= esc(getRoleNameById($user['role'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>

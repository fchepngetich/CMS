<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container mb-5">
    <div class="page-header ">
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="title">
            <h4>My Profile</h4>
        </div>
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= base_url('admin/home')?>">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    View Profile
                </li>
            </ol>
        </nav>
    </div>
   
</div>
</div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-box bg-white box-shadow border-radius-10 p-4">
               
                
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

<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
        <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
        <div class="header-search">
            <form>
                <div class="form-group mb-0">
                    <h4 class="text-center">Change Management System</h4>

                </div>
            </form>
        </div>
    </div>
    <div class="header-right">

        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle mr-5" href="#" role="button" data-toggle="dropdown">
                    <!--<span class="user-icon">
                            <img src="<? //esc($user['profile_photo'] ?? '/backend/vendors/images/default-photo.jpg') ?>" alt="" />
                        </span>-->
                    <span class="user-name mr-1"><?= esc($full_name) ?></span>
                </a>


                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="<?= base_url('/admin/profile') ?>"><i class="dw dw-user"></i>
                        Profile</a>
                    <a class="dropdown-item" href="<?= base_url('/admin/change-password') ?>"><i class="dw dw-settings"></i>
                        Change Password</a>
                    <a class="dropdown-item" href="<?= base_url('admin/logout') ?>"><i class="dw dw-logout"></i> Log
                        Out</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Manage Users</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Manage Users
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#user-modal">
                Add User
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-box">
            <div class="card-body">
                <table class="table table-sm table-hover table-striped table-borderless" id="users-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1; foreach ($users as $user): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= $user['full_name'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['role'] ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning edit-user-btn" data-id="<?= $user['id'] ?>">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(<?= $user['id'] ?>)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- Add User Modal -->
<?php include 'modals/create-user-modal.php'?>
<?php include 'modals/edit-user-modal.php'?>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>

$(document).ready(function() {
    $('#add-user-form').on('submit', function(e) {
        e.preventDefault();

        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var modal = $('#user-modal');
        var formData = new FormData(form);
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formData,
            processData: false,
            dataType: 'json',
            contentType: false,
            cache: false,
            beforeSend: function() {
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function(response) {
                if (response.token) {
                    $('.ci_csrf_data').val(response.token);
                }
                if (response.status === 1) {
                    $(form)[0].reset();
                    modal.modal('hide');
                    toastr.success(response.msg);
                    // Reload  your data tables here if needed
                } else if (response.status === 0) {
                    toastr.error(response.msg);
                } else {
                    if (response.errors) {
                        $.each(response.errors, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val);
                        });
                    } else {
                        toastr.error('An unexpected error occurred.');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", xhr, status, error);
                toastr.error('An error occurred. Please try again.');
            }
        });
    });
});


$(document).ready(function() {
    $('.edit-user-btn').on('click', function() {
        var userId = $(this).data('id');
        
        $.ajax({
            url: '<?= route_to('admin.users.get') ?>/' + userId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 1) {
                    var user = response.user;
                    $('#edit_user_id').val(user.id);
                    $('#edit_full_name').val(user.full_name);
                    $('#edit_email').val(user.email);

                    var roleOptions = '';
                    $.each(response.roles, function(index, role) {
                        var selected = (role.id == user.role_id) ? 'selected' : '';
                        roleOptions += '<option value="' + role.id + '" ' + selected + '>' + role.name + '</option>';
                    });
                    $('#edit_role').html(roleOptions);

                    $('#edit-user-modal').modal('show');
                } else {
                    toastr.error(response.msg);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", xhr, status, error);
                toastr.error('An error occurred. Please try again.');
            }
        });
    });

    $('#edit-user-form').on('submit', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var formdata = new FormData(form);
        formdata.append(csrfName, csrfHash);
        
        $.ajax({
            url: '<?= route_to('admin.users.update') ?>',
            method: 'POST',
            data: formdata,
            processData: false,
            dataType: 'json',
            contentType: false,
            cache: false,
            beforeSend: function() {
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function(response) {
                if (response.token) {
                    $('.ci_csrf_data').val(response.token);
                }
                if (response.status === 1) {
                    $(form)[0].reset();
                    $('#edit-user-modal').modal('hide');
                    toastr.success(response.msg);
                    location.reload();
                } else if (response.status === 0) {
                    toastr.error(response.msg);
                } else {
                    if (response.errors) {
                        $.each(response.errors, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val);
                        });
                    } else {
                        toastr.error('An unexpected error occurred.');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", xhr, status, error);
                toastr.error('An error occurred. Please try again.');
            }
        });
    });
});





function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        $.ajax({
            url: '<?= route_to('admin.users.delete') ?>/' + userId,
            method: 'POST',
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.status === 1) {
                    toastr.success(response.msg);
                    // Optionally reload the table or remove the row
                    location.reload();
                } else {
                    toastr.error(response.msg);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", xhr, status, error);
                toastr.error('An error occurred. Please try again.');
            }
        });
    }
}

</script>
<?= $this->endSection() ?>

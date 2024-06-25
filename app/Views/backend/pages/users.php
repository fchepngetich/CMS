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
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#user-modal">
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
                                <button type="button" class="btn btn-sm btn-warning edit-user-btn btn-sm" data-id="<?= $user['id'] ?>">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-user-btn" data-id="<?= $user['id'] ?>">
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

<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>

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
    // Handle edit button click
    $('.edit-user-btn').on('click', function() {
        var userId = $(this).data('id');
        $.ajax({
            url: '<?= route_to('user.edit') ?>',
            method: 'GET',
            data: { id: userId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 1) {
                    $('#edit-user-id').val(response.data.id);
                    $('#edit-full-name').val(response.data.full_name);
                    $('#edit-email').val(response.data.email);
                    $('#edit-role').val(response.data.role);
                    $('#edit-user-modal').modal('show');
                } else {
                    toastr.error('Failed to fetch user data.');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", xhr, status, error);
                toastr.error('An error occurred. Please try again.');
            }
        });
    });

    // Handle edit user form submission
    $('#edit-user-form').on('submit', function(e) {
        e.preventDefault();

        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
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
                    $('#edit-user-modal').modal('hide');
                    toastr.success(response.msg);
                    // Reload your data tables here if needed
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
    // Handle delete button click
    $('.delete-user-btn').on('click', function() {
        var userId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= route_to('user.delete') ?>',
                    method: 'POST',
                    data: {
                        id: userId,
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.token) {
                            $('.ci_csrf_data').val(response.token);
                        }
                        if (response.status === 1) {
                            Swal.fire(
                                'Deleted!',
                                response.msg,
                                'success'
                            )
                            $('#user-row-' + userId).remove();
                        } else {
                            Swal.fire(
                                'Error!',
                                response.msg,
                                'error'
                            )
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed:", xhr, status, error);
                        Swal.fire(
                            'Error!',
                            'An error occurred. Please try again.',
                            'error'
                        )
                    }
                });
            }
        });
    });
});

</script>
<?= $this->endSection() ?>

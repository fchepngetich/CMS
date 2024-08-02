<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Manage Roles</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/home') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Manage Roles
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <button type="button" id="btn-add-role" class="btn btn-primary btn-sm">
                   Add Role
                </button>
            </div>
        </div>
    </div>
<div class="col-md-12">
    <div class="card card-box">
        <div class="card-body">
            <table class="table table-sm table-hover table-striped table-borderless" id="roles-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Role Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlspecialchars($role['name']) ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-edit-role" data-id="<?= $role['id'] ?>" data-name="<?= $role['name'] ?>" style="border:none; background:none; color: rgb(38, 94, 215);">
                                <i class="icon-copy dw dw-edit2 mr-3"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-role-btn" data-id="<?= $role['id'] ?>">
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

<!-- Add/Edit Role Modal -->
<div class="modal fade" id="role-modal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="role-form" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">Add Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role-name">Role Name</label>
                        <input type="text" name="name" id="role-name" class="form-control" required>
                        <span class="error-text name_error"></span>
                    </div>
                    <input type="hidden" name="id" id="role-id">
                    <input type="hidden" class="ci_csrf_data" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div></div>
<?= $this->endSection() ?>

<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="<?= base_url('public/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/backend/src/plugins/datatables/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
<link rel="stylesheet" href="<?= base_url('public/extra-assets/jquery-ui-1.13.3/jquery-ui.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/extra-assets/jquery-ui-1.13.3/jquery-ui.structure.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/extra-assets/jquery-ui-1.13.3/jquery-ui.theme.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('public/backend/src/plugins/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('public/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('public/backend/src/plugins/datatables/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('public/backend/src/plugins/datatables/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="<?= base_url('public/extra-assets/jquery-ui-1.13.3/jquery-ui.min.js') ?>"></script>


<script>
    $(document).ready(function() {
        // Handle edit button click
        $('.btn-edit-role').on('click', function() {
            var roleId = $(this).data('id');
            var roleName = $(this).data('name');

            $('#role-id').val(roleId);
            $('#role-name').val(roleName);
            $('#role-form').attr('action', '<?= base_url('admin/roles/update') ?>/' + roleId);
            $('#roleModalLabel').text('Edit Role');
            $('#role-modal').modal('show');
        });

        // Handle add new role button click
        $('#btn-add-role').on('click', function() {
            $('#role-id').val('');
            $('#role-name').val('');
            $('#role-form').attr('action', '<?= base_url('admin/roles/create') ?>');
            $('#roleModalLabel').text('Add Role');
            $('#role-modal').modal('show');
        });

        // Handle form submission
        $('#role-form').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var formData = new FormData(form);
            var id = $('#role-id').val();
            var url = id ? '<?= base_url('admin/roles/update') ?>/' + id : '<?= base_url('admin/roles/create') ?>';

            $.ajax({
                url: url,
                method: 'post',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.token) {
                        $('.ci_csrf_data').val(response.token);
                    }
                    if (response.status === 1) {
                        $('#role-modal').modal('hide');
                        toastr.success(response.msg);
                        setTimeout(function() { location.reload(); }, 1000);
                    } else if (response.status === 0) {
                        toastr.error(response.msg);
                        if (response.errors) {
                            $.each(response.errors, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val);
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed:", xhr, status, error);
                    toastr.error('An error occurred. Please try again.');
                }
            });
        });

        // Handle delete button click
        $('.delete-role-btn').on('click', function() {
            var id = $(this).data('id');
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
                        url: '<?= base_url('admin/roles/delete') ?>/' + id,
                        method: 'get',
                        dataType: 'json',
                        success: function(response) {
                            if (response.token) {
                                $('.ci_csrf_data').val(response.token);
                            }
                            if (response.status === 1) {
                                toastr.success(response.msg);
                                setTimeout(function() { location.reload(); }, 1000);
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
            });
        });
    });
</script>
<?= $this->endSection() ?>

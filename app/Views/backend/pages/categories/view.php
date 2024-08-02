<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="page-header ">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>View Categories</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/home') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            View Categories
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="<?= base_url('admin/categories/add-categories') ?>" class="btn btn-primary btn-sm ">Add category</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-box">
                <div class="card-body">
                    <table class="data-table table nowrap dataTable no-footer dtr-inline" id="categories-table" role="grid">
                        <thead>
                            <tr role="row">
                                <th class="sorting" tabindex="0">#</th>
                                <th class="sorting" tabindex="0">Category</th>
                                <th class="sorting" tabindex="0">Total Tickets</th>
                                <th class="sorting" tabindex="0">Pending Tickets</th>
                                <th class="sorting" tabindex="0">Closed Tickets</th>
                                <th class="datatable-nosort sorting_disabled" rowspan="1" colspan="1" aria-label="Actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php foreach ($categories as $category) : ?>
                                <tr role="row" class="<?= $count % 2 == 0 ? 'even' : 'odd' ?>">
                                    <td class="sorting_1"><?= $count++ ?></td>
                                    <td> <div class="category-item">
                                        <a href="<?= base_url('admin/categories/tickets/' . $category['id']) ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                            <?php if ($category['unread_count'] > 0): ?>
                                                <span class="badge badge-danger"><?= $category['unread_count'] ?></span>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    </td>
                                    <td><?= htmlspecialchars($category['total_tickets']) ?></td>
                                    <td><?= htmlspecialchars($category['pending_tickets']) ?></td>
                                    <td><?= htmlspecialchars($category['closed_tickets']) ?></td>
                                    <td>
                                       <div class="table-actions">
                                            <a href="<?= base_url('admin/categories/edit-category/'.$category['id']) ?>" data-color="#265ed7" style="color: rgb(38, 94, 215);"><i class="icon-copy dw dw-edit2 mr-3"></i></a>
                                         <button type="button" class="delete-category-btn" data-id="<?= $category['id'] ?>" data-color="#e95959" style="border:none; background:none; color: rgb(233, 89, 89);">
                                            <i class="icon-copy dw dw-delete-3"></i>
                                        </button>                                       
                                            
                                            </div>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.3/jquery-ui.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.3/jquery-ui.structure.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.3/jquery-ui.theme.min.css">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="/backend/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="/extra-assets/jquery-ui-1.13.3/jquery-ui.min.js"></script>
<script>

$(document).ready(function() {
    $('#categories-table').DataTable({
        "scrollX": true,
        "autoWidth": false,
        "ordering": true,
        "columnDefs": [{
            "targets": 'datatable-nosort',
            "orderable": false
        }]
    });
});


$(document).ready(function() {
        $('.delete-category-btn').on('click', function() {
        var categoryId = $(this).data('id');
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
                    url: '<?= base_url('admin/categories/delete-category') ?>',
                    method: 'POST',
                    data: {
                        id: categoryId,
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
                            ).then(() => {
                                location.reload(); 
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.msg,
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed:", xhr, status, error);
                        Swal.fire(
                            'Error!',
                            'An error occurred. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});

</script>
<?= $this->endSection() ?>

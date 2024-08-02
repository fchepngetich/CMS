<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Tickets Reports</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/home') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Reports 
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <table id="ticketsTable" class="data-table table nowrap dataTable no-footer dtr-inline" role="grid" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Dev Assigned</th>
                <th>Date Assigned</th>
                <th>Status</th>
                <th>Date Completed</th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1; foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><a href="<?= base_url('admin/tickets/ticket-details/' . $ticket['id']) ?>"><?= $ticket['subject'] ?></a></td>
                    <td><?= !empty($ticket['assigned_to']) ? getUsernameById($ticket['assigned_to']) : '-' ?></td>
                    <td><?= !empty($ticket['assigned_at']) ? date('jS F Y', strtotime($ticket['assigned_at'])) : '-' ?></td>
                    <td>
                        <?php if ($ticket['dev_status'] == 0): ?>
                            <span class="badge badge-warning"><?= 'Pending' ?></span>
                        <?php else: ?>
                            <span class="badge badge-success"><?= 'Completed' ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= !empty($ticket['date_completed']) ? date('jS F Y', strtotime($ticket['date_completed'])) : '-' ?></td>
                   
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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
    $('#ticketsTable').DataTable();

    $('.close-ticket').on('click', function() {
        var ticketId = $(this).data('ticket-id');
        $('#ticket_id').val(ticketId);
        $('#remarksModal').modal('show');
    });

    $('#remarks-form').on('submit', function(e) {
        e.preventDefault();

        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var modal = $('#remarksModal');
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
                if (response.status === 1) {
                    $(form)[0].reset();
                    modal.modal('hide');
                    toastr.success(response.msg);
                    setTimeout(function() {
                        window.location.href = "<?= base_url('admin/home') ?>"; 
                    }, 1500); 
                } else if (response.status === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.msg,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", xhr, status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});

</script>
<?= $this->endSection() ?>



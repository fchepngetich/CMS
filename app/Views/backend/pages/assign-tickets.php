<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Assign Developer</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/home')?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Assign Developer
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="assign-ticket-box bg-white box-shadow border-radius-10 p-4">
        <form id="assignTicketForm" action="<?= base_url('admin/tickets/assign') ?>" method="POST">
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category_id" required>
                                        <option value="" disabled selected>Select Category</option>

                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="ticket">Ticket</label>
                <select class="form-control" id="ticket" name="ticket_id" required>
                <option value="" disabled selected>Select Category First</option>

                    <!-- Options will be populated dynamically -->
                </select>
            </div>

            <div class="form-group">
                <label for="agent">Assign to Developer</label>
                <select class="form-control" id="agent" name="agent_ids[]" multiple required>
                                        <option value="" disabled>Select Developer</option>

                    <?php foreach ($agents as $agent): ?>
                        <option value="<?= $agent['id'] ?>"><?= $agent['full_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-sm">Assign Ticket</button>
                <span><a href="<?= base_url('admin/home')?>" class="btn btn-primary btn-sm">Cancel</a></span>
            </div>
        </form>
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
        // CSRF token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            }
        });

        $('#category').on('change', function() {
            var categoryId = $(this).val();
            console.log('Category changed:', categoryId); // Debug statement
            $.ajax({
                url: '<?= base_url('admin/tickets/get-open-tickets-by-category') ?>',
                method: 'POST',
                data: {
                    category_id: categoryId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Tickets fetched:', response); // Debug statement
                    var ticketSelect = $('#ticket');
                    ticketSelect.empty();
                    if (response.tickets) {
                        $.each(response.tickets, function(index, ticket) {
                            ticketSelect.append('<option value="' + ticket.id + '">' + ticket.subject + '</option>');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed:", xhr, status, error); // Debug statement
                    Swal.fire(
                        'Error!',
                        'An error occurred while fetching tickets. Please try again.',
                        'error'
                    );
                }
            });
        });

        $('#assignTicketForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            console.log('Form data:', formData); // Debug statement
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log('Form submitted successfully:', response); // Debug statement
                    if (response.status === 1) {
                        Swal.fire(
                            'Success!',
                            response.msg,
                            'success'
                        ).then(() => {
                            window.location.href = '<?= base_url('admin/home') ?>';
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
                    console.error("Form submission failed:", xhr, status, error); // Debug statement
                    Swal.fire(
                        'Error!',
                        'An error occurred while assigning the ticket. Please try again.',
                        'error'
                    );
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>

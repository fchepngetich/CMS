<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Add Ticket</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Ticket
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="<?= base_url('admin/tickets/my-tickets') ?>" class="btn btn-info">View my Tickets</a>
        </div>
    </div>
</div>
<form action="<?= base_url('admin/tickets/create-ticket') ?>" method="post" autocomplete="off" enctype="multipart/form-data" id="addTicketform">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""><b>Category</b></label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="text-danger error-text category_id-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""><b>Ticket Subject</b></label>
                                <input type="text" class="form-control" required placeholder="Enter Ticket Subject" name="title" id="">
                                <span class="text-danger error-text title-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="content" cols="30" rows="10" id="" required class="form-control" placeholder="Type here...."></textarea>
                        <span class="text-danger error-text content-error"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary btn-sm">Add Ticket</button>
        <span> <a href="<?= base_url('admin/home') ?>" class="btn btn-primary btn-sm">Cancel</a>
</span>
    </div>
</form>




<?= $this->section('scripts') ?>
<script>
      $('#addTicketform').on('submit', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var formdata = new FormData(form);
        formdata.append(csrfName, csrfHash);

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
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
                    toastr.success(response.msg);
                    window.location.href = response.redirect;
                    
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
</script>

<?= $this->endSection() ?>

 <?= $this->endSection() ?>

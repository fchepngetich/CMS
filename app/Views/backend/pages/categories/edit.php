<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Edit Category</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/home')?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Edit Category
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="<?= base_url('admin/categories/get-categories') ?>" class="btn btn-info btn-sm">View Categories</a>
            </div>
        </div>
    </div>

    <form id="editCategoryForm" method="POST" action="<?= base_url('admin/categories/update-category/'.$category['id']) ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= esc($category['name']) ?>" required>
            <span class="name_error text-danger"></span>
        </div>
        <div class="form-group">
            <label for="description">Category Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?= esc($category['description']) ?></textarea>
            <span class="description_error text-danger"></span>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Update Category</button>
        <a href="<?= base_url('admin/categories/get-categories') ?>" class="btn btn-primary btn-sm">Cancel</a>
    </form>
</div>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#editCategoryForm').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(form);

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function(response) {
                if (response.token) {
                    $('input[name=csrf_test_name]').val(response.token); // Update CSRF token name as needed
                }

                if (response.status === 1) {
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
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>

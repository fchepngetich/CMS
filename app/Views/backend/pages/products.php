<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="col-md-12 col-sm-12">
   
    <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= route_to('admin.home') ?>">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
               Products
            </li>
        </ol>
    </nav>
</div>
<div class="row">
<div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-header">
                <div class="clearfix">
                    <div class="pull-left">
                        Products
                    </div>
                    <div class="pull-right">
                        <div class="btn btn-default btn-sm p-0" type="button" id="add_product_btn">
                            <i class="fa fa-plus-circle"></i>Add Product
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover table-striped table-borderless" id="products-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Image</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Description</th>
                            <th scope="col">Price</th>


                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'modals/product-modal-form.php'?>


<?= $this->endSection() ?>

<?= $this->section('stylesheets')?>

<link rel="stylesheet" href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.3/jquery-ui.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.3/jquery-ui.structure.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.3/jquery-ui.theme.min.css">

<?= $this->endSection()?>

<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="/backend/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="/extra-assets/jquery-ui-1.13.3/jquery-ui.min.js"></script>

<script>
$(document).on('click', '#add_product_btn', function(e) {
    e.preventDefault();

    var modal = $('body').find('div#product-modal');
    var modal_title = 'Add Product';
    var modal_btn_text = 'Add';
    var select = modal.find('select[name="category"]');
    var url ="<?= route_to('get-parent-categories')?>";
    
    $.getJSON(url, {parent_category_id: null}, function(response) {
        select.find('option').remove();
        select.html(response.data);
    });

    modal.find('.modal-title').html(modal_title);
    modal.find('.modal-footer > button.action').html(modal_btn_text);
    modal.find('span.error-text').html('');
    modal.find('input[type="text"]').val('');
    modal.find('input[type="text"]').val('');
    modal.find('textarea').html('');
    modal.modal('show');
});

$('#add_product_form').on('submit', function(e) {
    e.preventDefault();
    
    var csrfName = $('.ci_csrf_data').attr('name');
    var csrfHash = $('.ci_csrf_data').val();
    var form = this;
    var modal = $('body').find('div#product-modal');
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
                modal.modal('hide');
                toastr.success(response.msg);
                // Assuming you have DataTables or other components to reload
               // products_DT.ajax.reload(null, false);
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

<?= $this->endSection()?>

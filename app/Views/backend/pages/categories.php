<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="col-md-12 col-sm-12">
   
    <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= route_to('admin.home') ?>">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Categories
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
                        Categories
                    </div>
                    <div class="pull-right">
                        <div class="btn btn-default btn-sm p-0" type="button" id="add_category_btn">
                            <i class="fa fa-plus-circle"></i>Add Category
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover table-striped table-borderless" id="categories-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">No of Subcategories</th>
                            <th scope="col">Action</th>
                            <th scope="col">Ordering</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-header">
                <div class="clearfix">
                    <div class="pull-left">
                        Subcategories
                    </div>
                    <div class="pull-right">
                        <div class="btn btn-default btn-sm p-0" type="button" id="add_subcategory_btn">
                            <i class="fa fa-plus-circle"></i>Add Subcategory
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover table-stripe table-borderless" id="sub-categories-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Sub Category Name</th>
                            <th scope="col">Parent Category</th>
                            <th scope="col">No of Products</th>
                            <th scope="col">Action</th>
                            <th scope="col">Ordering</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'modals/category-modal-form.php'?>
<?php include 'modals/edit-category-modal-form.php'?>
<?php include 'modals/subcategory-modal-form.php'?>
<?php include 'modals/edit-subcategory-modal-form.php'?>




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
    $(document).on('click', '#add_category_btn', function(e) {
        e.preventDefault();
        var modal = $('body').find('div#category-modal');
        var modal_title = 'Add Category';
        var modal_btn_text = 'Add';

        modal.find('.modal-title').html(modal_title);
        modal.find('.modal-footer > button.action').html(modal_btn_text);
        modal.find('span.error-text').html('');
        modal.find('input[type="text"]').val('');
        modal.modal('show');
    });

    $('#add_category_form').on('submit', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var modal = $('body').find('div#category-modal');
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
                    categories_DT.ajax.reload(null,false);
                    subcategories_DT.ajax.reload(null, false);
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
    var categories_DT = $('#categories-table').DataTable({
        processing:true,
        serverSide:true,
        ajax:"<?= route_to('get-categories')?>",
        dom:"Brtip",
        info:true,
        fnCreatedRow:function(row,data,index){
            $('td',row).eq(0).html(index+1);
            $('td',row).parent().attr('data-index',data[0]).attr('data-ordering',data[4]);
        },
        columnDefs:[
            {orderable:false,targets:[0,1,2,3]},
            {visible:false,targets:4}
        ],
        order:[4,'asc']
    });

    $(document).on('click', '.editCategoryBtn', function(e) {
    e.preventDefault();
    var category_id = $(this).data('id');
    var url = "<?= route_to('get-category') ?>";

    $.get(url, { category_id: category_id }, function(response) {
        var modal = $('body').find('div#edit-category-modal');
        var modal_title = 'Edit Category';
        var modal_btn_text = 'Save Changes';

        modal.find('form').find('input[type="hidden"][name="category_id"]').val(category_id);
        modal.find('.modal-title').html(modal_title);
        modal.find('.modal-footer > button.action').html(modal_btn_text);
        modal.find('input[type="text"]').val(response.data.name);
        modal.find('span.error-text').html('');
        modal.modal('show');
    }, 'json');
});

$('#update_category_form').on('submit', function(e) {
    e.preventDefault();
    var csrfName = $('.ci_csrf_data').attr('name');
    var csrfHash = $('.ci_csrf_data').val();
    var form = this;
    var modal = $('body').find('div#edit-category-modal');
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
            $('.ci_csrf_data').val(response.token);
            if ($.isEmptyObject(response.error)) {
                if (response.status == 1) {
                    $(form)[0].reset();
                    modal.modal('hide');
                    toastr.success(response.msg);
                    categories_DT.ajax.reload(null,false);
                    subcategories_DT.ajax.reload(null, false);


                    // Reload or update the datatable here if needed
                } else {
                    toastr.error(response.msg);
                }
            } else {
                $.each(response.error, function(prefix, val) {
                    $(form).find('span.' + prefix + '_error').text(val);
                });
            }
        },
        error: function(xhr, status, error) {
            toastr.error('An error occurred. Please try again.');
        }
    });
    
});
$(document).on('click', '.deleteCategoryBtn', function(e) {
        e.preventDefault();
        var category_id = $(this).data('id');
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();

        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to delete this category!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed deletion
                $.ajax({
                    url: "<?= route_to('delete-category') ?>",
                    method: 'get',
                    data: {
                        category_id: category_id,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('.ci_csrf_data').val(response.token);
                        if (response.status == 1) {
                            categoriesDT.ajax.reload(null, false)
                            subcategories_DT.ajax.reload(null, false);


                            toastr.success(response.msg);
                            $('#categories-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            }
        });
    });

$('table#categories-table').find('tbody').sortable({
        update:function(event,ui){
            $(this).children().each(function(index){
                if ($(this).attr('data-ordering') != (index + 1)) {
                    $(this).attr('data-ordering',(index+1)).addClass('updated');
                }
            });
            var positions = [];

            $('.updated').each(function(index){
                positions.push([$(this).attr('data-index'),$(this).attr('data-ordering')]);
                $(this).removeClass('updated')
            });
            var url =" <?= route_to('reorder-categories')?>";
            $.get(url,{positions:positions}, function(response){
                if(response.status ==1){
                    categories_DT.ajax.reload(null,false);
                    toastr.success(response.msg)
                }
            },'json');
        }


    });

$(document).on('click', '#add_subcategory_btn', function(e) {
        e.preventDefault();
        var modal = $('body').find('div#sub-category-modal');
        var modal_title = 'Add SubCategory';
        var modal_btn_text = 'Add';
        var select = modal.find('select[name="parent_cat"]');
        var url ="<?= route_to('get-parent-categories')?>";
        $.getJSON(url,{parent_category_id:null}, function(response){
            select.find('option').remove();
            select.html(response.data);
        })

        modal.find('.modal-title').html(modal_title);
        modal.find('.modal-footer > button.action').html(modal_btn_text);
        modal.find('span.error-text').html('');
        modal.find('input[type="text"]').val('');
        modal.find('textarea').html('');
        modal.modal('show');
    });


$(document).on('submit','#add_subcategory_form', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var modal = $('body').find('div#sub-category-modal');
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
                console.log("Sending AJAX request...");
            },
            success: function(response) {
                console.log("AJAX request successful:", response);
                if (response.token) {
                    $('.ci_csrf_data').val(response.token);
                }

                if (response.status === 1) {
                    $(form)[0].reset();
                    modal.modal('hide');
                    toastr.success(response.msg);
                    subcategories_DT.ajax.reload(null,false);
                    categories_DT.ajax.reload(null,false);

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

var subcategories_DT = $('#sub-categories-table').DataTable({
        processing:true,
        serverSide:true,
        ajax:"<?= route_to('get-subcategories')?>",
        dom:"Brtip",
        info:true,
        fnCreatedRow:function(row,data,index){
            $('td',row).eq(0).html(index+1);
            $('td',row).parent().attr('data-index',data[0]).attr('data-ordering',data[5]);
        },
        columnDefs:[
            {orderable:false,targets:[0,1,2,3,4]},
            {visible:false,targets:5}
        ],
        order:[5,'asc']
    });

$(document).on('click', '.editsubCategoryBtn', function(e) {
    e.preventDefault();
    var subcategory_id = $(this).data('id');
    var get_subcategory_url = "<?= route_to('get-subcategory') ?>";
    var get_parent_categories_url = "<?= route_to('get-parent-categories') ?>";
    var modal = $('body').find('div#edit-sub-category-modal');
    var modal_title = 'Edit Sub Category';
    var modal_btn_text = 'Save Changes';
    modal.find('span.error-text').html('');
    modal.find('.modal-title').html(modal_title);
    modal.find('.modal-footer > button.action').html(modal_btn_text);
    var select = modal.find('select[name="parent_cat"]');
    
    $.get(get_subcategory_url, { subcategory_id: subcategory_id }, function(response) {
        modal.find('input[type="text"][name="subcategory_name"]').val(response.data.name);
        modal.find('form').find('input[type="hidden"][name="subcategory_id"]').val(response.data.id);
        modal.find('form').find('textarea[name="description"]').val(response.data.description);

        $.getJSON(get_parent_categories_url, { parent_category_id: response.data.parent_cat }, function(response) {
            select.find('option').remove();
            select.html(response.data);

        });
        
        
        modal.modal('show');
    }, 'json');
});

$('#update_subcategory_form').on('submit', function(e) {
    e.preventDefault();
    var csrfName = $('.ci_csrf_data').attr('name');
    var csrfHash = $('.ci_csrf_data').val();
    var form = this;
    var modal = $('body').find('div#edit-sub-category-modal');
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
            $('.ci_csrf_data').val(response.token);
            if ($.isEmptyObject(response.error)) {
                if (response.status == 1) {
                    $(form)[0].reset();
                    modal.modal('hide');
                    toastr.success(response.msg);
                    subcategories_DT.ajax.reload(null, false);
                    categories_DT.ajax.reload(null,false);


                } else {
                    toastr.error(response.msg);
                }
            } else {
                $.each(response.error, function(prefix, val) {
                    $(form).find('span.' + prefix + '_error').text(val);
                });
            }
        },
        error: function(xhr, status, error) {
            toastr.error('An error occurred. Please try again.');
        }
    });
    
});

$('table#sub-categories-table').find('tbody').sortable({
        update:function(event,ui){
            $(this).children().each(function(index){
                if ($(this).attr('data-ordering') != (index + 1)) {
                    $(this).attr('data-ordering',(index+1)).addClass('updated');
                }
            });
            var positions = [];

            $('.updated').each(function(index){
                positions.push([$(this).attr('data-index'),$(this).attr('data-ordering')]);
                $(this).removeClass('updated')
            });
            var url =" <?= route_to('reorder-subcategories')?>";
            $.get(url,{positions:positions}, function(response){
                if(response.status ==1){
                    categories_DT.ajax.reload(null,false);
                    subcategories_DT.ajax.reload(null, false);

                    toastr.success(response.msg)
                }
            },'json');
        }


    });

$(document).on('click', '.deleteSubCategoryBtn', function(e) {
        e.preventDefault();
        var subcategory_id = $(this).data('id');
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();

        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to delete this sub category!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true,
            cancelButtonColor:'#d33',
            confirmButtonColor:'#3085d6'

        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed deletion
                $.ajax({
                    url: "<?= route_to('delete-subcategory') ?>",
                    method: 'get',
                    data: {
                        subcategory_id: subcategory_id,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('.ci_csrf_data').val(response.token);
                        if (response.status == 1) {
                            categoriesDT.ajax.reload(null, false)
                            subcategories_DT.ajax.reload(null, false);


                            toastr.success(response.msg);
                            $('#sub-categories-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            }
        });
    });

</script>




<?= $this->endSection('scripts') ?>
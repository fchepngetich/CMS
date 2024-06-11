
<div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="<?= route_to('add-product') ?>" id="add_product_form" method="post" enctype="multipart/form-data">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Product Title</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for=""><b>Category</b></label>
                            <select name="category" id="" class="form-control">
                                <option value="">Uncategorized</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for=""><b>Product Name</b></label>
                            <input type="text" name="name" class="form-control" required placeholder="Enter product name">
                            <span class="text-danger error product_name_error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for=""><b>Price</b></label>
                            <input type="text" name="price" class="form-control" required placeholder="Enter product price">
                            <span class="text-danger error price_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for=""><b>Image</b></label>
                            <input type="file" name="image" class="form-control" required>
                            <span class="text-danger error image_error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for=""><b>Description</b></label>
                            <textarea name="description" class="form-control" cols="10" rows="4" placeholder="Type..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary action">Save changes</button>
            </div>
        </form>
    </div>
</div>





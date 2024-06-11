<div class="modal fade" id="edit-category-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="<?= route_to('update-category')?>" id="update_category_form" method="post">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Category Title
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token()?>" value="<?= csrf_hash()?>"
                 class="ci_csrf_data">
                 <input type="hidden" name="category_id">
                <div class="form-group">
                    <label for=""><b>Category name</b></label>
                    <input type="text" name="category_name" class="form-control" required placeholder="Enter category name">
                    <span class="text-danger error category_name_error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-primary action">
                    Save changes
                </button>
            </div>
        </form>
    </div>
</div>
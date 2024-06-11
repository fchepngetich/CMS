<!-- Modal to Add User -->
<div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="<?= route_to('create-user') ?>" id="add-user-form" method="post">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Add User
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                <div class="form-group">
                    <label for="full_name"><b>Full Name</b></label>
                    <input type="text" name="full_name" class="form-control" required placeholder="Enter full name">
                    <span class="text-danger error full_name_error"></span>
                </div>
                <div class="form-group">
                    <label for="email"><b>Email</b></label>
                    <input type="email" name="email" class="form-control" required placeholder="Enter email">
                    <span class="text-danger error email_error"></span>
                </div>
                <div class="form-group">
                    <label for="role"><b>Role</b></label>
                    <select name="role" class="form-control" required>
                        <option value="">Select Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-danger error role_error"></span>
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

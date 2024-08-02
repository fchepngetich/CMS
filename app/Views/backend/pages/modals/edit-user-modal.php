<!-- Edit User Modal -->
<div class="modal fade" id="edit-user-modal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="edit-user-form" action="<?= base_url('admin/user/update') ?>" method="post">
            <?= csrf_field() ?>

                <input type="hidden" name="<?= csrf_token() ?>" class="ci_csrf_data" value="<?= csrf_hash() ?>" />
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit-user-id">
                    <div class="form-group">
                        <label for="edit-full-name">Full Name</label>
                        <input type="text" class="form-control" id="edit-full-name" name="full_name" required>
                        <span class="error-text full_name_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit-email">Email</label>
                        <input type="email" class="form-control" id="edit-email" name="email" required>
                        <span class="error-text email_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="edit-role">Role</label>
                        <select class="form-control" id="edit-role" name="role_id" required>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                        <span class="error-text role_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

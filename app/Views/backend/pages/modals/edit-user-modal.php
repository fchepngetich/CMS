<!-- Edit User Modal -->
<div class="modal fade" id="edit-user-modal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="edit-user-form" action="<?= site_url('admin.users.update') ?>" method="post">
            <div class="modal-header">
                <h4 class="modal-title" id="editUserModalLabel">Edit User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                <input type="hidden" id="edit_user_id" name="user_id">

                <div class="form-group">
                    <label for="edit_full_name"><b>Full Name</b></label>
                    <input type="text" id="edit_full_name" name="full_name" class="form-control" required>
                    <span class="text-danger error full_name_error"></span>
                </div>

                <div class="form-group">
                    <label for="edit_email"><b>Email</b></label>
                    <input type="email" id="edit_email" name="email" class="form-control" required>
                    <span class="text-danger error email_error"></span>
                </div>

                <div class="form-group">
                    <label for="edit_role"><b>Role</b></label>
                    <select id="edit_role" name="role" class="form-control" required></select>
                    <span class="text-danger error role_error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>

<!-- modal view -->
<div class="modal fade" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="remarksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="remarksModalLabel">Add Remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('admin/tickets/addRemarks') ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="remarks">Remarks:</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="ticket_id" id="ticket_id" value="">
                    <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-primary action">
                    Submit
                </button>
            </div>                </form>
            </div>
        </div>
    </div>
</div>
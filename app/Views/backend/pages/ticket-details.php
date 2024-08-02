<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container">
  <div class="page-header ">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Details</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('admin/home') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            View Details
                        </li>
                    </ol>
                </nav>
            </div>
           
        </div>
    </div>
    <div class="rounded shadow card mb-5">
        <h5 class="card-header">
            <?= esc($ticket['subject']) ?>
        </h5>
        <div class="card-body">
           
            <p class="ticket-description">
                <?= esc($ticket['description']) ?>
            </p>
             <p class="card-text text-muted mb-2"><small>
                <span>Status:</span>
                <?= esc($ticket['status']) ?> |
                <span></span>
                <?= date('M d, Y - h:i A', strtotime($ticket['created_at'])) ?> |
                <span>Replies:</span>
                <?= count($replies) ?> |
                <?php if (\App\Libraries\CIAuth::role() !== '4'): ?>
                    <?php if (!empty($ticket['assigned_to'])): ?>
    <?php
    $agentIds = array_map('trim', explode(',', $ticket['assigned_to']));
    $agentNames = [];

    foreach ($agentIds as $agentId) {
        if (!empty($agentId)) {
            $agentNames[] = esc(getUsernameById($agentId));
        }
    }

    if (!empty($agentNames)) {
        ?>
        Agent: <?= implode(', ', $agentNames) ?>
    <?php } else { ?>
        Agent: Not Assigned
    <?php } ?>
<?php else: ?>
    Agent: Not Assigned
<?php endif; ?>

                <?php endif; ?>|
                                <?php if (\App\Libraries\CIAuth::role() !== '4'): ?>

        Posted by:  <?= esc(getUsernameById($ticket['user_id'])) ?> 
                        <?php endif; ?>


                </small> 
                
           </p>
            <?php if (\App\Libraries\CIAuth::role() !== '2' && \App\Libraries\CIAuth::role() !== '4'): ?>
 
                           <!-- <div class="col-md-4 mb-2">
                                <?php if ($ticket['status'] === 'open'): ?>
                                    <form class="" id="closeForm<?= $ticket['id'] ?>" method="post"
                                        action="<?= base_url('/tickets/close/' . $ticket['id']) ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="closeticket" value="<?= esc($ticket['id']) ?>">
                                        <button type="button" class="btn btn-info btn-sm"
                                            onclick="submitForm('closeForm<?= $ticket['id'] ?>')">Close Ticket</button>
                                    </form>
                                <?php else: ?>
                                    <form class="" id="reopenForm<?= $ticket['id'] ?>" method="post"
                                        action="<?= base_url('/tickets/reopen/' . $ticket['id']) ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="reopenticket" value="<?= esc($ticket['id']) ?>">
                                        <button type="button" class="btn btn-warning btn-sm"
                                            onclick="submitForm('reopenForm<?= $ticket['id'] ?>')">Reopen Ticket</button>
                                    </form>
                                <?php endif; ?>
                            </div>-->
                        <?php endif; ?>
            <div class="replies">
                <?php if (!empty($replies) && is_array($replies)): ?>
                    <?php foreach ($replies as $reply): ?>
                        <div class="reply rounded shadow p-3 mb-3">
                            <p><?= esc($reply['description']) ?></p>
                            <p class="text-muted">
                        <small><?= date('M d, Y - h:i A', strtotime($reply['created_at'])) ?> | <i class="bi bi-person"></i> : <?= esc($userModel->getFullNameById($reply['user_id'])) ?></small>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No replies for this ticket.</p>
                <?php endif; ?>
            </div>
            <?php if ($ticket['status'] !== 'closed'): ?>
                <form method="POST" action="<?= base_url('admin/tickets/post-reply') ?>" class="replyForm mt-3">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                    <input type="hidden" name="ticket_id" value="<?= esc($ticket['id']) ?>">
                    <div class="form-group">
                        <label for="reply_content<?= $ticket['id'] ?>">Enter your reply</label>
                        <textarea class="form-control" required name="reply_content" id="reply_content<?= $ticket['id'] ?>" rows="3"
                                  placeholder="Type your reply here"></textarea>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Submit Reply</button>
                </form>

            <?php endif; ?>
            <a class="btn btn-sm btn-info mt-3" href="<?= base_url('admin/home')?>">Home</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

<script>
    $(document).ready(function () {
        $('.replyForm').on('submit', function (event) {
            event.preventDefault();

            var form = $(this);
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 1) {
                        toastr.success(response.msg);
                        location.reload();
                    } else {
                        toastr.error(response.msg);
                    }
                },
                error: function () {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>


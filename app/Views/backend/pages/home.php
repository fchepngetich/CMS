<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <?php if (!empty($tickets) && is_array($tickets)): ?>
        <?php foreach ($tickets as $ticket): ?>
            <div class="rounded shadow card mb-5">
                <h5 class="card-header">
                    <?= $ticket['subject'] ?>
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="card-text text-muted">
                                <span>Status:</span>
                                <?= $ticket['status'] ?> |
                                <span>Date:</span>
                                <?= date('M d, Y - h:i A', strtotime($ticket['created_at'])) ?> |
                                <span>Replies:</span>
                                <?= count(array_filter($replies, fn($reply) => $reply['ticket_id'] === $ticket['id'])) ?>
                            </p>
                        </div>
                        <?php if (\App\Libraries\CIAuth::role() !== 'default'): ?>
                            <div class="col-md-4">
                                <?php if ($ticket['status'] === 'open'): ?>
                                    <form class="pull-right" id="closeForm<?= $ticket['id'] ?>" method="post"
                                        action="<?= base_url('/tickets/close/' . $ticket['id']) ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="closeticket" value="<?= $ticket['id'] ?>">
                                        <button type="button" class="btn btn-info"
                                            onclick="submitForm('closeForm<?= $ticket['id'] ?>')">Close Ticket</button>
                                    </form>
                                <?php else: ?>
                                    <form class="pull-right" id="reopenForm<?= $ticket['id'] ?>" method="post"
                                        action="<?= base_url('/tickets/reopen/' . $ticket['id']) ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="reopenticket" value="<?= $ticket['id'] ?>">
                                        <button type="button" class="btn btn-warning"
                                            onclick="submitForm('reopenForm<?= $ticket['id'] ?>')">Reopen Ticket</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                    <?php endif; ?>

                </div>
                <p class="ticket-description" id="ticketDescription<?= $ticket['id'] ?>">
                    <?= $ticket['description'] ?>
                </p>
                <div class="full-content" id="fullContent<?= $ticket['id'] ?>" style="display: none;">
                    <p class="full-description">
                        <?= $ticket['description'] ?>
                    </p>
                    <div class="replies" id="replies<?= $ticket['id'] ?>">
                        <?php
                        $ticketReplies = array_filter($replies, fn($reply) => $reply['ticket_id'] === $ticket['id']);

                        if (!empty($ticketReplies) && is_array($ticketReplies)): ?>
                            <?php foreach ($ticketReplies as $reply): ?>
                                <?php
                                $replyClass = ($reply['user_id'] == $ticket['user_id']) ? 'ticket-creator-reply' : 'other-reply';
                                ?>
                                <div class="reply rounded shadow p-3 mb-3 <?= $replyClass ?>">
                                    <p>posted by: <?= $userModel->getFullNameById($reply['user_id']) ?></p>
                                    <p><?= $reply['description'] ?></p>
                                    <p class="text-muted"><small><?= date('M d, Y - h:i A', strtotime($reply['created_at'])) ?></small>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No replies for this ticket.</p>
                        <?php endif; ?>
                    </div>
                    <?php if ($ticket['status']!=='closed'): ?>
                    <form method="POST" action="<?= route_to('post-reply') ?>" class="replyForm mt-3">
                        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                        <div class="form-group">
                            <label for="reply_content<?= $ticket['id'] ?>">Enter your reply</label>
                            <textarea class="form-control" required name="reply_content" id="reply_content<?= $ticket['id'] ?>"
                                rows="3" placeholder="Type your reply here"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Reply</button>
                    </form>
                    <?php endif ?>

                </div>
                <a href="#" class="read-more-link mt-5" data-ticket-id="<?= $ticket['id'] ?>">Read More</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No tickets found.</p>
<?php endif; ?>
</div>

<?= $this->section('stylesheets') ?>
<style>
    .ticket-creator-reply {
        text-align: left;
        background-color: #e1ffc7;
        margin: 10px;
        border-radius: 10px;
        padding: 10px;
        max-width: 60%;
    }

    .other-reply {
        text-align: right;
        background-color: #fff;
        margin: 10px;
        border-radius: 10px;
        padding: 10px;
        max-width: 60%;
        float: right;
    }
</style>
<?= $this->endSection() ?>

<script>

    function submitForm(formId) {
        document.getElementById(formId).submit();
    }

    document.querySelectorAll('.read-more-link').forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const ticketId = this.getAttribute('data-ticket-id');
            const fullContent = document.getElementById('fullContent' + ticketId);
            const ticketDescription = document.getElementById('ticketDescription' + ticketId);
            console.log(ticketId);

            if (fullContent.style.display === 'none') {
                fullContent.style.display = 'block';
                ticketDescription.style.display = 'none';
                this.textContent = 'Read Less';
            } else {
                fullContent.style.display = 'none';
                ticketDescription.style.display = 'block';
                this.textContent = 'Read More';
            }
        });
    });

    document.querySelectorAll('.replyForm').forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 1) {
                        toastr.success(data.msg);

                        const ticketId = formData.get('ticket_id');
                        const repliesSection = document.getElementById('replies' + ticketId);
                        const replyContent = formData.get('reply_content');
                        const userId = "<?php use App\Libraries\CIAuth;
                        CIAuth::id() ?>";

                        // Determine the class for the new reply
                        const replyClass = (userId == <?= $ticket['user_id'] ?>) ? 'ticket-creator-reply' : 'other-reply';

                        const replyHTML = `
                        <div class="reply mb-3 ${replyClass}">
                            <p><b>User ID:</b> ${userId}</p>
                            <p>${replyContent}</p>
                            <p class="text-muted"><small>Just now</small></p>
                        </div>
                    `;

                        repliesSection.insertAdjacentHTML('beforeend', replyHTML);

                        // Reset the form
                        form.reset();
                    } else {
                        toastr.error(data.msg);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('An error occurred. Please try again.');
                });
        });
    });
</script>

<?= $this->endSection() ?>
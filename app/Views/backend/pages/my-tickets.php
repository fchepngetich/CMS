<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<?php if (!empty($tickets) && is_array($tickets)): ?>
    <?php foreach ($tickets as $ticket): ?>
        <div class="rounded shadow card mb-5">
            <h5 class="card-header">
                <?= esc($ticket['subject']) ?>
            </h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="card-text text-muted">
                            <span>Status:</span>
                            <?= esc($ticket['status']) ?> |
                            <span>Date:</span>
                            <?= date('M d, Y - h:i A', strtotime($ticket['created_at'])) ?> |
                            <span>Replies:</span>
                            <?= count(array_filter($replies, fn($reply) => $reply['ticket_id'] === $ticket['id'])) ?>
                        </p>
                    </div>
                    <?php if (\App\Libraries\CIAuth::role() !== 'default'): ?>
                        <div class="col-md-4">
                            <form method="post" action="">
                                <input type="hidden" name="closeticket" value="<?= esc($ticket['id']) ?>">
                                <button type="button" class="btn btn-info"
                                    onclick="submitForm('closeForm<?= $ticket['id'] ?>')">Close Ticket</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
                <p class="ticket-description" id="ticketDescription<?= $ticket['id'] ?>">
                    <?= esc($ticket['description']) ?>
                </p>
                <div class="full-content" id="fullContent<?= $ticket['id'] ?>" style="display: none;">
                    <p class="full-description">
                        <?= esc($ticket['description']) ?>
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
                                    <p>posted by: <?= esc($userModel->getFullNameById($reply['user_id'])) ?></p>
                                    <p><?= esc($reply['description']) ?></p>
                                    <p class="text-muted"><small><?= date('M d, Y - h:i A', strtotime($reply['created_at'])) ?></small>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No replies for this ticket.</p>
                        <?php endif; ?>
                    </div>
                    <?php if (\App\Libraries\CIAuth::role() !== 'default'): ?>
                        <?php if ($ticket['status']!=='closed'): ?>

                        <form method="POST" action="<?= route_to('post-reply') ?>" class="replyForm mt-3"
                            id="form<?= $ticket['id'] ?>">
                            <input type="hidden" name="ticket_id" value="<?= esc($ticket['id']) ?>">
                            <div class="form-group">
                                <label for="reply_content<?= $ticket['id'] ?>">Enter your reply</label>
                                <textarea class="form-control" required name="reply_content" id="reply_content<?= $ticket['id'] ?>"
                                    rows="3" placeholder="Type your reply here"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Reply</button>
                        </form>
                    <?php endif; ?>
                    <?php endif; ?>

                </div>
                <a href="#" class="read-more-link mt-5" data-ticket-id="<?= $ticket['id'] ?>">Read More</a>
            </div>
        </div>

        <script>


            document.querySelector('.replyForm#form<?= $ticket['id'] ?>').addEventListener('su bmit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                const ticketId = formData.get('ticket_id');
                const replyContent = formData.get('reply_content');
                const replyClass = (loggedInUserId == ticketUserId_<?= $ticket['id'] ?>) ? 'ticket-creator-reply' : 'other-reply';

                const replyHTML = `
                                <div class="reply mb-3 ${replyClass}">
                                    <p><b>User ID:</b> ${loggedInUserId}</p>
                                    <p>${replyContent}</p>
                                    <p class="text-muted"><small>Just now</small></p>
                                </div>
                            `;

                document.getElementById('replies<?= $ticket['id'] ?>').insertAdjacentHTML('beforeend', replyHTML);

                this.reset();
            });
        </script>
    <?php endforeach; ?>
<?php else: ?>
    <div class="rounded shadow card mb-5">
        <div class="card-body">
            <p>No tickets found.</p>
        </div>
    </div>
<?php endif; ?>


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
<?= $this->endSection() ?>
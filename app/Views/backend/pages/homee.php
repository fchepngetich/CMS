<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <?php if (!empty($tickets) && is_array($tickets)): ?>
        <?php foreach ($tickets as $ticket): ?>
            <div class="rounded shadow card mb-5">
                <h5 class="card-header">
                    <a href="#" class="ticket-title-link" data-ticket-id="<?= $ticket['id'] ?>">
                        <?= esc($ticket['subject']) ?>
                    </a>
                </h5>
                <div class="card-body">
                    <div class="ticket-summary">
                        <p class="card-text text-muted mb-2">
                            <span>Status:</span>
                            <?= esc($ticket['status']) ?> |
                            <span>Date:</span>
                            <?= date('M d, Y - h:i A', strtotime($ticket['created_at'])) ?> |
                            <span>Replies:</span>
                            <?= count(array_filter($replies, fn($reply) => $reply['ticket_id'] === $ticket['id'])) ?> |
                            <?php if (\App\Libraries\CIAuth::role() !== '4'): ?>
                                <?php if (!empty($ticket['assigned_to'])): ?>
                                    Agent: <?= esc(getUsernameById($ticket['assigned_to'])) ?>
                                <?php else: ?>
                                    Agent: Not Assigned
                                <?php endif; ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="ticket-full-content" id="fullContent<?= $ticket['id'] ?>" style="display: none;">
                        <p class="ticket-description"><?= esc($ticket['description']) ?></p>
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
                                        <p class="text-muted">
                                            <small><?= date('M d, Y - h:i A', strtotime($reply['created_at'])) ?></small></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No replies for this ticket.</p>
                            <?php endif; ?>
                        </div>
                        <?php if ($ticket['status'] !== 'closed'): ?>
                            <form method="POST" action="<?= route_to('post-reply') ?>" class="replyForm mt-3">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                                <input type="hidden" name="ticket_id" value="<?= esc($ticket['id']) ?>">
                                <div class="form-group">
                                    <label for="reply_content<?= $ticket['id'] ?>">Enter your reply</label>
                                    <textarea class="form-control" required name="reply_content" id="reply_content<?= $ticket['id'] ?>" rows="3" placeholder="Type your reply here"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Reply</button>
                            </form>
                        <?php endif; ?>
                        <a href="#" class="read-less-link btn-sm mt-2" data-ticket-id="<?= $ticket['id'] ?>">Read Less</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tickets found.</p>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.ticket-title-link').forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const ticketId = this.getAttribute('data-ticket-id');
                const fullContent = document.getElementById('fullContent' + ticketId);

                fullContent.style.display = fullContent.style.display === 'none' ? 'block' : 'none';
            });
        });

        document.querySelectorAll('.read-less-link').forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const ticketId = this.getAttribute('data-ticket-id');
                const fullContent = document.getElementById('fullContent' + ticketId);

                fullContent.style.display = 'none';
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
                        const userId = "<?= \App\Libraries\CIAuth::id() ?>";

                        const replyClass = (userId == <?= $ticket['user_id'] ?>) ? 'ticket-creator-reply' : 'other-reply';

                        
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
    });
</script>

<?= $this->endSection() ?>

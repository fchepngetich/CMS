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
                        <p class="card-text text-muted mb-2">
                            <span>Status:</span>
                            <?= esc($ticket['status']) ?> |
                            <span>Date:</span>
                            <?= date('M d, Y - h:i A', strtotime($ticket['created_at'])) ?> |
                            <span>Replies:</span>
                            <?= count(array_filter($replies, fn($reply) => $reply['ticket_id'] === $ticket['id'])) ?>
                        </p>
                    </div>  
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
                                <div class="reply row rounded shadow p-3 mb-3 <?= $replyClass ?>">
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
                    <form method="POST" action="<?= route_to('post-reply') ?>" class="replyForm mt-3" id="form<?= $ticket['id'] ?>">
                        <input type="hidden" name="ticket_id" value="<?= esc($ticket['id']) ?>">
                        <div class="form-group">
                    <label for="reply_content<?= $ticket['id'] ?>">Enter your reply</label>
                <textarea class="form-control" required name="reply_content" id="reply_content<?= $ticket['id'] ?>" rows="3" placeholder="Type your reply here"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Reply</button>
                    </form>
                        <?php endif; ?>
                    <?php endif; ?>
                            <div class="row">
                                <div class="col-md-2">
                                <a href="#" class="read-less-link" data-ticket-id="<?= $ticket['id'] ?>">Read Less</a>

                                </div>
                            </div>
                </div>
                <div class="row">
                                <div class="col-md-2">
                                <a href="#" class="read-more-link" data-ticket-id="<?= $ticket['id'] ?>">Read More</a>
                                </div>
                            </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.read-more-link').forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        const ticketId = this.getAttribute('data-ticket-id');
                        const fullContent = document.getElementById(`fullContent${ticketId}`);
                        const ticketDescription = document.getElementById(`ticketDescription${ticketId}`);
                        if (fullContent.style.display === 'none') {
                            fullContent.style.display = 'block';
                            ticketDescription.style.display = 'none';
                            this.style.display = 'none';
                        }
                    });
                });

            

                document.querySelectorAll('.read-less-link').forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        const ticketId = this.getAttribute('data-ticket-id');
                        const fullContent = document.getElementById(`fullContent${ticketId}`);
                        const ticketDescription = document.getElementById(`ticketDescription${ticketId}`);
                        if (fullContent.style.display === 'block') {
                            fullContent.style.display = 'none';
                            ticketDescription.style.display = 'block';
                            document.querySelector(`.read-more-link[data-ticket-id="${ticketId}"]`).style.display = 'block';
                        }
                    });
                });

                document.querySelectorAll('.replyForm').forEach(form => {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault();
                        const formData = new FormData(this);
                        const ticketId = formData.get('ticket_id');
                        const replyContent = formData.get('reply_content');
                        const replyClass = (loggedInUserId == ticketUserId) ? 'ticket-creator-reply' : 'other-reply';

                        const replyHTML = `
                            <div class="reply row rounded shadow p-3 mb-3 ${replyClass}">
                                <p><b>User ID:</b> ${loggedInUserId}</p>
                                <p>${replyContent}</p>
                                <p class="text-muted"><small>Just now</small></p>
                            </div>
                        `;

                        document.getElementById('replies' + ticketId).insertAdjacentHTML('beforeend', replyHTML);
                        this.reset();
                    });
                });
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


<?= $this->endSection() ?>
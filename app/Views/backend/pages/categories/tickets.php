<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                    <h4><?= htmlspecialchars($category['name']) ?></h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/home') ?>">Home</a>
                    </li>
                   
                </ol>
            </nav>
        </div>
        
    </div>
</div>
    <form method="POST" action="<?= base_url('admin/tickets/search-tickets') ?>" class="mb-4">
        <?= csrf_field() ?>
            <input type="hidden" name="category_id" value="<?= esc($category['id'] ?? '') ?>">

        <div class="row">
            <div class="col-md-4">
                <input type="text" name="title" class="form-control" placeholder="Title" value="<?= esc($title ?? '') ?>">
            </div>
            <div class="col-md-4">
                <input type="date" name="start_date" id="start_date" class="form-control" placeholder="Start Date" max="<?= date('Y-m-d') ?>" value="<?= esc($start_date ?? '') ?>">
            </div>
            <div class="col-md-4">
                <input type="date" name="end_date" id="end_date" class="form-control" placeholder="End Date" max="<?= date('Y-m-d') ?>" value="<?= esc($end_date ?? '') ?>">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <select name="status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="open" <?= (isset($status) && $status == 'open') ? 'selected' : '' ?>>Open</option>
                    <option value="closed" <?= (isset($status) && $status == 'closed') ? 'selected' : '' ?>>Closed</option>
                    <option value="re-opened" <?= (isset($status) && $status == 're-opened') ? 'selected' : '' ?>>Re-opened</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-sm btn-info">Search</button>
                <a href="<?= base_url('admin/home') ?>" class="btn btn-sm btn-info ml-2">Reset</a>
            </div>
        </div>
    </form>

    <?php if (!empty($tickets) && is_array($tickets)): ?>
        <?php foreach ($tickets as $ticket): ?>
            <div class="rounded shadow card mb-5">
                <h5 class="card-header">
                    <?= esc($ticket['subject']) ?>
                </h5>
                <div class="card-body">
                    <p class="ticket-description" id="ticketDescription<?= $ticket['id'] ?>">
                        <?= esc(substr($ticket['description'], 0, 240)) ?>...
                        <div class="row">
                            <div class="col-md-8">
                                <p class="card-text text-muted mb-2"><small>
                                    <span>Status:</span>
                                    <?= esc($ticket['status']) ?> |
                                    <?= date('M d, Y ', strtotime($ticket['created_at'])) ?> |
                                    <span>Replies:</span>
                                    <?= count(array_filter($replies, fn($reply) => $reply['ticket_id'] === $ticket['id'])) ?> |
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

                                    <?php endif; ?>
                                </small></p>
                            </div>
                            <?php if (\App\Libraries\CIAuth::role() !== '2' && \App\Libraries\CIAuth::role() !== '4'): ?>
                                <div class="col-md-4 text-right">
                                    <?php if ($ticket['status'] === 'open'): ?>
                                        <form id="closeForm<?= $ticket['id'] ?>" method="post" action="<?= base_url('admin/tickets/close/' . $ticket['id']) ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="closeticket" value="<?= esc($ticket['id']) ?>">
                                            <button type="button" class="btn btn-info btn-sm" onclick="confirmClose(<?= $ticket['id'] ?>)">Close Ticket</button>
                                            </form>
                                    <?php else: ?>
                                        <form id="reopenForm<?= $ticket['id'] ?>" method="post" action="<?= base_url('admin/tickets/reopen/' . $ticket['id']) ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="reopenticket" value="<?= esc($ticket['id']) ?>">
                                            <button type="button" class="btn btn-warning btn-sm" onclick="confirmReopen(<?= $ticket['id'] ?>)">Reopen Ticket</button>

                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </p>
                    <div class="row">
                        <div class="col-md-2">
                            <a href="<?= base_url('admin/tickets/ticket-details/' . $ticket['id']) ?>" class="btn btn-primary btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tickets found.</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var start_date_input = document.getElementById('start_date');
        var end_date_input = document.getElementById('end_date');

        if (!('placeholder' in start_date_input)) {
            start_date_input.value = 'Start Date';
            end_date_input.value = 'End Date';

            start_date_input.addEventListener('focus', function() {
                if (this.value === 'Start Date') {
                    this.value = '';
                }
            });

            start_date_input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.value = 'Start Date';
                }
            });

            end_date_input.addEventListener('focus', function() {
                if (this.value === 'End Date') {
                    this.value = '';
                }
            });

            end_date_input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.value = 'End Date';
                }
            });
        }
    });

  
function confirmClose(ticketId) {
    if (confirm("Are you sure you want to close this ticket?")) {
        submitForm('closeForm' + ticketId);
    }
}

function confirmReopen(ticketId) {
    if (confirm("Are you sure you want to reopen this ticket?")) {
        submitForm('reopenForm' + ticketId);
    }
}

   function submitForm(formId) {
        document.getElementById(formId).submit();
    }
</script>
<?= $this->endSection() ?>

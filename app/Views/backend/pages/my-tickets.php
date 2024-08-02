<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<div class="container">
<form method="POST" action="<?= base_url('admin/tickets/my-tickets-search') ?>" class="mb-4">
    <?= csrf_field() ?>
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="title" class="form-control" placeholder="Title" value="<?= esc($title ?? '') ?>">
        </div>
        
        <div class="col-md-4">
            <input type="date" name="start_date" class="form-control" placeholder="Start Date" max="<?= date('Y-m-d') ?>" value="<?= esc($start_date ?? '') ?>">
        </div>
        <div class="col-md-4">
            <input type="date" name="end_date" class="form-control" placeholder="End Date" max="<?= date('Y-m-d') ?>" value="<?= esc($end_date ?? '') ?>">
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
            <span><a href="<?= base_url('admin/tickets/my-tickets')?>" class="btn btn-sm btn-info">Reset</a></span>
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

                    </p>
                <div class="row">
                    <div class="col-md-12">
                        <p class="card-text text-muted mb-2"><small>
                            <span>Status:</span>
                            <?= esc($ticket['status']) ?> |
                            <span></span>
                            <?= date('M d, Y - h:i A', strtotime($ticket['created_at'])) ?> |
                            <span>Replies:</span>
                            <?= count(array_filter($replies, fn($reply) => $reply['ticket_id'] === $ticket['id'])) ?>
                            </small>
                        </p>
                    </div>  
                </div>
        
                <div class="row">
                    <div class="col-md-2">
                        <a href="<?= base_url('admin/tickets/ticket-details/' . $ticket['id']) ?>" class="btn btn-primary btn-sm">Read More</a>
                    </div>
                </div>
        </div></div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="rounded shadow card mb-5">
        <div class="card-body">
            <p>No tickets found.</p>
        </div>
    </div>
<?php endif; ?>


<?= $this->endSection() ?>
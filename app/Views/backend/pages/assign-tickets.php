<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="assign-ticket-box bg-white box-shadow border-radius-10 p-4">
    <div class="assign-ticket-title">
        <h2 class="text-center">Assign Ticket</h2>
    </div>
    <form action="<?= route_to('assign_ticket') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="ticket">Ticket</label>
            <select class="form-control" id="ticket" name="ticket_id" required>
                <?php foreach ($tickets as $ticket): ?>
                    <option value="<?= $ticket['id'] ?>"><?= $ticket['subject'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="agent">Assign to Agent</label>
            <select class="form-control" id="agent" name="agent_id" required>
                <?php foreach ($agents as $agent): ?>
                    <option value="<?= $agent['id'] ?>"><?= $agent['full_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Assign Ticket</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

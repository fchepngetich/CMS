<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>System Logs</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        View Logs
                    </li>
                </ol>
            </nav>
        </div>
    <table class="table table-striped">
        <thead>
        <?php if (!empty($logs) && is_array($logs)): ?>

            <tr>
                <th>#</th>
                <th>Message</th>
                <th>User</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
                <?php $count=1; foreach ($logs as $log): ?>
                    <tr>
                        <td><?= esc($count++) ?></td>
                        <td><?= esc($log['message']) ?></td>
                        <td><?= esc(getUsernameById($log['user_id'])) ?></td>
                        <td><?= esc($log['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No logs found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>

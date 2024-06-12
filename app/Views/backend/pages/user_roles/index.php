<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Manage Roles</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= route_to('admin.home') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Manage Roles
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#user-modal">
                    <a href="/userroles/create">Add New Role</a>
                </button>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Role Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $count=1; foreach ($roles as $role): ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><?= $role['name'] ?></td>
                    <td>
                        <a href="/userroles/edit/<?= $role['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="/userroles/delete/<?= $role['id'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
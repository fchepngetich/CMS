<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>



    <div class="container mt-5">
        <h2 class="mb-4">Roles Management</h2>
        <table id="role-table" class="table table-sm table-striped table-borderless" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables will populate the table body -->
            </tbody>
        </table>
    </div>

   




<?= $this->endSection() ?>

<?= $this->section('stylesheets')?>

<link rel="stylesheet" href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.3/jquery-ui.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.3/jquery-ui.structure.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.13.3/jquery-ui.theme.min.css">

<?= $this->endSection()?>

<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="/backend/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="/extra-assets/jquery-ui-1.13.3/jquery-ui.min.js"></script>
<script>
        $(document).ready(function() {
            $('#role-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?= route_to('admin.roles.getRoles') ?>",
                    "type": "GET",
                    "dataSrc": function(json) {
                        console.log(json); // Log the entire response to debug
                        if (json.data) {
                            return json.data;
                        } else {
                            alert('Error: ' + (json.message || 'Unknown error'));
                            return [];
                        }
                    }
                },
                "columns": [
                    { "data": "id" },
                    { "data": "name" },
                    { "data": "description" }
                ],
                "language": {
                    "emptyTable": "No roles available"
                },
                "pagingType": "simple_numbers",
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "pageLength": 10,
                "responsive": true,
                "autoWidth": false,
                "dom": '<"top"lf>rt<"bottom"ip><"clear">'
            });
        });
    </script>





<?= $this->endSection()?>

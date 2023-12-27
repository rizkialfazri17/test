<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Logs</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url("/admin/dashboard"); ?>">Home</a></li>
                <li class="breadcrumb-item">Superadmin</li>
                <li class="breadcrumb-item active">Logs</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Filter</h5>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="filterTableListLogByUsername" class="form-label">Username</label>
                                <input class="form-control" list="dataListOptionUsername" id="filterTableListLogByUsername" name="filterTableListLogByUsername" placeholder="Search by Username">
                                <datalist id="dataListOptionUsername">
                                    <?php foreach ($getListUsername as $row) : ?>
                                        <option value="<?= $row['username']; ?>"></option>
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="filterTableListLogByIp" class="form-label">IP Address</label>
                                <input class="form-control" list="dataListOptionIp" id="filterTableListLogByIp" name="filterTableListLogByIp" placeholder="Search by IP Address">
                                <datalist id="dataListOptionIp">
                                    <?php foreach ($getListIp as $row) : ?>
                                        <option value="<?= $row['ip_address']; ?>"></option>
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="filterTableListLogByAction" class="form-label">Action</label>
                                <select id="filterTableListLogByAction" name="filterTableListLogByAction" class="form-select shadow-none">
                                    <option value="">-- Select Action --</option>
                                    <?php foreach ($getListAction as $row) : ?>
                                        <option value="<?= $row['action']; ?>"><?= $row['action']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label for="filterTableListLogByDate" class="form-label">Filter By Date</label>
                                <input type="text" class="form-control" id="filterTableListLogByDate" name="filterTableListLogByDate" value="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Table List Logs</h5>
                        <div class="table-responsive">
                            <table id="tableListLog" class="table table-bordered table-hover small dataTable" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col" width="10%">Username</th>
                                        <th scope="col" width="15%">Action</th>
                                        <th scope="col">Description</th>
                                        <th scope="col" width="10%">IP Address</th>
                                        <th scope="col" width="15%">Date Time</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<input type="hidden" id="csrfProtection" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
<!-- Script -->
<script type="text/javascript">
    $(document).ready(function() {
        let tableListLog = $("#tableListLog").DataTable({
            "processing": true,
            "serverSide": true,
            "serverMethod": "POST",
            "searching": true,
            "deferRender": true,
            "ajax": {
                "url": base_url + 'admin/logs/table',
                "data": function(data) {
                    // CSRF
                    let csrfName = $("#csrfProtection").attr('name');
                    let csrfHash = $("#csrfProtection").val();

                    // custom filter values
                    let filterByUsername = $("#filterTableListLogByUsername").val();
                    let filterByIp = $("#filterTableListLogByIp").val();
                    let filterByAction = $("#filterTableListLogByAction").val();
                    let filterByDate = $("#filterTableListLogByDate").val();

                    data.searchByUsername = filterByUsername;
                    data.searchByIp = filterByIp;
                    data.searchByAction = filterByAction;
                    data.searchByDate = filterByDate;
                    return {
                        data: data,
                        [csrfName]: csrfHash // CSRF Token
                    };
                },
                dataSrc: function(data) {
                    // Update token hash
                    $("#csrfProtection").val(data.token);

                    // Datatable data
                    return data.aaData;
                }
            },
            "dom": '<"row mb-3"<"col-md-3 mt-1 mb-2"l><"col-md-6 text-center align-center"B><"col-md-3 mt-1"f>>' +
                '<"row"<"col-md-12"tr>>' +
                '<"row"<"col-md-5"i><"col-md-7"p>>',
            "buttons": [
                'copy',
                'excel',
                'csv',
                'pdf',
                'print',
            ],
            "oLanguage": {
                "sLengthMenu": "Show: _MENU_",
            },
            "aLengthMenu": [
                [10, 25, 50, 100, 500, -1],
                [10, 25, 50, 100, 500, "All"]
            ],
            "pageLength": 10,
            "paging": true,
            "ordering": true,
            "order": [
                [5, 'desc']
            ],
            "columnDefs": [{
                "targets": [0, 1, 2, 3, 4, 5],
                "className": "align-middle",
            }, {
                "targets": [0],
                "orderable": false,
            }, {
                "targets": [0, 2],
                "className": "text-center",
            }],
            "createdRow": function(row, data, dataIndex) {
                if (data["level"] == 1) {
                    $(row).css("background-color", "rgb(0,200,255,0.1)");
                } else if (data["level"] == 2) {
                    $(row).css("background-color", "rgb(0,255,0,0.1)");
                } else if (data["level"] == 3) {
                    $(row).css("background-color", "rgb(255,255,0,0.1)");
                } else if (data["level"] == 4) {
                    $(row).css("background-color", "rgb(255,0,0,0.1)");
                } else {
                    $(row).css("background-color", "transparent");
                }
            },
            "columns": [{
                "data": null,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1 + '.';
                }
            }, {
                "data": "username"
            }, {
                "data": "action"
            }, {
                "data": "description"
            }, {
                "data": "ip_address"
            }, {
                "data": "date_time"
            }, ]
        });

        // custom filter
        $("#filterTableListLogByUsername").change(function() {
            tableListLog.draw();
        });

        $("#filterTableListLogByIp").change(function() {
            tableListLog.draw();
        });

        $("#filterTableListLogByAction").change(function() {
            tableListLog.draw();
        });

        $("#filterTableListLogByDate").daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        $("#filterTableListLogByDate").on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
            tableListLog.draw();
        });
        $("#filterTableListLogByDate").on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            tableListLog.draw();
        });
    });
</script>
<?= $this->endSection(); ?>
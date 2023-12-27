<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= lang('Pages.my_history.title'); ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard'); ?>"><?= lang('Menu.home'); ?></a></li>
                <li class="breadcrumb-item"><?= lang('Menu.user'); ?></li>
                <li class="breadcrumb-item active"><?= lang('Menu.my_history'); ?></li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-xxl-3 col-lg-6">
                <div class="card info-card card-info">
                    <div class="card-body">
                        <h5 class="card-title"><?= lang('Pages.my_history.total_activity'); ?></h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-card-list"></i>
                            </div>
                            <div class="text-end">
                                <h6><?= $getCountAll; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-lg-6">
                <div class="card info-card card-success">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" id="filterCountActivity">
                            <li class="dropdown-header text-start">
                                <h6><?= lang('Global.filter'); ?></h6>
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);"><?= lang('Global.today'); ?></a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);"><?= lang('Global.yesterday'); ?></a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);"><?= lang('Global.this_week'); ?></a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);"><?= lang('Global.this_month'); ?></a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= lang('Pages.my_history.activity'); ?> <span id="resultFilterDayActivity">| <?= lang('Global.today'); ?></span></h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-card-checklist"></i>
                            </div>
                            <div class="text-end">
                                <h6 id="resultCountActivity"><?= $getCountToday; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= lang('Pages.my_history.filter_list_history'); ?></h5>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label for="filterTableListHistoryByAction" class="form-label"><?= lang('Global.action'); ?></label>
                                <select id="filterTableListHistoryByAction" name="filterTableListHistoryByAction" class="form-select shadow-none">
                                    <option value="">-- <?= lang('Global.select'); ?> --</option>
                                    <?php foreach ($getListAction as $row) : ?>
                                        <option value="<?= $row['action']; ?>"><?= $row['action']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="filterTableListHistoryByDate" class="form-label"><?= lang('Global.filter_by_date'); ?></label>
                                <input type="text" class="form-control" id="filterTableListHistoryByDate" name="filterTableListHistoryByDate" value="" />
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
                        <h5 class="card-title"><?= lang('Pages.my_history.my_history_list'); ?></h5>
                        <div class="table-responsive">
                            <table id="tableListHistory" class="table table-bordered table-hover small dataTable" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col" width="15%"><?= lang('Global.action'); ?></th>
                                        <th scope="col"><?= lang('Global.description'); ?></th>
                                        <th scope="col" width="10%"><?= lang('Global.ip_address'); ?></th>
                                        <th scope="col" width="15%"><?= lang('Global.time'); ?></th>
                                        <th scope="col" width="10%"><?= lang('Global.action'); ?></th>
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
        let tableListHistory = $("#tableListHistory").DataTable({
            "processing": true,
            "serverSide": true,
            "serverMethod": "POST",
            "searching": true,
            "deferRender": true,
            "ajax": {
                "url": base_url + 'admin/my-history',
                "data": function(data) {
                    // CSRF
                    let csrfName = $("#csrfProtection").attr('name');
                    let csrfHash = $("#csrfProtection").val();

                    // custom filter values
                    let filterByAction = $("#filterTableListHistoryByAction").val();
                    let filterByDate = $("#filterTableListHistoryByDate").val();

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
            "dom": '<"row mb-3"<"col-md-3 mt-1 mb-2"l><"col-md-6 text-center align-center"B><"col-md-3 mt-1"f>><"row"<"col-md-12"tr>><"row"<"col-md-5"i><"col-md-7"p>>',
            "buttons": [
                'copy',
                {
                    "extend": 'excel',
                    "title": '<?= lang('Pages.my_history.my_history_list') ?>',
                },
                {
                    "extend": 'pdf',
                    "title": '<?= lang('Pages.my_history.my_history_list') ?>',
                },
                {
                    "extend": 'print',
                    "title": '<?= lang('Pages.my_history.my_history_list') ?>',
                },
                // 'csv',
            ],
            "oLanguage": {
                "sLengthMenu": "<?= lang('Datatables.sLengthMenu') . ' : _MENU_'; ?>",
                "sSearch": "<?= lang('Datatables.sSearch') . ' :_INPUT_'; ?>",
                "sZeroRecords": "<?= lang('Datatables.sZeroRecords'); ?>",
                "sInfo": "<?= lang('Datatables.showing') . ' _START_ ' . lang('Datatables.to') . ' _END_ ' . lang('Datatables.of') . ' _TOTAL_ ' . lang('Datatables.entires'); ?>",
                "sInfoFiltered": "(<?= lang('Datatables.filtered_from') . ' _MAX_ ' . lang('Datatables.total_recors'); ?>)",
                "sInfoEmpty": "<?= lang('Datatables.sInfoEmpty'); ?>",
            },
            "language": {
                "paginate": {
                    "next": '<i class="bi bi-arrow-right"></i>',
                    "previous": '<i class="bi bi-arrow-left"></i>'
                }
            },
            "aLengthMenu": [
                [10, 25, 50, 100, 500, -1],
                [10, 25, 50, 100, 500, "<?= lang('Global.all'); ?>"]
            ],
            "pageLength": 10,
            "paging": true,
            "ordering": true,
            "order": [
                [4, 'desc']
            ],
            "columnDefs": [{
                "targets": [0, 1, 2, 3, 4],
                "className": "align-middle",
            }, {
                "targets": [0],
                "orderable": false,
            }, {
                "targets": [0, 1],
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
        $("#filterTableListHistoryByAction").change(function() {
            tableListHistory.draw();
        });

        $("#filterTableListHistoryByDate").daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        $("#filterTableListHistoryByDate").on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
            tableListHistory.draw();
        });
        $("#filterTableListHistoryByDate").on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            tableListHistory.draw();
        });

        // filter count
        $("#filterCountActivity li a").click(function() {
            let filterByDay = $(this).html();
            $("#resultFilterDayActivity").html('| ' + filterByDay);

            if (filterByDay === '<?= lang('Global.yesterday'); ?>') {
                $("#resultCountActivity").html(<?= $getCountYesterday; ?>);
            } else if (filterByDay === '<?= lang('Global.this_week'); ?>') {
                $("#resultCountActivity").html(<?= $getCountThisWeek; ?>);
            } else if (filterByDay === '<?= lang('Global.this_month'); ?>') {
                $("#resultCountActivity").html(<?= $getCountThisMonth; ?>);
            } else {
                $("#resultCountActivity").html(<?= $getCountToday; ?>);
            }
        });
    });
</script>
<?= $this->endSection(); ?>
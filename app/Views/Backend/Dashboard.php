<?= $this->extend('Backend/Layout/Main'); ?>

<?= $this->section('MainContent'); ?>
<main id="main" class="main">
   <div class="pagetitle mb-3">
      <h1><?= lang('Pages.dashboard.title'); ?></h1>
   </div>
   <section class="section dashboard">
      <div class="row mb-2">
         <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible fade show p-3" role="alert">
               <?php $hours = date('H');
               if ($hours < 12) {
                  $greeting = '<i class="bi bi-cloud-sun"></i> ' . lang('Global.good_morning');
               } else if ($hours < 18) {
                  $greeting = '<i class="bi bi-clouds"></i> ' . lang('Global.good_afternoon');
               } else {
                  $greeting = '<i class="bi bi-moon-stars"></i> ' . lang('Global.good_night');
               } ?>
               <?= $greeting . ', ' . (isset($DataAccount) ? $DataAccount['full_name'] : '') . ' !'; ?>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-xxl-3 col-md-6">
            <div class="card info-card card-info">
               <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" id="filterCountVisitors">
                     <li class="dropdown-header text-start">
                        <h6><?= lang('Global.filter'); ?></h6>
                     </li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="today"><?= lang('Global.today'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="yesterday"><?= lang('Global.yesterday'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="this-month"><?= lang('Global.this_month'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="this-year"><?= lang('Global.this_year'); ?></a></li>
                  </ul>
               </div>
               <div class="card-body">
                  <h5 class="card-title"><?= lang('Pages.dashboard.visitors') ?> <span id="titleCountVisitors">| <?= lang('Global.today'); ?></span></h5>
                  <div class="d-flex justify-content-between align-items-center">
                     <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-eye"></i>
                     </div>
                     <div class="text-end">
                        <h6 id="resultCountVisitors"><?= $countVisitorsToday; ?></h6>
                        <div id="resultStatsVisitors"><?= $statsVisitorsToday; ?></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-3 col-md-6">
            <div class="card info-card card-danger">
               <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                     <li class="dropdown-header text-start">
                        <h6><?= lang('Global.filter'); ?></h6>
                     </li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="today"><?= lang('Global.today'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="yesterday"><?= lang('Global.yesterday'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="this-month"><?= lang('Global.this_month'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="this-year"><?= lang('Global.this_year'); ?></a></li>
                  </ul>
               </div>
               <div class="card-body">
                  <h5 class="card-title">Orders <span>| Today</span></h5>
                  <div class="d-flex justify-content-between align-items-center">
                     <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cash-coin"></i>
                     </div>
                     <div class="text-end">
                        <h6>IDR 0</h6>
                        <span class="text-success small pt-1 fw-bold">0%</span>
                        <span class="text-muted small pt-2 ps-1">Increase</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-3 col-md-6">
            <div class="card info-card card-success">
               <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                     <li class="dropdown-header text-start">
                        <h6><?= lang('Global.filter'); ?></h6>
                     </li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="today"><?= lang('Global.today'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="yesterday"><?= lang('Global.yesterday'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="this-month"><?= lang('Global.this_month'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="this-year"><?= lang('Global.this_year'); ?></a></li>
                  </ul>
               </div>
               <div class="card-body">
                  <h5 class="card-title">Income <span>| Today</span></h5>
                  <div class="d-flex justify-content-between align-items-center">
                     <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cash-coin"></i>
                     </div>
                     <div class="text-end">
                        <h6>IDR 0</h6>
                        <span class="text-success small pt-1 fw-bold">0%</span>
                        <span class="text-muted small pt-2 ps-1">Increase</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xxl-3 col-md-6">
            <div class="card info-card card-warning">
               <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                     <li class="dropdown-header text-start">
                        <h6><?= lang('Global.filter'); ?></h6>
                     </li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="today"><?= lang('Global.today'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="yesterday"><?= lang('Global.yesterday'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="this-month"><?= lang('Global.this_month'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="this-year"><?= lang('Global.this_year'); ?></a></li>
                  </ul>
               </div>
               <div class="card-body">
                  <h5 class="card-title">New User <span>| Today</span></h5>
                  <div class="d-flex justify-content-between align-items-center">
                     <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                     </div>
                     <div class="text-end">
                        <h6>0</h6>
                        <span class="text-success small pt-1 fw-bold">0%</span>
                        <span class="text-muted small pt-2 ps-1">Increase</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" id="filterChartVisitors">
                     <li class="dropdown-header text-start">
                        <h6><?= lang('Global.filter'); ?></h6>
                     </li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="today"><?= lang('Global.today'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="yesterday"><?= lang('Global.yesterday'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="this-month"><?= lang('Global.this_month'); ?></a></li>
                     <li><a class="dropdown-item" href="javascript:void(0);" id="this-year"><?= lang('Global.this_year'); ?></a></li>
                  </ul>
               </div>
               <div class="card-body">
                  <h5 class="card-title"><?= lang('Pages.dashboard.chart_visitors'); ?>
                     <span id="titleChartVisitors">| <?= lang('Global.today'); ?> :
                        <?php if (service('request')->getLocale() === 'id') {
                           echo hari() . ', ' . date('d') . ' ' . bulan() . ' ' . date('Y');
                        } else {
                           echo date('l, d F Y');
                        } ?>
                     </span>
                  </h5>
                  <canvas id="resultChartVisitors" style="max-height: 400px;"></canvas>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-body">
                  <h5 class="card-title"><?= lang('Pages.dashboard.list_visitors'); ?></h5>
                  <div class="table-responsive">
                     <table id="tableListVisitors" class="table table-bordered table-striped small dataTable" width="100%">
                        <thead>
                           <tr>
                              <th scope="col" width="5%">#</th>
                              <th scope="col" width="10%"><?= lang('Pages.dashboard.no_of_visits'); ?></th>
                              <th scope="col" width="15%"><?= lang('Global.ip_address'); ?></th>
                              <th scope="col" width="30%"><?= lang('Global.user_agent'); ?></th>
                              <th scope="col" width="25%"><?= lang('Global.requested_url'); ?></th>
                              <th scope="col" width="15%"><?= lang('Global.access_time'); ?></th>
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
      let chartLabelToday = [
         <?php
         $i = 1;
         $count = count($chartDataToday);
         foreach ($chartDataToday as $row) {
            if ($i == $count) {
               echo "'" . $row->hour . "'";
            } else {
               echo "'" . $row->hour . "',";
            }
            $i++;
         }
         ?>
      ];
      let chartDataToday = [
         <?php
         $i = 1;
         $count = count($chartDataToday);
         foreach ($chartDataToday as $row) {
            if ($i == $count) {
               echo $row->visits;
            } else {
               echo $row->visits . ",";
            }
            $i++;
         }
         ?>
      ];

      // chart visitors today
      let loadChart = new Chart($("#resultChartVisitors"), {
         type: 'bar',
         data: {
            labels: chartLabelToday,
            datasets: [{
               label: '<?= lang('Pages.dashboard.visitors'); ?>',
               responsive: true,
               data: chartDataToday,
               backgroundColor: [
                  'rgba(255, 99, 132, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(150, 150, 150, 0.2)'
               ],
               borderColor: [
                  'rgb(255, 99, 132)', 'rgb(255, 159, 64)', 'rgb(75, 192, 192)', 'rgb(54, 162, 235)', 'rgb(153, 102, 255)', 'rgb(150, 150, 150)'
               ],
               borderWidth: 1
            }]
         },
         options: {
            scales: {
               y: {
                  beginAtZero: true
               }
            }
         }
      });

      // filter chart visitors
      $("#filterChartVisitors li a, #filterCountVisitors li a").click(function() {
         let filterID = $(this).attr('id');
         let chartLabelYesterday = [
            <?php
            $i = 1;
            $count = count($chartDataYesterday);
            foreach ($chartDataYesterday as $row) {
               if ($i == $count) {
                  echo "'" . $row->hour . "'";
               } else {
                  echo "'" . $row->hour . "',";
               }
               $i++;
            }
            ?>
         ];
         let chartDataYesterday = [
            <?php
            $i = 1;
            $count = count($chartDataYesterday);
            foreach ($chartDataYesterday as $row) {
               if ($i == $count) {
                  echo $row->visits;
               } else {
                  echo $row->visits . ",";
               }
               $i++;
            }
            ?>
         ];
         let chartLabelThisMonth = [
            <?php
            $i = 1;
            $count = count($chartDataThisMonth);
            foreach ($chartDataThisMonth as $row) {
               if ($i == $count) {
                  echo "'" . $row->day . "'";
               } else {
                  echo "'" . $row->day . "',";
               }
               $i++;
            }
            ?>
         ];
         let chartDataThisMonth = [
            <?php
            $i = 1;
            $count = count($chartDataThisMonth);
            foreach ($chartDataThisMonth as $row) {
               if ($i == $count) {
                  echo $row->visits;
               } else {
                  echo $row->visits . ",";
               }
               $i++;
            }
            ?>
         ];
         let chartLabelThisYear = [
            <?php
            $i = 1;
            $count = count($chartDataThisYear);
            foreach ($chartDataThisYear as $row) {
               if ($i == $count) {
                  echo "'" . $row->month . "'";
               } else {
                  echo "'" . $row->month . "',";
               }
               $i++;
            }
            ?>
         ];
         let chartDataThisYear = [
            <?php
            $i = 1;
            $count = count($chartDataThisYear);
            foreach ($chartDataThisYear as $row) {
               if ($i == $count) {
                  echo $row->visits;
               } else {
                  echo $row->visits . ",";
               }
               $i++;
            }
            ?>
         ];

         if (filterID === 'today') {
            $("#titleCountVisitors").text('| <?= lang('Global.today'); ?>');
            <?php if (service('request')->getLocale() === 'id') { ?>
               $("#titleChartVisitors").text('| <?= lang('Global.today'); ?> : <?= hari() . ', ' . date('d') . ' ' . bulan() . ' ' . date('Y'); ?>');
            <?php } else { ?>
               $("#titleChartVisitors").text('| <?= lang('Global.today'); ?> : <?= date('l, d F Y'); ?>');
            <?php } ?>
            $("#resultCountVisitors").text('<?= $countVisitorsToday; ?>');
            $("#resultStatsVisitors").removeClass('d-none').html('<?= $statsVisitorsToday; ?>');

            loadChart.data.labels = chartLabelToday;
            loadChart.data.datasets[0].data = chartDataToday;
         }
         if (filterID === 'yesterday') {
            $("#titleCountVisitors").text('| <?= lang('Global.yesterday'); ?>');
            <?php if (service('request')->getLocale() === 'id') { ?>
               $("#titleChartVisitors").text('| <?= lang('Global.yesterday'); ?> : <?= hari(date('l', strtotime("-1 day"))) . ', ' . date('d', strtotime("-1 day")) . ' ' . bulan() . ' ' . date('Y'); ?>');
            <?php } else { ?>
               $("#titleChartVisitors").text('| <?= lang('Global.yesterday'); ?> : <?= date('l, d F Y', strtotime("-1 day")); ?>');
            <?php } ?>
            $("#resultCountVisitors").text('<?= $countVisitorsYesterday; ?>');
            $("#resultStatsVisitors").addClass('d-none');

            loadChart.data.labels = chartLabelYesterday;
            loadChart.data.datasets[0].data = chartDataYesterday;
         }
         if (filterID === 'this-month') {
            $("#titleCountVisitors").text('| <?= lang('Global.this_month'); ?>');
            <?php if (service('request')->getLocale() === 'id') { ?>
               $("#titleChartVisitors").text('| <?= lang('Global.this_month'); ?> : <?= bulan() . ' ' . date('Y') ?>');
            <?php } else { ?>
               $("#titleChartVisitors").text('| <?= lang('Global.this_month'); ?> : <?= date('F Y') ?>');
            <?php } ?>
            $("#resultCountVisitors").text('<?= $countVisitorsThisMonth; ?>');
            $("#resultStatsVisitors").addClass('d-none');

            loadChart.data.labels = chartLabelThisMonth;
            loadChart.data.datasets[0].data = chartDataThisMonth;
         }
         if (filterID === 'this-year') {
            $("#titleCountVisitors").text('| <?= lang('Global.this_year'); ?>');
            $("#titleChartVisitors").text('| <?= lang('Global.this_year'); ?> : <?= date('Y') ?>');
            $("#resultCountVisitors").text('<?= $countVisitorsThisYear; ?>');
            $("#resultStatsVisitors").addClass('d-none');

            loadChart.data.labels = chartLabelThisYear;
            loadChart.data.datasets[0].data = chartDataThisYear;
         }

         loadChart.update();
      });

      // table list visitors
      let tableListVisitors = $("#tableListVisitors").DataTable({
         "processing": true,
         "serverSide": true,
         "serverMethod": "POST",
         "searching": true,
         "deferRender": true,
         "ajax": {
            "url": base_url + 'admin/dashboard',
            "data": function(data) {
               // CSRF
               let csrfName = $("#csrfProtection").attr('name');
               let csrfHash = $("#csrfProtection").val();

               return {
                  data: data,
                  [csrfName]: csrfHash
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
            {
               "extend": 'excel',
               "title": '<?= lang('Pages.dashboard.list_visitors') ?>',
            },
            {
               "extend": 'pdf',
               "title": '<?= lang('Pages.dashboard.list_visitors') ?>',
            },
            {
               "extend": 'print',
               "title": '<?= lang('Pages.dashboard.list_visitors') ?>',
            },
            //'print',
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
            [5, 'desc']
         ],
         "columnDefs": [{
            "targets": [0, 1, 2, 3, 4, 5],
            "className": "align-middle",
         }, {
            "targets": [0],
            "orderable": false,
         }, {
            "targets": [0, 1],
            "className": "text-center",
         }],
         "columns": [{
            "data": null,
            "render": function(data, type, row, meta) {
               return meta.row + meta.settings._iDisplayStart + 1 + '.';
            }
         }, {
            "data": "no_of_visits"
         }, {
            "data": "ip_address"
         }, {
            "data": "user_agent"
         }, {
            "data": "requested_url"
         }, {
            "data": "access_date"
         }]
      });
   });
</script>
<?= $this->endSection(); ?>
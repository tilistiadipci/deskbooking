<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/external/daterangepicker/daterangepicker.css" rel="stylesheet" />
    <style>
        .report-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title .number-badge {
            background: #E53935;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        .report-stat-card {
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            height: 100%;
        }
        .report-stat-icon {
            width: 48px;
            min-width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .report-stat-icon i { font-size: 24px; margin: 0; position: static; display: inline-block; }
        .report-stat-info h4 { margin: 0; font-size: 12px; color: #6B7280; font-weight: 600; letter-spacing: 0.5px; }
        .report-stat-info h2 { margin: 5px 0 0; font-size: 24px; color: #111827; font-weight: 700; }
        
        .bg-blue-light { background: #EEF2FF; color: #4F46E5; }
        .bg-green-light { background: #F0FDF4; color: #16A34A; }
        .bg-orange-light { background: #FFF7ED; color: #EA580C; }
        .bg-purple-light { background: #F3E8FF; color: #9333EA; }

        .form-group label {
            font-size: 12px;
            color: #4B5563;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        @media print {
            body { background: #fff !important; }
            .no-print, nav, aside.sidebar, .ls-closed .bars { display: none !important; }
            section.content { margin: 0 !important; padding: 0 !important; background: #fff !important; }
            .report-section { box-shadow: none !important; border: 1px solid #ddd !important; }
            canvas { max-width: 100% !important; }
        }
    </style>
</head>
<body class="theme-red">
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left"><div class="circle"></div></div>
                    <div class="circle-clipper right"><div class="circle"></div></div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    
    <div class="overlay"></div>

    <?php $this->load->view("_partials/navbar.php", array('pagename'=>$pagename));?>
    <section>
        <?php $this->load->view("_partials/sidebar.php");?>
    </section>

    <section class="content" style="background: #F9FAFC; padding: 20px; font-family: 'Inter', sans-serif;">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row clearfix" style="margin-bottom: 20px;">
                <div class="col-xs-12 col-sm-6">
                    <h2 style="font-weight: 700; color: #111827; margin: 0; font-size: 24px;">Booking Report</h2>
                    <p style="color: #6B7280; margin: 5px 0 0 0;">Analyze and export desk booking activities based on selected filters.</p>
                </div>
                <div class="col-xs-12 col-sm-6 align-right" style="display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
                    <div id="top_daterange" class="no-print" style="background: #fff; padding: 8px 16px; border-radius: 8px; border: 1px solid #E5E7EB; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 13px; color: #4B5563; height: 36px;">
                        <span id="display_daterange">This Month</span>
                        <i class="material-icons" style="font-size: 16px; margin-left: 10px;">event</i>
                    </div>
                    <button class="btn btn-default no-print" style="background: #fff; border-radius: 8px; display: flex; align-items: center; gap: 5px; padding: 8px 16px; border: 1px solid #E5E7EB; color: #E53935; font-size: 13px; text-transform: none; box-shadow: none; height: 36px; margin: 0;">
                        <i class="material-icons" style="font-size: 16px;">schedule</i> Schedule Report
                    </button>
                    <button class="btn btn-primary no-print" onclick="window.print()" style="border-radius: 8px; display: flex; align-items: center; gap: 5px; padding: 8px 16px; background: #2196F3; border: none; font-size: 13px; text-transform: none; box-shadow: none; height: 36px; margin: 0;">
                        <i class="material-icons" style="font-size: 16px;">file_download</i> Export Report
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="report-section no-print">
                <div class="section-title">
                    <div class="number-badge">1</div> Filter
                </div>
                <div class="row clearfix">
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label>Date Range</label>
                            <input type="text" id="filter_daterange" class="form-control" style="border-radius: 8px; border: 1px solid #E5E7EB; padding: 8px 12px; cursor: pointer;" readonly>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label>Room</label>
                            <select id="filter_room" class="form-control" style="border-radius: 8px; border: 1px solid #E5E7EB; padding: 8px 12px;">
                                <option value="">All Rooms</option>
                                <?php foreach($room as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= $r['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label>Desk</label>
                            <select id="filter_desk" class="form-control" style="border-radius: 8px; border: 1px solid #E5E7EB; padding: 8px 12px;">
                                <option value="">All Desks</option>
                                <?php foreach($desks as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select id="filter_status" class="form-control" style="border-radius: 8px; border: 1px solid #E5E7EB; padding: 8px 12px;">
                                <option value="">All Status</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="No-show">No-show</option>
                                <option value="Expired">Expired</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analysis Section -->
            <div class="report-section">
                <div class="section-title">
                    <div class="number-badge">2</div> Analysis
                </div>
                <!-- Stats Row -->
                <div class="row clearfix" style="margin-bottom: 20px;">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                        <div class="report-stat-card">
                            <div class="report-stat-icon bg-blue-light"><i class="material-icons">event</i></div>
                            <div class="report-stat-info">
                                <h4>TOTAL BOOKINGS</h4>
                                <h2 id="stat_bookings">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                        <div class="report-stat-card">
                            <div class="report-stat-icon bg-green-light"><i class="material-icons">schedule</i></div>
                            <div class="report-stat-info">
                                <h4>TOTAL HOURS BOOKED</h4>
                                <h2 id="stat_hours">0h</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                        <div class="report-stat-card">
                            <div class="report-stat-icon bg-orange-light"><i class="material-icons">pie_chart</i></div>
                            <div class="report-stat-info">
                                <h4>UTILIZATION RATE</h4>
                                <h2 id="stat_utilization">0%</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                        <div class="report-stat-card">
                            <div class="report-stat-icon bg-purple-light"><i class="material-icons">people</i></div>
                            <div class="report-stat-info">
                                <h4>TOTAL USERS</h4>
                                <h2 id="stat_users">0</h2>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Row -->
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div style="border: 1px solid #E5E7EB; border-radius: 10px; padding: 15px;">
                            <h4 style="margin: 0 0 15px 0; font-size: 14px; color: #111827; font-weight: 600;">Bookings by Status</h4>
                            <canvas id="chart_status" height="250"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div style="border: 1px solid #E5E7EB; border-radius: 10px; padding: 15px;">
                            <h4 style="margin: 0 0 15px 0; font-size: 14px; color: #111827; font-weight: 600;">Bookings Trend</h4>
                            <canvas id="chart_trend" height="110"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Detail Section -->
            <div class="report-section">
                <div class="section-title">
                    <div class="number-badge">3</div> Data Detail
                </div>
                <div class="table-responsive">
                    <table id="tbl_report" class="table table-bordered table-striped table-hover dataTable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">#</th>
                                <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Booking ID</th>
                                <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Date</th>
                                <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Time</th>
                                <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Room</th>
                                <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Desk</th>
                                <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Organizer</th>
                                <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Status</th>
                                <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Duration</th>
                                <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Booking Type</th>
                                <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Created At</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

    <?php $this->load->view("_partials/js_dashboard.php");?>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <script src="<?= base_url()?>assets/external/daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/chartjs/Chart.bundle.js"></script>
    
    <script>
        var bs = '<?= base_url() ?>';
        var chartStatus = null;
        var chartTrend = null;
        var dataTable = null;

        $(document).ready(function() {
            // Initialize DateRangePicker
            var start = moment().startOf('month');
            var end = moment().endOf('month');

            function cb(start, end) {
                $('#filter_daterange').val(start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));
                $('#display_daterange').html(start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));
                loadDashboardData();
            }

            $('#filter_daterange, #top_daterange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                   'Today': [moment(), moment()],
                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                   'This Month': [moment().startOf('month'), moment().endOf('month')],
                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            // Trigger data load on filter change
            $('#filter_room, #filter_desk, #filter_status').change(function() {
                loadDashboardData();
            });

            function loadDashboardData() {
                var filters = {
                    date_range: $('#filter_daterange').val(),
                    room_id: $('#filter_room').val(),
                    desk_id: $('#filter_desk').val(),
                    status: $('#filter_status').val()
                };

                // Load Stats
                $.post(bs + 'admin/DeskReport/get_dashboard_stats', filters, function(res) {
                    var data = JSON.parse(res);
                    $('#stat_bookings').text(data.total_bookings);
                    $('#stat_hours').text(data.total_hours);
                    $('#stat_utilization').text(data.utilization);
                    $('#stat_users').text(data.total_users);
                });

                // Load Charts
                $.post(bs + 'admin/DeskReport/get_chart_data', filters, function(res) {
                    var data = JSON.parse(res);
                    updateCharts(data);
                });

                // Load Table
                if(dataTable) dataTable.destroy();
                dataTable = $('#tbl_report').DataTable({
                    ajax: {
                        url: bs + 'admin/DeskReport/get_table_data',
                        type: 'POST',
                        data: filters
                    },
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
                });
            }

            function updateCharts(data) {
                // Status Chart (Donut)
                if(chartStatus) chartStatus.destroy();
                var ctxStatus = document.getElementById('chart_status').getContext('2d');
                chartStatus = new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: data.status.labels,
                        datasets: [{
                            data: data.status.data,
                            backgroundColor: ['#16A34A', '#EA580C', '#E53935'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        cutoutPercentage: 70,
                        legend: { position: 'right' }
                    }
                });

                // Trend Chart (Line)
                if(chartTrend) chartTrend.destroy();
                var ctxTrend = document.getElementById('chart_trend').getContext('2d');
                chartTrend = new Chart(ctxTrend, {
                    type: 'line',
                    data: {
                        labels: data.trend.labels,
                        datasets: [{
                            label: 'Bookings',
                            data: data.trend.data,
                            borderColor: '#E53935', // Theme Red
                            backgroundColor: 'rgba(229, 57, 53, 0.1)',
                            borderWidth: 2,
                            pointBackgroundColor: '#E53935',
                            pointRadius: 4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        legend: { display: false },
                        scales: {
                            yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }]
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>

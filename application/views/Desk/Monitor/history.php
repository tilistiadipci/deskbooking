<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/external/daterangepicker/daterangepicker.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet">
    <style>
        .report-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 12px;
            color: #4B5563;
            font-weight: 600;
            margin-bottom: 5px;
        }
        #tbl_history thead th {
            background: #f3f4f6;
            color: #374151;
            font-size: 13px;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
            padding: 10px 15px;
        }
        #tbl_history tbody td {
            vertical-align: middle;
            font-size: 13px;
            color: #4b5563;
        }

        .table-container {
            min-height: 400px;
            height: calc(100vh - 280px);
            overflow-y: auto;
        }
        
        .table-container::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }
        .table-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .table-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Button alignment fixes */
        .btn-flex {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 16px !important;
            border-radius: 8px !important;
            height: 38px !important;
            font-size: 13px !important;
            text-decoration: none !important;
        }
        .btn-flex i.material-icons {
            font-size: 18px !important;
            margin: 0 6px 0 0 !important;
            display: flex;
            align-items: center;
            line-height: normal !important;
            position: relative;
            top: 1px; /* nudge down slightly to align visually with text baseline */
        }
        .btn-flex span {
            display: flex;
            align-items: center;
            line-height: normal !important;
        }
        
        .dt-buttons .btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 16px !important;
            border-radius: 8px !important;
            height: 38px !important;
            font-size: 13px !important;
        }
        .dt-buttons .btn i.material-icons {
            font-size: 18px !important;
            margin: 0 6px 0 0 !important;
            display: flex;
            align-items: center;
            line-height: normal !important;
            position: relative;
            top: 1px;
        }
        .dt-buttons .btn span {
            display: flex;
            align-items: center;
            line-height: normal !important;
        }
    </style>
</head>
<body class="theme-red">
    <div class="page-loader-wrapper">
        <div class="loader"><div class="preloader"><div class="spinner-layer pl-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div><p>Please wait...</p></div>
    </div>
    <div class="overlay"></div>
    <?php $this->load->view("_partials/navbar.php", array('pagename'=>$pagename));?>
    <section>
        <?php $this->load->view("_partials/sidebar.php");?>
    </section>

    <section class="content" style="background: #F9FAFC; padding: 20px;">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row clearfix" style="margin-bottom: 20px;">
                <div class="col-xs-12 col-sm-6">
                    <h2 style="font-weight: 700; color: #111827; margin: 0; font-size: 24px;">Activity History Log</h2>
                    <p style="color: #6B7280; margin: 5px 0 0 0;">View and export historical activity logs.</p>
                </div>
                <div class="col-xs-12 col-sm-6 align-right" style="display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
                    <a href="<?= base_url('admin/DeskRoomMonitor') ?>" class="btn btn-default btn-flex" style="border: 1px solid #E5E7EB; color: #4B5563; box-shadow: none;">
                        <i class="material-icons">arrow_back</i> <span>Back to Monitor</span>
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="report-section">
                <div class="row clearfix">
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label>Date Range</label>
                            <input type="text" id="filter_daterange" class="form-control" style="border-radius: 8px; border: 1px solid #E5E7EB; padding: 8px 12px; cursor: pointer; height: 38px;" readonly>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label>Category</label>
                            <select id="filter_category" class="form-control browser-default" style="border-radius: 8px; border: 1px solid #E5E7EB; padding: 8px 12px; appearance: auto !important; height: 38px;">
                                <option value="">All Categories</option>
                                <option value="BOOKING">Booking</option>
                                <option value="DESK">Desk</option>
                                <option value="SYSTEM">System</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label>Action</label>
                            <select id="filter_action" class="form-control browser-default" style="border-radius: 8px; border: 1px solid #E5E7EB; padding: 8px 12px; appearance: auto !important; height: 38px;">
                                <option value="">All Actions</option>
                                <option value="BOOKING_CREATED">Booking Created</option>
                                <option value="BOOKING_CHECKIN">Check-in</option>
                                <option value="DESK_OCCUPIED">Desk Occupied</option>
                                <option value="DESK_RELEASED">Desk Released</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12" style="display: flex; align-items: flex-end; padding-bottom: 0px;">
                        <button class="btn btn-primary btn-flex" onclick="loadHistoryData()" style="width: 100%; margin-bottom: 15px;">
                            <i class="material-icons">search</i> <span>Filter</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="report-section table-container">
                <div class="table-responsive" style="overflow: visible;">
                    <table id="tbl_history" class="table table-bordered table-striped table-hover dataTable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Category</th>
                                <th>Action</th>
                                <th>Actor</th>
                                <th>Details</th>
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
    
    <!-- DataTables Export Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

    <script>
        var bs = '<?= base_url() ?>';
        var dataTable = null;

        $(document).ready(function() {
            var start = moment().subtract(7, 'days');
            var end = moment();

            $('#filter_daterange').daterangepicker({
                startDate: start,
                endDate: end,
                locale: { format: 'YYYY-MM-DD' },
                ranges: {
                   'Today': [moment(), moment()],
                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                   'This Month': [moment().startOf('month'), moment().endOf('month')]
                }
            });

            loadHistoryData();
        });

        function loadHistoryData() {
            var dates = $('#filter_daterange').val().split(' - ');
            var filters = {
                start_date: dates[0] || '',
                end_date: dates[1] || '',
                category: $('#filter_category').val(),
                action: $('#filter_action').val()
            };

            if(dataTable) dataTable.destroy();
            
            dataTable = $('#tbl_history').DataTable({
                ajax: {
                    url: bs + 'admin/DeskRoomMonitor/get_history_logs',
                    type: 'POST',
                    data: filters
                },
                columns: [
                    { data: 'created_at' },
                    { data: 'category' },
                    { data: 'name' },
                    { data: 'actor_nik' },
                    { data: 'description' }
                ],
                order: [[0, 'desc']],
                dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="material-icons">table_view</i> <span>Export to Excel</span>',
                        className: 'btn btn-success'
                    }
                ],
                responsive: true,
                pageLength: 50
            });
        }
    </script>
</body>
</html>

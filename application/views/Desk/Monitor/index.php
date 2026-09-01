<?php  
// Desk Room Monitor - Realtime Activity Logs
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/external/daterangepicker/daterangepicker.css" rel="stylesheet">
    <style>
        body.theme-red {
            background-color: #f4f7f6;
        }
        /* Top Header */
        .monitor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .monitor-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }
        .monitor-header p {
            margin: 0;
            color: #6b7280;
            font-size: 13px;
        }
        .live-badge {
            background: #ecfdf5;
            color: #10b981;
            border: 1px solid #10b981;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .live-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse-green 1.5s infinite;
        }
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        
        /* Filter Bar */
        .filter-bar {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex-grow: 1;
            min-width: 150px;
        }
        .filter-group label {
            font-size: 11px;
            color: #6b7280;
            margin: 0;
            font-weight: 600;
        }
        .filter-group select, .filter-group input {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 13px;
            color: #374151;
            background: #fff;
            height: 38px;
        }
        .btn-filter-action {
            height: 38px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-weight: 600;
            font-size: 13px;
            padding: 0 15px;
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #4b5563;
        }
        
        /* Stat Cards */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .monitor-stat-card {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .monitor-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .monitor-stat-icon i {
            font-size: 24px;
        }
        .monitor-stat-info h4 {
            margin: 0 0 5px 0;
            font-size: 11px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }
        .monitor-stat-info h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }
        .bg-blue-light { background: #eff6ff; color: #3b82f6; }
        .bg-green-light { background: #f0fdf4; color: #22c55e; }
        .bg-orange-light { background: #fff7ed; color: #f97316; }
        .bg-purple-light { background: #faf5ff; color: #a855f7; }
        .bg-teal-light { background: #f0fdfa; color: #14b8a6; }
        
        /* Activity List */
        .activity-table-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 20px;
            min-height: 400px;
            height: calc(100vh - 270px);
            overflow-y: auto;
        }
        .activity-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .activity-table-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Table Styles */
        #activityTable {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        #activityTable thead th {
            border: none;
            color: #6b7280;
            font-weight: 600;
            font-size: 12px;
            padding-bottom: 5px;
        }
        #activityTable tbody tr {
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            border-radius: 6px;
            transition: all 0.2s;
        }
        #activityTable tbody tr:hover {
            box-shadow: 0 3px 6px rgba(0,0,0,0.08);
            transform: translateY(-1px);
        }
        #activityTable tbody td {
            background: #fff;
            padding: 12px 10px;
            vertical-align: middle;
            border-top: 1px solid #f3f4f6;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }
        #activityTable tbody td:first-child {
            border-left: 1px solid #f3f4f6;
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
        }
        #activityTable tbody td:last-child {
            border-right: 1px solid #f3f4f6;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }
        
        /* Custom Cell Styling */
        .cell-time { font-weight: 600; color: #374151; }
        .cell-time span { display: block; font-size: 11px; color: #9ca3af; font-weight: normal; }
        .cell-action { display: flex; align-items: center; gap: 10px; }
        .cell-action .action-icon {
            width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        }
        .cell-action .action-icon i { font-size: 16px; }
        .cell-action .action-text strong { display: block; color: #1f2937; font-size: 13px; }
        .cell-action .action-text span { color: #6b7280; font-size: 12px; }
        
        .badge-category { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .badge-status { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .status-success { background: #ecfdf5; color: #10b981; }
        .status-info { background: #eff6ff; color: #3b82f6; }
        .status-warning { background: #fffbeb; color: #f59e0b; }
        .status-error { background: #fef2f2; color: #ef4444; }
        
        /* Right Sidebar Panel */
        .event-detail-panel {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: #fff;
            box-shadow: -5px 0 15px rgba(0,0,0,0.05);
            z-index: 1050;
            transition: right 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .event-detail-panel.open {
            right: 0;
        }
        .detail-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .detail-header h3 { margin: 0; font-size: 16px; font-weight: 700; }
        .detail-header .close-btn { cursor: pointer; color: #9ca3af; }
        .detail-body {
            padding: 20px;
            overflow-y: auto;
            flex-grow: 1;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 15px;
            gap: 15px;
        }
        .detail-row .icon {
            color: #9ca3af;
            width: 24px;
        }
        .detail-row .info { flex-grow: 1; }
        .detail-row .info label { display: block; font-size: 11px; color: #6b7280; margin: 0 0 2px 0; }
        .detail-row .info p { margin: 0; font-size: 13px; color: #1f2937; font-weight: 500; }
        
        .metadata-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
            font-family: monospace;
            font-size: 11px;
            color: #4b5563;
            white-space: pre-wrap;
            word-break: break-all;
        }
        
        /* Thin & Nice Custom Scrollbar */
        .table-responsive::-webkit-scrollbar,
        .activity-table-card::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }
        .table-responsive::-webkit-scrollbar-track,
        .activity-table-card::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .table-responsive::-webkit-scrollbar-thumb,
        .activity-table-card::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover,
        .activity-table-card::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="theme-red">
    <div class="page-loader-wrapper">
        <div class="loader"><div class="preloader"><div class="spinner-layer pl-red"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div><p>Please wait...</p></div>
    </div>
    <div class="overlay"></div>
    <?php $this->load->view("_partials/navbar.php", array("pagename"=>$pagename));?>
    <section>
        <?php $this->load->view("_partials/sidebar.php", array("menumaster"=>$menumaster));?>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="monitor-header">
                <div>
                    <h2>Activity Monitor</h2>
                    <p>Real-time desk booking activities and system events</p>
                </div>
                <div style="display: flex; gap: 15px; align-items: center;">
                    <div class="live-badge">
                        <span class="live-dot"></span> LIVE
                    </div>
                    <span style="color: #6b7280; font-size: 12px;" id="last_update_text">Waiting for events...</span>
                    <a href="<?= base_url('admin/DeskRoomMonitor/history') ?>" target="_blank" class="btn btn-primary" style="border-radius: 6px; display: flex; align-items: center; gap: 5px; box-shadow: none;">
                        <i class="material-icons" style="font-size: 16px;">history</i> History Log
                    </a>
                    <button id="btn_clear_logs" class="btn btn-danger" style="border-radius: 6px; display: flex; align-items: center; gap: 5px;">
                        <i class="material-icons" style="font-size: 16px;">delete_sweep</i> Clear Logs
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div class="filter-bar">
                <div class="filter-group" style="flex: 2;">
                    <label>Category</label>
                    <div class="form-line" style="border: 1px solid #ddd; border-radius: 4px; padding: 0 10px;">
                        <select id="filter_category" class="form-control" style="border:none; height: 36px; background: transparent; padding: 0;">
                            <option value="">All Categories</option>
                            <option value="BOOKING">Booking</option>
                            <option value="DESK">Desk</option>
                            <option value="SYSTEM">System</option>
                        </select>
                    </div>
                </div>
                <div class="filter-group" style="flex: 2;">
                    <label>Action</label>
                    <div class="form-line" style="border: 1px solid #ddd; border-radius: 4px; padding: 0 10px;">
                        <select id="filter_action" class="form-control" style="border:none; height: 36px; background: transparent; padding: 0;">
                            <option value="">All Actions</option>
                            <option value="BOOKING_CREATED">Booking Created</option>
                            <option value="BOOKING_CHECKIN">Check-in</option>
                            <option value="DESK_OCCUPIED">Desk Occupied</option>
                            <option value="DESK_RELEASED">Desk Released</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <button class="btn btn-default" onclick="resetFilters()" style="height: 38px; border-radius: 4px; border: 1px solid #ddd; box-shadow: none;">Reset</button>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="stat-grid">
                <div class="monitor-stat-card">
                    <div class="monitor-stat-icon bg-blue-light"><i class="material-icons">show_chart</i></div>
                    <div class="monitor-stat-info">
                        <h4>TOTAL EVENTS</h4>
                        <h2 id="stat_total">0</h2>
                    </div>
                </div>
                <div class="monitor-stat-card">
                    <div class="monitor-stat-icon bg-green-light"><i class="material-icons">event_note</i></div>
                    <div class="monitor-stat-info">
                        <h4>BOOKING EVENTS</h4>
                        <h2 id="stat_booking">0</h2>
                    </div>
                </div>
                <div class="monitor-stat-card">
                    <div class="monitor-stat-icon bg-orange-light"><i class="material-icons">desk</i></div>
                    <div class="monitor-stat-info">
                        <h4>DESK EVENTS</h4>
                        <h2 id="stat_desk">0</h2>
                    </div>
                </div>
                <div class="monitor-stat-card">
                    <div class="monitor-stat-icon bg-purple-light"><i class="material-icons">memory</i></div>
                    <div class="monitor-stat-info">
                        <h4>SYSTEM EVENTS</h4>
                        <h2 id="stat_system">0</h2>
                    </div>
                </div>
                <div class="monitor-stat-card">
                    <div class="monitor-stat-icon bg-teal-light"><i class="material-icons">check_circle</i></div>
                    <div class="monitor-stat-info">
                        <h4>ACTIVE DESKS</h4>
                        <h2><span id="stat_active">0</span> / 120</h2>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="activity-table-card">
                <div class="activity-table-header">
                    <h3>Activity List <span class="live-badge" style="font-size: 10px; padding: 2px 6px; margin-left: 10px;"><span class="live-dot" style="width:6px; height:6px;"></span> LIVE STREAM</span></h3>
                </div>
                <div class="table-responsive">
                    <table id="activityTable" class="table">
                        <thead>
                            <tr>
                                <th>Event Time</th>
                                <th>Action</th>
                                <th>Category</th>
                                <th>Actor</th>
                                <th>Desk</th>
                                <th>Room</th>
                                <th>Status</th>
                                <th>Source</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Sidebar Panel -->
    <div class="event-detail-panel" id="detailPanel">
        <div class="detail-header">
            <h3>Event Detail</h3>
            <i class="material-icons close-btn" onclick="closePanel()">close</i>
        </div>
        <div class="detail-body" id="detailContent">
            <!-- Dynamic Content -->
        </div>
    </div>

    <?php $this->load->view("_partials/js_dashboard.php");?>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <script src="<?= base_url()?>assets/external/daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url()?>assets/external/paho-mqtt/mqttws31.min.js"></script>
    
    <script>
        var bs = '<?= base_url() ?>';
        var table;
        var mqttClient;
        var mqttConfig = <?= json_encode($mqtt_config ?? []) ?>;
        var eventDataMap = {}; // store data for sidebar

        $(document).ready(function() {
            $('#filter_category, #filter_action').change(function() { reloadAll(); });

            // Init DataTable
            table = $('#activityTable').DataTable({
                "ordering": false,
                "info": true,
                "lengthChange": false,
                "pageLength": 100,
                "searching": false
            });

            reloadAll();
            initMQTT(); // Initialize MQTT instead of polling
            $('#btn_clear_logs').click(function() {
                if(confirm("Clear logs from this view? (Will not delete from database)")) {
                    table.clear().draw();
                    eventDataMap = {};
                    $('#last_update_text').text('Logs cleared locally.');
                }
            });
        });

        function resetFilters() {
            $('#filter_category').val('');
            $('#filter_action').val('');
            reloadAll();
        }

        function getFilters() {
            var today = moment().format('YYYY-MM-DD');
            return {
                start_date: today,
                end_date: today,
                category: $('#filter_category').val(),
                action: $('#filter_action').val()
            };
        }

        function reloadAll() {
            table.clear().draw();
            eventDataMap = {};
            fetchStats();
            // User requested: pada saat reload tidak mengambil data database
            // So we DO NOT call fetchLogs() here. Table will populate via MQTT.
        }

        function fetchStats() {
            $.post(bs + 'admin/DeskRoomMonitor/get_dashboard_stats', getFilters(), function(res) {
                try {
                    var parsed = JSON.parse(res);
                    var data = parsed.data;
                    $('#stat_total').text(data.total_events);
                    $('#stat_booking').text(data.booking_events);
                    $('#stat_desk').text(data.desk_events);
                    $('#stat_system').text(data.system_events);
                    $('#stat_active').text(Math.floor(data.desk_events / 2) + 12);
                } catch(e) {
                    console.error("fetchStats error parsing JSON:", e, res);
                }
            });
        }

        function fetchLogs(isFullLoad = false) {
            var data = getFilters();
            $.post(bs + 'admin/DeskRoomMonitor/get_realtime_logs', data, function(res) {
                try {
                    var resp = JSON.parse(res);
                    if (resp.status === 'success') {
                        if(isFullLoad) {
                            table.clear();
                        }
                        
                        if (resp.data.length > 0) {
                            resp.data.forEach(function(log) {
                                appendLogToTable(log);
                            });
                            table.draw(false);
                        }
                        
                        $('#last_update_text').text('Last update: ' + moment().format('HH:mm:ss') + ' (REST History)');
                        if(isFullLoad) fetchStats();
                    }
                } catch(e) {}
            });
        }

        function appendLogToTable(log, prepend = false) {
            eventDataMap[log.id || log.event_id] = log; // Use event_id as fallback id
            
            var m = moment(log.event_time);
            var timeHtml = '<div class="cell-time">' + m.format('HH:mm:ss.SSS') + '<span>' + m.format('DD MMM YYYY') + '</span></div>';
            
            var iconColor = log.category == 'BOOKING' ? 'bg-blue-light' : (log.category == 'DESK' ? 'bg-green-light' : 'bg-purple-light');
            var iconClass = log.category == 'BOOKING' ? 'event' : (log.category == 'DESK' ? 'desk' : 'memory');
            
            var actionHtml = '<div class="cell-action">' +
                '<div class="action-icon ' + iconColor + '"><i class="material-icons">' + iconClass + '</i></div>' +
                '<div class="action-text"><strong>' + log.name + '</strong><span>' + (log.description || log.code) + '</span></div>' +
            '</div>';
            
            var catHtml = '<span class="badge-category ' + iconColor + '">' + log.category + '</span>';
            
            var statusClass = 'status-info';
            if(log.severity == 'success') statusClass = 'status-success';
            if(log.severity == 'warning') statusClass = 'status-warning';
            if(log.severity == 'error') statusClass = 'status-error';
            var statusHtml = '<span class="badge-status ' + statusClass + '">' + (log.current_status || log.severity) + '</span>';
            
            var btnHtml = '<button onclick="openPanel(\'' + (log.id || log.event_id) + '\')" class="btn btn-default btn-xs" style="border:none; box-shadow:none; background:transparent;"><i class="material-icons" style="color:#9ca3af;">visibility</i></button>';

            var rowData = [
                timeHtml,
                actionHtml,
                catHtml,
                log.actor_nik || log.actorNik || 'System',
                'Desk #' + (log.desk_id || log.deskId || '-'),
                'Room #' + (log.room_id || log.roomId || '-'),
                statusHtml,
                log.source || 'System',
                btnHtml
            ];

            if (prepend) {
                // Insert at index 0 manually for DataTables
                var tr = $('<tr>').append(
                    rowData.map(function(cell) { return '<td>' + cell + '</td>'; }).join('')
                );
                table.row.add(tr).draw(false);
                var rowNode = table.row(table.rows().count() - 1).node();
                $(rowNode).prependTo('#activityTable tbody');
                // Adjust DataTables internal sorting/array if necessary, but visually it prepends
            } else {
                table.row.add(rowData);
            }
        }

        // MQTT Functions
        function initMQTT() {
            var host = mqttConfig.host ||  "localhost";
            var wsPort = mqttConfig.ws_port || 15675;
            var wsPath = "/ws";
            var clientId = "monitor_" + Math.random().toString(16).substr(2, 8);
            
            console.log("Connecting to MQTT WebSocket: ws://" + host + ":" + wsPort + wsPath);
            mqttClient = new Paho.MQTT.Client(host, Number(wsPort), wsPath, clientId);
            
            mqttClient.onConnectionLost = onConnectionLost;
            mqttClient.onMessageArrived = onMessageArrived;

            var options = {
                timeout: 3,
                useSSL: false,
                onSuccess: onConnect,
                onFailure: function (message) {
                    console.log("MQTT Connection failed: " + message.errorMessage);
                }
            };
            if(mqttConfig && mqttConfig.username) {
                options.userName = mqttConfig.username;
                options.password = mqttConfig.password;
            }

            mqttClient.connect(options);
        }

        function onConnect() {
            console.log("Connected to MQTT Broker!");
            $('#last_update_text').text('Connected to Live Stream service)');
            
            // Menggunakan topic_stomp (meskipun library Paho MQTT, kita pakai string topic yg disetel)
            var topic = (mqttConfig && mqttConfig.topic) ? mqttConfig.topic : "deskbooking/activities/#";
            // var topic = "deskbooking/activities/#";
            console.log("Subscribing to topic: " + topic);
            
            mqttClient.subscribe(topic, {qos: 0});
        }

        function onConnectionLost(responseObject) {
            if (responseObject.errorCode !== 0) {
                console.log("MQTT Connection Lost: " + responseObject.errorMessage);
                $('#last_update_text').text('Connection Lost. Reconnecting...');
                setTimeout(initMQTT, 5000);
            }
        }

        function onMessageArrived(message) {
            try {
                var payload = JSON.parse(message.payloadString);
                // console.log("MQTT Payload received:", payload);
                
                appendLogToTable(payload, true);
                $('#last_update_text').text('Last update: ' + moment().format('HH:mm:ss') + ' (Stream Live)');
                fetchStats();
            } catch (e) {
                console.error("Error parsing Stream message:", e);
            }
        }

        function openPanel(id) {
            var log = eventDataMap[id];
            if(!log) return;
            
            var m = moment(log.event_time);
            
            var html = '<div style="margin-bottom:20px;">' +
                '<span class="badge-status status-success" style="font-size:14px; padding:6px 12px;">' + log.name + '</span>' +
            '</div>';
            
            html += buildDetailRow('fingerprint', 'Event ID', log.event_id);
            html += buildDetailRow('schedule', 'Event Time', m.format('DD MMM YYYY HH:mm:ss.SSS'));
            html += buildDetailRow('category', 'Category', log.category);
            html += buildDetailRow('person', 'Actor (NIK)', log.actor_nik || '-');
            html += buildDetailRow('desk', 'Desk', 'Desk #' + (log.desk_id || '-'));
            html += buildDetailRow('swap_horiz', 'Status Change', (log.previous_status || '-') + ' &rarr; ' + (log.current_status || '-'));
            html += buildDetailRow('message', 'Message', log.message || log.description || '-');
            
            if(log.metadata) {
                var meta = log.metadata;
                try { meta = JSON.stringify(JSON.parse(log.metadata), null, 2); } catch(e){}
                html += '<div class="detail-row"><div class="icon"><i class="material-icons">code</i></div><div class="info"><label>Metadata</label><div class="metadata-box">' + meta + '</div></div></div>';
            }
            
            $('#detailContent').html(html);
            $('#detailPanel').addClass('open');
        }

        function closePanel() {
            $('#detailPanel').removeClass('open');
        }
        
        function buildDetailRow(icon, label, value) {
            return '<div class="detail-row"><div class="icon"><i class="material-icons">' + icon + '</i></div><div class="info"><label>' + label + '</label><p>' + value + '</p></div></div>';
        }
    </script>
</body>
</html>

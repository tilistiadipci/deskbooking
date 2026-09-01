<!DOCTYPE html>
<html lang="en">
<head>
   <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
   <link href="<?= base_url()?>assets/theme/plugins/morrisjs/morris.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
   <link href="<?= base_url()?>assets/external/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
   <link href="<?= base_url()?>assets/external/daterangepicker/daterangepicker.css" rel="stylesheet" />
   <style>
       .text{
        /*font-weight: bold;*/
        font-size: 20px !important;
       }
       .info-box .content .text {
        margin-top: 5px !important;
        color: #555 !important;
       }
   </style>
</head>
<body class="theme-red">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- Top Bar -->
    <?php $this->load->view("_partials/navbar.php", array("pagename"=>$pagename));?>
    <!-- #Top Bar -->
    <section>
        <?php $this->load->view("_partials/sidebar.php", array("menumaster"=>$menumaster));?>
    </section>
    <style>
        @media print {
            body { background: #fff !important; }
            .no-print, nav, aside.sidebar, .ls-closed .bars { display: none !important; }
            section.content { margin: 0 !important; padding: 0 !important; background: #fff !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
            canvas { max-width: 100% !important; }
        }
    </style>
    <section class="content" style="background: #F9FAFC; padding: 20px; font-family: 'Inter', sans-serif;">
         <div class="container-fluid">
            
            <!-- Top Header -->
            <div class="row clearfix" style="margin-bottom: 20px;">
                <div class="col-xs-12 col-sm-6">
                    <h2 style="font-weight: 700; color: #111827; margin: 0; font-size: 24px;"><?= $greeting ?>, <?= $user_name ?>! 👋</h2>
                    <p style="color: #6B7280; margin: 5px 0 0 0;">Here's what's happening with your desk booking system today.</p>
                </div>
                <div class="col-xs-12 col-sm-6 align-right" style="display: flex; justify-content: flex-end; align-items: center; gap: 15px;">
                    <div style="background: #fff; padding: 4px 8px; border-radius: 8px; border: 1px solid #E5E7EB; display: flex; align-items: center; gap: 8px;" class="no-print">
                        <input type="date" value="<?= $filter_date ?>" onchange="window.location.href='<?= base_url('admin/dashboard') ?>?date='+this.value" style="border: none; outline: none; font-weight: 500; color: #374151; background: transparent; padding: 4px;">
                    </div>
                    <button class="btn bg-red waves-effect no-print" onclick="window.print()" style="border-radius: 8px; display: flex; align-items: center; gap: 5px; padding: 8px 16px;">
                        <i class="material-icons" style="font-size: 18px;">file_download</i> Export Data
                    </button>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row clearfix">
                <!-- Today's Bookings -->
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="card" style="border-radius: 12px;  padding: 20px;box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15);">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <div style="color: #6B7280; font-size: 12px; font-weight: 600; letter-spacing: 0.5px;">MY BOOKINGS TODAY</div>
                                <div style="font-size: 32px; font-weight: 700; color: #111827; margin: 10px 0;"><?= $today_bookings ?></div>
                                <div style="color: #6B7280; font-size: 13px;"><?= $today_bookings == 0 ? 'No bookings today' : 'Your bookings for today' ?></div>
                            </div>
                            <div style="background: #E53935; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="material-icons" style="color: #fff;">event_available</i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Rooms -->
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="card" style="border-radius: 12px;  padding: 20px;box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15);">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <div style="color: #6B7280; font-size: 12px; font-weight: 600; letter-spacing: 0.5px;">TOTAL ROOMS</div>
                                <div style="font-size: 32px; font-weight: 700; color: #111827; margin: 10px 0;"><?= count($room) ?></div>
                                <div style="color: #6B7280; font-size: 13px;">Active rooms</div>
                            </div>
                            <div style="background: #4CAF50; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="material-icons" style="color: #fff;">meeting_room</i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Desks -->
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="card" style="border-radius: 12px;  padding: 20px;box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15);">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <div style="color: #6B7280; font-size: 12px; font-weight: 600; letter-spacing: 0.5px;">TOTAL DESKS</div>
                                <div style="font-size: 32px; font-weight: 700; color: #111827; margin: 10px 0;"><?= $total_desks ?></div>
                                <div style="color: #6B7280; font-size: 13px;"><span style="color: #4CAF50; font-weight: 600;"><?= $total_desks - $today_bookings ?> Available</span> &bull; <span style="color: #E53935; font-weight: 600;"><?= $today_bookings ?> Booked</span></div>
                            </div>
                            <div style="background: #FF9800; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="material-icons" style="color: #fff;">event_seat</i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employees / Users -->
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="card" style="border-radius: 12px;  padding: 20px;box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15);">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <div style="color: #6B7280; font-size: 12px; font-weight: 600; letter-spacing: 0.5px;">MY CANCELLED BOOKINGS</div>
                                <div style="font-size: 32px; font-weight: 700; color: #111827; margin: 10px 0;"><?= $status_cancelled ?></div>
                                <div style="color: #6B7280; font-size: 13px;">This month</div>
                            </div>
                            <div style="background: #9C27B0; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="material-icons" style="color: #fff;">people</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Body Grid -->
            <div class="row clearfix" style="display: flex; flex-wrap: wrap; align-items: stretch;">
                <!-- Main Charts and Table Container -->
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" style="display: flex; flex-direction: column;">
                    <div class="row clearfix">
                        <!-- Booking Trend -->
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="display: flex; flex-direction: column; gap: 20px;">
                            <div class="card" style="border-radius: 12px;box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15); margin-bottom: 20px;">
                                <div class="header" style="border-bottom: none; padding-bottom: 0;">
                                    <h2 style="font-weight: 700; color: #111827; font-size: 14px; letter-spacing: 0.5px;">BOOKING TREND</h2>
                                </div>
                                <div class="body">
                                    <canvas id="line_chart_transaction" height="200"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Popular Desks -->
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="display: flex; flex-direction: column; gap: 20px;">
                            <div class="card" style="border-radius: 12px;box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15); margin-bottom: 20px;">
                                <div class="header" style="border-bottom: none; padding-bottom: 0;">
                                    <h2 style="font-weight: 700; color: #111827; font-size: 14px; letter-spacing: 0.5px;">MY FAVORITE DESKS</h2>
                                </div>
                                <div class="body">
                                    <canvas id="line_chart_top_room" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Bookings -->
                    <div class="card" style="border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15); flex: 1; display: flex; flex-direction: column;">
                        <div class="header" style="border-bottom: none; padding-bottom: 0; flex-shrink: 0;">
                            <h2 style="font-weight: 700; color: #111827; font-size: 14px; letter-spacing: 0.5px;">ON-GOING BOOKINGS</h2>
                        </div>
                        <div class="body" style="padding-top: 0; flex: 1;">
                             <table id="id_tbl_ongoing" class="table table-hover" style="margin-top: 15px; width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Time</th>
                                        <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Room/Desk</th>
                                        <th style="border-bottom: 1px solid #E5E7EB; color: #6B7280; font-weight: 600;">Status</th>
                                    </tr>
                                </thead>    
                                <tbody></tbody>  
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- Today's Summary -->
                    <div class="card" style="border-radius: 12px;box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15); ">
                        <div class="header" style="border-bottom: none; padding-bottom: 0;">
                            <h2 style="font-weight: 700; color: #111827; font-size: 14px; letter-spacing: 0.5px;">TODAY'S SUMMARY</h2>
                        </div>
                        <div class="body">
                             <ul style="list-style: none; padding: 0; margin: 0;">
                                <li style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #F3F4F6;">
                                    <div style="display: flex; align-items: center; gap: 10px; color: #6B7280;"><i class="material-icons" style="font-size: 18px;">event</i> Date</div>
                                    <div style="font-weight: 600; color: #111827;"><?= date('d M Y', strtotime($filter_date)) ?></div>
                                </li>
                                <li style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #F3F4F6;">
                                    <div style="display: flex; align-items: center; gap: 10px; color: #6B7280;"><i class="material-icons" style="font-size: 18px;">people</i> Active Bookings</div>
                                    <div style="font-weight: 600; color: #111827;"><?= $checkins_today ?></div>
                                </li>
                                <li style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0;">
                                    <div style="display: flex; align-items: center; gap: 10px; color: #6B7280;"><i class="material-icons" style="font-size: 18px;">cancel</i> No-shows</div>
                                    <div style="font-weight: 600; color: #111827;"><?= $no_shows ?></div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Booking Status Donut Chart -->
                    <div class="card" style="border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15);">
                        <div class="header" style="border-bottom: none; padding-bottom: 0;">
                            <h2 style="font-weight: 700; color: #111827; font-size: 14px; letter-spacing: 0.5px;">BOOKING STATUS</h2>
                        </div>
                        <div class="body">
                            <?php
                            $val_available = $total_desks - $today_bookings;
                            $val_reserved = $today_bookings - $checkins_today;
                            $val_occupied = $checkins_today;
                            $val_unavailable = 0;

                            $pct_available = $total_desks > 0 ? round(($val_available / $total_desks) * 100) : 0;
                            $pct_reserved = $total_desks > 0 ? round(($val_reserved / $total_desks) * 100) : 0;
                            $pct_occupied = $total_desks > 0 ? round(($val_occupied / $total_desks) * 100) : 0;
                            $pct_unavailable = $total_desks > 0 ? round(($val_unavailable / $total_desks) * 100) : 0;
                            ?>
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="position: relative; width: 140px; height: 140px; flex-shrink: 0;">
                                    <canvas id="donut_chart_status"></canvas>
                                    <!-- Chart Center Text -->
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                        <div style="font-size: 20px; font-weight: 700; color: #111827; line-height: 1;"><?= $total_desks ?></div>
                                        <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">Total Desks</div>
                                    </div>
                                </div>
                                <div style="flex-grow: 1; padding-left: 20px;">
                                    <ul style="list-style: none; padding: 0; margin: 0;">
                                        <li style="margin-bottom: 8px;">
                                            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #4B5563;">
                                                <div style="width: 12px; height: 12px; background: #4CAF50; border-radius: 2px;"></div> Available
                                            </div>
                                            <div style="padding-left: 20px; font-size: 12px; color: #6B7280;"><?= $val_available ?> (<?= $pct_available ?>%)</div>
                                        </li>
                                        <li style="margin-bottom: 8px;">
                                            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #4B5563;">
                                                <div style="width: 12px; height: 12px; background: #FF9800; border-radius: 2px;"></div> Reserved
                                            </div>
                                            <div style="padding-left: 20px; font-size: 12px; color: #6B7280;"><?= $val_reserved ?> (<?= $pct_reserved ?>%)</div>
                                        </li>
                                        <li style="margin-bottom: 8px;">
                                            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #4B5563;">
                                                <div style="width: 12px; height: 12px; background: #E53935; border-radius: 2px;"></div> Occupied
                                            </div>
                                            <div style="padding-left: 20px; font-size: 12px; color: #6B7280;"><?= $val_occupied ?> (<?= $pct_occupied ?>%)</div>
                                        </li>
                                        <li>
                                            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #4B5563;">
                                                <div style="width: 12px; height: 12px; background: #9E9E9E; border-radius: 2px;"></div> Unavailable
                                            </div>
                                            <div style="padding-left: 20px; font-size: 12px; color: #6B7280;"><?= $val_unavailable ?> (<?= $pct_unavailable ?>%)</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card" style="border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15);">
                        <div class="header" style="border-bottom: none; padding-bottom: 0;">
                            <h2 style="font-weight: 700; color: #111827; font-size: 14px; letter-spacing: 0.5px;">QUICK ACTIONS</h2>
                        </div>
                        <div class="body">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <a href="<?= base_url('admin/deskbooking')?>" style="text-decoration: none; border: 1px solid #E5E7EB; border-radius: 8px; padding: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; color: #E53935; transition: all 0.2s; background: #fff;" onmouseover="this.style.background='#FFEBEE'" onmouseout="this.style.background='#fff'">
                                    <i class="material-icons" style="font-size: 28px;">add_box</i>
                                    <span style="font-weight: 600; color: #374151; font-size: 13px;">New Booking</span>
                                </a>
                                <a href="#" style="text-decoration: none; border: 1px solid #E5E7EB; border-radius: 8px; padding: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; color: #E53935; transition: all 0.2s; background: #fff;" onmouseover="this.style.background='#FFEBEE'" onmouseout="this.style.background='#fff'">
                                    <i class="material-icons" style="font-size: 28px;">qr_code_scanner</i>
                                    <span style="font-weight: 600; color: #374151; font-size: 13px;">Scan QR</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    
    <!-- Hidden data for Donut Chart -->
    <input type="hidden" id="id_total_desks" value="<?= $total_desks ?>">
    <input type="hidden" id="id_status_available" value="<?= $total_desks - $today_bookings ?>">
    <input type="hidden" id="id_status_reserved" value="<?= $today_bookings - $checkins_today ?>">
    <input type="hidden" id="id_status_occupied" value="<?= $checkins_today ?>">
    <input type="hidden" id="id_status_unavailable" value="0">

    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <?php $this->load->view("_partials/js_dashboard.php");?>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="<?= base_url()?>assets/external/daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url()?>assets/external/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/chartjs/Chart.bundle.js"></script>
    <script src="<?= base_url()?>assets/process/dashboard/index.js?v=<?= time() ?>"></script>
    </body>
</html>

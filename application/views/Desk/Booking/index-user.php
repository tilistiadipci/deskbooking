
<?php  

?>
<textarea id="id_modules" style="display: none;"><?php echo json_encode($modules) ?></textarea> 

<!DOCTYPE html>
<html lang="en">
<head>
   <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/external/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
   <link href="<?= base_url()?>assets/external/daterangepicker/daterangepicker.css" rel="stylesheet" />
    <style>
        .selectedRoom{
            border:2px solid blue;
        }
        .btn.active{
            background: #bf104b !important;
        }
        .img-booking{
            width: 100%;
            height: 400px;
        }
        .img-booking2{
            width: 100%;
            height: 350px;
        }
        .caption{
            padding:5px !important; 
        }
        td.day.disabled {
            color: white !important;
            background: #B2DFDB !important;
        }
        .select tbody tr.disabled {
            color: white !important;
            background: #B2DFDB !important;
        }
        .input-group{
            margin-bottom : 0px !important;
        }
        .lbl_text{
            font-size: 18px;

        }
        hr {
          margin: none;
          border: none;
          height: 2px;
          background-image: -webkit-linear-gradient(left, rgba(255,2,5,.5), rgba(137,11,138,.5), rgba(27,123,187,.5), rgba(47,195,44,.5), rgba(253,201,1,.5), rgba(255,91,2,.5), #ccc 15%, #ccc 85%, rgba(255,91,2,.5), rgba(253,201,1,.5), rgba(47,195,44,.5), rgba(27,123,187,.5), rgba(137,11,138,.5), rgba(255,2,5,.5));
                background-image: -moz-linear-gradient(left, rgba(255,2,5,.5), rgba(137,11,138,.5), rgba(27,123,187,.5), rgba(47,195,44,.5), rgba(253,201,1,.5), rgba(255,91,2,.5), #ccc 15%, #ccc 85%, rgba(255,91,2,.5), rgba(253,201,1,.5), rgba(47,195,44,.5), rgba(27,123,187,.5), rgba(137,11,138,.5), rgba(255,2,5,.5)); 
                background-image: -ms-linear-gradient(left, rgba(255,2,5,.5), rgba(137,11,138,.5), rgba(27,123,187,.5), rgba(47,195,44,.5), rgba(253,201,1,.5), rgba(255,91,2,.5), #ccc 15%, #ccc 85%, rgba(255,91,2,.5), rgba(253,201,1,.5), rgba(47,195,44,.5), rgba(27,123,187,.5), rgba(137,11,138,.5), rgba(255,2,5,.5));
                background-image: -o-linear-gradient(left, rgba(255,2,5,.5), rgba(137,11,138,.5), rgba(27,123,187,.5), rgba(47,195,44,.5), rgba(253,201,1,.5), rgba(255,91,2,.5), #ccc 15%, #ccc 85%, rgba(255,91,2,.5), rgba(253,201,1,.5), rgba(47,195,44,.5), rgba(27,123,187,.5), rgba(137,11,138,.5), rgba(255,2,5,.5));
            } 
        }
        .lbl_text_cost{
            font-size: 22px;
            font-style: italic;
        }
        @media screen and (min-width: 480px) {
            .img-booking{
                height: 200px;
            }
        }
        @media screen and (min-width: 240px) {
            .img-booking{
                height: 180px;
            }
        }
        
        hr { display: none !important; }
        
        /* Modern Table Styles (Red Theme) */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0 10px;
            width: 100%;
        }
        .table-modern thead th {
            border-bottom: none !important;
            border-top: none !important;
            color: #757575;
            font-weight: 500;
            padding: 15px;
            background: #fff;
        }
        .table-modern tbody tr {
            background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            border-radius: 8px;
        }
        .table-modern tbody td {
            border-top: none !important;
            border-bottom: none !important;
            padding: 15px;
            vertical-align: middle !important;
        }
        .table-modern tbody td:first-child {
            border-radius: 8px 0 0 8px;
        }
        .table-modern tbody td:last-child {
            border-radius: 0 8px 8px 0;
        }
        .id-link {
            color: #E53935;
            font-weight: 600;
            text-decoration: none;
        }
        .id-link:hover {
            color: #C62828;
            text-decoration: underline;
        }
        .title-text {
            font-weight: 600;
            color: #333;
        }
        .icon-text-row {
            display: flex;
            align-items: flex-start;
        }
        .icon-text-row i {
            margin-right: 10px;
            color: #9E9E9E;
            font-size: 20px;
            margin-top: 2px;
        }
        .icon-text-content {
            display: flex;
            flex-direction: column;
            line-height: 1.4;
        }
        .content-sub {
            color: #E53935;
            font-size: 13px;
            font-weight: 500;
        }
        
        /* Pill Badges */
        .status-pill {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }
        .status-pill::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }
        .pill-active { background: #E8F5E9; color: #2E7D32; }
        .pill-active::before { background: #2E7D32; }
        .pill-soon { background: #FFF3E0; color: #EF6C00; }
        .pill-soon::before { background: #EF6C00; }
        .pill-expired { background: #F5F5F5; color: #757575; }
        .pill-expired::before { background: #757575; }
        .pill-canceled { background: #FFEBEE; color: #C62828; }
        .pill-canceled::before { background: #C62828; }
        
        /* Avatar */
        .avatar-row {
            display: flex;
            align-items: center;
        }
        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #E3F2FD;
            color: #1976D2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            margin-right: 12px;
        }
        .avatar-circle.c-red { background: #FFEBEE; color: #C62828; }
        .avatar-circle.c-blue { background: #E3F2FD; color: #1976D2; }
        .avatar-circle.c-green { background: #E8F5E9; color: #2E7D32; }
        .avatar-circle.c-orange { background: #FFF3E0; color: #EF6C00; }
        .avatar-circle.c-purple { background: #F3E5F5; color: #7B1FA2; }
        
        /* Filter Pills */
        .filter-pills-container {
            display: flex;
            gap: 10px;
            background: #fff;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #E0E0E0;
            display: inline-flex;
            flex-wrap: wrap;
        }
        .btn-filter-pill {
            display: flex;
            align-items: center;
            padding: 6px 16px;
            border-radius: 20px;
            cursor: pointer;
            margin: 0;
            font-weight: 500;
            color: #616161;
            transition: all 0.2s;
            background: transparent;
            border: none;
            outline: none;
            white-space: nowrap;
        }
        .btn-filter-pill i {
            font-size: 16px;
            margin-right: 6px;
        }
        .btn-filter-pill:hover {
            background: #F5F5F5;
        }
        .btn-filter-pill.active {
            color: #E53935;
            background: #FFEBEE;
        }
        
        /* Action Dropdown */
        .action-dropdown-btn {
            background: transparent;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #757575;
            box-shadow: none;
            transition: all 0.2s;
        }
        .action-dropdown-btn:hover {
            background: #F5F5F5;
            color: #333;
        }
        .action-dropdown-btn i {
            font-size: 20px;
        }
        
        /* Clean Top Bar Layout */
        .top-bar-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .top-bar-right {
            display: flex;
            gap: 15px;
            flex: 1;
            justify-content: flex-end;
        }
        .daterange-modern {
            background: #fff;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            padding: 0 15px;
            height: 40px;
            display: flex;
            align-items: center;
            width: 250px;
        }
        .daterange-modern input {
            border: none;
            outline: none;
            width: 100%;
            background: transparent;
        }
        .daterange-modern i {
            color: #9E9E9E;
            margin-right: 10px;
        }
    </style>
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
    <section class="content">
         <div class="container-fluid">
            <div class="block-header">
                <h2><?= strtoupper($pagename) ?></h2>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <ul class="nav nav-tabs tab-nav-right" role="tablist">
                                <li role="presentation" class="active"><a href="#homepage" data-toggle="tab">Desk Transaction List</a></li>
                                <li role="presentation" ><a href="#booknowpage" data-toggle="tab">Book Now</a></li>
                        </ul>
                        <div class="body ">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="homepage">
                                    <div class="top-bar-modern">
                                        <!-- Left Side: Filter Pills -->
                                        <div class="filter-pills-container">
                                            <button type="button" class="btn-filter-pill active" data-value="all">
                                                <i class="material-icons">layers</i> All
                                            </button>
                                            <button type="button" class="btn-filter-pill" data-value="soon">
                                                <i class="material-icons">access_time</i> Soon
                                            </button>
                                            <button type="button" class="btn-filter-pill" data-value="active">
                                                <i class="material-icons">check_circle_outline</i> Active
                                            </button>
                                            <button type="button" class="btn-filter-pill" data-value="expired">
                                                <i class="material-icons">event_busy</i> Expired
                                            </button>
                                            <button type="button" class="btn-filter-pill" data-value="cancel">
                                                <i class="material-icons">cancel</i> Cancel
                                            </button>
                                        </div>

                                        <!-- Right Side: Date Picker and Export -->
                                        <div class="top-bar-right">
                                            <div class="daterange-modern">
                                                <i class="material-icons">date_range</i>
                                                <input id="daterangepicker" type="text" placeholder="Select Date Range">
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" style="height: 40px; border-radius: 8px; box-shadow: none; background: #fff; color: #333; border: 1px solid #e0e0e0;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    Export <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a href="javascript:exportPDF();" class=" waves-effect waves-block">Export PDF</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix" style="margin-top: 10px;">
                                        <div class="col-xs-12">
                                            <div style="width: 100%;">
                                                <table class="table table-hover table-modern" id="tbldata">
                                                    <thead>
                                                        <tr>
                                                            <th># <i class="material-icons" style="font-size:14px;vertical-align:middle;">unfold_more</i></th>
                                                            <th>ID <i class="material-icons" style="font-size:14px;vertical-align:middle;">unfold_more</i></th>
                                                            <th>Title <i class="material-icons" style="font-size:14px;vertical-align:middle;">unfold_more</i></th>
                                                            <th>Room - Desk <i class="material-icons" style="font-size:14px;vertical-align:middle;">unfold_more</i></th>
                                                            <th>Date Time <i class="material-icons" style="font-size:14px;vertical-align:middle;">unfold_more</i></th>
                                                            <th>Status <i class="material-icons" style="font-size:14px;vertical-align:middle;">unfold_more</i></th>
                                                            <th>Organizer <i class="material-icons" style="font-size:14px;vertical-align:middle;">unfold_more</i></th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                           
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="booknowpage">
                                     <!-- div row Choose Time -->
                                    <div class="row clearfix" >
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                            <label for="">Choose Time</label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                            <div class="btn-group btn-group-toggle btn-group-justified" data-toggle="buttons" data-toggle="buttons">
                                              <label onclick="chooseTimeCrt('today')"  class="btn bg-pink btn-lg waves-effect active">
                                                <input type="radio" name="options" id="id_choose_today" autocomplete="off" checked> Today
                                              </label>
                                              <label onclick="choosePickerDateCrt()" class="btn btn-lg bg-pink waves-effect ">
                                                <input type="radio" name="options" id="id_choose_date" autocomplete="off"> Pick Date <b id="id_pick_date_crt">as</b>
                                              </label>
                                              
                                            </div>
                                        </div>
                                    </div>
                                     <!-- div row Choose Time -->
                                     <!-- div row room -->
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                            <label for=""> </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                            <div class="row clearfix" id="id_area_booking_ad_room">
                                                
                                            </div>
                                        </div>
                                    </div> 
                                    <!-- div row room -->
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="id_mdl_reschedule" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Reschedule - <b id="id_res_text_name"></b></h4>
                        </div>
                        <div class="modal-body " id="id_mdl_reschedule_body">
                            <form id="frm_reschedule">
                                <label for="">Date</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input autocomplete="off" readonly="" required="" type="text"  id="id_frm_res_date_dummy"  class="form-control" placeholder="Date">
                                        <input type="hidden" name="date" id="id_frm_res_date"  class="form-control" >
                                        <input type="hidden" name="booking_id" id="id_frm_res_booking_id"  class="form-control" >
                                    </div>
                                </div>
                                <!-- START BLOCK -->
                                          <div class="row clearfix">
                                           
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <b class="lbl_text" >Position</b> <b style="color:red">*</b>
                                              <div class="input-group">
                                                  <select onchange="changeResPosition($(this))" id="id_frm_res_position" class="form-control show-tick" data-live-search="true" >
                                                    <option value=""></option>
                                                  </select>
                                              </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <b class="lbl_text">Desk Number</b> <b style="color:red">*</b>
                                              <div class="input-group">
                                                  <select onchange="changeResDeskNumber($(this))" id="id_frm_res_desk_number" class="form-control show-tick" data-live-search="true" >
                                                    <option value=""></option>
                                                  </select>
                                              </div>
                                            </div>
                                          </div>
                                          <!-- END BLOCK -->
                                          <br>
                                <div class="form-group">
                                     <div class="row clearfix">
                                        <input type="hidden" name="start" id="id_frm_res_start_input">
                                        <input type="hidden" name="end" id="id_frm_res_end_input">
                                        <div class="col-xs-6 align-left">
                                            <label for="">START</label>&nbsp;&nbsp;&nbsp;
                                            <button data-type="start" data-id="id_frm_res_start" onclick="openAlertPilihRes($(this))" type="button" class="btn btn-default btn-sm waves-effect">
                                                    <b id="id_frm_res_start" class="lbl_text">07:30 AM</b> <span class="caret"> &nbsp;</span>
                                            </button>
                                        </div>
                                        <div class="col-xs-6 align-right">
                                            <label for="">FINISH</label>&nbsp;&nbsp;&nbsp;
                                            <button data-type="end" data-id="id_frm_res_finish" onclick="openAlertPilihRes($(this))" type="button" class="btn btn-default btn-sm waves-effect">
                                                    <b id="id_frm_res_finish" class="lbl_text">07:30 AM</b> <span class="caret"> &nbsp;</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" style="display: none;" id="id_btn_res_submit" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                             <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6 ">
                                    <button onclick="clickSubmit('id_btn_res_submit')" type="button" class="btn btn-primary waves-effect " >RESCHEDULE  </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <input type="hidden" id="id_level" value="<?= $this->session->userdata('levelid-nya')?>">
    <input type="hidden" id="id_user" value="<?= $this->session->userdata('user-nya')?>">


    <textarea style="display: none;" id="id_settinggeneral" cols="30" rows="10"><?= $settinggeneral?></textarea>
    <textarea style="display: none;" id="id_invoicedata" cols="30" rows="10"><?= $invoice?></textarea>
    <?php $this->load->view("_partials/js_dashboard.php");?>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
    <!-- Moment Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <script src="<?= base_url()?>assets/external/numeral/numeral.min.js"></script>
    <script src="<?= base_url()?>assets/external/numeral/locale.id.js"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <!-- <script src="<?= base_url()?>assets/theme/plugins/jquery-editable/src/table-edits.js"></script> -->
    <script src="<?= base_url()?>assets/external/daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url()?>assets/external/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-steps/jquery.steps.js"></script>

    <!-- FullCalendar Plugin -->
    <link href="<?= base_url()?>assets/external/fullcalendar/main.min.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/external/fullcalendar/timeline.main.min.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/external/fullcalendar/resource-timeline.main.min.css" rel="stylesheet" />
    <script src="<?= base_url()?>assets/external/fullcalendar/main.min.js"></script>
    <script src="<?= base_url()?>assets/external/fullcalendar/interaction.main.min.js"></script>
    <script src="<?= base_url()?>assets/external/fullcalendar/timeline.main.min.js"></script>
    <script src="<?= base_url()?>assets/external/fullcalendar/resource-common.main.min.js"></script>
    <script src="<?= base_url()?>assets/external/fullcalendar/resource-timeline.main.min.js"></script>

    <script src="<?= base_url()?>assets/process/deskbooking/index.js?v=<?= time() ?>"></script>
    </body>
</html>

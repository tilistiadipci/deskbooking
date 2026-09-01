<?php  

?>
<textarea id="id_modules" style="display: none;"><?php echo json_encode($modules) ?></textarea>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/select.dataTables.min.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/fixedColumns.dataTables.min.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/external/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/external/daterangepicker/daterangepicker.css" rel="stylesheet" />
    <style>
        .form-group-filter{

            border-radius: 5px;
            transition: 0.3s;
            border: 1px solid #d5cfe0;
            padding: 5px;
            display: table;
            table-layout: fixed;
            width: 100%;
            min-height: 45px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        .form-group-filter-icon{
            padding-left: 5px;
            display: table-cell;
            vertical-align: middle;
            width: 14%;
        }
        .form-group-filter-form{
            display: table-cell;
            vertical-align: middle;
        }
        .btn-noshadow{
            background: #fff;
            box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.16), 0 0px 0px rgba(0, 0, 0, 0.0) !important;
    
        }
      

        .media:hover{
/*            background-color: #f4ecec;*/
            border: 2px solid #bf104b !important;
            cursor: pointer;
        }
        .media{
            padding: 5px;
            border-radius: 5px;
            transition: 0.3s;
            border: 2px solid transparent;

        }
        .media, .media-body{
            overflow: visible;
        }
        .label-bordered {
               border: 1px solid #333 !important;
               color : #333 !important;
               border-radius: 4px;
        }
        .selectedRoom{
            border:2px solid blue;
        }
        .btn.active{
            background: #bf104b !important;
        }
        .img-booking{
            width: 100%;
            height: 200px;
        }
        .img-booking2{
            width: 100%;
            height: 250px;
        }
        .caption{
            padding:5px !important; 
        }
        td.day.disabled {
            color: white !important;
            background: #B2DFDB !important;
        }
        .timedisabled {
          font-weight: bold !important;
         /* color: #fff !important;
          background: #bc0000 !important;
          text-transform: uppercase;*/
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
        .lbl_text_location{
            font-size: 12px;
        }
        .popover{
            max-width: 400px;
            
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

        .modal-dialog {
          position:absolute;
          top:50% !important;
          transform: translate(0, -50%) !important;
          -ms-transform: translate(0, -50%) !important;
          -webkit-transform: translate(0, -50%) !important;
          margin:auto 5%;
          width:90%;
          height:80%;
        }
        .modal-content {
          min-height:100%;
          position:absolute;
          top:0;
          bottom:0;
          left:0;
          right:0; 
        }
        .modal-body {
          position:absolute;
          top:45px; /** height of header **/
          bottom:45px;  /** height of footer **/
          left:0;
          right:0;
          overflow-y:auto;
        }
        .modal-footer {
          position:absolute;
          bottom:0;
          left:0;
          right:0;
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
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    <?= strtoupper($pagename) ?>
                </h2>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation" ><a href="#homepage" data-toggle="tab"><b>Booking List</b></a></li>
                        <li role="presentation" class="active"><a href="#booknowpage" data-toggle="tab"><b>Book Now</b></a></li>
                    </ul>
                </div>
            </div>
            <div class="row clearfix">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade " id="homepage">
                        <div class="card">
                            <div class="body">
                                <div class="row clearfix">
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">date_range</i>
                                            </span>
                                            <div class="form-line">
                                                <input id="id_schedule_daterange_search" type="text" class="form-control ">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">people</i>
                                            </span>
                                            <select class="form-control" id="id_schedule_employee_search" data-live-search="true">
                                                <option value="">All Organizer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">my_location</i>
                                            </span>
                                            <select onchange="ocFilterBuilding()" class="form-control" id="id_schedule_building_search" data-live-search="true">
                                                <option value="">All Building</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">map</i>
                                            </span>
                                            <select class="form-control" id="id_schedule_room_search" data-live-search="true">
                                                <option value="">All Room</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                        <button class="btn btn-success waves-effect" onclick="init()"><b>Filter</b></button>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-xs-12">
                                        <div class="">
                                            <table class="table table-hover" id="tbldata">
                                                <thead>
                                                    <th>#</th>
                                                    <th>Status</th>
                                                    <th style="width:80px !important;">Subject</th>
                                                    <th>Room</th>
                                                    <!-- <th >Date</th> -->
                                                    <th style="width:80px !important;">Time</th>
                                                    <th>Attendees</th>
                                                    <th>Organizer</th>
                                                    <th style="width:110px !important;"></th>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- <div class="table-responsive responsive">
                                            
                                        </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in active " id="booknowpage">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="card">
                                    <div class="body">
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="card">
                                    <div class="body">
                                        <!-- div row Choose Time -->
                                        <div class="row clearfix">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                                <label for="">Choose Time</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                <div class="btn-group btn-group-toggle btn-group-justified" data-toggle="buttons" data-toggle="buttons">
                                                    <label onclick="chooseTimeCrt('today')" class="btn bg-pink btn-lg waves-effect active">
                                                        <input type="radio" name="options" id="id_choose_today" autocomplete="off" checked> Today
                                                    </label>
                                                    <label onclick="choosePickerDateCrt()" class="btn btn-lg bg-pink waves-effect ">
                                                        <input type="radio" name="options" id="id_choose_date" autocomplete="off"> Pick Date <b id="id_pick_date_crt"></b>
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
        </div>
    </section>
    <div class="modal fade" id="id_mdl_reschedule" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="idmdlcrLabel">Reschedule Meeting - <b id="id_res_text_name"></b></h4>
                </div>
                <div class="modal-body " id="id_mdl_reschedule_body">
                    <form id="frm_reschedule">
                        <label for="">Date</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input autocomplete="off" readonly="" required="" type="text" id="id_frm_res_date_dummy" class="form-control" placeholder="Date">
                                <input type="hidden" name="date" id="id_frm_res_date" class="form-control">
                                <input type="hidden" name="timezone" id="id_frm_res_timezone" class="form-control">
                                <input type="hidden" name="booking_id" id="id_frm_res_booking_id" class="form-control">
                            </div>
                        </div>
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
                            <button onclick="clickSubmit('id_btn_res_submit')" type="button" class="btn btn-primary waves-effect ">RESCHEDULE MEETING</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="id_mdl_partisipant" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="id_mdl_partisipantLabel">Attendees From <b id="id_partisipant_title"></b></h4>
                </div>
                <div class="modal-body " id="id_mdl_partisipant_body">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-12">
                                    <h2>Attendees Internal</h2>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <br>
                            <div class="row clearfix">
                                <div class="col-xs-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="tbldataInternal">
                                            <thead>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Ext No.</th>
                                                <th>Status</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-12">
                                    <h2>Attendees External</h2>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <br>
                            <div class="row clearfix">
                                <div class="col-xs-12  ">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="tbldataEksternal">
                                            <thead>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Organization/Company</th>
                                                <!-- <th >Position</th> -->
                                                <th>Status</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row clearfix">
                        <div class="col-xs-6 align-left">
                        </div>
                        <div class="col-xs-6 ">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <textarea style="display: none;" id="id_settinggeneral" cols="30" rows="10"><?= $settinggeneral?></textarea>
    <textarea style="display: none;" id="id_invoicedata" cols="30" rows="10"><?= $invoice?></textarea>
    <textarea style="display: none;" id="id_category" cols="30" rows="10"><?= $category?></textarea>
    <textarea id="id_building" style="display: none;"><?= $building?></textarea>
    <textarea id="id_room" style="display: none;"><?= $room?></textarea>
    <textarea id="id_employee" style="display: none;"><?= $organizer?></textarea>
    <?php $this->load->view("_partials/js_dashboard.php");?>
    <!-- Datatables -->
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/dataTables.select.min.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/dataTables.fixedColumns.min.js"></script>
    <!-- end Datatables -->
    <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
    <!-- Moment Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment-timezone.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment-timezone-data.min.js"></script>
    <script src="<?= base_url()?>assets/external/numeral/numeral.min.js"></script>
    <script src="<?= base_url()?>assets/external/numeral/locale.id.js"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <!-- <script src="<?= base_url()?>assets/theme/plugins/jquery-editable/src/table-edits.js"></script> -->
    <script src="<?= base_url()?>assets/external/daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url()?>assets/external/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-steps/jquery.steps.js"></script>
    <script src="<?= base_url()?>assets/process/booking/index.js?datetime=<?= date(" YmdHis")?>
    ">
    </script>
    <script src="<?= base_url()?>assets/process/booking/booking_adv.js?datetime=<?= date(" YmdHis")?>
    ">
    </script>
</body>

</html>
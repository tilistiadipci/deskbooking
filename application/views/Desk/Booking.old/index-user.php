<textarea id="id_modules" style="display: none;"><?php echo json_encode($modules) ?></textarea> 

<?php  
?>
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
                        <div class="body">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active " id="homepage">
                                    <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="height: 40px;">
                                            <div class="radiogroup">
                                                <input id="cal_option_all" label="All" type="radio" name="gender" value="all" checked="" class="filter_status">
                                                <input id="cal_option_soon" label="Soon" type="radio" name="gender" value="soon" class="filter_status">
                                                <input id="cal_option_active" label="Active" type="radio" name="gender" value="active" class="filter_status">
                                                <input id="cal_option_expired" label="Expired" type="radio" name="gender" value="expired" class="filter_status">
                                                <input id="cal_option_cancel" label="Cancel" type="radio" name="gender" value="cancel" class="filter_status">
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle btn-block" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    Export Data <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <!-- <li><a href="javascript:exportExcell();" class=" waves-effect waves-block">Export Excell</a></li>
                                                    <li role="separator" class="divider"></li> -->
                                                    <li><a href="javascript:exportPDF();" class=" waves-effect waves-block">Export PDF</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">date_range</i>
                                                </span>
                                                <div class="form-line">
                                                    <input id="daterangepicker" type="text" class="form-control " >
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix ">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  table-responsive responsive">
                                            <table class="table table-hover" id="tbldata">
                                                <thead>
                                                        <th>#</th>
                                                        <th>ID</th>
                                                        <th>Title</th>
                                                        <th>Room - Desk</th>
                                                        <th>Date Time</th>
                                                        <th>Status</th>
                                                        <th>Organizer</th>
                                                        <!-- <th></th> -->
                                                        <th>Action</th>
                                                    </thead>
                                                <tbody>
                                                           
                                            </tbody>
                                            </table>
                                        </div>
                                        
                                    </div>
                                    <!-- Yoyr Meeting List -->
                                    <h4 style="display:none;">Other Meeting List</h4>
                                    <div class="row clearfix" style="display:none;">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">date_range</i>
                                                </span>
                                                <div class="form-line">
                                                    <input id="daterangepicker_other" type="text" class="form-control " >
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        </div>
                                    </div>
                                    <div class="row clearfix " style="display:none;">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  table-responsive responsive">
                                            <table class="table table-hover" id="tbldata_other">
                                                <thead>
                                                        <th>#</th>
                                                        <th>ID</th>
                                                        <th>Title</th>
                                                        <th>Room</th>
                                                        <th>Desk</th>
                                                        <th>Date Time</th>
                                                        <th>Status</th>
                                                        <th>Organizer</th>
                                                        <!-- <th></th> -->
                                                        <th></th>
                                                    </thead>
                                                <tbody>
                                                           
                                            </tbody>
                                            </table>
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
                            <h4 class="modal-title" id="idmdlcrLabel">Reschedule Meeting - <b id="id_res_text_name"></b></h4>
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
                                    <button onclick="clickSubmit('id_btn_res_submit')" type="button" class="btn btn-primary waves-effect " >RESCHEDULE MEETING</button>
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
    <script src="<?= base_url()?>assets/process/deskbooking/index.js"></script>
    </body>
</html>

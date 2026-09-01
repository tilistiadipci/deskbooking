
<?php  
// date_default_timezone_set("Asia/Jakarta");
// echo date('w');
// die();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
   <link href="<?= base_url()?>assets/external/daterangepicker/daterangepicker.css" rel="stylesheet" />
    
    <!-- <link href="<?= base_url()?>assets/theme/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" /> -->
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
                <h2><?= strtoupper($pagename) ?></h2>
            </div>
            <!-- DIV  -->
            <div class="row clearfix">

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box hover-expand-effect">
                        <div class="icon  bg-orange ">
                            <i class="material-icons">event</i>
                        </div>
                        <div class="content">
                            <div class="text"><?= strtoupper(date("d M Y"))?></div>
                            <div class="number count-to" id="time1">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box  hover-expand-effect">
                        <div class="icon bg-light-green">
                            <i class="material-icons">toc</i>
                        </div>
                        <div class="content">
                            <div class="text">TOTAL MEETING</div>
                            <div class="number count-to" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20" id="id_count_total_meeting">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box  hover-expand-effect">
                        <div class="icon bg-light-green">
                            <i class="material-icons">toc</i>
                        </div>
                        <div class="content">
                            <div class="text">TOTAL ORGANIZER</div>
                            <div class="number count-to" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20" id="id_count_total_organizer">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box  hover-expand-effect">
                        <div class="icon bg-light-green">
                            <i class="material-icons">toc</i>
                        </div>
                        <div class="content">
                            <div class="text">TOTAL ATTENDEES</div>
                            <div class="number count-to" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20" id="id_count_total_attendees">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <!-- <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>Room Report</h2>
                                </div>
                                <div class="col-xs-12 col-sm-6 align-right">
                    
                                </div>
                            </div>
                        </div> -->
                        <div class="body">
                            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                                <li onclick="changeTabs('roomusage')" role="presentation" class="active" ><a href="#roomusage" data-toggle="tab" >ROOM USAGE</a></li>
                                <li  onclick="changeTabs('organizer')"  role="presentation"><a href="#organizer" data-toggle="tab">ORGANIZER USAGE</a></li>
                                <li  onclick="changeTabs('attendees')"  role="presentation" ><a href="#attendees" data-toggle="tab">ATTENDEES</a></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade  in active" id="roomusage">
                                    <?php $this->load->view("Admin/Report/room/view_room_usage.php", array('pagename'=>$pagename));?>
                                </div>
                                <div role="tabpanel" class="tab-pane fade " id="organizer">
                                    <?php $this->load->view("Admin/Report/room/view_organizer.php", array('pagename'=>$pagename));?>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="attendees">
                                     <?php $this->load->view("Admin/Report/room/view_attendees.php", array('pagename'=>$pagename));?>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="settings">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- # END MODAL CREATE  -->

    <?php $this->load->view("_partials/js_dashboard.php");?>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
    <!-- Moment Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment-timezone.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment-timezone-data.min.js"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <!-- <script src="<?= base_url()?>assets/theme/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script> -->
    <script src="<?= base_url()?>assets/external/daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url()?>assets/external/numeral/numeral.min.js"></script>
    <script src="<?= base_url()?>assets/external/numeral/locale.id.js"></script>
    <script src="<?= base_url()?>assets/external/sheetjs/xlsx.full.min.js"></script>


    <textarea  id="id_modules" style="display: none;"> <?= json_encode($modules)?></textarea> 
    <textarea  id="id_statusInvoice" style="display: none;"><?= $statusInvoiceJson?></textarea>
    <textarea  id="id_building" style="display: none;"><?= $building?></textarea>
    <textarea  id="id_room" style="display: none;"><?= $room?></textarea>
    <textarea  id="id_employee" style="display: none;"><?= $employee?></textarea>
    <!-- base url -->
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <script type="text/javascript">
        var localtimezone = moment.tz.guess();
        var gBuilding = JSON.parse($('#id_building').val());
        var gRoom = JSON.parse($('#id_room').val());
        var gEmployee = JSON.parse($('#id_employee').val());
        var initialTab =  "roomusage";
        var gGlobalTabs = "roomusage";
        function changeTabs(argument) {
            console.log(argument)
            if(gGlobalTabs != argument){
                gGlobalTabs = argument;
            }
        }


        // numeral.locale('id');
    </script>
    <script>
        // var localtimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        var globalStatusInvoice = [];
        $(function(){
            getStatus();
            // init();
            // getAutomation();
            // getFacility();
            intrTime()
            initGlobalData()
            initialLoad();
        }) 
        function initGlobalData(){
            initBuilding();
            initEmployee();
            initRangeDate();
           
        }
        function initialLoad(){
            gGlobalTabs = "roomusage";
            initGlobalData()
            initRoom();
            gGlobalTabs = "organizer";
            initGlobalData();
            filterOrganizer()
            gGlobalTabs = "attendees";
            initGlobalData();
            filterAttendees()

            gGlobalTabs = initialTab;
            initGlobalData();

        }
        function intrTime(){
            setInterval(
                function(){
                var tm = moment().format('hh:mm A');
                $('#time1').html(tm);
                },500
            );
        }

        function getModule(){
            var modules = $('#id_modules').val();
            return JSON.parse(modules)
        }
        function initRangeDate() {
            // console.log(`#id_${gGlobalTabs}_daterange_search`)
            $(`.input-group #id_${gGlobalTabs}_daterange_search`).daterangepicker({
                "showDropdowns": true,
                "showWeekNumbers": true,
                "showISOWeekNumbers": true,
                "opens": "center",
                // "drops": "up",
                "startDate": moment().subtract(29, 'days').format('MM/DD/YYYY'),
                "endDate": moment().format('MM/DD/YYYY'),
                // "autoApply": true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                },
                "alwaysShowCalendars": true,
            }, function(start, end, label) {
                // initRoom(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
            });
        }
        function initBuilding(){
            var htmlBuilding = '<option selected value="">All Building</option>';
            var htmlRoom = '<option selected value="">All Room</option>';
            for(var x in gBuilding){
                htmlBuilding += '<option value="'+gBuilding[x].id+'">'+gBuilding[x].name+'</option>';
            }
            for(var x in gRoom){
                htmlRoom += '<option value="'+gRoom[x].radid+'">'+gRoom[x].name+'</option>';
            }
            $(`#id_${gGlobalTabs}_building_search`).html(htmlBuilding);
            $(`#id_${gGlobalTabs}_room_search`).html(htmlRoom);
            select_enable();
        }
        function initEmployee(){
            var html = '<option selected value="">All Employee</option>';
            var s = gEmployee.length == 1?"selected":"";
            for(var x in gEmployee){
                html += `<option ${s} value="${gEmployee[x].id}">${gEmployee[x].name}</option>`;
            }
            $(`#id_${gGlobalTabs}_employee_search`).html(html);
            select_enable();
        }
        
        function getTimeFromMins(mins) {
            if (mins >= 24 * 60 || mins < 0) {
                throw new RangeError("Valid input should be greater than or equal to 0 and less than 1440.");
            }
            var h = mins / 60 | 0,
                m = mins % 60 | 0;
            var dd = moment.utc().hours(h).minutes(m).format("HH:mm");
            spDd = dd.split(":");
            var frm = "";
            if(spDd[0] != "00" ){
                frm += (spDd[0] -0)+" hour ";
            }
            if(spDd[1] != "00" ){
                frm += (spDd[1] -0)+" minute ";
            }
            return frm;
        }
        function getStatus(){
            globalStatusInvoice = [];
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"report/get/status-invoice",
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                      globalStatusInvoice = data.collection;
                      console.log(globalStatusInvoice);
                      initRoom();
                    }else{
                        var msg = "Your session is expired, login again !!!";
                        showNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        }
        

        function initTable(selector) {
            selector.DataTable();
        }

        function clearTable(selector) {
            if(selector != null){
                selector.DataTable().destroy();
            }
        }

        function select_enable() {
            $('select').selectpicker("refresh");
            $('select').selectpicker("initialize");
        }

        function enable_datetimepicker() {
            $('.timepicker').bootstrapMaterialDatePicker({
                format: 'HH:mm',
                clearButton: true,
                date: false
            });
        }

        function loadingg(title = "", body = "") {
            Swal.fire({
                title: title,
                html: body,
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            });
        }

        function errorAjax(xhr, ajaxOptions, thrownError){
            $('#id_loader').html('');
            if(ajaxOptions == "parsererror"){
                var msg = "Status Code 500, Error Server bad parsing";
                showNotification('alert-danger', msg,'bottom','left')
            }else{
                var msg ="Status Code "+ xhr.status + " Please check your connection !!!";
                showNotification('alert-danger', msg,'bottom','left')
            }
        }
    </script>
    <script src="<?= base_url()?>assets/process/report/room.room_usage.js"></script>
    <script src="<?= base_url()?>assets/process/report/room.organizer.js"></script>
    <script src="<?= base_url()?>assets/process/report/room.attendees.js"></script>

    </body>
</html>

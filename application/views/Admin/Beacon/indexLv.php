
<?php  
// date_default_timezone_set("Asia/Jakarta");
// echo date('w');
// print_r($modules);
// die();
?>
<textarea id="id_modules" style="display: none;"> 
    <?= json_encode($modules)?>
</textarea> 
<!DOCTYPE html>
<html lang="en">
<head>
   <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/theme/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/external/filepond/filepond.min.css" rel="stylesheet">
    <style>
        .demo-filepond-wrapper{
            height: 300px;
            padding-left: 20px;
            padding-top: 20px;
            padding-right: 20px;
            border: dashed 2px grey;
            background: white center no-repeat;
            background-size: cover;
            text-align: center;
            display: block;
            cursor: pointer;
        }
        .image-box {
            position: relative;
            height: 200px;
            width: 100%;
            position: relative;
            background-color: #eee;
            border: dotted 1px grey;
        }
        .img_overlay{
            opacity: 1;
            width: 100%;
            height: 100%;
            display: block;
        }
        .img_overlay:hover{
            opacity: 0.6;
            transition: 1s;
        }
        .image-box:hover .overlay{
            opacity: 1;
            /*width: 150px;*/
        }
        .imgFilter{height: 240px;width: 180px;}
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
                <h2><?= strtoupper($pagename) ?></h2>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <ul class="nav nav-tabs tab-nav-right" role="tablist">
                            <li role="presentation" class="active"><a href="#home" data-toggle="tab">Beacon List</a></li>
                            <li role="presentation"><a href="#beacon-user" data-toggle="tab">Beacon Card User Monitoring</a></li>
                            <li role="presentation"><a target="__blank" href="<?= base_url()?>beacon-monitor-room" >Beacon Map Position</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active table-responsive responsive" id="home">
                                
                                <div class="body">
                                    <div class="row clearfix">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <button type="button"  class="btn btn-primary btn-sm  waves-effect" onclick="clickFilterFunc()">Filter</button>
                                        </div>
                                    </div>
                                    <div class=" table-responsive responsive">
                                        <table class="table table-hover" id="tbldata">
                                        <thead>
                                                <th>#</th>
                                                <th>Date & Time</th>
                                                <!-- <th>MAC</th>
                                                <th>QR</th> -->
                                                <th>Floor</th>
                                                <th>Room</th>
                                                <th>Card No</th>
                                                <th>Employee</th>
                                                <th>Division</th>
                                                <th>Department</th>
                                                <th>Transaction Status</th>
                                                <th>
                                                    
                                                </th>
                                        </thead>
                                        <tbody>
                                                   
                                        </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane table-responsive responsive" id="beacon-user">
                                <div class="body" id="list-beacon-user">
                                    <div class="row clearfix">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <button type="button"  class="btn btn-primary btn-sm  waves-effect" onclick="clickFilterFunc()">Filter</button>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <table class="table table-bordered table-condensed" id="tblbeaconuser">
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>  
                             </div>
                        </div>
                        
                    </div>
                </div>
               
            </div>

        </div>
    </section>

    <div class="modal fade" id="id_mdl_filter" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Live Monitor Filter</h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                            <form id="frm_filter">
                                <label for="">Time Refresh</label>
                                <div class="form-group">
                                    <select  id="id_filter_time" class="form-control show-tick selectpickerr"></select>
                                </div>
                                <label for="">Floor</label>
                                <div class="form-group">
                                    <select  id="id_filter_floor" class="form-control show-tick selectpickerr"></select>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" id="id_filter_alarm" checked />
                                    <label for="id_filter_alarm">Show Alarm Only</label>
                                </div>
                                <br>
                                <button type="submit" id="id_btn_filter_submit" class="btn btn-primary m-t-15 waves-effect" style="display: none;" >Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6">
                                    <button onclick="clickSubmit('id_btn_filter_submit')" type="button" class="btn btn-primary waves-effect " >APPLY</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <textarea id="id_building_json" style="display: none;"><?= $data['building']?></textarea>
    <textarea id="id_floor_json" style="display: none;"><?= $data['floor']?></textarea>
    <textarea id="id_room_json" style="display: none;"><?= $data['room']?></textarea>

    
    <!-- # END MODAL CREATE  -->
    <?php $this->load->view("_partials/js_dashboard.php");?>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
    <!-- Moment Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="<?= base_url()?>assets/external/numeral/numeral.min.js"></script>
    <!-- <script src="<?= base_url()?>assets/external/numeral/locale.id.js"></script> -->
    <!-- Input Mask Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <!-- <script src="<?= base_url()?>assets/process/access/index.js"></script> -->
    <script type="text/javascript">
        var imgDefault = '<?= base_url()?>'+'assets/theme/images/no_avatar.jpg';
        var gBuilding = JSON.parse($('#id_building_json').val());
        var gFloor = JSON.parse($('#id_floor_json').val());
        var gRoom = JSON.parse($('#id_room_json').val());
        var gFilter = {
            time_refresh : 1,
            floor : "",
            is_alarm_only : 0,
        }

        var gListTimeRefresh = [1,5,10,20,30,60];
        // var gRoom =  [1,5,10,20,30,60];
        var cTimedelay;
        function runInitial(){
            if(cTimedelay != null){
                clearInterval(cTimedelay)
            }
            cTimedelay = setInterval(function(){
                init();
            }, gFilter.time_refresh*1000)
        }
        function getBuilding(id){
            var xxx = null;
            for(var x in gBuilding){
                if(gBuilding[x].id == id){
                    xxx = gBuilding[x]
                }
            }
            return xxx;
        }
        function getFloor(id){
            var xxx = null;
            for(var x in gFloor){
                if(gFloor[x].id == id){
                    xxx = gFloor[x]
                }
            }
            return xxx;
        }
        $(function(){
            init();
            runInitial();
        }) 
        function clickSubmit(id){
            $('#'+id).click();
        }
        function getModule(){
            var modules = $('#id_modules').val();
            return JSON.parse(modules)
        }
        function initTable(selector){
            selector.DataTable({"paging": false});
        }
        function clearTable(selector){
            selector.DataTable().destroy();
        }
        function select_enable(){
            $('.selectpickerr').selectpicker("refresh");
            $('.selectpickerr').selectpicker("initialize");
        }
        function enable_datetimepicker(){
            $('.timepicker').bootstrapMaterialDatePicker({
                format: 'HH:mm',
                clearButton: true,
                date: false
            });
        }

         
        
        function clickFilterFunc(){
            
            var htmlR = '';
            var htmlF = '';
                htmlF +='<option value="">ALL</option>';
            for(var x in gListTimeRefresh){
                var sl = gFilter.time_refresh == gListTimeRefresh[x] ? 'selected':'';
                htmlR +='<option '+sl+' value="'+gListTimeRefresh[x]+'">'+gListTimeRefresh[x]+'</option>';
            }
            for(var x in gFloor){
                var bb = getBuilding(gFloor[x].building_id) == null ? {name : ""} : getBuilding(gFloor[x].building_id);
                var sl = gFilter.floor == gFloor[x].id ? 'selected':'';
                htmlF +='<option '+sl+' value="'+gFloor[x].id+'">'+bb.name+' - '+gFloor[x].name+'</option>';
            }
            $('#id_filter_time').html(htmlR);
            $('#id_filter_floor').html(htmlF);
            var alarmCk = gFilter.is_alarm_only == 0?false : true;
            $('#id_filter_alarm').prop('checked',alarmCk);
            select_enable();
            $('#id_mdl_filter').modal('show')
            
        }
        $('#frm_filter').submit(function(e){
            e.preventDefault()
            gFilter.time_refresh = $('#id_filter_time').val() - 0;
            gFilter.floor = $('#id_filter_floor').val();
            gFilter.is_alarm_only = $('#id_filter_alarm').is(':checked');
            runInitial()
            console.log(gFilter)
            $('#id_mdl_filter').modal('hide')
        })
        function init(){
            // initEmpNoBeacon("",$('#id_crt_beacon_employee'));
            var bs = $('#id_baseurl').val();
            var modules = getModule();
            var params = 
            $.ajax({
                url : bs+"beacon-live-monitor/get/data",
                type : "POST",
                dataType: "json",
                data:gFilter,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        clearTable($('#tbldata'));
                        var html = "";
                        var html22 = "";
                        var nn = 0;
                        $.each(data.collection, function(index, item){
                            nn++;
                            var ename = "";
                            if(item.employee_id != null){
                                ename = item.employee_name;
                            }
                            var d = moment(item.datetime).format("YYYY-MM-DD HH:mm:ss");  
                            html += '<tr>'
                            html += '<td>'+nn+'</td>';
                            html += '<td style="width:150px;">'+d+'</td>';
                            // html += '<td>'+item.beacon_mac+'</td>';
                            // html += '<td>'+item.beacon_qr+'</td>';
                            html += '<td>'+(item.floor_name == null ? "" : item.floor_name)+'</td>';
                            html += '<td>'+(item.room_id == null ? "" : "")+'</td>';
                            html += '<td>'+item.beacon_card_no+'</td>';
                            html += '<td>'+ename+'</td>';

                            html += '<td>'+item.company_name+'</td>';
                            html += '<td>'+item.department_name+'</td>';
                            html += '<td>'+ename+'</td>';
                         
                            html += '<td>';
                            html += '<button \
                                 onclick="updateData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-id="'+item.beacon_name+'" \
                                 type="button" class="btn btn-info waves-effect">Detail</button>';
                           
                            html += '</td>';
                            html += '</tr>';
                            var uP = bs + "assets/employee/"+item.employee_photo;
                            var photo = item.employee_photo == null || item.employee_photo == ""? imgDefault : uP;
                            var ff = getFloor(item.floor_id) == null ? {name : ""} : getFloor(item.floor_id);
                            var bb = getBuilding(ff.building_id) == null ? {name : ""}  : getFloor(item.floor_id);
                            var room= item.room_id == null ? " " : " - " +item.room_name;
                            var location = bb.name  + ", "+ff.name  +room;
                            var alarmMsg = item.alarm == 0 ? "Valid access" : "Invalid & People Pass restricted area";
                            html22 += `<table class="table table-bordered table-condensed">
                                                <tbody>
                                                    <tr>
                                                        <table class="table table-striped table-condensed" style=" border-collapse: collapse;">
                                                          <tr>
                                                            <td rowspan="5" style="width: 180px;" >
                                                                 <img class="imgFilter" src="${photo}">
                                                             </td>
                                                            <td>Location</td>
                                                            <td>${location}</td>
                                                            <td>Date & Time</td>
                                                            <td>${d}</td>
                                                          </tr>
                                                          <tr>
                                                            <td colspan="4"><b>${alarmMsg}</b></td>
                                                          </tr>
                                                          <tr>
                                                            <td>Name</td>
                                                            <td>${ename}</td>
                                                            <td>Department</td>
                                                            <td>${item.department_name}</td>
                                                          </tr>
                                                          <tr>
                                                            <td>Card & Beacon No</td>
                                                            <td>${item.beacon_card_no}</td>
                                                            <td>Division/Company</td>
                                                            <td>${item.company_name}</td>
                                                          </tr>
                                                          <tr>
                                                            <td>NIK/Employee ID/Staff No</td>
                                                            <td>${item.employee_nik}</td>
                                                            <td>Email Address</td>
                                                            <td>${item.employee_email==null ? "" : item.employee_email}</td>
                                                          </tr>
                                                        </table>
                                                    </tr>
                                                </tbody>
                                            </table>`;
                        })
                        $('#tbldata tbody').html(html);
                        $('#tblbeaconuser tbody').html(html22);
                        initTable($('#tbldata'));


                    }else{
                        var msg = "Your session is expired, login again !!!";
                        swalShowNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        }
        function createData(){
            // initEmpNoBeacon("",$('#id_crt_beacon_employee'));
            $('#id_mdl_create').modal('show');
            enable_datetimepicker()
            select_enable()
        }

        function updateData(t){
            var id = t.data('id');
            var bs = $('#id_baseurl').val();
            var modules = getModule();
            $.ajax({
                url : bs+"beacon-tag/get/data",
                type : "POST",
                data : {
                    id : id
                },
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        var row = data.collection;
                        initEmpNoBeacon(row.beacon_employee, $('#id_edt_beacon_employee'));

                        $('#id_edt_beacon_id').val(row.id);
                        $('#id_edt_beacon_name').val(row.beacon_name);
                        $('#id_edt_beacon_mac').val(row.beacon_mac);
                        $('#id_edt_beacon_qr').val(row.beacon_qr);
                        $('#id_edt_beacon_card_no').val(row.beacon_card_no);
                        $('#id_mdl_update').modal('show');
                    }else{
                        var msg = "Your session is expired, login again !!!";
                        swalShowNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
            enable_datetimepicker()
            select_enable()
        }
        function removeData(t){
            var id = t.data('id');
            var name = t.data('name');
             Swal.fire({
                title:'Are you sure you want delete '+name+'?',
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete !',
                cancelButtonText: 'Cancel !',
                reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        var bs = $('#id_baseurl').val();
                        $.ajax({
                            url : bs+"beacon-tag/post/delete",
                            type : "POST",
                            data : {id:id},
                            dataType: "json",
                            beforeSend: function(){
                                $('#id_loader').html('<div class="linePreloader"></div>');
                            },
                            success:function(data){
                                if(data.status == "success"){
                                    Swal.fire({
                                    title:'Message',
                                    text: data.msg,
                                    type: "success"})
                                    init();
                                    
                                }else{
                                    var msg = "Your session is expired, login again !!!";
                                    swalShowNotification('alert-danger', msg,'top','center')
                                }
                                $('#id_loader').html('');
                            },
                            error: errorAjax
                        })
                    }
                else{

                }
            })
        }
        $('#frm_create').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title:'Are you sure you want save it?',
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Save !',
                cancelButtonText: 'Cancel !',
                reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        saveCreate()
                    }
                else{

                }
            })
        })
        $('#frm_update').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title:'Are you sure you want save it?',
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Save !',
                cancelButtonText: 'Cancel !',
                reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        saveUpdate()
                    }
                else{

                }
            })
        })
        $('#frm_upload').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title:'Are you sure you want upload it?',
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Upload !',
                cancelButtonText: 'Cancel !',
                reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        uploadCreate()
                    }
                else{

                }
            })
        })
        function saveCreate(){
            var form = $('#frm_create').serialize();
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"beacon-tag/post/create",
                type : "POST",
                data : form,
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        Swal.fire({
                        title:'Message',
                        text: data.msg,
                        type: "success"})
                        init();
                        $('#id_mdl_create').modal('hide');
                        $('#frm_create')[0].reset()
                        
                    }else{
                        var msg = "Your session is expired, login again !!!";
                        swalShowNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        }
        
        function saveUpdate(){
            var form = $('#frm_update').serialize();
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"beacon-tag/post/update",
                type : "POST",
                data : form,
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        Swal.fire({
                        title:'Message',
                        text: data.msg,
                        type: "success"})
                        init();
                        $('#id_mdl_update').modal('hide');
                        $('#frm_update')[0].reset()
                        
                    }else{
                        var msg = "Your session is expired, login again !!!";
                        swalShowNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        }

         function uploadCreate(){
            var form = $('#frm_upload')[0];
            var formdata = new FormData(form)
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"beacon-tag/post/upload",
                type : "POST",
                data : formdata,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        Swal.fire({
                        title:'Message',
                        text: data.msg,
                        type: "success"})
                        init();
                        // $('#id_mdl_create').modal('hide');
                        $('#frm_upload')[0].reset()
                        
                    }else{
                        var msg = "Your session is expired, login again !!!";
                        swalShowNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        }
        
    </script>
    <script type="text/javascript">
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
        function swalShowNotification(icon,title,loc = "", loc2="" ){
            var ic = "";
            if(icon == "alert-success"){
                ic = "success";
            }else if(icon == "alert-danger"){
                ic = "danger";
            }else if(icon == "alert-warning"){
                ic = "warning";
            }else if(icon == "alert-info"){
                ic = "info";
            }
            Swal.fire(
              title,
              '',
              ic
            )
        }
    </script>
    
    </body>
</html>

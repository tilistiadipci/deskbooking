
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
                            <li role="presentation" class="active"><a href="#home" data-toggle="tab">Beacon Gateway</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active table-responsive responsive" id="home">
                                <div class="body table-responsive responsive">
                                    <table class="table table-hover" id="tbldata">
                                        <thead>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Room</th>
                                            <th>MAC</th>
                                            <th>IP</th>
                                            <th>Location</th>
                                            <th>Location Pixel</th>
                                            <th>
                                                <button 
                                                onclick="createData($(this))" 
                                                type="button" class="btn btn-primary waves-effect ">
                                                <i class="material-icons">add_circle</i> CREATE
                                                </button>
                                            </th>
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

        </div>
    </section>
    <div class="modal fade" id="id_mdl_create" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Create Beacon Gateway </h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                            <form id="frm_create">
                                <label for="">Name <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="name" id="id_crt_name" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                <label for="">Building <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <select required onchange="onchangeBuilding('crt','','')" title="Choose one of the building..." data-live-search="true"  id="id_crt_building" class="form-control selectpickerr show-tick"></select>
                                </div>
                                <label for="">Floor <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <select required title="Choose one of the floor..." data-live-search="true" name="floor_id" id="id_crt_floor_id" class="form-control selectpickerr show-tick"></select>
                                </div>
                                <label for="">Room</label>
                                <div class="form-group">
                                    <select title="Choose one of the floor..." data-live-search="true" name="room_id" id="id_crt_room_id" class="form-control selectpickerr show-tick"></select>
                                </div>
                                <label for="">Mac <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" required name="mac" id="id_crt_mac"   class="form-control ip" placeholder="Gateway/Base Station Mac Address">
                                    </div>
                                </div>
                                <label for="">IP Address</label>
                                <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" required name="ip" id="id_crt_ip"  class="form-control ip" placeholder="IP Gateway/Base Station">
                                        </div>
                                </div>
                                <div class="form-group">
                                       <button type="button" onclick="openEditorCRTPointerWindow()" class="btn btn-primary btn-block waves-effect" >Pointer Gateway/Base Station</button>
                                </div>
                                <label for="">Real Location XY (meter) <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input required readonly type="text" name="location" id="id_crt_location"   class="form-control" placeholder="Ex. 2,3">
                                    </div>
                                </div>
                                <label for="">Real Location XY (pixel) <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input required readonly type="text" name="location_px" id="id_crt_location_px"   class="form-control" placeholder="Ex. 20,30">
                                    </div>
                                </div>
                                
                                <button type="submit" id="id_btn_crt_submit" class="btn btn-primary m-t-15 waves-effect" style="display: none;" >Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6">
                                    <button onclick="clickSubmit('id_btn_crt_submit')" type="button" class="btn btn-primary waves-effect " >SAVE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
    <div class="modal fade" id="id_mdl_update" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Update Beacon Tag</h4>
                        </div>
                        <div class="modal-body " id="">
                           <form id="frm_update">
                                
                                <label for="">Name <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="hidden" autocomplete="off" name="id" id="id_edt_id" required=""  class="form-control" placeholder="">
                                        <input type="text" autocomplete="off" name="name" id="id_edt_name" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                <label for="">Building <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <select required onchange="onchangeBuilding('edit','','')" title="Choose one of the building..." data-live-search="true"  id="id_edt_building" class="form-control selectpickerr show-tick"></select>
                                </div>
                                <label for="">Floor <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <select required title="Choose one of the floor..." data-live-search="true" name="floor_id" id="id_edt_floor_id" class="form-control selectpickerr show-tick"></select>
                                </div>
                                <label for="">Room</label>
                                <div class="form-group">
                                    <select title="Choose one of the floor..." data-live-search="true" name="room_id" id="id_edt_room_id" class="form-control selectpickerr show-tick"></select>
                                </div>
                                <label for="">Mac <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" required name="mac" id="id_edt_mac"   class="form-control ip" placeholder="Gateway/Base Station Mac Address">
                                    </div>
                                </div>
                                <label for="">IP Address</label>
                                <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" required name="ip" id="id_edt_ip"  class="form-control ip" placeholder="IP Gateway/Base Station">
                                        </div>
                                </div>
                                <div class="form-group">
                                       <button type="button" onclick="openEditoredtPointerWindow()" class="btn btn-primary btn-block waves-effect" >Pointer Gateway/Base Station</button>
                                </div>
                                <label for="">Real Location XY (meter) <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input required readonly type="text" name="location" id="id_edt_location"   class="form-control" placeholder="Ex. 2,3">
                                    </div>
                                </div>
                                <label for="">Real Location XY (pixel) <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input required readonly type="text" name="location_px" id="id_edt_location_px"   class="form-control" placeholder="Ex. 20,30">
                                    </div>
                                </div>
                                <br>
                                <button type="submit" id="id_btn_edt_submit" style="display: none;" class="btn btn-primary m-t-15 waves-effect">Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    
                                     <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6">
                                    <button onclick="clickSubmit('id_btn_edt_submit')" type="button" class="btn btn-primary waves-effect " >SAVE</button>
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
        var gBuilding = JSON.parse($('#id_building_json').val());
        var gFloor = JSON.parse($('#id_floor_json').val());
        var gRoom = JSON.parse($('#id_room_json').val());
        $(function(){
            init();
        }) 
        function clickSubmit(id){
            $('#'+id).click();
        }
        function getModule(){
            var modules = $('#id_modules').val();
            return JSON.parse(modules)
        }
        function initTable(selector){
            selector.DataTable();
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
        function openEditorCRTPointerWindow( ){
            var bs = $('#id_baseurl').val();
            var floor_id = $('#'+"id_crt_floor_id").val()
            var room_id = $('#'+"id_crt_room_id").val()
            var val = $('#id_crt_location').val()
            var val_px = $('#id_crt_location_px').val()
            var selector1 = 'id_crt_location';
            var selector2 = 'id_crt_location_px';

            if(floor_id != ""){
                var url = 'beacon-gateway/editor?room='+room_id+'&selector1='+selector1+'&selector2='+selector2+'&floor='+floor_id+'&pointer='+val_px;
                var win = window.open(url, "_blank", "width=1200,height=850");
            }else{
                var msg = "Floor map is empty";
                swalShowNotification('alert-danger', msg,'top','center')
            }
        }
        function openEditoredtPointerWindow( ){
            var bs = $('#id_baseurl').val();
            var floor_id = $('#'+"id_edt_floor_id").val()
            var room_id = $('#'+"id_edt_room_id").val()
            var val = $('#id_crt_location').val()
            var val_px = $('#id_edt_location_px').val()
            var selector1 = 'id_edt_location';
            var selector2 = 'id_edt_location_px';

            if(floor_id != ""){
                var url = 'beacon-gateway/editor?room='+room_id+'&selector1='+selector1+'&selector2='+selector2+'&floor='+floor_id+'&pointer='+val_px;
                var win = window.open(url, "_blank", "width=1200,height=850");
            }else{
                var msg = "Floor map is empty";
                swalShowNotification('alert-danger', msg,'top','center')
            }
        }
        function init(){
            var bs = $('#id_baseurl').val();
            var modules = getModule();
            $.ajax({
                url : bs+"beacon-gateway/get/data",
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        clearTable($('#tbldata'));
                        var html = "";
                        var nn = 0;
                        $.each(data.collection, function(index, item){
                            nn++;
                            console.log(item.mac)
                            var room_name = item.room_name == null ? "" : item.room_name ;
                            html += '<tr>'
                            html += '<td>'+nn+'</td>';
                            html += '<td style="width:150px;">'+item.name+'</td>';
                            html += '<td>'+room_name +'</td>';
                            html += '<td>'+item.mac+'</td>';
                            html += '<td>'+item.ip+'</td>';
                            html += '<td>'+item.location+'</td>';
                            html += '<td>'+item.location_px+'</td>';
                            html += '<td>';
                            html += '<button \
                                 onclick="updateData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-id="'+item.name+'" \
                                 type="button" class="btn btn-info waves-effect">Detail</button>';
                            html += ' <button \
                                 onclick="removeData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-name="'+item.name+'" \
                                 type="button" class="btn btn-danger waves-effect"><i class="material-icons">delete</i> </button> ';
                            html += '</td>';
                            html += '</tr>';
                        })
                        $('#tbldata tbody').html(html);
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
            $('#id_mdl_create').modal('show');
            initBuilding('id_crt_building',  "")
            enable_datetimepicker()
            select_enable()
        }

        function initBuilding(id,  value = ""){
            var sel = $('#'+id);
            var html = '';
            if(value != ""){
                for(var x in gBuilding){
                    var s = gBuilding[x]['id'] == value ? "selected" : "";
                    html += "<option "+s+" value='"+gBuilding[x]['id']+"' >"+gBuilding[x]['name']+"</option>";
                }  
            }else{
                for(var x in gBuilding){
                    html += "<option value='"+gBuilding[x]['id']+"' >"+gBuilding[x]['name']+"</option>";
                }  
            }
            
            sel.html(html)
        }
        function getFloorRoom(type = "", building_id){
            var data = [];
            if(type == "floor"){
                for(var x in gFloor){
                    if(gFloor[x].building_id == building_id){
                        data.push(gFloor[x]);
                    }
                } 
            }else if(type == "room"){
                for(var x in gRoom){
                    if(gRoom[x].building_id == building_id){
                        data.push(gRoom[x]);
                    }
                } 
            }
            return data;
        }

        function getBuildingFromFloor(floor_id){
            var d = {};
            for(var x in gFloor){
                    if(gFloor[x].id == floor_id){
                        d = gFloor[x];
                    }
            } 
            return d.building_id == null ? "": d.building_id;
        }
        function onchangeBuilding(action,valueFloor = "", valueRoom = ""){
            // if(valueFloor != ""){

            // }else{

            // }

            var vB = $('#id_crt_building').val();
            if(action == "edit"){
                 vB = $('#id_edt_building').val();
            }
            var vvFloor = getFloorRoom('floor', vB);
            var vvRoom = getFloorRoom('room', vB);
            var htmlFloor = '';
            var htmlRoom = '';
            for(var x in vvFloor){
                var s = vvFloor[x]['id'] == valueFloor ? "selected" : "";
                htmlFloor += '<option '+s+' value="'+vvFloor[x]['id']+'" >'+vvFloor[x]['name']+'</option>';
            } 


            for(var x in vvRoom){
                var s = vvRoom[x]['radid'] == valueRoom ? "selected" : "";
                htmlRoom += '<option '+s+' value="'+vvRoom[x]['radid']+'" >'+vvRoom[x]['name']+'</option>';
            } 

            if(action == "edit"){
                console.log(htmlFloor)
                $('#id_edt_room_id').html(htmlRoom);
                $('#id_edt_floor_id').html(htmlFloor);
            }else{
                $('#id_crt_room_id').html(htmlRoom);
                $('#id_crt_floor_id').html(htmlFloor);
            }
            select_enable()
           
        }

        function updateData(t){
            var id = t.data('id');
            var bs = $('#id_baseurl').val();
            var modules = getModule();
            $.ajax({
                url : bs+"beacon-gateway/get/data",
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
                        var ff = getBuildingFromFloor(row.floor_id);
                        initBuilding('id_edt_building',  ff);
                        var htmll = '';
                        for(var x in gBuilding){
                            var s = gBuilding[x]['id'] == ff ? "selected" : "";
                            htmll += "<option "+s+" value='"+gBuilding[x]['id']+"' >"+gBuilding[x]['name']+"</option>";
                        }  
                        console.log(htmll)
                        // initEmpNoBeacon(row.beacon_employee, $('#id_edt_beacon_employee'));
                        $('#id_edt_building').html(htmll);
                        select_enable()
                        $('#id_edt_id').val(row.id);
                        $('#id_edt_name').val(row.name);
                        $('#id_edt_mac').val(row.mac);
                        $('#id_edt_ip').val(row.ip);
                        $('#id_edt_location').val(row.location);
                        $('#id_edt_location_px').val(row.location_px);
                        
                        
                        $('#id_mdl_update').modal('show');
                        setTimeout(function(){
                            console.log(1,row.floor_id,row.room_id)
                            onchangeBuilding("edit", row.floor_id, row.room_id)
                        },500)
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
                title: 'Confirmation',
                text: 'Are you sure you want delete '+name+'?',
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
                            url : bs+"beacon-gateway/post/delete",
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
                title:'Confirmation',
                text: "Are you sure you want save it?",
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
                title:'Confirmation',
                text: "Are you sure you want save it?",
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
                url : bs+"beacon-gateway/post/create",
                type : "POST",
                data : form,
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                    loadingg('Please wait ! ', 'Process to save')
                },
                success:function(data){
                    swal.close();
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
                url : bs+"beacon-gateway/post/update",
                type : "POST",
                data : form,
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                    loadingg('Please wait ! ', 'Process to save')
                },
                success:function(data){
                    swal.close();
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

       

        function loadingg(title = "",body = ""){
            Swal.fire({
                title: title,
                html: body,
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            });
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

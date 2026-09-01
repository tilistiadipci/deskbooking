
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
                            <li role="presentation" class="active"><a href="#home" data-toggle="tab">Beacon List</a></li>
                            <li role="presentation"><a href="#add-batch" data-toggle="tab">Create New Batch</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active table-responsive responsive" id="home">
                                
                                <div class="body table-responsive responsive">
                                    <table class="table table-hover" id="tbldata">
                                        <thead>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>MAC</th>
                                                <th>QR</th>
                                                <th>Card No</th>
                                                <th>Registered</th>
                                                <th>Employee</th>
                                                <th>
                                                    <button 
                                                     onclick="createData($(this))" 
                                                     type="button" class="btn btn-primary waves-effect ">
                                                     <i class="material-icons">add_circle</i> CREATE</button>
                                                </th>
                                        </thead>
                                        <tbody>
                                                   
                                        </tbody>
                                        </table>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane table-responsive responsive" id="add-batch">
                                     
                                    <div class="body">
                                         <div class="row">
                                            <div class="col-xs-6">
                                                <form id="frm_upload">
                                                    
                                                <label for="">Upload CSV & XLSX</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="file" name="file" id="id_crt_beacon_name"  required=""  class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                                    </div>
                                                      <button type="submit" id="id_btn_upl_submit" class="btn btn-primary m-t-15 waves-effect" style="display: none;" >Save</button>
                                                </div>
                                                 <div class="form-group">
                                                     <button onclick="clickSubmit('id_btn_upl_submit')" type="button" class="btn btn-primary waves-effect " >Upload</button>
                                                </div>
                                                </form>

                                            </div>
                                            <div class="col-xs-6">
                                                 <button onclick="download()" type="button" class="btn btn-primary waves-effect " >Download Template</button>
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
    <div class="modal fade" id="id_mdl_create" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Create Beacon Tag </h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                            <form id="frm_create">
                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="beacon_name" id="id_crt_beacon_name" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                <label for="">Beacon Mac</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="beacon_mac" id="id_crt_beacon_mac" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                               
                                <label for="">Beacon QR</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="beacon_qr" id="id_crt_beacon_qr"   class="form-control ip" placeholder="Beacon QR">
                                    </div>
                                </div>
                                <div id="id_crt_controller_area_custid" > 
                                    <label for="">Beacon Card No.</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="beacon_card_no" id="id_crt_beacon_card_no"  class="form-control" placeholder="Beacon Card No.">
                                        </div>
                                    </div>
                                   
                                </div>
                                 <label for="">Employee</label>
                                <div class="form-group">
                                    <select title="Choose one of the employee..." data-live-search="true" name="beacon_employee" id="id_crt_beacon_employee" class="form-control selectpickerr show-tick"></select>
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
                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="hidden" autocomplete="off" name="id" id="id_edt_beacon_id" required=""  class="form-control" placeholder="">
                                        <input type="text" autocomplete="off" name="beacon_name" id="id_edt_beacon_name" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                <label for="">Beacon Mac</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="beacon_mac" id="id_edt_beacon_mac" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                               
                                <label for="">Beacon QR</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="beacon_qr" id="id_edt_beacon_qr"   class="form-control ip" placeholder="Beacon QR">
                                    </div>
                                </div>
                                <div id="id_crt_controller_area_custid" > 
                                    <label for="">Beacon Card No.</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="beacon_card_no" id="id_edt_beacon_card_no"  class="form-control" placeholder="Beacon Card No.">
                                        </div>
                                    </div>
                                   
                                </div>
                                 <label for="">Employee</label>
                                <div class="form-group">
                                    <select title="Choose one of the employee..." data-live-search="true" name="beacon_employee" id="id_edt_beacon_employee" class="form-control selectpickerr show-tick"></select>
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

         function initEmpNoBeacon(emp_id = "", selector){
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"beacon-tag/get/employee-no-beacon",
                type : "POST",
                data : {
                    id : emp_id,
                },
                dataType: "json",
                beforeSend: function(){
                },
                success:function(data){
                    if(data.status == "success"){
                        var html = "";
                        var nn = 0;
                            html += '<option value="" >Select a employee</option>';

                        $.each(data.collection, function(index, item){
                            nn++;
                            var s = emp_id == item.id ? "selected": "";
                            html += '<option '+s+' value="'+item.id+'" >'+item.name+'</option>';
                        })
                        selector.html(html);

                    }else{
                       selector.html(html);
                    }
                    select_enable()
                },
                // error: errorAjax
            })
        }
        function download(){
            var file = "beacon_tag_template.xlsx";
            var bs = $('#id_baseurl').val();
            var fileurldownload = bs+"assets/file/template/"+file;
            window.location.href = fileurldownload;
        }

        function init(){
            initEmpNoBeacon("",$('#id_crt_beacon_employee'));
            var bs = $('#id_baseurl').val();
            var modules = getModule();
            $.ajax({
                url : bs+"beacon-tag/get/data",
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
                            var ename = "";
                            if(item.employee_id != null){
                                ename = item.employee_name;
                            }
                            html += '<tr>'
                            html += '<td>'+nn+'</td>';
                            html += '<td style="width:150px;">'+item.beacon_name+'</td>';
                            html += '<td>'+item.beacon_mac+'</td>';
                            html += '<td>'+item.beacon_qr+'</td>';
                            html += '<td>'+item.beacon_card_no+'</td>';
                            html += '<td>'+(ename != "" ?"Registered": "Unregistered")+'</td>';
                            html += '<td>'+ename+'</td>';
                         
                            html += '<td>';
                            html += '<button \
                                 onclick="updateData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-id="'+item.beacon_name+'" \
                                 type="button" class="btn btn-info waves-effect">Detail</button>';
                            html += ' <button \
                                 onclick="removeData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-name="'+item.beacon_name+'" \
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
            initEmpNoBeacon("",$('#id_crt_beacon_employee'));
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

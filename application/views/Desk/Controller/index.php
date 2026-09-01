
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
    <link href="<?= base_url()?>assets/theme/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
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
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
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
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="info-box  hover-expand-effect">
                        <div class="icon bg-light-green">
                            <i class="material-icons">toc</i>
                        </div>
                        <div class="content">
                            <div class="text">TOTAL CONTROLLER</div>
                            <div class="number count-to" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20" id="id_count_total">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">

                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>Desk Controller List</h2>
                                </div>
                                <div class="col-xs-12 col-sm-6 align-right">
                    
                                </div>
                            </div>
                        </div>
                        <div class="body table-responsive responsive">
                            <table class="table table-hover" id="tbldata">
                                <thead>
                                        <th style="width: 50px;">#</th>
                                        <th style="width: 30%;">Name</th>
                                        <th style="width: 20%;">IP Address</th>
                                        <th >Capacity</th>
                                        <th ></th>
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
                </div>
               
            </div>

        </div>
    </section>
    <div class="modal fade" id="id_mdl_create" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Create Controller</h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                            <form id="frm_create">
                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="name" id="id_crt_name" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                <label for="">IP Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="ip_address" id="id_crt_ip_address" required=""  class="form-control" placeholder="IP Controller">
                                    </div>
                                </div>
                                <label for="">Controller Capacity</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" autocomplete="off" name="capacity" id="id_crt_capacity" required=""  class="form-control" placeholder="Capacity">
                                    </div>
                                </div>
                                
                                <br>
                                <button type="submit" style="display: none;" id="id_btn_type_crt_submit" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6 align-right">
                                    <button onclick="clickSubmit('id_btn_type_crt_submit')" type="button" class="btn btn-primary waves-effect " >SAVE</button>
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
                            <h4 class="modal-title" id="idmdlcrLabel">Update Controller</h4>
                        </div>
                        <div class="modal-body " id="id_mdl_update_body">
                            <form id="frm_update">
                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="hidden" id="id_edt_id" required="">
                                        <input type="text" autocomplete="off" name="name" id="id_edt_name" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                               <label for="">IP Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="ip_address" id="id_edt_ip_address" required=""  class="form-control" placeholder="IP Controller">
                                    </div>
                                </div>
                                <br>
                                <button type="submit" style="display: none;" id="id_btn_type_edt_submit" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6 align-right">
                                    <button onclick="clickSubmit('id_btn_type_edt_submit')" type="button" class="btn btn-primary waves-effect " >SAVE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <div class="modal fade" id="id_mdl_initial" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="id_mdl_initialLabel">Controller</h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                            <div class="table-responsive responsive">
                                <table class="table table-hover" id="tbldataCInitial">
                                    <thead>
                                        <th style="width: 50px;">#</th>
                                        <th style="width: 30%;">Socket Port</th>
                                        <th style="width: 30%;">Room Name</th>
                                        <th style="width: 20%;">Block</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                               
                                    </tbody>
                                    </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6 align-right">
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
    <script src="<?= base_url()?>assets/theme/plugins/jquery-editable/src/table-edits.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <script>
        var iconData = {};
        $(function(){
            init();
            intrTime();

        }) 
        var gControllerId = "";
        function intrTime(){
            setInterval(
                function(){
                var tm = moment().format('hh:mm A');
                $('#time1').html(tm);
                },500
            );
        }
        function clickSubmit(id){
            $('#'+id).click();
        }
        var gAutomation = [];
        function initTable(selector){
            selector.DataTable();
        }
        function clearTable(selector){
            selector.DataTable().destroy();
        }
        function select_enable(){
            $('select').selectpicker("refresh");
            $('select').selectpicker("initialize");
        }
        function enable_datetimepicker(){
            $('.timepicker').bootstrapMaterialDatePicker({
                format: 'HH:mm',
                clearButton: true,
                date: false
            });
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
        function createData(){
            $('#id_mdl_create').modal('show');
            enable_datetimepicker()
            select_enable()
        }
        
        $('#frm_create').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title:'Confirmation',
                text: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Save !',
                cancelButtonText: 'Close !',
                reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                       submitCreate()
                    }
                else{

                }
            })
        })  
        $('#frm_update').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title:'Confirmation',
                text: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Save !',
                cancelButtonText: 'Close !',
                reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                       submitUpdate()
                    }
                else{

                }
            })
            
        })  
 
        function updateData(t){
            var id = t.data('id')
            // enable_datetimepicker()
            // select_enable()
            // e.preventDefault();
            // var form =  $('#frm_create').serialize();
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"deskcontroller/get/data/detail/"+id,
                type : "POST",
                dataType: "json",
                data : {
                    
                },
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        var col = data.collection;
                        $('#id_mdl_update').modal('show');
                        $('#id_edt_name').val(col.name)
                        $('#id_edt_ip_address').val(col.ip_address)
                        $('#id_edt_id').val(col.id)
                    }else{
                        $('#id_mdl_update').modal('hide');
                        showNotification('alert-danger', data.msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })

        }
        function init(){
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"deskcontroller/get/data",
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        clearTable($('#tbldata'));

                        var html = "";
                        var n = 0;
                        $('#id_count_total').html(data.collection.length);
                        $.each(data.collection, function(index, item){
                            n++;
                            html += '<tr data-id="'+item.id+'">'
                            html += '<td data-field="name">'+n+'</td>';
                            html += '<td data-field="name">'+item.name+'</td>';
                            html += '<td data-field="name">'+item.ip_address+'</td>';
                            html += '<td data-field="name">'+item.capacity+'</td>';
                            html += '<td>';
                            html += '<button \
                                 onclick="controllerInitial($(this))" \
                                 data-id="'+item.id+'" \
                                 data-name="'+item.name+'" \
                                 type="button" class="btn btn-lg btn-primary waves-effect ">Initialize Controller</button>';
                            html += '</td>';
                            html += '<td>';
                        
                            html += '&nbsp;&nbsp;&nbsp;';
                            html += '<button \
                                 onclick="updateData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-name="'+item.name+'" \
                                 type="button" class="btn btn-default waves-effect "><i class="material-icons">edit</i></button>';
                            html += '<button \
                                 onclick="removeData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-name="'+item.name+'" \
                                 type="button" class="btn btn-danger waves-effect "><i class="material-icons">delete</i></button>';
                            html += '</td>';
                            html += '</tr>';
                        })
                        $('#tbldata tbody').html(html);
                        initTable($('#tbldata'));
                    }else{
                        var msg = "Your session is expired, login again !!!";
                        showNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        }

        
        function controllerInitial(t){
            var id = ""
            if(t == null){
                id = gControllerId;
            }else{
                id = t.data('id')
            }
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"deskcontroller/get/data/controller-initial/"+id,
                type : "POST",
                dataType: "json",
                // data : form,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        gControllerId = id;
                        clearTable($('#tbldataCInitial'));
                        var html ='';
                        var col = data.collection;
                        var n = 0;
                        $.each(col, function(index, item){
                            n++;
                            var blockk_d = item.block_desk != null ?  item.block_desk : "";
                            var blockk_n = item.block_number != null ?  item.block_number : "";
                            var block = blockk_d+blockk_n;
                            var room_name =  item.room_name != null ?  item.room_name : "";
                           
                            html += '<tr>'
                            html += '<td>'+n+'</td>';
                            html += '<td>'+item.socket+'</td>';
                            html += '<td>'+room_name+'</td>';
                            html += '<td>'+block+'</td>';
                            html += '<td>';
                        
                            html += '&nbsp;&nbsp;&nbsp;';
                            html += '<button \
                                 onclick="removeControllerInitialData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-name="Socket '+item.socket+'" \
                                 type="button" class="btn btn-danger waves-effect "><i class="material-icons">delete</i></button>';
                            html += '</td>';
                            html += '</tr>';
                        })
                        $('#tbldataCInitial tbody').html(html);
                        initTable($('#tbldataCInitial'));
                        $('#id_mdl_initial').modal('show');
                       
                    }else{
                        $('#id_mdl_initial').modal('hide');
                        showNotification('alert-danger', data.msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })

        }

        function submitCreate(){
            var form =  $('#frm_create').serialize();
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"deskcontroller/post/create",
                type : "POST",
                dataType: "json",
                data : form,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                    loadingg('Please wait ! ', 'Process to save')
                },
                success:function(data){
                    $('#id_loader').html('');
                    swal.close();
                    if(data.status == "success"){
                        $('#frm_create')[0].reset();
                          $('#id_crt_icon_google_icon').html("");
                        init();
                        $('#id_mdl_create').modal('hide');
                        showNotification('alert-success', data.msg,'top','center')
                    }else{
                        showNotification('alert-danger', data.msg,'top','center')
                    }
                },
                error: errorAjax
            })
        }
        function submitUpdate(){
            var form =  $('#frm_update').serialize();
            var id = $('#id_edt_id').val()
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"deskcontroller/post/update/"+id,
                type : "POST",
                dataType: "json",
                data : form,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                    loadingg('Please wait ! ', 'Process to save')

                },
                success:function(data){
                    $('#id_loader').html('');
                    swal.close();
                        if(data.status == "success"){
                          $('#frm_update')[0].reset();
                          $('#id_edt_icon_google_icon').html("");
                          init();
                          $('#id_mdl_update').modal('hide');
                          showNotification('alert-success', data.msg,'top','center')
                        }else{
                          showNotification('alert-danger', data.msg,'top','center')
                        }
                    },
                    error: errorAjax
            })
        }


        function removeData(t){
            var id = t.data('id');
            var name = t.data('name');
            var form = new FormData();
            form.append('id', id);
            form.append('name', name);
            Swal.fire({
                title:'Confirmation',
                text: "Are you sure want delete it? You will lose the data controller "+name+" !",
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
                            url : bs+"deskcontroller/post/delete",
                            type: "POST",
                            data : form,
                            processData: false,
                            contentType: false,
                            dataType :"json",
                            beforeSend: function(){
                                $('#id_loader').html('<div class="linePreloader"></div>');
                                loadingg('Please wait ! ', 'Process to save')
                            },
                            success:function(data){
                                swal.close();
                                $('#id_loader').html('');
                                if (data.status == "success") {
                                    showNotification('alert-success', "Succes deleted desk controller "+name ,'top','center')
                                    init();
                                }else{
                                    showNotification('alert-danger', "Data not found",'bottom','left')
                                }
                            },
                            error: errorAjax,
                        })
                    }
                else{

                }
            })
            
        }
        function removeControllerInitialData(t){
            var id = t.data('id');
            var name = t.data('name');
            var form = new FormData();
            form.append('id', id);
            form.append('name', name);
            Swal.fire({
                title:'Confirmation',
                text: "Reset the data controller socket",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Reset !',
                cancelButtonText: 'Cancel !',
                reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        var bs = $('#id_baseurl').val();
                        $.ajax({
                            url : bs+"deskcontroller/post/reset-controller",
                            type: "POST",
                            data : form,
                            processData: false,
                            contentType: false,
                            dataType :"json",
                            beforeSend: function(){
                                $('#id_loader').html('<div class="linePreloader"></div>');
                                loadingg('Please wait ! ', 'Process to reset')
                            },
                            success:function(data){
                                swal.close();
                                $('#id_loader').html('');
                                if (data.status == "success") {
                                    showNotification('alert-success', "Succes deleted "+name ,'top','center')
                                    controllerInitial(null);
                                }else{
                                    showNotification('alert-danger', "Data not found",'bottom','left')
                                }
                            },
                            error: errorAjax,
                        })
                    }
                else{

                }
            })
            
        }

        function errorAjax(xhr, ajaxOptions, thrownError){
            $('#id_loader').html('');
            swal.close();
            if(ajaxOptions == "parsererror"){
                var msg = "Status Code 500, Error Server bad parsing";
                showNotification('alert-danger', msg,'bottom','left')
            }else{
                var msg ="Status Code "+ xhr.status + " Please check your connection !!!";
                showNotification('alert-danger', msg,'bottom','left')
            }
        }
    </script>
    </body>
</html>

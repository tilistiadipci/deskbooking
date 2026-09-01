<!DOCTYPE html>
<html lang="en">
<head>
   <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
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
                        <div class="header">

                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>Automation List</h2>
                                </div>
                                <div class="col-xs-12 col-sm-6 align-right">
                    
                                </div>
                            </div>
                        </div>
                        <div class="body table-responsive responsive">
                            <table class="table table-hover" id="tbldata">
                                <thead>
                                        <th>Name</th>
                                        <th>Serial</th>
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
                            <h4 class="modal-title" id="idmdlcrLabel">Create Automation</h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                            <form id="frm_create">
                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="name" id="id_crt_name" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                <label for="">IP Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input required="" type="text" id="id_crt_ip_address" name="ip_address"  class="form-control" placeholder="IP Address">
                                    </div>
                                </div>
                                <label for="">Serial</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input required="" type="text" name="serial" id="id_crt_serial"  class="form-control" placeholder="Serial">
                                    </div>
                                </div>
                                <label for=""> Generate Device By Serial</label>
                                <div class="form-group">
                                    <button 
                                             onclick="genData('', 'id_crt_serial')" 
                                             type="button" class="btn btn-primary waves-effect ">
                                             <i class="material-icons">cloud</i> GENERATE</button>
                                </div>
                                <label for="">Room</label>
                                <div class="row" id="id_crt_room">
                                    
                                </div>
                                <label for="">Devices</label>
                                <div class="row" id="id_crt_devices">
                                    
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
                            <h4 class="modal-title" id="idmdlcrLabel">Update Automation</h4>
                        </div>
                        <div class="modal-body " id="id_mdl_update">
                            <form id="frm_update">
                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="name" id="id_edt_name" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                <label for="">IP Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input required="" type="text" id="id_edt_ip_address" name="ip_address"  class="form-control" placeholder="IP Address">
                                    </div>
                                </div>
                                <label for="">Serial</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input required="" type="text" name="serial" id="id_edt_serial"  class="form-control" placeholder="Serial">
                                        <input required="" type="hidden" id="id_edt_id" >
                                    </div>
                                </div>
                                <label for=""> Generate Device By Serial</label>
                                <div class="form-group">
                                    <button 
                                             onclick="genData('edit', 'id_edt_serial')" 
                                             type="button" class="btn btn-primary waves-effect ">
                                             <i class="material-icons">cloud</i> GENERATE</button>
                                </div>
                                <label for="">Room</label>
                                <div class="row" id="id_edt_room">
                                    
                                </div>
                                <label for="">Devices</label>
                                <div class="row" id="id_edt_devices">
                                    
                                </div>
                                <br>
                                <button type="submit" style="display: none;" id="id_btn_type_edt_submit"  class="btn btn-primary m-t-15 waves-effect">Submit</button>
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
    <!-- # END MODAL CREATE  -->
    <?php $this->load->view("_partials/js_dashboard.php");?>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <script>
        $(function(){
            init();
        }) 
        function clickSubmit(id){
            $('#'+id).click();
        }
        var gDatalistRoom = [];
        var gDatalistDevices = [];
        function initTable(selector){
            selector.DataTable();
        }
        function clearTable(selector){
            selector.DataTable().destroy();
        }
        
        function createData(){
            $('#id_mdl_create').modal('show');
            $('#id_crt_room').html('');
            $('#id_crt_devices').html('');

        }
        $('#frm_create').submit(function(e){
            e.preventDefault();
            var form = new FormData();
            var room = JSON.stringify(gDatalistRoom);
            var devices = JSON.stringify(gDatalistDevices);
            form.append('name', $('#id_crt_name').val() )
            form.append('ip_address', $('#id_crt_ip_address').val() )
            form.append('serial', $('#id_crt_serial').val() )
            form.append('room', room);
            form.append('devices', devices);

            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"automation/post/create",
                type : "POST",
                dataType: "json",
                data : form,
                processData: false,
                contentType: false,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                        if(data.status == "success"){
                          $('#frm_create')[0].reset();
                          $('#id_mdl_create').modal('hide');
                          $('#id_crt_room').html('');
                          $('#id_crt_devices').html('');
                          init();
                          showNotification('alert-success', data.msg,'top','center')
                        }else{
                          showNotification('alert-danger', data.msg,'top','center')
                        }
                        $('#id_loader').html('');
                    },
                    error: errorAjax
            })
        })  
        $('#frm_update').submit(function(e){
            e.preventDefault();
            var form = new FormData();
            var room = JSON.stringify(gDatalistRoom);
            var devices = JSON.stringify(gDatalistDevices);
            form.append('name', $('#id_edt_name').val() )
            form.append('ip_address', $('#id_edt_ip_address').val() )
            form.append('serial', $('#id_edt_serial').val() )
            form.append('room', room);
            form.append('devices', devices);
            var id = $('#id_edt_id').val()
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"automation/post/update/"+id,
                type : "POST",
                dataType: "json",
                data : form,
                processData: false,
                contentType: false,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                        if(data.status == "success"){
                          $('#frm_update')[0].reset();
                          $('#id_mdl_update').modal('hide');
                          $('#id_edt_room').html('');
                          $('#id_edt_devices').html('');
                          init();
                          showNotification('alert-success', data.msg,'top','center')
                        }else{
                          showNotification('alert-danger', data.msg,'top','center')
                        }
                        $('#id_loader').html('');
                    },
                    error: errorAjax
            })
        })

        function genData($action =  "", serialselector = ""){
            var serial = $('#'+serialselector).val();
            if(serial.length == 0){
                showNotification('alert-danger', 'Serial is empty','top','center')
            }else{
                var bs = $('#id_baseurl').val();
                gDatalistRoom = [];
                gDatalistDevices = [];
                $.ajax({
                    url : bs+"automation/get/gendata/"+serial,
                    type : "GET",
                    dataType: "json",
                    beforeSend: function(){
                        $('#id_loader').html('<div class="linePreloader"></div>');
                    },
                    success:function(data){
                        if(data.status == "success"){
                            var html_room = "";
                            var html_devices = "";
                            var nroom = 0;
                            var nzona = 0;

                            $.each(data.collection.room, function(index, item){
                                var $room = {
                                    id : item.id,
                                    roomName : item.nama,
                                    is_active : 0 
                                }
                                gDatalistRoom.push($room);
                                html_room += '<div class="col-sm-6 col-md-6 col-xs-6 col-lg-4 "> ';
                                html_room += '<input onchange="onCRoom($(this))" value="'+nroom+'" type="checkbox" id="id_crt_cbx_room_'+nroom+'" class="filled-in chk-col-red" />\
                                        <label for="id_crt_cbx_room_'+nroom+'">'+item.nama+'</label>';
                                html_room += '</div>';
                                nroom++;
                            })

                            $.each(data.collection.zona, function(index, item){
                                var $devices = item;
                                $devices['is_active'] = 0;
                                gDatalistDevices.push($devices);
                                html_devices += '<div class="col-sm-6 col-md-6 col-xs-6 col-lg-4 "> ';
                                html_devices += '<input onchange="onCDevice($(this))" value="'+nzona+'" type="checkbox" id="id_crt_cbx_devices_'+nzona+'" class="filled-in chk-col-blue" />\
                                        <label for="id_crt_cbx_devices_'+nzona+'">'+item.nama+'('+item.nama_ruangan+')</label>';
                                html_devices += '</div>';
                                nzona++;
                            })
                            // console.log($action)
                            if($action == "edit"){
                                $('#id_edt_room').html(html_room)
                                $('#id_edt_devices').html(html_devices)
                            }else{
                                $('#id_crt_room').html(html_room)
                                $('#id_crt_devices').html(html_devices)
                            }
                        }else{
                            var msg = "Your session is expired, login again !!!";
                            showNotification('alert-danger', msg,'top','center')
                        }
                        $('#id_loader').html('');
                    },
                    error: errorAjax
                })
            }
            
        }
        function onCRoom(t){
            var index = t.val()
            var check = t.prop('checked');
            console.log(index)
            console.log(check)
            console.log(gDatalistRoom[index])
            if(check == true){
                gDatalistRoom[index]['is_active'] = 1;
            }else{
                gDatalistRoom[index]['is_active'] = 0;
            }
        }
        function onCDevice(t){
            var index = t.val()
            var check = t.prop('checked');
            if(check == true){
                gDatalistDevices[index]['is_active'] = 1;
            }else{
                gDatalistDevices[index]['is_active'] = 0;
            }
        }
        function init(){
            var bs = $('#id_baseurl').val();
            gDatalistRoom = [];
            gDatalistDevices = [];
            $.ajax({
                url : bs+"automation/get/data",
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        clearTable($('#tbldata'));
                        var html = "";
                        $.each(data.collection, function(index, item){
                            html += '<tr>'
                            html += '<td>'+item.name+'</td>';
                            html += '<td>'+item.serial+'</td>';
                            html += '<td>';
                            html += '<button \
                                 onclick="editData($(this))" \
                                 data-id="'+item.id+'" \
                                 type="button" class="btn btn-info waves-effect btn-sm"><i class="material-icons">edit</i></button>';
                            html += '<button \
                                 onclick="removeData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-name="'+item.name+'" \
                                 type="button" class="btn btn-danger waves-effect btn-sm"><i class="material-icons">delete</i></button>';
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

        function editData(t){
            var id = t.data('id');
            var bs = $('#id_baseurl').val();
            gDatalistRoom = [];
            gDatalistDevices = [];
            $.ajax({
                url : bs+"automation/get/edit/"+id,
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        var input = data.collection;
                        $('#id_edt_name').val(input['name']);
                        $('#id_edt_ip_address').val(input['ip_address']);
                        $('#id_edt_serial').val(input['serial']);
                        $('#id_edt_id').val(input['id']);
                        var room = JSON.parse(input['room']) ;
                        var devices = JSON.parse(input['devices']) ;
                        var html_room = "";
                        var html_devices="";
                        var nroom = 0;
                        var nzona = 0;
                        $.each(room, function(index, item){
                                var $room = {
                                    id : item.id,
                                    roomName : item.nama,
                                    is_active : item.is_active
                                }
                                gDatalistRoom.push($room);
                                var ch = "";
                                if(item.is_active == 1){
                                    ch = "checked";
                                }
                                html_room += '<div class="col-sm-6 col-md-6 col-xs-6 col-lg-4 "> ';
                                html_room += '<input '+ch+' onchange="onCRoom($(this))" value="'+nroom+'" type="checkbox" id="id_crt_cbx_room_'+nroom+'" class="filled-in chk-col-red" />\
                                        <label for="id_crt_cbx_room_'+nroom+'">'+item.roomName+'</label>';
                                html_room += '</div>';
                            nroom++;
                        })
                        $.each(devices, function(index, item){
                                var $devices = item;
                                gDatalistDevices.push($devices);
                                var ch = "";
                                if(item.is_active == 1){
                                    ch = "checked";
                                }
                                html_devices += '<div class="col-sm-6 col-md-6 col-xs-6 col-lg-4 "> ';
                                html_devices += '<input '+ch+' onchange="onCDevice($(this))" value="'+nzona+'" type="checkbox" id="id_crt_cbx_devices_'+nzona+'" class="filled-in chk-col-blue" />\
                                        <label for="id_crt_cbx_devices_'+nzona+'">'+item.nama+'('+item.nama_ruangan+')</label>';
                                html_devices += '</div>';
                                nzona++;
                        })
                        $('#id_mdl_update').modal('show');
                        $('#id_edt_devices').html(html_devices)
                        $('#id_edt_room').html(html_room)
                    }else{
                        var msg = "Your session is expired, login again !!!";
                        showNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
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
                title:'Are you sure you want delete it?',
                text: "You will lose the data automation!",
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
                            url : bs+"automation/post/delete",
                            type: "POST",
                            data : form,
                            processData: false,
                            contentType: false,
                            dataType :"json",
                            beforeSend: function(){
                                $('#id_loader').html('<div class="linePreloader"></div>');
                            },
                            success:function(data){
                                $('#id_loader').html('');
                                if (data.status == "success") {
                                    showNotification('alert-success', "Succes deleted automation",'top','center')
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
    </body>
</html>

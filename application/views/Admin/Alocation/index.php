
<?php  
// ob_start( ) ;
//             echo "Output";
//            $buff = ob_get_contents( ) ;
//             echo $buff ;
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
                <!-- type -->
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5" >
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="header">

                                    <div class="row clearfix">
                                        <div class="col-xs-6 col-sm-6">
                                            <h2>Company </h2>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 align-right">
                            
                                        </div>
                                    </div>
                                </div>
                                <div class="body  table-responsive responsive">
                                    <table class="table table-hover" id="tbldataType">
                                        <thead>
                                                <th>
                                                    <button 
                                                     onclick="createData_type($(this))" 
                                                     type="button" class="btn btn-primary waves-effect ">
                                                     <i class="material-icons">add_circle</i> CREATE</button>
                                                </th>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <!-- <th style="width:100px !important;">Invoice</th> -->
                                                <th></th>
                                                
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- alocation -->
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="header">

                                    <div class="row clearfix">
                                        <div class="col-xs-6 col-sm-6">
                                            <h2>Departement</h2>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 align-right">
                            
                                        </div>
                                    </div>
                                </div>
                                <div class="body table-responsive responsive">
                                    <table class="table table-hover" id="tbldataAlocation">
                                        <thead>
                                                <th>
                                                    <button 
                                                     onclick="createData_alocation($(this))" 
                                                     type="button" class="btn btn-primary waves-effect ">
                                                     <i class="material-icons">add_circle</i> CREATE</button>
                                                 </th>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Departement Type</th>
                                                <!-- <th>Invoice</th> -->
                                                <!-- <th>Asign</th> -->
                                                
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="id_mdl_create_type" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Create Company </h4>
                        </div>
                        <div class="modal-body " >
                            <form id="frm_create_type">
                                <label for="">ID </label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="id"  required=""  class="form-control" placeholder="ID ">
                                    </div>
                                </div>
                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="name"  required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                
                                <br>
                                <button type="submit" id="id_btn_type_crt_submit" class="btn btn-primary m-t-15 waves-effect" style="display: none;">Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                   <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button> 
                                </div>
                                <div class="col-xs-6">
                                    <button onclick="clickSubmit('id_btn_type_crt_submit')" type="button" class="btn btn-primary waves-effect " >SAVE</button>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <div class="modal fade" id="id_mdl_create_alocation" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Create Department </h4>
                        </div>
                        <div class="modal-body " >
                            <form id="frm_create_alocation">
                                <label for="">Department ID</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="id" id="id_crt_id" required=""  class="form-control" placeholder="Department ID">
                                    </div>
                                </div>
                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="name" id="id_crt_name" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                <label for="">Company</label>
                                <div class="form-group">
                                   <select id="id_crt_type" class="form-control show-tick" data-live-search="true" name="type" >
                                    </select>
                                </div>
                                <br>
                                <button type="submit" id="id_btn_alocation_crt_submit" class="btn btn-primary m-t-15 waves-effect" style="display: none;">Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                    
                                </div>
                                <div class="col-xs-6">
                                   <button onclick="clickSubmit('id_btn_alocation_crt_submit')" type="button" class="btn btn-primary waves-effect " >SAVE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <div class="modal fade" id="id_mdl_assign" tabindex="-1" role="dialog">
                <div class="modal-dialog modeal-lg" role="document" style="width: 800px !important">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" >Assign employee to <b id="id_assign_title"></b> </h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body" style="height: 500px;overflow-y: scroll;overflow-x: hidden;">

                            <form id="frm_assign">
                                <input type="hidden" name="id" id="id_access_assign_id">
                                <table class="table table-hover" id="id_list_assign">
                                    <thead>
                                        <th>#</th>
                                        <th>NAME</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                              <!--   <ul class="list-group" id="id_list_assign">
                                    
                                </ul> -->
                                <br>
                                <button style="display: none;" id="id_btn_assign_submit" type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6">
                                    <button onclick="clickSubmit('id_btn_assign_submit')" type="button" class="btn btn-primary waves-effect " >SAVE</button>
                                    
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
                            <h4 class="modal-title" id="idmdlcrLabel">Update Access Controller</h4>
                        </div>
                        <div class="modal-body " id="id_mdl_update">
                           <form id="frm_update">
                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="hidden" name="id" id="id_edt_id">
                                        <input type="text" autocomplete="off" name="name" id="id_edt_name" required=""  class="form-control" placeholder="Name">
                                    </div>
                                </div>
                                <label for="">Controller IP</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="ip_controller" id="id_edt_controller" required=""  class="form-control ip" placeholder="Controller IP">
                                    </div>
                                </div>
                                <label for="">Channel</label>
                                <div class="form-group">
                                    <select  data-live-search="true" name="channel" id="id_edt_channel" class="form-control show-tick"></select>
                                </div>
                                <label for="">Room</label>
                                <div class="form-group">
                                    <select  data-live-search="true" name="room[]" multiple="" id="id_edt_room" class="form-control show-tick"></select>
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
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <?php $this->load->view("_partials/js_dashboard.php");?>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
    <!-- Moment Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/jquery-editable/src/table-edits.js"></script>
    <script src="<?= base_url()?>assets/process/alocation/index.js"></script>
    <script>
       
    </script>
    </body>
</html>

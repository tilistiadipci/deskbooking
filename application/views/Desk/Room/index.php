
<?php  
// date_default_timezone_set("Asia/Jakarta");
// echo date('w');
// print_r($modules);
// die();
?>
<textarea id="id_modules" style="display: none;"> <?= json_encode($modules)?></textarea> 
<textarea id="id_building" style="display: none;"> <?= json_encode($building)?></textarea> 
<!DOCTYPE html>
<html lang="en">
<head>
   <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/theme/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/external/filepond/filepond.min.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/external/dropify/css/dropify.min.css" rel="stylesheet">
    <style>
       
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
                            <div class="text">TOTAL DESK ROOM</div>
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
                                    <h2>Desk Room List</h2>
                                </div>
                                <div class="col-xs-12 col-sm-6 align-right">
                    
                                </div>
                            </div>
                        </div>
                        <div class="body table-responsive responsive">
                            <table class="table table-hover" id="tbldata">
                                <thead>
                                        <th>#</th>
                                        <th>Thumbnail</th>
                                        <th>Name</th>
                                        <th>Building</th>
                                        <th>Capacity</th>
                                        <th>Working Time</th>
                                        <th>Status</th>
                                        <th style="width: 200px;"> 
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
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Create Desk Room </h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                            <form id="frm_create" method="POST">
                                <h3>Desk Room Information</h3>
                                <div class="fieldset_wizard">
                                    <label for="">Room Thumbnail </label>
                                    <div class="form-group">
                                        <input type="file" name="image" class="dropify" data-max-file-size="2M" accept="image/*"  />
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <label for="">Room Mapping (JPG/PNG)</label>
                                            <div class="form-group">
                                                <input type="file" name="room_map"  class="dropify" data-height="120" data-max-file-size="15M" accept="image/*"   
                                                />
                                            </div>
                                        </div>
                                        <!-- <div class="col-xs-6">
                                            <label for="">Slide Image </label>
                                            <div class="form-group">
                                                <input type="file" name="image2[]" multiple class="dropify" data-height="120" data-max-file-size="2M" accept="image/*"   
                                                />
                                            </div>
                                        </div> -->
                                    </div>
                                    <label for="">Map Position</label>
                                    <div class="form-group">
                                        <select name="posmap" id="id_crt_posmap" class="form-control show-tick">
                                            
                                        </select>
                                    </div>
                                    <label for="">Building <b style="color:red;">*</b></label>
                                    <div class="form-group">
                                        <select name="building_id" required id="id_crt_building_id" class="form-control show-tick"></select>
                                    </div>
                                   
                                    <label for="">Status <b style="color:red;">*</b></label>
                                    <div class="form-group">
                                        <select required name="is_disabled" id="id_crt_is_disabled" class="form-control show-tick">
                                            <option value="0">Enabled</option>
                                            <option value="1">Disabled</option>
                                        </select>
                                    </div>
                                    <label for="">Name <b style="color:red;">*</b></label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" autocomplete="off" name="name" id="id_crt_name" required=""  class="form-control" placeholder="Name">
                                        </div>
                                    </div>
                                    <label for="">Description</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="4"  name="description" class="form-control no-resize"  id="id_crt_description" placeholder="Please type the room description..."></textarea>
                                        </div>    
                                    </div>
                                    <label for="">Detail Location</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="4"  name="location" class="form-control no-resize"  id="id_crt_location" placeholder="Please type the room location..."></textarea>
                                        </div>    
                                    </div>
                                    <label for="">Link Map</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" autocomplete="off" name="google_map" id="id_crt_google_map"   class="form-control" placeholder="Google Map">
                                        </div>
                                    </div>
                                    <label for="">Room Capacity <b style="color:red;">*</b></label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" name="capacity" id="id_crt_capacity" required=""  class="form-control" placeholder="Capacity">
                                        </div>
                                    </div>
                                    
                                    <label for="">Room Facility</label>
                                    <div class="form-group">
                                        <select  data-live-search="true" multiple name="facility_room[]" id="id_crt_facility_room" class="form-control show-tick" data-actions-box="true"></select>
                                    </div>
                                    <label for="">Working Day</label>
                                    <div class="form-group">
                                        <select multiple name="work_day[]" id="id_crt_workday" class="form-control show-tick"  data-actions-box="true">
                                            <option value="SUNDAY">SUNDAY</option>
                                            <option value="MONDAY">MONDAY</option>
                                            <option value="TUESDAY">TUESDAY</option>
                                            <option value="WEDNESDAY">WEDNESDAY</option>
                                            <option value="THURSDAY">THURSDAY</option>
                                            <option value="FRIDAY">FRIDAY</option>
                                            <option value="SATURDAY">SATURDAY</option>
                                        </select>
                                    </div>
                                    <label for="">Working Time <b style="color:red;">*</b></label>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-6 col-lg-6 col-sm-6">
                                                <div class="form-line">
                                                    <input  type="text" name="work_start" id="id_crt_work_start"  class="timepicker form-control" required="" placeholder="Please choose a start time...">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-6 col-lg-6 col-sm-6">
                                                <div class="form-line">
                                                    <input  type="text" name="work_end" id="id_crt_work_end"  class="timepicker form-control" required="" placeholder="Please choose a finish time...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3>Zone & Finish</h3>
                                <div  class="fieldset_wizard">
                                    <label for="">Zone Name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" autocomplete="off"  id="id_crt_zone_name"   class="form-control" placeholder="Zone Name">
                                            <input type="hidden" autocomplete="off"  id="id_crt_zone_id"   class="form-control" >
                                            <input type="hidden" autocomplete="off"  id="id_crt_zone_action"   class="form-control" >
                                        </div>
                                    </div>
                                    <button data-type="crtZone" id="id_crt_zone_close_btn" style="display:none;" type="button" class="btn btn-default waves-effect " onclick="closeAddZone($(this))">Close</button>
                                    <button id="id_crt_zone_btn" type="button" class="btn btn-primary waves-effect " onclick="createAddZone()">ADD</button>
                                    <table class="table table-hover" id="tbldatacrtzone">
                                        <thead>
                                                <th>#</th>
                                                <th>Zone name</th>
                                                <th></th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="submit" style="display: none;" id="id_btn_crt_submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
                                
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6">
                                    <!-- <button onclick="clickSubmit('id_btn_crt_submit')" type="button" class="btn btn-primary waves-effect ">SAVE</button> -->
                                </div>
                            </div>
                            <!-- <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button> -->
                        </div>
                    </div>
                </div>
            </div>
    <div class="modal fade" id="id_mdl_update" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Update Desk Room</h4>
                        </div>
                        <div class="modal-body " id="id_mdl_update">
                            <form id="frm_update">
                                <h3>Desk Room Information</h3>
                                <div class="fieldset_wizard">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <label for="">Old Room Thumbnail </label>
                                            <a href="javascript:void(0);" class="thumbnail">
                                                <img id="id_edt_image_old" src="" style="height:100px" class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="col-xs-6">
                                            <label for="">New Room Thumbnail </label>
                                            <div class="form-group">
                                                <input type="file" name="image"  id="id_edt_image" class="dropify" data-height="100" data-max-file-size="15M" accept="image/*"  />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <label for="">Old Room Mapping</label>
                                            <a href="javascript:void(0);" class="thumbnail">
                                                <img id="id_edt_room_map_old" src="" style="height:100px" class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="col-xs-6">
                                            <label for="">Room Mapping (JPG/PNG)</label>
                                            <div class="form-group">
                                                <input type="file" name="room_map"  id="id_edt_room_map" class="dropify" data-height="100" data-max-file-size="15M" accept="image/*"  />
                                            </div>
                                        </div>
                                    </div>
                                    <label for="">Map Position</label>
                                    <div class="form-group">
                                        <select name="posmap" id="id_edt_posmap" class="form-control show-tick">
                                            
                                        </select>
                                    </div>
                                    <div class="row">
                                        <!-- <div class="col-xs-6">
                                            <label for="">Old Slide Image </label>
                                            <a href="javascript:void(0);" class="thumbnail">
                                                <img id="id_edt_image2_old" src="" style="height:100px" class="img-responsive">
                                            </a>
                                        </div> -->
                                        <!-- <div class="col-xs-6">
                                            <label for="">New Slide Image </label>
                                            <div class="form-group">
                                                <input type="file" name="image2[]" multiple id="id_edt_image2" class="dropify" data-height="100" data-max-file-size="15M" accept="image/*"  />
                                            </div>
                                        </div> -->
                                    </div>
                                    <label for="">Building</label>
                                    <div class="form-group">
                                        <select name="building_id" id="id_edt_building_id" class="form-control show-tick">
                                            
                                        </select>
                                    </div>
                                    <label for="">Status</label>
                                    <div class="form-group">
                                        <select name="is_disabled" id="id_edt_is_disabled" class="form-control show-tick">
                                            
                                        </select>
                                    </div>
                                    <label for="">Name <b style="color:red;">*</b></label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" autocomplete="off" name="name" id="id_edt_name" required=""  class="form-control" placeholder="Name">
                                            <input type="hidden" autocomplete="off" name="id" id="id_edt_id" readonly="" required=""  >
                                        </div>
                                    </div>
                                    <label for="">Description</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="4"  name="description" class="form-control no-resize"  id="id_edt_description" placeholder="Please type the room description..."></textarea>
                                        </div>    
                                    </div>
                                    <label for="">Detail Location</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="4"  name="location" class="form-control no-resize"  id="id_edt_location" placeholder="Please type the room location..."></textarea>
                                        </div>    
                                        
                                    </div>
                                    <label for="">Link Map</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" autocomplete="off" name="google_map" id="id_edt_google_map"   class="form-control" placeholder="Link Map">
                                        </div>
                                    </div>
                                    <label for="">Room Capacity  <b style="color:red;">*</b></label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input required="" type="number" name="capacity" id="id_edt_capacity" required=""  class="form-control" placeholder="Capacity">
                                        </div>
                                    </div>
                                    <label for="">Room Facility</label>
                                    <div class="form-group">
                                        <select data-live-search="true" multiple name="facility_room[]" id="id_edt_facility_room" class="form-control show-tick" data-actions-box="true"></select>
                                    </div>
                                    <label for="">Working Day <b style="color:red;">*</b></label>
                                    <div class="form-group">
                                        <select required="" multiple name="work_day[]" id="id_edt_workday" class="form-control show-tick" data-actions-box="true">
                                         
                                        </select>
                                    </div>
                                    <label for="">Working Time <b style="color:red;">*</b></label>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-6 col-lg-6 col-sm-6">
                                                <div class="form-line">
                                                    <input required="" type="text" name="work_start" id="id_edt_work_start"  class="timepicker form-control" placeholder="Please choose a start time...">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-6 col-lg-6 col-sm-6">
                                                <div class="form-line">
                                                    <input required="" type="text" name="work_end" id="id_edt_work_end"  class="timepicker form-control" placeholder="Please choose a finish time...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3>Zone & Finish</h3>
                                <div  class="fieldset_wizard">
                                    <label for="">Zone Name</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" autocomplete="off"  id="id_edt_zone_name"   class="form-control" placeholder="Zone Name">
                                            <input type="hidden" autocomplete="off"  id="id_edt_zone_id"   class="form-control" >
                                            <input type="hidden" autocomplete="off"  id="id_edt_zone_action"   class="form-control" >
                                        </div>
                                    </div>
                                    <button data-type="edtZone" id="id_edt_zone_close_btn" style="display:none;" type="button" class="btn btn-default waves-effect " onclick="closeAddZone($(this))">Close</button>
                                    <button id="id_edt_zone_btn" type="button" class="btn btn-primary waves-effect " onclick="createEdtZone()">ADD</button>
                                    <table class="table table-hover" id="tbldataedtzone">
                                        <thead>
                                                <th>#</th>
                                                <th>Zone name</th>
                                                <th></th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <br>
                                <button type="submit" style="display: none;" id="id_btn_edt_submit" class="btn btn-primary m-t-15 waves-effect">Update</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6">
                                </div>
                            </div>
                            <!-- <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button> -->
                        </div>
                    </div>
                </div>
            </div>
    <!-- # END MODAL CREATE  -->

     <div class="modal fade" id="id_mdl_editor" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="id_mdl_editorLabel">Editor Desk Zone - <b id="id_mdl_editorLabel_title"></b> </h4>
                </div>
                <div class="modal-body ">
                    <label for="">Zone</label>
                    <div class="form-group">
                        <select onchange="onChangeEditorZone()" id="id_edtor_zone" class="form-control show-tick"></select>
                    </div>
                    <hr>
                   
                    <label for="">Desk/Block Number</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="hidden" autocomplete="off"  id="id_editor_room_id"   class="form-control" >
                            <input type="hidden" autocomplete="off"  id="id_editor_zone_id"   class="form-control" >
                            <input type="hidden" autocomplete="off"  id="id_editor_zone_action"   class="form-control" >
                            <input type="hidden" autocomplete="off"  id="id_editor_desk_id"   class="form-control" >
                            <input type="number" autocomplete="off"  id="id_editor_zone_number" name="number"  class=" form-control" placeholder="Number">


                            <input type="hidden" name="old_socket" autocomplete="off"  id="id_editor_old_socket"   class="form-control" >
                            <input type="hidden" name="old_controller" autocomplete="off"  id="id_editor_old_controller"   class="form-control" >

                        </div>

                    </div>
                    <label for="">Desk Pointer</label>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="form-line">

                                <input type="text" readonly autocomplete="off"  id="id_editor_zone_pointer" name="pointer"  class="form-control" placeholder="Number">
                                <!-- <input type="hidden" readonly autocomplete="off"  id="id_editor_zone_pointer_selector" name="pointer"  class="form-control" placeholder="Number"> -->
                            </div>
                            <span class="input-group-addon">
                                <button type="button" onclick="openEditorPointerWindow('id_editor_zone_pointer','id_editor_zone_id','id_editor_room_id' )" class="btn btn-primary waves-effect" >Pointer</button>
                            </span>
                        </div>
                    </div>
                    <label for="">Desk Controller</label>
                    <div class="form-group">
                        <select onchange="changeController('id_editor_desk_controller' ,'id_editor_desk_socket', '')" id="id_editor_desk_controller" name="controller" class="form-control show-tick" ></select>
                    </div>
                    <label for="">Desk Socket</label>
                    <div class="form-group">
                        <select id="id_editor_desk_socket" name="socket" class="form-control show-tick" ></select>
                    </div>
                    
                    <button data-type="crtZone" id="id_editor_zone_close_btn" style="display:none;" type="button" class="btn btn-default waves-effect " onclick="closeEditorZone($(this))">Close</button>
                    <button id="id_editor_zone_btn" type="button" class="btn btn-primary waves-effect " onclick="createEditorZone()">ADD</button>
                    <br>
                    <br>
                    <hr>
                    <table class="table table-hover" id="tbldataeditorzone">
                        <thead>
                            <th>#</th>
                            <th>Zone name</th>
                            <th>Desk/Block Number</th>
                            <th>Pointer</th>
                            <th>Controller</th>
                            <th>Socket</th>
                            <th></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="row clearfix">
                        <div class="col-xs-6 align-left">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view("_partials/js_dashboard.php");?>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
    <!-- JQuery Steps Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/jquery-steps/jquery.steps.js"></script>
    <!-- Moment Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="<?= base_url()?>assets/external/numeral/numeral.min.js"></script>
    <script src="<?= base_url()?>assets/external/numeral/locale.id.js"></script>
    <script src="<?= base_url()?>assets/external/dropify/js/dropify.min.js"></script>


    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <script>
        var typeRoom = [{'text':'Single Room', 'value':'single'},{'text':'Merge Room', 'value':'merge'}];
        var bs = $('#id_baseurl').val();
        var pathImage = bs+"assets/file/room/";
        var defaultImage = "default.jpeg";
        var uploadimageCrt = false;
        var gAutomation = [];
        var gFacility= [];
        var gDeskController =  [];
        var assetsImageUrl = "";
        var enabledRoom = ["Enabled", "Disabled"];
        var building = $('#id_building').val();
        var gBuilding = JSON.parse(building);
        var gDatalistRoom = [];
        var gCrtZone= [];
        var gCrtZoneDeleted= [];
        var gCrtDesk= {};
        var tablezoneelement = '';
        var posmap = [{value:"landscape",name:"Landscape"},{value:"potrait",name:"Potrait"}];



        var editorZoneList = [];
        var editorZoneObj = {};
        var editorZoneObjDesk = [];


        $(function(){
            init();
            getFacility();
            initDrop();
            intrTime();
        }) 
        function intrTime(){
            setInterval(
                function(){
                var tm = moment().format('hh:mm A');
                $('#time1').html(tm);
                },500
            );
        }

        function generatePosmap(value = ""){
            var html = "";
            for (var i in posmap){
                var s = value == posmap[i].value ?"selected" :""
                html += `<option ${s} value="${posmap[i].value}">${posmap[i].name}</option>`;
            }
            return html;
        }
         var form = $('#frm_create');
             // class="fieldset_wizard"
            form.steps({
                headerTag: 'h3',
                bodyTag: 'div.fieldset_wizard',
                transitionEffect: 'slideLeft',
                onInit: function (event, currentIndex) {
                    $.AdminBSB.input.activate();

                    //Set tab width
                    var $tab = $(event.currentTarget).find('ul[role="tablist"] li');
                    var tabCount = $tab.length;
                    $tab.css('width', (100 / tabCount) + '%');

                    //set button waves effect
                    setButtonWavesEffect(event);
                },
                onStepChanging: function (event, currentIndex, newIndex) {
                    if (currentIndex > newIndex) { return true; }

                    if (currentIndex < newIndex) {
                        form.find('.body:eq(' + newIndex + ') label.error').remove();
                        form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
                    }

                    form.validate().settings.ignore = ':disabled,:hidden';
                    console.log(123, form.valid(), currentIndex)
                    return form.valid();
                    // return true;
                },
                onStepChanged: function (event, currentIndex, priorIndex) {

                    setButtonWavesEffect(event);
                },
                onFinishing: function (event, currentIndex) {
                    form.validate().settings.ignore = ':disabled';
                    console.log(123, form.valid(), event, currentIndex)
                    // return form.valid();
                    return true;
                },
                onFinished: function (event, currentIndex) {
                    console.log(1234)
                    clickSubmit('id_btn_crt_submit');
                    // swal("Good job!", "Submitted!", "success");
                }
            });

        
        function clickSubmit(id){
            $('#'+id).click();
        }

        function makeid(length) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while (counter < length) {
              result += characters.charAt(Math.floor(Math.random() * charactersLength));
              counter += 1;
            }
            return result;
        }


        function initDrop(){
            var drDestroy =  $('.dropify').dropify();
            drDestroy = drDestroy.data('dropify')
            // drDestroy.isDropified()
            drDestroy.destroy();
            drDestroy.init();

        }
        
        function setButtonWavesEffect(event) {
            $(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
            $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
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
            // $('select').selectpicker("destroy");
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

        function createAddZone(){
           
            var action = $('#id_crt_zone_action').val();
            var name = $('#id_crt_zone_name').val();
            if(name != ""){
                if(action == "edit"){
                    var n = 0;
                    for(var x in gCrtZone){
                        n++;
                        var id = $('#id_crt_zone_id').val();
                        var r = gCrtZone[x];
                        if(r.id == id){
                            r.name = name;
                            gCrtZone[x] = r;
                            break;
                        }
                        
                    }
                    generateZone('tbldatacrtzone');
                    closeAddZone($('#id_crt_zone_close_btn'));

                }else{
                    var zoneid = makeid("6");
                    var dzone = {
                        id:zoneid,
                        name : name
                    }
                    gCrtZone.push(dzone);

                }
                $('#id_crt_zone_name').val('');
                generateZone('tbldatacrtzone');
            }
        }

        function createEdtZone(){
            var action = $('#id_edt_zone_action').val();
            var name = $('#id_edt_zone_name').val();
            if(name != ""){
                if(action == "edit"){
                    var n = 0;
                    for(var x in gCrtZone){
                        n++;
                        var id = $('#id_edt_zone_id').val();
                        var r = gCrtZone[x];
                        if(r.id == id){
                            r.name = name;
                            gCrtZone[x] = r;
                            break;
                        }
                        
                    }
                    generateZone('tbldataedtzone');
                    closeAddZone($('#id_edt_zone_close_btn'));

                }else{
                    var zoneid = makeid("6");
                    var dzone = {
                        id:zoneid,
                        name : name
                    }
                    gCrtZone.push(dzone);

                }
                $('#id_edt_zone_name').val('');
                generateZone('tbldataedtzone');
            }
        }

        
       
        function generateZone(element){
            var html = '';
            var n = 0;
            for(var x in gCrtZone){
                n++;
                var item = gCrtZone[x];
                html += '<tr>';
                html += '<td>'+n+'</td>';
                html += '<td>'+item['name']+'</td>';
                html += '<td>';
                html += '<button \
                    onclick="editZone($(this))" \
                    data-id="'+item.id+'" \
                    data-index="'+x+'" \
                    data-type="edtZone" \
                    data-name="'+item.name+'" \
                    type="button" class="btn btn-info waves-effect">Update</button>';
                html += ' <button \
                    onclick="removeZone($(this))" \
                    data-index="'+x+'" \
                    data-type="edtZone" \
                    data-id="'+item.id+'" \
                    data-name="'+item.name+'" \
                    type="button" class="btn btn-danger waves-effect"><i class="material-icons">delete</i> </button> ';
                html += '</td>';
                html += '</tr>';
            }
            $('#'+element+' tbody').html(html)
        }
        function closeAddZone(t){
            var action = $('#id_crt_zone_action').val();
            var type = t.data('type');
            if(type == "crtZone"){
                $('#id_crt_zone_name').val('');
                $('#id_crt_zone_btn').html("Add");
                t.hide("fast");
                $('#id_crt_zone_action').val("add");
            }else{
                $('#id_edt_zone_name').val('');
                $('#id_edt_zone_btn').html("Add");
                t.hide("fast");
                $('#id_edt_zone_action').val("add");
            }
        }
        function removeZone(t){
            var indexZ = t.data('index');
            gCrtZoneDeleted.push(gCrtZone[indexZ])
            gCrtZone.splice(indexZ,1);
            generateZone(tablezoneelement);
            closeAddZone($('#id_crt_zone_close_btn'));
        }
        function editZone(t){
            var id = t.data('id');
            var type = t.data('type');
            if(type == "crtZone"){
                $('#id_crt_zone_btn').html("Update");
                $('#id_crt_zone_action').val("edit");
                var name = t.data('name');
                $('#id_crt_zone_name').val(name);
                $('#id_crt_zone_id').val(id);
                $('#id_crt_zone_close_btn').show('fast');
            }else{
                $('#id_edt_zone_btn').html("Update");
                $('#id_edt_zone_action').val("edit");
                var name = t.data('name');
                $('#id_edt_zone_name').val(name);
                $('#id_edt_zone_id').val(id);
                $('#id_edt_zone_close_btn').show('fast');
            }
        }

       


        function createData(){
            gCrtZoneDeleted = [];
            gCrtZone = [];
            initDrop();
            tablezoneelement = 'tbldatacrtzone';
            $('#id_crt_zone_action').val("add");
            $('#id_area_add_merge').hide();
            $('#id_mdl_create').modal({backdrop: 'static', keyboard: false})  
            $('#id_mdl_create').modal('show');
            $('#id_crt_room').html('');
            $('#id_crt_devices').html('');

            var html_posmap = generatePosmap("");
            // typeRoom

            var html_building = "";
            html_building += "<option value=''>Please choose a building ...</option>";
            for (var i in gBuilding){
                html_building += "<option value="+gBuilding[i].id+">"+gBuilding[i].name+"</option>";
            }

            var html_facility = "";
            html_facility += "<option value=''>Please choose a facility ...</option>";
            for (var i in gFacility){
                html_facility += "<option value='"+gFacility[i]['id']+"'>"+gFacility[i]['name']+"</option>";
            }  
            $('#id_crt_facility_room').html(html_facility);
            $('#id_crt_building_id').html(html_building);
            $('#id_crt_posmap').html(html_posmap);
            enable_datetimepicker()
            select_enable()
        }
        $('#frm_create').submit(function(e){
            e.preventDefault();
            var form =  new FormData(this);
            var collFF = [];
            var bs = $('#id_baseurl').val();
            var vF = $('#id_crt_facility_room').val();
            for(var x in vF ){
                var i = vF[x];
                for(var m in gFacility ){
                    var gFF = gFacility[m];
                    if(gFF['id'] == i){
                        form.append('facility_room_name[]', gFF['name']);
                        // collFF.push(gFF['name'])
                        break;
                    }
                }

            }
            if(gCrtZone.length <= 0){
                swalShowNotification('alert-danger', "Please insert zone minimum 1 zone",'top','center')
                return; 
            }
            form.append('zone', JSON.stringify(gCrtZone));
            $.ajax({
                url : bs+"deskroom/post/create",
                type : "POST",
                dataType: "json",
                data:  form,
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                    loadingg('Please wait ! ', 'Process to save')

                },
                success:function(data){
                    swal.close();
                    if(data.status == "success"){
                        $('#frm_create')[0].reset();
                        init();
                        $('#id_mdl_create').modal('hide');
                        Swal.fire({
                            title:'Success',
                            text: data.msg,
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Close !',
                            }).then((result) => {
                                window.location.reload();
                        })
                        // swalShowNotification('alert-success', data.msg,'top','center')
                    }else{
                        swalShowNotification('alert-danger', data.msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        })  
        $('#frm_update').submit(function(e){
            e.preventDefault();
            // var form = $('#frm_update').serialize();
            var id = $('#id_edt_id').val()
            var bs = $('#id_baseurl').val();
            var form =  new FormData(this);
            var vF = $('#id_edt_facility_room').val();
            var collFF = [];
            for(var x in vF ){
                var i = vF[x];
                for(var m in gFacility ){
                    var gFF = gFacility[m];
                    if(gFF['id'] == i){
                        form.append('facility_room_name[]', gFF['name']);
                        // collFF.push(gFF['name'])
                        break;
                    }
                }

            }

            if(gCrtZone.length <= 0){
                swalShowNotification('alert-danger', "Please insert zone minimum 1 zone",'top','center')
                return; 
            }
            
            form.append('zone', JSON.stringify(gCrtZone));
            form.append('zone_delete', JSON.stringify(gCrtZoneDeleted));
            $.ajax({
                url : bs+"deskroom/post/update/"+id,
                type : "POST",
                dataType: "json",
                contentType: false,
                cache: false,
                processData:false,
                data:  form,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                    loadingg('Please wait ! ', 'Process to save')
                },
                success:function(data){
                    swal.close();
                    if(data.status == "success"){

                        $('#frm_update')[0].reset();
                        $('#id_mdl_update').modal('hide');
                        init();
                        Swal.fire({
                            title:'Success',
                            text: data.msg,
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Close !',
                            }).then((result) => {
                                window.location.reload();
                        })
                    }else{
                        swalShowNotification('alert-danger', data.msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        });



       
        function getFacility(){
            gFacility = [];
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"facility/get/data",
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                       gFacility = [];
                       // $.each(data.collection, function(index, item){
                       //  gFacility.push(item.name);
                       // })
                       gFacility = data.collection
                    }else{
                        var msg = "Your session is expired, login again !!!";
                        showNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        }

        function getDeskController(){
            gDeskController = [];
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
                       gDeskController = [];
                       // $.each(data.collection, function(index, item){
                       //  gFacility.push(item.name);
                       // })
                       gDeskController = data.collection
                    }else{
                        
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        }

        
        function init(){
            var bs = $('#id_baseurl').val();
            var modules = getModule();
            gCrtZoneDeleted = [];
            gCrtZone = [];
            $.ajax({
                url : bs+"deskroom/get/data",
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        clearTable($('#tbldata'));
                        $('#id_count_total').html(data.collection.length)
                        var html = "";
                        var nn = 0;
                        $.each(data.collection, function(index, item){
                            nn++;
                            var price = item.price == null || item.price == "" ? 0 : item.price ;
                            var ra_name = item.ra_name == null  ? "" : item.ra_name
                            html += '<tr>'
                            html += '<td>'+nn+'</td>';
                             var imgp = item.image == "" || item.image == null ? defaultImage : item.image;
                            html += '<td> <a href="javascript:void(0);" class="thumbnail"><img src="'+pathImage+imgp+'" style="height:64px;" class="img-responsive"></a></td>';
                            html += '<td style="width:150px;">'+item.name+'</td>';
                            html += '<td style="width:150px;">'+item.building_name+'</td>';
                            html += '<td>'+item.capacity+'</td>';
                            html += '<td>'+item.work_time+'</td>';
                            // html += '<td>'+item.work_day+'</td>';
                            // 
                            // console.log(item)
                            html += '<td>'+enabledRoom[item.is_disabled]+'</td>';

                            html += '<td>';
                            html += '<button \
                                 onclick="editData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-id="'+item.name+'" \
                                 data-ra_id="'+item.ra_id+'" \
                                 type="button" class="btn btn-info btn-sm waves-effect">Detail</button>&nbsp;&nbsp;';
                            html += '<button \
                                 onclick="editorData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-name="'+item.name+'" \
                                 data-ra_id="'+item.ra_id+'" \
                                 type="button" class="btn btn-warning  btn-sm waves-effect">Editor</button>&nbsp;&nbsp;';
                            html += ' <button \
                                 onclick="removeData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-name="'+item.name+'" \
                                 type="button" class="btn btn-danger  btn-sm waves-effect"><i class="material-icons">delete</i> </button> ';
                            html += '</td>';
                            html += '</tr>';
                        })
                        $('#tbldata tbody').html(html);

                        initTable($('#tbldata'));
                        getDeskController();
                    }else{

                        var msg = "Your session is expired, login again !!!";
                        swalShowNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        }

         var form = $('#frm_update');

                            form.steps({
                                headerTag: 'h3',
                                bodyTag: 'div.fieldset_wizard',
                                transitionEffect: 'slideLeft',
                                onInit: function (event, currentIndex) {
                                    // swal.close()
                                    

                                    //Set tab width
                                    var $tab = $(event.currentTarget).find('ul[role="tablist"] li');
                                    var tabCount = $tab.length;
                                    $tab.css('width', (100 / tabCount) + '%');
                                    // $.AdminBSB.input.activate();
                                    // enable_datetimepicker();
                                    // enable_datetimepicker()
                                    // select_enable()

                                    //set button waves effect
                                    setButtonWavesEffect(event);
                                },
                                onStepChanging: function (event, currentIndex, newIndex) {
                                    if (currentIndex > newIndex) { return true; }
                                    if (currentIndex < newIndex) {
                                        form.find('.body:eq(' + newIndex + ') label.error').remove();
                                        form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
                                    }
                                    form.validate().settings.ignore = ':disabled,:hidden';
                                    return form.valid();
                                },
                                onStepChanged: function (event, currentIndex, priorIndex) {
                                    setButtonWavesEffect(event);
                                },
                                onFinishing: function (event, currentIndex) {
                                    form.validate().settings.ignore = ':disabled';
                                    console.log(form.valid());

                                    return form.valid();
                                },
                                onFinished: function (event, currentIndex) {
                                    console.log(12322);
                                    clickSubmit('id_btn_edt_submit');
                                    // swal("Good job!", "Submitted!", "success");
                                }
                            });


        function editData(t){
            var id = t.data('id');
            var bs = $('#id_baseurl').val();
            var modules = getModule();
            $('#frm_update')[0].reset();
            assetsImageUrl = "";
            tablezoneelement = 'tbldataedtzone';
            gCrtZoneDeleted = [];
            gCrtZone = [];

            $.ajax({
                url : bs+"deskroom/get/edit/"+id,
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        var input = data.collection;
                        var pathimage = bs+"assets/file/room/";
                        assetsImageUrl = input['image'];
                        var imgp = input.image == "" || input.image == null ? defaultImage : input.image;
                        var img_room_map = input.room_map == "" || input.room_map == null ? defaultImage : input.room_map;
                        var imap = pathImage+imgp;
                        var ima_room_map_path = pathImage+img_room_map;
                        $('#id_edt_image_old').attr("src",imap);
                        $('#id_edt_room_map_old').attr("src",ima_room_map_path);

                        // gCrtZone = input.zone;

                        for(var x in input.zone){
                            var rzon1 = input.zone[x];
                            gCrtZone.push({
                                'id' : rzon1.zone_id,
                                'name' : rzon1.name,
                                'desk_room_id' : rzon1.desk_room_id,
                            })
                        }
                        generateZone('tbldataedtzone');
                        if(input['image2'] != null){
                            var im2= input['image2'].split("##");
                            var ht = "";
                            var n0 = 0;
                            for(var x in im2){
                                n0++;
                                var imgp2 = im2[x] == "" || im2[x] == null ? defaultImage : im2[x];
                                var imap2 = pathImage+imgp2;
                                $('#id_edt_image2_'+n0+'_old').attr("src",imap2);
                            }
                        }
                        $('#id_edt_image_old').attr("src",pathimage+input['image']);
                        var timest = input['work_time'];
                        var timesp = timest.split('-');
                        var html_room = "";
                        var html_devices="";
                        $('#id_edt_name').val(input['name']);
                        // loadingg('Please wait ! ', 'Loading . . . ')
                        $('#id_edt_price').val(input['price']);
                                $('#id_edt_access_id').val(input['access_id']);
                                $('#id_edt_google_map').val(input['google_map']);
                                $('#id_edt_name').val(input['name']);
                                $('#id_edt_capacity').val(input['capacity']);
                                $('#id_edt_location').val(input['location'])
                                $('#id_edt_description').val(input['description'])
                                $('#id_edt_id').val(id);
                                $('#id_edt_work_start').val(timesp[0]);
                                $('#id_edt_work_end').val(timesp[1]);

                                var wrkdaydb = input['work_day'];
                                var wrkdaydb_array = wrkdaydb.split(",");


                                var facilitydb = input['facility_room'];
                                var facilitydb_array = [];
                                for(var xxx in input['facility_room2']){
                                    facilitydb_array.push(input['facility_room2'][xxx]['facility_id'])
                                }
                                var html_aut = "";
                                var html_wkd = "";
                                var html_active = "";
                                // var html_typeroom = "";
                                var html_building = "";
                                var dataR = "";
                                for(var x in enabledRoom){
                                    var sel = x == input['is_disabled'] ? "selected" : "";
                                    dataR += '<option '+sel+' value="'+x+'">'+enabledRoom[x]+'</option>'
                                }
                                $('#id_edt_is_disabled').html(dataR)
                   
                                var workday = ["SUNDAY", "MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY"];
                                $.each(workday,function(index, item) {
                                    var sl = "";
                                    if (wrkdaydb_array.indexOf(item.toUpperCase()) >= 0) {
                                        sl = "selected";
                                    }
                                    html_wkd += "<option "+sl+" value="+item+">"+item+"</option>";
                                });
                                var html_facility = "";
                                html_facility += "<option value=''>Please choose a facility ...</option>";
                                console.log(facilitydb_array)
                                for (var i in gFacility){
                                    var selcted = "";
                                    if(facilitydb_array.indexOf(gFacility[i]['id']) >= 0) { selcted = "selected"}
                                    html_facility += "<option "+selcted+" value='"+gFacility[i]['id']+"'>"+gFacility[i]['name']+"</option>";
                                }

                                html_building += "<option value=''>Please choose a building ...</option>";
                                for (var i in gBuilding){

                                    var selcted = gBuilding[i]['id'] == input['building_id'] ?"selected":"";
                                    html_building += "<option "+selcted+" value='"+gBuilding[i]['id']+"'>"+gBuilding[i]['name']+"</option>";
                                }
                                $('#id_edt_building_id').html(html_building)
                                $('#id_edt_workday').html(html_wkd);
                                $('#id_edt_facility_room').html(html_facility);
                        var html_posmap = generatePosmap(input['posmap']);
                        // console.log(html_posmap)
                        $('#id_edt_posmap').html(html_posmap);
                                enable_datetimepicker()
                                    select_enable()


                        setTimeout(function(){

                        }, 1000)
                        

                        $('#id_mdl_update').modal('show');
                    }else{
                        var msg = "Your session is expired, login again !!!";
                        swalShowNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                    
                },
                error: errorAjax
            })
        }

        


        function finderList(list, key, value){
            var ff = null;
            for(var x in list){
                if(list[x][key] == value){
                    ff = list[x];
                    break;
                }
            }
            return ff;
        }

        function removeData(t){
            var id = t.data('id');
            var name = t.data('name');
            var form = new FormData();
            form.append('id', id);
            form.append('name', name);
            Swal.fire({
                title:'Confirmation',
                text: "You will lose the data desk room "+name+" !",
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
                            url : bs+"deskroom/post/delete",
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
                                    swalShowNotification('alert-success', "Succes deleted desk room "+name ,'top','center')
                                    init();
                                }else{
                                    swalShowNotification('alert-danger', "Data not found",'bottom','left')
                                }
                            },
                            error: errorAjax,
                        })
                    }
                else{

                }
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
    <script type="text/javascript">
         // EDITOR
        function editorData(t){

            var id = t.data('id');
            var name = t.data('name');
            var bs = $('#id_baseurl').val();
            var modules = getModule();
            $('#frm_update')[0].reset();

            window.location.href = bs + 'deskroom/editor-zone?room='+id
            // assetsImageUrl = "";

            return;
            $.ajax({
                url : bs+"deskroom/get/editor/"+id,
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    loadingg('Please wait ! ', 'Loading . . . ')
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    swal.close();

                    // $('#id_edt_image2_old_div').html("")
                    if(data.status == "success"){
                        editorZoneList = [];
                        editorZoneObj = {};
                        editorZoneObjDesk = [];

                        var col = data.collection;
                        editorZoneList = col;
                        var html_zone = "";
                        // html_zone += "<option value=''>Please choose a facility ...</option>";
                        for (var i in editorZoneList){
                            var selcted = "";
                            if(i == 0){
                                selcted = "selected";
                            }
                            html_zone += "<option "+selcted+" value='"+editorZoneList[i]['zone_id']+"'>"+editorZoneList[i]['name']+"</option>";
                        }
                        $('#id_edtor_zone').html(html_zone);
                        var html_controller = '<option value=""> -- Desk Controller --</option>';
                        for(var x in gDeskController){
                            html_controller += '<option value="'+gDeskController[x].id+'" >'+gDeskController[x].name+'</option>';
                        }
                        $('#id_editor_desk_controller').html(html_controller)

                        enable_datetimepicker()
                        select_enable()
                        $('#id_mdl_editorLabel_title').html(name)
                        $('#id_editor_room_id').val(id)
                        if( $('#id_edtor_zone').val() != null && $('#id_edtor_zone').val() != ""){
                            editorZoneObj = finderList(editorZoneList, 'zone_id', $('#id_edtor_zone').val());
                            $('#id_editor_zone_pointer_selector').val(editorZoneObj.pointer);
                            $('#id_editor_zone_id').val(editorZoneObj.zone_id)
                            $('#id_editor_zone_action').val("add");
                        }

                        $('#id_mdl_editor').modal({backdrop: 'static', keyboard: false})  
                        $('#id_mdl_editor').modal('show');

                        generateEditorZone('tbldataeditorzone');
                    }else{
                        var msg = "Your session is expired, login again !!!";
                        swalShowNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                    
                },
                error: errorAjax
            })
        }
        // function onChangeEditorZone(){
        //     closeEditorZone();
        //     editorZoneObj = finderList(editorZoneList, 'zone_id', $('#id_edtor_zone').val());
        //     $('#id_editor_zone_pointer_selector').val(editorZoneObj.pointer);
        //     $('#id_editor_zone_id').val(editorZoneObj.zone_id)
        //     $('#id_editor_zone_action').val("add");
        //     generateEditorZone('tbldataeditorzone');
        // }

        // function openEditorPointerWindow(selector, zone, room){
        //     var bs = $('#id_baseurl').val();
        //     var zone_id = $('#'+zone).val()
        //     var room_id = $('#'+room).val()
        //     var val = $('#'+selector).val()
        //     if(zone_id != ""){
        //         var url = 'deskroom/editor-zone?room='+room_id+'&selector='+selector+'&zone='+zone_id+'&pointer='+val;
        //         var win = window.open(url, "_blank", "width=1200,height=850");
        //     }else{
        //         var msg = "Zone map is empty";
        //         swalShowNotification('alert-danger', msg,'top','center')
        //     }
        // }
        // function changeController(initSelector, toSelector, value = ""){
        //     var iVal = $('#'+initSelector).val();
        //     var bs = $('#id_baseurl').val();
        //     if(iVal == ""){
        //         $('#'+toSelector).html('')
        //         enable_datetimepicker()
        //         select_enable()
        //         return ;
        //     }
        //     var old_c = $('#id_editor_old_controller').val(); 
        //     var old_s = $('#id_editor_old_socket').val(); 
        //     // console.log(old_c,old_s)
        //     // console.log("IVALold_c",iVal)

        //     $.ajax({
        //         url : bs+"deskroom/get/editor-controller-socket/"+iVal+"?value="+value,
        //         type : "GET",
        //         dataType: "json",
        //         beforeSend: function(){
        //             loadingg('Please wait ! ', 'Loading . . . ')
        //             $('#id_loader').html('<div class="linePreloader"></div>');
        //         },
        //         success:function(data){
        //             swal.close()
        //             if(data.status == "success"){
                    
        //                var col = data.collection
        //                // console.log(col);
        //                var html_socket = '<option value=""> -- Desk Socket --</option>';
        //                 for(var x in col){
        //                     var s = ''
        //                     if(col[x].desk_id == null || col[x].desk_id == ""){
        //                     }else{
        //                         // console.log(old_c != iVal ,old_s != col[x].socket);
        //                         if(old_c != iVal && old_s != col[x].socket ){
        //                             continue; 
        //                         }else{
        //                             // console.log(123)
        //                         }
        //                     }
        //                     if(old_c == iVal && old_s == col[x].socket ){
        //                          s = "selected"
        //                     }
        //                     // if(old_s == col[x]['socket']){
        //                     //     s = "selected"
        //                     // }
        //                     html_socket += '<option '+s+' value="'+col[x].socket+'" >'+col[x].socket+'</option>';
        //                 }
        //                 $('#'+toSelector).html(html_socket)
        //                 enable_datetimepicker()
        //                 select_enable()
        //             }else{
                        
        //             }
        //             $('#id_loader').html('');
        //         },
        //         error: errorAjax
        //     });
        // }
        // function closeEditorZone(){
        //     var action = $('#id_editor_zone_action').val();
        //     $('#id_editor_desk_socket').html('<option value="">-- Desk Socket --<option>');
        //     $('#id_editor_desk_controller').html('<option value="">-- Desk Controller --<option>'),
        //     $('#id_editor_zone_number').val("");
        //     $('#id_editor_zone_pointer').val("");
        //     $('#id_editor_desk_id').val("");
        //     $('#id_editor_desk_old_socket').val("");
        //     $('#id_editor_desk_old_controller').val("");
        //     $('#id_editor_zone_action').val("add");
        //     $('#id_editor_zone_close_btn').hide("fast");
        //     $('#id_editor_zone_btn').html("ADD");

        //     var html_controller = '<option value=""> -- Desk Controller --</option>';
        //     for(var x in gDeskController){
        //         var selected = "";
        //         html_controller += '<option '+selected+' value="'+gDeskController[x].id+'" >'+gDeskController[x].name+'</option>';
        //     }
        //     $('#id_editor_desk_controller').html(html_controller)
        //     select_enable()
        //     enable_datetimepicker()

           
        // }
        // function editEditorZone(t){
        //     var id = t.data('id');
        //     var socket = t.data('socket');
        //     var number = t.data('number');
        //     var pointer = t.data('pointer');
        //     var controller_id = t.data('controller');

        //     $('#id_editor_zone_action').val("edit");
        //     $('#id_editor_zone_close_btn').show("fast");
        //     $('#id_editor_zone_btn').html("UPDATE");
        //     $('#id_editor_desk_id').val(id);
        //     $('#id_editor_old_controller').val(controller_id);
        //     $('#id_editor_old_socket').val(socket);
        //     $('#id_editor_zone_number').val(number);
        //     $('#id_editor_zone_pointer').val(pointer);

        //     var html_controller = '<option value=""> -- Desk Controller --</option>';
        //     for(var x in gDeskController){
        //         var selected = "";
        //         if(controller_id == gDeskController[x].id){selected ='selected';}
        //         html_controller += '<option '+selected+' value="'+gDeskController[x].id+'" >'+gDeskController[x].name+'</option>';
        //     }
        //     $('#id_editor_desk_controller').html(html_controller)
        //     enable_datetimepicker()
        //     select_enable()
        //     changeController('id_editor_desk_controller','id_editor_desk_socket',socket)
        // }
        // function generateEditorZone(element){
        //     var html = '';
        //     var n = 0;
        //     $.ajax({
        //         url : bs+"deskroom/get/editor-data",
        //         type : "POST",
        //         data:editorZoneObj,
        //         dataType: "json",
        //         beforeSend: function(){

        //             $('#id_loader').html('<div class="linePreloader"></div>');
        //         },
        //         success:function(data){
        //             // swal.close()
        //             if(data.status == "success"){
        //                 var col = data.collection;
        //                 var html = '';
        //                 var nn = 0;
        //                 for(var x in col){
        //                     nn++;
        //                     var item = col[x];
        //                     var pointer = item.pointer_desk_x+","+item.pointer_desk_y;
        //                     html += '<tr>'
        //                     html += '<td>'+nn+'</td>';
        //                     html += '<td style="width:150px;">'+editorZoneObj.name+'</td>';
        //                     html += '<td style="width:150px;">'+item.block_number+'</td>';
        //                     html += '<td>'+pointer+'</td>';
        //                     html += '<td>'+item.controller_name+'</td>';
        //                     html += '<td>'+item.socket+'</td>';
        //                     html += '<td>';
        //                     html += '<button \
        //                          onclick="editEditorZone($(this))" \
        //                          data-id="'+item.desk_id+'" \
        //                          data-socket="'+item.socket+'" \
        //                          data-number="'+item.block_number+'" \
        //                          data-pointer="'+pointer+'" \
        //                          data-controller="'+item.controller_id+'" \
        //                          type="button" class="btn btn-warning  btn-sm waves-effect">Edit</button>&nbsp;&nbsp;';
        //                     html += ' <button \
        //                          onclick="removeEditorZone($(this))" \
        //                          data-id="'+item.desk_id+'" \
        //                          data-room="'+item.desk_room_id+'" \
        //                          data-number="'+item.block_number+'" \
        //                          data-socket="'+item.socket+'" \
        //                          data-controller="'+item.controller_id+'" \
        //                          type="button" class="btn btn-danger  btn-sm waves-effect"><i class="material-icons">delete</i> </button> ';
        //                     html += '</td>';
        //                 }
        //                 $('#'+element+' tbody').html(html)
        //             }else{
                        
        //             }
        //             $('#id_loader').html('');
        //         },
        //         error: errorAjax
        //     });
            
        // }
        // function removeEditorZone(t){
        //     var id = t.data('id');
        //     var socket = t.data('socket');
        //     var number = t.data('number');
        //     var pointer = t.data('pointer');
        //     var room = t.data('room');
        //     var controller_id = t.data('controller');


        //     var form = new FormData();
        //     form.append('id', id);
        //     form.append('socket', socket);
        //     form.append('number', number);
        //     form.append('controller_id', controller_id);
        //     form.append('room', room);
        //     Swal.fire({
        //         title:'Confirmation',
        //         text: "You will lose the data desk !",
        //         type: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Delete !',
        //         cancelButtonText: 'Cancel !',
        //         reverseButtons: true
        //         }).then((result) => {
        //             if (result.value) {
        //                 var bs = $('#id_baseurl').val();
        //                 $.ajax({
        //                     url : bs+"deskroom/post/delete-editor-zone",
        //                     type: "POST",
        //                     data : form,
        //                     processData: false,
        //                     contentType: false,
        //                     dataType :"json",
        //                     beforeSend: function(){
        //                         loadingg('Please wait ! ', 'Loading . . . ')
        //                         $('#id_loader').html('<div class="linePreloader"></div>');

        //                     },
        //                     success:function(data){
        //                         swal.close()
        //                         $('#id_loader').html('');
        //                         if (data.status == "success") {
        //                             // swalShowNotification('alert-success', "Succes deleted desk "+number ,'top','center')
        //                             generateEditorZone('tbldataeditorzone');
        //                         }else{
        //                             // swalShowNotification('alert-danger', "Data not found",'bottom','left')
        //                         }
        //                     },
        //                     error: errorAjax,
        //                 })
        //             }
        //         else{

        //         }
        //     })
            
        // }

        // function createEditorZone(){
            
        //     var form = {
        //         desk_id     : $('#id_editor_desk_id').val(),
        //         action      : $('#id_editor_zone_action').val(),
        //         old_socket  : $('#id_editor_old_socket').val(),
        //         socket      : $('#id_editor_desk_socket').val(),
        //         number      : $('#id_editor_zone_number').val(),
        //         pointer     : $('#id_editor_zone_pointer').val(),
        //         controller  : $('#id_editor_desk_controller').val(),
        //         zone        : editorZoneObj.zone_id,
        //         room        : editorZoneObj.desk_room_id,
        //     };
        //     $.ajax({
        //         url : bs+"deskroom/post/create-editor-zone",
        //         type : "POST",
        //         data:form,
        //         dataType: "json",
        //         beforeSend: function(){
        //             loadingg('Please wait ! ', 'Loading . . . ')
        //             $('#id_loader').html('<div class="linePreloader"></div>');
        //         },
        //         success:function(data){
        //             swal.close()
        //             if(data.status == "success"){
        //                 closeEditorZone();
        //                 var html_controller = '<option value=""> -- Desk Controller --</option>';
        //                 for(var x in gDeskController){
        //                     html_controller += '<option value="'+gDeskController[x].id+'" >'+gDeskController[x].name+'</option>';
        //                 }
        //                 $('#id_editor_desk_controller').html(html_controller)
        //                 enable_datetimepicker()
        //                 select_enable()
        //                 // changeController('id_editor_desk_controller' ,'id_editor_desk_socket', '')
        //                 generateEditorZone('tbldataeditorzone');
        //             }else{
                        
        //             }
        //             $('#id_loader').html('');
        //         },
        //         error: errorAjax
        //     });
            
        // }
    </script>
    </body>
</html>

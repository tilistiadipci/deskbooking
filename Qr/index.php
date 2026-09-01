
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
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">

                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>Room List</h2>
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
                                        <!-- <th>Work Day</th> -->
                                        <?php if ($modules['automation']['is_enabled'] == 1): ?>
                                            <th>Automation Active</th>
                                            <th>Automation</th>
                                        <?php else: ?>
                                        <?php endif; ?>
                                        <?php if ($modules['price']['is_enabled'] == 1): ?>
                                            <!-- <th>Price</th> -->
                                        <?php else: ?>
                                        <?php endif; ?>
                                        <th>Status</th>
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
                            <h4 class="modal-title" id="idmdlcrLabel">Create Room </h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                            <form id="frm_create">
                                <label for="">Room Thumbnail </label>
                                <div class="form-group">
                                    <input type="file" name="image"  id="input-file-to-destroy" class="dropify" data-height="150" data-max-file-size="2M" accept="image/*"  />
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label for="">Image 1 </label>
                                        <div class="form-group">
                                            <input type="file" name="image2[]"  class="dropify" data-height="120" data-max-file-size="2M" accept="image/*"   
                                            />
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <label for="">Image 2 </label>
                                        <div class="form-group">
                                            <input type="file" name="image2[]"  class="dropify" data-height="120" data-max-file-size="2M" accept="image/*"   
                                            />
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <label for="">Image 3 </label>
                                        <div class="form-group">
                                            <input type="file" name="image2[]"  class="dropify" data-height="120" data-max-file-size="2M" accept="image/*"   
                                            />
                                        </div>
                                    </div>
                                </div>
                                <label for="">Building</label>
                                <div class="form-group">
                                    <select name="building_id" id="id_crt_building_id" class="form-control show-tick"></select>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label for="">Type Room</label>
                                        <div class="form-group">
                                            <select onchange="initTypeRoom('','id_crt_type_room','id_crt_merge_room','')" name="type_room" id="id_crt_type_room" class="form-control show-tick">
                                                <option value="single">Single Room</option>
                                                <option value="merge">Merge Room</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6" id="id_area_add_merge">
                                        <label for="">Merger Room</label>
                                        <div class="form-group">
                                            <select name="merge_room[]" id="id_crt_merge_room" class="form-control show-tick" multiple data-actions-box="true">
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <label for="">Status</label>
                                <div class="form-group">
                                    <select name="is_disabled" id="id_crt_is_disabled" class="form-control show-tick">
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
                                        <input type="text" autocomplete="off" name="google_map" id="id_edt_google_map"   class="form-control" placeholder="Google Map">
                                    </div>
                                </div>
                                <label for="">Room Capacity <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" name="capacity" id="id_crt_capacity" required=""  class="form-control" placeholder="Capacity">
                                    </div>
                                </div>
                            <?php if ($modules['automation']['is_enabled'] == 1): ?>
                                <label for="">Automation Active</label>
                                <div class="form-group">
                                    <select name="is_automation" id="id_crt_automation_active" class="form-control show-tick">
                                        <option value="0">Off</option>
                                        <option value="1">On</option>
                                    </select>
                                </div>
                                <label for="">Automation List</label>
                                <div class="form-group">
                                    <select name="automation_id" id="id_crt_automation" class="form-control show-tick"></select>
                                </div>
                            <?php else: ?>

                            <?php endif; ?>
                                
                                <label for="">Room Facility</label>
                                <div class="form-group">
                                    <select  data-live-search="true" multiple name="facility_room[]" id="id_crt_facility_room" class="form-control show-tick" data-actions-box="true"></select>
                                </div>
                                <label for="">Working Day</label>
                                <div class="form-group">
                                    <select multiple name="work_day[]" id="id_crt_workday" class="form-control show-tick">
                                        <option value="SUNDAY">SUNDAY</option>
                                        <option value="MONDAY">MONDAY</option>
                                        <option value="TUESDAY">TUESDAY</option>
                                        <option value="WEDNESDAY">WEDNESDAY</option>
                                        <option value="WEDNESDAY">THURSDAY</option>
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
                            <?php if ($modules['price']['is_enabled'] == 1): ?>
                               <!--  <label for="">Price of the room per room<b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input  type="number" name="price" id="id_crt_price"  class=" form-control" required="" placeholder="Price Room ...">
                                    </div>
                                    
                                </div> -->
                            <?php else: ?>

                            <?php endif; ?>
                               <!--  <label for="">Room Access Controll</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input autocomplete="off" type="text" name="access_id" id="id_crt_access_id" required=""  class="form-control" placeholder="Access Number Ex. 1 ">
                                    </div>
                                </div> -->
                            
                                <br>
                                <button type="submit" style="display: none;" id="id_btn_crt_submit"  class="btn btn-primary m-t-15 waves-effect">Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6">
                                    <button onclick="clickSubmit('id_btn_crt_submit')" type="button" class="btn btn-primary waves-effect ">SAVE</button>
                                </div>
                            </div>
                            <!-- <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button> -->
                        </div>
                    </div>
                </div>
            </div>
    <div class="modal fade" id="id_mdl_update" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Update Room</h4>
                        </div>
                        <div class="modal-body " id="id_mdl_update">
                            <form id="frm_update">

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
                                            <input type="file" name="image"  id="id_edt_image" class="dropify" data-height="100" data-max-file-size="2M" accept="image/*"  />
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label for="">Old Image 1 </label>
                                        <a href="javascript:void(0);" class="thumbnail">
                                            <img id="id_edt_image2_1_old" src="" style="height:100px" class="img-responsive">
                                        </a>
                                    </div>
                                    <div class="col-xs-4">
                                        <label for="">Old Image 2 </label>
                                        <a href="javascript:void(0);" class="thumbnail">
                                            <img id="id_edt_image2_2_old" src="" style="height:100px" class="img-responsive">
                                        </a>
                                    </div>
                                    <div class="col-xs-4">
                                        <label for="">Old Image 3 </label>
                                        <a href="javascript:void(0);" class="thumbnail">
                                            <img id="id_edt_image2_3_old" src="" style="height:100px" class="img-responsive">
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label for="">New Image 1 </label>
                                        <div class="form-group">
                                            <input type="file" name="image2_1"  class="dropify" data-height="100" data-max-file-size="2M" accept="image/*"   
                                            />
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <label for="">New Image 2 </label>
                                        <div class="form-group">
                                            <input type="file" name="image2_2"  class="dropify" data-height="100" data-max-file-size="2M" accept="image/*"   
                                            />
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <label for="">New Image 3 </label>
                                        <div class="form-group">
                                            <input type="file" name="image2_3"  class="dropify" data-height="100" data-max-file-size="2M" accept="image/*"   
                                            />
                                        </div>
                                    </div>
                                </div>
                                
                                <label for="">Building</label>
                                <div class="form-group">
                                    <select name="building_id" id="id_edt_building_id" class="form-control show-tick">
                                        
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label for="">Type Room</label>
                                        <div class="form-group">
                                            <select onchange="initTypeRoom('edit','id_edt_type_room','id_edt_merge_room','')" name="type_room" id="id_edt_type_room" class="form-control show-tick">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6" id="id_area_edt_merge">
                                        <label for="">Merger Room</label>
                                        <div class="form-group">
                                            <select name="merge_room[]" id="id_edt_merge_room" class="form-control show-tick" multiple data-actions-box="true">
                                            </select>
                                        </div>
                                    </div>
                                    
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
                                        <input type="hidden" autocomplete="off" name="radid" id="id_edt_radid" readonly="" required=""  >
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
                           
                            <?php if ($modules['automation']['is_enabled'] == 1): ?>
                                <label for="">Automation Active</label>
                                <div class="form-group">
                                    <select name="is_automation" id="id_edt_automation_active" class="form-control show-tick">
                                        <option value="0">Off</option>
                                        <option value="1">On</option>
                                    </select>
                                </div>
                                <label for="">Automation List</label>
                                <div class="form-group">
                                    <select name="automation_id" id="id_edt_automation" class="form-control show-tick"></select>
                                </div>
                            <?php else: ?>

                            <?php endif; ?>

                                <label for="">Room Facility</label>
                                <div class="form-group">
                                    <select data-live-search="true" multiple name="facility_room[]" id="id_edt_facility_room" class="form-control show-tick" data-actions-box="true"></select>
                                </div>
                                <label for="">Working Day <b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <select required="" multiple name="work_day[]" id="id_edt_workday" class="form-control show-tick">
                                     
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
                            <?php if ($modules['price']['is_enabled'] == 1): ?>
                                <!-- <label for="">Price of the room per room<b style="color:red;">*</b></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input  type="number" name="price" id="id_edt_price"  class=" form-control" required="" placeholder="Price Room ...">
                                    </div>
                                    
                                </div> -->
                            <?php else: ?>

                            <?php endif; ?>
                               <!--  <label for="">Room Access Controll</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input autocomplete="off" type="text" name="access_id" id="id_edt_access_id" required=""  class="form-control" placeholder="Access Number Ex. 1 ">
                                    </div>
                                </div> -->
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
                                    <button onclick="clickSubmit('id_btn_edt_submit')" type="button" class="btn btn-primary waves-effect ">SAVE</button>
                                </div>
                            </div>
                            <!-- <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button> -->
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
        var assetsImageUrl = "";
        var enabledRoom = ["Enabled", "Disabled"];
        var building = $('#id_building').val();
        var gBuilding = JSON.parse(building);
        var gDatalistRoom = [];


        $(function(){
            init();
            initSingle();
            getAutomation();
            getFacility();
        }) 
        function clickSubmit(id){
            $('#'+id).click();
        }

        // $('.dropify').dropify();
        initDrop();
        function initDrop(){
            var drDestroy =  $('.dropify').dropify();
            drDestroy = drDestroy.data('dropify')
            // drDestroy.isDropified()
            drDestroy.destroy();
            drDestroy.init();

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
        function createData(){
            initDrop();
            $('#id_area_add_merge').hide();
            $('#id_mdl_create').modal('show');
            $('#id_crt_room').html('');
            $('#id_crt_devices').html('');

            var html_automation = "";
            html_automation += "<option value=''>Please choose a automation ...</option>";
            for (var i in gAutomation){
                html_automation += "<option value="+gAutomation[i].id+">"+gAutomation[i].name+"</option>";
            }

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

            var html_typeroom = "";
            html_typeroom += "";
            for (var i in typeRoom){
                html_typeroom += "<option value="+typeRoom[i].value+">"+typeRoom[i].text+"</option>";
            }
            $('#id_crt_automation').html(html_automation);
            $('#id_crt_facility_room').html(html_facility);
            $('#id_crt_building_id').html(html_building);
            $('#id_crt_type_room').html(html_typeroom);



            enable_datetimepicker()
            select_enable()
        }
        $('#frm_create').submit(function(e){
            e.preventDefault();
            // gFacility = data.collection
            var form =  new FormData(this);
            var vF = $('#id_crt_facility_room').val();
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
            if($('#id_crt_type_room').val() == "merge"){
                if($('#id_crt_merge_room').val().length <= 1){
                    swalShowNotification('alert-warning',"Please choose a merge/combine room, minimum 2 rooms",'top','center');
                    return;
                }
            }



            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"room/post/create",
                type : "POST",
                dataType: "json",
                data:  form,
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        $('#frm_create')[0].reset();

                        init();
                        $('#id_mdl_create').modal('hide');
                          swalShowNotification('alert-success', data.msg,'top','center')
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
            if($('#id_edt_type_room').val() == "merge"){
                if($('#id_edt_merge_room').val().length <= 1){
                    swalShowNotification('alert-warning',"Please choose a merge/combine room, minimum 2 rooms",'top','center');
                    return;
                }
            }
            // console.log()
            $.ajax({
                url : bs+"room/post/update/"+id,
                type : "POST",
                dataType: "json",
                contentType: false,
                cache: false,
                processData:false,
                // data : form,
                data:  form,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        $('#frm_update')[0].reset();
                        $('#id_mdl_update').modal('hide');
                        init();
                        swalShowNotification('alert-success', data.msg,'top','center')
                    }else{
                        swalShowNotification('alert-danger', data.msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        });



        function getAutomation(){
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"automation/get/data",
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                       gAutomation = data.collection;
                    }else{
                        var msg = "Your session is expired, login again !!!";
                        showNotification('alert-danger', msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        }
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
        function initTypeRoom(action,id_type_room,id_merge_room, values){
            // console.log($('#'+id_type_room).val());
            if($('#'+id_type_room).val() == ""){
                if(action == "edit"){
                    $('#id_area_edt_merge').hide('slow');
                }else{
                    $('#id_area_add_merge').hide('slow');
                }
                
                return;
            }
            if($('#'+id_type_room).val() == "single"){
                if(action == "edit"){
                    $('#id_area_edt_merge').hide('slow');
                }else{
                    $('#id_area_add_merge').hide('slow');
                }
                return;
            }
            if(values == ""){
                values = "null"
            }
            if(action == "edit"){
                $('#id_area_edt_merge').show('slow');
            }else{
                $('#id_area_add_merge').show('slow');
            }

            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"room/get/merge",
                type : "POST",
                data:{
                    room_id : values
                },
                dataType: "json",
                beforeSend: function(){
                },
                success:function(data){
                    if(data.status == "success"){
                        var list = [];
                        for(var x in data.collection){
                            list.push(data.collection[x].merge_room_id);
                        }
                        console.log(list);

                        var html = "";
                        var nn = 0;
                        $.each(gDatalistRoom, function(index, item){
                            var ft = list.includes(item.radid);
                            var sel = ft == true? "selected" : "";
                            html+= '<option '+sel+'  value="'+item.radid+'" >'+item.name+'</option>'
                        })
                        $('#'+id_merge_room).html(html);
                        // enable_datetimepicker()
                        select_enable()

                    }else{
                        var msg = "Your session is expired, login again !!!";
                        swalShowNotification('alert-danger', msg,'top','center')
                    }
                },
                error: errorAjax
            })
           


        }
        function init(){
            var bs = $('#id_baseurl').val();
            var modules = getModule();
            $.ajax({
                url : bs+"room/get/data",
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
                            var automation = item.is_automation == 0 ? "Unactive" : "Active";
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
                            if(modules['automation']['is_enabled'] ==  1){
                                html += '<td>'+automation+'</td>';
                                html += '<td>'+ra_name+'</td>';
                            }
                            if(modules['price']['is_enabled'] ==  1){
                                // html += '<td>'+numeral(price).format('$ 0,0.00');+'</td>';
                            }
                            // console.log(item)
                            html += '<td>'+enabledRoom[item.is_disabled]+'</td>';

                            html += '<td>';
                            html += '<button \
                                 onclick="editData($(this))" \
                                 data-id="'+item.id+'" \
                                 data-id="'+item.name+'" \
                                 data-ra_id="'+item.ra_id+'" \
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

        function initSingle(){
            var bs = $('#id_baseurl').val();
            var modules = getModule();
            $.ajax({
                url : bs+"room/get/single",
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                },
                success:function(data){
                    gDatalistRoom = [];
                    if(data.status == "success"){
                        gDatalistRoom = data.collection;
                    }else{

                    }
                },
                error: errorAjax
            });
        }

        function editData(t){
            var id = t.data('id');
            var bs = $('#id_baseurl').val();
            var modules = getModule();
            $('#frm_update')[0].reset();
            assetsImageUrl = "";
            $.ajax({
                url : bs+"room/get/edit/"+id,
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    // $('#id_edt_image2_old_div').html("")
                    if(data.status == "success"){
                        var input = data.collection;
                        var pathimage = bs+"assets/file/room/";
                        assetsImageUrl = input['image'];
                        var dataR = "";
                        for(var x in enabledRoom){
                            var sel = x == input['is_disabled'] ? "selected" : "";
                            dataR += '<option '+sel+' value="'+x+'">'+enabledRoom[x]+'</option>'
                        }
                        var imgp = input.image == "" || input.image == null ? defaultImage : input.image;
                        var imap = pathImage+imgp;
                        $('#id_edt_image_old').attr("src",imap);
                        


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
                        $('#id_edt_is_disabled').html(dataR)
                        // console.log(pathimage+input['image'] )
                        // $('#blah2').attr('src',bs+"file/room/"+assetsImageUrl );
                        $('#id_edt_image_old').attr("src",pathimage+input['image']);
                        $('#id_edt_radid').val(input['radid']);
                        $('#id_edt_name').val(input['name']);
                        $('#id_edt_capacity').val(input['capacity']);
                        $('#id_edt_automation_active').val(input['serial']);
                        $('#id_edt_location').val(input['location'])
                        $('#id_edt_description').val(input['description'])
                        $('#id_edt_id').val(id);
                        var timest = input['work_time'];
                        var timesp = timest.split('-');
                        var html_room = "";
                        var html_devices="";
                        $('#id_edt_work_start').val(timesp[0]);
                        $('#id_edt_work_end').val(timesp[1]);
                        $('#id_edt_price').val(input['price']);
                        $('#id_edt_access_id').val(input['access_id']);
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
                        var html_typeroom = "";
                        var html_building = "";



                        if(modules['automation']['is_enabled'] ==  1){
                            var active = ["Off", "On"];
                            $.each(active,function(index, item) {
                                var sl = "";
                               if (input['is_automation'] == index) { sl = "selected" }
                                html_active += "<option "+sl+" value="+index+">"+item+"</option>";
                            });
                            html_aut += "<option value=''>Please choose a automation ...</option>";
                            $.each(gAutomation,function(index, item) {
                                var sl = "";
                                if (input['automation_id'] == item.id) {
                                    sl = "selected";
                                }
                                html_aut += "<option "+sl+" value="+item.id+">"+item.name+"</option>";
                            });
                        }

                        $.each(typeRoom,function(index, item) {
                            var sl = item.value == input['type_room'] ? "selected":"";
                           
                            html_typeroom += "<option "+sl+" value="+item.value+">"+item.text+"</option>";
                        });

                        

                        
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
                        $('#id_edt_automation').html(html_aut);
                        $('#id_edt_workday').html(html_wkd);
                        $('#id_edt_automation_active').html(html_active);
                        $('#id_edt_facility_room').html(html_facility);
                        $('#id_edt_type_room').html(html_typeroom);
                        enable_datetimepicker()
                        select_enable()
                        initTypeRoom('edit','id_edt_type_room','id_edt_merge_room',input.radid);

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

        function removeData(t){
            var id = t.data('id');
            var name = t.data('name');
            var form = new FormData();
            form.append('id', id);
            form.append('name', name);
            Swal.fire({
                title:'Are you sure you want delete it?',
                text: "You will lose the data room "+name+" !",
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
                            url : bs+"room/post/delete",
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
                                    swalShowNotification('alert-success', "Succes deleted room "+name ,'top','center')
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

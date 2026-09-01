<?php  
// date_default_timezone_set("Asia/Jakarta");
// echo date('w');
// print_r($modules);
// die();
?>
<textarea id="id_modules" style="display: none;">{}</textarea>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/theme/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/external/filepond/filepond.min.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/external/dropify/css/dropify.min.css" rel="stylesheet">
    <style>
        .mediacard{
            padding: 10px;
            border-radius: 5px;
            transition: 0.3s;
            border: 2px solid transparent;
            height: 310px !important;
        }
        .mediacard:hover{
            background-color: #f7f7f7;
            border: 2px solid #bf104b !important;
            cursor: pointer;
        }
        /*.media:hover{
            background-color: #f4ecec;
            border: 2px solid #bf104b !important;
        }*/
        .media{
            padding: 10px;
            border-radius: 5px;
            transition: 0.3s;
            border: 2px solid transparent;

        }
        .media, .media-body{
            overflow: visible;
        }
        .label-bordered {
               border: 1px solid #333 !important;
               color : #333 !important;
               border-radius: 4px;
        }
        .selectedRoom{
            border:2px solid blue;
        }

        .media-text-thumbnail {
            color: #999;
            
        }
        .media-text-thumbnail td{
            /*display: block;
            font-size: 12px;
            margin-top: 5px;
            line-height: 15px;*/
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
                <h2>
                    <?= strtoupper($pagename) ?>
                    <div class="pull-right"><button onclick="createData($(this))" type="button" class="btn btn-primary waves-effect ">CREATE NEW</button></div>
                </h2>
            </div>
            <br>
            <div class="row clearfix" id="building_list_area">
                
            </div>
        
        </div>
    </section>
    <div class="modal fade" id="id_mdl_create" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="idmdlcrLabel">Create Building </h4>
                </div>
                <div class="modal-body " id="id_mdl_create_body">
                    <form id="frm_create">
                        <label for="">Image </label>
                        <div class="form-group">
                            <input type="file" name="image" class="dropify dropifycrt" data-height="150" data-max-file-size="2M" accept="image/*" />
                        </div>
                        <label for="">Name <b style="color:red;">*</b></label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" autocomplete="off" name="name" id="id_crt_name" required="" class="form-control" placeholder="Name">
                            </div>
                        </div>
                        <label for="">Description <b style="color:red;">*</b></label>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea required rows="2" name="description" class="form-control no-resize" id="id_crt_description" placeholder="Please type the building description..."></textarea>
                            </div>
                        </div>
                        <label for="">Address</label>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" name="detail_address" class="form-control no-resize" id="id_crt_detail_address" placeholder="Please type the building address..."></textarea>
                            </div>
                        </div>
                        <label for="">Link Map </label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" autocomplete="off" name="google_map" id="id_crt_google_map" class="form-control" placeholder="Google Map">
                            </div>
                        </div>
                        <label for="">Time Zone <b style="color:red;">*</b></label>
                        <div class="form-group">
                            <select required="" data-live-search="true" name="timezone" id="id_crt_timezone" class="form-control show-tick"></select>
                        </div>
                        <br>
                        <button type="submit" style="display: none;" id="id_btn_crt_submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
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
                    <h4 class="modal-title" id="idmdlcrLabel">Update Building</h4>
                </div>
                <div class="modal-body " id="id_mdl_update">
                    <form id="frm_update">
                        <div id="id_edt_image_old_div" class="row">
                            <div class="col-xs-6">
                                <label for="">Old Image</label>
                                <div class="form-group">
                                    <a href="javascript:void(0);" class="thumbnail">
                                        <img id="id_edt_image_old" src="" style="height:150px" class="img-responsive">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <label for="">New Image </label>
                                <div class="form-group">
                                    <input type="file" name="image" id="id_edt_image" class="dropify" data-height="150" data-max-file-size="2M" accept="image/*" />
                                </div>
                            </div>
                        </div>
                        <label for="">Name <b style="color:red;">*</b></label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" autocomplete="off" name="name" id="id_edt_name" required="" class="form-control" placeholder="Name">
                                <input type="hidden" autocomplete="off" id="id_edt_id" readonly="" required="">
                            </div>
                        </div>
                        <label for="">Description <b style="color:red;">*</b></label>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="2" required name="description" class="form-control no-resize" id="id_edt_description" placeholder="Please type the building description..."></textarea>
                            </div>
                        </div>
                        <label for="">Address</label>
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" name="detail_address" class="form-control no-resize" id="id_edt_detail_address" placeholder="Please type the building address..."></textarea>
                            </div>
                        </div>
                        <label for="">Link Map</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" autocomplete="off" name="google_map" id="id_edt_google_map" class="form-control" placeholder="Google Map">
                            </div>
                        </div>
                        <label for="">Time Zone <b style="color:red;">*</b></label>
                        <div class="form-group">
                            <select required="" data-live-search="true" name="timezone" id="id_edt_timezone" class="form-control show-tick"></select>
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
                            <button onclick="clickSubmit('id_btn_edt_submit')" type="button" class="btn btn-primary waves-effect ">SAVE</button>
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
    <script src="<?= base_url()?>assets/external/numeral/locale.id.js"></script>
    <script src="<?= base_url()?>assets/external/dropify/js/dropify.min.js"></script>
    <textarea id="id_listtimezone" style="display:none;"><?= json_encode($listtimezone)?></textarea>
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <input type="hidden" id="id_default_timezone" value="<?= date_default_timezone_get()?>">
    <script>
    $('.block-header').show();
    var bs = $('#id_baseurl').val();
    var listtimezone_json = $('#id_listtimezone').val();
    var default_timezone = $('#id_default_timezone').val();
    var pathImage = bs + "assets/file/building/";
    var defaultImage = "default.jpeg";
    var uploadimageCrt = false;
    var gAutomation = [];
    var gFacility = [];
    var assetsImageUrl = "";
    var listtimezone = JSON.parse(listtimezone_json);
    var enabledRoom = ["Enabled", "Disabled"];
    $(function() {
        init();
    })
    var data_content = `<?= $content?>`;

    function clickSubmit(id) {
        $('#' + id).click();
    }
    var gDrop = $('.dropify').dropify();
    var gDrop2 = $('.dropifycrt').dropify();

    // $('.dropify').dropify();
    // initDrop();
    // function initDrop(){
    //     gDrop =  $('.dropify').dropify();
    //     var drDestroy =  gDrop;
    //     drDestroy = drDestroy.data('dropify');
    //     if(drDestroy.isDropified()){
    //         drDestroy.destroy();
    //         // drDestroy.init();
    //     }else{}
    // }
    function initCrtDrop() {
        gDrop = $('.dropifycrt').dropify();
        var drDestroy = gDrop;
        drDestroy = drDestroy.data('dropify');
        if (drDestroy.isDropified()) {
            console.log(1);
            drDestroy.destroy();
            // drDestroy.init();
        } else {}
    }

    function softInitDrop() {
        var drDestroy = $('.dropify').dropify();
    }

    function getModule() {
        var modules = $('#id_modules').val();
        return JSON.parse(modules)
    }

    function initTable(selector) {
        selector.DataTable();
    }

    function clearTable(selector) {
        selector.DataTable().destroy();
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

    function createData() {
        // initCrtDrop();
        $('#id_mdl_create').modal('show');
        var html_timezone = "";
        for (var i in listtimezone) {
            var sel = '';
            if (i == default_timezone) {
                sel = "selected";
            }
            html_timezone += "<option  " + sel + " value='" + i + "'>" + listtimezone[i] + "</option>";
        }

        $('#id_crt_timezone').html(html_timezone);
        enable_datetimepicker()
        select_enable()
    }
    $('#frm_create').submit(function(e) {
        e.preventDefault();
        // gFacility = data.collection
        var form = new FormData(this);

        var bs = $('#id_baseurl').val();
        $.ajax({
            url: bs + "building/post/create",
            type: "POST",
            dataType: "json",
            data: form,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $('#id_loader').html('<div class="linePreloader"></div>');
            },
            success: function(data) {
                if (data.status == "success") {
                    $('#frm_create')[0].reset();
                    init();
                    $('#id_mdl_create').modal('hide');
                    swalShowNotification('alert-success', data.msg, 'top', 'center')
                    initDrop()
                } else {
                    swalShowNotification('alert-danger', data.msg, 'top', 'center')
                }
                $('#id_loader').html('');
            },
            error: errorAjax
        })
    })
    $('#frm_update').submit(function(e) {
        e.preventDefault();
        // var form = $('#frm_update').serialize();
        var id = $('#id_edt_id').val()
        var bs = $('#id_baseurl').val();
        var form = new FormData(this);

        // console.log()
        $.ajax({
            url: bs + "building/post/update/" + id,
            type: "POST",
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            // data : form,
            data: form,
            beforeSend: function() {
                $('#id_loader').html('<div class="linePreloader"></div>');
            },
            success: function(data) {
                if (data.status == "success") {
                    $('#frm_update')[0].reset();
                    $('#id_mdl_update').modal('hide');
                    init();
                    initDrop();
                    swalShowNotification('alert-success', data.msg, 'top', 'center')
                } else {
                    swalShowNotification('alert-danger', data.msg, 'top', 'center')
                }
                $('#id_loader').html('');
            },
            error: errorAjax
        })
    })



    function init() {
        var bs = $('#id_baseurl').val();
        var modules = getModule();
        $.ajax({
            url: bs + "building/get/data",
            type: "GET",
            dataType: "json",
            beforeSend: function() {
                $('#id_loader').html('<div class="linePreloader"></div>');
            },
            success: function(data) {
                if (data.status == "success") {
                    clearTable($('#tbldata'));
                    var html = "";
                    var htmlcontent = "";
                    var nn = 0;
                    $.each(data.collection, function(index, item) {
                        nn++;
                        var clone_dc = data_content;
                        // console.log(bId);

                        var bIdEnc = item.encrypt;
                        var ttzone = listtimezone[item.timezone] != null ? listtimezone[item.timezone] : "";
                        var detail_address = item.detail_address != null ? item.detail_address : "";
                        var google_map = item.google_map != null ? item.google_map : "";
                        clone_dc = clone_dc.replace("%title_building%", item.name);
                        clone_dc = clone_dc.replace("%title_building%", item.name);
                        clone_dc = clone_dc.replace("%timezone%", ttzone);
                        clone_dc = clone_dc.replace("%google_location%", google_map);
                        clone_dc = clone_dc.replace("%address_location%", detail_address);
                        var btnedit = `<a  onclick="editData($(this))"
                                 data-id="${item.id}"  data-id="${item.name}"  type="button" >Edit/Detail</a>`
                         var btndelete= `<a  onclick="removeData($(this))"
                                 data-id="${item.id}"  data-id="${item.name}"  type="button" >Delete</a>`
                        clone_dc = clone_dc.replace("%Edit/Detail%", btnedit);
                        clone_dc = clone_dc.replace("%Delete%", btndelete);
                        clone_dc = clone_dc.replace("%buildingId%", bIdEnc);
                        clone_dc = clone_dc.replace("%buildingId%", bIdEnc);
                        clone_dc = clone_dc.replace("%buildingId%", bIdEnc);
                        clone_dc = clone_dc.replace("%buildingId%", bIdEnc);
                        clone_dc = clone_dc.replace("%buildingId%", bIdEnc);
                        var urlimage = `${bs}assets/file/building/${item.image}`
                        clone_dc = clone_dc.replace("%img_building%", urlimage);
                        htmlcontent += clone_dc;
                        
                    })
                    $('#building_list_area').html(htmlcontent);
                    // $('#tbldata tbody').html(html);
                    // initTable($('#tbldata'));
                } else {
                    var msg = "Your session is expired, login again !!!";
                    swalShowNotification('alert-danger', msg, 'top', 'center')
                }
                $('#id_loader').html('');
            },
            error: errorAjax
        })
    }

    function onClickBuildingCard(t){
        // var id = t.data('id');
        // var bs = $('#id_baseurl').val();
        // var u = bs + "building/floor?building="+id;
        // // console.log(u);
        // window.location.href = u;

    }   

    function editData(t) {
        var id = t.data('id');
        var bs = $('#id_baseurl').val();
        $('#frm_update')[0].reset();
        assetsImageUrl = "";
        $.ajax({
            url: bs + "building/get/edit/" + id,
            type: "GET",
            dataType: "json",
            beforeSend: function() {
                $('#id_loader').html('<div class="linePreloader"></div>');
            },
            success: function(data) {
                $('#id_edt_image2_old_div').html("")
                if (data.status == "success") {
                    var input = data.collection;
                    var pathimage = bs + "assets/file/room/";
                    assetsImageUrl = input['image'];
                    var imgp = input.image == "" || input.image == null ? defaultImage : input.image;
                    var imap = pathImage + imgp;
                    $('#id_edt_image_old').attr("src", imap);
                    $('#id_edt_name').val(input['name']);
                    $('#id_edt_detail_address').val(input['detail_address'])
                    $('#id_edt_description').val(input['description'])
                    $('#id_edt_google_map').val(input['google_map'])
                    $('#id_edt_id').val(id);
                    var tz = input.timezone;
                    var html_timezone = "";
                    for (var i in listtimezone) {
                        var sel = '';
                        if (tz == i) {
                            sel = "selected";
                        }
                        html_timezone += "<option  " + sel + " value='" + i + "'>" + listtimezone[i] + "</option>";
                    }
                    $('#id_edt_timezone').html(html_timezone);
                    enable_datetimepicker()
                    select_enable()
                    $('#id_mdl_update').modal('show');
                } else {
                    var msg = "Your session is expired, login again !!!";
                    swalShowNotification('alert-danger', msg, 'top', 'center')
                }
                $('#id_loader').html('');

            },
            error: errorAjax
        })
    }

    function removeData(t) {
        var id = t.data('id');
        var name = t.data('name');
        var form = new FormData();
        form.append('id', id);
        form.append('name', name);
        Swal.fire({
            title: 'Are you sure you want delete it?',
            text: "You will lose the data building " + name + " !",
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
                    url: bs + "building/post/delete",
                    type: "POST",
                    data: form,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function() {
                        $('#id_loader').html('<div class="linePreloader"></div>');
                    },
                    success: function(data) {
                        $('#id_loader').html('');
                        if (data.status == "success") {
                            swalShowNotification('alert-success', "Succes deleted building", 'top', 'center')
                            init();
                        } else {
                            swalShowNotification('alert-danger', "Data not found", 'bottom', 'left')
                        }
                    },
                    error: errorAjax,
                })
            } else {

            }
        })

    }

    function swalShowNotification(icon, title, loc = "", loc2 = "") {
        var ic = "";
        if (icon == "alert-success") {
            ic = "success";
        } else if (icon == "alert-danger") {
            ic = "danger";
        } else if (icon == "alert-warning") {
            ic = "warning";
        } else if (icon == "alert-info") {
            ic = "info";
        }
        Swal.fire(
            title,
            '',
            ic
        )
    }

    function errorAjax(xhr, ajaxOptions, thrownError) {
        $('#id_loader').html('');
        if (ajaxOptions == "parsererror") {
            var msg = "Status Code 500, Error Server bad parsing";
            showNotification('alert-danger', msg, 'bottom', 'left')
        } else {
            var msg = "Status Code " + xhr.status + " Please check your connection !!!";
            showNotification('alert-danger', msg, 'bottom', 'left')
        }
    }
    </script>
</body>

</html>
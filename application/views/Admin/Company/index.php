
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
     <!-- Dropzone Css -->
    <link href="<?= base_url()?>assets/theme/plugins/dropzone/dropzone.css" rel="stylesheet">
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
                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-12">
                                    <h2>Login Page Background</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 align-right">
                                   
                                </div>
                            </div>
                        </div>
                        <div class="body ">
                            <div class="row clearfix align-center">
                                <?php  
                                    if($company['picture'] == "" ){
                                ?>
                                <img src="http://placehold.it/500x300" id="id_pic_preview" style="height: 100px;width: 160px;">
                                <?php
                                }else{
                                ?>
                                <img src="" id="id_pic_preview" style="height: 100px;">
                                <?php  

                                }
                                ?>
                            </div>

                            <p align="center"><br>Maximum size <b>8MB</b> </p>
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-12 ">
                                <form id="frm_create_upload1">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="hidden" name="type" value="bg">
                                            <input type="file" accept="image/png, image/jpeg, image/jpg" 
                                        autocomplete="off" name="file" id="id_pic_upload" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="row clearfix align-right">
                                        <button type="submit" class="btn btn-primary m-t-15 waves-effect ">Upload</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-12">
                                    <h2>Icon</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 align-right">
                                   
                                </div>
                            </div>
                        </div>
                        <div class="body ">
                            <div class="row clearfix align-center">
                                <?php  
                                    if($company['icon'] == "" ){
                                ?>
                                <img src="http://placehold.it/500x300" id="id_icon_preview" style="height: 100px;">
                                <?php
                                }else{
                                ?>
                                <img src="" id="id_icon_preview" style="height: 100px;">
                                <?php  

                                }
                                ?>
                            </div>

                            <p align="center"><br>Maximum size <b>8MB</b> </p>
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-12 ">
                                <form id="frm_create_upload2">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="hidden" name="type" value="icon">
                                            <input type="file" accept="image/png, image/jpeg, image/jpg" autocomplete="off" name="file" id="id_icon_upload" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="row clearfix align-right">
                                        <button type="submit" class="btn btn-primary m-t-15 waves-effect ">Upload</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-12">
                                    <h2>Logo</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 align-right">
                                   
                                </div>
                            </div>
                        </div>
                        <div class="body ">
                            <div class="row clearfix align-center">
                                <?php  
                                    if($company['logo'] == "" ){
                                ?>
                                <img src="http://placehold.it/500x300" id="id_logo_preview" style="height: 100px;">
                                <?php
                                }else{
                                ?>
                                <img src="" id="id_logo_preview" style="height: 100px;">
                                <?php  

                                }
                                ?>
                            </div>

                            <p align="center"><br>Maximum size <b>8MB</b> </p>
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-12 ">
                                <form id="frm_create_upload3">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="hidden" name="type" value="logo">
                                            <input type="file" accept="image/png, image/jpeg, image/jpg" autocomplete="off" name="file" id="id_logo_upload" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="row clearfix align-right">
                                        <button type="submit" class="btn btn-primary m-t-15 waves-effect ">Upload</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                </div>
                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-12">
                                    <h2>Menu Bar</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 align-right">
                                   
                                </div>
                            </div>
                        </div>
                        <div class="body ">
                            <div class="row clearfix align-center">
                                <?php  
                                    if($company['menu_bar'] == "" ){
                                ?>
                                <img src="http://placehold.it/500x300" id="id_menu_preview" style="height: 100px;">
                                <?php
                                }else{
                                ?>
                                <img src="" id="id_menu_preview" style="height: 100px;">
                                <?php  

                                }
                                ?>
                            </div>

                            <p align="center"><br>Maximum size <b>8MB</b> </p>
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-12 ">
                                <form id="frm_create_upload4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="hidden" name="type" value="menu">
                                            <input type="file" accept="image/png, image/jpeg, image/jpg" autocomplete="off" name="file" id="id_menu_upload" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="row clearfix align-right">
                                        <button type="submit" class="btn btn-primary m-t-15 waves-effect ">Upload</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="header">

                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>Company Profile</h2>
                                </div>
                                <div class="col-xs-12 col-sm-6 align-right">
                                   </div> 
                            </div>
                        </div>
                        <div class="body table-responsive responsive">
                          <form id="frm_create">
                                        <label for="">Name</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" name="name" required=""  class="form-control" placeholder="Name" value="<?= @$company['name']?>" >
                                            </div>
                                        </div>
                                        <label for="">Address</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <textarea  name="address" rows="3" class="form-control" ><?= @$company['address']?></textarea>
                                            </div>
                                        </div>
                                        <label for="">City</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" name="city" required=""  class="form-control" placeholder="City" value="<?= @$company['city']?>" >
                                            </div>
                                        </div>
                                        <label for="">State/ Province</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" name="state" class="form-control" placeholder="State/Province" value="<?= @$company['state']?>" >
                                            </div>
                                        </div>
                                        <label for="">Phone</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" name="phone" required=""  class="form-control" placeholder="Phone" value="<?= @$company['phone']?>" >
                                            </div>
                                        </div>
                                        <label for="">Email Address</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" name="email"  class="form-control" placeholder="Email Address" value="<?= @$company['email']?>" >
                                            </div>
                                        </div>
                                        <label for="">URL Address/ Website</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" autocomplete="off" name="url_address"  class="form-control" placeholder="Website Address" value="<?= @$company['url_address']?>" >
                                            </div>
                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>
               
            </div>

        </div>
    </section>
   
    <?php $this->load->view("_partials/js_dashboard.php");?>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
    <!-- Moment Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/jquery-editable/src/table-edits.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/dropzone/dropzone.js"></script>
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <textarea style="display: none;" id="id_company"><?= $companyParse ?></textarea>
    <!-- <input type="hidden"  value=""> -->
    <script>
        var gBeforImage = "";
        $(function(){
            initCompany()
            // $('.datepicker').datepicker();
        }) 


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

        function initCompany(){
            var bs = $('#id_baseurl').val();
            var company = $('#id_company').val();
            try{
                var parseC = JSON.parse(company);
                console.log(parseC);
                if(parseC.picture == ""){
                    gBeforImage = "http://placehold.it/500x300";
                    $('#id_pic_preview').prop("src", gBeforImage);
                }else{
                    gBeforImage =bs +"assets/file/company/" +parseC.picture;
                    $('#id_pic_preview').prop("src", gBeforImage);
                    $('#id_logo_preview').prop("src", bs +"assets/file/company/" + parseC.logo);
                    $('#id_icon_preview').prop("src", bs +"assets/file/company/" + parseC.icon);
                    $('#id_menu_preview').prop("src", bs +"assets/file/company/" + parseC.menu_bar);
                }
            }catch(error){
                gBeforImage = "http://placehold.it/500x300";
                $('#id_pic_preview').prop("src", gBeforImage);

            }
            
            
        }
        
        $('#frm_create').submit(function(e){
            e.preventDefault();
            var form =  $('#frm_create').serialize();
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"company/post/update",
                type : "POST",
                dataType: "json",
                data : form,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                        if(data.status == "success"){
                          showNotification('alert-success', data.msg,'top','center')
                        }else{
                          showNotification('alert-danger', data.msg,'top','center')
                        }
                        $('#id_loader').html('');
                    },
                    error: errorAjax
            })
        })  

        $('#frm_create_upload1').submit(function(e){
            e.preventDefault();
            var form = $('#frm_create_upload1')[0];
            var formData = new FormData(form);
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"company/post/media",
                type : "POST",
                dataType: "json",
                data : formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        var c = data.collection;
                        gBeforImage = bs +"assets/file/company/" +c.picture;
                        showNotification('alert-success', data.msg,'top','center')
                    }else{
                        showNotification('alert-danger', data.msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        })  
        $('#frm_create_upload2').submit(function(e){
            e.preventDefault();
            var form = $('#frm_create_upload2')[0];
            var formData = new FormData(form);
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"company/post/media",
                type : "POST",
                dataType: "json",
                data : formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        var c = data.collection;
                        gBeforImage = bs +"assets/file/company/" +c.picture;
                        showNotification('alert-success', data.msg,'top','center')
                    }else{
                        showNotification('alert-danger', data.msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        })  
        $('#frm_create_upload3').submit(function(e){
            e.preventDefault();
            var form = $('#frm_create_upload3')[0];
            var formData = new FormData(form);
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"company/post/media",
                type : "POST",
                dataType: "json",
                data : formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        var c = data.collection;
                        gBeforImage = bs +"assets/file/company/" +c.picture;
                        showNotification('alert-success', data.msg,'top','center')
                    }else{
                        showNotification('alert-danger', data.msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        })  
        $('#frm_create_upload4').submit(function(e){
            e.preventDefault();
            var form = $('#frm_create_upload4')[0];
            var formData = new FormData(form);
            var bs = $('#id_baseurl').val();
            $.ajax({
                url : bs+"company/post/media",
                type : "POST",
                dataType: "json",
                data : formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false,
                beforeSend: function(){
                    $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    if(data.status == "success"){
                        var c = data.collection;
                        gBeforImage = bs +"assets/file/company/" +c.picture;
                        showNotification('alert-success', data.msg,'top','center')
                    }else{
                        showNotification('alert-danger', data.msg,'top','center')
                    }
                    $('#id_loader').html('');
                },
                error: errorAjax
            })
        })  
        $("#id_pic_upload").change(function() {
          readURL(this, $('#id_pic_preview'));
        });
        $("#id_icon_upload").change(function() {
          readURL(this, $('#id_icon_preview'));
        });
        $("#id_logo_upload").change(function() {
          readURL(this, $('#id_logo_preview'));
        });
        $("#id_menu_upload").change(function() {
          readURL(this, $('#id_menu_preview'));
        });
        function readURL(input, selected) {
          if (input.files && input.files[0] && input.files.length != 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
              selected.attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]); // convert to base64 string
          }else{
            var imghtml = gBeforImage;
            selected.prop("src", imghtml);
          }
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

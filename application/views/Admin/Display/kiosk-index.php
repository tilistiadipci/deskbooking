
<?php  
?>
<textarea id="id_modules" style="display: none;"> 
    <!-- <?= json_encode($modules)?> -->
</textarea> 
<!DOCTYPE html>
<html lang="en">
<head>
   <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Colorpicker Css -->
    <link href="<?= base_url()?>assets/theme/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />

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
                        <div class="header">

                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>Display Kiosk List</h2>
                                </div>
                                <div class="col-xs-12 col-sm-6 align-right">
                    
                                </div>
                            </div>
                        </div>
                        <div class="body table-responsive responsive">
                            <table class="table table-hover" id="tbldata">
                                <thead>
                                        <th>#</th>
                                        <th>Serial</th>
                                        <th>Kiosk Name</th>
                                        <th>Device UUID</th>
                                        <th>Last Logged</th>
                                        <th>Action</th>
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
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Create Kiosk Display </h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                            <form id="frm_create">
                                <label for="">Kiosk Type </label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select required="" data-live-search="true" name="display_type" id="id_crt_display_type" class="form-control show-tick"></select>
                                    </div>
                                </div>

                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                         <input required="" type="text" class="form-control"  name="display_name" id="id_crt_display_name" >
                                    </div>
                                   
                                </div>
                                <br>
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
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Update Kiosk Display </h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                            <form id="frm_update">
                                <!-- <label for="">Kiosk Type </label> -->
                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                         <input  type="hidden" class="form-control"  name="id" id="id_edt_display_id" >
                                         <input required="" type="text" class="form-control"  name="display_name" id="id_edt_display_name" >
                                    </div>
                                   
                                </div>
                                <br>
                                <button type="submit" id="id_btn_edt_submit" class="btn btn-primary m-t-15 waves-effect" style="display: none;" >Save</button>
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
        
        <div class="modal fade" id="id_mdl_sigange" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="id_mdl_sigangeLabel">Sigange Display </h4>
                        </div>
                        <div class="modal-body " id="id_mdl_sigange_body">

                            <form id="frm_signage">
                                <label for="">Old Signage</label>
                                <div class="row clearfix">
                                    <div class="col-xs-12">
                                        <video width="320" id="id_si_old" height="240" controls>
                                          Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                                    
                                <br>
                                <label for="">Insert Signage Video</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input required="" type="file" accept="video/mp4" name="signage" id="id_si_signage" >
                                        <p class="small">Insert File Ext MP4. Max upload < 20MB </p>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" id="id_btn_si_submit" class="btn btn-primary m-t-15 waves-effect" style="display: none;" >Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-xs-6 align-left">
                                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                                </div>
                                <div class="col-xs-6">
                                    <button onclick="clickSubmit('id_btn_si_submit')" type="button" class="btn btn-primary waves-effect " >SAVE</button>
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
    <!-- Bootstrap Colorpicker Js -->
    <script src="<?= base_url()?>assets/theme/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
    <!-- Input Mask Plugin Js -->
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <script src="<?= base_url()?>assets/process/kioskdisplay/index.js"></script>
    
    </body>
</html>

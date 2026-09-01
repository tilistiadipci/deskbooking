<?php 
// echo ROOT_URL;

// die(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?= HEAD_NAME?> - Authentication</title>
	<?php $this->load->view("_partials/head_css.php");?>
	<style>
				
				
	.linePreloader{
	    width:100%;
	    height:2px;
	    background:linear-gradient(to right,green,green);
	    background-color:#ccc;
	    position:absolute;
	    top:0;
	    bottom:0;
	    left:0;
	    right:0;
	    /*margin:auto;*/
	    border-radius:4px;
	    background-size:20%;
	    background-repeat:repeat-y;
	    background-position:-25% 0;
	    animation:scroll 1.2s ease-in-out infinite;
	  }
	  
	 @keyframes scroll{
	    50%{background-size:80%}
	    100%{background-position:125% 0;}
	 }
	</style>
</head>
<body>
	<?php if ($background != null || $background != ""): ?>
		
	<body class="login-page" style="background:url('<?= $background?>') center no-repeat;background-size: cover;background-attachment: fixed;">
	<?php else: ?>
	<body class="login-page" >
	<?php endif ?>
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);"><font class="base_color1">Desk</font><b class="base_color2">Booking</b></a>
            <small><?= APP_NAME?> v<?=APP_VERSION?></small>
        </div>
        <div class="card">
            <div class="body">
            	<div id="id_loader"></div>
            	
                <form id="sign_in" method="POST" >
                	<?php if ($this->session->flashdata('error_login')): ?>
                		<div class="alert alert-danger">
                			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <strong>Important!</strong> Captcha not match, try again.
                        </div>
                	<?php endif ?>
                	
                    <div class="msg">Sign in to start your session</div>
                    <!-- <div class=""></div> -->
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" placeholder="Username " required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">security</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="capt" placeholder="Input Captcha" required>
                        </div>
                    </div>
                    <div class="row">
                    	<span class="input-group-addon">
                    		<img src="<?= $captcha; ?>" />
                    	</span>
                    	
                    </div>	
                    <div class="row">
                        <div class="col-xs-12 p-t-5">
                        </div>
                        <div class="col-xs-4">
                        	<button type="submit" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float">
                                <i class="material-icons">keyboard_arrow_right</i>
                            </button>
                        </div>

                        
                    </div>


                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-12">
                        	<!-- <a style="text-decoration: underline;" href="https://drive.google.com/drive/folders/1sZeLEzlOyjK4bkNQ0eKUYz2kCPCu9TJA?usp=sharing" target="__blank">Click Here to Read Documentation & Manual Guide</a> -->
                        </div>
                        <!-- <div class="col-xs-6 align-right"> -->
                        	<!--  -->
                        <!-- </div> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
	<?php $this->load->view("_partials/js.php");?>
	<script>
		$(function () {
		    $('#sign_in').validate({
		        highlight: function (input) {
		            // console.log(input);
		            $(input).parents('.form-line').addClass('error');
		        },
		        unhighlight: function (input) {
		            $(input).parents('.form-line').removeClass('error');
		        },
		        errorPlacement: function (error, element) {
		            $(element).parents('.input-group').append(error);
		        },
		        submitHandler: function(form) {
				  
				}

		    });
		    // console.log(d);	
		});
		$('#sign_in').submit(function(e){
			e.preventDefault();
			if($(this).valid() == false){
				return false;
			}
			$.ajax({
				url : "authentication/login",
				type : "POST",
				dataType : "json",
				data: $(this).serialize(),	
				beforeSend: function(XMLHttpRequest){
					$('#id_loader').html('<div class="linePreloader"></div>');
				},
				success: function(data){
					// console.log(data)
					if(data.status == "fail"){
						$('#id_loader').html('');
						
						if(data.msg == "Failed to login, captcha not match"){
							window.location.reload();
						}else{
							showNotification('alert-danger', data.msg,'bottom','left')
						}
					}else if(data.status == "success"){
						showNotification('alert-success', "Success, please wait for redirect.",'bottom','left')
						setTimeout(function(){
							$('#id_loader').html('');
							window.location.href = "./";
						}, 1000);
					}
					
				},
				error: function (xhr, ajaxOptions, thrownError) {
					$('#id_loader').html('');
			        // console.log("Error :> ", xhr.status);
			        if(ajaxOptions == "parsererror"){
			        	var msg = "Status Code 500, Error Server bad parsing";
			        	showNotification('alert-danger', msg,'bottom','left')
			        }else{
			        	var msg ="Status Code "+ xhr.status + " Please check your connection !!!";
			        	showNotification('alert-danger', msg,'bottom','left')
			        }
			        // console.log( ajaxOptions);
			    },
				
			})
			// handleFormSubmit($('#sign_in'));
		});
		// function handleFormSubmit(form, input) {
	 //        // validate the form against the constraints
	 //        var errors = validate(form, constraints);
	 //        // then we update the form to reflect the results
	        
	 //    }
	</script>
</body>
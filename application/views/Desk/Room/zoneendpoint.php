<!DOCTYPE html>
<html>
<head>
	<title>Endpoint Route</title>
	<!-- Favicon-->
    <link rel="icon" href="assets/theme/favicon.ico" type="image/x-icon">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url("assets/theme/plugins/bootstrap/css/bootstrap.css") ?>" rel="stylesheet">
    <link href="<?php echo base_url("assets/theme/plugins/iconfont/material-icons.css");?>" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="<?php echo base_url("assets/theme/plugins/node-waves/waves.css") ?>" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="<?php echo base_url("assets/theme/plugins/animate-css/animate.css") ?>" rel="stylesheet" />
   
    <style type="text/css">
        
        #buttonArea{
			/*display: none;*/
		  border: 2px solid red;
		  background: rgba(255,255,255,.6);
		  position: fixed;
		  width: 200px;
		  top: 300px;
		  left: 30px;
		  z-index: 1200;
		  padding: 20px;
		}
		.booth{
          	/*position: absolute;*/
          	z-index: 1200;
          	cursor: pointer;
        }
        .time{
          	/*position: absolute;*/
          	color: #FFF;
          	font-size: 28px;

        }
        .h1{
        	color: #FFF;
        }
    </style>
</head>
<body style="margin: 0px !important;"  >
	<div id="buttonArea">
		<a href="javascript:void()" onclick="onBootAdd()">Show Boot New Path </a> | <a  onclick="onBootRemove()" href="javascript:void()" >Remove Boot New Path </a>
		<br>
		<input type="text" id="id_booth_input">
		<hr>
		<a href="javascript:void(0)" onclick="closeFunc()">Close Window</a>
		<br>
		
	</div>
	<div id="id_template_area" style=" background-color:#00363a; padding: 5px;">
		
		<div style="height: 0px"></div>
		<div style="height: 740px; background-color:white;width: 100%;">
			<div class="booth" id="id_booth"></div>
			<div >
				<img id="id_image_map" src="<?= base_url()?>assets/file/room/1942081.png" style="height: 540px;width: 960px; ">
			</div>
		</div>
		<!-- <div style="height: 900px; background-color:white; ">
		</div> -->
	</div>
	 <!-- Jquery Core Js -->
    <script src="<?php echo base_url("assets/theme/plugins/jquery/jquery.min.js");?>"></script> 
    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url("assets/theme/plugins/bootstrap/js/bootstrap.js");?>"></script>
    <script src="<?php echo base_url("assets/external/jquery-ui/jquery.ui.js");?>"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <textarea style="display: none;" id="id_source"><?= $source?></textarea>
	<script type="text/javascript">
		var bs = "<?= base_url()?>";
		var source = JSON.parse($('#id_source').val());
		var pointer = source.pointer;
		var selector = source.selector;
		var map = bs + "assets/file/room/"+source.map;

		var gTemplate = {
			height : 1920,
			width : 1080,
			image : "1610353074067_image.jp"
		}
		function clockTime(){
			var time = moment().format('HH:mm A');
			$('#id_time_loop').html(time)
		}
		initMap();
		clockTime();
		dragDIv();
		setInterval(function(){
			clockTime()
		}, 500)

		function getParameterByName(name, url = window.location.href) {
		    name = name.replace(/[\[\]]/g, '\\$&');
		    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
		        results = regex.exec(url);
		    if (!results) return null;
		    if (!results[2]) return '';
		    return decodeURIComponent(results[2].replace(/\+/g, ' '));
		}
		window.onbeforeunload = function(){
			// copyFunc()
			closeFunc();
		};
		function closeFunc() {
			// var selector = getParameterByName('selector');
			// console.log(selector)
			if (window.opener != null && !window.opener.closed) {
		            var txtName = window.opener.document.getElementById(selector);
		            txtName.value = $('#id_booth_input').val();
		    }
		    window.close();
		}
		function onBootAdd(){
			$('#id_booth').html("");
			$('#id_booth').html('<div id="pin_booth_marker" style="position:absolute;height: 45x;width: 45px;); ">\
                      <img src="'+bs+'assets/file/desk/pointer.png" style="width:100%;height:100%;">\
                      </div>');
			dragBooth();
		}
		function onBootAddPoint(point){
			var sp_point = point.split(",");
			var x = sp_point[0];
			var y = sp_point[1];
			$('#id_booth').html("");
			$('#id_booth').html('<div id="pin_booth_marker" style="position:absolute;height: 45px;width: 45px;left:'+x+'px;top:'+y+'px; ">\
                      <img src="'+bs+'assets/file/desk/pointer.png" style="width:100%;height:100%;">\
                      </div>');

			dragBooth();
		}
		function dragDIv(){
            $('#buttonArea').draggable({
                drag: function(){
                    // var offset = $(this).offset();
                    // get_coordinate_x = $(this).css("left").replace("px","");
                    // get_coordinane_y = $(this).css("top").replace("px","");
                    // $('#id_booth_input').val(offset.left+","+offset.top);
                },
                stop: function() {
			        
			    },
            });
        }
		function dragBooth(){
            $('#pin_booth_marker').draggable({
                drag: function(){
                    var offset = $(this).offset();
                    get_coordinate_x = $(this).css("left").replace("px","");
                    get_coordinane_y = $(this).css("top").replace("px","");
                    $('#id_booth_input').val(offset.left+","+offset.top);
                },
                stop: function() {
			        
			    },
            });
        }
        function onBootRemove(){
			$('#id_booth').html("");
			$('#id_booth_input').val("");
		}

		function initMap(){
			var x = getParameterByName('data')
			$('#id_template_area').css({
                'height': 650+"px",
                'width': 1100+"px",
            });
            // console.log(pointer)
            if(pointer != "" || pointer != null){
                onBootAddPoint(pointer);
                $('#id_booth_input').val(pointer);
            }
            $('#id_image_map').attr('src', map)
			
        }
	</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
     <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
     <style type="text/css">
      html, body {
        height: 100%;
        margin: 0px;
      }

      .sidenav {
           height: 80%;
           width: 280px;
           position: fixed;
           z-index: 100;
           background-color: white;
           overflow-x: hidden;
           padding: 20px;
           border: 2px solid #333;
      }

      .flex-container {
          height: 100%;
          padding: 0;
          margin: 0;
          display: flex;
          align-items: center;
          justify-content: center;
      }
      .btn-active, .btn-active:hover, .btn-active:active, .btn-active:focus {
          background-color: #fb483a !important;
      }

        .imgFilter{height: 180px;width: 120px;}

     </style>
</head>
<body >
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
    
    <div class="flex-container" style="height: 100%;width: 100%;background-color: #eee;position: absolute; ">
      <div style=";background-color: #eee;" id="id_canvas" >
      </div>
    </div>
    <div class="sidenav" id="sidenav" >
            <label for="">Building</label>
            <div class="form-group">
               <select onchange="onChangeBuild()" title="Choose one of the building..." data-live-search="true" name="building_id" id="id_building_id" class="form-control selectpickerr show-tick"></select>
            </div>
            <label for="">Floor</label>
            <div class="form-group">
               <select title="Choose one of the Floor..." data-live-search="true" name="floor_id" id="id_floor_id" class="form-control selectpickerr show-tick"></select>
            </div>
            <div class="form-group">
               <button type="button"  class="btn btn-primary btn-sm  waves-effect" onclick="onClickBtnFloor()">Set Floor</button>
               <button id="btn_start_id" type="button"  class="btn btn-default btn-sm  waves-effect" onclick="onClickBtnStartScan()">Start Scan</button>
            </div>
            <div class="row clearfix">
                  <div class="col-xs-12 align-left">
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" onclick="zoomout()" class="btn btn-default waves-effect"><i class="material-icons">remove</i></button>
                        <button type="button" onclick="zoomin()" class="btn btn-default waves-effect"><i class="material-icons">add</i></button>
                    </div>
                  </div>
               </div>
               <hr>
            <div id="area_id" style="display:none;">
               <input type="checkbox" id="show_cbx_gate"  />
               <label for="show_cbx_gate">Show Gateway/Base station</label>

               <input type="checkbox" id="show_cbx_beacon"  />
               <label for="show_cbx_beacon">Show Beacon Tag</label>
            </div>
    </div>
    <div class="modal fade" id="id_mdl_profile_card" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="idmdlcrLabel">Card Profile User/Data</h4>
                        </div>
                        <div class="modal-body " id="id_mdl_create_body">
                           <table class="table table-bordered ">
                                                <tbody>
                                                    <tr>
                                                        <table class="table table-striped " style=" border-collapse: collapse;">
                                                          <tr>
                                                            <td rowspan="5" style="width: 120px;" >
                                                                 <img id="id_user_photo" class="imgFilter" src="${photo}">
                                                             </td>
                                                            <td>Location</td>
                                                            <td id="id_user_location"></td>
                                                            <td>Date & Time</td>
                                                            <td id="id_user_datetime"></td>
                                                          </tr>
                                                          <tr>
                                                            <td colspan="4"><b id="id_user_alarm_msg"></b></td>
                                                          </tr>
                                                          <tr>
                                                            <td>Name</td>
                                                            <td id="id_user_employee_name"></td>
                                                            <td>Department</td>
                                                            <td id="id_user_department"></td>
                                                          </tr>
                                                          <tr>
                                                            <td>Card & Beacon No</td>
                                                            <td id="id_user_beacon_no"></td>
                                                            <td>Division/Company</td>
                                                            <td id="id_user_company"></td>
                                                          </tr>
                                                          <tr>
                                                            <td>NIK/Employee ID/Staff No</td>
                                                            <td id="id_user_employee_nik"></td>
                                                            <td>Email Address</td>
                                                            <td id="id_user_employee_email"></td>
                                                          </tr>
                                                        </table>
                                                    </tr>
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

    <textarea id="id_building_json" style="display: none;"><?= $data['building']?></textarea>
    <textarea id="id_floor_json" style="display: none;"><?= $data['floor']?></textarea>
    <textarea id="id_room_json" style="display: none;"><?= $data['room']?></textarea>
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <input type="hidden" id="id_input_rect_id" value="">

   <?php $this->load->view("_partials/js_dashboard.php");?>
   <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
    <!-- Moment Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <script src="<?= base_url()?>assets/external/interactjs/interact.js"></script>
    <script src="<?= base_url()?>assets/external/konva.min.js"></script>
    <script type="text/javascript">
      var bs = $('#id_baseurl').val();
      var canvass = $('#id_canvas');
      var filterSize = 20;
      var iconGt = 30;
      var beaconTagSize = 10;
      var initialPos = 20;
      var initialSize = 100;
      var startScan = false;
        var imgDefault = bs+'assets/theme/images/no_avatar.jpg';
        function getBuilding(id){
            var xxx = null;
            for(var x in gBuilding){
                if(gBuilding[x].id == id){
                    xxx = gBuilding[x]
                }
            }
            return xxx;
        }
        function getFloor(id){
            var xxx = null;
            for(var x in gFloor){
                if(gFloor[x].id == id){
                    xxx = gFloor[x]
                }
            }
            return xxx;
        }
      function select_enable(){
            $('.selectpickerr').selectpicker("refresh");
            $('.selectpickerr').selectpicker("initialize");
      }
      var gBuilding = JSON.parse($('#id_building_json').val());
      var gFloor = JSON.parse($('#id_floor_json').val());
      var gRoom = JSON.parse($('#id_room_json').val());
      var doingSetEditingFloor = false;
      var doingSetEditingRoom = false;
      var gSetFloor = {};
      var gSetRoom = {};
      var gFloorResizee = {
        width : 0,
        height : 0,
      }
      var rect1;
      var tr;

      var width = window.innerWidth;
      var height = window.innerHeight;
      var gStage;
      var gLayer;
      var gGrup;
      var gGroupBeacon;
      var gBeaconArray = [];
      var gBeaconObj = {};

      runBuilding();

      setInterval(function(){
         if(startScan == true){
            initStartScan();
         }
      }, 3000)

      function runBuilding(){
         var html = "";
         // html += "<option value='' >-- Building -- </option>";
         for(var x in gBuilding){
                html += "<option value='"+gBuilding[x]['id']+"' >"+gBuilding[x]['name']+"</option>";
         }
         $('#id_building_id').html(html)
         select_enable();
      }

      function onChangeBuild(){
         var v = $('#id_building_id').val();
         if(v == ""){
            Swal.fire(
              'Set Floor !!!',
              'Building & Floor must be selected!',
              'warning'
            )
            return;
         }

         var html = "";
         for(var x in gFloor){
            if(gFloor[x]['building_id'] == v){
                html += "<option value='"+gFloor[x]['id']+"' >"+gFloor[x]['name']+"</option>";
            }
         }
         $('#id_floor_id').html(html)
         select_enable();
      }
      function onChangeSet(){
         var v = $('#id_building_id').val();
         if(v == ""){
            Swal.fire(
              'Set Floor !!!',
              'Building & Floor must be selected!',
              'warning'
            )
            return;
         }

         var html = "";
         for(var x in gBuilding){
            if(gRoom[x]['building_id'] == v){
                html += "<option value='"+gRoom[x]['radid']+"' >"+gRoom[x]['name']+"</option>";
            }
         }
         $('#id_room_id').html(html)
         select_enable();
      }
      
      function initStartGetGateway(){
            var bs = $('#id_baseurl').val();
            var bd = $('#id_building_id').val();
            var rd = $('#id_floor_id').val();
            $.ajax({
                url : bs+"beacon-monitor-room/gateway",
                type : "POST",
                data : {
                    building_id : bd,
                    floor_id : rd,
                },
                dataType: "json",
                beforeSend: function(){
                },
                success:function(data){
                    if(data.status == "success"){
                        var col = data.collection;
                        for(var x in col){
                           var roww = col[x];
                           showImage(roww)
                        }

                        gLayer.add(gGrup);
                        gLayer.add(gGroupBeacon)

                    }
                },
                // error: errorAjax
            })
        }
      function initStartScan(){
            var bs = $('#id_baseurl').val();
            var bd = $('#id_building_id').val();
            var rd = $('#id_floor_id').val();
            $.ajax({
                url : bs+"beacon-monitor-room/beacon",
                type : "POST",
                data : {
                    building_id : bd,
                    floor_id : rd,
                },
                dataType: "json",
                beforeSend: function(){
                },
                success:function(data){
                    if(data.status == "success"){
                        var col = data.collection;
                        // console.log(col);
                        beaconExist(col);
                        runbeacontracks();
                    }
                },
                // error: errorAjax
            })
      }
      function openProfileAccess(dataObj){
        if (dataObj == null  ) {return}
        var item = dataObj;
        var ename = "";
        if(item.employee_id != null){
            ename = item.employee_name;
        }
        var d = moment(item.datetime).format("YYYY-MM-DD HH:mm:ss");  
        var uP = bs + "assets/employee/"+item.employee_photo;
        var photo = item.employee_photo == null || item.employee_photo == ""? imgDefault : uP;
        var ff = getFloor(item.floor_id) == null ? {name : ""} : getFloor(item.floor_id);
        var bb = getBuilding(ff.building_id) == null ? {name : ""}  : getFloor(item.floor_id);
        var room= item.room_id == null ? " " : " - " +item.room_name;
        var location = bb.name + ", "+ff.name +room;
        var alarmMsg = item.alarm == 0 ? "Valid access" : "Invalid & People Pass restricted area";
        $('#id_user_photo').prop('src',photo);
        $('#id_user_location').html(location);
        $('#id_user_datetime').html(d);
        $('#id_user_alarm_msg').html(alarmMsg);
        $('#id_user_employee_name').html(ename);
        $('#id_user_department').html(item.department_name);
        $('#id_user_beacon_no').html(item.beacon_card_no);
        $('#id_user_company').html(item.company_name);
        $('#id_user_employee_nik').html(item.employee_nik);
        $('#id_user_employee_email').html(item.employee_email);
        $('#id_mdl_profile_card').modal('show')

      }
      function runbeacontracks(){
        gSetFloor.plus_width = 0;
        gSetFloor.plus_height = 0;
        var pxx = gSetFloor.pixel;
        var pxxspl = pxx.split("x");
        // var pxLen = gFloorResizee[];
        // var pxLen = pxxspl[0] -0;
        // var pxWid = pxxspl[1]-0;
        var pxLen = gFloorResizee['width'];
        var pxWid = gFloorResizee['height'];
         for(var x in gBeaconObj){
            var beacon_mac = x;
            var ckDataInterfaceBeacon = checkOnGrubBeacon(beacon_mac);
            var data = gBeaconObj[x];
            var pos = data.coordinate_px;
            var posSp = pos.split(',');
            var metLen = meterToPixel(posSp[0]-0, 1) -30;
            var metWid = meterToPixel(posSp[1]-0,1) - 30;
            console.log(posSp[0]-0,posSp[1] - 0, "ckDataInterfaceBeacon",ckDataInterfaceBeacon)

            if(ckDataInterfaceBeacon == null){
                // var iddDataa = data.beacon_mac+"#"
              // console.log("circle", metLen + gSetFloor.plus_width, metWid + gSetFloor.plus_height)
               var circle = new Konva.Circle({
                 id:data.beacon_mac,
                 // x: metLen,
                 // y: metWid,
                 x: metLen+gSetFloor.plus_width,
                 y: metWid+gSetFloor.plus_height,
                 radius: beaconTagSize,
                 fill: '#42A5F5',
                 stroke: 'white',
                 strokeWidth: 4,
               });
               circle.on('click', function (e) {
                 // console.log(e.target);
                 var attr = e.target.attrs;
                 var dataaOnclick = gBeaconObj[attr.id];
                 openProfileAccess(dataaOnclick)

               });
               circle.on('mouseover', function (e) {
                 // console.log(e.target);   
                 e.target.stroke("black")
               });
               circle.on('mouseout', function (e) {
                 // console.log(e.target);
                 e.target.stroke("white")
               });
               gGroupBeacon.add(circle);
            }else{
               var bIndex = ckDataInterfaceBeacon - 0;
               gGroupBeacon.children[bIndex];
               if(metLen <= 0) metLen = 10;
               if(metWid <= 0) metWid = 2;
               if(metLen > pxLen) metLen = pxLen-30;
               if(metWid > pxWid) metWid = pxWid-30;
               var tween = new Konva.Tween({
                  node: gGroupBeacon.children[bIndex],
                  duration: 1,
                  x: metLen+(gSetFloor.plus_width-0),
                  y: metWid+(gSetFloor.plus_height-0),
                });
               tween.play();
               gLayer.draw()
            }
         }
      }
      function meterToPixel(datameter, px = 0){
        var farrr = gFloorResizee.width - 0;
        var lengthfff = gSetFloor.floor_length - 0;
         var met = gSetFloor.meter_per_px -0;

         // console.log("meterToPixel", lengthfff/farrr, met, "datameter",datameter,"res",datameter/met );
         return (datameter/met).toFixed(0)-0;
      }
      function checkOnGrubBeacon(beacon_mac){
         var id = beacon_mac;
         var numindex = null;
         for(var x in gGroupBeacon.children){
            if(gGroupBeacon.children[x].attrs.id == id){
               numindex = x;
               break;
            }
         }
         return numindex;
      }

      function beaconExist(data){
         for(var x in data){
            var r = data[x]
            if(gBeaconArray.includes(r.beacon_mac)){
               var old = gBeaconObj[r.beacon_mac];
               var dtOld = moment(old.datetime).format('X');
               var dtNew = moment(r.datetime).format('X');
               if(dtNew > dtOld){
                  gBeaconObj[r.beacon_mac] = r;
               }
            }else{
               console.log("update beacon pos",r.beacon_mac );

               gBeaconArray.push(r.beacon_mac);
               gBeaconObj[r.beacon_mac] = r;
            }
         }
      }

      function onClickBtnStartScan(){
         if(startScan == true){
            $('#area_id').hide("slow");
            startScan = false;
            $('#btn_start_id').html("Start Scan")
            $('#btn_start_id').removeClass('btn-active')
         }else{
            $('#area_id').show("slow");
            startScan = true;
            $('#btn_start_id').html("Stop Scan")
            $('#btn_start_id').addClass('btn-active')
         }

      }
      function onClickBtnFloor(){
         if(startScan == true){
            Swal.fire(
              'Warning Scanning is active ...!!!',
              'Stop scanning for set new floor!',
              'warning'
            )
            return;
         }
         var floor = $('#id_floor_id').val()
         var building = $('#id_building_id').val()
         if(gLayer != null){ gLayer.clear();}
         if(gStage != null){ gStage.clear();}
         if(gGrup != null){ gGrup.clear();}
         if(gGroupBeacon != null){ gGroupBeacon.clear();}
         buildCANVA();
         initStartGetGateway()
         gGrup.hide();
         gGroupBeacon.hide()
         
         
      }


     
      function getDataRect(){
         var id = $('#id_input_rect_id').val();
         var numindex = null;
         for(var x in gGrup.children){
            if(gGrup.children[x].attrs.id == id){
               numindex = x;
               break;
            }
         }
         return numindex;
      }
      
      $('#show_cbx_gate').on('click', function(e) {
        var a = $(this).data('state');
        var b = $(this).prop('checked');
        if(a == true){
         $(this).prop('checked', false);
         $(this).data('state', false);
         gGrup.hide()
         // disable
        }else{
         gGrup.show()
         $(this).prop('checked', true);
         $(this).data('state', true);
        }
      })
      $('#show_cbx_beacon').on('click', function(e) {
        var a = $(this).data('state');
        var b = $(this).prop('checked');
        if(a == true){
         $(this).prop('checked', false);
         $(this).data('state', false);
         gGroupBeacon.hide()
         // disable
        }else{
         gGroupBeacon.show()
         $(this).prop('checked', true);
         $(this).data('state', true);
        }
      })
      function showImage(data){
         var imageGatewayObj = new Image();
         var sp = (data.location_px).split(",");

         var pathfile = bs + "assets/file/beacon_gateway.png";
         imageGatewayObj.onload = function () {
           var onjImage = new Konva.Image({
             x: sp[0]-20,
             y: sp[1]-20,
             image: imageGatewayObj,
             width: iconGt,
             height: iconGt,
           });
           // add the shape to the layer
           gGrup.add(onjImage);
         };
         imageGatewayObj.src = pathfile;
      }
      function buildCANVA(){
         var floor = $('#id_floor_id').val()
         var building = $('#id_building_id').val()
          if(floor == "" || building == ""){
               Swal.fire(
                 'Set Floor !!!',
                 'Building & Floor must be selected!',
                 'warning'
               )
               $('#id_floor_id').attr('disabled',false)
               $('#id_building_id').attr('disabled',false)
               select_enable();
               return
            }
            doingSetEditingFloor = true
            for(var x in gFloor){
               if(gFloor[x]['id'] == floor){
                  gSetFloor = gFloor[x];
                   break;
               }
            }
            initialEditorCanvas()
            select_enable();
      }
      // var scaleBy = 1.01;
      
     function zoomin(){
        // increasePoint +=increasePointNo;
        var oldScale = gStage.scaleX();
        var gty = gStage.getPointerPosition();
        var center = {
          x: gStage.width() / 2,
          y: gStage.height() / 2,
        };
        var relatedTo = {
            x: (center.x - gStage.x()) / oldScale,
            y: (center.y - gStage.y()) / oldScale,
        };
        // console.log(gty)
        var newScale =
          gty.y > 0 ? oldScale * scaleBy : oldScale / scaleBy;
        // var newScale =oldScale * scaleBy ;
          // e.evt.deltaY > 0 ? oldScale * scaleBy : oldScale / scaleBy;
        gStage.scale({
          x: newScale,
          y: newScale
        });
        var newPos = {
            x: center.x - relatedTo.x * newScale,
            y: center.y - relatedTo.y * newScale,
          };
        // layer.scale({
        //   x: increasePoint,
        //   y: increasePoint,
        // });
        gStage.position(newPos);
        gStage.batchDraw();
    }
    function zoomout(){

        var oldScale = gStage.scaleX();
        var gty = gStage.getPointerPosition();
        var center = {
          x: gStage.width() / 2,
          y: gStage.height() / 2,
        };
        var relatedTo = {
            x: (center.x - gStage.x()) / oldScale,
            y: (center.y - gStage.y()) / oldScale,
        };
        // console.log(gty)
        var newScale =
          gty.y < 0 ? oldScale * scaleBy : oldScale / scaleBy;
        // var newScale =oldScale * scaleBy ;
          // e.evt.deltaY > 0 ? oldScale * scaleBy : oldScale / scaleBy;
        gStage.scale({
          x: newScale,
          y: newScale
        });
        var newPos = {
            x: center.x - relatedTo.x * newScale,
            y: center.y - relatedTo.y * newScale,
          };
        gStage.position(newPos);
        gStage.batchDraw();
    }
      function initialEditorCanvas(){
         var pixel= (gSetFloor.pixel).split("x");
         var imgSrc = gSetFloor.image
         var pathfile = bs + "assets/file/beaconfloor/"+imgSrc;

         var pw = pixel[0]-0;
         var ph = pixel[1]-0;
         // canvass.css("width",(filterSize+pw)+"px");
         // canvass.css("height",(filterSize+ph)+"px");
         canvass.css("border","1px solid #000");

         // var Gwidth = window.innerWidth;
         // var Gheight = window.innerHeight;

         var Gwidth = 1200;
         var Gheight = 660;
       
         gStage = new Konva.Stage({
              container: 'id_canvas',
              width: Gwidth,
              height: Gheight,
        });
         gLayer = new Konva.Layer();
         gGrup = new Konva.Group();
         gGroupBeacon = new Konva.Group();
         gStage.add(gLayer);

        
         // gStage.add(gGrup);
         // var imageObj = new Image();
         // imageObj.onload = function () {
         //   var onjImage = new Konva.Image({
         //     x: filterSize/2,
         //     y: filterSize/2,
         //     image: imageObj,
         //     width: pw,
         //     height: ph,
         //   });
         //   // add the shape to the layer
         //   gLayer.add(onjImage);
         // };
         // imageObj.src = pathfile;
         console.log(gStage.getWidth(),gStage.getHeight())


        var imgObj = new Image();
        var imageObj = new Konva.Image({
            // x: gStage.getWidth() / 2,
            // y: gStage.getHeight() / 2,

            x: gStage.getWidth() / 2,
            y: gStage.getHeight() / 2,
            width: 200, 
            height: 200,
            stroke: 'red',
            strokeWidth: 0,
            draggable: false
          });

          imgObj.src = pathfile;
          gLayer.add(imageObj);
          imgObj.onload = function () {
            imageObj.image(imgObj);  // give the image to the cannvas image object.
            var padding = 20;
            var w = imgObj.width;  
            var h = imgObj.height;
            var targetW = gStage.getWidth() - (2 * padding);
            var targetH = gStage.getHeight() - (2 * padding);
            var widthFit =  targetW / w;
            var heightFit = targetH / h;
            var scale = (widthFit > heightFit) ? heightFit : widthFit  ;
            w = parseInt(w * scale, 10);
            h = parseInt(h * scale, 10);
            imageObj.size({
              width: w,
              height: h
            });
            gFloorResizee['width']= w;
            gFloorResizee['height']= h;
            // console.log(w,h,"Imagee map")
            centreRectShape(imageObj);
          }
      }
      function centreRectShape(shape){
        shape.x( ( gStage.getWidth() - shape.getWidth() ) / 2);
        shape.y( ( gStage.getHeight() - shape.getHeight() ) / 2);
      }
      function getCorner(pivotX, pivotY, diffX, diffY, angle) {
        const distance = Math.sqrt(diffX * diffX + diffY * diffY);

        /// find angle from pivot to corner
        angle += Math.atan2(diffY, diffX);

        /// get new x and y and round it off to integer
        const x = pivotX + distance * Math.cos(angle);
        const y = pivotY + distance * Math.sin(angle);

        return { x: x, y: y };
      }
      function getClientRect(rotatedBox) {
        const { x, y, width, height } = rotatedBox;
        const rad = rotatedBox.rotation;

        const p1 = getCorner(x, y, 0, 0, rad);
        const p2 = getCorner(x, y, width, 0, rad);
        const p3 = getCorner(x, y, width, height, rad);
        const p4 = getCorner(x, y, 0, height, rad);

        const minX = Math.min(p1.x, p2.x, p3.x, p4.x);
        const minY = Math.min(p1.y, p2.y, p3.y, p4.y);
        const maxX = Math.max(p1.x, p2.x, p3.x, p4.x);
        const maxY = Math.max(p1.y, p2.y, p3.y, p4.y);

        return {
          x: minX,
          y: minY,
          width: maxX - minX,
          height: maxY - minY,
        };
      }

      function getTotalBox(boxes) {
        let minX = Infinity;
        let minY = Infinity;
        let maxX = -Infinity;
        let maxY = -Infinity;

        boxes.forEach((box) => {
          minX = Math.min(minX, box.x);
          minY = Math.min(minY, box.y);
          maxX = Math.max(maxX, box.x + box.width);
          maxY = Math.max(maxY, box.y + box.height);
        });
        return {
          x: minX,
          y: minY,
          width: maxX - minX,
          height: maxY - minY,
        };
      }

    </script>
    <script type="text/javascript">
      var element = document.getElementById('sidenav');
       interact(element)
        .draggable({
          // enable inertial throwing
          inertia: true,
          // keep the element within the area of it's parent
          modifiers: [
            interact.modifiers.restrict({
              restriction: element.parentNode,
              elementRect: { top: 0, left: 0, bottom: 1, right: 1 },
              endOnly: true
            })
           
          ],
          // enable autoScroll
          autoScroll: true,
          listeners: {
            // call this function on every dragmove event
            move: dragMoveListener,

            // call this function on every dragend event
            end (event) {
              
            }
          }
        })

         function dragMoveListener (event) {
           var target = event.target
           // keep the dragged position in the data-x/data-y attributes
           var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx
           var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy

           // translate the element
           target.style.transform = 'translate(' + x + 'px, ' + y + 'px)'

           // update the posiion attributes
           target.setAttribute('data-x', x)
           target.setAttribute('data-y', y)
         }

         // this function is used later in the resizing and gesture demos
         window.dragMoveListener = dragMoveListener
    </script>
</body>
</html>
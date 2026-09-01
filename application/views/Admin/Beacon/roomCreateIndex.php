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
           width: 360px;
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
     
      input{
          z-index: 0 !important;
      }
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
            </div>

            <label for="">Room</label>
            <div class="input-group">
                <div class="form-group">
                  <select title="Choose one of the Room.."  name="room_id" id="id_room_id" class="selectpickerr form-control  show-tick"></select>
               </div>
                <span class="input-group-addon">
                     <button class="btn btn-primary btn-sm  waves-effect" onclick="onClickBtnRoom()">Set Room</button>
                  </span>
            </div>
           
            <!-- <label for="">Zoom In/Out</label>
            <div class="input-group">
                  <span class="input-group-addon">
                     <button class="btn btn-primary btn-sm  waves-effect"><i class="material-icons" style="color:white">remove</i></button>
                  </span>
                  <div class="form-line">
                     <input type="number" value="100" class="form-control" placeholder="Zoom">
                  </div>
                  <span class="input-group-addon">
                     <button class="btn btn-primary btn-sm  waves-effect" onclick="buttonZoomIn()"><i class="material-icons" style="color:white">add</i></button>
                  </span>
            </div> -->
            <label for="">Size</label>
            <div class="input-group">
                  <div class="form-line">
                     <input type="number" value="0" id="id_size_length" class="form-control" placeholder="Length Size">
                  </div>
                  <span class="input-group-addon">
                     X
                  </span>
                  <span class="input-group-addon">
                  </span>
                  <div class="form-line">
                     <input type="number" value="0" id="id_size_width" class="form-control" placeholder="Width Size">
                  </div>
            </div>

            <label for="">Position</label>
            <div class="input-group">
                  <div class="form-line">
                     <input type="number" value="0" id="id_pos_x" class="form-control" placeholder="Position X">
                  </div>
                  <span class="input-group-addon">
                     X
                  </span>
                  <span class="input-group-addon">
                  </span>
                  <div class="form-line">
                     <input type="number" value="0" id="id_pos_y" class="form-control" placeholder="Position Y">
                  </div>
            </div>
            <div class="form-group">
               <button type="button"  class="btn btn-block btn-danger btn-sm  waves-effect" onclick="resetDraw()">Reset</button>
               <br>
               <br>
               <button type="button"  class="btn btn-block btn-primary btn-sm  waves-effect" onclick="submitData()">Save</button>
               <br>
               <br> 
               <button type="button"  class="btn btn-block btn-default btn-sm  waves-effect" onclick="backFUNC()">Close</button>
            </div>


    </div>
    <textarea id="id_building_json" style="display: none;"><?= $data['building']?></textarea>
    <textarea id="id_floor_json" style="display: none;"><?= $data['floor']?></textarea>
    <textarea id="id_room_json" style="display: none;"><?= $data['room']?></textarea>
    <textarea id="id_floor_room_json" style="display: none;"><?= $data['floor_room']?></textarea>
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
      var backurl = bs+"beacon-floor";
      var canvass = $('#id_canvas');
      var filterSize = 20;
      var initialPos = 20;
      var initialSize = 100;
      function select_enable(){
         $('.selectpickerr').selectpicker("refresh");
         $('.selectpickerr').selectpicker("initialize");
      }
      var gBuilding = JSON.parse($('#id_building_json').val());
      var gFloor = JSON.parse($('#id_floor_json').val());
      var gFloorRoom = JSON.parse($('#id_floor_room_json').val());
      var gRoom = JSON.parse($('#id_room_json').val());
      var doingSetEditingFloor = false;
      var doingSetEditingRoom = false;
      var gSetFloor = {};
      var gSetRoom = {};

      var rect1;
      var tr;

      var width = window.innerWidth;
      var height = window.innerHeight;
      var gStage;
      var gLayer;
      var gGrup;
      var gGrupInitial;

      runBuilding();
      function backFUNC(){
         Swal.fire({
              title: 'Confirmation',
              text: 'Room changes will be not save?',
              showCancelButton: true,
              confirmButtonText: 'Ok',
              reverseButtons: true,
              allowOutsideClick: () => {return false;},
            }).then((result) => {
              if (result.value) {
                  window.location.href = backurl;
              } else if (result.isDenied) {
                  return false;
              }
         })
      }

      function submitData(){
         if(arrDrawLine.length < 2){
            Swal.fire(
              'Set Draw Shape !!!',
              'Please Draw Shape!',
              'warning'
            )
            return false;
         }
         Swal.fire({
              title: 'Confirmation',
              text: 'Room can be mapping for beacon',
              showCancelButton: true,
              confirmButtonText: 'Ok',
              reverseButtons: true,
              allowOutsideClick: () => {return false;},
            }).then((result) => {
              if (result.value) {
               processsubmit();
              } else if (result.isDenied) {
               return false;
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
      function processsubmit(){
         var dataFormShape = arrDrawLine;
         var f = {
                  building_id: $('#id_building_id').val(),
                  floor_id: $('#id_floor_id').val(),
                  room_id: $('#id_room_id').val(),
                  shape: dataFormShape,
                  length : $('#id_size_length').val(), 
                  width : $('#id_size_width').val(), 
                  x : $('#id_pos_x').val(), 
                  y : $('#id_pos_y').val(), 
                };
         // console.log(f)
         $.ajax({
                url : bs+"beacon-floor-room/post/create",
                type : "POST",
                data : f,
                dataType: "json",
                beforeSend: function(){
                  $('#id_loader').html('<div class="linePreloader"></div>');
                  loadingg('Please wait ! ', 'Process to save')
                },
                success:function(data){
                  $('#id_loader').html('');
                  swal.close();
                  if(data.status == "success"){
                     Swal.fire({
                            title:'Success',
                            text: data.msg,
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Close !',
                            }).then((result) => {
                              window.location.href = backurl;
                                // window.location.reload();
                     })
                     
                      
                  }else{
                     showNotification('alert-danger', data.msg,'top','center')
                  }
                },
                error: errorAjax,
                // error: errorAjax
         })
      }
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
         for(var x in gRoom){
            if(gRoom[x]['building_id'] == v){
                html += "<option value='"+gRoom[x]['radid']+"' >"+gRoom[x]['name']+"</option>";
            }
         }
         $('#id_room_id').html(html)
         select_enable();
      }
      function onClickBtnRoom(){
         var room = $('#id_room_id').val();
         if(room == "" ){
            Swal.fire(
              'Set Room !!!',
              'Room must be selected!',
              'warning'
            )
            return;
         }
         $('#id_pos_x').val(0)
         $('#id_pos_y').val(0)
         $('#id_size_length').val(0)
         $('#id_size_width').val(0)

         if(doingSetEditingRoom == true){
            Swal.fire({
              title: 'Confirmation',
              text: 'Room changes will be reset room back?',
              showCancelButton: true,
              confirmButtonText: 'Ok',
              // denyButtonText: `Don't save`,
              reverseButtons: true,
              allowOutsideClick: () => {return false;},
            }).then((result) => {
              if (result.value) {
               gGrup.destroy();
               gGrup = new Konva.Group();
               gLayer.draw()
               buildRoomCANVA();
              } else if (result.isDenied) {
               return false;
              }
            })
         }else{
            doingSetEditingRoom = true;
            buildRoomCANVA();

         }

        
      }
      function resetDraw(){
         Swal.fire({
              title: 'Confirmation',
              text: 'Room changes will be reset room back?',
              showCancelButton: true,
              confirmButtonText: 'Ok',
              reverseButtons: true,
              allowOutsideClick: () => {return false;},
            }).then((result) => {
              if (result.value) {
               window.location.reload();
              } else if (result.isDenied) {
               return false;
              }
            })
         
      }
      function onClickBtnFloor(){
         var floor = $('#id_floor_id').val()
         var building = $('#id_building_id').val()
         if(doingSetEditingFloor == true){
            gGrupInitial.destroy();
            gGrupInitial = new Konva.Group();
            Swal.fire(
              'Set Floor !!!',
              'Building & Floor must be selected!',
              'warning'
            )
            Swal.fire({
              title: 'Confirmation',
              text: 'All changes will be reset back?',
              showCancelButton: true,
              confirmButtonText: 'Ok',
              reverseButtons: true,
              allowOutsideClick: () => {return false;},
            }).then((result) => {
              if (result.value) {
               arrDrawLine = [];
               $('#id_pos_x').val(0)
               $('#id_pos_y').val(0)
               $('#id_size_length').val(0)
               $('#id_size_width').val(0)
               gLayer.destroy();
               gStage.destroy();
               gGrup.destroy();
               gGrup = new Konva.Group();
               buildCANVA();
               onChangeSet();
                // Swal.fire('Saved!', '', 'success')
              } else if (result.isDenied) {
               return false;
              }
            })
         }else{
            buildCANVA()
            onChangeSet();
         }

      }
      $('#id_size_length').on("keyup", function(){
         var d =$('#id_size_length').val()-0;
         var objectnumindex = getDataRect();
         if(objectnumindex == null){
            return
         }
         gGrup.children[objectnumindex].width(d)
      });
      $('#id_size_width').on("keyup", function(){
         var d = $('#id_size_width').val()-0;
         var objectnumindex = getDataRect();
         if(objectnumindex == null){
            return
         }
         gGrup.children[objectnumindex].height(d)
      });
      $('#id_pos_x').on("keyup", function(){
         var d =$('#id_pos_x').val()-0;
         var objectnumindex = getDataRect();
         if(objectnumindex == null){
            return
         }
         gGrup.children[objectnumindex].x(d)
      });
      $('#id_pos_y').on("keyup", function(){
         var d = $('#id_pos_y').val()-0;
         var objectnumindex = getDataRect();
         if(objectnumindex == null){
            return
         }
         gGrup.children[objectnumindex].y(d)
      });
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
      // ========================================
      // ========================================
      // ========================================
      // ========================================
      var setCurr = {x:null,y:null};
      var setFirst = false;
      var setFirstPoint ={x:null,y:null};
      var onObj = false;
      var onDraw = false;
      var arrDrawLine = [];
      var copyObj;
      function buildRoomCANVA(){
         // console.log(1)
         arrDrawLine = [];
         var room = $('#id_room_id').val();
         for(var x in gRoom){
            if(gRoom[x]['radid'] == room){
               gSetRoom = gRoom[x];
               break;
            }
         }
         var id_rect = "rect-id-"+Date.now();
         $('#id_input_rect_id').val(id_rect);
         var redLine = new Konva.Line({
           name:"linepoint",
           points: [],
           stroke: 'red',
           strokeWidth: 2,

           // lineCap: 'round',
           // lineJoin: 'round',
           fill: 'green',
           // closed:true,
           opacity: 0.6,
         });
         redLine.on('mouseover', function () {
           document.body.style.cursor = 'pointer';
           this.opacity(0.5);
         });
         redLine.on('mouseout', function () {
           document.body.style.cursor = 'default';
           this.opacity(0.6);
         });
         redLine.on('dragmove', function () {
            $('#id_pos_x').val(redLine.x().toFixed(0))
            $('#id_pos_y').val(redLine.y().toFixed(0))
         });
         gGrup.add(redLine);
         gLayer.on('pointerdown', function (event) {
            if(doingSetEditingRoom == false && redLine == null){return false;}
            const { x, y } = event.target?.getStage()?.getPointerPosition();
            if(setFirst == false){
               setFirstPoint.x = x.toFixed(0);
               setFirstPoint.y = y.toFixed(0);
               $('#id_pos_x').val(setFirstPoint.x)
               $('#id_pos_y').val(setFirstPoint.y)
               var anchor = new Konva.Circle({
                   x: x,
                   y: y,
                   id:'firstpoint',
                   radius: 5,
                   stroke: '#666',
                   fill: '#ddd',
                   strokeWidth: 2,
                   draggable: false,
               }).on('mousemove', function (event) {
                  onObj = true;
                  console.log('On first object');
               }).on('mouseout', function () {
                  onObj = false;
               }).on('click', function (event) {
                  if(arrDrawLine.length > 2){
                     var lastYIndex = arrDrawLine.length-1; 
                     var lastXIndex = arrDrawLine.length-2; 
                     var xoLD = arrDrawLine[lastXIndex];
                     var yoLD = arrDrawLine[lastYIndex];
                     var xx = (arrDrawLine[0]-0).toFixed(0);
                     var yy = (arrDrawLine[1]-0).toFixed(0);
                     var d = redLine.points().concat([xx, yy]);
                     arrDrawLine = d;
                     redLine.points(d)
                     redLine.closed(true)
                     copyObj = redLine;
                     redLine = null;
                     gGrup.destroy();
                     if(xoLD<xx){
                        $('#id_pos_x').val((xoLD-0).toFixed(0))
                     }
                     if(yoLD<yy){
                        $('#id_pos_y').val((yoLD-0).toFixed(0))
                     }
                     $('#id_size_length').val(copyObj.width().toFixed(0))
                     $('#id_size_width').val(copyObj.height().toFixed(0))

                     // copyObj.draggable(true);
                     gGrup.add(copyObj)
                     gLayer.add(gGrup);
                     gLayer.draw();

                     console.log('Click complete node',);
                  }else{
                     console.log('Click first object');
                  }
                  // console.log(event.target);
               });
               setFirst = true;
            }else{
               var anchor = new Konva.Circle({
                   id:'slavepoint',
                   x: x,
                   y: y,
                   radius: 5,
                   stroke: '#666',
                   fill: '#ddd',
                   strokeWidth: 2,
                   draggable: false,
               }).on('mousemove', function (event) {
                document.body.style.cursor = 'pointer';
                  onObj = true;
               }).on('mouseout', function () {
                document.body.style.cursor = 'default';
                  onObj = false;
                  // console.log("out first object");
               }).on('click', function (event) {

               });
            }
            if(onObj==false){
               var d = redLine.points().concat([x.toFixed(0), y.toFixed(0)]); // add new line
               arrDrawLine = d;
               redLine.points(d)
               setCurr.x = x.toFixed(0);
               setCurr.y = y.toFixed(0);
               gGrup.add(anchor);
               gLayer.draw();
            }
           
           // redLine
         }).on('pointerup', function (event) {

         });
         gLayer.add(gGrup);
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
            $('#id_floor_id').attr('disabled',true)
            $('#id_building_id').attr('disabled',true)
            select_enable();
      }

      var scaleBy = 1.01;
      function buttonZoomIn(){
         // 
         var oldScale = gStage.scaleX();
         var pointer = gStage.getPointerPosition();
         var mousePointTo = {
            x: (pointer.x - gStage.x()) / oldScale,
            y: (pointer.y - gStage.y()) / oldScale,
         };
         var direction = 1;
         var newScale = direction > 0 ? oldScale * scaleBy : oldScale / scaleBy;
         gStage.scale({ x: newScale, y: newScale });
         var newPos = {
             x: pointer.x - mousePointTo.x * newScale,
             y: pointer.y - mousePointTo.y * newScale,
           };
         gStage.position(newPos);
         // stage.scale({ x: newScale+, y: newScale });
      }

      
      function initialEditorCanvas(){
         var pixel= (gSetFloor.pixel).split("x");
         var imgSrc = gSetFloor.image
         var pathfile = bs + "assets/file/beaconfloor/"+imgSrc;

         var pw = pixel[0]-0;
         var ph = pixel[1]-0;
         canvass.css("width",(filterSize+pw)+"px");
         canvass.css("height",(filterSize+ph)+"px");
         canvass.css("border","1px solid #000");
         gStage = new Konva.Stage({
              container: 'id_canvas',
              width: filterSize+pw,
              height: filterSize+ph,
         });

         gLayer = new Konva.Layer();
         // gLayer.add(redLine);
         gGrup = new Konva.Group();
         gGrupInitial = new Konva.Group();
         gStage.add(gLayer);
         // gStage.add(gGrup);
         var imageObj = new Image();
         imageObj.onload = function () {
           var onjImage = new Konva.Image({
             x: filterSize/2,
             y: filterSize/2,
             image: imageObj,
             width: pw,
             height: ph,
           });

           // add the shape to the layer
          gLayer.add(onjImage);
          initRoomAnotherr()
          // gLayer.add(poly);
          // gLayer.add(redLine);

         };
         imageObj.src = pathfile;
      }
      function initRoomAnotherr(){
         var f_id=$('#id_floor_id').val();
         for(var x in gFloorRoom){
            var ii = gFloorRoom[x];
            var points = JSON.parse(ii.shape);
            var ddRoom = new Konva.Line({
               name:"linepoint_point",
               id:ii.room_id,
               points: points,
               stroke: 'red',
               strokeWidth: 2,
              fill: 'red',
              closed:true,
              opacity: 0.6,
              draggable:false,
            }).on('mousemove', function (event) {
                  onObj = true;
            }).on('mouseout', function () {
                  onObj = false;
            });
            gGrupInitial.add(ddRoom)
         }
         gLayer.add(gGrupInitial);
         gLayer.draw();
         
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
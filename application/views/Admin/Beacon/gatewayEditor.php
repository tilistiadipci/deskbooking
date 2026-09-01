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
           right: 100px;
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
          
            <label for="">Floor </label>
            <div class="form-group">
                <div class="form-line">
                    <input disabled type="text" value="" id="id_floor_name" class="form-control" >
                </div>
            </div>
           


            <label for="">Position</label>
            <div class="input-group">
                  <div class="form-line">
                     <input type="number" readonly value="0" id="id_pos_x" class="form-control" placeholder="Position X">
                  </div>
                  <span class="input-group-addon">
                     X
                  </span>
                  <span class="input-group-addon">
                  </span>
                  <div class="form-line">
                     <input type="number" readonly value="0" id="id_pos_y" class="form-control" placeholder="Position Y">
                  </div>
            </div>
            <div class="form-group">
                <div class="row clearfix">
                <div class="col-xs-12 align-left">
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" onclick="zoomout()" class="btn btn-default waves-effect"><i class="material-icons">remove</i></button>
                        <button type="button" onclick="zoomin()" class="btn btn-default waves-effect"><i class="material-icons">add</i></button>
                    </div>
                </div>
               </div>
               <br>
               <br>
               <button type="button"  class="btn btn-block btn-danger btn-sm  waves-effect" onclick="resetDraw()">Reset</button>
               <br>
               <br>
               <button type="button"  class="btn btn-block btn-primary btn-sm  waves-effect" onclick="saveFunc()">Save</button>
               <br>
               <br> 
               <button type="button"  class="btn btn-block btn-default btn-sm  waves-effect" onclick="backFUNC()">Close</button>
            </div>


    </div>
    <textarea id="id_floor_json" style="display: none;"><?= $data['floor']?></textarea>
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <input type="hidden" id="id_input_rect_id" value="">
    <input type="hidden" id="id_pointer" value="<?= $data['pointer']?>">
    <input type="hidden" id="id_selector1" value="<?= $data['selector1']?>">
    <input type="hidden" id="id_selector2" value="<?= $data['selector2']?>">


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
      var initialPos_x = 20;
      var initialPos_y = 20;
      var initialSize = 15;
      var selector1 = $('#id_selector1').val()
      var selector2 = $('#id_selector2').val()
      var pointer = $('#id_pointer').val()


      function select_enable(){
         $('.selectpickerr').selectpicker("refresh");
         $('.selectpickerr').selectpicker("initialize");
      }
      var gFloor = JSON.parse($('#id_floor_json').val());
      var doingSetEditingFloor = false;
      var doingSetEditingRoom = false;
      var gSetFloor = {};
      var gSetRoom = {};

      var rect1;
      var tr;

      var Gwidth = 1200;
      var Gheight = 660;

      var width = window.innerWidth;
      var height = window.innerHeight;
      var gStage;
      var gLayer;
      var gGrup;
      var gGrupInitial;

        var scaleBy = 1.1;
    var scaleByInitial = 2;
    // setTimeout(function(){
    //        // zoomInitial()
    //     },5000)

      initial()
      // zoomInitial()
    var anchor;
    initialPos_x = anchor.x();
    initialPos_y = anchor.x();
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

    function zoomInitial(){

        var oldScale = gStage.scaleX();
        var gty = gStage.getPointerPosition();
        // console.log(gStage)
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
          gty.y < 0 ? oldScale * scaleByInitial+1 : oldScale / scaleByInitial+1;
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

    function updatePos(){
        var x = anchor.x();
        var y = anchor.y();
        $('#id_pos_x').val(x.toFixed(0) - 0)
        $('#id_pos_y').val(y.toFixed(0) - 0)
    }
    function backFUNC(){
         Swal.fire({
              title: 'Confirmation',
              text: 'Changes will be not save?',
              showCancelButton: true,
              confirmButtonText: 'Ok',
              reverseButtons: true,
              allowOutsideClick: () => {return false;},
            }).then((result) => {
              if (result.value) {
                if (window.opener != null && !window.opener.closed) {
                       
                }
                window.close();
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
      
    function saveFunc() {
            var posPixel_x =  $('#id_pos_x').val()-0;
            var posPixel_y =  $('#id_pos_y').val()-0;

             var posMeter_x = (posPixel_x * (gFloor.meter_per_px -0)).toFixed(2);
             var posMeter_y = (posPixel_y * (gFloor.meter_per_px -0)).toFixed(2);

            // console.log(selector)
            if (window.opener != null && !window.opener.closed) {
                    var selector1Obj = window.opener.document.getElementById(selector1);
                    var selector2Obj = window.opener.document.getElementById(selector2);
                    selector1Obj.value = posMeter_x+","+posMeter_y;
                    selector2Obj.value = posPixel_x+","+posPixel_y;
            }
            window.close();
    }

      
      function resetDraw(){
         Swal.fire({
              title: 'Confirmation',
              text: 'Changes will be reset back?',
              showCancelButton: true,
              confirmButtonText: 'Ok',
              reverseButtons: true,
              allowOutsideClick: () => {return false;},
            }).then((result) => {
              anchor.x(initialPos_x);
              anchor.y(initialPos_y);
              $('#id_pos_x').val(initialPos_x)
              $('#id_pos_y').val(initialPos_y)
            })
         
      }
      function initial(){
        // gFloor

        var floor = gFloor;
        var pixel= (floor.pixel).split("x");
        var imgSrc = floor.image
        var pathfile = bs + "assets/file/beaconfloor/"+imgSrc;

        $('#id_floor_name').val(floor.name)
        var pw = pixel[0]-0;
        var ph = pixel[1]-0;
        // var Gwidth = window.innerWidth;
        // var Gheight = window.innerHeight;
       
        canvass.css("border","1px solid #000");
        gStage = new Konva.Stage({
            container: 'id_canvas',
            width: Gwidth,
            height: Gheight,
        });
        canvass[0].click();
        // document.getElementById("myCheck").click();

        $('#id_pos_x').val(initialPos_x)
        $('#id_pos_y').val(initialPos_y)
        gLayer = new Konva.Layer();
        gGrup = new Konva.Group();
        gGrupInitial = new Konva.Group();
        gStage.add(gLayer);
         // gStage.add(gGrup);
        var imgObj = new Image();
        var imageObj = new Konva.Image({
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
            // console.log(3)
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
           centreRectShape(imageObj);

          gLayer.add(anchor);
          if(pointer != "" && pointer != null){
            var ssp = pointer.split(",");
                if(ssp.length > 1){

                    anchor.x(ssp[0]-0)
                    anchor.y(ssp[1]-0)
                }
            }
        };
        // imageObj.src = pathfile;
        anchor = new Konva.Circle({
            x: gStage.getWidth() / 2,
            y: gStage.getHeight() / 2,
            id:'firstpoint',
            radius: initialSize,
            stroke: 'black',
            fill: 'red',
            strokeWidth: 3,
            draggable: true,
        }).on('dragmove', function (e) {
            updatePos()
        }).on('dragend', function (e) {
            updatePos()
        }).on('mouseover', function () {
            document.body.style.cursor = 'pointer';
        }).on('mouseout', function () {
            document.body.style.cursor = 'default';
        });

      }
      function centreRectShape(shape){
        shape.x( ( gStage.getWidth() - shape.getWidth() ) / 2);
        shape.y( ( gStage.getHeight() - shape.getHeight() ) / 2);
      }
      
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
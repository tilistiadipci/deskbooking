
<?php  
// date_default_timezone_set("Asia/Jakarta");
// echo date('w');
// print_r($modules);
// die();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>""));?>
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/theme/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/external/filepond/filepond.min.css" rel="stylesheet">
    <style>
        body{
            overflow: hidden;
/*            overflow: auto;*/
        }
        .footer {
/*            height: 100px;*/
           position: fixed;
           left: 20vw;
           bottom: 10px;
           width: 57vw;
/*           background-color: red;*/
           color: black;
           text-align: center;
        }
        .container{
            overflow: auto;
        }
        .link-area.active{
          background-color: #eee !important;
          font-weight: bold !important;
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
        <?php $this->load->view("BeaconFloor/navbar.konva.php", array("pagename"=>""));?>
    <!-- #Top Bar -->
    <section>
        <?php $this->load->view("BeaconFloor/sidebar.konva.php",  array("building"=>$building, 'query' => $query));?>
    </section>
    <section>
        <?php $this->load->view("BeaconFloor/rightbar.konva.php");?>
    </section>
    <section class="content" style="margin-left:0px  !important; margin-top:0px !important;">
      <center>
        <div id="container"></div>
      </center>
    </section>
    <div class="footer">
      <div class="row clearfix">
        <div class="col-xs-8 align-left">
          <button type="button" onclick="saveFloorArea()" class="btn btn-lg btn-success waves-effect"><b>Save Map</b></button>
          &nbsp;
          &nbsp;
          <button type="button" id="id_cancel_create_shape" style="display:none;" onclick="cancel_create_shape()" class="btn btn-lg btn-default waves-effect"><b>Cancel area shape </b></button>
        </div>
        <div class="col-xs-4 align-right">
            <div class="btn-group" role="group" aria-label="First group">
                <button type="button" onclick="zoomout()" class="btn btn-default waves-effect"><i class="material-icons">remove</i></button>
                <button type="button" onclick="zoomin()" class="btn btn-default waves-effect"><i class="material-icons">add</i></button>
            </div>
        </div>
      </div>
    </div>
   
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
    <!-- Input Mask Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    <script src="<?= base_url()?>assets/external/konva.min.js"></script>
    <textarea id="id_query" style="display:none;"><?= json_encode($query)?></textarea>
    <textarea id="id_room" style="display:none;"><?= json_encode($room)?></textarea>
    <script>
      var bs = "<?= base_url()?>";
      var queryStr = $('#id_query').val();
      var roomStr = $('#id_room').val();
      var query = JSON.parse(queryStr);
      var gRoom = JSON.parse(roomStr);
      var gRoomBuilding = [];
      var pathfloorfile = bs + "assets/file/beaconfloor/demo.jpeg";
      var pathfloorfile_2 = bs + "assets/file/beaconfloor/";

      var Gwidth = 1200;
      var Gheight = 660;
      // var width = window.innerWidth;
      // var height = window.innerHeight;
      var increasePoint = 1;
      var increasePointNo = 0.1;
      // var WIDTH = 1920;
      // var HEIGHT = 1080;
      var NUMBER = 200;
      const PADDING = 0;

      var tween = null;
      var scaleBy = 1.1;
      var scaleByInitial = 2;

      var stage = new Konva.Stage({
        container: 'container',
        width: Gwidth,
        height: Gheight,
      });
      var shapingAreaHistory = [];
      var shapingAreaHistoryStep = [];
      $('#container').css("border","1px solid #000");

      // var imageLayers = new Konva.Layer();
      var layer = new Konva.Layer();
      var scrollLayers = new Konva.Layer();
      var drawAreaLayer = new Konva.Layer();

      var area_group = new Konva.Group();
      // stage.add(scrollLayers);
      // stage.add(imageLayers);
      stage.add(layer);
      stage.add(drawAreaLayer);
      layer.add(area_group);
      var tooltipLayer = new Konva.Layer();
      var tooltip = new Konva.Label({
        opacity: 0.75,
        visible: false,
        listening: false,
      });
      tooltip.add(
        new Konva.Tag({
          fill: 'black',
          pointerDirection: 'down',
          pointerWidth: 10,
          pointerHeight: 10,
          lineJoin: 'round',
          shadowColor: 'black',
          shadowBlur: 10,
          shadowOffsetX: 10,
          shadowOffsetY: 10,
          shadowOpacity: 0.5,
        })
      );
      tooltip.add(
        new Konva.Text({
          text: '',
          fontFamily: 'Calibri',
          fontSize: 18,
          padding: 5,
          fill: 'white',
        })
      );
      tooltipLayer.add(tooltip);
      stage.add(tooltipLayer);


      if(query['building'] != null && query['building'] != ""){
          initFloorList(query['building']);
          getRoomFromBuilding();
      }
      function getRoomFromBuilding (id = ""){
        gRoomBuilding = [];
        for(var xx in gRoom){
          if(gRoom[xx].building_id == query['building']){
            gRoomBuilding.push(gRoom[xx])
          }
        }
      }

      var area_new = JSON.stringify({
        id : null,
        rand_id : "",
        floor_id : "",
        building_id : "",
        room_id : "",
        room_name : "",
        name : "",
        length : "",
        width : "",
        color_box : "",
        shape : "",
        position_px : "",
        finish : false,
      });
      var listt_area = [];
      var listt_area_delete = [];
      var new_area = "";
      var new_obj_area = {};
      var current_area = {};
      function select_enable(selecc = 'select'){
        $(selecc).selectpicker("refresh");
        $(selecc).selectpicker("initialize");
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
      function zoomin(){
        var oldScale = stage.scaleX();
        var gty = stage.getPointerPosition();
        var center = {
          x: stage.width() / 2,
          y: stage.height() / 2,
        };
        var relatedTo = {
            x: (center.x - stage.x()) / oldScale,
            y: (center.y - stage.y()) / oldScale,
        };
        var newScale =
          gty.y > 0 ? oldScale * scaleBy : oldScale / scaleBy;
        stage.scale({
          x: newScale,
          y: newScale
        });
        var newPos = {
            x: center.x - relatedTo.x * newScale,
            y: center.y - relatedTo.y * newScale,
          };
        stage.position(newPos);
        stage.batchDraw();
      }
      function zoomout(){
        var oldScale = stage.scaleX();
        var gty = stage.getPointerPosition();
        var center = {
          x: stage.width() / 2,
          y: stage.height() / 2,
        };
        var relatedTo = {
            x: (center.x - stage.x()) / oldScale,
            y: (center.y - stage.y()) / oldScale,
        };
        var newScale =
          gty.y < 0 ? oldScale * scaleBy : oldScale / scaleBy;
        stage.scale({
          x: newScale,
          y: newScale
        });
        var newPos = {
            x: center.x - relatedTo.x * newScale,
            y: center.y - relatedTo.y * newScale,
          };
        stage.position(newPos);
        stage.batchDraw();
      }
      
      // ==========startDrawArea==============================
      // ==========startDrawArea==============================
      // ==========startDrawArea==============================
      // ==========startDrawArea==============================
      var setCurr = {x:null,y:null};
      var setFirst = false;
      var setFirstPoint ={x:null,y:null};
      var onObj = false;
      // var onDraw = false;
      var arrDrawLine = [];
      var arrDrawLine_obj = "";
      var copyObj;
      var redLine;
      function startDrawArea(){
         // console.log(1)
         arrDrawLine = [];
         setFirst = false;
         onObj = false;
        new_obj_area[new_area.rand_id] = new Konva.Line({
           name:new_area.name,
           points: [],
           stroke: 'red',
           strokeWidth: 2,
           fill: 'green',
           opacity: 0.6,
         });
         new_obj_area[new_area.rand_id].setAttr('rand_id', new_area.rand_id);
         new_obj_area[new_area.rand_id].on('mouseover', function () {
           document.body.style.cursor = 'pointer';
           this.opacity(0.5);
         });
         new_obj_area[new_area.rand_id].on('mouseout', function () {
           document.body.style.cursor = 'default';
           this.opacity(0.6);
         });
         new_obj_area[new_area.rand_id].on('dragmove', function () {
            $('#id_pos_x').val(new_obj_area[new_area.rand_id].x().toFixed(0))
            $('#id_pos_y').val(new_obj_area[new_area.rand_id].y().toFixed(0))
         });

         drawAreaLayer.add(new_obj_area[new_area.rand_id]);
      }
    
      function removeSlavePoint(){
        drawAreaLayer.clear()
        var xc = drawAreaLayer.find('Circle');

        for(var im in xc){
          xc[im].destroy()
        }
        drawAreaLayer.draw();
      }

      stage.on('pointerdown', function (event) {
            const { x, y } = stage.getPointerPosition();
              console.log(new_area['rand_id'],setFirst);
            if(new_area['rand_id'] != null && setFirst == false){
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
                     var d = new_obj_area[new_area.rand_id].points().concat([xx, yy]);
                     arrDrawLine_obj += xx+","+yy;
                     arrDrawLine = d;
                     new_obj_area[new_area.rand_id].points(d)
                     new_obj_area[new_area.rand_id].closed(true)
                     copyObj = new_obj_area[new_area.rand_id];
                     // redLine = null;
                     if(xoLD<xx){
                        $('#id_pos_x').val((xoLD-0).toFixed(0))
                     }
                     if(yoLD<yy){
                        $('#id_pos_y').val((yoLD-0).toFixed(0))
                     }
                     $('#id_size_length').val(copyObj.width().toFixed(0))
                     $('#id_size_width').val(copyObj.height().toFixed(0))

                     // drawAreaLayer.destroy();
                     layer.add(copyObj)
                     // drawAreaLayer.delete();
                     drawAreaLayer.draw();
                     removeSlavePoint();
                     finish_create_shape()

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
                  onObj = true;
               }).on('mouseout', function () {
                  onObj = false;
                  // console.log("out first object");
               }).on('click', function (event) {

               });
            }
            if(onObj==false && new_area.rand_id !=null ){

               var d = new_obj_area[new_area.rand_id].points().concat([x.toFixed(0), y.toFixed(0)]); // add new line
               // console.log()
               arrDrawLine_obj += x.toFixed(0)+","+y.toFixed(0)+"|";
               arrDrawLine = d;
               new_obj_area[new_area.rand_id].points(d)
               setCurr.x = x.toFixed(0);
               setCurr.y = y.toFixed(0);
               drawAreaLayer.add(anchor);
               drawAreaLayer.draw();
            }
           
           // redLine
      }).on('pointerup', function (event) {

      });
      function initFloorArea(floor_id = ""){
        $.ajax({
          url : bs+"beacon-floor-area-room-editor/floor-area",
          type : "GET",
          dataType: "json",
          data:  {floor_id : floor_id},
          beforeSend: function(){

          },
          success:function(data){
            swal.close();
            if(data.status == "success"){
              var row = data.collection;
              // id_floor_area
              var html = '';
              var data_coldatalist = []
              // listt_area = row;
              for(var x in row){
                var coldata = {
                  id : row[x]['id'],
                  rand_id : makeid(12),
                  floor_id : row[x]['floor_id'],
                  building_id : row[x]['building_id'],
                  room_id : row[x]['room_id'],
                  room_name : row[x]['room_name'],
                  name : row[x]['name'],
                  length : row[x]['length'],
                  width : row[x]['width'],
                  color_box : "",
                  shape : row[x]['shape'],
                  position_px : row[x]['shape'],
                  finish : true,
                }
                data_coldatalist.push(coldata)
              }
              listt_area = data_coldatalist;

              reGenerateListArea ()
              reGenerateShapeArea ();
             
            }
          },
          error: errorAjax
        })
      }
      function reGenerateShapeArea (){

        for(var x in listt_area){
          var points = extractPointObj(listt_area[x].shape);
              // console.log(points)

          if(points == null){
            continue;
          }
          new_obj_area[listt_area[x].rand_id] = new Konva.Line({
            name:listt_area[x].name,
            points: points,
            stroke: 'red',
            strokeWidth: 2,
            fill: 'green',
            opacity: 0.6,
            closed :  true,
          }).on('mouseover', function () {
            document.body.style.cursor = 'default';
            // document.body.style.cursor = 'pointer';
            this.opacity(0.5);
            var mousePos = stage.getPointerPosition();
            var x = mousePos.x;
            var y = mousePos.y;
            // console.log(this.attrs)
            updateTooltip(tooltip, x, y, this.attrs.name);
         }).on('mouseout', function () {
            document.body.style.cursor = 'default';
            tooltip.hide();
            this.opacity(0.6); 
         });
            layer.add(new_obj_area[listt_area[x].rand_id]);
        }
      }
      function updateTooltip(tooltip, x, y, text) {
        tooltip.getText().text(text);
        tooltip.position({
          x: x,
          y: y,
        });
        tooltip.show();
      }
      function extractPointObj(datastring = ""){
        var nodes = datastring.split("|");
        var nodepoints = [];
        if(nodes.length < 1){
          return null
        }
        for(var x in nodes){
          var nodestring = nodes[x];
          var node = nodestring.split(",");
          console.log(nodestring)
          for(var n1 in node){
            nodepoints.push(node[n1]);
          }
        }
        return nodepoints;
      }
     

      function initMapFloor(floor_id = ""){
        $.ajax({
          url : bs+"beacon-floor-area-room-editor/floor-data",
          type : "GET",
          dataType: "json",
          data:  {floor_id : floor_id},
          beforeSend: function(){
            loadingg('Please wait ! ', 'Loading . . . ')
              // $('#id_loader').html('<div class="linePreloader"></div>');
          },
          success:function(data){
            swal.close()
            if(data.status == "success"){
              var row = data.collection;
              if(row['image'] != null){
                initMapImage(row['image'])

              }
            }
          },
          error: errorAjax
        })
      }

      function initFloorList(id = ""){
            $.ajax({
                url : bs+"beacon-floor-area-room-editor/floorlist",
                type : "GET",
                dataType: "json",
                data:  {building_id : id},
                beforeSend: function(){
                  loadingg('Please wait ! ', 'Loading . . . ')
                    // $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                  swal.close()

                  if(data.status == "success"){
                    var html = "";
                    var urll = bs+'beacon-floor-area-room-editor?building='+id+'&floor=';
                    var floor_click = false;
                    var floor_id = "";
                    for(var x in data.collection){
                      var r = data.collection[x];
                      var f_urll = urll + r['id'];
                      var sssl ="";
                      html += '<li>'
                      if(query['floor'] != null && query['floor'] != ""){
                          var val_floor = query['floor'];
                          if(val_floor==r['id']){
                            floor_click = true;
                            floor_id = r['id'];
                            html += '<a href="javascript:;"><b>'+r['name'];
                            html += '</b></a>';
                          }else{
                            html += '<a href="'+f_urll+'">'+r['name'];
                            html += '</a>';
                          }
                          sssl = val_floor==r['id'] ? "selected":"";
                      }else{
                        html += '<a href="'+f_urll+'">'+r['name'];
                        html += '</a>';
                      }
                      html += '</li>'
                     
                     
                    }
                    $('#id_floor_list').html(html);

                    if(floor_click){
                      initMapFloor(floor_id);
                      initFloorArea(floor_id)
                    }

                      
                  }else{
                      
                  }
                },
                error: errorAjax
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

      function errorAjax(xhr, ajaxOptions, thrownError){
            swal.close();

            $('#id_loader').html('');
            if(ajaxOptions == "parsererror"){
                var msg = "Status Code 500, Error Server bad parsing";
                showNotification('alert-danger', msg,'bottom','left')
            }else{
                var msg ="Status Code "+ xhr.status + " Please check your connection !!!";
                showNotification('alert-danger', msg,'bottom','left')
            }
        }

      
      function shape_mouseover(evt){
        var shape = evt.target;
        shape.setAttr('oldFill', shape.fill());
        shape.fill('lime');
        document.body.style.cursor = 'pointer';
        // console.log(shape.x()-5,shape.y()-5);
        // console.log(shape.width()+5,shape.height()+);
        //  var rect1_hover = new Konva.Rect({
        //     x: shape.x()-10,
        //     y: shape.y()-10,
        //     width: shape.width()+20,
        //     height: shape.height()+20,
        //     strokeWidth: 1,
        //     fill: 'rgba(0,0,255,0.2)',
        //     stroke: 'rgba(0,0,255,1)',
        //     name: 'border',
        //     // draggable: true,
        //   });
        //  rect1_hover.on('mouseout', (evt)=>{
        //     var shape = evt.target;
        //     shape.fill(shape.getAttr('oldFill')); 
        //     document.body.style.cursor = 'default';
        //     var shapes = borderSelected.find('.border');
        //     shapes.filter((shape) =>{
        //         shape.destroy();
        //     });
        // });
        //  borderSelected.add(rect1_hover);
      }
      function centreRectShape(shape){
        shape.x( ( stage.getWidth() - shape.getWidth() ) / 2);
        shape.y( ( stage.getHeight() - shape.getHeight() ) / 2);
      }
      function shape_mouseout(evt){
        var shape = evt.target;
        shape.fill(shape.getAttr('oldFill')); 
        document.body.style.cursor = 'default';
        // var shapes = borderSelected.find('.border');
        // shapes.filter((shape) =>{
        //     shape.destroy();
        // });
      }
      var tr = new Konva.Transformer();
      layer.add(tr);
      var selectionRectangle = new Konva.Rect({
        fill: 'rgba(0,0,255,0.5)',
        visible: false,
      });
      layer.add(selectionRectangle);

      var x1, y1, x2, y2;
      stage.on('mousedown touchstart', (e) => {
        // do nothing if we mousedown on any shape
        if (e.target !== stage) {
          return;
        }
        e.evt.preventDefault();
        x1 = stage.getPointerPosition().x;
        y1 = stage.getPointerPosition().y;
        x2 = stage.getPointerPosition().x;
        y2 = stage.getPointerPosition().y;
        selectionRectangle.visible(true);
        selectionRectangle.width(0);
        selectionRectangle.height(0);
      });
    
      stage.on('mousemove touchmove', (e) => {
        // console.log("asas")
        // if (!selectionRectangle.visible()) {
        //   return;
        // }
        // e.evt.preventDefault();
        // x2 = stage.getPointerPosition().x;
        // y2 = stage.getPointerPosition().y;
        // selectionRectangle.setAttrs({
        //   x: Math.min(x1, x2),
        //   y: Math.min(y1, y2),
        //   width: Math.abs(x2 - x1),
        //   height: Math.abs(y2 - y1),
        // });

      });

      stage.on('mouseup touchend', (e) => {
        document.body.style.cursor = 'default';
        // do nothing if we didn't start selection
        if (!selectionRectangle.visible()) {
          return;
        }
        e.evt.preventDefault();
        // update visibility in timeout, so we can check it in click event
        setTimeout(() => {
          selectionRectangle.visible(false);
        });

        var shapes = stage.find('.rect');
        var box = selectionRectangle.getClientRect();
        var selected = shapes.filter((shape) =>
          Konva.Util.haveIntersection(box, shape.getClientRect())
        );
        tr.nodes(selected);
      });
      // clicks should select/deselect shapes
      stage.on('click tap', function (e) {
        // if we are selecting with rect, do nothing
        if (selectionRectangle.visible()) {
          return;
        }
        // if click on empty area - remove all selections
        if (e.target === stage) {
          tr.nodes([]);
          return;
        }
        // do nothing if clicked NOT on our rectangles
        if (!e.target.hasName('rect')) {
          return;
        }
        // do we pressed shift or ctrl?
        const metaPressed = e.evt.shiftKey || e.evt.ctrlKey || e.evt.metaKey;
        const isSelected = tr.nodes().indexOf(e.target) >= 0;
        var shapes = borderSelected.find('.border');
        shapes.filter((shape) =>{
          shape.destroy();
        });
        if (!metaPressed && !isSelected) {
          tr.nodes([e.target]);
        } else if (metaPressed && isSelected) {
          const nodes = tr.nodes().slice(); // use slice to have new copy of array
          nodes.splice(nodes.indexOf(e.target), 1);
          tr.nodes(nodes);
        } else if (metaPressed && !isSelected) {
          const nodes = tr.nodes().concat([e.target]);
          tr.nodes(nodes);
        }
      });
      function initMapImage(imageSRCC){
          if(imageSRCC == null || imageSRCC == ""){
            return;
          }
          var _imgsrc = pathfloorfile_2+imageSRCC;
          var imgObj = new Image();
          // console.log(stage.getWidth() / 2)
          var uploadedImage = new Konva.Image({
            x: stage.getWidth() / 2,
            y: stage.getHeight() / 2,
            width: 200, 
            height: 200,
            stroke: 'red',
            strokeWidth: 0,
            draggable: false
          });
          layer.add(uploadedImage);
          imgObj.src = _imgsrc;
          imgObj.onload = function() {
            uploadedImage.image(imgObj);  // give the image to the cannvas image object.
            var padding = 20;
            var w = imgObj.width;  
            var h = imgObj.height;
            var targetW = stage.getWidth() - (2 * padding);
            var targetH = stage.getHeight() - (2 * padding);
            var widthFit =  targetW / w;
            var heightFit = targetH / h;
            var scale = (widthFit > heightFit) ? heightFit : widthFit  ;
            w = parseInt(w * scale, 10);
            h = parseInt(h * scale, 10);
            uploadedImage.size({
              width: w,
              height: h
            });
            centreRectShape(uploadedImage); 
            layer.draw(); // My favourite thing to forget.
          }
      }
    </script>
    <script>
      function addNewArea(indexx = ""){
        console.log(indexx)
        if(new_area['rand_id'] != null){
          return;
        }
        if(query['building'] == null && query['building'] == ""){
           showNotification('alert-danger', 'Please select a building','bottom','left')
          return;
        }
        if(query['floor'] == null && query['floor'] == ""){
           showNotification('alert-danger', 'Please select a floor','bottom','left')
          return;
        }
        const ddataArea = JSON.parse(area_new);
          var rr = makeid(10);
          ddataArea['rand_id'] =rr 
          ddataArea['floor_id'] = query['floor'];
          ddataArea['building_id'] = query['building'];
          ddataArea['name'] = "New area of "+rr;
          listt_area.push(ddataArea);
          new_area = ddataArea;
          $('#id_btn_create_shape').prop('disabled',true);
          $('#id_crt_create_area').prop('disabled',true);
          $('#id_cancel_create_shape').show('fast');
          $('#id_crt_remove_area').hide("fast");
          $('#id_crt_create_area').show("fast");
          document.body.style.cursor = 'pointer';
          $('#id_crt_name').val(ddataArea['name']);
          $('#id_crt_width').val(ddataArea['length']);
          $('#id_crt_height').val(ddataArea['width']);
          startDrawArea();
          reGenerateListArea ();
          reGenerateRoom();
      }
      function updateNewArea(indexx = ""){
        const ddataArea = listt_area[indexx];
          // console.log(ddataArea)
          // listt_area.push(ddataArea);
          new_area = ddataArea;
          $('#id_btn_create_shape').prop('disabled',true);
          $('#id_crt_create_area').prop('disabled',true);
          $('#id_cancel_create_shape').show('fast');
          $('#id_crt_remove_area').hide("fast");
          $('#id_crt_create_area').show("fast");
          document.body.style.cursor = 'pointer';
          $('#id_crt_name').val(ddataArea['name']);
          $('#id_crt_width').val(ddataArea['length']);
          $('#id_crt_height').val(ddataArea['width']);
          startDrawArea();
          reGenerateListArea ();
          reGenerateRoom();
        
      }
      function cancel_create_shape(){
        $('#frm_rightbar')[0].reset();
        var idelete = [];
        for(var xi in listt_area){
          if(listt_area[xi]['finish'] == false){
            idelete.push(xi);
            new_obj_area[listt_area[xi].rand_id].remove() ;
          }
        }
        for (var i = idelete.length -1; i >= 0; i--){
          listt_area.splice(idelete[i],1);
        }
        new_area = {};
        drawAreaLayer.draw();
        $('#id_btn_create_shape').prop('disabled',false);
        $('#id_cancel_create_shape').hide('fast');
        $('#id_crt_create_area').prop('disabled',false);
        $('#id_crt_create_area').show("fast");
        document.body.style.cursor = 'default';
        reGenerateListArea ()
      }
      function update_create_shape(){
        var indexx = $('#id_crt_create_area').data('index');
        if(indexx == null || indexx == ""){
          return;
        }
        updateNewArea(indexx);
      }
      function remove_create_shape(){
        var indexx;
        var current_id = $('#id_crt_rand_id').val()
        for(var xi in listt_area){
          if(listt_area[xi]['rand_id'] == current_id){
            indexx = xi;
          }
        }
        if(indexx == null){
          console.log("no area editt")
          return;
        }
        $('#id_crt_create_area').data('index', indexx);
        new_area = {};
        listt_area[indexx]['shape'] = "";
        $('#id_crt_shape').val("");
        $('#id_btn_create_shape').prop('disabled',false);
        $('#id_cancel_create_shape').hide('fast');
        $('#id_crt_create_area').prop('disabled',false);
        $('#id_crt_create_area').show("fast");
        $('#id_crt_remove_area').hide();
        document.body.style.cursor = 'default';
        new_obj_area[listt_area[indexx].rand_id].remove() ;
        drawAreaLayer.draw();
      }
      function finish_create_shape(){
        var indexx;
        for(var xi in listt_area){
          if(listt_area[xi]['rand_id'] == new_area.rand_id){
            indexx = xi;
          }
        }
        if(indexx == null){
          console.log("no area editt")
          return;
        }
        // arrDrawLine
        listt_area[indexx]['finish'] = true;
        listt_area[indexx]['shape'] = arrDrawLine_obj;
        listt_area[indexx]['width'] =  new_obj_area[new_area.rand_id].height();
        listt_area[indexx]['length'] = new_obj_area[new_area.rand_id].width();
        $('#id_crt_rand_id').val(listt_area[indexx]['rand_id']);
        var position_px = arrDrawLine[0]+","+arrDrawLine[1];
        listt_area[indexx]['position_px'] = position_px;
        $('#id_crt_width').val(listt_area[indexx]['width']);
        $('#id_crt_height').val(listt_area[indexx]['height']);
        $('#id_crt_shape').val("Area_"+new_area.rand_id);
        $('#id_crt_create_area').data('index', indexx);
        arrDrawLine_obj = "";
        arrDrawLine = [];
        // changeTo Current 
        current_area = listt_area[indexx];
        new_area = {};
        $('#id_btn_create_shape').prop('disabled',false);
        $('#id_cancel_create_shape').hide('fast');
        $('#id_crt_create_area').hide();
        $('#id_crt_remove_area').show("fast");
        document.body.style.cursor = 'default';
      }
      function reGenerateRoom (radid = ""){
        var html = '';
        for(var x in gRoomBuilding){
          var ss = radid == gRoomBuilding[x]['radid']? "selected" : "";
          html += '<option '+ss+' value="'+gRoomBuilding[x]['radid']+'">'
          html += gRoomBuilding[x]['name']
          html += '</option>'
        }
        $('#id_crt_room').html(html);
        select_enable('.selectpickerr');
      }

      function reGenerateListArea (){
        var html = '';
        for(var x in listt_area){
            var r = listt_area[x];
            var ss = ""
            var rand_id = r.rand_id;
            html += '<li class="link-area " onclick="clickArea('+x+', $(this))" >'
            html += '<a  href="javascript:;" id="id_floor_list_'+rand_id+'">'+r['name'];
              html += '<div class="col-xs-4 align-right">\
                    <button onclick="remove_area('+x+')"  id="" class="btn  btn-danger btn-circle waves-effect waves-circle waves-float">\
                      <i class="material-icons font-bold ">remove</i>\
                    </button>\
                  </div>\
                  </a> ';
              html += '</li>'
        }
        $('#id_floor_area').html(html);
      }
      function clickArea(indexx = "", t){
        // var t = 
        $('.link-area').removeClass("active");
        t.addClass("active");

        var ddataArea = listt_area[indexx];
        // console.log(ddataArea)
        if(ddataArea == null){
          return;
        }
        $('#id_crt_name').val(ddataArea['name']);
        $('#id_crt_width').val( ddataArea['width']);
        $('#id_crt_height').val(ddataArea['length']);
        $('#id_crt_room_name').val(ddataArea['name']);
        if(ddataArea.shape != ""){
          $('#id_crt_create_area').data('index',indexx);
          $('#id_crt_shape').val("Area_"+ddataArea.rand_id);
          $('#id_btn_create_shape').prop('disabled',false);
          $('#id_cancel_create_shape').hide('fast');
          $('#id_crt_create_area').hide();
          $('#id_crt_remove_area').show("fast");
          document.body.style.cursor = 'default';
        }else{
          $('#id_crt_create_area').data('index',indexx);
          $('#id_btn_create_shape').prop('disabled',false);
          $('#id_cancel_create_shape').hide('fast');
          $('#id_crt_create_area').prop('disabled',false);
          $('#id_crt_create_area').show("fast");
          $('#id_crt_remove_area').hide();
          // new_obj_area[ddataArea.rand_id].remove() ;
        }
        reGenerateRoom(ddataArea.room_id);
      }
      function ocRoomData(){
        var room =  $('#id_crt_room');
        var nm = $('#id_crt_room option:selected').text();
        $('#id_crt_room_name').val(nm);
      }
      function closeDrawingMapping(){
          $('#frm_rightbar')[0].reset();
          $('.link-area').removeClass("active");
          $('#id_crt_create_area').data('index', "");
          // reGenerateRoom();
          document.body.style.cursor = 'default';
          $('#id_btn_create_shape').prop('disabled',false);
          $('#id_cancel_create_shape').hide('fast');
          $('#id_crt_create_area').prop('disabled',false);
          $('#id_crt_create_area').show("fast");
          $('#id_crt_remove_area').hide();
          cancel_create_shape();
          
      }
      function remove_area(indexx){
         var ddataArea = listt_area[indexx];
         new_area = {};
         new_obj_area[ddataArea.rand_id].remove();
         listt_area_delete.push(ddataArea);
         listt_area.splice(indexx-0,1);
         cancel_create_shape();
      }
      $('#id_btn_crt_close').click(function(e){
        closeDrawingMapping();
      })
      $('#frm_rightbar').submit(function(e){
        e.preventDefault();
        var indexx = $('#id_crt_create_area').data('index');
        // console.log(listt_area[indexx])
        if(listt_area[indexx] == null){
          return;
        }
        var ddataArea = listt_area[indexx];
        ddataArea['name'] = $('#id_crt_name').val();
        ddataArea['width'] = $('#id_crt_height').val();
        ddataArea['length'] = $('#id_crt_width').val();
        // ddataArea['height'] = $('#id_crt_height').val();
        ddataArea['room_id'] = $('#id_crt_room').val();
        ddataArea['room_name'] = $('#id_crt_room_name').val();
        $('#id_floor_list_'+ddataArea['rand_id']).html(ddataArea['name']);

      });
      function saveFloorArea(){
        $.ajax({
          url : bs+"beacon-floor-area-room-editor/floor-area/save",
          type : "POST",
          dataType: "json",
          data:  {
            listarea : listt_area,
            listarea_delete : listt_area_delete,
            floor : query['floor'],
            building : query['building'],
          },
          beforeSend: function(){
            loadingg('Please wait ! ', 'Loading . . . ')
            $('#id_loader').html('<div class="linePreloader"></div>');
          },
          success:function(data){
            swal.close();
             $('#id_loader').html('');

            if(data.status == "success"){
                 Swal.fire({
                        title:'Message',
                        text: data.msg,
                        type: "success"})
             
            }
          },
          error: errorAjax
        })
      }
    </script>

    </body>
</html>


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
           position: absolute;
           left: 20vw;
           bottom: 10px;
           width: 57vw;
           background-color: red;
           color: black;
           text-align: center;
        }
        .container{
            overflow: auto;
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
        <?php $this->load->view("Konva/navbar.konva.php", array("pagename"=>""));?>
    <!-- #Top Bar -->
    <section>
        <?php $this->load->view("Konva/sidebar.konva.php");?>
    </section>
    <section>
        <?php $this->load->view("Konva/rightbar.konva.php");?>
    </section>
    <section class="content">
        <div id="container"></div>
    </section>
    <div class="footer">
      <div class="row clearfix">
        <div class="col-xs-8"></div>
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
    <script>
      var width = window.innerWidth;
      var height = window.innerHeight;
      var increasePoint = 1;
      var increasePointNo = 0.1;
      var WIDTH = 1920;
      var HEIGHT = 1080;
      var NUMBER = 200;
      const PADDING = 0;

      var tween = null;

      var stage = new Konva.Stage({
        container: 'container',
        width: width,
        height: height,
      });

      var layer = new Konva.Layer();
      var scrollLayers = new Konva.Layer();
      var borderSelected = new Konva.Layer();
      stage.add(scrollLayers);
      stage.add(layer);
      stage.add(borderSelected);
      var verticalBar = new Konva.Rect({
        width: 10,
        height: 100,
        fill: 'grey',
        opacity: 0.8,
        x: stage.width() - PADDING - 10,
        y: PADDING,
        draggable: true,
        dragBoundFunc: function (pos) {
          pos.x = stage.width() - PADDING - 10;
          pos.y = Math.max(
            Math.min(pos.y, stage.height() - this.height() - PADDING),
            PADDING
          );
          return pos;
        },
      });
      scrollLayers.add(verticalBar);
       var horizontalBar = new Konva.Rect({
        width: 100,
        height: 10,
        fill: 'grey',
        opacity: 0.8,
        x: PADDING,
        y: stage.height() - PADDING - 10,
        draggable: true,
        dragBoundFunc: function (pos) {
          pos.x = Math.max(
            Math.min(pos.x, stage.width() - this.width() - PADDING),
            PADDING
          );
          pos.y = stage.height() - PADDING - 10;

          return pos;
        },
      });
      scrollLayers.add(horizontalBar);
      horizontalBar.on('dragmove', function () {
        // delta in %
        const availableWidth =
          stage.width() - PADDING * 2 - horizontalBar.width();
        var delta = (horizontalBar.x() - PADDING) / availableWidth;

        layer.x(-(WIDTH - stage.width()) * delta);
      });
      verticalBar.on('dragmove', function () {
        // delta in %
        const availableHeight =
          stage.height() - PADDING * 2 - verticalBar.height();
        var delta = (verticalBar.y() - PADDING) / availableHeight;

        layer.y(-(HEIGHT - stage.height()) * delta);
      });
      stage.on('wheel', function (e) {
        // prevent parent scrolling
        e.evt.preventDefault();
        const dx = e.evt.deltaX;
        const dy = e.evt.deltaY;

        const minX = -(WIDTH - stage.width());
        const maxX = 0;

        const x = Math.max(minX, Math.min(layer.x() - dx, maxX));
        const minY = -(HEIGHT - stage.height());
        const maxY = 0;
        const y = Math.max(minY, Math.min(layer.y() - dy, maxY));
        layer.position({ x, y });

        const availableHeight =
          stage.height() - PADDING * 2 - verticalBar.height();
        const vy =
          (layer.y() / (-HEIGHT + stage.height())) * availableHeight + PADDING;
        verticalBar.y(vy);

        const availableWidth =
          stage.width() - PADDING * 2 - horizontalBar.width();

        const hx =
          (layer.x() / (-WIDTH + stage.width())) * availableWidth + PADDING;
        horizontalBar.x(hx);
      });

      function zoomin(){
        increasePoint +=increasePointNo;
        layer.scale({
          x: increasePoint,
          y: increasePoint,
        });
      }
      function zoomout(){
        if(increasePoint <= 1){
            increasePoint = 1
        }else{
            increasePoint -=increasePointNo;
        }
        layer.scale({
          x: increasePoint,
          y: increasePoint,
        });
      }
      function shape_mouseover(evt){
        var shape = evt.target;
        shape.setAttr('oldFill', shape.fill());
        shape.fill('lime');
        document.body.style.cursor = 'pointer';
        // console.log(shape.x()-5,shape.y()-5);
        // console.log(shape.width()+5,shape.height()+);
         var rect1_hover = new Konva.Rect({
            x: shape.x()-10,
            y: shape.y()-10,
            width: shape.width()+20,
            height: shape.height()+20,
            strokeWidth: 1,
            fill: 'rgba(0,0,255,0.2)',
            stroke: 'rgba(0,0,255,1)',
            name: 'border',
            // draggable: true,
          });
         rect1_hover.on('mouseout', (evt)=>{
            var shape = evt.target;
            shape.fill(shape.getAttr('oldFill')); 
            document.body.style.cursor = 'default';
            var shapes = borderSelected.find('.border');
            shapes.filter((shape) =>{
                shape.destroy();
            });
        });
         borderSelected.add(rect1_hover);
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

      var rect1 = new Konva.Rect({
        x: 60,
        y: 60,
        width: 100,
        height: 90,
        fill: 'red',
        name: 'rect',
        draggable: true,
        strokeWidth: 4,
        stroke: 'white',
        shadowColor: 'rgba(0,0,255,0.2)',
        shadowBlur: 10,
        shadowOffset: { x: 0, y: 0 },
        shadowOpacity: 1,
      });
      layer.add(rect1);

      var rect2 = new Konva.Rect({
        x: 250,
        y: 100,
        width: 150,
        height: 90,
        fill: 'green',
        name: 'rect',
        draggable: true,
        strokeWidth: 4,
        stroke: 'white',
        shadowColor: 'rgba(0,0,255,0.2)',
        shadowBlur: 10,
        shadowOffset: { x: 0, y: 0 },
        shadowOpacity: 1,
      });
      layer.add(rect2);

      var tr = new Konva.Transformer();
      layer.add(tr);

      // by default select all shapes
      // tr.nodes([rect1, rect2]);

      // add a new feature, lets add ability to draw selection rectangle
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
      rect2.on('mouseover', shape_mouseover);
      rect2.on('mouseout', shape_mouseout);
      rect1.on('mouseover', shape_mouseover);
      rect1.on('mouseout', shape_mouseout);
      stage.on('mousemove touchmove', (e) => {
        // do nothing if we didn't start selection
        if (!selectionRectangle.visible()) {
          return;
        }
        e.evt.preventDefault();
        x2 = stage.getPointerPosition().x;
        y2 = stage.getPointerPosition().y;

        selectionRectangle.setAttrs({
          x: Math.min(x1, x2),
          y: Math.min(y1, y2),
          width: Math.abs(x2 - x1),
          height: Math.abs(y2 - y1),
        });
      });

      stage.on('mouseup touchend', (e) => {
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
          // if no key pressed and the node is not selected
          // select just one
          tr.nodes([e.target]);
        } else if (metaPressed && isSelected) {
          // if we pressed keys and node was selected
          // we need to remove it from selection:
          const nodes = tr.nodes().slice(); // use slice to have new copy of array
          // remove node from array
          nodes.splice(nodes.indexOf(e.target), 1);
          tr.nodes(nodes);
        } else if (metaPressed && !isSelected) {
          // add the node into selection
          const nodes = tr.nodes().concat([e.target]);
          tr.nodes(nodes);
        }
      });
    </script>
    </body>
</html>

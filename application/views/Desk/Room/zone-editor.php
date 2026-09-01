<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <title>Zone Editor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
     <link href="<?php echo base_url("assets/theme/plugins/bootstrap/css/bootstrap.css") ?>" rel="stylesheet">
     <link rel="stylesheet" href="<?php echo base_url("assets/theme/plugins/bs-select/select.css");?>">
    <link href="<?php echo base_url("assets/theme/css/style.css") ?>" rel="stylesheet">
    <!-- <link href="<?php echo base_url("assets/theme/css/themes/all-themes.css") ?>" rel="stylesheet" /> -->

    <style>
        body {
            background-color: #dddddd;
        }

        .card-body {
            overflow-y: scroll;
            -ms-overflow-style: none;
            scrollbar-width: none;
            padding: 10px;
        }

        .card-body::-webkit-scrollbar {
            display: none;
        }
        .card-body-form{
            padding: 15px;
        }

        .modal-right {
            position: fixed;
            top: 0;
            right: -100%;
            height: 100%;
            width: 30%;
            background-color: white;
            box-shadow: -5px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            transition: right 0.3s ease-in-out;
        }

        .modal-right.show {
            right: 0;
        }

        .create-button {
            position: absolute;
            bottom: 60px;
            right: 10px;
        }

        .list-group {
            margin-bottom: 5px;
        }

        .list-group-item .index {
            margin-right: 10px;
        }
        #id_listdeskpointer{
            height: 100%;
        }
        .card-header {
            padding: .75rem 1.25rem;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, .03);
            border-bottom: 1px solid rgba(0, 0, 0, .125);
        }
        .h5, h5 {
            font-size: 2.25rem;
        }
        .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
            margin-bottom: .5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        .circlePointer {
            background-color: #ff8200;
            height: 30px;
            width: 30px;
            border-radius: 50%;
            border:3px solid black ;
            position:absolute;
            cursor:pointer;
            font: 10px, sans-serif;
            color: white ;
            line-height: 25px;
            text-align: center;
        }

        .circlePointer.pointerSelected {
            background-color: #3f62cc;
        }
        .circlePointer:hover{
            background-color: #703d08;
            border:1px solid black ;
        }

        .header-fix{
            width: 98% ;
            position: fixed;
            z-index: 99999;
            top: 0px;
            visibility: hidden;
        }
        .requiredForm{
            color: red;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header-fix" id="id_alert_change_pos" >
            <div class="alert alert-danger">
                <button type="button" class="close" onclick="closeAlertPosition()" aria-label="Close"><span aria-hidden="true">×</span></button>
                <strong>You change desk position!</strong> click button save to update &nbsp;&nbsp;&nbsp; <button class="btn btn-info " type="button" onclick="savePosition()">Save & Update Position</button>
            </div>
        </div>
        <div class="row" style="margin: 10px">
            <div class="col-sm-8">
                <div class="card" style=" position: relative;">
                    <div class="card-body">
                        <div id="id_template_area" style=" ">
                            <div style="height: 0px"></div>
                            <div id="id_map_area" style="background-color:white;width: 100%;">
                                <div class="booth" id="id_booth"></div>
                                <div>
                                    <img id="id_image_map" src="<?= base_url()?>assets/file/room/1942081.png">
                                </div>
                            </div>
                            <!-- <img id="id_image_map" src="denah-baru.png" style="width: 100%;" alt=""> -->

                        </div>
                        
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card" style="height: 95vh;">
                    <div class="card-header">
                        <div class="row clearfix">
                             <div class="col-xs-12 col-sm-8">
                                <h4>Desk Editor - <span id="id_roomName"></span> 
                                    
                                </h4>
                             </div>
                             <div class="col-xs-12 col-sm-4">
                                <button class="btn btn-success" onclick="backDesk()">Back</button>
                                <button class="btn btn-primary pull-right" onclick="createMember()">Create</button>
                             </div>
                        </div>
                        

                    </div>
                    <div class="card-body" style="overflow-y: scroll;" id="id_listdeskpointer">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Modal -->
    <div id="sidebarModal" class="modal-right">
        <div class="p-4">
            <div 
            style="padding-left: 10px;padding-right: 10px; display: flex; justify-content: space-between; align-items: center"
            >
                <h5 id="titleModal">Update IT Staff</h5>
                <div onclick="closeSidebar()" class="close" style="cursor: pointer;">
                    &times;
                </div>
            </div>
            <hr>
            <form class="card-body-form" id="form">
                <label for="controller">Desk Zone <font class="requiredForm">*</font></label>
                <div class="form-group">
                    <select required id="zone" class="form-control" title="Desk Zone"></select>
                </div>
                <label for="number">Desk Number <font class="requiredForm">*</font></label>
                <div class="form-group">
                    <div class="form-line">
                        <input required onkeyup="onChangeDeskNumber()" maxlength="3" type="text" id="number" class="form-control" placeholder="Enter Member">
                    </div>
                </div>
                <label for="controller">Controller <font class="requiredForm">*</font></label>
                <div class="form-group">
                    <select required onchange="onChangeController()" id="controller" class="form-control" title="Desk Controller"></select>
                </div>
                <label for="socket">Socket <font class="requiredForm">*</font></label>
                <div class="form-group">
                    <select required id="socket" class="form-control" title="Socket"></select>
                   
                    <div class="form-line">
                        <input type="text" id="coordinate" placeholder="">

                    </div>
                </div>
                
                <!-- </div> -->
                &nbsp;
                <button id="id_btn_deleteform" type="button" class="btn btn-danger delete-zone-update"   data-deskid="" >Delete</button>
                <button type="submit" id="id_btn_saveform"  class="btn btn-success pull-right">Save</button>
                
                

                
            </form>
        </div>
    </div>
    <input type="hidden" name="" id="id_editor_old_controller">
    <input type="hidden" name="" id="id_editor_old_socket">

    <textarea style="display: none;" id="id_source"><?= $source?></textarea>
    <!-- Jquery Core Js -->
    <?php $this->load->view("_partials/js_dashboard.php");?>
    <!-- Bootstrap Core Js -->
    <!-- Slimscroll Plugin Js -->
    <script src="<?= base_url()?>assets/theme/plugins/sweatalert2/sweetalert2.all.min.js"></script>
    <script src="<?php echo base_url("assets/external/jquery-ui/jquery.ui.js");?>"></script>
    <script>
        var bs = "<?= base_url()?>";
        var source = JSON.parse($('#id_source').val());
        var linkmap = `${bs}assets/file/room/${source.map}`;
        var zoneRoomList = source.zoneRoom;
        var listcontroller = source.controller;
        var listsocket = [];
        var listposmap = {
            'landscape' : {
                width :960 ,
                height:540,
            },
            'potrait' : {
                width :540,
                height:640,
            }
        } 
        var selectedPointerId = "";
        var selectedZone = {};
        var addheightbgwhite = 20;
        var posmap = listposmap[source.posmap];
        var listdesk = [];
        var listdeskdragpost = [];
        var action = "";
        var deskId = "";
        var deskEdit = {};



        // var listdeskNew 


        function select_enable(){
            // $('select').selectpicker("destroy");
            $('select').selectpicker("refresh");
            $('select').selectpicker("initialize");
        }
        function enable_datetimepicker(){
            // $('.timepicker').bootstrapMaterialDatePicker({
            //     format: 'HH:mm',
            //     clearButton: true,
            //     date: false
            // });
        }
        function generateDeskController(value = ""){
            var html_controller = '';
            for(var x in listcontroller){
                var selected = value == listcontroller[x].id ? 'selected':'';
                html_controller += `<option ${selected} value="${listcontroller[x].id}" >${listcontroller[x].name}</option>`;
            }
            return html_controller;
        }

        function generateDeskZone(value = ""){
            var html = '';
            for(var x in zoneRoomList){
                var selected = value == zoneRoomList[x].zone_id ? 'selected':'';
                html += `<option ${selected} value="${zoneRoomList[x].zone_id}" >${zoneRoomList[x].name}</option>`;
            }
            return html;
        }

        function generateDeskSocket(value = ""){
            var html = '<option value=""></option>';
            for(var x in listsocket){
                var selected = value == listsocket[x].socket ? 'selected':'';
                var assigned = listsocket[x].block_number == null ? "":" - "+ listsocket[x].room_name + " Desk " + listsocket[x].block_number ;
                var disabled = listsocket[x].block_number == null ? "":"disabled" ;
                html += `<option ${disabled} ${selected} value="${listsocket[x].socket}" >socket ${listsocket[x].socket}${assigned}</option>`;
            }
            return html;
        }
        // ready
        $(function(){

            initMap();
            getPointerDesk();
        });

        function backDesk(){
            window.location.href = bs +"deskroom";
        }
        function resetModal() {
            action = "";
            deskId = "";
            $('#number').val('');
            $('#controller').val('');
            $('#socket').val('');
            $('#coordinate').val('');

            $('#id_editor_old_controller').val(''); 
            $('#id_editor_old_socket').val(''); 

            $('.deskpointernew').remove();
            selectedPointerId = "";
        }
        // INITIAL MAP

        function initMap(){
            $('#id_roomName').text(source.room.name)
            var harea = posmap.height+addheightbgwhite;
            $('#id_map_area').css({
                'height': harea+"px",
            });
            $('#id_image_map').css({
                'height': posmap.height+"px",
                'width': posmap.width+"px",
            });
            $('#id_image_map').attr('src', linkmap);
            if(zoneRoomList.length > 0){
                selectedZone = zoneRoomList[0];
            }
            
        }


        function getPointerDesk(){
            var id = source.room_id;
            listdeskdragpost = JSON.parse("[]");
            // var name = t.data('name');
            var name = "";
            var pdata = {
                'desk_room_id' : source.room_id,
            }
            // assetsImageUrl = "";
            $.ajax({
                url : bs+"deskroom/get/editor-data",
                type : "POST",
                data:pdata,
                dataType: "json",
                beforeSend: function(){
                    loadingg('Please wait ! ', 'Loading . . . ');
                    // $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    swal.close();
                    if(data.status == "success"){
                        var col = data.collection;
                        listdesk = JSON.parse(JSON.stringify(col));
                        var html = ``;
                        var n = 0;
                        for(var x in col){
                            n++;
                            var index = x;
                            html += createCardDesk(index, n,col[x]);

                            onGenerateBoot(index,col[x]);
                        }
                        $('#id_listdeskpointer').html(html);
                       
                    }else{
                    }
                    $('#id_loader').html('');
                    
                },
                error: errorAjax
            });
        }



        function createCardDesk(index, no = 0, item){
            if(item == null){
                return '';
            }
            var ct = `Controller: ${item.controller_name} | ${item.zone_name}`;
            var deskname = `Desk ${item.block_number}`;
            var id = item.id;
            return `<div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div style="font-weight: bold;">
                                        <span class="index">${no}.</span>${deskname}
                                    </div>
                                    <div class="text-muted">${ct}</div>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary mr-1" title="Update"
                                        onclick="updateMemberByZone('${index}')">
                                        &#9998;
                                    </button>
                                    <button class="btn btn-sm btn-danger text-white" title="Delete"
                                        onclick="deleteMemberZone('${index}')">
                                        &#128465;
                                    </button>
                                </div>
                            </div>
                        </div>`;
        }




        function onChangeDeskNumber(){
            var v = $('#number').val();
            $('#'+selectedPointerId).text(v);
        }

        async function onChangeController(){
           var ctrlvalue =  $('#controller').val();
           if(ctrlvalue == ""){
            $('#socket').html('');
            return;
           }
            var err = false;
            loadingg('Please wait ! ', 'Loading . . . ');
            var ctrl = null;
            try{
                ctrl = await getSocketController('');
            }catch(e){
                console.log(e)
                err = true;
            }
            swal.close();
            if(err == true){
                $('#socket').html('');
                return;
            }
            if(ctrl.status !="success"){
                $('#socket').html('');
                return;
            }
            listsocket = JSON.parse(JSON.stringify(ctrl.collection));
            var html_socket = generateDeskSocket("");
            $('#socket').html(html_socket);
            // console.log(listsocket)
            select_enable()
        }

        function onBootAdd(){
            var id = makeid(32);
            selectedPointerId = id;
            var pointer_y = (10 - 0);
            var pointer_x = (40 - 0);
            var cord = `${pointer_x},${pointer_y}`;
            $('#coordinate').val(`${cord}`);
             pointer_y -= 10;
             pointer_x -= 40;
            $('#id_booth').append(`<div 
                style="top:${pointer_y}px;left:${pointer_x}px;" 
                class="circlePointer pointerSelected deskpointernew" id="${id}" ></div>`);
            dragBooth(id);
        }

        function onGenerateBoot(index, item){
            var id = item.id;
            var number = item.block_number;
            // selectedPointerId = id;
            var pointer_y = (item.pointer_desk_y - 0) - 10;
            var pointer_x = (item.pointer_desk_x - 0) -40;
            $('#id_booth').append(`<div 
                data-index="${index}"
                style="top:${pointer_y}px;left:${pointer_x}px;" 
                class="circlePointer deskpointer" id="${id}" >${number}</div>`);
            dragBoothUpdate(id);
        }

        function dragBooth(objectId){
            $('#'+objectId).draggable({
                drag: function(){
                    var offset = $(this).offset();
                    var index = $(this).data('index');
                    // get_coordinate_x = $(this).css("left").replace("px","");
                    // get_coordinane_y = $(this).css("top").replace("px","");
                    var cord = `${offset.left},${offset.top}`;
                    $('#coordinate').val(`${cord}`);

                    
                },
                stop: function() {
                    console.log("as")
                },
            });
        }

        function dragBoothUpdate(objectId){
            $('#'+objectId).draggable({
                drag: function(){
                    var index = $(this).data('index');
                    $('#id_alert_change_pos').css('visibility','visible')
                    var offset = $(this).offset();
                    // get_coordinate_x = $(this).css("left").replace("px","");
                    // get_coordinane_y = $(this).css("top").replace("px","");
                    var cord = `${offset.left},${offset.top}`;
                    $('#coordinate').val(`${cord}`);
                    if(listdeskdragpost.includes(index) == false){
                        listdeskdragpost.push(index)
                    }
                    listdesk[index].pointer_desk_x = offset.left;
                    listdesk[index].pointer_desk_y = offset.top;
                },
                stop: function() {
                    console.log("as")
                },
            });
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


        function createMember() {
            resetModal();
            var zone = `Create - New Desk `;
            $('#titleModal').text(zone);
            document.getElementById('sidebarModal').classList.add('show');
            var html_controller = generateDeskController("");
            var html_socket= generateDeskSocket("");
            var html_zone= generateDeskZone("");

            
            $('#zone').html(html_zone);
            $('#controller').html(html_controller);
            $('#socket').html(html_socket);
            select_enable()
            onBootAdd()
            action = "add";
            $('#id_btn_saveform').html('Save')
        }
        async function updateMemberByZone(index) { // 
            var item = listdesk[index];
            deskEdit = item;
            resetModal();
            $('#id_editor_old_controller').val(item.controller_id); 
            $('#id_editor_old_socket').val(item.socket); 
            var zone = `Update - Desk ${item.block_number} `
            $('#titleModal').text(zone);
            $('#number').val(item.block_number);
            $('#coordinate').val(item.pointer_desk_x + ","+item.pointer_desk_y);
            // $('#controller').val(item.controller_name);
            // $('#socket').val(item.socket);

            var html_controller = generateDeskController(item.controller_id);
            $('#controller').html(html_controller);

            var err = false;
            loadingg('Please wait ! ', 'Loading . . . ');
            var ctrl = null;
            try{
                ctrl = await getSocketController(item.socket-0);
            }catch(e){
                console.log(e)
                err = true;
            }
            swal.close();
            if(err == true){
                $('#socket').html('');
                return;
            }
            if(ctrl.status !="success"){
                $('#socket').html('');
                return;
            }
            listsocket = JSON.parse(JSON.stringify(ctrl.collection));
            
            // console.log(ctrl);

            var html_socket= generateDeskSocket(item.socket);
            var html_zone= generateDeskZone(item.zone_id);

            
            $('#zone').html(html_zone);
            // 
            $('#socket').html(html_socket);

            $('#id_btn_deleteform').prop('data-index',"123");
            document.getElementById('sidebarModal').classList.add('show');
            $('#id_btn_saveform').html('Update')
            deskId = item.desk_id;
            action = "update";
            select_enable()
        }

        async function getSocketController(value = ""){
            var iVal = $('#controller').val();
            if(iVal == ""){
                
                return null;
            }
            var old_c = $('#id_editor_old_controller').val(); 
            var old_s = $('#id_editor_old_socket').val(); 
            
            return $.ajax({
                url : bs+"deskroom/get/editor-controller-socket/"+iVal+"?value="+value,
                type : "GET",
                dataType: "json",
                beforeSend: function(){
                    loadingg('Please wait ! ', 'Loading . . . ')
                    // $('#id_loader').html('<div class="linePreloader"></div>');
                },
                
                error: errorAjax
            });

            
        }
        $('#id_btn_deleteform').on('click', function(){
            if(action == "add"){
                closeSidebar()
                return
            }
            console.log($(this).data())
            deleteMemberZone(null,deskEdit )
        })

        function deleteMemberZone(index = "", datarex = []) {
            if(index != null){
                var item = listdesk[index] //index

            }else{
                var item = datarex //index

            }

            var form = new FormData();
            form.append('id', item.desk_id);
            form.append('socket', item.socket);
            form.append('number', item.block_number);
            form.append('controller_id', item.controller_id);
            form.append('room', source.room_id);

            Swal.fire({
                title:'Confirmation',
                text: "You will lose the data desk !",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete !',
                cancelButtonText: 'Cancel !',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                            url : bs+"deskroom/post/delete-editor-zone",
                            type: "POST",
                            data : form,
                            processData: false,
                            contentType: false,
                            dataType :"json",
                            beforeSend: function(){
                                loadingg('Please wait ! ', 'Loading . . . ')
                            },
                            success:function(data){
                                swal.close()
                                getPointerDesk();
                                closeSidebar();
                                swalShowNotification('alert-success', "Success delete desk " ,'top','center')
                            },
                            error: errorAjax,
                        })
                }
            })
            // alert('delete member zone ' + zoneId);
        }

        function closeSidebar() {
            resetModal();

            document.getElementById('sidebarModal').classList.remove('show');
        }

        function getListupdatePosition(){
            var data = [];
            for(var x in listdeskdragpost){
                var n = listdeskdragpost[x];
                data.push(listdesk[n])

            }

            return data;
        }
        function closeAlertPosition(){
            $('#id_alert_change_pos').css('visibility','hidden')
        }
        $('#form').submit(function(e){
            e.preventDefault();
            var f = $(this).serialize();
            var form = {
                desk_id     : "",
                action      : action,
                socket      : $('#socket').val(),
                number      : $('#number').val(),
                pointer     : $('#coordinate').val(),
                controller  : $('#controller').val(),
                zone        : $('#zone').val(),
                room        : source.room_id,
            };
            if(action == "update" || action == "edit"){
                form['desk_id'] = deskId;
                form['old_socket'] = deskEdit.socket;
            }
            $.ajax({
                url : bs+"deskroom/post/create-editor-zone",
                type : "POST",
                data:form,
                dataType: "json",
                beforeSend: function(){
                    loadingg('Please wait ! ', 'Loading . . . ');
                    // $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    swal.close();
                    if(data.status == "success"){
                        closeSidebar();
                        $('#id_alert_change_pos').css('visibility','hidden')
                        swalShowNotification('alert-success', "Success save new desk " ,'top','center')
                        getPointerDesk();
                    }else{
                    }
                    $('#id_loader').html('');
                    
                },
                error: errorAjax
            })
        })
        function savePosition(){
            var f = getListupdatePosition();
            $.ajax({
                url : bs+"deskroom/save/editor-data-position",
                type : "POST",
                data:{
                    position : JSON.stringify(f), 
                },
                dataType: "json",
                beforeSend: function(){
                    loadingg('Please wait ! ', 'Loading . . . ');
                    // $('#id_loader').html('<div class="linePreloader"></div>');
                },
                success:function(data){
                    swal.close();
                    if(data.status == "success"){
                        closeSidebar();
                        $('#id_alert_change_pos').css('visibility','hidden')
                        swalShowNotification('alert-success', "Success save position desk " ,'top','center')
                    }else{
                    }
                    $('#id_loader').html('');
                    
                },
                error: errorAjax
            })
        }


        // $('.delete-zone-update').click(function () {
        //     // let zoneId = $(this).data('zoneid');

        //     // if (zoneId != 0) {
        //     //     alert('Delete zone update ' + zoneId);
        //     // }
        // })

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
</body>

</html>

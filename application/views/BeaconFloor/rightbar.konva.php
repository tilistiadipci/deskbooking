<aside id="" class="right-sidebar open">
    
    <div class="container">
        <br>
        <div class="row clearfix">
            <div class="col-xs-12">
                <form id="frm_rightbar">
                                <label for="">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="name" id="id_crt_name" required=""  class="form-control" placeholder="Name">
                                        <input type="hidden" autocomplete="off" name="rand_id" id="id_crt_rand_id" required=""  class="form-control">
                                    </div>
                                </div>
                                <label for="">Width</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="width" id="id_crt_width" required=""  class="form-control" placeholder="0">
                                    </div>
                                </div>
                                <label for="">Height</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" autocomplete="off" name="height" id="id_crt_height" required=""  class="form-control" placeholder="0">
                                    </div>
                                </div>
                                
                                
                                
                                <label for="">Room</label>
                                <div class="form-group">
                                    <select onchange="ocRoomData()" title="Choose one of the room..." name="room" id="id_crt_room" class="form-control selectpickerr "></select>
                                    <input type="hidden" id="id_crt_room_name"  >
                                </div>
                                <hr>
                                <label for="">Area</label>
                                
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <a onclick="remove_create_shape()" style="display:none;" id="id_crt_remove_area" class="waves-effect waves-circle ">
                                            <i class="material-icons font-bold col-red">remove_circle</i>
                                        </a>
                                        <a data-index="" onclick="update_create_shape()" id="id_crt_create_area" class="waves-effect waves-circle ">
                                            <i class="material-icons font-bold col-blue">add_circle</i>
                                        </a>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" disabled class="form-control" id="id_crt_shape" placeholder="Shape">
                                    </div>
                                </div>

                                <hr>

                                <button type="button" id="id_btn_crt_close" class="btn btn-danger m-t-15 waves-effect">Close</button>
                                <button type="submit" id="id_btn_crt_submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
                            </form>
            </div>
        </div>
    </div>
    
</aside>


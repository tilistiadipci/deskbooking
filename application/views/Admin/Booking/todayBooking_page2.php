<div class="card">
    <div class="header">
        <div class="row clearfix">
            <div class="col-xs-12">
                <h2>Room Book Form</h2>
            </div>
        </div>
    </div>
    <div class="body">
        <div class="row clearfix">
            <div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
                <button onclick="onButtonBack()" type="button" class="btn btn-block bg-red btn-lg waves-effect">
                    BACK
                </button>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 align-middle" id="id_frm_crt_name">
            </div>
        </div>
        <div class="row clearfix" style="min-height: 500px;overflow-y: scroll;background: #fff;">
            <!--START FORM BOOKING -->
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <!-- DATE -->
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <b class="lbl_text">SUBJECT MEETING</b> <b style="color:red">*</b>
                        <div class="input-group">
                            <div class="form-line">
                                <input autocomplete="off" type="hidden" id="id_frm_crt_type_room">
                                <input autocomplete="off" type="text" class="form-control" id="id_frm_crt_title" placeholder="Subject meeting ">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                        <b class="lbl_text" style="color:transparent;"> *</b>
                        <div class="input-group">
                            <select data-type="" onchange="setSelectedTimeCrtBookingInside($(this))" data-size="5" title="Choose the Time..." onchange="s($(this))" id="id_frm_crt_timestart" class="form-control show-tick" data-live-search="true">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 align-right">
                        <b class="lbl_text" style="color:transparent;"> *</b>
                        <div class="input-group">
                            <select data-type="end" onchange="setSelectedTimeCrtBookingInside($(this))" data-size="5" title="Choose the Time..." onchange="s($(this))" id="id_frm_crt_timeend" class="form-control show-tick" data-live-search="true">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <b class="lbl_text">ORGANIZER(HOST)</b> <b style="color:red">*</b>
                        <div class="input-group">
                            <select data-size="5" title="Choose the organizer..." onchange="changePIC($(this))" id="id_frm_crt_pic" class="form-control show-tick" data-live-search="true">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <b class="lbl_text">DEPARTMENT</b> <b style="color:red">*</b>
                        <div class="input-group">
                            <select data-size="5" title="Choose the Department..." onchange="changeALOCATION($(this))" id="id_frm_crt_alocation" class="form-control show-tick" data-live-search="true">
                                <option value=""></option>
                            </select>
                            <!-- <p >Select pic to open the alocation in charge</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <b class="lbl_text">MEETING NOTE</b>
                        <div class="input-group">
                            <div class="form-line">
                                <textarea class="form-control" id="id_frm_crt_note" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <b class="lbl_text">LINK MEETING</b>
                        <div class="input-group">
                            <div class="form-line">
                                <input autocomplete="off" type="text" class="form-control" id="id_frm_crt_external_link" placeholder="Link Meeting, Ex. https://goo.gl/maps/XXXXXXXX ">
                            </div>
                            <!-- <p >Select pic to open the alocation in charge</p> -->
                        </div>
                    </div>
                </div>
                <!-- TIME -->
                <div class="row clearfix">
                </div>
                <!-- Merger & vip -->
                <div class="row clearfix">
                    <div class="col-xs-6" id="id_area_vip" style="display :none;">
                        <b class="lbl_text">VIP MEETING </b> <b style="color:red">*</b>
                        <div class="input-group">
                            <select disabled title="Choose the Meeting VIP..." id="id_frm_crt_vip_meeting" class="form-control show-tick">
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6" id="id_area_category" style="display :none;">
                        <b class="lbl_text">MEETING CATEGORY </b> <b style="color:red">*</b>
                        <div class="input-group">
                            <select disabled title="Choose the Category" id="id_frm_crt_category" class="form-control show-tick">
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Reservation Cost -->
                <div class="row clearfix" id="id_frm_crt_cost_area" style="display:none;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="" class="lbl_text">RESERVATION COST <u id="id_frm_crt_cost"></u></label>
                    </div>
                </div>
                <!-- <hr> -->
                <!-- <u></u> -->
                <!--  -->
                
            </div>
            <!-- END FORM BOOKING -->
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="row clearfix">
                    <div class="col-xs-6" id="id_area_merge_room">
                        <b class="lbl_text">MERGE ROOM </b> <b style="color:red">*</b>
                        <div class="input-group">
                            <select data-size="4" title="Choose the room..." data-actions-box="true" onchange="changeMergeRoom($(this))" multiple id="id_frm_crt_merge_room" class="form-control show-tick" data-live-search="true">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--START  ATTENDEESS  -->
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="row clearfix">
                            <div class="col-xs-12">
                                <b class="lbl_text">INTERNAL ATTENDEES</b>
                                <div class="input-group">
                                    <select data-size="4" title="Choose the attendees..." data-actions-box="true" data-selected-text-format="count > 0" multiple id="id_frm_crt_participant" class="form-control show-tick" data-live-search="true">
                                    </select>
                                    <span class="input-group-addon">
                                        <button onclick="clickPartisipantAdd($(this))" type="button" class="btn bg-blue waves-effect">
                                            <i class="material-icons">add</i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div style="height: 150px;overflow-y: scroll;;background: #fff;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-bordered" style="border-collapse: collapse;border-spacing: 0;" id="id_list_tbl_participant">
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <b class="lbl_text">EXTERNAL ATTENDEES</b>
                                <div class="input-group">
                                    <button onclick="clickPartisipantManualOpen()" type="button" class="btn bg-blue  btn-lg waves-effect">
                                        ADD MANUAL
                                    </button>
                                </div>
                            </div>
                            <div style="height: 150px;overflow-y: scroll;background: #fff;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-bordered table-hover " style="border-collapse: collapse;border-spacing: 0;" id="id_list_tbl_participant_manual">
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--END  ATTENDEESS  -->
                <!--START  PANTRY  -->
                <div class="row clearfix" id="id_pantry_area" >
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <b class="lbl_text">PANTRY ORDER </b>
                        <div class="input-group">
                            <select data-size="4" onchange="oncPantry()" id="id_frm_crt_pantry" class="form-control show-tick" data-live-search="true">
                                <option value="">C H O O S E</option>
                            </select>
                            <span class="input-group-addon">
                            </span>
                        </div>
                        <hr>
                        <div style="background: #fff;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <table class="table table-bordered table-hover" id="id_list_tbl_patry">
                                <thead>
                                    <th>#</th>
                                    <th style="width: 100px !important;">Name</th>
                                    <th style="width: 100px !important;">Qty</th>
                                    <th style="width: 100px !important;">Note</th>
                                    <th></th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--END  PANTRY  -->
                <!-- <img class="img-booking2" src="" alt="" id="id_frm_crt_image"> -->
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button id="id_btn_crt_submit_booking" style="padding: 5px !important; font-size: 22px;font-weight: bold;" onclick="clickSubmitCrtBooking($(this))" type="button" class="btn bg-red btn-block waves-effect">
                    SUBMIT BOOKING
                </button>
            </div>
        </div>
    </div>
</div>
</div>
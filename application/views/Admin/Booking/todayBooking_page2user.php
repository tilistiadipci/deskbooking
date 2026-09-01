                                    <div class="row clearfix" >
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                         
                                          <button onclick="onButtonBack()" type="button" class="btn bg-red btn-lg waves-effect">
                                            BACK
                                          </button>
                                        </div>
                                       
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-control-label">
                                            <img class="img-booking2" src="" alt="" id="id_frm_crt_image">
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                          <!-- DATE -->
                                          <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                              <b class="lbl_text">SUBJECT MEETING</b> <b style="color:red">*</b>
                                              <div class="input-group">
                                                  <div class="form-line">
                                                      <input autocomplete="off" type="hidden"  id="id_frm_crt_type_room" >
                                                      <input autocomplete="off" type="text"  class="form-control" id="id_frm_crt_title" placeholder="Title of meeting ">
                                                  </div>
                                                 
                                              </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <b class="lbl_text" >PERSON IN CHARGE(PIC/HOST)</b> <b style="color:red">*</b>
                                              <div class="input-group">
                                                  <select onchange="changePIC($(this))" id="id_frm_crt_pic" class="form-control show-tick" data-live-search="true" >
                                                    <option value=""></option>
                                                  </select>
                                              </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <b class="lbl_text">DEPARTMENT IN CHARGE</b> <b style="color:red">*</b>
                                              <div class="input-group">
                                                  <select  onchange="changeALOCATION($(this))" id="id_frm_crt_alocation" class="form-control show-tick" data-live-search="true" >
                                                    <option value=""></option>
                                                  </select>
                                                  <!-- <p >Select pic to open the alocation in charge</p> -->
                                              </div>
                                            </div>
                                             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <b class="lbl_text">NOTE MEETING</b> 
                                              <div class="input-group">
                                                  <div class="form-line">
                                                    <textarea  class="form-control" id="id_frm_crt_note" rows="1"></textarea>
                                                  </div>
                                                 
                                              </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <b class="lbl_text">LINK MEETING</b> 
                                              <div class="input-group">
                                                <div class="form-line">
                                                   <input autocomplete="off" type="text"  class="form-control" id="id_frm_crt_external_link" placeholder="Link Meeting, Ex. https://goo.gl/maps/XXXXXXXX ">
                                                </div>
                                                 
                                                  <!-- <p >Select pic to open the alocation in charge</p> -->
                                              </div>
                                            </div>
                                          </div>
                                          </div>
                                          <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                              <b for="" id="id_frm_crt_name" style="font-size: 24px;"> </b>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                              <div class="input-group">
                                                  <span class="input-group-addon">
                                                      <i class="material-icons">date_range</i>
                                                  </span>
                                                  <label class="lbl_text" for="" id="id_frm_crt_date"></label>
                                              </div>
                                            </div>
                                          </div>
                                          <!-- TIME -->
                                          <div class="row clearfix">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <!-- <div class="input-group">
                                                  <label class="lbl_text" for="">START  &nbsp; &nbsp;</label>
                                                  <button data-type="start" data-id="id_frm_crt_time_start" onclick="openAlertPilihCrt($(this))" type="button" class="btn btn-default btn-sm waves-effect">
                                                    <b id="id_frm_crt_time_start" class="lbl_text"></b> <span class="caret"> &nbsp;</span>
                                                  </button>
                                              </div> -->
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 align-right">
                                              <!-- <div class="input-group align-right">
                                                  <label class="lbl_text" for="">END  &nbsp; &nbsp;</label>
                                                  <button onclick="openAlertPilihCrt($(this))" data-type="end" data-id="id_frm_crt_time_end" type="button" class="btn btn-default btn-sm waves-effect">
                                                    <b id="id_frm_crt_time_end" class="lbl_text"></b> <span class="caret"> &nbsp;</span>
                                                  </button>
                                                  
                                              </div> -->
                                            </div>
                                          </div>
                                          <div class="row clearfix" id="id_area_merge_room">
                                             <div class="col-xs-12">
                                                <b class="lbl_text">Merge Room</b> <b style="color:red">*</b>
                                                <div class="input-group">
                                                  <select  onchange="changeMergeRoom($(this))" multiple id="id_frm_crt_merge_room" class="form-control show-tick" data-live-search="true" >
                                                      <option value=""></option>
                                                  </select>
                                                </div>
                                                
                                             </div>
                                          </div>
                                          <!-- Reservation Cost -->
                                          <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                              <label for="" class="lbl_text" >RESERVATION COST <u id="id_frm_crt_cost"></u></label>
                                            </div>
                                            
                                          </div>
                                          <hr>
                                          <!-- <u></u> -->
                                          <!--  -->
                                          <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                              <div class="input-group">
                                                  <span class="input-group-addon">
                                                      <i class="material-icons">place</i>
                                                  </span>
                                                  <p id="id_frm_crt_location" class="lbl_text"></p>
                                              </div>
                                              <div class="input-group">
                                                  <span class="input-group-addon">
                                                      <i class="material-icons">loyalty</i>
                                                  </span>
                                                  <p  for="" id="id_frm_crt_cost_room" class="lbl_text"></p>
                                              </div>
                                              <div class="input-group">
                                                  <span class="input-group-addon">
                                                      <i class="material-icons">person</i>
                                                  </span>
                                                  <label for="" id="id_frm_crt_capacity" class="lbl_text"></label>
                                              </div>
                                              <div class="input-group">
                                                  <span class="input-group-addon">
                                                      <i class="material-icons">add</i>
                                                  </span>
                                                  <p  for="" id="id_frm_crt_facility" class="lbl_text"></p>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row clearfix">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                              <div class="row clearfix">
                                                <div class="col-xs-12">
                                                  <b class="lbl_text">INTERNAL ATTENDEES</b>
                                                  <div class="input-group">
                                                      <select 
                                                      title="Choose the attendees..."
                                                      data-actions-box="true"
                                                      data-selected-text-format="count > 1"
                                                      multiple 
                                                      id="id_frm_crt_participant" 
                                                      class="form-control show-tick" data-live-search="true" >
                                                      </select>
                                                      <span class="input-group-addon">
                                                          <button  onclick ="clickPartisipantAdd($(this))" type="button" class="btn bg-blue btn-sm waves-effect">
                                                            <i class="material-icons"  >add</i> 
                                                          </button>
                                                      </span>
                                                  </div>
                                                </div>
                                                <div style="height: 150px;overflow-y: scroll;;background: #fff;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                                  <table class="table table-bordered" id="id_list_tbl_participant">
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
                                                    <button  onclick ="clickPartisipantManualOpen()" type="button" class="btn bg-blue  btn-lg waves-effect">
                                                        ADD PARTICIPANT MANUAL
                                                    </button>
                                                  </div>
                                                </div>
                                                <div style="height: 150px;overflow-y: scroll;background: #fff;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                                  <table class="table table-bordered table-hover" id="id_list_tbl_participant_manual">
                                                    <tbody></tbody>
                                                  </table>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          
                                            <!--  -->
                                            
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="id_pantry_area" style="display: none;">
                                              <b class="lbl_text">PANTRY ORDER</b>
                                              <div class="input-group">
                                                  <select onchange ="oncPantry()" id="id_frm_crt_pantry" class="form-control show-tick" data-live-search="true" >
                                                      <option value="">C H O O S E</option>
                                                  </select>
                                                  <span class="input-group-addon">
                                                  </span>
                                              </div>
                                              <hr>
                                              <div style="background: #fff;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                                <table class="table table-bordered table-hover" id="id_list_tbl_patry">
                                                  <thead>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Qty</th>
                                                    <th>Note</th>
                                                    <th></th>
                                                  </thead>
                                                  <tbody></tbody>
                                                </table>
                                              </div>
                                            </div>
                                          </div>

                                          <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                              <button  id="id_btn_crt_submit_booking" style="padding: 15px !important; font-size: 22px;"  onclick ="clickSubmitCrtBooking($(this))" type="button" class="btn bg-red btn-block waves-effect">
                                                SUBMIT BOOKING
                                              </button>
                                            </div>
                                          </div>
                                        </div>
                                    </div> 
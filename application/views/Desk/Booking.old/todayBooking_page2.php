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
                                           
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <b class="lbl_text" >ORGANIZER</b> <b style="color:red">*</b>
                                              <div class="input-group">
                                                  <select onchange="changePIC($(this))" id="id_frm_crt_pic" class="form-control show-tick" data-live-search="true" >
                                                    <option value=""></option>
                                                  </select>
                                              </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <b class="lbl_text">DEPARTMENT IN CHARGE</b> <b style="color:red">*</b>
                                              <div class="input-group">
                                                  <select disabled id="id_frm_crt_alocation" class="form-control show-tick" data-live-search="true" >
                                                    <option value=""></option>
                                                  </select>
                                                  <!-- <p >Select pic to open the alocation in charge</p> -->
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
                                          <!-- START BLOCK -->
                                          <div class="row clearfix">
                                           
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <b class="lbl_text" >Position</b> <b style="color:red">*</b>
                                              <div class="input-group">
                                                  <select onchange="changePosition($(this))" id="id_frm_crt_position" class="form-control show-tick" data-live-search="true" >
                                                    <option value=""></option>
                                                  </select>
                                              </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <b class="lbl_text">Desk Number</b> <b style="color:red">*</b>
                                              <div class="input-group">
                                                  <select onchange="changeDeskNumber($(this))" id="id_frm_crt_desk_number" class="form-control show-tick" data-live-search="true" >
                                                    <option value=""></option>
                                                  </select>
                                                  <!-- <p >Select pic to open the alocation in charge</p> -->
                                              </div>
                                            </div>
                                          </div>
                                          <!-- END BLOCK -->
                                          <!-- START TIME -->
                                          <div class="row clearfix">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              <div class="input-group">
                                                  
                                                  <label class="lbl_text" for="">START  &nbsp; &nbsp;</label>
                                                  <button data-type="start" data-id="id_frm_crt_time_start" onclick="openAlertPilihCrt($(this))" type="button" class="btn btn-default btn-sm waves-effect">
                                                    <b id="id_frm_crt_time_start" class="lbl_text"></b> <span class="caret"> &nbsp;</span>
                                                  </button>
                                              </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 align-right">
                                              <div class="input-group align-right">
                                                  <label class="lbl_text" for="">END  &nbsp; &nbsp;</label>
                                                  <button onclick="openAlertPilihCrt($(this))" data-type="end" data-id="id_frm_crt_time_end" type="button" class="btn btn-default btn-sm waves-effect">
                                                    <b id="id_frm_crt_time_end" class="lbl_text"></b> <span class="caret"> &nbsp;</span>
                                                  </button>
                                                  
                                              </div>
                                            </div>
                                          </div>
                                          <!-- END TIME -->
                                          
                                          <!-- Reservation Cost -->
                                         <!--  <div class="row clearfix" >
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                              <label for="" class="lbl_text" >RESERVATION COST <u id="id_frm_crt_cost"></u></label>
                                            </div>
                                            
                                          </div> -->
                                          <!-- <hr> -->
                                          <!-- <u></u> -->
                                          <!--  -->
                                          <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                               <div class="input-group">
                                                  <span class="input-group-addon">
                                                      <i class="material-icons">business</i>
                                                  </span>
                                                  <p id="id_frm_crt_building" class="lbl_text"></p>
                                              </div>
                                              <div class="input-group">
                                                  <span class="input-group-addon">
                                                      <i class="material-icons">place</i>
                                                  </span>
                                                  <p id="id_frm_crt_location" class="lbl_text"></p>
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
                                          <!--  -->
                                          
                                          <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                              <button  id="id_btn_crt_submit_booking" style="padding: 15px !important; font-size: 22px;"  onclick ="clickSubmitCrtBooking($(this))" type="button" class="btn bg-red btn-block waves-effect">
                                                SUBMIT DESK BOOKING
                                              </button>
                                            </div>
                                          </div>
                                        </div>
                                    </div> 
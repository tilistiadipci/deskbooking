<!-- <div class="row clearfix">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
        <label for="">Choose Time</label>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <div class="btn-group btn-group-toggle btn-group-justified" data-toggle="buttons" data-toggle="buttons">
            <label onclick="chooseTimeCrt('today')" class="btn bg-pink btn-lg waves-effect active">
                <input type="radio" name="options" id="id_choose_today" autocomplete="off" checked> Today
            </label>
            <label onclick="choosePickerDateCrt()" class="btn btn-lg bg-pink waves-effect ">
                <input type="radio" name="options" id="id_choose_date" autocomplete="off"> Pick Date <b id="id_pick_date_crt"></b>
            </label>
        </div>
    </div>
</div> -->
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card">
            <div class="header">
                <div class="row clearfix">
                    <div class="col-xs-12">
                        <h2>Find & Reserve a Room </h2>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                        <b class="lbl_text">When</b> <b style="color:red">*</b>
                        <br>
                        <br>
                        <div class="form-group-filter">
                            <div class="form-group-filter-icon">
                                <i style="font-size: 24px;position: relative;left: -1px; top: 0px;" class="material-icons">date_range</i>
                            </div>
                            <div class="form-group-filter-form">
                                <button id="id_book_filter_when" style="font-size: 16px;position: relative;left: -1px; top: 0px;" type="button" class="btn btn-noshadow btn-block waves-effect">Today, 28 Aug 2024</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                        <b class="lbl_text">Location</b> <b style="color:red">*</b>
                        <br>
                        <br>
                        <div class="form-group-filter">
                            <div class="form-group-filter-icon">
                                <i style="font-size: 24px;position: relative;left: -1px; top: 0px;" class="material-icons">place</i>
                            </div>
                            <div class="form-group-filter-form">
                                <div class="form-group" style="width: 100%; margin-bottom: 0px !important;">
                                    <select data-live-search="true" class="form-control" id="id_book_filter_location" onchange="onChangeFilterLocation($(this))">
                                        <option value="1">11:52</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                        <b class="lbl_text">From</b> <b style="color:red">*</b>
                        <br>
                        <br>
                        <div class="form-group-filter">
                            <div class="form-group-filter-icon">
                                <i style="font-size: 24px;position: relative;left: -1px; top: 0px;" class="material-icons">access_time</i>
                            </div>
                            <div class="form-group-filter-form">
                                <input readonly type="text" name="" id="id_book_filter_from" class="timepicker form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                        <b class="lbl_text">Until</b> <b style="color:red">*</b>
                        <br>
                        <br>
                        <div class="form-group-filter">
                            <div class="form-group-filter-icon">
                                <i style="font-size: 24px;position: relative;left: -1px; top: 0px;" class="material-icons">access_time</i>
                            </div>

                            <div class="form-group-filter-form">
                                <input readonly type="text" name="" id="id_book_filter_until" class="timepicker form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <input onclick="onChangeFilterAllday($(this))" type="checkbox" id="id_book_filter_allday" class="filled-in chk-col-red"  />
                        <label for="id_book_filter_allday">All Day</label>
                    </div>
                    
                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                        <b class="lbl_text">Subject</b> <b style="color:red">*</b>
                        <br>
                        <br>
                        <div class="form-group-filter">
                            <div class="form-group-filter-form">
                                <input onkeyup="onKeyUpFilterSubject($(this))" autocomplete="off" type="text" class="form-control" id="id_book_filter_subject" placeholder="Subject">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-xs-12 form-group-filter-flex" >
                        <input onclick="onChangeFilterPrivate($(this))" type="checkbox" id="id_book_filter_private" class="filled-in chk-col-red"  />
                        <label for="id_book_filter_private">
                            Make Private
                        </label> 
                        <div class="form-group-filter-icon">
                            <a style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="" data-original-title="If activated, the meeting subject on the display will be hidden to become Private Meeting.">
                                <i style="font-size: 24px;position: relative;left: -1px; top: 0px;" class="material-icons">info_outline</i>
                            </a>
                        </div>
                    </div>
                   
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button type="button" id="id_book_filter_capabilities" class="btn btn-default dropdown-toggle form-group-filter-flex btn-block" 
                        data-toggle="dropdown" role="button" aria-expanded="true" >
                        <label for="id_book_filter_capabilities">
                        <i style="font-size: 24px;position: relative;left: -1px; top: 4px;" class="material-icons">accessibility</i> Capabilities
                        </label>
                        </button>
                    </div>

                </div>
                <div class="row clearfix">
                    <div class="col-xs-12">
                        <button id="id_book_filter_findroom" style="font-size: 16px;position: relative;left: -1px; top: 0px;" type="button" class="btn btn-primary btn-block btn-lg waves-effect">Find Room</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <div class="card">
            <div class="header">
                <div class="row clearfix">
                    <div class="col-xs-12">
                        <h2>Available Room </h2>
                    </div>
                </div>
            </div>
            <div class="body">
                <!-- div row room -->
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row clearfix" id="id_area_booking_ad_room">
                            <!-- <div class="row clearfix" id="id_area_booking_ad_room__"> -->
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <a href="javascript:void(0);">
                                            <img class="media-object" src="http://localhost/demobooking/file/room/420759.jpeg" width="100" height="100">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading"><a data-toggle="popover" data-placement="bottom" title="Popover title" tabindex="0" data-trigger="focus" data-content='
                        <table class="table borderless" >
                            <tr>
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Location">location_city</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">PUSAT / Merge Room 3</td>
                            </tr>
                            <tr>
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="People/Attendees Capacity">people_outline</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">20 people</td>
                            </tr>
                            <tr>
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Merge Room">domain</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">Yes</td>
                            </tr>
                            <tr>
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Private">do_not_disturb</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">Yes</td>
                            </tr>
                            <tr>
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Room Time">access_time</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">08:00 - 19:00</td>
                            </tr>
                            <tr style="padding:2px !important; border:0px; ">
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Facility" >lightbulb_outline</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">
                                    <span class="label label-bordered ">Projector</span>
                                    <span class="label label-bordered ">Light</span>
                                    <span class="label label-bordered ">Screen</span>
                                    <span class="label label-bordered ">LCD TV</span>
                                    <span class="label label-bordered ">Air Conditioner</span>
                                    <span class="label label-bordered ">High speed internet</span>
                                    <span class="label label-bordered ">Power outlet</span>
                                </td>
                            </tr>
                        </table>
                        ' data-html="true">Room 1 </a></h4>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <table class="table borderless table-borderless ">
                                                    <tr>
                                                        <td style="padding:2px !important; border:0px; ">
                                                            <i class="material-icons" style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Location">location_city</i>
                                                        </td>
                                                        <td style="padding:2px !important; border:0px; ">Gedung Pama 1 / Room 1</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding:2px !important; border:0px; ">
                                                            <i class="material-icons" style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="People/Attendees Capacity">people_outline</i>
                                                        </td>
                                                        <td style="padding:2px !important; border:0px; ">20 people</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-xs-12">
                                        <button type="button" class="btn btn-success btn-block" aria-haspopup="true" aria-expanded="false">
                                                        Reserve Room <!-- <span class="caret"></span> -->
                                        </button>
                                    </div>
                                </div>
                                </div>
                                
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <a href="javascript:void(0);">
                                            <img class="media-object" src="http://localhost/demobooking/file/room/420759.jpeg" width="80" height="80">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading"><a data-toggle="popover" data-placement="bottom" title="Popover title" tabindex="0" data-trigger="focus" data-content='
                        <table class="table borderless" >
                            <tr>
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Location">location_city</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">PUSAT / Merge Room 3</td>
                            </tr>
                            <tr>
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="People/Attendees Capacity">people_outline</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">20 people</td>
                            </tr>
                            <tr>
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Merge Room">domain</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">Yes</td>
                            </tr>
                            <tr>
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Private">do_not_disturb</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">Yes</td>
                            </tr>
                            <tr>
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Room Time">access_time</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">08:00 - 19:00</td>
                            </tr>
                            <tr style="padding:2px !important; border:0px; ">
                                <td style="padding:2px !important; border:0px; ">
                                    <i class="material-icons"  style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Facility" >lightbulb_outline</i>
                                </td>
                                <td style="padding:2px !important; border:0px; ">
                                    <span class="label label-bordered ">Projector</span>
                                    <span class="label label-bordered ">Light</span>
                                    <span class="label label-bordered ">Screen</span>
                                    <span class="label label-bordered ">LCD TV</span>
                                    <span class="label label-bordered ">Air Conditioner</span>
                                    <span class="label label-bordered ">High speed internet</span>
                                    <span class="label label-bordered ">Power outlet</span>
                                </td>
                            </tr>
                        </table>
                        ' data-html="true">Room 2 </a></h4>
                                        <div class="row clearfix">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <table class="table borderless table-borderless ">
                                                    <tr>
                                                        <td style="padding:2px !important; border:0px; ">
                                                            <i class="material-icons" style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Location">location_city</i>
                                                        </td>
                                                        <td style="padding:2px !important; border:0px; ">Gedung Pama 1 / Room 2</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding:2px !important; border:0px; ">
                                                            <i class="material-icons" style="" data-toggle="tooltip" data-placement="top" title="" data-original-title="People/Attendees Capacity">people_outline</i>
                                                        </td>
                                                        <td style="padding:2px !important; border:0px; ">20 people</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <select class="form-control" id="">
                                                        <option value="1">11:52</option>
                                                        <option value="2">19:15</option>
                                                        <option value="3">19:30</option>
                                                        <option value="4">19:30</option>
                                                        <option value="5">19:30</option>
                                                        <option value="6">19:10</option>
                                                        <option value="7">19:30</option>
                                                        <option value="8">19:30</option>
                                                        <option value="9">19:30</option>
                                                        <option value="10">19:30</option>
                                                        <option value="1">19:30</option>
                                                    </select>
                                                </div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Reserve Room <!-- <span class="caret"></span> -->
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block">30 mins</a></li>
                                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block">60 mins</a></li>
                                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block">90 mins</a></li>
                                                        <li role="separator" class="divider"></li>
                                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block">120 mins</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                
                        </div>

                    </div>
                </div>
                <!-- div row room -->
            </div>
        </div>
    </div>
</div>
<!-- div row Choose Time -->
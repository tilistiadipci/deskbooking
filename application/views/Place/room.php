<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("_partials/head_css_dashboard.php", array('pagename'=>$pagename));?>
    <link href="<?= base_url()?>assets/theme/plugins/morrisjs/morris.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/external/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/external/daterangepicker/daterangepicker.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/theme/plugins/select2/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url()?>assets/external/fullcalendar/main.min.css" />
    <link rel="stylesheet" href="<?= base_url()?>assets/external/fullcalendar/resource-timeline.main.min.css" />
    <link rel="stylesheet" href="<?= base_url()?>assets/external/fullcalendar/timeline.main.min.css" />
    <!-- Tippy Js -->
    <link rel="stylesheet" href="<?= base_url()?>assets/external/tippy/light.css" />
    <link rel="stylesheet" href="<?= base_url()?>assets/external/picker/picker.min.css" />
    <style>
        .text{
        /*font-weight: bold;*/
        font-size: 20px !important;
       }
        #biocalendar {
            max-width: 100%;
            margin: 40px auto;
        }
        #room-information-card{
/*            background-color: white !important;*/
        }
        .tippy-box[data-theme~='room-information'] {
          background-color: tomato;
          color: yellow;
        }
        .select2-container .select2-selection--single {
            height: 35px;
            border: 0px solid #aaa;
        }
       .select2-container .select2-selection--multiple {
            height: auto !important;
            border-radius: 5px !important;
            height: 48px !important;
       }
       .select2-container .select2-search--inline .select2-search__field{
            margin-left: 40px;
            font-size: 16px;
            line-height: 2;
            height: 100%;
       }

        li.select2-results__option strong.select2-results__group:hover {
          background-color: #ddd;
          cursor: pointer;
        }
        .optGSelected{
            background-color: #ddd;
        }
        .past-event {
            background-color: #dddddd;
            cursor: not-allowed;
            z-index: 3 !important;
        }
    .past-event:hover {
        /*        background-color: white;*/
        cursor: not-allowed !important;
    }
        .slot-event {
            cursor: pointer;
            z-index: 10 !important;
        }
       /* .slot-event:hover {
            background-color: red;
        }*/
        /*.slot-event {
            cursor: pointer;
            z-index: 3 !important;
        }*/



/*     .fc-content table tbody td:hover{background: #adf4fa;}*/
     .fc-time-area>.fc-scroller-clip>.fc-scroller>.fc-scroller-canvas>.fc-bg{
        z-index: 2 !important;
     }

     .fc-resource-area .fc-scroller-clip .fc-scroller .fc-scroller-canvas .fc-content td:hover{
        background-color: #dddddd;
        cursor: pointer !important;
     }
     .tippy-box[data-theme~=light] {
        color: #26323d;
        width: 500px;
        box-shadow: 0 0 20px 4px rgba(154,161,177,.15),0 4px 80px -8px rgba(36,40,47,.25),0 4px 4px -2px rgba(91,94,105,.15);
        background-color: #fff
    }
    .nomarqin-col{
        margin-bottom: 0px !important;
    }
    .fc-widget-content{
        height:60px;
    }
    .fc-timeline-event {
        position: absolute;
        border-radius: 0;
        margin-bottom: 1px;
        height:52px;
    }
    
/*    .bootstrapCustom { z-index: 1 !important;}*/


   
       
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
    <?php $this->load->view("_partials/navbarplace.php", array("pagename"=>$pagename));?>
    <!-- #Top Bar -->
    <section>
    </section>
    <section class="content" style="margin: 100px 15px 0px 15px;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>
                    <?= strtoupper($pagename) ?>
                </h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                    <center>
                        <div class="radiogroup">
                            <input id="cal_option_day" label="Day" type="radio" id="male" name="gender" value="male" checked>
                            <input id="cal_option_week" label="Week" type="radio" id="female" name="gender" value="female">
                            <input id="cal_option_month" label="Month" type="radio" id="other" name="gender" value="other">
                        </div>
                    </center>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                    <div class="btn-group" role="group">
                        <button id="cal_btn_prev" type="button" class="btn btn-lg btn-default waves-effect"><i class="material-icons">keyboard_arrow_left</i></button>
                        <button id="cal_btn_next" type="button" class="btn btn-lg btn-default waves-effect"><i class="material-icons">keyboard_arrow_right</i></button>
                    </div>
                    <button id="cal_btn_today" type="button" class="btn btn-lg btn-default waves-effect" style="font-size:19px !important;">TODAY</button>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon input-group-pretend ">
                            <i class="material-icons">date_range</i>
                        </span>
                        <div class="form-line">
                            <input id="id_schedule_daterange_search" type="text" class="form-control form-control-big pretend">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon input-group-pretend ">
                            <i class="material-icons">my_location</i>
                        </span>
                        <div class="form-line">
                            <select id="floor_filter" name="floor[]" class="form-control" multiple style="width: 100%"></select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                    <button type="button" class="btn btn-lg btn-default waves-effect"><i class="material-icons">filter_list</i> <span style="font-size:19px !important;">Filter</span></button>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-xs-12" style="height:20px;"></div>
            </div>
            <div class="row clearfix">
                <!-- Line Booking -->
                <div class="col-xs-12">
                    <div id='biocalendar'></div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div id="room-information-card">
                        <div class="card" style="box-shadow: none; background:transparent;">
                            <div class="header">
                                <div class="row clearfix">
                                    <div class="col-xs-12 col-sm-6">
                                        <h2 id="room-information-card-title"></h2>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 align-right">
                                    </div>
                                </div>
                            </div>
                            <div class="body">
                                <div class="row">
                                    <div class="col-xs-1">
                                        <i class="material-icons">domain</i>
                                    </div>
                                    <div class="col-xs-10" id="room-information-card-location"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-1">
                                        <i class="material-icons">people_outline</i>
                                    </div>
                                    <div class="col-xs-10" id="room-information-card-people"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-1">
                                        <i class="material-icons">lightbulb_outline</i>
                                    </div>
                                    <div class="col-xs-10" id="room-information-card-facility"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div>
                    </div>
                </div>
                <!-- BAR Booking -->
                <!-- #END# Line Booking -->
            </div>
        </div>
    </section>
    <div class="modal fade" id="id_mdl_add_bookingroom" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="idmdlcrLabel">Add Meeting</h4>
                </div>
                <div class="modal-body " id="id_mdl_add_bookingroom_body">
                    <div class="row">
                        <div class="col-xs-12 nomarqin-col">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons" data-toggle="tooltip" data-placement="top" data-original-title="Subject">title</i>
                                </span>
                                <div class="form-line">
                                    <input type="text" class="form-control" placeholder="Subject">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 nomarqin-col">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons" data-toggle="tooltip" data-placement="top" data-original-title="Time">access_time</i>
                                </span>
                                <div class="form-line">
                                    <input name="date" type="text" class="form-control addFormDate_meeting" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-8 nomarqin-col">
                            <div class="input-group">
                                <div class="form-line">
                                    <select name="start" class="form-control selectpickerr">
                                        <option>10:00</option>
                                    </select>
                                </div>
                                <span class="input-group-addon"><i class="material-icons" data-toggle="tooltip" data-placement="top" data-original-title="Time"> &nbsp;-&nbsp;&nbsp;</i></span>
                                <div class="form-line">
                                    <select name="end" class="form-control selectpickerr">
                                        <option>10:00</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 nomarqin-col">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons" data-toggle="tooltip" data-placement="top" data-original-title="Meeting Room">domain</i>
                                </span>
                                <div class="form-line">
                                    <select name="room" class="form-control selectpicker2" id="id_crt_room">
                                        <option value="" disabled hidden>Select Recurring</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 nomarqin-col">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons" data-toggle="tooltip" data-placement="top" data-original-title="Internal Attendees">people_outline</i>
                                </span>
                                <div class="form-line">
                                    <select  id="id_crt_internal_attendees" name="attendees" class="form-control selectpicker2 bootstrapCustom" data-live-search="true" multiple>
                                        <option value="" disabled hidden>Search Attendees</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 nomarqin-col">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons" data-toggle="tooltip" data-placement="top" data-original-title="External Attendees/Guest">insert_invitation</i>
                                </span>
                                <div class="form-line">
                                    <select id="id_crt_external_attendees"  name="attendees" class="form-control selectpicker2 bootstrapCustom" data-live-search="true" multiple>
                                        <option value="" disabled hidden>Search External Attendees</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 nomarqin-col">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons" data-toggle="tooltip" data-placement="top" data-original-title="Reminder Notification">priority_high</i>
                                </span>
                                <div class="form-line">
                                    <select id="id_crt_reminder"  name="reminder" class="form-control selectpicker2 bootstrapCustom" data-live-search="true" multiple>
                                        <option value="" disabled hidden>Select Reminder</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 nomarqin-col">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons" data-toggle="tooltip" data-placement="top" data-original-title="Repeat/Recurring">repeat</i>
                                </span>
                                <div class="form-line">
                                    <select id="id_crt_recurring"  name="recurring" class="form-control selectpicker2 bootstrapCustom" data-live-search="true" >
                                        <option value="" disabled hidden>Select Recurring</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="id_baseurl" value="<?= base_url()?>">
    <textarea style="display: none;" id="id_employee"><?= json_encode($employee)?></textarea>
    <?php $this->load->view("_partials/js_dashboard.php");?>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment-timezone.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/momentjs/moment-timezone-data.min.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="<?= base_url()?>assets/external/daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url()?>assets/external/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/chartjs/Chart.bundle.js"></script>
    <script src="<?= base_url()?>assets/theme/plugins/select2/js/select2.min.js"></script>
    <!-- <script src="<?= base_url()?>assets/process/dashboard/index.js"></script> -->
    <!-- Tippy Js -->
    <script type="text/javascript" src="<?= base_url()?>assets/external/tippy/popper.min.js"></script>
    <script type="text/javascript" src="<?= base_url()?>assets/external/tippy/tippy-bundle.umd.min.js"></script>
    <!-- Calendar Js -->
    <script type="text/javascript" src="<?= base_url()?>assets/external/fullcalendar/main.min.js"></script>
    <script type="text/javascript" src="<?= base_url()?>assets/external/fullcalendar/interaction.main.min.js"></script>
    <script type="text/javascript" src="<?= base_url()?>assets/external/fullcalendar/timeline.main.min.js"></script>
    <script type="text/javascript" src="<?= base_url()?>assets/external/fullcalendar/resource-common.main.min.js"></script>
    <script type="text/javascript" src="<?= base_url()?>assets/external/fullcalendar/resource-timeline.main.min.js"></script>
    <script type="text/javascript" src="<?= base_url()?>assets/external/picker/picker.min.js"></script>
    <!-- <script type="text/javascript" src="<?= base_url()?>assets/external/fullcalendar/"></script> -->
    <script type="text/javascript">
    enable_datetimepicker();
    var localtimezone = moment.tz.guess();
    var bs = $('#id_baseurl').val();
    var empraw = $('#id_employee').val();
    var roomInfoCard = $('#room-information-card')
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var calendarEl = document.getElementById('biocalendar');
    var calendar;
    var filterGFloor = [];
    var filterGRoom = [];
    var filterGFacility = [];
    var gSelectedEventEmpty = {};
    var gAttendees = JSON.parse(empraw);


    var gRecurring = [
        {'id':"day", 'text' : 'Every Day'},
        {'id':"week", 'text' : 'Every Week'},
        {'id':"month", 'text' : 'Every Month'},
    ];

    var gReminder = [
        {'id':"5", 'text' : '5 minute before'},
        {'id':"15", 'text' : '15 minute before'},
        {'id':"30", 'text' : '30 minute before'},
        {'id':"60", 'text' : '1 hour before'},
        {'id':"1440", 'text' : '1 day before'},
    ];



    $(function() {
        $('select').selectpicker('destroy');

        // $('#floor_filter').selectpicker('destroy');
        getFloorFilter();
        getFacilityFilter();
        initCalendar();
        setTimeout(function() {
            getRoomFilterResource();
        }, 1000)
        tooltipInit();
        initSelectpicker()
    })
    var floor_filter = [{
        "id": 1,
        "text": "Greece",
        "children": [{
            "id": "athens",
            "text": "Athens"
        }, {
            "id": "thessalonica",
            "text": "Thessalonica"
        }]
    }];

    function tooltipInit() {
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    }

    function initSelectFloorFilter() {
        $('#floor_filter').select2({
            placeholder: "Please select floor",
            allowClear: true,
            width: '100%',
            data: floor_filter,
            tags: true,
            closeOnSelect: false,
        });
    }

    function initSelectpicker() {
        $('.selectpickerr').select2({
            placeholder: "Please select floor",
            allowClear: true,
            width: '100%',
            // data: floor_filter,
            tags: true,
            closeOnSelect: false,
        });
        $('.selectpicker2').picker({ search: true });
        // $('.selectpicker2').selectpicker('refresh');

    }

    function enable_datetimepicker() {
        $('.input-group #id_schedule_daterange_search').daterangepicker({
            "showWeekNumbers": true,
            "showISOWeekNumbers": true,
            "opens": "center",
            "drops": "down",
            'ranges': {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "alwaysShowCalendars": true,
            "locale": {
                'format': 'YYYY-MM-DD'
            },
        }, function(start, end, label) {
            console.log(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))

        });

        $('.input-group .addFormDate_meeting').daterangepicker({
            "singleDatePicker": true,
            "showWeekNumbers": true,
            "showISOWeekNumbers": true,
            "opens": "center",
            "drops": "down",
            "alwaysShowCalendars": true,
            "locale": {
                'format': 'YYYY-MM-DD'
            },
        }, function(start, end, label) {

            console.log(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))

        });
    }


    $('#floor_filter').on('select2:open', function(e) {
        $('#select2-floor_filter-results').on('click', function(event) {
            event.stopPropagation();
            var data = $(event.target).html();
            var selectedOptionGroup = data.toString().trim();
            var groupchildren = [];
            for (var i = 0; i < floor_filter.length; i++) {
                if (selectedOptionGroup.toString() === floor_filter[i].text.toString()) {
                    if (floor_filter[i].children == null) {
                        continue;
                    }
                    for (var j = 0; j < floor_filter[i].children.length; j++) {
                        groupchildren.push(floor_filter[i].children[j].id);
                    }
                }
            }
            var options = [];
            options = $('#floor_filter').val();

            if (options === null || options === '') {
                options = [];
            }
            for (var i = 0; i < groupchildren.length; i++) {
                var count = 0;
                for (var j = 0; j < options.length; j++) {
                    if (options[j].toString() === groupchildren[i].toString()) {
                        count++;
                        break;
                    }
                }
                if (count === 0) {
                    options.push(groupchildren[i].toString());
                }
            }
            $('#floor_filter').val(options);
            $('#floor_filter').trigger('change'); // Notify any JS components that the value changed
            $('#floor_filter').select2('close');
        });
    });
    $('#floor_filter').on('change', function() {
        var vfloorid = $('#floor_filter').val();
    });
    $('.slot-event').on('mouseenter mouseleave', function() {
        console.log("2");
    });



    function formatDateLocale(date, days = 0) {
        const pastDate = new Date(date)
        pastDate.setDate(pastDate.getDate() - days)
        date = pastDate;
        var isoF = date.toISOString();

        var sp = isoF.split("T");
        var date = sp[0];
        var rawtime = sp[1];
        var sptime = rawtime.split(":");
        var spzone = sptime[2].split(".");
        var time = [sptime[0], sptime[1], spzone[0]].join(":");
        return date + " " + time;
    }

    function changeTimeIntoTimezone(time, $timezone) {
        var dataMoment = moment.tz(time, $timezone);
        var localtimezone = moment.tz.guess(true);
        if ($timezone != localtimezone) {
            return dataMoment.clone().tz(localtimezone);
        } else {
            return dataMoment;
        }
    }
    var selectIdEvent = "";

    function initCalendar() {

        calendar = new FullCalendar.Calendar(calendarEl, {
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            plugins: ['interaction', 'resourceTimeline'],
            timeZone: 'UTC+07:00',
            selectable: true,
            header: {
                // left: 'today prev,next',
                center: 'title',
                right: ''
            },
            initialDate: moment().format(),
            firstDay: 0,
            defaultView: 'resourceTimelineDay',
            scrollTime: '10:00',
            aspectRatio: 1.5,
            minTime: '00:00:00',
            maxTime: '24:00:00',
            views: {
                resourceTimelineDay: {
                    buttonText: ':15 slots',
                    slotDuration: '00:15:00',
                    snapDuration: '00:15:00',
                    slotLabelFormat: [{
                        hour: 'numeric',
                        minute: '2-digit',
                        omitZeroMinute: false,
                        hour12: false,
                        // meridiem: 'short'
                    }],
                    // slotLabelInterval: {minutes: 15},

                },
                resourceTimelineWeek: {
                    slotDuration: { days: 1 },
                    duration: { days: 7 },
                    slotLabelFormat: [
                        { month: 'long', year: 'numeric' },
                        { weekday: 'long' },

                    ]
                },
                resourceTimelineMonth: {
                    slotDuration: { days: 1 },
                    // slotLabelFormat: [
                    //     { month: 'long', year: 'numeric' },
                    //     // { weekday: 'long' },

                    // ]
                },

                // resourceTimelineTenDay: {
                //     type: 'resourceTimeline',
                //     duration: { days: 10 },
                //     buttonText: '10 days',

                // },
                // resourceTimelineTwentyDay: {
                //     type: 'resourceTimeline',
                //     duration: { days: 12 },
                //     buttonText: '12 days',
                //     slotDuration: '24:00:00'
                // }
            },
            select: function(info) {
                $(".fc-highlight").css("background", "red");
                var startString = formatDateLocale(info.start);
                var endString = formatDateLocale(info.end);
                var mstart = moment(startString);
                var mend = moment(endString);
                var now = moment();
                var past = mstart.diff(now) <= 0;
                if (past) return false;
                var el = info.view.el;
                selectIdEvent = "M" + moment().format('x');
                var roomId = info.resource.id;
                var r = getRooom(roomId)
                var event = {
                    id: selectIdEvent,
                    resourceId: roomId,
                    title: "Meeting on " + r.name,
                    start: mstart.format(),
                    end: mend.format(),
                    editable: true,
                    startEditable: true,
                    durationEditable: true,
                    backgroundColor: "'transparent",
                    borderColor: "blue",
                    date : mstart.format("YYYY-MM-DD"),
                    // allDay: allday == 1 ? true : false,
                }
                gSelectedEventEmpty = event;

                createAddBookingRoom()
                // var
                // console.log(JSON.stringify(event))

                // calendar.addEvent(event);



            },
            selectAllow: function(selectInfo) {
                return moment().diff(selectInfo.start) <= 0
            },
            mouseEnter: function(info) {
                console.log("mouseEnter")
                console.log(info)
                // info.event.setProp('backgroundColor', '#00CCFF');
                // alert('Clicked on: ' + info.dateStr);
                // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
                // alert('Current view: ' + info.view.type);
                // // change the day's background color just for fun
                // info.dayEl.style.backgroundColor = 'red';
            },

            // validRange: function(nowDate) {

            //     return {
            //         start: nowDate
            //     };
            // },
            // businessHours: [ // specify an array instead
            //     {
            //         daysOfWeek: [1, 2, 3, 4, 5], // Monday, Tuesday, Wednesday
            //         startTime: '08:00', // 8am
            //         endTime: '18:00' // 6pm
            //     },

            // ],
            editable: true,
            resourceLabelText: 'Rooms',
            resources: [],
            events: [],
            dayRender: function(info) {
                var stringdate = formatDateLocale(info.date);
                var past = moment(stringdate);
                // console.log(info.date.toISOString())
                var s = moment().diff(past, 'minutes')
                if (s > 0) {
                    tippy(info.el, {
                        content: `Can't book in the past`,
                        arrow: true,
                        // animation: 'fade',
                        followCursor: true,
                    });
                    info.el.classList.add("past-event")
                    // info.el.classList.remove("slot-event")
                } else {
                    info.el.classList.remove("past-event")
                    info.el.classList.add("slot-event")
                    // tippy(info.el, {
                    //     content: `Can't book in the past`,
                    //     arrow: true,
                    //     // animation: 'fade',
                    //     followCursor: true,
                    // });

                }


            },
            resourceRender: function(info) {
                var gr = getRooom(info.resource.id);
                $('#room-information-card-title').html(info.resource.title);
                if (gr == null) {

                } else {
                    var cc = (gr.facility_room).split(",");
                    var lblfacility = ``;
                    for (var x in cc) {
                        if (cc[x] == "") { continue; }
                        lblfacility += `<span class="badge bg-pink">${cc[x]}</span>&nbsp;`;
                    }
                    var location_name = gr.building_name + " " + gr.floor_name;
                    $('#room-information-card-location').html(location_name);
                    $('#room-information-card-people').html(gr.capacity - 0);
                    $('#room-information-card-facility').html(lblfacility);
                }
                tippy.setDefaultProps({ maxWidth: '500px' })
                tippy(info.el, {
                    theme: 'light',
                    inertia: true,
                    content: roomInfoCard.html(),
                    arrow: false,
                    placement: 'right',
                    allowHTML: true,
                });
                // renderInfo.el.style.backgroundColor = 'blue';
            },
            eventRender: function(info) {
                var element = info.el;
                // console.log()
                // var tooltip = new Tooltip(info.el, {
                //     title: info.event.extendedProps.description,
                //     placement: 'top',
                //     trigger: 'hover',
                //     container: 'body'
                //   });

                // info.el.style.backgroundColor = 'red';
                // var ntoday = new Date().getTime();
                // var eventEnd = moment(event.end).valueOf();
                // var eventStart = moment(event.start).valueOf();
                // if (!event.end) {
                //     // console.log(info.el);

                //     if (eventStart < ntoday) {
                //         element.addClass("past-event");
                //         info.view.el.style.backgroundColor = 'red';
                //         element.children().addClass("past-event");
                //     }
                // } else {
                //     if (eventEnd < ntoday) {

                //         info.view.el.style.backgroundColor = 'red';
                //         element.addClass("past-event");
                //         element.children().addClass("past-event");
                //     }
                // }

            }


        });
        calendar.render();
    }


    // calendar.refetchResources()
    // calendar.resourcesSet([{ "id": "b", "title": "Hello A" }])

    $('#cal_btn_prev').on("click", function() {
        clickCalendar('prev');
    });
    $('#cal_btn_next').on("click", function() {
        clickCalendar('next');
    });
    $('#cal_btn_today').on("click", function() {
        clickCalendar('today');
    });
    $('#cal_option_day').on("change", function() {
        clickCalendar('day');
    });
    $('#cal_option_week').on("change", function() {
        clickCalendar('week');
    });
    $('#cal_option_month').on("change", function() {
        clickCalendar('month');
    });



    async function createAddBookingRoom() {
        $('#id_mdl_add_bookingroom').modal('show');
        var htmlRecurring = `<option value="" disabled hidden>Select Recurring</option>`;
        for(var x in gRecurring){
            htmlRecurring += `<option value="${gRecurring[x].id}" >${gRecurring[x].text}</option>`;
        }
        var htmlReminder= `<option value="" disabled hidden>Select Reminder</option>`;
        for(var i in gReminder){
            htmlReminder += `<option value="${gReminder[i].id}" >${gReminder[i].text}</option>`;
        }
        var htmlAttendees = `<option value="" disabled hidden>Search Attendees</option>`;
        var htmlAttendees = `<option value="" disabled hidden>Search Attendees</option>`;
        for(var e in gAttendees){
            htmlAttendees += `<option value="${gAttendees[e].id}" >${gAttendees[e].name}</option>`;
        }
        
        
        var roomid = gSelectedEventEmpty.resourceId;
        var date = gSelectedEventEmpty.date;
        var start = moment(gSelectedEventEmpty.start).format('YYYY-MM-DD HH:mm:ss');
        var end = moment(gSelectedEventEmpty.end).format('YYYY-MM-DD HH:mm:ss');

        const res = await getRoomTime(roomid,date,start);
        var datatime = [];
        if(res.status == "success"){
            datatime = res.collection;
        }
        // console.log(res);
        var dataroom = getRooom(roomid);
        var htmlRooms = ``;
        htmlRooms += `<option value="${dataroom.radid}" >${dataroom.name}</option>`;

        $('#id_crt_recurring').html(htmlRecurring);
        $('#id_crt_reminder').html(htmlReminder);
        $('#id_crt_internal_attendees').html(htmlAttendees);
        $('#id_crt_room').html(htmlRooms);
        var cktime = checkValidationTime(datatime, start)
        initSelectpicker();
        selectTimeAddBooking(cktime, start, end);
        
    }
    function selectTimeAddBooking(datatime, time1, time2){
        var m1 = moment(time1);
        var m2 = moment(time2);
        var diff = m2.diff(m1, 'minutes');
        var html = ``;
        // console.log(diff, time1, time2);
        return;
    }
    function checkValidationTime(datatime, time){
        var now = moment().format("YYYY-MM-DD");
        var date = moment(time).format("YYYY-MM-DD");
        var starttt = moment();

        if(now != date){ // today
            var tomo = `${date} 00:00:00`;
            starttt = moment(tomo);
        }
        var list = [];
        for(var x in datatime){
            var b = datatime[x].book - 0;
            var tt = datatime[x].time_array;
            var dtall = `${date} ${tt}`;
            var dif2 = moment(dtall);
            if(b == 1 ){
                continue;
            }
            var diff = dif2.diff(starttt, 'minutes');
            if(diff >= 0){
                if(diff == 0){
                    datatime[x]['selected'] = true;
                    list.push(datatime[x])
                }else{
                    list.push(datatime[x])
                }
                
            }
        }
        return list;
    }

    function clickCalendar(action) {
        switch (action) {
            case 'prev':
                calendar.prev();

                break;
            case 'next':
                calendar.next();

                break;
            case 'today':
                calendar.today();

                break;
            case 'getdate':
                var date = calendar.getDate();
                // console.log(date.toISOString())
                break;
            case 'day':
                calendar.changeView('resourceTimelineDay', {
                    slotDuration: '00:15'
                });

                break;
            case 'week':
                var date = calendar.getDate();
                var now = formatDateLocale(date);
                var momentCalDateStartWeeks = moment(now).startOf('week');
                calendar.gotoDate(momentCalDateStartWeeks.format());
                calendar.changeView('resourceTimelineWeek', {
                    type: 'resourceTimeline',
                    duration: { days: 7 },
                });
                break;
            case 'month':
                calendar.changeView('resourceTimelineMonth', {

                });
                break;
            default:
                break;


        }
        getRoomFilterResource()
    }

    // GET DATA
    function getRoomFilterResource() {
        var vfloorid = $('#floor_filter').val();
        var vfacilityid = $('#floor_filter').val();
        if (vfloorid == null) {
            vfloorid = [];
        }
        vfacilityid = [];
        var date_start = formatDateLocale(calendar.view.currentStart);
        var date_end = formatDateLocale(calendar.view.currentEnd, 1);

        var filterdata = {
            filter: true,
            date_start_cari: date_start,
            date_end_cari: date_end,
            floor_cari: vfloorid.join("#"),
            capacity_min_cari: 0,
            capacity_max_cari: 100,
            facility_cari: vfacilityid.join("#"),

        }
        $.ajax({
            url: bs + "room-place/get/data-calendar-filter",
            type: "GET",
            dataType: "json",
            data: filterdata,
            beforeSend: function() {},
            success: function(data) {
                if (data.status == "success") {
                    var col = data.collection;
                    filterGRoom = col;
                    // console.log(filterGRoom);
                    clearRooms();
                    clearEvents()
                    initializeEventsCalendar();
                } else {

                }
            },
            error: errorAjax
        })
    }
    function getRoomTime(roomid = "", date = "", time = "") { 
        var dt = {
            roomid : roomid,
            date : date,
            time : time,
        }
        var url = bs + "room-place/get/data-time-room";
        return $.ajax({
            url: url,
            data:dt,
            dataType:'json',
            type: 'GET',
        });
    };

    function getRooom(id = "") {
        var r = null;
        for (var x in filterGRoom) {
            var item = filterGRoom[x];
            if (id == item.radid) {
                r = item;
                break;
            }
        }
        return r;
    }

    function clearRooms() {
        var rooms = calendar.getResources();
        for (var x in rooms) {
            rooms[x].remove();
        }
    }

    function clearEvents() {
        var events = calendar.getEvents()
        for (var x in events) {
            events[x].remove();
        }
    }

    function initializeEventsCalendar() {
        var l = filterGRoom;
        for (var x in filterGRoom) {
            var room = filterGRoom[x];
            var bookings = room['booking'];
            var droom = {
                id: room.radid,
                title: room.name,
                // eventAllow: true,
                // eventAllow: true,
                // eventOverlap: false,
            }
            calendar.addResource(droom);
            for (var e in bookings) {
                var b = bookings[e];
                var allday = b.all_day == null ? 0 : b.all_day - 0;

                // changeTimeIntoTimezone("2024-06-04 14:30:00", localtimezone);
                var event = {
                    id: b.booking_id,
                    resourceId: room.radid,
                    title: b.title,
                    start: changeTimeIntoTimezone(b.server_start, localtimezone).format(),
                    end: changeTimeIntoTimezone(b.server_end, localtimezone).format(),
                    allDay: allday == 1 ? true : false,
                }
                console.log(JSON.stringify(event))
                calendar.addEvent(event)
            }
        }
    }


    function getFacilityFilter() {
        $.ajax({
            url: bs + "room-place/get/data-facility-filter",
            type: "GET",
            dataType: "json",
            data: {},
            beforeSend: function() {},
            success: function(data) {
                if (data.status == "success") {
                    var col = data.collection;
                    filterGFacility = col;

                } else {

                }
            },
            error: errorAjax
        })
    }


    function getFloorFilter() {
        $.ajax({
            url: bs + "room-place/get/data-floor-filter",
            type: "GET",
            dataType: "json",
            data: {},
            beforeSend: function() {},
            success: function(data) {
                if (data.status == "success") {
                    console.log(data.collection)
                    var col = data.collection;
                    // floor_filter ;
                    filterGFloor = col;
                    var colfilter = [{
                        id: "all",
                        text: "ALL",
                        children: []
                    }];

                    for (var x in col) {
                        var item = col[x];
                        var d = {
                            id: item['id'],
                            text: item['name'],
                            children: []
                        }
                        var floor = item['floor'];
                        for (var i in floor) {
                            var dt = {
                                id: floor[i]['id'],
                                text: floor[i]['name'],
                            }
                            d['children'].push(dt)
                        }
                        colfilter.push(d);

                    }
                    floor_filter = colfilter;
                    initSelectFloorFilter();
                } else {

                }
            },
            error: errorAjax
        })
    }
    </script>
    <script type="text/javascript">
    function errorAjax(xhr, ajaxOptions, thrownError) {
        $('#id_loader').html('');
        if (ajaxOptions == "parsererror") {
            var msg = "Status Code 500, Error Server bad parsing";
            showNotification('alert-danger', msg, 'bottom', 'left')
        } else {
            var msg = "Status Code " + xhr.status + " Please check your connection !!!";
            showNotification('alert-danger', msg, 'bottom', 'left')
        }
    }
    </script>
</body>

</html>
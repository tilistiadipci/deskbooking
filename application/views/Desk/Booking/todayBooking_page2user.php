<style>
/* New styles for the redesign */
.fc-highlight {
    background-color: rgba(76, 175, 80, 0.2) !important;
}

/* Taller rows for easier selection */
.fc-timeline-lane, .fc-timeline-row, .fc-row {
    height: 75px !important;
    min-height: 75px !important;
}
tr[data-resource-id] td {
    height: 75px !important;
}
.fc-timeline-event {
    height: 55px !important;
    display: flex;
    align-items: center;
}

/* Custom styling for the selected time block */
.custom-selection-event {
    background-color: rgba(76, 175, 80, 0.3) !important;
    border: 1px solid #4CAF50 !important;
    border-radius: 8px !important;
}

/* Styling the resize handles on left and right */
.custom-selection-event .fc-resizer {
    position: absolute !important;
    background-color: #4CAF50 !important;
    width: 24px !important;
    opacity: 1 !important;
    display: flex !important;
    align-items: center;
    justify-content: center;
    top: 0 !important;
    bottom: 0 !important;
    height: 100% !important;
}
.custom-selection-event .fc-resizer::after {
    content: '||';
    color: white;
    font-size: 12px;
    font-weight: bold;
    letter-spacing: -1px;
}
.custom-selection-event .fc-resizer-start {
    border-top-left-radius: 8px !important;
    border-bottom-left-radius: 8px !important;
}
.custom-selection-event .fc-resizer-end {
    border-top-right-radius: 8px !important;
    border-bottom-right-radius: 8px !important;
}

.map-container {
    position: relative;
    width: 100%;
    height: 450px;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 15px;
}
.map-controls {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 10;
    background: white;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.map-controls button {
    display: block;
    width: 30px;
    height: 30px;
    border: 1px solid #ddd;
    background: white;
    font-size: 18px;
    line-height: 1;
    cursor: pointer;
}
.map-layer {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    transform-origin: 0 0;
}
.map-layer img {
    max-width: none;
}
.desk-point {
    position: absolute;
    width: 30px;
    height: 30px;
    border-radius: 4px;
    cursor: pointer;
    background-color: rgba(200, 200, 200, 0.7);
    border: 2px solid transparent;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 10px;
    font-weight: bold;
}
.desk-point.available { background-color: rgba(255, 255, 255, 0.9); border-color: #ccc; }
.desk-point.selected { background-color: rgba(76, 175, 80, 0.9); border-color: #388e3c; color: white;}
.desk-point.occupied { background-color: rgba(244, 67, 54, 0.9); border-color: #d32f2f; color: white;}
.desk-point.reserved { background-color: rgba(255, 152, 0, 0.9); border-color: #f57c00; color: white;}
.desk-point.unavailable { background-color: rgba(158, 158, 158, 0.9); border-color: #757575; color: white;}

.legend-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background: white;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 12px;
}
.legend-item {
    display: flex;
    align-items: center;
}
.legend-color {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    margin-right: 5px;
}

.selected-desk-card {
    border: 1px solid #4CAF50;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: center;
    background: #f1f8e9;
}
.desk-icon-wrapper {
    width: 50px;
    height: 50px;
    background: #e8f5e9;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 15px;
}
.desk-icon-wrapper i { color: #4CAF50; font-size: 24px; }

.custom-form-group { margin-bottom: 20px; }
.custom-form-label { font-weight: bold; font-size: 12px; text-transform: uppercase; margin-bottom: 5px; display: block;}
.custom-form-label .req { color: red; }
.custom-input-group { border: 1px solid #ccc; border-radius: 6px; overflow: hidden; background: white;}
.custom-input-group .bootstrap-select .dropdown-toggle { border: none !important; box-shadow: none !important; background: white !important;}

.time-selector-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.time-box {
    flex: 1;
    border: 1px solid #ccc;
    border-radius: 6px;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    background: white;
}
.duration-badge {
    padding: 10px 15px;
    background: #e3f2fd;
    color: #1976d2;
    border-radius: 8px;
    margin: 0 15px;
    text-align: center;
    font-weight: bold;
    font-size: 12px;
}

.desk-info-box {
    background: white;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}
.desk-info-item {
    display: flex;
    margin-bottom: 15px;
}
.desk-info-item:last-child { margin-bottom: 0; }
.desk-info-item i { width: 30px; color: #666; font-size: 20px; }
.desk-info-text { flex: 1; font-size: 13px; color: #333; }

.btn-submit-red {
    background-color: #f44336 !important;
    color: white !important;
    border-radius: 8px !important;
    padding: 15px !important;
    font-size: 16px !important;
    font-weight: bold !important;
}
</style>

<div class="row clearfix">
    <div class="col-lg-12">
        <button onclick="onButtonBack()" type="button" class="btn btn-default btn-sm waves-effect" style="margin-bottom:15px;">
            <i class="material-icons">arrow_back</i> BACK
        </button>
    </div>
</div>

<div class="row clearfix">
    <!-- LEFT SIDE: MAP -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <h4 style="margin-top:0;">FLOOR PLAN - <span id="map_room_title">Room</span></h4>
        
        <div class="map-container" id="map_container">
            <div class="map-layer-wrapper" style="position:relative; width: 100%; height: 500px; overflow: auto; background:#ddd;">
                <div class="map-layer" id="map_layer" style="position: absolute; transform-origin: 0 0; width: 1000px; height: 640px;">
                    <img id="id_frm_crt_image" src="" alt="Floor Plan" style="position: absolute; top: 10px; left: 10px; width: auto; height: auto;">
                    <div id="desk_overlays" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
                </div>
            </div>
            
            <div class="map-controls">
                <button class="btn btn-default" onclick="zoomMap(0.2)">+</button>
                <button class="btn btn-default" onclick="zoomMap(-0.2)">-</button>
            </div>
        </div>

        <div class="legend-box">
            <div class="legend-item"><div class="legend-color" style="border:1px solid #ccc; background:#fff;"></div> Available</div>
            <div class="legend-item"><div class="legend-color" style="background:#4CAF50;"></div> Selected</div>
            <div class="legend-item"><div class="legend-color" style="background:#f44336;"></div> Occupied</div>
            <div class="legend-item"><div class="legend-color" style="background:#ff9800;"></div> Reserved</div>
            <div class="legend-item"><div class="legend-color" style="background:#9e9e9e;"></div> Unavailable</div>
        </div>

        <h5>Selected Desk</h5>
        <div class="selected-desk-card">
            <div class="desk-icon-wrapper">
                <i class="material-icons">event_seat</i>
            </div>
            <div>
                <div style="font-size:12px; color:#666;">Desk Number</div>
                <div style="font-size:24px; font-weight:bold;" id="sel_desk_number">-</div>
            </div>
            <div style="margin-left:auto; text-align:right;">
                <div style="font-size:12px; color:#666;">Location</div>
                <div style="font-size:14px; font-weight:bold;" id="sel_desk_location">-</div>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE: FORM -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="row clearfix">
            <div class="col-xs-6 custom-form-group">
                <label class="custom-form-label">ORGANIZER <span class="req">*</span></label>
                <div class="custom-input-group">
                    <select disabled onchange="changePIC($(this))" id="id_frm_crt_pic" class="form-control show-tick" data-live-search="true" data-container="body">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 custom-form-group">
                <label class="custom-form-label">DEPARTMENT IN CHARGE <span class="req">*</span></label>
                <div class="custom-input-group">
                    <select disabled id="id_frm_crt_alocation" class="form-control show-tick" data-live-search="true" data-container="body">
                        <option value=""></option>
                    </select>
                </div>
            </div>
        </div>

        <div style="margin-bottom:20px;">
            <h3 style="margin:0 0 5px 0;" id="id_frm_crt_name">Room</h3>
            <div style="color:#666; display:flex; align-items:center;">
                <i class="material-icons" style="font-size:18px; margin-right:5px;">event</i>
                <span id="id_frm_crt_date">Date</span>
            </div>
        </div>

        <div class="row clearfix" style="display: none;">
            <div class="col-xs-6 custom-form-group">
                <label class="custom-form-label">POSITION <span class="req">*</span></label>
                <div class="custom-input-group">
                    <select onchange="changePosition($(this))" id="id_frm_crt_position" class="form-control show-tick" data-live-search="true" data-container="body">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 custom-form-group">
                <label class="custom-form-label">DESK NUMBER <span class="req">*</span></label>
                <div class="custom-input-group">
                    <select onchange="changeDeskNumber($(this))" id="id_frm_crt_desk_number" class="form-control show-tick" data-live-search="true" data-container="body">
                        <option value=""></option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row clearfix" id="time_selection_row" style="display: none;">
             <div class="col-xs-6 custom-form-group">
                <label class="custom-form-label">START <span class="req">*</span></label>
                <div class="time-box">
                    <div style="display:flex; align-items:center;">
                        <i class="material-icons" style="color:#666; margin-right:5px; font-size:18px;">access_time</i>
                        <b id="id_frm_crt_time_start"></b>
                    </div>
                    <button id="btn_start_trig" style="display:none;" data-type="start" data-id="id_frm_crt_time_start"></button>
                </div>
            </div>
            <div class="col-xs-6 custom-form-group">
                <label class="custom-form-label">END <span class="req">*</span></label>
                 <div class="time-box">
                    <div style="display:flex; align-items:center;">
                        <i class="material-icons" style="color:#666; margin-right:5px; font-size:18px;">access_time</i>
                        <b id="id_frm_crt_time_end"></b>
                    </div>
                    <button id="btn_end_trig" style="display:none;" data-type="end" data-id="id_frm_crt_time_end"></button>
                </div>
            </div>
            
            <div class="col-xs-12">
                <p style="margin-top: 10px; font-size: 12px; color: #666;">Drag to select your booking time.</p>
                <div id="calendar-timeline" style="margin-top: 10px;"></div>
            </div>
        </div>

        <h5 style="margin-top:20px;">Desk Information</h5>
        <div class="desk-info-box">
            <div class="desk-info-item">
                <i class="material-icons">business</i>
                <div class="desk-info-text">
                    <span id="id_frm_crt_building"></span> - <span id="id_frm_crt_location"></span>
                </div>
            </div>
            <div class="desk-info-item">
                <i class="material-icons">person</i>
                <div class="desk-info-text" id="id_frm_crt_capacity"></div>
            </div>
            <div class="desk-info-item">
                <i class="material-icons">list</i>
                <div class="desk-info-text" id="id_frm_crt_facility"></div>
            </div>
        </div>

        <button id="id_btn_crt_submit_booking" onclick="clickSubmitCrtBooking($(this))" type="button" class="btn btn-block btn-submit-red waves-effect">
            <i class="material-icons" style="margin-right:5px;">event_available</i> SUBMIT DESK BOOKING
        </button>
    </div>
</div>
 
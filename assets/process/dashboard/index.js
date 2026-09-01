var chartTransaction;
var chartTopRoom;
var g = {
    name : "sss",
    num : "1"
}
 
var x = new moment()
var y = new moment('2020-01-30 12:00:00')
var duration = moment.duration(x.diff(y)).as('minutes');
 
var nn = [1,2,3,4,5];
$(function () {
    getMorrisLineTransaction();
    intrTime();
    getTodayYears();
    enable_datetimepicker()
    // ====================
    getChartTransaction();
    getChartTopRoom();
    getTableOngoing();
    initDonutChart();
});

function initDonutChart() {
    var available = $('#id_status_available').val() || 0;
    var reserved = $('#id_status_reserved').val() || 0;
    var occupied = $('#id_status_occupied').val() || 0;
    var unavailable = $('#id_status_unavailable').val() || 0;

    var ctx = document.getElementById("donut_chart_status");
    if(ctx) {
        new Chart(ctx.getContext("2d"), {
            type: 'doughnut',
            data: {
                labels: ["Available", "Reserved", "Occupied", "Unavailable"],
                datasets: [{
                    data: [available, reserved, occupied, unavailable],
                    backgroundColor: ['#4CAF50', '#FF9800', '#E53935', '#9E9E9E'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 75,
                legend: {
                    display: false
                }
            }
        });
    }
}
function initTable(selector){
    selector.DataTable();
}
function clearTable(selector){
    selector.DataTable().destroy();
}
function intrTime(){
    setInterval(
            function(){
                var tm = moment().format('hh:mm A');
                $('#time1').html(tm);
            },
        );
}

function enable_datetimepicker(){
    $('#id_datepicker_transaction').datepicker({
        startView: 2, // years only
        format: "yyyy",
        todayHighlight: true,
        minViewMode : 2,
        minViewMode :2,
    }).on('changeYear', function(e) {
        var tm =  moment(e.date).format('YYYY');
        $( "#id_select_years_transaction" ).parent().parent().removeClass("open");
        $('#id_select_years_transaction').html(tm)
        getChartTransaction(tm);
        // `e` here contains the extra attributes
    });
    $('#id_datepicker_top_room').datepicker({
        startView: 2, // years only
        format: "yyyy",
        todayHighlight: true,
        minViewMode : 2,
        minViewMode :2,
    }).on('changeYear', function(e) {
        var tm =  moment(e.date).format('YYYY');
        $( "#id_select_years_top_room" ).parent().parent().removeClass("open");
        $('#id_select_years_top_room').html(tm)
        getChartTopRoom(tm);
        // `e` here contains the extra attributes
    });
    $('.input-group #daterangepicker').daterangepicker({
        "showWeekNumbers": true,
        "showISOWeekNumbers": true,
        "opens": "center",
        "drops": "up",
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "alwaysShowCalendars": true,
    }, function(start, end, label) {
      getTableOngoing(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))
      // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });
}
function getChartTransaction(year = ""){
    var bs = $('#id_baseurl').val();
    if(year == ""){
        year = moment().format('YYYY');
    }
    $.ajax({
        url : bs+"dashboard/get/chart/booking/"+year,
        type : "GET",
        dataType: "json",
        beforeSend: function(){
        },
        success:function(data){
            if(data.status == "success"){
                getMorrisLineTransaction(data.collection)
            }else{

            }
        },
        error: errorAjax
    })
}
function getChartTopRoom(year = ""){
    var bs = $('#id_baseurl').val();
    if(year == ""){
        year = moment().format('YYYY');
    }
    $.ajax({
        url : bs+"dashboard/get/chart/top-room/"+year,
        type : "GET",
        dataType: "json",
        beforeSend: function(){
        },
        success:function(data){
            if(data.status == "success"){
                getMorrisLineTopRoom(data.collection)
            }else{

            }
        },
        error: errorAjax
    })
}
function getTableOngoing(date1 = "", date2=""){
    var bs = $('#id_baseurl').val();
    if(date1 == ""){
        date1 = moment().format('YYYY-MM-DD');
    }
    if(date2 == ""){
        date2 = date1;
    }
    $.ajax({
        url : bs+"dashboard/get/table/ongoing/"+date1+"/"+date2,
        type : "GET",
        dataType: "json",
        beforeSend: function(){
        },
        success:function(data){
            if(data.status == "success"){
                clearTable($('#id_tbl_ongoing'));
                var html = '';
                var col = data.collection;
                $.each(col, (index, input)=>{
                    var txtStatus = "";
                    var timeStart = moment(input.start).format('hh:mm A');
                    var timeEnd = moment(input.end).format('hh:mm A');
                    var today = new moment()
                    var start = new moment(input.start)
                    var end = new moment(input.end)
                    var diffstart = moment.duration(today.diff(start)).as('minutes');
                    var diffend = moment.duration(today.diff(end)).as('minutes');
                    console.log(diffstart,diffend)
                    if (diffstart > 0 && diffend < 0 ) {
                        // active
                        txtStatus = "<b class='font-bold col-green'>Active</b>";
                    }else if(diffend > 0){
                        txtStatus = "<b class='font-bold col-red'>Expired</b>";
                    }else if(diffstart < 0 && diffend < 0 ){
                        txtStatus = "<b class='font-bold col-blue'>Soon</b>";
                    }

                    html += '<tr style="cursor:pointer;" onclick="getDetailMeeting($(this))" data-id="'+input.booking_id+'" >';
                    html += '<td>'+timeStart + " - "+timeEnd+'<br><small style="color: #6B7280;">'+input.duration+' mins</small></td>';
                    html += '<td><strong>'+input.title+'</strong><br><span style="color: #6B7280;">'+input.name+'</span></td>';
                    html += '<td>'+txtStatus+""+'</td>';
                    html += '</tr>';
                })
                $('#id_tbl_ongoing tbody').html(html);
                initTable($('#id_tbl_ongoing'));
                
            }else{

            }
        },
        error: errorAjax
    })
}
function getDetailMeeting(){

}

function getTodayYears(){
    var tm = moment().year(); 
    $('#id_select_years_transaction').html(tm)
    $('#id_select_years_top_room').html(tm)
}
function getMorrisLineTopRoom($data) {
    try{
        chartTransaction.destroy();
    }catch(error){

    }
    
    var arRoom = [];
    var dataTotal = [];
    var color = [];
    
    $.each($data, (index, item)=>{
        arRoom.push(item.name || "Unknown Desk");
        dataTotal.push(item.total || 0);
        color.push('#4285F4'); // Google Blue from screenshot
    })
    
    var config = {
            type: 'horizontalBar',
            data: {
                labels: arRoom,
                datasets: [{
                    data: dataTotal,
                    backgroundColor: color,
                    borderWidth: 0,
                    barPercentage: 0.5,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: '#6B7280'
                        },
                        gridLines: {
                            color: '#F3F4F6',
                            zeroLineColor: '#E5E7EB'
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            fontColor: '#374151',
                            fontFamily: "'Inter', sans-serif",
                            fontSize: 13
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        };
    chartTransaction = new Chart(document.getElementById("line_chart_top_room").getContext("2d"), config);
}

function getMorrisLineTransaction($data) {
    try{
        chartTopRoom.destroy();
    }catch(error){

    }
    var arMonth = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "Desember"];
    var dataMonthTrs1 = [];
    $.each($data, (index, item)=>{
        dataMonthTrs1.push(item.total)
    })
    var config = {
            type: 'line',
            data: {
                labels: arMonth,
                datasets: [{
                    label: "Max Transaction ",
                    data: dataMonthTrs1,
                    borderColor: 'rgba(211, 47, 47,0.7)',
                    backgroundColor: 'rgba(239, 83, 80,0.3)',
                    pointBorderColor: 'rgba(0, 188, 212, 0)',
                    pointBackgroundColor: 'rgba(255, 0, 0, 0.9)',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false }
            }
        };
    chartTopRoom = new Chart(document.getElementById("line_chart_transaction").getContext("2d"), config);
}

    function errorAjax(xhr, ajaxOptions, thrownError){
            $('#id_loader').html('');
            if(ajaxOptions == "parsererror"){
                var msg = "Status Code 500, Error Server bad parsing";
            }else{
                var msg ="Status Code "+ xhr.status + " Please check your connection !!!";
            }
    }
<html>
<title>
    Export PDF
    </title>
<style>
    table {  
    font-family: arial, sans-serif;  
    border-collapse: collapse;  
    width: 100%;  
}  

td {  
    border: 1px solid #dddddd;  
    text-align: left;  
    padding: 8px;  
} 
th{
    border: 1px solid #dddddd;  
    text-align: left;  
    padding: 8px;  
    background-color: #111;  
    color:white;
}

tr:nth-child(odd) {  
    background-color: #dddddd;  
}
</style>
<form class="form">
    <table>
        <thead>
            <tr>
                <th colspan="7">
                    <center>Desk Transaction <b id="status"></b> <b id="date"></b></center>
                </th>
            </tr>
            <tr>
                <th colspan="7"></th>
            </tr>
            <tr>
                <th>No.</th>
                <th>ID</th>
                <th>Title</th>
                <th>Room - Desk</th>
                <th>Date Time</th>
                <th>Status</th>
                <th>Organizer</th>
            </tr>
        </thead>
        <tbody id="tbody">
        </tbody>
    </table><br>
</form>
<textarea style="display:none;" id="data"><?= json_encode($data)?></textarea>
<input type="hidden" id="start" value="<?= $start?>">
<input type="hidden" id="end" value="<?= $end?>">
<script type="text/javascript"></script>
<script src="<?php echo base_url('assets/theme/plugins/jquery/jquery.min.js');?>"></script>
<!-- Moment Plugin Js -->
<script src="<?= base_url()?>assets/theme/plugins/momentjs/moment.js"></script>
<!-- <script src="<?= base_url()?>assets/external/numeral/numeral.min.js"></script> -->
<script src="<?= base_url()?>assets/external/numeral/locale.id.js"></script>
<script src="<?php echo base_url('assets/external/jspdf.min.js');?>"></script>
<script>
var start = $('#start').val()
var end = $('#end').val()
var data = JSON.parse($('#data').val());
var collection = data.data;
var status = `<?= $status?>`;
var status2 = `<?= $status?>`;

 var form = $('.form'),
        cache_width = form.width(),
        a4 = [595.28, 841.89]; // for a4 size paper width and height  
$(document).ready(function() {
    $('#date').html(moment(start).format("DD MMMM YYYY") + " - " + moment(end).format("DD MMMM YYYY"))
    $('#status').html(status)
    init();
    setTimeout(function(){
        createPDF()
    },100)

   

   
    

    
});
function createPDF() {
        var title = "desk_export_"+status2+"_"+moment(start).format("DDMMMMYYYY")+"_"+moment(end).format("DDMMMMYYYY")+"_"+moment().format("YYYYMMDDHHmmss");
        getCanvas().then(function(canvas) {
            var
                img = canvas.toDataURL("image/png"),
                doc = new jsPDF({
                    unit: 'px',
                    format: 'a4'
                });
            doc.addImage(img, 'JPEG', 20, 20);
            doc.save(title+'.pdf');
            form.width(cache_width);

            window.close();
        });

    }
    function getCanvas() {
        form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');
        return html2canvas(form, {
            imageTimeout: 2000,
            removeContainer: true
        });
    }

function init() {
    html = ``;
    var numm = 0;
    for (var x in collection) {
        var item = collection[x];
        var $start = moment(item.start).format("HH:mm ");
        var extendedDur = item.extended_duration - 0;
        var $end = moment(item.end).add(extendedDur, 'minutes').format("HH:mm ");
        var datebooking = moment(item.date).format("DD MMMM YYYY");
        if (moment().unix() > moment(item.end).unix()) {
            status = "<b style='color:red;'>Expired</b>"
        } else if (item.end_early_meeting == 1) {
            status = "<b style='color:light-green;'>Expired, End Early</b>";
        } else if (
            (moment().unix() <= moment(item.end).unix() &&
                moment().unix() >= moment(item.start).unix()) || item.status == "active"
        ) {
            status = "<b style='color:light-green;'>Active</b>"; // meeting dimulai
        } else if (
            moment().unix() <= moment(item.date + " " + item.start).unix()
        ) {
            status = "<b style='color:blue;'>Soon</b>"; // antrian
        }
        if (item.status == "soon") {
            if (item.is_rescheduled == 1) {
                status = "<b style='color:blue;'>Soon, Reschedule</b>"; // antrian
            }
        }
        if (item.status == "cancel") {
            status = "<b style='color:red;'>Canceled</b>"; // antrian
        }
        if (item.status == "expired") {
            status = "<b style='color:red;'>Expired</b>"; // antrian
        }
        html += '<tr data-id="' + item.booking_id + '">'
        html += '<td>' + numm + '</td>';
        html += '<td>' + item.booking_id + '</td>';
        html += '<td data-field="name">' + item.title + '</td>';
        html += `<td>${item.room_name} <br> <small><b>${item.desk_name}</b></small></td>`; // 
        html += `<td>${datebooking} <br> <small>${$start} - ${$end}</small></td>`;
        html += '<td>' + status; + '</td>';
        html += `<td>${item.pic}</td>`;
        html += '</tr>';
    }
    $('#tbody').html(html)
    
}
</script>

</html>
var nodemailer = require('nodemailer');
var fs = require('fs');
var moment = require('moment');
const conn = require('./conn');
var mailcfg = fs.readFileSync(__dirname+'/config/email.json', 'utf8');
var mailconfig = JSON.parse(mailcfg);
var gSendEmail = 0;
var url_participanInternal = "http://localhost/SMR_WEB/participant/internal/booking/:booking_id/employee/:employee/attendance/:attendance";
var url_participanEksternal = "http://localhost/SMR_WEB/participant/eksternal/booking/:booking_id/email/:email/attendance/:attendance";
var collectDataNotif = [];
var ginternal_array = [];
var geksternal_array = [];

async function email_service_booking_send_invitation(){
  var data = await getBookingReminder();
  var template = await getSettingTemplateEmail();
  var template_data = {
    data1 :  template[0],
    data2 :  template[1],
    data3 :  template[2],
  };
  if(data.length == 0 ){
    console.log("debug :> data email....", data.length, moment().format("DD MMMM YYYY HH:mm:ss ") );
    return false;
  }
  var datacompany = await getinfocompany();
  var getSMTP = await getSMTPData();
  getSMTP = getSMTP[0];
  if(getSMTP['is_enabled'] == 1){
    var company = datacompany[0];
    for(var i in data){
      var getBATCH =  JSON.parse(data[i].batch);
      var internal = getBATCH['internal']; 
      var eksternal = getBATCH['eksternal']; 
      for(var x in internal ){
        gSendEmail +=1;
      }
      for(var x in eksternal ){
        gSendEmail +=1;
      }
      for(var ss in internal ){
        var cbinternal = await sendEmailBookingInternal(internal[ss], data[i], getSMTP, template_data);
      
      }
      for(var es in eksternal ){
        var cbeksternal = await sendEmailBookingEksternal(eksternal[es], data[i], company, getSMTP, template_data);
      }
      i
      var remove_info_email = await remove_info_sending(data[i]['id']);

    }
  } // data
  else{
    console.log("not enabled");
  }
  
}

async function getBookingReminder(){
  return new Promise(resolve => {
        var query = 'SELECT r.location as room_location, r.name as room_name, b.pic, se.*, b.title, b.date, \
              b.start, b.end FROM sending_email se LEFT JOIN booking b ON se.booking_id=b.booking_id  ';
        query += ' LEFT JOIN room r ON b.room_id=r.radid ';
        query += ' WHERE se.is_status!=0 ';
        conn.query(query,
            [ 0 ], function (error, rows, fields){
            if(error){
                console.log("error=> ",error)
            } else{
                resolve(rows); //Kembalian berupa kontak data
            }
        });
    });
}
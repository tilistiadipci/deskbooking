var fs = require('fs');
var moment = require('moment');
const conn = require('./conn');
var config = require('./config');
const request = require('request')

var reminderBeforeEnd =[];
booking_services_notif_before_end();
booking_services_expires()
setInterval(function (){
	booking_services_expires();
	booking_services_notif_before_end();
}, 5000)

const express = require('express')
const app = express()
const portdata = 8002

app.listen(portdata, () => {
  console.log(`service_booking_expired listening on port ${portdata}`)
})

async function booking_services_expires(){
	console.log("run booking_services_expires",moment().format('YYYY-MM-DD HH:mm'))
	var past = await checkExpiredMeetingPast();
	var today = await checkExpiredMeetingToday();
	if(past.length > 0){
		for(var i in past){
			var cbinternal = await postExpired(past[i]['booking_id']);
		}
	}
	if(today.length > 0){
		for(var i in today){
			var cbinternal = await postExpired(today[i]['booking_id']);
		}
	}
}

async function booking_services_notif_before_end(){
    console.log("run booking_services_notif_before_end",moment().format('YYYY-MM-DD HH:mm'))
    var notifconfig = await getDataNotif();
    notifconfig = notifconfig[0];
	var configRaw 	= await getConfig();
	var config 		= configRaw[0];
    var notif       = await notifBeforeEnd(config['extend_meeting_notification']);
	// var notif 		= await notifBeforeEnd(1);
	if(notif.length > 0){
		for(var i in notif){
            var row = notif[i]
            if(reminderBeforeEnd.indexOf(row.booking_id) >= 0){

                continue;
            }
            var starttime = row.start;
            var timenow = moment();
            var timebooking = moment(starttime);
            var duration = moment.duration(timebooking.diff(timenow));
            var invitation = await getInvitation(row.booking_id);


            var to = notifconfig.topics;
            for(var x in invitation){
                var starttimeText = timebooking.format("DD MMMM YYYY HH:mm ");
                var invilist = invitation[x]; 
                var topic = to+invilist.nik;
                var title = "Reminder, your "+row.title + ", It will be over soon";
                var body = row.room_name + " at "+starttimeText;
                var payloads = fcmtopics(topic, title, body);
                // await fcmsendmessage(notifconfig, payloads);
                // console.log(payloads)

            }
            reminderBeforeEnd.push(row.booking_id);
			// var cbinternal = await postNotifBeforeExtendMeeting(row['booking_id']);
		}
	}
	
}

async function getDataNotif(){
  return new Promise(resolve => {
        var query = 'SELECT * ';
        query += ' FROM  notification_config  ';
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
async function getConfig(){
	return new Promise(resolve => {
        var datetime = moment().format("YYYY-MM-DD  HH:mm:ss ");
		// var datetime = moment().format("DD MMMM YYYY HH:mm:ss ");
		var date = moment().format("YYYY-MM-DD");
		var time = moment().format("HH:mm:ss");
        var query = 'SELECT * FROM setting_rule_booking  ';
        // console.log(query);
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

async function getInvitation(id){
  return new Promise(resolve => {
        var query = 'SELECT *  ';
        query += ' FROM  desk_booking_invitation   ';
        query += ' WHERE booking_id ="'+id+'"  ';
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
async function notifBeforeEnd(numBefore){
	return new Promise(resolve => {
        var datetime = moment().format("YYYY-MM-DD  HH:mm:ss ");
		// var datetime = moment().format("DD MMMM YYYY HH:mm:ss ");
		var date = moment().format("YYYY-MM-DD");
		var time = moment().format("HH:mm:ss");
        var query = 'SELECT *, TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) as end_dur FROM desk_booking WHERE date = "'+date+'" AND is_expired=0 AND is_canceled="0"  ';
        query += ' AND is_notif_before_end_meeting=0  ';
        query += ' AND DATE_ADD(TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)), INTERVAL -'+numBefore+' MINUTE) < "'+time+'"  ';
        // console.log(query);
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
async function checkExpiredMeetingPast(){
	return new Promise(resolve => {
        var datetime = moment().format("YYYY-MM-DD  HH:mm:ss ");
		// var datetime = moment().format("DD MMMM YYYY HH:mm:ss ");
		var date = moment().format("YYYY-MM-DD");
		var time = moment().format("HH:mm:ss");
        var query = 'SELECT * FROM desk_booking WHERE date < "'+date+'" AND is_expired=0 AND is_canceled="0" AND is_alive=1  ';
        // console.log(query);
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
async function checkExpiredMeetingToday(){
	return new Promise(resolve => {
        var datetime = moment().format("YYYY-MM-DD  HH:mm:ss ");
		// var datetime = moment().format("DD MMMM YYYY HH:mm:ss ");
		var date = moment().format("YYYY-MM-DD");
		var time = moment().format("HH:mm:ss");
        var query = 'SELECT * FROM desk_booking WHERE date ="'+date+'" AND is_expired=0 AND is_canceled="0"  ';
        	query += 'AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) < "'+time+'"  AND is_alive=1  ';
        // console.log(query);
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

async function postExpired(booking_id){
	return new Promise(resolve => {
        var datetime = moment().format("YYYY-MM-DD  HH:mm:ss ");
		// var datetime = moment().format("DD MMMM YYYY HH:mm:ss ");
        var query = 'UPDATE desk_booking SET status="expired", is_alive=4, is_expired=1, expired_by="sistem", expired_at="'+datetime+'"  WHERE booking_id=? ';
        conn.query(query,
            [ booking_id ], function (error, rows, fields){
            if(error){
                console.log("error=> ",error)
            } else{
            	console.log("Expired Booking ID => ", booking_id, datetime);
                resolve(rows); //Kembalian berupa kontak data
            }
        });
    });
}
async function postNotifBeforeExtendMeeting(booking_id){
	return new Promise(resolve => {
        var datetime = moment().format("YYYY-MM-DD  HH:mm:ss ");
		// var datetime = moment().format("DD MMMM YYYY HH:mm:ss ");
		// console.log(id)
        var query = 'UPDATE desk_booking SET is_notif_before_end_meeting=1 WHERE booking_id=? ';
        conn.query(query,
            [ booking_id ], function (error, rows, fields){
            if(error){
                console.log("error=> ",error)
            } else{
            	console.log("Notif Booking ID => ", booking_id, datetime);
                resolve(rows); //Kembalian berupa kontak data
            }
        });
    });
}

function fcmtopics(topic, title, body){
    var ar = {
        'to' : '/topics/'+topic,
        'notification' : {
            'title':title,
            'body':body,
            'priority':'high',
            'content_available':true,
        },
        'data': {
            'title':title,
            'body':body,
            'priority':'high',
            'content_available':true,
        }
    }
    return JSON.stringify(ar);
}

async function fcmsendmessage(config, payloads){
    // console.log(config,payloads)
    if(config.active ==1){
            var url = config.url;
            var auth = config.authorization;
            var h = {
                'Authorization':auth,
                'Content-Type' : 'application/json'
            }
        sendPOST(h, url, JSON.parse(payloads));
    }   
}

async function sendPOST(header, url, payloads){
  return new Promise(resolve => {
        request.post(url, {
          headers : header,
          json: payloads
        }, (error, res, body) => {
          if (error) {
            console.log("error=> ",error)
            // return
          }else{
            console.log(`statusCode: ${res.statusCode}`)
            console.log(body)
          }
          
        })

    });
}
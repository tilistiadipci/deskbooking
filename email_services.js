var nodemailer = require('nodemailer');
var atob = require('atob');
var fs = require('fs');
var moment = require('moment');
const conn = require('./conn');
var config = require('./config');
const request = require('request')
const imageToBase64 = require('image-to-base64');
// var Blob = require('blob');
// var URL = require('url');
// var createObjectURL = require('create-object-url');
// const Blob = require('node-blob');
// const Window = require('window');
// const window = new Window();
// var b64toBlob = require('b64-to-blob');

// var mailcfg = fs.readFileSync(__dirname+'/config/email.json', 'utf8');
var mailconfig = config.emailServer;
var mailconfigLocal = config.emailServer;
var gSendEmail = 0;
var url_participanInternal = config.server+"participant/internal/booking/:booking_id/employee/:employee/attendance/:attendance";
var url_participanEksternal = config.server+"participant/eksternal/booking/:booking_id/email/:email/attendance/:attendance";

var ginternal_array = [];
var geksternal_array = [];
var reminderOfDayBefore = [];
var reminderOfDayUnsed = [];
var reminderOfDayClosed = [];

// testoutlook();
// email_service_booking_send_invitation();
setInterval(function (){
	// email_service_booking_send_invitation()

}, 30000)
setInterval(function (){
	check_reminder_before();
	check_reminder_meeting_unused();
}, 10000)


async function check_reminder_before(){
	var automation = await getBookingAutomationModule();
	var enabledAutomation = automation.length>0 ? true : false;

	var data = await getBookingReminderBefore();
	var notifconfig = await getDataNotif();
	var ruleconfig = await getDataRuleBooking();
	notifconfig = notifconfig[0];
	ruleconfig = ruleconfig[0];
	if(data.length > 0 ){
		for(var i in data){
			var row = data[i];
			var ruletime = ruleconfig.max_end_meeting;
			// var ruletime = 215;
			var ruletime5 = ruletime +5;
			var starttime = row.start;
			var timenow = moment();
			var timebooking = moment(starttime);
			var duration = moment.duration(timebooking.diff(timenow));
			var dur_minutes = duration.as('minutes');
			// console.log(dur_minutes, ruletime)
			if( dur_minutes >= ruletime && dur_minutes <= ruletime5){

				 var checkdata = reminderOfDayBefore.indexOf(row.booking_id)
				 if(checkdata < 0){
					// console.log(dur_minutes, ruletime)
				 	var invitation = await getInvitation(row.booking_id);
				 	var to = notifconfig.topics;
				 	for(var x in invitation){
				 		var starttimeText = timebooking.format("DD MMMM YYYY HH:mm ");
				 		var invilist = invitation[x]; 
				 		var topic = to+invilist.nik;
				 		var title = "Reminder, your meeting "+row.title;
				 		var body = row.room_name + " at "+starttimeText;
				 		var payloads = fcmtopics(topic, title, body);
				 		await fcmsendmessage(notifconfig, payloads);
				 	}
				 	reminderOfDayBefore.push(row.booking_id);
				 	if(enabledAutomation){
						if(row.is_automation == 1){
							var h = {
								'Content-Type' : 'application/json'
							}
							sendPOST(h, url, JSON.parse(payloads));
						}
					}
					console.log("debug :> send reminder meeting "+row.title+" ....", moment().format("DD MMMM YYYY HH:mm:ss ") );
				 }
			}
		}
	}
}
async function check_reminder_meeting_unused(){
	console.log("debug :> run check_reminder_meeting_unused " );
	// var data = await getBookingReminderBefore(); // testing only
	var data = await getBookingReminderUnused();
	var notifconfig = await getDataNotif();
	var ruleconfig = await getDataRuleBooking();
	notifconfig = notifconfig[0];
	ruleconfig = ruleconfig[0];
	if(data.length > 0 ){
		for(var i in data){
			var row = data[i];
			var logDoor = await getLogDoor(row.booking_id);
			var unused = ruleconfig.if_unused_room;
			var ruletime = ruleconfig.notif_unuse_before_meeting; // time data before meeting
			// console.log("debug :> run check_reminder_meeting_unused ", logDoor );
			// var ruletime = -190;
			var ruletime5 = ruletime +10;
			var starttime = row.start;
			var timenow = moment();
			var timebooking = moment(starttime);
			var duration = moment.duration(timenow.diff(timebooking));

			var dur_minutes = duration.as('minutes');
			if( dur_minutes >= ruletime && dur_minutes <= ruletime5 && unused ==1 ){
				 var checkdata = reminderOfDayUnsed.indexOf(row.booking_id)
				 var number_attend_room = logDoor.length;
				console.log("debug :> run check_reminder_meeting_unused ", dur_minutes, ruletime)

				if(checkdata < 0 && number_attend_room <= 0){
				 	var invitation = await getInvitation(row.booking_id);
				 	var to = notifconfig.topics;
				 	for(var x in invitation){
				 		var starttimeText = timebooking.format("DD MMMM YYYY HH:mm ");
  						var datetime = moment().format("YYYY-MM-DD HH:mm:ss")
				 		var invilist = invitation[x]; 
				 		var topic = to+invilist.nik;
				 		var title = "Reminder, your meeting of "+row.title+" unused";
				 		var body = row.room_name + " at "+starttimeText;
				 		var payloads = fcmtopics(topic, title, body);
				 		var dataInsert = {
				 			nik : invilist.nik,
				 			type : 3,
				 			datetime : datetime,
				 			title : title,
				 			body : body,
				 			value : row.booking_id,
				 			is_sending : 1,
				 			is_deleted : 0,
				 			created_at : datetime
				 		}
				 		var insertnotif = await insertNotif(dataInsert);
				 		var rpfcm = await fcmsendmessage(notifconfig, payloads);
				 	}
				 	reminderOfDayUnsed.push(row.booking_id);
					console.log("debug :> check_reminder_meeting_unused"," send reminder end meeting "+row.title+" ....", moment().format("DD MMMM YYYY HH:mm:ss ") );
				 }
			}

		}
	}
}

async function meeting_unused_expired(){
	
}

async function check_reminder_meeting_closed(){
	
}


async function openDataEmail(){
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


async function openDataBookingToday(){
	return new Promise(resolve => {
		var datetime = moment().format("DD MMMM YYYY HH:mm:ss ");
		var date = moment().format("YYYY-MM-DD");
		var time = moment().format("HH:mm:ss");
        var query = 'SELECT * FROM booking WHERE date="'+date+'" ';
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


async function getSMTPData(){
	return new Promise(resolve => {
        var query = 'SELECT  *  FROM setting_smtp  ';
        query += ' WHERE is_deleted=0 ';
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

async function getSettingTemplateEmail(){
	return new Promise(resolve => {
        var query = 'SELECT  *  FROM setting_email_template  ';
        query += '  ';
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

async function getinfocompany(){
	return new Promise(resolve => {
        var query = 'SELECT  * FROM company  ';
        // query += ' INNER JOIN room r ON b.room_id=r.id ';
        conn.query(query, function (error, rows, fields){
            if(error){
                console.log("error=> ",error)
            } else{
                resolve(rows); //Kembalian berupa kontak data
            }
        });
    });
}

async function remove_info_sending(id){
	return new Promise(resolve => {
		// console.log(id)
        var query = 'UPDATE sending_email SET is_status=0 WHERE id=? ';
        conn.query(query,
            [ id ], function (error, rows, fields){
            if(error){
                console.log("error=> ",error)
            } else{
                resolve(rows); //Kembalian berupa kontak data
            }
        });
    });
}

async function getBookingReminderBefore(){
  return new Promise(resolve => {
  		var datenow = moment().format("YYYY-MM-DD")
  		var timenow = moment().format("YYYY-MM-DD HH:mm:ss")
        var query = 'SELECT b.*, r.name room_name, r.location room_location, r.automation_id, r.is_automation, ra.ip_address,ra.devices ';
        query += ' FROM  booking b  ';
        query += ' LEFT JOIN room r ON b.room_id=r.radid ';
        query += ' LEFT JOIN room_automation ra ON r.automation_id=ra.id ';
        query += ' WHERE b.date="'+datenow+'" AND b.start>"'+timenow+'" AND is_expired=0 AND is_notif_before_end_meeting=0 ';
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

async function getBookingAutomationModule(){
  return new Promise(resolve => {
        var query = 'SELECT *  ';
        query += ' FROM  module_backend m ';
        query += ' WHERE m.module_text="module_automation" AND m.is_enabled=1 ';
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
async function getBookingReminderUnused(){
  return new Promise(resolve => {
  		var datenow = moment().format("YYYY-MM-DD")
  		var timenow = moment().format("YYYY-MM-DD HH:mm:ss")
        var query = 'SELECT b.*, r.name room_name, r.location room_location  ';
        query += ' FROM  booking b  ';
        query += ' LEFT JOIN room r ON b.room_id=r.radid ';
        query += ' WHERE b.date="'+datenow+'" AND b.start<"'+timenow+'" AND b.end >"'+timenow+'" AND is_expired=0 AND is_notif_before_end_meeting=0 ';
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
async function getBookingExpiredUnsed(){
  return new Promise(resolve => {
  		var datenow = moment().format("YYYY-MM-DD")
  		var timenow = moment().format("YYYY-MM-DD HH:mm:ss")
        var query = 'SELECT b.*, r.name room_name,r.price, r.location room_location, at.invoice_status type_invoice_status, a.invoice_status alocation_invoice_status  ';
        query += ' FROM  booking b  ';
        query += ' LEFT JOIN room r ON b.room_id=r.radid ';
        query += ' LEFT JOIN alocation a ON b.alocation_id=a.id ';
        query += ' LEFT JOIN alocation_type at ON a.type=at.name ';
        query += ' WHERE b.date="'+datenow+'" AND b.start<"'+timenow+'" AND b.end >"'+timenow+'" AND b.is_alive=1 ';
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
async function getLogDoor(id){
  return new Promise(resolve => {
        var query = 'SELECT *  ';
        query += ' FROM log_access_room   ';
        query += ' WHERE booking_id ="'+id+'"  AND status=1 ';
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
        query += ' FROM  booking_invitation   ';
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

async function getDataRuleBooking(){
  return new Promise(resolve => {
        var query = 'SELECT * ';
        query += ' FROM  setting_rule_booking  ';
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

async function insertNotif(payloads){
	return new Promise(resolve => {
        var query = 'INSERT INTO notification_data SET ? ';
        conn.query(query,payloads, function (error, rows, fields){
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
		var datetime = moment().format("DD MMMM YYYY HH:mm:ss ");
		// console.log(id)
        var query = 'UPDATE booking SET  is_alive=4, is_expired=1 WHERE booking_id=? ';
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

async function postExpiredBookingUnsed(booking_id, price =0,note=""){
	return new Promise(resolve => {
		var datetime = moment().format("YYYY-MM-DD  HH:mm:ss ");
        var query = 'UPDATE booking SET is_alive=4, cost_total_booking='+price+', is_expired=1, expired_by="sistem", expired_at="'+datetime+'" ,note="'+note+'"  WHERE booking_id=? ';
        conn.query(query,
            [ booking_id ], function (error, rows, fields){
            if(error){
                console.log("error=> ",error)
            } else{
            	console.log(" debug :> postExpiredBookingUnsed Expired Booking ID => ", booking_id, datetime);
                resolve(rows); //Kembalian berupa kontak data
            }
        });
    });
}
async function postExpiredInvoiceUnsed(booking_id, price =0){
	return new Promise(resolve => {
		var datetime = moment().format("YYYY-MM-DD  HH:mm:ss ");
        var query = 'UPDATE booking_invoice SET rent_cost='+price+', updated_by="sistem", updated_at="'+datetime+'" WHERE booking_id=? ';
        conn.query(query,
            [ booking_id ], function (error, rows, fields){
            if(error){
                console.log("error=> ",error)
            } else{
            	console.log(" debug :> postExpiredBookingUnsed Expired Booking ID => ", booking_id, datetime);
                resolve(rows); //Kembalian berupa kontak data
            }
        });
    });
}


async function testoutlook(){
	var getSMTP = await getSMTPData();
	var datasmtp = getSMTP[0];
	var uid = moment().format('x');
	var datetime = moment().format("YYYY-MM-DD  HH:mm:ss ");
	var $from_name = "Tommy May Perdana";
	var $from_address = "tmperdana157@gmail.com";
	var $to_name = "Yuansyah";
	var $to_address = "yuan@solusicreative.com";
	var $startTime = "12/28/2020 16:00:00";
	var $endTime = "12/28/2020 17:30:00";
	var $subject = "Reminder for event";
	var $description = "eminder for event";
	var $location = "Your Location";
	var $domain = 'bio-experience.com';
	var $mime_boundary = "----Meeting Booking----"+moment().format('x');
	var $message = "--"+$mime_boundary+"\r\n";
	$message += "Content-Type: text/html; charset=UTF-8\n";
	$message += "Content-Transfer-Encoding: 8bit\n\n";
	$message += "&lt;html&gt;\n";
	$message += "&lt;body&gt;\n";

	$message += 'Demo Message';

	$message += "&lt;/body&gt;\n";
	$message += "&lt;/html&gt;\n";
	$message += "--"+$mime_boundary+"\r\n";
	var headers= {
        'MIME-Version': '1.0',
        'Content-Type': 'multipart/alternative; boundary="'+$mime_boundary+'" ',
        'Content-class': 'urn:content-classes:calendarmessage',
    }

	var $ical = 'BEGIN:VCALENDAR' + "\r\n" +
	'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' + "\r\n" +
	'VERSION:2.0' + "\r\n" +
	'METHOD:REQUEST' + "\r\n" +
	'BEGIN:VTIMEZONE' + "\r\n" +
	'TZID:Eastern Time' + "\r\n" +
	'BEGIN:STANDARD' + "\r\n" +
	'DTSTART:20091101T020000' + "\r\n" +
	'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' + "\r\n" +
	'TZOFFSETFROM:-0400' + "\r\n" +
	'TZOFFSETTO:-0500' + "\r\n" +
	'TZNAME:EST' + "\r\n" +
	'END:STANDARD' + "\r\n" +
	'BEGIN:DAYLIGHT' + "\r\n" +
	'DTSTART:20090301T020000' + "\r\n" +
	'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' + "\r\n" +
	'TZOFFSETFROM:-0500' + "\r\n" +
	'TZOFFSETTO:-0400' + "\r\n" +
	'TZNAME:EDST' + "\r\n" +
	'END:DAYLIGHT' + "\r\n" +
	'END:VTIMEZONE' + "\r\n" +
	'BEGIN:VEVENT' + "\r\n" +
	'ORGANIZER;CN="'+$from_name+'":MAILTO:'+$from_address+ "\r\n" +
	'ATTENDEE;CN="'+$to_name+'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'+$from_address+ "\r\n" +
	'LAST-MODIFIED:' + datetime + "\r\n" +
	'UID:'+uid+"@"+$domain+"\r\n" +
	'DTSTAMP:' + datetime + "\r\n" +
	'DTSTART;TZID="Pacific Daylight":' + datetime +  "\r\n" +
	'DTEND;TZID="Pacific Daylight":' +datetime+ "\r\n" +
	'TRANSP:OPAQUE' + "\r\n" +
	'SEQUENCE:1' + "\r\n" +
	'SUMMARY:' + $subject + "\r\n" +
	'LOCATION:' + $location + "\r\n" +
	'CLASS:PUBLIC' + "\r\n" +
	'PRIORITY:5'+ "\r\n" +
	'BEGIN:VALARM' + "\r\n" +
	'TRIGGER:-PT15M' + "\r\n" +
	'ACTION:DISPLAY' + "\r\n" +
	'DESCRIPTION:Reminder' + "\r\n" +
	'END:VALARM' + "\r\n" +
	'END:VEVENT'+ "\r\n" +
	'END:VCALENDAR'+ "\r\n";
	$message += 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST'+"\n";
	$message += "Content-Transfer-Encoding: 8bit\n\n";
	$message += $ical;
	// let content = 'BEGIN:VCALENDAR\r\nPRODID:-//ACME/DesktopCalendar//EN\r\nMETHOD:REQUEST\r\n...';
	var transporter = nodemailer.createTransport({
		  pool: true,
		  host: datasmtp.host,
		  port: datasmtp.port,
		  tls: {rejectUnauthorized: false},
		  secure: datasmtp.secure, // use TLS
		  auth: {
		    user: datasmtp.user,
		    pass: datasmtp.password
		  }
	});
	var mailOptions = {
		from: $from_address, // sender address
		to: $to_address , // list of receivers
		subject: "Invitation Meeting appointment" , // Subject line
		text: 'Please see the attached appointment',
		// html: $message // plain text body
		icalEvent: {
	        filename: 'invitation.ics',
	        method: 'request',
	        content: $ical
	    }
	};

	// transporter.sendMail({
 //        from: $from_address,
 //        to: $to_address,
 //        subject: 'Meeting',
 //        html: "Hiya!!",
 //        text: "Hola!!",
 //        alternatives: [{
 //          contentType: "text/calendar",
 //          content: new Buffer($ical)
 //        }]
 //        }, function(err, responseStatus) {
 //        if (err) {
	// 		console.log("Error eksternal")
 //            // console.log(err);
 //            // res.render('schedule',{errors: err.message});
 //        } else {
 //            // console.log(responseStatus.message);
	// 		console.log("Success sending eksternal to "+mailOptions.to)
 //            // res.render('schedule',{success_msg: "Successfully Created!"});
 //        }
 //    });

	// console.log({
	// 	  pool: true,
	// 	  host: datasmtp.host,
	// 	  port: datasmtp.port,
	// 	  tls: {rejectUnauthorized: false},
	// 	  secure: datasmtp.secure, // use TLS
	// 	  auth: {
	// 	    user: datasmtp.user,
	// 	    pass: datasmtp.password
	// 	  }
	// })
	
	if(true){
		transporter.sendMail(mailOptions, function (err, info) {
			if(err){
				console.log("Error eksternal")
				console.log(err)
				resolve(null);
			}
			else{
				console.log("Success sending eksternal to "+mailOptions.to)
				resolve(info);
				// gSendEmail = gSendEmail-1;

			}
		});
	}else{
		resolve(null);
	}
}

function b64toBlob (b64Data, contentType='', sliceSize=512)  {
  const byteCharacters = atob(b64Data);
  const byteArrays = [];

  for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
    const slice = byteCharacters.slice(offset, offset + sliceSize);

    const byteNumbers = new Array(slice.length);
    for (let i = 0; i < slice.length; i++) {
      byteNumbers[i] = slice.charCodeAt(i);
    }

    const byteArray = new Uint8Array(byteNumbers);
    byteArrays.push(byteArray);
  }

  const blob = new Blob(byteArrays, {type: contentType});
  return blob;
}




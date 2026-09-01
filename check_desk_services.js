var fs = require('fs');
var moment = require('moment');
const conn = require('./conn');
var config = require('./config');
const request = require('request')
const mqtt = require('mqtt');
var urlSocketOn = "http://:ip:3000/on/:socket";
var urlSocketOff = "http://:ip:3000/off/:socket";
var stringtemplateUploadDoor = '{"doorip":"","cmd":"","uid":"","user":"","acctype":"1","acctype2":"null","acctype3":"null","acctype4":"null","validuntil":""}';
// var admin = require("firebase-admin");
// var serviceAccount  = require('./config/fcm-privatekey.json');
// var topic = 'mobile';

const express = require('express')
const app = express()
const portdata = 8001

app.listen(portdata, () => {
  console.log(`service_booking listening on port ${portdata}`)
})
var mqtttopicglobal = config.mqtt.topics;
const clientId = `bookingapps_${Math.random().toString(16).slice(3)}`;
const connectUrl = `mqtt://${config.mqtt.host}:${config.mqtt.port}`;
var clientmqtt = mqtt.connect(connectUrl, {
  clientId,
  clean: true,
  connectTimeout: 4000,
  username: config.mqtt.user,
  password:  config.mqtt.pass,
  reconnectPeriod: 1000,
});
clientmqtt.on('connect', () => {
    console.log("MQTT Connected!");
});

checkMeetingToday();
checkMeetingAfterToday();
checkControllerConnection();

setInterval(function (){
	checkMeetingToday();
	checkMeetingAfterToday();
}, 5000)

setInterval(function (){
    checkControllerConnection();
}, 120000);

function sendPublishTopic(payload=""){
	clientmqtt.publish(mqtttopicglobal, payload, { qos: 0, retain: false }, (error) => {
	    if (error) {
	    }else{

	    }
	})
}

function sendPublishTopicDesk(topic, payload=""){
    console.log("MQTT Topic:", topic, "Payload:", payload);
    if (clientmqtt && clientmqtt.connected) {
        clientmqtt.publish(topic, payload, { qos: 0, retain: false }, (error) => {
            if (error) console.error("MQTT Publish Error:", error);
        });
    } else {
        console.log("MQTT Client not connected");
    }
}

async function checkMeetingToday(){
	console.log("run checkMeetingToday",moment().format('YYYY-MM-DD HH:mm'));
	var completetime = moment().format('YYYY-MM-DD HH:mm');
	var datenowunix = moment().format('X'); // unix
	var timenow = moment().format('X');

	var btanggal = moment().format('YYYY-MM-DD');

	var dataSetting = await openDataSetting();
	var waktuAccess = (dataSetting['notif_unuse_before_meeting'])-0; // waktu user dapet diinkan mengakses mins
	waktuAccess = 1; // waktu user dapet diinkan mengakses mins

	var data = await openDataMeeting();
	// return false;

	if(data == null){
		return false;
	}

	for (var i in data){
		var rowData = data[i];
		var bookingId = rowData.booking_id;
		var action = false;
		// var datemeeting = data[i].end;
		var datemeetingStart = data[i].start;

		var datemeetingEnd = data[i].end;
		// console.log()
		var datemeetingStartMoment = moment(datemeetingStart);
		var datemeetingAccess = datemeetingStartMoment.subtract(waktuAccess, 'minutes'); // - waktuAccess

		var extendedDur = data[i].extended_duration - 0;
		var datemeetingEndMoment = moment(datemeetingEnd).add(extendedDur, 'minutes');
		// var datemeetingAccessEnd = datemeetingEndMoment.add(waktuAccess+1, 'minutes');

		// var ip_access = rowData.ac_ip_controller;
		var datemeetingunix = datemeetingAccess.format('X'); // unix
		if(datenowunix >= datemeetingunix){ // sudah memasuki 15 mins
			// before 
			var sql = 'SELECT bi.*, e.card_number, e.name from  desk_booking_invitation bi  ';
        	sql += ' INNER JOIN employee e ON bi.nik=e.id ';
        	sql += ' WHERE bi.internal=1 AND bi.booking_id ="'+rowData['booking_id']+'" AND e.is_deleted=0 ';
        	sql += ' AND bi.execute_door_access=0';

			var rowback = await runSelectQuery(sql);
			for(var rv in rowback){
				var dataInv = rowback[rv];
				var h = {
					'Content-Type' : 'text/xml'
				}
				var url = urlSocketOn;
				url = url.replace(":ip", rowData.ip_address);
				url = url.replace(":socket", rowData.socket);
				var getResponse = await sendGET({},url);
				if (getResponse.error) {
				    console.error("Error GET " + url + " - Skipping status update", getResponse.error);
				    var act_event_id = "evt_" + Math.random().toString(36).substring(2, 10);
				    var act_event_time = moment().format('YYYY-MM-DDTHH:mm:ss');
				    var act_created_at = moment().format('YYYY-MM-DD HH:mm:ss');
				    var act_message = "Failed to connect to desk socket ON: " + url;
				    var insertAct = `INSERT INTO activity_log (event_id, event_time, code, name, category, severity, created_at, room_id, desk_id, booking_id, message) 
				                     VALUES ('${act_event_id}', '${act_event_time}', 'DESK_UNAVAILABLE', 'Desk Unavailable', 'DESK', 'error', '${act_created_at}', '${rowData.room_id}', '${rowData.desk_id}', '${bookingId}', '${act_message}')`;
				    await runActionQuery(insertAct);
				    continue;
				}
				var sqldesk = 'SELECT d.desk_id, d.zone_id, di.controller_id, di.socket from  desk_room_table d  ';
        		sqldesk += ' INNER JOIN desk_controller_initial di ON d.desk_id=di.desk_id ';
        		sqldesk += ' WHERE d.desk_id="'+rowData['desk_id']+'"';
				
				var rowdesk = await runSelectQuery(sqldesk);
				var payloadobj = { room_id: rowData.room_id, desk_id: rowData.desk_id, action: "on", socket: rowData.socket, booking_id: bookingId, card_number: dataInv.card_number, person_name: dataInv.name, action_at:now };
				if(rowdesk.length > 0){
					payloadobj.zone_id = rowdesk[0].zone_id;
					payloadobj.controller_id = rowdesk[0].controller_id;
				}
				var now = moment().format('YYYY-MM-DD HH:mm:ss');
                var topic = config.mqtt.topicExeDesk.replace('{roomId}', rowData.room_id).replace('{deskId}', rowData.desk_id);
                var payload = JSON.stringify(payloadobj);
                sendPublishTopicDesk(topic, payload);

				console.log("Socket on "+dataInv.card_number, "Room name "+rowData.room_name, "Desk "+rowData.desk_name);
				await delay(1000);
        		var update_sql = 'UPDATE desk_booking_invitation SET execute_door_access=1 WHERE id="'+dataInv['id']+'" ';
        		var update_sql2 = 'UPDATE desk_booking_invitation bi INNER JOIN desk_booking db ON bi.booking_id=b.booking_id SET bi.execute_door_access=2 WHERE b.date="'+btanggal+'" AND b.is_alive > 1 AND bi.execute_door_access<>2 ';
        		var update_sqlstatus = `UPDATE desk_booking SET status="active" WHERE booking_id="${bookingId}"`;
        		// console.log(update_sql);
				await runActionQuery(update_sqlstatus);
				await runActionQuery(update_sql);
				await runActionQuery(update_sql2);

				await delay(1000);
			}
		}
		
	}
}
async function checkMeetingAfterToday(){
	console.log("run checkMeetingAfterToday",moment().format('YYYY-MM-DD HH:mm'));
	var completetime = moment().format('YYYY-MM-DD HH:mm');
	var datenowunix = moment().format('X'); // unix
	var timenow = moment().format('X');
	var dataSetting = await openDataSetting();
	var waktuAccess = (dataSetting['notif_unuse_before_meeting'] == null ? 5 : dataSetting['notif_unuse_before_meeting'] )-0; // waktu user dapet diinkan mengakses mins
	waktuAccess = 1;
	var data = await openDataMeetingEnd();
	
	if(data == null){
		return false;
	}

	for (var i in data){
		var rowData = data[i];
		var action = false;
		// var datemeeting = data[i].end;
		var datemeetingStart = data[i].end; //end time
		if(rowData.end_early_meeting == 1){
			datemeetingStart = rowData.early_ended_at; //
			console.log("early ended", rowData.booking_id, datemeetingStart)
		}
		// console.log()


		var extendedDur = data[i].extended_duration - 0;
		var datemeetingStartMoment = moment(datemeetingStart);
		var datemeetingAccess = datemeetingStartMoment.add(extendedDur, 'minutes'); // - waktuAccess

		var datemeetingunix = datemeetingAccess.format('X'); // unix
		// var datainvitation = data[i].batch;
		if(datenowunix >= datemeetingunix){ // sudah memasuki 15 mins
			// before 
			var sql = 'SELECT bi.*, e.card_number, e.name from  desk_booking_invitation bi  ';
        	sql += ' INNER JOIN employee e ON bi.nik=e.id ';
        	sql += ' WHERE bi.internal=1 AND bi.booking_id ="'+rowData['booking_id']+'" AND e.is_deleted=0 ';
        	sql += ' AND bi.execute_door_access=1 ';
			var rowback = await runSelectQuery(sql);
			for(var rv in rowback){
				var dataInv = rowback[rv];
				var h = {
					'Content-Type' : 'text/xml'
				}
				var url = urlSocketOff;
				url = url.replace(":ip", rowData.ip_address);
				url = url.replace(":socket", rowData.socket);
				var getResponse = await sendGET({},url);
				if (getResponse.error) {
				    console.error("Error GET " + url + " - Skipping status update", getResponse.error);
				    var act_event_id = "evt_" + Math.random().toString(36).substring(2, 10);
				    var act_event_time = moment().format('YYYY-MM-DDTHH:mm:ss');
				    var act_created_at = moment().format('YYYY-MM-DD HH:mm:ss');
				    var act_message = "Failed to connect to desk socket OFF: " + url;
				    var insertAct = `INSERT INTO activity_log (event_id, event_time, code, name, category, severity, created_at, room_id, desk_id, booking_id, message) 
				                     VALUES ('${act_event_id}', '${act_event_time}', 'DESK_UNAVAILABLE', 'Desk Unavailable', 'DESK', 'error', '${act_created_at}', '${rowData.room_id}', '${rowData.desk_id}', '${rowData.booking_id}', '${act_message}')`;
				    await runActionQuery(insertAct);
				    continue;
				}
				var sqldesk = 'SELECT d.desk_id, d.zone_id, di.controller_id, di.socket from  desk_room_table d  ';
        		sqldesk += ' INNER JOIN desk_controller_initial di ON d.desk_id=di.desk_id ';
        		sqldesk += ' WHERE d.desk_id="'+rowData['desk_id']+'"';
				
				var rowdesk = await runSelectQuery(sqldesk);
				var payloadobj = { room_id: rowData.room_id, desk_id: rowData.desk_id, action: "on", socket: rowData.socket, booking_id: bookingId, card_number: dataInv.card_number, person_name: dataInv.name, action_at:now };
				if(rowdesk.length > 0){
					payloadobj.zone_id = rowdesk[0].zone_id;
					payloadobj.controller_id = rowdesk[0].controller_id;
				}

                var topic = config.mqtt.topicExeDesk.replace('{roomId}', rowData.room_id).replace('{deskId}', rowData.desk_id);
                var payload = JSON.stringify(payloadobj);
                sendPublishTopicDesk(topic, payload);
				var room_name = rowData.room_name ; // room_name / roomname 
				console.log("Socket off "+dataInv.card_number, "Room name "+rowData.room_name, "Desk "+rowData.desk_name);
        		var update_sql = 'UPDATE desk_booking_invitation SET execute_door_access=2 WHERE id="'+dataInv['id']+'" ';
				await runActionQuery(update_sql);
				await delay(1000);
			}
			var update_sql = `UPDATE desk_booking SET is_access_trigger=1, status="expired" WHERE booking_id="${rowData['booking_id']}" `;
			await runActionQuery(update_sql);
		}
		
	}
}


async function executeAddAccess(invitation, room){

}
async function checkControllerConnection() {
    console.log("run checkControllerConnection", moment().format('YYYY-MM-DD HH:mm'));
    var query = `SELECT dc.id as controller_id, dc.ip_address, dc.name, dci.desk_room_id as room_id, dci.desk_id, dci.socket, d.zone_id 
                 FROM desk_controller dc 
                 INNER JOIN desk_controller_initial dci ON dc.id = dci.controller_id 
                 LEFT JOIN desk_room_table d ON dci.desk_id = d.desk_id
                 WHERE dc.is_deleted = 0`;
    var rows = await runSelectQuery(query);
    if (!rows || rows.length === 0) return;
    
    var controllers = {};
    for (var r of rows) {
        if (!r.ip_address) continue;
        if (!controllers[r.ip_address]) {
            controllers[r.ip_address] = {
                ip: r.ip_address,
                desks: []
            };
        }
        controllers[r.ip_address].desks.push(r);
    }

    for (var ip in controllers) {
        var url = `http://${ip}:3000/ping`;
        var getResponse = await sendGET({}, url);
        
        var isConnected = false;
        if (!getResponse.error && getResponse.result) {
            var statusCode = getResponse.result.statusCode;
            if (statusCode === 200 || statusCode === 404) {
                isConnected = true;
            }
        }

        if (!isConnected) {
            console.log(`Controller ${ip} is NOT CONNECTED`);
            for (var desk of controllers[ip].desks) {
                var act_event_id = "evt_" + Math.random().toString(36).substring(2, 10);
                var act_event_time = moment().format('YYYY-MM-DDTHH:mm:ss');
                var act_created_at = moment().format('YYYY-MM-DD HH:mm:ss');
                var act_message = "Controller " + desk.name + " (" + ip + ") is NOT CONNECTED";
                
                var topic = config.mqtt.topicsActivitiesDesk;
                var payloadobj = { 
                    eventId: act_event_id,
                    eventTime: act_event_time,
                    code: "DESK_UNAVAILABLE",
                    name: "Desk Unavailable",
                    description: "Desk Unavailable",
                    category: "DESK",
                    severity: "error",
                    actorNik: "system",
                    ownerNik: null,
                    bookingId: null,
                    roomId: parseInt(desk.room_id),
                    deskId: parseInt(desk.desk_id),
                    previousStatus: null,
                    currentStatus: "not_connected",
                    source: "service",
                    message: act_message,
                    visibility: "private",
                    metadata: {
                        action: "not_connected",
                        socket: desk.socket,
                        controller_id: desk.controller_id,
                        zone_id: desk.zone_id,
                        action_at: act_created_at
                    }
                };
                sendPublishTopicDesk(topic, JSON.stringify(payloadobj));
                
                var insertAct = `INSERT INTO activity_log (event_id, event_time, code, name, category, severity, created_at, room_id, desk_id, message) 
                                 VALUES ('${act_event_id}', '${act_event_time}', 'DESK_UNAVAILABLE', 'Desk Unavailable', 'DESK', 'error', '${act_created_at}', '${desk.room_id}', '${desk.desk_id}', '${act_message}')`;
                await runActionQuery(insertAct);
            }
        } else {
            for (var desk of controllers[ip].desks) {
                var act_event_id = "evt_" + Math.random().toString(36).substring(2, 10);
                var act_event_time = moment().format('YYYY-MM-DDTHH:mm:ss');
                var act_created_at = moment().format('YYYY-MM-DD HH:mm:ss');
                var act_message = "Controller " + desk.name + " (" + ip + ") is CONNECTED";

                var topic = config.mqtt.topicsActivitiesDesk;
                var payloadobj = { 
                    eventId: act_event_id,
                    eventTime: act_event_time,
                    code: "DESK_AVAILABLE",
                    name: "Desk Available",
                    description: "Desk Available",
                    category: "DESK",
                    severity: "info",
                    actorNik: "system",
                    ownerNik: null,
                    bookingId: null,
                    roomId: parseInt(desk.room_id),
                    deskId: parseInt(desk.desk_id),
                    previousStatus: null,
                    currentStatus: "connected",
                    source: "service",
                    message: act_message,
                    visibility: "private",
                    metadata: {
                        action: "connected",
                        socket: desk.socket,
                        controller_id: desk.controller_id,
                        zone_id: desk.zone_id,
                        action_at: act_created_at
                    }
                };
                sendPublishTopicDesk(topic, JSON.stringify(payloadobj));
            }
        }
    }
}

async function sendPOST(header, url, payloads){
  return new Promise(resolve => {
	    request.post(url, {
	      headers : header,
		  json: payloads
		}, (error, res, body) => {

		  if (error) {
		  	resolve({
		  		error : error,
		  		body : body,
		  		result : res,
		  		// statusCode : res.statusCode
		  	}); 
		  }else{
		  	resolve({
		  		error : null,
		  		body : body,
		  		result : res,
		  		// statusCode : res.statusCode
		  	}); 
		  }
		  
		})

    });
}
async function sendGET(header, url){
  return new Promise(resolve => {
        try {
            request.get(url, {
              headers : header,
            }, (error, res, body) => {
              // console.log("sendGET",body);
              if (error) {
                resolve({
                    error : error,
                    body : body,
                    result : res,
                    // statusCode : res.statusCode
                }); 
              }else{
                resolve({
                    error : null,
                    body : body,
                    result : res,
                    // statusCode : res.statusCode
                }); 
                // console.log(`statusCode: ${res.statusCode}`)
                // console.log(body)
              }
              
            })
        } catch (e) {
            resolve({
                error : e,
                body : null,
                result : null,
            }); 
        }
    });
}
function delay(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function openDataMeeting(){
	var datenow = moment().format('YYYY-MM-DD');
	return new Promise( (resolve, reject) => {
        var query = 'SELECT b.*,r.name room_name, ';
        query += ' dc.name as controller_name,   dci.socket,';
        query += ' dc.ip_address as ip_address  ';
        query += ' from desk_booking b ';
        query += ' INNER JOIN desk_room r ON b.room_id=r.id ';
        query += ' INNER JOIN desk_controller_initial dci ON r.id=dci.desk_room_id AND b.desk_id=dci.desk_id ';
        query += ' INNER JOIN desk_controller dc ON dci.controller_id=dc.id ';
        // query += ' INNER JOIN access_controller_falco acf ON ac.id=acf.access_id ';
        query += ' WHERE b.date ="'+datenow+'" AND b.is_deleted=0 AND b.is_expired=0 AND b.is_canceled="0" AND b.status="soon"  ';
        query += '  AND r.is_deleted = 0 AND dc.is_deleted=0 ';
        // console.log()
        conn.query(query,
            [ 0 ], function (error, rows, fields){
            if(error != null){
            	console.log(error)
            	resolve(null);
            } else{
                resolve(rows); //Kembalian berupa kontak data
            }
        });
    });
}

async function openDataMeetingEnd(){
	var datenow = moment().format('YYYY-MM-DD');
	return new Promise( (resolve, reject) => {
        var query = 'SELECT b.*,r.name room_name, ';
        query += ' dc.name as controller_name,  dci.socket, ';
        query += ' dc.ip_address as ip_address  ';
        query += ' from desk_booking b ';
        query += ' INNER JOIN desk_room r ON b.room_id=r.id ';
        query += ' INNER JOIN desk_controller_initial dci ON r.id=dci.desk_room_id AND b.desk_id=dci.desk_id ';
        query += ' INNER JOIN desk_controller dc ON dci.controller_id=dc.id ';
        query += ' WHERE b.date ="'+datenow+'" AND ( b.end_early_meeting =1 OR b.is_deleted=1 OR b.is_expired=1 OR b.is_canceled="1" OR b.status="cancel" OR b.status="expired" ) ';
        query += ' AND r.is_deleted = 0 AND  dc.is_deleted=0  AND b.is_access_trigger=0 ';

        conn.query(query,
            [ 0 ], function (error, rows, fields){
            if(error != null){
            	console.log(error)
            	resolve(null);
            } else{
                resolve(rows); //Kembalian berupa kontak data
            }
        });
    });
}

async function openDataSetting(){
	var datenow = moment().format('YYYY-MM-DD');
	return new Promise( (resolve, reject) => {
        var query = 'SELECT * from  setting_rule_booking ';
        conn.query(query,
            [ 0 ], function (error, rows, fields){
            if(error != null){
            	resolve(null);
            } else{
                resolve(rows[0]); //Kembalian berupa kontak data
            }
        });
    });
}

async function runSelectQuery(q){
	return new Promise( (resolve, reject) => {
        var query = q;
        conn.query(query,
            [ 0 ], function (error, rows, fields){
            if(error != null){
                resolve(null);
            } else{
                resolve(rows); //Kembalian berupa kontak data
            }
        });
    });
}
async function runActionQuery(q){
	return new Promise( (resolve, reject) => {
        var query = q;
        conn.query(query,
            [ 0 ], function (error, rows, fields){
            if(error != null){
                resolve(null);
            } else{
                resolve(rows); //Kembalian berupa kontak data
            }
        });
    });
}



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
const portdata = 8003

app.listen(portdata, () => {
  console.log(`service_booking listening on port ${portdata}`)
})
var mqtttopicglobal = config.mqtt.topicsActivities;
const clientId = `deskactivities_${Math.random().toString(16).slice(3)}`;
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
    clientmqtt.subscribe(mqtttopicglobal, function (err) { if (!err) {  console.log("subscribe to topic " + mqtttopicglobal)  } })
})


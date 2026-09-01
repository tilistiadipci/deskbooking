<?php

$config['mqtt'] = [
    'host'     => MQTT_HOST,
    'port'     => MQTT_PORT,
    'username' => MQTT_USERNAME,
    'password' => MQTT_PASSWORD,
    'client_id' => MQTT_CLIENT_ID,
    'ws_port'  => defined('MQTT_WS_PORT') ? MQTT_WS_PORT : 9001,
    'topic'    => defined('MQTT_ACTIVITIES_TOPIC') ? MQTT_ACTIVITIES_TOPIC : 'deskbooking/activities',
    'topic_stomp' => defined('MQTT_ACTIVITIES_TOPIC_STOMP') ? MQTT_ACTIVITIES_TOPIC_STOMP : '/queue/deskbooking.activities.desk',
];
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Activity Log Categories
 */
define('CAT_BOOKING', 'BOOKING');
define('CAT_DESK', 'DESK');
define('CAT_SYSTEM', 'SYSTEM');
define('CAT_MASTER', 'MASTER');

/**
 * Activity Log Codes & Names
 */
$GLOBALS['ACTIVITY_CODES'] = [
    'BOOKING_CREATED'     => ['category' => CAT_BOOKING, 'name' => 'Booking Created'],
    'BOOKING_RESCHEDULED' => ['category' => CAT_BOOKING, 'name' => 'Booking Rescheduled'],
    'BOOKING_CANCELLED'   => ['category' => CAT_BOOKING, 'name' => 'Booking Cancelled'],
    'BOOKING_CHECKIN'     => ['category' => CAT_BOOKING, 'name' => 'Booking Check-in'],
    'BOOKING_CHECKOUT'    => ['category' => CAT_BOOKING, 'name' => 'Booking Checkout'],
    'DESK_RESERVED'       => ['category' => CAT_DESK,    'name' => 'Desk Reserved'],
    'DESK_OCCUPIED'       => ['category' => CAT_DESK,    'name' => 'Desk Occupied'],
    'DESK_RELEASED'       => ['category' => CAT_DESK,    'name' => 'Desk Released'],
    'DESK_UNAVAILABLE'    => ['category' => CAT_DESK,    'name' => 'Desk Unavailable'],
    'DESK_AVAILABLE'      => ['category' => CAT_DESK,    'name' => 'Desk Available'],
    
    // Master Data
    'ROOM_CREATED'        => ['category' => CAT_MASTER,  'name' => 'Room Created'],
    'ROOM_UPDATED'        => ['category' => CAT_MASTER,  'name' => 'Room Updated'],
    'ROOM_DELETED'        => ['category' => CAT_MASTER,  'name' => 'Room Deleted'],
    'BUILDING_CREATED'    => ['category' => CAT_MASTER,  'name' => 'Building Created'],
    'BUILDING_UPDATED'    => ['category' => CAT_MASTER,  'name' => 'Building Updated'],
    'BUILDING_DELETED'    => ['category' => CAT_MASTER,  'name' => 'Building Deleted'],
    'ALOCATION_CREATED'   => ['category' => CAT_MASTER,  'name' => 'Alocation Created'],
    'ALOCATION_UPDATED'   => ['category' => CAT_MASTER,  'name' => 'Alocation Updated'],
    'ALOCATION_DELETED'   => ['category' => CAT_MASTER,  'name' => 'Alocation Deleted'],
    'EMPLOYEE_CREATED'    => ['category' => CAT_MASTER,  'name' => 'Employee Created'],
    'EMPLOYEE_UPDATED'    => ['category' => CAT_MASTER,  'name' => 'Employee Updated'],
    'EMPLOYEE_DELETED'    => ['category' => CAT_MASTER,  'name' => 'Employee Deleted'],
    'USER_CREATED'        => ['category' => CAT_MASTER,  'name' => 'User Created'],
    'USER_UPDATED'        => ['category' => CAT_MASTER,  'name' => 'User Updated'],
    'USER_DELETED'        => ['category' => CAT_MASTER,  'name' => 'User Deleted'],
    'FACILITY_CREATED'    => ['category' => CAT_MASTER,  'name' => 'Facility Created'],
    'FACILITY_UPDATED'    => ['category' => CAT_MASTER,  'name' => 'Facility Updated'],
    'FACILITY_DELETED'    => ['category' => CAT_MASTER,  'name' => 'Facility Deleted'],
    
    // System Config
    'SYSTEM_CONFIG_UPDATED' => ['category' => CAT_SYSTEM,  'name' => 'System Config Updated'],
];

if (!function_exists('record_activity')) {
    /**
     * Helper function to quickly insert an activity log
     *
     * @param string $code The activity code (e.g., 'BOOKING_CREATED')
     * @param array $data Array of additional data to insert
     * @return bool
     */
    function record_activity($code, $data = []) {
        $CI =& get_instance();
        $CI->load->model('Model_ActivityLog');

        global $ACTIVITY_CODES;

        if (!isset($ACTIVITY_CODES[$code])) {
            $category = CAT_SYSTEM;
            $name = $code;
        } else {
            $category = $ACTIVITY_CODES[$code]['category'];
            $name = $ACTIVITY_CODES[$code]['name'];
        }

        $log_data = array_merge([
            'actor_nik' => isset($log_data['actor_nik']) ? $log_data['actor_nik'] : "system",
            'event_id'   => uniqid('evt_', true) . rand(1000, 9999),
            'event_time' => date('Y-m-d\TH:i:sP'),
            'code'       => $code,
            'name'       => $name,
            'category'   => $category,
            'severity'   => 'info',
            'created_at' => date('Y-m-d H:i:s')
        ], $data);

        // Prepare DB Data
        $db_data = $log_data;
        if (isset($db_data['metadata']) && is_array($db_data['metadata'])) {
            $db_data['metadata'] = json_encode($db_data['metadata']);
        }
        $db_data['event_time'] = date('Y-m-d H:i:s.v'); // standard mysql datetime(3)

        // Save to Database Log
        $saved = $CI->Model_ActivityLog->insert_log($db_data);

        // Prepare MQTT Payload
        $mqtt_payload = [
            "eventId" => $log_data['event_id'],
            "eventTime" => $log_data['event_time'],
            "code" => $log_data['code'],
            "name" => $log_data['name'],
            "description" => isset($log_data['description']) ? $log_data['description'] : $log_data['name'],
            "category" => $log_data['category'],
            "severity" => isset($log_data['severity']) ? $log_data['severity'] : "info",
            "actorNik" => isset($log_data['actor_nik']) ? $log_data['actor_nik'] : "system",
            "ownerNik" => isset($log_data['owner_nik']) ? $log_data['owner_nik'] : null,
            "bookingId" => isset($log_data['booking_id']) ? (int)$log_data['booking_id'] : null,
            "roomId" => isset($log_data['room_id']) ? (int)$log_data['room_id'] : null,
            "deskId" => isset($log_data['desk_id']) ? (int)$log_data['desk_id'] : null,
            "previousStatus" => isset($log_data['previous_status']) ? $log_data['previous_status'] : null,
            "currentStatus" => isset($log_data['current_status']) ? $log_data['current_status'] : null,
            "source" => isset($log_data['source']) ? $log_data['source'] : "web",
            "message" => isset($log_data['message']) ? $log_data['message'] : "",
            "visibility" => "private"
        ];

        // Process Metadata JSON if exists
        if (isset($log_data['metadata'])) {
            $mqtt_payload['metadata'] = is_array($log_data['metadata']) ? $log_data['metadata'] : json_decode($log_data['metadata'], true);
        } else {
            $mqtt_payload['metadata'] = new stdClass();
        }

        // Publish to MQTT
        $CI->load->library('mqtt');
        $CI->mqtt->publish(MQTT_ACTIVITIES_TOPIC, $mqtt_payload);
       

        return $saved;
    }
}

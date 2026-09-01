<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Display extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Admin');
		$this->load->model('Model_Api');
		$this->load->model('Model_Api2');
		$this->load->model('Model_Notif');
		$this->load->model('Model_Kiosk');
		$this->load->model('Model_License');
		$this->load->model('Model_Booking');
		$this->load->model('Model_Pantry');
		$this->load->model('Model_Invoice');
		$this->load->model('Model_MeetingLimitation');


		$this->load->helper('response');
		$this->output->set_content_type('application/json');
		
	}

	private function checkSerialIsAlready($post){
		if(isset($post['serial']) && $post['serial'] != ""){
			$fetch = $this->Model_Api->getDisplayBySerial($post['serial'] );
			if(!isset($fetch['display_serial'])){
				echo response("fail", [], "Display not available/registered");
				die();
			}
			if( ($fetch['enabled']-0) == 0 ){
				echo response("fail", $fetch, "Display is disabled");
				die();
			}
			
		}


	}
	public function getDisplayBySerial()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		if(isset($post['serial']) && $post['serial'] != ""){
			$fetch = $this->Model_Api->getDisplayBySerial($post['serial'] );
			if(!isset($fetch['display_serial'])){
				echo response("fail", [], "Display not available/registered");
				die();
			}
			$roomselect = [];
			$roomSelect = $fetch['room_select'] == null ? "": $fetch['room_select'];
			$roomSelectSp = explode(",", $roomSelect);
			$fetchroom = $this->Model_Admin->getDataRoomDisplayByListID($roomSelectSp);
			$fetch['room_select_data'] = $fetchroom['data'];
			if( ($fetch['enabled']-0) == 0 ){
				echo response("success", $fetch, "Get success");
				die();
			}
			echo response("success", $fetch, "Get success");
			die();
			
		}else{
			$response = response("fail", array(), "Failed restrict access");
			echo $response ;
			die();
		}
	}
	public function getData()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		if(isset($post['username']) && $post['username'] != ""){
			$data = $this->Model_Api->getDataDisplay($post );
			if($data['error'] == null){
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
		}else{
			$response = response("fail", array(), "Failed restrict access");
			echo $response ;
		}
		
		
	}
	public function postDisplaySignage()
	{
		$type = $this->uri->segment(5);
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		switch ($type) {
			case 'background':
				$raw = array(
					'background_update' => 0,
				);
				$w = array(
					'room_id' => $post['room_id'],
				);
				$resp = $this->Model_Api->updateData('room_display', $raw, $w );
				echo response("success", $post , "Update success");

				# code...
				break;
			case 'signage':
				$raw = array(
					'signage_update' => 0,
				);
				$w = array(
					'room_id' => $post['room_id'],
				);
				$resp = $this->Model_Api->updateData('room_display', $raw, $w );
				echo response("success", $post , "Update success");
				break;
			
			default:
				echo response("fail", $post , "Get failed");
				# code...
				break;
		}
	}

	public function getSignageMeeting()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date = date("Y-m-d");
		
		$data = $this->Model_Api->getSignageMeeting($date );
		if($data['error'] == null){
				echo response("success", $data['data'], "Get success");
		}else{
				echo response("fail", array(), "Get failed");
		}
		
		
	}

	public function kioskAuth()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date = date("Y-m-d");
		$datetime = date("Y-m-d H:i:s");
 
		$display_serial = $post['serial'];
		$uuid = $post['uuid'];
		$hwserial = $post['hw_serial'];
		$data = $this->Model_Kiosk->getDataAuth($display_serial,$hwserial,$uuid);
		if($data['error'] == null){
			if(count($data['data']) <=0 ){
				echo response("fail", array(), "Display serial not found or unregistered");
				die();
			}
			$dp = $data['data'][0];
			if($dp['is_logged'] == 1){
				if($dp['display_hw_serial'] == $hwserial && $dp['display_uuid'] == $uuid){
					
				}else{
					echo response("fail", array($dp), "The serial is belong another device or already registered");
					die();
				}
				
			}
			if($dp['display_type'] != "display_deskbooking"){
				echo response("fail", array(), "The serial is not intended for this device");
				die();
			}
			$w = array('display_serial' => $display_serial);
			$up = array('display_uuid' => $uuid,'display_hw_serial' => $hwserial,'last_logged' => $datetime,'is_logged' => 1 );
			if(isset($post['koordinate'])){
				$up['koordinate'] = $post['koordinate'];
			}
			$resp = $this->Model_Api->updateData('kiosk_display', $up, $w );

			echo response("success", $dp, "Get success");
		}else{
			echo response("fail", array(), "Server busy");
		}
		
		
	}

	// ===========================================================
	// SCHEDULE MEETING
	// ===========================================================

	public function getMeetingWithMoreRoomOccupiedDisplay()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$this->checkSerialIsAlready($post);
		// initialCheck
		$timezone = APP_GMT;
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
		}
		date_default_timezone_set($timezone);
		$waktu_mulai = $post['date'] . " " .$post['time'] ;
		$server_date = new DateTime($waktu_mulai, new DateTimeZone($timezone));
		$server_time = new DateTime($waktu_mulai, new DateTimeZone($timezone));

		$post['date'] = $server_date->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d');
		$post['time'] = $server_time->setTimezone(new DateTimeZone(APP_GMT))->format('H:i:s');
		$roomSelect = [];
		$room_id = $post['room_id'] == null ? "" :  $post['room_id'];
		if(isset($post['type'])){
			if($post['type'] == "allroom" || $post['type'] == "receptionist" ){
				$roomSelect = explode(",", $post['room_select']);
			}else{
				array_push($roomSelect, $room_id);
			}
		}else{
			array_push($roomSelect, $room_id);
		}
		$getData = $this->Model_Api2->getMeetingListOccupiedByDisplay($post, $roomSelect);
		$response = response("success", $getData, "Success get data to list ");
		echo $response ;
		die();
		
	}
	public function getMeetingWithMoreRoomListDisplay()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$this->checkSerialIsAlready($post);
		// initialCheck
		$timezone = APP_GMT;
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
		}
		date_default_timezone_set($timezone);
		
		$waktu_mulai = $post['date'] . " " .$post['time'] ;
		$server_date = new DateTime($waktu_mulai, new DateTimeZone($timezone));
		$server_time = new DateTime($waktu_mulai, new DateTimeZone($timezone));

		$post['date'] = $server_date->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d');
		$post['time'] = $server_time->setTimezone(new DateTimeZone(APP_GMT))->format('H:i:s');

		$roomSelect = [];
		$room_id = $post['room_id'] == null ? "" :  $post['room_id'];
		if(isset($post['type'])){
			if($post['type'] == "allroom" || $post['type'] == "receptionist" ){
				$roomSelect = explode(",", $post['room_select']);
			}else{
				array_push($roomSelect, $room_id);
			}
		}else{
			array_push($roomSelect, $room_id);
		}
		
		$getData = $this->Model_Api2->getMeetingListByDisplay($post, $roomSelect);
		$response = response("success", $getData, "Success get data to list ");
		echo $response ;
	}
	public function getMeetingOccupiedDisplay()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$timezone = APP_GMT;
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set($timezone);
		}

		$tanggal_meeting = $post['date'];
		$time = $post['time'] ;
		$datetime =  $post['date'] . " ". $post['time'];
		
		$server_date_1 = new DateTime($datetime, new DateTimeZone($timezone));
		$server_time_1 = new DateTime($datetime, new DateTimeZone($timezone));

		$ck_server_date = $server_date_1->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d');
		$ck_server_time = $server_time_1->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
		$post['date'] = $ck_server_date;
		$post['time'] = $ck_server_time;
		
		// print_r($post);

		$getData = $this->Model_Api2->getMeetingOccupiedByDisplay($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to active ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}

	// ===========================================================
	// DISPLAY MEETING BOOK
	// ===========================================================
	// 
	
	public function fastBooked()
	{
		include APPPATH.'third_party/phpqrcode/qrlib.php';
		$modules['pantry'] = $this->Model_Module->get_module_pantry();
		$modules['loker'] = $this->Model_Module->get_module_loker();
		$modules['price'] = $this->Model_Module->get_module_price();
		$modules['invoice'] = $this->Model_Module->get_module_invoice();
		$modules['email'] = $this->Model_Module->get_module_email();

		$tmpdir = ASSETS_QR;

		$json = file_get_contents("php://input");
		// file_put_contents("response.txt",$json);
		$post = json_decode($json, TRUE);
		// START SERIAL
		$this->checkSerialIsAlready($post);
		// END SERIAL
		// START TIMEZONE
		$timezone = APP_GMT;
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set($timezone);
		}
		$datetime 	= date('Y-m-d H:i:sP'); // LOCAL TIME
		// END TIMEZONE
		$oldBookingId = isset($post['databook']['old_booking_id']) ? $post['databook']['old_booking_id'] : "";
		$actionSendNotfi = isset($post['databook']['send_notif']) ? $post['databook']['send_notif']-0 : 0;

		$datetime 					= date('Y-m-d H:i:s');
		$time_duration 				= ($post['duration']-0);
		$room_id 					= $post['room_id'];
		$randoom_id					=  random_string('numeric', 10);
		$id 						= 'DISP-' . $randoom_id."";
		$invoice_id 				= $randoom_id."";
		$nikPic						= $post['nik'];

		// $ecnyPass = encryp_data(isset($post['password']) ? $post['password'] : '');
		// $username = $post['nik'];
		// $check 		= $this->Model_Api->checkLogin($username, $ecnyPass);

		// if ($check['username']->num_rows() <= 0) {
		// 	$response = response("fail", array(), "User not available/exist ");
		// 	echo $response ;	
		// 	die();
		// }
		// $datauser = $check['username']->row_array();
		// if (!isset($datauser['nik'])) {
		// 		$response = response("fail", array(), "User not available/exist ");
		// 		echo $response ;	
		// 		die();
		// }

		$nikPic 			= $post['nik'];
		// $nikPic = $datauser['nik'];
		$resPIC 			= $this->Model_Api->getNikEmployeeByPic($nikPic);
		$getDataPIC 		= $resPIC['data'];
		$alocation_id 		= $getDataPIC ['alocation_id'];
		$alocation 			= [
			'alocation_id' => $getDataPIC ['alocation_id'],
			'alocation_name' => $getDataPIC ['alocation_name']
		];

		$isMerge 			= isset($post['is_merge']) ? $post['is_merge'] : 0;
		if($isMerge == "false" ){
			$isMerge = 0;
		}else if($isMerge == "true" ){
			$isMerge = 1;
		}
		$mergeRoom 	= isset($post['merge_room']) ? $post['merge_room'] : array();
		$dataMergeRoom 	=  array();
		$dataMergeRoomWidthJson =  "[]";
		if($getDataPIC['nik'] == null){
			$fail = array(
				'datetime' => $datetime
			);
			$response = response("fail", $fail, "Your username/nik don't have access");
			echo $response ;
			die();
		}

		$rawroomquery = "SELECT * FROM room WHERE radid='".$room_id."' ";
		$rawroom =  $this->Model_Api->querySql($rawroomquery);
		$roomresult 	= $rawroom->result_array();
		if(count($roomresult) <= 0){
			$response = response("fail", array(), "Room not found");
			echo $response ;
			die();
		}
		$room = $roomresult[0];
		$room_name 					= $room['name'];
		$merge_room_name 			= $room['name'];
		$merge_room_id 				= $room['radid'];

		if($isMerge == true){
			$tempMergeRoomName = array();
			foreach ($mergeRoom  as $mk => $mv) {
				$Q_room						= "SELECT * FROM room WHERE radid=".$mv." ";
				$vmergeroom 			 	= $this->Model_Admin->querySql($Q_room)->row_array();
				if( $vmergeroom ['name'] != null){
					$vmergeroom_name = $vmergeroom ['name'] != null ? $vmergeroom 	['name'] : "";
					$dataSimpleRoom = array(
						"radid" => $vmergeroom['radid'],
						"name" => $vmergeroom['name'],
						"location" => $vmergeroom['location'],
						"link" => $vmergeroom['google_map'],
					);
					array_push($dataMergeRoom, $dataSimpleRoom);
					array_push($tempMergeRoomName, $vmergeroom['name']);
				}
			}
			if(count($tempMergeRoomName)>0){
				$room_name 	.= " (".implode(", ", $tempMergeRoomName) . ")";
				$dataMergeRoomWidthJson = json_encode($dataMergeRoom);
			}
		}


		$settingGeneral				= $this->Model_Api->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];
		// $alocation 	= $databook['alocation'];
		$dataEmailInternal = array();
		$fHour 						= $dataSettingGeneral['duration'];
		$time1 = date('Y-m-d H:i:s');
		if(isset($post['startTime'])){
			if($post['startTime'] != ""){
				$time1 = $post['date'] ." ".$post['startTime'];
			}
		}
		$time2 = date('Y-m-d H:i:s',strtotime($time1 . "+".$time_duration." minutes"));
		$startStr = date('H:i:s',strtotime($time1));
		$endStr = date('H:i:s',strtotime($time2));

		$duration = $time_duration;
		$getHoursMeeting 			= floor($duration / $fHour);
		$checkHours 				= fmod($duration,$fHour);
		if($checkHours > 0){
			$getHoursMeeting += 1;
		}
		$tanggal_meeting = $post['date'];
		$waktu_mulai = $time1;
		$waktu_end = $time2;
		$waktu_akhir = $waktu_end;
		$waktu_timestart 	= $startStr;
		$waktu_timeend	 	= $endStr;

		$server_date_1 = new DateTime($waktu_mulai, new DateTimeZone($timezone));
		$server_start_1 = new DateTime($waktu_mulai, new DateTimeZone($timezone));
		$server_end_1 = new DateTime($waktu_akhir, new DateTimeZone($timezone));

		$ck_server_date = $server_date_1->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d');
		$ck_server_start = $server_start_1->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
		$ck_server_end = $server_end_1->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');

		$databook = [
			'title' => $post['title'],
			'date' => $post['date'],
			'startStr' => $startStr,
			'endStr' => $endStr,
		];
		$ruangan = $room['radid'];
		$reservation_cost 			= $room['price'] * $getHoursMeeting;
		$internalBatch 				= array();
		if($isMerge == true){
			$tempMergeRoomName = array();
			foreach ($mergeRoom  as $mk => $mv) {
				$ruangan_m_id = $mv;
				$this->Model_Admin->checkKondisiBookingPerRuangan($ruangan_m_id,$ck_server_date, $ck_server_start,$ck_server_end);
			}
		}else{
			$this->Model_Admin->checkKondisiBookingPerRuangan($ruangan,$ck_server_date, $ck_server_start,$ck_server_end);
		}
		
		$internal 	= isset($post['internal_data']) ?  $post['internal_data'] : array();
		$eksternal 	= isset($post['external_data']) ? $post['external_data'] : array();
		// $alocation 	= $post['alocation'];
		$nikArray = array();
		foreach ($internal as $key => $value) {
			array_push($nikArray, $value['nik']);
		}
		if(count($nikArray) > 0) {
			$rowInternal 			= $this->Model_Api->getDataEmployeeWhereInNik($nikArray);
		}else{
			$rowInternal 			= array();
			$rowInternal['data'] 	= array();
		}
		
		// START MODULE PANTRY
		// START MODULE PANTRY
		$this->Model_Pantry->createPantryOrder($databook,$id);
		// END MODULE PANTRY
		// END MODULE PANTRY

		// START MODULE INVOICE & PRICE
		// START MODULE INVOICE & PRICE'
		$duration 					= $this->Model_Invoice->calculateDuration($databook);
		$reservation_cost 			= $this->Model_Invoice->calculateReservationCost($databook, $room);
		$formatInvoice 				= $this->Model_Invoice->createInvoiceOrder($databook,$alocation, $room);
		// END MODULE INVOICE & PRICE
		// END MODULE INVOICE & PRICE

		$internalBatch 				= array();
		$eksternalBatch 			= array();
		$dataEmailInternal 			= $rowInternal['data'];
		$dataEmailEksternal 		= array();
		$dataEmailInternal_array	= array();
		$data 						= array();  // DATA BOOKING
		$data 						= $this->Model_Booking->MobileFormatBooking($databook);  // DATA BOOKING
		$data['booking_id'] 		= $id;
		$data['no_order'] 			= $formatInvoice;
		$data['room_id'] 			= $room['radid'];
		$data['total_duration'] 	= $duration;
		$data['duration_per_meeting'] = $fHour ;
		$data['cost_total_booking'] = $reservation_cost ;
		$data['created_at'] 		= $datetime;
		$data['created_by'] 		= $nikPic; // mobile created
		$data['room_name'] 			= $room_name;
		$data['alocation_id'] 		= $alocation['alocation_id'];
		$data['alocation_name'] 	= $alocation['alocation_name'];
		$data['pic'] 				= $getDataPIC['name'];
		$data['is_merge'] 			= $isMerge;
		$data['merge_room_name'] 	= $merge_room_name;
		$data['merge_room_id'] 		= $merge_room_id;
		$data['merge_room'] 		= $dataMergeRoomWidthJson;
		$data['timezone'] 			= $timezone;
		$data['booking_devices']	= "display";

		

		$server_start = new DateTime($data['start'], new DateTimeZone($timezone));
		$server_end = new DateTime($data['end'], new DateTimeZone($timezone));
		$data['server_start'] = $server_start->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
		$data['server_end'] = $server_end->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
		$data['server_date'] = $server_start->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d');

		$data	= $this->Model_MeetingLimitation->adjustAdvanceMeeting($data, $room);
		$data	= $this->Model_MeetingLimitation->checkMeetingVipAccess($data, $room);
		$data	= $this->Model_MeetingLimitation->checkApprovalMeetingAccess($data, $room);
		// CREATED PIC/ORGANIZER
		$invitation_pic 						= $this->Model_Booking->createInvitationPic($data, $nikPic,$getDataPIC);
		$qrnvitationPIC = $id."_".$invitation_pic['pin_room'];
		QRcode::png($qrnvitationPIC,$tmpdir.$qrnvitationPIC.".png", QR_ECLEVEL_H,10,3);
		$vipForceOtherMoved = $data['is_vip'] == 1 ? true : false ;
		// print_r();

		// =========================================================================
		// START ATTENDEES AREA
		$dataGenerateInternal 						= $this->Model_Booking->createInternalBatch($data, $dataEmailInternal, $nikPic,true);
		$internalBatch = $dataGenerateInternal['internalBatch'];
		$dataEmailInternal = $dataGenerateInternal['dataEmailInternal'];
		// insert of PIC invitation
		$ipicemail['nik'] 					= $getDataPIC['nik']; // employee id
		$ipicemail['name'] 					= $getDataPIC['name'];
		$ipicemail['division_id']			= 0;
		$ipicemail['is_pic'] 				= 1;
		$ipicemail['email'] 				= $getDataPIC['email']; 
		$ipicemail['pin_room'] 				= $invitation_pic['pin_room'];
		array_push($dataEmailInternal, $ipicemail);
		$dataGenerateExternal 				= $this->Model_Booking->createExternalBatch($data, $eksternal, $nikPic,true);
		$eksternalBatch = $dataGenerateExternal['eksternalBatch'];
		$dataEmailEksternal = $dataGenerateExternal['dataEmailEksternal'];
		$dataToSend['internal'] 			= $dataEmailInternal;
		$dataToSend['eksternal'] 			= $dataEmailEksternal;
		$batchSendingEmail 					= json_encode($dataToSend);
		$batchSendingNotif 					= json_encode($dataToSend['internal']);
		
		$sending_email 				= $this->Model_Booking->createSendingBatchEmail($id, $batchSendingEmail, $datetime);
		$sending_notif 				= $this->Model_Booking->createSendingBatchNotif($id, $batchSendingNotif, $datetime);

		$respP 			= $this->Model_Api->insertData('booking_invitation', $invitation_pic);
		if(count($internalBatch) > 0){
			$resp1		= $this->Model_Api->insertDataBatch('booking_invitation', $internalBatch);
		}
		if(count($eksternalBatch) > 0){
			$resp2 		= $this->Model_Api->insertDataBatch('booking_invitation', $eksternalBatch);
		}
		//END ATTENDEES AREA
		// =========================================================================

		// =========================================================================
		// START 365
		// =========================================================================
		$module_int_365 = $this->Model_Module->get_module_int_365();
		$module_int_google = $this->Model_Module->get_module_int_google();
		$room_365 = $room['config_microsoft'] == null ? "" : $room['config_microsoft'];
		$room_google = $room['config_google'] == null ? "" : $room['config_google'];
		if(($module_int_365['is_enabled']-0) == 1 && $data['is_alive'] == 1 && $room_365 != "" ){
			$ms365 = $this->Model_License->get365Integration();
			$ck365 = $this->Model_License->check365Data();
			if($ck365 == true){
				$res_365 = $this->Model_License->createEvent365($data,$room,$ms365, $dataEmailInternal,$dataEmailEksternal);
				// $jres_365 = json_decode($res_365, TRUE);
				try{
					$jres_365 = json_decode($res_365, TRUE);
					if(!isset($jres_365['error'])){
						$data['booking_id_365'] = $jres_365 ['id'];
					}else{
						$data['booking_id_365'] = "";
					}
				}catch(Exeption $e){
					$data['booking_id_365'] = "";
				}
			}
		}
		if(($module_int_google['is_enabled']-0) == 1 && $data['is_alive'] == 1  && $room_google != ""){
			$data['booking_id_google'] = "";
		}
		// =========================================================================
		// END 365
		// =========================================================================

		// =========================================================================
		// CREATE BOOKING
		// =========================================================================
		if($isMerge == false){
			$resp3 = $this->Model_Admin->insertData('booking', $data);
		}else{
			foreach ($dataMergeRoom as $SP => $SPVAL) {
				$data['room_id'] = $SPVAL['radid'] ;
				$resp3 = $this->Model_Admin->insertData('booking', $data);
			}
		}
		// =========================================================================
		// END BOOKING
		// =========================================================================

		// =========================================================================
		// START NOTOFICATION MEETING
		// =========================================================================
		$respw = $this->Model_Api->insertData('sending_email', $sending_email);
		$resp4 = $this->Model_Api->insertData('sending_notif', $sending_notif);
		$notifcollectdata = array();
		$notifcollectdata 	= $this->Model_Booking->createNotifikasiCollectData($id, $dataEmailInternal, $data,$datetime);
		$type_notif 				= 1; // notification_type 1=booking
		$notif_insert				= false; // notification_type 1=booking
		$_notif_pic = ['datetime'=>$datetime, 'nik' => $nikPic, 'type' => $type_notif,'value' => $id,'title' => "Create a meeting schedule", 'body' => $databook['title'] ." - ". getformatDate($databook['date']), 'is_sending' => 0,'is_deleted' => 0, 'created_at' => $datetime];
		array_push($notifcollectdata, $_notif_pic);
		$this->Model_Notif->insertNotifAdmin(12, "Create meeting", $data['title']);
		// print_r($_notif_pic);
		$this->Model_Notif->insertNotifBatch($notifcollectdata);
		// print_r($dataToSend);
		// die();
		// START MANAGE NOTIF
		$send_notif = true;
		$notif_reminder = isset($post['notif']) ? ($post['notif']-0) : 0 ;
		if($notif_reminder == 0){
			$send_notif = false;
		}
		// END MANAGE NOTIF
		$booking_id = $id;
		$getBooking 		= $this->Model_Api->getDataBookingById($booking_id); //
		if($getBooking ['error'] != null){
			$response = response("fail", array(), "Data not exist ");
			echo $response;	
			die();
		}
		$dataBooking = $getBooking['data'];
		$emailBooking = $dataBooking;
		$emailBooking['format_time_start'] = $this->Model_Admin->formatTime($waktu_timestart);
		$emailBooking['format_time_end'] = $this->Model_Admin->formatTime($waktu_timeend);
		$emailBooking['format_date'] = $this->Model_Admin->formatDate($tanggal_meeting);
		// MODULE EMAIL
		// MODULE EMAIL
		// MODULE EMAIL
		if( ($modules['email']['is_enabled']-0) == 1 && $data['is_alive'] == 1 && $send_notif == true){
			foreach ($dataToSend['internal'] as $key => $people) {
				$pNotif = $this->Model_Notif->sendEmailInternal("invitation", $emailBooking, $people, $invitation_pic);
			}	
			foreach ($dataToSend['eksternal'] as $key => $people) {
				$pNotif = $this->Model_Notif->sendEmailExternal("invitation", $emailBooking, $people, $invitation_pic);
			}	
		}
		// MODULE NOTIFIKASI
		$meeting_title 		= $databook['title'];
		$meeting_date 		= $databook['date'];
		$meeting_start 		= $databook['startStr'];
		$meeting_end 		= $databook['endStr'];
		if($data['is_alive'] == 1 && $send_notif == true){
			$notification_title = "Invitation Meeting of ".$meeting_title;
			$notification_body 	= $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end). " at " .$room_name;
			$pNotif 			= $this->Model_Notif->pushNotification($notification_title,$notification_body,$dataToSend['internal'] ,$type_notif,$notif_insert );
			$type_notif_admin = 12; // booking
			$this->Model_Notif->insertNotifAdminApi($type_notif_admin, "Create meeting", $meeting_title, $ipicemail['nik'] );
		}
		// =========================================================================
		// END NOTOFICATION MEETING
		// =========================================================================
		// =========================================================================
		// START LOCKER MODULE
		// =========================================================================
		if( ($modules['loker']['is_enabled']-0) == 1 && $data['is_alive'] == 1 ){
			$this->Model_Booking->bookingLockerForAttendees($id, $dataEmailInternal, $data,$datetime);
		}	
		// =========================================================================
		// END LOCKER MODULE
		// =========================================================================
		
		$response = response("success", array(), "Success create a booking ".$post['title']);
		echo $response;
		
	}
}

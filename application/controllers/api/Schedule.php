<?php
use Dompdf\Dompdf;
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Schedule extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Api');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Api2', "Api2");
		$this->load->model('Model_Notif');
		 $this->load->model('Model_Invoice');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
		
	}

	public function setExtendBookingDisplay()
	{
		try{
			$datetime = date("Y-m-d H:i:s");
			$ddd = $datetime ;
			$json = file_get_contents("php://input");
			$post = json_decode($json, TRUE);
			
			$ecnyPass = encryp_data(isset($post['password']) ? $post['password'] : '');
			$username = $post['npk'];
			$check 		= $this->Model_Api->checkLogin($username, $ecnyPass);
			if ($check['username']->num_rows() <= 0) {
				$response = response("fail", array(), "User not available/exist ");
				echo $response ;	
				die();
			}
			$datauser = $check['username']->row_array();
			if (!isset($datauser['nik'])) {
				$response = response("fail", array(), "User not available/exist ");
				echo $response ;	
				die();
			}
			$nikPic = $datauser['nik'];

			$booking_id = isset($post['booking_id']) ? $post['booking_id'] : "";
			$datetime = date("Y-m-d H:i:s");
			$dataBooking 		= $this->Api2->getBookingPic($booking_id);
			if (!isset($dataBooking['nik'])) {
				$response = response("fail", array(), "Meeting schedule not available/exist ");
				echo $response ;	
				die();
			}
			if ($dataBooking['nik'] != $nikPic) {
				$response = response("fail", array(), "You don't have access, Only PIC/Organizer could be end of meeting ");
				echo $response ;	
				die();
			}


			$getBooking = $this->Model_Api->getDataBookingById($post['booking_id']);
			$ex = $post['extend']-0;
			$duration = $ex;
			$dataBooking = $getBooking['data'];
			$dataBooking['extended_value'] = $ex ;
			$getBookingInv 		= $this->Model_Admin->getDataBookingInvById($post['booking_id'])['data'];
			$room_name 			= $dataBooking['room_name'];
			$notifcollectdata 	= array();
			// 
			$reservation_cost 			= 0;
			$duration 					= $this->Model_Invoice->calculateDurationExtend($dataBooking);
			$reservation_cost 			= $this->Model_Invoice->calculateReservationCostExtend($dataBooking);
			
			$extended_duration = $dataBooking['extended_duration'] -0;
			$total_extended = $ex +$extended_duration;
			$reservation_cost 			= $reservation_cost ;		
			$data = array(
				"extended_duration" => $ex,
			);
			$sql = "UPDATE booking SET 
				extended_duration=".$total_extended." ,
				cost_total_booking=".$reservation_cost." 
				WHERE booking_id='". $post['booking_id']."' ";
			
			// print_r($sql);

			$resp3 = $this->Model_Api->querySql($sql);
			foreach ($getBookingInv as $val) {
				if($val['internal'] == 1){
					// only internal
					$_notif 					= array();
					$_notif['datetime'] 		= $datetime;
					$_notif['nik'] 				= $val['nik']; // user id
					$_notif['type'] 			= 1; // booking is 1
					$_notif['value'] 			= $post['booking_id']; // booking id
					$_notif['title'] 			= "Extend meeting";
					$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
					$_notif['is_sending'] 		= 0;
					$_notif['is_deleted'] 		= 0;
					$_notif['created_at'] 		= $datetime;
					if($val['is_pic'] == 1){
						$_notif['title'] 			= "End meeting";
						$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
					}
					array_push($notifcollectdata, $_notif);
				}
			}
			if(count($notifcollectdata) > 0){
					$this->Model_Notif->insertNotifBatch($notifcollectdata);
					$meeting_title 		= $dataBooking['title'];
					$meeting_date 		= $dataBooking['date'];
					$explodeS 			= explode(" ", $dataBooking['start']);
					$explodeE 			= explode(" ", $dataBooking['end']);
					$meeting_start 		= $explodeS[1];
					$meeting_end 		= $explodeE[1];
					$notification_title = "Extend Meeting of ".$meeting_title;
					$notification_body 	= $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end). " at " .$room_name;
					$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
			}
			$response = response("success", array(), "");
			echo $response;
		}catch(Exeption $error){
			$response = response("fail", array(), "The process of extend time failed");
			echo $response;
		}
		
	}
	
	public function setExtendBooking()
	{
		$modules['price']   = $this->Model_Module->get_module_price();
        $modules['invoice'] = $this->Model_Module->get_module_invoice();
        $json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			
			// print_r($post );
			// die();
			$datetime = date("Y-m-d H:i:s");

			$getBooking = $this->Model_Api->getDataBookingById($post['booking_id']);
			$ex = $post['data']['duration']-0;
			// $duration = $ex;
			$dataBooking = $getBooking['data'];
			$dataBooking['extended_value'] = $ex ;
			$reservation_cost 			= 0;
			$duration 					= $this->Model_Invoice->calculateDurationExtend($dataBooking);
			$reservation_cost 			= $this->Model_Invoice->calculateReservationCostExtend($dataBooking);
			// $extended_duration 			= $dataBooking['extended_duration'] - 0;
			// $settingGeneral				= $this->Model_Api->getSettingDataGeneral();
			// $dataSettingGeneral 		= $settingGeneral['data'];
			// $fHour 						= $dataSettingGeneral['duration'];
			// // 
			// $cost						= $dataBooking['price'] - 0; // per hours
			// $allduration 				= $extended_duration + $duration + $ex;



			// $getHoursMeeting 			= floor($allduration / $fHour);
			// $checkHours 				= fmod($allduration,$fHour);

			
			// if($checkHours > 0){
			// 	$getHoursMeeting += 1;
			// }
			// $reservation_cost 			= $cost	* $getHoursMeeting;
			$extended_duration = $dataBooking['extended_duration'] -0;
			$total_extended = $ex +$extended_duration;
			$reservation_cost 			= $reservation_cost ;		

			
			$data = array(
				"extended_duration" => $ex,
			);
			$sql = "UPDATE booking SET 
				extended_duration=".$total_extended." ,
				cost_total_booking=".$reservation_cost." 
				WHERE booking_id='". $post['booking_id']."' ";
			
			$winvoice 	= array("booking_id" => $dataBooking['booking_id']);
			$resp3 = $this->Model_Api->querySql($sql);
			// $udata 	= $this->Model_Api->updateData("booking_invoice", $booking_invoice, $winvoice);
			$response = response("success",$winvoice, "The process of extend time is success");
			echo $response;
		}catch(Exeption $error){
			$response = response("fail", array(), "The process of extend time is failed");
			echo $response;
		}
		
	}
	public function getUsersBooking()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getUsersBooking();
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to active ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function postmonitor()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$id = "monitor-".date("YmdHis");
		$date = date('Y-m-d', strtotime($post['start']));

		$datetime = date('Y-m-d H:i:s');
		$getDataPIC = $this->Model_Api->getNikEmployee($post['picNik']);
		$getDataPIC = $getDataPIC['data'];
		$data['booking_id'] 	= $id;
		$data['title'] 			= $post['title'];
		$data['room_id'] 		= $post['room_id'];
		$data['date'] 			= $date;
		$data['start'] 			= $post['start'];
		$data['end'] 			= $post['end'];
		$data['pic'] 			= $post['picName'];
		$data['is_meal'] 		= 0;
		$data['is_deleted'] 	= 0;
		$data['is_rescheduled'] = 0;
		$data['is_canceled'] 	= 0;

		$invitation_pic = array();
		$invitation_pic['booking_id'] = $id;
		$invitation_pic['employee_id'] = $getDataPIC['id']; // employee id
		$invitation_pic['name'] = $getDataPIC['name'];
		$invitation_pic['internal'] = 1;
		$invitation_pic['attendance_status'] = 0;
		$invitation_pic['email'] = "";
		$invitation_pic['is_pic'] = 1;
		$invitation_pic['company'] = "";
		$invitation_pic['created_at'] = $datetime;
		$invitation_pic['updated_at'] = $datetime;
		$invitation_pic['is_deleted'] = 0;

		$notif_id = uniqid(rand(), true);
		$notifbatch['employee_id'] = $getDataPIC['id'];
		$notifbatch['notif_id'] = $notif_id;
		$notifbatch['booking_id'] = $id;
		$notifbatch['is_reschedule'] = 0;
		$notifbatch['is_invited'] = 1;
		$notifbatch['is_notifhandler'] = 0;
		$notifbatch['is_notifSend'] = 0;
		
		$respP = $this->Model_Api->insertData('booking', $data);
		$respP = $this->Model_Api->insertData('booking_invitation', $invitation_pic);
		$respP = $this->Model_Api->insertData('notif_booking', $notifbatch);
		if($respP['error'] == null ){
			$response = response("success", $respP['data'], "Success get data to active ");
			echo $response ;
		}else{
			$response = response("fail", $respP, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function fastBooked()
	{
		include APPPATH.'third_party/phpqrcode/qrlib.php';
		$modules['pantry'] = $this->Model_Module->get_module_pantry();
		$modules['loker'] = $this->Model_Module->get_module_loker();
		$modules['price'] = $this->Model_Module->get_module_price();
		$modules['invoice'] = $this->Model_Module->get_module_invoice();
		$modules['email'] = $this->Model_Module->get_module_email();

		$tmpdir = "assets/qr/";

		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$oldBookingId = isset($post['databook']['old_booking_id']) ? $post['databook']['old_booking_id'] : "";

		$datetime 					= date('Y-m-d H:i:s');
		$time_duration 				= ($post['duration']-0);
		$room_id 					= $post['room_id'];
		$randoom_id					= random_string('numeric', 5);
		$id 						= $randoom_id."";
		$invoice_id 				= $randoom_id."";
		$nikPic						= $post['nik'];

		$ecnyPass = encryp_data(isset($post['password']) ? $post['password'] : '');
		$username = $post['nik'];
		$check 		= $this->Model_Api->checkLogin($username, $ecnyPass);
		if ($check['username']->num_rows() <= 0) {
			$response = response("fail", array(), "User not available/exist ");
			echo $response ;	
			die();
		}
		$datauser = $check['username']->row_array();
		if (!isset($datauser['nik'])) {
				$response = response("fail", array(), "User not available/exist ");
				echo $response ;	
				die();
		}
		$nikPic = $datauser['nik'];
		$resPIC 					= $this->Model_Api->getNikEmployeeByPic($nikPic);
		$getDataPIC 				= $resPIC['data'];
		$isMerge 	= isset($post['is_merge']) ? $post['is_merge'] : 0;
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

		// print_r($getDataPIC);
		// die();
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


		$ruangan = $room['radid'];
		$reservation_cost 			= $room['price'] * $getHoursMeeting;
		$internalBatch 				= array();
		if($isMerge == true){
			$tempMergeRoomName = array();
			foreach ($mergeRoom  as $mk => $mv) {
				$ruangan_m_id = $mv;
				$this->Model_Admin->checkKondisiBookingPerRuangan($ruangan_m_id,$tanggal_meeting, $waktu_mulai,$waktu_akhir);
			}
		}else{
			$this->Model_Admin->checkKondisiBookingPerRuangan($ruangan,$tanggal_meeting, $waktu_mulai,$waktu_akhir);
		}
		// =========================================================================
		$years 						= date('Y', strtotime($post['date'])); // get tahun from date
		$y_years 					= date('y', strtotime($post['date'])); // get tahun from date
		$months						= date('m', strtotime($post['date'])); // get tahun from date
		$days 						= date('d', strtotime($post['date'])); // get tahun from date

		$sql_invoice 	= "SELECT COALESCE(max(no_order), '') as no_order from booking
							WHERE YEAR(date) = '".$years."'";
		$resInvoice 	= $this->Model_Api->querySql($sql_invoice);
		$rowInvoice 	= $resInvoice->row_array();

		$alocationId = $getDataPIC['department_id'] ; // alocation_id
		$invAlocationID = $alocationId . '-E-Meeting';

		if($rowInvoice['no_order'] == "" || $rowInvoice['no_order'] == null ){
			$newNoUrut			= sprintf("%03d", "1");
			$formatInvoice		= $newNoUrut . "/" . $invAlocationID . "/" . $months ."/" .$y_years;
			$formatInvoice2		= $newNoUrut . "/" . $invAlocationID . "/" . $months ."/" .$y_years;
		}else{
			$oldNoInv	 		= $rowInvoice['no_order'];
			$spOldInv			= explode("/", $oldNoInv);
			$noUrut				= ($spOldInv[0]-0) + 1;
			$newNoUrut			= sprintf("%03d", $noUrut); // returns 001
			$formatInvoice		= $newNoUrut . "/" . $invAlocationID . "/" . $months ."/" .$y_years;
			$formatInvoice2		= $newNoUrut . "/" . $invAlocationID . "/" . $months ."/" .$y_years;
		}


		// CREATE BOOKING ROW
		$data 						= array();  // DATA BOOKING
		$data['booking_id'] 		= $id;
		$data['no_order'] 			= $formatInvoice;
		$data['title'] 				= $post['title'];
		$data['room_id'] 			= $room['radid'];
		$data['date'] 				= $post['date'];
		$data['start'] 				= $time1;
		$data['end'] 				= $time2;
		$data['total_duration'] 	= $duration;
		$data['duration_per_meeting'] = $fHour ;
		$data['cost_total_booking'] = $reservation_cost ;
		$data['alocation_id'] 		= $getDataPIC ['alocation_id'];
		$data['alocation_name'] 	= $getDataPIC ['alocation_name'];
		$data['pic'] 				= $getDataPIC['name'];
		$data['is_alive'] 			= 1;
		$data['is_meal'] 			= 0;
		$data['is_deleted'] 		= 0;
		$data['is_rescheduled'] 	= 0;
		$data['is_canceled']		= 0;
		$data['is_expired']			= 0;
		$data['created_at'] 		= $datetime;
		$data['is_device'] 			= 2;
		$data['created_by'] 		= $nikPic; // mobile created
		$data['external_link'] 		= isset($post['link']) ? $post['link']: "";
		$data['note'] 				= isset($post['note']) ? $post['note'] : "";
		$data['room_name'] 			= $room_name;
		$data['is_merge'] 			= $isMerge;
		$data['merge_room_name'] 	= $merge_room_name;
		$data['merge_room_id'] 		= $merge_room_id;
		$data['merge_room'] 		= $dataMergeRoomWidthJson;
		// // // $invitation_pic 

		// server 
		
		$invitation_pic = array();
		$invitation_pic['booking_id'] 			= $id;
		$invitation_pic['nik'] 					= $getDataPIC['nik']; // employee id
		$invitation_pic['name'] 				= $getDataPIC['name'];
		$invitation_pic['internal'] 			= 1;
		$invitation_pic['attendance_status']	= 0;
		$invitation_pic['email'] 				= $getDataPIC['email']; 
		$invitation_pic['is_pic'] 				= 1;
		$invitation_pic['company'] 				= "";
		$invitation_pic['pin_room'] 			= random_string('numeric', 6);
		$invitation_pic['created_at'] 			= $datetime;
		$invitation_pic['created_by']			= $nikPic;
		$invitation_pic['is_deleted'] 			= 0;
		$qrnvitationPIC = $id."_".$invitation_pic['pin_room'];
		QRcode::png($qrnvitationPIC,$tmpdir.$qrnvitationPIC.".png");
		// batch 
		// insert of PIC invitation
		$ipicemail['nik'] 					= $getDataPIC['nik']; // employee id
		$ipicemail['name'] 					= $getDataPIC['name'];
		$ipicemail['division_id']			= 0;
		$ipicemail['is_pic'] 				= 1;
		$ipicemail['email'] 				= $getDataPIC['email']; 
		$ipicemail['pin_room'] 				= $invitation_pic['pin_room'];
		
		array_push($dataEmailInternal, $ipicemail);
		// External invitation
		$dataToSend['internal'] 			= $dataEmailInternal;
		$dataToSend['eksternal'] 			= array();
		$batchSendingEmail 					= json_encode($dataToSend);
		$batchSendingNotif 					= json_encode($dataToSend['internal']);
		$sending_email 		= array(
			"batch" 		=> $batchSendingEmail,
			"type" 			=> 1,
			"booking_id" 	=> $id,
			"pending" 		=> 0,
			"is_status" 	=> 1,
			"error_sending" => 0,
			"success" 		=> 0,
			"created_at" 	=> $datetime,
			"updated_at" 	=> $datetime,
			"is_deleted" 	=> 0
		);
		$sending_notif		= array(
			"batch" 		=> $batchSendingNotif,
			"type" 			=> 1,
			"booking_id" 	=> $id,
			"is_status" 	=> 1,
			"pending" 		=> 0,
			"error_sending" => 0,
			"success" 		=> 0,
			"created_at" 	=> $datetime,
			"updated_at" 	=> $datetime,
			"is_deleted" 	=> 0
		);
		
		$data_invoice = array(
			"invoice_no" => $invoice_id,
			"invoice_format" => $formatInvoice2,
			"booking_id" => $id, // bookingid
			"rent_cost" => $reservation_cost,
			"alocation" => $getDataPIC ['alocation_id'],
			"time_before" => $datetime,
			"created_at" 	=> $datetime,
			"created_by" 	=> $nikPic,
			"invoice_status" 	=> 0, // before send
		);

		$_notif = array();
		$_notif['datetime'] 		= $datetime;
		$_notif['nik'] 				= $nikPic; // user id
		$_notif['type'] 			= 1; // booking is 1
		$_notif['value'] 			= $id; // booking id
		$_notif['title'] 			= "Create a meeting schedule";
		$_notif['body'] 			= $post['title'] ." - ". getformatDate($post['date']);
		$_notif['is_sending'] 		= 0;
		$_notif['is_deleted'] 		= 0;
		$_notif['created_at'] 		= $datetime;
		$type_notif 				= 1; // notification_type 1=booking
		$notif_insert				= false; // notification_type 1=booking
		$notifcollectdata = array();
		array_push($notifcollectdata, $_notif);
		$this->Model_Notif->insertNotifAdmin(12, "Create meeting", $data['title']);
		$this->Model_Notif->insertNotifBatch($notifcollectdata);
		$meeting_title = $post['title'];
		$meeting_date = $post['date'];
		$meeting_start = date('H:i:s',strtotime($time1));
		$meeting_end = date('H:i:s',strtotime($time2));
		
		$room_name = $room['name'];
		$notification_title = "Direct Booking of ".$meeting_title;
		$notification_body = $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end). " at " .$room_name;
		
		$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$dataToSend['internal'] ,$type_notif,$notif_insert );
		$type_notif_admin = 12; // booking
		$this->Model_Notif->insertNotifAdminApi($type_notif_admin, "Create meeting", $meeting_title, $ipicemail['nik'] );
		// die();
		$resp3 = $this->Model_Api->insertData('booking_invoice', $data_invoice);
		$respw = $this->Model_Api->insertData('sending_email', $sending_email);
		$resp4 = $this->Model_Api->insertData('sending_notif', $sending_notif);
		$respP 			= $this->Model_Api->insertData('booking_invitation', $invitation_pic);
		// INSERT BOOKING
		if($isMerge == false){
			$resp3 = $this->Model_Admin->insertData('booking', $data);
		}else{
			foreach ($dataMergeRoom as $SP => $SPVAL) {
				$data['room_id'] = $SPVAL['radid'] ;
				$resp3 = $this->Model_Admin->insertData('booking', $data);
			}

		}

		$booking_id = $id;
		$getBooking 		= $this->Model_Api->getDataBookingById($booking_id); //
		if($getBooking ['error'] != null){
			$response = response("fail", array(), "Data not exist ");
			echo $response;	
			die();
		}
		$dataBooking = $getBooking['data'];
		$emailBooking = $dataBooking;
		$emailBooking['format_time_start'] = $this->formatTime($waktu_timestart);
		$emailBooking['format_time_end'] = $this->formatTime($waktu_timeend);
		$emailBooking['format_date'] = $this->formatDate($tanggal_meeting);

		// print_r($dataMergeRoom);
		// die();
		foreach ($dataToSend['internal'] as $key => $people) {
			$pNotif = $this->Model_Notif->sendEmailInternal("invitation", $emailBooking, $people);
		}	

		// 2022-10-13 // LOCKER MODULE
		if($modules['loker']['is_enabled'] == 1 ){
			$dataLockerSystem			= array();
			foreach ($dataEmailInternal as $vlo) {
				array_push($dataLockerSystem, $vlo['card_number']);
			}
			$dataLocker = $this->Model_Admin->querySql('SELECT * FROM locker WHERE 1=1 AND is_deleted=0 AND auto_reserve=1')->row_array();
			if(isset($dataLocker['name'])){
				foreach($dataLockerSystem as $noCard){
					$urlLockerSystem = $dataLocker['ip_locker']; // // http://192.168.1.14/lokerr/
					$this->Model_Admin->uploadDataToLockerSystem($urlLockerSystem,$noCard );
				}
			}
		}	
		$response = response("success", array(), "Success create a booking ".$post['title']);
		echo $response;
		
	}

	public function getMeetingListDisplay()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getMeetingListByDisplay($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to LIS ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
	}
	public function getMeetingMergeListDisplay()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Api2->getMeetingMergeListByDisplay($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to LIS ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function getExtendTime()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$rawconfig = $this->Model_Api->getGeneralSetting();
		$config =$rawconfig ['data'];
		$date = $post ['date'];
		if(!isset($post ['time'])){
			$time =date("H:i:s");
		}else{
			$time =$post ['time'];

		}
		
		$pieceTime = $config['extend_count_time'];
		// print_r($pieceTime . " " .$config['extend_meeting_max']);
		if($config['extend_meeting'] == 1){
			$rawbooking = $this->Model_Api->getBookingInfo($post['booking_id']);
			$booking = $rawbooking['data'];
			$work_end = $booking['work_end'];
			$extend = $booking['extended_duration']-0;
			// print_r($booking);
			$max = $config['extend_meeting_max']-0;
			$collectcheck = array();
			if($max >= $pieceTime){
				$count = $max/$pieceTime;
				$end = $booking['end'];
				$end_with_extend = date($date.' H:i:s',strtotime($end . "+".$extend." minutes"));
				$select = "SELECT * FROM ( ";
				for($x = $pieceTime; $x <= $max; $x+=$pieceTime){
					$timeroom = $date ." ".$work_end;
					// $checknow 	= $date ." ".$time ;
					$convertTime = strtotime($end_with_extend);
					$aftersumnow = date($date.' H:i:s', strtotime("+".$x." minutes",$convertTime));
					// echo  $timeroom ." - ". $aftersumnow . " "  .  " - " . $end_with_extend . "<br/>"; 
					if(strtotime($aftersumnow) > strtotime($timeroom)){
						array_push($collectcheck, $x); //
					}
					$endtimeExtent = date('Y-m-d H:i:s',strtotime($end_with_extend . "+".$x." minutes"));
					if($max == $x){
						$select .= "SELECT CONCAT('".$x ."') as duration, COUNT(*) as book, TIME('".$endtimeExtent."') as time_data FROM booking 
						WHERE end_early_meeting=0 
						AND is_alive = 1 
						AND room_id='".$booking['room_id']."' AND date='".$date."' 
						AND TIME('".$endtimeExtent ."') 
						BETWEEN TIME(start) AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) ";
					}else{
						$select .= "SELECT CONCAT('".$x ."') as duration, COUNT(*) as book, TIME('".$endtimeExtent."') as time_data FROM booking 

						WHERE end_early_meeting=0 
						AND is_alive = 1 
						AND room_id='".$booking['room_id']."' AND date='".$date."' 
						AND TIME('".$endtimeExtent ."') 
						BETWEEN TIME(start) AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) union ";
					}
				}
				$select 	.= " ) datatime ";
				$queryTime 	= $this->Model_Api->querySql($select);
				$datax 		= $queryTime->result_array();
				$lendata = count($datax);
				foreach ($datax as $key => $value) {
					if ( count($collectcheck) >0 ) {
						if (array_search($value['duration'],$collectcheck) >=0) {
							$datax[$key]['book'] = "1";
							# code...
						}
						
					}
				}
				foreach ($datax as $key => $value) {
					if($value['book'] == "1" ){
						unset($datax[$key]);
					}
				}

				$lendata = count($datax);
				if($lendata > 0){
					$response = response("success", $datax , "Extend time available");
					echo $response ;
				}else{
					$response = response("fail", array() , "Extend time not available. ");
					echo $response ;
				}
			}else{
				$response = response("fail", array(), "Extend time not available. ");
				echo $response ;
			}
			
		}else{
			$response = response("fail", array(), "Extend Time feature is disabled, please enable for use this feature.");
			echo $response ;
		}
	}
	public function getExtendTimeDisplay()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$rawconfig = $this->Model_Api->getGeneralSetting();
		$config =$rawconfig ['data'];
		$date = $post ['date'];
		if(!isset($post ['time'])){
			$time =date("H:i:s");
		}else{
			$time =$post ['time'];

		}
		
		$pieceTime = $config['extend_count_time'];
		// print_r($config['extend_meeting'] );
		// print($pieceTime . " - ". $config['extend_meeting_max']);
		if($config['extend_meeting'] == 1){
			$rawbooking = $this->Model_Api->getBookingInfo($post['booking_id']);
			$booking = $rawbooking['data'];
			$work_end = $booking['work_end'];
			$extend = $booking['extended_duration']-0;
			// print_r($booking);
			$max = $config['extend_meeting_max']-0;
			$collectcheck = array();
			// print_r($pieceTime . " ".$max );

			if($max >= $pieceTime){
				$count = $max/$pieceTime;
				$end = $booking['end'];
				$end_with_extend = date('Y-m-d H:i:s',strtotime($end . "+".$extend." minutes"));
				$select = "SELECT * FROM ( ";
				for($x = $pieceTime; $x <= $max; $x+=$pieceTime){
					$timeroom = $date ." ".$work_end;
					$checknow 	= $date ." ".$time ;
					$convertTime = strtotime($end_with_extend);
					$aftersumnow = date('Y-m-d H:i:s', strtotime("+".$x." minutes",$convertTime));
					if(strtotime($aftersumnow) > strtotime($timeroom)){

						array_push($collectcheck, $x); //
					}
					$endtimeExtent = date('Y-m-d H:i:s',strtotime($end_with_extend . "+".$x." minutes"));
					if($max == $x){
						$select .= "SELECT CONCAT('".$x ."') as duration, COUNT(*) as book, TIME('".$endtimeExtent."') as time_data FROM booking 
						WHERE end_early_meeting=0 
						AND is_alive = 1 
						AND room_id='".$booking['room_id']."' AND date='".$date."' 
						AND TIME('".$endtimeExtent ."') 
						BETWEEN TIME(start) AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) ";
					}else{
						$select .= "SELECT CONCAT('".$x ."') as duration, COUNT(*) as book, TIME('".$endtimeExtent."') as time_data FROM booking 
						WHERE end_early_meeting=0 
						AND is_alive = 1 
						AND room_id='".$booking['room_id']."' AND date='".$date."' 
						AND TIME('".$endtimeExtent ."') 
						BETWEEN TIME(start) AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) union ";
					}
				}
				$select 	.= " ) datatime ";
				$queryTime 	= $this->Model_Api->querySql($select);
				$datax 		= $queryTime->result_array();
				$lendata = count($datax);
				foreach ($datax as $key => $value) {
					if ( count($collectcheck) >0 ) {
						if (array_search($value['duration'],$collectcheck) >=0) {
							$datax[$key]['book'] = "1";
							# code...
						}
						
					}
				}
				foreach ($datax as $key => $value) {
					if($value['book'] == "1" ){
						unset($datax[$key]);
					}
				}
				$lendata = count($datax);
				// print_r($datax);

				// echo "123";
				// die();
				if($lendata > 0){
					$response = response("success", $datax , "Extend time available");
					echo $response ;
				}else{
					$response = response("fail", array() , "Extend time not available.");
					echo $response ;
				}
			}else{
				$response = response("fail", array(), "Extend time not available.");
				echo $response ;
			}
			
		}else{
			$response = response("fail", array(), "Extend Time feature is disabled, please enable for use this feature.");
			echo $response ;
		}
	}

	public function getTimeForFastBooked()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$rawconfig = $this->Model_Api->getGeneralSetting();
		$dataSettingGeneral =$rawconfig ['data'];
		$room_id = $post['room_id'];
		$dateTimeServer = date("Y-m-d H:i:s");
		$date =$post ['date'];
		$pieceTime = $dataSettingGeneral['duration']-0;
		$max = $dataSettingGeneral['max_display_duration']-0;

		$isMerge =@$post ['date'];

		// print_r($pieceTime );
		// print_r($max );
		$date 			= date("Y-m-d");
		$meetingRoom 	= array();
		$timearray 		= array();
		// if()
		$dataRoom 		= $this->Model_Api->getDataRoomById($room_id)['data'];
		
		if(count($dataRoom) <= 0){

			$response = response("fail", array(), "Booked Time feature is disabled, please enable for use this feature.");
			echo $response;
			die();
		}
		if($dataRoom[0]['type_room'] == 'merge'){
			$mergeRoom 		= $this->Model_Admin->getDataMergeRoomBooking($room_id)['data'];
			$dataRoom = $mergeRoom; 
		}

		// $dataRoom 		= $dataRoom['data'];
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$stTime 		= $date." 00:00:00";
		$timess 		= strtotime($stTime);
		
		if($pieceTime == 15){
			$pieceTime = 30;
		}
	
		foreach ($dataRoom  as $k => $v) {
			$sql 		= "";
			$room_id 	= $v['radid'];
			$sql 		.= "SELECT * FROM ( ";
			for($x = $pieceTime; $x <= $max; $x+=$pieceTime){
				$timeData = date('Y-m-d H:i:s',strtotime($dateTimeServer . "+".$x." minutes"));
				if ($max == $x) {
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration MINUTE))";
				}else{
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration MINUTE))";
					$sql .= " UNION ";
				}
				// echo $sql;
				// die;
			}
			$sql 			.= ") room_time";
			$queryTime 		= $this->Model_Api->querySql($sql);
			$dataTimeArray 	= $queryTime->result_array();
			$dataRoom[$k]['datatime'] = $dataTimeArray;
			if($v['type_room'] == "merge"){
				$mergeRoom 		= $this->Model_Admin->getDataMergeRoomBooking($room_id)['data'];
				$dataRoom[$k]['merge_room']  = $mergeRoom;
			}
		} // foreach $dataroo

		// print_r($dataRoom);
		// die();
		$databack = array();
		if(count($dataRoom) > 0){
			$datafilter = array();
			$databack = $dataRoom[0];
			$datatimeAr = $databack['datatime'];
			$nnn = 0;
			foreach ($datatimeAr  as $kn => $vn) {
				$nnn += $pieceTime;
				$datatimeAr[$kn]['duration'] = $nnn;
			}
			foreach ($datatimeAr  as $kn => $vn) {
				if($kn <=3){
					array_push($datafilter, $vn);
				}
			}

			$response = response("success", $datafilter , "Booked time available");
			echo $response ;			
		}else{
			$response = response("fail", array(), "Booked Time feature is disabled, please enable for use this feature.");

		}


	}

	public function getTimeMergeForFastBooked()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$rawconfig = $this->Model_Api->getGeneralSetting();
		$dataSettingGeneral =$rawconfig ['data'];
		$room_id = $post['room_id'];
		$dateTimeServer = date("Y-m-d H:i:s");
		$date =$post ['date'];
		$pieceTime = $dataSettingGeneral['duration']-0;
		$max = $dataSettingGeneral['max_display_duration']-0;

		$isMerge =@$post ['date'];
		$date 			= date("Y-m-d");
		$meetingRoom 	= array();
		$timearray 		= array();
		// if()
		$dataRoom 		= $this->Model_Admin->getDataMergeRoomBooking($room_id)['data'];
		if(count($dataRoom) <= 0){

			$response = response("fail", array(), "Booked Time feature is disabled, please enable for use this feature.");
			echo $response;
			die();
		}
		
		// $dataRoom 		= $dataRoom['data'];
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$stTime 		= $date." 00:00:00";
		$timess 		= strtotime($stTime);
		
		if($pieceTime == 15){
			$pieceTime = 30;
		}
		// $lenTime = count($timearray)-1;
		foreach ($dataRoom  as $k => $v) {
			$sql 		= "";
			$sql 		.= "SELECT * FROM ( ";
			for($x = $pieceTime; $x <= $max; $x+=$pieceTime){
				$timeData = date('Y-m-d H:i:s',strtotime($dateTimeServer . "+".$x." minutes"));
				if ($max == $x) {
					$sql 	.= "SELECT COUNT(*) as book, TIME('".$timeData ."') time_array, b.room_id  FROM booking b 
					LEFT JOIN room r ON b.room_id=r.radid  
					WHERE room_id='".$v['radid']."' AND date='2020-01-31' 
					AND b.end_early_meeting=0 
					AND  AND b.is_alive = 1 
					AND TIME('2020-01-31 18:00:00') 
					BETWEEN TIME(start) AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) ";
				}else{
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData ."') time_array, b.room_id  FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE room_id='".$v['radid']."' AND date='".$date."' 
						AND  AND b.is_alive = 1 
						AND b.end_early_meeting=0 
						AND TIME('".$timeData ."') 
						BETWEEN TIME(start) AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) union ";
				}
				// echo $sql;
				// die;
			}
		}
		foreach ($dataRoom  as $k => $v) {
			$sql 		= "";
			$room_id 	= $v['radid'];
			$sql 		.= "SELECT * FROM ( ";
			for($x = $pieceTime; $x <= $max; $x+=$pieceTime){
				$timeData = date('Y-m-d H:i:s',strtotime($dateTimeServer . "+".$x." minutes"));
				if ($max == $x) {
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration MINUTE))";
				}else{
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration MINUTE))";
					$sql .= " UNION ";
				}
				// echo $sql;
				// die;
			}
			$sql 			.= ") room_time";
			$queryTime 		= $this->Model_Api->querySql($sql);
			$dataTimeArray 	= $queryTime->result_array();
			$dataRoom[$k]['datatime'] = $dataTimeArray;
			// print_r($dataTimeArray);
			// die();
		} // foreach $dataroo
		$databack = array();
		if(count($dataRoom) > 0){
			$datafilter = array();
			
			foreach ($dataRoom  as $k => $v) {
				$databack = $v;
				$datatimeAr = $databack['datatime'];
				$pieceTime_da = $pieceTime;
				$nnn = 0;

				foreach ($datatimeAr  as $kn => $vn) {
					$nnn += $pieceTime_da;
					$dataRoom[$k]['datatime'][$kn]['duration'] = $nnn;
				}
			}
			
			$response = response("success", $dataRoom , "Booked time available");
			echo $response ;			
		}else{
			$response = response("fail", array(), "Booked Time feature is disabled, please enable for use this feature.");

		}


	}
	public function getMeetingOccupiedDisplay()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);




		$getData = $this->Model_Api->getMeetingOccupiedByDisplay($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to active ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function getMeetingOccupiedDisplayAllStatus()
	{
		// $room_id = $this->uri->segment(4);
		$date = date("Y-m-d");
		$time = date("H:i:s");
		
		$collection = [];
		$getDataRoom = $this->Model_Admin->getDataRoom2();
		$dataRoom = $getDataRoom['data'];
		foreach ($dataRoom as $key => $value) {
			$whdata = [
				'date' => $date,
				'time' => $time,
				'room_id' =>$value['radid'],
			];
			$getData = $this->Model_Api->getAllMeetingOccupiedByDisplayStatus($whdata);
			$roomBooking = $getData['data'];
			$dataRoomStatus = [
				"date"  => $date,
				"time"  => $time,
				"room_id" => $value['radid'],
				"room_name" => $value['name'],
				"schedule_occupied" => count($roomBooking) <= 0 ? false : true,
			];
			array_push($collection, $dataRoomStatus);

		}
		
		$response = response("success", $collection, "success get a active ");
			echo $response ;
		
	}


	public function getTimeMonitor()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getTimeMonitor($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to active ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function getactiveroom()
	{
		$json = file_get_contents("php://input");

		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getDataActiveByRoom($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to active ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function getsoonroom()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getDataMonitorSoonByRoom($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to active ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function getactivesSchedule()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getDataActiveByNik($post);
		$datalist = $getData['data'];
		$rule = $this->Model_Api->getGeneralSetting()['data'];
		foreach ($datalist as $key => $value) {
			$datalist[$key]['before_meeting'] = $rule['notif_unuse_before_meeting'];
		}
		if($getData['error'] == null ){
			$response = response("success", $datalist, "Success get data to active ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}

	public function getallschedule()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$getData = $this->Model_Api->getAllSchedule($post);
		$datalist = $getData['data'];
		$rule = $this->Model_Api->getGeneralSetting()['data'];
		foreach ($datalist as $key => $value) {
			$datalist[$key]['before_meeting'] = $rule['notif_unuse_before_meeting'];
		}
		if($getData['error'] == null ){
			$response = response("success", $datalist, "Success get data to schedule ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function getallscheduleDate()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		
		// TIMEZONE
		// TIMEZONE
		// TIMEZONE
		$timezone = APP_GMT;
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
		}
		date_default_timezone_set($timezone);

		// print_r($post );
		// die();
		$where = " 1=1 ";
		if(isset($post['search'])){
			if(isset($post['search']['date1_search']) && isset($post['search']['date2_search'])){

				if($post['search']['date1_search'] != "" && $post['search']['date1_search'] != ""){
					$where .= " AND b.date >= '".$post['search']['date1_search']."'  AND  b.date <= '".$post['search']['date2_search']."'  ";
				}
			}else if(isset($post['search']['date1_search'])){
				if($post['search']['date1_search'] != "" ){
					$where .= " AND b.date >= '".$post['search']['date1_search']."'  ";
				}
			}
			
			if(isset($post['search']['status_search'])){
				if($post['search']['status_search'] == ""){
					$where .= " ";
				}else if($post['search']['status_search'] == "expired_moved"){
					$where .= " AND is_expired=1 AND is_moved=1 ";

				}else if($post['search']['status_search'] == "reject"){
					// $where .= " is_expired=1 AND is_moved=1 ";
					$where .= " AND is_expired=1 AND is_approve=2 AND is_enable_approval=1 ";

				}else if($post['search']['status_search'] == "canceled"){
					$where .= " AND is_expired=1 AND is_canceled=1 ";

				}else if($post['search']['status_search'] == "expired_released"){
					$where .= " AND is_expired=1 AND is_released=1 ";

				}else if($post['search']['status_search'] == "expired_early"){
					$where .= " AND is_expired=1 AND end_early_meeting=1 ";

				}else if($post['search']['status_search'] == "expired"){
					$where .= " AND is_expired=1 ";

				}else if($post['search']['status_search'] == "pending_moved"){
					$where .= " AND is_alive=0 AND is_moved=1 ";

				}else if($post['search']['status_search'] == "pending"){
					$where .= " AND is_alive=0 AND is_approve=0 AND is_enable_approval=1 ";

				}else if($post['search']['status_search'] == "active"){
					$where .= " AND is_alive=1 AND is_expired=0 ";

				}else if($post['search']['status_search'] == "queue"){
					$where .= " AND is_alive=1 AND is_expired=0 ";

				}else if($post['search']['status_search'] == "soon"){
					$where .= " AND is_alive=1 AND is_expired=0 ";
				}else{
					$where .= " ";
				}
			}


		}
		$getData = $this->Model_Api->getNewAllScheduleDate($where, $post);
		$datalist = $getData['data'];
		$rule = $this->Model_Api->getGeneralSetting()['data'];
		foreach ($datalist as $key => $value) {
			$datalist[$key]['before_meeting'] = $rule['notif_unuse_before_meeting'];
		}
		if($getData['error'] == null ){
			$response = response("success", $datalist, "Success get data to schedule ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}

	

	public function getallscheduleCalendar()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$period = new DatePeriod(
		     new DateTime($post['date1']),
		     new DateInterval('P1D'),
		     new DateTime($post['date2'])
		);
		$calenderData = [
			'meeting' => [],
			'desk' => [],
			'pantry' => [],
		];
		foreach ($period as $key => $value) {
		    //$value->format('Y-m-d')       
		    $tglMeeting =  $value->format('Y-m-d') ;
		    $dataModel = $post;
		    $dataModel['date1']  = $tglMeeting;
		    $dataModel['date2']  = $tglMeeting;
		    $calMet = $this->Model_Api->getAllScheduleDate($dataModel);
		    $calenderData['meeting'][$tglMeeting] = $calMet['data'];
		}

		// $getData = $this->Model_Api->getAllScheduleDate($post);
		// $datalist = $getData['data'];
		$response = response("success", $calenderData, "Success get data to schedule ");
		echo $response ;
		// $rule = $this->Model_Api->getGeneralSetting()['data'];
		// foreach ($datalist as $key => $value) {
		// 	$datalist[$key]['before_meeting'] = $rule['notif_unuse_before_meeting'];
		// }
		// if($getData['error'] == null ){
		// 	$response = response("success", $datalist, "Success get data to schedule ");
		// 	echo $response ;
		// }else{
		// 	$response = response("fail", $getData, "Failed error a active ");
		// 	echo $response ;
		// }
		
	}
	public function getListToday()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getListToday($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to schedule ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
	}
	public function getListAllToday()
	{

		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getListAllToday($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to schedule ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
	}
	public function getListAllMeeting()
	{
		$type = $this->uri->segment(4);
		switch ($type) {
			case 'user':
				$json = file_get_contents("php://input");
				$post = json_decode($json, TRUE);
				$getData = $this->Model_Api->getListAllMeeting($type,$post);
				if($getData['error'] == null ){
					$response = response("success", $getData['data'], "Success get data to schedule ");
					echo $response ;
				}else{
					$response = response("fail", $getData, "Failed error a schedule ");
					echo $response ;
				}
				break;
			case 'general':
				$json = file_get_contents("php://input");
				$post = json_decode($json, TRUE);
				$getData = $this->Model_Api->getListAllMeeting($type,$post);
				if($getData['error'] == null ){
					$response = response("success", $getData['data'], "Success get data to schedule ");
					echo $response ;
				}else{
					$response = response("fail", $getData, "Failed error a schedule ");
					echo $response ;
				}
				# code...
				break;
			default:
				$response = response("fail", $getData, "Failed error a schedule ");
					echo $response ;
				# code...
				break;
		}
		
	}
	public function getsoonschedule()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getSoonSchedule($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to schedule ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function getexpiredschedule()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getExpiredSchedule($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to schedule ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function deleteScheduleList()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$update = array('is_deleted' => 1);
			$w = array('booking_id' => $post['booking_id'], 'nik' => $post['nik']);
			$getData = $this->Model_Api->updateData('booking_invitation',$update, $w);

			$response = response("success", $getData['data'], "Success delete list data schedule ");
			echo $response ;

		}catch(Exception $error){
			$response = response("fail", $getData, "Failed delete list ");
			echo $response ;

		}

	}
	public function getListMoved()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Api2->getListMoved($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to schedule moved ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
	}
	
	public function delete()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// print_r($post);
		// $getData = $this->Model_Api->deleteScheduleInvitation($post);
		// if($getData['error'] == null ){
		// 	$response = response("success", $getData['data'], "Success get data to schedule ");
		// 	echo $response ;
		// }else{
		// 	$response = response("fail", $getData, "Failed error a active ");
		// 	echo $response ;
		// }
		

	}
	public function cancel()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$dataSendAr = array(
			"is_deleted"=> 0,
		);
		$whereAr = array(
			"booking_id"=> $post['booking_id'],
		);

		$bookingAr = array(
			"is_canceled" => 1,
			"is_rescheduled" => 0,
		);
		$dataBooking =  $this->Model_Api->updateData('booking',$bookingAr, $whereAr);
		if($dataBooking['error'] == null ){
			$getData = $this->Model_Api->updateData('sending_email',$dataSendAr, $whereAr);
			if($getData['error'] == null ){
				$response = response("success", $getData['data'], "Success cancel data to schedule ");
				echo $response ;
			}else{
				$response = response("fail", $getData, "Failed error a cancel ");
				echo $response ;
			}
		}else{
			$response = response("fail", $getData, "Failed error a cancel ");
			echo $response ;
			die();
		}
	}
	public function postEndMeeting()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);


		$ecnyPass = encryp_data(isset($post['password']) ? $post['password'] : '');
		$username = $post['npk'];
		$check 		= $this->Model_Api->checkLogin($username, $ecnyPass);
		if ($check['username']->num_rows() <= 0) {
			$response = response("fail", array(), "User not available/exist ");
			echo $response ;	
			die();
		}
		$datauser = $check['username']->row_array();
		if (!isset($datauser['nik'])) {
			$response = response("fail", array(), "User not available/exist ");
			echo $response ;	
			die();
		}
		$nikPic = $datauser['nik'];

		$booking_id = isset($post['booking_id']) ? $post['booking_id'] : "";
		$datetime = date("Y-m-d H:i:s");
		$dataBooking 		= $this->Api2->getBookingPic($booking_id);
		if (!isset($dataBooking['nik'])) {
			$response = response("fail", array(), "Meeting schedule not available/exist ");
			echo $response ;	
			die();
		}
		if ($dataBooking['nik'] != $nikPic) {
			$response = response("fail", array(), "You don't have access, Only PIC/Host could be end of meeting ");
			echo $response ;	
			die();
		}


		$datarow = array(
			"end_early_meeting" => 1,
			"updated_at" => $datetime,
			"updated_by" => $nikPic,
			"is_alive" => 4,
			"early_ended_by" => $nikPic,
			"early_ended_at" => $datetime ,
			"text_early" => "By Display Signage"
		);

		$whereAr = array(
			"booking_id" => $post['booking_id']
		);
		$getData = $this->Model_Api->updateData('booking',$datarow, $whereAr);
		$response = response("success", array(), "Success data to end meeting ");
		echo $response ;	
	}
	public function getCheckDateTime()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date = $post['date'];
		$start = $post['start'];
		$end = $post['end'];
		$meetingRoom = array();
		$dataRoom = $this->Model_Api->getDataRoom();
		if($dataRoom['error'] == null){
			foreach ($dataRoom['data'] as $key => $value) {
				$dataX = $this->Model_Api->checkBookingRoom($date, $start, $end, $value['id']);
				if($dataX['error'] == null){
					$ar = array(
						"room_id" => $value['id'],
						"count" => count($dataX['data'] ),
						"data_room" => $value,
					);
					array_push($meetingRoom, $ar);
				}else{
					echo response("fail", array(), "Time to meeting ");
					exit();
					die();
				}
			}
			echo response("success", $meetingRoom, "Get Success ");
		}else{
			echo response("fail", array(), "Time to meeting ");
		}
		
	}
	public function postEndMeetingMobile()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$datetime = date("Y-m-d H:i:s");


		$ddd = $datetime;
		$getBooking 		= $this->Model_Api->getDataBookingById($post['booking_id']);
		$dataBooking 		= $getBooking['data'];
		$getBookingInv 		= $this->Model_Admin->getDataBookingInvById($post['booking_id'])['data'];
		$room_name 			= $dataBooking['room_name'];
		$notifcollectdata 	= array();
		// die();
		$datarow = array(
			"end_early_meeting" => 1,
			"is_expired" => 1,	
			"updated_at" => $datetime,
			"updated_by" => $post['nik'],
			"is_alive" => 4,
			"early_ended_by" => $post['nik'],
			"early_ended_at" => $datetime ,
			"text_early" => "By Mobile Apps",
		);
		$whereAr = array(
			"booking_id" => $post['booking_id']
		);
		$getData = $this->Model_Api->updateData('booking',$datarow, $whereAr);
		foreach ($getBookingInv as $val) {
			if($val['internal'] == 1){
				// only internal
				$_notif 					= array();
				$_notif['datetime'] 		= $datetime;
				$_notif['nik'] 				= $val['nik']; // user id
				$_notif['type'] 			= 1; // booking is 1
				$_notif['value'] 			= $post['booking_id']; // booking id
				$_notif['title'] 			= "End meeting";
				$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				$_notif['is_sending'] 		= 0;
				$_notif['is_deleted'] 		= 0;
				$_notif['created_at'] 		= $datetime;
				if($val['is_pic'] == 1){
					$_notif['title'] 			= "End meeting";
					$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				}
				array_push($notifcollectdata, $_notif);
			}
			
		}
		if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title 		= $dataBooking['title'];
				$meeting_date 		= $dataBooking['date'];
				$explodeS 			= explode(" ", $dataBooking['start']);
				$explodeE 			= explode(" ", $dataBooking['end']);
				$meeting_start 		= $explodeS[1];
				$meeting_end 		= $explodeE[1];
				$notification_title = "End Meeting of ".$meeting_title;
				$notification_body 	= $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end). " at " .$room_name;
				$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
		}
		$response = response("success", $datarow , "Success data to end meeting ");
		echo $response ;	
	}

	public function postAttendMobile()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$datetime = date("Y-m-d H:i:s");

		if(!isset($post['attend']) || !isset($post['booking_id']) || !isset($post['username'])  ){
			$response = response("fail", array(), "Some parameter is gone ");
			echo $response;	
			die();
		}
		$datarow = array(
			"updated_at" => $datetime,
			"updated_by" => $post['username'],
			"attendance_status" => ($post['attend']-0),
			"execute_attendance" => 1,
			"attendance_reason" => isset($post['reason']) ? $post['reason']:"",
		);
		$whereAr = array(
			"booking_id" => $post['booking_id'],
			"nik" => $post['nik'],
		);
		$getData = $this->Model_Api->updateData('booking_invitation',$datarow, $whereAr);
		$response = response("success", array(), "Success get data to insert attend meeting ");
		echo $response;	
	}

	public function postInviteAttendMobile()
	{
		include APPPATH.'third_party/phpqrcode/qrlib.php';
		$tmpdir = "assets/qr/";

		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$datetime = date("Y-m-d H:i:s");

		if(!isset($post['list_invitation']) || !isset($post['booking_id']) || !isset($post['username'])  ){

			$response = response("fail", array(), "Some parameter is gone ");
			echo $response;	
			die();
		}

		$booking_id = isset($post['booking_id']) ? $post['booking_id']:"";
		$list_invitation = isset($post['list_invitation']) ? $post['list_invitation']:array();
		$list_invitation_fix = array();

		$sqlNotif 			= "SELECT * FROM sending_notif WHERE booking_id='".$booking_id."' ";
		$sqlEmail 			= "SELECT * FROM sending_email WHERE booking_id='".$booking_id."' ";
		$getBooking 		= $this->Model_Api->getDataBookingById($booking_id);

		$getBookingInv 		= $this->Model_Api->getDataBookingInvById($booking_id)['data'];
		$settingGeneral				= $this->Model_Api->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];
		if($getBooking ['error'] != null){
			$response = response("fail", array(), "Data not exist ");
			echo $response;	
			die();
		}
		$dataBooking = $getBooking['data'];
		$room_name = $dataBooking['room_name'];
		foreach ($list_invitation as $k => $liv) {
			$finde = false;
			foreach ($getBookingInv as $k => $gInv) {
				if($gInv['nik'] == $liv){
					$finde = true;
					break;
				}
			}
			if($finde == false){
				array_push($list_invitation_fix, $liv);
			}
		}
		$employee = $this->Model_Api->getDataEmployeeWhereInNik($list_invitation_fix)['data'];
		foreach ($employee as $key => $value) {
			$employee [$key]['pin_room'] = random_string('numeric', 6);
			$qrnvitation = $dataBooking['booking_id']."_".$employee [$key]['pin_room'];
			QRcode::png($qrnvitation,$tmpdir.$qrnvitation.".png");
		}
		$tableNotif			= $this->Model_Api->querySql($sqlNotif)->result_array();
		$tableEmail			= $this->Model_Api->querySql($sqlEmail)->result_array();

		$meeting_date = $dataBooking['date'];
		$explodeS = explode(" ", $dataBooking['start']);
		$explodeE = explode(" ", $dataBooking['end']);
		$meeting_start = $explodeS[1];
		$meeting_end = $explodeE[1];

		$emailBooking = $dataBooking;
		$emailBooking['format_time_start'] = $this->formatTime($meeting_start);
		$emailBooking['format_time_end'] = $this->formatTime($meeting_end);
		$emailBooking['format_date'] = $this->formatDate($meeting_date);
		

		// print_r($list_invitation_fix );
		if(count($tableNotif) > 0){
			$tableNotif			= $this->Model_Api->querySql($sqlNotif)->result_array()[0];
			$wh1 = array(
						 "booking_id"=>$tableNotif['booking_id']
			);
			$tableNotifBatch 	= json_decode($tableNotif['batch'], true);
			$notification_data 	= $employee ;
			foreach ($employee as $key => $value) {
				array_push($tableNotifBatch , $value);
			}
			$tableNotif['batch'] = json_encode($tableNotifBatch);
			//
			// update notif
			$u1 				= $this->Model_Api->updateData("sending_notif", $tableNotif, $wh1);
			// end update notif
			//
			$meeting_title = $dataBooking['title'];
			
			$notification_title = "Invitation Meeting of ".$meeting_title;
			$notification_body = $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end) . " at " .$room_name;
			$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notification_data );
			$type_notif_admin = 12;
			$this->Model_Notif->insertNotifAdminApi($type_notif_admin, "Invitation meeting", $meeting_title, $post['nik'] );

		}
		if(count($tableEmail) > 0){
			$tableEmail			= $this->Model_Api->querySql($sqlEmail)->result_array();
			if(count($tableEmail) > 0)
			{
				$tableEmail= $tableEmail[0];
				$tableEmailBatch 	= json_decode($tableEmail['batch'], true);
				foreach ($employee as $key => $value) {
					array_push($tableEmailBatch['internal'] , $value);
				}
				$meeting_title = $dataBooking['title'];
				$notification_title = "Invitation Meeting of ".$meeting_title;
				$wh2 = array(
							 "booking_id"=>$tableEmail['booking_id']
				);
				foreach ($employee as $key => $people) {
					$pNotif = $this->Model_Notif->sendEmailInternal("invitation", $emailBooking, $people);
				}	
				// update email
				unset($tableEmail['id'] );
				//
				$u2 				= $this->Model_Api->updateData("sending_email", $tableEmail, $wh2);
				// end update email
				//
			}
					
		}
		// print_r($employee 	);
		$response = response("success", array(), "Success get data to invite ");
		echo $response;	
	}


	public function postDeleteAttendMobile()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$datetime = date("Y-m-d H:i:s");

		if(!isset($post['invitation_id']) || !isset($post['booking_id']) || !isset($post['username'])  ){

			$response = response("fail", array(), "Some parameter is gone ");
			echo $response;	
			die();
		}
		$datarow = array(
			"updated_at" => $datetime,
			"updated_by" => $post['username'],
			// "attendance_status" => ($post['attend']-0),
			"is_deleted" => 1,
			// "attendance_reason" => isset($post['reason']) ? $post['reason']:"",
		);
		$whereAr = array(
			"booking_id" => $post['booking_id'],
			"id" => $post['invitation_id'],
		);
		$getData = $this->Model_Api->updateData('booking_invitation',$datarow, $whereAr);
		$response = response("success", array(), "Success get data to delete attend meeting ");
		echo $response;	
	}
	public function getDataRoomMeeting()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date 			= $post['date_meeting'];
		$room_id = $this->uri->segment(7);
		if(!isset($post['booking_id'])){
			$post['booking_id']		= "";

		}
		$booking_id 		= $post['booking_id'];
		$meetingRoom 	= array();
		$timearray 		= array();

		
		
		$sqlroom 		= "SELECT * FROM room  WHERE is_deleted=0 AND radid='".$post['room_id']."' ";
		$queryRoom 		= $this->Model_Api->querySql($sqlroom);
		$dataRoom 		= $queryRoom->result_array();
		$settingGeneral	= $this->Model_Api->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		$numLoop		= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$stTime 		= $date." 00:00:00";
		$timess 		= strtotime($stTime);
		
		for($d=0;$d<$numLoop;$d++){
			if ($d == 0) {
				$time = date("H:i",$timess );
				array_push($timearray, $time);
			}
			$time1 = date("H:i",strtotime('+'.$setDuration.' minutes',$timess));
			if($time1 != "00:00"){
				array_push($timearray, $time1);
			}
			$timess = strtotime($time1);
		} //
		$datenow = date("Y-m-d"); 
		$datetimenow = date("Y-m-d H:i"); 
		$lenTime = count($timearray)-1;
		

		foreach ($dataRoom  as $k => $v) {
			$sql 		= "";
			$room_id 	= $v['radid'];
			$www = "";
			if($booking_id != ""){
				$www = " AND b.booking_id<>'".$booking_id."'  ";
			}
			$sql 		.= "SELECT * FROM ( ";
			foreach ($timearray as $key => $value) {
				$timeData = $date." ".$value .":00";
				if ($lenTime == $key) {
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."' AND b.is_alive = 1 ".$www."
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration MINUTE))";
				}else{
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 ".$www." 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration MINUTE))";
					$sql .= " UNION ";
				}
			}
			$sql 			.= ") room_time";
			$queryTime 		= $this->Model_Api->querySql($sql);
			$dataTimeArray 	= $queryTime->result_array();
			// print_r($datenow . " - " . $date);
			foreach ($dataTimeArray as $key => $value) {
				// $date
				if($value['canceled'] >=1 || $value['expired'] >=1 || $value['endearly'] >=1 ){
					$dataTimeArray[$key]['book'] = 0;
				}
				if($dataTimeArray[$key]['book'] >= 1){
					$dataTimeArray[$key]['book'] = 1;
				}
				if($datenow == $date){
					// today
					$dtf = $date . " ".$value['time_array'];
					$dtime1 = strtotime($datetimenow);
					$dtime2 = strtotime($dtf);
					if($dtime1 > $dtime2){
						$dataTimeArray[$key]['book'] = 1;
					}
				}
				$dataTimeArray[$key]['book'] = $dataTimeArray[$key]['book'] . "";
				unset($dataTimeArray[$key]['canceled']);
				unset($dataTimeArray[$key]['expired']);
				unset($dataTimeArray[$key]['endearly']);
			}
			$dataRoom[$k]['datatime'] = $dataTimeArray;
			$dataRoom[$k]['setting'] = $dataSettingGeneral;
		} // foreach $dataroom

		$response = response("success", $dataRoom, "Success get data to end meeting ");
		echo $response ;	
	}

	public function getParticipant()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$booking_id 			= $post['booking_id'];
		$room_id = $this->uri->segment(7);
		

		$wh = array("bi.booking_id"=>$booking_id ,"internal"=>1,);
		$wheks = array("bi.booking_id"=>$booking_id ,"internal"=>0);
		$wh_await = array("bi.booking_id"=>$booking_id ,"attendance_status"=>0);
		$wh_accept = array("bi.booking_id"=>$booking_id ,"attendance_status"=>1);
		$wh_reject = array("bi.booking_id"=>$booking_id ,"attendance_status"=>2);


		$data_in = $this->Api2->getParticipantData($wh);
		$data_ek = $this->Api2->getParticipantData($wheks);
		$data_accept = $this->Api2->getParticipantData($wh_accept);
		$data_wait = $this->Api2->getParticipantData($wh_await);
		$data_reject = $this->Api2->getParticipantData($wh_reject);

		$num_partisipan = $data_in->num_rows() + $data_ek->num_rows();
		$resData = array(
			"total" => $num_partisipan,
			"internal" => $data_in->result_array(),
			"external" => $data_ek->result_array(),
			"accept" => $data_accept->num_rows(),
			"reject" => $data_reject->num_rows(),
			"wait" => $data_wait->num_rows(),
		);
		$response = response("success", $resData, "Success get data to participant ");
		echo $response ;	
	}
	public function formatDate($string)
	{
		$nM = array('','Jan','Feb','Mar', 'Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$d = explode("-", $string);
		$y = $d[0];
		$m = $d[1]-0;
		$day = $d[2];
		return $day . " ". $nM[$m] . " ".$y;
	}
	public function formatTime($string)
	{

		// echo 
		$nM = array(
			'00'=> '00',
		    '01'=> '01',
		    '02'=> '02',
		    '03'=> '03',
		    '04'=> '04',
		    '05'=> '05',
		    '06'=> '06',
		    '07'=> '07',
		    '08'=> '08',
		    '09'=> '09',
		    '10'=> '10',
		    '11'=> '11',
		    '12'=> '12',
		    '13'=> '13',
		    '14'=> '14',
		    '15'=> '15',
		    '16'=> '16',
		    '17'=> '17',
		    '18'=> '18',
		    '19'=> '19',
		    '20'=> '20',
		    '21'=> '21',
		    '22'=> '22',
		    '23'=> '23',
		    '24'=> '24',
		);
		$d = explode(":", $string);
		// print_r($d);
		$h = $d[0];
		$m = $d[1];
		$s = $d[2];
		// $formatH = ( (($m-0) > 12) ) ? "PM":"AM";
		$formatH = "";
		// echo $m ;
		return $nM[$h] . ":". $m . " ".$formatH;
	}

	public function reportMeeting()
	{
		ob_end_clean();
		$this->load->helper('file');
		require_once APPPATH."third_party/dompdf/autoload.inc.php";
		$bookingId = $this->uri->segment(4);
		$getData = $this->Model_Api->getOneSchedule($bookingId);
		$partisipantInt = $this->Model_Api->getInvitationInternalNik($bookingId)['data'];
		$partisipantExt = $this->Model_Api->getInvitationExternal($bookingId)['data'];

		if($getData ['data'] == null ){
			$response = response("fail", array(), "Booking not exist ");
			echo $response;	
			die();
		}
		$databook = $getData ['data'];

		$string = read_file('./config/report_view.html');


		$string = str_replace("%agenda%",$databook['title'],$string);
		$string = str_replace("%agenda%",$databook['title'],$string);
		$extendTime = $row['extended_duration'] - 0;
		$date = $databook['date'];
		$starttime = date('H:i:s', strtotime($databook['start']));
		$endtime = date('H:i:s', strtotime($databook['end']));
		$endtime        = date('H:i:s', strtotime('+' . $extendTime . ' minutes', strtotime($databook['end'])));
		$datetime = $date . " " . $starttime ." - ". $endtime;
		$string = str_replace("%tanggal%",$datetime,$string);
		$string = str_replace("%tempat%",$databook['room_name'],$string);
		$string = str_replace("%location%",$databook['location'],$string);
		$string = str_replace("%facility%",$databook['facility_room'],$string);

		$parttisipantInternal = "";


		// echo $string;
		// echo "<pre>";
		foreach ($partisipantInt as $k => $vpi) {
			$parttisipantInternal .= '<tr>';
			$parttisipantInternal .= '<td>'.$vpi['name'].'</td>';
			$parttisipantInternal .= '<td>'.$vpi['email'].'</td>';
			$parttisipantInternal .= '<td>'.$vpi['no_phone'].'</td>';
			$parttisipantInternal .= '<td>'.$vpi['no_ext'].'</td>';
			$parttisipantInternal .= '<td>YES</td>';
			$parttisipantInternal .= '</tr>';
		}
		foreach ($partisipantExt as $k => $vpi) {
			$parttisipantInternal .= '<tr>';
			$parttisipantInternal .= '<td>'.$vpi['name'].'</td>';
			$parttisipantInternal .= '<td>'.$vpi['email'].'</td>';
			$parttisipantInternal .= '<td></td>';
			$parttisipantInternal .= '<td></td>';
			$parttisipantInternal .= '<td>NO</td>';
			$parttisipantInternal .= '</tr>';
		}

		$string = str_replace("%partisipant_text%",$parttisipantInternal,$string);
		$string = str_replace("%penyelenggara%",$databook['pic'],$string);
		$string = str_replace("%note%",$databook['note'],$string);
		// echo $string;
		// die();

		$dompdf = new Dompdf();
		$dompdf->loadHtml($string);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		$filename = date('YmdHis')."_".$bookingId."_view_meeting.pdf";
		// Output the generated PDF to Browser
		// $dompdf->stream();
		$dompdf->stream($filename, array("Attachment" => false));
	}


	public function checkRescheduleBookingWithRoom()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$booking_id = isset($post['booking_id']) ? $post['booking_id'] :"" ;
		$selectDate = isset($post['selected_date']) ? $post['selected_date']: "" ;
		$room_id = isset($post['room_id']) ? $post['room_id'] :"" ;
		$timenow = isset($post['time']) ? $post['time'] :"" ;
		$currentdate =  isset($post['date']) ? $post['date'] :"" ;

		if($booking_id == ""){
			echo response("fail", array(), "Data booking not exist");
			die();
		}
		if($selectDate == ""){
			echo response("fail", array(), "Selected date  not exist");
			die();
		}
		if($room_id == ""){
			echo response("fail", array(), "Room not exist");
			die();
		}

		$date 			= $selectDate;
		$meetingRoom 	= array();
		$timearray 		= array();
		$dataBooking 	= $this->Model_Admin->getDataBookingById($booking_id)['data'];
		$dataRoom 		= $this->Model_Admin->getRoomRadid($room_id)['data'];
		$settingGeneral	= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];

		$numLoop		= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$stTime 		= $date." 00:00:00";

		if($dataBooking['title'] == null){
			echo response("fail", array(), "Data booking not exist");
			die();
		}

		// IF MERGE
		if($dataBooking['is_merge'] == true){
			$dataRoom 			= array();
			$dataMergeRoomRaw 	= $dataBooking['merge_room'];
			$dataMergeRoom 		= json_decode($dataMergeRoomRaw,true);
			foreach ($dataMergeRoom  as $key => $value) {
				$temRoom = $this->Model_Admin->getRoomRadid($room_id)['data'];
				if (count($temRoom) > 0) {
					array_push($dataRoom, $temRoom[0]);
				}
			}

		}
		
		$timearray	= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		$timearray = $timearray['data'];
		$lenTime = count($timearray)-1;
		foreach ($dataRoom  as $k => $v) {
			$sql 		= "";
			$room_id 	= $v['radid'];
			$sql 		.= "SELECT * FROM ( ";
			foreach ($timearray as $key => $value) {
				$timeData = $date." ".$value['time'] .":00";
				if ($lenTime == $key) {
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."' AND b.is_alive = 1 AND  b.booking_id<>'".$booking_id."' 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))";
				}else{
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 AND  b.booking_id<>'".$booking_id."' 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))";
					$sql .= " UNION ";
				}
			}
			$sql 	.= ") room_time";
			if($v['type_room'] == "merge"){
				$mergeRoom 		= $this->Model_Admin->getDataMergeRoomBooking($room_id)['data'];
				$dataRoom[$k]['merge_room']  = $mergeRoom;
			}
			$queryTime 		= $this->Model_Api->querySql($sql);
			$dataTimeArray 	= $queryTime->result_array();
			foreach ($dataTimeArray as $key => $value) {
				if($value['canceled'] >=1 || $value['expired'] >=1 || $value['endearly'] >=1 ){
					$dataTimeArray[$key]['book'] = 0;
				}
				if($dataTimeArray[$key]['book'] >= 1){
					$dataTimeArray[$key]['book'] = 1;
				}
				unset($dataTimeArray[$key]['canceled']);
				unset($dataTimeArray[$key]['expired']);
				unset($dataTimeArray[$key]['endearly']);
			}
			$dataRoom[$k]['datatime'] = $dataTimeArray;
			$dataRoom[$k]['setting'] = $dataSettingGeneral;
		} // foreach 
		foreach ($dataRoom  as $k => $v) {
			$d = $dataRoom[$k]['datatime'];
			$work_start = strtotime($date .' '.$v['work_start']);
			$work_end = strtotime($date .' '.$v['work_end']);
			foreach ($d as $key => $value) {
				$date2time = strtotime($date .' '.$value['time_array']);
				if($work_start <= $date2time && $work_end > $date2time ){

				}else{
					$dataRoom[$k]['datatime'][$key]['book'] = "1";
				}

			}

		}
		if($currentdate == $date){
			foreach ($dataRoom  as $k => $v) {
				$d = $dataRoom[$k]['datatime'];
				$work_start = strtotime($date .' '.$v['work_start']);
				$work_end = strtotime($date .' '.$v['work_end']);
				$timenowint = strtotime($date ." " . $timenow);
				foreach ($d as $key => $value) {
					$date2time = strtotime($date .' '.$value['time_array']);
					if($timenowint > $date2time){
						$dataRoom[$k]['datatime'][$key]['book'] = "1";
					}

				}

			}
		}
		
		echo response("success", $dataRoom, "Get success");
	}

	public function getListSearchRoom()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$modules['room_adv'] = $this->Model_Module->get_module_room_adv();
		$modules_room_adv_enabled = $modules['room_adv']['is_enabled'] -0;
		// $date 			= date('Y-m-d');
		if(!isset( $post['nik']) || !isset( $post['username']) || !isset( $post['date'])|| !isset( $post['timezone'])){
			echo response("fail", [], "Search parameter not exist");
			die();
		}

		$timezone = APP_GMT;
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set(APP_GMT);
		}
		$nik 			= $post['nik'];
		$date 			= $post['date'];
		$sst 			= $post['time'];
		$time1 			= $post['time'];
		$time2 			= $post['time'];
		$meetingRoom 	= array();
		$timearray 		= array();
		$whreRoomString = " 1=1 ";
		$capacity_search = 0;
		if(isset($post['search'])){
			if(isset($post['search']['date_search'])){
				if($post['search']['date_search'] != ""){
					$date = $post['search']['date_search'];
				}
			}
			if(isset($post['search']['facility_search'])){
				if(count($post['search']['facility_search']) > 0){
					$listfacility = $post['search']['facility_search'];
					$lastindex = count($post['search']['facility_search'])-1;
					$whreRoomString .= " AND ( ";
					foreach ($listfacility as $key => $vfacility) {
						if($key == $lastindex){
							$whreRoomString .= " r.facility_room LIKE '%".$vfacility."%' ";
						}else{
							$whreRoomString .= " r.facility_room LIKE '%".$vfacility."%' OR ";
						}
					}
					$whreRoomString .= " ) ";
				}

			}
			if(isset($post['search']['room_search'])){
				if($post['search']['room_search'] != ""){
					$whreRoomString .= " AND r.radid=".$post['search']['room_search']." ";
				}
			}
			if(isset($post['search']['timestart_search'])){
				if($post['search']['timestart_search'] != ""){
					$time1 = $post['search']['timestart_search'];
				}
			}
			if(isset($post['search']['timeend_search'])){
				if($post['search']['timeend_search'] != ""){


					$time2 = $post['search']['timeend_search'];
				}
			}
			if(isset($post['search']['capacity_search'])){
				$filterCapacity = $post['search']['capacity_search'];
				if( ($filterCapacity - 0) >0 ){
					$capacity_search = $filterCapacity - 0;
					$whreRoomString .= " AND r.capacity>=".$filterCapacity . "";
				}
			}
			if(isset($post['search']['building_search'])){
				if(count($post['search']['building_search']) > 0){
					$listbuilding = $post['search']['building_search'];
					$lastindex = count($post['search']['building_search'])-1;
					$whreRoomString .= " AND ( ";
					foreach ($listbuilding as $key => $vbuilding) {
						if($key == $lastindex){
							$whreRoomString .= " r.building_id LIKE '%".$vbuilding."%' ";
						}else{
							$whreRoomString .= " r.building_id LIKE '%".$vbuilding."%' OR ";
						}
					}
					$whreRoomString .= " ) ";
				}
			}
		}
		$date_complete = $date ." ".$time1 ;
		$start_complete = $date ." ".$time1 ;
		$end_complete = $date ." ".$time2 ;
		$server_date = new DateTime($date_complete, new DateTimeZone($timezone));
		$server_start = new DateTime($start_complete, new DateTimeZone($timezone));
		$server_end = new DateTime($end_complete, new DateTimeZone($timezone));


		$date = $server_date->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d');
		$time1 = $server_date->setTimezone(new DateTimeZone(APP_GMT))->format('H:i');
		$time2 = $server_date->setTimezone(new DateTimeZone(APP_GMT))->format('H:i');

		$dayNameNum = date("w", strtotime($date));
		$dayName = getDayName($dayNameNum);
		$whreRoomString .= "  AND r.work_day LIKE '%".$dayName."%' ";
		// $whreRoomString .= "  AND r.radid LIKE '%4718532960%' ";
		
		$dataRoom 		= $this->Model_Admin->getDataRoom($whreRoomString)['data'];
		$settingGeneral	= $this->Model_Api->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$timearray	= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		$timearray = $timearray['data'];
		$lenTime = count($timearray)-1;
		$collectcheck = array();
		// room time booked check
		$colRoomCheckedIfBooked = [];
		$colPermissionRoomNotContainsUser = [];
		$colRoomSearchCategory = [];

		foreach ($dataRoom  as $k => $v) {
			$room_id 	= $v['radid'];
			$room_id2 	= $v['radid'];
			$dataRoom[$k]['valid'] = true;
			$room_is_config_enable 	= isset($v['is_config_setting_enable']) ? $v['is_config_setting_enable']-0:0 ;
			$room_is_enable_permission 	= isset($v['is_enable_permission']) ? $v['is_enable_permission']-0:0 ;
			$timeroom = $date ." ".$v['work_end'];
			$timeroom_wkstart = $date ." ".$v['work_start'];
			$checknow1 	= $date ." ".$time1 ;
			$checknow2 	= $date ." ".$time2 ;
			$convertTime1 = strtotime($checknow1);
			$convertTime2 = strtotime($checknow2);
			// if()
			if($convertTime1 < strtotime($timeroom_wkstart) ){
				// array_push($collectcheck, $k);
				$dataRoom[$k]['valid'] = false;
			}else if($convertTime2 > strtotime($timeroom)  ){
				// array_push($collectcheck, $k);
				$dataRoom[$k]['valid'] = false;
			}
			// 
			if($dataRoom[$k]['valid'] == false)continue;
			if($modules_room_adv_enabled == 1){
				$config_permission_user = $v['config_permission_user'];
				if($room_is_config_enable == 1 && $room_is_enable_permission == 1){
					if (strpos($config_permission_user, $nik) !== false) {
					    
					}else{
						$dataRoom[$k]['valid'] == false;
						continue;
					}
				}
			}
			// 
			if($dataRoom[$k]['valid'] == false)continue;

			$sql 		= "";
			$sql 		.= "SELECT * FROM ( ";
			foreach ($timearray as $key => $value) {
				$timeData = $date." ".$value['time'] .":00";
				if ($lenTime == $key) {
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))";
				}else{
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))";
					$sql .= " UNION ";
				}
			}
			$sql 			.= ") room_time";
			if($v['type_room'] == "merge"){
				$mergeRoom 		= $this->Model_Admin->getDataMergeRoomBooking($room_id)['data'];
				// $dataRoom[$k]['merge_room']  = $mergeRoom;
			}
			$queryTime 		= $this->Model_Api->querySql($sql);
			$dataTimeArray 	= $queryTime->result_array();
			foreach ($dataTimeArray as $key => $value) {
				if($value['canceled'] >=1 || $value['expired'] >=1 || $value['endearly'] >=1 ){
					$dataTimeArray[$key]['book'] = 0;
				}
				if($dataTimeArray[$key]['book'] >= 1){
					$dataTimeArray[$key]['book'] = 1;
				}
				unset($dataTimeArray[$key]['canceled']);
				unset($dataTimeArray[$key]['expired']);
				unset($dataTimeArray[$key]['endearly']);
			}

			foreach ($dataTimeArray as $key => $value) {
				$timesearch1 = strtotime($date . " ".$time1);
				$timesearch2 = strtotime($date . " ".$time2);
				$bookingtime = strtotime($date . " " . $value['time_array']);
				if( $timesearch1  < $bookingtime && $timesearch2  > $bookingtime ){
					if($value['book'] == 1 && $value['book'] == "1"){
						$dataRoom[$k]['valid'] = false;

					}
					// die();
				}
				$dataTimeArray[$key]['test'] = ($timesearch1  <= $bookingtime);
				$dataTimeArray[$key]['test2'] =($timesearch2 >= $bookingtime) ;
			}
			$dataRoom[$k]['datatime'] = $dataTimeArray;
			$queryCat 		= $this->Model_Api->querySql("SELECT rd.*, ru.name FROM room_for_usage_detail rd INNER JOIN room_for_usage ru ON rd.room_usage_id=ru.id WHERE rd.room_id='".$room_id2."' ORDER BY ru.id ASC ");
			$dataRoom[$k]['category'] = $queryCat->result_array();
			$config_room_for_usage = $v['config_room_for_usage'];
			$sp_crfu =	explode(",", $config_room_for_usage);

			
			if(isset($post['search'])){
				if(isset($post['search']['category_search'])){
					$filterCategory = $post['search']['category_search'];
					foreach ($dataRoom[$k]['category'] as $kc => $vc) {
						if(($vc['room_usage_id'] - 0) == ($filterCategory-0)){
							$finde_crfu = true;
							if($capacity_search == 0){
								// $finde_crfu = true;
							}else if( $capacity_search >= ($vc['min_cap']-0) ){
								// $finde_crfu = true;
							}	else{
								$dataRoom[$k]['valid'] = false;
								// $finde_crfu = false;
							}
						}
					}
				}
			}

		} // foreach $dataroom
		$g = $dataRoom;
		$dataRoom = [];
		// die();	
		foreach ($g as $key => $v) {
			if($v['valid'] == true){
				array_push($dataRoom, $v);
			}
		}
		echo response("success", $dataRoom, "Get success");
		die();
	}
	
	public function getBookingById()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		if(!isset($post['booking_id'])){
			echo response("fail", [], "Get fail");
			die();
		}
		$date 			= $post['date'];
		$time 			= $post['time'];
		$bookingId 			= $post['booking_id'];
		$getBooking 		= $this->Model_Api->getDataBookingById($bookingId); //
		if($getBooking ['error'] != null){
			$response = response("fail", array(), "Data not exist ");
			echo $response;	
			die();
		}
		$dataBooking = $getBooking['data'];

		$wh = array("bi.booking_id"=>$bookingId ,"internal"=>1,);
		$wheks = array("bi.booking_id"=>$bookingId ,"internal"=>0);
		$whpic = array("bi.booking_id"=>$bookingId ,"is_pic"=>1);
		// $whall = array("bi.booking_id"=>$bookingId ,"is_pic"=>1);


		$data_in = $this->Api2->getParticipantData($wh);
		$data_ek = $this->Api2->getParticipantData($wheks);
		$data_pic = $this->Api2->getParticipantData($whpic);

		$num_partisipan = $data_in->num_rows() + $data_ek->num_rows();
		$attendees = array(
			"internal" => $data_in->result_array(),
			"external" => $data_ek->result_array(),
			"pic" => $data_pic->row_array(),
		);
		$res  = [
			'booking' => $dataBooking,
			'attendees' => $attendees,
		];
		echo response("success", $res, "Get success");
		die();

	}
	
	
}

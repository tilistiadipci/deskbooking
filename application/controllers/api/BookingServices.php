<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class BookingServices extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Api');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Api2');
		$this->load->model('Model_Notif');
		$this->load->model('Model_MeetingLimitation');
		$this->load->model('Model_License');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
	}
	// CRUD
	public function postCreateBookingBy365()
	{
		include APPPATH.'third_party/phpqrcode/qrlib.php';
		$modules['pantry'] = $this->Model_Module->get_module_pantry();
		$modules['loker'] = $this->Model_Module->get_module_loker();
		$modules['price'] = $this->Model_Module->get_module_price();
		$modules['invoice'] = $this->Model_Module->get_module_invoice();
		$modules['email'] = $this->Model_Module->get_module_email();
		$tmpdir = "assets/qr/";
		$json = file_get_contents("php://input");
		// file_put_contents("response.txt",$json);
		$post = json_decode($json, TRUE);
		// print_r($post['databook']);
		// die();
		// TIMEZONE
		// TIMEZONE
		// TIMEZONE
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set(APP_GMT);
		}
		$uniqcode 	= "M365-"; 
		$datetime 	= date('Y-m-d H:i:s');
		$randoom_id	= random_string('numeric', 10);
		$id 		= $uniqcode.$randoom_id."";
		$invoice_id = $uniqcode.$randoom_id."";

		$databook 	= $post['databook'];
		$internal 	= $databook['internal_data'];
		$eksternal 	= $databook['external_data'];
		$alocation 	= $databook['alocation'];

		$startevent	= $databook['startevent'];
		$endevent	= $databook['endevent'];
		// MEETING LIMITATION
		$this->Model_MeetingLimitation->checkMeeting($startevent, $endevent);
		// 
		$settingGeneral				= $this->Model_Api->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];

		$pantry_package				= empty($databook['pantry_package']) == false ? $databook['pantry_package'] : "" ;
		$pantry_detail				= empty($databook['pantry_detail']) == false ? $databook['pantry_detail'] : array() ;
		$set_pantry_config          = $this->Model_Admin->select_all_data('setting_pantry_config', array(), array(), 'row');
		$pantry_expired 			= $set_pantry_config['pantry_expired']; 
		$pantry_max_order_qty 		= $set_pantry_config['max_order_qty']; 
		$pantry_before_order_meeting= $set_pantry_config['before_order_meeting']; 
		$set_pantry 				= array();
		$collected_pantry_detail 	= array();
		// 
		$isMerge 	= isset($databook['is_merge']) ? $databook['is_merge'] : 0;
		if($isMerge == "false" ){
			$isMerge = 0;
		}else if($isMerge == "true"){
			$isMerge = 1;
		}
		$mergeRoom 			= isset($databook['merge_room']) ? $databook['merge_room'] : array();
		$mergeRoomRadid 	= array();

		foreach($mergeRoom  as $mk => $mv){
			array_push($mergeRoomRadid, $mv['radid']);
		}
		$mergeRoom 			= $mergeRoomRadid;

		$dataMergeRoom 	=  array();
		$dataMergeRoomWidthJson =  "[]";
		$room1 						= $databook['room'];
		$room_id 					= $room1['radid'];
		$Q_room						= "SELECT * FROM room WHERE radid=".$room_id." ";
		$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		if(count($room) <= 0){
			$response = response("fail", array(), "Room not found ");
			echo $response;
			die();
		}
		$room 			 			= $room[0];
		$room_name 					= $room['name'];
		$merge_room_name 			= $room1['name'];
		$merge_room_id 				= $room1['radid'];
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

		$fHour 						= $dataSettingGeneral['duration'];
		$time1 						= new DateTime($startevent);
		$time2 						= new DateTime($endevent);
		$reservation_cost 			= 0;
		$formatInvoice 				= "";
		
		$timediff 					=	 $time2->diff($time1);
		$duration_hours 			= $timediff->h*60;
		$duration_minute			= $timediff->i;
		$duration 					= $duration_hours+$duration_minute;
		
		$getHoursMeeting 			= floor($duration / $fHour);
		$checkHours 				= fmod($duration,$fHour);
		if($checkHours > 0){
			$getHoursMeeting += 1;
		}
		if($modules['price']['is_enabled'] == 1){
			$cost						= $room['price'] - 0;  // per hours
			$getHoursMeeting 			= floor($duration / $fHour);
			$checkHours 				= fmod($duration,$fHour);
			if($checkHours > 0){
				$getHoursMeeting += 1;
			}
			$reservation_cost 			= $cost * $getHoursMeeting;
		}
		$emailBook						= $post['emailBook'];
		$resPIC 					= $this->Model_Api2->getNikEmployeeByEmail($emailBook);
		$getDataPIC 				= $resPIC['data'];
		if(!isset($getDataPIC['nik'])){
			$response = response("fail", array(), "PIC/Host/Organizer not found or unregistered in system, please register. ");
				echo $response;
			die();
		}
		$nikPic						= $getDataPIC ['nik'];
		$internalBatch 				= array();
		$eksternalBatch 			= array();
		$dataEmailInternal 			= $rowInternal['data'];
		$dataEmailEksternal 		= array();
		$dataEmailInternal_array	= array();
		$error_pantry = false;
		// MODULE PANTRY
		if($set_pantry_config['status'] == 1 && $modules['pantry']['is_enabled'] == 1 ){
			foreach ($pantry_detail as $key => $value) {
				if($pantry_max_order_qty < $value['qty']){
					$error_pantry = true;
					break;
				}
			}
			if($pantry_package != ""){
				$tanggaltime_order_pantry = $databook['date'] ." ". $databook['startStr'];
				$b_time = "-".$pantry_before_order_meeting;
				$tanggaltime_order_pantry_before = date('Y-m-d H:i:s', strtotime($b_time .'minutes', strtotime($tanggaltime_order_pantry)));
				$tanggal_order_pantry = $databook['date'] ;
				$data_pantry = $this->Model_Admin->getDataPantryPackage($pantry_package)['data'][0];
				$pantry_trs_status = $this->Model_Admin->select_all_data('pantry_transaksi_status', array('id'=>0), array(), 'row');
				$sql_pantry = "SELECT COALESCE(max(order_no), '') as order_no from pantry_transaksi
							WHERE DATE(order_datetime) = '".$tanggal_order_pantry."'   AND pantry_id=".$data_pantry['pantry_id'] ." "  ;
				$idtrspantry = "METTING-".date('YmdHis').random_string('numeric', 3);
				$row_order_pantry 	= $this->Model_Admin->querySql($sql_pantry)->row_array();
				if($row_order_pantry['order_no'] == "" || $row_order_pantry['order_no'] == null){
					$no_order_pantry = sprintf("%04d", "1");
				}else{
					$old_no_order_pantry = $row_order_pantry['order_no']-0;
					$no_sort_order_pantry = $old_no_order_pantry + 1;
					$no_order_pantry = sprintf("%04d", $no_sort_order_pantry);

				}
				$p_datetime = date('Y-m-d H:i:s');
				$set_pantry = array(
					'id' => $idtrspantry,
					'pantry_id' => $data_pantry['pantry_id'],
					'order_no' => $no_order_pantry,
					'employee_id' => $nikPic,
					'booking_id' => $id,// booking ID
					'via' => "booking",
					'datetime' => $p_datetime ,
					'order_datetime' => $tanggaltime_order_pantry ,
					'order_datetime_before' => $tanggaltime_order_pantry_before ,
					'order_st' => 0,
					'order_st_name' => $pantry_trs_status['name'],
					'process' => 0 ,
					'complete' => 0 ,
					'failed' => 0 ,
					'done' => 0 ,
					'note' =>'',
					'created_at' => $p_datetime,
					'is_deleted' => 0,
				);
				foreach ($pantry_detail as $key => $value) {
					$d_trs_pantry = array(
						'transaksi_id' => $idtrspantry,
						'menu_id' => $value['id'],
						'qty' => $value['qty']-0,
						'note_order' => $value['note'],
						'note_reject' => "",
						'is_rejected' => 0,
						'is_deleted' => 0,
						'status' => $value['status'],
					);
					array_push($collected_pantry_detail , $d_trs_pantry);
				}
				// print_r($set_pantry);
			}
			if ($error_pantry) {
				$response = response("fail", array(), "Orders per item exceed. Maximum quantity of ".$pantry_max_order_qty);
				echo $response;
				die();
			}

			// MODULE PANTRY
			if($set_pantry_config['status'] == 1){
				if($pantry_package != ""){
					if(count($pantry_detail) > 0){
						$resp2 		= $this->Model_Admin->insertData('pantry_transaksi', $set_pantry);
						$resp2 		= $this->Model_Admin->insertDataBatch('pantry_transaksi_d', $collected_pantry_detail);
					}
				}
			}

		}
		
		$tanggal_meeting 	= $databook['date'];
		$waktu_timestart 	= date('H:i:s', strtotime($startevent));
		$waktu_timeend	 	= date('H:i:s', strtotime($endevent));
		$waktu_mulai 		= $startevent;
		$waktu_end 			= $endevent;
		$waktu_akhir 		= $waktu_end ;
		$ruangan 			= $room['radid'];

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
		if ($modules['invoice']['is_enabled'] == 1 && $modules['price']['is_enabled'] == 1) {
			$years 			= date('Y', strtotime($databook['date'])); // get tahun from date
			$y_years 		= date('y', strtotime($databook['date'])); // get tahun from date
			$months			= date('m', strtotime($databook['date'])); // get tahun from date
			$days 			= date('d', strtotime($databook['date'])); // get tahun from date
			$sql_invoice 	= "SELECT COALESCE(max(no_order), '') as no_order from booking
								WHERE YEAR(date) = '".$years."'";
			$resInvoice 	= $this->Model_Api->querySql($sql_invoice);
			$rowInvoice 	= $resInvoice->row_array();
			$invAlocationID = $alocation ['alocation_id'] . "-E-Meeting";
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
		}
		
		// CREATE BOOKING ROW
		$data 						= array();  // DATA BOOKING
		$data['booking_id'] 		= $id;
		$data['booking_id_365'] 	= $databook['booking_id_365'];
		$data['booking_devices']	= "365";
		$data['no_order'] 			= $formatInvoice;
		$data['title'] 				= $databook['title'];
		$data['room_id'] 			= $room['radid'];
		$data['date'] 				= $databook['date'];
		$data['start'] 				= $startevent;
		$data['end'] 				= $endevent;
		$data['total_duration'] 	= $duration;
		$data['duration_per_meeting'] = $fHour ;
		$data['cost_total_booking'] = $reservation_cost ;
		$data['alocation_id'] 		= $alocation ['alocation_id'];
		$data['alocation_name'] 	= $alocation ['name'];
		$data['pic'] 				= $getDataPIC['name'];
		$data['is_alive'] 			= 1;
		$data['is_meal'] 			= 0;
		$data['is_deleted'] 		= 0;
		$data['is_rescheduled'] 	= 0;
		$data['is_canceled']		= 0;
		$data['is_expired']			= 0;
		$data['is_device'] 			= 1;
		$data['created_at'] 		= $datetime;
		$data['created_by'] 		= $nikPic; // mobile created
		$data['external_link'] 		= isset($databook['link']) ? $databook['link']: "";
		$data['external_link_365'] 		= isset($databook['link']) ? $databook['link']: "";
		$data['note'] 				= isset($databook['note']) ? $databook['note'] : "";
		$data['room_name'] 			= $room_name;
		$data['is_merge'] 			= $isMerge;
		$data['merge_room_name'] 	= $merge_room_name;
		$data['merge_room_id'] 		= $merge_room_id;
		$data['merge_room'] 		= $dataMergeRoomWidthJson;

		// // // $invitation_pic 
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
		$nn0 = 0;
		foreach ($dataEmailInternal as $val) {
			$num_str 						= random_string('numeric', 6);
			$ibatch 						= array();
			$ibatch['booking_id'] 			= $id;
			$ibatch['nik'] 					= $val['nik']; // employee id
			$ibatch['name'] 				= $val['name'];
			$ibatch['internal'] 			= 1;
			$ibatch['attendance_status']	= 0;
			$ibatch['email'] 				= $val['email'];
			$ibatch['is_pic'] 				= 0;
			$ibatch['company'] 				= "";
			$ibatch['pin_room'] 			= $num_str;
			$ibatch['created_at'] 			= $datetime;
			$ibatch['created_by'] 			= $nikPic;
			$ibatch['is_deleted'] 			= 0;
			$qrnvitation = $id."_".$num_str;
			QRcode::png($qrnvitation,$tmpdir.$qrnvitation.".png");
			array_push($internalBatch, $ibatch);
			$dataEmailInternal[$nn0]['pin_room'] = $num_str;
			$nn0 ++;
		}
		// insert of PIC invitation
		$ipicemail['nik'] 					= $getDataPIC['nik']; // employee id
		$ipicemail['name'] 					= $getDataPIC['name'];
		$ipicemail['division_id']			= 0;
		$ipicemail['is_pic'] 				= 1;
		$ipicemail['email'] 				= $getDataPIC['email']; 
		$ipicemail['pin_room'] 				= $invitation_pic['pin_room'];
		array_push($dataEmailInternal, $ipicemail);
		// External invitation
		foreach ($eksternal as $val) {
			$num_str 						= random_string('numeric', 6);
			$ibatch = array();
			$ibatch['email']				= $val['email'];
			$ibatch['company']				= $val['company'];
			$ibatch['name'] 				= $val['name'];
			$ibatch['is_pic'] 				= 0;
			$ibatch['booking_id'] 			= $id;
			$ibatch['pin_room'] 			= $num_str 	;
			array_push($dataEmailEksternal, $ibatch);
			$ibatch['internal'] 			= 0;
			$ibatch['attendance_status'] 	= 0;
			$ibatch['created_at'] 			= $datetime;
			$ibatch['created_by'] 			= $nikPic;
			$ibatch['is_deleted'] 			= 0;
			array_push($eksternalBatch, $ibatch);
			$qrnvitation = $id."_".$num_str;
			QRcode::png($qrnvitation,$tmpdir.$qrnvitation.".png");
		}


		$dataToSend['internal'] 			= $dataEmailInternal;
		$dataToSend['eksternal'] 			= $dataEmailEksternal;
		$batchSendingEmail 					= json_encode($dataToSend);
		$batchSendingNotif 					= json_encode($dataToSend['internal']);

		$sending_email 		= array(
			"batch" 		=> $batchSendingEmail,
			"type" 			=> 1,
			"booking_id" 	=> $id,
			"pending" 		=> 0,
			"is_status" 	=> 0, // direct email php
			// "is_status" 	=> 1,
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
		$invStatus 				= "";
		$invName 				= "";
		// MODULE PRICE & INVOICE
		if ($modules['invoice']['is_enabled'] == 1 && $modules['price']['is_enabled'] == 1) {
			$data_invoice = array(
				"invoice_no" 	=> $invoice_id,
				"invoice_format"=> $formatInvoice2,
				"booking_id" 	=> $id, // bookingid
				"rent_cost" 	=> $reservation_cost,
				"alocation" 	=> $alocation ['alocation_id'],
				"time_before" 	=> $datetime,
				"created_at" 	=> $datetime,
				"created_by" 	=> $nikPic,
				"invoice_status" 	=> 0, // before send
			);
			$resp3 = $this->Model_Admin->insertData('booking_invoice', $data_invoice);
		}
		$respP 			= $this->Model_Api->insertData('booking_invitation', $invitation_pic);
		if(count($internalBatch) > 0){
			$resp1		= $this->Model_Api->insertDataBatch('booking_invitation', $internalBatch);
		}
		if(count($eksternalBatch) > 0){
			$resp2 		= $this->Model_Api->insertDataBatch('booking_invitation', $eksternalBatch);
		}

		
		// INSERT BOOKING
		if($isMerge == false){
			$resp3 = $this->Model_Admin->insertData('booking', $data);
		}else{
			foreach ($dataMergeRoom as $SP => $SPVAL) {
				$data['room_id'] = $SPVAL['radid'] ;
				$resp3 = $this->Model_Admin->insertData('booking', $data);
			}

		}
		// $resp3 = $this->Model_Admin->insertData('booking_invoice', $data_invoice);
		$respw = $this->Model_Api->insertData('sending_email', $sending_email);
		$resp4 = $this->Model_Api->insertData('sending_notif', $sending_notif);
		$notifcollectdata = array();
		foreach ($dataEmailInternal as $val) {
			$_notif 					= array();
			$_notif['datetime'] 		=  $datetime;
			$_notif['nik'] 				= $val['nik']; // user id
			$_notif['type'] 			= 1; // booking is 1
			$_notif['value'] 			= $id; // booking id
			$_notif['title'] 			= "Invitation meeting";
			$_notif['body'] 			= $databook['title'] ." - ". getformatDate($databook['date']);
			$_notif['is_sending'] 		= 0;
			$_notif['is_deleted'] 		= 0;
			$_notif['created_at'] 		= $datetime;
			array_push($notifcollectdata, $_notif);
		}
		$_notif = array();
		$_notif['datetime'] 		= $datetime;
		$_notif['nik'] 				= $nikPic; // user id
		$_notif['type'] 			= 1; // booking is 1
		$_notif['value'] 			= $id; // booking id
		$_notif['title'] 			= "Create a meeting schedule";
		$_notif['body'] 			= $databook['title'] ." - ". getformatDate($databook['date']);
		$_notif['is_sending'] 		= 0;
		$_notif['is_deleted'] 		= 0;
		$_notif['created_at'] 		= $datetime;
		$type_notif 				= 1; // notification_type 1=booking
		$notif_insert				= false; // notification_type 1=booking
		array_push($notifcollectdata, $_notif);
		$this->Model_Notif->insertNotifAdmin(12, "Create meeting", $data['title']);
		$this->Model_Notif->insertNotifBatch($notifcollectdata);
		

		$meeting_title 		= $databook['title'];
		$meeting_date 		= $databook['date'];
		$meeting_start 		= date('H:i:s', strtotime($startevent));
		$meeting_end 		= date('H:i:s', strtotime($endevent));
		$notification_title = "Invitation Meeting of ".$meeting_title;
		$notification_body 	= $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end). " at " .$room_name;
		$pNotif 			= $this->Model_Notif->pushNotification($notification_title,$notification_body,$dataToSend['internal'] ,$type_notif,$notif_insert );

		$type_notif_admin = 12; // booking
		$this->Model_Notif->insertNotifAdminApi($type_notif_admin, "Create meeting", $meeting_title, $ipicemail['nik'] );
		// 2023-02-10  SEND EMAIL TO AUDIENCE
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

		// MODULE EMAIL
		if($modules['email']['is_enabled'] == 1 ){
			foreach ($dataToSend['internal'] as $key => $people) {
				$pNotif = $this->Model_Notif->sendEmailInternal("invitation", $emailBooking, $people);
			}	
			foreach ($dataToSend['eksternal'] as $key => $people) {
				$pNotif = $this->Model_Notif->sendEmailExternal("invitation", $emailBooking, $people);
			}	
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
		$response = response("success", array(), "Success create a booking ".$databook['title']);
		echo $response;
	}
	// postCreateBookingBy365
	public function postCancelBookingBy365()
	{
		$modules['pantry'] = $this->Model_Module->get_module_pantry();
		$modules['loker'] = $this->Model_Module->get_module_loker();
		$modules['price'] = $this->Model_Module->get_module_price();
		$modules['invoice'] = $this->Model_Module->get_module_invoice();
		$modules['email'] = $this->Model_Module->get_module_email();
		// $post =$_POST;
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// TIMEZONE
		// TIMEZONE
		// TIMEZONE
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set(APP_GMT);
		}

		$getBooking 		= $this->Model_Api->getDataBookingById($post['booking_id']);
		$sqlNotif 			= "SELECT * FROM sending_notif WHERE booking_id='".$post['booking_id']."' ";
		$sqlEmail 			= "SELECT * FROM sending_email WHERE booking_id='".$post['booking_id']."' ";
		$booking_id 		= $post['booking_id'];
		$noteReason = isset($post['note']) ? $post['note'] : "";
		$dataBooking = $getBooking['data'];
		// 
		// MODULE PANTRY
		if($modules['pantry']['is_enabled'] == 1 ){
			$set_pantry_config          = $this->Model_Admin->select_all_data('setting_pantry_config', array(), array(), 'row');
			$pantry_expired 			= $set_pantry_config['pantry_expired']; 
			$pantry_max_order_qty 		= $set_pantry_config['max_order_qty']; 
			$pantry_before_order_meeting= $set_pantry_config['before_order_meeting']; 
			$set_pantry 				= array();
			$where_pantry 				= array();
			$pantry_order 				= $this->Model_Admin->select_all_data('pantry_transaksi', 
											array('booking_id' =>$dataBooking ['booking_id'], 'via'=>'booking', 'order_st'=>0 ), 
											array(), 'result');
			if(count($pantry_order) > 0){
				$updateOrderTime = array(); // collected data pantry
				$row_pantry_order = $pantry_order[0];
				$s_datetime = $datetime ;
				$pantry_trs_status = $this->Model_Admin->select_all_data('pantry_transaksi_status', array('id'=>4), array(), 'row');
				$updateOrderTime['via'] = 'booking';
				$updateOrderTime['order_st'] = 4;
				$updateOrderTime['order_st_name'] = $pantry_trs_status['name'];
				$updateOrderTime['is_canceled'] = 1;
				$updateOrderTime['updated_at'] = $s_datetime;
				$updateOrderTime['canceled_at'] = $s_datetime;
				$updateOrderTime['canceled_by'] = $this->session->userdata('user-nya');
				$set_pantry = $updateOrderTime;
				$where_pantry['id'] = $row_pantry_order['id'];

			}
			if(count($pantry_order) > 0){
				$pdata 				= $this->Model_Admin->updateData("pantry_transaksi", $set_pantry, $where_pantry);
			}
		}

		if($getBooking ['error'] == null){
			$datetime 					= date('Y-m-d H:i:s');
			
			$dataBooking['is_expired'] = 0;
			$dataBooking['is_rescheduled'] = 0;
			$dataBooking['is_canceled'] = 1;
			
			$dataBooking['is_alive'] = 2;
			$dataBooking['canceled_by'] = $post['nik'];
			$dataBooking['canceled_at'] = $datetime;

			$dataBooking['updated_at'] = $datetime;
			$dataBooking['updated_by'] = $post['nik'];
			$where = array(
				"booking_id"=>$dataBooking['booking_id']
			);

			$room_name = $dataBooking['room_name'];
			$dataBooking['canceled_note'] = $noteReason;
			// MICROSOFT 365
			// MICROSOFT 365
			// MICROSOFT 365
			$module_int_365 = $this->Model_Module->get_module_int_365();
			$module_int_google = $this->Model_Module->get_module_int_google();
			if($module_int_365['is_enabled'] == 1 ){
				$ms365 = $this->Model_License->get365Integration();
				$ck365 = $this->Model_License->check365Data();
				// print_r($dataInvitation);
				if($ck365 == true){
					$dataInvitation222 = [];
					// $res_365 = $this->Model_License->cancelEvent365($dataBooking,$ms365, $dataInvitation222,$dataInvitation222);
					// print_r($res_365 );
					// $jres_365 = json_decode($res_365, TRUE);
					// print_r($jres_365);
					// if(!isset($jres_365['error'])){
					// 	// $data['booking_id_365'] = $jres_365 ['id'];
					// }else{
					// 	// $data['booking_id_365'] = "";
					// }
					// print_r($jres_365);
				}
			}
			if($module_int_google['is_enabled'] == 1 ){

			}
			unset($dataBooking['room_name']);
			unset($dataBooking['price']);
			unset($dataBooking['booking_id']);
			unset($dataBooking['room_description']);
			unset($dataBooking['room_location']);
			unset($dataBooking['room_capacity']);
			unset($dataBooking['room_google_map']);
			unset($dataBooking['building_name']);
			unset($dataBooking['room_google_map']);
			unset($dataBooking['building_detail_address']);
			unset($dataBooking['building_google_map']);

			$meeting_title = $getBooking['data']['title'];
			$meeting_date = $getBooking['data']['date'];
			$explodeS = explode(" ", $getBooking['data']['start']);
			$explodeE = explode(" ", $getBooking['data']['end']);
			$meeting_start = $explodeS[1];
			$meeting_end = $explodeE[1];
			$notification_title = "Cancel Meeting of ".$meeting_title;
			$udata 				= $this->Model_Api->updateData("booking", $dataBooking, $where);
			// die();
			try{
				$tableNotif			= $this->Model_Api->querySql($sqlNotif)->result_array();
				$tableEmail			= $this->Model_Api->querySql($sqlEmail)->result_array();
				if(count($tableNotif) > 0){
					$tableNotif			= $this->Model_Api->querySql($sqlNotif)->result_array()[0];
					$wh1 = array(
						 "booking_id"=>$tableNotif['booking_id']
					);
					$tableNotif['type']	= 3;
					$tableNotif['is_status']	= 3;
					$tableNotif['is_deleted']	= 0;
					unset($tableNotif['id']);
					$u1 			= $this->Model_Api->updateData("sending_notif", $tableNotif, $wh1);
					$notification_data 	= json_decode($tableNotif['batch'], true);
					
					$notification_body = $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end) . " at " .$room_name;
					$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notification_data );
					$type_notif_admin = 12; // booking
					$this->Model_Notif->insertNotifAdminApi($type_notif_admin, "Cancel meeting", $meeting_title, $post['nik'] );
				} // 
				// 
				if(count($tableEmail) > 0){
	                $tableEmail 	= $tableEmail[0];
					$getBookingEmail        = $this->Model_Api->getDataBookingById($booking_id); //
	                if($getBookingEmail ['error'] != null){
	                    $response = response("fail", array(), "Data not exist ");
	                    echo $response; 
	                    die();
	                }
	                $batchemail     = $tableEmail['batch'];
	                $dataToSend     = json_decode($batchemail,true);
	                // inisil booking 
	                $emailBooking = $getBookingEmail['data'];
	                $emailBooking['format_time_start'] = $this->formatTime($meeting_start);
	                $emailBooking['format_time_end'] = $this->formatTime($meeting_end);
	                $emailBooking['format_date'] = $this->formatDate($meeting_date);
	                if($modules['email']['is_enabled'] == 1 ){
	                	foreach ($dataToSend['internal'] as $key => $people) {
		                    $pNotif = $this->Model_Notif->sendEmailInternal("cancel", $emailBooking, $people);
		                }
		                foreach ($dataToSend['eksternal'] as $key => $people) {
		                    $pNotif = $this->Model_Notif->sendEmailExternal("cancel", $emailBooking, $people);
		                } 
	                }	
				}
			}catch(Exception $e){

			}
			$response 			= response("success", array(), "Success Cancel a booking ");
			echo $response;
		}else{
			echo response("fail", array(), "Data of meeting not found");
		}
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
		    '13'=> '01',
		    '14'=> '02',
		    '15'=> '03',
		    '16'=> '04',
		    '17'=> '05',
		    '18'=> '06',
		    '19'=> '07',
		    '20'=> '08',
		    '21'=> '09',
		    '22'=> '10',
		    '23'=> '11',
		    '24'=> '12',
		);
		$d = explode(":", $string);
		$h = $d[0];
		$m = $d[1];
		$s = $d[2];
		$formatH = ( ($m-0) > 12 ) ? "PM":"AM";
		return $nM[$h] . ":". $m . " ".$formatH;
	}
}
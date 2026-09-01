<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Booking extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Api');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Api2');
		$this->load->model('Model_Notif');
		$this->load->model('Model_MeetingLimitation');
		$this->load->model('Model_License');
		$this->load->model('Model_Booking');
		$this->load->model('Model_Pantry');
		$this->load->model('Model_Invoice');


		$this->load->helper('response');
		$this->output->set_content_type('application/json');
	}

	
	private function getStatusInvoiceName()
	{
		$data 		= $this->Model_Admin->getInvStatusName();
		if($data['error'] == null){
			return  $data['data'];
		}else{
			return  array();
		}
	}
	public function getFilter()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// $nik = $post['nik'];
		$filter_room_for_usage = $this->Model_Admin->select_all_data('room_for_usage', ["is_deleted" => 0], ["*"],"result");
		$default_room_for_usage_selected = ['name' => "ALL", 'id' => "0", 'is_deleted' => "0", 'selected' => "0"];
		array_unshift($filter_room_for_usage , $default_room_for_usage_selected);

		$filter = [];
		$filter['room_for_usage'] = $filter_room_for_usage;
		$filter['capacity'] = 0;
		echo response("success", $filter, "Get filter success");
	}
	public function getAlocation()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$nik = $post['nik'];
		$dataAlocation 		= $this->Model_Api->getBookingAlocationPIC($nik);
		if($dataAlocation['error'] == null){
			echo response("success", $dataAlocation['data'], "Get success");
		}else{
			echo response("fail", $dataAlocation, "Get failed");
		}

	}
	
	// CRUD
	public function postCreateBooking()
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
		$oldBookingId = isset($post['databook']['old_booking_id']) ? $post['databook']['old_booking_id'] : "";
		// TIMEZONE
		// TIMEZONE
		// TIMEZONE
		$timezone = APP_GMT;
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set($timezone);
		}

		$datetime 	= date('Y-m-d H:i:s');
		$randoom_id	= random_string('numeric', 10);

		// replace id with OLD Booking 
		if($oldBookingId != ""){
			$id 		= $oldBookingId."";
			$invoice_id = $oldBookingId."";

			
			$getBookingOld 		= $this->Model_Api->getDataBookingById($oldBookingId); //
			if($getBookingOld ['error'] != null){
				$response = response("fail", array(), "Data not exist, please refresh the apps ");
				echo $response;	
				die();
			}

			$dataBookingOld = $getBookingOld['data'];
			$dataBookingOld['canceled_note'] = "This meeting schedule has changed, attendees will soon receive a new schedule, please see the notification via apps or email";

			$module_int_365 = $this->Model_Module->get_module_int_365();
			$module_int_google = $this->Model_Module->get_module_int_google();
			if($module_int_365['is_enabled'] == 1 ){
				$ms365 = $this->Model_License->get365Integration();
				$ck365 = $this->Model_License->check365Data();
				// print_r($dataInvitation);
				if($ck365 == true){
					$dataInvitation222 = [];
					$res_365 = $this->Model_License->cancelEvent365($dataBookingOld,$ms365, $dataInvitation222,$dataInvitation222);
				}
			}
			if($module_int_google['is_enabled'] == 1 ){

			}
			$updateDataOld = [
				'is_moved' => 1,
				'is_moved_agree' => 1,
				'is_alive' => 4,
				'is_expired' => 1,
				'canceled_note' => $dataBookingOld['canceled_note'],
			];
			// EXECUTE NOTIFICATION
			$this->Model_Booking->postSendNotificationMovedMeeting($oldBookingId); // 
			// print_r($updateDataOld);
			$this->Model_Api->updateData('booking', $updateDataOld, ['booking_id' => $oldBookingId  ]);
			$this->Model_Api->updateData('booking_invitation', ['is_deleted' => 1], ['booking_id' => $oldBookingId  ]);
			$respw = $this->Model_Api->updateData('pantry_transaksi', ['is_deleted' => 1],['booking_id' => $oldBookingId  ]);
			$respw = $this->Model_Api->updateData('sending_email', ['is_deleted' => 1],['booking_id' => $oldBookingId  ]);
			$resp4 = $this->Model_Api->updateData('sending_notif', ['is_deleted' => 1],['booking_id' => $oldBookingId ]);
			$resp4 = $this->Model_Api->deleteAll('booking_invoice', ['booking_id' => $oldBookingId  ]);
		}

		$id 		= $randoom_id."";
		$invoice_id = $randoom_id."";
		

		$databook 	= $post['databook'];
		$internal 	= $databook['internal_data'];
		$eksternal 	= $databook['external_data'];
		$alocation 	= $databook['alocation'];

		// MEETING LIMITATION
		$this->Model_MeetingLimitation->checkMeeting($databook['date'].' '. $databook['startStr'], $databook['date'].' '. $databook['endStr']);
		
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
		$time1 						= new DateTime($databook['date'].' '. $databook['startStr']);
		$time2 						= new DateTime($databook['date'].' '. $databook['endStr']);
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
		if(($modules['price']['is_enabled']-0) == 1){
			$cost						= $room['price'] - 0;  // per hours
			$getHoursMeeting 			= floor($duration / $fHour);
			$checkHours 				= fmod($duration,$fHour);
			if($checkHours > 0){
				$getHoursMeeting += 1;
			}
			$reservation_cost 			= $cost * $getHoursMeeting;
		}
		$nikPic						= $post['nik'];
		$resPIC 					= $this->Model_Api->getNikEmployee($nikPic);
		$getDataPIC 				= $resPIC['data'];
		if(!isset($getDataPIC['nik'])){
			$response = response("fail", array(), "PIC/Host/Organizer not found or unregistered in system, please register. ");
				echo $response;
			die();
		}
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
		$waktu_timestart 	= $databook['startStr'];
		$waktu_timeend	 	= $databook['endStr'];
		$waktu_mulai 		= $databook['date'].' '. $databook['startStr'];
		$waktu_end 			= $databook['date'].' '. $databook['endStr'];
		$waktu_akhir 		= $waktu_end ;
		$ruangan 			= $room['radid'];
		
		
		
		// CREATE BOOKING ROW
		$data 						= array();  // DATA BOOKING
		$data['booking_id'] 		= $id;
		$data['booking_devices']	= "mobile";
		$data['no_order'] 			= $formatInvoice;
		$data['title'] 				= $databook['title'];
		$data['room_id'] 			= $room['radid'];
		$data['date'] 				= $databook['date'];
		$data['start'] 				= $databook['date'] ." ". $databook['startStr'];
		$data['end'] 				= $databook['date'] ." ". $databook['endStr'];
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
		$data['note'] 				= isset($databook['note']) ? $databook['note'] : "";
		$data['room_name'] 			= $room_name;
		$data['is_merge'] 			= $isMerge;
		$data['merge_room_name'] 	= $merge_room_name;
		$data['merge_room_id'] 		= $merge_room_id;
		$data['merge_room'] 		= $dataMergeRoomWidthJson;
		$data['is_vip'] 			= isset($databook['is_vip']) ? ($databook['is_vip']-0) : 0;
		// $data['is_vip'] 			= 1;
		$data['vip_user'] 			= isset($databook['vip_user']) ? $databook['vip_user']  : "";
		$data['is_approve']			= 0;  
		$data['user_approval']		= "";
		$data['category']			= isset($databook['category']) ? $databook['category']  : "";
		$data['timezone']			= $timezone;


		// server 
		$server_start = new DateTime($data['start'], new DateTimeZone($timezone));
		$server_end = new DateTime($data['end'], new DateTimeZone($timezone));
		$server_date = new DateTime($data['start'], new DateTimeZone($timezone));

		$data['server_start'] = $server_start->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
		$data['server_end'] = $server_end->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
		$data['server_date'] = $server_date->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d');
		// print_r($data['date']);
		// print_r($data);
		

		// die();

		$data	= $this->Model_MeetingLimitation->adjustAdvanceMeeting($data, $room);
		$data	= $this->Model_MeetingLimitation->checkMeetingVipAccess($data, $room);
		$data	= $this->Model_MeetingLimitation->checkApprovalMeetingAccess($data, $room);

		// CREATED PIC/ORGANIZER
		$invitation_pic = [];
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
		QRcode::png($qrnvitationPIC,$tmpdir.$qrnvitationPIC.".png",QR_ECLEVEL_H,10,3);

		$feedbackcheck = [];

		$vipForceOtherMoved = $data['is_vip'] == 1 ;

		if(isset($databook['moved']) == false){ // IS VIP
			if($isMerge == true){
				$tempMergeRoomName = array();

				foreach ($mergeRoom  as $mk => $mv) {
					$ruangan_m_id = $mv;
					$feedbackcheck_child = $this->Model_Admin->checkKondisiBookingPerRuangan($ruangan_m_id,$data['server_date'], $data['server_start'],$data['server_end'], $vipForceOtherMoved  );
					$feedbackcheck = $this->Model_Admin->checkFeedMeetingMerge($feedbackcheck,$feedbackcheck_child);
				}
			}else{
				$feedbackcheck = $this->Model_Admin->checkKondisiBookingPerRuangan($ruangan,$data['server_date'], $data['server_start'],$data['server_end'], $vipForceOtherMoved);
			}
			// print_r($data);
			// die();

			if($vipForceOtherMoved){
				// check shcdule book is vip or not
				$ckVipRestricted = $this->Model_MeetingLimitation->checkBatchMeetingHaveVipAccess($feedbackcheck);
			// print_r($ckVipRestricted);

				if($ckVipRestricted == true){
				// if($ckVipRestricted == false){
					$response = response("fail", [], "The schedule has been used, There are VIP people who use this schedule too");
					echo $response;
					die();
				}
				if(count($feedbackcheck) > 0){
					$ffret = [
						'moved' =>$feedbackcheck,
						'databook'=> $databook,
					];
					$response = response("fail", $ffret, "The schedule has been used, do you want to shift the schedule?");
					echo $response;
					die();
				}
				
			}
		}else{
			$movedBooking = $databook['moved'];
			$ckVipRestricted = $this->Model_MeetingLimitation->processMovedMeeting($movedBooking, $invitation_pic);
		}
		// print_r($data);
		// die();

		// =========================================================================
		if (($modules['invoice']['is_enabled']-0) == 1 && $modules['price']['is_enabled'] == 1 && $data['is_alive'] == 1 ) {
			$years 			= date('Y', strtotime($data['date'])); // get tahun from date
			$y_years 		= date('y', strtotime($data['date'])); // get tahun from date
			$months			= date('m', strtotime($data['date'])); // get tahun from date
			$days 			= date('d', strtotime($data['date'])); // get tahun from date
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
		// 
		
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
			QRcode::png($qrnvitation,$tmpdir.$qrnvitation.".png",QR_ECLEVEL_H,10,3);
			array_push($internalBatch, $ibatch);
			$dataEmailInternal[$nn0]['pin_room'] = $num_str;
			$dataEmailInternal[$nn0]['is_pic'] = 0;
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
			QRcode::png($qrnvitation,$tmpdir.$qrnvitation.".png",QR_ECLEVEL_H,10,3);
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
		if (($modules['invoice']['is_enabled']-0) == 1 && $modules['price']['is_enabled'] == 1 && $databook['is_alive'] == 1 ) {
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
		// MICROSOFT 365
		// MICROSOFT 365
		// MICROSOFT 365
		$module_int_365 = $this->Model_Module->get_module_int_365();
		$module_int_google = $this->Model_Module->get_module_int_google();
		// MICROSOFT 365
		$room_365 = $room['config_microsoft'] == null ? "" : $room['config_microsoft'];
		$room_google = $room['config_google'] == null ? "" : $room['config_google'];
		if(($module_int_365['is_enabled']-0) == 1 && $data['is_alive'] == 1 && $room_365 != "" ){
			$ms365 = $this->Model_License->get365Integration();
			$ck365 = $this->Model_License->check365Data();
			if($ck365 == true){
				$res_365 = $this->Model_License->createEvent365($data,$room,$ms365, $dataEmailInternal,$dataEmailEksternal);
				$jres_365 = json_decode($res_365, TRUE);
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
			$_notif['body'] 			= $data['title'] ." - ". getformatDate($data['date']);
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
		$_notif['body'] 			= $data['title'] ." - ". getformatDate($data['date']);
		$_notif['is_sending'] 		= 0;
		$_notif['is_deleted'] 		= 0;
		$_notif['created_at'] 		= $datetime;
		$type_notif 				= 1; // notification_type 1=booking
		$notif_insert				= false; // notification_type 1=booking
		array_push($notifcollectdata, $_notif);
		$this->Model_Notif->insertNotifAdmin(12, "Create meeting", $data['title']);
		$this->Model_Notif->insertNotifBatch($notifcollectdata);
		
		
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
		$emailBooking['format_time_start'] = $this->Model_Admin->formatTime($waktu_timestart);
		$emailBooking['format_time_end'] = $this->Model_Admin->formatTime($waktu_timeend);
		$emailBooking['format_date'] = $this->Model_Admin->formatDate($tanggal_meeting);

		// MODULE EMAIL
		if( ($modules['email']['is_enabled']-0) == 1 && $data['is_alive'] == 1 ){
			foreach ($dataToSend['internal'] as $key => $people) {
				// $pNotif = $this->Model_Notif->sendEmailInternal("invitation", $emailBooking, $people, $invitation_pic);
				if($people['is_pic'] == 1){
					$pNotif = $this->Model_Notif->sendEmailPIC("invitation", $emailBooking, $people, $invitation_pic, 0); // untuk PIC/HOST/ORGANIZER

					$pNotif = $this->Model_Notif->sendEmailPIC("invitation", $emailBooking, $people, $invitation_pic, 1); // UNTUK ADMIN
				}else{
					$pNotif = $this->Model_Notif->sendEmailInternal("invitation", $emailBooking, $people, $invitation_pic);
				}
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
		if($data['is_alive'] == 1){
			$notification_title = "Invitation Meeting of ".$meeting_title;
			$notification_body 	= $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end). " at " .$room_name;
			$pNotif 			= $this->Model_Notif->pushNotification($notification_title,$notification_body,$dataToSend['internal'] ,$type_notif,$notif_insert );
			$type_notif_admin = 12; // booking
			$this->Model_Notif->insertNotifAdminApi($type_notif_admin, "Create meeting", $meeting_title, $ipicemail['nik'] );
		}
		// 2022-10-13 // LOCKER MODULE
		if( ($modules['loker']['is_enabled']-0) == 1 && $data['is_alive'] == 1 ){
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
		// APPROVALL
		if($data['is_alive'] == 0 && $data['is_approve'] == 0 && ($room['is_enable_approval']-0) == 1){
			$data_userapproval = $room['config_approval_user'];
			$list_userapproval = explode(",", $data_userapproval);
			if(count($list_userapproval) > 0){
				$data_listuserinterval 	= $this->Model_Admin->getDataEmployeeWhereInNik($list_userapproval);
				$data_listuserinterval  = $data_listuserinterval ['data'];
			}else{	
				$data_listuserinterval = [];
			}
			// print_r($data_listuserinterval);
			if(($modules['email']['is_enabled']-0) == 1 ){
				foreach ($data_listuserinterval as $key => $people) {
					$this->Model_Notif->sendEmailApproval($emailBooking, $people, $invitation_pic);
				}	
			}
			$notification_title_approve = "Request Meeting ".$meeting_title;
			$notification_body_approve 	= $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end). " at " .$room_name;
			$this->Model_Notif->pushNotification($notification_title_approve,$notification_body_approve,$data_listuserinterval  ,$type_notif,$notif_insert );

		}
		$response = response("success", array(), "Success create a booking ".$databook['title']);
		echo $response;
	}

	public function postReCreateBooking()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// file_put_contents("response.txt",$json);
		$modules['pantry'] = $this->Model_Module->get_module_pantry();
		$modules['loker'] = $this->Model_Module->get_module_loker();
		$modules['price'] = $this->Model_Module->get_module_price();
		$modules['invoice'] = $this->Model_Module->get_module_invoice();
		$modules['email'] = $this->Model_Module->get_module_email();
		// TIMEZONE
		// TIMEZONE
		// TIMEZONE
		$timezone = APP_GMT;
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set($timezone);
		}
		$databook 			= $post['databook'];
		$room 				= $databook['room'];
		$getBooking 		= $this->Model_Admin->getDataBookingById($databook['booking_id']);
		$getBookingInv 		= $this->Model_Api->getDataBookingInvById($databook['booking_id'])['data'];
		$getPic 			= $this->Model_Admin->getPICByBookingId($post['booking_id']);
		$dataPic 			= $getPic['data'];
		$dataBooking 		= $getBooking['data'];
		$dataInvitation		= $getBookingInv;
		$datetime 			= date("Y-m-d H:i:s");
		if(!isset($dataBooking['booking_id'])){
			$response = response("fail", array(), "Data of meeting not found ");
			echo $response;
			die();
		}
		$this->Model_MeetingLimitation->checkMeeting($databook['date'].' '. $databook['startStr'], $databook['date'].' '. $databook['endStr']);

		$sqlNotif 			= "SELECT * FROM sending_notif WHERE booking_id='".$databook['booking_id']."' ";
		$sqlEmail 			= "SELECT * FROM sending_email WHERE booking_id='".$databook['booking_id']."' ";
		$settingGeneral				= $this->Model_Api->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];

		$isMerge 					= $dataBooking['is_merge'];
		$notifcollectdata 			= array();
		$ddd = $post['date'] == "" ? $dataBooking['date'] :  $post['date'];
		$room_id 					= $dataBooking['room_id'];
		if($isMerge == true){
			$Q_room						= " SELECT * FROM room WHERE radid=".$dataBooking['merge_room_id']." ";
			$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		}else{
			$Q_room						= " SELECT * FROM room WHERE radid=".$room_id." ";
			$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		}
		// MODULE PANTRY
		if($modules['pantry']['is_enabled'] == 1 ){
			$set_pantry_config          = $this->Model_Admin->select_all_data('setting_pantry_config', array(), array(), 'row');
			$pantry_expired 			= $set_pantry_config['pantry_expired']; 
			$pantry_max_order_qty 		= $set_pantry_config['max_order_qty']; 
			$pantry_before_order_meeting= $set_pantry_config['before_order_meeting']; 
			$set_pantry 				= array();
			$where_pantry 				= array();
			$pantry_order 				= $this->Model_Admin->select_all_data('pantry_transaksi', 
											array('booking_id' =>$dataBooking ['booking_id'], 'via'=>'booking', 'order_st'=>0 )
											, array(), 'result');
			
			if(count($pantry_order) > 0){
				$updateOrderTime = array(); // collected data pantry
				$row_pantry_order = $pantry_order[0];
				if($dataBooking['date'] != $post['date']){
					$q_date = $dataBooking['date'];
					$sql_pantry = "SELECT COALESCE(max(order_no), '') as order_no from pantry_transaksi
								WHERE DATE(order_datetime) = '".$q_date."'   AND pantry_id=".$row_pantry_order['pantry_id'] ." "  ;
					$row_order_pantry 	= $this->Model_Admin->querySql($sql_pantry)->row_array();
					if($row_order_pantry['order_no'] == "" || $row_order_pantry['order_no'] == null){
						$no_order_pantry = sprintf("%04d", "1");
					}else{
						$old_no_order_pantry = $row_order_pantry['order_no']-0;
						$no_sort_order_pantry = $old_no_order_pantry + 1;
						$no_order_pantry = sprintf("%04d", $no_sort_order_pantry);
					}
					$updateOrderTime['order_no'] = $no_order_pantry;
				}
				$pantry_trs_status = $this->Model_Admin->select_all_data('pantry_transaksi_status', array('id'=>0), array(), 'row');
				$startBooking = $post['start'] == "" ? $dataBooking['start'] :  $post['start'];
				$tanggaltime_order_pantry = $startBooking;
				$b_time = "-".$pantry_before_order_meeting;
				$tanggaltime_order_pantry_before = date('Y-m-d H:i:s', strtotime($b_time .'minutes', strtotime($tanggaltime_order_pantry)));
				$updateOrderTime['order_datetime'] = $tanggaltime_order_pantry;
				$updateOrderTime['order_datetime_before'] = $tanggaltime_order_pantry_before;
				$updateOrderTime['via'] = 'booking';
				$updateOrderTime['order_st'] = 0;
				$updateOrderTime['order_st_name'] = $pantry_trs_status['name'];
				$dataBooking['updated_at'] = $datetime;
				$set_pantry = $updateOrderTime;
				$where_pantry['id'] = $row_pantry_order['id'];

			}
		}
		// die();
		if(count($room) <= 0){
			$response = response("fail", array(), "Room not found ");
			echo $response;
			die();
		}
		$room 			 			= $room[0];
		foreach ($dataInvitation as $val) {
			if($val['internal'] == 1){
				// only internal
				$_notif 					= array();
				$_notif['datetime'] 		= $datetime;
				$_notif['nik'] 				= $val['nik']; // user id
				$_notif['type'] 			= 1; // booking is 1
				$_notif['value'] 			= $databook['booking_id']; // booking id
				$_notif['title'] 			= "Reschedule meeting";
				$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				$_notif['is_sending'] 		= 0;
				$_notif['is_deleted'] 		= 0;
				$_notif['created_at'] 		= $datetime;
				if($val['is_pic'] == 1){
					// pic
					$_notif['title'] 			= "Reschedule a meeting";
					$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				}
				array_push($notifcollectdata, $_notif);
			}
			
		}
		if($getBooking ['error'] == null){
			$datatimestart 			=  $databook['date']  . " ". $databook['startStr'];
			$datatimeend 			=  $databook['date']  . " ". $databook['endStr'];
			$datetime 				= date('Y-m-d H:i:s');
			$dataBooking 			= $getBooking['data'];
			// print_r($dataBooking);

			$dataBooking['date'] 	= $databook['date'] == "" ? $dataBooking['date'] :  $databook['date'];
			$dataBooking['start'] 	= $databook['startStr'] == "" ? $dataBooking['start'] :  $datatimestart;
			$dataBooking['end'] 	= $databook['endStr'] == "" ? $dataBooking['end'] :  $datatimeend;
			
			$booking_id 			= $dataBooking['booking_id'];
			$startTime 				= strtotime($dataBooking['start']);
			$checkTime 				= strtotime($dataBooking['end']);
			$fHour 					= $dataSettingGeneral['duration'];
			$extended_duration 		= $dataBooking["extended_duration"];
			$duration = round(abs($checkTime - $startTime) / 60,2) + $extended_duration;
			$cost					= $dataBooking['price']; // per hours
			$allduration 			= $extended_duration+ $duration;
			$getHoursMeeting 		= floor($allduration / $fHour);
			$checkHours 			= fmod($allduration,$fHour);
			if($checkHours > 0){
				$getHoursMeeting += 1;
			}

			$tanggal_meeting = $databook['date'];
			$waktu_mulai = $databook['date'].' '. $databook['startStr'];
			$waktu_end = $databook['date'].' '. $databook['endStr'];
			$waktu_timeend = $waktu_end;
			$ruangan = $dataBooking['room_id'];


			// server 
			$server_date = new DateTime($waktu_mulai, new DateTimeZone($timezone));
			$server_start = new DateTime($waktu_mulai, new DateTimeZone($timezone));
			$server_end = new DateTime($waktu_end, new DateTimeZone($timezone));

			$dataBooking['server_start'] = $server_start->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
			$dataBooking['server_end'] = $server_end->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
			$dataBooking['server_date'] = $server_date->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d');

			if($isMerge == true){
				$tempMergeRoomNameRaw = $dataBooking['merge_room'];
				$tempMergeRoomName = json_decode($dataBooking['merge_room'],true);
				foreach ($tempMergeRoomName  as $mk => $mv) {
					$ruangan_m_id = $mv['radid'];
					$this->Model_Admin->checkKondisiBookingPerRuanganRes($ruangan_m_id,$dataBooking['server_date'] , $dataBooking['server_start'],$dataBooking['server_end'], $booking_id);
				}
			}else{
				$this->Model_Admin->checkKondisiBookingPerRuanganRes($ruangan,$dataBooking['server_date'] , $dataBooking['server_start'],$dataBooking['server_end'], $booking_id);
			}
			
			$reservation_cost 			= $cost	* $getHoursMeeting;
			$dataBooking['duration_per_meeting'] = $fHour ;
			$dataBooking['cost_total_booking'] = $reservation_cost ;
			$dataBooking['total_duration'] = $duration;
			$dataBooking['is_expired'] = 0;
			$dataBooking['is_canceled'] = 0;
			$dataBooking['is_alive'] = 1;
			$dataBooking['is_rescheduled'] = 1;
			$dataBooking['updated_at'] = $datetime;
			$dataBooking['updated_by'] = $post['nik'];

			$room_name = $dataBooking['room_name'];

			$where = array(
				 "booking_id"=>$booking_id
			);
			// MICROSOFT 365
			// MICROSOFT 365
			// MICROSOFT 365
			$module_int_365 = $this->Model_Module->get_module_int_365();
			$module_int_google = $this->Model_Module->get_module_int_google();
			if($module_int_365['is_enabled'] == 1 ){
				$ms365 = $this->Model_License->get365Integration();
				$ck365 = $this->Model_License->check365Data();
				if($ck365 == true){
					$dataInvitation222 = [];
					$res_365 = $this->Model_License->rescheduleEvent365($dataBooking,$room,$ms365, $dataInvitation,$dataInvitation222);

					$jres_365 = json_decode($res_365, TRUE);
					// print_r($jres_365);
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

			if($module_int_google['is_enabled'] == 1 ){

			}
			unset($dataBooking['price']);
			unset($dataBooking['booking_id']);
			unset($dataBooking['room_name']);
			unset($dataBooking['merge_room']);
			unset($dataBooking['is_merge']);
			unset($dataBooking['merge_room_id']);
			unset($dataBooking['merge_room_name']);
			unset($dataBooking['room_id']);
			unset($dataBooking['id']);
			unset($dataBooking['room_name2']);

			if($modules['invoice']['is_enabled'] == 1  && $modules['price']['is_enabled'] == 1 ){
				$booking_invoice = array(
					"rent_cost" => $reservation_cost,
					"alocation" => $dataBooking ['alocation_id'],
					"time_before" => $datetime,
					"updated_at" 	=> $datetime,
					"updated_by" 	=> $post['nik'],
				);
				$winvoice 	= array("booking_id" => $booking_id);
				$udata 				= $this->Model_Api->updateData("booking_invoice", $booking_invoice, $winvoice);
			}
			
			$udata 				= $this->Model_Api->updateData("booking", $dataBooking, $where);
			$meeting_date = $dataBooking['date'];
			$explodeS = explode(" ", $dataBooking['start']);
			$explodeE = explode(" ", $dataBooking['end']);
			$meeting_start = $explodeS[1];
			$meeting_end = $explodeE[1];
			if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title = $dataBooking['title'];
				
				$notification_title = "Reschedule Meeting of ".$meeting_title;
				$room_name = $room['name'];
				$notification_body = $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end) . " at " .$room_name;
				$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
			}	
			$tableEmail			= $this->Model_Admin->querySql($sqlEmail)->result_array();
			if(count($tableEmail) > 0){
				$getBookingEmail 		= $this->Model_Api->getDataBookingById($booking_id); //
				if($getBookingEmail ['error'] != null){
					$response = response("fail", array(), "Data not exist ");
					echo $response;	
					die();
				}
				$tableEmail		= $tableEmail[0];
				$batchemail 	= $tableEmail['batch'];
				$dataToSend		= json_decode($batchemail,true);
				// inisil booking 
				$emailBooking = $getBookingEmail['data'];
		        $emailBooking['format_time_start'] = $this->Model_Admin->formatTime($meeting_start);
		        $emailBooking['format_time_end'] = $this->Model_Admin->formatTime($meeting_end);
		        $emailBooking['format_date'] = $this->Model_Admin->formatDate($meeting_date);

		       // MODULE EMAIL 
		       	if($modules['email']['is_enabled'] == 1 ){
		       		foreach ($dataToSend['internal'] as $key => $people) {
		            	$pNotif = $this->Model_Notif->sendEmailInternal("reschedule", $emailBooking, $people, $dataPIC);
			        }   
			        foreach ($dataToSend['eksternal'] as $key => $people) {
			            $pNotif = $this->Model_Notif->sendEmailExternal("reschedule", $emailBooking, $people, $dataPIC);
			        }   
		       	}
		        
				
			}
			$response = response("success", array(), "Success reschedule a booking ");
			echo $response;
		}else{
			echo response("fail", array(), "Data of meeting not found");
		}
	}

	public function postUpdateBooking()
	{
		// CRUD
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
		$oldBookingId = isset($post['databook']['old_booking_id']) ? $post['databook']['old_booking_id'] : "";
		$actionSendNotfi = isset($post['databook']['send_notif']) ? $post['databook']['send_notif']-0 : 0;
		// TIMEZONE
		// TIMEZONE
		// TIMEZONE
		$timezone = APP_GMT;
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set($timezone);
		}
		$datetime 	= date('Y-m-d H:i:s');
		// replace id with OLD Booking 
		if($oldBookingId != ""){
			$id 		= $oldBookingId."";
			$invoice_id = $oldBookingId."";
			$getBookingOld 		= $this->Model_Api->getDataBookingById($oldBookingId); //
			if($getBookingOld ['error'] != null){
				$response = response("fail", array(), "Data not exist, please refresh the apps ");
				echo $response;	
				die();
			}
			$dataBookingOld = $getBookingOld['data'];
			$dataBookingOld['canceled_note'] = "This meeting schedule has changed, attendees will soon receive a new schedule, please see the notification via apps or email";
			$module_int_365 = $this->Model_Module->get_module_int_365();
			$module_int_google = $this->Model_Module->get_module_int_google();
			if($module_int_365['is_enabled'] == 1 ){
				$ms365 = $this->Model_License->get365Integration();
				$ck365 = $this->Model_License->check365Data();
				// print_r($dataInvitation);
				if($ck365 == true){
					$dataInvitation222 = [];
					$res_365 = $this->Model_License->cancelEvent365($dataBookingOld,$ms365, $dataInvitation222,$dataInvitation222);
				}
			}
			if($module_int_google['is_enabled'] == 1 ){

			}
			// REMOVED OLD 
			$this->Model_Api->deleteAll('booking',['booking_id' => $oldBookingId  ]);
			$this->Model_Api->deleteAll('booking_invitation', ['booking_id' => $oldBookingId  ]);
			$this->Model_Api->deleteAll('booking_invoice', ['booking_id' => $oldBookingId  ]);
			$this->Model_Api->deleteAll('sending_notif', ['booking_id' => $oldBookingId  ]);
			$this->Model_Api->deleteAll('sending_email', ['booking_id' => $oldBookingId  ]);
			$respw = $this->Model_Api->updateData('pantry_transaksi', ['is_deleted' => 1],['booking_id' => $oldBookingId  ]);
			
		}
		// INITIAL BOOKING ID
		$randoom_id	= $oldBookingId;
		$id 		= $randoom_id."";
		$invoice_id = $randoom_id."";
		$isMerge 	= isset($databook['is_merge']) ? $databook['is_merge'] : 0;

		$databook 	= $post['databook'];
		$internal 	= $databook['internal_data'];
		$eksternal 	= $databook['external_data'];
		$alocation 	= $databook['alocation'];

		// MEETING LIMITATION
		$this->Model_MeetingLimitation->checkMeeting($databook['date'].' '. $databook['startStr'], $databook['date'].' '. $databook['endStr']);
		
		$settingGeneral				= $this->Model_Api->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];
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

		$reservation_cost 			= 0;
		$formatInvoice 				= "";
		$fHour                      = $dataSettingGeneral['duration'];

		// START MODULE PANTRY
		// START MODULE PANTRY'
		$this->Model_Pantry->createPantryOrder($databook, $id);
		// END MODULE PANTRY
		// END MODULE PANTRY


		// START MODULE INVOICE & PRICE
		// START MODULE INVOICE & PRICE'
		$duration 					= $this->Model_Invoice->calculateDuration($databook);
		$reservation_cost 			= $this->Model_Invoice->calculateReservationCost($databook, $room);
		$formatInvoice 				= $this->Model_Invoice->createInvoiceOrder($databook, $alocation, $room);
		// END MODULE INVOICE & PRICE
		// END MODULE INVOICE & PRICE

		$nikPic						= $post['nik'];
		$resPIC 					= $this->Model_Api->getNikEmployee($nikPic);
		$getDataPIC 				= $resPIC['data'];
		if(!isset($getDataPIC['nik'])){
			$response = response("fail", array(), "PIC/Host/Organizer not found or unregistered in system, please register. ");
				echo $response;
			die();
		}
		$internalBatch 				= array();
		$eksternalBatch 			= array();
		$dataEmailInternal 			= $rowInternal['data'];
		$dataEmailEksternal 		= array();
		$dataEmailInternal_array	= array();
		
		$tanggal_meeting 	= $databook['date'];
		$waktu_timestart 	= $databook['startStr'];
		$waktu_timeend	 	= $databook['endStr'];
		$waktu_mulai 		= $databook['date'].' '. $databook['startStr'];
		$waktu_end 			= $databook['date'].' '. $databook['endStr'];
		$waktu_akhir 		= $waktu_end ;
		$ruangan 			= $room['radid'];
				
		// CREATE BOOKING ROW
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

		// server 
		$server_date = new DateTime($data['date'], new DateTimeZone($timezone));
		$server_start = new DateTime($data['start'], new DateTimeZone($timezone));
		$server_end = new DateTime($data['end'], new DateTimeZone($timezone));

		$data['server_start'] = $server_start->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
		$data['server_end'] = $server_end->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
		$data['server_date'] = $server_date->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d');

		$data	= $this->Model_MeetingLimitation->adjustAdvanceMeeting($data, $room);
		$data	= $this->Model_MeetingLimitation->checkMeetingVipAccess($data, $room);
		$data	= $this->Model_MeetingLimitation->checkApprovalMeetingAccess($data, $room);


		// CREATED PIC/ORGANIZER
		$invitation_pic 						= $this->Model_Booking->createInvitationPic($data, $nikPic,$getDataPIC);
		// print_r($invitation_pic 	);
		$qrnvitationPIC = $id."_".$invitation_pic['pin_room'];
		QRcode::png($qrnvitationPIC,$tmpdir.$qrnvitationPIC.".png",QR_ECLEVEL_H,10,3);
		$vipForceOtherMoved = $data['is_vip'] == 1 ;

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
		// die();
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
		
		$respP 			= $this->Model_Api->insertData('booking_invitation', $invitation_pic);
		if(count($internalBatch) > 0){
			$resp1		= $this->Model_Api->insertDataBatch('booking_invitation', $internalBatch);
		}
		if(count($eksternalBatch) > 0){
			$resp2 		= $this->Model_Api->insertDataBatch('booking_invitation', $eksternalBatch);
		}
		
		//END ATTENDEES AREA


		// MICROSOFT 365
		// MICROSOFT 365
		// MICROSOFT 365
		$module_int_365 = $this->Model_Module->get_module_int_365();
		$module_int_google = $this->Model_Module->get_module_int_google();
		// MICROSOFT 365
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

		// START NOTOFICATION MEETING TABLE
		$respw = $this->Model_Api->insertData('sending_email', $sending_email);
		$resp4 = $this->Model_Api->insertData('sending_notif', $sending_notif);
		$notifcollectdata = array();
		foreach ($dataEmailInternal as $val) {
			$_notif 					= array();
			$_notif['datetime'] 		= $datetime;
			$_notif['nik'] 				= $val['nik']; // user id
			$_notif['type'] 			= 1; // booking is 1
			$_notif['value'] 			= $id; // booking id
			$_notif['title'] 			= "Invitation Meeting";
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
		// END NOTOFICATION MEETING TABLE
		
		// START MANAGE NOTIF
		$send_notif = true;
		if(isset($post['databook']['notif'])){
			if( ($post['databook']['notif'] -0 ) == 0){
				$send_notif = false;
			}else{
				$send_notif = true;
			}
		}
		// END MANAGE NOTIF
		// 2023-02-10  SEND EMAIL TO ATTENDEES
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
		if( ($modules['email']['is_enabled']-0) == 1 && $data['is_alive'] == 1 && $send_notif == true){
			foreach ($dataToSend['internal'] as $key => $people) {
				if($people['is_pic'] == 1){
					$pNotif = $this->Model_Notif->sendEmailPIC("invitation", $emailBooking, $people, $invitation_pic, 0); // untuk PIC/HOST/ORGANIZER

					$pNotif = $this->Model_Notif->sendEmailPIC("invitation", $emailBooking, $people, $invitation_pic, 1); // UNTUK ADMIN
				}else{
					$pNotif = $this->Model_Notif->sendEmailInternal("invitation", $emailBooking, $people, $invitation_pic);
				}
				// $pNotif = $this->Model_Notif->sendEmailInternal("invitation", $emailBooking, $people, $invitation_pic);
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

		// 2022-10-13 // LOCKER MODULE
		if( ($modules['loker']['is_enabled']-0) == 1 && $data['is_alive'] == 1 ){
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
		// APPROVALL
		if($data['is_alive'] == 0 && $data['is_approve'] == 0 && ($room['is_enable_approval']-0) == 1){
			$data_userapproval = $room['config_approval_user'];
			$list_userapproval = explode(",", $data_userapproval);
			if(count($list_userapproval) > 0){
				$data_listuserinterval 	= $this->Model_Admin->getDataEmployeeWhereInNik($list_userapproval);
				$data_listuserinterval  = $data_listuserinterval ['data'];
			}else{	
				$data_listuserinterval = [];
			}
			// print_r($data_listuserinterval);
			if(($modules['email']['is_enabled']-0) == 1 ){
				foreach ($data_listuserinterval as $key => $people) {
					$this->Model_Notif->sendEmailApproval($emailBooking, $people, $invitation_pic);
				}	
			}
			$notification_title_approve = "Request Meeting ".$meeting_title;
			$notification_body_approve 	= $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end). " at " .$room_name;
			$this->Model_Notif->pushNotification($notification_title_approve,$notification_body_approve,$data_listuserinterval  ,$type_notif,$notif_insert );

		}
		$response = response("success", array(), "Success create a booking ".$databook['title']);
		echo $response;
	}

	public function postCancelBooking()
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
		$timezone = APP_GMT;
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set($timezone);
		}

		$getBooking 		= $this->Model_Api->getDataBookingById($post['booking_id']);
		$sqlNotif 			= "SELECT * FROM sending_notif WHERE booking_id='".$post['booking_id']."' ";
		$sqlEmail 			= "SELECT * FROM sending_email WHERE booking_id='".$post['booking_id']."' ";
		$getPic 			= $this->Model_Admin->getPICByBookingId($post['booking_id']);
		$dataPic 			= $getPic['data'];
		// print_r($dataPic);
		// die();
		$booking_id 		= $post['booking_id'];
		$noteReason = isset($post['note']) ? $post['note'] : "";
		$dataBooking = $getBooking['data'];
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
			
			$dataBooking['is_expired'] = 1;
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
					$res_365 = $this->Model_License->cancelEvent365($dataBooking,$ms365, $dataInvitation222,$dataInvitation222);
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
			$notification_title = "Meeting Cancelled, ".$meeting_title;
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
					
					$notification_body = $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end) . " at " .$room_name;
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
	                $emailBooking['format_time_start'] = $this->Model_Admin->formatTime($meeting_start);
	                $emailBooking['format_time_end'] = $this->Model_Admin->formatTime($meeting_end);
	                $emailBooking['format_date'] = $this->Model_Admin->formatDate($meeting_date);
	                if($modules['email']['is_enabled'] == 1 ){
	                	foreach ($dataToSend['internal'] as $key => $people) {
		                    $pNotif = $this->Model_Notif->sendEmailInternal("cancel", $emailBooking, $people, $dataPic);
		                }
		                foreach ($dataToSend['eksternal'] as $key => $people) {
		                    $pNotif = $this->Model_Notif->sendEmailExternal("cancel", $emailBooking, $people, $dataPic);
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

	public function postMakeHostBooking()
	{
		$post =$_POST;
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// echo "<pre>";
		$booking_id = isset($post['booking_id']) ? $post['booking_id'] : "";

		$getBooking 		= $this->Model_Api->getDataBookingById($booking_id);
		$getPic 		= $this->Model_Api2->getBookingPic($booking_id);
		$sqlNotif 			= "SELECT * FROM sending_notif WHERE booking_id='".$post['booking_id']."' ";
		$sqlEmail 			= "SELECT * FROM sending_email WHERE booking_id='".$post['booking_id']."' ";
		
		if(!isset($getPic['nik'])){
			$response 			= response("fail", array(), "Host/PIC meeting not found ");
			echo $response;
			die();
		}

		$oldHostId = $getPic['nik'];
		$hostId = isset($post['host_id']) ? $post['host_id'] : "";
		$whereChangeHost = array(
			'booking_id' => $booking_id,
			'nik' => $oldHostId,
		);

		$dataOldChange = array(
			"is_pic" => 0,
		);
		// 
		$getDataOldPIC 		= $this->Model_Api->getDataEmployeeWhereInNik(array($oldHostId));
		$getDataNewPIC 		= $this->Model_Api->getDataEmployeeWhereInNik(array($hostId));
		// 
		$dataNewChange = array(
			"is_pic" => 1,
		);
		$whereHost = array(
			'booking_id' => $booking_id,
			'nik' => $hostId,
		);

		$name_old = $getDataOldPIC['data'][0]['name'];
		$name_new = $getDataNewPIC['data'][0]['name'];
		$updateDataOLDInvitation 	 = $this->Model_Api->updateData("booking_invitation", $dataOldChange , $whereChangeHost);
		$updateDataNEWInvitation 	 = $this->Model_Api->updateData("booking_invitation", $dataNewChange , $whereHost);
		$meeting_title = $getBooking['data']['title'];
		$sqlNotif 			= "SELECT * FROM sending_notif WHERE booking_id='".$booking_id."' ";
		$sqlEmail 			= "SELECT * FROM sending_email WHERE booking_id='".$booking_id."' ";
		$tableNotif			= $this->Model_Api->querySql($sqlNotif)->result_array();
		$tableEmail			= $this->Model_Api->querySql($sqlEmail)->result_array();
		if(count($tableNotif) > 0){
			$tableNotif	= $tableNotif[0];
			$notification_title = "Change a Host of ".$meeting_title . " from ".$name_old." to ".$name_new;
			$notification_body = "New Host/PIC from ".$name_old." to ".$name_new ;
			$notification_data 	= json_decode($tableNotif['batch'], true);
			$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notification_data );
			$type_notif_admin = 12; // booking
			$this->Model_Notif->insertNotifAdminApi($type_notif_admin, "Change a host", $meeting_title, $post['nik'] );
		}
		$response 		= response("success", array(), "Success Change a host ");
		echo $response;
		die();
	}
	public function checkTodayBooking()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		// $date 			= date('Y-m-d');
		$date 			= $post['date'];
		$sst 			= $post['time'];
		$meetingRoom 	= array();
		$timearray 		= array();
		$dayNameNum = date("w", strtotime($date));
		// $dataRoom 		= $this->Model_Admin->getDataRoom()['data'];
		$dayName = getDayName($dayNameNum);
		$whreRoomString = " r.work_day LIKE '%".$dayName."%' ";
		if(isset($post['filter'])){
			$filterCapacity = $post['filter']['capacity'];
			$filterRoomUsage = $post['filter']['roomUsage'];
			if(isset($filterCapacity)){
				if( ($filterCapacity - 0) >0 ){
					$whreRoomString .= " AND r.capacity>=".$filterCapacity . "";
				}
			}
			if(isset($filterRoomUsage )){
				if(count($filterRoomUsage) > 0){
					$whreRoomString .= " AND ( ";
					$nr = 0;
					$nr_max = count($filterRoomUsage);
					foreach ($filterRoomUsage as $key => $value) {
						$nr++;
						if($nr_max == $nr){
							$whreRoomString .= " r.config_room_for_usage LIKE '%".$value."%' ";
						}else{
							$whreRoomString .= " r.config_room_for_usage LIKE '%".$value."%' OR ";
						}
					}
					$whreRoomString .= " ) ";
				}
			}
			
		}
		
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
		foreach ($dataRoom  as $k => $v) {
			$room_id 	= $v['radid'];
			$timeroom = $date ." ".$v['work_end'];
			$checknow 	= $date ." ".$sst ;
			$convertTime = strtotime($checknow);
			$aftersumnow = date('Y-m-d H:i:s', strtotime("+".$setDuration." minutes",$convertTime));
			// echo $aftersumnow . " " .$timeroom . "<br>";
			if(strtotime($aftersumnow) > strtotime($timeroom)){
				array_push($collectcheck, $k);
			}
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
			foreach ($dataTimeArray as $key => $value) {
				$nowtime = strtotime($date . " ".$sst);
				$bookingtime = strtotime($date . " " . $value['time_array']);
				if( $nowtime  > $bookingtime){
					$dataTimeArray[$key]['book'] = 1;
				}
				$dataTimeArray[$key]['book'] = $dataTimeArray[$key]['book'] . "";
			}
			$dataRoom[$k]['datatime'] = $dataTimeArray;
			$dataRoom[$k]['setting'] = $dataSettingGeneral;
			$queryCat 		= $this->Model_Api->querySql("SELECT rd.*, ru.name FROM room_for_usage_detail rd INNER JOIN room_for_usage ru ON rd.room_usage_id=ru.id WHERE rd.room_id='".$room_id."' ORDER BY ru.id ASC ");
			$dataRoom[$k]['category'] = $queryCat->result_array();


		} // foreach $dataroom
		foreach ($collectcheck as $key => $v) {
			unset($dataRoom[$key]);
		}
		$g = $dataRoom;
		$dataRoom = [];
		foreach ($g as $key => $v) {
			array_push($dataRoom, $v);
		}
		echo response("success", $dataRoom, "Get success");
		die();
	}

	public function checkPickBooking()
	{
		// $this->output->set_content_type('application/json');
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date 			= $post['date'];
		$meetingRoom 	= array();
		$timearray 		= array();
		$dayNameNum = date("w", strtotime($date));
		$dayName = getDayName($dayNameNum);
		$whreRoomString = "  r.work_day LIKE '%".$dayName."%' ";

		if(isset($post['filter'])){
			$filterCapacity = $post['filter']['capacity'];
			$whreRoomString .= " AND r.capacity>=".$filterCapacity . "";
			$filterRoomUsage = $post['filter']['roomUsage'];
			if(isset($filterRoomUsage )){
				if (in_array("ALL", $filterRoomUsage) == false){
					array_push($filterRoomUsage, "ALL");
				}else{
					array_push($filterRoomUsage, "Internal");
					array_push($filterRoomUsage, "External");
				}
				$whreRoomString .= " AND ( ";
				$nr = 0;
				$nr_max = count($filterRoomUsage);
				foreach ($filterRoomUsage as $key => $value) {
					$nr++;
					if($nr_max == $nr){
						$whreRoomString .= " r.config_room_for_usage LIKE '%".$value."%' ";
					}else{
						$whreRoomString .= " r.config_room_for_usage LIKE '%".$value."%' OR ";

					}
				}
				$whreRoomString .= " ) ";
			}
		}
		$dataRoom 		= $this->Model_Admin->getDataRoom($whreRoomString)['data'];
		$settingGeneral	= $this->Model_Api->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60

		
		// die();
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
						WHERE b.date='".$date."' AND b.room_id='".$room_id."' AND b.is_alive = 1 
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
				$dataRoom[$k]['merge_room']  = $mergeRoom;
			}
			$queryTime 		= $this->Model_Api->querySql($sql);
			$dataTimeArray 	= $queryTime->result_array();
			foreach ($dataTimeArray as $key => $value) {
				// $date
				if($value['canceled'] >=1 || $value['expired'] >=1 || $value['endearly'] >=1 ){
					$dataTimeArray[$key]['book'] = 0;
				}
				if($dataTimeArray[$key]['book'] >= 1){
					$dataTimeArray[$key]['book'] = 1;
				}
				
				$dataTimeArray[$key]['book'] = $dataTimeArray[$key]['book'] . "";

				unset($dataTimeArray[$key]['canceled']);
				unset($dataTimeArray[$key]['expired']);
				unset($dataTimeArray[$key]['endearly']);
			}
			$dataRoom[$k]['datatime'] = $dataTimeArray;
			$dataRoom[$k]['setting'] = $dataSettingGeneral;
		} // foreach $dataroom
		echo response("success", $dataRoom, "Get success");
		die();
	}
	
}

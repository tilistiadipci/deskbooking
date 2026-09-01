<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Approval extends CI_Model {
	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Notif');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Api');
		$this->load->model('Model_Api2');

		// $this->load->library('database');
	}
	public function getApprovalMeeting($WHERE, $post){
		$datemobile_sp = "";
		$ar = array(
			"bi.is_deleted" => 0,
			"bi.is_pic" => 1,
			"e.is_deleted" =>0
		);
		try{
			$datemobile_sp = "";
			$strtotime = strtotime($post['date'].":00");
			$date = $post['date'];
			$time = $post['time'];
			$ar = array(
				"bi.is_deleted" => 0,
				"bi.is_pic" => 1,
				// "b.is_deleted" => 0,
				"e.is_deleted" => 0,
				// "b.is_expired" => 0,
				// "b.is_canceled" => 0,
				// "b.end_early_meeting" => 0,
				// "e.nik" => $post['nik']
			);
			$data = $this->db->select('b.*, r.location, 
					r.name as room_name2,
					r.facility_room, r.capacity, 
					r.work_day, r.work_start, r.work_end,
					bl.name as building_name,
					r.config_approval_user,
					bi.pin_room, 
					bi.is_pic, bi.attendance_status, bi.attendance_reason, bi.execute_attendance,
					(SELECT bii.nik FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_phone,
					(SELECT ee.email FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_email,
					(SELECT ee.no_phone FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_ext
					
					')
					->select('e.email, e.no_phone, e.no_ext')
					->select('(SELECT COUNT(*) FROM booking_invitation bii WHERE
						bii.booking_id=b.booking_id) as num_partisipant  ')
					->from("employee e ")
					->join("booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("booking b ", "bi.booking_id=b.booking_id")
					->join("room r ", "b.room_id=r.radid")
					->join("building bl ", "r.building_id=bl.id","left")
					->where($ar)
					->where($WHERE)
					->where(" r.config_approval_user LIKE '%".$post['nik']."%' ")
					->group_by('b.booking_id')
					->order_by('b.id', 'DESC')
					->get();
			
			$sn = array(
				"error" => null,
				"data" => $data->result_array()
			);
			return $sn;
		}catch(Exception $error){
			$sn = array(
				"error" => $error,
				"data" => $this->db->error()
			);
			return $sn;
		}
	}
	public function postApprovalBookReject($booking_id, $dataupdate)
	{
		$modules['pantry'] = $this->Model_Module->get_module_pantry();
		$modules['loker'] = $this->Model_Module->get_module_loker();
		$modules['price'] = $this->Model_Module->get_module_price();
		$modules['invoice'] = $this->Model_Module->get_module_invoice();
		$modules['email'] = $this->Model_Module->get_module_email();
		$post = [
			'booking_id' =>$booking_id,
		];
		$getBooking 				= $this->Model_Admin->getDataBookingById($post['booking_id']);
		$getPic 					= $this->Model_Admin->getPICByBookingId($post['booking_id']);
		$dataPic 					= $getPic['data'];
		$dataBooking 				= $getBooking['data'];
		$getBookingInv 				= $this->Model_Admin->getDataBookingInvById($post['booking_id'])['data'];
		$dataInvitation				= $getBookingInv;
		$ddd 						= $dataBooking['date'];
		$datetime 					= date("Y-m-d H:i:s");
		$notifcollectdata 			= [];
		$isMerge 					= $dataBooking['is_merge'];
		$room_id 					= $dataBooking['room_id'];
		if($isMerge == true){
			$Q_room						= " SELECT * FROM room WHERE radid=".$dataBooking['merge_room_id']." ";
			$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		}else{
			$Q_room						= " SELECT * FROM room WHERE radid=".$room_id." ";
			$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		}
		$where = array(
			"booking_id"=>$dataBooking['booking_id']
		);
		foreach ($dataInvitation as $val) {
			if($val['internal'] == 1 && $val['is_pic'] ){
				// only internal
				$_notif 					= array();
				$_notif['datetime'] 		= $datetime;
				$_notif['nik'] 				= $val['nik']; // user id
				$_notif['type'] 			= 1; // booking is 1
				$_notif['value'] 			= $post['booking_id']; // booking id
				$_notif['title'] 			= "Invitation meeting";
				$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				$_notif['is_sending'] 		= 0;
				$_notif['is_deleted'] 		= 0;
				$_notif['created_at'] 		= $datetime;
				if($val['is_pic'] == 1){
					$_notif['title'] 			= "Request Meeting Rejected";
					$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				}
				array_push($notifcollectdata, $_notif);
			}
		}
		$meeting_date = $dataBooking['date'];
		$explodeS = explode(" ", $dataBooking['start']);
		$explodeE = explode(" ", $dataBooking['end']);
		$meeting_start = $explodeS[1];
		$meeting_end = $explodeE[1];
		
		$dataBooking['is_alive'] = 4; 
		$dataBooking['is_approve'] = 2; 
		$dataBooking['is_expired'] = 1; 
		$dataBooking['user_approval'] = $dataupdate['user_approval']; 
		$dataBooking['user_approval_datetime'] = $dataupdate['datetime']; 
		$getBookingEmail 		= $this->Model_Api->getDataBookingById($booking_id); //

		$emailBooking = $getBookingEmail['data'];
		$emailBooking['format_time_start'] = $this->Model_Admin->formatTime($meeting_start);
		$emailBooking['format_time_end'] = $this->Model_Admin->formatTime($meeting_end);
		$emailBooking['format_date'] = $this->Model_Admin->formatDate($meeting_date);
		$room 			 			= $room[0];
			
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

		
		// UPDATE BOOKING 
		$udata 				= $this->Model_Admin->updateData("booking", $dataBooking, $where);
		$this->Model_Notif->insertNotifAdmin(12, "Request Meeting Rejected", $dataBooking['title']);
		$meeting_date = $dataBooking['date'];
		$explodeS = explode(" ", $dataBooking['start']);
		$explodeE = explode(" ", $dataBooking['end']);
		$meeting_start = $explodeS[1];
		$meeting_end = $explodeE[1];
		if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title = $dataBooking['title'];
				$notification_title = "Request Meeting Rejected ".$meeting_title;
				$room_name = $room['name'];
				$notification_body = $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end) . " at " .$room_name;
				$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
		}	
			
		if($modules['email']['is_enabled'] == 1 ){
			$this->Model_Notif->sendEmailResponseApproval( $emailBooking, $dataPic, 0);	
		}
	}
	public function postApprovalBook($booking_id, $dataupdate)
	{
		$modules['pantry'] = $this->Model_Module->get_module_pantry();
		$modules['loker'] = $this->Model_Module->get_module_loker();
		$modules['price'] = $this->Model_Module->get_module_price();
		$modules['invoice'] = $this->Model_Module->get_module_invoice();
		$modules['email'] = $this->Model_Module->get_module_email();
		$post = [
			'booking_id' =>$booking_id,
		];
		
		$getBooking 				= $this->Model_Admin->getDataBookingById($post['booking_id']);
		$getPic 					= $this->Model_Admin->getPICByBookingId($post['booking_id']);
		$dataPic 					= $getPic['data'];
		$getBookingInv 				= $this->Model_Admin->getDataBookingInvById($post['booking_id'])['data'];
		$sqlEmail 					= "SELECT * FROM sending_email WHERE booking_id='".$post['booking_id']."' ";
		$settingGeneral				= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];
		$dataInvitation				= $getBookingInv;
		$datetime 					= date("Y-m-d H:i:s");
		$dataBooking 				= $getBooking['data'];
		$isMerge 					= $dataBooking['is_merge'];
		$notifcollectdata 			= array();
		$ddd 						= $dataBooking['date'];
		$room_id 					= $dataBooking['room_id'];

		// $this->Model_MeetingLimitation->checkMeeting($post['start'], $post['end']);

		if($isMerge == true){
			$Q_room						= " SELECT * FROM room WHERE radid=".$dataBooking['merge_room_id']." ";
			$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		}else{
			$Q_room						= " SELECT * FROM room WHERE radid=".$room_id." ";
			$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		}
		// module pantry
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
			if(count($pantry_order) > 0){
				$pdata 				= $this->Model_Admin->updateData("pantry_transaksi", $set_pantry, $where_pantry);
			}
		}
		if(count($room) <= 0){
			$response = response("fail", array(), "Room not found ");
			die();
		}
		$room 			 			= $room[0];
		// die();
		foreach ($dataInvitation as $val) {
			if($val['internal'] == 1){
				// only internal
				$_notif 					= array();
				$_notif['datetime'] 		= $datetime;
				$_notif['nik'] 				= $val['nik']; // user id
				$_notif['type'] 			= 1; // booking is 1
				$_notif['value'] 			= $post['booking_id']; // booking id
				$_notif['title'] 			= "Invitation meeting";
				$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				$_notif['is_sending'] 		= 0;
				$_notif['is_deleted'] 		= 0;
				$_notif['created_at'] 		= $datetime;
				if($val['is_pic'] == 1){
					// pic
					$_notif['title'] 			= "Create a meeting schedule";
					$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				}
				array_push($notifcollectdata, $_notif);
			}
		}
		
		if($getBooking ['error'] == null){

			$datetime 					= date('Y-m-d H:i:s');
			$dataBooking = $getBooking['data'];
			// $dataBooking['date'] = $post['date'] == "" ? $dataBooking['date'] :  $post['date'];
			// $dataBooking['start'] = $post['start'] == "" ? $dataBooking['start'] :  $post['start'];
			// $dataBooking['end'] = $post['end'] == "" ? $dataBooking['end'] :  $post['end'];
			$startTime = strtotime($dataBooking['start']);
			$checkTime = strtotime($dataBooking['end']);
			$extended_duration = $dataBooking["extended_duration"];
			$duration = round(abs($checkTime - $startTime) / 60,2) + $extended_duration; // get minute of duration
			$fHour 						= $dataSettingGeneral['duration'];
			// $duration					= $duration;
			if($modules['price']['is_enabled'] == 1 ){
				$cost						= $dataBooking['price']; // per hours
				$allduration 				= $extended_duration+ $duration;
				$getHoursMeeting 			= floor($allduration / $fHour);
				$checkHours 				= fmod($allduration,$fHour);
				if($checkHours > 0){
					$getHoursMeeting += 1;
				}
			}
			

			$booking_id = $dataBooking['booking_id'];
			$tanggal_meeting = $dataBooking['date'];
			$waktu_timestart = $dataBooking['start'];
			$waktu_timeend = $dataBooking['end'];
			$waktu_mulai = $waktu_timestart;
			$ruangan = $dataBooking['room_id'];
			if($isMerge == true){
				$tempMergeRoomNameRaw = $dataBooking['merge_room'];
				$tempMergeRoomName = json_decode($dataBooking['merge_room'],true);
				foreach ($tempMergeRoomName  as $mk => $mv) {
					$ruangan_m_id = $mv['radid'];
					$this->Model_Admin->checkKondisiBookingPerRuanganRes($ruangan_m_id,$tanggal_meeting, $waktu_mulai,$waktu_timeend, $booking_id);
				}
			}else{
				$this->Model_Admin->checkKondisiBookingPerRuanganRes($ruangan,$tanggal_meeting, $waktu_mulai,$waktu_timeend, $booking_id);
			}

			// =====================================================
			$reservation_cost 			= 0;

			if($modules['invoice']['is_enabled'] == 1  && $modules['price']['is_enabled'] == 1 ){
				$booking_id_invoice = $dataBooking['booking_id'];
				$reservation_cost 			= $cost	* $getHoursMeeting;
				$winvoice 	= array("booking_id" =>$booking_id_invoice);
				$data_invoice = array(
					"rent_cost" => $reservation_cost,
					"alocation" => $dataBooking ['alocation_id'],
					"time_before" => $datetime,
					"updated_at" 	=> $datetime,
					"updated_by" 	=> $this->session->userdata('user-nya'),
				);
				$udata 				= $this->Model_Admin->updateData("booking_invoice", $data_invoice, $winvoice);
			}

			$where = array(
				 "booking_id"=>$dataBooking['booking_id']
			);
			$room_name = $dataBooking['room_name'];
			$tableEmail			= $this->Model_Admin->querySql($sqlEmail)->result_array();


			// MICROSOFT 365
			// MICROSOFT 365
			// MICROSOFT 365
			$module_int_365 = $this->Model_Module->get_module_int_365();
			$module_int_google = $this->Model_Module->get_module_int_google();
			// MICROSOFT 365
			$dataEmailInternal = [];
			$dataEmailEksternal = [];
			if(count($tableEmail) > 0){
				// $tableEmail		= $tableEmail[0];
				$batchemail 	= $tableEmail[0]['batch'];
				$dataToSend		= json_decode($batchemail,true);
				$dataEmailInternal = $dataToSend['internal'];
				$dataEmailEksternal = $dataToSend['eksternal'];
			}

			$room_365 = $room['config_microsoft'] == null ? "" : $room['config_microsoft'];
			$room_google = $room['config_google'] == null ? "" : $room['config_google'];
			if($module_int_365['is_enabled'] == 1 && $room_365 != "" ){
				$ms365 = $this->Model_License->get365Integration();
				$ck365 = $this->Model_License->check365Data();
				if($ck365 == true){
					$res_365 = $this->Model_License->createEvent365($dataBooking,$room,$ms365, $dataEmailInternal,$dataEmailEksternal,);
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
			if($module_int_google['is_enabled'] == 1 && $room_google != "" ){
				$data['booking_id_google'] = "";
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

			$dataBooking['is_alive'] = 1; 
			$dataBooking['is_approve'] = 1; 
			$dataBooking['is_expired'] = 0; 
			$dataBooking['user_approval'] = $dataupdate['user_approval']; 
			$dataBooking['user_approval_datetime'] = $dataupdate['datetime']; 

			
			// UPDATE BOOKING 
			$udata 				= $this->Model_Admin->updateData("booking", $dataBooking, $where);
			$this->Model_Notif->insertNotifAdmin(12, "Create meeting", $dataBooking['title']);
			$meeting_date = $dataBooking['date'];
			$explodeS = explode(" ", $dataBooking['start']);
			$explodeE = explode(" ", $dataBooking['end']);
			$meeting_start = $explodeS[1];
			$meeting_end = $explodeE[1];
			if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title = $dataBooking['title'];
				
				$notification_title = "Create Meeting of ".$meeting_title;
				$room_name = $room['name'];
				$notification_body = $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end) . " at " .$room_name;
				$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
			}	
			// $tableNotif			= $this->Model_Admin->querySql($sqlNotif)->result_array();
			
			if(count($tableEmail) > 0){
				$getBookingEmail 		= $this->Model_Api->getDataBookingById($booking_id); //
				if($getBookingEmail ['error'] != null){
					$response = response("fail", array(), "Data not exist ");
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
		       

		        if($modules['email']['is_enabled'] == 1 ){
		        	foreach ($dataToSend['internal'] as $key => $people) {
		            	$pNotif = $this->Model_Notif->sendEmailInternal("invitation", $emailBooking, $people, $dataPic);
			        }   
			        foreach ($dataToSend['eksternal'] as $key => $people) {
			            $pNotif = $this->Model_Notif->sendEmailExternal("invitation", $emailBooking, $people, $dataPic);
			        }   
			        $pNotif = $this->Model_Notif->sendEmailResponseApproval( $emailBooking, $dataPic, 1);	
		        }
			}
			
			return;
		}else{
			return;
			
		}
	}

}
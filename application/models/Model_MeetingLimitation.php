<?php  
date_default_timezone_set("Asia/Jakarta");
class Model_MeetingLimitation extends CI_Model {
	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Admin');
		// $this->load->helper('response');
		
	}
	public function checkMeeting($start, $end){
		$time1 						= new DateTime($start);
		$time2 						= new DateTime($end);
		$timediff 					= $time2->diff($time1);
		$duration_hours 			= $timediff->h*60;
		$duration_minute			= $timediff->i;
		$duration 					= $duration_hours+$duration_minute;

		// if($duration > 120){
		// 	$response = response("fail", array(), "Meeting room reservation time is limited to 120 minutes or 2 hours");
		// 	echo $response;
		// 	die();
		// }
	}
	public function adjustAdvanceMeeting($databooking, $dataroom ){
		$modules['vip'] = $this->Model_Module->get_module_vip();
		$modules['room_adv'] = $this->Model_Module->get_module_room_adv();
		$modules_room_adv_enabled = $modules['room_adv']['is_enabled']-0;
		if($modules_room_adv_enabled == 0){
			$databooking['is_config_setting_enable'] = 0;
			$databooking['is_enable_approval'] = 0;
			$databooking['is_enable_permission'] = 0;
			$databooking['is_enable_recurring'] = 0;
			$databooking['is_enable_checkin'] = 0;
		}

		$databooking['is_config_setting_enable'] = isset($dataroom['is_config_setting_enable']) ? $dataroom['is_config_setting_enable']-0  : 0;
		$databooking['is_enable_approval'] = isset($dataroom['is_enable_approval']) ? $dataroom['is_enable_approval']-0  : 0;
		$databooking['is_enable_permission'] = isset($dataroom['is_enable_permission']) ? $dataroom['is_enable_permission']-0  : 0;
		$databooking['is_enable_recurring'] = isset($dataroom['is_enable_recurring']) ? $dataroom['is_enable_recurring']-0  : 0;
		$databooking['is_enable_checkin'] = isset($dataroom['is_enable_checkin']) ? $dataroom['is_enable_checkin']-0  : 0;
		$databooking['is_realease_checkin_timeout'] = isset($dataroom['is_realease_checkin_timeout']) ? $dataroom['is_realease_checkin_timeout']-0  : 0;
		$databooking['is_enable_checkin_count'] = isset($dataroom['is_enable_checkin_count']) ? $dataroom['is_enable_checkin_count']-0  : 0;

		return $databooking;
	}
	public function checkBatchMeetingHaveVipAccess($databooking ){
		$list_bookingId = [];
		foreach ($databooking as $key => $v) {
			array_push($list_bookingId,  $v['booking_id']);
		}
		$norestrict = false;
		if(count($list_bookingId) <= 0){
			$norestrict = false;
			return $norestrict;
		}
		$dataBookingVip		= $this->Model_Admin->ckMeetingVipAccess($list_bookingId);
		if($dataBookingVip['error'] == null){
			foreach ($dataBookingVip['data'] as $key => $vvip) {
				if($vvip['vip_lock_room'] == 1){
					$norestrict = true;
					break;
				}
			}
		}else{
			$norestrict = true;
		}
		return $norestrict;
	}
	public function checkMeetingVipAccess($databooking, $dataroom ){
		$modules['vip'] = $this->Model_Module->get_module_vip();
		$modules['room_adv'] = $this->Model_Module->get_module_room_adv();
		$modules_room_adv_enabled = $modules['room_adv']['is_enabled']-0;
		$modules_vip_enabled = $modules['vip']['is_enabled']-0;
		$vip_user 	= $databooking['vip_user'] ;
		$resVIP		= $this->Model_Admin->getEditEmployeeByID($vip_user);
		if(isset($resVIP['data']['id']) == false){
			$databooking['is_vip'] = 0;
			$databooking['vip_user'] = '';
			return $databooking;
		}
		$dataVIP 	= $resVIP['data'];
		if(
			$modules_room_adv_enabled == 0 || $modules_vip_enabled == 0 || ($dataroom['is_config_setting_enable']-0) == 0 || $databooking['is_vip'] == 0 ) {
			$databooking['is_vip'] = 0;
			$databooking['vip_user'] = '';
			return $databooking;
			// vip acceess not correct
		}
		$databooking['vip_approve_bypass'] 		= $dataVIP['vip_approve_bypass'] - 0;
		$databooking['vip_limit_cap_bypass'] 	= $dataVIP['vip_limit_cap_bypass'] - 0;
		$databooking['vip_lock_room'] 			= $dataVIP['vip_lock_room'] - 0;
		$databooking['vip_user']				= $vip_user;
		return $databooking;
	}

	public function checkApprovalMeetingAccess($databooking, $dataroom ){
		$modules['vip'] 		= $this->Model_Module->get_module_vip();
		$modules['room_adv']	= $this->Model_Module->get_module_room_adv();
		$modules_room_adv_enabled = $modules['room_adv']['is_enabled']-0;
		$modules_vip_enabled = $modules['vip']['is_enabled']-0;
		$vip_user 	= $databooking['vip_user'] ;
		$resVIP		= $this->Model_Admin->getEditEmployeeByID($vip_user);
		if( ($dataroom['is_config_setting_enable']-0) == 0 || ($dataroom['is_enable_approval']-0) == 0 ){
			$databooking['is_approve'] = 3;
			$databooking['user_approval'] = '';
			return $databooking;
		}
		$dataVIP 	= $resVIP['data'];
		if( $modules_vip_enabled == 0 || $databooking['is_vip'] == 0 ) {
			$databooking['is_approve'] = 0;
			$databooking['is_alive'] = 0;
			$databooking['user_approval'] = '';
			return $databooking;
		}
		if($databooking['vip_approve_bypass'] == 0 ) {
			$databooking['is_approve'] = 0;
			$databooking['is_alive'] = 0;
			$databooking['user_approval'] = '';
			return $databooking;
		}
		$databooking['is_approve'] = 1;
		$databooking['user_approval'] = $vip_user ;
		return $databooking;

	}

	public function processMovedMeeting($databooking, $vip_user ){
		$modules['email'] = $this->Model_Module->get_module_email();
		$modules_email_enabled = $modules['email']['is_enabled']-0;
		$dataMoved = [
			'is_alive' => 0,
			'is_moved' => 1,
			'is_moved_agree' => 0,
			'moved_duration' => 5,
			'vip_force_moved' => $vip_user['nik'],
		];
		$table = "booking";
		foreach ($databooking as $key => $v) {
			$wh = [
				'booking_id' => $v['booking_id'],
			];
			$this->Model_Admin->updateData($table,$dataMoved,$wh);
		}
		if($modules_email_enabled == 1){
			foreach ($databooking as $key => $value) {
				$emailBooking = $value;
				$meeting_title = $emailBooking['title'];
				$meeting_date = $emailBooking['date'];
				$meeting_start =date("H:i:s" ,strtotime($meeting_date." ".$emailBooking['start']));
				$meeting_end = date("H:i:s" ,strtotime($meeting_date." ".$emailBooking['end']));
				$pic = [
					'name' => $value['pic_name'],
					'email' => $value['pic_email'],
					'nik' => $value['pic_nik'],
					'is_vip' => $value['pic_vip'],
				];
				$emailBooking['format_time_start'] = $this->Model_Admin->formatTime($meeting_start);
				$emailBooking['format_time_end'] = $this->Model_Admin->formatTime($meeting_end);
				$emailBooking['format_date'] = $this->Model_Admin->formatDate($meeting_date);
				$this->Model_Notif->sendEmailForceMovedMeeting($emailBooking, $pic,  $vip_user['name']);
			}
		}
	}


}






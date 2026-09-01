<?php  

date_default_timezone_set(APP_GMT);
class Partisipant extends CI_Controller {
	public function __construct(){
		parent::__construct();

		// $this->load->model('Model_Menu');
		$this->load->model('Model_Api');
		$this->load->model('Model_Admin');
		$this->load->model('Model_License');
		$this->load->model('Model_Approval');

		$this->load->helper('response');
		$this->load->helper('string');
		

	}
	public function index(){
		
	}
	public function internal(){
		$booking_id = $this->uri->segment(4);
		$nik = $this->uri->segment(6);
		$attendance = $this->uri->segment(8);
		$getAtt = $this->Model_Admin->getDataAttendanceInvitationInternal($booking_id, $nik);

		if($getAtt['error'] == null){
			if($getAtt['data']['execute_attendance'] == 1){
				// echo "!";
				$dataD =  json_encode($getAtt['data']);
				$text_title = "Attend the Meeting";
				$text_msg = "Thank you for confirming the presence";
				$attend = $getAtt['data']['attendance_status'] == 1 ? "Attend" : "Not Attend";
				$this->load->view('Admin/Partisipant/internal', array("text_msg" => $text_msg, "text_title" => $text_title , "data"=>$dataD, "attend" => $attend ));
				// die();
			}
		}
		// die();
		if($attendance == 1 || $attendance == "1"){
			$getData = $this->Model_Admin->getDataAttendanceInvitationInternal($booking_id, $nik);
			if($getData['error'] == null){
				if($getData['data']['execute_attendance'] != 1){
					$update = array(
						"attendance_status" => 1,
						"execute_attendance" => 1,
					);
					$wh = array(
						"booking_id" => $booking_id,
						"nik" => $nik,
					);
					$updateModel = $this->Model_Admin->updateData('booking_invitation', $update, $wh);
				}
				
				$dataD =  json_encode($getData['data']);
				$text_title = "Attend the Meeting";
				$text_msg = "Thank you for confirming the presence";
				$attend = "Attend";
			}else{
				$dataD = "";
				$text_title = "";
				$text_msg = "";
				$attend = "";
			}
			
			$this->load->view('Admin/Partisipant/internal', array("text_msg" => $text_msg, "text_title" => $text_title , "data"=>$dataD, "attend" => $attend ));
		}else if($attendance == 0 || $attendance == "0"){
			$getData = $this->Model_Admin->getDataAttendanceInvitationInternal($booking_id, $nik);
			if($getData['error'] == null){
				if($getData['data']['execute_attendance'] != 1){
					$update = array(
						"attendance_status" => 0,
						"execute_attendance" => 1,
					);
					$wh = array(
						"booking_id" => $booking_id,
						"nik" => $nik,
					);
					$updateModel = $this->Model_Admin->updateData('booking_invitation', $update, $wh);
				}
				
				$dataD =  json_encode($getData['data']);
				$text_title = "Attend the Meeting";
				$text_msg = "Thank you for confirming the presence";
				$attend = "Not Attend";
			}else{
				$dataD = "";
				$text_title = "";
				$text_msg = "";
				$attend = "";
			}
			
			$this->load->view('Admin/Partisipant/internal', array("text_msg" => $text_msg, "text_title" => $text_title , "data"=>$dataD, "attend" => $attend ));
		}else{

		}
		
	}
	public function eksternal(){
		$booking_id = $this->uri->segment(4);
		$email 		= $this->uri->segment(6);
		$attendance = $this->uri->segment(8);
		$getAtt 	= $this->Model_Admin->getDataAttendanceInvitationEksternal($booking_id, $email);
		
		if($getAtt['error'] == null){
			if($getAtt['data']['execute_attendance'] == 1){
				$dataD =  json_encode($getAtt['data']);
				$text_title = "Attend the Meeting";
				$text_msg = "Thank you for confirming the presence";
				$attend = $getAtt['data']['attendance_status'] == 1 ? "Attend" : "Not Attend";
				$this->load->view('Admin/Partisipant/eksternal', array("text_msg" => $text_msg, "text_title" => $text_title , "data"=>$dataD, "attend" => $attend ));
			}
		}
		// die();
		if($attendance == 1 || $attendance == "1"){
			$getData = $this->Model_Admin->getDataAttendanceInvitationEksternal($booking_id, $email);
			if($getData['error'] == null){
				if($getData['data']['execute_attendance'] != 1){
					$update = array(
						"attendance_status" => 1,
						"execute_attendance" => 1,
					);
					$wh = array(
						"booking_id" => $booking_id,
						"email" => $email,
					);
					$updateModel = $this->Model_Admin->updateData('booking_invitation', $update, $wh);
				}
				
				$dataD =  json_encode($getData['data']);
				$text_title = "Attend the Meeting";
				$text_msg = "Thank you for confirming the presence";
				$attend = "Attend";
			}else{
				$dataD = "";
				$text_title = "";
				$text_msg = "";
				$attend = "";
			}
			
			$this->load->view('Admin/Partisipant/eksternal', array("text_msg" => $text_msg, "text_title" => $text_title , "data"=>$dataD, "attend" => $attend ));
		}else if($attendance == 0 || $attendance == "0"){
			$getData = $this->Model_Admin->getDataAttendanceInvitationEksternal($booking_id, $email);
			if($getData['error'] == null){
				if($getData['data']['execute_attendance'] != 1){
					$update = array(
						"attendance_status" => 0,
						"execute_attendance" => 1,
					);
					$wh = array(
						"booking_id" => $booking_id,
						"email" => $email,
					);
					$updateModel = $this->Model_Admin->updateData('booking_invitation', $update, $wh);
				}
				
				$dataD =  json_encode($getData['data']);
				$text_title = "Attend the Meeting";
				$text_msg = "Thank you for confirming the presence";
				$attend = "Not Attend";
			}else{
				$dataD = "";
				$text_title = "";
				$text_msg = "";
				$attend = "";
			}
			
			$this->load->view('Admin/Partisipant/eksternal', array("text_msg" => $text_msg, "text_title" => $text_title , "data"=>$dataD, "attend" => $attend ));
		}else{

		}
		
	}
	public function setReasonInternal(){
		$post = $_POST;
		$update = array(
			"attendance_reason" => $post['reason'],
			"execute_attendance" => 1,
		);
		$wh = array(
			"booking_id" => $post['booking_id'],
			"nik" => $post['nik'],
		);
		$updateModel = $this->Model_Admin->updateData('booking_invitation', $update, $wh);
		$response = response("success", array() , "");
		echo $response;
	}

	public function meetingApprove(){
		$get = $_GET;
		if(!isset($get['token'])){
			die();
		}
		$text_title = "";
		$text_msg = "";
		$img_warning = "warning.png";
		$img_reject = "reject.png";
		$img_approve = "approve.png";

		// $data = '{"booking_id":"3052786941","user_id":"admin","approve":"0","timezone":"Asia/Jakarta"}';
		// $e = encryp_aes($data);
		$token = $get['token'];
		$stringtoken =  decryp_aes($token);
		$decoder = [];
		$er = false;
		try{
			$decoder = json_decode($stringtoken, true);
		}catch(Exeption $error){
			$er = true;

		}
		if($er){
			$text_title = "";
			$text_msg = "Token has expired. Please contact the administrator. ";
			echo $this->load->view('Admin/Partisipant/approval', 
				array('image' =>$img_warning ,"text_msg" => $text_msg, "text_title" => $text_title,), TRUE);
			die();
		}
		if(
			!isset($decoder['booking_id']) 
			|| !isset($decoder['user_id']) 
			|| !isset($decoder['approve']) 
			|| !isset($decoder['timezone']) 
		){
			$text_title = "";
			$text_msg = "Token has expired. Please contact the administrator. ";
			echo $this->load->view('Admin/Partisipant/approval', 
				array('image' =>$img_warning ,"text_msg" => $text_msg, "text_title" => $text_title,), TRUE);
			die();
		}
		$timezone = APP_GMT;
		date_default_timezone_set(APP_GMT);
		if(isset($decoder['timezone'])){
			if($decoder['timezone'] != ""){
				$timezone = $decoder['timezone'];
				date_default_timezone_set($timezone);
			}
		}else{
			date_default_timezone_set(APP_GMT);
		}
		$decoder['timezone'] = $timezone ;
		// print_r($decoder);
		// die();
		$tz = $decoder['timezone'];
		$servertime = date('Y-m-d H:i:s');
		$now_datetime = new DateTime($servertime);
		$meeting_timezone = new DateTimeZone($tz);
		$nowtimetz = $now_datetime->setTimezone($meeting_timezone);
		$nowtimetzfrm = $nowtimetz->format('Y-m-d H:i:s');
		$booking_id = $decoder['booking_id'];

		$fetchdata = $this->Model_Api->getDataBookingById($booking_id);
		$databooking = $fetchdata['data'];
		if(!isset($databooking['booking_id'])){
			$text_title = "";
			$text_msg = "The meeting has expired. Please contact the administrator. ";
			echo $this->load->view('Admin/Partisipant/approval', 
				array('image' =>$img_warning ,"text_msg" => $text_msg, "text_title" => $text_title,), TRUE);
			die();
		}

		if(($databooking['is_approve'] - 0) != 0){
			
			if( ($decoder['approve'] - 0) == 1 ){
				$text_title  = "Approval Failed";
			}else{
				$text_title  = "Reject Failed";
			}
			
			$text_msg = "The meeting has expired. ";
			if(($databooking['is_approve']-0) == 1){
				$text_msg = "The meeting room request has been approved";
			}else if(($databooking['is_approve']-0) == 2){
				$text_msg = " The meeting room request has been rejected.";
			}
			echo $this->load->view('Admin/Partisipant/approval', 
				array('image' =>$img_warning ,"text_msg" => $text_msg, "text_title" => $text_title,), TRUE);
			die();
		}

		$start_meeting = $databooking['start'];
		// $start_meeting = '2023-11-01 17:00:00';
		$end_meeting = $databooking['end'];
		date_default_timezone_set($tz);
		$st_datetime = strtotime($start_meeting);
		$en_datetime = strtotime($end_meeting);
		$noww_datetime = strtotime($nowtimetzfrm);

		$difference_s  = $noww_datetime - $st_datetime; 
		$difference_e  = $noww_datetime - $en_datetime; 
		$diff_minute_fromstart = floor($difference_s / (60));
		$diff_minute_fromend = floor($difference_e / (60));
		// echo $databooking['booking_id']." ". $nowtimetzfrm."----".$start_meeting." ".$diff_minute_fromstart." ".$tz;
		// echo $databooking['booking_id']." ". "<hr>";
		// echo $databooking['booking_id']." ". $nowtimetzfrm."----".$end_meeting." ".$diff_minute_fromend." ".$tz;
		if($diff_minute_fromstart > 5 || $diff_minute_fromend > 5){

			$text_title = "";
			$text_msg = "The meeting has expired. Please contact the administrator. ";
			echo $this->load->view('Admin/Partisipant/approval', 
				array('image' =>$img_warning ,"text_msg" => $text_msg, "text_title" => $text_title,), TRUE);

			die();
		}
		if($databooking['is_alive'] == 4 || $databooking['is_expired']==1 || $databooking['end_early_meeting'] == 1  ){
			$text_title = "";
			$text_msg = "The meeting has expired. Please contact the administrator. ";
			echo $this->load->view('Admin/Partisipant/approval', 
				array('image' =>$img_warning ,"text_msg" => $text_msg, "text_title" => $text_title,),TRUE);
			die();
		}
		if($databooking['is_canceled'] == 1  ){
			$text_title = "";
			$text_msg = "The meeting has expired. Please contact the administrator. ";
			echo $this->load->view('Admin/Partisipant/approval', 
				array('image' =>$img_warning ,"text_msg" => $text_msg, "text_title" => $text_title,),TRUE);
			die();
		}
		$approval_data = $decoder;
		$update = array(
			"is_alive" => 0,
			"is_approve" => 0,
			"datetime" => $nowtimetzfrm,
		);
		if( ($approval_data['approve'] - 0) == 1){
			$update['is_alive'] = 1;
			$update['is_approve'] = 1;
			$update['is_expired'] = 0;
			$update['user_approval'] = $approval_data['user_id'];

			$img_warning = 'image_people.png';
			$text_title = "Request Meeting Approved";
			$text_msg = "The meeting room request has been approved.";
			$this->Model_Approval->postApprovalBook($booking_id,$update);

		}else{
			// reject 
			$img_warning = 'reject.png';
			$update['is_alive'] = 4;
			$update['is_approve'] = 2;
			$update['is_expired'] = 1;
			$update['user_approval'] = $approval_data['user_id'];

			$text_title = "Request Meeting Failed";
			$text_msg = "The meeting room request has been rejected.";
			$this->Model_Approval->postApprovalBookReject($booking_id,$update);

		}
		

		$this->load->view('Admin/Partisipant/approval', array('image' =>$img_warning ,"text_msg","text_msg" => $text_msg, "text_title" => $text_title ));
	}
}








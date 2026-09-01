<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Approval extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Api');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Api2');
		$this->load->model('Model_Notif');
		$this->load->model('Model_MeetingLimitation');
		$this->load->model('Model_License');
		$this->load->model('Model_Approval');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
	}


	public function getApproval()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// print_r($post);
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
				}else if($post['search']['status_search'] == "1"){
					$where .= " AND b.is_approve=1 AND b.is_enable_approval=1 ";

				}else if($post['search']['status_search'] == "2"){
					$where .= " AND b.is_approve=2 AND b.is_enable_approval=1 ";

				}else if($post['search']['status_search'] == "0"){
					$where .= " AND b.is_approve=0 AND b.is_enable_approval=1 ";
				}else{
					$where .= " ";
				}
			}


		}


		$getData = $this->Model_Approval->getApprovalMeeting($where, $post);
		$datalist = $getData['data'];
		// $rule = $this->Model_Api->getGeneralSetting()['data'];
		// foreach ($datalist as $key => $value) {
		// 	$datalist[$key]['before_meeting'] = $rule['notif_unuse_before_meeting'];
		// }
		if($getData['error'] == null ){
			$response = response("success", $datalist, "Success get data to schedule ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function meetingApprove(){
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$get = $_GET;
		
		$text_title = "";
		$text_msg = "";
		$img_warning = "warning.png";
		$img_reject = "reject.png";
		$img_approve = "approve.png";

		// $data = '{"booking_id":"3052786941","user_id":"admin","approve":"0","timezone":"Asia/Jakarta"}';
		// $e = encryp_aes($data);
		
		$er = false;

		if(
			!isset($post['booking_id']) 
			|| !isset($post['nik']) 
			|| !isset($post['approve']) 
			|| !isset($post['timezone']) 
		){
			$response = response("fail", array(), "Token has expired. ");
			echo $response;	
			die();
		}
		$post['user_id'] = $post['nik'];
		
		$tz = $post['timezone'];
		$servertime = date('Y-m-d H:i:s');
		$now_datetime = new DateTime($servertime);
		$meeting_timezone = new DateTimeZone($tz);
		$nowtimetz = $now_datetime->setTimezone($meeting_timezone);
		$nowtimetzfrm = $nowtimetz->format('Y-m-d H:i:s');
		$booking_id = $post['booking_id'];

		$fetchdata = $this->Model_Api->getDataBookingById($booking_id);
		$databooking = $fetchdata['data'];
		if(!isset($databooking['booking_id'])){
			$text_title = "";
			$text_msg = "The meeting has expired. ";
			$response = response("success", array(), $text_msg);
			echo $response;	
			die();
		}

		if(($databooking['is_approve'] - 0) != 0){
			$text_title = "";
			if( ($post['approve'] - 0) == 1 ){
				$txt = "Approval Failed";
			}else{
				$txt = "Reject Failed";
			}
			
			$text_msg = "The meeting has expired. ";
			if(($databooking['is_approve']-0) == 1){
				$text_msg = $txt.", The meeting room request has been approved";
			}else if(($databooking['is_approve']-0) == 2){
				$text_msg = $txt.",  The meeting room request has been rejected.";
			}
			$response = response("success", array(), $text_msg);
			echo $response;	
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
		
		if($diff_minute_fromstart > 5 || $diff_minute_fromend > 5){

			$text_title = "";
			$text_msg = "The meeting has expired. ";
			$response = response("success", array(), $text_msg);
			echo $response;	
			die();
		}


		if($databooking['is_alive'] == 4 || $databooking['is_expired']==1 || $databooking['end_early_meeting'] == 1  ){
			$text_title = "";
			$text_msg = "The meeting has expired. ";
			$response = response("success", array(), $text_msg);
			echo $response;	
			die();
		}
		if($databooking['is_canceled'] == 1  ){
			$text_title = "";
			$text_msg = "The meeting has expired.";
			$response = response("success", array(), $text_msg);
			echo $response;	
			die();
		}

		$approval_data = $post;
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
		$response = response("success", array(), $text_msg);
		echo $response;	

		
		


	}
}
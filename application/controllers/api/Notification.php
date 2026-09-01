<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Notification extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Api');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
		// if($this->session->userdata('logged-in')){
		// 	if($this->session->userdata('levelid-nya') != 1){
		// 		redirect('authentication');
		// 	}
		// }else{
		// 	redirect('authentication');
		// }
	}
	public function index()
	{
		
		$response = response("fail", array(), "Failed  ");
		echo $response;
		
	}
	public function getAllNotification()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		
		$getData = $this->Model_Api->getAllNotificationData($post['nik']);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to notification ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a notification ");
			echo $response ;
		}

		
	}
	public function deleteNotification()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// print_r($post);
		try{
			$u = array(
				'is_deleted' => 1
			);
			$w = array('id' => $post['notifId']);
			$getData = $this->Model_Api->updateData('notification_data',$u,$w );
			$response = response("success", array(), "Success deleted to notification ID".$post['notifId']."  ");
			echo $response ;
		}catch(Exception $error){
			$response = response("fail", $getData, "Failed error a notification ");
			echo $response ;

		}
		

		
	}
	
	
}

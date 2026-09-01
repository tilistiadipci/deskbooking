<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Users extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Api');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
		
	}
	public function getUsersBooking()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getUsersBooking($post['nik']);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to active ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Setting extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Api');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
		
	}
	public function getDataGeneral()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		if(isset($post['username']) && $post['username'] != ""){
			$getData = $this->Model_Api->getGeneralSetting($post);
			$username = $post['username'];
			// $postLog = $this->Model_Api->postApiLog($username, "post");
			if($getData['error'] == null ){
				$response = response("success", $getData['data'], "Success get data to room ");
				echo $response ;
			}else{
				$response = response("fail", $getData, "Failed something wrong");
				echo $response ;
			}
		}else{
			$response = response("fail", array(), "Failed restrict access");
			echo $response ;
		}
	}
	
}

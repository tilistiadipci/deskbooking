<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Room extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Api');
		$this->load->model('Model_Api2');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
		
	}
	public function getBuildingList()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		if(isset($post['username']) && $post['username'] != ""){

			$getData = $this->Model_Api2->getDataBuilding();
			if($getData['error'] == null ){
				$response = response("success", $getData['data'], "Success get data to building ");
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
	public function getRoomList()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		if(isset($post['username']) && $post['username'] != ""){
			$getData = $this->Model_Api->getRoomList($post);
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

	public function getMergeRoomList()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		if(isset($post['username']) && $post['username'] != "" && isset($post['room_id']) ){

			$getData = $this->Model_Api2->getMergeRoomList($post);
			$username = $post['username'];
			// $postLog = $this->Model_Api->postApiLog($username, "post");
			if($getData['error'] == null ){
				$response = response("success", $getData['data'], "Success get data to merge room ");
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
	public function getFacilityList()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		if(isset($post['username']) && $post['username'] != ""  ){

			$getData = $this->Model_Api2->getRoomFacilityList($post);
			$username = $post['username'];
			if($getData['error'] == null ){
				$response = response("success", $getData['data'], "Success get data to faicility room ");
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
	public function getRoomId()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		if(isset($post['username']) && $post['username'] != ""){
			$w = array('radid'=>$post["radid"]);
			$getData = $this->Model_Api->getRoomId($w);
			
			// $postLog = $this->Model_Api->postApiLog($username, "post");
			if($getData['error'] == null ){
				if(count($getData['data']) > 0){
					$row = $getData['data'][0];
					$response = response("success", $row, "Success get data to room ");
					echo $response ;
				}else{
					$response = response("fail", $getData, "Room not exist");
					echo $response ;
				}
				
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

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BliveRoom extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Api');
		$this->load->helper('response');
		

		// $this->load->model('', '', true);
	}	
	public function checkRoom()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$where = array(
				"is_deleted" => 0,
				"radid" => $post['serial']
			);
			$select = "*, radid as serial";
			$getData = $this->Model_Api->select_sql("room",$select,$where);
			$cn = $getData->num_rows();
			// print_r($cn);
			// if
			if($cn >= 1){
				$dataRes =  $getData->row_array();
				
				$response = response("success", $dataRes, "Success get data to room ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data1',
				);
				$response = response("fail", $rr, "Room not exist");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a room ");
			echo $response ;
		}
	}
	
}

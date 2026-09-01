<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BliveHelpdesk extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Api');
		$this->load->model('Model_Admin');
		$this->load->helper('response');
		

		// $this->load->model('', '', true);
	}	
	public function ButtonHelpdesk()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// print_r($post);
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
				$datetime = date("Y-m-d H:i:s", strtotime($post['date']));
				$genid = gen_uuid();
				$insert = array(
					'id' => $genid,
					'datetime' => $datetime,
					'room_id' => $dataRes['radid'],
					'action' => 1,
					'response' => 0,
					'is_deleted'=>0

				);
				$cb = array(
					"request_id" => $genid,
					'action' => 1,
					'response' => 0,
				);
				// $post['updated_at'] =  ;
				$resp = $this->Model_Admin->insertData('helpdesk_monitor', $insert);
				$response = response("success", $cb, "Success submit monitor ");
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
	public function viewHelpdesk()
	{
		$this->load->view('Blive/helpdesk_monitor', array());


	}
	public function getDataHelpdesk()
	{
		$get = $_GET;
		$start = $get['start'];
		$end = $get['end'];
		$data = $this->Model_Admin->getBliveHelpgdeskMonitor($start, $end);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getDataMobileHelpdesk()
	{
		$get = $_GET;
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$start =date("Y-m-d");
		$end = date("Y-m-d");
		$serial = $post['serial'];
		$data = $this->Model_Admin->getBliveHelpdeskMobile($start, $end, $serial);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getDataHelpdeskDetail()
	{
		$get = $_GET;
		$id = $get['id'];
		if($id == null || $id == ""){
			echo response("fail", array(), "Get failed");
		}else{
			$w = array(
				"hm.id" => $id 
			);
			$data = $this->Model_Admin->getBliveHelpgdeskMonitorDetail($w);
			if($data['error'] == null){
				if(count($data['data']) > 0){
					echo response("success", $data['data'][0], "Get success");
				}else{
					echo response("fail", $data, "Data not exist");
				}
			}else{
				echo response("fail", $data, "Get failed");
			}
		}
		
	}

	public function getDataHelpdeskSubmit()
	{
		$post = $_POST;
		$id = $post['id'];
		unset($post['id']);
		$wh = array(
			'id'=>$id
		);
		if( $post['response'] == 2){
			$post['action'] = 0;
		}else if($post['response'] == 0){
			$post['action'] = 1;

		}else if($post['response'] == 1){
			$post['action'] = 1;

		}

		$resp = $this->Model_Admin->updateData('helpdesk_monitor', $post, $wh);
		if($resp){
	    	$response = response("success", array(), "Success update a helpdesk monitor ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a helpdesk monitor ");
			echo $response;
		}
		
	}
	public function buttonHelpdeskGate()
	{
		$action = $this->uri->segment(4);
		if($action == "" || $action == "off"){
			$url = "http://192.168.0.127/gpio1/?value=0";
			$this->curlGET(array(), $url);
		}else{
			$url = "http://192.168.0.127/gpio1/?value=100";
			// $this->curlGET(array(), $url);
			// $url111 = "http://192.168.0.127/CT_1/?value=1";
			$this->curlGET(array(), $url);
			// $this->curlGET(array(), $url111);
		}
		$response = response("success", array("gate"=>$action), "Success open a helpdesk monitor ");
		echo $response;
		// $this->load->view('Blive/helpdesk_monitor', array());


	}
	public function curlGET($header = array(), $url=""){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt($ch, CURLOPT_FAILONERROR, 1 );
			$rest = curl_exec($ch);
			if (curl_errno($ch)) {
			    $error_msg = curl_error($ch);
			}
			curl_close($ch); 

			if (isset($error_msg)) {
				return $error_msg;
			}else{
				return $rest;
			}
	}
	
}

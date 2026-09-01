<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Pantrydisplay extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Api');
		$this->load->model('Model_Api2');
		$this->load->model('Model_Access');
		$this->load->helper('response');
		
	}
	// ==========================================
	// ==========================================
	// ==========================================
	// ==========================================

	public function getlistpantry()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$access = $this->Model_Access->getAccessPantrybooking($post['username']);
		if($access == false){
			$response = response("fail", array(), "Failed, your access is restricted ");
			echo $response;
			die();
		}
		try{
			$getData = $this->Model_Api2->getDisplayPantry();
			if($getData ['error']== null){
				
				$response = response("success", $getData['data'], "Success get data to pantry ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry");
			echo $response ;
		}
	}

	public function getorderentry()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		if(!isset($post['date'])){
			$post['date'] = date('Y-m-d');
		}
		$access = $this->Model_Access->getAccessPantrybooking($post['username']);
		if($access == false){
			$response = response("fail", array(), "Failed, your access is restricted ");
			echo $response;
			die();
		}
		try{
			$getData = $this->Model_Api->getOrderEntry($post);
			if($getData ['error']== null){
				$col = $getData['data'];
				foreach ($col as $k => $value) {
					$p = array();
					$p['id'] = $value['transaksi_id'];
					$coldetail = $this->Model_Api->getDetailPantry($p);
					$getData['data'][$k]['detail'] = $coldetail['data'] ;
				}
				$response = response("success", $getData['data'], "Success get data to pantry Entry ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry Entry");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry Entry");
			echo $response ;
		}
	}
	public function getorderprocess()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$access = $this->Model_Access->getAccessPantrybooking($post['username']);
		if($access == false){
			$response = response("fail", array(), "Failed, your access is restricted ");
			echo $response;
			die();
		}
		try{
			$getData = $this->Model_Api->getOrderProcess($post);
			if($getData ['error']== null){
				$col = $getData['data'];
				foreach ($col as $k => $value) {
					$p = array();
					$p['id'] = $value['transaksi_id'];
					$coldetail = $this->Model_Api->getDetailPantry($p);
					$getData['data'][$k]['detail'] = $coldetail['data'] ;
				}
				$response = response("success", $getData['data'], "Success get data to pantry Process ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry Entry");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry Entry");
			echo $response ;
		}
	}
	public function getordercomplete()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$access = $this->Model_Access->getAccessPantrybooking($post['username']);
		if($access == false){
			$response = response("fail", array(), "Failed, your access is restricted ");
			echo $response;
			die();
		}
		try{
			$getData = $this->Model_Api->getordercomplete($post);
			if($getData ['error']== null){
				$col = $getData['data'];
				foreach ($col as $k => $value) {
					$p = array();
					$p['id'] = $value['transaksi_id'];
					$coldetail = $this->Model_Api->getDetailPantry($p);
					$getData['data'][$k]['detail'] = $coldetail['data'] ;
				}
				$response = response("success", $getData['data'], "Success get data to pantry Complete ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry Entry");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry Entry");
			echo $response ;
		}
	}

	public function pushprocess()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$access = $this->Model_Access->getAccessPantrybooking($post['username']);
		if($access == false){
			$response = response("fail", array(), "Failed, your access is restricted ");
			echo $response;
			die();
		}
		try{
			$datetime = date('Y-m-d H:i:s');
			$pantry_trs_status = $this->Model_Api->select_all_data('pantry_transaksi_status', array('id'=>1), array(), 'row');
			$username = $post['username'];
			$data = array(
				"order_st" =>1,
				"order_st_name" =>$pantry_trs_status ['name'],
				"process" => 1,
				"complete" => 0,
				"failed" => 0,
				"done" => 0,
				"process_at" => $datetime,
				"process_by" => $username ,
				"is_rejected_pantry" => 0
			);
			$where  = array(
				"id" => $post['transaksi_id']
			);
			$getData = $this->Model_Api->updateData("pantry_transaksi", $data, $where);
			$response = response("success", $getData, "Success push pantry Entry to process ");
			echo $response ;
			
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry Entry");
			echo $response ;
		}
	}
	public function pushrejectorder()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$access = $this->Model_Access->getAccessPantrybooking($post['username']);
		if($access == false){
			$response = response("fail", array(), "Failed, your access is restricted ");
			echo $response;
			die();
		}
		try{
			$datetime = date('Y-m-d H:i:s');
			$username = $post['username'];
			$note_reject = isset($post['note_reject']) ? $post['note_reject'] : "";
			$pantry_trs_status = $this->Model_Api->select_all_data('pantry_transaksi_status', array('id'=>5), array(), 'row');
			$data = array(
				"order_st" =>5,
				"note_reject" =>$note_reject,
				"rejected_pantry_by" =>isset($post['pantry_id']) ? $post['pantry_id'] : "",
				"order_st_name" =>$pantry_trs_status ['name'],
				"process" => 0,
				"complete" => 0,
				"failed" => 0,
				"done" => 0,
				"rejected_at" => $datetime,
				"rejected_by" => $username ,
				"is_rejected_pantry" => 1,
			);
			$where  = array(
				"id" => $post['transaksi_id']
			);
			$getData = $this->Model_Api->updateData("pantry_transaksi", $data, $where);
			$response = response("success", $getData, "Success push pantry process to reject ");
			echo $response ;
			
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry process");
			echo $response ;
		}
	}
	public function pushcomplete()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$access = $this->Model_Access->getAccessPantrybooking($post['username']);
		if($access == false){
			$response = response("fail", array(), "Failed, your access is restricted ");
			echo $response;
			die();
		}
		try{
			$datetime = date('Y-m-d H:i:s');
			$username = $post['username'];
			$pantry_trs_status = $this->Model_Api->select_all_data('pantry_transaksi_status', array('id'=>3), array(), 'row');
			$data = array(
				"order_st" =>3,
				"order_st_name" =>$pantry_trs_status ['name'],
				"process" => 0,
				"complete" => 1,
				"failed" => 0,
				"done" => 0,
				"completed_at" => $datetime,
				"completed_by" => $username ,
				"completed_pantry_by" => isset($post['pantry_id']) ? $post['pantry_id'] : "",
				"is_rejected_pantry" => 0,
				"is_trashpantry" => 0,
			);
			$where  = array(
				"id" => $post['transaksi_id']
			);
			$getData = $this->Model_Api->updateData("pantry_transaksi", $data, $where);
			$response = response("success", $getData, "Success push pantry process to complete ");
			echo $response ;
			
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry Process");
			echo $response ;
		}
	}
	public function pushremove()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$access = $this->Model_Access->getAccessPantrybooking($post['username']);
		if($access == false){
			$response = response("fail", array(), "Failed, your access is restricted ");
			echo $response;
			die();
		}
		try{
			$data = array(
				"is_trashpantry" => 1,
			);
			$where  = array(
				"id" => $post['transaksi_id']
			);
			
			$getData = $this->Model_Api->updateData("pantry_transaksi", $data, $where);
			$response = response("success", $getData, "Success remove data to pantry List ");
			echo $response ;
			
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry Entry");
			echo $response ;
		}
	}

	public function pushrejectitem()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$access = $this->Model_Access->getAccessPantrybooking($post['username']);
		if($access == false){
			$response = response("fail", array(), "Failed, your access is restricted ");
			echo $response;
			die();
		}
		try{
			$data = array(
				"is_rejected" => 1,
				"note_reject" => $post['note_reject'],
			);
			$where  = array(
				"id" => $post['item_id']
			);
			$getData = $this->Model_Api->updateData("pantry_transaksi_d", $data, $where);
			if($getData ['error']== null){
				$response = response("success", $getData['data'], "Success get data to pantry Entry ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry Entry");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry Entry");
			echo $response ;
		}
	}

	
	
}

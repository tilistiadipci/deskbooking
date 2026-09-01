<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class Facility extends CI_Controller {

	
	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
		$this->load->helper('response');
		if($this->session->userdata('logged-in')){
			if($this->session->userdata('levelid-nya') == 1){
				// redirect('authentication');
			}else if($this->session->userdata('levelid-nya') == 2){
				// 
			}else{
				redirect('authentication');

			}
		}else{
			redirect('authentication');
		}
	}
	public function index()
	{
		$pagename = "Facility";
		$menu = $this->Model_Menu->getMenu($pagename);
		$this->load->view('Admin/Facility/index', array('menumaster'=> $menu, 'pagename' => $pagename));
		
		
	}
	public function getData()
	{
		$data = $this->Model_Admin->getDataFacility();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	
	public function postCreate()
	{
		$post = $_POST;
		$datetime = date("Y-m-d H:i:s");
		$post['created_at'] = $datetime ;
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] =0;
		if(!isset($post['google_icon'])){
			unset($post['google_icon']);
		}
		$resp = $this->Model_Admin->insertData('facility', $post);
		if($resp){
			record_activity('FACILITY_CREATED', [
				'description' => "Admin created facility: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'success'
			]);
	    	$response = response("success", array(), "Success create a facility ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a facility ".$post['name']);
			echo $response;
		}
	}
	public function getDataDetail(){
		$post = $_POST;
		$id = $this->uri->segment(5);
		if(!isset($id)){
			$response = response("fail", array(), "Parameter not complete ");
			echo $response;
			die();
		}
		$sql = $this->Model_Admin->querySql("
			SELECT * FROM facility 
			WHERE id=".$id." AND is_deleted=0
			");

		$res = $sql->result_array();
		if(count($res)<=0){
			$response = response("fail", array("id" => $id), "data not found ");
			echo $response;
			die();
		}
		echo response("success", $res[0], "Get success");
		
	}
	public function postUpdate()
	{
		$post = $_POST;
		$id = $this->uri->segment(4);
		$wh = array(
			'id'=>$id
		);
		$datetime = date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime ;
		if(!isset($post['google_icon'])){
			unset($post['google_icon']);
		}
		// unset($post['id']);
		$resp = $this->Model_Admin->updateData('facility', $post, $wh);
		if($resp){
			record_activity('FACILITY_UPDATED', [
				'description' => "Admin updated facility: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'info'
			]);
	    	$response = response("success", array(), "Success update a facility ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a facility ".$post['name']);
			echo $response;
		}
	}
	public function postDelete()
	{
		$post = $_POST;
		$d = array(
			'is_deleted' => 1
		);
		$w = array ( "id"=>$post['id']);
		$resp = $this->Model_Admin->updateData('facility', $d, $w);
		if($resp){
			record_activity('FACILITY_DELETED', [
				'description' => "Admin deleted facility: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'warning'
			]);
	    	$response = response("success", array(), "Success delete a facility ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a facility ".$post['name']);
			echo $response;
		}
	}
	
}

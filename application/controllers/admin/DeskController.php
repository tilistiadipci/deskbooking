<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class DeskController extends CI_Controller {

	
	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Deskbooking');
		$this->load->helper('response');
		$this->load->helper('string');
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
		$pagename = "Desk Controller";
		$menu = $this->Model_Menu->getMenu($pagename);
		$this->load->view('Desk/Controller/index', array('menumaster'=> $menu, 'pagename' => $pagename));
		
		
	}
	public function getData()
	{
		$data = $this->Model_Deskbooking->getDataController();

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
		$post['created_at'] 	= $datetime ;
		$post['updated_at'] 	= $datetime ;
		$post['is_deleted'] 	= 0;
		$post['created_by'] 	= $this->session->userdata('user-nya');
		$post['id'] 			= md5(random_string('alnum', 16));
		if($post['capacity'] <= 0){
			$response = response("fail", array(), "Failed capacity must be more than 0 ".$post['name']);
			echo $response;
			die();
		}
		$data_ar = [];
		for ($i=1; $i <= $post['capacity']; $i++) { 
			$data_init = [
				'socket' 		=> $i,
				'controller_id' 	=> $post['id'] 
			];
			array_push($data_ar , $data_init);

		}
		$resp1 = $this->Model_Admin->insertDataBatch('desk_controller_initial', $data_ar);
		$resp = $this->Model_Admin->insertData('desk_controller', $post);
		if($resp){
	    	$response = response("success", array(), "Success create a desk controller ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a desk controller ".$post['name']);
			echo $response;
		}
	}

	public function getDataDetail(){
		$post = $_POST;
		// print_r($post);
		
		$id = $this->uri->segment(5);
		if(!isset($id)){
			$response = response("fail", array(), "Parameter not complete ");
			echo $response;
			die();
		}
		$where = [
			'id' => $id,
		];
		$data = $this->Model_Deskbooking->getDataController($where );
		if($data['error'] == null){
			echo response("success", $data['data'][0], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
		
	}
	public function getDataControllerInitial(){
		$post = $_POST;
		$id = $this->uri->segment(5);
		if(!isset($id)){
			$response = response("fail", array(), "Parameter not complete ");
			echo $response;
			die();
		}
		$where = [
			'd.controller_id'	=> $id
		];
		$data = $this->Model_Deskbooking->getDataControllerInitial($where );
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
		
	}
	
	public function postUpdate()
	{
		$post = $_POST;
		$id = $this->uri->segment(4);
		$wh = array(
			'id'	=> $id
		);
		$datetime = date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime ;
		$resp = $this->Model_Admin->updateData('desk_controller', $post, $wh);
		if($resp){
	    	$response = response("success", array(), "Success update a desk controller ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a desk controller ".$post['name']);
			echo $response;
		}
	}
	public function resetController()
	{
		$post = $_POST;
		$d = array(
			'desk_room_id' => "",
			'desk_id' => "",
		);
		$name = $post['name'];
		unset($post['name']);
		$w = array ( "id"=>$post['id']);
		$datetime = date("Y-m-d H:i:s");
		$resp = $this->Model_Admin->updateData('desk_controller_initial', $post, $w);
		if($resp){
	    	$response = response("success", array(), "Success update a desk controller ".$name);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a desk controller ".$name);
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
		$resp = $this->Model_Admin->updateData('desk_controller', $d, $w);
		if($resp){
	    	$response = response("success", array(), "Success delete a desk controller ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a desk controller ".$post['name']);
			echo $response;
		}
	}
	
}

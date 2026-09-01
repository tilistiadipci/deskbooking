<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Automation extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Notif');


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
		$pagename = "Automation";
		$menu = $this->Model_Menu->getMenu($pagename);
		$this->load->view('Admin/Automation/index', array('menumaster'=> $menu, 'pagename' => $pagename));
		
		
	}
	public function getData()
	{
		$data = $this->Model_Admin->getDataAutomation();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getEdit()
	{
		$id = $this->uri->segment(4);
		$data = $this->Model_Admin->getEditAutomation($id);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function genData()
	{
		$serial = $this->uri->segment(4);
		$head = array();
		$url_cloud = CLOUD_URL;
		$url = $url_cloud . CLOUD_PATH .$serial ;
		$request = $this->Model_Notif->curlGET($head,$url );
		$rp = json_decode($request ,true);

		print_r($rp);
		if($rp['status'] == "success"){
			$transaction = $rp['collection'];
	    	$response = response("success", $transaction, "Success get data");
			echo $response;
		}else{
	    	$response = response("fail", $request['error'], "Failed cannot conect to server, please check connection");
			echo $response;
		}
		
	}
	public function postCreate()
	{
		$post = $_POST;
		$resp = $this->Model_Admin->insertData('room_automation', $post);
		if($resp){
	    	$response = response("success", array(), "Success create a room ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a room ".$post['name']);
			echo $response;
		}
	}
	public function postUpdate()
	{
		$id = $this->uri->segment(4);
		$wh = array(
			'id'=>$id
		);
		$post = $_POST;
		$resp = $this->Model_Admin->updateData('room_automation', $post, $wh);
		if($resp){
	    	$response = response("success", array(), "Success update a room ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a room ".$post['name']);
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
		$resp = $this->Model_Admin->updateData('room_automation', $d, $w);
		if($resp){
	    	$response = response("success", array(), "Success delete a room ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a room ".$post['name']);
			echo $response;
		}
	}
	
}

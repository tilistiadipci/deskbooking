<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Building extends CI_Controller {

	
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
		$this->encryption->initialize(
		  array(
		    'driver' => 'openssl',
		    'cipher' => 'aes-256',
		    'mode' => 'ctr',
		  )
		);


	}
	public function index()
	{
		$module_room = $this->Model_Module->get_module_room();
		$listtimezone = $this->Model_Module->listTimezone();
		$modules = array();
		$modules['room'] = $module_room;
		$pagename = "Building";
		$menu = $this->Model_Menu->getMenu($pagename);


		$contentBuilding = $this->load->view('Admin/Building/content_building', '', true);
		$this->load->view('Admin/Building/index', array('menumaster'=> $menu, 'pagename' => $pagename, 'modules'=>$modules,'listtimezone' => $listtimezone, 'content' => $contentBuilding));
	}
	public function getData()
	{

		$id = $this->uri->segment(4);
		if(isset($id) && $id != ""){
			$w = array("id" => $id);
			$data = $this->Model_Admin->getDataBuilding($w);
			if($data['error'] == null){
				if(count($data['data']) <= 0){
					echo response("fail", $data, "Get failed");
					die();
				}
				echo response("success", $data['data'][0], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}

		}else{
			$data = $this->Model_Admin->getDataBuilding();
			if($data['error'] == null){
				// echo $this->encryption->encrypt($data['data'][0]['id']);
				// die();
				foreach ($data['data'] as $key => $value) {
					$data['data'][$key]['encrypt'] = encryp_aes($value['id']);
				}
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
		}
		
	}
	
	public function postCreate()
	{
		$this->load->library('upload');
		// $this->load->helper('string');

		$post = $_POST;
		$files = $_FILES;
		// $
		$idbuilding 		= random_string('numeric', 10);

		if(isset($files['image']) && $files['image']['name'] != ""){
			$oriname 	= $files['image']['name'];
			$ext 		= pathinfo($oriname, PATHINFO_EXTENSION);
			$filenameimage = $idbuilding .".".$ext;
			$config = array();
			$config = array(
				'file_name'		=>  $filenameimage,
			    'upload_path'   => './assets/file/building/',
			    'allowed_types' => 'gif|jpg|png|jpeg',
			    'max_size'     => 8000,   // kb                    
			);
			$this->upload->initialize($config);
			if($this->upload->do_upload('image')){
				$post['image'] = $filenameimage;
			    	
			}
		}
		
		$datetime = date("Y-m-d H:i:s");
		$post['id'] = $idbuilding;
		$post['created_at'] = $datetime;
		$post['updated_at'] = $datetime;
		$post['is_deleted'] =0;
		$resp = $this->Model_Admin->insertData('building', $post);
		if($resp){
			record_activity('BUILDING_CREATED', [
				'description' => "Admin created building: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'success'
			]);
	    	$response = response("success", array(), "Success create a building ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a building ".$post['name']);
			echo $response;
		}
	}
	
	public function postUpdate()
	{
		$post = $_POST;
		$id = $this->uri->segment(4) != null ? $this->uri->segment(4) : "";

		if($id == ""){
			$response = response("fail", array(), "Failed update a building ");
			echo $response;
			die();
		}

		$wh = array(
			'id'=>$id
		);

		$post = $_POST;
		$files = $_FILES;
		
		$radid 		= random_string('numeric', 10);
		$dataar 	= array();

		$this->load->library('upload');
		if(isset($files['image']) && $files['image']['name'] != ""){
			$oriname 	= $files['image']['name'];
			$ext 		= pathinfo($oriname, PATHINFO_EXTENSION);
			$filenameimage = $radid .".".$ext;
			$config = array();
			$config = array(
				'file_name'		=>  $filenameimage,
			    'upload_path'   => './assets/file/building/',
			    'allowed_types' => 'gif|jpg|png|jpeg',
			    'max_size'     => 8000,   // kb                    
			);
			$this->upload->initialize($config);
			if($this->upload->do_upload('image')){
				$post['image'] = $filenameimage;
			    	
			}
		}
		unset($post['id']);
		$datetime = date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] =0;
		// print_r($post);
		// print_r($wh);
		$resp = $this->Model_Admin->updateData('building', $post, $wh);
		if($resp){
			record_activity('BUILDING_UPDATED', [
				'description' => "Admin updated building: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'info'
			]);
	    	$response = response("success", array(), "Success update a building ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a building ".$post['name']);
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
		$resp = $this->Model_Admin->updateData('building', $d, $w);
		if($resp){
			record_activity('BUILDING_DELETED', [
				'description' => "Admin deleted building ID " . $post['id'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'warning'
			]);
	    	$response = response("success", array(), "Success delete a building ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a building ");
			echo $response;
		}
	}

	// FLOOR

	public function floorIndex()
	{
		$module_room = $this->Model_Module->get_module_room();
		$listtimezone = $this->Model_Module->listTimezone();
		$modules = array();
		$modules['room'] = $module_room;
		$pagename = "Building";
		$pagename2 = "Floor";

		$get = $_GET;

		
		if(!isset($get['building'])){
			redirect('building');
		}
		if($get['building'] == ""){
			redirect('building');
		}
		$eBuildingId = $get['building'];
		$buildingId = decryp_aes($eBuildingId);
		$w = array("id" => $buildingId);
		
		$data = $this->Model_Admin->getDataBuilding($w);
		if(count($data['data']) <= 0){
			redirect('building');
		}
		$databuilding = $data['data'][0];
		$databuilding['encrypt'] = $eBuildingId ;
		$databuilding['id'] = $eBuildingId ;
		$menu = $this->Model_Menu->getMenu($pagename);

		$wb = "id <>'".$buildingId."' ";
		$listbuilding = $this->Model_Admin->getDataBuilding($wb);
		$datalistbuilding = $listbuilding['data'];
		foreach ($datalistbuilding as $key => $value) {
			$datalistbuilding[$key]['id'] = encryp_aes($value['id']);
		}

		$this->load->view('Admin/Building/index.floor.php', 
			array('menumaster'=> $menu, 
				'pagename' => $pagename, 
				'pagename2' => $pagename2, 
				'modules'=>$modules, 
				'listbuilding'=>$datalistbuilding, 
				'obbuilding'=>$databuilding, 
				'building' => json_encode($databuilding)));
	}
	public function getFloorData()
	{
		if($this->input->method() == "get"){

			$get = $_GET;
		}else{
			$get = $_POST;

		}
		$eBuildingId = $get['building'] ?? "";
		$buildingId = decryp_aes($eBuildingId);
		$eFloorId = $get['floor'] ?? "";
		$floorId = decryp_aes($eFloorId);
		$w = [
			'f.is_deleted' => 0,
			'f.building_id' => $buildingId,
		];
		if($eFloorId != ""){
			$w['f.id'] = $floorId;
		}
		$data = $this->Model_Admin->getDataFloor($w);
		if($data['error'] != null){
			$response = response("fail", array(), "Get failed ");
			echo $response;
			die();
		}	
		if($eFloorId != ""){
			$data['data'][0]['id'] = encryp_aes($data['data'][0]['id']);
			$data['data'][0]['building_id'] = encryp_aes($data['data'][0]['building_id']);
			$data = $data['data'][0];
		}else{
			foreach ($data['data'] as $key => $value) {
				$data['data'][$key]['id'] = encryp_aes($value['id']);
				$data['data'][$key]['building_id'] = encryp_aes($value['building_id']);
			}
			$data = $data['data'];
		}
		$response = response("success", $data,  "Get success");
		echo $response;
	}

	public function postFloorCreate()
	{
		$post = $_POST;
		$idFloor 		= date('YmdHis');
		$eBuildingId 	= $post['building_id'];
		unset($post['building_id']);
		$csrf_name = $this->security->get_csrf_token_name();
		if(isset($post[$csrf_name ])){
			unset($post[$csrf_name]);
		}
		$buildingId = decryp_aes($eBuildingId);
		$w = [
			'f.is_deleted' => 0,
			'f.building_id' => $buildingId,
		];
		$datalast = $this->Model_Admin->getDataFloorLastFloor($w);
		// print_r($datalast);
		if(count($datalast['data']) <= 0){
			$pos = 1;
		}else{
			$pos = ($datalast['data'][0]['position'] -0) + 1;
		}
		$datetime = date("Y-m-d H:i:s");
		$post['id'] = $idFloor;
		$post['building_id'] = $idFloor;
		$post['created_at'] = $datetime;
		$post['updated_at'] = $datetime;
		$post['building_id'] = $buildingId;
		$post['created_by'] = $this->session->userdata('user-nya');
		$post['position'] = $pos;
		$post['is_deleted'] =0;
		$resp = $this->Model_Admin->insertData('building_floor', $post);
		if($resp){
	    	$response = response("success", array(), "Success create a floor ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a floor ".$post['name']);
			echo $response;
		}
	}

	public function postFloorUpdate()
	{
		$post = $_POST;
		$eFloorId	= $post['id'];
		$floorId = decryp_aes($eFloorId);
		$w = ['f.id' => $floorId];
		$data = $this->Model_Admin->getDataFloor($w);
		$csrf_name = $this->security->get_csrf_token_name();
		if(isset($post[$csrf_name ])){
			unset($post[$csrf_name]);
		}
		if(!isset($data['data'][0])){
			$response = response("fail", array(), "Failed the floor not found");
			echo $response;
			die();
		}

		$datafloor = $data['data'][0];
		$datetime = date("Y-m-d H:i:s");
		$up = [
			'name' => $post['name'],
			'updated_at' => $datetime,
			'updated_by' => $this->session->userdata('user-nya'),
		];
		$where = ['id' => $floorId];
		$resp = $this->Model_Admin->updateData('building_floor', $up, $where);
		if($resp){
	    	$response = response("success", array(), "Success update ".$datafloor['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update");
			echo $response;
		}
	}
	public function postFloorDelete()
	{
		$post = $_POST;
		$eFloorId	= $post['id'];
		$floorId = decryp_aes($eFloorId);
		$w = ['f.id' => $floorId];
		$data = $this->Model_Admin->getDataFloor($w);
		$csrf_name = $this->security->get_csrf_token_name();
		if(isset($post[$csrf_name ])){
			unset($post[$csrf_name]);
		}
		if(!isset($data['data'][0])){
			$response = response("fail", array(), "Failed the floor not found");
			echo $response;
			die();
		}
		$datafloor = $data['data'][0];
		$datetime = date("Y-m-d H:i:s");
		$up = [
			'is_deleted' => 1,
			'updated_at' => $datetime,
			'updated_by' => $this->session->userdata('user-nya'),
		];
		$where = ['id' => $floorId];
		$resp = $this->Model_Admin->updateData('building_floor', $up, $where);
		if($resp){
	    	$response = response("success", array(), "Success delete ".$datafloor['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete");
			echo $response;
		}
	}

	public function postFloorUploadImage()
	{
		$this->load->library('upload');
		$post = $_POST;
		$files = $_FILES;
		$eFloorId	= $post['id'];
		$floorId = decryp_aes($eFloorId);
		$w = ['f.id' => $floorId];
		$data = $this->Model_Admin->getDataFloor($w);
		if(!isset($data['data'][0])){
			$response = response("fail", array(), "Failed the floor not found");
			echo $response;
			die();
		}
		$datafloor = $data['data'][0];
		$oldImage =$datafloor['image'] ?? "";
		$imagename = random_string('alnum',32);
		$csrf_name = $this->security->get_csrf_token_name();
		if(isset($post[$csrf_name ])){
			unset($post[$csrf_name]);
		}
		if(isset($files['image']) && $files['image']['name'] != ""){
			$oriname 	= $files['image']['name'];
			$ext 		= pathinfo($oriname, PATHINFO_EXTENSION);
			$filenameimage = $imagename .".".$ext;
			$config = array();
			$config = array(
				'file_name'		=>  $filenameimage,
			    'upload_path'   => './assets/file/floor/',
			    'allowed_types' => 'gif|jpg|png|jpeg|bmp',
			    'max_size'     => 11000,   // kb                    
			);
			$this->upload->initialize($config);
			if($this->upload->do_upload('image')){
				$post['image'] = $filenameimage;
				if($oldImage != ""){
					@unlink("/assets/file/floor/".$oldImage); 

				}
			}
		}
		unset($post['id']);
		$datetime = date("Y-m-d H:i:s");
		$where = [
			"id" => $floorId,
		];
		$post['updated_at'] = $datetime;
		$post['updated_by'] = $this->session->userdata('user-nya');
		$resp = $this->Model_Admin->updateData('building_floor', $post, $where);
		if($resp){
	    	$response = response("success", array(), "Success upload ".$datafloor['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed upload");
			echo $response;
		}

	}
	
	
}

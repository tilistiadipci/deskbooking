<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class DeskRoom extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
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
		$pagename = "Desk Room";
		$menu = $this->Model_Menu->getMenu($pagename);
		$module_automation = $this->Model_Module->get_module_automation();
		$module_price = $this->Model_Module->get_module_price();
		$modules = array();
		$modules['automation'] = $module_automation;
		$modules['price'] = $module_price;


		$building = $this->Model_Admin->getDataBuilding()['data'];
		// print_r($modules['price']);
		$this->load->view('Desk/Room/index', array(
			'menumaster'=> $menu, 
			'building'=> $building, 
			'pagename' => $pagename, 
			'modules'=>$modules));
		
	}
	public function editorIndex()
	{
		$pagename = "Desk Room";
		$menu = $this->Model_Menu->getMenu($pagename);
		$modules = array();


		$building = $this->Model_Admin->getDataBuilding()['data'];
		// print_r($modules['price']);
		$this->load->view('Desk/Room/endpoint', array(
			'menumaster'=> $menu, 
			'building'=> $building, 
			'pagename' => $pagename, 
			'modules'=>$modules));
		
	}
	public function editorZoneIndex()
	{
		$pagename = "Desk Room";
		$menu = $this->Model_Menu->getMenu($pagename);
		$modules = array();
		// print_r($_GET);
		// echo isset($_GET['room']) ;
		if(isset($_GET['selector']) == false || isset($_GET['room']) == false || isset($_GET['zone']) == false  ){
			echo "<h1>Parameter not exist, Editor failed</h1>";
			die();
		}
		$ar = array(
			"rz.desk_room_id" => isset($_GET['room']) ? $_GET['room'] : "",
			"rz.zone_id" => isset($_GET['zone']) ? $_GET['zone'] : "",
		);
		$data = $this->Model_Deskbooking->getRoomZone($ar);
		if($data['error'] != null){
			echo response("fail", $data, "Get failed");
		}
		// if
		$dataZone = $data['data'][0];
		$source = [
			'map' => $dataZone['room_map'],
			'room_id' => $_GET['room'],
			'zone_id' => $_GET['zone'],
			'selector' => $_GET['selector'],
			'pointer' => $_GET['pointer'],
		];

		$building = $this->Model_Admin->getDataBuilding()['data'];
		// print_r($modules['price']);
		$this->load->view('Desk/Room/zoneendpoint', array(
			'menumaster'=> $menu, 
			'source'=> json_encode($source), 
			'pagename' => $pagename, 
			'modules'=>$modules));
	}

	public function editorZoneIndex2()
	{
		$pagename = "Desk Room";
		$menu = $this->Model_Menu->getMenu($pagename);
		$modules = array();
		// print_r($_GET);
		// echo isset($_GET['room']) ;
		if( !isset($_GET['room'])  ){
			echo "<h1>Parameter not exist, Editor failed</h1>";
			die();
		}
		$ar = array(
			"rz.desk_room_id" => isset($_GET['room']) ? $_GET['room'] : "",
			// "rz.zone_id" => isset($_GET['zone']) ? $_GET['zone'] : "",
		);
		$data = $this->Model_Deskbooking->getRoomZone($ar);
		if($data['error'] != null){
			echo response("fail", $data, "Get failed");
		}
		// if
		$dataZone = $data['data'][0];
		$posmap = "landscape";
		if(isset($dataZone['posmap'])){
			$posmap = $dataZone['posmap'] ?? "landscape";
		}
		$ar = array(
			"r.id" => $_GET['room'],
		);
		$room = $this->Model_Deskbooking->getDataRoom2(['r.id' =>  @$_GET['room']]);
		$roomdata = $room['data'];
		$zoneRoom = $this->Model_Deskbooking->getRoomZone($ar);
		$controller = $this->Model_Deskbooking->getDataController();

		if(count($roomdata) <= 0){
			echo "<h1>Room not fount </h1>";
			die();
		}
		$source = [
			'map' => $dataZone['room_map'],
			'room_id' => $_GET['room'],
			'room' => $roomdata[0],
			// 'zone_id' => $_GET['zone'],
			// 'selector' => $_GET['selector'],
			// 'pointer' => $_GET['pointer'],
			'posmap' => $posmap,
			'zoneRoom' => $zoneRoom['data'],
			'controller' => $controller['data'],
		];
		// echo "<pre>";
		// print_r($source);
		// die();

		$building = $this->Model_Admin->getDataBuilding()['data'];
		$this->load->view('Desk/Room/zone-editor', array(
			'menumaster'=> $menu, 
			'source'=> json_encode($source), 
			'pagename' => $pagename, 
			'modules'=>$modules));
	}
	public function getData()
	{
		$data = $this->Model_Deskbooking->getDataRoom2();

		if($data['error'] == null){
			foreach ($data['data'] as $k => $value) {
				$id =  $value['id'];
				$sql = $this->Model_Admin->querySql("
					SELECT * FROM room_detail 
					WHERE room_id=".$id." AND 1=1
					");

				$res = $sql->result_array();
				$data['data'][$k]['facility_room2'] = $res;
			}
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Data not found");
		}
	}
	

	public function getEditor()
	{
		$id = $this->uri->segment(4);
		$ar = array(
				"r.id" => $id,
			);
		$data = $this->Model_Deskbooking->getRoomZone($ar);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}

	public function getEditorData()
	{
		$post = $_POST;
		$ar = array(
			"r.id" => $post['desk_room_id'],
		);
		if(isset($post['zone_id'])){
			$ar ['rt.zone_id'] =$post['zone_id'];
		}
		$data = $this->Model_Deskbooking->getDataRoomDeskTable($ar);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}


	public function getSocketZoneController()
	{
		$post = $_POST;
		$get = $_GET;
		$controller = $this->uri->segment(4);
		if(!isset($controller)){
			$response = response("fail", array(), "Parameter not complete ");
			echo $response;
			die();
		}
		$where = [
			'd.controller_id' => $controller
		];
		$data = $this->Model_Deskbooking->getDataControllerInitial($where );
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	

	
	public function getEdit()
	{
		$id = $this->uri->segment(4);
		$where = [
			'r.id'	=> $id
		];
		$data = $this->Model_Deskbooking->getDataRoom2($where);
		if($data['error'] == null){
			if(count($data['data']) <= 0){
				echo response("fail", array(), "Data not found");
				die();
			}
			$resData = $data['data'][0];


			$sql = $this->Model_Admin->querySql("
					SELECT * FROM room_detail 
					WHERE room_id=".$resData['id']." AND 1=1
					");

				$res = $sql->result_array();
			$resData['facility_room2'] = $res;


			$sql = $this->Model_Admin->querySql("
					SELECT * FROM desk_room_zone 
					WHERE desk_room_id=".$resData['id']." AND 1=1
			");

			$res2 = $sql->result_array();
			$resData['zone'] = $res2;

			echo response("success", $resData, "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	
	public function postCreate()
	{
		$this->load->library('upload');
		$post = $_POST;
		$files = $_FILES;
		$module_automation = $this->Model_Module->get_module_automation();
		$module_price = $this->Model_Module->get_module_price();
		
		$radid 		= random_string('numeric', 6);
		$dataar 	= array();
		$post['id'] = $radid;
		$zone = $post['zone'];
		$arZone = json_decode($zone,true);

		$dataZone = [];
		foreach ($arZone as $k => $vz) {
			$da = [
				'desk_room_id' => $radid,
				'zone_id' => $vz['id'],
				'name' => $vz['name'],
				'pointer' => "",
				'size' => "40",
				'color' => "black",
			];
			array_push($dataZone, $da);
		}
		unset($post['zone']);

        if(isset($files['image']) && $files['image']['name'] != ""){
			$oriname 	= $files['image']['name'];
			$ext 		= pathinfo($oriname, PATHINFO_EXTENSION);
			$filenameimage = $radid .".".$ext;
			$config = array();
			$config = array(
				'file_name'		=>  $filenameimage,
			    'upload_path'   => './assets/file/room/',
			    'allowed_types' => 'gif|jpg|png|jpeg',
			    'max_size'     => 8000,   // kb                    
			);
			$this->upload->initialize($config);
			if($this->upload->do_upload('image')){
				$post['image'] = $filenameimage;
			    	
			}
		}

		if(isset($files['room_map']) && $files['room_map']['name'] != ""){
			$oriname 	= $files['room_map']['name'];
			$ext 		= pathinfo($oriname, PATHINFO_EXTENSION);
			$filenameimage = $radid .".".$ext;
			$config = array();
			$config = array(
				'file_name'		=>  $filenameimage,
			    'upload_path'   => './assets/file/room/',
			    'allowed_types' => 'gif|jpg|png|jpeg',
			    'max_size'     => 8000,   // kb                    
			);
			$this->upload->initialize($config);
			if($this->upload->do_upload('room_map')){
				$post['room_map'] = $filenameimage;
			}
		}


		if(isset($files['image2']) && count($files['image2']['name']) > 0){
			$filenameimage2 = "";
			$config = array();
			$config = array(
			    'upload_path'   => './assets/file/room/',
			    'allowed_types' => 'gif|jpg|png|jpeg',
			    'max_size'     => 8000,   // kb                    
			);
			$this->upload->initialize($config);
			$image2 = $files['image2'];
			$nameFFile = "files_1";
			$colimage = array();
			foreach ($image2['name'] as $km => $vm) {
				$_FILES[$nameFFile]['name']= $image2['name'][$km];
			    $_FILES[$nameFFile]['type']= $image2['type'][$km];
			    $_FILES[$nameFFile]['tmp_name']= $image2['tmp_name'][$km];
			    $_FILES[$nameFFile]['error']= $image2['error'][$km];
			    $_FILES[$nameFFile]['size']= $image2['size'][$km];
			    $oriname 	= $image2['name'][$km];
				$ext 		= pathinfo($oriname, PATHINFO_EXTENSION);
			    $randname 		= random_string('numeric', 10);
			    $fileName = $radid."_".$randname .".".$ext;
			    $ar = array(
			        "name" =>  $fileName,
			        "size" => $_FILES[$nameFFile]['size'],
			        "type" => $_FILES[$nameFFile]['type'],
			        "status" => ""
			    );
			    // $fileName = "profile" .'_'. $photo['name'];
			    $_FILES[$nameFFile]['name']= $fileName;
			    if($this->upload->do_upload($nameFFile)){
			    	array_push($colimage, $fileName);
			    	$ar ['status'] = "success";
		        	$ar ['error'] =  $this->upload->display_errors();
			    }else{
			    	$ar ['status'] = "fail";
		        	$ar ['error'] =  $this->upload->display_errors();
			    }
			}
			$post['image2'] = implode("##", $colimage);
		}

		if(isset($post['facility_room_name'])){
			if($post['facility_room'] == ""){
				$post['facility_room'] = "";
			}else{

				$resp = $this->Model_Admin->deleteData('room_detail', array("room_id" => $radid));
				$col_insertfac = array();
				foreach ($post['facility_room'] as $k => $v) {
					$post_fac = array(
						'room_id' => $radid,
						'facility_id' => $v
					);
					array_push($col_insertfac, $post_fac);
				}
				if(count($col_insertfac) > 0){
					$this->Model_Admin->insertDataBatch('room_detail', $col_insertfac);
					$post['facility_room'] = implode(",", $post['facility_room_name']);
				}
			}
			unset($post['facility_room_name']);


		}else{
			if($post['facility_room'] == ""){
				$post['facility_room'] = "";
			}else{
				$post['facility_room'] = implode(",", $post['facility_room_name']);
			}
			unset($post['facility_room_name']);
		}

		if($post['work_day'] == ""){
			$post['work_day'] = "Empty";
		}else{
			$post['work_day'] = implode(",", $post['work_day']);
		}

		$post['work_time'] = $post['work_start'] . "-" .$post['work_end'];
		$post['work_start']  =$post['work_start'] .":00";
		$post['work_end'] =$post['work_end'] . ":00";
		$datetime = date("Y-m-d H:i:s");
		$post['created_at'] = $datetime ;
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] =0;

		
		// print_r($post);
		// die();
		$resp = $this->Model_Admin->insertData('desk_room', $post);
		$resp = $this->Model_Admin->insertDataBatch('desk_room_zone', $dataZone);

		if($resp){
			record_activity('ROOM_CREATED', [
				'description' => "Admin created desk room: " . $post['name'],
				'room_id' => $radid,
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'success'
			]);
	    	$response = response("success", array(), "Success create a desk room ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a desk room ".$post['name']);
			echo $response;
		}
	}


	public function uploadImageFile($files,$name = "", $multiple = false,$randomname ="", $newpath = ""){
		if($multiple){
			// print_r($files[$name]);
			if(isset($files[$name]) && $files[$name]['name'] != null){
				if($randomname == ""){
					$randomname 		= random_string('numeric', 6);
				}
				$filenameimage2 = "";
				$config = array();
				$config = array(
				    'upload_path'   => $newpath,
				    'allowed_types' => 'gif|jpg|png|jpeg',
				    'max_size'     => 8000,   // kb                    
					'overwrite'		=>  TRUE,
				);
				$this->upload->initialize($config);
				$image2 = $files[$name];
				$nameFFile = "files_1";
				$colimage = array();
				foreach ($image2['name'] as $km => $vm) {
					$_FILES[$nameFFile]['name']= $image2['name'][$km];
				    $_FILES[$nameFFile]['type']= $image2['type'][$km];
				    $_FILES[$nameFFile]['tmp_name']= $image2['tmp_name'][$km];
				    $_FILES[$nameFFile]['error']= $image2['error'][$km];
				    $_FILES[$nameFFile]['size']= $image2['size'][$km];
				    $oriname 	= $image2['name'][$km];
					$ext 		= pathinfo($oriname, PATHINFO_EXTENSION);
				    $randname 		= random_string('numeric', 10);
				    $fileName = $randomname."_".$randname .".".$ext;
				    $_FILES[$nameFFile]['name']= $fileName;
				    if($this->upload->do_upload($nameFFile)){
				    	array_push($colimage, $fileName);
				    }
				}
				return implode("##", $colimage);
			}else{
				return "";
			}
		}else{

			if(isset($files[$name]) && $files[$name]['name'] != ""){
				$oriname 	= $files[$name]['name'];
				if($randomname == ""){
					$randomname 		= random_string('numeric', 6);
				}
				$ext 		= pathinfo($oriname, PATHINFO_EXTENSION);
				$filenameimage = $randomname .".".$ext;
				$config = array();
				$config = array(
					'file_name'		=>  $filenameimage,
					'overwrite'		=>  TRUE,
				    'upload_path'   => $newpath ,
				    'allowed_types' => 'gif|jpg|png|jpeg',
				    'max_size'     => 8000,   // kb                    
				);
				$this->upload->initialize($config);
				if($this->upload->do_upload($name)){
					return $filenameimage;
				}else{
					return "";
				}
			}
		}
		
	}



	public function postUpdate()
	{
		$this->load->library('upload');
		$post = $_POST;
		$files = $_FILES;
		$id = $this->uri->segment(4);
		$wh = array(
			'id'=>$id
		);

		$zone = $post['zone'];
		$zone_delete = $post['zone_delete'];
		$arZone = json_decode($zone,true);
		$arZoneDelete = json_decode($zone_delete,true);
		$dataZone = [];
		foreach ($arZone as $k => $vz) {
			$da = [
				'desk_room_id' => $id,
				'zone_id' => $vz['id'],
				'name' => $vz['name'],
				'pointer' => "",
				'size' => "40",
				'color' => "black",
			];
			array_push($dataZone, $da);
		}
		foreach ($arZoneDelete as $k => $vz) {
			$wZoneDelete = [
				'desk_room_id' => $id,
				'zone_id' => $vz['id'],
			];
			$this->Model_Admin->deleteData('desk_room_zone',$wZoneDelete);
		}
		unset($post['zone']);
		unset($post['zone_delete']);
		$wR = [
			'r.id' => $id
		];
		$room_zone = $this->Model_Deskbooking->getRoomZone($wR)['data'];
		$data_r = $this->Model_Deskbooking->getDataRoom2($wR)['data'];

		if(count($data_r) <= 0){
			$response = response("fail", array(), "Failed update a room ".$post['name']);
			die();
		}
		$data_r = $data_r[0];
		$room_map = $data_r['room_map'];

		$image_file = $this->uploadImageFile($files,'image', false,$id, './assets/file/room/');
		$room_map_file = $this->uploadImageFile($files,'room_map', false,$id."_".random_string('numeric', 10), './assets/file/room/');

		if($image_file != "") {$post['image'] = $image_file;}
		if($room_map_file != "") {$post['room_map'] = $room_map_file;}
		$col_insertfac = array();
		if(isset($post['facility_room_name'])){
			if($post['facility_room'] == ""){
				$post['facility_room'] = "";
			}else{
				$resp = $this->Model_Admin->deleteData('room_detail', array("room_id" => $id));

				// $col_insertfac = array();
				foreach ($post['facility_room'] as $k => $v) {
					$post_fac = array(
						'room_id' => $id,
						'facility_id' => $v
					);
					array_push($col_insertfac, $post_fac);
				}
				
				if(count($col_insertfac) > 0){
					$ff = $this->Model_Admin->insertDataBatch('room_detail', $col_insertfac);
				}
				$post['facility_room'] = implode(",", $post['facility_room_name']);
				
			}
			unset($post['facility_room_name']);
		}else{
			if(isset($post['facility_room'])){
				if($post['facility_room'] == ""){
					$post['facility_room'] = "";
				}else{
					$post['facility_room'] = implode(",", $post['facility_room_name']);
				}
				unset($post['facility_room_name']);
			}else{
				$post['facility_room'] = "";
			}
			if(isset($post['facility_room_name'])){
				unset($post['facility_room_name']);
			}
			

		}

		if($post['work_day'] == ""){
			$post['work_day'] = "Empty";
		}else{
			$post['work_day'] = implode(",", $post['work_day']);
		}

		$post['work_time'] = $post['work_start'] . "-" .$post['work_end'];
		$post['work_start']  =$post['work_start'] .":00";
		$post['work_end'] =$post['work_end'] . ":00";
		$datetime = date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime ;
		unset($post['id']);
		// print_r($post);
		// die();


		$resp = $this->Model_Admin->updateData('desk_room', $post, $wh);
		foreach ($dataZone as $key => $value) {
			$dattzone_ = null;
			foreach ($room_zone as $krz => $vrz) {
				if($value['zone_id'] == $vrz['zone_id']){
					$dattzone_ = $vrz;
					$data_zone1 = [
						'name' => $value['name']
					];
					$wZone1 = [
						'zone_id' =>	$value['zone_id'],
						'desk_room_id' =>	$id,
					];
					$this->Model_Admin->updateData('desk_room_zone', $data_zone1, $wZone1);
					break;
				}
			}
			if($dattzone_  == null){
				$this->Model_Admin->insertData('desk_room_zone', $value);
			}
		}
		if($resp){
			record_activity('ROOM_UPDATED', [
				'description' => "Admin updated desk room: " . $post['name'],
				'room_id' => $id,
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'info'
			]);
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
		$resp = $this->Model_Admin->updateData('desk_room', $d, $w);
		$ctrlData = [
			'desk_room_id' => '',
			'desk_id' => '',
		];
		$wh = [
			'desk_room_id'	=>$post['id'],
		];
		$this->Model_Admin->updateData('desk_controller_initial', $ctrlData, $wh);
		if($resp){
			record_activity('ROOM_DELETED', [
				'description' => "Admin deleted desk room: " . $post['name'],
				'room_id' => $post['id'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'warning'
			]);
	    	$response = response("success", array(), "Success delete a room ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a room ".$post['name']);
			echo $response;
		}
	}

	public function postActionEditor()
	{
		$this->load->library('upload');
		$post 		= $_POST;
		$files 		= $_FILES;
		$datetime 	= date("Y-m-d H:i:s");
		$action 	= $post['action']; 
		$deskid 	= random_string('numeric',16);
		$dataar 	= array();
		$pointer = isset($post['pointer']) ? $post['pointer']:'';
		
		$data = [
			'desk_id' => $deskid ,
			'desk_room_id' => isset($post['room']) ? $post['room']:'',
			'zone_id' => isset($post['zone']) ? $post['zone']:'',
			'block_number' => isset($post['number']) ? $post['number']:'',
			'is_deleted' => 0,
			'datetime' => $datetime ,
		];
		
		if(count(explode(",",$pointer)) >= 2){
			$p = explode(",",$pointer);
			$data['pointer_desk_x'] = $p[0];
			$data['pointer_desk_y'] = $p[1];
		}
		// print_r($data);

		$msg = '';
		if($action == "edit" || $action == "update"){
			$id = isset($post['desk_id']) ? $post['desk_id']:'';
			$w = [
				'desk_id' => $id
			];
			$msg = 'Success update a desk ';
			$ctrlData = [
				'desk_room_id' => '',
				'desk_id' => '',
			];
			$wh = [
				'desk_room_id'	=> isset($post['room']) ? $post['room']:'',
				'desk_id'		=> $id, 
				'socket' 		=> isset($post['old_socket']) ? $post['old_socket']:'', 

			];
			// $resp = $this->Model_Admin->updateData('desk_controller_initial', $ctrlData, $wh);

			$ctrlData2 = [
				'desk_room_id' 	=> isset($post['room']) ? $post['room']:'',
				'desk_id' 		=> $deskid,
			];
			$wh2 = [
				'controller_id' => isset($post['controller']) ? $post['controller']:'', 
				'socket' 		=> isset($post['socket']) ? $post['socket']:'', 
			];

			// print_r($post);
			// print_r($wh);
			// print_r($wh2);
			// print_r($w);
			// print_r($ctrlData);
			// print_r($ctrlData2);
			// print_r($data);
			// die();

			$resp = $this->Model_Admin->updateData('desk_controller_initial', $ctrlData2, $wh2);
			$resp = $this->Model_Admin->updateData('desk_room_table', $data, $w);
		}else{
			$msg = 'Success create a desk ';
			$ctrlData = [
				'desk_room_id' 	=> isset($post['room']) ? $post['room']:'',
				'desk_id' 		=> $deskid,
			];

			// print_r($post);
			// print_r($ctrlData);
			// print_r($data);

			$wh = [
				'controller_id' => isset($post['controller']) ? $post['controller']:'', 
				'socket' 		=> isset($post['socket']) ? $post['socket']:'', 
			];
			$resp = $this->Model_Admin->updateData('desk_controller_initial', $ctrlData, $wh);
			// print_r($wh);
			// die();
			$resp = $this->Model_Admin->insertData('desk_room_table', $data);
		}
		if($resp){
	    	$response = response("success", array(), $msg);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed submit a desk ");
			echo $response;
		}
	}

	public function postDeleteEditor()
	{
		$post = $_POST;
		$d = array(
			'is_deleted' => 1
		);
		$w = array ( "desk_id"=>$post['id']);
		$resp = $this->Model_Admin->updateData('desk_room_table', $d, $w);

		$ctrlData = [
				'desk_room_id' => '',
				'desk_id' => '',
		];
		$wh = [
				'desk_room_id'	=> isset($post['room']) ? $post['room']:'',
				'desk_id'		=> $post['id'], 
				'socket' 		=> isset($post['socket']) ? $post['socket']:'', 

		];
		
		// print_r($post);
		// 	print_r($ctrlData);
		// 	print_r($wh);
		// 	die();
		$this->Model_Admin->updateData('desk_controller_initial', $ctrlData, $wh);

		if($resp){
	    	$response = response("success", array(), "Success delete a desk ".$post['number']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a desk ".$post['number']);
			echo $response;
		}
	}

	public function postSavePosition()
	{
		$post = $_POST;
		if(!isset($post['position'])){
			$response = response("fail", array(), "Failed save position desk ".$post['number']);
			echo $response;
			die();
		}

		$raw = $post['position'];
		$data = json_decode($raw, TRUE);
		foreach ($data as $key => $value) {
			$w = array ( "desk_id"=>$value['desk_id']);
			$d = array(
				'pointer_desk_x' => $value['pointer_desk_x'],
				'pointer_desk_y' => $value['pointer_desk_y'],
			);
			$resp = $this->Model_Admin->updateData('desk_room_table', $d, $w);
		}
		if($resp){
	    	$response = response("success", array(), "Success save position desk ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed save position desk ");
			echo $response;
		}
	}



	
}

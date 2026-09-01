<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Room extends CI_Controller {

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
		$this->load->model('Model_License');
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

		$pagename = "Room";
		$menu = $this->Model_Menu->getMenu($pagename);
		$module_automation = $this->Model_Module->get_module_automation();
		$module_price = $this->Model_Module->get_module_price();
		$module_int_365 = $this->Model_Module->get_module_int_365();
		$module_int_google = $this->Model_Module->get_module_int_google();
		$modules = array();
		$modules['automation'] = $module_automation;
		$modules['vip'] = $this->Model_Module->get_module_vip();
		$modules['price'] = $module_price;
		$modules['int_365'] = $module_int_365;
		$modules['int_google'] = $module_int_google;
		$modules['room_adv'] = $this->Model_Module->get_module_room_adv();
		$modules['vip'] = $this->Model_Module->get_module_vip();
		$room_for_usage = $this->Model_Admin->select_all_data('room_for_usage', ["is_deleted" => 0], ["*"],"result");
		$room_user_checkin = $this->Model_Admin->select_all_data('room_user_checkin', ["is_deleted" => 0], ["*"],"result");
		$userApproval = [];
		$usetPermission = [];
		if($modules['room_adv']['is_enabled'] == 1){
			$userApproval = $this->Model_Admin->getDataEmployeeApproval([]);
			$usetPermission = $this->Model_Admin->getDataEmployee([]);
		}
		$building = $this->Model_Admin->getDataBuilding()['data'];
		$floor = $this->Model_Admin->getDataFloor()['data'];
		
		$this->load->view('Admin/Room/index', array(
			'menumaster'=> $menu, 
			'building'=> $building, 
			'floor'=> $floor, 
			'pagename' => $pagename, 
			'room_for_usage' => json_encode($room_for_usage), 
			'user_approval' => $userApproval['data'], 
			'user_permission' => $usetPermission['data'], 
			'room_user_checkin' => $room_user_checkin, 
			'modules'=>$modules
		));
		
	}
	public function getData()
	{
		$data = $this->Model_Admin->getDataRoom2();

		if($data['error'] == null){
			foreach ($data['data'] as $k => $value) {
				$id =  $value['radid'];
				$sql = $this->Model_Admin->querySql("
					SELECT * FROM room_detail 
					WHERE room_id=".$id." AND 1=1
					");

				$res = $sql->result_array();
				$data['data'][$k]['facility_room2'] = $res;
			}
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	function getDataRoomIntegration(){
		$post = $_POST;
		$type = !isset($post['type']) ? "" : $post['type'];
		$roomid = !isset($post['roomid']) ? "" : $post['roomid'];
		if($type == ""){
			echo response("fail", [], "Integration type is empty");
		}

		if($type == "365"){

			$dataRoom = $this->Model_License->roomSystemToIntegration($roomid,$type);
			$listRoom = $this->Model_License->roomIntegration($type);
			$datalistRoom = [];

			if(isset($dataRoom['radid'])){
				foreach ($listRoom as $key => $value) {
					if($value['id'] == $dataRoom['id'] && $value['initial'] == 1 ){
						array_push($datalistRoom, $value);
					}else if($value['initial'] == 0 ){
						array_push($datalistRoom, $value);
					}
				}
			}else{
				foreach ($listRoom as $key => $value) {
					if($value['initial'] == 0 ){
						array_push($datalistRoom, $value);
					}
				}
			}
			$cb = [
				'data' => $dataRoom,
				'listRoom' =>$datalistRoom,
				'type' =>$type ,
			];
			echo response("success", $cb , "");
		}else{

		}

	}
	public function getDataSingleRoom()
	{

		$post = $_POST;
		$data = $this->Model_Admin->getDataSingleRoom();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getDataMerge()
	{

		$post = $_POST;
		$room_id = @$post['room_id'];
		$data = $this->Model_Admin->getDataMergeRoom($room_id);

		if($data['error'] == null){
			
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getEdit()
	{
		$id = $this->uri->segment(4);
		$data = $this->Model_Admin->getEditRoom($id);
		if($data['error'] == null){

			$sql = $this->Model_Admin->querySql("
					SELECT * FROM room_detail 
					WHERE room_id=".$data['data']['radid']." AND 1=1
					");

			$sqlusage = $this->Model_Admin->querySql("
					SELECT * FROM room_for_usage_detail 
					WHERE room_id=".$data['data']['radid']." AND 1=1
					");
				$res = $sql->result_array();
				$resusage = $sqlusage->result_array();
				$data['data']['facility_room2'] = $res;
				$data['data']['room_data_usage'] = $resusage;
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	
	public function postCreate()
	{
		$this->load->library('upload');
		$post = $_POST;
		$files = $_FILES;
		$this->Model_License->checkRoomModuleLicense();
		
		$module_automation = $this->Model_Module->get_module_automation();
		$module_price = $this->Model_Module->get_module_price();
		
		$radid 		= random_string('numeric', 6);
		$dataar 	= array();

		$merge_room = isset($post['merge_room']) ? $post['merge_room'] : array();
		$type_room = isset($post['type_room']) ? $post['type_room'] : '';

		unset($post['merge_room']);

		$post['radid'] = $radid;
		// $post['config_approval_user'] = "";
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
			if(isset($post['facility_room'])){
				if($post['facility_room'] == ""){
					$post['facility_room'] = "";
				}else{
					$post['facility_room'] = implode(",", $post['facility_room_name']);
				}	
			}else{
				$post['facility_room'] = "";
			}
			if(isset($post['facility_room_name'])){
				unset($post['facility_room_name']);
			}
			
		}

		if($type_room == "merge"){

			$resp = $this->Model_Admin->deleteData('room_merge_detail', array("room_id" => $radid));
			$col_insertmerge = array();
			foreach ($merge_room as $k => $v) {
				$post_megre = array(
					'room_id' => $radid,
					'merge_room_id' => $v
				);
				array_push($col_insertmerge, $post_megre);
			}
			if(count($col_insertmerge) > 0){
				$this->Model_Admin->insertDataBatch('room_merge_detail', $col_insertmerge);
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
		$post['created_at'] = $datetime ;
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] =0;
		// print_r($post);
		$resp = $this->Model_Admin->insertData('room', $post);
		if($resp){
			record_activity('ROOM_CREATED', [
				'description' => "Admin created meeting room: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'success'
			]);
	    	$response = response("success", array(), "Success create a room ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a room ".$post['name']);
			echo $response;
		}
	}


	public function uploadImageFile($files,$name = "", $multiple = false,$randomname ="", $newpath = ""){
		if($multiple){
			if(isset($files[$name]) && count($files[$name]['name']) > 0){
				if($randomname == ""){
					$randomname 		= random_string('numeric', 10);
				}
				$filenameimage2 = "";
				$config = array();
				$config = array(
					'overwrite' => TRUE,
				    'upload_path'   => $newpath,
				    'allowed_types' => 'gif|jpg|png|jpeg',
				    'max_size'     => 128000,   // kb                    
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

		$merge_room = isset($post['merge_room']) ? $post['merge_room'] : array();
		$type_room = isset($post['type_room']) ? $post['type_room'] : '';
		unset($post['merge_room']);

		$data_r = $this->Model_Admin->getEditRoom($id);
		$radid = $data_r['data']['radid'];
		$image2 = $data_r['data']['image2'];
		$spimage2 = explode("##", $image2);
		
		
		$image = $this->uploadImageFile($files,'image', false,"", './assets/file/room/');
		if($image != "") {$post['image'] = $image;}
		$image2_file = array();
		$image2_file[0] = $this->uploadImageFile($files,'image2_1', false,$radid."_".random_string('numeric', 10), './assets/file/room/');
		$image2_file[1] = $this->uploadImageFile($files,'image2_2', false,$radid."_".random_string('numeric', 10), './assets/file/room/');
		$image2_file[2] = $this->uploadImageFile($files,'image2_3', false,$radid."_".random_string('numeric', 10), './assets/file/room/');
		foreach ($image2_file as $key => $imv) {
			if($image2_file[$key] != ""){
				$spimage2[$key] = $image2_file[$key];
			}
		}


		$post['image2'] = implode("##", $spimage2);

		if(isset($post['facility_room_name']) && isset($post['facility_room'])){
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
					$ff = $this->Model_Admin->insertDataBatch('room_detail', $col_insertfac);
				}
				$post['facility_room'] = implode(",", $post['facility_room_name']);
				
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
		
		if($type_room == "merge"){

			$resp = $this->Model_Admin->deleteData('room_merge_detail', array("room_id" => $radid));
			$col_insertmerge = array();
			foreach ($merge_room as $k => $v) {
				$post_megre = array(
					'room_id' => $radid,
					'merge_room_id' => $v
				);
				array_push($col_insertmerge, $post_megre);
			}
			if(count($col_insertmerge) > 0){
				$this->Model_Admin->insertDataBatch('room_merge_detail', $col_insertmerge);
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

		$resp = $this->Model_Admin->updateData('room', $post, $wh);
		if($resp){
			record_activity('ROOM_UPDATED', [
				'description' => "Admin updated meeting room: " . $post['name'],
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

	public function postUpdateAdv()
	{
		$post = $_POST;
		$id = $this->uri->segment(4);
		$wh = array(
			'id'=>$id
		);

		$dataUp = [];
		$datetime = date("Y-m-d H:i:s");
		$config_enable = isset($post['is_config_setting_enable']) ? true : false;
		if($config_enable){
			$dataUp['is_config_setting_enable'] = 1;
		}else{
			$dataUp['is_config_setting_enable'] = 0;
		}


		
		if(isset($post['config_advance_booking'])  && $config_enable == true && isset($post['config_room_for_usage'])){
			if(count($post['config_room_for_usage']) > 0 && $config_enable == true){
				$imp_room_for_usage = implode(",", $post['config_room_for_usage']);
				$dataUp['config_room_for_usage'] = $imp_room_for_usage;
			}else{
				$imp_room_for_usage = "ALL";
				$dataUp['config_room_for_usage'] = $imp_room_for_usage;
			}
		}


		
		if(isset($post['config_advance_booking']) && $config_enable == true){
			$dataUp['config_advance_booking'] = $post['config_advance_booking'] -0;
		}
		if(isset($post['config_max_duration']) && $config_enable == true){
			$dataUp['config_max_duration'] = $post['config_max_duration'] -0;
		}
		if(isset($post['config_min_duration']) && $config_enable == true){
			$dataUp['config_min_duration'] = $post['config_min_duration'] -0;
		}
		if(isset($post['is_enable_recurring']) && $config_enable == true){
			$dataUp['is_enable_recurring'] = 1;
		}else{
			$dataUp['is_enable_recurring'] = 0;
		}
		
		if(isset($post['is_enable_approval']) && $config_enable == true){
			$dataUp['is_enable_approval'] = 1;
		}else{
			$dataUp['is_enable_approval'] = 0;
		}

		if(isset($post['is_enable_approval'])  && $config_enable == true) {
			if(isset($post['config_approval_user'])){
				$dataUp['config_approval_user'] =implode(",", $post['config_approval_user']);
			}
		}

		if(isset($post['is_enable_permission']) && $config_enable == true){
			$dataUp['is_enable_permission'] = 1;
		}else{
			$dataUp['is_enable_permission'] = 0;
		}

		if(isset($post['is_enable_permission'])  && $config_enable == true) {
			if(isset($post['config_permission_user'])){
				$dataUp['config_permission_user'] =implode(",", $post['config_permission_user']);
			}
		}

		$data_r = $this->Model_Admin->getEditRoom($id);
		$radid = $data_r['data']['radid'];
		$name = $data_r['data']['name'];

		if(isset($post['room_usage_detail']) && $config_enable == true){

			$resp = $this->Model_Admin->deleteData('room_for_usage_detail', array("room_id" => $radid));
			try{
				$data_room_for_usage_detail = json_decode($post['room_usage_detail'], TRUE);
				if(count($data_room_for_usage_detail) >0){
					$this->Model_Admin->insertDataBatch('room_for_usage_detail',$data_room_for_usage_detail);
				}
			}catch(Exception $error){

			}
			
		}
		
		$dataUp['updated_at'] = $datetime ;
		// print_r($dataUp);
		// die();
		$resp = $this->Model_Admin->updateData('room', $dataUp, $wh);
	
		if($resp){
			record_activity('ROOM_UPDATED', [
				'description' => "Admin updated advance settings for room: " . $name,
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'info'
			]);
	    	$response = response("success", array(), "Success update a room advance");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a room advance");
			echo $response;
		}
	}

	public function postUpdateAdvCheckin(){
		$datetime = date("Y-m-d H:i:s");
		$post = $_POST;
		$id = $this->uri->segment(4);
		$wh = array(
			'id'=>$id
		);
		$data_r = $this->Model_Admin->getEditRoom($id);
		$radid = $data_r['data']['radid'];
		$name = $data_r['data']['name'];
		$config_enable = ( $data_r['data']['is_config_setting_enable'] - 0) == 1 ? true:false;
		$config_release_checkin_timeout = isset($post['is_realease_checkin_timeout']);
		$is_enable_checkin = isset($post['is_enable_checkin']);
		if($config_enable == true){
			$dataUp['config_permission_end'] = isset($post['config_permission_end']) ? $post['config_permission_end'] : "";
		}
		if(isset($post['is_enable_checkin']) && $config_enable == true){
			$dataUp['is_enable_checkin'] = 1;
			$dataUp['config_permission_checkin'] = isset($post['config_permission_checkin']) ? $post['config_permission_checkin'] : "";
		}else{
			$dataUp['is_enable_checkin'] = 0;
		}
		if($config_release_checkin_timeout == true  && $is_enable_checkin == true){
			$dataUp['is_realease_checkin_timeout'] = 1;
		}else{
			$dataUp['is_realease_checkin_timeout'] = 0;
		}
		if(isset($post['config_release_room_checkin_timeout']) && $is_enable_checkin == true){
			$dataUp['config_release_room_checkin_timeout'] = $post['config_release_room_checkin_timeout'] -0;
		}
		$dataUp['updated_at'] = $datetime ;
		$resp = $this->Model_Admin->updateData('room', $dataUp, $wh);
		// $response = response("success", $dataUp, "Success update a room advance");
		// echo $response;
	    // die();
		if($resp){
			record_activity('ROOM_UPDATED', [
				'description' => "Admin updated checkin settings for room: " . $name,
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'info'
			]);
	    	$response = response("success", array(), "Success update a room checkin");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a room checkin");
			echo $response;
		}

	}

	
	public function postDelete()
	{
		$datetime = date("Y-m-d H:i:s");
		$post = $_POST;
		$d = array(
			'is_deleted' => 1,
			'updated_at' => $datetime
		);
		$w = array ( "id"=>$post['id']);
		$resp = $this->Model_Admin->updateData('room', $d, $w);
		if($resp){
			record_activity('ROOM_DELETED', [
				'description' => "Admin deleted room: " . $post['name'],
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

	public function postRoomIntegration()
	{
		$post = $_POST;
		$roomid = isset($post['roomid']) ? $post['roomid'] :""; // radid
		$type = isset($post['type']) ? $post['type'] :""; // 
		$room_int_id = isset($post['room_int_id']) ? $post['room_int_id'] :""; // 
		$old_room_int_id = isset($post['id']) ? $post['id'] :""; // 
		
		$data_r = $this->Model_Admin->getRoomRadid($roomid);

		$dupdate = ['config_microsoft' => ''];
		$wupdate = ['radid' => $roomid];
		$this->Model_Admin->updateData('room', $dupdate, $wupdate);
		$dnewupdate = ['config_microsoft' => $room_int_id];
		$wnewroom = ['radid' => $roomid];
		$this->Model_Admin->updateData('room', $dnewupdate, $wnewroom);

		if($type == "365"){
			$doldintupdate = ['initial' => 0];
			$woldroomint = ['id' => $old_room_int_id];
			$this->Model_Admin->updateData('room_365', $doldintupdate, $woldroomint);

			$dnewintupdate = ['initial' => 1];
			$wnewroomint = ['id' => $room_int_id];
			$this->Model_Admin->updateData('room_365', $dnewintupdate, $wnewroomint);
		}else{

		}
		$response = response("success", array(), "Success update a room integration ");
		echo $response;
	}
	public function removeAllRoom()
	{
		$post = $_POST;
		$radids = isset($post['data']) ? $post['data'] : ""; // radid
		$radidsMs365 = isset($post['ms365']) ? $post['ms365'] : ""; // radid
		$radidsGoogle = isset($post['google']) ? $post['google'] : ""; // radid
		if($radids == ""){
			$response = response("fail", array(), "Room data not found ");
			echo $response;
			die();
		}
		$spRadids = explode(",", $radids);
		$spMs365 = explode(",", $radidsMs365);
		$spGoogle = explode(",", $radidsGoogle);
		foreach ($spRadids as $key => $value) {
			$ms365 = $spMs365[$key];
			$google = $spGoogle[$key];
			$radid = $value;
			$m = ['initial' => 0,];
			$wm = [
				"id" => $ms365
			];
			$g = [
				'initial' => 0,
			];
			$wg = [
				"id" => $ms365
			];
			$wra = [
				"radid" => $radid
			];
			$dt = [
				'is_deleted' => 1,
			];
			if($google != null || $google != ""){
				$this->Model_Admin->updateData('room_google', $g, $wg);
			}
			if($ms365 != null || $ms365 != ""){
				$this->Model_Admin->updateData('room_365', $m , $wm);
			}
			$this->Model_Admin->updateData('room', $dt, $wra);
		}
		$response = response("success", array(), "Success remove a room ");
		echo $response;
	}

	public function postRemoveRoomIntegration()
	{
		$post = $_POST;
		$roomid = isset($post['roomid']) ? $post['roomid'] :""; // radid
		$type = isset($post['type']) ? $post['type'] :""; // 
		// $room_int_id = isset($post['room_int_id']) ? $post['room_int_id'] :""; // 
		$old_room_int_id = isset($post['id']) ? $post['id'] :""; // 
		
		$data_r = $this->Model_Admin->getRoomRadid($roomid);

		$dupdate = ['config_microsoft' => ''];
		$wupdate = ['radid' => $roomid];
		$this->Model_Admin->updateData('room', $dupdate, $wupdate);
		if($type == "365"){
			$doldintupdate = ['initial' => 0];
			$woldroomint = ['id' => $old_room_int_id];
			$this->Model_Admin->updateData('room_365', $doldintupdate, $woldroomint);
			
		}else{

		}
		$response = response("success", array(), "Success remove a room integration ");
		echo $response;
	}
	
}

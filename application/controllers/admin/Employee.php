<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class Employee extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Secure');
		$this->load->model('Model_URL');
		$this->load->helper('response');
		$this->load->helper('download');
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
		$modules['vip'] = $this->Model_Module->get_module_vip();

		// $head_employee = $this->Model_Admin->getDataEmployeeWithQr();
		$pagename = "Employee";
		$menu = $this->Model_Menu->getMenu($pagename);
		$getDivision = $this->Model_Admin->getDataAlocationType()['data']; 	
		$this->load->view('Admin/Employee/index', array(
			'menumaster'=> $menu, 
			'pagename' => $pagename, 
			'alocation' => $getDivision, 
			'modules'=>$modules
			// 'modules'=>$head_employee
		 ));
	}

	public function qrcode()
	{
		if(!isset($_GET['code'])){
			redirect('employee');
		}
		if($_GET['code'] == ""){
			redirect('employee');
		}

		if(!isset($_GET['long'])){
			redirect('employee');
		}

		if($_GET['long'] == ""){
			redirect('employee');
		}
		$long = $_GET['long'];


		$code = $_GET['code'];
		$col = $this->Model_Admin->getDataEmployeeWithQrByQrcode($code);
		$len = count($col['data']);
		if($len <= 0){

		}
		$item = $col['data'][0];
		$this->load->view('Admin/Employee/qr', array(
			'code'=> $code, 
			'username'=> $item['username'], 
			'password'=> $item['real_password'], 
		 ));
	}
	public function getData()
	{
		// $data = $this->Model_Admin->getDataEmployee();
		$data = $this->Model_Admin->getDataEmployeeWithQr();
		$datacol = $data['data'];
		// print_r($datacol);
		// die();
		foreach ($datacol as $key => $value) {
			if($value['secure_qr'] == null){
				$scq =  $this->Model_Secure->encryptBio($value['id']); // employee id
				$datacol[$key]['secure_qr_full'] = $scq; 
				$datacol[$key]['secure_qr'] = $this->Model_Secure->encCompress($scq);
				$this->Model_Api->updateData('user', array("secure_qr" => $scq), array('username' =>$value['username'], 'employee_id' => $value['id']));
			}else if($value['secure_qr'] == ''){
				$scq =  $this->Model_Secure->encryptBio($data['username']);
				$datacol[$key]['secure_qr_full'] = $scq;
				$datacol[$key]['secure_qr'] = $this->Model_Secure->encCompress($scq);
				$this->Model_Api->updateData('user', array("secure_qr" => $scq), array('username' =>$value['username'], 'employee_id' => $value['id']));
			}else{
				$scq =  $datacol[$key]['secure_qr'] ;
				$datacol[$key]['secure_qr_full'] = $scq;
				$datacol[$key]['secure_qr'] = $this->Model_Secure->encCompress($scq);
			}
		}
		if($data['error'] == null){
			echo response("success", $datacol, "Get success");
		}else{
			echo response("fail", array(), "Get failed");
		}
	}
	public function getDepartement()
	{
		$id = $this->uri->segment(4);
		$where = array('a.type' => $id );
		$data = $this->Model_Admin->getDataAlocationData($where);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getDetailEmployee()
	{
		$id = $this->uri->segment(4);
		$data = $this->Model_Admin->getEditEmployee($id);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getEdit()
	{
		$id = $this->uri->segment(4);
		// print_r($id);
		$data = $this->Model_Admin->getEditEmployeeByID($id);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	
	public function donwloadTemplate()
	{
		force_download('assets/file/template/template.xls',NULL);
	}
	public function uploadFile()
	{
		include APPPATH.'third_party/phpspreadsheet/autoload.php';
		$datetime = date("Y-m-d H:i:s");
		if(!empty($_FILES['file']['name'])) { 
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			if($ext == 'csv'){
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if($ext == 'xlsx') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else if($ext == 'xls') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else{
            	$response = response("fail", array(), "Extension isn't CSV, XLSX, or XLS ");
				echo $response;
				die();
            }
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
            $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
			unset($allDataInSheet[1]);
			$var_batch = array();
			$var_batchuser = array();
			$getUserConf =  $this->Model_Admin->select_all_data('user_config', array(), array(), 'row');
			$default_password = $getUserConf['default_password'];
			$datetime = date("Y-m-d H:i:s");
			
			foreach ($allDataInSheet as $k => $val) {
				if($val["C"] == "" || $val["C"] == null){
					continue;
				}
				$idx = date('YmdHis');
				$id = "EU".random_string('numeric', 16); // nik
				$company_id = $val["A"]; // 
				$department_id = $val["B"]; // 
				$name = $val["C"];
				$nik = $val["D"]; // nik
				$head_employee = $val["E"]; // head_nik
				$birth_date = $val["F"];
				$gender = $val["G"];
				$no_phone = $val["H"];
				$no_ext = $val["I"];
				$address = $val["J"];
				$card_number = $val["K"];
				$email = $val["L"];
				$username = $nik; // nik
				if($val["M"] == ""){
					$username = $val["M"]; // nik
				}
				$password = $val["N"];

				if($val["N"] == ""){
					$password = $default_password; // nik

				}

				$ENpassword = encryp_data($password);
				$scq =  $this->Model_Secure->encryptBio($id);
				$insertEmployeeUser = array(
					"name" => $name,
					"username" => $username,
					"password" => $ENpassword,
					"real_password" => $password,
					"employee_id" => $id,
					"is_disactived" => 0,
					'created_by' => $this->session->userdata('user-nya'),
					'created_at' => $datetime,
					'is_deleted' => 0,
					'access_id' => "1#2#3#4",
					"level_id" => 2, // user biasa
					"secure_qr" => $scq, // user biasa
				);

				$dataInsert = array(
					"id" => $id,
					"company_id" => $company_id,
					"division_id" => "",
					"department_id" => $department_id,
					"name" => $name,
					"nik" => $id, // 
					"nik_display" => $nik,
					"head_employee" => $head_employee,
					"birth_date" => $birth_date,
					"gender" => $gender,
					"address" => $address,
					"card_number_real" => $card_number,
					"card_number" => $card_number,
					"email" => $email,
					"no_phone" => $no_phone,
					"no_ext" => $no_ext,
				);
				if(!empty($dataInsert['name'])){
					array_push($var_batch, $dataInsert);
					array_push($var_batchuser, $insertEmployeeUser);
				}
				
			}

			
			if(count($var_batch) > 0){
				// print_r($var_batch);
				// print_r($var_batchuser);
				$resp = $this->Model_Admin->insertDataBatch('employee', $var_batch);
				$resp23 = $this->Model_Admin->insertDataBatch('user', $var_batchuser);
				$fileassets = array(
					"name" => $_FILES['file']['name'],
					"time" => date('Y-m-d H:i:s'),
					"total_row" => count($var_batch),
					"total_size" => $_FILES['file']['size'],
					"is_deleted" => 0
				);
				$resp = $this->Model_Admin->insertData('batch_upload', $fileassets);
				if($resp){
					$config['upload_path']          = './assets/file/upload/';
	            	$config['allowed_types']        = 'xls|csv|xlsx';
	            	$config['max_size']             = 10000000;
	            	$this->load->library('upload', $config);
	            	if ( ! $this->upload->do_upload('file')){
						$error = array('error' => $this->upload->display_errors());
						$response = response("fail", $error , "Failed upload batch to  employee ");
						echo $response;
					}else{ 
						// success
						$response = response("success", array(), "Success upload batch to  employee ");
						echo $response;
					}
				}else{
					$response = response("fail", array(), "Failed upload batch to  employee ");
					echo $response;
				}
			}else{
				$response = response("fail", array(), "Failed content is empty ");
				echo $response;
			}
			
		}else{
			$response = response("fail", array(), "Failed file is empty");
			echo $response;
		}
	}



	public function postCreateNew()
	{
		// $list_controller = 
		$post = $_POST;
		$files = $_FILES;
		// print_r($post);
		// die();
		$photo_name = $_FILES['photo']['name'];
		$path_tmp = $_FILES['photo']['tmp_name'];
		if($photo_name != "" ){
			$extsp = explode(".", $files['photo']['name']);
			$ext = end($extsp);
			$file_name =gen_uuid(). "." .$ext;
			$config = array();
			$config['file_name']     = $file_name;
			$config['upload_path']   = './assets/employee/';
	        $config['allowed_types'] = 'png|jpg|jpeg';
			$this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('photo'))
	        {
	            $photo_name = "";
	            $error =  $this->upload->display_errors();
	            // print_r($error);
	            die();
	        }
	        else
	        {
	            $photo_name = $file_name;
	        }
		}
		// die();
		$idx = date('YmdHis');
		// $id = random_string('numeric', 3).$idx; // nik
		$id = "EC".$idx; // nik
		$getUserConf =  $this->Model_Admin->select_all_data('user_config', array(), array(), 'row');// user_config
		$default_password = $getUserConf['default_password'];
		$datetime = date("Y-m-d H:i:s");
		// $post['card_number']     = $id;
		$post['created_at'] = $datetime ;
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] =0;
		$post['id'] =$id;
		$post['nik'] = $id;
		$post['gb_id'] ="";
		$post['photo'] =$photo_name;

		if(isset($post['is_vip'])){
			$post['is_vip'] = 1;
		}else{
			$post['is_vip'] = 0;
		}
		if(isset($post['vip_approve_bypass'])){
			$post['vip_approve_bypass'] = 1;
		}else{
			$post['vip_approve_bypass'] = 0;
		}
		if(isset($post['vip_limit_cap_bypass'])){
			$post['vip_limit_cap_bypass'] = 1;
		}else{
			$post['vip_limit_cap_bypass'] = 0;
		}
		if(isset($post['vip_lock_room'])){
			$post['vip_lock_room'] = 1;
		}else{
			$post['vip_lock_room'] = 0;
		}
		if($post['is_vip'] == 0){
			$post['vip_lock_room'] = 0;
			$post['vip_limit_cap_bypass'] = 0;
			$post['vip_approve_bypass'] = 0;
		}
		// $nik 	  = trim($post['nik_display']);
		$username = trim($post['nik_display']);
		$password = encryp_data($default_password);
		$scq =  $this->Model_Secure->encryptBio($id);
		$insertEmployeeUser = array(
			"name" => $post['name'],
			"username" => $username,
			"password" => $password,
			"real_password" => $default_password,
			"employee_id" => $id,
			"is_disactived" => 0,
			'created_by' => $this->session->userdata('user-nya'),
			'created_at' => $datetime,
			'access_id' => '1#2#3#4',
			'is_deleted' => 0,
			"level_id" => 2, // user biasa
			"secure_qr" => $scq, // user biasa
		);
		$wmatrix = array(
			'nik' =>$id ,
			'alocation_id' =>$post['department_id'], // alocation_id
		);
		$insertmatrix = array(
			'nik' => $id ,
			'alocation_id' =>$post['department_id'], // alocation_id
		);
		$gbAccess = false;
		$gbFalco = false;
		// $arGB = "http://192.168.0.20:8080/people?";
		// $arGB =  $this->Model_URL->URLApiGBPeople();

		// if($photo_name != ""){
		// 	$GBDataSetting = $this->GBAddCardDataSetting($post);
		// 	$uriParamGB = $arGB.$GBDataSetting['query'];
		// 	$fgcGB = file_get_contents(FCPATH."assets/employee/".$photo_name);
		// 	$gbVaultSend = $this->uploadDataToFRGB($uriParamGB, $fgcGB, $GBDataSetting ['header']);
		// 	$jgbVaultSend = json_decode($gbVaultSend, true);
		// 	$jgbGBidentifiedFaces = $jgbVaultSend['identifiedFaces'];
		// 	if(count($jgbGBidentifiedFaces) > 0){
		// 		$pGb = $jgbGBidentifiedFaces[0];
		// 		$post['gb_id'] =$pGb['personId'];
		// 		$gbAccess = true;
		// 	}
		// }

		// $arIp =  $this->Model_URL->URLFRFalcoApi();
		// // $arIp = array("http://192.168.0.111:8090/");
		// // $arFalcoVault = "http://192.168.0.51/vaultSite/APIwebservice.asmx";
		// $arFalcoVault =  $this->Model_URL->URLApiFalco();
		// $urlApiBioFr =  $this->Model_URL->URLApiGuestBookFR();
		// $falcoVaultData = $this->falcoAddCardData($post,$photo_name);
		// $falcoVaultSend = $this->uploadDataToVaultFalco($arFalcoVault, $falcoVaultData);
		// $b64 = "";
		// foreach ($arIp as $key => $value) {
		// 	$uri = $value."person/create";
		// 	$uriphoto = $value."face";
		// 	$bb = array (
		// 		"id" => $id,
		// 		"name" => $post['name'] ,
		// 		"idcardNum" => $post['card_number'] ,
		// 		"iDNumber" => $id ,
		// 		"facePermission" => 2,
		// 		"idCardPermission" => 2,
		// 		"faceAndCardPermission" => 2,
		// 		"iDPermission" => 2,
		// 		"tag" => "",
		// 		"phone" => "",
		// 		"passwordPermission" => 1,
		// 	);
		// 	// ========================================================================================
		// 	// =============   ===========   ==========   ========  ===== ==== == === === === ==== ====
		// 	// ========================================================================================
		// 	$bjson = json_encode($bb);
		// 	$fbodyfr =  "person=".$bjson ."&pass=1011121314";
		// 	$df = $this->uploadDataToFRFalco($uri, $fbodyfr);
		// 	// print_r($df);
		// 	if($photo_name != ""){
		// 		$fgcd = file_get_contents(FCPATH."assets/employee/".$photo_name);
		// 		$b_64file = base64_encode($fgcd);
		// 		// $b64 = $b_64file;
		// 		$bbphoto = array (
		// 			"personId" => $id,
		// 			"base64" => $b_64file ,
		// 			"faceId" => $post['card_number']  ,
		// 			"pass" => "1011121314" ,
		// 		);
		// 		$fbodyphoto = http_build_query($bbphoto);
		// 		$frbackFalco =  $this->uploadPhotoToFRFalco($uriphoto , $fbodyphoto);
		// 	}
		// }
		// if($photo_name != ""){
		// 	// $fgcBIO = file_get_contents(FCPATH."assets/employee/".$photo_name);
		// 	// $b_64fileBio = base64_encode($fgcBIO);
		// 	// $bioFRRequestData =  $this->BioFRSendToData($post, $b_64fileBio);
		// 	// $bioFRSendData= $this->uploadBioFR($urlApiBioFr, json_encode($bioFRRequestData));
		// }else{
		// 	// $bioFRRequestData =  $this->BioFRSendToData($post, "");
		// 	// $bioFRSendData= $this->uploadBioFR($urlApiBioFr, json_encode($bioFRRequestData));
		// }
		$resp = $this->Model_Admin->deleteData('alocation_matrix', $wmatrix);
		$this->Model_Admin->logActivity("DELETE", "DELETE MATRIX ".$id." - ".$post['department_id']);
		$resp = $this->Model_Admin->insertData('alocation_matrix', $insertmatrix);
		$this->Model_Admin->logActivity("ADD", "ADD MATRIX ".$id." - ".$post['department_id']);
		$resp = $this->Model_Admin->insertData('employee', $post);
		$this->Model_Admin->logActivity("ADD", "ADD EMPLOYEE ".$id." - ".$post['name']);
		$resp123 = $this->Model_Admin->insertData('user', $insertEmployeeUser);
		$this->Model_Admin->logActivity("ADD", "ADD USER ".$id." - ".$post['name']);

		if($resp){
			record_activity('EMPLOYEE_CREATED', [
				'description' => "Admin created employee: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'success'
			]);
	    	$response = response("success", array( ), "Success create a employee ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a employee ".$post['name']);
			echo $response;
		}
	}

	public function postUpdateNew()
	{
		$post = $_POST;
		$id = $this->uri->segment(4);
		$files = $_FILES;
		if(isset($_FILES['photo'])){
			$photo_name = @$_FILES['photo']['name'];
			$path_tmp = @$_FILES['photo']['tmp_name'];
		}else{
			$photo_name ="";
			$path_tmp ="";
		}
		
		$getUserConf =  $this->Model_Admin->select_all_data('employee', array('id'=>$id), array(), 'row');// user_config
		$photoOld = $getUserConf['photo'];
		if($photo_name != "" ){
			$extsp = explode(".", $files['photo']['name']);
			$ext = end($extsp);
			$file_name =gen_uuid(). "." .$ext;
			$config = array();
			$config['file_name']     = $file_name;
			$config['upload_path']   = './assets/employee/';
	        $config['allowed_types'] = 'png|jpg|jpeg';
			$this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('photo'))
	        {
	            $photo_name = "";
	            $error =  $this->upload->display_errors();
	            // print_r($error);
	            die();
	        }
	        else
	        {
	            $photo_name = $photoOld;
	        }
		}
		if(!isset($getUserConf['nik'])){
			$response = response("fail", array(), "Data employee not found");
			echo $response;
			die();
		}
		$datetime = date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime ;
		if($getUserConf['department_id'] !=  $post['department_id']){
			$wmatrix = array( 'nik' => $id, 'alocation_id' =>$getUserConf['department_id'], );
			$insertmatrix = array( 'nik' => $id, 'alocation_id' =>$post['department_id'], ); // alocation_id
			$resp = $this->Model_Admin->deleteData('alocation_matrix', $wmatrix);
			$this->Model_Admin->logActivity("DELETE", "DELETE MATRIX ".$id." - ".$post['department_id']);
			$resp = $this->Model_Admin->insertData('alocation_matrix', $insertmatrix);
			$this->Model_Admin->logActivity("ADD", "ADD MATRIX ".$id." - ".$post['department_id']);
		}
		$wh = array('id'=>$id);

		// $arIp =  $this->Model_URL->URLFRFalcoApi();
		// $arFalcoVault =  $this->Model_URL->URLApiFalco();
		// $urlApiBioFr =  $this->Model_URL->URLApiGuestBookFR();

		// $arGB =  $this->Model_URL->URLApiGBPeople();
		
		// $b64 = "";
		// foreach ($arIp as $key => $value) {
		// 	$uri = $value."person/update";
		// 	$uriphoto = $value."face";
		// 	$bb = array (
		// 		"id" => $id,
		// 		"name" => $post['name'] ,
		// 		"idcardNum" => $post['card_number'] ,
		// 		"iDNumber" => $id ,
		// 		"facePermission" => 2,
		// 		"idCardPermission" => 2,
		// 		"faceAndCardPermission" => 2,
		// 		"iDPermission" => 2,
		// 		"tag" => "",
		// 		"phone" => "",
		// 		"passwordPermission" => 1,
		// 	);
		// 	// ========================================================================================
		// 	// =============   ===========   ==========   ========  ===== ==== == === === === ==== ====
		// 	// ========================================================================================
		// 	$bjson = json_encode($bb);
		// 	$fbodyfr =  "person=".$bjson ."&pass=1011121314";
		// 	$df = $this->uploadDataToFRFalco($uri, $fbodyfr);
		// 	// print_r($df);
		// 	if($photo_name != ""){
		// 		$fgcd = file_get_contents(FCPATH."assets/employee/".$photo_name);
		// 		$b_64file = base64_encode($fgcd);
		// 		// $b64 = $b_64file;
		// 		$bbphoto = array (
		// 			"personId" => $id,
		// 			"base64" => $b_64file ,
		// 			"faceId" => $post['card_number']  ,
		// 			"pass" => "1011121314" ,
		// 		);
		// 		$fbodyphoto = http_build_query($bbphoto);
		// 		$frbackFalco =  $this->uploadPhotoToFRFalco($uriphoto , $fbodyphoto);
		// 	}
		// }
		// $falcoVaultData = $this->falcoUpdateCardData($post,$photo_name);
		// $falcoVaultSend = $this->uploadDataToVaultFalco($arFalcoVault, $falcoVaultData);

		unset($post['id']);
		$resp = $this->Model_Admin->updateData('employee', $post, $wh);
		if($resp){
			record_activity('EMPLOYEE_UPDATED', [
				'description' => "Admin updated employee: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'info'
			]);
	    	$response = response("success", array(), "Success update a employee ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a employee ".$post['name']);
			echo $response;
		}
	}

	public function postUpdateNewVip()
	{
		$post = $_POST;
		$id = $this->uri->segment(4);
		
		
		$getUserConf =  $this->Model_Admin->select_all_data('employee', array('id'=>$id), array(), 'row');// user_config
		
		if(!isset($getUserConf['nik'])){
			$response = response("fail", array(), "Data employee not found");
			echo $response;
			die();
		}
		$datetime = date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime ;
		
		$wh = array('id'=>$id);
		$data = [];
		if(isset($post['is_vip'])){
			$data['is_vip'] = 1;
		}else{
			$data['is_vip'] = 0;
		}
		if(isset($post['vip_approve_bypass'])){
			$data['vip_approve_bypass'] = 1;
		}else{
			$data['vip_approve_bypass'] = 0;
		}
		if(isset($post['vip_limit_cap_bypass'])){
			$data['vip_limit_cap_bypass'] = 1;
		}else{
			$data['vip_limit_cap_bypass'] = 0;
		}
		if(isset($post['vip_lock_room'])){
			$data['vip_lock_room'] = 1;
		}else{
			$data['vip_lock_room'] = 0;
		}
		$data['updated_at'] = $datetime ;


		$resp = $this->Model_Admin->updateData('employee', $data, $wh);
		// $response = response("success", $wh, "Success update a employee vip");
		// echo $response;
		if($resp){
			record_activity('EMPLOYEE_UPDATED', [
				'description' => "Admin updated VIP status for employee",
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'info'
			]);
	    	$response = response("success",  array(), "Success update a employee vip");
	    	// $response = response("success", $data, "Success update a employee vip");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a employee vip");
			echo $response;
		}
	}

	public function postDeleteNew()
	{
		$post = $_POST;
		$d = array(
			'is_deleted' => 1
		);
		$id = $post['id'];
		$w = array ( "id"=>$post['id']);
		$getUserConf =  $this->Model_Admin->select_all_data('employee', array('id'=>$post['id']), array(), 'row');// user_config

		$resp = $this->Model_Admin->updateData('employee', $d, $w);
		if($resp){
			record_activity('EMPLOYEE_DELETED', [
				'description' => "Admin deleted employee: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'warning'
			]);
	    	$response = response("success", array(), "Success delete a employee ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a employee ".$post['name']);
			echo $response;
		}
	}



	private function uploadDataToVaultFalco($url, $xmlbody){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlbody);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		curl_close ($ch);
		// $array_data = json_decode(json_encode(simplexml_load_string($server_output)), true);
		return $server_output;
	}
	private function uploadDataToFRGB($url, $body, $header){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		curl_close ($ch);
		// $array_data = json_decode(json_encode(simplexml_load_string($server_output)), true);
		return $server_output;
	}
	private function uploadDataToFRFalco($url, $body){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		             $body);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		curl_close ($ch);
		return $server_output;
	}
	private function uploadBioFR($url, $body){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		             $body);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		curl_close ($ch);
		return $server_output;
	}
	private function uploadPhotoToFRFalco($url, $body){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		             $body);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		curl_close ($ch);
		return $server_output;
	}
	private function BioFRSendToData($data, $base64){
		
		$bbGBData = array (
			"name" => $data['name'],
			"cardnum" => $data['card_number'] ,
			"person_id" =>  $data['id']  ,
			"faceId" =>  $data['card_number'] ,
			"company" => "",
			"address" =>  $data['address'] ,
			"place_birth" =>  "" ,
			"date_birth" => $data['birth_date'], 
			"religion_id" => "" ,
			"telp" =>  $data['no_phone'] ,
			"gender" =>  $data['gender'] ,
			"pict" => $base64,
			"status" =>  1 ,
			"is_show" => 0 ,
		);
		
		return $bbGBData;
	}
	private function GBAddCardDataSetting($data){
		$spname = explode(" ", $data['name']);
		$getFirstname = $spname[0];
		if(count($spname) > 1){
			$getLastname = end($spname);
		}else{
			$getLastname = "";
		}
		$bbGB = array (
				"insert" => "true",
				"update" => "true" ,
				"update-if-lower-quality" => "false" ,
				"merge" => "true" ,
				"regroup" => "false",
				"detect-age" => "false",
				"detect-gender" => "false",
				"detect-sentiment" => "false",
				"detect-occlusion" => "false",
				"detect-mask" => "false",
				"differentiate" => "false",
				"similar_limit" => 0,
				"linear-match" => "false",
				"site" => 'default',
				"source" => 'default',
				"provide-face-id" => "true",
				"min-cpq" => -1,
				"min-fcq" => -1,
				"min-fsq" => -1,
				"insert-profile" => "false",
				"max-occlusion" => -1,
				"event" => "none",
				"context" => "live",
				"type" => "person",
				"include-expired" => "false",
		);
		$hGB = array(
				'Content-Type: application/octet-stream',
				'X-RPC-DIRECTORY: main',
				'X-RPC-PERSON-FIRST-NAME: '.$getFirstname ,
				'X-RPC-PERSON-LAST-NAME: '.$getLastname,
				'X-RPC-PERSON-NAME: '.$data['name'],
				'X-RPC-EXTERNAL-ID: '.$data['id'],
				'X-RPC-AUTHORIZATION:telkomsigma:@telkom2021',
			);
		return array('header'=>$hGB, 'query'=>http_build_query($bbGB), 'arQuery'=>$bbGB);
	}
	private function falcoAddCardData($data, $photo_name = ""){

		// $u = base_url()."assets/employee/".$photo_name;
		if($photo_name == ""){
			$u = "";
		}else{
			$fgcGB = file_get_contents(FCPATH."assets/employee/".$photo_name);
			$b_64file = base64_encode($fgcGB);
			$u = $b_64file;
		}

		$t = '<?xml version="1.0" encoding="utf-8"?>
			<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			  <soap:Body>
			    <AddCard xmlns="WebAPI">
			      <CardProfile>
			        <CardNo>'.$data['card_number'].'</CardNo>
			        <Name>'.$data['name'].'</Name>
			        <CardPinNo></CardPinNo>
			        <CardType></CardType>
			        <Department></Department>
			        <Company></Company>
			        <Gentle></Gentle>
			        <AccessLevel>02</AccessLevel>
			        <LiftAccessLevel>00</LiftAccessLevel>
			        <BypassAP>false</BypassAP>
			        <ActiveStatus>true</ActiveStatus>
			        <NonExpired>false</NonExpired>
			        <ExpiredDate>9999/12/12</ExpiredDate>
			        <VehicleNo></VehicleNo>
			        <FloorNo></FloorNo>
			        <UnitNo></UnitNo>
			        <ParkingNo></ParkingNo>
			        <StaffNo>'.$data['id'].'</StaffNo>
			        <Title></Title>
			        <Position></Position>
			        <NRIC></NRIC>
			        <Passport></Passport>
			        <Race></Race>
			        <DOB>1950/08/28</DOB>
			        <JoiningDate>1900/12/12</JoiningDate>
			        <ResignDate>9999/12/12</ResignDate>
			        <Address1>'.$data['address'].'</Address1>
			        <Address2>'.$data['address'].'</Address2>
			        <PostalCode></PostalCode>
			        <City></City>
			        <State></State>
			        <Email>'.$data['email'].'</Email>
			        <MobileNo>'.$data['no_phone'].'</MobileNo>
			        <Photo>'.$u.'</Photo>
			        <DownloadCard>true</DownloadCard>
			      </CardProfile>
			    </AddCard>
			  </soap:Body>
			</soap:Envelope>';
		return $t;
	}
	private function falcoUpdateCardData($data, $photo_name = ""){
		if($photo_name == ""){
			$u = "";
		}else{
			// $fgcGB = file_get_contents(FCPATH."assets/employee/".$photo_name);
			$fgcGB = file_get_contents(FCPATH."assets/employee/".$photo_name);
			$b_64file = base64_encode($fgcGB);
			$u = $b_64file;
		}

		$t = '<?xml version="1.0" encoding="utf-8"?>
			<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
			  <soap12:Body>
			    <UpdateCard xmlns="WebAPI">
			      <CardNo>'.$data['card_number'].'</CardNo>
			      <CardProfile>
			        <CardNo>'.$data['card_number'].'</CardNo>
			        <Name>'.$data['name'].'</Name>
			        <CardPinNo></CardPinNo>
			        <CardType>Normal Card</CardType>
			        <Department></Department>
			        <Company></Company>
			        <Gentle></Gentle>
			        <AccessLevel>02</AccessLevel>
			        <LiftAccessLevel>00</LiftAccessLevel>
			        <BypassAP>false</BypassAP>
			        <ActiveStatus>true</ActiveStatus>
			        <NonExpired>true</NonExpired>
			        <ExpiredDate>9999/01/01</ExpiredDate>
			        <VehicleNo></VehicleNo>
			        <FloorNo></FloorNo>
			        <UnitNo></UnitNo>
			        <ParkingNo></ParkingNo>
			        <StaffNo>'.$data['card_number'].'</StaffNo>
			        <Title></Title>
			        <Position></Position>
			        <NRIC></NRIC>
			        <Passport></Passport>
			        <Race></Race>
			        <DOB>9999/01/01</DOB>
			        <JoiningDate>9999/01/01</JoiningDate>
			        <ResignDate>9999/01/01</ResignDate>
			        <Address1>'.$data['address'].'</Address1>
			        <Address2>'.$data['address'].'</Address2>
			        <PostalCode></PostalCode>
			        <City></City>
			        <State></State>
			        <Email>'.$data['email'].'</Email>
			        <MobileNo>'.$data['no_phone'].'</MobileNo>
			        <Photo>'.$u.'</Photo>
			        <DownloadCard>true</DownloadCard>
			      </CardProfile>
			    </UpdateCard>
			  </soap12:Body>
			</soap12:Envelope>';
		return $t;
	}
	private function falcoDeleteCardData($cardNum = ""){
		$t = '<?xml version="1.0" encoding="utf-8"?>
			<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
			  <soap12:Body>
			    <DeleteCard xmlns="WebAPI">
			      <CardNo>'.$cardNum.'</CardNo>
			      <DeleteFromDevice>true</DeleteFromDevice>
			    </DeleteCard>
			  </soap12:Body>
			</soap12:Envelope>';
		return $t;
	}
	
}

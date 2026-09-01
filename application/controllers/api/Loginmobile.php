<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);


class LoginMobile extends CI_Controller {

	
	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Api');
		$this->load->model('Model_Auth');
		$this->load->model('Model_Api2');
		$this->load->model('Model_Secure');
		$this->load->model('Model_Access');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
		
	}
	public function loginApps()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$password =  encryp_data($post['password']);
		$general = $this->Model_Api->getGeneralSetting()['data'];
		$check = $this->Model_Api->checkLogin($post['username'], $password);

		$module_pantry = $this->Model_Module->get_module_pantry();
		$module_automation = $this->Model_Module->get_module_automation();
		$module_price = $this->Model_Module->get_module_price();
		$module_int_365 = $this->Model_Module->get_module_int_365();
		$module_int_google = $this->Model_Module->get_module_int_google();
		$modules['pantry'] = $module_pantry;
		$modules['automation'] = $module_automation;
		$modules['price'] = $module_price;
		$modules['int_365'] = $module_int_365;
		$modules['int_google'] = $module_int_google;
		$modules['room_adv'] = $this->Model_Module->get_module_room_adv();
		$modules['vip'] = $this->Model_Module->get_module_vip();	

		if($check['username']->num_rows() > 0){
			$store = $check['username']->row_array();
			if($store['level_id'] == 1){
				$response = response("fail", array() , "Failed to login, please formost user use web access");
				echo $response;
			}else{
				$getLevel = $this->Model_Api->getLevel($store['level_id']);
				$level_name = $getLevel[0]['level_name'];
				$data = $check['username']->row_array();
				$data['level_name'] = $level_name ;
				$data['general'] = $general ;
				$empid = $data['id'] ;
				if($data['secure_qr'] == null || $data['secure_qr'] == ""){
					$scq =  $this->Model_Secure->encryptBio($empid);
					$this->Model_Api->updateData('user', array("secure_qr" => $scq), array('username' =>$data['username'], 'employee_id' => $data['id']));
					$data['secure_qr_full'] = $scq;
					$data['secure_qr'] = $this->Model_Secure->encCompress($scq);
				}else{
					$data['secure_qr_full'] = $data['secure_qr'];
					$data['secure_qr'] = $this->Model_Secure->encCompress($data['secure_qr']);
				}
				if(isset($data['access_id'])){
					$dataAccess = explode("#", $data['access_id']);
					$data['user_access'] = $this->Model_Access->getDataAccessUserById($dataAccess);

				}
				$data['modules'] = $modules;
				$data['menu'] = $this->Model_Api2->getDataMobileMenu();
				$response = response("success", $data , "Success to login, please wait for redirect");
				echo $response;
			}
		}else{
			$response = response("fail", array(), "Failed to login, please try again");
			echo $response;
			// echo json_encode(array());
		}

		
		
	}
	public function refreshApps()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$username =  $post['username'];
		$nik =  $post['nik'];
		$general = $this->Model_Api->getGeneralSetting()['data'];
		$check = $this->Model_Api2->checkRefresh($username, $nik);

		$module_pantry = $this->Model_Module->get_module_pantry();
		$module_automation = $this->Model_Module->get_module_automation();
		$module_price = $this->Model_Module->get_module_price();
		$module_int_365 = $this->Model_Module->get_module_int_365();
		$module_int_google = $this->Model_Module->get_module_int_google();
		$modules['pantry'] = $module_pantry;
		$modules['automation'] = $module_automation;
		$modules['price'] = $module_price;
		$modules['int_365'] = $module_int_365;
		$modules['int_google'] = $module_int_google;
		$modules['room_adv'] = $this->Model_Module->get_module_room_adv();
		$modules['vip'] = $this->Model_Module->get_module_vip();	

		if($check->num_rows() > 0){
			$store = $check->row_array();
			if($store['level_id'] == 1){
				$response = response("fail", array() , "Failed to login, please formost user use web access");
				echo $response;
			}else{
				$getLevel = $this->Model_Api->getLevel($store['level_id']);
				$level_name = $getLevel[0]['level_name'];
				$data = $check->row_array();
				$data['level_name'] = $level_name ;
				$data['general'] = $general ;
				if($data['secure_qr'] == null || $data['secure_qr'] == ""){
					$scq =  $this->Model_Secure->encryptBio($data['username']);
					$this->Model_Api->updateData('user', array("secure_qr" => $scq), array('username' =>$data['username'], 'employee_id' => $data['id']));
					$data['secure_qr_full'] = $scq;
					$data['secure_qr'] = $this->Model_Secure->encCompress($scq);
				}else{
					$data['secure_qr_full'] = $data['secure_qr'];
					$data['secure_qr'] = $this->Model_Secure->encCompress($data['secure_qr']);
				}
				if(isset($data['access_id'])){
					$dataAccess = explode("#", $data['access_id']);
					$data['user_access'] = $this->Model_Access->getDataAccessUserById($dataAccess);
				}
				$data['menu'] = $this->Model_Api2->getDataMobileMenu();
				$data['modules'] = $modules;
				$response = response("success", $data , "Success to login, please wait for redirect");
				echo $response;
			}
		}else{
			$response = response("fail", array(), "Failed to login, please try again");
			echo $response;
			// echo json_encode(array());
		}

		
		
	}

	public function loginDisplay()
	{
		$this->output->set_content_type('application/json');

		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$password =  encryp_data($post['password']);
		$check = $this->Model_Auth->displayLoginAdmin($post['username'], $password );
		// print_r($password);
		if($check['error'] == null){
			if(count($check['data']) > 0){
				if( $check['data'][0]['level_id'] == 1 ) { // admin only
					$response = response("success", $check['data'][0], "Success Login, please wait !!!");
					echo $response;
				}else{
					$response = response("fail", array(), "Failed login, your access is restricted ");
					echo $response;
				}
				
			}else{
				$response = response("fail", array(), "Failed login, Username/NPK or password is wrong ");
				echo $response;
			}
			
		}else{

		}
	}
	public function loginPantry()
	{
		$this->output->set_content_type('application/json');
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$password =  encryp_data($post['password']);

		$check = $this->Model_Auth->displayLoginPantry($post['username'], $password );
		if($check['error'] == null){
			if(count($check['data']) > 0){
				$store = $check['data'][0];
				$access = $this->Model_Access->getAccessPantrybooking($post['username']);
				if( $store == true ) { //
					$response = response("success", $store, "Success Login, please wait !!!");
					echo $response;
				}else{
					$response = response("fail", array(), "Failed login, your access is restricted ");
					echo $response;
				}
				
			}else{
				$response = response("fail", array(), "Failed login, Username or password is wrong ");
				echo $response;
			}
			
		}else{

		}
	}

	
	
}

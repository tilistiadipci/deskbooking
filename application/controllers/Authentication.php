<?php
use Gregwar\Captcha\CaptchaBuilder;
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Auth');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Menu');
		$this->load->helper('response');
		

		// $this->load->model('', '', true);
	}	
	public function index()
	{
		$settinggeneral = $this->Model_Admin->getSettingDataGeneral()['data'];
		include APPPATH.'third_party/captcha/autoload.php';

		$builder = new CaptchaBuilder;
		$builder->build();
		$this->session->set_userdata("phrase", $builder->getPhrase());
		$datacp = $this->Model_Admin->getDataCompany();
		$pic = $datacp['data']['picture'];
		$data['background'] = $pic == null || $pic == "" ? "" : base_url()."assets/file/company/".$pic;
		$data['captcha'] =  $builder->inline();
		// print_r ($data['captcha']);
		// die();
		if($this->session->userdata('logged-in')){
			redirect('redirect');
		}else{
			$this->load->view('authentication', $data);
		}
		
	}
	public function checklogin()
	{
		include APPPATH.'third_party/captcha/autoload.php';
		$data = $_POST;
		$builder = new CaptchaBuilder;
		$builder->build();
		// print_r($data);

		$phr = $this->input->post('capt',TRUE); 
		$ckphr = $this->session->userdata('phrase');
		if($phr !== $ckphr ){
			// print_r($this->session->userdata());
			$this->session->set_flashdata('error_login', '<strong>Important!</strong> Captcha not match, try again.');
			$this->session->set_userdata("phrase", $builder->getPhrase());
			$response = response("fail", array(), "Failed to login, captcha not match");
			echo $response;
			die();

		}


		$username = $data['username'];
		$ecnyPass = encryp_data($data['password']);
		$check = $this->Model_Auth->checkAuth($username,$ecnyPass);
		if ($check['username']->num_rows() > 0) {
			$response = response("success", array(), "Success to login, please wait for redirect");
			$store = $check['username']->row_array();
			$usernamenya = $store['username'];
			$level_id = $store['level_id'];
			$getLevel = $this->Model_Auth->getLevel($store['level_id']);
			$getMenuHeader = $this->Model_Auth->getMenuHeader($store['level_id']);
			$name = $store['name'];
			$level_name = $getLevel[0]['level_name'];
			$usernik = $store['nik'];
			$menuheader = $this->Model_Menu->getMenuHeader("MH0003", $store['level_id']);
			$this->setSessionLogin($usernik, $level_name, $level_id, $getLevel,$menuheader, $name, $usernamenya);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed to login, please try again");
			echo $response;
		}
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect('authentication');
	}
	private function setSessionLogin($username, $level_name, $level_id, $menu,$menuheader, $name, $usernamenya){
		$this->session->set_userdata('logged-in', true);
		$this->session->set_userdata('user-nya', $username);
		$this->session->set_userdata('username-nya', $usernamenya);
		$this->session->set_userdata('level-nya', $level_name);
		$this->session->set_userdata('levelid-nya', $level_id);
		$this->session->set_userdata('menu-nya', $menu);
		$this->session->set_userdata('menuheader-nya', $menuheader);
		$this->session->set_userdata('name-nya', $name);
		$this->session->mark_as_temp('logged_in', 3600);
	}
}

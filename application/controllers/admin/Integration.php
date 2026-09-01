<?php

use myPHPnotes\Microsoft\Auth;
use myPHPnotes\Microsoft\Handlers\Session;
use myPHPnotes\Microsoft\Models\User;

defined('BASEPATH') OR exit('No direct script access allowed');
// date_default_timezone_set("Asia/Jakarta");
class Integration extends CI_Controller {

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
		$this->load->helper('response');
		$this->load->helper('string');
		// if($this->session->userdata('logged-in')){
		// 	if($this->session->userdata('levelid-nya') == 1){
		// 		// redirect('authentication');
		// 	}else if($this->session->userdata('levelid-nya') == 2){
		// 		// 
		// 	}else{
		// 		redirect('authentication');

		// 	}
		// }else{
		// 	redirect('authentication');
		// }
	}

	public function index()
	{
		$pagename = "Integration";
		$menu = $this->Model_Menu->getMenu($pagename);
		$module_int_alarm = $this->Model_Module->get_module_int_alarm();
		$module_int_google = $this->Model_Module->get_module_int_google();
		$module_int_365 = $this->Model_Module->get_module_int_365();
		$modules = array();
		$modules['alarm'] = $module_int_alarm;
		$modules['google'] = $module_int_google;
		$modules['m365'] = $module_int_365;
		$alarm_integration =  $this->Model_Admin->select_all_data('alarm_integration', array(), array(), 'row');// 
		$m365_integration =  $this->Model_Admin->select_all_data('integration_365', array(), array(), 'row');// 
		$m365_devices['url_callback'] = base_url().MS_365_CALLBACK_DISCONNECTED;
		$m365_devices['url_dis_m365'] = MS_365_DISCONNECTED;
		$m365_devices['url_open_m365'] = base_url().MS_365_OPEN;

		
		$this->load->view('Admin/Integration/index', array(
			'menumaster'=> $menu, 
			'pagename' => $pagename, 
			'alarm_integration' => json_encode($alarm_integration), 
			'm365_integration' => json_encode($m365_integration), 
			'm365_devices' => json_encode($m365_devices), 
			'modules'=>$modules)
		);
		
	}
	public function saveAlarmConfig()
	{
		$post = $_POST;
		$data = [
			"url_auth"=> $post['auth_http'].$post['url_auth'],
			"url_feedback"=> $post['feed_http'].$post['url_feedback'],
		];
		$wh = [];
		$resp = $this->Model_Admin->updateData('alarm_integration', $data, $wh);
		echo response("success", $data, "Save success");
		
	}
	public function alarmRedirect()
	{
		$post = $_GET;
		// print_r($post);
		// die();
		if(isset($post['token'])){
			$wh = [];
			$data = [
				"password"=> isset($post['password']) ?$post['password']:"",
				"username"=> isset($post['username']) ?$post['username']:"" ,
				"token"=> isset($post['token']) ? $post['token'] : "",
				"active"=> 1,
				"status_integration" => 1,
			];
			$resp = $this->Model_Admin->updateData('alarm_integration', $data, $wh);
			echo '<script>if (window.opener != null && !window.opener.closed) {var txtName = window.opener.document.getElementById("id_feedback_collection");
			txtName.value = "ok";
			window.close();
			}
			</script>';
		}else{

			echo response("fail", [], "Token not valid");
		}
		
		
	}


	public function m365OpenConnection()
	{
		include APPPATH.'third_party/microsoftLib/autoload.php';
		$tenant = MS_365_TENANT;
		$client_id = MS_365_CLIENT_ID;
		$client_secret = MS_365_CLIENT_SECRET;
		$callback = base_url() . MS_365_CALLBACK;
		$scopes = ["User.Read","User.Read.All", "offline_access","Calendars.Read","Calendars.ReadWrite","Place.Read.All"];
		$microsoft = new Auth($tenant, $client_id, $client_secret,$callback, $scopes);
		// echo  $microsoft->getAuthUrl();
		// die();
		header("location: " . $microsoft->getAuthUrl());
		// echo $callback;
		
	}

	public function m365CallbackDisonnection()
	{
		$dateTime = date("Y-m-d H:i:s");
		$data = [
			"updated_at" => $dateTime,
		];
		$data['status'] =0;
		$data['refresh_token'] ="";
		$data['access_token'] ="";
		$data['updated_at'] =$dateTime;
		$data['refresh_at'] =$dateTime;
		$wh = [];
		$resp = $this->Model_Admin->updateData('integration_365', $data, $wh);
		header("location: " . base_url()."integration" );
	}
	public function m365CallbackConnection()
	{
		include APPPATH.'third_party/microsoftLib/autoload.php';
		$tenant = MS_365_TENANT;
		$client_id = MS_365_CLIENT_ID;
		$client_secret = MS_365_CLIENT_SECRET;
		$callback = base_url() . MS_365_CALLBACK;
		$scopes = ["User.Read"];
		$auth = new Auth($tenant, $client_id, $client_secret,$callback, $scopes);
		if(!isset($_GET['code'])){
			die();
		}
		$dateTime = date("Y-m-d H:i:s");
		$data = [
			"code"=> $_GET['code'],
			"refresh_token"=> "",
			"access_token"=> "",
			"created_at" => $dateTime,
			"updated_at" => $dateTime,
			"refresh_at" => $dateTime
		];
		$tokens = $auth->getToken($_GET['code'], $_GET['state']);
		$dateTime_token = date("Y-m-d H:i:s");
		$data['refresh_at'] = $dateTime_token;
		$accessToken = $tokens->access_token;
		$refreshToken = $tokens->refresh_token;
		$data['access_token'] = $accessToken;
		$data['refresh_token'] = $refreshToken;
		$data['scope'] =  $tokens->scope;
		$auth->setAccessToken($accessToken);
		// print_r($tokens);
		// die();
		$user = new User;

		$properties = $user->data->getProperties();
		$account_id = $properties['id'];
		$data['userPrincipalName'] = $user->data->getUserPrincipalName();
		$data['display_name'] =  $user->data->getDisplayName();
		$data['email'] = $user->data->getUserPrincipalName();
		$data['account_id'] =$account_id;
		$data['status'] =1;
		$wh = [];
		$resp = $this->Model_Admin->updateData('integration_365', $data, $wh);
		header("location: " . base_url()."integration" );
	}
}
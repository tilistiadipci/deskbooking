<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Setting extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
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
	public function settingGeneralIndex()
	{
		$pagename = "General Setting";
		$menu = $this->Model_Menu->getMenu($pagename);
		$module_automation = $this->Model_Module->get_module_automation();
		$module_price = $this->Model_Module->get_module_price();
		$module_access_door = $this->Model_Module->get_module_access_door();
		$modules = array();
		$modules['automation'] = $module_automation;
		$modules['price'] = $module_price;
		$modules['access_door'] = $module_access_door;
		$invConfig = $this->Model_Admin->getSettingInvoiceConfig();

		// print_r($modules['price']);
		$this->load->view('Admin/Setting/general', array('menumaster'=> $menu, 'pagename' => $pagename, 'modules'=>$modules, 'invConfig' => $invConfig['data']));
	}
	public function settingSmtpEmailIndex()
	{
		$pagename = "SMTP & Email Setting ";
		$menu = $this->Model_Menu->getMenu($pagename);
		$module_automation = $this->Model_Module->get_module_automation();
		$module_price = $this->Model_Module->get_module_price();
		$module_access_door = $this->Model_Module->get_module_access_door();
		$modules = array();
		$modules['automation'] = $module_automation;
		$modules['price'] = $module_price;
		$modules['access_door'] = $module_access_door;
		$invConfig = $this->Model_Admin->getSettingInvoiceConfig();

		// print_r($modules['price']);
		$this->load->view('Admin/Setting/smtp_email', array('menumaster'=> $menu, 'pagename' => $pagename, 'modules'=>$modules, 'invConfig' => $invConfig['data']));
	}
	public function settingInvoiceConfigPost()
	{
		$post = $_POST;
		$up = $this->Model_Admin->updateData('setting_invoice_config', $post, array());
		// print_r($post);
		// die();
		$response = response("success", array(), "Success save rules");
		echo $response;
		
	}
	
	public function settingGeneralData()
	{
		// print_r($modules['price']);
		$data = $this->Model_Admin->getSettingDataGeneral();
		$response = response("success", $data ['data'], "Success get data");
		echo $response;
		
	}
	public function settingGeneralPost()
	{
		$post = $_POST;
		// echo "<pre>";
		// print_r($post);
		$data = [
			"config_release_room_checkin_enable" => htmlspecialchars($this->input->post('config_release_room_checkin_enable',TRUE),ENT_QUOTES),
			"config_release_room_checkin_time" => htmlspecialchars($this->input->post('config_release_room_checkin_time',TRUE),ENT_QUOTES),
			"notification_reminder" => htmlspecialchars($this->input->post('notification_reminder',TRUE),ENT_QUOTES),
			"enable_security_captcha" => htmlspecialchars($this->input->post('enable_security_captcha',TRUE),ENT_QUOTES),
			"config_book_duration" => htmlspecialchars($this->input->post('config_book_duration',TRUE),ENT_QUOTES),
		];
		// 
		$up = $this->Model_Admin->updateData('setting_rule_deskbooking', $post, array());
		$response = response("success", array(), "Success save rules");
		echo $response;
		
	}

	public function settingEmailSMTPData()
	{
		$data = $this->Model_Admin->getListSettingEmailSMTPData();
		$response = response("success", $data ['data'], "Success get datalist");
		echo $response;
	}
	public function settingEmailSMTPPost()
	{
		$post = $_POST;
		if(!isset($post['name'])){
			$response = response("fail", array(), "Failed apply smtp email");
			echo $response;
			die();
		}
		
		$name = $post['name'];
		
		if($post['name'] == "Disabled"){
			$dt = [
				'selected_email' => 0,
				'is_enabled' => 0,
			];
			$this->Model_Admin->updateData('setting_smtp', $dt, array());
			$dtupdate = [
				'selected_email' => 1,
				'is_enabled' => 0,
			];
			$this->Model_Admin->updateData('setting_smtp', $dtupdate, array(
				"name" => $name,
			));
		}else if($post['name'] == "Custom SMTP Server"){
			$dtupdate = [
				'is_enabled' =>	isset($post['is_enabled']) ? $post['is_enabled'] : "",
				'selected_email' => 1,
				'host' 		=> isset($post['host']) ? $post['host'] : "",
				'user' 		=> isset($post['user']) ? $post['user'] : "",
				'password'	=> isset($post['password']) ? $post['password'] : "",
				'port' 		=> isset($post['port']) ? $post['port'] : 23,
				'secure' 	=> isset($post['secure']) ? $post['secure'] : 1,
			];
			$dt = [
				'selected_email' => 0,
				'is_enabled' => 0,
			];
			$this->Model_Admin->updateData('setting_smtp', $dt, array());
			$this->Model_Admin->updateData('setting_smtp', $dtupdate, array(
				"name" => $name,
			));

		}else if($post['name'] == "BIO SMTP Server"){
			$dtupdate = [
				'selected_email' => 1,
				'is_enabled' => 1,
			];
			$dt = [
				'selected_email' => 0,
				'is_enabled' => 0,
			];
			$this->Model_Admin->updateData('setting_smtp', $dt, array());
			$this->Model_Admin->updateData('setting_smtp', $dtupdate, array(
				"name" => $name,
			));
		}
		record_activity('SYSTEM_CONFIG_UPDATED', [
			'description' => "Admin updated SMTP email settings: " . $name,
			'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
			'severity' => 'info'
		]);
		$response = response("success", array(), "Success save rules email smtp");
		echo $response;
		
	}
	public function settingEmailTemplateData()
	{
		$data = $this->Model_Admin->getSettingEmailTemplateData();
		$response = response("success", $data ['data'], "Success get data");
		echo $response;
	}

	public function settingEmailTemplatePreview()
	{
		$post = $_POST;
		// echo "<pre>";
		// print_r($post);
		// die();
		$this->load->view('Admin/Setting/previewEmail', array('data'=> $post) );
	}
	public function settingEmailTemplateInvPost()
	{
		$post = $_POST;
		$wher= array(
			"type" => "invitation",
		);
		$up = $this->Model_Admin->updateData('setting_email_template', $post, $wher);
		$response = response("success", array(), "Success save rules template invitation");
		echo $response;
		
	}
	public function settingEmailTemplateResPost()
	{
		$post = $_POST;
		$wher= array(
			"type" => "reschedule",
		);
		$up = $this->Model_Admin->updateData('setting_email_template', $post, $wher);
		$response = response("success", array(), "Success save rules template reschedule");
		echo $response;
		
	}
	public function settingEmailTemplateCancelPost()
	{
		$post = $_POST;
		$wher= array(
			"type" => "cancel",
		);
		$up = $this->Model_Admin->updateData('setting_email_template', $post, $wher);
		$response = response("success", array(), "Success save rules template cancel");
		echo $response;
		
	}

	public function settingGeneralGetPantry()
	{
		$data =  $this->Model_Admin->select_all_data('setting_pantry_config', array(), array(), 'row'); // user_config
		$response = response("success", $data, "Success get data");
		echo $response;
	}
	public function settingPantryStatusPost()
	{
		$post = $_POST;
		$data = array(
			'status' => $post['status']
		);
		$up = $this->Model_Admin->updateData('setting_pantry_config', $data, array());
		$response = response("success", array(), "Success save pantry status");
		echo $response;
	}
	public function settingPantryPost()
	{
		$post = $_POST;
		// print_r($post);
		// die();
		$up = $this->Model_Admin->updateData('setting_pantry_config', $post, array());
		$response = response("success", array(), "Success save pantry");
		echo $response;
	}


}
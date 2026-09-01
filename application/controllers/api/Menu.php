<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Menu extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Api');
		$this->load->model('Model_Api2');
		$this->load->model('Model_Menu');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
		
	}
	public function getData()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		if(!isset($post['username']) || $post['username'] == ""){
			$response = response("fail", array(), "Failed restrict access");
			echo $response ;
			die();
		}

		$res = $this->Model_Api2->getDataMobileMenu();
		echo response("success", $res, "Get success");
		
	}

	public function getModule()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		if(!isset($post['username']) || $post['username'] == ""){
			$response = response("fail", array(), "Failed restrict access");
			echo $response ;
			die();
		}
		$w = array("is_deleted" => 0);
		$q = "SELECT * FROM module_backend  
		WHERE is_enabled=1
		ORDER BY module_text ASC";
		$res = $this->Model_Api->querySql($q);
		echo response("success", $res->result_array(), "Get module");
		
	}
	
}
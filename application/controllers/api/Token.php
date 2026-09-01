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
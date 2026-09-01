<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class ErrorPage extends CI_Controller {

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

		$this->load->helper('response');
		// if($this->session->userdata('logged-in')){
		// 	if($this->session->userdata('levelid-nya') != 1){
		// 		redirect('authentication');
		// 	}
		// }else{
		// 	redirect('authentication');
		// }
	}
	public function index()
	{
		
		$segment = $this->uri->segment(1);
		
		if($segment == "api"){
			// $seg2 = $this->uri->segment(1);
			$this->limit_area();
		}else{
			redirect('authentication');
		}
		
	}
	private function limit_area(){
		$array = array(
			"code" => 404,
			"error" => "Api service not found"
		);
		$response = response("fail", $array , "Page not found ");
		echo $response;
	}
	
	
}

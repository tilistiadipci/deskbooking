<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class ApiIndex extends CI_Controller {

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

		$this->load->model('Model_Admin');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
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
		$pagename = "Facility";
		$menu = $this->Model_Menu->getMenu($pagename);
		$this->load->view('Admin/Facility/index', array('menumaster'=> $menu, 'pagename' => $pagename));
		$response = response("fail", array(), "Failed delete a facility ".$post['name']);
		echo $response;
		
	}
	public function test()
	{
		
		$plain_txt = "php-encrypt-and-decrypt";
		echo "Plain Text = $plain_txt <br>";
		 
		$encrypted_txt = encryp_aes($plain_txt);
		echo "Encrypted Text = $encrypted_txt <br>";
		 
// w/hx8m8U9+m46PDF4VVBK4kNE06VUh+AN/K4LrJ0THg=
		$decrypted_txt = decryp_aes("J52u2PxiE1FBAsdEHCQFwktekyTpsEYtjkLklUwM3IA=");
		echo "Decrypted Text = $decrypted_txt <br>";
		 
		if ($plain_txt === $decrypted_txt)
		  echo "SUCCESS";
		else
		  echo "FAILED";
		 
		echo "\n";
		// $response = response("success",$_GET, "Msg");
		// echo $response;
		
	}
	
	
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class License extends CI_Controller {

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
		$pagename = "License";
		$data = $this->Model_License->getLicenseSetting();
		$datalist = $this->Model_License->getLicenseList();
		
		$menu = $this->Model_Menu->getMenu($pagename);
		// $companyParse = json_encode($data['data']);
		// print_r($data['data']);
		// die();
		$this->load->view('Admin/License/index', array('menumaster'=> $menu, 'pagename' => $pagename,'license'=>$data['data'],'license_list'=>$datalist['data']
		));
		
		
	}
	
	public function postUpdate()
	{
		$wh = array();
		$post = $_POST;
		$resp = $this->Model_Admin->updateData('company', $post, $wh);
		
		if($resp){
	    	$response = response("success", array(), "Success update a company ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a company ".$post['name']);
			echo $response;
		}
	}
	public function postMedia()
	{
		$wh = array();
		$post = $_POST;
		$files = $_FILES;
		$oriname 	= $files['file']['name'];
		$spname 	= explode(".", $oriname);
		$ext 		= end($spname);

		$type 		= isset($post['type']) ? $post['type'] : "";


		if($type == "bg"){
			$filename 	= "bg_logo_company.".$ext;
		}else if($type == "icon"){
			$filename 	= "icon_logo_company.".$ext;
		}else if($type == "logo"){
			$filename 	= "logo_company.".$ext;
		}else if($type == "menu"){
			$filename 	= "menu_logo_company.".$ext;
		}else{
			$filename 	= "logo_company.".$ext;
		}
		$config['upload_path']          = './assets/file/company/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
        $config['file_name']            = $filename ;
        $config['overwrite']			= true;
        $config['max_size']             = 10000; // kb
        // $config['max_width']            = 3000;
        // $config['max_height']           = 3000;
        // print_r($files);
        // print_r($post);
        // die();
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('file'))
        {
        	$er = $this->upload->display_errors();
        	$er = str_replace("<p>", "", $error);
	        $er = str_replace("</p>", "", $error);
        	$error = array('error' =>$er );

        	$response = response("fail", array(), $er);
			echo $response;
			die();
        }
		$wh = array();
		$data = [];
		if($type == "bg"){
			$data['picture'] = $filename ;
		}else if($type == "icon"){
			$data['icon'] = $filename ;
		}else if($type == "logo"){
			$data['logo'] = $filename ;
		}else if($type == "menu"){
			$data['menu_bar'] = $filename ;
		}else{
			$data['picture'] = $filename ;
		}
		$resp = $this->Model_Admin->updateData('company', $data, $wh);
		if($resp){
	    	$response = response("success", $post, "Success update a company image ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a company image");
			echo $response;
		}
	}
	
	
}

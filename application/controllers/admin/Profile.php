<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

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
		$nik = $this->session->userdata('user-nya');
		$usernamenya = $this->session->userdata('username-nya');
		$pagename = "Profile";
		$gu = $this->session->userdata('level-nya');
		$data = $this->Model_Admin->getProfile($nik);
		if($data['error'] == null){
			$profile = $data['data'];
		}else{
			$profile = array();
		}
		$menu = $this->Model_Menu->getMenu($pagename);
		$this->load->view('Admin/Profile/index', array('menumaster'=> $menu, 'pagename' => $pagename, 'profile'=>$profile, 'group_user'=> $gu, "usernamenya"=>$usernamenya));
		// die();
		
	}
	
	public function postUpdate()
	{
		$wh = array(
			"nik" => $this->session->userdata('user-nya')
		);
		$post = $_POST;
		$resp = $this->Model_Admin->updateData('employee', $post, $wh);
		
		if($resp){
	    	$response = response("success", array(), "Success update a profile ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a profile ".$post['name']);
			echo $response;
		}
	}
	public function postUsername()
	{
		$wh = array(
			"employee_id" => $this->session->userdata('user-nya')
		);
		$post = $_POST;
		$username = $post['username'];
		$check = $this->Model_Admin->checkProfileUsername($username);
		$numCheck = 0;

		$numCheck += $check['email']->num_rows();
		$numCheck += $check['nik']->num_rows();
		$numCheck += $check['username']->num_rows();
		$numCheck += $check['card']->num_rows();
		if($numCheck> 0){
			$response = response("fail", array(), "Username already used ");
			echo $response;
		}else{
			$raw = array(
				'username'=>$username
			);
			$wh = array(
				"employee_id" => $this->session->userdata('user-nya')
			);
			$this->session->set_userdata('username-nya', $username);
			$resp = $this->Model_Admin->updateData('user', $raw, $wh);
			$response = response("success", array(), "Success update a username ");
			echo $response;
		}
	}
	public function postPassword()
	{
		$nik = $this->session->userdata('user-nya');
		$sql = "SELECT u.*, e.name, nik FROM user u ";
		$sql .= " INNER JOIN employee e ON u.employee_id=e.nik " ;
		$sql .= " WHERE e.nik='".$nik."' " ;
		$post = $_POST;
		// print_r($sql);
		$result = $this->Model_Admin->querySql($sql)->result_array();
		if(count($result) > 0){
			$data = $result[0];
			$encpass_old = encryp_data($post['old_pass']);
			$encpass_new = encryp_data($post['new_pass']);
			// $encpass_old = md5($post['old_pass']);
			// $encpass_new = md5($post['new_pass']);

			if($encpass_old != $data['password']){
				$response = response("fail", array(), "Old passwords do not match ");
				echo $response;
			}else {
				$raw = array(
					'password'=>$encpass_new
				);
				$wh = array(
					"employee_id" => $this->session->userdata('user-nya')
				);
				$resp = $this->Model_Admin->updateData('user', $raw, $wh);
				$response = response("success", array(), "Success update a password ");
				echo $response;
			}

		}else{
			$response = response("fail", array(), "Failed update a password ");
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
		$filename 	= "logo_company.".$ext;
		$config['upload_path']          = './assets/file/company/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
        $config['file_name']            = $filename ;
        $config['overwrite']			= true;
        $config['max_size']             = 3000; // kb
        // $config['max_width']            = 3000;
        // $config['max_height']           = 3000;
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('file'))
        {
        	$error = array('error' => $this->upload->display_errors());
        	$response = response("fail", $error, "Failed upload a company image ");
			echo $response;
			die();
        }
		$wh = array();
		$post['picture'] = $filename;
		$resp = $this->Model_Admin->updateData('company', $post, $wh);
		if($resp){
	    	$response = response("success", array(), "Success update a company image ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a company image");
			echo $response;
		}
	}
	
	
}

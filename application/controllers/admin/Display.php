<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);

class Display extends CI_Controller {

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
		$this->load->model('Model_Kiosk');
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
		$pagename = "Display Signage";
		$module_display = $this->Model_Module->get_module_display();
		$modules = array();
		$modules['display'] = $module_display;
		$menu = $this->Model_Menu->getMenu($pagename);
		$this->load->view('Admin/Display/index', array('menumaster'=> $menu, 'pagename' => $pagename));
		
		
	}
	public function getData()
	{
		$fetch = $this->Model_Admin->getDataDisplay();
		$data = $fetch['data'];
		// echo "<pre>";/
		
		
		foreach ($data as $key => $display) {
			if(isset($display['room_select'])){
				$data[ $key]['room_select_data'] = [];
				$roomSelect = $display['room_select'] == null ? "": $display['room_select'];
				$roomSelectSp = explode(",", $roomSelect);
				if(count($roomSelectSp) <= 0){
					continue;
				}
				// print_r($roomSelectSp);

				$fetchroom = $this->Model_Admin->getDataRoomDisplayByListID($roomSelectSp);
				$data[ $key]['room_select_data'] = $fetchroom['data'];
			}else{
				$data[$key]['room_select'] = "";
				$data[$key]['room_select_data'] = [];
			}
		}

		echo response("success", $data, "Get success");
	}
	public function getDataRoomDisplay()
	{
		$data = $this->Model_Admin->getDataRoomDisplay();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
		
	}
	public function postUpdate()
	{
		$datetime 			= date("Y-m-d H:i:s");
		$wh = array();
		$post = $_POST;
		$files = $_FILES;

		if($files['background']['name'] != "" ||$files['background']['name']  != null){
			$random 	= random_string('numeric', 12);
			$dataar 	= array();
			$oriname 	= $files['background']['name'];
			$spname 	= explode(".", $oriname);
			$ext 		= end($spname);
			$filenameimage = $random .".".$ext;
			$config['upload_path']          = './assets/file/display/background/';
	        $config['allowed_types']        = 'gif|jpg|png|jpeg';
	        $config['file_name']            = $filenameimage;
	        $config['overwrite']			= true;
	        $config['max_size']             = 10000; // < 10MB
	        $config['max_width']            = 3000;
	        $config['max_height']           = 3000;
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('background'))
	        {
	        	$error = array('error' => $this->upload->display_errors());
	        	$response = response("fail", $error, "Failed create a display ");
				echo $response;
				die();
	        }
        	$post['background'] = $filenameimage;
        	$post['background_update'] = 1;
		}
		$where = array(
			'id' => $post['id']
		);
		$room_select  = '';
		if(isset($post['room_select'])){
			if($post['room_select'] != ""){
				$room_select = implode(",",  $post['room_select']);
			}

		}

		$post['room_select'] = $room_select;
		unset($post['id']);
        $post['status_sync'] = 2;
        $post['updated_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
		// print_r($post);
		// die();
		
        $resp = $this->Model_Admin->updateData('room_display', $post, $where);
        if($resp){
	    	$response = response("success", array(), "Success update a display ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a display ");
			echo $response;
		}		
	}
	

	public function postEnabled()
	{
		$datetime 			= date("Y-m-d H:i:s");
		$wh = array();
		$post = $_POST;

		$where = array(
			'id' => $post['id']
		);
		$post['enabled'] = $post['action']-0;
		unset($post['action']);
		unset($post['id']);
        $post['status_sync'] = 2;
        $post['updated_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
        $resp = $this->Model_Admin->updateData('room_display', $post, $where);
        if($resp){
	    	$response = response("success", array(), "Success update a display ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a display ");
			echo $response;
		}		
	}
	public function postDeleted(){
		$datetime 			= date("Y-m-d H:i:s");
		$wh = array();
		$post = $_POST;

		$where = array(
			'id' => $post['id']
		);
		unset($post['id']);
        $post['is_deleted'] = 1;
        $post['updated_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
        $resp = $this->Model_Admin->updateData('room_display', $post, $where);
        if($resp){
	    	$response = response("success", array(), "Success delete a display ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a display ");
			echo $response;
		}		
	}
	public function postCreated()
	{
		$datetime 			= date("Y-m-d H:i:s");
		$wh = array();
		$post = $_POST;
		$files = $_FILES;

		$this->Model_License->checkDisplayModuleLicense();
		$random 	= random_string('numeric', 12);
		$dataar 	= array();
		$oriname 	= $files['background']['name'];
		$spname 	= explode(".", $oriname);
		$ext 		= end($spname);
		$filenameimage = $random .".".$ext;
		$room_select  = '';
		$post['enabled'] = 1;
		$post['hardware_uuid'] = '';
		$post['hardware_info'] = '';
		$post['hardware_lastsync'] = $datetime ;
		$post['status_sync'] = 0 ;
		if(isset($post['room_select'])){
			if($post['room_select'] != ""){
				$room_select = implode(",",  $post['room_select']);
			}

		}
		$post['room_select'] =  $room_select;
		$config['upload_path']          = './assets/file/display/background/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['file_name']            = $filenameimage;
        $config['overwrite']			= true;
        $config['max_size']             = 10000; // < 10MB
        $config['max_width']            = 3000;
        $config['max_height']           = 3000;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('background'))
        {
        	$error = array('error' => $this->upload->display_errors());
        	$response = response("fail", $error, "Failed create a display ");
			echo $response;
			die();
        }
        $post['background'] = $filenameimage;
        $post['background_update'] = 1;
        $post['created_by'] = $this->session->userdata('user-nya'); 
        $post['created_at'] = $datetime ;
		$post['updated_at'] = $datetime ;
        $resp = $this->Model_Admin->insertData('room_display', $post);
        if($resp){
	    	$response = response("success", array(), "Success create a display ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a display ");
			echo $response;
		}		
	}
	public function postSignage()
	{
		$datetime 			= date("Y-m-d H:i:s");
		$wh = array();
		$post = $_POST;
		$files = $_FILES;
		$random 		= random_string('numeric', 12);
		$dataar 	= array();
		$oriname 	= $files['signage']['name'];
		$spname 	= explode(".", $oriname);
		$ext 		= end($spname);
		$filenameimage = $random .".".$ext;
		$config['upload_path']          = './assets/file/display/signage/';
        $config['allowed_types']        = 'mp4|webm';
        $config['file_name']            = $filenameimage;
        $config['overwrite']			= true;
        $config['max_size']             = 50000; // < 20MB
        $config['max_width']            = 3000;
        $config['max_height']           = 3000;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('signage'))
        {
        	$error = array('error' => $this->upload->display_errors());
        	$response = response("fail", $error, $this->upload->display_errors());
			echo $response;
			die();
        }
        $post['signage_media'] = $filenameimage;
        $post['signage_update'] = 1;
        $post['updated_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
        $resp = $this->Model_Admin->updateData('room_display', $post, $wh);
        if($resp){
	    	$response = response("success", array(), "Success upload signage a display ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed upload signage a display ");
			echo $response;
		}		
	}
	// 
	// 
	// KIOSK DISPLAY
	// 
	// 

	public function kioskindex()
	{
		$pagename = "Display Kiosk";
		$module_display = $this->Model_Module->get_module_display();
		$modules = array();
		$modules['display'] = $module_display;
		$menu = $this->Model_Menu->getMenu($pagename);
		$this->load->view('Admin/Display/kiosk-index', array('menumaster'=> $menu, 'pagename' => $pagename));
		
		
	}
	public function getKioskData()
	{
		$data = $this->Model_Kiosk->getDataDisplay();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
		
	}
	
	// public function getDataRoomDisplay()
	// {
	// 	$data = $this->Model_Admin->getDataRoomDisplay();
	// 	if($data['error'] == null){
	// 		echo response("success", $data['data'], "Get success");
	// 	}else{
	// 		echo response("fail", $data, "Get failed");
	// 	}
		
	// }
	// public function postUpdate()
	// {
	// 	$datetime 			= date("Y-m-d H:i:s");
	// 	$wh = array();
	// 	$post = $_POST;
	// 	$files = $_FILES;

	// 	if($files['background']['name'] != "" ||$files['background']['name']  != null){
	// 		$random 	= random_string('numeric', 12);
	// 		$dataar 	= array();
	// 		$oriname 	= $files['background']['name'];
	// 		$spname 	= explode(".", $oriname);
	// 		$ext 		= end($spname);
	// 		$filenameimage = $random .".".$ext;
	// 		$config['upload_path']          = './assets/file/display/background/';
	//         $config['allowed_types']        = 'gif|jpg|png|jpeg';
	//         $config['file_name']            = $filenameimage;
	//         $config['overwrite']			= true;
	//         $config['max_size']             = 10000; // < 10MB
	//         $config['max_width']            = 3000;
	//         $config['max_height']           = 3000;
	// 		$this->load->library('upload', $config);
	// 		if (!$this->upload->do_upload('background'))
	//         {
	//         	$error = array('error' => $this->upload->display_errors());
	//         	$response = response("fail", $error, "Failed create a display ");
	// 			echo $response;
	// 			die();
	//         }
    //     	$post['background'] = $filenameimage;
    //     	$post['background_update'] = 1;
	// 	}
	// 	$where = array(
	// 		'room_id' => $post['id']
	// 	);
	// 	unset($post['id']);
    //     $post['updated_by'] = $this->session->userdata('user-nya'); 
	// 	$post['updated_at'] = $datetime ;
		
    //     $resp = $this->Model_Admin->updateData('room_display', $post, $where);
    //     if($resp){
	//     	$response = response("success", array(), "Success update a display ");
	// 		echo $response;
	// 	}else{
	// 		$response = response("fail", array(), "Failed update a display ");
	// 		echo $response;
	// 	}		
	// }
	public function postKioskCreated()
	{
		$datetime 			= date("Y-m-d H:i:s");
		$wh = array();
		$post = $_POST;

        $post['display_serial'] = random_string('nozero', 6);
		$post['updated_at'] = $datetime ;
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] = 0 ;
		$post['is_logged'] = 0 ;
        $resp = $this->Model_Admin->insertData('kiosk_display', $post);
        if($resp){
	    	$response = response("success", array(), "Success create a kiosk/display ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a kiosk/display ");
			echo $response;
		}		
	}
	public function postKioskUpdated()
	{
		$datetime 			= date("Y-m-d H:i:s");
		$wh = array();
		$post = $_POST;
		$post['updated_at'] = $datetime ;
		$where = array(
			'id' => $post['id']
		);
		unset($post['id']);
        $resp = $this->Model_Admin->updateData('kiosk_display', $post, $where);
        if($resp){
	    	$response = response("success", array(), "Success update a kiosk/display ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a kiosk/display ");
			echo $response;
		}		
	}
	public function logoutKiosk()
	{
		$datetime 			= date("Y-m-d H:i:s");
		$wh = array();
		$post = $_POST;
		$post['is_logged'] = 0;
		$where = array(
			'id' => $post['id']
		);
		unset($post['id']);
        $resp = $this->Model_Admin->updateData('kiosk_display', $post, $where);
        if($resp){
	    	$response = response("success", array(), "Success logout a kiosk/display ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed logout a kiosk/display ");
			echo $response;
		}		
	}

	
}

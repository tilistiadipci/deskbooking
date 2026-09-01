<?php  

date_default_timezone_set(APP_GMT);
class User extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Access');
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
	public function index(){
		$pagename = "User";
		$menu = $this->Model_Menu->getMenu($pagename);
		$module_booking = $this->Model_Module->get_module_booking();
		$module_pantry = $this->Model_Module->get_module_pantry();
		$module_desk = $this->Model_Module->get_module_desk();
		$data_acccess = $this->Model_Access->getDataListAccessUser();
		$modules = array();
		$modules['booking'] = $module_booking;
		$modules['pantry'] = $module_pantry;
		$modules['desk'] = $module_desk;
		$modules['room_adv'] = $this->Model_Module->get_module_room_adv();
		$access = [];
		foreach ($data_acccess as $key => $value) {
			if($value['access_id'] == 1 && $module_booking['is_enabled'] == 1){
				array_push($access, $value);
			}else if($value['access_id'] == 2 && $module_pantry['is_enabled'] == 1){
				array_push($access, $value);
			}else if($value['access_id'] ==3  && $module_desk['is_enabled'] == 1){
				array_push($access, $value);
			}else if($value['access_id'] == 4  && $modules['room_adv']['is_enabled'] == 1){
				array_push($access, $value);
			}

			// code...
		}
		// print_r($modules);
		$this->load->view('Admin/User/index', array('menumaster'=> $menu, 'pagename' => $pagename, 
			'modules'=>$modules,
			'access'=>$access,
		));
	}


	public function groupDetail()
	{
		$id = $this->uri->segment(5);
		$data = $this->Model_Admin->getGroupDetail($id);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function postUpdateGroup()
	{
		$post = $_POST;
		$where = array(
			"id" => $post['id']
		);
		unset($post['id']);
		$resp = $this->Model_Admin->updateData('level', $post, $where);
		if($resp){
			record_activity('USER_UPDATED', [
				'description' => "Admin updated user group: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'info'
			]);
			echo response("success", array(), "Success update a group ".$post['name']);
		}else{
			echo response("fail", $resp, "Failed update a group ".$post['name']);
		}
	}
	public function getDataNotUser()
	{
		$data = $this->Model_Admin->getDataNotUser();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getDataGroup()
	{
		$data = $this->Model_Admin->getDataGroupUser();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getDataUser()
	{
		$data = $this->Model_Admin->getDataUser();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getDataUserDetail()
	{
		$id = $this->uri->segment(5);
		$data = $this->Model_Admin->getEditUser($id);
		if($data['error'] == null){
			$passEnc = $data['data']['password']; 
			$passDec = decryp_data($passEnc);
			$data['data']['password'] = $passDec;
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function postUpdateUser()
	{
		$post 				= $_POST;
		$datetime 			= date("Y-m-d H:i:s");
		$post['created_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] = 0;
		$id = $post['id'];
		$wh = array(
			'id'=>$id
		);
		$post['password'] 	= encryp_data(trim($post['password']));
		if(isset($post['access_id'])){
			$post['access_id'] = implode("#", $post['access_id']);
		}
		// print_r($post);
		// die();
		unset($post['id']);
		$resp = $this->Model_Admin->updateData('user', $post, $wh);
		if($resp){
			record_activity('USER_UPDATED', [
				'description' => "Admin updated user account",
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'info'
			]);
	    	$response = response("success", array(), "Success update a user ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a user ");
			echo $response;
		}
	}
	public function postCreateUser()
	{
		$post 				= $_POST;
		$datetime 			= date("Y-m-d H:i:s");
		$post['created_by'] = $this->session->userdata('user-nya'); 
		$post['password'] 	= encryp_data(trim($post['password']));
		$post['created_at'] = $datetime ;
		$post['updated_at'] = $datetime ;
		// print_r($post);
		// die();
		$resEMP 					= $this->Model_Admin->getEditEmployeeByID(trim($post['employee_id']));
		$getEmp				= $resEMP['data'];

		$post['name']		= $getEmp['name'];
		if(isset($post['access_id'])){
			$post['access_id'] = implode("#", $post['access_id']);
		}
		
		$resp = $this->Model_Admin->insertData('user', $post);
		if($resp){
			record_activity('USER_CREATED', [
				'description' => "Admin created user account: " . $post['username'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'success'
			]);
			echo response("success", array(), "Success create a user ");
		}else{
			echo response("fail", $resp, "Failed create a user ");
		}
	}
	public function postDeleteUser()
	{
		$post 				= $_POST;
		$datetime 			= date("Y-m-d H:i:s");
		$post['created_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] = 1;
		$id = $post['id'];
		$wh = array(
			'id'=>$id
		);
		unset($post['id']);
		$resp = $this->Model_Admin->updateData('user', $post, $wh);
		if($resp){
			record_activity('USER_DELETED', [
				'description' => "Admin deleted user account",
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'warning'
			]);
			echo response("success", array(), "Success delete a user ");
		}else{
			echo response("fail", $resp, "Failed delete a user ");
		}
	}
	public function postDisableUser()
	{
		$post 				= $_POST;
		$datetime 			= date("Y-m-d H:i:s");
		$post['created_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
		$id = $post['id'];
		$wh = array(
			'id'=>$id
		);
		// print_r($post);
		// die();
		unset($post['id']);
		$resp = $this->Model_Admin->updateData('user', $post, $wh);
		if($resp){
			echo response("success", array(), "Success disable a user ");
		}else{
			echo response("fail", $resp, "Failed disable a user ");
		}
	}
}

?>
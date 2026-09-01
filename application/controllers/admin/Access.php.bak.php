<?php  

date_default_timezone_set("Asia/Jakarta");
class Access extends CI_Controller {
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
	public function index(){
		$pagename = "Access";
		$menu = $this->Model_Menu->getMenu($pagename);

		$module_access_door = $this->Model_Module->get_module_access_door();
		$wdata = array(
			'is_deleted' => 0
		);
		$access_controller_type = $this->Model_Admin->select_all_data('access_controller_type',$wdata,array(), "result");
		// $module_price = $this->Model_Module->get_module_price();
		$modules = array();
		$modules['access_door'] = $module_access_door;
		$this->load->view('Admin/Access/index', array(
			'access_controller_type' =>json_encode($access_controller_type),
			'menumaster'=> $menu, 'pagename' => $pagename, 'modules'=>$modules));
	}
	public function getEdit()
	{
		$id = $this->uri->segment(4);
		$data = array();
		$access = $this->Model_Admin->getDataAccessEdit($id);
		$integrate = $this->Model_Admin->getDataIntegrated($id);
		if($access['error'] != null || $integrate['error'] != null ){
			$data['error'] = "error get data";
		}else{
			$data['error'] = null;
		}
		$data['data'] = array();
		$data['data']['access'] =  $access['data'];
		$data['data']['integrate'] =  $integrate['data'];
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getData()
	{
		$data = $this->Model_Admin->getDataAccess();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getDataChannel()
	{
		$data = $this->Model_Admin->getDataChannel();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}


	public function getAccessIntegrated()
	{
		$id = $this->uri->segment(5);
		$data = $this->Model_Admin->getDataIntegrated($id);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
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
	
	public function postUpdateAccess()
	{
		$post 				= $_POST;
		// echo "<pre>";
		// print_r($post);
		// die();
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");
		$post['updated_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] = 0;
		$id = $post['id'];
		$where = array('id'=>$id);
		if (isset($post['room'])) {
			foreach ($post['room'] as $key => $value) {
				$wh = array(
					"access_id" => $id,
					"room_id" => $value
				);
				$delete = $this->Model_Admin->deleteData('access_integrated', $wh);
				$daa = array(
					"access_id" => $id,
					"room_id" => $value,
					"is_deleted" => 0
				);
				array_push($arrayroom, $daa);
			}
		}else{
			// delete 
			$wh = array(
					"access_id" => $id,
			);
			$delete = $this->Model_Admin->deleteData('access_integrated', $wh);
		}
		
		unset($post['room']);
		unset($post['id']);
		if(count($arrayroom) > 0 ){
			$resp = $this->Model_Admin->insertDataBatch('access_integrated', $arrayroom);
		}
		$resp = $this->Model_Admin->updateData('access_control', $post, $where);
		if($resp){
	    	$response = response("success", array(), "Success update a access ");
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a access ");
			echo $response;
		}
	}
	public function postCreate()
	{
		$radid 				= random_string('numeric', 10);
		$post 				= $_POST;
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");
		$post['created_by'] = $this->session->userdata('user-nya'); 
		$post['created_at'] = $datetime ;
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] = 0 ;
		$post['id'] 		= $radid ;
		foreach ($post['room'] as $key => $value) {
			$daa = array(
				"access_id" => $radid,
				"room_id" => $value,
				"is_deleted" => 0
			);
			array_push($arrayroom, $daa);
		}
		if(count($arrayroom) > 0 ){
			$resp = $this->Model_Admin->insertDataBatch('access_integrated', $arrayroom);
		}
		unset($post['room'] );
		$resp = $this->Model_Admin->insertData('access_control', $post);
		if($resp){
			echo response("success", array(), "Success create a access control ");
		}else{
			echo response("fail", $resp, "Failed create a access control ");
		}
	}
	public function postAssign()
	{
		$radid 				= random_string('numeric', 10);
		$post 				= $_POST;
		$jsonObj 			= json_decode($post['strdata'], true);
		$access 			= $post['access'];
		foreach ($jsonObj as $key => $value) {
			$where = array(
				"access_id" => $access,
				"room_id" => $value['room']
			);
			$arrayCheck = $this->Model_Admin->checkIntegrated($where);
			$check = $arrayCheck['data'];
			if($value['status'] == 1){
				if (count($check) == 0 ) {
					$inss = array(
						"access_id" => $access,
						"room_id" => $value['room'],
						"is_deleted" => 0
					);
					$resp = $this->Model_Admin->insertData('access_integrated', $inss);
				}
			}else{
				$wh = array(
						"access_id" => $access,
						"room_id" => $value['room']
					);
				$delete = $this->Model_Admin->deleteData('access_integrated', $wh);
			}
			
		}
		echo response("success", array(), "Success save a access integrated ");
	}
	public function postDelete()
	{
		$post 				= $_POST;
		$datetime 			= date("Y-m-d H:i:s");
		$post['updated_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] = 1;
		$id = $post['id'];
		$wh = array('id'=>$id);
		unset($post['id']);
		$resp = $this->Model_Admin->updateData('access_control', $post, $wh);
		if($resp){
			echo response("success", array(), "Success delete a access ");
		}else{
			echo response("fail", $resp, "Failed delete a access ");
		}
	}
}

?>
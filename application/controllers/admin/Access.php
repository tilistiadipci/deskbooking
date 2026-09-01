<?php  

date_default_timezone_set(APP_GMT);
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
		$r_controller_type = $this->Model_Admin->select_all_data("access_controller_type", array("is_deleted" => 0), array(), 'result');
		$text_controller_type = json_encode($r_controller_type);
		// $module_price = $this->Model_Module->get_module_price();
		$modules = array();
		$modules['access_door'] = $module_access_door;
		$this->load->view('Admin/Access/index', array('menumaster'=> $menu, 'controller_type' =>$text_controller_type ,'pagename' => $pagename, 'modules'=>$modules));
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
		if($post['type'] == "custid"){
			unset($post['falco_unit_no']);
			unset($post['door'] );
			unset($post['falco_group_access'] );
		}else if($post['type'] == "falcoid"){
			$doorid = empty($post['door']) ? "" :$post['door'];
			$group_access = $post['falco_group_access'];
			$falco_unit_no= isset($post['falco_unit_no']) ? $post['falco_unit_no'] : "";
			$R_falco = $this->Model_Admin->select_all_data("access_controller_falco", array("access_id" => $id), array(), 'result');
			if(count($R_falco) > 0){
				$rfalco = $R_falco[0];
				$dfalco =array("access_id" => $id, 'group_access' => $group_access, 'unit_no' => $falco_unit_no);
				$this->Model_Admin->updateData('access_controller_falco', $dfalco,  array("id" => $rfalco['id']));
			}else{
				$dfalco =array("access_id" => $id, "group_access" => $group_access ,'unit_no' => $falco_unit_no);
				$this->Model_Admin->insertData('access_controller_falco', $dfalco);
			}
			unset($post['door']);
			unset($post['falco_group_access'] );
			unset($post['falco_unit_no']);
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
			// print_r($post);
			// die();
		foreach ($post['room'] as $key => $value) {
			$daa = array(
				"access_id" => $radid,
				"room_id" => $value,
				"is_deleted" => 0
			);
			array_push($arrayroom, $daa);
		}
		if($post['type'] == "custid"){
			unset($post['door'] );
			unset($post['falco_group_access']);
			unset($post['falco_unit_no']);
			unset($post['falco_ip']);

		}else if($post['type'] == "falcoid"){
			// $doorid = empty($post['door']) ? "" :$post['door'];
			$group_access= $post['falco_group_access'];
			$falco_ip= $post['ip_controller'];
			$falco_unit_no= isset($post['falco_unit_no']) ? $post['falco_unit_no'] : "";
			unset($post['falco_group_access']);
			unset($post['falco_ip']);
			unset($post['falco_unit_no']);
			// $doorid = $post['door'];
			$R_falco = $this->Model_Admin->select_all_data("access_controller_falco", array("access_id" => $radid), array('is_deleted' => 0), 'result');
			if(count($R_falco) > 0){
				$rfalco = $R_falco[0];
				$dfalco =array("access_id" => $radid, "group_access" => $group_access, "falco_ip"=>$falco_ip, 'unit_no' => $falco_unit_no);
				$this->Model_Admin->updateData('access_controller_falco', $dfalco,  array("id" => $rfalco['id']));
			}else{
				$dfalco =array("access_id" => $radid, "group_access" => $group_access, "falco_ip"=>$falco_ip, 'unit_no' => $falco_unit_no);
				$this->Model_Admin->insertData('access_controller_falco', $dfalco);
			}
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

		$this->Model_Admin->updateData('access_controller_falco', array("is_deleted" => 1),  array("id" => $id ));
		$resp = $this->Model_Admin->updateData('access_control', $post, $wh);
		if($resp){
			echo response("success", array(), "Success delete a access ");
		}else{
			echo response("fail", $resp, "Failed delete a access ");
		}
	}
}

?>
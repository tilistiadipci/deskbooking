<?php  

date_default_timezone_set(APP_GMT);
class Alocation extends CI_Controller {
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
		$pagename = "Company/Department";
		$menu = $this->Model_Menu->getMenu($pagename);

		$module_access_alocation = $this->Model_Module->get_module_alocation();
		// $module_price = $this->Model_Module->get_module_price();
		$modules = array();
		$modules['alocation'] = $module_access_alocation;
		$this->load->view('Admin/Alocation/index', array('menumaster'=> $menu, 'pagename' => $pagename, 'modules'=>$modules));
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
	public function getDataType()
	{
		$data = $this->Model_Admin->getDataAlocationType();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getDataAssignAlocation()
	{
		$id = $this->uri->segment(5);
		// $id = 
		$data = $this->Model_Admin->getDataAssignAlocation($id);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}

	public function getDataAlocation()
	{
		$data = $this->Model_Admin->getDataAlocationData();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}

	public function postCreateType()
	{
		$post = $_POST;
		$datetime = date("Y-m-d H:i:s");
		$post['created_at'] = $datetime ;
		$post['created_by'] = $this->session->userdata('user-nya');  
		// $post['updated_at'] =  ;
		$post['is_deleted'] =0;
		$resp = $this->Model_Admin->insertData('alocation_type', $post);
		if($resp){
			record_activity('ALOCATION_CREATED', [
				'description' => "Admin created alocation type: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'success'
			]);
	    	$response = response("success", array(), "Success create a alocation type ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a alocation type ".$post['name']);
			echo $response;
		}
	}
	public function postUpdateType()
	{
		$post = $_POST;
		$id = $post['id'];
		$wh = array(
			'id'=>$id
		);
		unset($post['id']);
		$datetime = date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime ;
		$post['updated_by'] = $this->session->userdata('user-nya') ;
		// unset($post['id']);
		$resp = $this->Model_Admin->updateData('alocation_type', $post, $wh);
		if($resp){
			record_activity('ALOCATION_UPDATED', [
				'description' => "Admin updated alocation type: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'info'
			]);
	    	$response = response("success", array(), "Success update a alocation type ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a alocation type ".$post['name']);
			echo $response;
		}
	}
	public function postDeleteType()
	{
		$post = $_POST;
		$id = $post['id'];
		$wh = array(
			'id'=>$id
		);
		unset($post['id']);
		$datetime = date("Y-m-d H:i:s");
		$datap['is_deleted'] = 1 ;
		$datap['updated_by'] = $this->session->userdata('user-nya') ;
		$datap['updated_at'] = $datetime ;
		// unset($post['id']);
		$resp = $this->Model_Admin->updateData('alocation_type', $datap, $wh);
		if($resp){
			record_activity('ALOCATION_DELETED', [
				'description' => "Admin deleted alocation type: " . $post['name'],
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'severity' => 'warning'
			]);
	    	$response = response("success", array(), "Success delete a alocation type ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a alocation type ".$post['name']);
			echo $response;
		}
	}
	public function postAssign()
	{
		$id 				= random_string('numeric', 10);
		$post 				= $_POST;
		$jsonObj 			= json_decode($post['strdata'], true);
		$alocation 			= $post['alocation'];
		foreach ($jsonObj as $key => $value) {
			// print_r($value);
			$where = array(
				"alocation_id" => $alocation ,
				"nik" => $value['nik']
			);
			$arrayCheck = $this->Model_Admin->checkAssignAlocation($where);
			$check = $arrayCheck['data'];
			if($value['status'] == 1){
				if (count($check) == 0 ) {
					$inss = array(
						"alocation_id" => $alocation,
						"nik" => $value['nik'],
					);
					$resp = $this->Model_Admin->insertData('alocation_matrix', $inss);
				}
			}else{
				$wh = array(
						"alocation_id" => $alocation,
						"nik" => $value['nik']
					);
				$delete = $this->Model_Admin->deleteData('alocation_matrix', $wh);
			}
			
		}
		echo response("success", array(), "Success save a alocation assign ");
	}

	public function postAlocation()
	{
		$type = $this->uri->segment(3);
		switch ($type) {
			case 'create':
				$post = $_POST;
				$datetime = date("Y-m-d H:i:s");
				$gen = date('YmdHis');
				$kode = $post['id'];
				$post['id'] = $gen.random_string('alnum', 4);
				// print_r($post);
				// die();
				if($kode == ""){
					$kode = $gen;
				}

				$ckdata = array(
					'id' => $post['id'],
					'is_deleted' => 0,
				);
				
				$post['department_code'] = $kode  ;
				$post['created_at'] = $datetime ;
				$post['created_by'] = $this->session->userdata('user-nya');  
				$post['is_deleted'] =0;
				$resp = $this->Model_Admin->insertData('alocation', $post);
				if($resp){
			    	$response = response("success", array(), "Success create a department ".$post['name']);
					echo $response;
				}else{
					$response = response("fail", array(), "Failed create a department ".$post['name']);
					echo $response;
				}
				break;
			case 'update':
				$post = $_POST;
				$id = $post['id'];
				$wh = array(
					'id'=>$id
				);
				// print_r($post);
				// die();

				unset($post['id']);
				$datetime = date("Y-m-d H:i:s");
				$post['updated_at'] = $datetime ;
				$post['updated_by'] = $this->session->userdata('user-nya') ;
				// unset($post['id']);
				$resp = $this->Model_Admin->updateData('alocation', $post, $wh);
				if($resp){
			    	$response = response("success", array(), "Success update a department ".$post['name']);
					echo $response;
				}else{
					$response = response("fail", array(), "Failed update a department ".$post['name']);
					echo $response;
				}
				break;
			case 'delete':
				$post = $_POST;
				$datetime = date("Y-m-d H:i:s");
				$d = array(
					'is_deleted' => 1
				);
				$d['updated_at'] = $datetime ;
				$d['updated_by'] = $this->session->userdata('user-nya') ;
				$w = array ( "id"=>$post['id']); // id 
				$resp = $this->Model_Admin->updateData('alocation', $d, $w);
				if($resp){
			    	$response = response("success", array(), "Success delete a department ".$post['name']);
					echo $response;
				}else{
					$response = response("fail", array(), "Failed delete a department ".$post['name']);
					echo $response;
				}
				break;
			default:
				# code...
				break;
		}
		
	}
	
	
}

?>
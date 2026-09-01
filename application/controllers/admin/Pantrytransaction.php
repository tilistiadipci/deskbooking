<?php  

date_default_timezone_set(APP_GMT);
class Pantrytransaction extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
		 $this->load->model('Model_License');
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
		$module_price           = $this->Model_Module->get_module_price();
		$pagename = "Pantry Report";
		$menu 				= $this->Model_Menu->getMenu($pagename);
		$wr = array();
		$pantry  			= $this->Model_Admin->select_all_data("pantry", $wr ,array(), "result");
		$modules 			= array();
		$module_access_door = $this->Model_Module->get_module_access_door();
		$modules['price']   = $module_price;
		$this->load->view('Admin/PantryTransaction/index', 
			array(
				'menumaster'	=> $menu, 
				'pantry'      	=> json_encode($pantry),
				'pagename' 		=> $pagename, 
				'modules'		=> $modules,
			)
		);
	}
	
	public function getData()
	{
		$get = $_GET;
		$start = $get['start'] == "" ? date("Y-m-d"): $get['start'];
		$end = $get['end'] == "" ? date("Y-m-d") : $get['end']  ;
		$idPantry = $get['pantry'] == "" ? "" : $get['pantry']   ;
		// print_r($idPantry);
		// die();
		$data = $this->Model_Admin->getDataPantryTransactin($idPantry,"" ,$start,$end, "result");
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	} 

	public function getPantryData()
	{
		$wr = array('is_deleted' => 0);
		$data = $this->Model_Admin->select_all_data("pantry", $wr ,array(), "result");
		echo response("success", $data, "Get success");
	}
	// // // // // // // // // // // // // / / / /// /// // / / / / // / //////////////
	// // // // // // /// /// // / / / / / / // / / / // // // /// /// /// /// // //// /// /// // // / / //// // //// / // / / / / / //// // // / / / // // // // // // // // // // // // //
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
	
}

?>
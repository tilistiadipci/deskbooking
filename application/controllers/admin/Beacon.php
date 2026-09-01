<?php  
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Reader;

date_default_timezone_set("Asia/Jakarta");
class Beacon extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Beacon');
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


		$pagename = "Beacon Tag";
		$menu = $this->Model_Menu->getMenu($pagename);

		$module= $this->Model_Module->get_module_beacon();
		$modules = array();
		$modules['beacon'] = $module;
		$this->load->view('Admin/Beacon/index', array('menumaster'=> $menu,'pagename' => $pagename, 'modules'=>$modules));
	}

	public function getData()
	{
		$id = isset($_POST['id']) ?$_POST['id'] : "" ;
		$w = array();
		if($id != ""){
			$w['b.id'] = $id;
		}
		$data = $this->Model_Beacon->getBeaconTag($w);

		if($id != ""){
			if($data->num_rows() <= 0){

				echo response("fai;", array(), "Beacon not exist, please try refresh page again!");
			}else{

				echo response("success", $data->row_array(), "Get success");
			}
		}else{
			echo response("success", $data->result_array(), "Get success");
		}
		
	}

	public function getEmployeeNoBeacon()
	{
		$id = isset($_POST['id']) ?$_POST['id'] : "" ;
		$w = "";
		if($id != ""){
			$w = " (b.beacon_name is NULL OR b.beacon_employee='".$id."' )";
		}else{
			$w = " (b.beacon_name is NULL)";
		}
		$data = $this->Model_Beacon->getEmployeeBeacon($w);
		echo response("success", $data->result_array(), "Get success");
	}
	public function postUpload()
	{
		include APPPATH.'third_party/phpspreadsheet/autoload.php';
		$radid 				= random_string('numeric', 3);
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			if($ext == 'csv'){
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else if($ext == 'xlsx') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else if($ext == 'xls') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else{
            	$response = response("fail", array(), "Extension isn't CSV, XLSX, or XLS ");
				echo $response;
				die();
            }
		$post 				= $_POST;
		$file 				= $_FILES;
		$newname = date("YmdHis")."_".$radid.".".$ext;
		$path = './assets/file/beacon/';
		$fullpath = './assets/file/beacon/'.$newname;
		$_FILES['file']['name'] = $newname;
		$config['upload_path']          = $path;
	    $config['allowed_types']        = '*';
	    $config['detect_mime']        = FALSE;
	    $config['max_size']             = 10000000;

	    $datetime 			= date("Y-m-d H:i:s");

	    $this->load->library('upload', $config);
	    if ( ! $this->upload->do_upload('file')){
			$error = array('error' => $this->upload->display_errors());
			$response = response("fail", $error , "Failed upload batch to beacon ");
			echo $response;
			die();
		}

		$spreadsheet = $reader->load($fullpath);
		$allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		unset($allDataInSheet[1]);
		$batchInsert = array();
		foreach ($allDataInSheet as $k => $value) {
			$insert = array();
			$insert['beacon_name'] = $value['B'];
			$insert['beacon_mac'] = $value['C'];
			$insert['beacon_qr'] = $value['D'];
			$insert['beacon_card_no'] = $value['E'];
			if( $value['F'] == "NULL" ||  $value['F'] == null){

			}else{
				$insert['beacon_employee'] = $value['F'];
				$insert['is_registered'] = 1;
			}
			$insert['created_by'] = $this->session->userdata('user-nya'); 
			$insert['created_at'] = $datetime ;
			$insert['updated_at'] = $datetime ;
			$insert['is_deleted'] = 0 ;

			array_push($batchInsert, $insert);
		}
		if(count($batchInsert) > 0){
			$resp = $this->Model_Admin->insertDataBatch('beacon_tag', $batchInsert);
		}
		echo response("success", array(), "Success create batch a beacon tag ");

	}

	public function postCreate()
	{
		// $radid 				= random_string('numeric', 10);
		$post 				= $_POST;
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");
		$post['created_by'] = $this->session->userdata('user-nya'); 
		$post['created_at'] = $datetime ;
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] = 0 ;
		if($post['created_by'])

		$resp = $this->Model_Admin->insertData('beacon_tag', $post);
		echo response("success", array(), "Success create a beacon tag ");
	}

	public function postUpdate()
	{
		$post 				= $_POST;
		$id 				= isset($post['id']) ? $post['id'] : "";
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");

		unset($post['id']);
		$post['updated_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
		$w = array("id" => $id);

		$resp = $this->Model_Admin->updateData('beacon_tag', $post, $w);
		echo response("success", array(), "Success update a beacon tag ");
	}
	public function postDelete()
	{
		$post 				= $_POST;
		$id 				= isset($post['id']) ? $post['id'] : "";
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");

		unset($post['id']);
		$post['updated_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] = 1 ;
		$w = array("id" => $id);

		$resp = $this->Model_Admin->updateData('beacon_tag', $post, $w);
		echo response("success", array(), "Success delete a beacon tag ");
	}

	// 
	// 
	// FLOOR
	// 
	// 

	public function floorIndex(){


		$pagename = "Floor";
		$menu = $this->Model_Menu->getMenu($pagename);

		$module= $this->Model_Module->get_module_beacon();
		$modules = array();
		$modules['beacon'] = $module;
		$dbuilding = $this->Model_Admin->select_all_data('building',array('is_deleted' => 0), array(), 'result');
		$data = array();
		$data['building'] = json_encode($dbuilding);
		$this->load->view('Admin/Beacon/floorIndex', array('menumaster'=> $menu,'pagename' => $pagename, 'modules'=>$modules, 'data' => $data));
	}

	public function getFloorData()
	{
		$id = isset($_POST['id']) ?$_POST['id'] : "" ;
		$w = array();
		if($id != ""){
			$w['f.id'] = $id;
		}
		$data = $this->Model_Beacon->getFloor($w);
		if($id != ""){
			if($data->num_rows() <= 0){
				echo response("fail", array(), "Floor not exist, please try refresh page again!");
			}else{
				echo response("success", $data->row_array(), "Get success");
			}
		}else{
			echo response("success", $data->result_array(), "Get success");
		}
		
	}

	public function postFloorCreate()
	{
		$radid 				= random_string('numeric', 3);
		$post 				= $_POST;
		$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

		$newname = date("YmdHis")."_floor_".$radid.".".$ext;
		$_FILES['image']['name'] = $newname;

		$path = './assets/file/beaconfloor/';
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");
		$post['created_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
		
		$post['is_deleted'] = 0 ;

		$config['upload_path']          = $path;
	    $config['allowed_types']        = '*';
	    $config['detect_mime']        	= FALSE;
	    $config['max_size']             = 10000000;

	    $this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('image')){
			$error = array('error' => $this->upload->display_errors(), "file" => $_FILES['image']);
			echo response("fail",$error,  "Failed upload image  ");
			die();
		}
		$post['image'] = $newname;
		$resp = $this->Model_Admin->insertData('beacon_floor', $post);
		echo response("success", array(), "Success create a beacon floor ");

	}

	public function postFloorUpdate()
	{
		$radid 				= random_string('numeric', 3);
		$post 				= $_POST;
		$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
		
		$id = isset($post['id']) ? $post['id'] : "";
		$path = './assets/file/beaconfloor/';
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] = 0 ;
		if($_FILES['image']['name'] != null){
			$newname = date("YmdHis")."_floor_".$radid.".".$ext;
			$_FILES['image']['name'] = $newname;
			$config['upload_path']          = $path;
		    $config['allowed_types']        = '*';
		    $config['detect_mime']        	= FALSE;
		    $config['max_size']             = 10000000;

		    $this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('image')){
				$error = array('error' => $this->upload->display_errors(), "file" => $_FILES['image']);
				echo response("fail",$error,  "Failed upload image  ");
				die();
			}
			$post['image'] = $newname;

		}
		unset($post['id']);
		$w = array("id" => $id);
		$resp = $this->Model_Admin->updateData('beacon_floor', $post, $w);
		echo response("success", array(), "Success update a beacon floor ");

	}
	public function postFloorDelete()
	{
		$post 				= $_POST;
		$id 				= isset($post['id']) ? $post['id'] : "";
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");

		unset($post['id']);
		$post['updated_by'] = $this->session->userdata('user-nya'); 
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] = 1 ;
		$w = array("id" => $id);

		$resp = $this->Model_Admin->updateData('beacon_floor', $post, $w);
		echo response("success", array(), "Success delete a beacon floor ");
	}

	// 
	// 
	// ROOMM
	// 
	// 
	public function createFloorRoom(){
		$pagename = "Create Floor Room";
		$menu = $this->Model_Menu->getMenu($pagename);
		$module= $this->Model_Module->get_module_beacon();
		$modules = array();
		$modules['beacon'] = $module;
		$dbuilding = $this->Model_Admin->select_all_data('building',array('is_deleted' => 0), array(), 'result');
		$dfloor = $this->Model_Admin->select_all_data('beacon_floor',array('is_deleted' => 0), array(), 'result');
		$droom = $this->Model_Beacon->getRoom(array('is_beacon' => 0))->result_array();
		$dfloor_room = $this->Model_Beacon->getFloorRoom(array('is_beacon' => 1))->result_array();
		$data = array();
		$data['building'] = json_encode($dbuilding);
		$data['floor'] = json_encode($dfloor);
		$data['room'] = json_encode($droom);
		$data['floor_room'] = json_encode($dfloor_room);
		$this->load->view('Admin/Beacon/roomCreateIndex', array('menumaster'=> $menu,'pagename' => $pagename, 'modules'=>$modules, 'data' => $data));
	}

	public function postFloorRoomCreate(){
		// print_r($_POST);
		$room_id = isset($_POST['room_id']) ? $_POST['room_id']: "";
		$floor_id = isset($_POST['floor_id']) ? $_POST['floor_id']: "";
		$building_id = isset($_POST['building_id']) ? $_POST['building_id']: "";
		$ww = array();
		if($room_id != ""){
			$ww['radid'] =$room_id;
		}
		$droom = $this->Model_Beacon->getRoom($ww)->row_array();
		if(!isset($droom['radid']) ){
			echo response("fail", array(), "Room not exist, please try refresh page again!");
			die();
		}
		$length = isset($_POST['length']) ? $_POST['length']: 0;
		$width = isset($_POST['width']) ? $_POST['width']: 0;
		$shapes = json_encode($_POST['shape']);
		$wide = ($length - 0) * ($width -0);

		$x = isset($_POST['x']) ? $_POST['x']: 0;
		$y = isset($_POST['y']) ? $_POST['y']: 0;
		$position_px = $x .",".$y;
		$dataa = [
			'floor_id' => $floor_id,
			'building_id' => $building_id,
			'room_id' => $room_id,
			'length' => $length-0, 
			'width' => $length- 0, 
			'wide' => $wide,
			'shape' => $shapes,
			'position_px' => $position_px,
			'is_deleted' =>0,
		];

		$ckROOM = $this->Model_Admin->select_all_data('beacon_floor_room',array('is_deleted' => 0, 'room_id' => $room_id), array(), 'result');
		if(count($ckROOM) <= 0){
			$wwipdate2 = [
				'radid' => $room_id
			];
			$resp = $this->Model_Admin->insertData('beacon_floor_room', $dataa);
			$resp = $this->Model_Admin->updateData('room', array('is_beacon' => 1), $wwipdate2);
		}else{
			$wwipdate = [
				'room_id' => $room_id
			];
			$wwipdate2 = [
				'radid' => $room_id
			];
			unset($dataa['room_id']);
			$resp = $this->Model_Admin->updateData('beacon_floor_room', $dataa, $wwipdate);
			$resp = $this->Model_Admin->updateData('room', array('is_beacon' => 1), $wwipdate2);
		}
		echo response("success", array(), "Save a beacon room success");
	}
	public function postFloorRoomDelete()
	{
		$post 				= $_POST;
		$id 				= isset($post['id']) ? $post['id'] : "";
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");
		$ckROOM = $this->Model_Admin->select_all_data('beacon_floor_room',array('id' => $id), array(), 'result');
		if(count($ckROOM) <= 0){
			echo response("fail", array(), "Room not exist, please try refresh page again!");
			die();
		}
		$rrr = $ckROOM[0];
		unset($post['id']);
		// $post['updated_by'] = $this->session->userdata('user-nya'); 
		// $post['updated_at'] = $datetime ;
		$post['is_deleted'] = 1 ;
		$w = array("id" => $id);
		$wwipdate2 = [
			'radid' => $rrr['room_id']
		];
		$resp = $this->Model_Admin->updateData('beacon_floor_room', $post, $w);
		$resp = $this->Model_Admin->updateData('room', array('is_beacon' => 0), $wwipdate2);
		echo response("success", array(), "Success delete a beacon floor room ");
	}

	public function getFloorRoomData()
	{
		$id = isset($_POST['id']) ?$_POST['id'] : "" ;
		$w = array();
		if($id != ""){
			$w['fr.id'] = $id;
		}
		// $w['r.is_beacon'] = 1;

		$data = $this->Model_Beacon->getFloorRoom($w);
		if($id != ""){
			if($data->num_rows() <= 0){
				echo response("fail", array(), "Floor Room Beacon not exist, please try refresh page again!");
			}else{
				echo response("success", $data->row_array(), "Get success");
			}
		}else{
			echo response("success", $data->result_array(), "Get success");
		}
	}


	public function indexLv(){
		$pagename = "Live Transaction";
		$menu = $this->Model_Menu->getMenu($pagename);

		$module= $this->Model_Module->get_module_beacon();
		$modules = array();
		$modules['beacon'] = $module;
		$data = array();
		$dbuilding = $this->Model_Admin->select_all_data('building',array('is_deleted' => 0), array(), 'result');
		$dfloor = $this->Model_Admin->select_all_data('beacon_floor',array('is_deleted' => 0), array(), 'result');
		$droom = $this->Model_Beacon->getRoom(array())->result_array();
		$data['building'] = json_encode($dbuilding);
		$data['floor'] = json_encode($dfloor);
		$data['room'] = json_encode($droom);
		$this->load->view('Admin/Beacon/indexLv', array('menumaster'=> $menu,'pagename' => $pagename, 'modules'=>$modules, 'data' => $data));
	}
	public function getLvTrs(){
		// print_r($_POST);
		$time_refresh = isset($_POST['time_refresh']) ? $_POST['time_refresh'] : 1 ;
		$floor = isset($_POST['floor']) ? $_POST['floor'] : "";
		$is_alarm_only = isset($_POST['is_alarm_only']) ? $_POST['is_alarm_only'] : false;
		$alarm = $is_alarm_only == true ?1:0;
		$w = array();
		if($floor != ""){
			$w['bt.floor_id'] = $floor;
		}
		if($alarm != ""){
			$w['bt.alarm'] = $alarm;
		}
		$data = $this->Model_Beacon->getBeaconMonitorEmployeeTrsLimit($w, 25, 0);
		echo response("success", $data->result_array(), "Get success");
	}
	public function monitor(){

		$pagename = "Monitor";
		$menu = $this->Model_Menu->getMenu($pagename);

		$module= $this->Model_Module->get_module_beacon();
		$modules = array();
		$modules['beacon'] = $module;

		$data = array();
		$dbuilding = $this->Model_Admin->select_all_data('building',array('is_deleted' => 0), array(), 'result');
		$dfloor = $this->Model_Admin->select_all_data('beacon_floor',array('is_deleted' => 0), array(), 'result');
		$droom = $this->Model_Beacon->getRoom(array())->result_array();
		$data['building'] = json_encode($dbuilding);
		$data['floor'] = json_encode($dfloor);
		$data['room'] = json_encode($droom);

		$this->load->view('Admin/Beacon/monitor', array('menumaster'=> $menu,'pagename' => $pagename, 'modules'=>$modules, 'data' => $data));
	}
	public function monitorGetGateway()
	{
		$building_id = isset($_POST['building_id']) ?$_POST['building_id'] : "" ;
		$floor_id = isset($_POST['floor_id']) ?$_POST['floor_id'] : "" ;
		$w = array();
		$w['floor_id'] = $floor_id;
		$data = $this->Model_Beacon->getGateway($w);
		echo response("success", $data->result_array(), "Get success");
		
	}

	public function monitorGetBeacon()
	{
		$building_id = isset($_POST['building_id']) ?$_POST['building_id'] : "" ;
		$floor_id = isset($_POST['floor_id']) ?$_POST['floor_id'] : "" ;
		$w = array();
		$w['floor_id'] = $floor_id;
		$data = $this->Model_Beacon->getBeaconMonitorEmployeeTrs($w);
		echo response("success", $data->result_array(), "Get success");
		
	}

	// 
	// 
	// GATEWAYY
	// 
	// 
	public function beaconGatewayIndex(){


		$pagename = "Beacon Gateway";
		$menu = $this->Model_Menu->getMenu($pagename);
		$dbuilding = $this->Model_Admin->select_all_data('building',array('is_deleted' => 0), array(), 'result');
		$dfloor = $this->Model_Admin->select_all_data('beacon_floor',array('is_deleted' => 0), array(), 'result');
		$droom = $this->Model_Beacon->getRoom(array())->result_array();
		$data = [];
		$data['building'] = json_encode($dbuilding);
		$data['floor'] = json_encode($dfloor);
		$data['room'] = json_encode($droom);
		$module= $this->Model_Module->get_module_beacon();
		$modules = array();
		$modules['beacon'] = $module;
		$this->load->view('Admin/Beacon/indexGateway', array('menumaster'=> $menu,'pagename' => $pagename, 'modules'=>$modules, 'data' => $data));
	}
	public function beaconGatewayEditor(){

		$pagename = "Beacon Gateway Editor";
		$floor_id = isset($_GET['floor']) ? $_GET['floor'] : "";
		$room_id = isset($_GET['room']) ? $_GET['room'] : "";
		$selector1 = isset($_GET['selector1']) ? $_GET['selector1'] : "";
		$selector2 = isset($_GET['selector2']) ? $_GET['selector2'] : "";
		$pointer = isset($_GET['pointer']) ? $_GET['pointer'] : "";

		$module= $this->Model_Module->get_module_beacon();
		$modules = array();
		$modules['beacon'] = $module;

		$data = array();
		$dfloor = $this->Model_Admin->select_all_data('beacon_floor',array('is_deleted' => 0, 'id' =>$floor_id), array(), 'result');
		if(count($dfloor) <= 0){
			echo "Floor map is empty";
			die();
		}
		$rfloor = $dfloor[0];
		$data['floor'] = json_encode($rfloor);
		$data['room_id'] = $room_id;
		$data['selector1'] = $selector1;
		$data['selector2'] = $selector2;
		$data['pointer'] = $pointer;
		
		$this->load->view('Admin/Beacon/gatewayEditor', array('pagename' => $pagename, 'modules'=>$modules, 'data' => $data));
	}

	public function getBeaconGatewayData()
	{
		$id = isset($_POST['id']) ?$_POST['id'] : "" ;
		$w = array();
		if($id != ""){
			$w['b.id'] = $id;
		}
		$data = $this->Model_Beacon->getBeaconGateway($w);
		if($id != ""){
			if($data->num_rows() <= 0){
				echo response("fai;", array(), "Beacon not exist, please try refresh page again!");
			}else{
				echo response("success", $data->row_array(), "Get success");
			}
		}else{
			echo response("success", $data->result_array(), "Get success");
		}
		
	}


	public function postBeaconGatewayCreate()
	{
		// $radid 				= random_string('numeric', 10);
		$post 				= $_POST;
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");
		// $post['created_at'] = $datetime ;
		// $post['updated_at'] = $datetime ;
		$posPX = $post['location_px'];
		$spPosPX = explode(",", $posPX);
		$post['is_deleted'] = 0 ;
		$post['is_enabled'] = 1 ;
		$post['location_x'] = $spPosPX[0] ;
		$post['location_y'] = $spPosPX[1] ;
		// if($post['created_by'])
		// print_r($post);
		// die();
		$resp = $this->Model_Admin->insertData('beacon_gateway', $post);
		echo response("success", array(), "Success create a beacon gateway ");
	}

	public function postBeaconGatewayUpdate()
	{
		$post 				= $_POST;
		$id 				= isset($post['id']) ? $post['id'] : "";
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");

		unset($post['id']);


		$posPX = $post['location_px'];
		$spPosPX = explode(",", $posPX);
		// $post['is_deleted'] = 0 ;
		// $post['is_enabled'] = 1 ;
		$post['location_x'] = $spPosPX[0] ;
		$post['location_y'] = $spPosPX[1] ;

		// $post['updated_by'] = $this->session->userdata('user-nya'); 
		// $post['updated_at'] = $datetime ;
		$w = array("id" => $id);

		$resp = $this->Model_Admin->updateData('beacon_gateway', $post, $w);
		echo response("success", array(), "Success update a beacon gateway ");
	}
	public function postBeaconGatewayDelete()
	{
		$post 				= $_POST;
		$id 				= isset($post['id']) ? $post['id'] : "";
		$arrayroom 			= array();
		$datetime 			= date("Y-m-d H:i:s");

		unset($post['id']);
		// $post['updated_by'] = $this->session->userdata('user-nya'); 
		// $post['updated_at'] = $datetime ;
		$post['is_deleted'] = 1 ;
		$w = array("id" => $id);

		$resp = $this->Model_Admin->updateData('beacon_gateway', $post, $w);
		echo response("success", array(), "Success delete a beacon gateway ");
	}

	// Floor Area

	public function beaconFloorAreaRoomEditor()
	{
		$query = $_GET;
		if(!isset($query['building'])){
			$query['building'] = "";
		}
		if(!isset($query['floor'])){
			$query['floor'] = "";
		}

		$pagename = "Access";
		$wbuilding = array("is_deleted" => 0);
		$room_data = $this->Model_Admin->getDataRoom2();
		$building_data = $this->Model_Admin->getDataBuilding($wbuilding);
		$modules = array();

		$param = [
			'room' => $room_data['data'],
			'building' => $building_data['data'],
			'query' => $query,
		];
		$this->load->view('BeaconFloor/index', $param);
	}

	public function beaconFloorAreaRoomEditorFloorList()
	{
		$this->load->model('Model_Beacon');
		$id = isset($_GET['building_id']) ?$_GET['building_id'] : "" ;
		$w = array();
		// print_r($id);
		if($id != ""){
			$w['f.building_id'] = $id;
		}
		$data = $this->Model_Beacon->getFloor($w);
		if($id != ""){
			if($data->num_rows() <= 0){
				echo response("fail", array(), "Floor not exist, please try refresh page again!");
			}else{
				echo response("success", $data->result_array(), "Get success");
			}
		}else{
			echo response("success", $data->result_array(), "Get success");
		}
		
	}
	public function beaconFloorAreaRoomEditorFloorGetDataId()
	{
		$this->load->model('Model_Beacon');
		$id = isset($_GET['floor_id']) ?$_GET['floor_id'] : "" ;
		$w = array();
		// print_r($id);
		if($id != ""){
			$w['f.id'] = $id;
		}
		$data = $this->Model_Beacon->getFloor($w);
		if($id != ""){
			if($data->num_rows() <= 0){
				echo response("fail", array(), "Floor not exist, please try refresh page again!");
			}else{
				echo response("success", $data->row_array(), "Get success");
			}
		}else{
			echo response("fail", array(), "Floor not exist, please try refresh page again!");
		}
		
	}
	public function beaconFloorAreaRoomEditorGetFloorRoomArea()
	{
		$this->load->model('Model_Beacon');
		$id = isset($_GET['id']) ? $_GET['id'] : "" ;
		$floor_id = isset($_GET['floor_id']) ? $_GET['floor_id'] : "" ;
		$w = array();
		$w['fr.floor_id'] = $floor_id;
		if($id != ""){
			$w['fr.id'] = $id;
		}
		$data = $this->Model_Beacon->getFloorRoomArea($w);
		if($id != ""){
			if($data->num_rows() <= 0){
				echo response("fail", array(), "Floor Area Beacon not exist, please try refresh page again!");
			}else{
				echo response("success", $data->row_array(), "Get success");
			}
		}else{
			echo response("success", $data->result_array(), "Get success");
		}
	}

	public function beaconFloorAreaRoomEditorSaveFloorRoomArea()
	{
		$this->load->model('Model_Beacon');
		$listarea = isset($_POST['listarea']) ? $_POST['listarea'] : [];
		$listarea_delete = isset($_POST['listarea_delete']) ? $_POST['listarea_delete'] : [];
		$building = isset($_POST['building']) ? $_POST['building'] : "";
		$floor = isset($_POST['floor']) ? $_POST['floor'] : "";
		// print_r($_POST);
		// die();
		foreach ($listarea_delete as $key => $value) {
			if($value['id'] != null){
				$id = $value['id'];
				$where = ["id" =>$id];
				$data = ["is_deleted" => 1];
				$this->Model_Admin->updateData("beacon_floor_room",$data, $where);
			}
		}
		foreach ($listarea as $key => $value) {
			if($value['id'] != null){
				$id = $value['id'];
				$where = ["id" =>$id];
				$l = isset($value['width']) ? $value['width'] : 0;
				$w = isset($value['height']) ? $value['height'] : 0;
				$wide = ($w-0) * ($l-0);
				$data = [
					'floor_id' =>$floor,
					'building_id' =>$building,
					'room_id' => isset($value['room_id']) ? $value['room_id'] : "",
					'room_name' => isset($value['room_name']) ? $value['room_name'] : "",
					'length' => isset($value['length']) ? $value['length'] : 0,
					'width' => isset($value['width']) ? $value['width'] : 0,
					'wide' => $wide,
					'position_px' =>  isset($value['position_px']) ? $value['position_px'] : "",
					'is_deleted' => 0,
					'name' => isset($value['name']) ? $value['name'] : "",
					'shape' => isset($value['shape']) ? $value['shape'] : "",
				];
				$this->Model_Admin->updateData("beacon_floor_room",$data, $where);
			}else{
				$l = isset($value['width']) ? $value['width'] : 0;
				$w = isset($value['height']) ? $value['height'] : 0;
				$wide = ($w-0) * ($l-0);
				$data = [
					'floor_id' =>$floor,
					'building_id' =>$building,
					'room_id' => isset($value['room_id']) ? $value['room_id'] : "",
					'room_name' => isset($value['room_name']) ? $value['room_name'] : "",
					'length' => isset($value['length']) ? $value['length'] : 0,
					'width' => isset($value['width']) ? $value['width'] : 0,
					'wide' => $wide,
					'position_px' =>  isset($value['position_px']) ? $value['position_px'] : "",
					'is_deleted' => 0,
					'name' => isset($value['name']) ? $value['name'] : "",
					'shape' => isset($value['shape']) ? $value['shape'] : "",

				];
				$this->Model_Admin->insertData("beacon_floor_room",$data);
			}
		}
		echo response("success", array(), "Save/Update area success");
	}



}
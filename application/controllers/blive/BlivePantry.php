<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BlivePantry extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Api');
		$this->load->helper('response');
		

		// $this->load->model('', '', true);
	}	
	public function checkPantry()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$where = array(
				"is_deleted" => 0,
				"id" => $post['serial']
			);
			$select = "*, id as serial, id as pantry_id";
			$getData = $this->Model_Api->select_sql("pantry",$select,$where);
			$cn = $getData->num_rows();
			// print_r($cn);
			// if
			if($cn >= 1){
				$dataRes =  $getData->row_array();
				
				$response = response("success", $dataRes, "Success get data to room ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data1',
				);
				$response = response("fail", $rr, "Room not exist");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a room ");
			echo $response ;
		}
	}
	public function getPantry()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$where = array(
				"is_deleted" => 0,
				"nik" => $post['nik']
			);
			$select = "name, nik, email, card_number";
			$getData = $this->Model_Api->select_sql("employee",$select,$where);
			$cn = $getData->num_rows();
			// if
			if($cn == 1){
				$dataEmployee =  $getData->row_array();
				$res = $this->Model_Api->getPantry();
				$response = response("success", $res['data'], "Success get data to pantry ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry ");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry ");
			echo $response ;
		}
	}
	
	public function getDetail()
	{
		
		try{
			$json = file_get_contents("php://input");
			$post = json_decode($json, TRUE);
			$res = $this->Model_Api->getDetailPantry($post);
			if($res['error'] == null){
				$response = response("success", $res['data'], "Success get detail to pantry ");
				echo $response;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry ");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry ");
			echo $response ;
		}
	}
	public function getAll()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$room_id = $post['roomId'];
			$pantry_id = $post['pantryId'];
			$date1 = $post['date1'];
			$date2 = $post['date2'];

			$res = $this->Model_Api->getAllTrsPantryBlive($room_id, $pantry_id, $date1, $date2 );
			$response = response("success", $res['data'], "Success get data to pantry transaction ");
			echo $response ;
			
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry transaction ");
			echo $response ;
		}
	}
	public function getPlace()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$nik = $post['nik'];

			$res = $this->Model_Api->select_all_data('pantry', array('is_deleted' => 0),array('id pantry_id', 'name', 'pic'), 'result' );
			$response = response("success", $res, "Success get data to pantry place ");
			echo $response ;
			
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry place ");
			echo $response ;
		}
	}
	public function getMenu()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			// $nik = $post['nik'];
			$pantry_id = $post['pantry_id'];
			$res = $this->Model_Api->select_all_data('pantry_detail', array('is_deleted' => 0, 'pantry_id'=> $pantry_id),array('id menu_id','id menuId', 'name', 'pic', 'description' ), 'result' );
			$response = response("success", $res, "Success get data to pantry place ");
			echo $response ;
			
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry place ");
			echo $response ;
		}
	}
	public function getMenuDetail()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			// $nik = $post['nik'];
			$menu_id = $post['menu_id'];
			$res = $this->Model_Api->getPantryMenuDetail($menu_id);
			if($res["error"] == null){
				$variant = $res['data'];
				foreach ($variant as $key => $value) {
					$variant_id = $value['id'];
					$d = $this->Model_Api->select_all_data('pantry_detail_menu_variant_detail', array('is_deleted' => 0, 'variant_id'=> $variant_id),array(), 'result' );
					$variant[$key]['variant_detail'] = $d;
				}
				$response = response("success", $variant, "Success get data to menu ");
				echo $response ;

			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a menu ");
				echo $response ;
			}
			
			
			
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry place ");
			echo $response ;
		}
	}
	public function getprocess()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$where = array(
				"is_deleted" => 0,
				"nik" => $post['nik']
			);
			$select = "name, nik, email, card_number, id";
			$getData = $this->Model_Api->select_sql("employee",$select,$where);
			$cn = $getData->num_rows();
			// print_r($getData->row_array());
			if($cn == 1){
				$dataEmployee =  $getData->row_array();
				$res = $this->Model_Api->getProcessTrsPantry($dataEmployee['id']);
				$response = response("success", $res['data'], "Success get data to pantry transaction ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry transaction ");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry transaction ");
			echo $response ;
		}
	}
	public function getcomplete()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$where = array(
				"is_deleted" => 0,
				"nik" => $post['nik']
			);
			$select = "name, nik, email, card_number, id";
			$getData = $this->Model_Api->select_sql("employee",$select,$where);
			$cn = $getData->num_rows();
			// print_r($getData->row_array());
			if($cn == 1){
				$dataEmployee =  $getData->row_array();
				$res = $this->Model_Api->getCompleteTrsPantry($dataEmployee['id']);
				$response = response("success", $res['data'], "Success get data to pantry transaction ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry transaction ");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry transaction ");
			echo $response ;
		}
	}
	public function getdone()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$where = array(
				"is_deleted" => 0,
				"nik" => $post['nik']
			);
			$select = "name, nik, email, card_number, id";
			$getData = $this->Model_Api->select_sql("employee",$select,$where);
			$cn = $getData->num_rows();
			// print_r($getData->row_array());
			if($cn == 1){
				$dataEmployee =  $getData->row_array();
				$res = $this->Model_Api->getDoneTrsPantry($dataEmployee['id']);
				$response = response("success", $res['data'], "Success get data to pantry transaction ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry transaction ");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry transaction ");
			echo $response ;
		}
	}
	public function getfailed()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$where = array(
				"is_deleted" => 0,
				"nik" => $post['nik']
			);
			$select = "name, nik, email, card_number, id";
			$getData = $this->Model_Api->select_sql("employee",$select,$where);
			$cn = $getData->num_rows();
			// print_r($getData->row_array());
			if($cn == 1){
				$dataEmployee =  $getData->row_array();
				$res = $this->Model_Api->getFailedTrsPantry($dataEmployee['id']);
				$response = response("success", $res['data'], "Success get data to pantry transaction ");
				echo $response ;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry transaction ");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry transaction ");
			echo $response ;
		}
	}


	public function postDelete()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$wh = array(
				"id" => $post['transaksiId']
			);
			$changeData = array(
				"is_deleted" =>1
			);
			$res = $this->Model_Api->updateData('pantry_transaksi',$changeData, $wh);
			$response = response("success", $res['data'], "Success delete data to pantry transaction ");
			echo $response ;
			
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry transaction ");
			echo $response ;
		}
	}
	public function postCancel()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$wh = array(
				"id" => $post['transaksiId']
			);
			$changeData = array(
				"failed" => 1,
				"note" => $post['note'],
			);
			$res = $this->Model_Api->updateData('pantry_transaksi',$changeData, $wh);
			$response = response("success", $res['data'], "Success canceled data to pantry transaction ");
			echo $response;
			
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry transaction ");
			echo $response ;
		}
	}
	public function postSubmit()
	{
		try{
			$json = file_get_contents("php://input");
			$post = json_decode($json, TRUE);
			$datetime = date("Y-m-d H:i:s");
			$id = date("YmdHis")-0;
			$dataBatch = array();
			$where = array(
				"DATE(datetime)" => "DATE(".$datetime.")",
			);
			$select = "datetime, order_no";
			$getData = $this->Model_Api->getOrderNumber($datetime);
			if(count($getData['data']) > 0){
				$last_data = end($getData['data']);
				$order_no = $last_data['order_no']+1;

			}else{
				$order_no = 1;
			}
			foreach ($post['order_menu'] as $dval) {
				$v = array();
				$v['menu_id'] = $dval['id'];
				$v['qty'] = $dval['qty'];
				$v['note_order'] = $dval['note'];
				$v['is_rejected'] = 0;
				$v['is_deleted'] = 0;
				$v["transaksi_id"] = $id;
				array_push($dataBatch, $v);
			}
			$inTrs = array();
			$inTrs['pantry_id'] = $post['pantry_id'];
			$inTrs['employee_id'] = $post['pic_id'];
			$inTrs['booking_id'] = $post['booking_id'];
			$inTrs['datetime'] = $datetime;
			$inTrs['order_no'] = $order_no;
			$inTrs['order_st'] = 1;
			$inTrs['process'] = 0;
			$inTrs['complete'] = 0;
			$inTrs['done'] = 0;
			$inTrs['failed'] = 0;	
			$inTrs['is_deleted'] = 0;	
			$inTrs['id'] = $id;	

			
			// print_r($inTrs);
			// die();
			$resDatabATCH = $this->Model_Api->insertDataBatch("pantry_transaksi_d", $dataBatch);
			if($resDatabATCH['error'] == null){
				$getData = $this->Model_Api->insertData("pantry_transaksi",$inTrs);
				if($getData['error'] == null ){
					$response = response("success", $getData['data'], "Success post data order ");
					echo $response ;
				}else{
					$wh = array(
						"transaksi_id"=> $id 
					);
					$whT = array(
						"id"=> $id 
					);
					$deleteAll = $this->Model_Api->deleteAll("pantry_transaksi_d", $wh);
					$deleteAll = $this->Model_Api->deleteAll("pantry_transaksi", $whT);
					$response = response("fail", $getData, "Failed error a active ");
					echo $response ;
				}
			}else{
				$wh = array(
					"transaksi_id"=> $id 
				);
				$deleteAll = $this->Model_Api->deleteAll("pantry_transaksi_d", $wh);
				$response = response("fail", $resDatabATCH['error'], "Failed error a pantry ");
				echo $response ;
			}
			
		}catch(Exeption $er){
			$response = response("fail", $er, "Failed error a pantry ");
			echo $response ;
		}
		
	}
	public function postSubmitOrder()
	{
		try{
			$json = file_get_contents("php://input");
			$post = json_decode($json, TRUE);
			file_put_contents("example.txt", $json);
			$datetime = date("Y-m-d H:i:s");
			$dataBatch = array();
			// $booking_id				 	= $post['bookingId']; // // 
			$pantry_id				 	= $post['pantryId'];
			$room_id				 	= $post['roomId'];
			if($room_id == "" || $pantry_id == ""){
				$response = response("fail",array(), "Pantry or room is empty");
				echo $response ;
				die();
			}
			$nik_order = @$post['nik'];
			// die();
			// print_r($post);
			foreach ($post['pantryOrder'] as $key => $value) {
				$d = $value['detailorder'];
				$js = json_decode($d, TRUE);

				$post['pantryOrder'][$key]['detailorder'] = $js ;
			}

			foreach ($post['pantryOrder'] as $key => $value) {
				$d = $value['detailorder'];
				foreach ($value['detailorder'] as $k => $v) {
					// print_r($v['variant_detail']);
					$ddd = $v['variant_detail'];
					foreach ($ddd as $ki => $vi) {
						if($vi['onchange'] == 1){
							// true

						}else{
							$b = $post['pantryOrder'][$key]['detailorder'][$k]['variant_detail'][$ki];
							unset($post['pantryOrder'][$key]['detailorder'][$k]['variant_detail'][$ki]);
						}
					}
				}
				
			}
			$set_pantry_config          = $this->Model_Api->select_all_data('setting_pantry_config', array(), array(), 'row');
			$pantry_expired 			= $set_pantry_config['pantry_expired']; 
			$pantry_max_order_qty 		= $set_pantry_config['max_order_qty']; 
			$pantry_before_order_meeting= $set_pantry_config['before_order_meeting']; 
			$set_pantry 				= array();
			$collected_pantry_detail 	= array();
			$tanggal_order_pantry = date("Y-m-d");
			$tanggaltime_order_pantry 	= $datetime;
			$tanggaltime_order_pantry_before 	= $datetime;
			$pantry_detail 				= $post['pantryOrder'];
			$pantry_trs_status = $this->Model_Api->select_all_data('pantry_transaksi_status', array('id'=>0), array(), 'row');
			$sql_pantry = "SELECT COALESCE(max(order_no), '') as order_no from pantry_transaksi
							WHERE DATE(order_datetime) = '".$tanggal_order_pantry."'   AND pantry_id=".$pantry_id ." "  ;
			$idtrspantry = "PANTRY-".date('YmdHis').random_string('numeric', 3);
			$no_order_pantry = "";
			$row_order_pantry 	= $this->Model_Api->querySql($sql_pantry)->row_array();
			if($row_order_pantry['order_no'] == "" || $row_order_pantry['order_no'] == null){
				$no_order_pantry = sprintf("%04d", "1");
			}else{
				$old_no_order_pantry = $row_order_pantry['order_no']-0;
				$no_sort_order_pantry = $old_no_order_pantry + 1;
				$no_order_pantry = sprintf("%04d", $no_sort_order_pantry);
			}
			$set_pantry = array(
					'id' => $idtrspantry,
					'pantry_id' => $pantry_id,
					'order_no' => $no_order_pantry,
					// 'employee_id' => $nik_order,
					'is_blive' => 1,// 
					'room_id' => $room_id,//
					'via' => "tab",
					'datetime' => $datetime ,
					'order_datetime' => $tanggaltime_order_pantry ,
					'order_datetime_before' => $tanggaltime_order_pantry_before ,
					'order_st' => 0,
					'order_st_name' => $pantry_trs_status['name'],
					'process' => 0 ,
					'complete' => 0 ,
					'failed' => 0 ,
					'done' => 0 ,
					'note' =>'',
					'created_at' => $datetime,
					'is_deleted' => 0,
			);

			foreach ($pantry_detail as $key => $value) {
				$jsondetail = json_encode($value['detailorder']);
				$d_trs_pantry = array(
					'transaksi_id' => $idtrspantry,
					'menu_id' => $value['id'],
					'qty' => $value['qty']-0,
					'note_order' => $value['note'],
					'note_reject' => "",
					'detailorder' => $jsondetail,
					'is_rejected' => 0,
					'is_deleted' => 0,
					'status' => $value['status'],
				);
				array_push($collected_pantry_detail , $d_trs_pantry);
			}


			if(count($pantry_detail) > 0){
				$resp2 		= $this->Model_Api->insertData('pantry_transaksi', $set_pantry);
				$resp3 		= $this->Model_Api->insertDataBatch('pantry_transaksi_d', $collected_pantry_detail);
			}
			// print_r($set_pantry);
			$response = response("success", array(), "Success create a order pantry ");
			echo $response;

			// die();
		}catch(Exeption $er){
			$response = response("fail", $er, "Failed error a pantry ");
			echo $response ;
		}
		
	}
	public function getDetailTrs()
	{
		
		try{
			$json = file_get_contents("php://input");
			$post = json_decode($json, TRUE);

			$res = $this->Model_Api->getDetailPantryTrs($post);
			if($res['error'] == null){
				$response = response("success", $res['data'], "Success get detail to pantry ");
				echo $response;
			}else{
				$rr = array(
					"err_msg" => 'Something wrong to request data',
				);
				$response = response("fail", $rr, "Failed error a pantry ");
				echo $response ;
			}
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a pantry ");
			echo $response ;
		}
	}

	public function getallschedule()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Api->getAllSchedule($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to schedule ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}

	public function postCancelOrder()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		try{
			$pantry_trs_status = $this->Model_Api->select_all_data('pantry_transaksi_status', array('id'=>4), array(), 'row');
			$wh = array(
				"id" => $post['id']
			);
			$changeData = array(
				"failed" => 1,
				"order_st" => 4,
				'order_st_name' => $pantry_trs_status['name'],
				// "note" => $post['note'],
			);
			$res = $this->Model_Api->updateData('pantry_transaksi',$changeData, $wh);
			$response = response("success", $res['data'], "Success canceled data to order transaction ");
			echo $response;
			
		}catch(Exeption $error){
			$rr = array(
				"err_msg" => 'Something wrong to request data',
			);
			$response = response("fail", $rr, "Failed error a order transaction ");
			echo $response ;
		}
	}
	
}

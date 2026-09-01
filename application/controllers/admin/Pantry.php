<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Pantry extends CI_Controller {
	/**
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
		$this->load->model('Model_License');
		$this->load->model('Model_Pantry');
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
		$module_price           = $this->Model_Module->get_module_price();
		$pagename = "Pantry";
		$menu = $this->Model_Menu->getMenu($pagename);
		$this->load->view('Admin/Pantry/index', array('menumaster'=> $menu, 'pagename' => $pagename));
	}
	public function pantryPackage()
	{
		$pagename = "Pantry Package";
		$menu = $this->Model_Menu->getMenu($pagename);
		$this->load->view('Admin/Pantry/pantry-package', array('menumaster'=> $menu, 'pagename' => $pagename));
	}
	// GET DATA
	public function getData()
	{
		$data = $this->Model_Admin->getDataPantry();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	
	public function getSatuan()
	{
		$sql = "SELECT * FROM pantry_satuan WHERE is_deleted=0 ORDER BY id ASC";
		$data = $this->Model_Admin->querySql($sql);
		$result = $data->result_array();
		// echo response("success", $result , "Get success");
		echo response("success", $result , "Get success");
	}
	// =================================================================================
	// PANTRY MENU
	// =================================================================================

	public function indexMenu()
	{
		$module_price           = $this->Model_Module->get_module_price();
		$modules['price']       = $module_price;
		$pantry 				= [];
		if($this->session->userdata('levelid-nya') == 1){
			$pantry             = $this->Model_Pantry->getDataPantry([])['data'];
		}else if($this->session->userdata('levelid-nya') == 5){
			$pantry             = $this->Model_Pantry->getDataPantry(['owner'])['data'];
		}
		$pagename = "Pantry Menu";
		$menu = $this->Model_Menu->getMenu($pagename);
		$this->load->view('Admin/Pantry/indexMenu', array(
			'menumaster'	=> $menu, 
			'pagename' 		=> $pagename,
			'modules'       => $modules,
			'pantry'		=> json_encode($pantry) ,
		));
	}
	public function filterMenu()
	{
		$post = $_POST;
		$w = [];
		if(isset($post['pantry_search'])){
			$w['pantry_id'] = $post['pantry_search'];
		}
		$id = $this->uri->segment(4);
		$sql = "SELECT pd.*, p.name as prefix FROM pantry_detail pd
		LEFT JOIN pantry_satuan p ON pd.prefix_id = p.id
		WHERE pd.pantry_id=".$id." AND pd.is_deleted=0 ORDER BY pd.id ASC";
		$data = $this->Model_Admin->querySql($sql);
		$result = $data->result_array();

		echo response("success", $result , "Get success");
	}
	public function getMenu()
	{
		$id = $this->uri->segment(4);
		$sql = "SELECT pd.*, p.name as prefix FROM pantry_detail pd
		LEFT JOIN pantry_satuan p ON pd.prefix_id = p.id
		WHERE pd.pantry_id=".$id." AND pd.is_deleted=0 ORDER BY pd.id ASC";
		$data = $this->Model_Admin->querySql($sql);
		$result = $data->result_array();
		echo response("success", $result , "Get success");
		
	}

	public function getEdit()
	{
		$id = $this->uri->segment(4);
		$data = $this->Model_Admin->getEditPantry($id);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getMenuUpdate()
	{
		$id = $this->uri->segment(4);
		$sql = "SELECT * FROM pantry_detail WHERE id='".$id."' AND is_deleted=0 ORDER BY id ASC";
		$data = $this->Model_Admin->querySql($sql);
		$result = $data->result_array();
		if(count($result) > 0){
			echo response("success", $result[0] , "Get success");
		}else{
			echo response("fail", array() , "Failed data not exist");
		}
		
	}

	public function postCreateMenu()
	{
		$post = $_POST;
		$files = $_FILES;
		$file_name = "";
		$datetime = date("Y-m-d H:i:s");
		if($files['pic']['name'] != ""){
			$extsp = explode(".", $files['pic']['name']);
			$ext = end($extsp);
			$file_name =gen_uuid(). "." .$ext;
			$config = array();
			$config['file_name']     = $file_name;
			$config['upload_path']   = './assets/pantry/';
	        $config['allowed_types'] = 'png|jpg|jpeg';
			$this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('pic'))
	        {
	            $error =  $this->upload->display_errors();
	            $error = str_replace("<p>", "", $error);
	            $error = str_replace("</p>", "", $error);
	            $response = response("fail", array(), $error);
				echo $response;
	        }
	        else
	        {
	            
	        }
		}
		$post['is_deleted'] =0;
		$post['created_at'] =$datetime;
		$post['updated_at'] =$datetime;
		$post['pic'] = $file_name;
		$resp = $this->Model_Admin->insertData('pantry_detail', $post);
		if($resp){
	    	$response = response("success", array(), "Success create a pantry menu ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a pantry menu ".$post['name']);
			echo $response;
		}
	}
	public function postUpdateMenu()
	{
		$post = $_POST;
		$files = $_FILES;
		$id = $this->uri->segment(4);
		$datetime = date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime;
		$file_name = "";
		if($files['pic']['name'] != ""){
			$extsp = explode(".", $files['pic']['name']);
			$ext = end($extsp);
			$file_name =gen_uuid(). "." .$ext;
			$config = array();
			$config['file_name']     = $file_name;
			$config['upload_path']   = './assets/pantry/';
	        $config['allowed_types'] = 'png|jpg|jpeg';
			$this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('pic'))
	        {
	            $error =  $this->upload->display_errors();
	            $error = str_replace("<p>", "", $error);
	            $error = str_replace("</p>", "", $error);
	            $response = response("fail", array(), $error);
				echo $response;
	        }
	        else
	        {
	            $post['pic'] = $file_name;
	        }
		}
		$wh = array(
			'id'=>$id
		);
		// print_r($post);	
		// print_r($wh);	
		// die();
		unset($post['id']);
		$resp = $this->Model_Admin->updateData('pantry_detail', $post, $wh);
		if($resp){
	    	$response = response("success", array(), "Success update a pantry menu ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a pantry menu ".$post['name']);
			echo $response;
		}
	}
	public function postDeleteMenu()
	{
		$post = $_POST;
		$d = array(
			'is_deleted' => 1
		);
		$w = array ( "id"=>$post['id']);
		$resp = $this->Model_Admin->updateData('pantry_detail', $d, $w);
		if($resp){
	    	$response = response("success", array(), "Success delete a pantry menu ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a pantry menu ".$post['name']);
			echo $response;
		}
	}
	
	public function postCreateSatuan()
	{
		$post = $_POST;
		$datetime = date("Y-m-d H:i:s");
		$post['is_deleted'] =0;
		$resp = $this->Model_Admin->insertData('pantry_satuan', $post);
		if($resp){
	    	$response = response("success", array(), "Success create a pantry prefix ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a pantry prefix ".$post['name']);
			echo $response;
		}
	}
	public function postUpdateSatuan()
	{
		$post = $_POST;
		$id = $this->uri->segment(4);
		$datetime = date("Y-m-d H:i:s");
		// $post['updated_at'] = $datetime;
		$wh = array(
			'id'=>$id
		);
		$resp = $this->Model_Admin->updateData('pantry_satuan', $post, $wh);
		if($resp){
	    	$response = response("success", array(), "Success update a pantry prefix ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a pantry prefix ".$post['name']);
			echo $response;
		}
	}
	public function postDeleteSatuan()
	{
		$post = $_POST;
		$d = array(
			'is_deleted' => 1
		);
		$w = array ( "id"=>$post['id']);
		$resp = $this->Model_Admin->updateData('pantry_satuan', $d, $w);
		if($resp){
	    	$response = response("success", array(), "Success delete a pantry prefix ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a pantry prefix ".$post['name']);
			echo $response;
		}
	}

	// #################################
	// VARIANT
	// #################################
	public function getVariant()
	{
		$id = $this->uri->segment(4);

		$sql = "SELECT * FROM pantry_detail_menu_variant 
		WHERE menu_id=".$id." AND is_deleted=0 ORDER BY id ASC";
		$data = $this->Model_Admin->querySql($sql);
		// echo $sql;
		$result = $data->result_array();
		echo response("success", $result , "Get success");
		
	}
	public function getVariantUpdate()
	{
		$id = $this->uri->segment(4);

		$sql = "SELECT * FROM pantry_detail_menu_variant 
		WHERE id='".$id."' AND is_deleted=0 ORDER BY id ASC";
		$data = $this->Model_Admin->querySql($sql);
		$result = $data->result_array();

		$sqldetatail = "SELECT * FROM pantry_detail_menu_variant_detail 
		WHERE variant_id='".$id."' AND is_deleted=0 ORDER BY id ASC";
		$datadetail = $this->Model_Admin->querySql($sqldetatail);
		$resultdetail = $datadetail->result_array();

		if(count($result) < 0){
			$response = response("fail", array(), "Failed data variant not exist ".$post['name']);
			echo $response;
			die();	
		}else{
			$result = $result[0];
		}
		$d = array(
			"data"=>$result,
			"detail" => $resultdetail
		);
		echo response("success", $d , "Get success");
		
	}
	public function postCreateVariant()
	{
		$idMenu = $this->uri->segment(4);
		$post = $_POST;
		$datetime = date("Y-m-d H:i:s");
		$post['is_deleted'] =0;
		$post['menu_id'] =$idMenu ;
		$variant = $post['variant'];
		// print_r($post);
		// print_r($idMenu);

		$id =date("YmdHis"). "-" .random_string('alnum', 4);
		$arbatch = array();
		$insert = array(
			"id" => $id,
			"menu_id" => $idMenu,
			"name" => $post["name"],
			"multiple" => $post["rule"],
			"min" => $post["min"],
			"max" => $post["max"],
			"is_deleted" => 0
		);

		foreach ($variant  as $k => $v) {
			$indetail = array(
				"variant_id" => $id,
				"name" => $v["name"],
				"is_deleted" =>0
			);
			array_push($arbatch, $indetail );
		}
		// // // // // //
		// print_r($variant);
		// die();
		// // // // // // 
		if(count($arbatch) > 0){
			$resp = $this->Model_Admin->insertDataBatch('pantry_detail_menu_variant_detail', $arbatch);
		}
		$resp = $this->Model_Admin->insertData('pantry_detail_menu_variant', $insert);
		if($resp){
	    	$response = response("success", array(), "Success create a pantry variant ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a pantry variant ".$post['name']);
			echo $response;
		}
	}
	public function postUpdateVariant()
	{
		$post = $_POST;
		$id = $this->uri->segment(4);
		$datetime = date("Y-m-d H:i:s");
		// $post['updated_at'] = $datetime;
		$whd = array(
			'id'=>$id
		);
		$arinsertbatch = array();
		$arupdatebatch = array();
		$ardeletebatch = array();
		$variant = $post['variant'];
		$update = array(
			
			"name" => $post["name"],
			"multiple" => $post["rule"],
			"min" => $post["min"],
			"max" => $post["max"],
		);
		// update
		foreach ($variant  as $k => $v) {
			if($v['is_deleted'] == 0 ){
				if($v['update'] == 0){
					$indetail = array(
						"variant_id" => $id,
						"name" => $v["name"],
						"is_deleted" =>0
					);
					array_push($arinsertbatch, $indetail );
				}else{
					$indetail = array(
						"name" => $v["name"],
					);
					$wh = array(
						"id" => $v["id"],
					);
					array_push($arupdatebatch, $indetail );
					$respupdate = $this->Model_Admin->updateData('pantry_detail_menu_variant_detail', $indetail, $wh);
				}
			}
			
		}
		// delete
		foreach ($variant  as $k => $v) {
			if($v['is_deleted'] == 1 ){
				if($v['id'] ==  "null"){

				}else {
					$indetaildelete = array(
						"is_deleted" => 1,
					);
					$whdelte = array(
						"id" => $v["id"],
					);
					$respupdelete = $this->Model_Admin->updateData('pantry_detail_menu_variant_detail', $indetaildelete, $whdelte);
					array_push($ardeletebatch, $v['id'] );
				}
				
			}
		}
		
		$resp = $this->Model_Admin->updateData('pantry_detail_menu_variant', $update, $whd);
		if(count($arinsertbatch) > 0){
			$resp = $this->Model_Admin->insertDataBatch('pantry_detail_menu_variant_detail', $arinsertbatch);
		}
		if($resp){
	    	$response = response("success", array(), "Success update a pantry variant ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a pantry variant ".$post['name']);
			echo $response;
		}
	}
	public function postDeleteVariant()
	{
		$id = $this->uri->segment(4);
		$post = $_POST;
		$d = array(
			'is_deleted' => 1
		);

		$ddetail = array(
			'is_deleted' => 1
		);
		$wddetail = array(
			'variant_id' => $id
		);
		$w = array ( "id"=>$id);
		$resp = $this->Model_Admin->updateData('pantry_detail_menu_variant', $d, $w);
		$resp = $this->Model_Admin->updateData('pantry_detail_menu_variant_detail', $ddetail, $wddetail);
		if($resp){
	    	$response = response("success", array(), "Success delete a pantry variant ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a pantry variant ".$post['name']);
			echo $response;
		}
	}

	public function postCreate()
	{
		$post = $_POST;
		$files = $_FILES;
		$file_name = "";
		if($files['pic']['name'] != ""){
			$extsp = explode(".", $files['pic']['name']);
			$ext = end($extsp);
			$file_name =gen_uuid(). "." .$ext;
			$config = array();
			$config['file_name']     = $file_name;
			$config['upload_path']   = './assets/pantry/';
	        $config['allowed_types'] = 'png|jpg|jpeg';
			$this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('pic'))
	        {
	            $error =  $this->upload->display_errors();
	            $error = str_replace("<p>", "", $error);
	            $error = str_replace("</p>", "", $error);
	            $response = response("fail", array(), $error);
				echo $response;
	        }
	        else
	        {
	            
	        }
		}
		$datetime = date("Y-m-d H:i:s");
		$post['created_at'] = $datetime ;
		$post['updated_at'] = $datetime ;
		$post['is_deleted'] =0;
		$post['pic'] = $file_name;
		$resp = $this->Model_Admin->insertData('pantry', $post);
		if($resp){
	  	$response = response("success", array(), "Success create a pantry ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a pantry ".$post['name']);
			echo $response;
		}
	}
	public function postUpdate()
	{
		$post = $_POST;
		$id = $this->uri->segment(4);
		$files = $_FILES;
		$file_name = "";
		if($files['pic']['name'] != ""){
			$extsp = explode(".", $files['pic']['name']);
			$ext = end($extsp);
			$file_name =gen_uuid(). "." .$ext;
			$config = array();
			$config['file_name']     = $file_name;
			$config['upload_path']   = './assets/pantry/';
	        $config['allowed_types'] = 'png|jpg|jpeg';
			$this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('pic'))
	        {
	            $error =  $this->upload->display_errors();
	            $error = str_replace("<p>", "", $error);
	            $error = str_replace("</p>", "", $error);
	            $response = response("fail", array(), $error);
				echo $response;
	        }
	        else
	        {
	            $post['pic'] = $file_name;
	        }
		}
		
		$datetime = date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime;
		$wh = array(
			'id'=>$id
		);
		$resp = $this->Model_Admin->updateData('pantry', $post, $wh);
		if($resp){
	    	$response = response("success", array(), "Success update a pantry ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a pantry ".$post['name']);
			echo $response;
		}
	}
	public function postDelete()
	{
		$post = $_POST;
		$d = array(
			'is_deleted' => 1
		);
		$w = array ( "id"=>$post['id']);
		$resp = $this->Model_Admin->updateData('pantry', $d, $w);
		if($resp){
	    	$response = response("success", array(), "Success delete a pantry ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a pantry ".$post['name']);
			echo $response;
		}
	}

	// PACKAGE

	public function getPackage()
	{
		$data = $this->Model_Admin->getDataPantryPackage("");
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getPackageUpdate()
	{

		$id = $this->uri->segment(4);
		$data = $this->Model_Admin->getDataPantryPackage($id)['data'];
		$detail = $this->Model_Admin->getDataPantryPackageDetail($id)['data'];
		if(count($data) <= 0){
			echo response("fail", $data, "Package not found");
			die();
		}
		$co = array(
			"data" => $data[0],
			"detail" => $detail,
		);
		echo response("success", $co, "Get package success");
	}
	public function postCreatePackage()
	{
		$datetime = date("Y-m-d H:i:s");
		$post = $_POST;

		$id = "p" .random_string('numeric', 3).date("YmdHis");
		$dataPaket = array(
			"id" => $id,
			"pantry_id" => $post['pantry_id'],
			"name" => $post['name'],
			"created_at" => $datetime,
			"updated_at" => $datetime,
			"is_deleted" => 0,
		);
		$collectMenu = array();
		foreach ($post['menu'] as $key => $value) {
			$p = array(
				"menu_id" => $value['id'],
				"package_id" => $id,
				"is_deleted" => 0,
			);
			array_push($collectMenu, $p);
		}
		$resp1 = $this->Model_Admin->insertData('pantry_menu_paket', $dataPaket);
		$resp1 = $this->Model_Admin->insertDataBatch('pantry_menu_paket_d', $collectMenu);
		if($resp1){
	    	$response = response("success", array(), "Success create a package ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a package ".$post['name']);
			echo $response;
		}
	}
	public function postUpdatePackage()
	{
		$post = $_POST;
		$id = $this->uri->segment(4);
		$datetime = date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime;
		$wh = array(
			'id'=>$id
		);
		$whdelete = array(
			'package_id'=>$id
		);
		$id = "p" .random_string('numeric', 3).date("YmdHis");
		$dataPaket = array(
			"pantry_id" => $post['pantry_id'],
			"name" => $post['name'],
			"updated_at" => $datetime,
		);
		$dataDeleteDetail = array(
			"is_deleted" => 1,
		);

		$collectMenu = array();
		foreach ($post['menu'] as $key => $value) {
			$p = array(
				"menu_id" => $value['id'],
				"package_id" => $id,
				"is_deleted" => 0,
			);
			array_push($collectMenu, $p);
		}

		$resp = $this->Model_Admin->updateData('pantry_menu_paket', $dataPaket, $wh);
		$resp1 = $this->Model_Admin->updateData('pantry_menu_paket_d', $dataDeleteDetail, $whdelete);
		$resp1 = $this->Model_Admin->insertDataBatch('pantry_menu_paket_d', $collectMenu);
		if($resp1){
	    	$response = response("success", array(), "Success update a package ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a package ".$post['name']);
			echo $response;
		}
	}
	public function postDeletePackage()
	{
		$post = $_POST;
		$d = array(
			'is_deleted' => 1
		);
		$w = array ( "id"=>$post['id']);
		$resp = $this->Model_Admin->updateData('pantry_menu_paket', $d, $w);
		if($resp){
	    	$response = response("success", array(), "Success delete a package ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a package ".$post['name']);
			echo $response;
		}
	}
	
}

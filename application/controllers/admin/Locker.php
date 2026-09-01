<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Locker extends CI_Controller {

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
		$pagename = "Locker";
		$data = $this->Model_Admin->getDataCompany();
		if($data['error'] == null){
			$company = $data['data'];
		}else{
			$company = array();
		}
		$menu = $this->Model_Menu->getMenu($pagename);
		$companyParse = json_encode($company);
		$this->load->view('Admin/Locker/index', array('menumaster'=> $menu, 'pagename' => $pagename,'companyParse'=>$companyParse, 'company'=>$company));
		
		
	}
	
	public function getData()
	{

		$sql = "SELECT * FROM locker WHERE 1=1 AND is_deleted=0 ORDER BY _generate ASC";
		$data = $this->Model_Admin->querySql($sql)->result_array();

		echo response("success", $data, "Get success");
		
	}
	
	public function postCreate()
	{
		$post = $_POST;
		$datetime = date("Y-m-d H:i:s");
		$id = "LOKCERID"."".date("YmdHis");

		$post['created_at'] = $datetime ;
		$post['updated_at'] = $datetime ;
		$post['created_by'] = $this->session->userdata('user-nya') ;

		$post['id'] = $id;
		$post['auto_reserve'] = 0 ;
		$post['is_deleted'] =0;
		$resp = $this->Model_Admin->insertData('locker', $post);
		if($resp){
	    	$response = response("success", array(), "Success create a locker ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed create a locker ".$post['name']);
			echo $response;
		}
	}
	public function getDataDetail(){
		$post = $_POST;
		$id = $this->uri->segment(5);
		if(!isset($id)){
			$response = response("fail", array(), "Parameter not complete ");
			echo $response;
			die();
		}
		$sql = $this->Model_Admin->querySql("
			SELECT * FROM locker 
			WHERE id='".$id."' AND is_deleted=0
			");

		$res = $sql->result_array();
		if(count($res)<=0){
			$response = response("fail", array("id" => $id), "data not found ");
			echo $response;
			die();
		}
		echo response("success", $res[0], "Get success");
		
	}
	public function postUpdate()
	{
		$post = $_POST;
		$id = $this->uri->segment(4);
		$wh = array(
			'id'=>$id
		);
		$datetime = date("Y-m-d H:i:s");
		$post['updated_at'] = $datetime ;
		
		// unset($post['id']);
		$resp = $this->Model_Admin->updateData('locker', $post, $wh);
		if($resp){
	    	$response = response("success", array(), "Success update a locker ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed update a locker ".$post['name']);
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
		$resp = $this->Model_Admin->updateData('locker', $d, $w);
		if($resp){
	    	$response = response("success", array(), "Success delete a locker ".$post['name']);
			echo $response;
		}else{
			$response = response("fail", array(), "Failed delete a locker ".$post['name']);
			echo $response;
		}
	}
	
	
}

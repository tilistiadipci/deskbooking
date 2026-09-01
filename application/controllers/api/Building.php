<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Building extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Api');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
		
	}
	public function getData()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$w = array("is_deleted" => 0);
		$q = "SELECT * FROM building WHERE is_deleted=0 ORDER BY name ASC";
		$res = $this->Model_Api->querySql($q);

		echo response("success", $res->result_array(), "Get success");
		
	}
	public function getDataBuildingRoom()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$building_id = $post['id'];
		$w = array("is_deleted" => 0);
		$q = "SELECT r.*,r.radid room_id ,b.name building_name FROM room r
		INNER JOIN building b ON r.building_id=b.id
		WHERE (r.is_deleted=0 AND b.is_deleted=0) AND b.id =".$building_id." 
		ORDER BY r.name ASC";
		$res = $this->Model_Api->querySql($q);
		echo response("success", $res->result_array(), "Get success");
	}
}
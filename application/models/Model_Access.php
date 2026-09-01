<?php  
date_default_timezone_set("Asia/Jakarta");
class Model_Access extends CI_Model {
	public function __construct(){
		parent::__construct();
		// $this->load->helper('response');
		
	}
	public function getListAccessUser(){
		$data = $this->db->select("access_id")
		->from('user_access')
		->where(array(
			"is_active"=>0,
		))
		->get();
		if($data->num_rows() <= 0){
			return null;
		}
		return $data->row_array();
	}
	public function getDataListAccessUser(){
		$data = $this->db->select("*")
		->from('user_access')
		->where(array(
			"is_active"=>1,
		))
		->get();
		
		return $data->result_array();
	}
	public function getDataAccessUserById($whre){
		$data = $this->db->select("*")
		->from('user_access')
		->where(array(
			"is_active"=>1,
		))
		->where_in('access_id', $whre)
		->get();
		
		return $data->result_array();
	}

	public function getAccessUsername($username){
		$data = $this->db->select("access_id")
		->from('user')
		->where(array(
			"username" => $username,
			"is_deleted"=>0,
			"is_disactived"=>0,
		))
		->get();
		if($data->num_rows() <= 0){
			return null;
		}
		return $data->row_array();
	}

	public function getAccessDeskbooking($username){
		$passed = false;
		$access_desk_id = 3;
		$user = $this->getAccessUsername($username);
		if($user == null){
			return $passed;
		}
		$spAccess = explode('#', $user['access_id']);
		foreach ($spAccess as $key => $value) {
			if($value == $access_desk_id){
				$passed = true;
				break;
			}
		}
		return $passed;
	}
	public function getAccessMeetingbooking($username){
		$passed = false;
		$access_desk_id = 1;
		$user = $this->getAccessUsername($username);
		if($user == null){
			return $passed;
		}
		$spAccess = explode('#', $user['access_id']);
		foreach ($spAccess as $key => $value) {
			if($value == $access_desk_id){
				$passed = true;
				break;
			}
		}
		return $passed;
	}
	public function getAccessPantrybooking($username){
		$passed = false;
		$access_desk_id = 2;
		$user = $this->getAccessUsername($username);
		if($user == null){
			return $passed;
		}
		$spAccess = explode('#', $user['access_id']);
		foreach ($spAccess as $key => $value) {
			if($value == $access_desk_id){
				$passed = true;
				break;
			}
		}
		return $passed;
	}
}
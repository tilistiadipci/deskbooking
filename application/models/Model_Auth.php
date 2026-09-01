<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Auth extends CI_Model {
	public function __construct(){
		parent::__construct();
		// $this->load->library('database');
		// $this->load->model('', '', true);
	}

	public function checkAuth($username, $password) {
		$where_username = array(
			'username' => $username,
			'password' => $password,
			'e.is_deleted' => 0,
			'u.is_disactived' => 0,
		);
		$qusername = $this->db->select(' e.* , level_id, u.username ')
			->from('user u')
			->join('employee e', 'u.employee_id = e.nik', 'left')
			->where($where_username)
			->get();

		$ret = array();
		
		$ret['username'] = $qusername;
		return $ret;
	}

	public function displayLoginAdmin($username, $password){
		try{
			
			$where_email = array(
				'u.username' => $username,
				'u.password' => $password,
				'u.is_deleted' => 0,
			);
			// print_r($where_email);
			
			$data = $this->db->select('u.*, l.name as level_name ,  u.secure_qr')
				->from('user u')
				->join('level l', 'u.level_id=l.id')
				->where($where_email)
				->get();
			
			$ret = array(
				"error" => null,
				"data" => $data->result_array()
			);
			return $ret;
		}catch(Exception $error){
			$sn = array(
				"error" => $error,
				"data" => $this->db->error()
			);
			return $sn;
		}
	}
	public function displayLoginPantry($username, $password){
		try{
			
			$where_email = array(
				'u.username' => $username,
				'u.password' => $password,
				'u.is_deleted' => 0,
			);
			// print_r($where_email);
			
			$data = $this->db->select('u.*, l.name as level_name ')
				->from('user u')
				->join('level l', 'u.level_id=l.id')
				->where($where_email)
				->get();
			
			$ret = array(
				"error" => null,
				"data" => $data->result_array()
			);
			return $ret;
		}catch(Exception $error){
			$sn = array(
				"error" => $error,
				"data" => $this->db->error()
			);
			return $sn;
		}
	}
	public function checkLoginUsername($username){
		try{
			$where_username = array(
				'username' => $username,
				'e.is_deleted' => 0,
				'u.is_disactived' => 0,
			);
			$qusername = $this->db->select(' e.* , level_id, u.username,at.name company_name, a.name department_name')
				->from('user u')
				->join('employee e', 'u.employee_id = e.id', 'left')
				->join("alocation_type at", "e.company_id=at.id", 'left')
				->join("alocation a", "e.department_id=a.id", 'left')
				->where($where_username)
				->get();

			$ret = array();
			// $ret['email'] = $qemail;
			// $ret['nik'] = $qnik;
			// // $ret['card'] = $qcard;
			$ret['username'] = $qusername;
			return $ret;
		}catch(Exception $error){
			$sn = array(
				"error" => $error,
				"data" => $this->db->error()
			);
			return $sn;
		}
	}

	public function checkAuthDeskBooking($qr) {
		
		$where_username = array(
			'e.is_deleted' => 0,
			'u.is_disactived' => 0
		);
		$qusername = $this->db->select(' u.username ')
			->from('user u')
			->join('employee e', 'u.employee_id = e.nik', 'left')
			->where($where_username)
			->where(" u.secure_qr LIKE '%".$qr."%' ")
			->get();
		return $qusername;
	}
	public function checkAuthDeskBookingCard($card) {
		
		$where_username = array(
			'e.is_deleted' => 0,
			'u.is_disactived' => 0
		);
		$qusername = $this->db->select(' u.username ')
		->from('user u')
		->join('employee e', 'u.employee_id = e.nik', 'left')
		->where($where_username)
		->where(" e.card_number LIKE '%".$card."%' ")
		->get();
		return $qusername;
	}
	public function getlevel($level_id) {
		$where = array(
			'l.id' => $level_id,
			'l.is_deleted' => false,
			'm.is_deleted' => false
		);
		$this->db->select('l.name as level_name, m.name as menu_name, url, icon, ')
		->from('level l')
		->join('level_detail ld', 'l.id = ld.level_id')
		->join('menu m', 'ld.menu_id = m.id')
		->where($where)	
		->order_by("sort", "asc");

		$query = $this->db->get();
		return $query->result_array();
	}

	public function getMenuHeader($level_id) {
		$where = array(
			'l.id' => $level_id,
			'l.is_deleted' => false,
			'm.is_deleted' => false
		);
		$this->db->select('l.name as level_name, m.name as menu_name, url, icon, ')
		->from('level l')
		->join('level_header_detail ld', 'l.id = ld.level_id')
		->join('menu_headers m', 'ld.menu_id = m.id')
		->where($where)	
		->order_by("sort", "asc");

		$query = $this->db->get();
		return $query->result_array();
	}

	public function gotoDefaultMenu($levelid){
		$where = array(
			'lv.id' => $levelid,
			'm.is_deleted' => 0,
			'lv.is_deleted' => 0,
		);
		$this->db->select('lv.default_menu, url, m.name')
		->from('level lv ')
		->join('menu m ',' lv.default_menu=m.id ', 'left')
		->where($where);
		$query = $this->db->get();
		return $query->row_array();
	}

}

?>
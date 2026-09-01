<?php  
date_default_timezone_set("Asia/Jakarta");
class Model_Kiosk extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	// ==============================================
	// DISPLAY ROOM
	// ==============================================
	public function getDataDisplay(){
		try{
			$ar = array(
				"d.is_deleted" => 0
			);
			$data = $this->db->select('d.*')
					->from("kiosk_display d")
					->where($ar)
					->order_by('d.id', 'ASC')
					->get();
			$sn = array(
				"error" => null,
				"data" => $data->result_array()
			);
			return $sn;
		}catch(Exception $error){
			$sn = array(
				"error" => $error,
				"data" => $this->db->error()
			);
			return $sn;
		}
	}
	public function getDataRoomDisplay(){
		try{
			$ar = array(
				"r.is_deleted" => 0
			);
			$data = $this->db->select('r.*, background')
					->from("room r")
					->join("room_display rd", "r.radid=rd.room_id", "left")
					->where($ar)
					->order_by('r.id', 'ASC')
					->get();
			$sn = array(
				"error" => null,
				"data" => $data->result_array()
			);
			return $sn;
		}catch(Exception $error){
			$sn = array(
				"error" => $error,
				"data" => $this->db->error()
			);
			return $sn;
		}
	}

	public function getDataAuth($serial ){
		try{
			$ar = array(
				"d.display_serial" => $serial,
				"d.is_deleted" => 0,
			);
			$data = $this->db->select('d.*')
					->from("kiosk_display d")
					->where($ar)
					->order_by('d.id', 'ASC')
					->get();
			$sn = array(
				"error" => null,
				"data" => $data->result_array()
			);
			return $sn;
		}catch(Exception $error){
			$sn = array(
				"error" => $error,
				"data" => $this->db->error()
			);
			return $sn;
		}
	}
}

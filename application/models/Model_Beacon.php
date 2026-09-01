<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Beacon extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function getBeaconTag($www){
		$data = $this->db->select("b.*, e.name as employee_name, e.nik_display as employee_nik, e.id as employee_id")
		->from('beacon_tag b')
		->join('employee e','b.beacon_employee=e.id', "left")
		->where(array(
			"b.is_deleted"=>0,
			// "e.is_deleted"=>0,
		))
		->where($www)
		->get();
		return $data;
	}

	public function getBeaconGateway($www){
		$data = $this->db->select("b.*, r.name room_name")
		->from('beacon_gateway b')
		->join('room r','b.room_id=r.radid', "left")
		->where(array(
			"b.is_deleted"=>0,
			// "e.is_deleted"=>0,
		))
		->where($www)
		->get();
		return $data;
	}

	public function getFloor($www){
		$data = $this->db->select("f.*, b.name building_name")
		->from('beacon_floor f')
		->join('building b','f.building_id=b.id')
		->where(array(
			"b.is_deleted"=>0,
			"f.is_deleted"=>0,
		))
		->where($www)
		->get();
		return $data;
	}

	public function getRoom($www){
		$data = $this->db->select("r.*, b.name building_name")
		->from('room r')
		->join('building b','r.building_id=b.id')
		->where(array(
			"b.is_deleted"=>0,
			"r.is_deleted"=>0,
		))
		->where($www)
		->get();
		return $data;
	}

	public function getFloorRoom($www){
		$data = $this->db->select("fr.*, r.name as room2_name, f.name as floor_name, b.name building_name")
		->from('beacon_floor_room fr')
		->join('room r','fr.room_id=r.radid', 'left')
		->join('beacon_floor f','fr.floor_id=f.id','left')
		->join('building b','f.building_id=b.id', 'left')
		->where(array(
			"fr.is_deleted"=>0,
			"b.is_deleted"=>0,
			"f.is_deleted"=>0,
		))
		->where($www)
		->get();
		return $data;
	}

	public function getFloorRoomArea($www){
		$data = $this->db->select("fr.*, f.name as floor_name, b.name building_name")
		->from('beacon_floor_room fr')
		// ->join('room r','fr.room_id=r.radid')
		->join('beacon_floor f','fr.floor_id=f.id')
		->join('building b','f.building_id=b.id')
		->where(array(
			"fr.is_deleted"=>0,
			"b.is_deleted"=>0,
			"f.is_deleted"=>0,
		))
		->where($www)
		->get();
		return $data;
	}


	public function getGateway($www){
		$data = $this->db->select("*")
		->from('beacon_gateway')
		->where(array(
			"is_deleted"=>0,
			"is_enabled"=>1,
		))
		->where($www)
		->get();
		return $data;
	}
	public function getBeaconTrs($www){
		$data = $this->db
		->select("*")
		->from('beacon_transaction')
		->where($www)
		->order_by('id_trs_beacon', 'DESC')
		->get();
		return $data;
	}
	public function getBeaconMonitorEmployeeTrs($www){
		$data = $this->db
		->select("bt.*,bf.name floor_name, br.name room_name, b.beacon_qr,b.beacon_qr, b.beacon_card_no, e.name as employee_name,e.email as employee_email,e.photo as employee_photo, e.nik_display as employee_nik, e.id as employee_id, at.name company_name, a.name department_name")
		->from('beacon_transaction bt')
		->join('beacon_floor bf','bt.floor_id=bf.id', "left")
		->join('room br','bt.room_id=br.radid', "left")
		->join('beacon_tag b','bt.beacon_mac=b.beacon_mac', "left")
		->join('employee e','b.beacon_employee=e.id', "left")
		->join('alocation_type at','e.company_id=at.id')
		->join('alocation a','e.department_id=a.id ')
		->where($www)
		->order_by('id_trs_beacon', 'DESC')
		->get();
		return $data;
	}
	public function getBeaconMonitorEmployeeTrsLimit($www, $limit = 25, $start = 0){
		$data = $this->db
		->select("bt.*,bf.name floor_name, br.name room_name, b.beacon_qr,b.beacon_qr, b.beacon_card_no, e.name as employee_name,e.email as employee_email,e.photo as employee_photo, e.nik_display as employee_nik, e.id as employee_id, at.name company_name, a.name department_name")
		->from('beacon_transaction bt')
		->join('beacon_floor bf','bt.floor_id=bf.id', "left")
		->join('room br','bt.room_id=br.radid', "left")
		->join('beacon_tag b','bt.beacon_mac=b.beacon_mac', "left")
		->join('employee e','b.beacon_employee=e.id', "left")
		->join('alocation_type at','e.company_id=at.id')
		->join('alocation a','e.department_id=a.id ')
		->where($www)
		->limit($limit, $start)
		->order_by('id_trs_beacon', 'DESC')
		->get();
		return $data;
	}
	public function getEmployeeBeacon($www){
		$data = $this->db->select("e.id, e.name, e.is_deleted, b.beacon_name,at.name as div_name,  ")
		->from('employee e')
		->join('beacon_tag b','e.id=b.beacon_employee', "left")
		->join('alocation_type at','e.company_id=at.id')
		->join('alocation a','e.department_id=a.id ')
		->where(array(
			"e.is_deleted"=>0,
		))
		->where($www)
		->get();
		return $data;
	}


}
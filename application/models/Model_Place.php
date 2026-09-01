<?php  
date_default_timezone_set("Asia/Jakarta");
class Model_Place extends CI_Model {
	public function __construct(){
		parent::__construct();
		$this->load->helper('response');
	}
	public function getFloorPlaceData ($filter = []){
		$fetch =  $this->db->select('f.*')
			->from("building_floor f")
			->join("building bui", "f.building_id=bui.id" , 'left')
			->where(['f.is_deleted' => 0,'bui.is_deleted' => 0,]);
		$result = $fetch->get()->result_array();
		return  $result;
	}
	public function getRoomPlaceData ($filter = []){
		$fetch =  $this->db->select('r.*, f.name floor_name, bui.name building_name')
			->from("room r")
			->join("building bui", "r.building_id=bui.id" , 'left')
			->join("building_floor f", "r.floor_id=f.id" , 'left')
			->where(['r.is_deleted' => 0,'r.is_disabled' => 0,'bui.is_deleted' => 0,]);
		if(count($filter) > 0){
			
			$room_cari = @$filter['room_cari'];
			$capacity_min_cari = $filter['capacity_min_cari'];
			$capacity_max_cari = $filter['capacity_max_cari'];
			$facility_cari = $filter['facility_cari'];
			$floor_cari = @$filter['floor_cari'];

			if(isset($floor_cari)){
				if($floor_cari != ""){
					$floor_cari_filter = explode("#", $room_cari);
					$fetch->where_in('floor_id', $floor_cari_filter);
				}
			}
			if(isset($capacity_min_cari)){
				if($capacity_min_cari != ""){
					$capacity_min_cari_filter = $capacity_min_cari - 0;
					$fetch->where('capacity >='.$capacity_min_cari);
					
				}
			}
			if(isset($capacity_max_cari)){
				if($capacity_max_cari != ""){
					$capacity_max_cari_filter = $capacity_max_cari - 0;
					$fetch->where('capacity <='.$capacity_max_cari);
				}
			}
			if(isset($facility_cari)){
				if($facility_cari != ""){
					$facility_cari_filter = explode(",", $facility_cari);
					foreach ($facility_cari_filter as $fkey => $fval) {
						$fetch->like('facility_room', $fval, 'both');
					}
				}
			}
		}
		$result = $fetch->get()->result_array();
		// echo $this->db->last_query();
		return  $result;
	}



	
}
<?php  
date_default_timezone_set("Asia/Jakarta");
class Model_Deskbooking extends CI_Model {
	public function __construct(){
		parent::__construct();
		$this->load->helper('response');
		// $this->load->library('database');

		// $this->load->model('', '', true);
	}

	public function getRoomData($wherein){
		$data = $this->db->select("*")
		->from('desk_room')
		->where_in("id",$wherein)
		->where(array(
			"is_deleted"=>0,
		))
		->get();
		return $data;
	}

	public function getDataController($where = array(), $where2 ="" ){
		
		$data = $this->db->select("*")
		->from('desk_controller d')
		->where($where)
		// ->where($where2)
		->where(array(
			"d.is_deleted"=>0,
		));
		if($where2 != ""){
			$data -> where($where2);
		}
		$dbdata = $data ->get();
		$sn = array(
				"error" => null,
				"data" => $dbdata->result_array()
		);
		return $sn;
	}
	public function getDataControllerInitial($where = array(), $where2 ="" ){
		$data = $this->db->select("d.*, r.name room_name, rt.zone_id, rt.block_number")
		->from('desk_controller_initial d')
		->join('desk_room_table rt', 'd.desk_id=rt.desk_id', 'left')
		->join('desk_room r', 'd.desk_room_id=r.id', 'left')
		->where($where)
		->where(array(
			// "r.is_deleted"=>0,
			// "rt.is_deleted"=>0,
		));

		if($where2 != ""){
			$data -> where($where2);
		}
		$dbdata = $data ->get();
		$sn = array(
				"error" => null,
				"data" => $dbdata->result_array()
		);
		return $sn;
	}
	
	public function getDataRoomDeskTable($where = array(), $where2 ="" ){
		$data = $this->db->select("
			d.*, r.name room_name,z.name as zone_name, rt.zone_id, rt.block_number, 
			rt.pointer_desk_x,rt.pointer_desk_y, rt.is_enable, dc.name as controller_name, r.work_start, 
			r.work_end, r.work_day")
		->from('desk_controller_initial d')
		->join('desk_controller dc', 'd.controller_id=dc.id', 'inner')
		->join('desk_room_table rt', 'd.desk_id=rt.desk_id', 'inner')
		->join('desk_room r', 'd.desk_room_id=r.id', 'left')
		->join('desk_room_zone z', 'rt.zone_id=z.zone_id', 'inner')
		->where($where)
		->where(array(
			"rt.is_deleted"=>0,
			// "rt.is_deleted"=>0,
		))->order_by('block_number','ASC');

		if($where2 != ""){
			$data -> where($where2);
		}
		$dbdata = $data ->get();
		$sn = array(
				"error" => null,
				"data" => $dbdata->result_array()
		);
		return $sn;
	}

	public function getDataRoom2($where = array()){
		try{
			$ar = array(
				"r.is_deleted" => 0,
			);
			$data = $this->db->select('r.*, 
					b.name as building_name,
					b.detail_address as building_detail,
					b.google_map as building_google_map
					')
					->from("desk_room r")
					->join("building b", "r.building_id=b.id", 'left')
					->where($ar)
					->where($where)
					
					->order_by("name", "ASC")
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

	public function getRoomZone($where){
		try{
			$ar = array(
				"r.is_deleted" => 0,
			);
			$data = $this->db->select('rz.*, r.room_map, r.name as room_name, r.posmap')
					->from("desk_room_zone rz")
					->join("desk_room r", "rz.desk_room_id=r.id", 'left')
					->where($ar)
					->where($where)
					->order_by("name", "ASC")
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

	public function getRoomRadid($id){
		try{
			$ar = array(
				"is_deleted" => 0,
				"id" => $id
			);
			$data = $this->db->select('*')
					->from("desk_room")
					->where($ar)
					->order_by('id', 'ASC')
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

	// // // // // // // // // //
	// // // // // // // // // //
	// DESK BOOKING TRANSACTION
	// // // // // // // // // //
	// // // // // // // // // //
	public function getBookingInfo($booking_id){
		try{
			
			$ar = array(
				"b.booking_id" => $booking_id,
			);
			$data = $this->db->select('b.*, r.name room_name, work_start, work_end, work_day')
					->from("desk_booking b ")
					->join("desk_room r ", "b.room_id=r.id")
					->where($ar)
					->get();
			$sn = array(
				"error" => null,
				"data" => $data->row_array()
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
	public function getDataBookingById($id){
		try{
			$ar = array(
				// "b.is_deleted" => 0,
				"b.booking_id" => $id,

			);
			$data = $this->db->select('b.*, r.name as room_name,
				r.description as room_description, 
				r.location as room_location, 
				r.capacity as room_capacity, 
				r.google_map as room_google_map, 
				bui.name as building_name, 
				bui.detail_address as building_detail_address, 
				bui.google_map as building_google_map, 
				price')
					->from("desk_booking b")
					->join("desk_room r", "b.room_id=r.ID" , 'left')
					->join("building bui", "r.building_id=bui.id" , 'left')
					->where($ar)
					->get();
			$sn = array(
				"error" => null,
				"data" => $data->row_array()
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

	public function getDataRoomDeskBooking($whereString = ""){
		try{
			$ar = array(
				"r.is_deleted" => 0,
				// "r.is_disabled" => 0
			);
			if($whereString == ""){
				$data = $this->db->select('r.*, 
					b.name as building_name,
					b.detail_address as building_detail,
					b.google_map as building_google_map
					')
					->from("desk_room r")
					->join("building b", "r.building_id=b.id", 'left')
					->where($ar)
					->order_by("name", "ASC")
					->get();
			}else{
				$data = $this->db->select('r.*, 
					b.name as building_name,
					b.detail_address as building_detail,
					b.google_map as building_google_map
					')
					->from("desk_room r")
					->join("building b", "r.building_id=b.id", 'left')
					->where($ar)
					->where($whereString)
					->order_by("name", "ASC")
					->get();
			}
			
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
	public function checkDeskBookingRoom($date, $start, $end, $room, $desk_id){
		try{
			$wh = array(
				"room_id" => $room,
				"desk_id" => $desk_id,
				"is_canceled" => 0,
				"date" => $date
			);
			$data2= $this->db->select('*')
					->from("desk_booking")
					->where($wh)
					->where("start BETWEEN TIME('$start') AND TIME('$end')")
					->or_where("end BETWEEN TIME('$start') AND TIME('$end')")
					;
			$data = $data2->get();
			$print = $this->db->last_query();
			// echo $print ;
			// die();
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

	public function getDataBooking($date1, $date2, $where = null){
		try{
			$ar = array(
				"b.is_deleted" => 0,
				"r.is_deleted" => 0,
				"t.is_deleted" => 0,
			);
			if( $where  == null){
				$where = [];
			}
			$data = $this->db
					// ->distinct()
					->select(' b.*, title,r.name as room_name2, z.name as zone_name, t.block_number as desk_number')
					->from("desk_booking b")
					->join("desk_room r", "b.room_id=r.id")
					->join("desk_room_table t", "b.desk_id=t.desk_id")
					->join("desk_room_zone z", "t.zone_id=z.zone_id")
					->where("b.date >=", $date1)
					->where("b.date <=", $date2)
					->where($ar)
					->where($where)
					->group_by('b.booking_id')
					->order_by("DATE(b.date)","desc")
					// ->order_by("start", 'desc')
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
	public function getDataBookingByNik($date1, $date2, $nik, $where = null){
		try{
			$ar = array(
				"b.is_deleted" => 0,
				"bi.internal" => 1,
				"bi.nik" => $nik,
				"r.is_deleted" => 0,
				"t.is_deleted" => 0,
			);
			if( $where  == null){
				$where = [];
			}
			$data = $this->db->select('b.*, r.name as room_name2, bi.is_pic, z.name as zone_name, t.block_number as desk_number')
				->from("desk_booking b")
				->join("desk_room r", "b.room_id=r.id")
				->join("desk_room_table t", "b.desk_id=t.desk_id")
				->join("desk_room_zone z", "t.zone_id=z.zone_id")
				->join("desk_booking_invitation bi", "b.booking_id=bi.booking_id")
				->where("b.date >=", $date1)
				->where("b.date <=", $date2)
				->where($ar)
				->where($where)
				->group_by('b.booking_id')
				->order_by("DATE(b.date)","desc")
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
	public function getDataBookingByOther($date1, $date2, $nik){
		try{
			$ar = array(
				"b.is_deleted" => 0,
				"bi.is_pic" => 1,
				"r.is_deleted" => 0,
				"t.is_deleted" => 0,
			);
			$data = $this->db->select('b.*, r.name as room_name, z.name as zone_name, t.block_number as desk_number')
				->from("desk_booking b")
				->join("desk_room r", "b.room_id=r.id")
				->join("desk_room_table t", "b.desk_id=t.desk_id")
				->join("desk_room_zone z", "t.zone_id=z.zone_id")
				->join("desk_booking_invitation bi", "b.booking_id=bi.booking_id")
				->where("b.date >=", $date1)
				->where("b.date <=", $date2)
				->where($ar)
				->where("bi.nik <>'". $nik."' ")
				->order_by("DATE(b.date)","desc")
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

	public function getDataBookingByRoomNow($room, $date, $time){
		try{
			$datetime = $date . " ".$time;
			$ar = array(
				"b.is_deleted" => 0,
				"bi.is_pic" => 1,
				"r.is_deleted" => 0,
				"t.is_deleted" => 0,
			);
			$data = $this->db->select('b.*, r.name as room_name, z.name as zone_name, 
				t.block_number as desk_number,t.pointer_desk_x,t.pointer_desk_y ')
				->from("desk_booking b")
				->join("desk_room r", "b.room_id=r.id")
				->join("desk_room_table t", "b.desk_id=t.desk_id")
				->join("desk_room_zone z", "t.zone_id=z.zone_id")
				->join("desk_booking_invitation bi", "b.booking_id=bi.booking_id")
				->where("b.date =", $date)
				->where("b.room_id =", $room)
				->where(" TIME('".$datetime."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))  ")
				->where("is_expired=0 and is_canceled=0 AND end_early_meeting=0 ")
				->where($ar)
				// ->where("bi.nik <>'". $nik."' ")
				->order_by("DATE(b.date)","desc")
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

	public function getDataDeskBookingOtherTime($room, $date, $deskId, $time){
		try{
			$datetime = $date . " ".$time;
			$ar = array(
				"b.is_deleted" => 0,
				"bi.is_pic" => 1,
				"r.is_deleted" => 0,
				"t.is_deleted" => 0,
			);
			$data = $this->db->select('b.*, r.name as room_name, z.name as zone_name, 
				t.block_number as desk_number,t.pointer_desk_x,t.pointer_desk_y ')
				->from("desk_booking b")
				->join("desk_room r", "b.room_id=r.id")
				->join("desk_room_table t", "b.desk_id=t.desk_id")
				->join("desk_room_zone z", "t.zone_id=z.zone_id")
				->join("desk_booking_invitation bi", "b.booking_id=bi.booking_id")
				->where("b.date", $date)
				->where("b.room_id", $room)
				->where("b.desk_id", $deskId)
				->where(" TIME('".$datetime."') <= TIME(b.start)   ")
				->where("is_expired=0 and is_canceled=0 AND end_early_meeting=0 ")
				->where($ar)
				// ->where("bi.nik <>'". $nik."' ")
				->order_by("DATE(b.date)","desc")
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


	public function getDataBookingPartisipant($p){
		try{
			$ar1 = array(
				"biv.booking_id" => $p['booking_id'],
				"biv.internal" => 1,
			);
			$ar2 = array(
				"biv.booking_id" => $p['booking_id'],
				"biv.internal" => 0,
			);
			$data1 = $this->db->select('biv.*, emp.name as emp_name, emp.no_phone as emp_phone, emp.email as emp_email ')
					->from("booking_invitation biv")
					// ->join("booking_invitation biv", "b.booking_id=biv.booking_id" , "left")
					->join("employee emp", "biv.nik=emp.nik", "left")
					->where($ar1)
					->get();
			$data2 = $this->db->select('biv.* ')
					// ->from("booking b")
					->from("booking_invitation biv")
					// ->join("booking_invitation biv", "b.booking_id=biv.booking_id")
					->where($ar2)
					->get();
			$datab = array(
				"internal" => $data1->result_array(),
				"eksternal" => $data2->result_array(),
			);
			$sn = array(
				"error" => null,
				"data" => $datab
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
	
	public function getDataBookingInvById($id){
		try{
			$ar = array(
				"bi.is_deleted" => 0,
				"bi.booking_id" => $id,
			);
			$data = $this->db->select('bi.*, e.name as name2, e.email as email2, e.no_phone')
					->from("desk_booking_invitation bi")
					->join("employee e", "bi.nik=e.nik", 'left')
					->where($ar)
					// ->group_by('nom_dept'); 
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
	public function getDataBookingNow($date){
		try{
			$ar = array(
				"is_deleted" => 0
			);
			$data = $this->db->select('*')
					->from("desk_booking")
					->where("date", $date)
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
	public function getBookingAlocationPIC($nik){
		try{
			$ar = array(
				"a.is_deleted" => 0,
				"am.nik" => $nik
			);
			$data = $this->db->select('am.*, a.name, type, at.invoice_status as alocation_type_invoice_status, a.invoice_status ')
					->from("alocation_matrix am")
					->join("alocation a", "am.alocation_id=a.id", "left")
					->join("alocation_type at", "a.type=at.name", "left")
					->where($ar)
					->order_by('a.id', 'ASC')
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

	public function checkBookingByTime($room, $desk_id,$date,$start, $end, $type = ""){
		try{
			$ar = array(
				"b.desk_id" => $desk_id,
				"b.room_id" => $room,
				"b.date" => $date,
				"b.is_deleted" => 0,
			);
			if( $type == "start" ){
				$data = $this->db->select('b.*, r.name as room_name, price')
					->from("desk_booking b")
					->join("desk_room r", "b.room_id=r.id" , 'left')
					->where($ar)
					->where(" TIME(b.start) BETWEEN TIME('".$start."') AND TIME('".$end."')  ")
					->get();
			}else{
				$data = $this->db->select('b.*, r.name as room_name, price')
					->from("desk_booking b")
					->join("desk_room r", "b.room_id=r.id" , 'left')
					->where($ar)
					->where(" TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))  BETWEEN TIME('".$start."') AND TIME('".$end."')  ")
					->get();
			}
			
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
	public function checkBookingByTimeRe($room,$desk_id,$date,$start, $end,$bookingid,  $type = ""){
		try{
			$ar = array(
				"b.desk_id" => $desk_id,
				"b.room_id" => $room,
				"b.date" => $date,
				"b.is_deleted" => 0,
			);

			if( $type == "start" ){
				$data = $this->db->select('b.*, r.name as room_name, price')
					->from("desk_booking b")
					->join("desk_room r", "b.room_id=r.id" , 'left')
					->where($ar)
					->where('b.booking_id <>"'.$bookingid.'" ')
					->where(" TIME(b.start) BETWEEN TIME('".$start."') AND TIME('".$end."')  ")
					->get();
			}else{
				$data = $this->db->select('b.*, r.name as room_name, price')
					->from("desk_booking b")
					->join("desk_room r", "b.room_id=r.id" , 'left')
					->where($ar)
					->where('b.booking_id <>"'.$bookingid.'" ')
					->where(" TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))  BETWEEN TIME('".$start."') AND TIME('".$end."')  ")
					->get();
			}

			// $sql = $this->db->last_query();
			// echo $sql;
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

	public function checkKondisiBookingPerRuangan($room,$desk_id,$date,$start, $end){
		$checkBookingByTime = $this->checkBookingByTime($room,$desk_id,$date,$start, $end, "start")['data'];
		$checkBookingByTimeEnd = $this->checkBookingByTime($room,$desk_id,$date,$start, $end, "end")['data'];

		$cCheckdata = array();
		if(count($checkBookingByTime) > 0){
			foreach ($checkBookingByTime as $key => $value) {
				if($value['is_canceled'] == 1 || $value['is_expired'] == 1 || $value['end_early_meeting'] == 1){
					array_push($cCheckdata,  $key);
				}
			}
			foreach ($cCheckdata as $key => $value) {
				unset($checkBookingByTime[$value]);
			}
			if(count($checkBookingByTime) > 0){
				$response = response("fail", array(), "The desk have been created by other ");
				echo $response;
				die();
			}
		}
		if(count($checkBookingByTimeEnd) > 0){
			foreach ($checkBookingByTimeEnd as $key => $value) {
				if($value['is_canceled'] == 1 || $value['is_expired'] == 1 || $value['end_early_meeting'] == 1){
					array_push($cCheckdata,  $key);
				}
			}
			foreach ($cCheckdata as $key => $value) {
				unset($checkBookingByTimeEnd[$value]);
			}
			if(count($checkBookingByTimeEnd) > 0){
				$response = response("fail", array(), "The desk have been created by other ");
				echo $response;
				die();
			}
		}
	}
	public function checkKondisiBookingPerRuanganRes($room,$desk_id,$date,$start, $end,$bookingid){
		$checkBookingByTime = $this->checkBookingByTimeRe($room,$desk_id,$date,$start,$end,$bookingid, "start")['data'];
		$checkBookingByTimeEnd = $this->checkBookingByTimeRe($room,$desk_id,$date,$start,$end,$bookingid, "end")['data'];
		$cCheckdata = array();
		if(count($checkBookingByTime) > 0){
				foreach ($checkBookingByTime as $key => $value) {
					if($value['is_canceled'] == 1 || $value['is_expired'] == 1 || $value['end_early_meeting'] == 1){
						array_push($cCheckdata,  $key);
					}
				}
				foreach ($cCheckdata as $key => $value) {
					unset($checkBookingByTime[$value]);
				}
				if(count($checkBookingByTime) > 0){
					$response = response("fail", array(), "The desk have been created by other ");
					echo $response;
					die();
				}
		}
		if(count($checkBookingByTimeEnd) > 0){
				foreach ($checkBookingByTimeEnd as $key => $value) {
					if($value['is_canceled'] == 1 || $value['is_expired'] == 1 || $value['end_early_meeting'] == 1){
						array_push($cCheckdata,  $key);
					}
				}
				foreach ($cCheckdata as $key => $value) {
					unset($checkBookingByTimeEnd[$value]);
				}
				if(count($checkBookingByTimeEnd) > 0){
					$response = response("fail", array(), "The desk have been created by other ");
					echo $response;
					die();
				}
		}
	}

	

	public function qrLoginDeskbooking($qr){
		try{
			
			$ar = array(
				"b.booking_id" => $booking_id,
			);
			$data = $this->db->select('b.*, r.name room_name, work_start, work_end, work_day')
					->from("desk_booking b ")
					->join("desk_room r ", "b.room_id=r.id")
					->where($ar)
					->get();
			$sn = array(
				"error" => null,
				"data" => $data->row_array()
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

	// DESKBOOKING MOBILE

	public function getAllSchedule($post){
		try{
			$datemobile_sp = "";
			$strtotime = strtotime($post['date'].":00");
			$date = $post['date'];
			$time = $post['time'];
			$ar = array(
				"bi.is_deleted" => 0,
				"e.is_deleted" => 0,
				"e.nik" => $post['nik']
			);
			$data = $this->db->select('b.*, r.location, 
					r.name as room_name, r.facility_room, r.capacity, 
					bl.name as building_name,
					r.work_day, r.work_start, r.work_end,
					bi.pin_room, 
					bi.is_pic, bi.attendance_status, bi.attendance_reason, bi.execute_attendance,
					(SELECT bii.nik FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_phone,

					(SELECT ee.email FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_email,
					(SELECT ee.no_phone FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_ext
					')
					->select('e.email, e.no_phone, e.no_ext')
					->select('(SELECT COUNT(*) FROM booking_invitation bii WHERE
						bii.booking_id=b.booking_id) as num_partisipant  ')
					->from("employee e ")
					->join("desk_booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("desk_booking b ", "bi.booking_id=b.booking_id")
					->join("desk_room r ", "b.room_id=r.id")
					->join("building bl ", "r.building_id=bl.id","left")
					->where($ar)
					// ->where(" b.date >= '".$post['date1']."'  AND  b.date <= '".$post['date2']."'  " )
					->order_by('b.date', 'ASC')
					// ->order_by('b.start', 'ASC')
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

	public function getAllScheduleDate($post){
		try{
			$datemobile_sp = "";
			$strtotime = strtotime($post['date'].":00");
			$date = $post['date'];
			$time = $post['time'];
			$ar = array(
				"bi.is_deleted" => 0,
				// "b.is_deleted" => 0,
				"e.is_deleted" => 0,
				// "b.is_expired" => 0,
				// "b.is_canceled" => 0,
				// "b.end_early_meeting" => 0,
				"e.nik" => $post['nik']
			);
			$data = $this->db->select('b.*, r.location, 
					r.name as room_name2,
					r.facility_room, r.capacity, 
					r.work_day, r.work_start, r.work_end,
					bl.name as building_name,
					bi.pin_room, 
					bi.is_pic, bi.attendance_status, bi.attendance_reason, bi.execute_attendance,
					(SELECT bii.nik FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_phone,
					(SELECT ee.email FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_email,
					(SELECT ee.no_phone FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_ext
					
					')
					->select('e.email, e.no_phone, e.no_ext')
					->select('(SELECT COUNT(*) FROM booking_invitation bii WHERE
						bii.booking_id=b.booking_id) as num_partisipant  ')
					->from("employee e ")
					->join("desk_booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("desk_booking b ", "bi.booking_id=b.booking_id")
					->join("desk_room r ", "b.room_id=r.id")
					->join("building bl ", "r.building_id=bl.id","left")
					->where($ar)
					->where(" b.date >= '".$post['date1']."'  AND  b.date <= '".$post['date2']."'  " )
					->group_by('b.booking_id')
					->order_by('b.id', 'DESC')
					// ->order_by('b.start', 'ASC')
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
	public function getOneSchedule($booking_id){
		try{
			$ar = array(
				"b.booking_id" => $booking_id,
			);
			$data = $this->db->select('b.*, r.location, 
					r.name as room_name, r.facility_room, r.capacity, 
					r.work_day, r.work_start, r.work_end,
					bl.name as building_name,
					bi.pin_room, 
					bi.is_pic, bi.attendance_status, bi.attendance_reason, bi.execute_attendance,
					(SELECT bii.nik FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_phone,
					(SELECT ee.email FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_email,
					(SELECT ee.no_phone FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_ext
					
					')
					->select('e.email, e.no_phone, e.no_ext')
					->select('(SELECT COUNT(*) FROM booking_invitation bii WHERE
						bii.booking_id=b.booking_id) as num_partisipant  ')
					->from("employee e ")
					->join("desk_booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("desk_booking b ", "bi.booking_id=b.booking_id")
					->join("desk_room r ", "b.room_id=r.id")
					->join("building bl ", "r.building_id=bl.id","left")
					->where($ar)
					->order_by('b.id', 'DESC')
					// ->order_by('b.start', 'ASC')
					->get();
			
			$sn = array(
				"error" => null,
				"data" => $data->row_array()
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

	public function getListToday($post){
		try{
			$datemobile_sp = "";
			$strtotime = strtotime($post['date'].":00");
			$date = $post['date'];
			$time = $post['time'];
			$ar = array(
				"bi.is_deleted" => 0,
				"b.is_deleted" => 0,
				"e.is_deleted" => 0,
				"b.is_expired" => 0,
				"b.is_canceled" => 0,
				"b.end_early_meeting" => 0,
				"b.date" => $date,
				"e.nik" => $post['nik']
			);
			$data = $this->db->select('b.*, r.location, 
					r.name as room_name,r.image as room_image, r.facility_room, r.capacity, 
					r.work_day, r.work_start, r.work_end,
					bi.is_pic,bi.pin_room, bi.attendance_status, bi.attendance_reason, bi.execute_attendance,
					(SELECT bii.nik FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id) as pic_no_phone,
					(SELECT ee.email FROM desk_booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id) as pic_email
					')
					->from("employee e ")
					->join("desk_booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("desk_booking b ", "bi.booking_id=b.booking_id", "left")
					->join("desk_room r ", "b.room_id=r.id", "left")
					->where($ar)
					->where("TIME(b.end) > '".$time."'"  )
					->order_by('b.start', 'ASC')
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
<?php  
// date_default_timezone_set("Asia/Jakarta");
class Model_Api2 extends CI_Model {
	public function __construct(){
		parent::__construct();
		// $this->load->helper('response');
		// $this->load->library('database');

		// $this->load->model('', '', true);
	}
	public function checkHardwareDevice($hardware_id = ""){
		$where_username = array(
				'hardware_id' => $hardware_id,
				
			);
			$qusername = $this->db->select('dpi.* ')
				->from('device_player_integration dpi')
				->where($where_username)
				->get();
			
			$ret = $qusername->row_array();
			return $ret;
	}


	public function checkRefresh($username, $nik = ""){
		try{
			
			$where_username = array(
				'username' => $username,
				'e.nik' => $nik,
				'e.is_deleted' => 0,
				'u.is_disactived' => 0,
			);
			$qusername = $this->db->select(' e.* , level_id, u.username, u.secure_qr, u.access_id, at.name company_name, a.name department_name')
				->from('user u')
				->join('employee e', 'u.employee_id = e.id', 'left')
				->join("alocation_type at", "e.company_id=at.id", 'left')
				->join("alocation a", "e.department_id=a.id", 'left')
				->where($where_username)
				->get();
			$ret = $qusername;
			return $ret;

		}catch(Exception $error){
			$sn = array(
				"error" => $error,
				"data" => $this->db->error()
			);
			return $sn;
		}
	}
	// MENU 
	public function getDataMobileMenu(){
		$w = array("is_deleted" => 0);
		$q = "SELECT ma.* FROM menu_apps  ma
		INNER JOIN module_backend mb ON ma.module_text=mb.module_text
		WHERE ma.is_deleted=0 
		AND mb.is_enabled=1
		ORDER BY ma.sort ASC";
		$query = $this->db->query($q);
		return $query->result_array();
	}




	public function getScanData($id, $pinroom) {
		$where = array(
			'bi.booking_id' => $id,
			'bi.pin_room' => $pinroom,
		);
		$this->db->select('bi.*, bl.name as building_name,
			r.location, r.name as room_name, r.facility_room, r.capacity')
		->from('booking_invitation bi')
		->join('booking b','bi.booking_id=b.booking_id','left')
		->join("room r ", "b.room_id=r.radid")
		->join("building bl ", "r.building_id=bl.id","left")
		->where($where)	;
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getEmployeeByNIk($nik) {
		$where = array(
			'e.nik' => $nik,
		);
		$this->db->select('e.*,a.name as company, a.name as alocation_name ')
		->from('employee e')
		->join('alocation_matrix am', 'e.nik = am.nik', 'left')
		->join('alocation a', 'am.alocation_id = a.id', 'left')
		->where($where)	;
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getNikEmployeeByEmail($email){
		try{
			$ar = array(
				"is_deleted" => 0,
				"email" => $email
			);
			$data = $this->db->select('*')
					->from("employee")
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

	public function getBookingPic($booking_id) {
		$where = array(
			'booking_id' => $booking_id,
			"internal" =>1,
			"is_pic"=>1
		);
		$this->db->select('nik')
		->from('booking_invitation')
		->where($where)	;
		$query = $this->db->get();
		return $query->row_array();
	}

	public function getParticipantData($where) {
		$data = $this->db->select('
			bi.*, e.name employee_name, e.nik employee_nik, e.nik_display employee_nik_display, e.email employee_email, e.no_phone employee_no_phone, e.no_ext employee_no_ext, e.gender employee_gender,at.name company_name,a.name department_name
			')
		->from("booking_invitation bi")
		->join("employee e","bi.nik=e.id","left")
		->join("alocation_type at","e.company_id=at.id","left")
		->join("alocation a","e.department_id=a.id","left")
		->where($where)
		->where(array('bi.is_deleted' => 0))
		->get();
		return $data;
	}

	public function getDeskParticipantData($where) {
		$data = $this->db->select('
			bi.*, e.name employee_name, e.nik employee_nik, e.nik_display employee_nik_display, e.email employee_email, e.no_phone employee_no_phone, e.no_ext employee_no_ext, e.gender employee_gender,at.name company_name,a.name department_name
			')
		->from("desk_booking_invitation bi")
		->join("employee e","bi.nik=e.id","left")
		->join("alocation_type at","e.company_id=at.id","left")
		->join("alocation a","e.department_id=a.id","left")
		->where($where)
		->where(array('bi.is_deleted' => 0))
		->get();
		return $data;
	}
	public function getMeetingMergeListByDisplay($post){
		// print_r($post);
		try{
			$strtotime = strtotime($post['date'].":00");
			$date = $post['date'];
			$time = $post['time'];
			// print_r($time);
			$ar = array(
				"b.is_deleted" => 0,
				"b.is_expired" => 0,
				"b.is_canceled" => 0,
				"b.end_early_meeting" => 0,
				"b.date" => $date,
				"b.is_merge" => 1,
				"b.merge_room_id" => $post['room_id']
			);
			$dataquery = $this->db->select('b.*, r.name')
					->from("booking b ")
					->join("room r ", "b.room_id=r.radid")
					->where($ar)
					// ->where("TIME(start) > TIME('".$time."') " )
					->get();
					
			$sn = array(
				"error" => null,
				"data" => $dataquery->result_array(),
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
	// ===========================================================
	// Room
	// ===========================================================

	public function getRoomFacilityList(){
		// print_r($post);
		try{
			$where = array(
				"is_deleted" => 0,
			);
			$data = $this->db->select('*')
					->from("facility")
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
	public function getMergeRoomList($post){
		// print_r($post);
		try{
			$where = array(
				"r.is_deleted" => 0,
				"rmd.room_id" => $post['room_id'],

			);
			$data = $this->db->select('r.*')
					->from("room_merge_detail rmd")
					->join("room r", "rmd.room_id=r.radid")
					->where($where)
					->order_by("r.name", "ASC")
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

	// ==============================================
    // BUILDING
    // ==============================================
    public function getDataBuilding($where = array())
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            // $count_room = "select count() from room rr where rr.is_deleted = 0 "
            $data = $this->db->select('*,
				(select count(rr.radid) from room rr where rr.is_deleted = 0 AND building.id=rr.building_id)  as count_room,
				(select count(ff.id) from beacon_floor ff where ff.is_deleted = 0 AND building.id=ff.building_id)  as count_floor,
				(select count(ddd.desk_id) from desk_room_table ddd INNER JOIN desk_room dr ON ddd.desk_room_id=dr.id where ddd.is_deleted = 0 AND building.id=dr.building_id)  as count_desk,
				 ')
                ->from("building")
                ->where($ar)
                ->where($where)
                ->order_by('id', 'ASC')
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->result_array(),
            );
            return $sn;
        } catch (Exception $error) {
            $sn = array(
                "error" => $error,
                "data"  => $this->db->error(),
            );
            return $sn;
        }
    }
    
	// ===========================================================
	// DISPLAY PANTRY
	// ===========================================================

	public function getDisplayPantry(){
		try{
			$ar = array(
				"is_deleted" => 0,
			);
			$data = $this->db->select('*, id as pantryId')
					->from("pantry")
					->where($ar)
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


	public function getListMoved($post){
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
				"b.is_alive" => 0,
				"b.is_moved" => 1,
				"b.end_early_meeting" => 0,
				"e.nik" => $post['nik'],
				"bi.is_pic" => 1,
			);
			$data = $this->db->select('b.*, r.location, 
					r.name as room_name,r.image as room_image, 
					r.facility_room, r.capacity, 
					r.work_day, r.work_start, r.work_end,
					bl.name as building_name,
					bi.is_pic,bi.pin_room, bi.attendance_status, bi.attendance_reason, bi.execute_attendance,
					(SELECT bii.nik FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id) as pic_no_phone,

					(SELECT ee.email FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id) as pic_email
					')
					->from("employee e ")
					->join("booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("booking b ", "bi.booking_id=b.booking_id", "left")
					->join("room r ", "b.room_id=r.radid", "left")
					->join("building bl ", "r.building_id=bl.id","left")
					->where($ar)
					// ->where("TIME(b.end) > '".$time."'"  )
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

	// ===========================================================
	// DISPLAY MEETING
	// ===========================================================
	public function getMeetingOccupiedByDisplay($post){
		try{
			$strtotime = strtotime($post['date'].":00");
			$date = $post['date'];
			$time = $post['time'];
			$ar = array(
				"b.is_alive" => 1,
				"b.is_deleted" => 0,
				"b.is_expired" => 0,
				"b.is_canceled" => 0,
				"b.end_early_meeting" => 0,
				"b.server_date" => $date,
				"b.room_id" => $post['room_id']
			);
			$dataquery = $this->db->select('b.*, r.name')
					->from("booking b ")
					->join("room r ", "b.room_id=r.radid")
					->where($ar)
					->where("TIME(server_start) < TIME('".$time."')  AND TIME(DATE_ADD(server_end, INTERVAL extended_duration MINUTE)) > TIME('".$time."') " )
					->get();
					
			$sn = array(
				"error" => null,
				"data" => $dataquery->result_array(),
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
	public function getMeetingListByDisplay($post, $roomSelect = []){
			// $strtotime = strtotime($post['date'].":00");
			$date = $post['date'];
			$time = $post['time'];
			// print_r($time);
			$ar = array(
				"b.is_alive" => 1,
				"b.is_deleted" => 0,
				"b.is_expired" => 0,
				"b.is_canceled" => 0,
				"b.end_early_meeting" => 0,
				"b.date" => $date,
				// "b.room_id" => $post['room_id']
			);

			$whereIn = $roomSelect;
			$dataquery = $this->db->select('b.*, r.name')
					->from("booking b ")
					->join("room r ", "b.room_id=r.radid")
					->where($ar)
					->where("TIME(server_start) > TIME('".$time."') " );
			if(count($whereIn) >0 ){
				$this->db->where_in('b.room_id',$whereIn);
			}	
			$fetct = $dataquery->get();
			return $fetct->result_array();
		
	}
	public function getMeetingListOccupiedByDisplay($post, $roomSelect = []){
			// $strtotime = strtotime($post['date'].":00");
			$date = $post['date'];
			$time = $post['time'];
			// print_r($time);
			$ar = array(
				"b.is_alive" => 1,
				"b.is_deleted" => 0,
				"b.is_expired" => 0,
				"b.is_canceled" => 0,
				"b.end_early_meeting" => 0,
				"b.date" => $date,
				// "b.room_id" => $post['room_id']
			);
			$whereIn = $roomSelect;
			$dataquery = $this->db->select('b.*, r.name')
					->from("booking b ")
					->join("room r ", "b.room_id=r.radid")
					->where($ar)
					->where("TIME(server_start) < TIME('".$time."')  AND TIME(DATE_ADD(server_end, INTERVAL extended_duration MINUTE)) > TIME('".$time."') " );
			if(count($whereIn) >0 ){
				$this->db->where_in('b.room_id',$whereIn);
			}	
			$fetct = $dataquery->get();
			return $fetct->result_array();
		
	}
	
}



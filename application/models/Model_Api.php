<?php  
date_default_timezone_set("Asia/Jakarta");
class Model_Api extends CI_Model {
	public function __construct(){
		parent::__construct();
		$this->load->helper('response');
		// $this->load->library('database');

		// $this->load->model('', '', true);
	}
	public function postApiLog($username, $method){
		$uri = get_uri_web();
		$data = array(
			'username' => $username,
			'method' => strtoupper( $method),
			'uri' => $uri['host'],
			'client_ip' => $uri['client'],
			'datetime' => date("Y-m-d H:i:s"),
			'route' => $uri['segment']['uri_string'],
		);
		$dta = $this->db->insert("log_api_trs", $data);
		return $dta;
	}
	public function insertData($table, $data){
		$dta = $this->db->insert($table, $data);
		return $dta;
	}
	public function insertDataBatch($table, $data){
		$dta = $this->db->insert_batch($table, $data);
		return $dta;
	}
	public function deleteAll($table, $where){
		$dta = $this->db->delete($table, $where);
		return $dta;
	}
	public function updateData($table, $data, $where){
		$this->db->where($where);
		$dta = $this->db->update($table, $data);
		return $dta;
	}
	public function select_sql($table="", $select="*",$where = array()){
		$data = $this->db->select($select)
		->from($table)
		->where($where)
		->get();
		return $data;
	}
	public function querySql($query){
		$dta = $this->db->query($query);
		return $dta;
	}
	public function procedure($query, $data){
		$dta = $this->db->query($query, $data);

		return $dta->result_array();
	}

	public function logActivity($action, $description=""){
		$ip = $this->input->ip_address();
		$cur_url =  current_url();
		$time =  date("Y-m-d H:i:s");
		$lq = $this->db->last_query();
		$user_id = $this->session->userdata('user-nya');
		$data = array(
			'nik' => $user_id,
			'access_ip' => $ip,
			'access_url' => $cur_url,
			'access_time ' => $time,
			'access_action ' => $action,
			'access_description ' => $description,
			'access_query' => $lq
		);
		$dta = $this->db->insert('log_activity', $data);
		return $dta;
	}
	public function select_all_data($table, $where, $field = array(), $result = 'result'){
		if(count($field)>0 ){
			$f = implode(",",  $field);
			$db = $this->db->select($f)
			->from($table)
			->where($where)->get();
		}else{
			$db = $this->db->select('*')
			->from($table)
			->where($where)->get();
		}
		if($result == "row"){
			return $db->row_array();
		}else{
			return $db->result_array();
		}
	}
	// ===========================================================
	// COMPANY
	// ===========================================================
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
	public function checkLogin($username, $password){
		try{
			
			$where_username = array(
				'username' => $username,
				'password' => $password,
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
			$ret = array();
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

	

	public function getInvitationInternal($id){
		try{
			$ar = array(
				"b.is_canceled" => 0,
				"b.booking_id" => $id,
				"bi.internal" =>1,
			);
			$data = $this->db->select('e.name, e.division_id, e.email, e.id as employee_id, e.nik')
					->from(" booking b ")
					->join(" booking_invitation bi", "b.booking_id=bi.booking_id")
					->join(" employee e", "bi.employee_id=e.id")
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
	public function getInvitationExternal($id){
		try{
			$ar = array(
				"b.is_canceled" => 0,
				"b.booking_id" => $id,
				"bi.internal" =>0,
			);
			$data = $this->db->select('bi.name, bi.email, bi.company')
					->from(" booking b ")
					->join(" booking_invitation bi", "b.booking_id=bi.booking_id")
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
	public function getInvitationInternalNik($id){
		try{
			$ar = array(
				// "b.is_canceled" => 0,
				"b.booking_id" => $id,
				"bi.internal" =>1,
			);
			$data = $this->db->select('e.name,no_phone,no_ext, e.division_id, e.email, e.id as employee_id, e.nik')
					->from(" booking b ")
					->join(" booking_invitation bi", "b.booking_id=bi.booking_id")
					->join(" employee e", "bi.nik=e.id")
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
	public function getInvitationExternalNik($id){
		try{
			$ar = array(
				// "b.is_canceled" => 0,
				"b.booking_id" => $id,
				"bi.internal" =>0,
			);
			$data = $this->db->select('bi.name, bi.email, bi.company')
					->from(" booking b ")
					->join(" booking_invitation bi", "b.booking_id=bi.booking_id")
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
	// ===========================================================
	// Schedule
	// ===========================================================
	public function getBookingInfo($booking_id){
		try{
			
			$ar = array(
				"b.booking_id" => $booking_id,
			);
			$data = $this->db->select('b.*, r.name room_name, work_start, work_end, work_day')
					->from("booking b ")
					->join("room r ", "b.room_id=r.radid")
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
	public function getDataActiveByRoom($post){
		try{
			
			// $datemobile_sp = 
			// 
			$strtotime = strtotime($post['date'].":00");
			$date = date('Y-m-d',$strtotime);
			$time = date('Y-m-d H:i:s',$strtotime);
			$ar = array(
				"b.is_alive" => 1,
				"b.is_deleted" => 0,
				"b.is_expired" => 0,
				"b.is_canceled" => 0,
				"b.date" => $date,
				"r.id" => $post['room_id']
			);
			$data = $this->db->select('b.is_rescheduled as isReschedule, b.is_canceled as isCancel, b.booking_id as bookingId, date,  SUBSTRING(TIME(b.end),1,5) as finishTime, SUBSTRING(TIME(b.start),1,5) as startTime, pic, r.name as roomName, b.room_id as roomId,  b.title ')
					->from("booking b ")
					->join("room r ", "b.room_id=r.id")
					->where($ar)
					->where("TIME('$time') BETWEEN TIME(start) AND TIME(end) ")
					// ->or_where("end BETWEEN TIME('$start') AND TIME('$end')")
					// ->order_by('b.date', 'DESC')
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
	public function getDataMonitorSoonByRoom($post){
		try{
			
			$datemobile_sp = 
			$strtotime = strtotime($post['date'].":00");
			$date = date('Y-m-d',$strtotime);
			$time = date('Y-m-d H:i:s',$strtotime);
			$ar = array(
				"b.is_alive" => 1,
				"b.is_deleted" => 0,
				"b.is_expired" => 0,
				"b.is_canceled" => 0,
				"b.date" => $date,
				"r.id" => $post['room_id']
			);
			$data = $this->db->select('b.is_rescheduled as isReschedule, b.is_canceled as isCancel, b.booking_id as bookingId, date,  SUBSTRING(TIME(b.end),1,5) as finishTime, SUBSTRING(TIME(b.start),1,5) as startTime, pic, r.name as roomName, b.room_id as roomId,  b.title ')
					->from("booking b ")
					->join("room r ", "b.room_id=r.id")
					->where($ar)
					->where("start > ", $time)
					// ->or_where("end BETWEEN TIME('$start') AND TIME('$end')")
					// ->order_by('b.date', 'DESC')
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
	public function getMeetingListByDisplay($post){
		try{
			$strtotime = strtotime($post['date'].":00");
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
				"b.room_id" => $post['room_id']
			);
			$dataquery = $this->db->select('b.*, r.name')
					->from("booking b ")
					->join("room r ", "b.room_id=r.radid")
					->where($ar)
					->where("TIME(start) > TIME('".$time."') " )
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
				"b.date" => $date,
				"b.room_id" => $post['room_id']
			);
			$dataquery = $this->db->select('b.*, r.name')
					->from("booking b ")
					->join("room r ", "b.room_id=r.radid")
					->where($ar)
					->where("TIME(start) < TIME('".$time."')  AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) > TIME('".$time."') " )
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
	public function getAllMeetingOccupiedByDisplayStatus($post){
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
				"b.date" => $date,
				"b.room_id" => $post['room_id']
			);
			$dataquery = $this->db->select('b.*, r.name')
					->from("booking b ")
					->join("room r ", "b.room_id=r.radid")
					->where($ar)
					->where("TIME(start) < TIME('".$time."')  AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) > TIME('".$time."') " )
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
	public function getEextendTime($post){
		try{
			$strtotime = strtotime($post['date'].":00");
			$date = $post['date'];
			$time = $post['time'];
			$ar = array(
				"b.is_deleted" => 0,
				"b.is_expired" => 0,
				"b.is_canceled" => 0,
				"b.end_early_meeting" => 0,
				"b.date" => $date,
				"b.room_id" => $post['room_id']
			);
			$dataquery = $this->db->select('b.*, r.name')
					->from("booking b ")
					->join("room r ", "b.room_id=r.radid")
					->where($ar)
					->where("TIME(start) < TIME('".$time."')  AND TIME(end) > TIME('".$time."') " )
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
	public function getTimeMonitor($post){
		try{
			$strtotime = strtotime($post['date'].":00");
			$date = date('Y-m-d',$strtotime);
			$start = date('Y-m-d H:i:s',$strtotime);
			$arrayMinute = array("30", "60", "90", "120");
			$ar = array(
				"b.is_deleted" => 0,
				"b.is_expired" => 0,
				"b.is_canceled" => 0,
				"b.date" => $date,
				"b.room_id" => $post['room_id']
			);
			$dataBatch = array();
			foreach ($arrayMinute as $key => $value) {
				$end = date('Y-m-d H:i:s',strtotime($post['date'].":00".' +'.$value.' minutes'));
				$dataquery = $this->db->select('b.booking_id')
					->from("booking b ")
					->join("room r ", "b.room_id=r.id")
					->where($ar)
					->where("start BETWEEN TIME('$start') AND TIME('$end')")
					->or_where("end BETWEEN TIME('$start') AND TIME('$end')")
					->get();
					$d = array();
					$d['time'] =  strval($value);
					$d['reserved'] =  $dataquery->num_rows();
					$d['room_id'] = $post['room_id'];
					$d['start'] = $start;
					$d['end'] = $end;
					array_push($dataBatch , $d);
			}
			
			$sn = array(
				"error" => null,
				"data" => $dataBatch,
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

	public function getDataActiveByNik($post){
		try{
			
			$datemobile_sp = "";
			$strtotime = strtotime($post['date'].":00");
			$date = $post['date'];
			$time = $post['time'];
			$ar = array(
				"bi.is_deleted" => 0,
				"b.is_alive" => 1,
				"b.is_deleted" => 0,
				"e.is_deleted" => 0,
				"b.is_expired" => 0,
				"b.is_canceled" => 0,
				"b.end_early_meeting" => 0,
				"b.date" => $date,
				"e.nik" => $post['nik']
			);
			// echo $date;
			// echo $post['date'].":00";
			$data = $this->db->select('b.*, r.location, 
					DATE_ADD(end, INTERVAL extended_duration MINUTE) as end_extend,
					r.name as room_name, r.facility_room, r.capacity, 
					bi.pin_room, 
					r.work_day, r.work_start, r.work_end,
					bi.is_pic,
					(SELECT bii.nik FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id) as pic_no_phone,

					(SELECT ee.email FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id) as pic_email,
					(SELECT ee.no_phone FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id) as pic_no_ext
					')
					->select('e.email, e.no_phone, e.no_ext')
					->select('(SELECT COUNT(*) FROM booking_invitation bii WHERE
						bii.booking_id=b.booking_id) as num_partisipant  ')
					->from("employee e ")
					->join("booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("booking b ", "bi.booking_id=b.booking_id", "left")
					->join("room r ", "b.room_id=r.radid", "left")
					->where($ar)
					->where("TIME(start) < TIME('".$time."')  AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) > TIME('".$time."') " )
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
				"b.is_alive" => 1,
				"b.date" => $date,
				"e.nik" => $post['nik']
			);
			$data = $this->db->select('b.*, r.location, 
					r.name as room_name,r.image as room_image, r.facility_room, r.capacity, 
					r.work_day, r.work_start, r.work_end,
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
	public function getListAllToday($post){
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
				"b.date" => $date,
				"e.nik" => $post['nik']
			);
			$data = $this->db->select('b.*, r.location, 
					r.name as room_name, r.facility_room, r.capacity, 
					r.work_day, r.work_start, r.work_end,
					bi.is_pic, bi.attendance_status, bi.attendance_reason, bi.execute_attendance,bi.pin_room,
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
					->where($ar)
					->order_by('TIME(b.start)', 'ASC')
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

	public function getListAllMeeting($type, $post){
		try{
			$datemobile_sp = "";
			$strtotime = strtotime($post['date'].":00");
			$date = $post['date'];
			$time = $post['time'];
			if($type == "user"){
				$ar = array(
					"bi.is_deleted" => 0,
					"e.is_deleted" => 0,
					"e.nik" => $post['nik']
				);
			}else{
				$ar = array(
					"bi.is_deleted" => 0,
					"e.is_deleted" => 0,
					"bi.is_pic" => 1
				);
			}
			$data = $this->db->select('b.*, r.location, 
					r.name as room_name, r.facility_room, r.capacity, 
					r.work_day, r.work_start, r.work_end,
					bi.pin_room,
					bi.is_pic, bi.attendance_status, bi.attendance_reason, bi.execute_attendance,
					(SELECT bii.nik FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_phone,

					(SELECT ee.email FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_email
					')
					->from("employee e ")
					->join("booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("booking b ", "bi.booking_id=b.booking_id", "left")
					->join("room r ", "b.room_id=r.radid", "left")
					->where($ar)
					->where(" b.date >= '".$post['date1']."' AND b.date <= '".$post['date2']."'  " )
					->order_by('TIME(b.start)', 'ASC')
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

	public function getAllSchedule($post){
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
					r.name as room_name, r.facility_room, r.capacity, 
					bl.name as building_name,
					r.work_day, r.work_start, r.work_end,
					bi.pin_room, 
					bi.is_pic, bi.attendance_status, bi.attendance_reason, bi.execute_attendance,
					(SELECT bii.nik FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_phone,

					(SELECT ee.email FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_email,
					(SELECT ee.no_phone FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_ext
					')
					->select('e.email, e.no_phone, e.no_ext')
					->select('(SELECT COUNT(*) FROM booking_invitation bii WHERE
						bii.booking_id=b.booking_id) as num_partisipant  ')
					->from("employee e ")
					->join("booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("booking b ", "bi.booking_id=b.booking_id")
					->join("room r ", "b.room_id=r.radid")
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
	public function getNewAllScheduleDate($WHERE, $post){
		try{
			$ar = array(
				"bi.is_deleted" => 0,
				"e.is_deleted" => 0,
				"e.nik" => $post['nik']
			);
			$data = $this->db->select('b.*, r.location, 
					r.name as room_name2,
					r.facility_room, r.capacity, 
					r.work_day, r.work_start, r.work_end,
					bl.name as building_name,
					bi.pin_room, 
					bi.is_pic, bi.attendance_status, bi.attendance_reason, bi.execute_attendance,
					(SELECT bii.nik FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_phone,
					(SELECT ee.email FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_email,
					(SELECT ee.no_phone FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_ext
					
					')
					->select('e.email, e.no_phone, e.no_ext')
					->select('(SELECT COUNT(*) FROM booking_invitation bii WHERE
						bii.booking_id=b.booking_id) as num_partisipant  ')
					->from("employee e ")
					->join("booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("booking b ", "bi.booking_id=b.booking_id")
					->join("room r ", "b.room_id=r.radid")
					->join("building bl ", "r.building_id=bl.id","left")
					->where($ar)
					->where($WHERE)
					->group_by('b.booking_id')
					->order_by('b.id', 'DESC')
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
					(SELECT bii.nik FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_phone,
					(SELECT ee.email FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_email,
					(SELECT ee.no_phone FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_ext
					
					')
					->select('e.email, e.no_phone, e.no_ext')
					->select('(SELECT COUNT(*) FROM booking_invitation bii WHERE
						bii.booking_id=b.booking_id) as num_partisipant  ')
					->from("employee e ")
					->join("booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("booking b ", "bi.booking_id=b.booking_id")
					->join("room r ", "b.room_id=r.radid")
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
					(SELECT bii.nik FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_phone,
					(SELECT ee.email FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_email,
					(SELECT ee.no_phone FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_ext
					
					')
					->select('e.email, e.no_phone, e.no_ext')
					->select('(SELECT COUNT(*) FROM booking_invitation bii WHERE
						bii.booking_id=b.booking_id) as num_partisipant  ')
					->from("employee e ")
					->join("booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("booking b ", "bi.booking_id=b.booking_id")
					->join("room r ", "b.room_id=r.radid")
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

	public function getReportAlocation($alocation = ""){
		try{
			if($alocation != ""){
				$ar = array(
					"b.alocation_id" => $alocation,
				);
			}
			$ar = array();
			
			$data = $this->db->select('b.*, r.location, 
					r.name as room_name, r.facility_room, r.capacity, 
					r.work_day, r.work_start, r.work_end,
					bl.name as building_name,
					bi.pin_room, 
					bi.is_pic, bi.attendance_status, bi.attendance_reason, bi.execute_attendance,
					(SELECT bii.nik FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_nik,
					(SELECT COALESCE(ee.no_phone, "N/A") FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_phone,
					(SELECT ee.email FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_email,
					(SELECT ee.no_phone FROM booking_invitation bii 
						INNER JOIN employee ee ON bii.nik=ee.nik 
						WHERE is_pic=1 AND bii.booking_id=b.booking_id LIMIT 1) as pic_no_ext
					
					')
					->select('e.email, e.no_phone, e.no_ext')
					->select('(SELECT COUNT(*) FROM booking_invitation bii WHERE
						bii.booking_id=b.booking_id) as num_partisipant  ')
					->from("employee e ")
					->join("booking_invitation bi ", "e.nik=bi.nik", "left")
					->join("booking b ", "bi.booking_id=b.booking_id")
					->join("room r ", "b.room_id=r.radid")
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

	public function getSoonSchedule($post){
		try{
			$ar = array(
				"bi.is_deleted" => 0,
				"b.is_deleted" => 0,
				"e.is_deleted" => 0,
				// "b.is_canceled" => 0,
				// "b.date" => 0,
				"e.nik" => $post['nik']
			);
			$strtotime = strtotime($post['date']);
			$date = date('Y-m-d H:i:s',$strtotime);

			$data = $this->db->select('b.is_rescheduled as isReschedule, b.is_canceled as isCancel, b.booking_id as bookingId, date, bi.is_pic as isPic, SUBSTRING(TIME(b.end),1,5) as finishTime, SUBSTRING(TIME(b.start),1,5) as startTime, e.name, e.nik, e.email, pic, r.name as roomName, b.room_id as roomId,  b.title ')
					->from("employee e ")
					->join("booking_invitation bi ", "e.id=bi.employee_id")
					->join("booking b ", "bi.booking_id=b.booking_id")
					->join("room r ", "b.room_id=r.id")
					->where($ar)
					->order_by('b.date', 'DESC')
					// ->where('b.date >=',)
					->where("start > ", $date)
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
	public function getexpiredschedule($post){
		try{
			$ar = array(
				"bi.is_deleted" => 0,
				"b.is_deleted" => 0,
				"e.is_deleted" => 0,
				// "b.is_canceled" => 0,
				"e.nik" => $post['nik']
			);
			// $post['date'] = str_replace("- ", "", $post['date']);
			$strtotime = strtotime($post['date']);
			$date = date('Y-m-d',$strtotime);
			// $time = date('H:i:s',$strtotime);
			$time = date('Y-m-d H:i:s',$strtotime);
			// echo $time ;
			$data = $this->db->select('b.is_rescheduled as isReschedule, b.is_canceled as isCancel, b.booking_id as bookingId, date, bi.is_pic as isPic, SUBSTRING(TIME(b.end),1,5) as finishTime, SUBSTRING(TIME(b.start),1,5) as startTime, e.name, e.nik, e.email, pic, r.name as roomName, b.room_id as roomId,  b.title ')
					->from("employee e ")
					->join("booking_invitation bi ", "e.id=bi.employee_id")
					->join("booking b ", "bi.booking_id=b.booking_id")
					->join("room r ", "b.room_id=r.id")
					->where($ar)
					->where('b.date <= ',$date)
					->where("end <= ", $time)
					->order_by('b.date', 'DESC')
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
	public function deleteScheduleInvitation($data){
		
		$dta = $this->db
		->set(array(
			"is_deleted" =>1
		))
		->where(array(
			"employee_id" => $data['id'],
			"booking_id" => $data['booking_id'],
		))
		->update('booking_invitation b');
		return $dta;
	}
	public function getDataRoom(){
		try{
			$ar = array(
				"r.is_deleted" => 0
			);
			$data = $this->db->select('r.*, ra.name as ra_name, ra.id as ra_id')
					->from("room r")
					->join("room_automation ra", "r.automation_id=ra.id", 'left')
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
	public function getDataRoomById($rid){
		try{
			$ar = array(
				"r.is_deleted" => 0,
				"r.radid" => $rid,
			);
			$data = $this->db->select('r.*, ra.name as ra_name, ra.id as ra_id')
					->from("room r")
					->join("room_automation ra", "r.automation_id=ra.id", 'left')
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
	public function checkBookingRoom($date, $start, $end, $room){
		try{
			$date = date("Y-m-d", strtotime($date));
			$wh = array(
				"room_id" => $room,
				"is_canceled" => 0,
				"date" => $date
			);
			// echo  $end; 
			
			$start = date("H:i:s", strtotime($start));
			$start = $date ." ".$start;
			$end = date("H:i:s", strtotime($end));
			$end = $date ." ".$end;
			$data2= $this->db->select('*')
					->from("booking")
					->where($wh)
					->where("start BETWEEN TIME('$start') AND TIME('$end')")
					->where("end BETWEEN TIME('$start') AND TIME('$end')")
					;
			$data = $data2->get();
			$print = $this->db->last_query();
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
	public function getUsersBooking($nik=""){
		try{
			$ar = array(
				"e.is_deleted" => 0,
			);
			$data = $this->db->select('e.id as employee_id, e.* , e.division_id as division,
					at.name company_name, a.name department_name')
					->from("employee e")
					->join("user u ", "e.id=u.employee_id")
					->join("alocation_type at", "e.company_id=at.id", 'left')
					->join("alocation a", "e.department_id=a.id", 'left')
					->where($ar)
					->where(" e.nik <>'".$nik."' AND u.level_id <> 1 ")
					->order_by('e.name', 'asc')
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
	public function getEditEmployee($id){
		try{
			$ar = array(
				"is_deleted" => 0,
				"id" => $id
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
	public function getNikEmployee($nik){
		try{
			$ar = array(
				"is_deleted" => 0,
				"nik" => $nik
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
	public function getNikEmployeeByPic($nik){
		try{
			$ar = array(
				"e.is_deleted" => 0,
			);
			$data = $this->db->select(' e.*, a.name as alocation_name, a.id as alocation_id ')
					->from("employee e")
					->join("alocation_matrix am", "e.nik=am.nik", "left")
					->join("alocation a ", "a.id=am.alocation_id", "left")
					->where($ar)
					->where(" (e.nik='".$nik."' OR e.nik_display='".$nik."') ")
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
	
	//  ===========================
	//  PANTRY
	//  ===========================
	public function getPantry(){
		try{
			$ar = array(
				"is_deleted" => 0,
			);
			$data = $this->db->select('id as pantryId, pic, name')
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
	public function getOrderNumber($date){
		try{
			// $ar = array(
			// 	"is_deleted" => 0,
			// );
			$data = $this->db->select('id as pantryId, order_no, datetime')
					->from("pantry_transaksi")
					->where("DATE(datetime) = DATE('".$date."')")
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
	public function getPantryMenuDetail($menuid){
		try{
			$ar = array(
				"is_deleted" => 0,
				"menu_id" => $menuid,


			);
			$data = $this->db->select('*')
					->from("pantry_detail_menu_variant")
					->where($ar)
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
	public function getPantryMenu($post){
		try{
			$ar = array(
				"pd.is_deleted" => 0,
				"ps.is_deleted" => 0,
				"pd.pantry_id" => $post['id'],


			);
			$data = $this->db->select('pd.id as menuId, pd.name,note, ps.name as prefixName ')
					->from("pantry_detail pd")
					->join("pantry_satuan ps", "pd.prefix_id=ps.id")
					->where($ar)
					->order_by("pd.name", "ASC")
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

	public function getAllTrsPantry($id){
		try{
			$ar = array(
				"pt.is_deleted" => 0,
				"pt.employee_id" => $id,
			);
			$data = $this->db->select('pt.id,pt.employee_id,pt.employee_id as order_user, pt.order_no as orderNo ,pt.pantry_id,pt.booking_id, pt.order_st as order,pt.process, pt.complete, pt.failed, pt.done, pt.note, p.name,b.title, b.date,b.start,b.end, r.name as room_name')
					->select('pts.name status_order, pt.order_datetime, pt.order_datetime_before, pt.datetime')

					->select('(SELECT count(*) FROM pantry_transaksi_d ptd WHERE ptd.transaksi_id=pt.id ) count_item ')
					->select('(SELECT name FROM employee e WHERE e.id=pt.employee_id LIMIT 1 ) order_user_name ')
					->select('(SELECT name FROM employee e WHERE e.id=pt.rejected_pantry_by LIMIT 1 ) rejected_pantry_by_name ')
					->select('(SELECT name FROM employee e WHERE e.id=pt.completed_pantry_by LIMIT 1 ) completed_pantry_by_name ')
					->select('(SELECT name FROM employee e WHERE e.id=pt.process_pantry_by LIMIT 1 ) process_pantry_by_name ')
					->from("pantry_transaksi pt")
					->join("pantry p", "pt.pantry_id=p.id", 'left')
					->join("booking b", "pt.booking_id=b.booking_id", 'left')
					->join("room r", "b.room_id=r.radid",'left')
					->join("pantry_transaksi_status pts", "pt.order_st=pts.id",'left')
					->where($ar)
					->order_by("pt.datetime", "ASC")
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
	public function getAllTrsPantryBlive($roomid, $pantryid, $date1, $date2){
		try{
			$ar = array(
				"pt.is_deleted" => 0,
				"pt.room_id" => $roomid,
				"pt.pantry_id" => $pantryid,
			);
			// print_r($ar);
			$data = $this->db->select('pt.id,pt.room_id ,pt.order_no as orderNo ,pt.pantry_id,pt.booking_id, pt.order_st as order,pt.process, pt.complete, pt.failed, pt.done, pt.note, p.name, r.name as room_name')
					->select('pts.name status_order, pt.order_datetime, pt.order_datetime_before, pt.datetime')
					->select('(SELECT count(*) FROM pantry_transaksi_d ptd WHERE ptd.transaksi_id=pt.id ) count_item ')
					->from("pantry_transaksi pt")
					->join("pantry p", "pt.pantry_id=p.id", 'left')
					->join("room r", "pt.room_id=r.radid",'left')
					->join("pantry_transaksi_status pts", "pt.order_st=pts.id",'left')
					->where($ar)
					->where("DATE(pt.datetime)>='".$date1."' AND DATE(pt.order_datetime)<='".$date2."' ")
					->order_by("pt.datetime", "ASC")
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
        
                                      
                                
	public function getProcessTrsPantry($id){
		try{
			$ar = array(
				"pt.is_deleted" => 0,
				"pt.employee_id" => $id,
				"pt.process" => 1
			);
			$data = $this->db->select('pt.id, pt.order_no as orderNo ,pt.pantry_id,pt.booking_id,pt.datetime, pt.order_st as order,pt.process, pt.complete, pt.failed, pt.done, pt.note, p.name,b.title, b.date,b.start,b.end, r.name as room_name')
					->from("pantry_transaksi pt")
					->join("pantry p", "pt.pantry_id=p.id")
					->join("booking b", "pt.booking_id=b.booking_id")
					->join("room r", "b.room_id=r.id")
					->where($ar)
					->order_by("pt.datetime", "ASC")
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
	public function getCompleteTrsPantry($id){
		try{
			$ar = array(
				"pt.is_deleted" => 0,
				"pt.employee_id" => $id,
				"pt.complete" => 1
			);
			$data = $this->db->select('pt.id, pt.order_no as orderNo ,pt.pantry_id,pt.booking_id,pt.datetime, pt.order_st as order,pt.process, pt.complete, pt.failed, pt.done, pt.note, p.name,b.title, b.date,b.start,b.end, r.name as room_name')
					->from("pantry_transaksi pt")
					->join("pantry p", "pt.pantry_id=p.id")
					->join("booking b", "pt.booking_id=b.booking_id")
					->join("room r", "b.room_id=r.id")
					->where($ar)
					->order_by("pt.datetime", "ASC")
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
	public function getDoneTrsPantry($id){
		try{
			$ar = array(
				"pt.is_deleted" => 0,
				"pt.employee_id" => $id,
				"pt.done" => 1
			);
			$data = $this->db->select('pt.id, pt.order_no as orderNo ,pt.pantry_id,pt.booking_id,pt.datetime, pt.order_st as order,pt.process, pt.complete, pt.failed, pt.done, pt.note, p.name,b.title, b.date,b.start,b.end, r.name as room_name')
					->from("pantry_transaksi pt")
					->join("pantry p", "pt.pantry_id=p.id")
					->join("booking b", "pt.booking_id=b.booking_id")
					->join("room r", "b.room_id=r.id")
					->where($ar)
					->order_by("pt.datetime", "ASC")
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
	public function getFailedTrsPantry($id){
		try{
			$ar = array(
				"pt.is_deleted" => 0,
				"pt.employee_id" => $id,
				"pt.failed" => 1
			);
			$data = $this->db->select('pt.id, pt.order_no as orderNo ,pt.pantry_id,pt.booking_id,pt.datetime, pt.order_st as order,pt.process, pt.complete, pt.failed, pt.done, pt.note, p.name,b.title, b.date,b.start,b.end, r.name as room_name')
					->from("pantry_transaksi pt")
					->join("pantry p", "pt.pantry_id=p.id")
					->join("booking b", "pt.booking_id=b.booking_id")
					->join("room r", "b.room_id=r.id")
					->where($ar)
					->order_by("pt.datetime", "ASC")
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
	public function getDetailPantry($post){
		// print_r($post);
		try{
			$where = array(
				"pt.is_deleted" => 0,
				"pt.transaksi_id" => $post['id'],

			);
			$data = $this->db->select('pt.id as item_id ,pt.qty, pt.note_order, pt.note_reject, pt.is_rejected, pd.name, ps.name as prefix, detailorder')
					->from("pantry_transaksi_d pt")
					->join("pantry_detail pd", "pt.menu_id=pd.id")
					->join("pantry_satuan ps", "pd.prefix_id=ps.id")
					->where($where)
					->order_by("pt.id", "ASC")
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
	public function getDetailPantryTrs($post){
		// print_r($post);
		try{
			$where = array(
				"pt.is_deleted" => 0,
				"pt.transaksi_id" => $post['id'],
				// "pt.employee_id" => $post['nik'],

			);
			$data = $this->db->select('pt.id as item_id ,pt.qty, pt.note_order, pt.note_reject, pt.is_rejected, pd.name,pt.detailorder ')
					->from("pantry_transaksi_d pt")
					->join("pantry_detail pd", "pt.menu_id=pd.id")
					->where($where)
					->order_by("pt.id", "ASC")
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
	// PANTRY MONITOR
	// ===========================================================
	public function getOrderEntry($post){
		// echo "1";
		try{
			$now = $post['date'];
			// echo $post['date(format)ate'];
			$where = array(
				"p.is_deleted" => 0,
				"p.order_st" => 0,
				"pantry_id" => $post['pantry_id'],
			);
			$data = $this->db->select('p.order_st,p.process, p.complete,p.failed, p.is_rejected_pantry, p.note_reject,p.pantry_id, p.id as transaksi_id, p.order_no, e.name, e.nik, title, r.name as room_name ,
				b.start as start_booking, b.end as end_booking,
				p.order_datetime, p.order_datetime_before,p.order_st_name, 
				p.completed_at, p.completed_by, p.process_at, p.process_by,p.rejected_at,p.rejected_by
				')
					->from("pantry_transaksi p")
					->join("employee e", "p.employee_id=e.nik", 'left')
					->join("booking b", "p.booking_id=b.booking_id", 'left')
					->join("room r", "b.room_id=r.radid", 'left')
					->where($where)
					->where("DATE(p.datetime) ='".$now ."' ")
					->order_by("p.id", "DESC")
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
	public function getOrderProcess($post){
		try{
			$now = $post['date'];
			$where = array(
				"p.is_deleted" => 0,
				"p.order_st" => 1,
				"pantry_id" => $post['pantry_id'],
			);
			$data = $this->db->select('p.order_st,p.process, p.complete,p.failed, p.is_rejected_pantry, p.note_reject,p.pantry_id, p.id as transaksi_id, p.order_no, e.name, e.nik, title, r.name as room_name,
				b.start as start_booking, b.end as end_booking,
				p.order_datetime, p.order_datetime_before,p.order_st_name, 
				p.completed_at, p.completed_by, p.process_at, p.process_by,p.rejected_at,p.rejected_by
				 ')
					->from("pantry_transaksi p")
					->join("employee e", "p.employee_id=e.nik", 'left')
					->join("booking b", "p.booking_id=b.booking_id", 'left')
					->join("room r", "b.room_id=r.radid", 'left')
					->where($where)
					->where("DATE(p.datetime) ='".$now ."' ")
					->order_by("p.id", "DESC")
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
	public function getOrderComplete($post){
		try{
			$now = $post['date'];
			$where = array(
				// "p.order_st" => 3,
				"pantry_id" => $post['pantry_id'],
				"is_trashpantry" => 0,
			);
			// $or_where = array(
			// 	"p.is_rejected_pantry" => 1,
			// );
			// $or_where2 = array(
			// 	"p.failed" => 1,
			// );
			$data = $this->db->select('is_trashpantry,p.order_st,p.process, p.complete,p.failed, p.is_canceled, p.is_rejected_pantry, p.note_reject,p.pantry_id, p.id as transaksi_id, p.order_no, e.name, e.nik, title, r.name as room_name,
				b.start as start_booking, b.end as end_booking,
				p.order_datetime, p.order_datetime_before,p.order_st_name, 
				p.completed_at, p.completed_by, p.process_at, p.process_by,p.rejected_at,p.rejected_by
				 ')
					->from("pantry_transaksi p")
					->join("employee e", "p.employee_id=e.nik", 'left')
					->join("booking b", "p.booking_id=b.booking_id", 'left')
					->join("room r", "b.room_id=r.radid", 'left')
					->where($where)
					->where(" (p.order_st=3 OR p.order_st=4 OR p.order_st=5 ) ")
					->where("DATE(p.datetime) ='".$now ."' ")
					->order_by("p.id", "DESC")
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
	// Room
	// ===========================================================
	public function getRoomList($post){
		// print_r($post);
		try{
			$where = array(
				"is_deleted" => 0,

			);
			$data = $this->db->select('*')
					->from("room")
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
	public function getRoomId($wheredata = array()){
		// print_r($post);
		try{
			$where = array(
				"is_deleted" => 0,
			);
			$data = $this->db->select('*')
					->from("room")
					->where($where)
					->where($wheredata)
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

	
	// ===========================================================
	// SETTING GENERAL
	// ===========================================================
	public function getGeneralSetting(){
		// print_r($post);
		try{
			$where = array();
			$data = $this->db->select('*')
					->from("setting_rule_booking")
					->where($where)
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
	// ===========================================================
	// ACCESS 
	// ===========================================================
	public function logPinStatus($id){
		try{
			$where = array(
				"id" => $id,
			);
			$data = $this->db->select('*')
					->from("setting_log_config")
					->where($where)
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
	public function checkDataDoorOpen($room_id, $model = ""){
		try{
			$where = array(
				"ai.room_id" => $room_id,
				"ac.is_deleted" => 0
			);
			if($model != ""){
				$where['model_controller'] = $model;
			}
			$data = $this->db->select('ai.room_id,ac.id, ac.access_id, type, ip_controller, delay, channel, ac.name')
					->from("access_control ac")
					->join("access_integrated ai", "ac.id=ai.access_id")
					->where($where)
					->get();
			//echo $this->db->last_query();
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
	public function checkDoorOpenMeetingPin($post){
		try{
			$time = $post['time'];
			$rules = $this->getSettingDataGeneral()['data'];
			$door_before = $rules['notif_unuse_before_meeting']-0;
			$datetime = $post['date'] . " ". $time;
			$convertTime = strtotime($datetime);
			$startSum = date('H:i:s', strtotime("+".$door_before." minutes",$convertTime));
			$endSum = date('H:i:s', strtotime("-".$door_before." minutes",$convertTime));
			
			$where = array(
				"b.date" => $post['date'],
				"b.is_alive" => 1,
				"b.is_canceled" => 0,
				"b.is_expired" => 0,
				"bi.pin_room" => $post['pin'],
				"b.end_early_meeting" => 0,
				"b.room_id" => $post['room_id'],
			);
			$data = $this->db->select('pin_room,b.room_id, b.booking_id, ')
					->from("booking_invitation bi")
					->join("booking b", "bi.booking_id=b.booking_id")
					->where($where)
					->where(" TIME(b.start) <='".$startSum."' AND TIME(b.end) >= '".$endSum."' ")
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
	public function checkDoorOpenMeetingPinDefault($post){
		try{
			$where = array(
				"room_pin_number" => $post['pin'],
			);
			$data = $this->db->select('room_pin_number')
					->from("setting_rule_booking bi")
					->where($where)
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
	public function checkDoorOpenMeetingQr($post){
		try{
			$time = $post['time'];
			$where = array(
				"b.date" => $post['date'],
				"b.room_id" => $post['room_id'],
				"bi.nik" => $post['nik'],
				"b.is_canceled" => 0,
				"b.is_expired" => 0,
				"b.end_early_meeting" => 0,
			);
			$data1 = $this->db->select('b.room_id, b.booking_id, bi.nik')
					->from("booking_invitation bi")
					->join("booking b", "bi.booking_id=b.booking_id")
					->where($where)
					->where(" TIME(b.start) <='".$time."' AND TIME(b.end) >= '".$time."' ")
					->get_compiled_select();
			$data = $this->db->select('b.room_id, b.booking_id, bi.nik')
					->from("booking_invitation bi")
					->join("booking b", "bi.booking_id=b.booking_id")
					->where($where)
					->where(" TIME(b.start) <='".$time."' AND TIME(b.end) >= '".$time."' ")
					->get();
			
			$sn = array(
				"error" => null,
				"data" => $data->result_array(),
				"q" => $data1,
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
	public function checkDoorOpenMeetingQrDisplay($post){
		try{
			$time = $post['time'];
			$where = array(
				"b.date" => $post['date'],
				"b.room_id" => $post['room_id'],
				"b.is_canceled" => 0,
				"b.is_expired" => 0,
				"b.end_early_meeting" => 0,
				"bi.pin_room" => $post['pin'],
				"bi.booking_id" => $post['booking_id'],
			);
			// print_r($where);
			$data1 = $this->db->select('b.room_id, b.booking_id, bi.nik')
					->from("booking_invitation bi")
					->join("booking b", "bi.booking_id=b.booking_id")
					->where($where)
					->where(" TIME(b.start) <='".$time."' AND TIME(b.end) >= '".$time."' ")
					->get_compiled_select();
			$data = $this->db->select('b.room_id, b.booking_id, bi.nik')
					->from("booking_invitation bi")
					->join("booking b", "bi.booking_id=b.booking_id")
					->where($where)
					->where(" TIME(b.start) <='".$time."' AND TIME(b.end) >= '".$time."' ")
					->get();
			
			$sn = array(
				"error" => null,
				"data" => $data->result_array(),
				"q" => $data1,
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
	// DISPLAY SIGNAGE 
	// ===========================================================
	public function getDisplayBySerial($serial = ""){
		
		$w = array(
				"rd.is_deleted" => 0,
			);
			$data = $this->db->select('rd.*')
					->from("room_display rd")
					->where($w)
					->where([
						'rd.display_serial' => $serial,
					])
					->get();
			
			return $data->row_array();
	}
	public function getDataDisplay($post){
		try{
			$ar1 = array("background_update" => 1);
			$ar2 = array("signage_update" => 1);
			$w = array(
				"room_id" => $post['room_id']
			);
			$data = $this->db->select('*')
					->from("room_display")
					->where("room_id='".$post['room_id']."' ")
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
	// SETTING
	// ==============================================
	public function getSettingDataGeneral(){
		try{
			
			$data = $this->db->select('*')
					->from("setting_rule_booking")
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
	public function getSettingInvoiceConfig(){
		try{
			
			$data = $this->db->select('*')
					->from("setting_invoice_config")
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
	public function getSettingEmailSMTPData(){
		try{
			
			$data = $this->db->select('*')
					->from("setting_smtp")
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
	public function getSettingEmailTemplateData(){
		try{
			
			$data = $this->db->select('*')
					->from("setting_email_template")
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
	// INVOICE
	// ==============================================
	public function getInvStatusName(){
		try{
			$ar = array(
				// "is_deleted" => 0	
			);
			$data = $this->db->select('*')
					->from("setting_invoice_text")
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

	// ==============================================
	// BOOKING
	// ==============================================
	public function checkBookingByTime($room,$date,$start){
		try{
			$ar = array(
				"b.room_id" => $room,
				"b.date" => $date,
				"b.is_deleted" => 0,
				"b.is_alive" => 1,
			);
			$data = $this->db->select('b.*, r.name as room_name, price')
					->from("booking b")
					->join("room r", "b.room_id=r.radid" , 'left')
					->where($ar)
					->where("TIME('".$start."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE)) ")
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
	public function checkBookingByTimeRe($room,$date,$start, $bookingid){
		try{
			$ar = array(
				"b.room_id" => $room,
				"b.date" => $date,
				"b.is_deleted" => 0,
			);
			$data = $this->db->select('b.*, r.name as room_name, price')
					->from("booking b")
					->join("room r", "b.room_id=r.radid" , 'left')
					->where($ar)
					->where('b.booking_id <>"'.$bookingid.'" ')
					->where("TIME('".$start."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE)) ")
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
					->from("booking b")
					->join("room r", "b.room_id=r.radid" , 'left')
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

	public function getDataBookingInvById($id){
		try{
			$ar = array(
				"bi.is_deleted" => 0,
				"bi.booking_id" => $id,
			);
			$data = $this->db->select('bi.*, e.name as name2, e.email as email2, e.no_phone')
					->from("booking_invitation bi")
					->join("employee e", "bi.nik=e.nik", 'left')
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
	public function getDataEmployeeWhereInNik($nikArray){
		try{
			
			$data = $this->db->select('name, division_id, email, id as employee_id, nik')
					->from("employee")
					->where_in('nik', $nikArray)
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
	
	public function getEmployeeBooking($data){
		try{
			$ar = array(
				"is_deleted" => 0
			);
			$data = $this->db->select('name, division_id, email, id as employee_id, nik')
					->from("employee")
					->where_in("nik", $data)
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
	public function checkBookingAlocationData($id){
		try{
			$ar = array(
				"a.is_deleted" => 0,
				"a.id" => $id
			);
			$data = $this->db->select('a.*, at.invoice_status as invoice')
					->from("alocation a")
					->join("alocation_type at", "a.type=at.name", "left")
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
	// ===========================================================
	// NOTIFICATION 
	// ===========================================================
	public function getAllNotificationData($nik){
		try{
			$type = $this->db->get('notification_type')->result_array();
			
			$where = array(
				"nik" => $nik,
				"is_deleted" => 0,
			);
			$data = $this->db->select('nd.*, nt.type notif_type, nt.route, nt.topics')
			->from("notification_data nd")
			->join("notification_type nt", 'nd.type=nt.id', 'left')
			->where($where)
			->order_by('id','DESC')
			->get()->result_array();
			
			
			$sn = array(
				"error" => null,
				"data" => $data
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
	// REPORT 
	// ===========================================================
	public function getAttendance($nik,$date1,$date2, $alocation = ""){
		try{
			$where = array(
				"nik" => $nik,
			);
			$waloc = array();
			if($alocation != ""){
				$waloc = array(
					"b.alocation_id" => $alocation,
				);
			}
			$data1 = $this->db->select('COUNT(*) as attend ')
					->from("booking_invitation bii")
					->join("booking b", "bii.booking_id=b.booking_id")
					->where($where)
					->where(" (attendance_status=1 OR is_pic=1 ) ")
					->where(" b.date >= '".$date1."' AND b.date <= '".$date2."' ")
					->where($waloc)
					->get();
			$data2 = $this->db->select('COUNT(*) as no_attend ')
					->from("booking_invitation bii")
					->join("booking b", "bii.booking_id=b.booking_id")
					->where($where)
					->where(" (attendance_status=0 AND is_pic=0 ) ")
					->where(" b.date >= '".$date1."' AND b.date <= '".$date2."' ")
					->where($waloc)
					->get();
			// $sql = "SELECT * "
			$data = array(
				'attend' => $data1->row_array()['attend'],
				'no_attend' =>  $data2->row_array()['no_attend']
			);
			$sn = array(
				"error" => null,
				"data" => $data
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
	
	public function getInvitation($nik,$date1,$date2, $alocation = ""){
		try{
			$where = array(
				"nik" => $nik,
			);
			$waloc = array();
			if($alocation != ""){
				$waloc = array(
					"b.alocation_id" => $alocation,
				);
			}
			$data = $this->db->select('COUNT(*) as invitation ')
					->from("booking_invitation bii")
					->join("booking b", "bii.booking_id=b.booking_id")
					->where($where)
					->where(" b.date >= '".$date1."' AND b.date <= '".$date2."' ")
					->where($waloc)
					->get();
			$sn = array(
				"error" => null,
				"data" => $data->row_array()
			);
			return $sn;
		}catch(Exception $error){
			$sn = array(
				"error" => $error,
				"data" => $data
			);
			return $sn;
		}
	}

	// ==============================================
	// DISPLAY SIGNAGE
	// ==============================================
	public function getSignageMeeting($date){
		try{
			$time = date('Y-m-d H:i:s');
			$ar = array(
				// "b.room_id" => $room,
				"b.date" => $date,
				"b.is_deleted" => 0,
				"b.is_alive" => 1,

			);
			$data = $this->db->select('b.*, r.name as room_name, price')
					->from("booking b")
					->join("room r", "b.room_id=r.radid" , 'left')
					->where($ar)
					->where(' end < "'.$time.'" AND (end_early_meeting = 0 )')
					// ->where("TIME('".$start."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration MINUTE)) ")
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
?>

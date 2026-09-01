<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class DeskBooking extends CI_Controller {

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
		$this->load->library('mqtt');
		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Notif');
		$this->load->model('Model_Api');
		$this->load->model('Model_Api2', 'Api2');
		$this->load->model('Model_Deskbooking');
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
		$pagename = "Desk Transaction";
		$module_desk = $this->Model_Module->get_module_desk();
		if($module_desk['is_enabled'] != 1){
			redirect('authentication');
			die();
		}
		
		$modules['desk'] = $module_desk;
		$employee = $data = $this->Model_Admin->getDataEmployee();
		$settinggeneral = $this->Model_Admin->getSettingDataGeneral()['data'];
		$menu = $this->Model_Menu->getMenu($pagename);
		if($this->session->userdata('levelid-nya') == 1){
			$this->load->view('Desk/Booking/index', 
				array('menumaster'=> $menu, 
					 'modules'=>$modules,
					'pagename' => $pagename, 
					// 'invoice' => json_encode($invoicename), 
					'employee'=> $employee['data'], 
					'settinggeneral'=> json_encode($settinggeneral)
				)
			);
		}else if($this->session->userdata('levelid-nya') == 2){
			// user
			$this->load->view('Desk/Booking/index-user', 
				array('menumaster'=> $menu, 
					 'modules'=>$modules,
					'pagename' => $pagename, 
					// 'invoice' => json_encode($invoicename), 
					'employee'=> $employee['data'], 'settinggeneral'=> json_encode($settinggeneral)
				)
			);
		}
		
	}
	
	public function formatDate($string)
	{
		$nM = array('','Jan','Feb','Mar', 'Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$d = explode("-", $string);
		$y = $d[0];
		$m = $d[1]-0;
		$day = $d[2];
		return $day . " ". $nM[$m] . " ".$y;
	}
	public function formatTime($string)
	{

		$nM = array(
			'00'=> '00',
		    '01'=> '01',
		    '02'=> '02',
		    '03'=> '03',
		    '04'=> '04',
		    '05'=> '05',
		    '06'=> '06',
		    '07'=> '07',
		    '08'=> '08',
		    '09'=> '09',
		    '10'=> '10',
		    '11'=> '11',
		    '12'=> '12',
		    '13'=> '01',
		    '14'=> '02',
		    '15'=> '03',
		    '16'=> '04',
		    '17'=> '05',
		    '18'=> '06',
		    '19'=> '07',
		    '20'=> '08',
		    '21'=> '09',
		    '22'=> '10',
		    '23'=> '11',
		    '24'=> '12',
		);
		$d = explode(":", $string);
		$h = $d[0];
		$m = $d[1];
		$s = $d[2];
		$formatH = ( ($m-0) > 12 ) ? "PM":"AM";
		return $nM[$h] . ":". $m . " ".$formatH;
	}
	public function getInvoiceStatusZZZZ()
	{
		$data = $this->Model_Admin->getInvStatusName();
		return $data['data'];
		
	}
	public function checkUserPic()
	{
		$nik = $this->session->userdata('user-nya');
		$q = "SELECT am.*, a.name, a.type, a.invoice_status FROM alocation_matrix am ";
		$q .= "INNER JOIN alocation a ON am.alocation_id=a.id ";
		$q .= "WHERE nik='".$nik."' AND a.is_deleted=0";
		$queryTime 		= $this->Model_Admin->querySql($q);
		$data 	= $queryTime->result_array();
		echo response("success", $data, "Get success");
	}
	public function getTodayBooking()
	{
		if($this->session->userdata('levelid-nya') == 1){
			$this->load->view('Desk/Booking/todayBooking');
			
		}else if($this->session->userdata('levelid-nya') == 2){
			// user
			$this->load->view('Desk/Booking/todayBooking');
		}
	}
	public function getTodayBookingPage2()
	{
		if($this->session->userdata('levelid-nya') == 1){

			$this->load->view('Desk/Booking/todayBooking_page2');
			
		}else if($this->session->userdata('levelid-nya') == 2){
			// user
			$this->load->view('Desk/Booking/todayBooking_page2user');
		}
	}
	public function getTodayBookingPage2Data()
	{
		if($this->session->userdata('levelid-nya') == 1){

			$post = $_POST;
			$room_id = $post['room_id'];
			$wzone 		= [
				'rz.desk_room_id' => $room_id,
			];
			$dataZone 		= $this->Model_Deskbooking->getRoomZone($wzone)['data'];
			$date = $this->input->post('date');
			if ($date == 'today' || empty($date)) {
				$date = date("Y-m-d");
			}
			$bookingQuery = $this->db->query("SELECT desk_id, start, end FROM desk_booking WHERE room_id = '$room_id' AND date = '$date' AND is_alive = 1")->result_array();
			$bookingsByDesk = [];
			foreach($bookingQuery as $b) {
				$bookingsByDesk[$b['desk_id']][] = $b;
			}
			
			foreach ($dataZone  as $k => $v) {
				$zone_id = $v['zone_id'];
				$wdesk 		= [
					'rt.zone_id' => $zone_id,
					'd.desk_room_id' => $room_id,
				];
				$dataDesk 		= $this->Model_Deskbooking->getDataRoomDeskTable($wdesk)['data'];
				foreach ($dataDesk as $dk => $dv) {
					$dataDesk[$dk]['today_bookings'] = isset($bookingsByDesk[$dv['desk_id']]) ? $bookingsByDesk[$dv['desk_id']] : [];
				}
				$dataZone[$k]['desk'] = $dataDesk ;
			}
			echo response("success", $dataZone, "Get success");
		}else if($this->session->userdata('levelid-nya') == 2){
			// user
			$post = $_POST;
			$room_id = $post['room_id'];
			$wzone 		= [
				'rz.desk_room_id' => $room_id,
			];
			$dataZone 		= $this->Model_Deskbooking->getRoomZone($wzone)['data'];
			$date = $this->input->post('date');
			if ($date == 'today' || empty($date)) {
				$date = date("Y-m-d");
			}
			$bookingQuery = $this->db->query("SELECT desk_id, start, end FROM desk_booking WHERE room_id = '$room_id' AND date = '$date' AND is_alive = 1")->result_array();
			$bookingsByDesk = [];
			foreach($bookingQuery as $b) {
				$bookingsByDesk[$b['desk_id']][] = $b;
			}
			
			foreach ($dataZone  as $k => $v) {
				$zone_id = $v['zone_id'];
				$wdesk 		= [
					'rt.zone_id' => $zone_id,
					'd.desk_room_id' => $room_id,
				];
				$dataDesk 		= $this->Model_Deskbooking->getDataRoomDeskTable($wdesk)['data'];
				foreach ($dataDesk as $dk => $dv) {
					$dataDesk[$dk]['today_bookings'] = isset($bookingsByDesk[$dv['desk_id']]) ? $bookingsByDesk[$dv['desk_id']] : [];
				}
				$dataZone[$k]['desk'] = $dataDesk 	;
			}
			echo response("success", $dataZone, "Get success");
		}
	}

	public function getPickDateBooking()
	{
		$this->load->view('Admin/Booking/index', array('menumaster'=> $menu, 'pagename' => $pagename, 'employee'=> $employee['data']));
	}
	public function getAlocation()
	{
		$nik = $this->uri->segment(5);
		$dataAlocation 		= $this->Model_Admin->getBookingAlocationPIC($nik);
		if($dataAlocation['error'] == null){
			echo response("success", $dataAlocation['data'], "Get success");
		}else{
			echo response("fail", $dataAlocation, "Get failed");
		}

	}
	private function getStatusInvoiceName()
	{
		$data 		= $this->Model_Admin->getInvStatusName();
		if($data['error'] == null){
			return  $data['data'];
		}else{
			return  array();
		}
	}
	public function checkTodayBooking()
	{
		$date 			= date("Y-m-d");
		$sst 			= date("H:i:s");
		$meetingRoom 	= array();
		$timearray 		= array();

		$dayNameNum = date("w", strtotime($date));
		$dayName = getDayName($dayNameNum);
		$whreRoomString = "  r.work_day LIKE '%".$dayName."%' ";
		$dataRoom 		= $this->Model_Deskbooking->getDataRoomDeskBooking($whreRoomString)['data'];
		
		$settingGeneral	= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		// $numLoop		= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration	= $dataSettingGeneral['config_book_duration']; // 30 or 60
		$stTime 		= $date." 00:00:00";
		$timess 		= strtotime($stTime);

		$timearray	= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		$timearray = $timearray['data'];


		$lenTime = count($timearray)-1;
		echo response("success", $dataRoom, "Get success");
		die();
		
	}
	public function checkPickerBooking()
	{

		$selectDate = $this->uri->segment(5);
		$date 			= $selectDate;
		$sst 			= date("H:i:s");
		$meetingRoom 	= array();
		$timearray 		= array();

		$dayNameNum = date("w", strtotime($date));
		$dayName = getDayName($dayNameNum);
		$whreRoomString = "  r.work_day LIKE '%".$dayName."%' ";
		$dataRoom 		= $this->Model_Deskbooking->getDataRoomDeskBooking($whreRoomString)['data'];
		// print_r($dayName);
		// die();
		// $sqlroom 		= "SELECT * FROM room  WHERE is_deleted=0";
		// $queryRoom 		= $this->Model_Admin->querySql($sqlroom);
		// $dataRoom 		= $queryRoom->result_array();
		$settingGeneral	= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		// $numLoop		= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration	= $dataSettingGeneral['config_book_duration']; // 30 or 60
		// $setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$stTime 		= $date." 00:00:00";
		$timess 		= strtotime($stTime);

		$timearray	= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		$timearray = $timearray['data'];


		$lenTime = count($timearray)-1;
		echo response("success", $dataRoom, "Get success");
		die();
		
	}
	public function checkPickerBookingWithRoom()
	{
		$booking_id = $this->uri->segment(5);
		$selectDate = $this->uri->segment(6);
		$room_id = $this->uri->segment(7);
		$date 			= $selectDate;
		$meetingRoom 	= array();
		$timearray 		= array();
		$dataBooking 	= $this->Model_Deskbooking->getDataBookingById($booking_id)['data'];
		$dataRoom 		= $this->Model_Deskbooking->getRoomRadid($room_id)['data'];
		$settingGeneral	= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];

		$numLoop		= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$stTime 		= $date." 00:00:00";

		if($dataBooking['title'] == null){
			echo response("fail", array(), "Data Desk Booking not exist");
			die();
		}
		if(count($dataRoom) <= 0){
			echo response("fail", array(), "Data Room not exist");
			die();
		}

		$room_id = $dataBooking['room_id'];
		$desk_id = $dataBooking['desk_id'];

		$wdesk 	= [
			'rt.desk_id' => $desk_id,
			'd.desk_room_id' => $room_id,
		];

		$wzone 		= [
			'rz.desk_room_id' => $room_id,
		];
		$dataZone 		= $this->Model_Deskbooking->getRoomZone($wzone)['data'];
		foreach ($dataZone  as $k => $v) {
			$zone_id = $v['zone_id'];
			$wdesk 		= [
				'rt.zone_id' => $zone_id,
				'd.desk_room_id' => $room_id,
			];
			$dataDesk 		= $this->Model_Deskbooking->getDataRoomDeskTable($wdesk)['data'];
			$dataZone[$k]['desk'] = $dataDesk ;
		}
		$dataRoomDesk 		= $this->Model_Deskbooking->getDataRoomDeskTable($wdesk)['data'];
		$datares = [
			'zone' => $dataZone,
			'room' => $dataRoom[0],
		];
		// echo "<pre>";	
		// print_r($dataZone);
		// print_r($dataRoomDesk);
		echo response("success", $datares, "Get success");
	}
	public function getRescheduleDeskBookTime()
	{
		// echo "<pre>";
		$post = $_POST;
		$date = @$post['date'];
		$zone = $post['zone'];
		$room = $post['room'];
		$desk = $post['desk'];
		$pick = $post['pick'];
		$time = $post['time'];
		$booking_id = $post['booking_id'];

		$zone_id = $post['zone'];
		$room_id = $post['room'];
		$wdesk 	= [
			'rt.zone_id' => $zone,
			'rt.desk_id' => $desk,
			'd.desk_room_id' => $room,
		];
		$dataDesk 			= $this->Model_Deskbooking->getDataRoomDeskTable($wdesk)['data'];
		$settingGeneral		= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		$numLoop			= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration		= $dataSettingGeneral['duration']; // 30 or 60
		$timearray			= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		$timearray = $timearray['data'];
		$lenTime = count($timearray)-1;

		$sql 		= "";
		$sql 		.= "SELECT * FROM ( ";
		foreach ($timearray as $key => $value) {
				$timeData = $date." ".$value['time'] .":00";

				if ($lenTime == $key) {
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM desk_booking b 
						LEFT JOIN desk_room r ON b.room_id=r.id  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 
						 AND  b.booking_id<>'".$booking_id."' 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))";
				}else{
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM desk_booking b 
						LEFT JOIN desk_room r ON b.room_id=r.id  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 
						 AND  b.booking_id<>'".$booking_id."' 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))";
					$sql .= " UNION ";
				}
		}
		$sql 			.= ") room_time";
			
		$queryTime 		= $this->Model_Api->querySql($sql);
		$dataTimeArray 	= $queryTime->result_array();
		foreach ($dataTimeArray as $key => $value) {
			if($value['canceled'] >=1 || $value['expired'] >=1 || $value['endearly'] >=1 ){
				$dataTimeArray[$key]['book'] = 0;
			}
			if($dataTimeArray[$key]['book'] >= 1){
				$dataTimeArray[$key]['book'] = 1;
			}
			unset($dataTimeArray[$key]['canceled']);
			unset($dataTimeArray[$key]['expired']);
			unset($dataTimeArray[$key]['endearly']);
		}
		if($pick == "today"){
			foreach ($dataTimeArray as $key => $value) {
				$nowtime = strtotime($date . " ".$time);
				$bookingtime = strtotime($date . " " . $value['time_array']);
				if( $nowtime  > $bookingtime){
					$dataTimeArray[$key]['book'] = 1;
				}
			}
		}
		
		echo response("success", $dataTimeArray, "Get Success ");
	}
	public function checkDateRangeFromExistMeeting($collect, $datatime){
		$datetime = strtotime($datatime);
		$return = false;
		foreach ($collect as $key => $value) {
			$starttime = strtotime($value['start']);
			$endtime = strtotime($value['end']);
			// echo "datatime ".$datatime." ".$value['start']." ". $value['end'] . " <br/> ";

			if($starttime <= $datetime && $endtime >= $datetime ){
				$return = true;
				break;
			}
			# code...
		}
		return $return;
	}

	public function getData()
	{
		// $start = $this->uri->segment(5);
		// $end = $this->uri->segment(7);
		$startDate = @$_GET['startDate'];
	    $endDate = @$_GET['endDate'];
	    $status = @$_GET['status'] == "" ? "all" : $_GET['status'];
		
		$start = $startDate;
		$end = $endDate;
		$where = ['status' => $status];
		if($status == "all"){
			$where = [];
		}else if($status == "reschedule"){
			$where = ['is_rescheduled' => 1, 'status' => "soon"];
		}
		if($this->session->userdata('levelid-nya') == 1){ // admin
			$data = $this->Model_Deskbooking->getDataBooking($start,$end, $where);
			if($data['error'] == null){
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
		}else if($this->session->userdata('levelid-nya') == 2){ // employee
			$nik = $this->session->userdata('user-nya');
			$data = $this->Model_Deskbooking->getDataBookingByNik($start,$end,$nik, $where);
			if($data['error'] == null){
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
		}else{
			echo response("fail", array(), "You don't have any access");
		}
		
	}
	public function getDataOther()
	{

		$start = $this->uri->segment(6);
		$end = $this->uri->segment(8);
		$nik = $this->session->userdata('user-nya');
		$data = $this->Model_Admin->getDataBookingByOther($start,$end,$nik);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
		
		
	}
	public function getDataPartisipant()
	{
		$post = $_POST;
		$data = $this->Model_Admin->getDataBookingPartisipant($post);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getDeskBookTime()
	{
		$post = $_POST;
		$date = @$post['date'];
		$zone = $post['zone'];
		$room = $post['room'];
		$desk = $post['desk'];
		$pick = $post['pick'];
		$time = $post['time'];

		$zone_id = $post['zone'];
		$room_id = $post['room'];
		$wdesk 	= [
			'rt.zone_id' => $zone,
			'rt.desk_id' => $desk,
			'd.desk_room_id' => $room,
		];
		$dataDesk 			= $this->Model_Deskbooking->getDataRoomDeskTable($wdesk)['data'];
		$settingGeneral		= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		$numLoop			= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration		= $dataSettingGeneral['duration']; // 30 or 60
		$timearray			= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		$timearray = $timearray['data'];
		$lenTime = count($timearray)-1;

		$sql 		= "";
		$sql 		.= "SELECT * FROM ( ";
		foreach ($timearray as $key => $value) {
				$timeData = $date." ".$value['time'] .":00";

				if ($lenTime == $key) {
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM desk_booking b 
						LEFT JOIN desk_room r ON b.room_id=r.id  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."' AND b.desk_id='".$desk."' AND b.is_alive = 1 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL COALESCE(b.extended_duration,0)-1 MINUTE))";
				}else{
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM desk_booking b 
						LEFT JOIN desk_room r ON b.room_id=r.id  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."' AND b.desk_id='".$desk."' AND b.is_alive = 1 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL COALESCE(b.extended_duration,0)-1 MINUTE))";
					$sql .= " UNION ";
				}
		}
		$sql 			.= ") room_time";
			
		$queryTime 		= $this->Model_Api->querySql($sql);
		$dataTimeArray 	= $queryTime->result_array();
		foreach ($dataTimeArray as $key => $value) {
			if($value['canceled'] >=1 || $value['expired'] >=1 || $value['endearly'] >=1 ){
				$dataTimeArray[$key]['book'] = 0;
			}
			if($dataTimeArray[$key]['book'] >= 1){
				$dataTimeArray[$key]['book'] = 1;
			}
			unset($dataTimeArray[$key]['canceled']);
			unset($dataTimeArray[$key]['expired']);
			unset($dataTimeArray[$key]['endearly']);
		}
		if($pick == "today"){
			foreach ($dataTimeArray as $key => $value) {
				$nowtime = strtotime($date . " ".$time);
				$bookingtime = strtotime($date . " " . $value['time_array']);
				if( $nowtime  > $bookingtime){
					$dataTimeArray[$key]['book'] = 1;
				}
			}
		}
		
		echo response("success", $dataTimeArray, "Get Success ");
	}
	public function checkDataTime()
	{
		// echo "<pre>";
		$post = $_POST;
		$date = @$post['date'];
		$start = $date." ".$post['start'].":00";
		$end = $date." ".$post['end'].":00";
		// $sql = "CALL check_booking(?,?,?)";
		$meetingRoom = array();
		$dataRoom = $this->Model_Admin->getDataRoom();
		if($dataRoom['error'] == null){
			foreach ($dataRoom['data'] as $key => $value) {
				$sql = "
					SELECT title, date, start, end 
					FROM booking
				   	WHERE date='$date' AND is_deleted=0 AND is_expired=0 AND is_canceled=0
				    AND start BETWEEN TIME('$start') AND TIME('$end')
				    OR end BETWEEN TIME('$start') AND TIME('$end')
				";
				$dataX = $this->Model_Admin->checkBookingRoom($date, $start, $end, $value['id']);
				if($dataX['error'] == null){
					$ar = array(
						"room_id" => $value['id'],
						"count" => count($dataX['data'] ),
						"data_room" => $value,
					);
					array_push($meetingRoom, $ar);
				}else{
					echo response("fail", array(), "Time to meeting ");
					exit();
					die();
				}
			}
			echo response("success", $meetingRoom, "Get Success ");
		}else{
			echo response("fail", array(), "Time to meeting ");
		}
		// if($c
	}
	public function checkTimePick()
	{
		// echo "<pre>";
		$post = $_POST;
		$date = $post['date'];
		$timestart = $post['timestart'];
		$timeend = $post['timeend'];
		$room = $post['room'];
		$start = $timestart['time_array'];
		$end = $timeend['time_array'];
		$room_id = $room['id'];
		if($date == "today"){
			$date = date("Y-m-d");
		}
		$ar = array(
			"b.room_id" => $room_id,
			"b.date" => $date,
			"b.is_deleted" => 0,
		);
		$datastart = $this->db->select('b.*, r.name as room_name, price')
			->from("booking b")
			->join("room r", "b.room_id=r.radid" , 'left')
			->where($ar)
			->where("  TIME('".$start."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE)) ")
			->get();
		$resultstart = $datastart->result_array();

		$dataend = $this->db->select('b.*, r.name as room_name, price')
			->from("booking b")
			->join("room r", "b.room_id=r.radid" , 'left')
			->where($ar)
			->where("  TIME('".$end."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE)) ")
			->get();
		$resultend = $dataend->result_array();
		if(count($resultstart) == 0 && count($resultend ) == 0 ){
			echo response("success", array(), "schedule has not been used");
		}else{
			echo response("fail", array(), "Schedule has been used, please reload page ");
		}
	}
	public function getExtendTime()
	{
		$post = $_GET;
		$rawconfig = $this->Model_Api->getGeneralSetting();
		$config =$rawconfig ['data'];
		$date = $post ['date'];
		if(!isset($post ['time'])){
			$time =date("H:i:s");
		}else{
			$time =$post ['time'];
		}
		$getBooking 		= $this->Model_Deskbooking->getDataBookingById($post['booking_id']);
		$dataBooking 		= $getBooking['data'];
		$room_id 					= $dataBooking['room_id'];
		// $zone_id 					= $post['zone'];
		$desk_id 					= $dataBooking['desk_id'];

		$Q_room						= "SELECT * FROM desk_room WHERE id=".$room_id." ";
		$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		$wdesk 		= [
			// 'rt.zone_id' => $zone_id,
			'd.desk_room_id' => $room_id,
			'rt.desk_id' => $desk_id 
		];
		$dataDesk 					= $this->Model_Deskbooking->getDataRoomDeskTable($wdesk)['data'];
		$pieceTime = $config['extend_count_time'];
		if($config['extend_meeting'] == 1){
			$rawbooking = $this->Model_Deskbooking->getBookingInfo($post['booking_id']);
			$booking = $rawbooking['data'];
			$work_end = $booking['work_end'];
			$extend = $booking['extended_duration']-0;
			// print_r($booking);
			$max = $config['extend_meeting_max']-0;
			$collectcheck = array();
			// print_r($pieceTime . " ".$max );

			if($max >= $pieceTime){
				$count = $max/$pieceTime;
				$end = $booking['end'];
				$end_with_extend = date('Y-m-d H:i:s',strtotime($end . "+".$extend." minutes"));
				$select = "SELECT * FROM ( ";
				for($x = $pieceTime; $x <= $max; $x+=$pieceTime){
					$timeroom = $date ." ".$work_end;
					$checknow 	= $date ." ".$time ;
					$convertTime = strtotime($end_with_extend);
					$aftersumnow = date('Y-m-d H:i:s', strtotime("+".$x." minutes",$convertTime));
					if(strtotime($aftersumnow) > strtotime($timeroom)){
						array_push($collectcheck, $x); //
					}
					$endtimeExtent = date('Y-m-d H:i:s',strtotime($end_with_extend . "+".$x." minutes"));
					if($max == $x){
						$select .= "SELECT CONCAT('".$x ."') as duration, COUNT(*) as book, TIME('".$endtimeExtent."') as time_data FROM desk_booking 
						WHERE end_early_meeting=0 
						AND is_alive = 1 
						AND room_id='".$dataBooking['room_id']."' 
						AND desk_id='".$dataBooking['desk_id']."' 

						AND date='".$date."' 
						AND TIME('".$endtimeExtent ."') 
						BETWEEN TIME(start) AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) ";
					}else{
						$select .= "SELECT CONCAT('".$x ."') as duration, COUNT(*) as book, TIME('".$endtimeExtent."') as time_data FROM desk_booking 
						WHERE end_early_meeting=0 
						AND is_alive = 1 
						AND room_id='".$dataBooking['room_id']."' 
						AND desk_id='".$dataBooking['desk_id']."' 

						AND date='".$date."' 
						AND TIME('".$endtimeExtent ."') 
						BETWEEN TIME(start) AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) union ";
					}
				}
				$select 	.= " ) datatime ";
				$queryTime 	= $this->Model_Api->querySql($select);
				$datax 		= $queryTime->result_array();
				$lendata = count($datax);
				foreach ($datax as $key => $value) {
					if ( count($collectcheck) >0 ) {
						if (array_search($value['duration'],$collectcheck) >=0) {
							$datax[$key]['book'] = "1";
							# code...
						}
						
					}
				}
				foreach ($datax as $key => $value) {
					if($value['book'] == "1" ){
						unset($datax[$key]);
					}
				}
				$lendata = count($datax);
				if($lendata > 0){
					$response = response("success", $datax , "Extend time available");
					echo $response ;
				}else{
					$response = response("fail", array() , "Extend time not available.");
					echo $response ;
				}
			}else{
				$response = response("fail", array(), "Extend time not available.");
				echo $response ;
			}
			
		}else{
			$response = response("fail", array(), "Extend Time feature is disabled, please enable for use this feature.");
			echo $response ;
		}
	}

	public function setExtendBooking()
	{
		try{
			$datetime = date("Y-m-d H:i:s");
			$ddd = $datetime;
			$post = $_POST;
			$getBooking = $this->Model_Deskbooking->getDataBookingById($post['booking_id']);
			$ex = $post['extend']-0;
			$duration = $ex;
			$dataBooking = $getBooking['data'];
			$getBookingInv 		= $this->Model_Deskbooking->getDataBookingInvById($post['booking_id'])['data'];
			$room_name 			= $dataBooking['room_name'];
			$extended_duration = $dataBooking['extended_duration'];
			$settingGeneral				= $this->Model_Api->getSettingDataGeneral();
			$dataSettingGeneral 		= $settingGeneral['data'];
			$fHour 						= $dataSettingGeneral['duration'];
			$cost						= $dataBooking['price']; // per hours
			$allduration 				= $extended_duration + $duration + $ex;
			$getHoursMeeting 			= floor($allduration / $fHour);
			$checkHours 				= fmod($allduration,$fHour);
			if($checkHours > 0){
				$getHoursMeeting += 1;
			}
			$reservation_cost 			= $cost	* $getHoursMeeting;
			$sql = "UPDATE desk_booking SET 
				extended_duration=extended_duration+".$ex.",
				cost_total_booking=".$reservation_cost." 
				WHERE booking_id='". $post['booking_id']."' ";
			// $booking_invoice = array(
			// 	"rent_cost" => $reservation_cost,
			// 	"alocation" => $dataBooking ['alocation_id'],
			// 	"time_before" => $datetime,
			// 	"updated_at" 	=> $datetime,
			// );
			// $winvoice 	= array("booking_id" => $dataBooking['booking_id']);
			$resp3 = $this->Model_Api->querySql($sql);
			// $udata 	= $this->Model_Api->updateData("booking_invoice", $booking_invoice, $winvoice);
			$notifcollectdata 	= array();
			foreach ($getBookingInv as $val) {
				if($val['internal'] == 1){
					// only internal
					$_notif 					= array();
					$_notif['datetime'] 		= $datetime;
					$_notif['nik'] 				= $val['nik']; // user id
					$_notif['type'] 			= 1; // booking is 1
					$_notif['value'] 			= $post['booking_id']; // booking id
					$_notif['title'] 			= "Extend desk booking";
					$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
					$_notif['is_sending'] 		= 0;
					$_notif['is_deleted'] 		= 0;
					$_notif['created_at'] 		= $datetime;
					if($val['is_pic'] == 1){
						$_notif['title'] 			= "Extend desk booking";
						$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
					}
					array_push($notifcollectdata, $_notif);
				}
			}
			if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title 		= $dataBooking['title'];
				$meeting_date 		= $dataBooking['date'];
				$explodeS 			= explode(" ", $dataBooking['start']);
				$explodeE 			= explode(" ", $dataBooking['end']);
				$meeting_start 		= $explodeS[1];
				$meeting_end 		= $explodeE[1];
				$notification_title = "Extend desk booking - ".$meeting_title ;
				// $room_name 			= $room_name;
				$notification_body 	= $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end). " at " .$room_name;
				// $pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
			}
			$response = response("success", $notifcollectdata, "");
			echo $response;
		}catch(Exeption $error){
			$response = response("fail", array(), "The process of extend time failed");
			echo $response;
		}
		
	}
	public function postEndMeeting()
	{
		$post 				= $_POST;
		$datetime 			= date("Y-m-d H:i:s");
		$getBooking 		= $this->Model_Deskbooking->getDataBookingById($post['booking_id']);
		$dataBooking 		= $getBooking['data'];
		$ddd 				= $dataBooking['date'];
		$getBookingInv 		= $this->Model_Deskbooking->getDataBookingInvById($post['booking_id'])['data'];
		$notifcollectdata 	= array();
		$room_name 			= $dataBooking['room_name'];
		$isUser 			= isset($post['user']) ? $post['user'] : false;
		$nameUser 			= $this->session->userdata('name-nya');
		foreach ($getBookingInv as $val) {
			if($val['internal'] == 1){
				// only internal
				$_notif 					= array();
				$_notif['datetime'] 		= $datetime;
				$_notif['nik'] 				= $val['nik']; // user id
				$_notif['type'] 			= 1; // booking is 1
				$_notif['value'] 			= $post['booking_id']; // booking id
				$_notif['title'] 			= "End desk booking";
				$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				$_notif['is_sending'] 		= 0;
				$_notif['is_deleted'] 		= 0;
				$_notif['created_at'] 		= $datetime;
				if($val['is_pic'] == 1){
					$_notif['title'] 			= "End desk booking";
					$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				}
				array_push($notifcollectdata, $_notif);
			}
			
		}
		if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title 		= $dataBooking['title'];
				$meeting_date 		= $dataBooking['date'];
				$explodeS 			= explode(" ", $dataBooking['start']);
				$explodeE 			= explode(" ", $dataBooking['end']);
				$meeting_start 		= $explodeS[1];
				$meeting_end 		= $explodeE[1];
				$notification_title = "End desk booking - ".$meeting_title;
				// $room_name 			= $room_name;
				$notification_body 	= $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end). " at " .$room_name;
				// $pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
		}

		$datarow = array(
			"end_early_meeting" => 1,
			"updated_at" 		=> $datetime,
			"updated_by" 		=> $this->session->userdata('user-nya'),
			"is_alive" 			=> 4,
			"early_ended_by" 	=> $this->session->userdata('user-nya'),
			"early_ended_at" 	=> $datetime ,
			"text_early" 		=> "By Web",
			"status" 			=> "expired",
		);
		if ($isUser) {
			$datarow['text_early'] = "".$nameUser;
		}

		$whereAr = array(
			"booking_id" => $post['booking_id']
		);
		$getData = $this->Model_Admin->updateData('desk_booking',$datarow, $whereAr);

		record_activity('BOOKING_CHECKOUT', [
			'description' => "Booking ended early",
			'booking_id' => $post['booking_id'],
			'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
			'room_id' => $dataBooking['room_id'],
			'desk_id' => $dataBooking['desk_id'],
			'severity' => 'info'
		]);

		if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title 		= $dataBooking['title'];
				$meeting_date 		= $dataBooking['date'];
				$explodeS 			= explode(" ", $dataBooking['start']);
				$explodeE 			= explode(" ", $dataBooking['end']);
				$meeting_start 		= $explodeS[1];
				$meeting_end 		= $explodeE[1];
				$notification_title = "End desk booking - ".$meeting_title;
				// $room_name 			= $room_name;
				$notification_body 	= $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end). " at " .$room_name;
				// $pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
		}

		$response = response("success", array(), "Success get data to end desk booking ");
		echo $response ;	
	}
	public function postCancelBook()
	{
		$post =$_POST;
		// echo "<pre>";
		$getBooking 		= $this->Model_Deskbooking->getDataBookingById($post['booking_id']);
		$getBookingInv 		= $this->Model_Deskbooking->getDataBookingInvById($post['booking_id'])['data'];
		$sqlEmail 			= "SELECT * FROM sending_email WHERE booking_id='".$post['booking_id']."' ";
		$dataBooking = $getBooking['data'];
		$notifcollectdata = array();
		$ddd = $dataBooking['date'];
		// die();
		$datetime 					= date('Y-m-d H:i:s');
		$room_id 					= $dataBooking['room_id'];
		$Q_room						= " SELECT * FROM desk_room WHERE id=".$room_id." ";
		$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		
		$booking_id					= $post['booking_id'];
		$room_id 					= $dataBooking['room_id'];
		$desk_id 					= $dataBooking['desk_id'];
		$wdesk 		= [
			'd.desk_room_id' => $room_id,
			'rt.desk_id' => $desk_id ,
		];
		$dataDesk 					= $this->Model_Deskbooking->getDataRoomDeskTable($wdesk)['data'];
		$dataDeskItem 				= $dataDesk[0];
		$title_desk 				= "Book a Desk in ".$dataDeskItem['zone_name']." - Desk No." .$dataDeskItem['block_number'];
		$desk_name					= $dataDeskItem['zone_name']." - Desk No." .$dataDeskItem['block_number'];
		if(count($room) <= 0){
			$response = response("fail", array(), "Room not found ");
			echo $response;
			die();
		}
		
		$room 			 			= $room[0];
		$room_name = $dataBooking['room_name'];

		foreach ($getBookingInv as $val) {
			if($val['internal'] == 1){
				// only internal
				$_notif 					= array();
				$_notif['datetime'] 		= $datetime;
				$_notif['nik'] 				= $val['nik']; // user id
				$_notif['type'] 			= 1; // booking is 1
				$_notif['value'] 			= $post['booking_id']; // booking id
				$_notif['title'] 			= "Notification desk cancel " ;
				$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				$_notif['is_sending'] 		= 0;
				$_notif['is_deleted'] 		= 0;
				$_notif['created_at'] 		= $datetime;
				if($val['is_pic'] == 1){
					// pic
					$_notif['title'] 			= "Notification desk cancel ";
					$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				}
				array_push($notifcollectdata, $_notif);
			}
			
		}
		if($getBooking ['error'] == null){
			$datetime 		= date('Y-m-d H:i:s');
			$dataBooking = $getBooking['data'];
			$dataBooking['is_expired'] = 0;
			$dataBooking['is_rescheduled'] = 0;
			$dataBooking['is_canceled'] = 1;
			$dataBooking['is_alive'] = 2;
			$dataBooking['canceled_by'] = $this->session->userdata('user-nya');
			$dataBooking['canceled_at'] = $datetime;
			$dataBooking['updated_at'] = $datetime;
			$dataBooking['updated_by'] = $this->session->userdata('user-nya');
			$dataBooking['status'] ="cancel";

			$where = array(
				 "booking_id"=>$dataBooking['booking_id']
			);
			unset($dataBooking['id']);
			unset($dataBooking['room_name']);
			unset($dataBooking['room_name2']);
			unset($dataBooking['price']);
			unset($dataBooking['booking_id']);
			unset($dataBooking['booking_id']);
			unset($dataBooking['room_description']);
			unset($dataBooking['room_location']);
			unset($dataBooking['room_capacity']);
			unset($dataBooking['room_google_map']);
			unset($dataBooking['building_name']);
			unset($dataBooking['building_detail_address']);
			unset($dataBooking['building_google_map']);
			$udata 				= $this->Model_Admin->updateData("desk_booking", $dataBooking, $where);
			
			record_activity('BOOKING_CANCELLED', [
				'description' => "Booking cancelled",
				'booking_id' => $booking_id,
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'room_id' => $room_id,
				'desk_id' => $desk_id,
				'severity' => 'warning'
			]);

			
			$this->Model_Notif->insertNotifAdmin(12, "Cancel desk booking", $dataBooking['title']);
			if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title = $title_desk ;
				$meeting_date = $dataBooking['date'];
				$explodeS = explode(" ", $dataBooking['start']);
				$explodeE = explode(" ", $dataBooking['end']);
				$meeting_start = $explodeS[1];
				$meeting_end = $explodeE[1];
				$notification_title = "Notification cancel - ".$meeting_title;
				$room_name = $room_name;
				$notification_body = $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end). " at " .$room_name;
				$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
			}
			$tableEmail			= $this->Model_Admin->querySql($sqlEmail)->result_array();
			if(count($tableEmail) > 0){
				$tableEmail			= $tableEmail[0];
				$getBookingEmail        = $this->Model_Api->getDataBookingById($booking_id); //
                if($getBookingEmail ['error'] != null){
                    $response = response("fail", array(), "Data not exist ");
                    echo $response; 
                    die();
                }
                $batchemail     = $tableEmail['batch'];
                $dataToSend     = json_decode($batchemail,true);
                // inisil booking 
                $emailBooking = $getBookingEmail['data'];
                $emailBooking['format_time_start'] = $this->formatTime($meeting_start);
                $emailBooking['format_time_end'] = $this->formatTime($meeting_end);
                $emailBooking['format_date'] = $this->formatDate($meeting_date);
                foreach ($dataToSend['internal'] as $key => $people) {
                    // $pNotif = $this->Model_Notif->sendDeskEmailInternal("cancel", $emailBooking, $people);
                }   
                
			}
			$response 			= response("success", array(), "Success cancel desk booking ");
			echo $response;
		}else{
			echo response("fail", array(), "Data of desk booking not found");
		}
	}

	public function postReBook()
	{
		$post 						= $_POST;
		$getBooking 				= $this->Model_Deskbooking->getDataBookingById($post['booking_id']);
		$getBookingInv 				= $this->Model_Deskbooking->getDataBookingInvById($post['booking_id'])['data'];
		// $sqlNotif 				= "SELECT * FROM sending_notif WHERE booking_id='".$post['booking_id']."' ";
		$sqlEmail 					= "SELECT * FROM sending_email WHERE booking_id='".$post['booking_id']."' ";
		$settingGeneral				= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];
		$dataInvitation				= $getBookingInv;
		$datetime 					= date("Y-m-d H:i:s");
		$dataBooking 				= $getBooking['data'];
		// $isMerge 					= $dataBooking['is_merge'];
		$notifcollectdata 			= array();
		$ddd = $post['date'] == "" ? $dataBooking['date'] :  $post['date'];
		$room_id 					= $dataBooking['room_id'];
		$desk_id 					= $dataBooking['desk_id'];
		$wdesk 		= [
			// 'rt.zone_id' => $zone_id,
			'd.desk_room_id' => $room_id,
			'rt.desk_id' => $desk_id ,
		];
		$dataDesk 					= $this->Model_Deskbooking->getDataRoomDeskTable($wdesk)['data'];
		$dataDeskItem 				= $dataDesk[0];

		$title_desk 				= "Book a Desk in ".$dataDeskItem['zone_name']." - Desk No." .$dataDeskItem['block_number'];
		$desk_name					= $dataDeskItem['zone_name']." - Desk No." .$dataDeskItem['block_number'];
		$Q_room						= " SELECT * FROM desk_room WHERE id=".$room_id." ";
		$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		if(count($room) <= 0){
			$response = response("fail", array(), "Room not found ");
			echo $response;
			die();
		}
		$room 			 			= $room[0];
		foreach ($dataInvitation as $val) {
			if($val['internal'] == 1){
				// only internal
				$_notif 					= array();
				$_notif['datetime'] 		= $datetime;
				$_notif['nik'] 				= $val['nik']; // user id
				$_notif['type'] 			= 1; // booking is 1
				$_notif['value'] 			= $post['booking_id']; // booking id
				$_notif['title'] 			= "Notification desk reschedule";
				$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				$_notif['is_sending'] 		= 0;
				$_notif['is_deleted'] 		= 0;
				$_notif['created_at'] 		= $datetime;
				if($val['is_pic'] == 1){
					// pic 
					$_notif['title'] 			= "Notification desk reschedule";
					$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				}
				array_push($notifcollectdata, $_notif);
			}
		}
		if($getBooking ['error'] == null){

			$datetime 					= date('Y-m-d H:i:s');
			$dataBooking 				= $getBooking['data'];
			$dataBooking['date'] 		= $post['date'] == "" ? $dataBooking['date'] :  $post['date'];
			$dataBooking['start'] 		= $post['start'] == "" ? $dataBooking['start'] :  $post['start'];
			$dataBooking['end'] 		= $post['end'] == "" ? $dataBooking['end'] :  $post['end'];
			$startTime 					= strtotime($dataBooking['start']);
			$checkTime 					= strtotime($dataBooking['end']);
			$extended_duration 			= $dataBooking["extended_duration"];
			$duration 					= round(abs($checkTime - $startTime) / 60,2) + $extended_duration; // 
			$fHour 						= $dataSettingGeneral['duration'];
			// $duration					= $duration;
			$cost						= $dataBooking['price']; // per hours
			$allduration 				= $extended_duration+ $duration;
			$getHoursMeeting 			= floor($allduration / $fHour);
			$checkHours 				= fmod($allduration,$fHour);
			if($checkHours > 0){
				$getHoursMeeting += 1;
			}
			$booking_id 				= $dataBooking['booking_id'];
			$tanggal_meeting 			= $dataBooking['date'];
			$waktu_timestart 			= $dataBooking['start'];
			$waktu_timeend 				= $dataBooking['end'];
			$waktu_mulai 				= $waktu_timestart;
			$ruangan 					= $dataBooking['room_id'];
			$desk_id 					= $dataBooking['desk_id'];
			// Check
			$this->Model_Deskbooking->checkKondisiBookingPerRuanganRes($ruangan,$desk_id,$tanggal_meeting, $waktu_mulai,$waktu_timeend, $booking_id);
			// =====================================================
			$reservation_cost 			= $cost	* $getHoursMeeting;
			$dataBooking['duration_per_meeting'] = $fHour ;
			$dataBooking['cost_total_booking'] = $reservation_cost ;
			$dataBooking['total_duration'] = $duration;
			$dataBooking['is_expired'] = 0;
			$dataBooking['is_canceled'] = 0;
			$dataBooking['is_rescheduled'] = 1;
			$dataBooking['rescheduled_by'] = $this->session->userdata('user-nya');
			$dataBooking['rescheduled_at'] = $datetime;
			$dataBooking['updated_at'] = $datetime;
			$dataBooking['updated_by'] = $this->session->userdata('user-nya');
			$where = array(
				 "booking_id"=>$dataBooking['booking_id']
			);
			$winvoice 	= array("booking_id" => $dataBooking['booking_id']);
			$room_name = $dataBooking['room_name'];
			unset($dataBooking['price']);
			unset($dataBooking['booking_id']);
			unset($dataBooking['room_name']);
			unset($dataBooking['merge_room']);
			unset($dataBooking['is_merge']);
			unset($dataBooking['merge_room_id']);
			unset($dataBooking['merge_room_name']);
			unset($dataBooking['room_id']);
			unset($dataBooking['id']);
			unset($dataBooking['room_name2']);
			unset($dataBooking['room_description']);
			unset($dataBooking['room_location']);
			unset($dataBooking['room_capacity']);
			unset($dataBooking['room_google_map']);
			unset($dataBooking['building_name']);
			unset($dataBooking['building_detail_address']);
			unset($dataBooking['building_google_map']);
			// unset($dataBooking['room_google_map']);
			$udata 				= $this->Model_Admin->updateData("desk_booking", $dataBooking, $where);

			record_activity('BOOKING_RESCHEDULED', [
				'description' => "Booking rescheduled",
				'booking_id' => $booking_id,
				'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
				'room_id' => $room_id,
				'desk_id' => $desk_id,
				'severity' => 'info',
				'metadata' => [
					'startTime' => $dataBooking['start'],
					'endTime' => $dataBooking['end']
				]
			]);

			// $udata 				= $this->Model_Admin->updateData("desk_booking_invoice", $data_invoice, $winvoice);
			$this->Model_Notif->insertNotifAdmin(12, "Reschedule meeting", $dataBooking['title']);
			$meeting_date = $dataBooking['date'];
			$explodeS = explode(" ", $dataBooking['start']);
			$explodeE = explode(" ", $dataBooking['end']);
			$meeting_start = $explodeS[1];
			$meeting_end = $explodeE[1];
			if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title =$title_desk ;
				$notification_title = "Notification reschedule - ".$meeting_title;
				$room_name = $room['name'];
				$notification_body = $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end) . " at " .$room_name;
				$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
			}	
			$tableEmail			= $this->Model_Admin->querySql($sqlEmail)->result_array();
			if(count($tableEmail) > 0){
				$getBookingEmail 		= $this->Model_Deskbooking->getDataBookingById($booking_id); //
				if($getBookingEmail ['error'] != null){
					$response = response("fail", array(), "Data not exist ");
					echo $response;	
					die();
				}
				$tableEmail		= $tableEmail[0];
				$batchemail 	= $tableEmail['batch'];
				$dataToSend		= json_decode($batchemail,true);
				// inisil booking 
				$emailBooking = $getBookingEmail['data'];
		        $emailBooking['format_time_start'] = $this->formatTime($meeting_start);
		        $emailBooking['format_time_end'] = $this->formatTime($meeting_end);
		        $emailBooking['format_date'] = $this->formatDate($meeting_date);
		        foreach ($dataToSend['internal'] as $key => $people) {
	            	$pNotif = $this->Model_Notif->sendDeskEmailInternal("reschedule", $emailBooking, $people);
		        }   
			}
			$response = response("success", array(), "Success reschedule desk booking ");
			echo $response;
		}else{
			echo response("fail", array(), "Data of desk booking not found");
		}
	}
	public function postBook()
	{
		include APPPATH.'third_party/phpqrcode/qrlib.php';
		$tmpdir = "assets/qr/";

		$statusInvoice 				= $this->getStatusInvoiceName();
		$post 						= $_POST;
		$datetime 					= date('Y-m-d H:i:s');
		$randoom_id					= random_string('numeric', 10);
		$id 						= $randoom_id;
		$invoice_id 				= $randoom_id;
		$internal  					= isset($post['partisipant']) ? $post['partisipant'] : array();
		$res_alocation				= $this->Model_Admin->checkBookingAlocationData($post['alocation']);
		$settingGeneral				= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];
		$alocation 					= $res_alocation['data'];
		$room_id 					= $post['room']['id'];
		$zone_id 					= $post['zone'];
		$desk_id 					= $post['desk'];

		$Q_room						= "SELECT * FROM desk_room WHERE id=".$room_id." ";
		$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		$wdesk 		= [
			'rt.zone_id' => $zone_id,
			'd.desk_room_id' => $room_id,
			'rt.desk_id' => $desk_id ,
		];
		$dataDesk 					= $this->Model_Deskbooking->getDataRoomDeskTable($wdesk)['data'];
		if(count($dataDesk) <= 0){
			$response = response("fail", array(), "Desk not found ");
			echo $response;
			die();
		}
		$dataDeskItem 				= $dataDesk[0];

		$title_desk 				= "Book a Desk in ".$dataDeskItem['zone_name']." - No." .$dataDeskItem['block_number'];
		$desk_name					= $dataDeskItem['zone_name']." - No." .$dataDeskItem['block_number'];
		if(count($room) <= 0){
			$response = response("fail", array(), "Room not found ");
			echo $response;
			die();
		}
		$room 			 			= $room[0];
		$room_name 					= $room['name'];
		if(count($internal) > 0) {
			$rowInternal 			= $this->Model_Admin->getDataEmployeeWhereInNik($internal);
		}else{
			$rowInternal 			= array();
			$rowInternal['data'] 	= array();
		}

		$fHour 						= $dataSettingGeneral['duration'];
		$duration					= $post['duration'];
		$cost						= $post['room']['price']; // per hours
		$getHoursMeeting 			= floor($duration / $fHour);
		$checkHours 				= fmod($duration,$fHour);
		if($checkHours > 0){
			$getHoursMeeting += 1;
		}
		$reservation_cost 			= $cost	* $getHoursMeeting;
		$nikPic						= $post['pic'];
		
		$resPIC 					= $this->Model_Admin->getEditEmployee($nikPic);
		$getDataPIC 				= $resPIC['data'];
		$internalBatch 				= array();
		$eksternalBatch 			= array();
		$dataEmailInternal 			= $rowInternal['data'];
		$dataEmailEksternal 		= array();
		$dataEmailInternal_array	= array();
		
		$tanggal_meeting = $post['date'];
		$waktu_timestart = $post['timestart']['time_array'];
		$waktu_timeend = $post['timeend']['time_array'];
		$waktu_mulai = $post['date'].' '. $waktu_timestart;
		$waktu_akhir = $post['date'].' '. $waktu_timeend;
		$ruangan = $post['room']['id'];
		$this->Model_Deskbooking->checkKondisiBookingPerRuangan($ruangan, $zone_id, $tanggal_meeting, $waktu_mulai,$waktu_akhir);
		// =========================================================================
		$years 			= date('Y', strtotime($post['date'])); // get tahun from date
		$y_years 		= date('y', strtotime($post['date'])); // get tahun from date
		$months			= date('m', strtotime($post['date'])); // get tahun from date
		$days 			= date('d', strtotime($post['date'])); // get tahun from date
		$sql_invoice 	= "SELECT COALESCE(max(no_order), '') as no_order from booking
							WHERE YEAR(date) = '".$years."'";
		$alocationOrderID = $alocation ['id'] . "-E-Meeting";
		$resInvoice 	= $this->Model_Admin->querySql($sql_invoice);
		$rowInvoice 	= $resInvoice->row_array();
		if($rowInvoice['no_order'] == "" || $rowInvoice['no_order'] == null ){
			$newNoUrut			= sprintf("%03d", "1");
			$formatInvoice		= $newNoUrut . "/" . $alocationOrderID . "/" . $months ."/" .$y_years;
			$formatInvoice2		= $newNoUrut . "/" . $alocationOrderID . "/" . $months ."/" .$y_years;
		}else{
			$oldNoInv	 		= $rowInvoice['no_order'];
			$spOldInv			= explode("/", $oldNoInv);
			$noUrut				= ($spOldInv[0]-0) + 1;
			$newNoUrut			= sprintf("%03d", $noUrut); // returns 001
			$formatInvoice		= $newNoUrut . "/" . $alocationOrderID . "/" . $months ."/" .$y_years;
			$formatInvoice2		= $newNoUrut . "/" . $alocationOrderID . "/" . $months ."/" .$y_years;
		}
		// ===
		$data 						= array();  // DATA BOOKING
		$data['booking_id'] 		= $id;
		$data['no_order'] 			= $formatInvoice;
		$data['title'] 				= $title_desk;
		$data['desk_name'] 			= $desk_name;
		$data['room_id'] 			= $post['room']['id'];
		$data['desk_id'] 			= $desk_id;
		$data['date'] 				= $post['date'];
		$data['start'] 				= $post['date'] ." ". $post['timestart']['time_array'];
		$data['end'] 				= $post['date'] ." ". $post['timeend']['time_array'];
		$data['total_duration'] 	= $post['duration'];
		$data['duration_per_meeting'] = $fHour ;
		$data['cost_total_booking'] = $reservation_cost ;
		$data['alocation_id'] 		= $alocation ['id'];
		$data['alocation_name'] 	= $alocation ['name'];
		$data['pic'] 				= $getDataPIC['name'];
		$data['is_meal'] 			= 0;
		$data['is_alive'] 			= 1;
		$data['is_deleted'] 		= 0;
		$data['is_rescheduled'] 	= 0;
		$data['is_canceled']		= 0;
		$data['is_expired']			= 0;
		$data['is_device'] 			= 0;
		// $data['external_link'] 		= isset($post['external_link']) ? $post['external_link']: "";
		// $data['note'] 				= isset($post['note']) ? $post['note']: "";
		$data['room_name'] 			= $room_name;
		$data['created_at'] 		= $datetime;
		$data['created_at'] 		= $datetime;
		$data['created_by'] 		= $this->session->userdata('user-nya');
		$data['status'] 		= "soon";

		// // // $invitation_pic 
		$invitation_pic = array();
		$invitation_pic['booking_id'] 			= $id;
		$invitation_pic['nik'] 					= $getDataPIC['nik']; // employee id
		$invitation_pic['name'] 				= $getDataPIC['name'];
		$invitation_pic['internal'] 			= 1;
		$invitation_pic['attendance_status']	= 0;
		$invitation_pic['email'] 				= $getDataPIC['email']; 
		$invitation_pic['is_pic'] 				= 1;
		$invitation_pic['company'] 				= "";
		$invitation_pic['pin_room'] 			= random_string('numeric', 6);
		$invitation_pic['created_at'] 			= $datetime;
		$invitation_pic['created_by']			= $this->session->userdata('user-nya');
		$invitation_pic['is_deleted'] 			= 0;

		$qrnvitationPIC = $id."_".$invitation_pic['pin_room'];
		// QRcode::png($qrnvitationPIC,$tmpdir.$qrnvitationPIC.".png");

		
		
		// insert of PIC invitation
		$ipicemail['card_number'] 			= $getDataPIC['card_number']; // card number
		$ipicemail['nik'] 					= $getDataPIC['nik']; // user id
		$ipicemail['name'] 					= $getDataPIC['name'];
		$ipicemail['division_id']			= 0;
		$ipicemail['is_pic'] 				= 1;
		$ipicemail['email'] 				= $getDataPIC['email']; 
		$ipicemail['pin_room']				= $invitation_pic['pin_room'];
		array_push($dataEmailInternal, $ipicemail);
		
		$dataToSend['internal'] 			= $dataEmailInternal;
		$dataToSend['eksternal'] 			= $dataEmailEksternal;
		$batchSendingEmail 					= json_encode($dataToSend);
		$batchSendingNotif 					= json_encode($dataToSend['internal']);
		$sending_email 		= array(
			"batch" 		=> $batchSendingEmail,
			"type" 			=> 1,
			"booking_id" 	=> $id,
			"pending" 		=> 0,
			"is_status" 	=> 0, // direct email php
			// "is_status" 	=> 1,
			"error_sending" => 0,
			"success" 		=> 0,
			"created_at" 	=> $datetime,
			"updated_at" 	=> $datetime,
			"is_deleted" 	=> 0
		);
		$sending_notif		= array(
			"batch" 		=> $batchSendingNotif,
			"type" 			=> 1,
			"booking_id" 	=> $id,
			"is_status" 	=> 1,
			"pending" 		=> 0,
			"error_sending" => 0,
			"success" 		=> 0,
			"created_at" 	=> $datetime,
			"updated_at" 	=> $datetime,
			"is_deleted" 	=> 0
		);
		

		
		$respP 			= $this->Model_Admin->insertData('desk_booking_invitation', $invitation_pic);
		if(count($internalBatch) > 0){
			$resp1		= $this->Model_Admin->insertDataBatch('desk_booking_invitation', $internalBatch);
		}
		if(count($eksternalBatch) > 0){
			// $resp2 		= $this->Model_Admin->insertDataBatch('booking_invitation', $eksternalBatch);
		}
		
		$invStatus 				= "";
		$invName 				= "";
		$data_invoice = array(
			"invoice_no" => $invoice_id,
			"invoice_format" => $formatInvoice2,
			"booking_id" => $id, // bookingid
			"rent_cost" => $reservation_cost,
			"alocation" => $alocation ['id'],
			"time_before" => $datetime,
			"created_at" 	=> $datetime,
			"created_by" 	=> $this->session->userdata('user-nya'),
			"invoice_status" 	=> 0, // before send
		);

		// INSERT BOOKING
		$resp3 = $this->Model_Admin->insertData('desk_booking', $data);

		record_activity('BOOKING_CREATED', [
			'description' => "Booking created for Desk No." . $dataDeskItem['block_number'],
			'booking_id' => $id,
			'actor_nik' => $this->session->userdata('nik') ? $this->session->userdata('nik') : 'System',
			'owner_nik' => $getDataPIC['nik'],
			'room_id' => $post['room']['id'],
			'desk_id' => $desk_id,
			'severity' => 'success',
			'metadata' => [
				'startTime' => $data['start'],
				'endTime' => $data['end']
			]
		]);

		// $resp3 = $this->Model_Admin->insertData('booking', $data);

		// $resp3 = $this->Model_Admin->insertData('booking_invoice', $data_invoice);
		$respw = $this->Model_Admin->insertData('sending_email', $sending_email);
		$resp4 = $this->Model_Api->insertData('sending_notif', $sending_notif);

		$notifcollectdata = array();
		foreach ($dataEmailInternal as $val) {
			$_notif 					= array();
			$_notif['datetime'] 		=  $datetime;
			$_notif['nik'] 				= $val['nik']; // user id
			$_notif['type'] 			= 1; // booking is 1
			$_notif['value'] 			= $id; // booking id
			$_notif['title'] 			= "Notification desk schedule";
			$_notif['body'] 			= $title_desk." - ". getformatDate($post['date']);
			$_notif['is_sending'] 		= 0;
			$_notif['is_deleted'] 		= 0;
			$_notif['created_at'] 		= $datetime;
			array_push($notifcollectdata, $_notif);
		}
		$_notif = array();
		$_notif['datetime'] 		= $datetime;
		$_notif['nik'] 				= $getDataPIC['nik']; // user id
		$_notif['type'] 			= 1; // booking is 1
		$_notif['value'] 			= $id; // booking id
		$_notif['title'] 			= "Create a desk schedule";
		$_notif['body'] 			= $title_desk ." - ". getformatDate($post['date']);
		$_notif['is_sending'] 		= 0;
		$_notif['is_deleted'] 		= 0;
		$_notif['created_at'] 		= $datetime;
		$type_notif 				= 3; // notification_type 1=booking
		$notif_insert				= false; // notification_type 1=booking
		array_push($notifcollectdata, $_notif);
		$this->Model_Notif->insertNotifAdmin(12, "Create desk", $data['title']);
		$this->Model_Notif->insertNotifBatch($notifcollectdata);

		$meeting_title = $title_desk;
		$meeting_date = $post['date'];
		$meeting_start = $post['timestart']['time_array'];
		$meeting_end = $post['timeend']['time_array'];
		$notification_title = "Notification - ".$meeting_title;
		$notification_body = $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end) . " at " .$room_name;

		// $pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$dataToSend['internal'],$type_notif,$notif_insert );

		// SEND EMAIL TO AUDIENCE
		$booking_id = $id;
		$getBooking 		= $this->Model_Deskbooking->getDataBookingById($booking_id); //
		if($getBooking ['error'] != null){
			$response = response("fail", array(), "Data not exist ");
			echo $response;	
			die();
		}
		$dataBooking = $getBooking['data'];
		$emailBooking = $dataBooking;
		$emailBooking['format_time_start'] = $this->formatTime($waktu_timestart);
		$emailBooking['format_time_end'] = $this->formatTime($waktu_timeend);
		$emailBooking['format_date'] = $this->formatDate($tanggal_meeting);
		foreach ($dataToSend['internal'] as $key => $people) {
			// $pNotif = $this->Model_Notif->sendDeskEmailInternal("invitation", $emailBooking, $people);
		}	
		$response = response("success", array(), "Success create a desk booking ".$title_desk);
		echo $response;
	}
	
	public function getPICInformation(){
		$id = $this->uri->segment(5);
		$sql = "SELECT b.* , e.name, e.email, e.no_phone, e.no_ext  FROM booking b";
		$sql .= " JOIN booking_invitation bi ON b.booking_id=bi.booking_id ";
		$sql .= " JOIN employee e ON bi.nik=e.nik ";
		$sql .= " WHERE bi.is_pic=1 AND b.booking_id='$id' ";
		$resp3 = $this->Model_Admin->querySql($sql);
		$data = $resp3->row_array();
		$response = response("success", $data, "");
		echo $response;
	}
	public function postDelete(){
		$post 		= $_POST;
		$dataBooking = array(
			"is_deleted" => 1
		);
		$where 		= array(
			"booking_id" => $post['booking_id']
		);
		$udata 		= $this->Model_Admin->updateData("booking", $dataBooking, $where);
		$response 	= response("success", array(), "Success remove a booking ");
		echo $response;
	}
	public function postAttendanceMeeting(){
		$post 		= $_POST;
		$att = 0;
		$nik = $this->session->userdata('user-nya');
		if($post['status'] == "attend"){
			$att = 1;
		}
		$dataBooking = array(
			"attendance_status" => $att,
			"execute_attendance" => 1, 
			"attendance_reason" => $post['reason']
		);
		$where 		= array(
			"booking_id" => $post['booking_id'],
			"nik" => $nik
		);
		$udata 		= $this->Model_Admin->updateData("booking_invitation", $dataBooking, $where);
		$response 	= response("success", array(), "Success remove a booking ");
		echo $response;
	}

	public function exportExcell()
	{
		// $start = $this->uri->segment(5);
		// $end = $this->uri->segment(7);
		$startDate = @$_GET['startDate'];
	    $endDate = @$_GET['endDate'];
	    $status = @$_GET['status'] == "" ? "all" : $_GET['status'];
		
		$start = $startDate;
		$end = $endDate;
		$where = ['status' => $status];
		if($status == "all"){
			$where = [];
		}else if($status == "reschedule"){
			$where = ['is_rescheduled' => 1, 'status' => "soon"];
		}
		if($this->session->userdata('levelid-nya') == 1){ // admin
			$data = $this->Model_Deskbooking->getDataBooking($start,$end, $where);
			if($data['error'] == null){
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
		}else if($this->session->userdata('levelid-nya') == 2){ // employee
			$nik = $this->session->userdata('user-nya');
			$data = $this->Model_Deskbooking->getDataBookingByNik($start,$end,$nik, $where);
			if($data['error'] == null){
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
		}else{
			echo response("fail", array(), "You don't have any access");
		}
		
	}
	public function exportPdf()
	{
		// $start = $this->uri->segment(5);
		// $end = $this->uri->segment(7);
		$startDate = @$_GET['startDate'];
	    $endDate = @$_GET['endDate'];
	    $status = @$_GET['status'] == "" ? "all" : $_GET['status'];
		
		$start = $startDate;
		$end = $endDate;
		$where = ['status' => $status];
		if($status == "all"){
			$where = [];
		}else if($status == "reschedule"){
			$where = ['is_rescheduled' => 1, 'status' => "soon"];
		}
		if($this->session->userdata('levelid-nya') == 1){ // admin
			$data = $this->Model_Deskbooking->getDataBooking($start,$end, $where);
			$this->load->view('Desk/Export/pdf', 
				array(
					'data'=> $data,
					'status'=> strtoupper($status),
					'start'=> strtoupper($start),
					'end'=> strtoupper($end),
				)
			);
		}else if($this->session->userdata('levelid-nya') == 2){ // employee
			$nik = $this->session->userdata('user-nya');
			$data = $this->Model_Deskbooking->getDataBookingByNik($start,$end,$nik, $where);
			$this->load->view('Desk/Export/pdf', 
				array(
					'data'=> $data,
					'status'=> strtoupper($status),
					'start'=> strtoupper($start),
					'end'=> strtoupper($end),
				)
			);
			
		}else{
			echo response("fail", array(), "You don't have any access");
		}
		
	}
	
}

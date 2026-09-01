<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Booking extends CI_Controller {

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
		$this->load->model('Model_Menu');
		$this->load->model('Model_Booking');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Notif');
		$this->load->model('Model_Api');
		$this->load->model('Model_License');
		$this->load->model('Model_MeetingLimitation');
		
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
		$pagename = "Booking";
		$building               = $this->Model_Admin->getDataBuilding()['data'];
        $room                   = $this->Model_Admin->getDataRoom2()['data'];
        $getEmployee            = $this->Model_Admin->getDataRoom2()['data'];

		$module_pantry = $this->Model_Module->get_module_pantry();
		$module_automation = $this->Model_Module->get_module_automation();
		$module_price = $this->Model_Module->get_module_price();
		$module_int_365 = $this->Model_Module->get_module_int_365();
		$module_int_google = $this->Model_Module->get_module_int_google();
		$modules['pantry'] = $module_pantry;
		$modules['automation'] = $module_automation;
		$modules['price'] = $module_price;
		$modules['int_365'] = $module_int_365;
		$modules['int_google'] = $module_int_google;
		$modules['room_adv'] = $this->Model_Module->get_module_room_adv();
		$modules['vip'] = $this->Model_Module->get_module_vip();	
		$room_for_usage = $this->Model_Admin->select_all_data('room_for_usage', ["is_deleted" => 0], ["*"],"result");

		

		$facility  = $this->Model_Admin->getDataFacility();
		$employee  = $this->Model_Admin->getDataEmployee();
		$settinggeneral = $this->Model_Admin->getSettingDataGeneral()['data'];
		$invoicename= $this->getInvoiceStatusZZZZ();
		$menu = $this->Model_Menu->getMenu($pagename);

		if($this->session->userdata('levelid-nya') == 1){
			$this->load->view('Admin/Booking/index', 
				array('menumaster'=> $menu, 
					 'modules'=>$modules,
					'pagename' => $pagename, 
					'invoice' => json_encode($invoicename), 
					'category' => json_encode($room_for_usage), 
					'employee'=> $employee['data'], 
					'settinggeneral'=> json_encode($settinggeneral),
					'building'          => json_encode($building),
                	'room'              => json_encode($room),
                	'organizer' => json_encode($getEmployee),
                	'facility' => json_encode($facility['data']),
				)
			);
		}else if($this->session->userdata('levelid-nya') == 2){
			// user
			$this->load->view('Admin/Booking/index-user', 
				array('menumaster'=> $menu, 
					 'modules'=>$modules,
					'pagename' => $pagename, 
					'invoice' => json_encode($invoicename), 
					'category' => json_encode($room_for_usage), 
					'employee'=> $employee['data'], 'settinggeneral'=> json_encode($settinggeneral),
					'building'          => json_encode($building),
                	'room'              => json_encode($room),
                	'organizer' => json_encode($getEmployee['data']),
                	'facility' => json_encode($facility['data']),
				)
			);
		}
		
	}
	public function pushNotification($title, $body,$batch )
	{
		$datetime = date("Y-m-d H:i:s");
		$config_notif = $this->Model_Notif->get_config();
		// print_r($config_notif);
		$collect = array();
		foreach ($batch as $key => $value) {
			$config = array(
				'url' => $config_notif['url'],
				'authorization' => $config_notif['authorization'],
				'active' => $config_notif['active'],
			);
			$topic = 'mobile_notif_'.$value['nik'];
			$payload = $this->Model_Notif->fcmtopics($topic, $title, $body);
			$send_msg = $this->Model_Notif->fcmsendmessage($config, $payload);
			$type_notif = 1; // notif booking
			$ttt = array(
				'datetime' => $datetime,
				'nik' => $value['nik'],
				'title' => $title,
				'type' => $type_notif,
				'body' => $body,
				'is_sending' =>1,
				'created_at' =>$datetime,
				'updated_at' =>$datetime,
				'is_deleted' =>0,
			);
			array_push($collect, $ttt);
		}
		$resp1		= $this->Model_Api->insertDataBatch('notification_data', $collect);
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
		    '13'=> '13',
		    '14'=> '14',
		    '15'=> '15',
		    '16'=> '16',
		    '17'=> '17',
		    '18'=> '18',
		    '19'=> '19',
		    '20'=> '20',
		    '21'=> '21',
		    '22'=> '22',
		    '23'=> '23',
		    '24'=> '24',
		);

		$d = explode(":", $string);
		$h = $d[0];
		$m = $d[1];
		$s = $d[2];
		// $formatH = ( ($m-0) > 12 ) ? "PM":"AM";
		$formatH = "";
		// return $nM[$h] . ":". $m . " ".$formatH;
		return $h. ":". $m . " ".$formatH;
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
			$this->load->view('Admin/Booking/todayBooking');
			
		}else if($this->session->userdata('levelid-nya') == 2){
			// user
			$this->load->view('Admin/Booking/todayBooking');
		}
	}
	public function getTodayBookingPage2()
	{
		if($this->session->userdata('levelid-nya') == 1){
			$this->load->view('Admin/Booking/todayBooking_page2');
			
		}else if($this->session->userdata('levelid-nya') == 2){
			// user
			$this->load->view('Admin/Booking/todayBooking_page2user');
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
	public function checkAvailableMeetingRoom()
	{
		


		$date 			= date("Y-m-d");
		$sst 			= date("H:i:s");
		$meetingRoom 	= array();
		$timearray 		= array();

		// $dataRoom 		= $this->Model_Admin->getDataRoom()['data'];
		$dayNameNum = date("w", strtotime($date));
		$dayName = getDayName($dayNameNum);
		$whreRoomString = "  r.work_day LIKE '%".$dayName."%' ";
		$dataRoom 		= $this->Model_Admin->getDataRoom($whreRoomString)['data'];
		// $sqlroom 		= "SELECT * FROM room  WHERE is_deleted=0";
		// $queryRoom 		= $this->Model_Admin->querySql($sqlroom);
		// $dataRoom 		= $queryRoom->result_array();
		$settingGeneral	= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		// $numLoop		= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$stTime 		= $date." 00:00:00";
		$timess 		= strtotime($stTime);

		// die();
		
		$timearray	= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		$timearray = $timearray['data'];
		echo "<pre>";
		print_r($_GET);
		print_r($dataSettingGeneral);
		print_r($timearray);
		die();

		$lenTime = count($timearray)-1;
		foreach ($dataRoom  as $k => $v) {
			$room_id 	= $v['radid'];
			$sqlgettime 	= $this->Model_Booking->makeQueryGetTimeData($timearray, $date, $room_id, $lenTime);
			if($v['type_room'] == "merge"){
				$mergeRoom 		= $this->Model_Admin->getDataMergeRoomBooking($room_id)['data'];
				$dataRoom[$k]['merge_room']  = $mergeRoom;
			}
			$queryTime 		= $this->Model_Api->querySql($sqlgettime);
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
			foreach ($dataTimeArray as $key => $value) {
				$nowtime = strtotime($date . " ".$sst);
				$bookingtime = strtotime($date . " " . $value['time_array']);
				if( $nowtime  > $bookingtime){
					$dataTimeArray[$key]['book'] = 1;
				}
			}
			$queryCat 		= $this->Model_Api->querySql("SELECT rd.*, ru.name FROM room_for_usage_detail rd INNER JOIN room_for_usage ru ON rd.room_usage_id=ru.id WHERE rd.room_id='".$room_id."' ORDER BY ru.id ASC ");
			$dataRoom[$k]['datatime'] = $dataTimeArray;
			$dataRoom[$k]['setting'] = $dataSettingGeneral;
			$dataRoom[$k]['category'] = $queryCat->result_array();
		} // foreach $dataroom
		echo response("success", $dataRoom, "Get success");
		die();
		
	}
	public function checkTodayBooking()
	{
		$date 			= date("Y-m-d");
		$sst 			= date("H:i:s");
		$meetingRoom 	= array();
		$timearray 		= array();

		// $dataRoom 		= $this->Model_Admin->getDataRoom()['data'];
		$dayNameNum = date("w", strtotime($date));
		$dayName = getDayName($dayNameNum);
		$whreRoomString = "  r.work_day LIKE '%".$dayName."%' ";
		$dataRoom 		= $this->Model_Admin->getDataRoom($whreRoomString)['data'];
		// $sqlroom 		= "SELECT * FROM room  WHERE is_deleted=0";
		// $queryRoom 		= $this->Model_Admin->querySql($sqlroom);
		// $dataRoom 		= $queryRoom->result_array();
		$settingGeneral	= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		// $numLoop		= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$stTime 		= $date." 00:00:00";
		$timess 		= strtotime($stTime);

		// die();
		
		$timearray	= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		$timearray = $timearray['data'];

		$lenTime = count($timearray)-1;
		foreach ($dataRoom  as $k => $v) {
			$room_id 	= $v['radid'];
			$sqlgettime 	= $this->Model_Booking->makeQueryGetTimeData($timearray, $date, $room_id, $lenTime);
			if($v['type_room'] == "merge"){
				$mergeRoom 		= $this->Model_Admin->getDataMergeRoomBooking($room_id)['data'];
				$dataRoom[$k]['merge_room']  = $mergeRoom;
			}
			$queryTime 		= $this->Model_Api->querySql($sqlgettime);
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
			foreach ($dataTimeArray as $key => $value) {
				$nowtime = strtotime($date . " ".$sst);
				$bookingtime = strtotime($date . " " . $value['time_array']);
				if( $nowtime  > $bookingtime){
					$dataTimeArray[$key]['book'] = 1;
				}
			}
			$queryCat 		= $this->Model_Api->querySql("SELECT rd.*, ru.name FROM room_for_usage_detail rd INNER JOIN room_for_usage ru ON rd.room_usage_id=ru.id WHERE rd.room_id='".$room_id."' ORDER BY ru.id ASC ");
			$dataRoom[$k]['datatime'] = $dataTimeArray;
			$dataRoom[$k]['setting'] = $dataSettingGeneral;
			$dataRoom[$k]['category'] = $queryCat->result_array();
		} // foreach $dataroom
		echo response("success", $dataRoom, "Get success");
		die();
		
	}
	public function checkPickerBooking()
	{
		$selectDate = $this->uri->segment(5);
		$date 			= $selectDate;
		$meetingRoom 	= array();
		$timearray 		= array();
		// $dataRoom 		= $this->Model_Admin->getDataRoom()['data'];

		$dayNameNum = date("w", strtotime($date));
		$dayName = getDayName($dayNameNum);
		$whreRoomString = "  r.work_day LIKE '%".$dayName."%' ";
		$dataRoom 		= $this->Model_Admin->getDataRoom($whreRoomString)['data'];
		// $sqlroom 		= "SELECT * FROM room  WHERE is_deleted=0";
		$settingGeneral	= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		$numLoop		= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$timearray	= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		$timearray = $timearray['data'];
		$lenTime = count($timearray)-1;
		foreach ($dataRoom  as $k => $v) {
			$room_id = $v['radid'];
			$sqlgettime 	= $this->Model_Booking->makeQueryGetTimeData($timearray, $date, $room_id, $lenTime);
			if($v['type_room'] == "merge"){
				$mergeRoom 		= $this->Model_Admin->getDataMergeRoomBooking($room_id)['data'];
				$dataRoom[$k]['merge_room']  = $mergeRoom;
			}
			$queryTime 		= $this->Model_Api->querySql($sqlgettime);
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
			$dataRoom[$k]['datatime'] = $dataTimeArray;
			$dataRoom[$k]['setting'] = $dataSettingGeneral;
		} // foreach $dataroom
		echo response("success", $dataRoom, "Get success");
	}	
	public function checkPickerBookingWithRoom()
	{
		$booking_id = $this->uri->segment(5);
		$selectDate = $this->uri->segment(6);
		$room_id = $this->uri->segment(7);
		$date 			= $selectDate;
		$meetingRoom 	= array();
		$timearray 		= array();
		$dataBooking 	= $this->Model_Admin->getDataBookingById($booking_id)['data'];
		$dataRoom 		= $this->Model_Admin->getRoomRadid($room_id)['data'];
		$settingGeneral	= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];

		$numLoop		= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$stTime 		= $date." 00:00:00";
		
		if($dataBooking['title'] == null){
			echo response("fail", array(), "Data booking not exist");
			die();
		}

		// IF MERGE
		if($dataBooking['is_merge'] == true){
			$dataRoom 			= array();
			$dataMergeRoomRaw 	= $dataBooking['merge_room'];
			$dataMergeRoom 		= json_decode($dataMergeRoomRaw,true);
			foreach ($dataMergeRoom  as $key => $value) {
				$temRoom = $this->Model_Admin->getRoomRadid($room_id)['data'];
				if (count($temRoom) > 0) {
					array_push($dataRoom, $temRoom[0]);
				}
			}

		}
		
		$timearray	= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		$timearray = $timearray['data'];
		$lenTime = count($timearray)-1;
		foreach ($dataRoom  as $k => $v) {
			$sql 		= "";
			$room_id 	= $v['radid'];
			$sql 		.= "SELECT * FROM ( ";
			foreach ($timearray as $key => $value) {
				$timeData = $date." ".$value['time'] .":00";
				if ($lenTime == $key) {
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."' AND b.is_alive = 1 AND  b.booking_id<>'".$booking_id."' 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))";
				}else{
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM booking b 
						LEFT JOIN room r ON b.room_id=r.radid  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 AND  b.booking_id<>'".$booking_id."' 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))";
					$sql .= " UNION ";
				}
			}
			$sql 	.= ") room_time";
			if($v['type_room'] == "merge"){
				$mergeRoom 		= $this->Model_Admin->getDataMergeRoomBooking($room_id)['data'];
				$dataRoom[$k]['merge_room']  = $mergeRoom;
			}
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
			$dataRoom[$k]['datatime'] = $dataTimeArray;
			$dataRoom[$k]['setting'] = $dataSettingGeneral;
		} // foreach 
		echo response("success", $dataRoom, "Get success");
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
		$start = $this->uri->segment(5);
		$end = $this->uri->segment(7);
		
		if($this->session->userdata('levelid-nya') == 1){
			$data = $this->Model_Admin->getDataBooking($start,$end);
			if($data['error'] == null){
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
		}else if($this->session->userdata('levelid-nya') == 2){
			// user
			// echo 123;
			$nik = $this->session->userdata('user-nya');
			$data = $this->Model_Admin->getDataBookingByNik($start,$end,$nik);
			if($data['error'] == null){
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
		}else{
			echo response("fail", array(), "You don't have any access");
		}
		
	}

	public function getFilterScheduleData()
	{
		$post              = $_GET;
        $building_search   = isset($post['building_search']) ? $post['building_search'] : "";
        $room_search       = isset($post['room_search']) ? $post['room_search'] : "";
        $date1_search      = isset($post['date1_search']) ? $post['date1_search'] : "";
        $date2_search      = isset($post['date2_search']) ? $post['date2_search'] : "";
        $timezone          = isset($post['timezone']) ? $post['timezone'] : APP_GMT;
        $employee_search = isset($post['employee_search']) ? $post['employee_search'] : "";
        date_default_timezone_set(APP_GMT);
        if ($timezone != "") {
             date_default_timezone_set($timezone);
        } 

		$wbooking ="  b.date >='" . $date1_search . "' AND b.date <='" . $date2_search . "'  ";
        if($employee_search != ""){ // organizer
            $wbooking .= " AND nik_pic='".$employee_search."'";
        }
        if($room_search != ""){
            $wbooking .= " AND r.radid=" . $room_search . " ";
        }
        if($building_search != ""){
            $wbooking .= " AND bu.id=" . $building_search . " ";
        }
        

		if($this->session->userdata('levelid-nya') == 1){
			$wbooking .= " AND bi.is_pic=1 ";
			$data = $this->Model_Admin->getFilterScheduleBooking($wbooking);
			if($data['error'] == null){
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
			die();
		}else if($this->session->userdata('levelid-nya') == 2){
			$nik = $this->session->userdata('user-nya');
			$wbooking .= " AND bi.nik='".$nik."'";
			$data = $this->Model_Admin->getFilterScheduleBooking($wbooking);
			$wbooking .= " AND bi.nik ='". $nik ."'  ";
			if($data['error'] == null){
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
			die();
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
		$pieceTime = $config['extend_count_time'];
		if($config['extend_meeting'] == 1){
			$rawbooking = $this->Model_Api->getBookingInfo($post['booking_id']);
			$booking = $rawbooking['data'];
			$work_end = $booking['work_end'];
			$extend = $booking['extended_duration']-0;
			$max = $config['extend_meeting_max']-0;
			$collectcheck = array();
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
						$select .= "SELECT CONCAT('".$x ."') as duration, COUNT(*) as book, TIME('".$endtimeExtent."') as time_data FROM booking 
						WHERE end_early_meeting=0 
						AND is_alive = 1 
						AND room_id='".$booking['room_id']."' AND date='".$date."' 
						AND TIME('".$endtimeExtent ."') 
						BETWEEN TIME(start) AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) ";
					}else{
						$select .= "SELECT CONCAT('".$x ."') as duration, COUNT(*) as book, TIME('".$endtimeExtent."') as time_data FROM booking 
						WHERE end_early_meeting=0 
						AND is_alive = 1 
						AND room_id='".$booking['room_id']."' AND date='".$date."' 
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
			$getBooking = $this->Model_Api->getDataBookingById($post['booking_id']);
			$ex = $post['extend']-0;
			$duration = $ex;
			$dataBooking = $getBooking['data'];
			$getBookingInv 		= $this->Model_Admin->getDataBookingInvById($post['booking_id'])['data'];
			$room_name 			= $dataBooking['room_name'];

			$extended_duration = $dataBooking['extended_duration'];
			$settingGeneral				= $this->Model_Api->getSettingDataGeneral();
			$dataSettingGeneral 		= $settingGeneral['data'];
			$fHour 						= $dataSettingGeneral['duration'];
			// 
			$cost						= $dataBooking['price']; // per hours
			$allduration 				= $extended_duration + $duration + $ex;
			$getHoursMeeting 			= floor($allduration / $fHour);
			$checkHours 				= fmod($allduration,$fHour);
			if($checkHours > 0){
				$getHoursMeeting += 1;
			}
			$reservation_cost 			= $cost	* $getHoursMeeting;
			$sql = "UPDATE booking SET 
				extended_duration=extended_duration+".$ex.",
				cost_total_booking=".$reservation_cost." 
				WHERE booking_id='". $post['booking_id']."' ";
			$booking_invoice = array(
				"rent_cost" => $reservation_cost,
				"alocation" => $dataBooking ['alocation_id'],
				"time_before" => $datetime,
				"updated_at" 	=> $datetime,
			);
			$winvoice 	= array("booking_id" => $dataBooking['booking_id']);
			$resp3 = $this->Model_Api->querySql($sql);
			$udata 	= $this->Model_Api->updateData("booking_invoice", $booking_invoice, $winvoice);

			$notifcollectdata 	= array();
			foreach ($getBookingInv as $val) {
				if($val['internal'] == 1){
					// only internal
					$_notif 					= array();
					$_notif['datetime'] 		= $datetime;
					$_notif['nik'] 				= $val['nik']; // user id
					$_notif['type'] 			= 1; // booking is 1
					$_notif['value'] 			= $post['booking_id']; // booking id
					$_notif['title'] 			= "Extend meeting";
					$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
					$_notif['is_sending'] 		= 0;
					$_notif['is_deleted'] 		= 0;
					$_notif['created_at'] 		= $datetime;
					if($val['is_pic'] == 1){
						$_notif['title'] 			= "Extend meeting";
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
				$notification_title = "Extend Meeting of ".$meeting_title ;
				// $room_name 			= $room_name;
				$notification_body 	= $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end). " at " .$room_name;
				$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
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
		$getBooking 		= $this->Model_Admin->getDataBookingById($post['booking_id']);
		$dataBooking 		= $getBooking['data'];
		$ddd 				= $dataBooking['date'];
		$getBookingInv 		= $this->Model_Admin->getDataBookingInvById($post['booking_id'])['data'];
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
				$_notif['title'] 			= "End meeting";
				$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				$_notif['is_sending'] 		= 0;
				$_notif['is_deleted'] 		= 0;
				$_notif['created_at'] 		= $datetime;
				if($val['is_pic'] == 1){
					$_notif['title'] 			= "End meeting";
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
				$notification_title = "Extend Meeting of ".$meeting_title;
				// $room_name 			= $room_name;
				$notification_body 	= $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end). " at " .$room_name;
				$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
		}

		$datarow = array(
			"end_early_meeting" => 1,
			"updated_at" 		=> $datetime,
			"updated_by" 		=> $this->session->userdata('user-nya'),
			"is_alive" 			=> 4,
			"early_ended_by" 	=> $this->session->userdata('user-nya'),
			"early_ended_at" 	=> $datetime ,
			"text_early" 		=> "By Admin"
		);
		if ($isUser) {
			$datarow['text_early'] = "By ".$nameUser;
		}

		$whereAr = array(
			"booking_id" => $post['booking_id']
		);
		$getData = $this->Model_Admin->updateData('booking',$datarow, $whereAr);
		if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title 		= $dataBooking['title'];
				$meeting_date 		= $dataBooking['date'];
				$explodeS 			= explode(" ", $dataBooking['start']);
				$explodeE 			= explode(" ", $dataBooking['end']);
				$meeting_start 		= $explodeS[1];
				$meeting_end 		= $explodeE[1];
				$notification_title = "Extend Meeting of ".$meeting_title;
				// $room_name 			= $room_name;
				$notification_body 	= $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end). " at " .$room_name;
				$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
		}

		$response = response("success", array(), "Success get data to end meeting ");
		echo $response ;	
	}
	public function postCancelBook()
	{
		$modules['pantry'] = $this->Model_Module->get_module_pantry();
		$modules['loker'] = $this->Model_Module->get_module_loker();
		$modules['price'] = $this->Model_Module->get_module_price();
		$modules['invoice'] = $this->Model_Module->get_module_invoice();
		$modules['email'] = $this->Model_Module->get_module_email();
		$post =$_POST;

		// TIMEZONE
		// TIMEZONE
		// TIMEZONE
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set(APP_GMT);
		}
		$getBooking 		= $this->Model_Admin->getDataBookingById($post['booking_id']);
		$getBookingInv 		= $this->Model_Admin->getDataBookingInvById($post['booking_id'])['data'];
		$getPic 					= $this->Model_Admin->getPICByBookingId($post['booking_id']);
		$dataPic 					= $getPic['data'];
		$sqlEmail 			= "SELECT * FROM sending_email WHERE booking_id='".$post['booking_id']."' ";
		$dataBooking = $getBooking['data'];
		$notifcollectdata = array();
		$ddd = $dataBooking['date'];

		$datetime 		= date('Y-m-d H:i:s');
		$room_id 					= $dataBooking['room_id'];
		$Q_room						= " SELECT * FROM room WHERE radid=".$room_id." ";
		$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		$booking_id					= $post['booking_id'];
		// 
		// MODULE PANTRY
		if($modules['pantry']['is_enabled'] == 1 ){
			$set_pantry_config          = $this->Model_Admin->select_all_data('setting_pantry_config', array(), array(), 'row');
			$pantry_expired 			= $set_pantry_config['pantry_expired']; 
			$pantry_max_order_qty 		= $set_pantry_config['max_order_qty']; 
			$pantry_before_order_meeting= $set_pantry_config['before_order_meeting']; 
			$set_pantry 				= array();
			$where_pantry 				= array();
			$pantry_order 				= $this->Model_Admin->select_all_data('pantry_transaksi', 
											array('booking_id' =>$dataBooking ['booking_id'], 'via'=>'booking', 'order_st'=>0 ), 
											array(), 'result');
			if(count($pantry_order) > 0){
				$updateOrderTime = array(); // collected data pantry
				$row_pantry_order = $pantry_order[0];
				$s_datetime = $datetime ;
				$pantry_trs_status = $this->Model_Admin->select_all_data('pantry_transaksi_status', array('id'=>4), array(), 'row');
				$updateOrderTime['via'] = 'booking';
				$updateOrderTime['order_st'] = 4;
				$updateOrderTime['order_st_name'] = $pantry_trs_status['name'];
				$updateOrderTime['is_canceled'] = 1;
				$updateOrderTime['updated_at'] = $s_datetime;
				$updateOrderTime['canceled_at'] = $s_datetime;
				$updateOrderTime['canceled_by'] = $this->session->userdata('user-nya');
				$set_pantry = $updateOrderTime;
				$where_pantry['id'] = $row_pantry_order['id'];

			}
			if(count($pantry_order) > 0){
				$pdata 				= $this->Model_Admin->updateData("pantry_transaksi", $set_pantry, $where_pantry);
			}
		}
		
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
				$_notif['title'] 			= "Meeting Cancelled";
				$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				$_notif['is_sending'] 		= 0;
				$_notif['is_deleted'] 		= 0;
				$_notif['created_at'] 		= $datetime;
				if($val['is_pic'] == 1){
					// pic
					$_notif['title'] 			= "Cancel a Meeting";
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
			$dataBooking['booking_devices'] = "web";
			$where = array(
				 "booking_id"=>$dataBooking['booking_id']
			);
			// MICROSOFT 365
			// MICROSOFT 365
			// MICROSOFT 365
			$module_int_365 = $this->Model_Module->get_module_int_365();
			$module_int_google = $this->Model_Module->get_module_int_google();
			// MICROSOFT 365
			// echo "<pre>";

			if($module_int_365['is_enabled'] == 1 ){
				$ms365 = $this->Model_License->get365Integration();
				$ck365 = $this->Model_License->check365Data();
				// print_r($dataInvitation);
				if($ck365 == true){
					$dataInvitation222 = [];
					$res_365 = $this->Model_License->cancelEvent365($dataBooking,$ms365, $dataInvitation222,$dataInvitation222);
					try{
						$jres_365 = json_decode($res_365, TRUE);
						if(!isset($jres_365['error'])){
							// $data['booking_id_365'] = $jres_365 ['id'];
						}else{
							// $data['booking_id_365'] = "";
						}
					}catch(Exeption $e){

					}
					
					// print_r($jres_365);
				}
			}
			if($module_int_google['is_enabled'] == 1 ){

			}
			// die();
			unset($dataBooking['id']);
			unset($dataBooking['room_name']);
			unset($dataBooking['room_name2']);
			unset($dataBooking['price']);
			unset($dataBooking['booking_id']);
			unset($dataBooking['booking_id']);

			$udata 				= $this->Model_Admin->updateData("booking", $dataBooking, $where);

			$response 			= response("success", array(), "Success Cancel a booking ");
			$this->Model_Notif->insertNotifAdmin(12, "Cancel a meeting", $dataBooking['title']);
			if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title = $dataBooking['title'];
				$meeting_date = $dataBooking['date'];
				$explodeS = explode(" ", $dataBooking['start']);
				$explodeE = explode(" ", $dataBooking['end']);
				$meeting_start = $explodeS[1];
				$meeting_end = $explodeE[1];
				$notification_title = "Meeting Cancelled, ".$meeting_title;
				$room_name = $room_name;
				$notification_body = $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end). " at " .$room_name;
				$pNotif =  $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
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
                $emailBooking['format_time_start'] = $this->Model_Admin->formatTime($meeting_start);
                $emailBooking['format_time_end'] = $this->Model_Admin->formatTime($meeting_end);
                $emailBooking['format_date'] = $this->Model_Admin->formatDate($meeting_date);
                if($modules['email']['is_enabled'] == 1 ){
                	foreach ($dataToSend['internal'] as $key => $people) {
	                    $pNotif = $this->Model_Notif->sendEmailInternal("cancel", $emailBooking, $people,$dataPic);
	                }   
	                foreach ($dataToSend['eksternal'] as $key => $people) {
	                    $pNotif = $this->Model_Notif->sendEmailExternal("cancel", $emailBooking, $people,$dataPic);
	                }  
                }
			}
			echo $response;
		}else{
			echo response("fail", array(), "Data of booking not found");
		}
	}

	public function postReBook()
	{
		$modules['pantry'] = $this->Model_Module->get_module_pantry();
		$modules['loker'] = $this->Model_Module->get_module_loker();
		$modules['price'] = $this->Model_Module->get_module_price();
		$modules['invoice'] = $this->Model_Module->get_module_invoice();
		$modules['email'] = $this->Model_Module->get_module_email();

		$post 						= $_POST;
		$timezone =APP_GMT;
		if(isset($post['timezone'])){
			$timezone = $post['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set(APP_GMT);
		}
		$getBooking 				= $this->Model_Admin->getDataBookingById($post['booking_id']);
		$getBookingInv 				= $this->Model_Admin->getDataBookingInvById($post['booking_id'])['data'];

		$getPic 					= $this->Model_Admin->getPICByBookingId($post['booking_id']);
		$dataPic 					= $getPic['data'];
		// $sqlNotif 				= "SELECT * FROM sending_notif WHERE booking_id='".$post['booking_id']."' ";
		$sqlEmail 					= "SELECT * FROM sending_email WHERE booking_id='".$post['booking_id']."' ";
		$settingGeneral				= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];
		$dataInvitation				= $getBookingInv;
		$datetime 					= date("Y-m-d H:i:s");
		$dataBooking 				= $getBooking['data'];
		$isMerge 					= $dataBooking['is_merge'];
		$notifcollectdata 			= array();
		$ddd = $post['date'] == "" ? $dataBooking['date'] :  $post['date'];
		$room_id 					= $dataBooking['room_id'];

		$this->Model_MeetingLimitation->checkMeeting($post['start'], $post['end']);

		if(isset($dataSettingGeneral['limit_time_booking'])){
			if($dataSettingGeneral['limit_time_booking'] > 0 ){
				$limit_duration = $dataSettingGeneral['limit_time_booking'] -0;
				$startTime1 = strtotime($post['start']);
				$checkTime1 = strtotime($post['end']);
				$extended_duration1 = $dataBooking["extended_duration"];
				$duration1 = round(abs($checkTime - $startTime) / 60,2) + $extended_duration;
				if($duration1 > $limit_duration){
					$response = response("fail", array(), "Maximum meeting duration is ".$limit_duration." minutes");
					echo $response;
					die();
				}
			}
		}
		if($isMerge == true){
			$Q_room						= " SELECT * FROM room WHERE radid=".$dataBooking['merge_room_id']." ";
			$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		}else{
			$Q_room						= " SELECT * FROM room WHERE radid=".$room_id." ";
			$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		}
		// module pantry
		if($modules['pantry']['is_enabled'] == 1 ){
			$set_pantry_config          = $this->Model_Admin->select_all_data('setting_pantry_config', array(), array(), 'row');
			$pantry_expired 			= $set_pantry_config['pantry_expired']; 
			$pantry_max_order_qty 		= $set_pantry_config['max_order_qty']; 
			$pantry_before_order_meeting= $set_pantry_config['before_order_meeting']; 
			$set_pantry 				= array();
			$where_pantry 				= array();
			$pantry_order 				= $this->Model_Admin->select_all_data('pantry_transaksi', 
											array('booking_id' =>$dataBooking ['booking_id'], 'via'=>'booking', 'order_st'=>0 )
											, array(), 'result');
			
			if(count($pantry_order) > 0){
				$updateOrderTime = array(); // collected data pantry
				$row_pantry_order = $pantry_order[0];
				if($dataBooking['date'] != $post['date']){
					$q_date = $dataBooking['date'];
					$sql_pantry = "SELECT COALESCE(max(order_no), '') as order_no from pantry_transaksi
								WHERE DATE(order_datetime) = '".$q_date."'   AND pantry_id=".$row_pantry_order['pantry_id'] ." "  ;
					$row_order_pantry 	= $this->Model_Admin->querySql($sql_pantry)->row_array();
					if($row_order_pantry['order_no'] == "" || $row_order_pantry['order_no'] == null){
						$no_order_pantry = sprintf("%04d", "1");
					}else{
						$old_no_order_pantry = $row_order_pantry['order_no']-0;
						$no_sort_order_pantry = $old_no_order_pantry + 1;
						$no_order_pantry = sprintf("%04d", $no_sort_order_pantry);
					}
					$updateOrderTime['order_no'] = $no_order_pantry;
				}
				$pantry_trs_status = $this->Model_Admin->select_all_data('pantry_transaksi_status', array('id'=>0), array(), 'row');
				$startBooking = $post['start'] == "" ? $dataBooking['start'] :  $post['start'];
				$tanggaltime_order_pantry = $startBooking;
				$b_time = "-".$pantry_before_order_meeting;
				$tanggaltime_order_pantry_before = date('Y-m-d H:i:s', strtotime($b_time .'minutes', strtotime($tanggaltime_order_pantry)));
				$updateOrderTime['order_datetime'] = $tanggaltime_order_pantry;
				$updateOrderTime['order_datetime_before'] = $tanggaltime_order_pantry_before;
				$updateOrderTime['via'] = 'booking';
				$updateOrderTime['order_st'] = 0;
				$updateOrderTime['order_st_name'] = $pantry_trs_status['name'];
				$dataBooking['updated_at'] = $datetime;
				$set_pantry = $updateOrderTime;
				$where_pantry['id'] = $row_pantry_order['id'];

			}
			if(count($pantry_order) > 0){
				$pdata 				= $this->Model_Admin->updateData("pantry_transaksi", $set_pantry, $where_pantry);
			}
		}
		if(count($room) <= 0){
			$response = response("fail", array(), "Room not found ");
			echo $response;
			die();
		}
		$room 			 			= $room[0];
		// die();
		foreach ($dataInvitation as $val) {
			if($val['internal'] == 1){
				// only internal
				$_notif 					= array();
				$_notif['datetime'] 		= $datetime;
				$_notif['nik'] 				= $val['nik']; // user id
				$_notif['type'] 			= 1; // booking is 1
				$_notif['value'] 			= $post['booking_id']; // booking id
				$_notif['title'] 			= "Reschedule meeting";
				$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				$_notif['is_sending'] 		= 0;
				$_notif['is_deleted'] 		= 0;
				$_notif['created_at'] 		= $datetime;
				if($val['is_pic'] == 1){
					// pic
					$_notif['title'] 			= "Reschedule a meeting";
					$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				}
				array_push($notifcollectdata, $_notif);
			}
			
		}

		
		if($getBooking ['error'] == null){

			$datetime 					= date('Y-m-d H:i:s');
			$dataBooking = $getBooking['data'];
			$dataBooking['date'] = $post['date'] == "" ? $dataBooking['date'] :  $post['date'];
			$dataBooking['start'] = $post['start'] == "" ? $dataBooking['start'] :  $post['start'];
			$dataBooking['end'] = $post['end'] == "" ? $dataBooking['end'] :  $post['end'];
			$startTime = strtotime($dataBooking['start']);
			$checkTime = strtotime($dataBooking['end']);
			$extended_duration = $dataBooking["extended_duration"];
			$duration = round(abs($checkTime - $startTime) / 60,2) + $extended_duration; // get minute of duration
			$fHour 						= $dataSettingGeneral['duration'];
			// $duration					= $duration;
			if($modules['price']['is_enabled'] == 1 ){
				$cost						= $dataBooking['price']; // per hours
				$allduration 				= $extended_duration+ $duration;
				$getHoursMeeting 			= floor($allduration / $fHour);
				$checkHours 				= fmod($allduration,$fHour);
				if($checkHours > 0){
					$getHoursMeeting += 1;
				}
			}
			

			$booking_id = $dataBooking['booking_id'];
			$tanggal_meeting = $dataBooking['date'];
			$waktu_timestart = $dataBooking['start'];
			$waktu_timeend = $dataBooking['end'];
			$waktu_mulai = $waktu_timestart;
			$ruangan = $dataBooking['room_id'];

			// server 
			$server_date = new DateTime($dataBooking['start'] , new DateTimeZone($timezone));
			$server_start = new DateTime($dataBooking['start'] , new DateTimeZone($timezone));
			$server_end = new DateTime($dataBooking['end'] , new DateTimeZone($timezone));

			$dataBooking['server_start'] = $server_start->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
			$dataBooking['server_end'] = $server_end->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
			$dataBooking['server_date'] = $server_date->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d');


			if($isMerge == true){
				$tempMergeRoomNameRaw = $dataBooking['merge_room'];
				$tempMergeRoomName = json_decode($dataBooking['merge_room'],true);
				foreach ($tempMergeRoomName  as $mk => $mv) {
					$ruangan_m_id = $mv['radid'];
					$this->Model_Admin->checkKondisiBookingPerRuanganRes($ruangan_m_id,$dataBooking['server_date'], $dataBooking['server_start'],$dataBooking['server_end'], $booking_id);
				}
			}else{
				$this->Model_Admin->checkKondisiBookingPerRuanganRes($ruangan,$dataBooking['server_date'], $dataBooking['server_start'],$dataBooking['server_end'], $booking_id);
			}

			// =====================================================
			$reservation_cost 			= 0;

			if($modules['invoice']['is_enabled'] == 1  && $modules['price']['is_enabled'] == 1 ){
				$booking_id_invoice = $dataBooking['booking_id'];
				$reservation_cost 			= $cost	* $getHoursMeeting;
				$winvoice 	= array("booking_id" =>$booking_id_invoice);
				$data_invoice = array(
					"rent_cost" => $reservation_cost,
					"alocation" => $dataBooking ['alocation_id'],
					"time_before" => $datetime,
					"updated_at" 	=> $datetime,
					"updated_by" 	=> $this->session->userdata('user-nya'),
				);
				$udata 				= $this->Model_Admin->updateData("booking_invoice", $data_invoice, $winvoice);
			}
			$dataBooking['booking_devices'] = "web";
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
			$room_name = $dataBooking['room_name'];
			// MICROSOFT 365
			// MICROSOFT 365
			// MICROSOFT 365
			$module_int_365 = $this->Model_Module->get_module_int_365();
			$module_int_google = $this->Model_Module->get_module_int_google();
			// MICROSOFT 365
			// echo "<pre>";
			if($module_int_365['is_enabled'] == 1 ){
				$ms365 = $this->Model_License->get365Integration();
				$ck365 = $this->Model_License->check365Data();
				// print_r($dataInvitation);
				if($ck365 == true){
					// print_r($dataInvitation);
					$dataInvitation222 = [];
					$res_365 = $this->Model_License->rescheduleEvent365($dataBooking,$room,$ms365, $dataInvitation,$dataInvitation222);
					try{
						$jres_365 = json_decode($res_365, TRUE);
						if(!isset($jres_365['error'])){
							$data['booking_id_365'] = $jres_365 ['id'];
						}else{
							$data['booking_id_365'] = "";
						}
					}catch(Exeption $e){
						
					}
					
				}
			}
			if($module_int_google['is_enabled'] == 1 ){
				$data['booking_id_google'] = "";
			}
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
			
			// UPDATE BOOKING 
			$udata 				= $this->Model_Admin->updateData("booking", $dataBooking, $where);
			// $
			$this->Model_Notif->insertNotifAdmin(12, "Reschedule meeting", $dataBooking['title']);
			$meeting_date = $dataBooking['date'];
			$explodeS = explode(" ", $dataBooking['start']);
			$explodeE = explode(" ", $dataBooking['end']);
			$meeting_start = $explodeS[1];
			$meeting_end = $explodeE[1];
			if(count($notifcollectdata) > 0){
				$this->Model_Notif->insertNotifBatch($notifcollectdata);
				$meeting_title = $dataBooking['title'];
				
				$notification_title = "Reschedule Meeting of ".$meeting_title;
				$room_name = $room['name'];
				$notification_body = $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end) . " at " .$room_name;
				$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
			}	

			// $tableNotif			= $this->Model_Admin->querySql($sqlNotif)->result_array();
			$tableEmail			= $this->Model_Admin->querySql($sqlEmail)->result_array();
			
			if(count($tableEmail) > 0){
				$getBookingEmail 		= $this->Model_Api->getDataBookingById($booking_id); //
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
		        $emailBooking['format_time_start'] = $this->Model_Admin->formatTime($meeting_start);
		        $emailBooking['format_time_end'] = $this->Model_Admin->formatTime($meeting_end);
		        $emailBooking['format_date'] = $this->Model_Admin->formatDate($meeting_date);
		        if($modules['email']['is_enabled'] == 1 ){
		        	foreach ($dataToSend['internal'] as $key => $people) {
		            	$pNotif = $this->Model_Notif->sendEmailInternal("reschedule", $emailBooking, $people,$dataPic);
			        }   
			        foreach ($dataToSend['eksternal'] as $key => $people) {
			            $pNotif = $this->Model_Notif->sendEmailExternal("reschedule", $emailBooking, $people,$dataPic);
			        }   	
		        }
		        
				
			}
			$response = response("success", array(), "Success reschedule a booking ");
			echo $response;
		}else{
			echo response("fail", array(), "Data of booking not found");
		}
	}
	public function postBook()
	{

		include APPPATH.'third_party/phpqrcode/qrlib.php';

		$modules['pantry'] = $this->Model_Module->get_module_pantry();
		$modules['loker'] = $this->Model_Module->get_module_loker();
		$modules['price'] = $this->Model_Module->get_module_price();
		$modules['invoice'] = $this->Model_Module->get_module_invoice();
		$modules['email'] = $this->Model_Module->get_module_email();
		$modules['vip'] = $this->Model_Module->get_module_vip();
		$modules['room_adv'] = $this->Model_Module->get_module_room_adv();
		$modules_room_adv_enabled = $modules['room_adv']['is_enabled']-0;
		$modules_vip_enabled = $modules['vip']['is_enabled']-0;

		$tmpdir = "assets/qr/";
		$statusInvoice 				= $this->getStatusInvoiceName();
		$post 						= $_POST;
		$timezone = APP_GMT;
		date_default_timezone_set(APP_GMT);
		if(isset($post['timezone'])){
			if($post['timezone'] != ""){
				$timezone = $post['timezone'];
				date_default_timezone_set($timezone);
			}
		}

		$datetime 					= date('Y-m-d H:i:s');
		$randoom_id					= random_string('numeric', 10);
		$id 						= $randoom_id;
		$invoice_id 				= $randoom_id;
		$internal  					= isset($post['partisipant']) ? $post['partisipant'] : array();
		$res_alocation				= $this->Model_Admin->checkBookingAlocationData($post['alocation']);
		$settingGeneral				= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];
		$alocation 					= $res_alocation['data'];
		$room_id 					= $post['room']['radid'];
		$pantry_package				= empty($post['pantry_package']) == false ? $post['pantry_package'] : "" ;
		$pantry_detail				= empty($post['pantry_detail']) == false ? $post['pantry_detail'] : array() ;
		$set_pantry_config          = $this->Model_Admin->select_all_data('setting_pantry_config', array(), array(), 'row');

		$pantry_expired 			= $set_pantry_config['pantry_expired']; 
		$pantry_max_order_qty 		= $set_pantry_config['max_order_qty']; 
		$pantry_before_order_meeting= $set_pantry_config['before_order_meeting']; 
		$set_pantry 				= array();
		$collected_pantry_detail 	= array();
		$isMerge 	= isset($post['is_merge']) ? $post['is_merge'] : 0;
		if($isMerge == "false" ){
			$isMerge = 0;
		}else if($isMerge == "true" ){
			$isMerge = 1;
		}

		$this->Model_MeetingLimitation->checkMeeting($post['date'].' '. $post['timestart']['time_array'], $post['date'].' '. $post['timeend']['time_array']);

		$mergeRoom 	= isset($post['merge_room']) ? $post['merge_room'] : array();
		$dataMergeRoom 	=  array();
		$dataMergeRoomWidthJson =  "[]";

		$Q_room						= "SELECT * FROM room WHERE radid=".$room_id." ";
		$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		if(count($room) <= 0){
			$response = response("fail", array(), "Room not found ");
			echo $response;
			die();
		}
		$room 			 			= $room[0];
		$room_name 					= $room['name'];
		$merge_room_name 			= $post['room']['name'];
		$merge_room_id 				= $post['room']['radid'];
		
		if($isMerge == true){
			$tempMergeRoomName = array();
			foreach ($mergeRoom  as $mk => $mv) {
				$Q_room						= "SELECT * FROM room WHERE radid=".$mv." ";
				$vmergeroom 			 	= $this->Model_Admin->querySql($Q_room)->row_array();
				if( $vmergeroom ['name'] != null){
					$vmergeroom_name = $vmergeroom ['name'] != null ? $vmergeroom 	['name'] : "";
					$dataSimpleRoom = array(
						"radid" => $vmergeroom['radid'],
						"name" => $vmergeroom['name'],
						"location" => $vmergeroom['location'],
						"link" => $vmergeroom['google_map'],
					);
					array_push($dataMergeRoom, $dataSimpleRoom);
					array_push($tempMergeRoomName, $vmergeroom['name']);
				}
			}
			if(count($tempMergeRoomName)>0){
				$room_name 	.= " (".implode(", ", $tempMergeRoomName) . ")";
				$dataMergeRoomWidthJson = json_encode($dataMergeRoom);
			}
		}
		if(count($internal) > 0) {
			$rowInternal 			= $this->Model_Admin->getDataEmployeeWhereInNik($internal);
		}else{
			$rowInternal 			= array();
			$rowInternal['data'] 	= array();
		}
		$eksternal  				= isset($post['partisipantManual']) ? $post['partisipantManual'] : array();

		$fHour 						= $dataSettingGeneral['duration'];
		$duration					= $post['duration'];
		$reservation_cost 			= 0;
		$formatInvoice 				= "";
		if($modules['price']['is_enabled'] == 1){
			$cost						= $post['room']['price']; // per hours
			$getHoursMeeting 			= floor($duration / $fHour);
			$checkHours 				= fmod($duration,$fHour);
			if($checkHours > 0){
				$getHoursMeeting += 1;
			}
			$reservation_cost 			= $cost	* $getHoursMeeting;
		}
		$nikPic						= $post['pic'];
		$resPIC 					= $this->Model_Admin->getEditEmployee($nikPic);
		$getDataPIC 				= $resPIC['data'];
		$internalBatch 				= array();
		$eksternalBatch 			= array();
		$dataEmailInternal 			= $rowInternal['data'];
		$dataEmailEksternal 		= array();
		$dataEmailInternal_array	= array();
		$error_pantry = false;

		if($set_pantry_config['status'] == 1 && $modules['pantry']['is_enabled'] == 1 ){
			foreach ($pantry_detail as $key => $value) {
				if($pantry_max_order_qty < $value['qty']){
					$error_pantry = true;
					break;
				}
			}
			if($pantry_package != ""){
				$tanggaltime_order_pantry = $post['date'] ." ". $post['timestart']['time_array'];
				$b_time = "-".$pantry_before_order_meeting;
				$tanggaltime_order_pantry_before = date('Y-m-d H:i:s', strtotime($b_time .'minutes', strtotime($tanggaltime_order_pantry)));
				$tanggal_order_pantry = $post['date'] ;
				$data_pantry = $this->Model_Admin->getDataPantryPackage($pantry_package)['data'][0];

				$pantry_trs_status = $this->Model_Admin->select_all_data('pantry_transaksi_status', array('id'=>0), array(), 'row');
				$sql_pantry = "SELECT COALESCE(max(order_no), '') as order_no from pantry_transaksi
							WHERE DATE(order_datetime) = '".$tanggal_order_pantry."'   AND pantry_id=".$data_pantry['pantry_id'] ." "  ;
				$idtrspantry = "METTING-".date('YmdHis').random_string('numeric', 3);
				$row_order_pantry 	= $this->Model_Admin->querySql($sql_pantry)->row_array();
				if($row_order_pantry['order_no'] == "" || $row_order_pantry['order_no'] == null){
					$no_order_pantry = sprintf("%04d", "1");
				}else{
					$old_no_order_pantry = $row_order_pantry['order_no']-0;
					$no_sort_order_pantry = $old_no_order_pantry + 1;
					$no_order_pantry = sprintf("%04d", $no_sort_order_pantry);
				}

				$p_datetime = date('Y-m-d H:i:s');
				$set_pantry = array(
					'id' => $idtrspantry, // order id
					'pantry_id' => $data_pantry['pantry_id'],
					'order_no' => $no_order_pantry,
					'employee_id' => $nikPic,
					'booking_id' => $id,// booking ID
					'via' => "booking",
					'datetime' => $p_datetime ,
					'order_datetime' => $tanggaltime_order_pantry ,
					'order_datetime_before' => $tanggaltime_order_pantry_before ,
					'order_st' => 0,
					'order_st_name' => $pantry_trs_status['name'],
					'process' => 0 ,
					'complete' => 0 ,
					'failed' => 0 ,
					'done' => 0 ,
					'note' =>'',
					'created_at' => $p_datetime,
					'is_deleted' => 0,
				);
				
				foreach ($pantry_detail as $key => $value) {
					$d_trs_pantry = array(
						'transaksi_id' => $idtrspantry,
						'menu_id' => $value['id'],
						'qty' => $value['qty']-0,
						'note_order' => $value['note'],
						'note_reject' => "",
						'is_rejected' => 0,
						'is_deleted' => 0,
						'status' => $value['status'],
					);
					array_push($collected_pantry_detail , $d_trs_pantry);
				}
			}
			// die();
			if ($error_pantry) {
				$response = response("fail", array(), "Orders per item exceed. Maximum quantity of ".$pantry_max_order_qty);
				echo $response;
				die();
			}
		}
		
		$tanggal_meeting = $post['date'];
		$waktu_timestart = $post['timestart']['time_array'];
		$waktu_timeend = $post['timeend']['time_array'];
		$waktu_mulai = $post['date'].' '. $waktu_timestart;
		$waktu_akhir = $post['date'].' '. $waktu_timeend;

		$ruangan = $post['room']['radid'];



		
		// =========================================================================
		$years 			= date('Y', strtotime($post['date'])); // get tahun from date
		$y_years 		= date('y', strtotime($post['date'])); // get tahun from date
		$months			= date('m', strtotime($post['date'])); // get tahun from date
		$days 			= date('d', strtotime($post['date'])); // get tahun from date

		if ($modules['invoice']['is_enabled'] == 1 && $modules['price']['is_enabled'] == 1) {
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
		}
		
		// ===
		$data 						= array();  // DATA BOOKING
		$data['booking_id'] 		= $id;
		$data['booking_devices'] 	= "web";
		$data['no_order'] 			= $formatInvoice;
		$data['title'] 				= $post['title'];
		$data['room_id'] 			= $post['room']['radid'];
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
		$data['external_link'] 		= isset($post['external_link']) ? $post['external_link']: "";
		$data['note'] 				= isset($post['note']) ? $post['note']: "";
		$data['room_name'] 			= $room_name;
		$data['is_merge'] 			= $isMerge;
		$data['merge_room_name'] 	= $merge_room_name;
		$data['merge_room_id'] 		= $merge_room_id;
		$data['merge_room'] 		= $dataMergeRoomWidthJson;
		$data['created_at'] 		= $datetime;
		$data['created_at'] 		= $datetime;
		$data['created_by'] 		= $this->session->userdata('user-nya');
		$data['is_vip'] 			= isset($post['is_vip']) ? ($post['is_vip']-0) : 0;
		$data['vip_user'] 			= isset($post['vip_user']) ? $post['vip_user']  : "";
		$data['is_approve']			= 0;  
		$data['user_approval']		= "";
		$data['category']			= isset($post['category']) ? $post['category']  : "";
		$data['timezone']			= $timezone;
		$data['canceled_note']		= "";
		$data['canceled_note']		= "";

		$data	= $this->Model_MeetingLimitation->adjustAdvanceMeeting($data, $room);
		$data	= $this->Model_MeetingLimitation->checkMeetingVipAccess($data, $room);
		$data	= $this->Model_MeetingLimitation->checkApprovalMeetingAccess($data, $room);

		// server 
		$server_date = new DateTime($data['start'] , new DateTimeZone($timezone));
		$server_start = new DateTime($data['start'] , new DateTimeZone($timezone));
		$server_end = new DateTime($data['end'] , new DateTimeZone($timezone));

		$data['server_start'] = $server_start->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
		$data['server_end'] = $server_end->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d H:i:s');
		$data['server_date'] = $server_date->setTimezone(new DateTimeZone(APP_GMT))->format('Y-m-d');

		if($isMerge == true){
			$tempMergeRoomName = array();
			foreach ($mergeRoom  as $mk => $mv) {
				$ruangan_m_id = $mv;
				$this->Model_Admin->checkKondisiBookingPerRuangan($ruangan_m_id,$data['server_date'] , $data['server_start'],$data['server_end']);
			}
		}else{
			$this->Model_Admin->checkKondisiBookingPerRuangan($ruangan,$data['server_date'] , $data['server_start'],$data['server_end']);
		}

		// // // $invitation_pic 
		$invitation_pic = array();
		$invitation_pic['booking_id'] 			= $id;
		$invitation_pic['nik'] 					= $getDataPIC['nik']; // employee id
		$invitation_pic['name'] 				= $getDataPIC['name'];
		if($getDataPIC['nik'] == $data['vip_user'] ){
			$invitation_pic['is_vip'] 			= 0;
		}
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
		
		QRcode::png($qrnvitationPIC,$tmpdir.$qrnvitationPIC.".png");
		// =======

		$nn = 0;
		foreach ($dataEmailInternal as $val) {
			$num_str = random_string('numeric', 6);
			$ibatch 						= array();
			if($val['nik'] == $data['vip_user'] ){
				$ibatch['is_vip'] 			= 0;
			}
			$ibatch['booking_id'] 			= $id;
			$ibatch['nik'] 					= $val['nik']; // user id
			$ibatch['name'] 				= $val['name'];
			$ibatch['internal'] 			= 1;
			$ibatch['attendance_status']	= 0;
			$ibatch['email'] 				= $val['email'];
			$ibatch['is_pic'] 				= 0;
			$ibatch['company'] 				= "";
			$ibatch['pin_room'] 			= $num_str;
			$ibatch['created_at'] 			= $datetime;
			$ibatch['created_by'] 			= $this->session->userdata('user-nya');
			$ibatch['is_deleted'] 			= 0;
			$dataEmailInternal[$nn]['pin_room']	= $num_str;
			$dataEmailInternal[$nn]['is_pic']	= 0;
			array_push($internalBatch, $ibatch);
			$qrnvitation = $id."_".$num_str;
			QRcode::png($qrnvitation,$tmpdir.$qrnvitation.".png");
			$nn ++;
		}
		// insert of PIC invitation
		$ipicemail['card_number'] 			= $getDataPIC['card_number']; // card number
		$ipicemail['nik'] 					= $getDataPIC['nik']; // user id
		$ipicemail['name'] 					= $getDataPIC['name'];
		$ipicemail['division_id']			= 0;
		$ipicemail['is_pic'] 				= 1;
		$ipicemail['email'] 				= $getDataPIC['email']; 
		$ipicemail['pin_room']				= $invitation_pic['pin_room'];
		array_push($dataEmailInternal, $ipicemail);
		foreach ($eksternal as $val) {
			$num_str = random_string('numeric', 6);
			$ibatch = array();
			$ibatch['email']				= $val['email'];
			$ibatch['company']				= $val['company'];
			$ibatch['name'] 				= $val['name'];
			$ibatch['is_pic'] 				= 0;
			$ibatch['booking_id'] 			= $id;
			$ibatch['pin_room'] 			=$num_str;
			array_push($dataEmailEksternal, $ibatch);
			$ibatch['internal'] 			= 0;
			$ibatch['attendance_status'] 	= 0;
			$ibatch['created_at'] 			= $datetime;
			$ibatch['created_by'] 			= $this->session->userdata('user-nya');
			$ibatch['is_deleted'] 			= 0;
			array_push($eksternalBatch, $ibatch);
			$qrnvitation = $id."_".$num_str;
			QRcode::png($qrnvitation,$tmpdir.$qrnvitation.".png");
		}
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

		// print_r($emailBooking);
		$respP 				= $this->Model_Admin->insertData('booking_invitation', $invitation_pic);
		if(count($internalBatch) > 0){
			$resp1			= $this->Model_Admin->insertDataBatch('booking_invitation', $internalBatch);
		}
		if(count($eksternalBatch) > 0){
			$resp2 			= $this->Model_Admin->insertDataBatch('booking_invitation', $eksternalBatch);
		}
		$invStatus 			= "";
		$invName 			= "";
		// MODULE PRICE & INVOICE
		if ($modules['invoice']['is_enabled'] == 1 && $modules['price']['is_enabled'] == 1 && $data['is_alive' == 1]) {
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
			$resp3 = $this->Model_Admin->insertData('booking_invoice', $data_invoice);
		}
		// MICROSOFT 365
		// MICROSOFT 365
		// MICROSOFT 365
		$module_int_365 = $this->Model_Module->get_module_int_365();
		$module_int_google = $this->Model_Module->get_module_int_google();
		// MICROSOFT 365
		$room_365 = $room['config_microsoft'] == null ? "" : $room['config_microsoft'];
		$room_google = $room['config_google'] == null ? "" : $room['config_google'];
		if($module_int_365['is_enabled'] == 1 && $data['is_alive'] == 1  && $room_365 != ""){
			$ms365 = $this->Model_License->get365Integration();
			$ck365 = $this->Model_License->check365Data();
			if($ck365 == true){
				$res_365 = $this->Model_License->createEvent365($data,$room,$ms365, $dataEmailInternal,$dataEmailEksternal);
				try{
					$jres_365 = json_decode($res_365, TRUE);
					if(!isset($jres_365['error'])){
						$data['booking_id_365'] = $jres_365 ['id'];
					}else{
						$data['booking_id_365'] = "";
					}
				}catch(Exeption $e){
					$data['booking_id_365'] = "";
				}
				
			}
		}
		if($module_int_google['is_enabled'] == 1 && $data['is_alive'] == 1  && $room_google != ""){
			$data['booking_id_google'] = "";
		}
		// INSERT BOOKING
		if($isMerge == false){
			$resp3 = $this->Model_Admin->insertData('booking', $data);
		}else{
			foreach ($dataMergeRoom as $SP => $SPVAL) {
				$data['room_id'] = $SPVAL['radid'] ;
				$resp3 = $this->Model_Admin->insertData('booking', $data);
			}
		}
		$respw = $this->Model_Admin->insertData('sending_email', $sending_email);
		$resp4 = $this->Model_Api->insertData('sending_notif', $sending_notif);
		$notifcollectdata = array();
		foreach ($dataEmailInternal as $val) {
			$_notif 					= array();
			$_notif['datetime'] 		=  $datetime;
			$_notif['nik'] 				= $val['nik']; // user id
			$_notif['type'] 			= 1; // booking is 1
			$_notif['value'] 			= $id; // booking id
			$_notif['title'] 			= "Invitation meeting";
			$_notif['body'] 			= $post['title'] ." - ". getformatDate($post['date']);
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
		$_notif['title'] 			= "Create a meeting schedule";
		$_notif['body'] 			= $post['title'] ." - ". getformatDate($post['date']);
		$_notif['is_sending'] 		= 0;
		$_notif['is_deleted'] 		= 0;
		$_notif['created_at'] 		= $datetime;
		$type_notif 				= 1; // notification_type 1=booking
		$notif_insert				= false; // notification_type 1=booking
		array_push($notifcollectdata, $_notif);
		
		// $this->Model_Notif->insertNotifBatch($notifcollectdata);
		$meeting_title = $post['title'];
		$meeting_date = $post['date'];
		$meeting_start = $post['timestart']['time_array'];
		$meeting_end = $post['timeend']['time_array'];
		

		// MODULE PANTRY
		if( ($set_pantry_config['status']-0) == 1 && ($modules['pantry']['is_enabled'] -0) == 1 && $data['is_alive'] == 1 ){
			if($pantry_package != ""){
				if(count($pantry_detail) > 0){
					$resp2 		= $this->Model_Admin->insertData('pantry_transaksi', $set_pantry);
					$resp2 		= $this->Model_Admin->insertDataBatch('pantry_transaksi_d', $collected_pantry_detail);
				}
			}
		}

		// SEND EMAIL TO AUDIENCE
		$booking_id = $id;
		$getBooking 		= $this->Model_Api->getDataBookingById($booking_id); //
		if($getBooking ['error'] != null){
			$response = response("fail", array(), "Data not exist ");
			echo $response;	
			die();
		}
		$dataBooking = $getBooking['data'];
		$emailBooking = $dataBooking;
		$emailBooking['format_time_start'] 	= $this->Model_Admin->formatTime($waktu_timestart);
		$emailBooking['format_time_end'] 	= $this->Model_Admin->formatTime($waktu_timeend);
		$emailBooking['format_date'] 		= $this->Model_Admin->formatDate($tanggal_meeting);
		// MODULE EMAIL
		if(($modules['email']['is_enabled']-0) == 1 && $data['is_alive'] == 1){
			foreach ($dataToSend['internal'] as $key => $people) {
				// print_r($people);
				if($people['is_pic'] == 1){
					$pNotif = $this->Model_Notif->sendEmailPIC("invitation", $emailBooking, $people, $invitation_pic, 0); // untuk PIC/HOST/ORGANIZER

					$pNotif = $this->Model_Notif->sendEmailPIC("invitation", $emailBooking, $people, $invitation_pic, 1); // UNTUK ADMIN
				}else{
					$pNotif = $this->Model_Notif->sendEmailInternal("invitation", $emailBooking, $people, $invitation_pic);
				}
				
			}	
			// print_r($dataToSend['eksternal']);
			foreach ($dataToSend['eksternal'] as $key => $people) {
				// print_r($people);
				$pNotif = $this->Model_Notif->sendEmailExternal("invitation", $emailBooking, $people, $invitation_pic);
			}	
		}

		// MODULE NOTIFIKASI
		if($data['is_alive'] == 1){
			$this->Model_Notif->insertNotifAdmin(12, "Create meeting", $data['title']);
			$this->Model_Notif->insertNotifBatch($notifcollectdata);
			$notification_title = "Invitation Meeting of ".$meeting_title;
			$notification_body = $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end) . " at " .$room_name;

			$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$dataToSend['internal'],$type_notif,$notif_insert );
		}
		// 2022-10-13 // LOCKER MODULE
		if($modules['loker']['is_enabled'] == 1 && $data['is_alive']  == 1){
			$dataLockerSystem			= array();
			foreach ($dataEmailInternal as $vlo) {
				array_push($dataLockerSystem, $vlo['card_number']);
			}
			$dataLocker = $this->Model_Admin->querySql('SELECT * FROM locker WHERE 1=1 AND is_deleted=0 AND auto_reserve=1')->row_array();
			if(isset($dataLocker['name'])){
				foreach($dataLockerSystem as $noCard){
					$urlLockerSystem = $dataLocker['ip_locker']; // // http://192.168.1.14/lokerr/
					$this->Model_Admin->uploadDataToLockerSystem($urlLockerSystem,$noCard );
				}
			}
		}
		// APPROVALL
		if($data['is_alive'] == 0 && $data['is_approve'] == 0 && ($room['is_enable_approval']-0) == 1){
			$data_userapproval = $room['config_approval_user'];
			$list_userapproval = explode(",", $data_userapproval);
			if(count($list_userapproval) > 0){
				$data_listuserinterval 	= $this->Model_Admin->getDataEmployeeWhereInNik($list_userapproval);
				$data_listuserinterval  = $data_listuserinterval ['data'];
			}else{	
				$data_listuserinterval = [];
			}
			if($modules['email']['is_enabled'] == 1 ){
				foreach ($data_listuserinterval as $key => $people) {
					$this->Model_Notif->sendEmailApproval($emailBooking, $people, $invitation_pic);
				}	
			}
			$notification_title_approve = "Request Meeting ".$meeting_title;
			$notification_body_approve 	= $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end). " at " .$room_name;
			$this->Model_Notif->pushNotification($notification_title_approve,$notification_body_approve,$data_listuserinterval  ,$type_notif,$notif_insert );
		}
		$response = response("success", array(), "Success create a booking ".$post['title']);
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
	
}

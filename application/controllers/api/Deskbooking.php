<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Deskbooking extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Auth');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Api');
		$this->load->model('Model_Api2');
		$this->load->model('Model_Deskbooking');
		$this->load->model('Model_Access');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
		
	}


	public function getallschedule()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$getData = $this->Model_Deskbooking->getAllSchedule($post);
		$datalist = $getData['data'];
		$rule = $this->Model_Api->getGeneralSetting()['data'];
		foreach ($datalist as $key => $value) {
			$datalist[$key]['before_meeting'] = $rule['notif_unuse_before_meeting'];
		}
		if($getData['error'] == null ){
			$response = response("success", $datalist, "Success get data to schedule desk ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function getallscheduleDate()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// print_r($post);
		// die();
		$getData = $this->Model_Deskbooking->getAllScheduleDate($post);
		$datalist = $getData['data'];
		$rule = $this->Model_Api->getGeneralSetting()['data'];
		foreach ($datalist as $key => $value) {
			$datalist[$key]['before_meeting'] = $rule['notif_unuse_before_meeting'];
		}
		if($getData['error'] == null ){
			$response = response("success", $datalist, "Success get data to schedule desk ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
		
	}
	public function getListToday()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$getData = $this->Model_Deskbooking->getListToday($post);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to schedule ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a active ");
			echo $response ;
		}
	}


	public function getParticipant()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$booking_id 			= $post['booking_id'];
		$room_id = $this->uri->segment(7);
		

		$wh = array("bi.booking_id"=>$booking_id ,"internal"=>1,);
		$wheks = array("bi.booking_id"=>$booking_id ,"internal"=>0);
		$wh_await = array("bi.booking_id"=>$booking_id ,"attendance_status"=>0);
		$wh_accept = array("bi.booking_id"=>$booking_id ,"attendance_status"=>1);
		$wh_reject = array("bi.booking_id"=>$booking_id ,"attendance_status"=>2);


		$data_in = $this->Model_Api2->getDeskParticipantData($wh);
		$data_ek = $this->Model_Api2->getDeskParticipantData($wheks);
		$data_accept = $this->Model_Api2->getDeskParticipantData($wh_accept);
		$data_wait = $this->Model_Api2->getDeskParticipantData($wh_await);
		$data_reject = $this->Model_Api2->getDeskParticipantData($wh_reject);

		$num_partisipan = $data_in->num_rows() + $data_ek->num_rows();
		$resData = array(
			"total" => $num_partisipan,
			"internal" => $data_in->result_array(),
			"external" => $data_ek->result_array(),
			"accept" => $data_accept->num_rows(),
			"reject" => $data_reject->num_rows(),
			"wait" => $data_wait->num_rows(),
		);
		$response = response("success", $resData, "Success get data to participant ");
		echo $response ;	
	}
	
	public function getExtendTime()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$rawconfig = $this->Model_Api->getGeneralSetting();
		$config =$rawconfig ['data'];
		$date = $post ['date'];
		if(!isset($post ['time'])){
			$time =date("H:i:s");
		}else{
			$time =$post ['time'];

		}
		
		$pieceTime = $config['extend_count_time'];
		// print_r($pieceTime . " " .$config['extend_meeting_max']);
		if($config['extend_meeting'] == 1){
			$rawbooking = $this->Model_Deskbooking->getBookingInfo($post['booking_id']);
			$booking = $rawbooking['data'];
			$work_end = $booking['work_end'];
			$extend = $booking['extended_duration']-0;
			// print_r($booking );
			$max = $config['extend_meeting_max']-0;
			$collectcheck = array();
			if($max >= $pieceTime){
				$count = $max/$pieceTime;
				$end = $booking['end'];
				$end_with_extend = date($date.' H:i:s',strtotime($end . "+".$extend." minutes"));
				$select = "SELECT * FROM ( ";
				for($x = $pieceTime; $x <= $max; $x+=$pieceTime){
					$timeroom = $date ." ".$work_end;
					// $checknow 	= $date ." ".$time ;
					$convertTime = strtotime($end_with_extend);
					$aftersumnow = date($date.' H:i:s', strtotime("+".$x." minutes",$convertTime));
					// echo  $timeroom ." - ". $aftersumnow . " "  .  " - " . $end_with_extend . "<br/>"; 
					if(strtotime($aftersumnow) > strtotime($timeroom)){
						array_push($collectcheck, $x); //
					}
					$endtimeExtent = date('Y-m-d H:i:s',strtotime($end_with_extend . "+".$x." minutes"));
					if($max == $x){
						$select .= "SELECT CONCAT('".$x ."') as duration, COUNT(*) as book, TIME('".$endtimeExtent."') as time_data FROM desk_booking 
						WHERE end_early_meeting=0 
						AND is_alive = 1 
						AND room_id='".$booking['room_id']."' AND date='".$date."' 
						AND TIME('".$endtimeExtent ."') 
						BETWEEN TIME(start) AND TIME(DATE_ADD(end, INTERVAL extended_duration MINUTE)) ";
					}else{
						$select .= "SELECT CONCAT('".$x ."') as duration, COUNT(*) as book, TIME('".$endtimeExtent."') as time_data FROM desk_booking 

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
						if (array_search($value['duration'],$collectcheck) >= 0) {
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
					$response = response("fail", array() , "Extend time not available. ");
					echo $response ;
				}
			}else{
				$response = response("fail", array(), "Extend time not available. ");
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
			$json = file_get_contents("php://input");
			$post = json_decode($json, TRUE);
			// print_r($post );
			// die();
			$datetime = date("Y-m-d H:i:s");
			$getBooking = $this->Model_Deskbooking->getDataBookingById($post['booking_id']);
			$ex = $post['data']['duration']-0;
			$duration = $ex;
			$dataBooking = $getBooking['data'];
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
			$data = array(
				"extended_duration" => $ex,
			);

			$sql = "UPDATE desk_booking SET 
				extended_duration=extended_duration+".$ex.",
				cost_total_booking=".$reservation_cost." 
				WHERE booking_id='". $post['booking_id']."' ";


			// $booking_invoice = array(
			// 	"rent_cost" => $reservation_cost,
			// 	"alocation" => $dataBooking ['alocation_id'],
			// 	"time_before" => $datetime,
			// 	"updated_at" 	=> $datetime,
			// 	// "updated_by" 	=> $post['nik'],
			// );
			// die();
			// $winvoice 	= array("booking_id" => $dataBooking['booking_id']);
			$resp3 = $this->Model_Api->querySql($sql);
			// $udata 	= $this->Model_Api->updateData("booking_invoice", $booking_invoice, $winvoice);
			$response = response("success", array(), "The process of extend time is success");
			echo $response;
		}catch(Exeption $error){
			$response = response("fail", array(), "The process of extend time is failed");
			echo $response;
		}
		
	}

	// 
	public function getLoginQr()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$qr = $post['qr'];

		// $room = $post['room'];
		$auth = false;
		$dataperson = [];
		$typelogin = "";
		
		
		if($auth == false){
			$data_qr = $this->Model_Auth->checkAuthDeskBooking($qr);
			if($data_qr->num_rows() > 0){
				$typelogin = "Mobile QR";
				$dataperson = $data_qr->row_array();
				$auth = true;
			}
		}
		
		if($auth == false){
			$data_card = $this->Model_Auth->checkAuthDeskBookingCard($qr);
			if($data_card->num_rows() > 0){
				$typelogin = "Card Access";
				$auth = true;
				$dataperson = $data_card->row_array();
			}
		}

		if($auth == false){
			echo response("fail", [], "User doesn't exist");
			die();
		}else{
			$auth = true;
		}
		$username = $dataperson['username'];
		$access_passed = $this->Model_Access->getAccessDeskbooking($username);
		if($access_passed == false){
			echo response("fail", [], "User doesn't have access to this feature");
			die();
		}
		$res = $this->Model_Auth->checkLoginUsername($username);
		if($res['username']->num_rows()  <= 0){
			echo response("fail", [], "User doesn't exist");
			die();
		}

		$data = $res['username']->row_array();
		$data['typelogin'] = $typelogin;
		echo response("success", $data, "Get success");
		
	}

	public function getRoom()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$room = $post['room'];
		$res = $this->Model_Deskbooking->getRoomData($room);
		echo response("success", $res->result_array(), "Get success");
		
	}
	public function getDeskDataBuildingRoom()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$building_id = $post['id'];
		$w = array("is_deleted" => 0);
		$q = "SELECT r.*, r.id room_id ,b.name building_name 
		FROM desk_room r
		INNER JOIN building b ON r.building_id=b.id
		WHERE (r.is_deleted=0 AND b.is_deleted=0) AND b.id =".$building_id." 
		ORDER BY r.name ASC";
		$res = $this->Model_Api->querySql($q);
		echo response("success", $res->result_array(), "Get success");
	}
	public function getDataBuildingRoom()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$building_id = $post['id'];
		$w = array("is_deleted" => 0);
		$q = "SELECT r.*,r.radid room_id ,b.name building_name FROM room r
		INNER JOIN building b ON r.building_id=b.id
		WHERE (r.is_deleted=0 AND b.is_deleted=0) AND b.id =".$building_id." 
		ORDER BY r.name ASC";
		$res = $this->Model_Api->querySql($q);
		echo response("success", $res->result_array(), "Get success");
	}
	public function getPointerActiveByRoomAndDateNow()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$room_id = $post['room_id'];
		$date = $post['date'];
		$time = $post['time'];
		$whereTable = array(
			"r.id" => $room_id,
		);
		$data = $this->Model_Deskbooking->getDataRoomDeskTable($whereTable)['data'];
		$res = $this->Model_Deskbooking->getDataBookingByRoomNow($room_id, $date, $time)['data'];
		foreach ($data as $key => $value) {
			$active = false;
			$dataOtherTime = $this->Model_Deskbooking->getDataDeskBookingOtherTime($room_id, $date, $value['desk_id'],$time)['data'];
			
			$data[$key]['other'] = $dataOtherTime ;
			foreach ($res as $kr => $kv) {
				if($kv['desk_id'] ==  $value['desk_id']){
					$active = true;
					$data[$key]['active'] = true;
					break;
				}
			}
			if($active == false){
				$data[$key]['active'] = false;
			}

		}

		echo response("success",$data , "Get success");
	}

	public function getDeskBookTime()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date = @$post['date'];
		$zone = @$post['zone'];
		$room = @$post['room'];
		$desk = @$post['desk'];
		$pick = @$post['pick'];
		$time = @$post['time'];

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
						WHERE b.date='".$date."' AND b.room_id='".$room_id."' AND b.desk_id='".$desk."'  AND b.is_alive = 1 
						AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))";
				}else{
					$sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
						COALESCE(b.room_id, '".$room_id."') room_id, 
						COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
						FROM desk_booking b 
						LEFT JOIN desk_room r ON b.room_id=r.id  
						WHERE b.date='".$date."' AND b.room_id='".$room_id."' AND b.desk_id='".$desk."' AND b.is_alive = 1 
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

	public function getRescheduleDeskBookTime()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// echo "<pre>";
		// $post = $_POST;
		$date = @$post['date'];
		
		$desk = $post['desk'];
		$pick = $post['pick'];
		$time = $post['time'];
		$booking_id = $post['booking_id'];
		$room_id = $post['room'];
		$wdesk 	= [
			'rt.desk_id' => $desk,
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

	// 
	// 
	// BOOKINGGGG
	// 
	// 
	public function checkTodayBooking()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		// $date 			= date('Y-m-d');
		$date 			= $post['date'];
		$sst 			= $post['time'];
		$meetingRoom 	= array();
		$timearray 		= array();
		// $dataRoom 		= $this->Model_Admin->getDataRoom()['data'];

		$dayNameNum = date("w", strtotime($date));
		$dayName = getDayName($dayNameNum);
		$whreRoomString = "  r.work_day LIKE '%".$dayName."%' ";
		$dataRoom 		= $this->Model_Deskbooking->getDataRoomDeskBooking($whreRoomString)['data'];
		$settingGeneral	= $this->Model_Api->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60

		$timearray	= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		foreach ($dataRoom  as $k => $v) {
			// $dataRoom[$k]['datatime'] = $dataTimeArray;
			$dataRoom[$k]['setting'] = $dataSettingGeneral;
		}
		$timearray = $timearray['data'];
		$lenTime = count($timearray)-1;
		
		echo response("success", $dataRoom, "Get success");
		die();
	}

	public function checkPickBooking()
	{
		// $this->output->set_content_type('application/json');
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date 			= $post['date'];
		$meetingRoom 	= array();
		$timearray 		= array();
		$dayNameNum = date("w", strtotime($date));
		$dayName = getDayName($dayNameNum);
		$whreRoomString = "  r.work_day LIKE '%".$dayName."%' ";
		$dataRoom 		= $this->Model_Deskbooking->getDataRoomDeskBooking($whreRoomString)['data'];
		$settingGeneral	= $this->Model_Api->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		// die();
		$timearray	= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}
		foreach ($dataRoom  as $k => $v) {
			// $dataRoom[$k]['datatime'] = $dataTimeArray;
			$dataRoom[$k]['setting'] = $dataSettingGeneral;
		}
		$timearray = $timearray['data'];
		$lenTime = count($timearray)-1;
		
		echo response("success", $dataRoom, "Get success");
		die();
	}

	public function checkDataTime()
	{
		// echo "<pre>";
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date = @$post['date'];
		$start = $date." ".$post['start'].":00";
		$end = $date." ".$post['end'].":00";
		// $sql = "CALL check_booking(?,?,?)";
		$meetingRoom = array();
		$dataRoom = $this->Model_Deskbooking->getDataRoomDeskTable();
		if($dataRoom['error'] == null){
			foreach ($dataRoom['data'] as $key => $value) {
				
				$dataX = $this->Model_Deskbooking->checkDeskBookingRoom($date, $start, $end, $value['desk_room_id'],$value['desk_id']);
				if($dataX['error'] == null){
					$ar = array(
						"room_id" => $value['desk_room_id'],
						"desk_id" => $value['desk_id'],
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
			echo response("fail", array(), "Data meeting ");
		}
		// if($c
	}

	public function getDataRoom()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$room_id = $post['room_id'];
		$wzone 	= [
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
			$dataZone[$k]['desk'] = $dataDesk 	;
		}
		echo response("success", $dataZone, "Get success");
	}

	public function getDataDeskRoomById()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$room_id = $post['room_id'];
		$wzone 	= [
			'r.id' => $room_id,
		];
		$dataZone 		= $this->Model_Deskbooking->getDataRoom2($wzone)['data'];
		echo response("success", $dataZone, "Get success");
	}


	public function postEndMeetingMobile()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$datetime = date("Y-m-d H:i:s");


		$ddd = $datetime;
		$getBooking 		= $this->Model_Deskbooking->getDataBookingById($post['booking_id']);
		$dataBooking 		= $getBooking['data'];
		$getBookingInv 		= $this->Model_Admin->getDataBookingInvById($post['booking_id'])['data'];
		$room_name 			= $dataBooking['room_name'];
		$notifcollectdata 	= array();
		// die();
		$datarow = array(
			"end_early_meeting" => 1,
			"updated_at" => $datetime,
			"updated_by" => $post['nik'],
			"is_alive" => 4,
			"status" => "expired",
			"early_ended_by" => $post['nik'],
			"early_ended_at" => $datetime ,
			"text_early" => "By Mobile Apps",
		);
		$whereAr = array(
			"booking_id" => $post['booking_id']
		);
		$getData = $this->Model_Api->updateData('desk_booking',$datarow, $whereAr);
		foreach ($getBookingInv as $val) {
			if($val['internal'] == 1){
				// only internal
				$_notif 					= array();
				$_notif['datetime'] 		= $datetime;
				$_notif['nik'] 				= $val['nik']; // user id
				$_notif['type'] 			= 1; // booking is 1
				$_notif['value'] 			= $post['booking_id']; // booking id
				$_notif['title'] 			= "End Desk Booking";
				$_notif['body'] 			= $dataBooking['title'] ." - ". getformatDate($ddd);
				$_notif['is_sending'] 		= 0;
				$_notif['is_deleted'] 		= 0;
				$_notif['created_at'] 		= $datetime;
				if($val['is_pic'] == 1){
					$_notif['title'] 			= "End Desk Booking";
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
				$notification_title = "End Desk Booking of ".$meeting_title;
				$notification_body 	= $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end). " at " .$room_name;
				$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notifcollectdata);
		}
		$response = response("success", array(), "Success data to end meeting ");
		echo $response ;	
	}
	
	public function postCancelBooking()
	{
		$post =$_POST;
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// echo "<pre>";
		$getBooking 		= $this->Model_Deskbooking->getDataBookingById($post['booking_id']);
		$sqlNotif 			= "SELECT * FROM sending_notif WHERE booking_id='".$post['booking_id']."' ";
		$sqlEmail 			= "SELECT * FROM sending_email WHERE booking_id='".$post['booking_id']."' ";
		$booking_id 		= $post['booking_id'];
		
		$noteReason = isset($post['note']) ? $post['note'] : "";
		if($getBooking ['error'] == null){
			$datetime 					= date('Y-m-d H:i:s');
			$dataBooking = $getBooking['data'];
			

			$dataBooking['is_expired'] = 0;
			$dataBooking['is_rescheduled'] = 0;
			$dataBooking['is_canceled'] = 1;
			
			$dataBooking['is_alive'] = 2;
			$dataBooking['status'] = 'cancel';
			$dataBooking['canceled_by'] = $post['nik'];
			$dataBooking['canceled_at'] = $datetime;

			$dataBooking['updated_at'] = $datetime;
			$dataBooking['updated_by'] = $post['nik'];
			$where = array(
				 "booking_id"=>$dataBooking['booking_id']
			);

			$room_name = $dataBooking['room_name'];
			$dataBooking['canceled_note'] = $noteReason;

			unset($dataBooking['room_name']);
			unset($dataBooking['price']);
			unset($dataBooking['booking_id']);
			unset($dataBooking['room_description']);
			unset($dataBooking['room_location']);
			unset($dataBooking['room_capacity']);
			unset($dataBooking['room_google_map']);
			unset($dataBooking['building_name']);
			unset($dataBooking['room_google_map']);
			unset($dataBooking['building_detail_address']);
			unset($dataBooking['building_google_map']);

			// print_r($dataBooking);

			$meeting_title = $getBooking['data']['title'];
			$meeting_date = $getBooking['data']['date'];
			$explodeS = explode(" ", $getBooking['data']['start']);
			$explodeE = explode(" ", $getBooking['data']['end']);
			$meeting_start = $explodeS[1];
			$meeting_end = $explodeE[1];
			$notification_title = "Cancel Desk Booking of ".$meeting_title;

			$udata 				= $this->Model_Api->updateData("desk_booking", $dataBooking, $where);
			try{
				$tableNotif			= $this->Model_Api->querySql($sqlNotif)->result_array();
				$tableEmail			= $this->Model_Api->querySql($sqlEmail)->result_array();
				if(count($tableNotif) > 0){
					$tableNotif			= $this->Model_Api->querySql($sqlNotif)->result_array()[0];
					$wh1 = array(
						 "booking_id"=>$tableNotif['booking_id']
					);
					$tableNotif['type']	= 3;
					$tableNotif['is_status']	= 3;
					$tableNotif['is_deleted']	= 0;
					unset($tableNotif['id']);
					$u1 			= $this->Model_Api->updateData("sending_notif", $tableNotif, $wh1);
					$notification_data 	= json_decode($tableNotif['batch'], true);
					// print_r($getBooking );
					// die();
					
					// $room_name = $room['name'];
					$notification_body = $this->formatDate($meeting_date) . " " .$this->formatTime($meeting_start) ."-". $this->formatTime($meeting_end) . " at " .$room_name;
					$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$notification_data );
					$type_notif_admin = 12; // booking
					$this->Model_Notif->insertNotifAdminApi($type_notif_admin, "Cancel Desk Booking", $meeting_title, $post['nik'] );
				}
				if(count($tableEmail) > 0){
	                $tableEmail 	= $tableEmail[0];
					$getBookingEmail        = $this->Model_Deskbooking->getDataBookingById($booking_id); //
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
	                    $pNotif = $this->Model_Notif->sendEmailInternal("cancel", $emailBooking, $people);
	                }   
	                foreach ($dataToSend['eksternal'] as $key => $people) {
	                    $pNotif = $this->Model_Notif->sendEmailExternal("cancel", $emailBooking, $people);
	                } 
					
				}
			}catch(Exception $e){

			}
			$response 			= response("success", array(), "Success cancel a desk booking ");
			echo $response;
		}else{
			echo response("fail", array(), "Data of desk booking not found");
		}
	}

	public function postReBook()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
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

		// print_r($dataBooking);
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
			unset($dataBooking['building_google_map']);
			// unset($dataBooking['room_google_map']);
			$udata 				= $this->Model_Admin->updateData("desk_booking", $dataBooking, $where);
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

	private function getStatusInvoiceName()
	{
		$data 		= $this->Model_Admin->getInvStatusName();
		if($data['error'] == null){
			return  $data['data'];
		}else{
			return  array();
		}
	}
	public function postBook()
	{
		include APPPATH.'third_party/phpqrcode/qrlib.php';
		$tmpdir = "assets/qr/";

		$statusInvoice 				= $this->getStatusInvoiceName();
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$datetime 					= date('Y-m-d H:i:s');
		$randoom_id					= random_string('numeric', 10);
		$id 						= $randoom_id;
		$invoice_id 				= $randoom_id;
		$internal  					= isset($post['partisipant']) ? $post['partisipant'] : array();
		$settingGeneral				= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];
		$room_id 					= $post['room'];
		$zone_id 					= $post['zone'];
		$desk_id 					= $post['desk'];
		$book_pic					= isset($post['pic']) ? $post['pic'] :'' ;
		$resPIC 					= $this->Model_Admin->getEditEmployee($book_pic);
		if($resPIC['error'] != null){
			$response = response("fail", array(), "User not exits, please logout and try again");
			echo $response;
			die();
		}
		if($resPIC['data'] == null){
			$response = response("fail", array(), "User not exits, please logout and try again");
			echo $response;
			die();
		}
		$getDataPIC 				= $resPIC['data'];
		$alocation_id 				= $getDataPIC ['department_id'];
		$res_alocation				= $this->Model_Admin->checkBookingAlocationData($alocation_id); // id
		$alocation 					= $res_alocation['data'];

		$Q_room						= "SELECT * FROM desk_room WHERE id=".$room_id." ";
		$room 			 			= $this->Model_Admin->querySql($Q_room)->result_array();
		$wdesk 		= [
			'rt.zone_id' => $zone_id,
			'd.desk_room_id' => $room_id,
			'rt.desk_id' => $desk_id ,
		];
		// print_r($wdesk );
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
		// print_r($desk_name);
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
		$cost						= $room['price']; // per hours
		$getHoursMeeting 			= floor($duration / $fHour);
		$checkHours 				= fmod($duration,$fHour);
		if($checkHours > 0){
			$getHoursMeeting += 1;
		}
		$reservation_cost 			= $cost	* $getHoursMeeting;
		// $nikPic						= $post['pic'];
		
		// $resPIC 					= $this->Model_Admin->getEditEmployee($nikPic);
		// $getDataPIC 				= $resPIC['data'];
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
		$ruangan = $room['id'];
		$this->Model_Deskbooking->checkKondisiBookingPerRuangan($ruangan, $desk_id, $tanggal_meeting, $waktu_mulai,$waktu_akhir);
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
		$data['room_id'] 			= $room['id'];
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
		$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$dataToSend['internal'],$type_notif,$notif_insert );
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
			$pNotif = $this->Model_Notif->sendDeskEmailInternal("invitation", $emailBooking, $people);
		}	
		$response = response("success", array(), "Success create a desk booking ".$title_desk);
		echo $response;
	}

	public function postBookKiosk()
	{
		include APPPATH.'third_party/phpqrcode/qrlib.php';
		$tmpdir = "assets/qr/";

		$statusInvoice 				= $this->getStatusInvoiceName();
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$datetime 					= date('Y-m-d H:i:s');
		$randoom_id					= random_string('numeric', 10);
		$id 						= $randoom_id;
		$invoice_id 				= $randoom_id;
		$internal  					= isset($post['partisipant']) ? $post['partisipant'] : array();
		$settingGeneral				= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral 		= $settingGeneral['data'];
		$room_id 					= $post['room'];
		$zone_id 					= $post['zone'];
		$desk_id 					= $post['desk'];
		$book_pic					= isset($post['pic']) ? $post['pic'] :'' ;
		$resPIC 					= $this->Model_Admin->getEditEmployee($book_pic);


		if($resPIC['error'] != null){
			$response = response("fail", array(), "User not exits, please logout and try again");
			echo $response;
			die();
		}
		if($resPIC['data'] == null){
			$response = response("fail", array(), "User not exits, please logout and try again");
			echo $response;
			die();
		}
		$getDataPIC 				= $resPIC['data'];
		$alocation_id 				= $getDataPIC ['department_id'];
		$res_alocation				= $this->Model_Admin->checkBookingAlocationData($alocation_id); // id
		$alocation 					= $res_alocation['data'];

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
		// print_r($dataDeskItem);
		$title_desk 				= "Book a Desk in ".$dataDeskItem['zone_name']." - Desk No." .$dataDeskItem['block_number'];
		$desk_name					= $dataDeskItem['zone_name']." - Desk No." .$dataDeskItem['block_number'];
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
		$cost						= $room['price']; // per hours
		$getHoursMeeting 			= floor($duration / $fHour);
		$checkHours 				= fmod($duration,$fHour);
		if($checkHours > 0){
			$getHoursMeeting += 1;
		}
		$reservation_cost 			= $cost	* $getHoursMeeting;
		// $nikPic						= $post['pic'];
		
		// $resPIC 					= $this->Model_Admin->getEditEmployee($nikPic);
		// $getDataPIC 				= $resPIC['data'];
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
		$ruangan = $room['id'];
		$this->Model_Deskbooking->checkKondisiBookingPerRuangan($ruangan, $desk_id, $tanggal_meeting, $waktu_mulai,$waktu_akhir);
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
		$data['room_id'] 			= $room['id'];
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
		$pNotif = $this->Model_Notif->pushNotification($notification_title,$notification_body,$dataToSend['internal'],$type_notif,$notif_insert );
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
			$pNotif = $this->Model_Notif->sendDeskEmailInternal("invitation", $emailBooking, $people);
		}	
		$response = response("success", array(), "Success create a desk booking ".$title_desk);
		echo $response;
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

	public function mydeskbooking()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		
		$username = isset($post['username']) ? $post['username'] : (isset($post['nik']) ? $post['nik'] : null);
		if (!$username) {
			echo response("fail", [], "Username/NIK is required");
			return;
		}

		$res = $this->Model_Auth->checkLoginUsername($username);
		if($res['username']->num_rows() <= 0){
			echo response("fail", [], "User doesn't exist");
			return;
		}
		
		$user_data = $res['username']->row_array();
		$nik = isset($user_data['nik']) ? $user_data['nik'] : (isset($user_data['username']) ? $user_data['username'] : $username);
		
		$date = isset($post['date']) ? $post['date'] : null;
		$status = isset($post['status']) ? $post['status'] : null;
		
		$where = [];
		if ($status) {
			$where['b.status'] = $status;
		}
		
		if (isset($post['room_id'])) {
		    $where['b.room_id'] = $post['room_id'];
		}

		$date1 = $date;
		$date2 = $date;

		$getData = $this->Model_Deskbooking->getDataBookingByNik($date1, $date2, $nik, $where);
		
		if($getData['error'] == null ){
			echo response("success", $getData['data'], "Success get my desk booking");
		}else{
			echo response("fail", $getData, "Failed to get desk booking");
		}
	}

	public function activityLog()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		
		$username = isset($post['username']) ? $post['username'] : (isset($post['nik']) ? $post['nik'] : null);
		if (!$username) {
			echo response("fail", [], "Username/NIK is required");
			return;
		}

		$res = $this->Model_Auth->checkLoginUsername($username);
		if($res['username']->num_rows() <= 0){
			echo response("fail", [], "User doesn't exist");
			return;
		}
		
		$user_data = $res['username']->row_array();
		
		// Ensure it's an employee
		$is_employee = false;
		if (isset($user_data['level']) && strtolower($user_data['level']) == 'employee') {
		    $is_employee = true;
		} else if (isset($user_data['role']) && strtolower($user_data['role']) == 'employee') {
		    $is_employee = true;
		} else if (isset($user_data['type']) && strtolower($user_data['type']) == 'employee') {
		    $is_employee = true;
		} else if (isset($user_data['nik']) && !empty($user_data['nik'])) {
		    $is_employee = true;
		}
		
		if (!$is_employee) {
		    echo response("fail", [], "Only employees can access activity logs");
			return;
		}
		
		$nik = isset($user_data['nik']) ? $user_data['nik'] : $username;

		$this->load->model('Model_ActivityLog');
		$filters = [];
		if (isset($post['date'])) {
			$filters['start_date'] = $post['date'];
			$filters['end_date'] = $post['date'];
		}
		if (isset($post['action'])) {
		    $filters['action'] = $post['action'];
		}
		$filters['actor_nik'] = $nik;
		
		$logs = $this->Model_ActivityLog->get_logs(100, null, $filters);
		
		echo response("success", $logs, "Success get activity log");
	}

}
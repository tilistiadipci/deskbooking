<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class PlaceRoom extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Menu');
		$this->load->model('Model_Place');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Notif');
		$this->load->model('Model_Api');
		$this->load->model('Model_License');
		$this->load->model('Model_Booking');
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
		$building               = $this->Model_Admin->getDataBuilding()['data'];
        $room                   = $this->Model_Admin->getDataRoom2()['data'];
        // $getEmployee 			= $this->Model_Admin->getDataEmployee()['data'];
		$menuheaderID = "MH0001";
		$pagename = "Room Place";

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
		$employee = $data = $this->Model_Admin->getDataEmployee();
		$settinggeneral = $this->Model_Admin->getSettingDataGeneral()['data'];
		// $invoicename= $this->getInvoiceStatusZZZZ();

		$menu = $this->Model_Menu->getMenuHeader("MH0001");
		$this->load->view('Place/room', 
				array(
					'menuheader_'	=> $menu, 
					'modules'		=>$modules,
					'pagename' 		=> $pagename, 
					// 'invoice' 		=> json_encode($invoicename), 
					'category' 		=> json_encode($room_for_usage), 
					'employee'		=> $employee['data'], 
					'settinggeneral'=> json_encode($settinggeneral),
					'building'      => json_encode($building),
                	'room'          => json_encode($room),
                	// 'organizer' 	=> json_encode($getEmployee),
				)
			);
	}

	public function getDataFloorRoom(){
		$data = $this->privGetDataFloorRoom();
		$response = response("success", $data, "Success get floor filter");
		echo $response;
	}
	public function getDataFacilityRoom(){
		$result = $this->Model_Admin->getDataFacility();
		$data =$result['data'];
		$response = response("success", $data, "Success get facility filter");
		echo $response;
	}
	private function privGetDataFloorRoom($filter = [])
	{
		$result = $this->Model_Place->getFloorPlaceData($filter);
		$resultBuilding = $this->Model_Admin->getDataBuilding([]);
		$resultBuilding = $resultBuilding['data'];
		$data = $result;
		foreach ($resultBuilding as $kb => $vb) {
			$floor = [];
			foreach ($data as $kr => $vr) {
				if($vr['building_id'] == $vb['id'] ){
					array_push($floor, $vr);
				}
			}
			$resultBuilding[$kb]['floor'] = $floor;
		}
		return $resultBuilding;
	}
	private function privGetDataRoom($filter = [])
	{
		
		$modules['room_adv'] = $this->Model_Module->get_module_room_adv();
		$modules_room_adv_enabled = $modules['room_adv']['is_enabled'] -0;
		
		
		$result = $this->Model_Place->getRoomPlaceData($filter);
		// print_r($result);
		$data = [];
		$user = $this->session->userdata('user-nya');
		$level = $this->session->userdata('levelid-nya');
		if($modules_room_adv_enabled == 1){ //
			foreach ($result as $key => $value) {
				if($value['is_config_setting_enable'] == 1 && $value['is_enable_permission'] == 1){
					if($level == 1 || $level == 6){ // admin & frontdesk
						array_push($data, $value);
					}else{ // $level 2
						$user_permission = $value['config_permission_user'];
						$user_permission_list = explode(",", $user_permission);
						if (in_array($user, $user_permission_list)) { 
							array_push($data, $value);
							continue;
						}
					}
				}else{
					array_push($data, $value);
				}

			}
		}else{
			$data = $result;
		}
		$r = $data;
		return $r;
	}

	public function getFilterCalendarRoom()
	{
		
		$modules['room_adv'] = $this->Model_Module->get_module_room_adv();
		$modules_room_adv_enabled = $modules['room_adv']['is_enabled'] -0;
		$filter = $_GET['filter'];
		$date_start_cari = $_GET['date_start_cari'];
		$date_end_cari = $_GET['date_end_cari'];
		$capacity_min_cari = $_GET['capacity_min_cari'];
		$capacity_max_cari = $_GET['capacity_max_cari'];
		$facility_cari = $_GET['facility_cari'];
		$filter_rooms =  $_GET;
		$data_room = $this->privGetDataRoom($filter_rooms);
		foreach ($data_room as $key => $value) {
			$filter_rooms['room_cari'] = $value['radid'];
			$data_room[$key]['booking'] = $this->Model_Booking->getBookingScheduleByRoom($filter_rooms);
		}
		$timezone = APP_GMT;
		if(isset($_GET['timezone'])){
			$timezone = $_GET['timezone'];
			date_default_timezone_set($timezone);
		}else{
			date_default_timezone_set(APP_GMT);
		}
		
		$response = response("success", $data_room, "Success get calendar filter");
		echo $response;
		die();
	}

	public function getTimeBookByRoom(){
		$date = @$_GET['date'];
		$nowdate = date('Y-m-d');
		$roomid = @$_GET['roomid'];
		$time 	= @$_GET['time'] ?? date('H:i:s');
		$dayNameNum = date("w", strtotime($date));
		$dayName = getDayName($dayNameNum);
		$whreRoomString = "  r.work_day LIKE '%".$dayName."%' AND radid=".$roomid." ";
		$dataRoom 		= $this->Model_Admin->getDataRoom($whreRoomString)['data'];
		$settingGeneral	= $this->Model_Admin->getSettingDataGeneral();
		$dataSettingGeneral = $settingGeneral['data'];
		$numLoop		= $dataSettingGeneral['duration'] == 60 ? 24 : 48; // 48 loop for 30 mins
		$setDuration	= $dataSettingGeneral['duration']; // 30 or 60
		$timearray	= $this->Model_Admin->getTimeSchedule($setDuration);
		if($timearray['error'] != null){
			echo response("fail", array(), "Duration time fail to load");
			die();
		}	
		$timearray 	= $timearray['data'];
		$lenTime 	= count($timearray)-1;
		$sqlgettime = $this->Model_Booking->makeQueryGetTimeData($timearray, $date, $roomid, $lenTime);
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
		$selDateDiff = strtotime($date);
		$nowDateDiff = strtotime($nowdate);
		if($date == $nowdate){
			foreach ($dataTimeArray as $key => $value) {
				$nowtime = strtotime($date . " ".$time);
				$bookingtime = strtotime($date . " " . $value['time_array']);
				if( $nowtime  > $bookingtime){
					$dataTimeArray[$key]['book'] = 1;
				}
			}
		}
		$response = response("success", $dataTimeArray, "Success get time book");
		echo $response;
		// echo $date . " ".$time;
		die();
	}
}

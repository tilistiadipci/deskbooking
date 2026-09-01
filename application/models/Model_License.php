<?php  
date_default_timezone_set("Asia/Jakarta");
class Model_License extends CI_Model {
	public function __construct(){
		parent::__construct();
		$this->load->helper('response');
	}

	
	public function getLicenseSetting(){
		try{
			$ar = array(
				
			);
			$data = $this->db->select('*')
					->from("license_setting")
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
	public function getLicenseList(){
		try{
			$ar = array(
				
			);
			$data = $this->db->select('*')
					->from("license_list")
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

	public function getLicenseData($ar = []){
		try{
			$data = $this->db->select('*')
					->from("license_list")
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


	public function checkRoomModuleLicense(){
		$ar = array(
				"module" =>"module_room"
			);
		$data = $this->db->select('*')
					->from("license_list")
					->where($ar)
					->get();
		$fetch = $data->row_array();

		$dataroom = $this->db->select('r.*, ra.name as ra_name, ra.id as ra_id, 
					b.name as building_name,
					b.detail_address as building_detail,
					b.google_map as building_google_map
					')
					->from("room r")
					->join("room_automation ra", "r.automation_id=ra.id", 'left')
					->join("building b", "r.building_id=b.id", 'left')
					->where(["r.is_deleted" => 0])
					
					->order_by("name", "ASC")
					->get();
		$countold = $dataroom->num_rows();
		$countnew = ($countold - 0)+1;

		if($fetch['status'] == 1 || fetch['status'] == "1"){
			$qty = $fetch['qty'] - 0;
			if($countnew > $qty){
				$response = response("fail", array(), "Failed Room has max limited, please check your license");
				echo $response;
				die();
			}
		}else{
			$response = response("fail", array(), "Failed Room has limited access, please check your license");
			echo $response;
			die();
		}
	}
	public function checkDisplayModuleLicense(){
		$ar = array(
				"module" =>"module_display"
			);
		$data = $this->db->select('*')
					->from("license_list")
					->where($ar)
					->get();
		$fetch = $data->row_array();

		$datadisplay = $this->db->select('rd.*, r.name as room_name')
					->from("room_display rd")
					->join("room r", "rd.room_id=r.radid")
					->where(["r.is_deleted" => 0, "rd.is_deleted" => 0])
					->order_by('r.id', 'ASC')
					->get();
		$countold = $datadisplay->num_rows();
		$countnew = ($countold - 0)+1;

		if($fetch['status'] == 1 || fetch['status'] == "1"){
			$qty = $fetch['qty'] - 0;
			if($countnew > $qty){
				$response = response("fail", array(), "Failed Display has max limited, please check your license");
				echo $response;
				die();
			}
		}else{
			$response = response("fail", array(), "Failed Display has limited access, please check your license");
			echo $response;
			die();
		}
	}
	
	public function get365Integration(){
		$data = $this->db->select('*')
					->from("integration_365")
					->get();
		$r = $data->row_array();
		return $r;
	}
	public function roomSystemToIntegration($roomid,$type){
		$r = [];
		if($type == "365"){
			$data = $this->db->select('r.*, rs.radid')
					->from("room_365 r")
					->join("room rs", "r.id=rs.config_microsoft")
					->where(["r.is_deleted" => 0,"r.initial" => 1])
					->where(["rs.radid" => $roomid, ])
					->order_by('r.displayName', 'ASC')
					->get();
			$r = $data->row_array();
		}else if($type == "google"){

		}
		
		return $r;
	}

	public function roomIntegration($type){
		$r = [];
		if($type == "365"){
			$data = $this->db->select('r.*')
					->from("room_365 r")
					->where(["r.is_deleted" => 0])
					->order_by('r.displayName', 'ASC')
					->get();
			$r = $data->result_array();
		}else if($type == "google"){

		}
		
		return $r;
	}


	// 
	public function check365Data(){
		$ckLicense = $this->getLicenseData(['module' => 'module_int_365']);
		$ms365 = $this->get365Integration();
		if($ms365['status'] == 1){
			return true;
		}else{
			return false;
		}
	}

	public function createEvent365($databook,$room, $ms365_ , $inv_internal,$inv_external){
		$ex = MS_365_CREATEEVENT_BODY;
		$body = json_decode($ex,TRUE);
		$accessToken = $ms365_['access_token'];
		$userPrincipalName = $ms365_['userPrincipalName'];
		$ms365Room = $this->Model_License->roomSystemToIntegration($room['radid'], '365');
		if($room['config_microsoft'] == ""  ){
			return ['error'=> "not microsoft room"];
		}
		if($room['config_microsoft'] == null  ){
			return ['error'=> "not microsoft room"];
		}
		$TZ = date_default_timezone_get();
		$ISO8601U = "Y-m-d\TH:i:s.uO";
		$start = date($ISO8601U,strtotime($databook['start']));
		$end = date($ISO8601U,strtotime($databook['end']));
		if(!isset($ms365Room['id'])){
			return ['error'=> "id microsoft room not found/empty"];
		}
		$attendees = [];
		foreach ($inv_internal as $k => $value) {
			$datt = [
				'emailAddress' => [
					'address' => $value['email'],
					'name' => $value['name'],
				],
				'type' => 'required',
			];
			if(isset($value['is_pic'])){
				if($value['is_pic'] == 1){
					$body ['organizer']['emailAddress']['name'] = $value['name'];
					$body ['organizer']['emailAddress']['address'] = $value['email'];
				}
				
			}
			array_push($attendees, $datt);
		}
		foreach ($inv_external as $k => $value) {
			$datt = [
				'emailAddress' => [
					'address' => $value['email'],
					'name' => $value['name'],
				],
				'type' => 'required',
			];
			array_push($attendees, $datt);
		}
		$dattroom = [
				'emailAddress' => [
					'address' => $ms365Room['emailAddress'],
					'name' => $ms365Room['displayName'],
				],
				'type' => 'Resource',
		];
		array_push($attendees, $dattroom);

		$body ['subject'] = $databook['title'];
		$body ['location']['displayName'] = $databook['room_name'];
		$body ['attendees'] = $attendees;
		$body ['start']['dateTime'] = $start;
		$body ['start']['timeZone'] = $TZ;
		$body ['end']['dateTime'] = $end;
		$body ['end']['timeZone'] = $TZ;
		$body ['originalStartTimeZone'] = $TZ;
		$body ['originalEndTimeZone'] = $TZ;

		// print_r($body);
		// die();
		$urlpath = MS_365_GRAPH.$userPrincipalName.MS_365_GRAPH_PATH_EVENT;

		$authorization = "Bearer ".$accessToken;
		$headers  = [
            'Authorization: '.$authorization,
            'Content-Type: application/json'
        ];
        $res = $this->send365Event($urlpath,$headers,$body );
		return $res;

	}
	public function rescheduleEvent365($databook,$room, $ms365_ , $inv_internal,$inv_external){
		$ex = MS_365_CREATEEVENT_BODY;
		$body = json_decode($ex,TRUE);
		$accessToken = $ms365_['access_token'];
		$userPrincipalName = $ms365_['userPrincipalName'];
		$ms365Room = $this->Model_License->roomSystemToIntegration($room['radid'], '365');
		if($room['config_microsoft'] == ""  ){
			return ['error'=> "not microsoft room"];
		}
		if($room['config_microsoft'] == null  ){
			return ['error'=> "not microsoft room"];
		}

		if($databook['booking_id_365'] == null ||   $databook['booking_id_365'] == ""){
			return ['error'=> "not sync microsoft room"];
		}
		$booking_id_365 = $databook['booking_id_365'];
		$TZ = date_default_timezone_get();
		$ISO8601U = "Y-m-d\TH:i:s.uO";
		$start = date($ISO8601U,strtotime($databook['start']));
		$end = date($ISO8601U,strtotime($databook['end']));
		if(!isset($ms365Room['id'])){
			return ['error'=> "id microsoft room not found/empty"];
		}
		$attendees = [];
		foreach ($inv_internal as $k => $value) {

			$datt = [
				'emailAddress' => [
					'address' => $value['email'],
					'name' => $value['name'],
				],
				'type' => 'required',
			];
			if(isset($value['is_pic'])){
				if($value['is_pic'] == 1){
					$body ['organizer']['emailAddress']['name'] = $value['name'];
					$body ['organizer']['emailAddress']['address'] = $value['email'];
				}
				
			}
			array_push($attendees, $datt);
		}
		foreach ($inv_external as $k => $value) {
			$datt = [
				'emailAddress' => [
					'address' => $value['email'],
					'name' => $value['name'],
				],
				'type' => 'required',
			];
			array_push($attendees, $datt);
		}
		$dattroom = [
				'emailAddress' => [
					'address' => $ms365Room['emailAddress'],
					'name' => $ms365Room['displayName'],
				],
				'type' => 'Resource',
		];
		array_push($attendees, $dattroom);
		$body ['subject'] = $databook['title'];
		$body ['location']['displayName'] = $databook['room_name'];
		$body ['attendees'] = $attendees;
		$body ['start']['dateTime'] = $start;
		$body ['start']['timeZone'] = $TZ;
		$body ['end']['dateTime'] = $end;
		$body ['end']['timeZone'] = $TZ;
		$body ['originalStartTimeZone'] = $TZ;
		$body ['originalEndTimeZone'] = $TZ;

		// print_r(json_encode($body));
		// die();
		
		$urlpath = MS_365_GRAPH.$userPrincipalName.MS_365_GRAPH_PATH_EVENT."/".$booking_id_365;

		$authorization = "Bearer ".$accessToken;
		$headers  = [
            'Authorization: '.$authorization,
            'Content-Type: application/json'
        ];
        // echo $urlpath;
       
        $res = $this->send365EventPatch($urlpath,$headers,$body );
		return $res;

	}
	public function cancelEvent365($databook, $ms365_ , $inv_internal,$inv_external){
		$ex = MS_365_CREATEEVENT_BODY;
		$body = json_decode($ex,TRUE);
		$accessToken = $ms365_['access_token'];
		$userPrincipalName = $ms365_['userPrincipalName'];
		
		if($databook['booking_id_365'] == null ||   $databook['booking_id_365'] == ""){
			return ['error'=> "not sync microsoft room"];
		}
		$booking_id_365 = $databook['booking_id_365'];
		$urlpath = MS_365_GRAPH.$userPrincipalName.MS_365_GRAPH_PATH_EVENT."/".$booking_id_365.MS_365_GRAPH_PATH_EVENT_CANCEL;
		$authorization = "Bearer ".$accessToken;
		$headers  = [
            'Authorization: '.$authorization,
            'Content-Type: application/json'
        ];
       	$body = [
       		'Comment' =>isset( $databook['canceled_note']) ?  $databook['canceled_note'] : "",
       	];
        $res = $this->send365Event($urlpath,$headers,$body );
		return $res;

	}

	public function send365EventPatch($url,$headers, $data = []){
		$ch = curl_init();
		$bodyjson = json_encode($data);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH'); 
		curl_setopt($ch, CURLOPT_URL,$url);
		// curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyjson);           
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result     = curl_exec ($ch);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return $result;
	}
	public function send365Event($url,$headers, $data = []){
		$ch = curl_init();
		$bodyjson = json_encode($data);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyjson);           
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result     = curl_exec ($ch);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return $result;
	}


	
	
}

<?php  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set("Asia/Jakarta");
class Model_Notif extends CI_Model {
	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Api');
		$this->load->model('Model_Module');
	}
	public function pushNotification($title, $body = null,array $batch = null, $type_notif=1, $insert=true )
	{

		$datetime = date("Y-m-d H:i:s");
		$config_notif = $this->get_config();
		$collect = array();
		foreach ($batch as $key => $value) {
			$config = array(
				'url' => $config_notif['url'],
				'authorization' => $config_notif['authorization'],
				'active' => $config_notif['active'],
			);
			$topic_db =  $config_notif['topics'];

			$topic = $topic_db .$value['nik'];
			$payload = $this->fcmtopics($topic, $title, $body);
			$send_msg = $this->fcmsendmessage($config, $payload);
			// $type_notif = 1; // notif booking
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
		if($insert){
			$resp1= $this->Model_Api->insertDataBatch('notification_data', $collect);
		}
	}

	public function insertNotifAdmin($type, $title, $body){
		$array = array(
			"nik" =>$this->session->userdata('user-nya'),
			"type" =>$type,
			"title" => $title,
			"body" =>$body,
			"datetime" => date("Y-m-d H:i:s"),
			"is_read" => 0,
			"is_sending" => 0,
			'is_deleted' => 0,
			'created_at'=> date("Y-m-d H:i:s"),

		);
		$dta = $this->db->insert("notification_admin", $array);
	}
	public function insertNotifAdminApi($type, $title, $body, $nik){
		$array = array(
			"nik" =>$this->session->userdata('user-nya'),
			"type" =>$type,
			"title" => $title,
			"body" =>$body,
			"datetime" => date("Y-m-d H:i:s"),
			"is_read" => 0,
			"is_sending" => 0,
			'is_deleted' => 0,
			'created_at'=> date("Y-m-d H:i:s"),

		);
		$dta = $this->db->insert("notification_admin", $array);
	}

	public function insertNotifBatch($arrayData){
		$dta = $this->db->insert_batch("notification_data", $arrayData);
	}

	public function get_notification() {
		$this->db->select('na.*, nt.element, nt.name as type_name, e.name as e_name')
		->from("notification_admin na")
		->join("notification_type_admin nt", "na.type=nt.id")
		->join("employee e", "e.nik=na.nik")
		->where(array(
			"na.is_read" => 0,
			"na.is_deleted" => 0
		))
		->limit(10)
		->order_by("datetime DESC");
		$ret = $this->db->get()->result_array();	
		return $ret;
	}
	public function get_config() {
		$this->db->select('*')
		->from("notification_config");
		$ret = $this->db->get()->row_array();	
		return $ret;
	}

	public function get_template($type) {
		$this->db->select('*')
		->from("setting_email_template")
		->where(array("type"=>$type));
		$ret = $this->db->get()->row_array();	
		return $ret;
	}

	public function get_setting_smtp() {
		$this->db->select('*')
		->from("setting_smtp")
		->where(["is_enabled" => 1, "is_deleted" => 0]);
		$ret = $this->db->get()->row_array();	
		if(isset($ret["host"])){
			return $ret;
		}else{
			return null;
		}
	}
	public function curlPOSTNotif(array $header = null, string $url= "", $body = ""){
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 2);
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,  $body); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
			$rest = curl_exec($ch);
			curl_close($ch);   
			return $rest;
	}
	public function curlPOST($header = array(), $url="", $body = ""){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_TIMEOUT, 6);
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,  $body); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
			$rest = curl_exec($ch);
			curl_close($ch);   
			return $rest;
	}
	public function curlGET($header = array(), $url=""){
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_TIMEOUT, 6);
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
			$rest = curl_exec($ch);
			curl_close($ch);   
			return $rest;
	}
	public function fcmsendmessage($config, $payloads){
		// print_r($config);
		
		if($config['active'] == 1){
				$url = $config['url'];
				$arrayh = array(
					'Authorization: '.$config['authorization'].'',
					'Content-Type: application/json',
				);
			$ret = $this->curlPOSTNotif($arrayh, $url, $payloads);
			return $ret;
		}else{
			return "{}";
		}
	}
	public function fcmtopics($topic, $title, $body){
			$ar = array(
				"to" => '/topics/'. $topic,
				"notification" => array(
					'title' => $title,
					'body' => $body,
					'priority' => 'high',
					'content_available' => true,
				),
				"data" => array(
					'title' => $title,
					'body' => $body,
					'priority' => 'high',
					'content_available' => true
				),
			);
			return json_encode($ar);
	}

	public function url_infitation($type_email = "") 
	{
		
	}
	public function createImageB64($path){
		// $path = 'myfolder/myimage.png';
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$ifExists = file_exists($path);
		if($ifExists == true){
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
			return $base64 ;
		}
		return "no:image";
		
		
	}

	public function sendEmailForceMovedMeeting($booking, $pic, $vip_name ) {// $type_email (invitation, reschedule, cancel, other){
		$modules = $this->Model_Module->get_module_email();
		if($modules['is_enabled'] == 0 ){
			return;
		}
		

		$datetime = strtotime(date('Y-m-d H:i:s'));

		$config = $this->get_setting_smtp();
		if($config == null){
			return "";
		}
		$string = "";
		$string = read_file('./config/template_email_moved.html');

		$explodeS = explode(" ", $booking['start']);
		$explodeE = explode(" ", $booking['end']);
		$meeting_start = $explodeS[1];
		$meeting_end = $explodeE[1];
		$building=  isset($booking['building_name']) ? $booking['building_name'] . " - ": "";
		$building_location=  isset($booking['building_detail_address']) ? $booking['building_detail_address'] . " <br> ": "";

		$aLink = "";

		if(isset($booking['building_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['building_google_map'].'" >Building Link</a> - ';
		}
		if(isset($booking['room_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['room_google_map'].'" >Room Link</a> ';
		}

		$tempat = $building . "".$booking['room_name'];
		$location = $building_location . "".$booking['room_location'];
		$location = "";
		$fTanggal = $booking["format_date"] ." " .$booking["format_time_start"] ."-".$booking["format_time_end"] ;
		$string = str_replace('%agenda%', $booking['title'], $string);
		$string = str_replace('%tanggal%', $fTanggal, $string);
		$string = str_replace('%tempat%', $tempat , $string);
		$string = str_replace('%location%', $location, $string);
		$string = str_replace('%link_map%', $aLink, $string);
		$string = str_replace('%orginizer%', isset($pic['name'])?$pic['name']:"" , $string);
		$string = str_replace('%vip%', $vip_name , $string);

		$email_body =  $string;
		if(isset($pic['email']) && $pic['email'] != ""){
			if (strpos($pic['email'], '@') !== false) {
				$email = array(
					'to' => $pic['email'],
					// 'to' => "tmperdana157@gmail.com",
					'subject' => "Request Meeting"." ".$booking['title'] . " - ".$fTanggal,
					'body' => $email_body,
					'from' => $config['user'],
					'title_email' => isset($config['title_email'])?$config['title_email']:$config['user'],
				);
				$response = $this->mailerService($config, $email);
				// print_r($response);
				return  $response ;
			}
			
		}
	}

	public function sendEmailResponseApproval($booking, $pic, $status ) {// $type_email (invitation, reschedule, cancel, other){
		$modules = $this->Model_Module->get_module_email();
		if($modules['is_enabled'] == 0 ){
			return;
		}
		$config = $this->get_setting_smtp();
		if($config == null){
			return "";
		}
		
		$string = "";
		$string = read_file('./config/template_email_approval_response.html');

		$explodeS = explode(" ", $booking['start']);
		$explodeE = explode(" ", $booking['end']);
		$meeting_start = $explodeS[1];
		$meeting_end = $explodeE[1];
		$building=  isset($booking['building_name']) ? $booking['building_name'] . " - ": "";
		$building_location=  isset($booking['building_detail_address']) ? $booking['building_detail_address'] . " <br> ": "";

		$aLink = "";

		if(isset($booking['building_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['building_google_map'].'" >Building Link</a> - ';
		}
		if(isset($booking['room_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['room_google_map'].'" >Room Link</a> ';
		}

		$tempat = $building . "".$booking['room_name'];
		$location = $building_location . "".$booking['room_location'];
		$location = "";
		$fTanggal = $booking["format_date"] ." " .$booking["format_time_start"] ."-".$booking["format_time_end"] ;
		$string = str_replace('%agenda%', $booking['title'], $string);
		$string = str_replace('%tanggal%', $fTanggal, $string);
		$string = str_replace('%tempat%', $tempat , $string);
		$string = str_replace('%location%', $location, $string);
		$string = str_replace('%link_map%', $aLink, $string);
		$string = str_replace('%orginizer%', isset($pic['name'])?$pic['name']:"" , $string);

		if($status == 1){
			$string = str_replace('%title%', "Request Meeting Approved", $string);
			$string = str_replace('%content_text%', 'The meeting room request has been approved.', $string);
		}else{
			$string = str_replace('%title%', "Request Meeting Failed", $string);
			$string = str_replace('%content_text%','The meeting room request has been rejected.' , $string);
		}
		$email_body =  $string;
		if(isset($pic['email']) && $pic['email'] != ""){
			if (strpos($pic['email'], '@') !== false) {
				$email = array(
					'to' => $pic['email'],
					// 'to' => "bestsolutionautomation@gmail.com",
					'subject' => "Request Meeting"." ".$booking['title'] . " - ".$fTanggal,
					'body' => $email_body,
					'from' => $config['user'],
					'title_email' => isset($config['title_email'])?$config['title_email']:$config['user'],
				);
				$response = $this->mailerService($config, $email);
				return  $response ;
			}
			
		}

	}
	public function sendEmailApproval($booking, $people, $pic ) { 
		$modules = $this->Model_Module->get_module_email();
		if($modules['is_enabled'] == 0 ){
			return;
		}
		$dataAp = [
			'booking_id' =>$booking['booking_id'],
			'user_id' 	=>$people['nik'],
			'approve' 	=>1,
			'timezone' 	=>$booking['timezone']
		];
		$dataRe = [
			'booking_id' =>$booking['booking_id'],
			'user_id' 	=>$people['nik'],
			'approve' 	=>0,
			'timezone' 	=>$booking['timezone']
		];

		// $e = encryp_aes($data);
		$datetime = strtotime(date('Y-m-d H:i:s'));
		$urllinkapproval = base_url()."approval/meeting-approve?datetime=".$datetime."&token=:token ";

		$config = $this->get_setting_smtp();
		if($config == null){
			return "";
		}
		$tokenApprove = encryp_aes(json_encode($dataAp));
		$tokenReject = encryp_aes(json_encode($dataRe));
		$string = "";
		$string = read_file('./config/template_email_approval.html');
		$url_approval = str_replace(':token', $tokenApprove, $urllinkapproval);
		$url_reject = str_replace(':token', $tokenReject, $urllinkapproval);

		$explodeS = explode(" ", $booking['start']);
		$explodeE = explode(" ", $booking['end']);
		$meeting_start = $explodeS[1];
		$meeting_end = $explodeE[1];
		$building=  isset($booking['building_name']) ? $booking['building_name'] . " - ": "";
		$building_location=  isset($booking['building_detail_address']) ? $booking['building_detail_address'] . " <br> ": "";

		$aLink = "";

		if(isset($booking['building_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['building_google_map'].'" >Building Link</a> - ';
		}
		if(isset($booking['room_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['room_google_map'].'" >Room Link</a> ';
		}

		$tempat = $building . "".$booking['room_name'];
		$location = $building_location . "".$booking['room_location'];
		$location = "";
		$fTanggal = $booking["format_date"] ." " .$booking["format_time_start"] ."-".$booking["format_time_end"] ;
		$string = str_replace('%agenda%', $booking['title'], $string);
		$string = str_replace('%tanggal%', $fTanggal, $string);
		$string = str_replace('%tempat%', $tempat , $string);
		$string = str_replace('%location%', $location, $string);
		$string = str_replace('%link_map%', $aLink, $string);
		$string = str_replace('%urlApproval%', $url_approval, $string);
		$string = str_replace('%urlReject%', $url_reject, $string);
		$string = str_replace('%orginizer%', isset($pic['name'])?$pic['name']:"" , $string);

		$email_body =  $string;
		if(isset($people['email']) && $people['email'] != ""){
			if (strpos($people['email'], '@') !== false) {
				$email = array(
					'to' => $people['email'],
					// 'to' => "tmperdana157@gmail.com",
					'subject' => "Request Meeting"." ".$booking['title'] . " - ".$fTanggal,
					'body' => $email_body,
					'from' => $config['user'],
					'title_email' => isset($config['title_email'])?$config['title_email']:$config['user'],
				);
				$response = $this->mailerService($config, $email);
				// print_r($response);
				return  $response ;
			}
			
		}

	}



	public function sendEmailInternal(string $type_email = "", array $booking = null, $people = null,$pic = array() ) // $type_email (invitation, reschedule, cancel, other)
	{
		$modules = $this->Model_Module->get_module_email();
		if($modules['is_enabled'] == 0 ){
			return;
		}
		$url_participanInternal = base_url()."participant/internal/booking/:booking_id/employee/:employee/attendance/:attendance";
		// $url_participanEksternal = base_url()."participant/eksternal/booking/:booking_id/email/:email/attendance/:attendance";
		
		$config = $this->get_setting_smtp();
		if($config == null){
			return "";
		}

		$template = $this->get_template($type_email);
		$string = "";
		if($type_email == "invitation"){
			$string = read_file('./config/template_email.html');
		}else if($type_email == "reschedule"){
			$string = read_file('./config/template_email_re.html');
		}else if($type_email == "cancel"){
			$string = read_file('./config/template_email_batal.html');
		}else{
			return "fail";
			// die();
		}
		$url_participanInternal = str_replace(':booking_id', $booking['booking_id'], $url_participanInternal);
		$url_participanInternal = str_replace(':employee', $people['nik'], $url_participanInternal);
		$url_hadir = str_replace(':attendance', 1, $url_participanInternal);
		$url_tidak_hadir = str_replace(':attendance', 0, $url_participanInternal);

		$string = str_replace('%title%', $template['title_of_text'], $string);
			$string = str_replace('%kepada_text%', $template['to_text'], $string);
			$string = str_replace('%agenda_text%', $template['title_agenda_text'], $string);
			$string = str_replace('%tanggal_text%', $template['date_text'], $string);
			$string = str_replace('%tempat_text%', $template['room'], $string);
			$string = str_replace('%location_text%', $template['detail_location'], $string);
			$string = str_replace('%content_text%', $template['content_text'], $string);
			$string = str_replace('%greeting_text%', $template['greeting_text'], $string);
			$string = str_replace('%attendance%', $template['attendance_text'], $string);
			$string = str_replace('%notattendance%', $template['attendance_no_text'], $string);
			$string = str_replace('%close_text%', $template['close_text'], $string);
			$string = str_replace('%support_text%', $template['support_text'], $string);
			$string = str_replace('%link_text%', $template['map_link_text'], $string);
			$string = str_replace('%foot_text%', $template['foot_text'], $string);

			$string = str_replace('%url%', $template['link'], $string);
			// book
			$explodeS = explode(" ", $booking['start']);
			$explodeE = explode(" ", $booking['end']);
			$meeting_start = $explodeS[1];
			$meeting_end = $explodeE[1];
			$building=  isset($booking['building_name']) ? $booking['building_name'] . " - ": "";
			$building_location=  isset($booking['building_detail_address']) ? $booking['building_detail_address'] . " <br> ": "";

			$aLink = "";

			if(isset($booking['building_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['building_google_map'].'" >Building Link</a> - ';
			}
			if(isset($booking['room_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['room_google_map'].'" >Room Link</a> ';
			}

			$tempat = $building . "".$booking['room_name'];
			$location = $building_location . "".$booking['room_location'];
			$fTanggal = $booking["format_date"] ." " .$booking["format_time_start"] ."-".$booking["format_time_end"] ;

			$imageQRName =  $booking['booking_id']."_".$people['pin_room'];
			$qrimageurl = base_url().'assets/qr/'.$imageQRName.".png";
			$qrimagepath = 'assets/qr/'.$imageQRName.".png";

			$b64 = $this->createImageB64($qrimagepath);
			$htmlQR = '<img title="QR CODE" alt="QR CODE" src="'.$b64.'" style="width:205px;height:205px;" />';
			$htmlQR .= '<br><a title="QR CODE" target="__blank" alt="QR CODE" href="'.$qrimageurl.'" >Click this if QR not show</a>';
			
			$string = str_replace('%penyelenggara%', $booking['pic'], $string);

			$string = str_replace('%kepada%', $people['name'], $string);
			$string = str_replace('%agenda%', $booking['title'], $string);
			$string = str_replace('%tanggal%', $fTanggal, $string);
			$string = str_replace('%tempat%', $tempat , $string);
			$string = str_replace('%location%', $location, $string);
			$string = str_replace('%link_map%', $aLink, $string);
			$string = str_replace('%qrtattendance%', $htmlQR, $string);
			$string = str_replace('%urlAttendance%', $url_hadir, $string);
			$string = str_replace('%urlNotAttendance%', $url_tidak_hadir, $string);
			$string = str_replace('%orginizer%', isset($pic['name'])?$pic['name']:"" , $string);

			$email_body=  $string;
			// $email = array(
			// 	'to' => $people['email'],
			// 	'body' => $email_body,
			// 	'from' => $config['user'],
			// 	'title_email' => isset($config['title_email'])?$config['title_email']:$config['user'],
			// );

			if(isset($people['email']) && $people['email'] != ""){
				if (strpos($people['email'], '@') !== false) {
					$email = array(
						'to' => $people['email'],
						// 'to' => "tmperdana157@gmail.com",
						'subject' => $template['title_of_text']." ".$booking['title'] . " - ".$fTanggal,
						'body' => $email_body,
						'from' => $config['user'],
						'title_email' => isset($config['title_email'])?$config['title_email']:$config['user'],
					);
					$response = $this->mailerService($config, $email);
					// print_r("response");
					// print_r($response);
					return  $response ;
				}
			}
			// $response = $this->mailerService($config, $email);
			// return  $response ;
	}


	public function sendEmailPIC( string $type_email = "", array $booking = null, $people = null,$pic = array(), $is_admin = 0 ) 
	{
		$modules = $this->Model_Module->get_module_email();
		if($modules['is_enabled'] == 0 ){
			return;
		}
		$url_participanInternal = base_url()."participant/internal/booking/:booking_id/employee/:employee/attendance/:attendance";
		// $url_participanEksternal = base_url()."participant/eksternal/booking/:booking_id/email/:email/attendance/:attendance";
		
		$config = $this->get_setting_smtp();
		if($config == null){
			return "";
		}

		$template = $this->get_template($type_email);
		$string = "";
		$string = read_file('./config/template_email_pic.html');
		
		$url_participanInternal = str_replace(':booking_id', $booking['booking_id'], $url_participanInternal);
		$url_participanInternal = str_replace(':employee', $people['nik'], $url_participanInternal);
		$url_hadir = str_replace(':attendance', 1, $url_participanInternal);
		$url_tidak_hadir = str_replace(':attendance', 0, $url_participanInternal);

		$string = str_replace('%title%', $template['title_of_text'], $string);
			$string = str_replace('%kepada_text%', $template['to_text'], $string);
			$string = str_replace('%agenda_text%', $template['title_agenda_text'], $string);
			$string = str_replace('%tanggal_text%', $template['date_text'], $string);
			$string = str_replace('%tempat_text%', $template['room'], $string);
			$string = str_replace('%location_text%', $template['detail_location'], $string);
			$string = str_replace('%content_text%', $template['content_text'], $string);
			$string = str_replace('%greeting_text%', $template['greeting_text'], $string);
			$string = str_replace('%attendance%', $template['attendance_text'], $string);
			$string = str_replace('%notattendance%', $template['attendance_no_text'], $string);
			$string = str_replace('%close_text%', $template['close_text'], $string);
			$string = str_replace('%support_text%', $template['support_text'], $string);
			$string = str_replace('%link_text%', $template['map_link_text'], $string);
			$string = str_replace('%foot_text%', $template['foot_text'], $string);

			$string = str_replace('%url%', $template['link'], $string);
			// book
			$explodeS = explode(" ", $booking['start']);
			$explodeE = explode(" ", $booking['end']);
			$meeting_start = $explodeS[1];
			$meeting_end = $explodeE[1];
			$building=  isset($booking['building_name']) ? $booking['building_name'] . " - ": "";
			$building_location=  isset($booking['building_detail_address']) ? $booking['building_detail_address'] . " <br> ": "";

			$aLink = "";

			if(isset($booking['building_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['building_google_map'].'" >Building Link</a> - ';
			}
			if(isset($booking['room_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['room_google_map'].'" >Room Link</a> ';
			}

			$tempat = $building . "".$booking['room_name'];
			$location = $building_location . "".$booking['room_location'];
			$fTanggal = $booking["format_date"] ." " .$booking["format_time_start"] ."-".$booking["format_time_end"] ;

			$imageQRName =  $booking['booking_id']."_".$people['pin_room'];
			$qrimageurl = base_url().'assets/qr/'.$imageQRName.".png";
			$qrimagepath = 'assets/qr/'.$imageQRName.".png";

			$b64 = $this->createImageB64($qrimagepath);
			$htmlQR = '<img title="QR CODE" alt="QR CODE" src="'.$b64.'" style="width:205px;height:205px;" />';
			$htmlQR .= '<br><a title="QR CODE" target="__blank" alt="QR CODE" href="'.$qrimageurl.'" >Click this if QR not show</a>';
			
			$string = str_replace('%penyelenggara%', $booking['pic'], $string);

			$string = str_replace('%kepada%', $people['name'], $string);
			$string = str_replace('%agenda%', $booking['title'], $string);
			$string = str_replace('%tanggal%', $fTanggal, $string);
			$string = str_replace('%tempat%', $tempat , $string);
			$string = str_replace('%location%', $location, $string);
			$string = str_replace('%link_map%', $aLink, $string);
			$string = str_replace('%qrtattendance%', $htmlQR, $string);
			$string = str_replace('%urlAttendance%', $url_hadir, $string);
			$string = str_replace('%urlNotAttendance%', $url_tidak_hadir, $string);
			$string = str_replace('%orginizer%', isset($pic['name'])?$pic['name']:"" , $string);
			$string = str_replace('%note%', isset($booking['note'])?$booking['note']:"" , $string);

			$email_body=  $string;
			// $email = array(
			// 	'to' => $people['email'],
			// 	'body' => $email_body,
			// 	'from' => $config['user'],
			// 	'title_email' => isset($config['title_email'])?$config['title_email']:$config['user'],
			// );
			if($is_admin == 1){
				$people['email'] = ADMIN_EMAIL;
			}

			if(isset($people['email']) && $people['email'] != ""){
				if (strpos($people['email'], '@') !== false) {
					
					$email = array(
						'to' => $people['email'],
						// 'to' => "tmperdana157@gmail.com",
						'subject' => $template['title_of_text']." ".$booking['title'] . " - ".$fTanggal,
						'body' => $email_body,
						'from' => $config['user'],
						'title_email' => isset($config['title_email'])?$config['title_email']:$config['user'],
					);
					$response = $this->mailerService($config, $email);
					return  $response ;
				}
			}
			// $response = $this->mailerService($config, $email);
			// return  $response ;
	}


	public function sendEmailExternal($type_email = "", $booking = null, $people = null,$pic = array()) // $type_email (invitation, reschedule, cancel, other)
	{
		$modules = $this->Model_Module->get_module_email();
		if($modules['is_enabled'] == 0 ){
			return;
		}
		$url_participanEksternal = base_url()."participant/eksternal/booking/:booking_id/email/:email/attendance/:attendance";
		
		$config = $this->get_setting_smtp();
		if($config == null){
			return "";
		}
		$template = $this->get_template($type_email);
		$string = "";
		if($type_email == "invitation"){
			$string = read_file('./config/template_email.html');
		}else if($type_email == "reschedule"){
			$string = read_file('./config/template_email_re.html');
		}else if($type_email == "cancel"){
			$string = read_file('./config/template_email_batal.html');
		}else{
			return "fail";
			die();
		}
		$url_participanEksternal = str_replace(':booking_id', $booking['booking_id'], $url_participanEksternal);
		$url_participanEksternal = str_replace(':email', $people['email'], $url_participanEksternal);
		$url_hadir = str_replace(':attendance', 1, $url_participanEksternal);
		$url_tidak_hadir = str_replace(':attendance', 0, $url_participanEksternal);

		$string = str_replace('%title%', $template['title_of_text'], $string);
			$string = str_replace('%kepada_text%', $template['to_text'], $string);
			$string = str_replace('%agenda_text%', $template['title_agenda_text'], $string);
			$string = str_replace('%tanggal_text%', $template['date_text'], $string);
			$string = str_replace('%tempat_text%', $template['room'], $string);
			$string = str_replace('%location_text%', $template['detail_location'], $string);
			$string = str_replace('%content_text%', $template['content_text'], $string);
			$string = str_replace('%greeting_text%', $template['greeting_text'], $string);
			$string = str_replace('%attendance%', $template['attendance_text'], $string);
			$string = str_replace('%notattendance%', $template['attendance_no_text'], $string);
			$string = str_replace('%close_text%', $template['close_text'], $string);
			$string = str_replace('%support_text%', $template['support_text'], $string);
			$string = str_replace('%link_text%', $template['map_link_text'], $string);
			$string = str_replace('%foot_text%', $template['foot_text'], $string);

			$string = str_replace('%url%', $template['link'], $string);
			// book
			$explodeS = explode(" ", $booking['start']);
			$explodeE = explode(" ", $booking['end']);
			$meeting_start = $explodeS[1];
			$meeting_end = $explodeE[1];
			$building=  isset($booking['building_name']) ? $booking['building_name'] . " - ": "";
			$building_location=  isset($booking['building_detail_address']) ? $booking['building_detail_address'] . " <br> ": "";

			$aLink = "";

			if(isset($booking['building_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['building_google_map'].'" >Building Link</a> - ';
			}
			if(isset($booking['room_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['room_google_map'].'" >Room Link</a> ';
			}

			$tempat = $building . "".$booking['room_name'];
			$location = $building_location . "".$booking['room_location'];
			$fTanggal = $booking["format_date"] ." " .$booking["format_time_start"] ."-".$booking["format_time_end"] ;

			$imageQRName =  $booking['booking_id']."_".$people['pin_room'];
			$qrimageurl = base_url().'assets/qr/'.$imageQRName.".png";
			$qrimagepath = 'assets/qr/'.$imageQRName.".png";

			$b64 = $this->createImageB64($qrimagepath);
			$htmlQR = '<img title="QR CODE" alt="QR CODE" src="'.$b64.'" style="width:205px;height:205px;" />';
			$htmlQR .= '<br><a title="QR CODE" target="__blank" alt="QR CODE" href="'.$qrimageurl.'" >Click this if QR not show</a>';
			
			$string = str_replace('%penyelenggara%', $booking['pic'], $string);

			$string = str_replace('%kepada%', $people['name'], $string);
			$string = str_replace('%agenda%', $booking['title'], $string);
			$string = str_replace('%tanggal%', $fTanggal, $string);
			$string = str_replace('%tempat%', $tempat , $string);
			$string = str_replace('%location%', $location, $string);
			$string = str_replace('%link_map%', $aLink, $string);
			$string = str_replace('%qrtattendance%', $htmlQR, $string);
			$string = str_replace('%urlAttendance%', $url_hadir, $string);
			$string = str_replace('%urlNotAttendance%', $url_tidak_hadir, $string);
			$string = str_replace('%orginizer%', isset($pic['name'])?$pic['name']:"" , $string);

			$email_body=  $string;
			// print_r($people );

			if(isset($people['email']) && $people['email'] != ""){
					

				if (strpos($people['email'], '@') !== false) {
					$email = array(
						'to' => $people['email'],
						'subject' => $template['title_of_text']." ".$booking['title'] . " - ".$fTanggal,
						'body' => $email_body,
						'from' => $config['user'],
						'title_email' => isset($config['title_email'])?$config['title_email']:$config['user'],
					);
					$response = $this->mailerService($config, $email);
					return  $response ;
				}
			}
	}

	public function sendDeskEmailInternal($type_email = "", $booking = null, $people = null) // $type_email (invitation, reschedule, cancel, other)
	{
		$url_participanInternal = base_url();
		// $url_participanInternal = base_url()."participant/internal/booking/:booking_id/employee/:employee/attendance/:attendance";
		// $url_participanEksternal = base_url()."participant/eksternal/booking/:booking_id/email/:email/attendance/:attendance";
		
		$config = $this->get_setting_smtp();
		if($config == null){
			return "";
		}
		$template = $this->get_template($type_email);
		$string = "";
		if($type_email == "desk_invitation"){
			$string = read_file('./config/template_email.html');
		}else if($type_email == "desk_reschedule"){
			$string = read_file('./config/template_email_re.html');
		}else if($type_email == "desk_cancel"){
			$string = read_file('./config/template_email_batal.html');
		}else{
			return "fail";
			die();
		}
		$url_participanInternal = str_replace(':booking_id', $booking['booking_id'], $url_participanInternal);
		$url_participanInternal = str_replace(':employee', $people['nik'], $url_participanInternal);
		$url_hadir = str_replace(':attendance', 1, $url_participanInternal);
		$url_tidak_hadir = str_replace(':attendance', 0, $url_participanInternal);

		$string = str_replace('%title%', $template['title_of_text'], $string);
			$string = str_replace('%kepada_text%', $template['to_text'], $string);
			$string = str_replace('%agenda_text%', $template['title_agenda_text'], $string);
			$string = str_replace('%tanggal_text%', $template['date_text'], $string);
			$string = str_replace('%tempat_text%', $template['room'], $string);
			$string = str_replace('%location_text%', $template['detail_location'], $string);
			$string = str_replace('%content_text%', $template['content_text'], $string);
			$string = str_replace('%greeting_text%', $template['greeting_text'], $string);
			$string = str_replace('%attendance%', $template['attendance_text'], $string);
			$string = str_replace('%notattendance%', $template['attendance_no_text'], $string);
			$string = str_replace('%close_text%', $template['close_text'], $string);
			$string = str_replace('%support_text%', $template['support_text'], $string);
			$string = str_replace('%link_text%', $template['map_link_text'], $string);
			$string = str_replace('%foot_text%', $template['foot_text'], $string);

			$string = str_replace('%url%', $template['link'], $string);
			// book
			$explodeS = explode(" ", $booking['start']);
			$explodeE = explode(" ", $booking['end']);
			$meeting_start = $explodeS[1];
			$meeting_end = $explodeE[1];
			$building=  isset($booking['building_name']) ? $booking['building_name'] . " - ": "";
			$building_location=  isset($booking['building_detail_address']) ? $booking['building_detail_address'] . " <br> ": "";

			$aLink = "";

			if(isset($booking['building_google_map'])){
				$aLink .= '<a target="__blank" href="'.$booking['building_google_map'].'" >Building Link</a> - ';
			}
			// if(isset($booking['room_google_map'])){
			// 	$aLink .= '<a target="__blank" href="'.$booking['room_google_map'].'" >Room Link</a> ';
			// }

			$tempat = $building . "".$booking['room_name'] . " " ." - desk ".$booking['room_name'] ;
			$location = $building_location . "".$booking['room_location'];
			$fTanggal = $booking["format_date"] ." " .$booking["format_time_start"] ."-".$booking["format_time_end"] ;

			$imageQRName =  $booking['booking_id']."_".$people['pin_room'];
			$qrimageurl = base_url().'assets/qr/'.$imageQRName.".png";
			$qrimagepath = 'assets/qr/'.$imageQRName.".png";

			$b64 = $this->createImageB64($qrimagepath);
			$htmlQR = '<img title="QR CODE" alt="QR CODE" src="'.$b64.'" style="width:205px;height:205px;" />';
			$htmlQR .= '<br><a title="QR CODE" target="__blank" alt="QR CODE" href="'.$qrimageurl.'" >Click this if QR not show</a>';
			
			$string = str_replace('%penyelenggara%', $booking['pic'], $string);

			$string = str_replace('%kepada%', $people['name'], $string);
			$string = str_replace('%agenda%', $booking['title'], $string);
			$string = str_replace('%tanggal%', $fTanggal, $string);
			$string = str_replace('%tempat%', $tempat , $string);
			$string = str_replace('%location%', $location, $string);
			$string = str_replace('%link_map%', $aLink, $string);
			$string = str_replace('%qrtattendance%', $htmlQR, $string);
			$string = str_replace('%urlAttendance%', $url_hadir, $string);
			$string = str_replace('%urlNotAttendance%', $url_tidak_hadir, $string);

			$email_body=  $string;
			// $email = array(
			// 	'to' => $people['email'],
			// 	'subject' => $template['title_of_text']." ".$booking['title'] . " - ".$fTanggal,
			// 	'body' => $email_body,
			// 	'from' => $config['user'],
			// 	'title_email' => isset($config['title_email'])?$config['title_email']:$config['user'],
			// );
			if(isset($people['email']) && $people['email'] != ""){
				if (strpos($people['email'], '@') !== false) {
					$email = array(
						'to' => $people['email'],
						'subject' => $template['title_of_text']." ".$booking['title'] . " - ".$fTanggal,
						'body' => $email_body,
						'from' => $config['user'],
						'title_email' => isset($config['title_email'])?$config['title_email']:$config['user'],
					);
					$response = $this->mailerService($config, $email);
					return  $response ;
				}
			}
	}


	public function mailerService($config, $email ){
		include APPPATH.'third_party/phpmailer/autoload.php';
		// print_r($config);
		$mail = new PHPMailer(true);
		try {
            $mail->SMTPDebug 	= false;
            $mail->isSMTP();
            $mail->Host       	= $config['host'];
            $mail->SMTPAuth   	= true;
            $mail->Username  	=  $config['user']; // ubah dengan alamat email Anda
            $mail->Password   	=  $config['password']; // ubah dengan password email Anda
            if($config['secure'] == 1){
            	$mail->SMTPSecure = 'ssl';
            }else{
            	$mail->SMTPSecure = false; //tls
				$mail->SMTPAutoTLS = false;
            }
           
            $mail->Port       = $config['port'];
            $mail->From = $config['user'];
            $mail->FromName = $email['title_email'];
            $mail->AddCustomHeader("X-MSMail-Priority: High");
            $mail->addAddress($email['to']);
 
            // Isi Email
            $mail->isHTML(true);
            $mail->Subject =$email['subject'];
            $mail->Body    =$email['body'];
            $ds = $mail->send();
            return "success";
        } catch (Exception $e) {
            return "fail";
        }

	}
}





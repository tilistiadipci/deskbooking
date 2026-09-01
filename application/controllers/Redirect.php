<?php
use PHPMailer\PHPMailer\PHPMailer;
defined('BASEPATH') OR exit('No direct script access allowed');

class Redirect extends CI_Controller {

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
		$this->load->model('Model_Auth');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Module');
		$this->load->helper('response');
	}	

	public function index()
	{
		if($this->session->userdata('logged-in')){
			$levelid = $this->session->userdata('levelid-nya');
			$default = $this->Model_Auth->gotoDefaultMenu($levelid);
			$url = $default['url'];
			// print_r($default);
			redirect(".".$url); 
			
		}else{
			redirect('authentication');
		}
		
		
	}
	function timezone_list() {
	    static $timezones = null;

	    if ($timezones === null) {
	        $timezones = [];
	        $offsets = [];
	        $now = new DateTime('now', new DateTimeZone('UTC'));

	        foreach (DateTimeZone::listIdentifiers() as $timezone) {
	            $now->setTimezone(new DateTimeZone($timezone));
	            $offsets[] = $offset = $now->getOffset();
	            $timezones[$timezone] = '(' . $this->format_GMT_offset($offset) . ') ' . $this->format_timezone_name($timezone);
	        }

	        array_multisort($offsets, $timezones);
	    }

	    return $timezones;
	}

	function format_GMT_offset($offset) {
	    $hours = intval($offset / 3600);
	    $minutes = abs(intval($offset % 3600 / 60));
	    return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
	}

	function format_timezone_name($name) {
	    $name = str_replace('/', ', ', $name);
	    $name = str_replace('_', ' ', $name);
	    $name = str_replace('St ', 'St. ', $name);
	    return $name;
	}
	public function testMail()
	{
		// $tzlist = $this->Model_Module->listTimezone();
		// echo $this->Model_Module->getLocalTimezon();
		// echo "<pre>";
		// print_r($this->timezone_list());

		// echo json_encode($tzlist);
		include APPPATH.'third_party/phpmailer/autoload.php';
		$event_id = 1234;
		$event = array();
		$event['description'] = "EVENT DESC 1234";
		$sequence = 0;
		$status = 'CONFIRMED';
		$summary = 'Summary of the event';
		$venue = 'Simbawanga 124';
		$start = '20230228';
		$start_time = '160630';
		$end = '20140820';
		$end_time = '180630';
		$ical = "BEGIN:VCALENDAR\r\n";
		$ical .= "VERSION:2.0\r\n";
		$ical .= "PRODID:-//Google Inc//Google Calendar 70.9054//EN\r\n";
		$ical .= "METHOD:PUBLISH\r\n";
		$ical .= "BEGIN:VEVENT\r\n";
		$ical .= "ORGANIZER;SENT-BY=\"MAILTO:team-noreply@bio-experience.com\":MAILTO:team-noreplyhost@bio-experience.com\r\n";
		$ical .= "ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=
		 TRUE;CN=tmperdana157@gmail.com;X-NUM-GUESTS=0:mailto:tmperdana157@gmail.com\r\n";
		$ical .= "ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=
		 TRUE;CN=bestsolutionautomation@gmail.com;X-NUM-GUESTS=0:mailto:bestsolutionautomation@gmail.com\r\n";
		$ical .= "UID:".strtoupper(md5($event_id))."-bio-experience.com\r\n";
		$ical .= "SEQUENCE:".$sequence."\r\n";
		$ical .= "STATUS:".$status."\r\n";
		$ical .= "DTSTAMPTZID=Asia/Jakarta:".date('Ymd').'T'.date('His')."\r\n";
		$ical .= "DTSTART:".$start."T".$start_time."\r\n";
		$ical .= "DTEND:".$end."T".$end_time."\r\n";
		$ical .= "LOCATION:".$venue."\r\n";
		$ical .= "SUMMARY:".$summary."\r\n";
		$ical .= "DESCRIPTION:".$event['description']."\r\n";
		// $ical .= "BEGIN:VALARM\r\n";
		// $ical .= "TRIGGER:-PT15M\r\n";
		// $ical .= "ACTION:DISPLAY\r\n";
		// // $ical .= "DESCRIPTION:Reminder\r\n";
		// $ical .= "END:VALARM\r\n";
		$ical .= "END:VEVENT\r\n";
		$ical .= "END:VCALENDAR\r\n";

		// $ical =  "BEGIN:VCALENDAR
		// PRODID:-//Google Inc//Google Calendar 70.9054//EN
		// VERSION:2.0
		// CALSCALE:GREGORIAN
		// METHOD:REQUEST
		// BEGIN:VTIMEZONE
		// TZID:Asia/Jakarta
		// X-LIC-LOCATION:Asia/Jakarta
		// BEGIN:STANDARD
		// TZOFFSETFROM:+0700
		// TZOFFSETTO:+0700
		// TZNAME:WIB
		// DTSTART:19700101T000000
		// END:STANDARD
		// END:VTIMEZONE
		// BEGIN:VEVENT
		// DTSTART;TZID=Asia/Jakarta:20220812T100000
		// DTEND;TZID=Asia/Jakarta:20220812T120000
		// RRULE:FREQ=WEEKLY;UNTIL=20230216T165959Z;BYDAY=FR
		// DTSTAMP:20230216T134006Z
		// ORGANIZER;CN=mei.hendrick.lesmana@gmail.com:mailto:mei.hendrick.lesmana@gma
		//  il.com
		// UID:1c2qivqks230q84mjfdvit0ges5p1@google.com
		// ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=
		//  TRUE;CN=tmperdana157@gmail.com;X-NUM-GUESTS=0:mailto:tmperdana157@gmail.com
		// X-MICROSOFT-CDO-OWNERAPPTID:881381848
		// CREATED:20220809T051329Z
		// DESCRIPTION:
		// LAST-MODIFIED:20230216T133948Z
		// LOCATION:Jl. Buni No.19\, RT.9/RW.3\, Mangga Besar\, Kec. Taman Sari\, Kota
		//   Jakarta Barat\, Daerah Khusus Ibukota Jakarta 11180\, Indonesia
		// SEQUENCE:0
		// STATUS:CONFIRMED
		// SUMMARY:weekly training
		// TRANSP:OPAQUE
		// END:VEVENT
		// END:VCALENDAR";
		// echo !extension_loaded('openssl')?"Not Available":"Available";
		// $mail = new PHPMailer;
		// $mail->isSMTP();
		// $mail->SMTPDebug = 2;
		// $mail->Host = "mailint-sg.rsint.net";
		// // $mail->Port = 25;
		// $mail->Port = 465;
		// $mail->SMTPSecure = 'ssl';
		// // $mail->SMTPSecure = false; //tls
		// // $mail->SMTPAutoTLS = false;
		// $mail->SMTPAuth = true;
		// $mail->Username = 'vinanto.wibowo@rohde-schwarz.com';
		// $mail->Password = 'bgt5^YHNbgt5^YHN';
		// $mail->setFrom('vinanto.wibowo@rohde-schwarz.com', 'Yours');
		// $mail->addAddress('tmperdana157@gmail.com', 'Receiver Name');
		// // $mail->addAddress('bestsolutionautomation@gmail.com', 'Receiver Name');


		// $mail->Subject = 'Testing ics 2';
		// $mail->addStringAttachment($ical,'ical.ics','base64','text/calendar');
		// // $mail->msgHTML(file_get_contents('message.html'), __DIR__);
		// $mail->Body = 'This is a plain text message body';
		// //$mail->addAttachment('test.txt');
		// if (!$mail->send()) {
		// echo 'Mailer Error: ' . $mail->ErrorInfo;
		// } else {
		// echo 'The email message was sent.';
		// }

		$mail = new PHPMailer(true);
		try {
            $mail->SMTPDebug 	= true;
            $mail->isSMTP();
            $mail->Host       	= 'mailint-sg.rsint.net';
            $mail->SMTPAuth   	= true;
            $mail->Username  	=  'vinanto.wibowo@rohde-schwarz.com'; // ubah dengan alamat email Anda
            $mail->Password   	=  'bgt5^YHNbgt5^YHN'; // ubah dengan password email Anda
            // if($config['secure'] == 1){
            // 	$mail->SMTPSecure = 'ssl';
            // }else{
            // 	$mail->SMTPSecure = false; //tls
			// 	$mail->SMTPAutoTLS = false;
            // }
            $mail->SMTPSecure = false; //tls
			// $mail->SMTPAutoTLS = false;
           
            $mail->Port       = 25;
            $mail->From = 'vinanto.wibowo@rohde-schwarz.com';
            $mail->FromName ='vinanto.wibowo@rohde-schwarz.com';
            $mail->AddCustomHeader("X-MSMail-Priority: High");
            $mail->addAddress('tmperdana157@gmail.com');
 
            // Isi Email
            $mail->isHTML(true);
            $mail->Subject ="Test";
            $mail->Body    ="<b>Test</b>";
            $ds = $mail->send();
            return "success";
        } catch (Exception $e) {
            return "fail";
        }
		
		
	}
	public function mediaLogo()
	{
		ob_end_clean();
		$img = "logo.png";
		$data = $this->Model_Admin->getDataCompany();
		if($data['error'] == null){
			$company = $data['data'];
			if($company["logo"] == ""){
				$img = "logo.png";
			}else{
				$img = $company['logo'];
			}
		}else{
			$img = "logo.png";
			// $company = array();
		}
		$this->load->helper('file');
		if (!file_exists('./assets/file/company/'.$img)) {   
			$img = "logo.png";
		}
		$this->output
	        ->set_status_header(200)
	        ->set_content_type('jpeg') 
	        ->set_content_type('png') 
	        ->set_content_type('gif') 
	        ->set_content_type('bmp') 
	        ->set_output(file_get_contents('./assets/file/company/'.$img));
	}
	public function mediaMenuBar()
	{
		ob_end_clean();
		$img = "logo.png";
		$data = $this->Model_Admin->getDataCompany();
		if($data['error'] == null){
			$company = $data['data'];
			if($company["menu_bar"] == ""){
				$img = "logo.png";
			}else{
				$img = $company['menu_bar'];
			}
		}else{
			$img = "logo.png";
			// $company = array();
		}
		$this->load->helper('file');
		if (!file_exists('./assets/file/company/'.$img)) {   
			$img = "logo.png";
		}
		$this->output
	        ->set_status_header(200)
	        ->set_content_type('jpeg') 
	        ->set_content_type('png') 
	        ->set_content_type('gif') 
	        ->set_content_type('bmp') 
	        ->set_output(file_get_contents('./assets/file/company/'.$img));
	}
	public function mediaIcon()
	{
		ob_end_clean();
		$img = "logo.png";
		$data = $this->Model_Admin->getDataCompany();
		if($data['error'] == null){
			$company = $data['data'];
			if($company["icon"] == ""){
				$img = "logo.png";
			}else{
				$img = $company['icon'];
			}
		}else{
			$img = "logo.png";
			// $company = array();
		}
		$this->load->helper('file');
		if (!file_exists('./assets/file/company/'.$img)) {   
			$img = "logo.png";
		}
		$this->output
	        ->set_status_header(200)
	        ->set_content_type('jpeg') 
	        ->set_content_type('png') 
	        ->set_content_type('gif') 
	        ->set_content_type('bmp') 
	        ->set_output(file_get_contents('./assets/file/company/'.$img));
	}
	public function mediaBG()
	{
		ob_end_clean();
		$img = "logo.png";
		$data = $this->Model_Admin->getDataCompany();
		if($data['error'] == null){
			$company = $data['data'];
			if($company["picture"] == ""){
				$img = "logo.png";
			}else{
				$img = $company['picture'];
			}
		}else{
			$img = "logo.png";
			// $company = array();
		}
		$this->load->helper('file');
		if (!file_exists('./assets/file/company/'.$img)) {   
			$img = "logo.png";
		}
		$this->output
	        ->set_status_header(200)
	        ->set_content_type('jpeg') 
	        ->set_content_type('png') 
	        ->set_content_type('gif') 
	        ->set_content_type('bmp') 
	        ->set_output(file_get_contents('./assets/file/company/'.$img));
	}
	public function mediaPantry()
	{
		$name = $this->uri->segment('3');
		$img = "pantry.jpeg";
		$this->load->helper('file');
		if (!file_exists('./assets/pantry/'.$name)) {   
			ob_end_clean();
			$img = "pantry.jpeg";
			$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/'.$img));
		}else{
			ob_end_clean();
			$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/pantry/'.$name));
		}
		
	}
	public function mediaPantryMenu()
	{
		$name = $this->uri->segment('3');
		$img = "pantry-detail.png";
		$this->load->helper('file');
		if (!file_exists('./assets/pantry/'.$name)) {   
			ob_end_clean();
			$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/'.$img));
		}else{
			ob_end_clean();
			$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/pantry/'.$name));
		}
		
	}

	
	public function media()
	{
		$this->load->helper('file');
		$name = $this->uri->segment('2'); 
		$sp = explode(".", $name);
		$imageType = array("jpeg", "png", "jpg", "gif", "bmp", "JPEG", "PNG", "JPG", "GIF", "BMP");
		if(end($sp) == "mp4" || end($sp) == "webm"){
			ob_end_clean();

			$this->output
	        ->set_status_header(200)
	        ->set_content_type('mp4') 
	        ->set_content_type('webm') 
	        ->set_output(file_get_contents('./assets/file/media/'.$name));
		}else if( in_array(end($sp), $imageType)){
			if (!file_exists('./assets/file/media/'.$name)) {  
				ob_end_clean();
				$img = "active_meeting.jpg";
				$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/'.$img));
			}else{
				ob_end_clean();
				$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/file/media/'.$name));
			}
			
		}else{
			echo json_encode(array("name" => $name, "type" => "other"));		
		}
// read_file('./assets/file/media/'.$name);
		
	}
	public function multimedia()
	{
		$this->load->helper('file');
		$path = $this->uri->segment('2'); 
		$name = $this->uri->segment('3'); 
		$sp = explode(".", $name);
		$imageType = array("jpeg", "png", "jpg", "gif", "bmp", "JPEG", "PNG", "JPG", "GIF", "BMP");
		if(end($sp) == "mp4" || end($sp) == "webm"){
			ob_end_clean();
			$this->output
	        ->set_status_header(200)
	        ->set_content_type('mp4') 
	        ->set_content_type('webm') 
	        ->set_output(file_get_contents('./assets/file/'.$path.'/'.$name));
		}else if( in_array(end($sp), $imageType)){
			ob_end_clean();
			
	        if (!file_exists('./assets/file/'.$path.'/'.$name)) {   
				$img = "active_meeting.jpg";
				$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/'.$img));
			}else{
				$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/file/'.$path.'/'.$name));
			}
		}else{
			echo json_encode(array("name" => $name, "type" => "other"));		
		}
// read_file('./assets/file/media/'.$name);
		
	}
	public function getDownloadSignage()
	{
		$this->load->helper('file');
		$name = $this->uri->segment('3'); 
		$sp = explode(".", $name);
		$imageType = array("jpeg", "png", "jpg", "gif", "bmp", "JPEG", "PNG", "JPG", "GIF", "BMP");
		if(end($sp) == "mp4" || end($sp) == "webm"){
			$this->output
	        ->set_status_header(200)
	        ->set_content_type('mp4') 
	        ->set_content_type('webm') 
	        ->set_output(file_get_contents('./assets/file/display/signage/'.$name));
		}else if( in_array(end($sp), $imageType)){
			ob_end_clean();
			
	        if (!file_exists('./assets/file/display/signage/'.$name)) {   
				$img = "active_meeting.jpg";
				$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/'.$img));
			}else{
				ob_end_clean();
				$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/file/display/signage/'.$name));
			}
		}else{
			echo json_encode(array("name" => $name, "type" => "other"));		
		}
	}
	public function getDownloadBackground()
	{
		$this->load->helper('file');
		$name = $this->uri->segment('3'); 
		$sp = explode(".", $name);
		$imageType = array("jpeg", "png", "jpg", "gif", "bmp", "JPEG", "PNG", "JPG", "GIF", "BMP");
		if(end($sp) == "mp4" || end($sp) == "webm"){
			ob_end_clean();
			$this->output
	        ->set_status_header(200)
	        ->set_content_type('mp4') 
	        ->set_content_type('webm') 
	        ->set_output(file_get_contents('./assets/file/display/background/'.$name));
		}else if( in_array(end($sp), $imageType)){
			ob_end_clean();
	        if (!file_exists('./assets/file/display/background/'.$name)) {   
				$img = "active_meeting.jpg";
				$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/'.$img));
			}else{
				$this->output
		        ->set_status_header(200)
		        ->set_content_type('jpeg') 
		        ->set_content_type('png') 
		        ->set_content_type('gif') 
		        ->set_content_type('bmp') 
		        ->set_output(file_get_contents('./assets/file/display/background/'.$name));
			}
		}else{
			echo json_encode(array("name" => $name, "type" => "other", "found"=>end($sp) ));		
		}
	}


	public function konva()
	{
		$query = $_GET;
		if(!isset($query['building'])){
			$query['building'] = "";
		}
		if(!isset($query['floor'])){
			$query['floor'] = "";
		}

		$pagename = "Access";
		$wbuilding = array("is_deleted" => 0);
		$room_data = $this->Model_Admin->getDataRoom2();
		$building_data = $this->Model_Admin->getDataBuilding($wbuilding);
		$modules = array();

		$param = [
			'room' => $room_data['data'],
			'building' => $building_data['data'],
			'query' => $query,
		];
		$this->load->view('Konva/index', $param);
	}

	public function konvaFloorList()
	{
		$this->load->model('Model_Beacon');
		$id = isset($_GET['building_id']) ?$_GET['building_id'] : "" ;
		$w = array();
		// print_r($id);
		if($id != ""){
			$w['f.building_id'] = $id;
		}
		$data = $this->Model_Beacon->getFloor($w);
		if($id != ""){
			if($data->num_rows() <= 0){
				echo response("fail", array(), "Floor not exist, please try refresh page again!");
			}else{
				echo response("success", $data->result_array(), "Get success");
			}
		}else{
			echo response("success", $data->result_array(), "Get success");
		}
		
	}
	public function konvaFloorGetDataId()
	{
		$this->load->model('Model_Beacon');
		$id = isset($_GET['floor_id']) ?$_GET['floor_id'] : "" ;
		$w = array();
		// print_r($id);
		if($id != ""){
			$w['f.id'] = $id;
		}
		$data = $this->Model_Beacon->getFloor($w);
		if($id != ""){
			if($data->num_rows() <= 0){
				echo response("fail", array(), "Floor not exist, please try refresh page again!");
			}else{
				echo response("success", $data->row_array(), "Get success");
			}
		}else{
			echo response("fail", array(), "Floor not exist, please try refresh page again!");
		}
		
	}
	public function konvaGetFloorRoomArea()
	{
		$this->load->model('Model_Beacon');
		$id = isset($_GET['id']) ? $_GET['id'] : "" ;
		$floor_id = isset($_GET['floor_id']) ? $_GET['floor_id'] : "" ;
		$w = array();
		$w['fr.floor_id'] = $floor_id;
		if($id != ""){
			$w['fr.id'] = $id;
		}
		$data = $this->Model_Beacon->getFloorRoomArea($w);
		if($id != ""){
			if($data->num_rows() <= 0){
				echo response("fail", array(), "Floor Area Beacon not exist, please try refresh page again!");
			}else{
				echo response("success", $data->row_array(), "Get success");
			}
		}else{
			echo response("success", $data->result_array(), "Get success");
		}
	}

	public function konvaSaveFloorRoomArea()
	{
		$this->load->model('Model_Beacon');
		$listarea = isset($_POST['listarea']) ? $_POST['listarea'] : [];
		$listarea_delete = isset($_POST['listarea_delete']) ? $_POST['listarea_delete'] : [];
		$building = isset($_POST['building']) ? $_POST['building'] : "";
		$floor = isset($_POST['floor']) ? $_POST['floor'] : "";
		print_r($_POST);
		die();
		foreach ($listarea_delete as $key => $value) {
			if($value['id'] != null){
				$id = $value['id'];
				$where = ["id" =>$id];
				$data = ["is_deleted" => 1];
				$this->Model_Admin->updateData("beacon_floor_room",$data, $where);
			}
		}
		foreach ($listarea as $key => $value) {
			if($value['id'] != null){
				$id = $value['id'];
				$where = ["id" =>$id];
				$l = isset($value['width']) ? $value['width'] : 0;
				$w = isset($value['height']) ? $value['height'] : 0;
				$wide = ($w-0) * ($l-0);
				$data = [
					'floor_id' =>$floor,
					'building_id' =>$building,
					'room_id' => isset($value['room_id']) ? $value['room_id'] : "",
					'room_name' => isset($value['room_name']) ? $value['room_name'] : "",
					'length' => isset($value['length']) ? $value['length'] : 0,
					'width' => isset($value['width']) ? $value['width'] : 0,
					'wide' => $wide,
					'position_px' =>  isset($value['position_px']) ? $value['position_px'] : "",
					'is_deleted' => 0,
					'name' => isset($value['name']) ? $value['name'] : "",
					'shape' => isset($value['shape']) ? $value['shape'] : "",
				];
				$this->Model_Admin->updateData("beacon_floor_room",$data, $where);
			}else{
				$l = isset($value['width']) ? $value['width'] : 0;
				$w = isset($value['height']) ? $value['height'] : 0;
				$wide = ($w-0) * ($l-0);
				$data = [
					'floor_id' =>$floor,
					'building_id' =>$building,
					'room_id' => isset($value['room_id']) ? $value['room_id'] : "",
					'room_name' => isset($value['room_name']) ? $value['room_name'] : "",
					'length' => isset($value['length']) ? $value['length'] : 0,
					'width' => isset($value['width']) ? $value['width'] : 0,
					'wide' => $wide,
					'position_px' =>  isset($value['position_px']) ? $value['position_px'] : "",
					'is_deleted' => 0,
					'name' => isset($value['name']) ? $value['name'] : "",
					'shape' => isset($value['shape']) ? $value['shape'] : "",

				];
				$this->Model_Admin->insertData("beacon_floor_room",$data);
			}
		}
		echo response("success", array(), "Save/Update area success");
	}
	
}

<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;




defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");
class Report extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Api');
		$this->load->model('Model_Report');
		$this->load->model('Model_Admin');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
	}
	public function index()
	{
		
		$response = response("fail", array(), "Failed  ");
		echo $response;
		
	}
	public function getAttendance()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date1 = $post['date1'];
		$date2 = $post['date2'];
		$alocationid = $post['alocation'];
		$getData = $this->Model_Api->getAttendance($post['nik'],$date1,$date2, $alocationid);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to attendance ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a attendance ");
			echo $response ;
		}
	}
	public function getInvitation()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date1 = $post['date1'];
		$date2 = $post['date2'];

		$alocationid = $post['alocation'];

		$getData = $this->Model_Api->getInvitation($post['nik'],$date1,$date2,  $alocationid);
		if($getData['error'] == null ){
			$response = response("success", $getData['data'], "Success get data to invitation ");
			echo $response ;
		}else{
			$response = response("fail", $getData, "Failed error a invitation ");
			echo $response ;
		}
	}

	public function getMeeting()
	{
		$module_price           = $this->Model_Module->get_module_price();
        $module_invoice           = $this->Model_Module->get_module_invoice();


		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date1 = @$post['date1'];
		$date2 = @$post['date2'];
		$nik = @$post['nik'];
		$alocationid = @$post['alocation'];
		$waloc = "";
		if($alocationid != ""){
			$waloc .= " AND b.alocation_id='".$alocationid."' ";
		}
		if($date1 != null || $date2 != null){
			$waloc .= " AND b.date >= '".$date1."' ";
			$waloc .= " AND b.date <= '".$date2."' ";
		}

		$invoice = [];
        if( ($module_invoice['is_enabled']-0 ) == 1 && ($module_price['is_enabled']-0 ) == 1){
			$invoice = $this->Model_Api->getInvStatusName()['data'];
        }
        $q = "SELECT DISTINCT 
        	(SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant,
        	(SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_attendees, 
        	(SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id AND attendance_status =1 ) as num_present, 
        	(SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id AND (attendance_status = 0 OR attendance_status=2)) as num_absent, 
        	(SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id AND checkin=1 ) as num_checkin, 
        	b.*,
				r.description as room_description, 
				r.capacity as room_capacity, 
				r.google_map as room_google_map, 
				bui.name as building_name, 
				bui.detail_address as building_detail_address, 
				bui.google_map as building_google_map, 
				r.name as room_name, 
				r.location as room_location, 
				binv.invoice_status, 
				itext.name invoice_status_name,
				COALESCE(a.name,'') as alocation_name, 
				a.type as alocation_type, 
				a.invoice_status as alocation_invoice_status,
				COALESCE(at.invoice_status,0) as alcoation_type_invoice_status, 
				cost_total_booking, e.name as name_employee, 
				e.email as email_employee, 
				e.no_phone as phone_employee, 
				e.no_ext as ext_employee ,
				cat.name as category_name 
				FROM booking b ";
				$q .= " LEFT JOIN room_for_usage cat ON b.category=cat.id " ;
				$q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id " ;
				$q .= " LEFT JOIN room r  ON b.room_id=r.radid " ;
				$q .= " LEFT JOIN booking_invitation bi  ON b.booking_id=bi.booking_id " ;
				$q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id " ;
				$q .= " LEFT JOIN alocation_type at ON a.type=at.id " ;
				$q .= " LEFT JOIN employee e  ON bi.nik=e.nik " ;
				$q .= " LEFT JOIN setting_invoice_text itext  ON binv.invoice_status=itext.id " ;
				$q .= " LEFT JOIN building bui  ON r.building_id=bui.id " ;
				$q .= " WHERE 1=1 ";
				$q .= " AND bi.nik ='".$nik ."' ";
				$q .= $waloc;
				$q .= " ORDER BY b.id ASC ";
				$query = $this->Model_Api->querySql($q);
		$result = $query->result_array();
		$data = array(
			'invoice' => $invoice,
			'report' => $result 
		);
		echo response("success", $data, "Success get data to report");
		
	}

	public function getMeetingNew()
	{
		

		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$timezone = isset($post['timezone']) ? $post['timezone'] : APP_GMT;
		date_default_timezone_set($timezone);
		// 
		$date = @$post['date'];
		$nik = @$post['nik'];
		
		$years = isset($post['search']['years_search']) ? $post['search']['years_search'] : date("Y");
		$listmonth = [1,2,3,4,5,6,7,8,9,10,11,12];

        $whtotal_meeting = "AND bii.nik = '".$nik."' AND YEAR(b.date)='".$years."' ";
        $whtotal_present = $whtotal_meeting . " AND  bii.attendance_status=1";
        $whtotal_absent = $whtotal_meeting . " AND  ( bii.attendance_status=0 OR bii.attendance_status=2) ";
        $whtotal_ckin = $whtotal_meeting . " AND bii.checkin=1 ";

        $whtotal_meeting_all = "AND YEAR(b.date)='".$years."' ";
        $whtotal_present_all = $whtotal_meeting_all . " AND  bii.attendance_status=1";
        $whtotal_absent_all = $whtotal_meeting_all .  " AND  ( bii.attendance_status=0 OR bii.attendance_status=2) ";
        $whtotal_ckin_all = $whtotal_meeting_all . "  AND bii.checkin=1 ";

        $total_meeting = $this->Model_Report->getBookingReportTotalMeetingApps($whtotal_meeting);
        $total_present_meeting = $this->Model_Report->getBookingReportTotalMeetingApps($whtotal_present);
        $total_absent_meeting = $this->Model_Report->getBookingReportTotalMeetingApps($whtotal_absent);
        $total_checkin_meeting = $this->Model_Report->getBookingReportTotalMeetingApps($whtotal_ckin);
        $total_duration_meeting = $this->Model_Report->getBookingReportTotalDurationApps($whtotal_meeting);
        $total_saved_duration_meeting = $this->Model_Report->getBookingReportTotalDurationSavedApps($whtotal_meeting);

        $total_meeting_all = $this->Model_Report->getBookingReportTotalMeetingApps($whtotal_meeting_all);
        $total_present_all = $this->Model_Report->getBookingReportTotalMeetingApps($whtotal_present_all);
        $total_absent_all = $this->Model_Report->getBookingReportTotalMeetingApps($whtotal_absent_all);
        $total_ckin_all = $this->Model_Report->getBookingReportTotalMeetingApps($whtotal_ckin_all);

        $data = array(
			'total_meeting' => $total_meeting[0],
			'total_present' => $total_present_meeting[0],
			'total_absent' => $total_absent_meeting[0],
			'total_checkin' => $total_checkin_meeting[0],
			'total_duration' => $total_duration_meeting[0],
			'total_save_duration' => $total_saved_duration_meeting[0],
			'total_meeting_all' => $total_meeting_all[0],
			'total_present_all' => $total_present_all[0],
			'total_absent_all' => $total_absent_all[0],
			'total_ckin_all' => $total_ckin_all[0],

			// 'sss' => $total_meeting[0]["1"],
		);
		echo response("success", $data, "Success get data to report");
		
	}

	function getNameFromNumber($num) {
	    $numeric = $num % 26;
	    $letter = chr(65 + $numeric);
	    $num2 = intval($num / 26);
	    if ($num2 > 0) {
	        return getNameFromNumber($num2 - 1) . $letter;
	    } else {
	        return $letter;
	    }
	}

	public function exportTableToExcell()
    {

    	ob_end_clean();
    	// $json = file_get_contents("php://input");
		$post = $_GET;
		if(!isset($post['header']) || !isset($post['body'])){
			echo "";
			die();
		}
    	$this->output->set_content_type('text/html');

		// echo "<pre>";
		// print_r($post);
  
        $this->load->view('Report/table_meeting', array(
            'header' => json_decode($post['header'], TRUE),
            'body' => json_decode($post['body'], TRUE),
        ));
        // echo "<html></html>";

        
    }
	public function getMeetingDownload()
	{
		
		ob_end_clean();

		// $module_price           = $this->Model_Module->get_module_price();
        // $module_invoice           = $this->Model_Module->get_module_invoice();
		require_once APPPATH."third_party/phpspreadsheet/autoload.php";
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$alocationid =  @$_GET['alocation'];
		$date1 =  @$_GET['date1'];
		$date2 =  @$_GET['date2'];
		$nik =  @$_GET['nik'];
		$date = @$_GET['date2'];
		
		$waloc = "";
		if($alocationid != ""){
			$waloc .= " AND b.alocation_id='".$alocationid."' ";
		}
		if($date1 != null || $date2 != null){
			$waloc .= " AND b.date >= '".$date1."' ";
			$waloc .= " AND b.date <= '".$date2."' ";
		}
		// $invoice = $this->Model_Api->getInvStatusName()['data'];
		try{
			$q = "SELECT DISTINCT 
        	(SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant,
        	(SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_attendees, 
        	(SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id AND attendance_status =1 ) as num_present, 
        	(SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id AND (attendance_status = 0 OR attendance_status=2)) as num_absent, 
        	(SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id AND checkin=1 ) as num_checkin, 
        	b.*,
				r.description as room_description, 
				r.capacity as room_capacity, 
				r.google_map as room_google_map, 
				bui.name as building_name, 
				bui.detail_address as building_detail_address, 
				bui.google_map as building_google_map, 
				r.name as room_name, 
				r.location as room_location, 
				binv.invoice_status, 
				itext.name invoice_status_name,
				COALESCE(a.name,'') as alocation_name, 
				a.type as alocation_type, 
				a.invoice_status as alocation_invoice_status,
				COALESCE(at.invoice_status,0) as alcoation_type_invoice_status, 
				cost_total_booking, e.name as name_employee, 
				e.email as email_employee, 
				e.no_phone as phone_employee, 
				e.no_ext as ext_employee ,
				cat.name as category_name 
				FROM booking b ";
				$q .= " LEFT JOIN room_for_usage cat ON b.category=cat.id " ;
				$q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id " ;
				$q .= " LEFT JOIN room r  ON b.room_id=r.radid " ;
				$q .= " LEFT JOIN booking_invitation bi  ON b.booking_id=bi.booking_id " ;
				$q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id " ;
				$q .= " LEFT JOIN alocation_type at ON a.type=at.id " ;
				$q .= " LEFT JOIN employee e  ON bi.nik=e.nik " ;
				$q .= " LEFT JOIN setting_invoice_text itext  ON binv.invoice_status=itext.id " ;
				$q .= " LEFT JOIN building bui  ON r.building_id=bui.id " ;
				$q .= " WHERE 1=1 ";
				$q .= " AND bi.nik ='".$nik ."' ";
				$q .= $waloc;
				$q .= " ORDER BY b.id ASC ";
			$query = $this->Model_Api->querySql($q);
			$result = $query->result_array();
			$data = array(
				// 'invoice' => $invoice,
				'report' => $result 
			);
			// print_r($result );
			$spreadsheet = new Spreadsheet();
			// $spreadsheet->getProperties()
    		// 	->setCreator("Bio Experience")
    		// 	->setLastModifiedBy("Bio Experience")
    		// 	// ->setTitle("Smart Meeting Report")
    		// 	->setSubject("Smart Meeting Report");
    		$spreadsheet->setActiveSheetIndex(0);
    		// Create a new worksheet called "My Data"
			$sheet = $spreadsheet->getActiveSheet();
			$first_row = 1;
			$second_row = $first_row+0;
			$start_col = 4;
			$header_cell1 = $this->getNameFromNumber($start_col)."".$first_row;
			$header_cell1_last ="";
			$headers = array('No.', 'Subject/Title',"Date Time","Place","Detail Location","Note");
			for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
				$getHuruf= $this->getNameFromNumber(($start_col+($i )));
				$header_cell1_last =$getHuruf."".$first_row;
	            $sheet->setCellValueByColumnAndRow($start_col+($i + 1), $first_row, $headers[$i]);
	        }
	        $styleArray = [
			    'font' => [
			        'bold' => true,
			    ],
			    'alignment' => [
			        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			    'borders' => [
			        'allBorders' => [
			            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			        ],

			    ],
		   
			];
			$styleArray2 = [
			    'alignment' => [
			        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			    'borders' => [
			        'allBorders' => [
			            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			        ],

			    ],
		   
			];
			$sheet->getStyle($header_cell1.':'.$header_cell1_last)->applyFromArray($styleArray);
	        $num = 0;

	        $body_cell1 = $this->getNameFromNumber($start_col)."".($second_row+1);
			$body_cell1_last ="";
	        foreach ($result  as $rk => $val) {
	        	$getHuruf= $this->getNameFromNumber($start_col+6);
	        	$body_cell1_last =$getHuruf."".($num+1);

	        	$partisipantInt = $this->Model_Api->getInvitationInternalNik($val['booking_id'])['data'];
				$partisipantExt = $this->Model_Api->getInvitationExternal($val['booking_id'])['data'];

	        	$building=  isset($val['building_name']) ? $val['building_name'] . " - ": "";
				$building_location=  isset($booking['building_detail_address']) ? $val['building_detail_address'] . "  ": "";
				$extendTime = $val['extended_duration'] - 0;
				$tempat = $building . "".$val['room_name'];
				$location = $building_location . "".$val['room_location'];
	        	$explodeS = explode(" ", $val['start']);
	        	$end       = date('Y-m-d H:i:s', strtotime('+' . $extendTime . ' minutes', strtotime($val['end'])));
				$explodeE = explode(" ", $end);
				$meeting_start = $explodeS[1];
				$meeting_end = $explodeE[1];
				$meeting_date = $val['date'];
	        	$date_time_meeting = $this->Model_Admin->formatDate($meeting_date) . " " .$this->Model_Admin->formatTime($meeting_start) ."-". $this->Model_Admin->formatTime($meeting_end);

	        	$title_sheet =  $val['booking_id'];
	        	$sheet->setCellValueByColumnAndRow($start_col+1, $second_row+($num+1), $num+1);
	        	$sheet->setCellValueByColumnAndRow($start_col+2, $second_row+($num+1), $val['title']);
	        	$sheet->setCellValueByColumnAndRow($start_col+3, $second_row+($num+1), $date_time_meeting);
	        	$sheet->setCellValueByColumnAndRow($start_col+4, $second_row+($num+1), $tempat);
	        	$sheet->setCellValueByColumnAndRow($start_col+5, $second_row+($num+1), $location);
	        	$sheet->setCellValueByColumnAndRow($start_col+6, $second_row+($num+1), $val['note']);
	        	$date_time_booking = $this->Model_Admin->formatDate($meeting_date) ."__".$val['booking_id'];
	        	$title_ = str_replace(" ","_",$val['title']);
	        	$title_ = str_replace("-","__",$title_);
	        	$date_time_ = str_replace(" ","_",$date_time_booking);
	        	$date_time_ = str_replace("-","T",$date_time_);
				$title_sheet = $title_sheet;
				$myWorkSheet = new Worksheet($spreadsheet,$title_sheet);
				$spreadsheet->addSheet($myWorkSheet, $rk +1);

				$anotherSheet = $spreadsheet->getSheetByName($title_sheet);

				$anotherSheet->setCellValueByColumnAndRow($start_col+1, $second_row+($num+1), "Subject");
	        	$anotherSheet->setCellValueByColumnAndRow($start_col+2, $second_row+($num+1), $val['title']);

	        	$anotherSheet->setCellValueByColumnAndRow($start_col+1, $second_row+($num+2), "Date Time");
	        	$anotherSheet->setCellValueByColumnAndRow($start_col+2, $second_row+($num+2), $date_time_meeting);

	        	$anotherSheet->setCellValueByColumnAndRow($start_col+1, $second_row+($num+3), "Place");
	        	$anotherSheet->setCellValueByColumnAndRow($start_col+2, $second_row+($num+3), $tempat);

	        	$anotherSheet->setCellValueByColumnAndRow($start_col+1, $second_row+($num+4), "Detail Location");
	        	$anotherSheet->setCellValueByColumnAndRow($start_col+2, $second_row+($num+4), $location);

	        	$anotherSheet->setCellValueByColumnAndRow($start_col+1, $second_row+($num+5), "Note");
	        	$anotherSheet->setCellValueByColumnAndRow($start_col+2, $second_row+($num+5), $val['note']);

	        	$anotherSheet->setCellValueByColumnAndRow($start_col+1, $second_row+($num+7), "Partisipan/audience");

	        	$anotherSheet->setCellValueByColumnAndRow($start_col+1, $second_row+($num+9), "Name");
	        	$anotherSheet->setCellValueByColumnAndRow($start_col+2, $second_row+($num+9), "Email");
	        	$anotherSheet->setCellValueByColumnAndRow($start_col+3, $second_row+($num+9), "Phone");
	        	$anotherSheet->setCellValueByColumnAndRow($start_col+4, $second_row+($num+9), "Ext Number");
	        	$anotherSheet->setCellValueByColumnAndRow($start_col+5, $second_row+($num+9), "Internal");

	        	$partisipRow = 11;
	        	$numpart = 0;
	        	foreach ($partisipantInt as $k => $vpi) {
	        		$anotherSheet->setCellValueByColumnAndRow($start_col+1, $partisipRow+($numpart+1), $vpi['name']);
	        		$anotherSheet->setCellValueByColumnAndRow($start_col+2, $partisipRow+($numpart+1), $vpi['email']);
	        		$anotherSheet->setCellValueByColumnAndRow($start_col+3, $partisipRow+($numpart+1), $vpi['no_phone']);
	        		$anotherSheet->setCellValueByColumnAndRow($start_col+4, $partisipRow+($numpart+1), $vpi['no_ext']);
	        		$anotherSheet->setCellValueByColumnAndRow($start_col+5, $partisipRow+($numpart+1), "YES");
					
					$numpart ++;
				}
				$partisipEksRow = $numpart;
				$numpart2 = 0;
				foreach ($partisipantExt as $k => $vpi) {
	        		$anotherSheet->setCellValueByColumnAndRow($start_col+1, $partisipEksRow+($numpart2+1), $vpi['name']);
	        		$anotherSheet->setCellValueByColumnAndRow($start_col+2, $partisipEksRow+($numpart2+1), $vpi['email']);
	        		$anotherSheet->setCellValueByColumnAndRow($start_col+3, $partisipEksRow+($numpart2+1),"");
	        		$anotherSheet->setCellValueByColumnAndRow($start_col+4, $partisipEksRow+($numpart2+1), "");
	        		$anotherSheet->setCellValueByColumnAndRow($start_col+5, $partisipEksRow+($numpart2+1), "NO");
					$numpart2 ++;
				}
	        	$num++;
	        }
	        $dat_ = date('YmdHis');
			$fileName = 'SMR_'.$dat_.'.xlsx';

			$sheet->getStyle($body_cell1.':'.$body_cell1_last)->applyFromArray($styleArray2);
			$writer = new Xlsx($spreadsheet);
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        	header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        	$writer->save('php://output');
			// $writer->save('hello world.xlsx');	

			// echo response("success", $data, "Success get data to report");
		}catch(Exception $error){

			$response = response("fail", $error, "Failed error a report ");
			echo $error ;
		}
		
	}



	
	public function getInvoice()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		$date1 = $post['date1'];
		$date2 = $post['date2'];
		$alocationid = $post['alocation'];
		$waloc = "";
		if($alocationid != ""){
			$waloc = " AND b.alocation_id='".$alocationid."' ";
		}
		// $year = $post['year']-0;
		$nik = $post['nik'];
		// $q = "SELECT big.*,  ";
			$q = "SELECT COALESCE(null,'".$date1."') as date1,COALESCE(null,'".$date2."') as date2, 
				a.invoice_status alocation_status, 
				COALESCE(at.invoice_status,0) alocation_type_status, 
				SUM(cost_total_booking) total_price, 
				COALESCE(a.name,'') as alocation_name, 
				COALESCE(a.id,'') alocation_id 
				FROM booking b ";
				$q .= " INNER JOIN booking_invoice binv ON b.booking_id=binv.booking_id " ;
				$q .= " INNER JOIN room r  ON b.room_id=r.radid " ;
				$q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id " ;
				$q .= " LEFT JOIN alocation a ON b.alocation_id=a.id " ;
				$q .= " LEFT JOIN alocation_type at ON a.type=at.id " ;
				$q .= " LEFT JOIN employee e  ON bi.nik=e.nik " ;
				$q .= " WHERE b.date >='". $date1."' ";
				$q .= " AND b.date <='".$date2."' ";
				$q .= " AND bi.nik ='".$nik ."' ";
				$q .= $waloc;
				$q .= " GROUP BY b.alocation_id, a.name, a.id, a.invoice_status, at.invoice_status ";
			$query = $this->Model_Api->querySql($q);
			$result = $query->result_array();
			echo response("success", $result, "Get success");
	}


}

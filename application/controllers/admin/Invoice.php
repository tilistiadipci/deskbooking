<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use PhpOffice\PhpSpreadsheet\IOFactory;
class Invoice extends CI_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
		$this->load->helper('response');
		if($this->session->userdata('logged-in')){
			if($this->session->userdata('levelid-nya') == 1){
				// redirect('authentication');
			}else if($this->session->userdata('levelid-nya') == 2){
				// 
			}else{
				redirect('authentication');

			}		}else{
			redirect('authentication');
		}
	}
	public function index()
	{
		$pagename = "Invoice Meeting Order";
		$settinggeneral = $this->Model_Admin->getSettingDataGeneral()['data'];
		$menu = $this->Model_Menu->getMenu($pagename);
		$statusName = $this->Model_Admin->getInvStatusName()['data'];
		$alocation = $this->getAlocationData();
		$statusInvoice = $this->getInvoiceStatusZZZZ();
		// $booking = $this->getBooking();
		// print_r($statusName);
		$booking = array();
		// die();
		$this->load->view('Admin/Invoice/index', array(
			'menumaster'=> $menu, 
			'pagename' => $pagename, 
			'booking' => $booking, 
			'settinggeneral'=> json_encode($settinggeneral),
			'statusname'=> json_encode($statusName),
			'alocation' => $alocation, 
			'statusInvoice' => $statusInvoice, 
			'statusInvoiceJson' => json_encode($statusInvoice), 
			)
		);
	}
	public function pushNotification($title, $body,$batch )
	{
		$datetime = date("Y-m-d H:i:s");
		$config_notif = $this->Model_Notif->get_config();
		$collect = array();
		foreach ($batch as $key => $value) {
			$config = array(
				'url' => $config_notif['url'],
				'authorization' => $config_notif['authorization'],
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
	public function month_name($month)
	{
		$nM = array('','Jan','Feb','Mar', 'Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$m = $month-0;
		// echo $nM[$m];
		// die();
		return $nM[$m];
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
		return $h . ":". $nM[$m] . " ".$formatH;
	}
	private function getAlocationData()
	{
		$data = $this->Model_Admin->getAlocationWithType();
		return $data['data'];
		
	}
	private function getInvoiceStatusZZZZ()
	{
		$data = $this->Model_Admin->getInvStatusName();
		return $data['data'];
		
	}
	public function publishInvoice()
	{
		$post = $_POST;
		$datetime = date("Y-m-d H:i:s");
		$dataUpdate= array(
			'memo_no' => $post['memo'],
			'referensi_no' => $post['refrensi'],
			'time_send' => $datetime,
 		);
		$where = array(
			'booking_id' => $post['booking_id'],
		);
		try{
			$data = $this->Model_Admin->updateData('booking_invoice', $dataUpdate, $where);
			echo response("success", array(), "Save publish is success");
		}catch(Exception $error){
			echo response("fail", array(), "Save failed");
		}
	}
	public function sendInvoice()
	{
		$post = $_POST;
		$datetime = date("Y-m-d H:i:s");
		$usermake = $this->session->userdata('user-nya');
		$dataInv = $this->Model_Admin->getInvoiceDetail($post['invoice_id'])['data'];
		$dataPIC = $this->Model_Admin->querySql('SELECT am.nik, a.type, a.name FROM  alocation_matrix am
		INNER JOIN alocation a ON am.alocation_id=a.id
		WHERE am.alocation_id = "'.$dataInv['alocation_id'].'"  
		')->result_array();
		$dataUpdate= array(
			'memo_no' => $post['memo'],
			'referensi_no' => $post['refrensi'],
			'status' => "1",
			'date_sending' => $datetime,
			'sending_by' => $usermake,
			'updated_at' => $datetime,
			'updated_by' => $usermake,
			// 'invoice_status' => 1,
 		);
 		$dataUpdatebooking_invoice = array(
			'memo_no' => $post['memo'],
			'referensi_no' => $post['refrensi'],
			'invoice_status' => "1",
			'time_send' => $datetime,
			'updated_at' => $datetime,
			'updated_by' => $usermake,
			// 'invoice_status' => 1,
 		);
		$where = array(
			'invoice_id' => $post['invoice_id'],
		);
		$wherebooking_invoice = array(
			'invoice_generate_no' => $post['invoice_id'],
		);
		// print_r($dataInv);
		try{
			$data = $this->Model_Admin->updateData('booking_invoice_generate', $dataUpdate, $where);
			$data = $this->Model_Admin->updateData('booking_invoice', $dataUpdatebooking_invoice, $wherebooking_invoice);
			if(count($dataPIC) > 0){
				$single= $dataPIC[0];
				$notification_title = "Invoice No ".$dataInv['invoice_format'];
				$notification_body = "Period from ".$this->month_name($dataInv['invoice_month1']) . " to ". $this->month_name($dataInv['invoice_month2']) . " ".$dataInv['invoice_years'];
				$pNotif = $this->pushNotification($notification_title,$notification_body,$dataPIC);
			}
			$this->Model_Notif->insertNotifAdmin(10, "Send Invoice ", "Send invoice ". $post['invoice_id']);
			echo response("success", array(), "Send to finance is success");
		}catch(Exception $error){
			echo response("fail", array(), "Send failed");
		}
	}
	public function confirmInvoice()
	{
		$post = $_POST;
		$datetime = date("Y-m-d H:i:s");
		$usermake = $this->session->userdata('user-nya');
		$dataInv = $this->Model_Admin->getInvoiceDetail($post['invoice_id'])['data'];
		$dataPIC = $this->Model_Admin->querySql('SELECT am.nik, a.type, a.name FROM  alocation_matrix am
		INNER JOIN alocation a ON am.alocation_id=a.id
		WHERE am.alocation_id = "'.$dataInv['alocation_id'].'"  
		')->result_array();
		$dataUpdate= array(
			'memo_no' => $post['memo'],
			'referensi_no' => $post['refrensi'],
			'status' => "2",
			'date_confirm' => $datetime,
			'sending_by' => $usermake,
			'updated_at' => $datetime,
			'updated_by' => $usermake,
			// 'invoice_status' => 1,
 		);
 		$dataUpdatebooking_invoice = array(
			'memo_no' => $post['memo'],
			'referensi_no' => $post['refrensi'],
			'invoice_status' => "2",
			'time_paid' => $datetime,
			'updated_at' => $datetime,
			'updated_by' => $usermake,
			// 'invoice_status' => 1,
 		);
		$where = array(
			'invoice_id' => $post['invoice_id'],
		);
		$wherebooking_invoice = array(
			'invoice_generate_no' => $post['invoice_id'],
		);
		try{
			$data = $this->Model_Admin->updateData('booking_invoice_generate', $dataUpdate, $where);
			$data = $this->Model_Admin->updateData('booking_invoice', $dataUpdatebooking_invoice, $wherebooking_invoice);
			if(count($dataPIC) > 0){
				$single= $dataPIC[0];
				$notification_title = "Confirm Invoice No ".$dataInv['invoice_format'];
				$notification_body = "Period from ".$this->month_name($dataInv['invoice_month1']) . " to ". $this->month_name($dataInv['invoice_month2']) . " ".$dataInv['invoice_years'];
				$pNotif = $this->pushNotification($notification_title,$notification_body,$dataPIC);
			}
			$this->Model_Notif->insertNotifAdmin(10, "Confirm Invoice ", "confirm invoice ". $post['invoice_id']);
			echo response("success", array(), "Save publish is success");
		}catch(Exception $error){
			echo response("fail", array(), "Save failed");
		}
	}

	public function getDataFilter()
	{
		$get = $_GET;
		$month1 = date("m", strtotime($get['date1']))-0;
		$month2 = date("m", strtotime($get['date2']))-0;
		$year1 = date("Y", strtotime($get['date1']))-0;
		$year2 = date("Y", strtotime($get['date2']))-0;
		$q = "SELECT big.*,  ";
			$q .= "a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status ";
			$q .= " FROM booking_invoice_generate big " ;
			$q .= " LEFT JOIN alocation a  ON big.alocation_id=a.id " ;
			$q .= " LEFT JOIN alocation_type at ON a.type=at.name " ;
			$q .= " WHERE big.is_deleted=0 ";
			$q .= " AND ( big.invoice_month1 <= ".$month1." OR big.invoice_month2 >= ".$month1." ) ";
			$q .= " AND ( big.invoice_month1 <= ".$month2." OR big.invoice_month2 >= ".$month2." ) ";
			// $q .= " AND big.invoice_month2 <= ".$month2." ";
			$q .= " AND big.invoice_years >= ".$year1." ";
			$q .= " AND big.invoice_years <= ".$year2." ";
			if($get['statusInvoice'] != "" ){
				$q .= " AND  big.status='".$get['statusInvoice']."'  ";
			}
			if($get['alocation'] != ""){
				$q .= " AND  big.alocation_id='".$get['alocation']."'  ";
			}
			$q .= " AND big.is_deleted=0 ";
			$q .= " ORDER BY big.invoice_years DESC, big.invoice_month1 DESC ";
			// echo $q ;
			// $q .= " AND bi.is_pic =1 ";
			$query = $this->Model_Admin->querySql($q);
			$result = $query->result_array();
			echo response("success", $result, "Get success");
	}

	

	public function getData()
	{
		$from = $this->uri->segment(5);
		$to = $this->uri->segment(7);
		$data = $this->Model_Admin->getDataBookingInvoiceAlocationWithDate($from, $to);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function getDataYears()
	{
		$from = $this->uri->segment(5);
		$data = $this->Model_Admin->getDataBookingInvoiceWithYears($from);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function generateInvoice()
	{
		// $from = $this->uri->segment(5);
		// echo "<pre>";
		$post = $_POST;
		if(isset($post['invoice_month1']) && isset($post['invoice_month2']) && isset($post['invoice_years'])   ){
			$month1 = $post['invoice_month1'];
			$month2 = $post['invoice_month2'];
			$year = $post['invoice_years'];
			$generate = $this->Model_Admin->getDataBookingInvoiceAlocation($month1,$month2,$year );
			$m_1 = $this->month_name($month1);
			$m_2 = $this->month_name($month2);
			$this->Model_Notif->insertNotifAdmin(10, "Generate Invoice ", "generate invoice period ".$m_1 ."-".$m_2." " . $year);
			if($generate['error'] == null){
				echo response("success", $generate['data'], "Generate success");
			}else{
				echo response("fail", array(), "Generate failed, something wrong please call the administrator !!!");
			}
		}else{
			echo response("fail", array(), "Generate failed, please check the parameter !!!");
			// parameter wrong
		}
		
	}
	
	public function getDetailById()
	{
		$from = $this->uri->segment(4);
		$data = $this->Model_Admin->getDetailInvoiceById($from);
		
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", array(), "Get failed");
		}
	}
	public function getDataAlocationDetail()
	{
		$alocation = $this->uri->segment(5);
		$year = $this->uri->segment(7);
		$data = $this->Model_Admin->getDataBookingInvoiceAlocationWithDateDetail($year, $alocation);
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	public function submitreport()
	{
		print_r($_POST);
	}
	public function makeinvoice()
	{
		$post = $_POST;
		$data = $this->Model_Admin->getDataBookingInvoiceDetail($post['booking']);
			$html = $this->load->view('Admin/Invoice/printPreview1' , 
				array(
					"booking"=> array(),
					"price" => $post['price']
				)

			, true);
		
	}
	public function getQueryInvoice($bookid)
	{
		$q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, 
			binv.invoice_format,binv.invoice_no,
			binv.memo_no as memo_no , binv.referensi_no as referensi_no ,
			binv.rent_cost as invoice_rent_cost, binv.time_before as invoice_time_before, binv.time_send as invoice_time_send, binv.time_paid as invoice_time_paid,
			b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
			$q .= " INNER JOIN booking_invoice binv ON b.booking_id=binv.booking_id " ;
			$q .= " INNER JOIN room r  ON b.room_id=r.radid " ;
			$q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id " ;
			$q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id " ;
			$q .= " LEFT JOIN alocation_type at ON a.type=at.name " ;
			$q .= " LEFT JOIN employee e  ON bi.nik=e.nik " ;
			$q .= " WHERE binv.booking_id >='". $bookid."' ";
			// $q .= " AND b.date <='".$get['date2']."' ";
			// if($get['statusInvoice'] != "" ){
			// 	$q .= " AND  binv.invoice_status='".$get['statusInvoice']."'  ";
			// }
			// if($get['alocation'] != ""){
			// 	$q .= " AND  b.alocation_id='".$get['alocation']."'  ";
			// }
			// $q .= " AND b.is_canceled=0 ";
			$q .= " AND bi.is_pic =1 ";
			$query = $this->Model_Admin->querySql($q);
			$result = $query->row_array();
			return $result;
			// echo response("success", $result, "Get success");
	}
	private function getGenerateByInvoiceId($invoiceid)
	{
		$q = " SELECT big.*, a.name as alocation_name , a.type as alocation_type, a.invoice_status as alocation_status FROM booking_invoice_generate big ";
		$q .= " INNER JOIN alocation a ON big.alocation_id=a.id ";
		$q .= " WHERE invoice_id ='". $invoiceid."' ";
			$query = $this->Model_Admin->querySql($q);
			$result = $query->row_array();
			return $result;
	}
	private function getInvoiceDetailByInvoiceId($invoiceid)
	{
		$q = " SELECT big.*, b.booking_id, bi.invoice_status, b.title, r.name as room_name, a.name as alocation_name , a.type as alocation_type, a.invoice_status as alocation_status, ";
		$q .= " b.date, b.start, b.end, cost_total_booking as cost_of_book, b.total_duration as duration_of_meet, b.extended_duration, end_early_meeting,  ";
		$q .= " e.nik as e_nik, e.name as e_name, e.email as e_email, e.no_phone as e_phone, e.no_ext as e_ext  ";
		$q .= " FROM booking_invoice_generate big";
		$q .= " INNER JOIN booking_invoice bi ON big.invoice_id=bi.invoice_generate_no " ;
		$q .= " INNER JOIN booking b ON bi.booking_id=b.booking_id " ;
		$q .= " INNER JOIN booking_invitation binv ON b.booking_id=binv.booking_id " ;
		$q .= " LEFT JOIN employee e ON binv.nik=e.nik " ;
		$q .= " INNER JOIN room r ON b.room_id=r.radid " ;
		$q .= " INNER JOIN alocation a ON b.alocation_id=a.id ";
		$q .= " WHERE big.invoice_id ='". $invoiceid."' AND binv.is_pic=1 ";
			$query = $this->Model_Admin->querySql($q);
			$result = $query->result_array();
			return $result;
	}
	public function exportToInvoiceExcell()
	{
		$status = $this->uri->segment(4);
		$invoiceid = $this->uri->segment(5);
		switch ($status) {
			case 'before':
				# code...
				$this->exportToInvoiceBefore($invoiceid, "excell");
				break;
			case 'send':
				# code...
				$this->exportToInvoiceBefore($invoiceid, "excell");
				break;
			case 'paid':
				# code...
				$this->exportToInvoiceBefore($invoiceid, "excell");
				break;
			
			default:
				# code...
				break;
		}
	}
	public function exportToInvoiceBefore($invoiceid, $export)
	{
		$post = $_POST;
		// $export = $this->uri->segment(4);
		$company = $this->Model_Admin->getDataCompany()['data'];
		$invoiceConfig = $this->Model_Admin->getSettingInvoiceConfig()['data'];
		$statusInvoice = $this->getInvoiceStatusZZZZ();
		$date = date("Y-m-d");
		try{
			if($export  == "excell"){
				include APPPATH.'third_party/phpspreadsheet/autoload.php';
				if($invoiceid){
					$data = $this->getGenerateByInvoiceId($invoiceid);
					$detail = $this->getInvoiceDetailByInvoiceId($invoiceid);
					$employee_pic_nik =  array();
					$employee_pic_desc =  array();
					foreach ($detail as $kd => $vd) {
						$nnnn =  array_search($vd['e_nik'],$employee_pic_nik,true);
						if($nnnn < 0 || $nnnn == null){
							$dddd = array(
								"name" => $vd['e_name'],
								"nik" => $vd['e_nik'],
								"email" => $vd['e_email'],
								"phone" => $vd['e_phone'],
								"ext" => $vd['e_ext'],
							);
							array_push($employee_pic_nik, $vd['e_nik']);
							array_push($employee_pic_desc, $dddd);
						}
					}
					// print_r($employee_pic_nik);
					// die();
					$detailSampel = $detail[0];
					$datename = getformatDate($date);
					$filename = "INVOICE_".$data['invoice_format']."_".$datename ;
					$numCancel = count($data);
					if($data == null){
						echo "Something wrong to parameter!!!";
						die();
					}
					// start header_
					header_xls($filename);
					// end header_
					$spreadsheet = new Spreadsheet();
					$html = new PhpOffice\PhpSpreadsheet\Helper\Html();
					$sheet0 = $spreadsheet->getActiveSheet(0);
					$sheet0->setTitle('INVOICE ');

					$spreadsheet->createSheet();
					$sheet1 = $spreadsheet->getSheet(1);
					$sheet1->setTitle('TERLAMPIR ');

					$titleStyle = array(
					   'font'  => array(
					        'bold'  => true,
					        'color' => array('rgb' => '000000'),
					        'size'  => 22,

					    ),
					   'alignment' => array(
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					    ),
					);
					$headTableStyle = array(
					   'font'  => array(
					        'bold'  => true,
					        'color' => array('rgb' => '000000'),
					        'size'  => 32,

					    ),
					   'alignment' => array(
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					    ),

					);
					$companyStyle = array(
					   'font'  => array(
					        'bold'  => true,
					        'color' => array('rgb' => '000000'),
					        'size'  => 16,

					    )
					);
					$periodStyle = array(
						'alignment' => array(
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					    ),
						'borders'=> array(
						   'allBorders' => array(
						   		'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
						   ),
					    ),
					    'fill' => array(
				            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				            'color' => array('rgb' => '0000FF')
				        ),
					   'font'  => array(
					        'bold'  => true,
					        'color' => array('rgb' => 'FFFFFF'),
					        'size'  => 16,

					    )
					);
					$headColumnStyle = array(
						'alignment' => array(
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					    ),
					    'borders'=> array(
						   'allBorders' => array(
						   		'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
						   ),
					    ),
					    'fill' => array(
				            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				            'color' => array('rgb' => 'FFFF00')
				        ),
					    'font'  => array(
					        'bold'  => true,
					        'color' => array('rgb' => '000000'),
					        'size'  => 16,
					    )
					);
					$contentRowStyle = array(
						'borders'=> array(
						   'allBorders' => array(
						   		'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
						   ),
					    ),
					    'alignment' => array(
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
					        'wrapText' => true,
					    ),
					    'font'  => array(
					        'bold'  => false,
					        'color' => array('rgb' => '00000000'),
					        'size'  => 14,

					    )
					);
					$contentWrapStyle = array(
					    'alignment' => array(
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
					        'wrapText' => true,
					    ),
					    'font'  => array(
					        'bold'  => false,
					        'color' => array('rgb' => '00000000'),
					        'size'  => 14,

					    )
					);
					$contentRowTitleStyle = array(
					    'alignment' => array(
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
					         
					    ),
					    'font'  => array(
					        'bold'  => false,
					        'color' => array('rgb' => '00000000'),
					        'size'  => 14,

					    )
					);
					$footerTextStyle = array(
					    'alignment' => array(
					        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
					        'wrapText' => true,
					    ),
					    'font'  => array(
					        'bold'  => false,
					        'color' => array('rgb' => '00000000'),
					        'size'  => 14,

					    )
					);
					foreach (range('A', 'Z') as $char) {
					    $sheet0->getColumnDimension($char)->setAutoSize(true);
					}
					$icfg = $invoiceConfig;
			        // == TITLE
					$sheet0->setCellValue('C1', "INVOICE OF ".$data['invoice_format']);
					$sheet0->getStyle('C1')->applyFromArray($titleStyle);
					$sheet0->mergeCells("C1:F2");  
			        // 
			        $sheet0->setCellValue('C4', $icfg['to_text']);
			        $sheet0->setCellValue('D4', $detailSampel['alocation_name']);
					$sheet0->getStyle('D4')->applyFromArray($companyStyle);
					// 
					$colomUptext = "D";
					$rowUptextFrom = 5;
					$rowUptextTo = $rowUptextFrom;

					// for($nn=0)
					$sheet0->setCellValue('C5', $icfg['up_text']);
					foreach ($employee_pic_nik as $kr => $vr) {

						$sheet0->setCellValue('D'.$rowUptextTo, $employee_pic_desc[$kr]['name']);
						$rowUptextTo++;
					}
					 
					$sheet0->setCellValue('F4', $icfg['date_text']);
			        $sheet0->setCellValue('G4', $datename);
					$sheet0->getStyle('G4')->applyFromArray($contentRowStyle);
					// 
					$sheet0->setCellValue('F5', $icfg['no_inv_text']);
			        $sheet0->setCellValue('G5', $data['invoice_format']);
					$sheet0->getStyle('G5')->applyFromArray($contentRowStyle);
			        // 
					$sheet0->setCellValue('F6', $icfg['no_profit_text']);
			        $sheet0->setCellValue('G6', "");
					$sheet0->getStyle('G6')->applyFromArray($contentRowStyle);
					$ss_invoice = $this->checkInvoice($statusInvoice, $data['alocation_status'],$data['status'] );
					$sheet0->setCellValue('F6', "Status Invoice ");
			        $sheet0->setCellValue('G6', $ss_invoice);
					$sheet0->getStyle('G6')->applyFromArray($contentRowStyle);
					
					// $sheet0->getStyle('C4:F5')->applyFromArray($contentRowStyle);
			        // == TABLE
					$numrow = 9;
					$startnum = 9;
					$num = $rowUptextTo + ($rowUptextTo - $rowUptextFrom )+1;
					$num2 = $num+3 ;
					$numContent = $num2+3;
					$sheet0->getStyle('C'.$num.':F'.$num2)->applyFromArray($headColumnStyle);

					// echo $numContent;
					$sheet0->setCellValue('C'.$num , $icfg['description_text']);
					$sheet0->mergeCells("C".$num.":E".$num2);  
					$sheet0->getStyle("C".$num.":E".$num2)->applyFromArray($headTableStyle);

					$sheet0->setCellValue('F'.$num , $icfg['amount_text']);
					$sheet0->mergeCells("F".$num.":F".$num2);  
					$sheet0->getStyle("F".$num.":F".$num2)->applyFromArray($headTableStyle);
					$content_text = $icfg['content_text'];
					$content_text = str_replace("%bln1%",sprintf("%02d", $data['invoice_month1']) , $content_text);
					$content_text = str_replace("%bln2%",sprintf("%02d", $data['invoice_month2']) , $content_text);
					$content_text = str_replace("%tahun%",sprintf("%02d", $data['invoice_month2']) , $content_text);

					$cell_convert_content_text = $html->toRichTextObject($content_text);
					$sheet0->setCellValue('D'.$numContent, $cell_convert_content_text);
					$sheet0->getStyle("D".$numContent)->applyFromArray($contentWrapStyle);
					// CONTENT
					$sheet0->setCellValue('D'.($numContent+2)	, $icfg['amount_bill_text']);
					$tax_range = $icfg['tax_amount'];
					$tax_text = $icfg['tax_text'] . " " . $icfg['tax_amount']."%";
					$sheet0->setCellValue('D'.($numContent+3)	, $tax_text);
					$total_cost =  $data['total_cost'];
					$tax_ppn = $data['total_cost'] /$tax_range ; 
					$grand_total = $total_cost +$tax_ppn ;
					$sheet0->setCellValue('F'.($numContent+2)	, $total_cost);
					$sheet0->setCellValue('F'.($numContent+3)	, $tax_ppn);
					$sheet0->setCellValue('C'.($numContent+5)	, $icfg['total_text']);
					$sheet0->setCellValue('F'.($numContent+5)	, $grand_total);
					$sheet0->mergeCells("F".($numContent+5).":F".($numContent+6));  
					$sheet0->mergeCells("C".($numContent+5).":E".($numContent+6));  
					// FOOTER
					$footer_text = $icfg['footer_text'];
					$cell_convert_footer_text = $html->toRichTextObject($footer_text);
					$sheet0->setCellValue('C'.($numContent+8)	, $cell_convert_footer_text);
					$sheet0->setCellValue('C'.($numContent+10)	, $icfg['footer2_text']);
					$sheet0->setCellValue('C'.($numContent+11)	, $icfg['footer3_text']);
					$sheet0->getStyle("C".($numContent+8))->applyFromArray($footerTextStyle);
					$sheet0->mergeCells("C".($numContent+8).  ":D".($numContent+8) );  
					$sheet0->mergeCells("C".($numContent+10).  ":F".($numContent+10) );  

					
					// $footerTextStyle
					// $sheet0->mergeCells('C'.$num11.':'.'E'.$num11);  
					// $sheet0->setCellValue('F'.$num11, $icfg['amount_text']);
					
					// $sheet0->setCellValue('S'.$num11, "PIC No Phone");
					// $sheet0->setCellValue('T'.$num11, "PIC No Extension");
					// $sheet0->setCellValue('U'.$num11, "");
					
					// ===========================
					// TERLAMPOIR
					foreach (range('A', 'Z') as $char) {
					    $sheet1->getColumnDimension($char)->setAutoSize(true);
					}
					$rowAwal = 3;
					$rowTable11 = 4;
					$rowTable = 4;
					// $sheet0->mergeCells('C'.$rowAwal.':'.'E'.$num11);  
					$sheet1->setCellValue('C'.$rowAwal, "No.");
					$sheet1->setCellValue('D'.$rowAwal, "Booking No.");
					$sheet1->setCellValue('E'.$rowAwal, "Title ");
					$sheet1->setCellValue('F'.$rowAwal, "Date & Time ");
					$sheet1->setCellValue('G'.$rowAwal, "Duration");
					$sheet1->setCellValue('H'.$rowAwal, "Room Name");
					$sheet1->setCellValue('I'.$rowAwal, "Cost of Meeting");
					$sheet1->setCellValue('J'.$rowAwal, "PIC");
					$sheet1->setCellValue('K'.$rowAwal, "PIC Email");
					$sheet1->setCellValue('L'.$rowAwal, "PIC Phone");
					$sheet1->setCellValue('M'.$rowAwal, "PIC Ext");
					$sheet1->getStyle('C'.$rowAwal.':M'.$rowAwal)->applyFromArray($headColumnStyle);
					// $sheet0->setCellValue('I'.$rowAwal, "Cancel Order");
					
					// $accmulationHours = 0;
					$dnum = 0;
					foreach ($detail as $k => $row) {
						$dnum++;
						$start = date("H:i", strtotime($row['start']));
						$end = date( "H:i",strtotime($row['end']));

						$datetime = $row['date'] ." " .$start. " - ".$end;
						$dur = ($row['duration_of_meet']-0)+($row['extended_duration']-0);
						$inv_status = $row['alocation_status'];
						$setHour = $dur/$row['duration_of_meet'];
						// $accmulationHours += $setHour;
						$invStt = $this->checkInvoice($statusInvoice, $inv_status,$row['invoice_status'] );
						$phone = ($row['e_phone'] == null ) ? "-" :$row['e_phone'];
						$email = ($row['e_email'] == null ) ? "-" :$row['e_email'];
						$noext = ($row['e_ext'] == null ) ? "-" :$row['e_ext'];

						// $level_cancel = ($row['level_cancel'] == 1)?"Admin":"PIC";

						$sheet1->setCellValue('C'.$rowTable, $dnum );
						$sheet1->setCellValue('D'.$rowTable, $row['booking_id']);
						$sheet1->setCellValue('E'.$rowTable, $row['title']);
						$sheet1->setCellValue('F'.$rowTable, $datetime . "Hours");
						$sheet1->setCellValue('G'.$rowTable, $setHour);
						$sheet1->setCellValue('H'.$rowTable, $row['room_name']);
						$sheet1->setCellValue('I'.$rowTable, $row['cost_of_book']);
						$sheet1->setCellValue('J'.$rowTable, $row['e_name']);
						$sheet1->setCellValue('K'.$rowTable, $email);
						$sheet1->setCellValue('L'.$rowTable, $phone);
						$sheet1->setCellValue('M'.$rowTable, $noext);
						$rowTable++;
					}
					$sheet1->getStyle('C'.$rowTable11.':M'.($rowTable-1))->applyFromArray($contentRowStyle);
					// $getRow = $data[0];
					// $sheet0->setCellValue('F4', "ALOCATION NAME");
					// $sheet0->setCellValue('G4', $alocationdata['name']);
					// $sheet0->setCellValue('H4', $alocationdata['name_type']);
					// $sheet0->setCellValue('F5', "ACCUMULATION HOURS:");
					// $sheet0->setCellValue('G5', $accmulationHours . " hour");
					// $sheet0->getStyle('F4:H5')->applyFromArray($contentRowTitleStyle);
					$spreadsheet->setActiveSheetIndex(0);

					$writer = new Xlsx($spreadsheet);
					$writer->save('php://output');
					// echo "download";
				}else{
					echo "error make excell !!!";
				} 
			}else{
				echo "No format"; 
			}
			
		}catch(Exeption $rror){
			echo json_encode($rror);
			die();
		}
	}
	private function checkInvoice($result, $sttEnable, $sttInvoice){
		if($sttEnable == 0 || $sttEnable == "0"){
			return $result[3]['name'];
		}else{
			$stt = $sttInvoice-0;
			$rt = "";
			foreach ($result as $key => $value) {
				if($value['id'] == $sttInvoice){
					$rt = $value['name'];
				}
			}
			return $rt;
		}
	}
	
	public function getDataAlocationDetailExcell()
	{
		$alocation = $this->uri->segment(5);
		$year = $this->uri->segment(7);
		$data = $this->Model_Admin->getDataBookingInvoiceAlocationWithDateDetail($year, $alocation);
		if($data['error'] == null){
			if(count($data['data']) > 0){
				$ext = "XLS/".$data['data'][0]['alocation_id']."/ALOCATION_".$year."_".$invoice[0]['alocation_name'].".xls";
				$filename = $ext;
				// echo response("success", $data['data'], "Get success");
				header('Content-Disposition: attachment; filename='.$filename);
				$this->load->view('Admin/Invoice/print-detailalocation-excell' , 
					array(
						'detail' => $data['data']
					));
			}else{
				echo response("fail", $data, "Get failed");
			}
			
		}else{
			echo response("fail", $data, "Get failed");
		}
	}
	
}

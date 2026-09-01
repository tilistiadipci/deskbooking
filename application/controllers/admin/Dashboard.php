<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
		$this->load->model('Model_Admin');
		$this->load->helper('response');
		// $this->load->helper('log');
		if($this->session->userdata('logged-in')){
			
		}else{
			redirect('authentication');
		}
	}
	public function index()
	{
		// Dynamic Date filter
		$filter_date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');

		// Time-based Greeting
		$hour = date('H');
		if ($hour < 12) {
			$greeting = "Good morning";
		} elseif ($hour < 18) {
			$greeting = "Good afternoon";
		} else {
			$greeting = "Good evening";
		}
		
		$menu = $this->Model_Menu->getMenu("Dashboard");
		$pagename = "Dashboard";
		$employee = $this->Model_Admin->getDataEmployee();
		$booking = $this->Model_Admin->getDataBookingInvoice();
		$room = $this->Model_Admin->getDataRoom();
		
		// New Dashboard Metrics
		$total_desks = $this->Model_Admin->querySql("SELECT COUNT(*) as count FROM desk_room_table WHERE is_deleted=0")->row_array()['count'];
		$no_shows = 0; // Placeholder for no-shows logic
		$checkins_today = 0; // Placeholder for check-ins logic
		$month = date('Y-m', strtotime($filter_date));
		
		$is_admin = ($this->session->userdata('levelid-nya') == 1 || $this->session->userdata('levelid-nya') == 3);
		$user_nik = $this->session->userdata('user-nya');

		if ($is_admin) {
			$today_bookings = $this->Model_Admin->querySql("SELECT COUNT(*) as count FROM desk_booking WHERE is_expired=0 AND is_deleted=0 AND date='$filter_date'")->row_array()['count'];
			$status_active = $this->Model_Admin->querySql("SELECT COUNT(*) as count FROM desk_booking WHERE is_expired=0 AND is_deleted=0 AND DATE_FORMAT(date, '%Y-%m')='$month'")->row_array()['count'];
			$status_expired = $this->Model_Admin->querySql("SELECT COUNT(*) as count FROM desk_booking WHERE is_expired=1 AND is_deleted=0 AND DATE_FORMAT(date, '%Y-%m')='$month'")->row_array()['count'];
			$status_cancelled = $this->Model_Admin->querySql("SELECT COUNT(*) as count FROM desk_booking WHERE is_deleted=1 AND DATE_FORMAT(date, '%Y-%m')='$month'")->row_array()['count'];
		} else {
			// For user, scope by invitation nik
			$today_bookings = $this->Model_Admin->querySql("SELECT COUNT(DISTINCT db.booking_id) as count FROM desk_booking db JOIN desk_booking_invitation dbi ON db.booking_id = dbi.booking_id WHERE db.is_expired=0 AND db.is_deleted=0 AND db.date='$filter_date' AND dbi.nik='$user_nik'")->row_array()['count'];
			$status_active = $this->Model_Admin->querySql("SELECT COUNT(DISTINCT db.booking_id) as count FROM desk_booking db JOIN desk_booking_invitation dbi ON db.booking_id = dbi.booking_id WHERE db.is_expired=0 AND db.is_deleted=0 AND DATE_FORMAT(db.date, '%Y-%m')='$month' AND dbi.nik='$user_nik'")->row_array()['count'];
			$status_expired = $this->Model_Admin->querySql("SELECT COUNT(DISTINCT db.booking_id) as count FROM desk_booking db JOIN desk_booking_invitation dbi ON db.booking_id = dbi.booking_id WHERE db.is_expired=1 AND db.is_deleted=0 AND DATE_FORMAT(db.date, '%Y-%m')='$month' AND dbi.nik='$user_nik'")->row_array()['count'];
			$status_cancelled = $this->Model_Admin->querySql("SELECT COUNT(DISTINCT db.booking_id) as count FROM desk_booking db JOIN desk_booking_invitation dbi ON db.booking_id = dbi.booking_id WHERE db.is_deleted=1 AND DATE_FORMAT(db.date, '%Y-%m')='$month' AND dbi.nik='$user_nik'")->row_array()['count'];
		}
		
		$dashboard_data = array(
			'greeting' => $greeting,
			'filter_date' => $filter_date,
			'menumaster'=> $menu, 
			'pagename' => $pagename,
			'booking' => $booking['data'],
			'employee' => $employee['data'],
			'room' => $room['data'],
			'total_desks' => $total_desks,
			'today_bookings' => $today_bookings,
			'no_shows' => $no_shows,
			'checkins_today' => $checkins_today,
			'status_active' => $status_active,
			'status_expired' => $status_expired,
			'status_cancelled' => $status_cancelled,
			'user_name' => $this->session->userdata('name') ?: 'User'
		);

		if($this->session->userdata('levelid-nya') == 1){
			// admin dashboard
			$this->load->view('Admin/Dashboard/dashboard', $dashboard_data);

		}else if($this->session->userdata('levelid-nya') == 2){
			// user
			$dashboard_data['is_user'] = true;
			$this->load->view('Admin/Dashboard/dashboard-user', $dashboard_data);
			
		}else if($this->session->userdata('levelid-nya') == 3){
			$this->load->view('Admin/Dashboard/dashboard', $dashboard_data);

		}else{
			redirect('authentication');
		}
		
	}

	public function getChartBooking()
	{
		$tahun1 = ($this->uri->segment(5) - 0);
		$tahun = ($this->uri->segment(5) - 0)+1;
		$now = $tahun."-01-01";
		$sql = "SELECT t1.month, t1.md, coalesce(SUM(t1.amount+t2.amount), 0) as total, t1.tahun FROM 
				( 
				select DATE_FORMAT(a.Date,'%b') as month,
			  DATE_FORMAT(a.Date, '%m-%Y') as md,
			  '0' as  amount,
			  DATE_FORMAT(a.Date, '%Y') as tahun
			  from (
			    select '".$now."'- INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
			    from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
			    cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
			    cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
			  ) a
			  where a.Date <= '".$now."' and a.Date >= Date_add( '".$now."'	 ,interval - 12 month)
			  group by md
				) as t1
			left join
			(
			  SELECT DATE_FORMAT(db.date, '%b') AS date ,DATE_FORMAT(db.date, '%m-%Y') as md, COUNT(DISTINCT db.booking_id) as amount, DATE_FORMAT(db.date, '%Y') as tahun
			  FROM desk_booking db
              ".(($this->session->userdata('levelid-nya') == 2) ? " JOIN desk_booking_invitation dbi ON db.booking_id = dbi.booking_id " : "")."
			  where db.Date <= '".$now."' and db.Date >= Date_add( '".$now."',interval - 12 month)
			  ".(($this->session->userdata('levelid-nya') == 2) ? " AND dbi.nik='".$this->session->userdata('user-nya')."'" : "")."
			  group by md
			)t2
			on t1.md = t2.md 
			WHERE t1.tahun = '".$tahun1."'
			group by t1.md
			order by t1.md
		";
		
		try{
			$getData = $this->Model_Admin->querySql($sql);
			$sn = $getData->result_array();
			echo response("success", $sn, "Get success");
		}catch(Exception $error){
			$sn = array(
				"error" => $error
			);
			echo response("fail", $sn, "Get failed");
		}
	}

	public function getChartTopRoom()
	{
		$tahun1 = ($this->uri->segment(5) - 0);
		$tahun = ($this->uri->segment(5) - 0)+1;
		$now = $tahun."-01-01";
		$sql = "SELECT CONCAT(r.name, ' - Desk #', LPAD(rt.block_number, 2, '0')) as name, COUNT(DISTINCT b.booking_id) as total 
				FROM desk_booking b 
				LEFT JOIN desk_room r ON b.room_id = r.id 
				LEFT JOIN desk_room_table rt ON b.desk_id = rt.desk_id 
                ".(($this->session->userdata('levelid-nya') == 2) ? " JOIN desk_booking_invitation dbi ON b.booking_id = dbi.booking_id " : "")."
				WHERE YEAR(b.date)='".$tahun1."' AND b.is_deleted=0 AND b.is_expired=0
				".(($this->session->userdata('levelid-nya') == 2) ? " AND dbi.nik='".$this->session->userdata('user-nya')."'" : "")."
				GROUP BY b.desk_id 
				ORDER BY total DESC 
				LIMIT 5";

		try{
			$getData = $this->Model_Admin->querySql($sql);
			$sn = $getData->result_array();
			echo response("success", $sn, "Get success");
		}catch(Exception $error){
			$sn = array(
				"error" => $error
			);
			echo response("fail", $sn, "Get failed");
		}
	}
	public function getOngoing()
	{
		$date1 = $this->uri->segment(5);
		$date2 = $this->uri->segment(6);
		if($date1 == ""){
			$date1 = date("Y-m-d"); 
		}
		if($date1 == ""){
			$date2 = date("Y-m-d"); 
		}
		if($this->session->userdata('levelid-nya') == 1){
			$sql = "SELECT b.*, r.name, 
				TIMESTAMPDIFF(MINUTE,b.start,b.end) duration
				FROM desk_booking b
			LEFT JOIN desk_room r ON b.room_id=r.id
			WHERE b.is_expired=0 AND b.date>='".$date1."' AND b.date<='".$date2."'  
			ORDER BY b.start DESC
			";

		}else if($this->session->userdata('levelid-nya') == 2){
			// user
			$nik = $this->session->userdata('user-nya');
			$sql = "SELECT b.*, r.name, 
				TIMESTAMPDIFF(MINUTE,b.start,b.end) duration
				FROM desk_booking b
			LEFT JOIN desk_room r ON b.room_id=r.id
			LEFT JOIN desk_booking_invitation bi ON b.booking_id=bi.booking_id
			WHERE b.is_expired=0 AND b.date>='".$date1."' AND b.date<='".$date2."'  
			AND internal=1 AND nik='".$nik."'
			ORDER BY b.start DESC
			";
		}else{
			echo response("fail", array(), "You don't have any access");
			die();
		}
		try{
			$getData = $this->Model_Admin->querySql($sql);
			$sn = $getData->result_array();
			echo response("success", $sn, "Get success");
		}catch(Exception $error){
			$sn = array(
				"error" => $error
			);
			echo response("fail", $sn, "Get failed");
		}
	}
	
}

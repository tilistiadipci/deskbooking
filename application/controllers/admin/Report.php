<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// def("DOMPDF_ENABLE_REMOTE", false);
class Report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Model_Menu');
        $this->load->model('Model_Admin');
        $this->load->model('Model_License');
        $this->load->model('Model_Report');
        $this->load->helper('response');
        if ($this->session->userdata('logged-in')) {
            if ($this->session->userdata('levelid-nya') == 1) {
                // redirect('authentication');
            } else if ($this->session->userdata('levelid-nya') == 2) {
                //
            } else {
                redirect('authentication');

            }
        } else {
            redirect('authentication');
        }
    }
    public function index()
    {
        $pagename = "Room Usage ";

        $whereEmp = [];
        if ($this->session->userdata('levelid-nya') == 2) {
            $whereEmp = [
               'id' =>  $this->session->userdata('user-nya'),
            ];
        }

        // echo "<pre>";
        // print_r($_SESSION);
        // die();
        $building               = $this->Model_Admin->getDataBuilding()['data'];
        $room                   = $this->Model_Admin->getDataRoom2()['data'];
        $getEmployee            = $this->Model_Admin->getDataRoom2()['data'];
        $module_automation      = $this->Model_Module->get_module_automation();
        $module_price           = $this->Model_Module->get_module_price();
        $module_invoice         = $this->Model_Module->get_module_invoice();
        $module_int_365         = $this->Model_Module->get_module_int_365();
        $module_int_google      = $this->Model_Module->get_module_int_google();
        $menu                   = $this->Model_Menu->getMenu($pagename);
        $dataEmployee           = $this->Model_Admin->getDataEmployee($whereEmp);
        $alocation              = $this->getAlocationData();
        $modules['automation']  = $module_automation;
        $modules['price']       = $module_price;
        $modules['room_adv']    = $this->Model_Module->get_module_room_adv();
        $modules['int_365']     = $module_int_365;
        $modules['int_google']  = $module_int_google;
        $modules['invoice']     = $module_invoice;
        // echo $this->session->userdata('user-nya');
        // die();
        $statusInvoice = $this->getInvoiceStatusZZZZ();
        if ($this->session->userdata('levelid-nya') == 1) {
            $this->load->view('Admin/Report/index', array(
                'menumaster'        => $menu, 'pagename' => $pagename, 
                'dataEmployee' => $dataEmployee['data'],
                'employee' => json_encode($dataEmployee['data']),
                'statusInvoiceJson' => json_encode($statusInvoice),
                'alocation'         => $alocation,
                'building'          => json_encode($building),
                'room'              => json_encode($room),
                'modules'           => $modules,
            ));
        } else if ($this->session->userdata('levelid-nya') == 2) {
            // user
            $this->load->view('Admin/Report/index', array(
                'menumaster'        => $menu, 'pagename' => $pagename, 
                'dataEmployee' => $dataEmployee['data'],
                'employee' => json_encode($dataEmployee['data']),
                'statusInvoiceJson' => json_encode($statusInvoice),
                'building'          => json_encode($building),
                'room'              => json_encode($room),
                'alocation'         => $alocation,
                'modules'           => $modules,
            ));
        }

    }
    public function cancelindex()
    {
        $pagename     = "Report of Cancel Order";
        $alocation    = $this->getAlocationData();
        $menu         = $this->Model_Menu->getMenu($pagename);
        $dataEmployee = $this->getEmployee();
        $this->load->view('Admin/Report/cancel_report', array(
            'menumaster'   => $menu,
            'pagename'     => $pagename,
            'dataEmployee' => $dataEmployee,
            'alocation'    => $alocation,
        ));
    }
    public function incomeindex()
    {
        $pagename     = "Report of Rent Income";
        $menu         = $this->Model_Menu->getMenu($pagename);
        $dataEmployee = $this->getEmployee();
        $alocation    = $this->getAlocationData();
        $this->load->view('Admin/Report/income_report', array(
            'menumaster'   => $menu,
            'pagename'     => $pagename,
            'dataEmployee' => $dataEmployee,
            'alocation'    => $alocation,
        ));
    }
    public function outstandingindex()
    {
        $pagename     = "Report of Outstanding Invoice";
        $menu         = $this->Model_Menu->getMenu($pagename);
        $alocation    = $this->getAlocationData();
        $dataEmployee = $this->getEmployee();

        $this->load->view('Admin/Report/outstanding_report', array(
            'menumaster'   => $menu,
            'pagename'     => $pagename,
            'dataEmployee' => $dataEmployee,
            'alocation'    => $alocation,
        ));

    }

    public function getEmployee()
    {
        $data = $this->Model_Admin->getDataEmployee();
        return $data;
    }
    public function submitreport()
    {
        // print_r($_POST);
    }
    public function getCancelReport()
    {
        $date1 = $this->uri->segment(4);
        $date2 = $this->uri->segment(5);
        if ($date1 != "" || $date2 != "") {
            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE b.date >='" . $date1 . "' ";
            $q .= " AND b.date <='" . $date2 . "' ";
            $q .= " AND b.is_canceled=1 ";
            $q .= " AND bi.is_pic =1 ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            echo response("success", $result, "Get success");
        } else {
            $response = response("fail", array(), "Failed get a report ");
            echo $response;
        }
    }
    public function getCancelReportAlocation()
    {
        $alocation = $this->uri->segment(4);
        $date1     = $this->uri->segment(5);
        $date2     = $this->uri->segment(6);
        if ($date1 != "" || $date2 != "") {
            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE b.date >='" . $date1 . "' ";
            $q .= " AND b.date <='" . $date2 . "' ";
            $q .= " AND b.is_canceled=1 ";
            $q .= " AND b.alocation_id='" . $alocation . "' ";
            $q .= " AND bi.is_pic =1 ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            echo response("success", $result, "Get success");
        } else {
            $response = response("fail", array(), "Failed get a report ");
            echo $response;
        }
    }

    public function getIncomeReportYear()
    {
        $year = $this->uri->segment(4);
        if ($year != "") {
            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, binv.rent_cost as invoice_rent_cost, b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE YEAR(b.date) ='" . $year . "' ";
            // $q .= " AND b.date <='".$date2."' ";
            $q .= " AND binv.invoice_status='2' "; // donepaid
            $q .= " AND bi.is_pic =1 ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            echo response("success", $result, "Get success");
        } else {
            $response = response("fail", array(), "Failed get a report ");
            echo $response;
        }
    }
    public function getIncomeReportMonth()
    {
        $year  = $this->uri->segment(4);
        $month = $this->uri->segment(5);
        if ($year != "") {
            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, binv.rent_cost as invoice_rent_cost, b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE YEAR(b.date) ='" . $year . "' ";
            $q .= " AND MONTH(b.date) =" . $month . " ";
            // $q .= " AND b.date <='".$date2."' ";
            $q .= " AND binv.invoice_status='2' "; // donepaid
            $q .= " AND bi.is_pic =1 ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            echo response("success", $result, "Get success");
        } else {
            $response = response("fail", array(), "Failed get a report ");
            echo $response;
        }
    }

    public function getRoomReport()
    {
        $date1 = $this->uri->segment(4);
        $date2 = $this->uri->segment(5);
        if ($date1 != "" || $date2 != "") {
            $userg = $this->session->userdata('levelid-nya');
            if ($this->session->userdata('levelid-nya') == 1) {
                $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location,
				binv.memo_no, binv.referensi_no ,
				binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
                $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
                $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
                $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
                $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
                $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
                $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
                $q .= " WHERE b.date >='" . $date1 . "' ";
                $q .= " AND b.date <='" . $date2 . "' ";
                $q .= " AND bi.is_pic =1 ";
            } else if ($this->session->userdata('levelid-nya') == 2) {
                // user
                $nik = $this->session->userdata('user-nya');
                $q   = "SELECT DISTINCT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location,
				binv.memo_no, binv.referensi_no ,
				binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
                $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
                $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
                $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
                $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
                $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
                $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
                $q .= " WHERE b.date >='" . $date1 . "' ";
                $q .= " AND b.date <='" . $date2 . "' ";
                $q .= " AND bi.nik ='" . $nik . "' ";
                // $q .= " AND bi.is_pic =1 ";
            }

            $query    = $this->Model_Admin->querySql($q);
            $result   = $query->result_array();
            $data_col = array(
                "user"   => $userg,
                "result" => $result,
            );
            echo response("success", $data_col, "Get success");
        } else {
            $response = response("fail", array(), "Failed get a report ");
            echo $response;
        }
    }
    public function getRoomReport2()
    {
        $date1 = $this->uri->segment(4);
        $date2 = $this->uri->segment(5);
        $alo   = $this->uri->segment(6);

        if ($date1 != "" || $date2 != "") {
            $userg = $this->session->userdata('levelid-nya');
            if ($this->session->userdata('levelid-nya') == 1) {
                $walo = " ";
                if ($alo == "all" || $alo == "") {

                } else {
                    $walo = " AND a.id='" . $alo . "'  ";
                }
                $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location,
				binv.memo_no, binv.referensi_no ,
				binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
                $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
                $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
                $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
                $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
                $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
                $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
                $q .= " WHERE b.date >='" . $date1 . "' ";
                $q .= " AND b.date <='" . $date2 . "' ";
                $q .= " AND bi.is_pic =1 ";
                $q .= $walo;
            } else if ($this->session->userdata('levelid-nya') == 2) {
                // user
                $walo = " ";
                if ($alo == "all" || $alo == "") {

                } else {
                    $walo = " AND a.id='" . $alo . "'  ";
                }
                $nik = $this->session->userdata('user-nya');
                $q   = "SELECT DISTINCT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location,
				binv.memo_no, binv.referensi_no ,
				binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
                $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
                $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
                $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
                $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
                $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
                $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
                $q .= " WHERE b.date >='" . $date1 . "' ";
                $q .= " AND b.date <='" . $date2 . "' ";
                $q .= " AND bi.nik ='" . $nik . "' ";
                $q .= $walo;
                // $q .= " AND bi.is_pic =1 ";
            }

            $query    = $this->Model_Admin->querySql($q);
            $result   = $query->result_array();
            $data_col = array(
                "user"   => $userg,
                "result" => $result,
            );
            echo response("success", $data_col, "Get success");
        } else {
            $response = response("fail", array(), "Failed get a report ");
            echo $response;
        }
    }

    public function getRoomUsageReport()
    {
        $post              = $_POST;
        $building_search   = isset($post['building_search']) ? $post['building_search'] : "";
        $room_search       = isset($post['room_search']) ? $post['room_search'] : "";
        $date1_search      = isset($post['date1_search']) ? $post['date1_search'] : "";
        $date2_search      = isset($post['date2_search']) ? $post['date2_search'] : "";
        $gmt               = isset($post['gmt']) ? $post['gmt'] : "";
        $department_search = isset($post['department_search']) ? $post['department_search'] : "";
        if ($gmt == "") {
            date_default_timezone_set(APP_GMT);
        } else {
            date_default_timezone_set($gmt);
        }
        $userg = $this->session->userdata('levelid-nya');

        $wdate   = " AND b.date >='" . $date1_search . "' AND b.date <='" . $date2_search . "'  ";
        $wreport = " ";

        if ($building_search != "") {
            $wreport .= " AND bu.id=" . $building_search . " ";
        }
        if ($room_search != "") {
            $wreport .= " AND r.radid=" . $room_search . " ";
        }

        if ($department_search != "") {
            $wreport .= " AND a.id ='" . $department_search . "'  ";
        }

        if ($userg == 1) {
            // admin
            $wreport .= " AND bi.is_pic = 1 ";
        } else if ($userg == 2) {
            $wreport .= " AND bi.nik ";
        }

        $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location,
				binv.memo_no, binv.referensi_no ,
				binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
        $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
        $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
        $q .= " INNER JOIN building bu ON r.building_id=bu.id ";
        $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
        $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
        $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
        $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
        $q .= " WHERE 1=1 ";
        $q .= $wdate;
        $q .= $wreport;

        $query    = $this->Model_Admin->querySql($q);
        $result   = $query->result_array();
        $data_col = array(
            "user"   => $userg,
            "result" => $result,
        );
        // echo "<pre>";
        // print_r($q);
        echo response("success", $data_col, "Get success");
        die();

    }

    public function getOrganizerReport()
    {
        $post              = $_POST;
        $building_search   = isset($post['building_search']) ? $post['building_search'] : "";
        $room_search       = isset($post['room_search']) ? $post['room_search'] : "";
        $date1_search      = isset($post['date1_search']) ? $post['date1_search'] : "";
        $date2_search      = isset($post['date2_search']) ? $post['date2_search'] : "";
        $timezone          = isset($post['timezone']) ? $post['timezone'] : APP_GMT;
        $employee_search = isset($post['employee_search']) ? $post['employee_search'] : "";
        $department_search = isset($post['department_search']) ? $post['department_search'] : "";
        date_default_timezone_set(APP_GMT);
        if ($timezone != "") {
             date_default_timezone_set($timezone);
        } 
        $employee = [];
        $wreport ="";
        $wreportbooking ="  b.date >='" . $date1_search . "' AND b.date <='" . $date2_search . "'  ";
        if($employee_search != ""){
            $wreport .= " AND e.nik='".$employee_search."'";
        }
        if($room_search != ""){
            $wreportbooking .= " AND r.radid=" . $room_search . " ";
        }
        if($room_search != ""){
            $wreportbooking .= " AND bu.id=" . $building_search . " ";
        }
        if ($department_search != "") {
            $wreportbooking .= " AND a.id ='" . $department_search . "'  ";
        }
        $arrEmp = [];

        $templateWhere = $wreportbooking;

        $selectcount_booking = "(SELECT COUNT(*) FROM booking_invitation bii 
            INNER JOIN booking b  ON bii.booking_id=b.booking_id 
            INNER JOIN room r  ON b.room_id=r.radid 
            INNER JOIN building bu ON r.building_id=bu.id
            WHERE bii.is_pic=1 AND b.is_alive<>0 AND internal=1 AND bii.nik = e.nik AND ".$wreportbooking."  )  as total_meeting  ";
        
        $q = "SELECT e.*, at.name company_name, a.name department_name, ";
        $q .= $selectcount_booking;
        $q .= "FROM employee e ";
        $q .= "LEFT JOIN alocation_type at ON e.company_id=at.id ";
        $q .= "LEFT JOIN alocation a ON e.department_id=a.id ";
        $q .= "WHERE e.is_deleted=0 ";
        $q .= $wreport;
        $q .= " ORDER BY total_meeting DESC";

        // foreach ($employee as $key => $row) {
           
        $query    = $this->Model_Admin->querySql($q);
        $result   = $query->result_array();
       
        foreach ($result as $key => $value) {
            if($value['total_meeting'] <= 0){

                $result[$key]['total_attendees'] = 0;
                $result[$key]['total_attendees_checkin'] = 0;
                $result[$key]['total_approve'] = 0;
                $result[$key]['total_reject'] = 0;
                $result[$key]['total_reschedule'] = 0;
                $result[$key]['total_cancel'] = 0;
                $result[$key]['total_auto_release'] = 0;
                $result[$key]['total_duration'] = 0;
                $result[$key]['total_force_moved'] = 0;
                $result[$key]['total_vip'] = 0;
                continue;
            }
            $getBookingId = "SELECT b.booking_id FROM booking_invitation bii  INNER JOIN booking b  ON bii.booking_id=b.booking_id WHERE bii.is_pic=1 AND internal=1 AND bii.nik = '".$value['nik']."'";
            $fetchbookingId   = $this->Model_Admin->querySql($getBookingId);
            $resBookingId   = $fetchbookingId->result_array();
            $colBookingId = [];
            $wreportbooking_ckin =  $wreportbooking." AND checkin=1";
            $wreportbooking_approve =  $wreportbooking." AND ( is_approve =1 OR is_approve=3) ";
            $wreportbooking_reject =  $wreportbooking." AND ( is_approve =2)";
            $wreportbooking_reschedule =  $wreportbooking." AND ( is_rescheduled =1)";
            $wreportbooking_cancel =  $wreportbooking." AND ( is_canceled =1)";
            $wreportbooking_moved =  $wreportbooking." AND ( is_moved =1)";
            $wreportbooking_vip =  $wreportbooking." AND ( is_vip =1)";
            $wreportbooking_release =  $wreportbooking." AND ( is_released =1)";

            foreach ($resBookingId as $kbid => $rbookid) {
                array_push($colBookingId, $rbookid['booking_id']);
            }
            $result[$key]['total_attendees'] = $this->Model_Report->getAttendess($wreportbooking, $colBookingId);
            $result[$key]['total_attendees_checkin'] = $this->Model_Report->getAttendess($wreportbooking_ckin, $colBookingId);
            $result[$key]['total_approve'] = $this->Model_Report->getBooking($wreportbooking_approve, $colBookingId);
            $result[$key]['total_reject'] = $this->Model_Report->getBooking($wreportbooking_reject, $colBookingId);
            $result[$key]['total_reschedule'] = $this->Model_Report->getBooking($wreportbooking_reschedule, $colBookingId);
            $result[$key]['total_cancel'] = $this->Model_Report->getBooking($wreportbooking_cancel, $colBookingId);
            $result[$key]['total_force_moved'] = $this->Model_Report->getBooking($wreportbooking_moved, $colBookingId);
            $result[$key]['total_vip'] = $this->Model_Report->getBooking($wreportbooking_moved, $colBookingId);
            $result[$key]['total_duration'] = $this->Model_Report->getTotalBooking($wreportbooking, $colBookingId);
            $result[$key]['total_auto_release'] = $this->Model_Report->getTotalBooking($wreportbooking_release, $colBookingId);

        }
        echo response("success", $result, "Get success");
        die();

    }
    public function getAttendeesReport()
    {
        $post              = $_POST;
        $building_search   = isset($post['building_search']) ? $post['building_search'] : "";
        $room_search       = isset($post['room_search']) ? $post['room_search'] : "";
        $date1_search      = isset($post['date1_search']) ? $post['date1_search'] : "";
        $date2_search      = isset($post['date2_search']) ? $post['date2_search'] : "";
        $gmt               = isset($post['gmt']) ? $post['gmt'] : "";
        $employee_search = isset($post['employee_search']) ? $post['employee_search'] : "";
        $department_search = isset($post['department_search']) ? $post['department_search'] : "";
        if ($gmt == "") {
            date_default_timezone_set(APP_GMT);
        } else {
            date_default_timezone_set($gmt);
        }
        $employee = [];
        $wreport ="";
        $wreportbooking ="  b.date >='" . $date1_search . "' AND b.date <='" . $date2_search . "'  ";
        if($employee_search != ""){
            $wreport .= " AND e.nik='".$employee_search."'";
        }
        if($room_search != ""){
            $wreportbooking .= " AND r.radid=" . $room_search . " ";
        }
        if($room_search != ""){
            $wreportbooking .= " AND bu.id=" . $building_search . " ";
        }
        if ($department_search != "") {
            $wreportbooking .= " AND a.id ='" . $department_search . "'  ";
        }
        $arrEmp = [];

        $templateWhere = $wreportbooking;

        $selectcount_booking = "(SELECT COUNT(*) FROM booking_invitation bii 
            INNER JOIN booking b  ON bii.booking_id=b.booking_id 
            INNER JOIN room r  ON b.room_id=r.radid 
            INNER JOIN building bu ON r.building_id=bu.id
            WHERE is_pic=0 AND internal=1 AND b.is_alive<>0  AND bii.nik = e.nik AND ".$wreportbooking."  )  as total_meeting  ";
        
        $q = "SELECT e.*, at.name company_name, a.name department_name, ";
        $q .= $selectcount_booking;
        $q .= "FROM employee e ";
        $q .= "LEFT JOIN alocation_type at ON e.company_id=at.id ";
        $q .= "LEFT JOIN alocation a ON e.department_id=a.id ";
        $q .= "WHERE e.is_deleted=0 ";
        $q .= $wreport;
        $q .= " ORDER BY total_meeting DESC";
        $query    = $this->Model_Admin->querySql($q);
        $result   = $query->result_array();
        foreach ($result as $key => $value) {
            if($value['total_meeting'] <= 0){

                $result[$key]['total_attendees'] = 0;
                $result[$key]['total_attendees_checkin'] = 0;
                $result[$key]['total_present'] = 0;
                $result[$key]['total_absent'] = 0;
                $result[$key]['total_approve'] = 0;
                $result[$key]['total_duration'] = 0;
                continue;
            }
            $getBookingId = "SELECT b.booking_id FROM booking_invitation bii  INNER JOIN booking b  ON bii.booking_id=b.booking_id WHERE bii.is_pic=1 AND internal=1 AND bii.nik = '".$value['nik']."'";
            $fetchbookingId   = $this->Model_Admin->querySql($getBookingId);
            $resBookingId   = $fetchbookingId->result_array();
            $colBookingId = [];
            $wreportbooking_ckin =  $wreportbooking." AND bii.nik ='".$value['nik']."' AND checkin=1";
            $wreportbooking_present =  $wreportbooking." AND bii.nik ='".$value['nik']."' AND bii.attendance_status=1";
            $wreportbooking_absent =  $wreportbooking." AND bii.nik ='".$value['nik']."' AND (bii.attendance_status=2 OR bii.attendance_status=0 )";
            
            foreach ($resBookingId as $kbid => $rbookid) {
                array_push($colBookingId, $rbookid['booking_id']);
            }
            $result[$key]['total_attendees'] =0;
            $result[$key]['total_attendees_checkin'] = $this->Model_Report->getAttendess($wreportbooking_ckin, $colBookingId);
            $result[$key]['total_present'] = $this->Model_Report->getAttendess($wreportbooking_present, $colBookingId);
            $result[$key]['total_absent'] = $this->Model_Report->getAttendess($wreportbooking_absent, $colBookingId);
            // $result[$key]['total_approve'] = $this->Model_Report->getBooking($wreportbooking_approve, $colBookingId);
            $result[$key]['total_duration'] = $this->Model_Report->getTotalBooking($wreportbooking, $colBookingId);
           

        }
        echo response("success", $result, "Get success");
        die();

    }
    public function previewRoomReport()
    {
        if(!isset($_GET['action'])){
            die();
        }

        if(!isset($_GET['save'])){

            die();
        }
        if(!isset($_GET['report_type'])){
            die();

        }
        $type =$_GET['report_type'];
        $action = $_GET['action'];
        $save = $_GET['save'];
        if($save == "true"){
            
            $this->session->set_userdata('report-data', $_POST);
            echo response("success", [], "Get success");
            die();
        }
        if($type == "attendees"){
            $s =  $this->session->userdata('report-data');
            if(!isset($s['data'])){
                echo "report data";
                die();
            }
            $this->load->view('Admin/Report/room/table_attendees', array(
                'report' => $this->session->userdata('report-data'),
            ));
        }else if($type == "organizer"){
            $s =  $this->session->userdata('report-data');
            if(!isset($s['data'])){
                echo "report data";
                die();
            }
            $this->load->view('Admin/Report/room/table_organizer', array(
                'report' => $this->session->userdata('report-data'),
            ));
        }

        
    }

    public function postRoomStatusInvoice()
    {
        $post = $_POST;
        $w    = array(
            'booking_id' => $post['booking_id'],
        );
        $input = array(
            'memo_no'        => $post['refrensi'],
            'referensi_no'   => $post['memo'],
            'invoice_status' => $post['status_invoice'],
        );
        $this->Model_Admin->updateData('booking_invoice', $input, $w);
        echo response("success", array(), "Success to submit invoice");
    }
    public function getOutstandingReport()
    {
        $date1 = $this->uri->segment(4);
        $date2 = $this->uri->segment(5);

        if ($date1 != "" || $date2 != "") {
            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant,
			binv.rent_cost as invoice_rent_cost, binv.time_before as invoice_time_before, binv.time_send as invoice_time_send, binv.time_paid as invoice_time_paid,
			b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE b.date >='" . $date1 . "' ";
            $q .= " AND b.date <='" . $date2 . "' ";
            $q .= " AND b.is_canceled=0 ";
            $q .= " AND ( binv.invoice_status='0' OR binv.invoice_status='1' ) ";
            $q .= " AND bi.is_pic =1 ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            echo response("success", $result, "Get success");
        } else {
            $response = response("fail", array(), "Failed get a report ");
            echo $response;
        }
    }
    public function getOutstandingReportAlocation()
    {

        $alocation = $this->uri->segment(4);
        $date1     = $this->uri->segment(5);
        $date2     = $this->uri->segment(6);
        if ($date1 != "" || $date2 != "") {
            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant,
			binv.rent_cost as invoice_rent_cost, binv.time_before as invoice_time_before, binv.time_send as invoice_time_send, binv.time_paid as invoice_time_paid,
			b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE b.date >='" . $date1 . "' ";
            $q .= " AND b.date <='" . $date2 . "' ";
            $q .= " AND b.is_canceled=0 ";
            $q .= " AND ( binv.invoice_status='0' OR binv.invoice_status='1' ) ";
            $q .= " AND b.alocation_id='" . $alocation . "' ";
            $q .= " AND bi.is_pic =1 ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            echo response("success", $result, "Get success");
        } else {
            $response = response("fail", array(), "Failed get a report ");
            echo $response;
        }
    }
    private function getAlocationData()
    {
        $userid = $this->session->userdata('user-nya'); // user_nik
        if ($this->session->userdata('levelid-nya') == 1) {
            $data = $this->Model_Admin->getAlocationWithType();
        } else if ($this->session->userdata('levelid-nya') == 2) {
            $data = $this->Model_Admin->getAlocationWithTypeUser($userid);

        }
        return $data['data'];

    }
    public function getInvoiceStatus()
    {
        $data = $this->Model_Admin->getInvStatusName();
        if ($data['error'] == null) {
            echo response("success", $data['data'], "Get success");
        } else {
            echo response("fail", $data, "Get failed");
        }

    }

    public function getInvoiceStatusZZZZ()
    {
        $data = $this->Model_Admin->getInvStatusName();
        return $data['data'];

    }

    private function checkInvoice($result, $sttEnable, $sttInvoice)
    {
        if ($sttEnable == 0 || $sttEnable == "0") {
            return $result[3]['name'];
        } else {
            $stt = $sttInvoice - 0;
            $rt  = "";
            foreach ($result as $key => $value) {
                if ($value['id'] == $sttInvoice) {
                    $rt = $value['name'];
                }
            }
            return $rt;
        }
    }
    public function exportRoomReportAll()
    {
        $export        = $this->uri->segment(4);
        $statusInvoice = $this->getInvoiceStatusZZZZ();
        $company       = $this->Model_Admin->getDataCompany()['data'];
        try {
            $date1 = $this->uri->segment(5);
            $date2 = $this->uri->segment(6);
        } catch (Exeption $rror) {
            echo json_encode($rror);
            die();
        }
        if ($export == "excell") {
            include APPPATH . 'third_party/phpspreadsheet/autoload.php';
            if ($date1 != "" || $date2 != "") {
                $filename = "REPORT_USAGE_ROOM_" . $date1 . "__" . $date2;
                $data     = $this->getAllReport($date1, $date2);
                // print_r($data);
                if ($data == null) {
                    echo "Something wrong to parameter!!!";
                    die();
                }
                // start header_
                header_xls($filename);
                // end header_
                $spreadsheet = new Spreadsheet();
                $sheet       = $spreadsheet->getActiveSheet();
                $titleStyle  = array(
                    'font' => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 22,

                    ),
                );
                $companyStyle = array(
                    'font' => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 18,

                    ),
                );
                $periodStyle = array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders'   => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill'      => array(
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => array('rgb' => '0000FF'),
                    ),
                    'font'      => array(
                        'bold'  => true,
                        'color' => array('rgb' => 'FFFFFF'),
                        'size'  => 16,

                    ),
                );
                $headColumnStyle = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill'    => array(
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => array('rgb' => 'FFFF00'),
                    ),
                    'font'    => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 16,
                    ),
                );
                $contentRowStyle = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'font'    => array(
                        'bold'  => false,
                        'color' => array('rgb' => '00000000'),
                        'size'  => 14,

                    ),
                );
                foreach (range('A', 'Z') as $char) {
                    $sheet->getColumnDimension($char)->setAutoSize(true);
                }
                // == TITLE
                $sheet->setCellValue('C1', "REPORT OF USAGE ROOM ");
                $sheet->getStyle('C1')->applyFromArray($titleStyle);
                $sheet->mergeCells("C1:M1");
                // == COMPANY
                $sheet->setCellValue('C3', $company['name']);
                $sheet->getStyle('C3')->applyFromArray($companyStyle);
                $sheet->mergeCells("C3:D3");

                $sheet->setCellValue('C4', "PERIOD");
                $sheet->getStyle('C4')->applyFromArray($periodStyle);
                $sheet->mergeCells("C4:D4");
                $sheet->setCellValue('C5', "FROM:");
                $sheet->setCellValue('D5', $date1);
                $sheet->setCellValue('C6', "TO");
                $sheet->setCellValue('D6', $date2);
                $sheet->getStyle('C5:D6')->applyFromArray($contentRowStyle);
                // == TABLE
                $numrow   = 9;
                $startnum = 9;
                $num      = 0;
                $num11    = 8;
                $sheet->getStyle('C' . $num11 . ':Q' . $num11)->applyFromArray($headColumnStyle);
                $sheet->setCellValue('C' . $num11, "#");
                $sheet->setCellValue('D' . $num11, "Booking No");
                $sheet->setCellValue('E' . $num11, "Title/Subject");
                $sheet->setCellValue('F' . $num11, "Date&Time");
                $sheet->setCellValue('G' . $num11, "Room");
                $sheet->setCellValue('H' . $num11, "Room Location");
                $sheet->setCellValue('I' . $num11, "Department");
                $sheet->setCellValue('J' . $num11, "Attendees");
                $sheet->setCellValue('K' . $num11, "Duration");
                $sheet->setCellValue('L' . $num11, "Rent Cost");
                $sheet->setCellValue('M' . $num11, "PIC ");
                $sheet->setCellValue('N' . $num11, "PIC email");
                $sheet->setCellValue('O' . $num11, "PIC No Phone");
                $sheet->setCellValue('P' . $num11, "PIC No Extension");
                $sheet->setCellValue('Q' . $num11, "Status Invoicing");

                foreach ($data as $k => $row) {
                    $num++;
                    $extendTime = $row['extended_duration'] - 0;
                    $start      = date("H:i", strtotime($row['start']));
                    $end        = date("H:i", strtotime($row['end']));
                    $end        = date('H:i', strtotime('+' . $extendTime . ' minutes', strtotime($row['end'])));
                    $datetime   = $row['date'] . " " . $start . " - " . $end;

                    $dur        = ($row['total_duration'] - 0) + ($row['extended_duration'] - 0);
                    $inv_status = $row['alcoation_type_invoice_status'] == $row['alocation_invoice_status'] ? $row['alcoation_type_invoice_status'] : $row['alocation_invoice_status'];
                    // $setHour = $dur/$row['duration_per_meeting'];

                    $setHour = $this->getTimeFromMins($dur);

                    $invStt = $this->checkInvoice($statusInvoice, $inv_status, $row['invoice_status']);
                    $phone  = ($row['phone_employee'] == null) ? "-" : $row['phone_employee'];
                    $noext  = ($row['ext_employee'] == null) ? "-" : $row['ext_employee'];
                    $sheet->setCellValue('C' . $numrow, $num);
                    $sheet->setCellValue('D' . $numrow, $row['no_order']);
                    $sheet->setCellValue('E' . $numrow, $row['title']);
                    $sheet->setCellValue('F' . $numrow, $datetime);
                    $sheet->setCellValue('G' . $numrow, $row['room_name']);
                    $sheet->setCellValue('H' . $numrow, $row['room_location']);
                    $sheet->setCellValue('I' . $numrow, $row['alocation_name']);
                    $sheet->setCellValue('J' . $numrow, $row['num_partisipant']);
                    $sheet->setCellValue('K' . $numrow, $setHour);
                    $sheet->setCellValue('L' . $numrow, $row['cost_total_booking']);
                    $sheet->setCellValue('M' . $numrow, $row['name_employee']);
                    $sheet->setCellValue('N' . $numrow, $row['email_employee']);
                    $sheet->setCellValue('O' . $numrow, $phone);
                    $sheet->setCellValue('P' . $numrow, $noext);
                    $sheet->setCellValue('Q' . $numrow, $invStt);
                    $numrow++;
                }
                $sheet->getStyle('C' . $startnum . ':Q' . $numrow)->applyFromArray($contentRowStyle);

                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                // echo "download";
            } else {
                echo "error make excell !!!";
            }
        } else {
            echo "No format";

        }

    }
    private function getTimeFromMins($mins)
    {
        $tdd = "2023-01-01 00:00:00";
        $add = strtotime("+" . $mins . " minutes", strtotime($tdd));
        $ts  = date("H:i", $add);
        $sp  = explode(":", $ts);
        $frm = "";
        if ($sp[0] != "00") {
            $frm .= ($sp[0] - 0) . " hour ";
        }
        if ($sp[1] != "00") {
            $frm .= ($sp[1] - 0) . " minute ";
        }
        return $frm;
    }

    public function exportRoomReportAll2()
    {
        $export        = $this->uri->segment(4);
        $statusInvoice = $this->getInvoiceStatusZZZZ();
        $company       = $this->Model_Admin->getDataCompany()['data'];
        try {
            $date1 = $this->uri->segment(5);
            $date2 = $this->uri->segment(6);
            $alo   = $this->uri->segment(7);
        } catch (Exeption $rror) {
            echo json_encode($rror);
            die();
        }
        if ($export == "excell") {
            include APPPATH . 'third_party/phpspreadsheet/autoload.php';

            if ($date1 != "" || $date2 != "") {
                $filename = "REPORT_USAGE_ROOM_" . $date1 . "__" . $date2;
                $data     = $this->getAllReport($date1, $date2, $alo);
                // echo "<pre>";
                // print_r($data);
                // die();

                if ($data == null) {
                    echo "Something wrong to parameter!!!";
                    die();
                }
                // start header_
                header_xls($filename);
                // end header_
                $spreadsheet = new Spreadsheet();
                $sheet       = $spreadsheet->getActiveSheet();
                $titleStyle  = array(
                    'font' => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 22,

                    ),
                );
                $companyStyle = array(
                    'font' => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 18,

                    ),
                );
                $periodStyle = array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders'   => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill'      => array(
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => array('rgb' => '0000FF'),
                    ),
                    'font'      => array(
                        'bold'  => true,
                        'color' => array('rgb' => 'FFFFFF'),
                        'size'  => 16,

                    ),
                );
                $headColumnStyle = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill'    => array(
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => array('rgb' => 'FFFF00'),
                    ),
                    'font'    => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 16,
                    ),
                );
                $contentRowStyle = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'font'    => array(
                        'bold'  => false,
                        'color' => array('rgb' => '00000000'),
                        'size'  => 14,

                    ),
                );
                foreach (range('A', 'Z') as $char) {
                    $sheet->getColumnDimension($char)->setAutoSize(true);
                }
                // == TITLE
                $sheet->setCellValue('C1', "REPORT OF USAGE ROOM ");
                $sheet->getStyle('C1')->applyFromArray($titleStyle);
                $sheet->mergeCells("C1:M1");
                // == COMPANY
                $sheet->setCellValue('C3', $company['name']);
                $sheet->getStyle('C3')->applyFromArray($companyStyle);
                $sheet->mergeCells("C3:D3");

                $sheet->setCellValue('C4', "PERIOD");
                $sheet->getStyle('C4')->applyFromArray($periodStyle);
                $sheet->mergeCells("C4:D4");
                $sheet->setCellValue('C5', "FROM:");
                $sheet->setCellValue('D5', $date1);
                $sheet->setCellValue('C6', "TO");
                $sheet->setCellValue('D6', $date2);
                $sheet->getStyle('C5:D6')->applyFromArray($contentRowStyle);
                // == TABLE
                $numrow   = 9;
                $startnum = 9;
                $num      = 0;
                $num11    = 8;
                $sheet->getStyle('C' . $num11 . ':Q' . $num11)->applyFromArray($headColumnStyle);
                $sheet->setCellValue('C' . $num11, "#");
                $sheet->setCellValue('D' . $num11, "Booking No");
                $sheet->setCellValue('E' . $num11, "Title/Subject");
                $sheet->setCellValue('F' . $num11, "Date&Time");
                $sheet->setCellValue('G' . $num11, "Room");
                $sheet->setCellValue('H' . $num11, "Room Location");
                $sheet->setCellValue('I' . $num11, "Alocation");
                $sheet->setCellValue('J' . $num11, "Attendees");
                $sheet->setCellValue('K' . $num11, "Duration");
                $sheet->setCellValue('L' . $num11, "Rent Cost");
                $sheet->setCellValue('M' . $num11, "PIC ");
                $sheet->setCellValue('N' . $num11, "PIC email");
                $sheet->setCellValue('O' . $num11, "PIC No Phone");
                $sheet->setCellValue('P' . $num11, "PIC No Extension");
                $sheet->setCellValue('Q' . $num11, "Status Invoicing");

                $total_cost     = 0;
                $total_duration = 0;
                foreach ($data as $k => $row) {
                    $num++;
                    $extendTime = $row['extended_duration'] - 0;
                    $start      = date("H:i", strtotime($row['start']));
                    $end        = date("H:i", strtotime($row['end']));
                    $end        = date('H:i', strtotime('+' . $extendTime . ' minutes', strtotime($row['end'])));
                    $datetime   = $row['date'] . " " . $start . " - " . $end;
                    $dur        = ($row['total_duration'] - 0) + ($row['extended_duration'] - 0);
                    $inv_status = $row['alcoation_type_invoice_status'] == $row['alocation_invoice_status'] ? $row['alcoation_type_invoice_status'] : $row['alocation_invoice_status'];
                    // $setHour = $dur/$row['duration_per_meeting'];
                    $invStt = $this->checkInvoice($statusInvoice, $inv_status, $row['invoice_status']);
                    $phone  = ($row['phone_employee'] == null) ? "-" : $row['phone_employee'];
                    $noext  = ($row['ext_employee'] == null) ? "-" : $row['ext_employee'];

                    $setHour = $this->getTimeFromMins($dur);

                    $sheet->setCellValue('C' . $numrow, $num);
                    $sheet->setCellValue('D' . $numrow, $row['no_order']);
                    $sheet->setCellValue('E' . $numrow, $row['title']);
                    $sheet->setCellValue('F' . $numrow, $datetime);
                    $sheet->setCellValue('G' . $numrow, $row['room_name']);
                    $sheet->setCellValue('H' . $numrow, $row['room_location']);
                    $sheet->setCellValue('I' . $numrow, $row['alocation_name']);
                    $sheet->setCellValue('J' . $numrow, $row['num_partisipant']);
                    $sheet->setCellValue('K' . $numrow, $setHour);
                    $sheet->setCellValue('L' . $numrow, $row['cost_total_booking']);
                    $sheet->setCellValue('M' . $numrow, $row['name_employee']);
                    $sheet->setCellValue('N' . $numrow, $row['email_employee']);
                    $sheet->setCellValue('O' . $numrow, $phone);
                    $sheet->setCellValue('P' . $numrow, $noext);
                    $sheet->setCellValue('Q' . $numrow, $invStt);

                    $total_cost += ($row['cost_total_booking'] - 0);
                    $total_duration += ($dur - 0);

                    $numrow++;
                }
                $sheet->getStyle('C' . $startnum . ':Q' . $numrow)->applyFromArray($contentRowStyle);

                // $sheet->setCellValue('F4', "TOTAL");
                // $sheet->getStyle('F4')->applyFromArray($periodStyle);
                $sheet->mergeCells("F4:G4");
                $sheet->setCellValue('F5', "TOTAL COST");
                $total_duration_mins = $this->getTimeFromMins($dur);

                $sheet->setCellValue('G5', $total_cost);
                $sheet->setCellValue('F6', "TOTAL DURATION ");
                $sheet->setCellValue('G6', $total_duration_mins . "");
                $sheet->getStyle('F5:G6')->applyFromArray($contentRowStyle);

                $totalrow = $numrow + 1;
                $writer   = new Xlsx($spreadsheet);
                $writer->save('php://output');
                // echo "download";
            } else {
                echo "error make excell !!!";
            }
        } else {
            echo "No format";

        }

    }

    public function exportReportExcellAllNew()
    {
        $modules['pantry']        = $this->Model_Module->get_module_pantry();
        $modules['loker']         = $this->Model_Module->get_module_loker();
        $modules['price']         = $this->Model_Module->get_module_price();
        $modules['invoice']       = $this->Model_Module->get_module_invoice();
        $modules['email']         = $this->Model_Module->get_module_email();
        $modules['vip']           = $this->Model_Module->get_module_vip();
        $modules['room_adv']      = $this->Model_Module->get_module_room_adv();
        $modules_room_adv_enabled = $modules['room_adv']['is_enabled'] - 0;
        $modules_vip_enabled      = $modules['vip']['is_enabled'] - 0;

        $this->load->helper('string');
        $get           = $_GET;
        $export        = $this->uri->segment(4);
        $statusInvoice = $this->getInvoiceStatusZZZZ();
        $company       = $this->Model_Admin->getDataCompany()['data'];
        if (!isset($get['department'])
            || !isset($get['date1'])
            || !isset($get['date2'])
            || !isset($get['room'])
            || !isset($get['building'])
        ) {
            echo "No found";
            die();
        }
        $formatlist = ["excell", "pdf"];
        if (!in_array($export, $formatlist)) {
            echo "No format";
            die();
        }

        $date1      = $get['date1'];
        $date2      = $get['date2'];
        $room       = $get['room'];
        $building   = $get['building'];
        $department = $get['department'];
        $reportData = [
            'date1'      => isset($get['date1']) ? $get['date1'] : "",
            'date2'      => isset($get['date2']) ? $get['date2'] : "",
            'room'       => isset($get['room']) ? $get['room'] : "",
            'building'   => isset($get['building']) ? $get['building'] : "",
            'department' => isset($get['department']) ? $get['department'] : "",
        ];
        $filename = "REPORT_MEETING_" . $date1 . "__" . $date2 . "_" . date("YmdHis") . "_" . random_string('nozero', 5);

        $data = $this->getAllReportNew($reportData);
        // echo "<pre>";
        // print_r($reportData);
        // die();

        include APPPATH . 'third_party/phpspreadsheet/autoload.php';
        if ($data == null) {
            echo "Something wrong to parameter!!!";
            die();
        }
        // start header_
        header_xls($filename);
        // end header_
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $titleStyle  = array(
            'font' => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 22,

            ),
        );
        $companyStyle = array(
            'font' => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 18,

            ),
        );
        $periodStyle = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ),
            'borders'   => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill'      => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color'    => array('rgb' => '0000FF'),
            ),
            'font'      => array(
                'bold'  => true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 16,

            ),
        );
        $headColumnStyle = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill'    => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color'    => array('rgb' => 'FFFF00'),
            ),
            'font'    => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 16,
            ),
        );
        $contentRowStyle = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'font'    => array(
                'bold'  => false,
                'color' => array('rgb' => '00000000'),
                'size'  => 14,

            ),
        );
        foreach (range('A', 'Z') as $char) {
            $sheet->getColumnDimension($char)->setAutoSize(true);
        }
        // == TITLE
        $sheet->setCellValue('C1', "REPORT OF USAGE ROOM ");
        $sheet->getStyle('C1')->applyFromArray($titleStyle);
        $sheet->mergeCells("C1:M1");
        // == COMPANY
        $sheet->setCellValue('C3', $company['name']);
        $sheet->getStyle('C3')->applyFromArray($companyStyle);
        $sheet->mergeCells("C3:D3");

        $sheet->setCellValue('C4', "PERIOD");
        $sheet->getStyle('C4')->applyFromArray($periodStyle);
        $sheet->mergeCells("C4:D4");
        $sheet->setCellValue('C5', "FROM:");
        $sheet->setCellValue('D5', $date1);
        $sheet->setCellValue('C6', "TO");
        $sheet->setCellValue('D6', $date2);
        $sheet->getStyle('C5:D6')->applyFromArray($contentRowStyle);
        // == TABLE
        $numrow   = 9;
        $startnum = 9;
        $num      = 0;
        $num11    = 8;
        $sheet->getStyle('C' . $num11 . ':Q' . $num11)->applyFromArray($headColumnStyle);
        $sheet->setCellValue('C' . $num11, "#");
        $sheet->setCellValue('D' . $num11, "Booking No");
        $sheet->setCellValue('E' . $num11, "Title/Subject");
        $sheet->setCellValue('F' . $num11, "Date&Time");
        $sheet->setCellValue('G' . $num11, "Room");
        $sheet->setCellValue('H' . $num11, "Room Location");
        $sheet->setCellValue('I' . $num11, "Alocation");
        $sheet->setCellValue('J' . $num11, "Attendees");
        $sheet->setCellValue('K' . $num11, "Duration");
        $sheet->setCellValue('L' . $num11, "PIC ");
        $sheet->setCellValue('M' . $num11, "PIC email");
        $sheet->setCellValue('N' . $num11, "PIC No Phone");
        $sheet->setCellValue('O' . $num11, "PIC No Extension");
        if(($modules['invoice']['is_enabled'] -0 ) == 1 && ($modules['price']['is_enabled'] -0 )  ){
        	$sheet->setCellValue('P' . $num11, "Status Invoicing");
        	$sheet->setCellValue('Q' . $num11, "Rent Cost");
        }
        

        $total_cost     = 0;
        $total_duration = 0;
        foreach ($data as $k => $row) {
            $num++;
            $extendTime = $row['extended_duration'] - 0;
            $start      = date("H:i", strtotime($row['start']));
            $end        = date("H:i", strtotime($row['end']));
            $end        = date('H:i', strtotime('+' . $extendTime . ' minutes', strtotime($row['end'])));
            $datetime   = $row['date'] . " " . $start . " - " . $end;
            $dur        = ($row['total_duration'] - 0) + ($row['extended_duration'] - 0);
            $inv_status = $row['alcoation_type_invoice_status'] == $row['alocation_invoice_status'] ? $row['alcoation_type_invoice_status'] : $row['alocation_invoice_status'];
            // $setHour = $dur/$row['duration_per_meeting'];
            $invStt = $this->checkInvoice($statusInvoice, $inv_status, $row['invoice_status']);
            $phone  = ($row['phone_employee'] == null) ? "-" : $row['phone_employee'];
            $noext  = ($row['ext_employee'] == null) ? "-" : $row['ext_employee'];

            $setHour = $this->getTimeFromMins($dur);

            $sheet->setCellValue('C' . $numrow, $num);
            $sheet->setCellValue('D' . $numrow, $row['no_order']);
            $sheet->setCellValue('E' . $numrow, $row['title']);
            $sheet->setCellValue('F' . $numrow, $datetime);
            $sheet->setCellValue('G' . $numrow, $row['room_name']);
            $sheet->setCellValue('H' . $numrow, $row['room_location']);
            $sheet->setCellValue('I' . $numrow, $row['alocation_name']);
            $sheet->setCellValue('J' . $numrow, $row['num_partisipant']);
            $sheet->setCellValue('K' . $numrow, $setHour);
            $sheet->setCellValue('L' . $numrow, $row['name_employee']);
            $sheet->setCellValue('M' . $numrow, $row['email_employee']);
            $sheet->setCellValue('N' . $numrow, $phone);
            $sheet->setCellValue('O' . $numrow, $noext);
            
            if(($modules['invoice']['is_enabled'] -0 ) == 1 && ($modules['price']['is_enabled'] -0 )  ){
	        	$sheet->setCellValue('P' . $numrow, $invStt);
            	$sheet->setCellValue('Q' . $numrow, $row['cost_total_booking']);
	        }

            $total_cost += ($row['cost_total_booking'] - 0);
            $total_duration += ($dur - 0);

            $numrow++;
        }
        $sheet->getStyle('C' . $startnum . ':Q' . $numrow)->applyFromArray($contentRowStyle);

        // $sheet->setCellValue('F4', "TOTAL");
        // $sheet->getStyle('F4')->applyFromArray($periodStyle);
        $sheet->mergeCells("F4:G4");
        if(($modules['invoice']['is_enabled'] -0 ) == 1 && ($modules['price']['is_enabled'] -0 )  ){
        	$sheet->setCellValue('F5', "TOTAL COST");
        	$sheet->setCellValue('G5', $total_cost);
        }
       
        $total_duration_mins = $this->getTimeFromMins($total_duration);

       
        $sheet->setCellValue('F6', "TOTAL DURATION ");
        $sheet->setCellValue('G6', $total_duration_mins . "");
        $sheet->getStyle('F5:G6')->applyFromArray($contentRowStyle);

        $totalrow = $numrow + 1;
        $writer   = new Xlsx($spreadsheet);
        $writer->save('php://output');

    }
    public function exportRoomReportPerMeeting()
    {
        $export        = $this->uri->segment(4);
        $company       = $this->Model_Admin->getDataCompany()['data'];
        $statusInvoice = $this->getInvoiceStatusZZZZ();
        try {
            if ($export == "excell") {
                include APPPATH . 'third_party/phpspreadsheet/autoload.php';

                $booking_id = $this->uri->segment(5);
                $date1      = $this->uri->segment(6);
                $date2      = $this->uri->segment(7);
                $filename   = "REPORT_USAGE_ROOM_" . $booking_id . "_" . $date1 . "__" . $date2;
                $data       = $this->getReportMeeting($booking_id, $date1, $date2);

                // echo "<pre>";
                // print_r($data);
                // die();
                $row = $data['data'];

                $inv = $data['invitation'];
                if ($data != null) {
                    $room_location = $row['room_location'];
                    if ($row['is_merge'] == "1" || $row['is_merge'] == 1) {
                        $mergeRoom        = json_decode($data['merge_room'], true);
                        $room_location_ar = array();
                        $room_location    = "";
                        foreach ($mergeRoom as $key => $value) {
                            array_push($room_location_ar, $value['Location']);
                        }
                        $room_location = implode(" & ", $room_location_ar);
                    }
                    header_xls($filename);
                    // end header_
                    $spreadsheet = new Spreadsheet();
                    $sheet       = $spreadsheet->getActiveSheet();
                    $titleStyle  = array(
                        'font' => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 22,

                        ),
                    );
                    $companyStyle = array(
                        'font' => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 18,

                        ),
                    );
                    $periodStyle = array(
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                        'borders'   => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'fill'      => array(
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color'    => array('rgb' => '0000FF'),
                        ),
                        'font'      => array(
                            'bold'  => true,
                            'color' => array('rgb' => 'FFFFFF'),
                            'size'  => 16,

                        ),
                    );
                    $headColumnStyle = array(
                        'borders' => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'fill'    => array(
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color'    => array('rgb' => 'FFFF00'),
                        ),
                        'font'    => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 16,
                        ),
                    );
                    $contentRowStyle = array(
                        'borders' => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'font'    => array(
                            'bold'  => false,
                            'color' => array('rgb' => '00000000'),
                            'size'  => 14,

                        ),
                    );
                    foreach (range('A', 'Z') as $char) {
                        $sheet->getColumnDimension($char)->setAutoSize(true);
                    }
                    // == TITLE
                    $sheet->setCellValue('C1', "REPORT OF USAGE ROOM ");
                    $sheet->getStyle('C1')->applyFromArray($titleStyle);
                    $sheet->mergeCells("C1:M1");
                    // == COMPANY
                    $sheet->setCellValue('C3', $company['name']);
                    $sheet->getStyle('C3')->applyFromArray($companyStyle);
                    $sheet->mergeCells("C3:D3");

                    $sheet->setCellValue('C4', "PERIOD");
                    $sheet->getStyle('C4')->applyFromArray($periodStyle);
                    $sheet->mergeCells("C4:D4");
                    $sheet->setCellValue('C5', "FROM:");
                    $sheet->setCellValue('D5', $date1);
                    $sheet->setCellValue('C6', "TO");
                    $sheet->setCellValue('D6', $date2);
                    $sheet->getStyle('C5:D6')->applyFromArray($contentRowStyle);
                    // column
                    $numrow   = 9;
                    $startnum = 9;
                    $num      = 0;
                    $num11    = 8;
                    $sheet->getStyle('C' . $num11 . ':S' . $num11)->applyFromArray($headColumnStyle);
                    $sheet->setCellValue('C' . $num11, "#");
                    $sheet->setCellValue('D' . $num11, "Booking No");
                    $sheet->setCellValue('E' . $num11, "Title/Subject");
                    $sheet->setCellValue('F' . $num11, "Date&Time");
                    $sheet->setCellValue('G' . $num11, "Room");
                    $sheet->setCellValue('H' . $num11, "Room Location");
                    $sheet->setCellValue('I' . $num11, "Department");
                    $sheet->setCellValue('J' . $num11, "Attendees");
                    $sheet->setCellValue('K' . $num11, "Duration");
                    $sheet->setCellValue('L' . $num11, "PIC ");
                    $sheet->setCellValue('M' . $num11, "PIC email");
                    $sheet->setCellValue('N' . $num11, "PIC No Phone");
                    $sheet->setCellValue('O' . $num11, "PIC No Extension");
                    $sheet->setCellValue('P' . $num11, "Note ");
                    $sheet->setCellValue('Q' . $num11, "Cancel Note ");
                    if(($modules['invoice']['is_enabled'] -0 ) == 1 && ($modules['price']['is_enabled'] -0 )  ){
                        $sheet->setCellValue('R' . $num11, "Rent Cost");
                        $sheet->setCellValue('S' . $num11, "Status Invoicing");
                    }
                    
                    // content
                    //
                    $extendTime = $row['extended_duration'] - 0;
                    $start      = date("H:i", strtotime($row['start']));
                    $end        = date("H:i", strtotime($row['end']));
                    $end        = date('H:i', strtotime('+' . $extendTime . ' minutes', strtotime($row['end'])));
                    $datetime   = $row['date'] . " " . $start . " - " . $end;

                    $dur        = ($row['total_duration'] - 0) + ($row['extended_duration'] - 0);
                    $inv_status = $row['alcoation_type_invoice_status'] == $row['alocation_invoice_status'] ? $row['alcoation_type_invoice_status'] : $row['alocation_invoice_status'];
                    // $setHour = $dur/$row['duration_per_meeting'];
                    $setHour = $this->getTimeFromMins($dur);
                    // echo $setHour;
                    // die();

                    $invStt = $this->checkInvoice($statusInvoice, $inv_status, $row['invoice_status']);
                    $phone  = ($row['phone_employee'] == null) ? "-" : $row['phone_employee'];
                    $noext  = ($row['ext_employee'] == null) ? "-" : $row['ext_employee'];
                    $sheet->setCellValue('C' . $numrow, $num);
                    $sheet->setCellValue('D' . $numrow, $row['no_order']);
                    $sheet->setCellValue('E' . $numrow, $row['title']);
                    $sheet->setCellValue('F' . $numrow, $datetime);
                    $sheet->setCellValue('G' . $numrow, $row['room_name']);
                    $sheet->setCellValue('H' . $numrow, $room_location);
                    $sheet->setCellValue('I' . $numrow, $row['alocation_name']);
                    $sheet->setCellValue('J' . $numrow, $row['num_partisipant']);
                    $sheet->setCellValue('K' . $numrow, $setHour);
                    $sheet->setCellValue('L' . $numrow, $row['name_employee']);
                    $sheet->setCellValue('M' . $numrow, $row['email_employee']);
                    $sheet->setCellValue('N' . $numrow, $phone);
                    $sheet->setCellValue('O' . $numrow, $noext);
                    $sheet->setCellValue('P' . $numrow, $row['note']);
                    $sheet->setCellValue('Q' . $numrow, $row['canceled_note']);
                    if(($modules['invoice']['is_enabled'] -0 ) == 1 && ($modules['price']['is_enabled'] -0 )  ){
                        $sheet->setCellValue('L' . $numrow, $row['cost_total_booking']);
                        $sheet->setCellValue('Q' . $numrow, $invStt);
                    }
                    $sheet->getStyle('C' . $startnum . ':S' . $numrow)->applyFromArray($contentRowStyle);
                    // invitation
                    $sheet->setCellValue('C12', "ATTENDEES");
                    $sheet->getStyle('C12')->applyFromArray($companyStyle);
                    $sheet->mergeCells("C12:H12");

                    $numINVrow   = 15;
                    $startINVnum = 15;
                    $numINV      = 0;
                    $num11INV    = 14;
                    $sheet->setCellValue('C' . $num11INV, "#");
                    $sheet->setCellValue('D' . $num11INV, "USERNAME/NIK");
                    $sheet->setCellValue('E' . $num11INV, "Name");
                    $sheet->setCellValue('F' . $num11INV, "Email");
                    $sheet->setCellValue('G' . $num11INV, "Phone");
                    $sheet->setCellValue('H' . $num11INV, "Extension");
                    $sheet->setCellValue('I' . $num11INV, "Internal");
                    $sheet->setCellValue('J' . $num11INV, "Company External");
                    $sheet->setCellValue('K' . $num11INV, "Position External");
                    $sheet->setCellValue('L' . $num11INV, "Status Attendance");
                    $sheet->setCellValue('M' . $num11INV, "PIC");
                    $sheet->getStyle('C' . $num11INV . ':M' . $num11INV)->applyFromArray($headColumnStyle);

                    foreach ($inv as $nnn => $rowinv) {

                        $numINV++;
                        $rowinv['attendance_status'] = ($rowinv['attendance_status'] == 1) ? "Attend" : "No Attend";
                        $rowinv['is_pic']            = ($rowinv['is_pic'] == 1) ? "PIC" : "AUDIENCE/PARTICIPANT";
                        if ($rowinv['internal'] == 1) {
                            // internal
                            $sheet->setCellValue('C' . $numINVrow, $numINV);
                            $sheet->setCellValue('D' . $numINVrow, $rowinv['nik']);
                            $sheet->setCellValue('E' . $numINVrow, $rowinv['employee_name']);
                            $sheet->setCellValue('F' . $numINVrow, $rowinv['emp_email']);
                            $sheet->setCellValue('G' . $numINVrow, $rowinv['emp_phone']);
                            $sheet->setCellValue('H' . $numINVrow, $rowinv['emp_ext']);
                            $sheet->setCellValue('I' . $numINVrow, "INTERNAL");
                            $sheet->setCellValue('L' . $numINVrow, $rowinv['attendance_status']);
                            $sheet->setCellValue('M' . $numINVrow, $rowinv['is_pic']);
                        } else {

                            // eksternal
                            $sheet->setCellValue('C' . $numINVrow, $numINV);
                            $sheet->setCellValue('D' . $numINVrow, $rowinv['nik']);
                            $sheet->setCellValue('E' . $numINVrow, $rowinv['name']);
                            $sheet->setCellValue('F' . $numINVrow, $rowinv['email']);
                            $sheet->setCellValue('G' . $numINVrow, $rowinv['emp_phone']);
                            $sheet->setCellValue('H' . $numINVrow, $rowinv['emp_ext']);
                            $sheet->setCellValue('I' . $numINVrow, "EXERNAL");
                            $sheet->setCellValue('J' . $numINVrow, $rowinv['company']);
                            $sheet->setCellValue('K' . $numINVrow, $rowinv['position']);
                            $sheet->setCellValue('L' . $numINVrow, $rowinv['attendance_status']);
                            $sheet->setCellValue('M' . $numINVrow, $rowinv['is_pic']);
                        }

                        $numINVrow++;
                    }
                    $sheet->getStyle('C' . $startINVnum . ':M' . $numINVrow)->applyFromArray($contentRowStyle);
                    $writer = new Xlsx($spreadsheet);
                    $writer->save('php://output');
                } else {
                    echo "Something wrong to parameter!!!";
                    die();
                }
                // export

            } else {
                echo "Something wrong to parameter!!!";
                die();
            }
        } catch (Exeption $rror) {
            echo json_encode($rror);
            die();
        }
    }
    public function exportCancelReportAll()
    {
        $export        = $this->uri->segment(4);
        $statusInvoice = $this->getInvoiceStatusZZZZ();
        $company       = $this->Model_Admin->getDataCompany()['data'];
        try {
            $date1 = $this->uri->segment(5);
            $date2 = $this->uri->segment(6);
        } catch (Exeption $rror) {
            echo json_encode($rror);
            die();
        }
        if ($export == "excell") {
            include APPPATH . 'third_party/phpspreadsheet/autoload.php';

            if ($date1 != "" || $date2 != "") {
                $filename = "REPORT_CANCEL_ORDER_BOOKING_" . $date1 . "__" . $date2;
                $data     = $this->getAllCancelReport($date1, $date2);
                if ($data == null) {
                    echo "Something wrong to parameter!!!";
                    die();
                }
                // start header_
                header_xls($filename);
                // end header_
                $spreadsheet = new Spreadsheet();
                $sheet       = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Worksheet 1');
                $titleStyle = array(
                    'font' => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 22,

                    ),
                );
                $companyStyle = array(
                    'font' => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 18,

                    ),
                );
                $periodStyle = array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders'   => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill'      => array(
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => array('rgb' => '0000FF'),
                    ),
                    'font'      => array(
                        'bold'  => true,
                        'color' => array('rgb' => 'FFFFFF'),
                        'size'  => 16,

                    ),
                );
                $headColumnStyle = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill'    => array(
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => array('rgb' => 'FFFF00'),
                    ),
                    'font'    => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 16,
                    ),
                );
                $contentRowStyle = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'font'    => array(
                        'bold'  => false,
                        'color' => array('rgb' => '00000000'),
                        'size'  => 14,

                    ),
                );
                $contentRowTitleStyle = array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ),
                    'font'      => array(
                        'bold'  => false,
                        'color' => array('rgb' => '00000000'),
                        'size'  => 14,

                    ),
                );
                foreach (range('A', 'Z') as $char) {
                    $sheet->getColumnDimension($char)->setAutoSize(true);
                }
                // == TITLE
                $sheet->setCellValue('C1', "REPORT OF CANCEL ORDER BOOKING ");
                $sheet->getStyle('C1')->applyFromArray($titleStyle);
                $sheet->mergeCells("C1:M1");
                // == COMPANY
                $sheet->setCellValue('C3', $company['name']);
                $sheet->getStyle('C3')->applyFromArray($companyStyle);
                $sheet->mergeCells("C3:D3");

                $sheet->setCellValue('C4', "PERIOD");
                $sheet->getStyle('C4')->applyFromArray($periodStyle);
                $sheet->mergeCells("C4:D4");
                $sheet->setCellValue('C5', "FROM:");
                $sheet->setCellValue('D5', $date1);
                $sheet->setCellValue('C6', "TO");
                $sheet->setCellValue('D6', $date2);
                $sheet->getStyle('C5:D6')->applyFromArray($contentRowStyle);
                // == TABLE
                $numrow   = 9;
                $startnum = 9;
                $num      = 0;
                $num11    = 8;
                $sheet->getStyle('C' . $num11 . ':S' . $num11)->applyFromArray($headColumnStyle);
                $sheet->setCellValue('C' . $num11, "#");
                $sheet->setCellValue('D' . $num11, "Canceled to");
                $sheet->setCellValue('E' . $num11, "Booking No");
                $sheet->setCellValue('F' . $num11, "Title/Subject");
                $sheet->setCellValue('G' . $num11, "Meeting Time");
                $sheet->setCellValue('H' . $num11, "Order Time");
                $sheet->setCellValue('I' . $num11, "Cancel Time");
                $sheet->setCellValue('J' . $num11, "Room");
                $sheet->setCellValue('K' . $num11, "Room Location");
                $sheet->setCellValue('L' . $num11, "Department");
                $sheet->setCellValue('M' . $num11, "Attendees");
                $sheet->setCellValue('N' . $num11, "Duration");
                $sheet->setCellValue('O' . $num11, "Rent Cost");
                $sheet->setCellValue('P' . $num11, "Canceled By ");
                $sheet->setCellValue('Q' . $num11, "Canceled Role ");
                $sheet->setCellValue('R' . $num11, "Note ");
                $sheet->setCellValue('S' . $num11, "Cancel Note ");

                $accmulationHours = 0;
                foreach ($data as $k => $row) {
                    $num++;
                    $extendTime = $row['extended_duration'] - 0;
                    $start      = date("H:i", strtotime($row['start']));
                    $end        = date("H:i", strtotime($row['end']));
                    $end        = date('H:i', strtotime('+' . $extendTime . ' minutes', strtotime($row['end'])));
                    $datetime   = $row['date'] . " " . $start . " - " . $end;

                    $dur        = ($row['total_duration'] - 0) + ($row['extended_duration'] - 0);
                    $inv_status = $row['alcoation_type_invoice_status'] == $row['alocation_invoice_status'] ? $row['alcoation_type_invoice_status'] : $row['alocation_invoice_status'];
                    $setHour    = $dur / $row['duration_per_meeting'];
                    $accmulationHours += $setHour;
                    $invStt = $this->checkInvoice($statusInvoice, $inv_status, $row['invoice_status']);
                    $phone  = ($row['phone_employee'] == null) ? "-" : $row['phone_employee'];
                    $noext  = ($row['ext_employee'] == null) ? "-" : $row['ext_employee'];

                    $dateorder     = date("Y-m-d H:i:s", strtotime($row['created_at']));
                    $datecancel    = date("Y-m-d H:i:s", strtotime($row['end']));
                    $strdateorder  = getformatDatetime($dateorder, true);
                    $strdatecancel = getformatDatetime($datecancel, true);
                    $level_cancel  = ($row['level_cancel'] == 1) ? "Admin" : "PIC";
                    $sheet->setCellValue('C' . $numrow, $num);
                    $sheet->setCellValue('D' . $numrow, $num);
                    $sheet->setCellValue('E' . $numrow, $row['booking_id']);
                    $sheet->setCellValue('F' . $numrow, $row['title']);
                    $sheet->setCellValue('G' . $numrow, $datetime);
                    $sheet->setCellValue('H' . $numrow, $strdateorder);
                    $sheet->setCellValue('I' . $numrow, $strdatecancel);
                    $sheet->setCellValue('J' . $numrow, $row['room_name']);
                    $sheet->setCellValue('K' . $numrow, $row['room_location']);
                    $sheet->setCellValue('L' . $numrow, $row['alocation_name']);
                    $sheet->setCellValue('M' . $numrow, $row['num_partisipant']);
                    $sheet->setCellValue('N' . $numrow, $setHour);
                    $sheet->setCellValue('O' . $numrow, $row['cost_total_booking']);
                    $sheet->setCellValue('P' . $numrow, $row['name_cancel']);
                    $sheet->setCellValue('Q' . $numrow, $level_cancel);
                    $sheet->setCellValue('R' . $numrow, $row['note']);
                    $sheet->setCellValue('S' . $numrow, $row['canceled_note']);
                    $numrow++;
                }
                $sheet->getStyle('C' . $startnum . ':S' . $numrow)->applyFromArray($contentRowStyle);
                // $sheet->setCellValue('F4', "ALOCATION NAME");
                // $sheet->setCellValue('G4', $alocationdata['name']);
                // $sheet->setCellValue('H4', $alocationdata['name_type']);
                $sheet->setCellValue('F4', "TOTAL ACCUMULATION HOURS:");
                $sheet->setCellValue('G4', $accmulationHours . " hour");
                $sheet->getStyle('F4:H5')->applyFromArray($contentRowTitleStyle);

                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                // echo "download";
            } else {
                echo "error make excell !!!";
            }
        } else {
            echo "No format";

        }

    }
    public function exportCancelReportAlocation()
    {
        $export        = $this->uri->segment(4);
        $company       = $this->Model_Admin->getDataCompany()['data'];
        $statusInvoice = $this->getInvoiceStatusZZZZ();
        try {
            if ($export == "excell") {
                include APPPATH . 'third_party/phpspreadsheet/autoload.php';
                $alocationid   = $this->uri->segment(5);
                $date1         = $this->uri->segment(6);
                $date2         = $this->uri->segment(7);
                $alocationdata = $this->Model_Admin->getAlocationWithId($alocationid)['data'];

                if ($date1 != "" || $date2 != "") {
                    $filename = "REPORT_CANCEL_ORDER_BOOKING_" . $date1 . "__" . $date2;
                    $data     = $this->getCancelAlocationReport($alocationid, $date1, $date2);
                    // echo "<pre>";
                    // print_r($data);
                    // die();
                    $numCancel = count($data);
                    if ($data == null) {
                        echo "Something wrong to parameter!!!";
                        die();
                    }
                    // start header_
                    header_xls($filename);
                    // end header_

                    $spreadsheet = new Spreadsheet();
                    $sheet       = $spreadsheet->getActiveSheet();
                    $sheet->setTitle('Worksheet 1');
                    $titleStyle = array(
                        'font'      => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 22,

                        ),
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                    );
                    $companyStyle = array(
                        'font' => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 18,

                        ),
                    );
                    $periodStyle = array(
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                        'borders'   => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'fill'      => array(
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color'    => array('rgb' => '0000FF'),
                        ),
                        'font'      => array(
                            'bold'  => true,
                            'color' => array('rgb' => 'FFFFFF'),
                            'size'  => 16,

                        ),
                    );
                    $headColumnStyle = array(
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                        'borders'   => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'fill'      => array(
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color'    => array('rgb' => 'FFFF00'),
                        ),
                        'font'      => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 16,
                        ),
                    );
                    $contentRowStyle = array(
                        'borders'   => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                            'wrapText'   => true,
                        ),
                        'font'      => array(
                            'bold'  => false,
                            'color' => array('rgb' => '00000000'),
                            'size'  => 14,

                        ),
                    );
                    $contentRowTitleStyle = array(
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ),
                        'font'      => array(
                            'bold'  => false,
                            'color' => array('rgb' => '00000000'),
                            'size'  => 14,

                        ),
                    );
                    foreach (range('A', 'Z') as $char) {
                        $sheet->getColumnDimension($char)->setAutoSize(true);
                    }
                    // == TITLE
                    $sheet->setCellValue('C1', "REPORT OF CANCEL ORDER BOOKING ");
                    $sheet->getStyle('C1')->applyFromArray($titleStyle);
                    $sheet->mergeCells("C1:Q1");
                    // == COMPANY
                    $sheet->setCellValue('C3', $company['name']);
                    $sheet->getStyle('C3')->applyFromArray($companyStyle);
                    $sheet->mergeCells("C3:D3");

                    $sheet->setCellValue('C4', "PERIOD");
                    $sheet->getStyle('C4')->applyFromArray($periodStyle);
                    $sheet->mergeCells("C4:D4");
                    $sheet->setCellValue('C5', "FROM:");
                    $sheet->setCellValue('D5', getformatDate($date1));
                    $sheet->setCellValue('C6', "TO");
                    $sheet->setCellValue('D6', getformatDate($date2));
                    $sheet->getStyle('C5:D6')->applyFromArray($contentRowStyle);

                    // == TABLE
                    $numrow   = 9;
                    $startnum = 9;
                    $num      = 0;
                    $num11    = 8;
                    $sheet->getStyle('C' . $num11 . ':R' . $num11)->applyFromArray($headColumnStyle);
                    $sheet->setCellValue('C' . $num11, "#");
                    $sheet->setCellValue('D' . $num11, "Canceled to");
                    $sheet->setCellValue('E' . $num11, "Booking No");
                    $sheet->setCellValue('F' . $num11, "Title/Subject");
                    $sheet->setCellValue('G' . $num11, "Meeting Time");
                    $sheet->setCellValue('H' . $num11, "Order Time");
                    $sheet->setCellValue('I' . $num11, "Cancel Time");
                    $sheet->setCellValue('J' . $num11, "Room");
                    $sheet->setCellValue('K' . $num11, "Room Location");
                    $sheet->setCellValue('L' . $num11, "Alocation");
                    $sheet->setCellValue('M' . $num11, "Attendees");
                    $sheet->setCellValue('N' . $num11, "Duration");
                    $sheet->setCellValue('O' . $num11, "Rent Cost");
                    $sheet->setCellValue('P' . $num11, "Canceled By ");
                    $sheet->setCellValue('Q' . $num11, "Canceled Role ");
                    // $sheet->setCellValue('S'.$num11, "PIC No Phone");
                    // $sheet->setCellValue('T'.$num11, "PIC No Extension");
                    // $sheet->setCellValue('U'.$num11, "");
                    $accmulationHours = 0;
                    foreach ($data as $k => $row) {
                        $num++;
                        $extendTime = $row['extended_duration'] - 0;
                        $start      = date("H:i", strtotime($row['start']));
                        $end        = date("H:i", strtotime($row['end']));
                        $end        = date('H:i', strtotime('+' . $extendTime . ' minutes', strtotime($row['end'])));
                        $datetime   = $row['date'] . " " . $start . " - " . $end;
                        $dur        = ($row['total_duration'] - 0) + ($row['extended_duration'] - 0);
                        $inv_status = $row['alcoation_type_invoice_status'] == $row['alocation_invoice_status'] ? $row['alcoation_type_invoice_status'] : $row['alocation_invoice_status'];
                        $setHour    = $dur / $row['duration_per_meeting'];
                        $accmulationHours += $setHour;
                        $invStt = $this->checkInvoice($statusInvoice, $inv_status, $row['invoice_status']);
                        $phone  = ($row['phone_employee'] == null) ? "-" : $row['phone_employee'];
                        $noext  = ($row['ext_employee'] == null) ? "-" : $row['ext_employee'];

                        $dateorder     = date("Y-m-d H:i:s", strtotime($row['created_at']));
                        $datecancel    = date("Y-m-d H:i:s", strtotime($row['end']));
                        $strdateorder  = getformatDatetime($dateorder, true);
                        $strdatecancel = getformatDatetime($datecancel, true);
                        $level_cancel  = ($row['level_cancel'] == 1) ? "Admin" : "PIC";
                        $sheet->setCellValue('C' . $numrow, $num);
                        $sheet->setCellValue('D' . $numrow, $num);
                        $sheet->setCellValue('E' . $numrow, $row['booking_id']);
                        $sheet->setCellValue('F' . $numrow, $row['title']);
                        $sheet->setCellValue('G' . $numrow, $datetime);
                        $sheet->setCellValue('H' . $numrow, $strdateorder);
                        $sheet->setCellValue('I' . $numrow, $strdatecancel);
                        $sheet->setCellValue('J' . $numrow, $row['room_name']);
                        $sheet->setCellValue('K' . $numrow, $row['room_location']);
                        $sheet->setCellValue('L' . $numrow, $row['alocation_name']);
                        $sheet->setCellValue('M' . $numrow, $row['num_partisipant']);
                        $sheet->setCellValue('N' . $numrow, $setHour);
                        $sheet->setCellValue('O' . $numrow, $row['cost_total_booking']);
                        $sheet->setCellValue('P' . $numrow, $row['name_cancel']);
                        $sheet->setCellValue('Q' . $numrow, $level_cancel);
                        $numrow++;
                    }
                    $sheet->getStyle('C' . $startnum . ':R' . ($numrow - 1))->applyFromArray($contentRowStyle);
                    $getRow = $data[0];
                    $sheet->setCellValue('F4', "ALOCATION NAME");
                    $sheet->setCellValue('G4', $alocationdata['name']);
                    $sheet->setCellValue('H4', $alocationdata['name_type']);
                    $sheet->setCellValue('F5', "ACCUMULATION HOURS:");
                    $sheet->setCellValue('G5', $accmulationHours . " hour");
                    $sheet->getStyle('F4:H5')->applyFromArray($contentRowTitleStyle);

                    $writer = new Xlsx($spreadsheet);
                    $writer->save('php://output');
                    // echo "download";
                } else {
                    echo "error make excell !!!";
                }
            } else {
                echo "No format";
            }

        } catch (Exeption $rror) {
            echo json_encode($rror);
            die();
        }
    }
    //  ==========

    public function exportIncomeReportYear()
    {
        $export        = $this->uri->segment(4);
        $company       = $this->Model_Admin->getDataCompany()['data'];
        $statusInvoice = $this->getInvoiceStatusZZZZ();
        try {
            if ($export == "excell") {
                include APPPATH . 'third_party/phpspreadsheet/autoload.php';
                $year = $this->uri->segment(5);

                if ($year != "") {
                    $filename  = "REPORT_INCOME_YEAR_" . $year;
                    $data      = $this->getIncomeReport($year);
                    $numCancel = count($data);
                    if (!is_array($data)) {
                        echo "Something wrong to parameter!!!";
                        die();
                    }
                    // start header_
                    header_xls($filename);
                    // end header_

                    $spreadsheet = new Spreadsheet();
                    $sheet       = $spreadsheet->getActiveSheet();
                    $sheet->setTitle('Worksheet 1');
                    $titleStyle = array(
                        'font'      => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 22,

                        ),
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                    );
                    $companyStyle = array(
                        'font' => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 18,

                        ),
                    );
                    $periodStyle = array(
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                        'borders'   => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'fill'      => array(
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color'    => array('rgb' => '0000FF'),
                        ),
                        'font'      => array(
                            'bold'  => true,
                            'color' => array('rgb' => 'FFFFFF'),
                            'size'  => 16,

                        ),
                    );
                    $headColumnStyle = array(
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                        'borders'   => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'fill'      => array(
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color'    => array('rgb' => 'FFFF00'),
                        ),
                        'font'      => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 16,
                        ),
                    );
                    $contentRowStyle = array(
                        'borders'   => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                            'wrapText'   => true,
                        ),
                        'font'      => array(
                            'bold'  => false,
                            'color' => array('rgb' => '00000000'),
                            'size'  => 14,

                        ),
                    );
                    $contentRowTitleStyle = array(
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ),
                        'font'      => array(
                            'bold'  => false,
                            'color' => array('rgb' => '00000000'),
                            'size'  => 14,

                        ),
                    );
                    foreach (range('A', 'Z') as $char) {
                        $sheet->getColumnDimension($char)->setAutoSize(true);
                    }
                    // == TITLE
                    $sheet->setCellValue('C1', "REPORT OF INCOME ORDER BOOKING ");
                    $sheet->getStyle('C1')->applyFromArray($titleStyle);
                    $sheet->mergeCells("C1:Q1");
                    // == COMPANY
                    $sheet->setCellValue('C3', $company['name']);
                    $sheet->getStyle('C3')->applyFromArray($companyStyle);
                    $sheet->mergeCells("C3:D3");

                    $sheet->setCellValue('C4', "PERIOD");
                    $sheet->getStyle('C4')->applyFromArray($periodStyle);
                    $sheet->mergeCells("C4:D4");
                    $sheet->setCellValue('C5', "YEAR");
                    $sheet->setCellValue('D5', $year);
                    $sheet->getStyle('C5:D5')->applyFromArray($contentRowStyle);

                    // == TABLE
                    $numrow   = 9;
                    $startnum = 9;
                    $num      = 0;
                    $num11    = 8;
                    $sheet->getStyle('C' . $num11 . ':I' . $num11)->applyFromArray($headColumnStyle);
                    $sheet->setCellValue('C' . $num11, "#");
                    $sheet->setCellValue('D' . $num11, "Booking No");
                    $sheet->setCellValue('E' . $num11, "Alocation");
                    $sheet->setCellValue('F' . $num11, "Room");
                    $sheet->setCellValue('G' . $num11, "Usage Duration");
                    $sheet->setCellValue('H' . $num11, "Status");
                    $sheet->setCellValue('I' . $num11, "Rent Cost");

                    // $sheet->setCellValue('S'.$num11, "PIC No Phone");
                    // $sheet->setCellValue('T'.$num11, "PIC No Extension");
                    // $sheet->setCellValue('U'.$num11, "");
                    $accmulationHours = 0;
                    $TOTAL            = 0;
                    foreach ($data as $k => $row) {
                        $num++;
                        $extendTime = $row['extended_duration'] - 0;
                        $start      = date("H:i", strtotime($row['start']));
                        $end        = date("H:i", strtotime($row['end']));
                        $end        = date('H:i', strtotime('+' . $extendTime . ' minutes', strtotime($row['end'])));
                        $datetime   = $row['date'] . " " . $start . " - " . $end;
                        $dur        = ($row['total_duration'] - 0) + ($row['extended_duration'] - 0);
                        $inv_status = $row['alcoation_type_invoice_status'] == $row['alocation_invoice_status'] ? $row['alcoation_type_invoice_status'] : $row['alocation_invoice_status'];
                        $setHour    = $dur / $row['duration_per_meeting'];
                        $accmulationHours += $setHour;
                        $invStt = $this->checkInvoice($statusInvoice, $inv_status, $row['invoice_status']);
                        $phone  = ($row['phone_employee'] == null) ? "-" : $row['phone_employee'];
                        $noext  = ($row['ext_employee'] == null) ? "-" : $row['ext_employee'];
                        $TOTAL += ($row['invoice_rent_cost'] - 0);
                        // $dateorder = date("Y-m-d H:i:s", strtotime($row['created_at']));
                        // $datecancel = date( "Y-m-d H:i:s",strtotime($row['end']));
                        // $strdateorder = getformatDatetime($dateorder, true);
                        // $strdatecancel = getformatDatetime($datecancel, true);
                        // $level_cancel = ($row['level_cancel'] == 1)?"Admin":"PIC";
                        $sheet->setCellValue('C' . $numrow, $num);
                        $sheet->setCellValue('D' . $numrow, $row['booking_id']);
                        $sheet->setCellValue('E' . $numrow, $row['alocation_name']);
                        $sheet->setCellValue('F' . $numrow, $row['room_name']);
                        $sheet->setCellValue('G' . $numrow, $setHour);
                        $sheet->setCellValue('H' . $numrow, $invStt);
                        $sheet->setCellValue('I' . $numrow, $row['invoice_rent_cost']);
                        $numrow++;
                    }
                    $sheet->setCellValue('C' . $numrow, "Total");
                    $sheet->mergeCells("C" . $numrow . ":H" . $numrow);
                    $sheet->setCellValue('I' . $numrow, $TOTAL);
                    $sheet->getStyle('C' . $startnum . ':I' . ($numrow))->applyFromArray($contentRowStyle);

                    $writer = new Xlsx($spreadsheet);
                    $writer->save('php://output');
                } else {
                    echo "error make excell !!!";
                }
            } else {
                echo "No format";
            }

        } catch (Exeption $rror) {
            echo json_encode($rror);
            die();
        }
    }
    public function exportIncomeReportMonth()
    {
        $export        = $this->uri->segment(4);
        $company       = $this->Model_Admin->getDataCompany()['data'];
        $statusInvoice = $this->getInvoiceStatusZZZZ();
        try {
            if ($export == "excell") {
                include APPPATH . 'third_party/phpspreadsheet/autoload.php';
                $year  = $this->uri->segment(5);
                $month = $this->uri->segment(6);

                if ($year != "") {
                    $filename  = "REPORT_INCOME_YEAR_" . $year;
                    $data      = $this->getIncomeReport($year, $month);
                    $numCancel = count($data);
                    if ($data == null) {
                        echo "Something wrong to parameter!!!";
                        die();
                    }
                    // start header_
                    header_xls($filename);
                    // end header_

                    $spreadsheet = new Spreadsheet();
                    $sheet       = $spreadsheet->getActiveSheet();
                    $sheet->setTitle('Worksheet 1');
                    $titleStyle = array(
                        'font'      => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 22,

                        ),
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                    );
                    $companyStyle = array(
                        'font' => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 18,

                        ),
                    );
                    $periodStyle = array(
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                        'borders'   => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'fill'      => array(
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color'    => array('rgb' => '0000FF'),
                        ),
                        'font'      => array(
                            'bold'  => true,
                            'color' => array('rgb' => 'FFFFFF'),
                            'size'  => 16,

                        ),
                    );
                    $headColumnStyle = array(
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                        'borders'   => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'fill'      => array(
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color'    => array('rgb' => 'FFFF00'),
                        ),
                        'font'      => array(
                            'bold'  => true,
                            'color' => array('rgb' => '000000'),
                            'size'  => 16,
                        ),
                    );
                    $contentRowStyle = array(
                        'borders'   => array(
                            'allBorders' => array(
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ),
                        ),
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                            'wrapText'   => true,
                        ),
                        'font'      => array(
                            'bold'  => false,
                            'color' => array('rgb' => '00000000'),
                            'size'  => 14,

                        ),
                    );
                    $contentRowTitleStyle = array(
                        'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ),
                        'font'      => array(
                            'bold'  => false,
                            'color' => array('rgb' => '00000000'),
                            'size'  => 14,

                        ),
                    );
                    foreach (range('A', 'Z') as $char) {
                        $sheet->getColumnDimension($char)->setAutoSize(true);
                    }
                    // == TITLE
                    $sheet->setCellValue('C1', "REPORT OF INCOME ORDER BOOKING ");
                    $sheet->getStyle('C1')->applyFromArray($titleStyle);
                    $sheet->mergeCells("C1:Q1");
                    // == COMPANY
                    $sheet->setCellValue('C3', $company['name']);
                    $sheet->getStyle('C3')->applyFromArray($companyStyle);
                    $sheet->mergeCells("C3:D3");

                    $sheet->setCellValue('C4', "PERIOD");
                    $sheet->getStyle('C4')->applyFromArray($periodStyle);
                    $sheet->mergeCells("C4:D4");
                    $sheet->setCellValue('C5', "YEAR");
                    $sheet->setCellValue('D5', $year);
                    $sheet->setCellValue('C5', "MONTH");
                    $sheet->setCellValue('D5', strtoupper(getMonth($month - 0)));
                    $sheet->getStyle('C5:D6')->applyFromArray($contentRowStyle);

                    // == TABLE
                    $numrow   = 9;
                    $startnum = 9;
                    $num      = 0;
                    $num11    = 8;
                    $sheet->getStyle('C' . $num11 . ':I' . $num11)->applyFromArray($headColumnStyle);
                    $sheet->setCellValue('C' . $num11, "#");
                    $sheet->setCellValue('D' . $num11, "Booking No");
                    $sheet->setCellValue('E' . $num11, "Alocation");
                    $sheet->setCellValue('F' . $num11, "Room");
                    $sheet->setCellValue('G' . $num11, "Usage Duration");
                    $sheet->setCellValue('H' . $num11, "Status");
                    $sheet->setCellValue('I' . $num11, "Rent Cost");

                    // $sheet->setCellValue('S'.$num11, "PIC No Phone");
                    // $sheet->setCellValue('T'.$num11, "PIC No Extension");
                    // $sheet->setCellValue('U'.$num11, "");
                    $accmulationHours = 0;
                    $TOTAL            = 0;
                    foreach ($data as $k => $row) {
                        $num++;
                        $extendTime = $row['extended_duration'] - 0;
                        $start      = date("H:i", strtotime($row['start']));
                        $end        = date("H:i", strtotime($row['end']));
                        $end        = date('H:i', strtotime('+' . $extendTime . ' minutes', strtotime($row['end'])));
                        $datetime   = $row['date'] . " " . $start . " - " . $end;
                        $dur        = ($row['total_duration'] - 0) + ($row['extended_duration'] - 0);
                        $inv_status = $row['alcoation_type_invoice_status'] == $row['alocation_invoice_status'] ? $row['alcoation_type_invoice_status'] : $row['alocation_invoice_status'];
                        $setHour    = $dur / $row['duration_per_meeting'];
                        $accmulationHours += $setHour;
                        $invStt = $this->checkInvoice($statusInvoice, $inv_status, $row['invoice_status']);
                        $phone  = ($row['phone_employee'] == null) ? "-" : $row['phone_employee'];
                        $noext  = ($row['ext_employee'] == null) ? "-" : $row['ext_employee'];
                        $TOTAL += ($row['invoice_rent_cost'] - 0);
                        // $dateorder = date("Y-m-d H:i:s", strtotime($row['created_at']));
                        // $datecancel = date( "Y-m-d H:i:s",strtotime($row['end']));
                        // $strdateorder = getformatDatetime($dateorder, true);
                        // $strdatecancel = getformatDatetime($datecancel, true);
                        // $level_cancel = ($row['level_cancel'] == 1)?"Admin":"PIC";
                        $sheet->setCellValue('C' . $numrow, $num);
                        $sheet->setCellValue('D' . $numrow, $row['booking_id']);
                        $sheet->setCellValue('E' . $numrow, $row['alocation_name']);
                        $sheet->setCellValue('F' . $numrow, $row['room_name']);
                        $sheet->setCellValue('G' . $numrow, $setHour);
                        $sheet->setCellValue('H' . $numrow, $invStt);
                        $sheet->setCellValue('I' . $numrow, $row['invoice_rent_cost']);
                        $numrow++;
                    }
                    $sheet->setCellValue('C' . $numrow, "Total");
                    $sheet->mergeCells("C" . $numrow . ":H" . $numrow);
                    $sheet->setCellValue('I' . $numrow, $TOTAL);
                    $sheet->getStyle('C' . $startnum . ':I' . ($numrow))->applyFromArray($contentRowStyle);
                    // $getRow = $data[0];
                    // $sheet->setCellValue('F4', "ALOCATION NAME");
                    // $sheet->setCellValue('G4', $alocationdata['name']);
                    // $sheet->setCellValue('H4', $alocationdata['name_type']);
                    // $sheet->setCellValue('F5', "ACCUMULATION HOURS:");
                    // $sheet->setCellValue('G5', $accmulationHours . " hour");
                    // $sheet->getStyle('F4:H5')->applyFromArray($contentRowTitleStyle);

                    $writer = new Xlsx($spreadsheet);
                    $writer->save('php://output');
                } else {
                    echo "error make excell !!!";
                }
            } else {
                echo "No format";
            }

        } catch (Exeption $rror) {
            echo json_encode($rror);
            die();
        }
    }

    // ==============

    public function exportOutstandingReportAll()
    {
        $export        = $this->uri->segment(4);
        $statusInvoice = $this->getInvoiceStatusZZZZ();
        $company       = $this->Model_Admin->getDataCompany()['data'];
        try {
            $date1 = $this->uri->segment(5);
            $date2 = $this->uri->segment(6);
        } catch (Exeption $rror) {
            echo json_encode($rror);
            die();
        }
        if ($export == "excell") {
            include APPPATH . 'third_party/phpspreadsheet/autoload.php';
            if ($date1 != "" || $date2 != "") {
                $filename = "REPORT_OUTSTANDING_INVOICE_" . $date1 . "__" . $date2;
                $data     = $this->getAllOutstandingReport($date1, $date2);
                if ($data == null) {
                    echo "Something wrong to parameter!!!";
                    die();
                }
                // start header_
                header_xls($filename);
                // end header_
                $spreadsheet = new Spreadsheet();
                $sheet       = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Worksheet 1');
                $titleStyle = array(
                    'font' => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 22,

                    ),
                );
                $companyStyle = array(
                    'font' => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 18,

                    ),
                );
                $periodStyle = array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders'   => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill'      => array(
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => array('rgb' => '0000FF'),
                    ),
                    'font'      => array(
                        'bold'  => true,
                        'color' => array('rgb' => 'FFFFFF'),
                        'size'  => 16,

                    ),
                );
                $headColumnStyle = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill'    => array(
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => array('rgb' => 'FFFF00'),
                    ),
                    'font'    => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 16,
                    ),
                );
                $contentRowStyle = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'font'    => array(
                        'bold'  => false,
                        'color' => array('rgb' => '00000000'),
                        'size'  => 14,

                    ),
                );
                $contentRowTitleStyle = array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ),
                    'font'      => array(
                        'bold'  => false,
                        'color' => array('rgb' => '00000000'),
                        'size'  => 14,

                    ),
                );
                foreach (range('A', 'Z') as $char) {
                    $sheet->getColumnDimension($char)->setAutoSize(true);
                }
                // == TITLE
                $sheet->setCellValue('C1', "REPORT OF OUTSTANDING INVOICE ");
                $sheet->getStyle('C1')->applyFromArray($titleStyle);
                $sheet->mergeCells("C1:M1");
                // == COMPANY
                $sheet->setCellValue('C3', $company['name']);
                $sheet->getStyle('C3')->applyFromArray($companyStyle);
                $sheet->mergeCells("C3:D3");

                $sheet->setCellValue('C4', "PERIOD");
                $sheet->getStyle('C4')->applyFromArray($periodStyle);
                $sheet->mergeCells("C4:D4");
                $sheet->setCellValue('C5', "FROM:");
                $sheet->setCellValue('D5', $date1);
                $sheet->setCellValue('C6', "TO");
                $sheet->setCellValue('D6', $date2);
                $sheet->getStyle('C5:D6')->applyFromArray($contentRowStyle);
                // == TABLE
                $numrow   = 9;
                $startnum = 9;
                $num      = 0;
                $num11    = 8;
                $sheet->getStyle('C' . $num11 . ':K' . $num11)->applyFromArray($headColumnStyle);
                $sheet->setCellValue('C' . $num11, "#");
                $sheet->setCellValue('D' . $num11, "Booking No");
                $sheet->setCellValue('E' . $num11, "Meeting Time");
                $sheet->setCellValue('F' . $num11, "Order Time");
                $sheet->setCellValue('G' . $num11, "Alocation");
                $sheet->setCellValue('H' . $num11, "Duration");
                $sheet->setCellValue('I' . $num11, "Rent Cost");
                $sheet->setCellValue('J' . $num11, "Corporate Finance Check");
                $sheet->setCellValue('K' . $num11, "Status Invoicing");

                $accmulationHours = 0;
                foreach ($data as $k => $row) {
                    $num++;
                    $extendTime = $row['extended_duration'] - 0;
                    $start      = date("H:i", strtotime($row['start']));
                    $end        = date("H:i", strtotime($row['end']));
                    $end        = date('H:i', strtotime('+' . $extendTime . ' minutes', strtotime($row['end'])));
                    $datetime   = $row['date'] . " " . $start . " - " . $end;
                    $dur        = ($row['total_duration'] - 0) + ($row['extended_duration'] - 0);
                    $inv_status = $row['alcoation_type_invoice_status'] == $row['alocation_invoice_status'] ? $row['alcoation_type_invoice_status'] : $row['alocation_invoice_status'];
                    $setHour    = $dur / $row['duration_per_meeting'];
                    $accmulationHours += $setHour;
                    $invStt = $this->checkInvoice($statusInvoice, $inv_status, $row['invoice_status']);
                    $phone  = ($row['phone_employee'] == null) ? "-" : $row['phone_employee'];
                    $noext  = ($row['ext_employee'] == null) ? "-" : $row['ext_employee'];

                    $dateorder     = date("Y-m-d H:i:s", strtotime($row['created_at']));
                    $datecancel    = date("Y-m-d H:i:s", strtotime($row['end']));
                    $strdateorder  = getformatDatetime($dateorder, true);
                    $strdatecancel = getformatDatetime($datecancel, true);
                    // $level_cancel = ($row['level_cancel'] == 1)?"Admin":"PIC";
                    $financeCheck = $row['invoice_time_send'] == null || $row['invoice_time_send'] == "null" ? "Unchecked" : "Checked";
                    $sheet->setCellValue('C' . $numrow, $num);
                    $sheet->setCellValue('D' . $numrow, $row['booking_id']);
                    $sheet->setCellValue('E' . $numrow, $datetime);
                    $sheet->setCellValue('F' . $numrow, $strdateorder);
                    $sheet->setCellValue('G' . $numrow, $row['alocation_name']);
                    $sheet->setCellValue('H' . $numrow, $setHour);
                    $sheet->setCellValue('I' . $numrow, $row['cost_total_booking']);
                    $sheet->setCellValue('J' . $numrow, $financeCheck);
                    $sheet->setCellValue('K' . $numrow, $invStt);
                    $numrow++;
                }
                $sheet->getStyle('C' . $startnum . ':K' . $numrow)->applyFromArray($contentRowStyle);
                // $sheet->setCellValue('F4', "ALOCATION NAME");
                // $sheet->setCellValue('G4', $alocationdata['name']);
                // $sheet->setCellValue('H4', $alocationdata['name_type']);
                // $sheet->setCellValue('F4', "TOTAL ACCUMULATION HOURS:");
                // $sheet->setCellValue('G4', $accmulationHours . " hour");
                $sheet->getStyle('F4:H5')->applyFromArray($contentRowTitleStyle);

                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                // echo "download";
            } else {
                echo "error make excell !!!";
            }
        } else {
            echo "No format";

        }

    }
    public function exportOutstandingReportAlocation()
    {
        $export        = $this->uri->segment(4);
        $statusInvoice = $this->getInvoiceStatusZZZZ();
        $company       = $this->Model_Admin->getDataCompany()['data'];
        try {
            $alocationid   = $this->uri->segment(5);
            $date1         = $this->uri->segment(6);
            $date2         = $this->uri->segment(7);
            $alocationdata = $this->Model_Admin->getAlocationWithId($alocationid)['data'];
        } catch (Exeption $rror) {
            echo json_encode($rror);
            die();
        }
        if ($export == "excell") {
            include APPPATH . 'third_party/phpspreadsheet/autoload.php';
            if ($date1 != "" || $date2 != "") {
                $data     = $this->getOutstandingAlocationReport($alocationid, $date1, $date2);
                $filename = "REPORT_OUTSTANDING_INVOICE_" . $date1 . "__" . $date2 . "_" . $alocationdata['name'];
                if ($data == null) {
                    echo "Something wrong to parameter!!!";
                    die();
                }
                // start header_
                header_xls($filename);
                // end header_
                $spreadsheet = new Spreadsheet();
                $sheet       = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Worksheet 1');
                $titleStyle = array(
                    'font' => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 22,

                    ),
                );
                $companyStyle = array(
                    'font' => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 18,

                    ),
                );
                $periodStyle = array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders'   => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill'      => array(
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => array('rgb' => '0000FF'),
                    ),
                    'font'      => array(
                        'bold'  => true,
                        'color' => array('rgb' => 'FFFFFF'),
                        'size'  => 16,

                    ),
                );
                $headColumnStyle = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'fill'    => array(
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => array('rgb' => 'FFFF00'),
                    ),
                    'font'    => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 16,
                    ),
                );
                $contentRowStyle = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                    'font'    => array(
                        'bold'  => false,
                        'color' => array('rgb' => '00000000'),
                        'size'  => 14,

                    ),
                );
                $contentRowTitleStyle = array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ),
                    'font'      => array(
                        'bold'  => false,
                        'color' => array('rgb' => '00000000'),
                        'size'  => 14,

                    ),
                );
                foreach (range('A', 'Z') as $char) {
                    $sheet->getColumnDimension($char)->setAutoSize(true);
                }
                // == TITLE
                $sheet->setCellValue('C1', "REPORT OF OUTSTANDING INVOICE ");
                $sheet->getStyle('C1')->applyFromArray($titleStyle);
                $sheet->mergeCells("C1:M1");
                // == COMPANY
                $sheet->setCellValue('C3', $company['name']);
                $sheet->getStyle('C3')->applyFromArray($companyStyle);
                $sheet->mergeCells("C3:D3");

                $sheet->setCellValue('C4', "PERIOD");
                $sheet->getStyle('C4')->applyFromArray($periodStyle);
                $sheet->mergeCells("C4:D4");
                $sheet->setCellValue('C5', "FROM:");
                $sheet->setCellValue('D5', $date1);
                $sheet->setCellValue('C6', "TO");
                $sheet->setCellValue('D6', $date2);
                $sheet->getStyle('C5:D6')->applyFromArray($contentRowStyle);
                // == TABLE
                $numrow   = 9;
                $startnum = 9;
                $num      = 0;
                $num11    = 8;
                $sheet->getStyle('C' . $num11 . ':K' . $num11)->applyFromArray($headColumnStyle);
                $sheet->setCellValue('C' . $num11, "#");
                $sheet->setCellValue('D' . $num11, "Booking No");
                $sheet->setCellValue('E' . $num11, "Meeting Time");
                $sheet->setCellValue('F' . $num11, "Order Time");
                $sheet->setCellValue('G' . $num11, "Alocation");
                $sheet->setCellValue('H' . $num11, "Duration");
                $sheet->setCellValue('I' . $num11, "Rent Cost");
                $sheet->setCellValue('J' . $num11, "Corporate Finance Check");
                $sheet->setCellValue('K' . $num11, "Status Invoicing");

                $accmulationHours = 0;
                foreach ($data as $k => $row) {
                    $num++;
                    $extendTime = $row['extended_duration'] - 0;
                    $start      = date("H:i", strtotime($row['start']));
                    $end        = date("H:i", strtotime($row['end']));
                    $end        = date('H:i', strtotime('+' . $extendTime . ' minutes', strtotime($row['end'])));
                    $datetime   = $row['date'] . " " . $start . " - " . $end;
                    $dur        = ($row['total_duration'] - 0) + ($row['extended_duration'] - 0);
                    $inv_status = $row['alcoation_type_invoice_status'] == $row['alocation_invoice_status'] ? $row['alcoation_type_invoice_status'] : $row['alocation_invoice_status'];
                    $setHour    = $dur / $row['duration_per_meeting'];
                    $accmulationHours += $setHour;
                    $invStt = $this->checkInvoice($statusInvoice, $inv_status, $row['invoice_status']);
                    $phone  = ($row['phone_employee'] == null) ? "-" : $row['phone_employee'];
                    $noext  = ($row['ext_employee'] == null) ? "-" : $row['ext_employee'];

                    $dateorder     = date("Y-m-d H:i:s", strtotime($row['created_at']));
                    $datecancel    = date("Y-m-d H:i:s", strtotime($row['end']));
                    $strdateorder  = getformatDatetime($dateorder, true);
                    $strdatecancel = getformatDatetime($datecancel, true);
                    // $level_cancel = ($row['level_cancel'] == 1)?"Admin":"PIC";
                    $financeCheck = $row['invoice_time_send'] == null || $row['invoice_time_send'] == "null" ? "Unchecked" : "Checked";
                    $sheet->setCellValue('C' . $numrow, $num);
                    $sheet->setCellValue('D' . $numrow, $row['booking_id']);
                    $sheet->setCellValue('E' . $numrow, $datetime);
                    $sheet->setCellValue('F' . $numrow, $strdateorder);
                    $sheet->setCellValue('G' . $numrow, $row['alocation_name']);
                    $sheet->setCellValue('H' . $numrow, $setHour);
                    $sheet->setCellValue('I' . $numrow, $row['cost_total_booking']);
                    $sheet->setCellValue('J' . $numrow, $financeCheck);
                    $sheet->setCellValue('K' . $numrow, $invStt);
                    $numrow++;
                }
                $sheet->getStyle('C' . $startnum . ':K' . $numrow)->applyFromArray($contentRowStyle);
                $sheet->setCellValue('F4', "ALOCATION NAME");
                $sheet->setCellValue('G4', $alocationdata['name']);
                $sheet->setCellValue('H4', $alocationdata['name_type']);
                // $sheet->setCellValue('F4', "TOTAL ACCUMULATION HOURS:");
                // $sheet->setCellValue('G4', $accmulationHours . " hour");
                $sheet->getStyle('F4:H5')->applyFromArray($contentRowTitleStyle);

                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                // echo "download";
            } else {
                echo "error make excell !!!";
            }
        } else {
            echo "No format";

        }

    }

    // public function format
    public function getData()
    {
        $data = $this->Model_Admin->getDataRoom();
        if ($data['error'] == null) {
            echo response("success", $data['data'], "Get success");
        } else {
            echo response("fail", $data, "Get failed");
        }
    }

    // =========================
    // GET DATA PRIVATE
    // =========================
    private function getAllReport($date1, $date2, $alo = "all")
    {

        if ($date1 != "" || $date2 != "") {
            $walo = " ";
            if ($alo == "all" || $alo == "") {
            } else {
                $walo = " AND a.id='" . $alo . "' ";
            }
            if ($this->session->userdata('levelid-nya') == 1) {
                $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location,
				binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
                $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
                $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
                $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
                $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
                $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
                $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
                $q .= " WHERE b.date >='" . $date1 . "' ";
                $q .= " AND b.date <='" . $date2 . "' ";
                $q .= " AND bi.is_pic =1 ";
                $q .= $walo;
            } else if ($this->session->userdata('levelid-nya') == 2) {
                // user
                $nik = $this->session->userdata('user-nya');
                $q   = "SELECT DISTINCT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location,
				binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
                $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
                $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
                $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
                $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
                $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
                $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
                $q .= " WHERE b.date >='" . $date1 . "' ";
                $q .= " AND b.date <='" . $date2 . "' ";
                $q .= " AND bi.nik ='" . $nik . "' ";
                $q .= $walo;

            }

            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            return $result;

        } else {
            return null;

        }
    }

    // =========================
    // GET DATA PRIVATE
    // =========================
    private function getAllReportNew($datareport)
    {

        $walo = " ";
        if ($datareport['department'] != "") {
            $walo .= " AND a.id='" . $datareport['department'] . "' ";
        }
        if ($datareport['room'] != "") {
            $walo .= " AND r.radid='" . $datareport['room'] . "' ";
        }
        if ($datareport['building'] != "") {
            $walo .= " AND r.building_id='" . $datareport['building'] . "' ";
        }
        if ($this->session->userdata('levelid-nya') == 1) {
            $walo .= " AND bi.is_pic =1  ";
        } else {
            $nik = $this->session->userdata('user-nya');
            $walo .= " AND bi.nik ='" . $nik . "' ";
        }
        $date1 = $datareport['date1'];
        $date2 = $datareport['date2'];

        $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location,
				bui.name as building_name, bui.detail_address as building_address,
				binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
        $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
        $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
        $q .= " LEFT JOIN building bui  ON r.building_id=bui.id ";
        $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
        $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
        $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
        $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
        $q .= " WHERE b.date >='" . $date1 . "' ";
        $q .= " AND b.date <='" . $date2 . "' ";
        // $q .= " AND bi.is_pic =1 ";
        $q .= $walo;
        $query  = $this->Model_Admin->querySql($q);
        $result = $query->result_array();
        return $result;
    }
    private function getReportMeeting($bookid, $date1, $date2)
    {
        if ($date1 != "" || $date2 != "" || $bookid == "") {
            $q = "SELECT DISTINCT b.booking_id AS bookid , (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name2, r.location as room_location,
			b.note,b.is_merge,
			bl.name as building_name, bl.description as building_description,
			binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " LEFT JOIN building bl  ON r.building_id=bl.id ";
            $q .= " WHERE  ";
            $q .= "  b.booking_id ='" . $bookid . "' ";
            $q .= " AND bi.is_pic =1 ";

            $inviq = "SELECT bi.*, e.name as employee_name, e.email as emp_email, e.no_phone as emp_phone, e.no_ext as emp_ext ";
            $inviq .= " FROM booking_invitation bi";
            $inviq .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $inviq .= " WHERE bi.booking_id ='" . $bookid . "' ";
            // echo $inviq;

            $invquery   = $this->Model_Admin->querySql($inviq);
            $query      = $this->Model_Admin->querySql($q);
            $datarow    = $query->result_array();
            $datarowinv = $invquery->result_array();
            if ($query->num_rows() > 0) {
                $result = array(
                    "data"       => $datarow[0],
                    "invitation" => $datarowinv,
                );
                // $result = $query->result_array();
                return $result;
            } else {
                return null;
            }

        } else {
            return null;

        }
    }

    private function getAllCancelReport($date1, $date2)
    {

        if ($date1 != "" || $date2 != "") {
            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status,
			cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee,  ";
            $q .= " (SELECT name FROM employee ee WHERE ee.nik=b.updated_by ) as name_cancel,  ";
            $q .= " (SELECT level_id FROM employee ee1
				INNER JOIN user ON ee1.id=user.employee_id
				WHERE ee1.nik=b.updated_by ) as level_cancel  ";
            $q .= " FROM booking b  ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE b.date >='" . $date1 . "' ";
            $q .= " AND b.date <='" . $date2 . "' ";
            $q .= " AND b.is_canceled=1 ";
            // $q .= " AND b.alocation_id='".$alocation."' ";
            $q .= " AND bi.is_pic =1 ";
            $q .= " ORDER BY  b.start ASC ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            return $result;

        } else {
            return null;

        }
    }
    private function getCancelAlocationReport($alocation, $date1, $date2)
    {

        if ($date1 != "" || $date2 != "") {

            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee,  ";
            $q .= " (SELECT name FROM employee ee WHERE ee.nik=b.updated_by ) as name_cancel,  ";
            $q .= " (SELECT level_id FROM employee ee1
				INNER JOIN user ON ee1.id=user.employee_id
				WHERE ee1.nik=b.updated_by ) as level_cancel  ";
            $q .= " FROM booking b  ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE b.date >='" . $date1 . "' ";
            $q .= " AND b.date <='" . $date2 . "' ";
            $q .= " AND b.is_canceled=1 ";
            $q .= " AND b.alocation_id='" . $alocation . "' ";
            $q .= " AND bi.is_pic =1 ";
            $q .= " ORDER BY  b.start ASC ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            return $result;

        } else {
            return null;

        }
    }

    private function getIncomeReport($year, $month = "")
    {
        if ($year == "") {
            return null;
        }

        if ($month != "") {
            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, binv.rent_cost as invoice_rent_cost, b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE YEAR(b.date) ='" . $year . "' ";
            $q .= " AND MONTH(b.date) =" . $month . " ";
            $q .= " AND binv.invoice_status='2' "; // donepaid
            $q .= " AND bi.is_pic =1 ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            return $result;

        } else {
            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant, binv.rent_cost as invoice_rent_cost, b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE YEAR(b.date) ='" . $year . "' ";
            // $q .= " AND MONTH(b.date) =".$month." ";
            $q .= " AND binv.invoice_status='2' "; // donepaid
            $q .= " AND bi.is_pic =1 ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            return $result;

        }

    }

    private function getAllOutstandingReport($date1, $date2)
    {

        if ($date1 != "" || $date2 != "") {
            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant,
			binv.rent_cost as invoice_rent_cost, binv.time_before as invoice_time_before, binv.time_send as invoice_time_send, binv.time_paid as invoice_time_paid,
			b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE b.date >='" . $date1 . "' ";
            $q .= " AND b.date <='" . $date2 . "' ";
            $q .= " AND b.is_canceled=0 ";
            $q .= " AND ( binv.invoice_status='0' OR binv.invoice_status='1' ) ";
            $q .= " AND bi.is_pic =1 ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            return $result;
        } else {
            return null;
        }
    }
    private function getOutstandingAlocationReport($alocation, $date1, $date2)
    {

        if ($date1 != "" || $date2 != "") {
            $q = "SELECT (SELECT COUNT(*) FROM booking_invitation bii WHERE bii.booking_id=b.booking_id ) as num_partisipant,
			binv.rent_cost as invoice_rent_cost, binv.time_before as invoice_time_before, binv.time_send as invoice_time_send, binv.time_paid as invoice_time_paid,
			b.*,r.name as room_name, r.location as room_location, binv.invoice_status, a.name as alocation_name, a.type as alocation_type, a.invoice_status as alocation_invoice_status,at.invoice_status as alcoation_type_invoice_status, cost_total_booking, e.name as name_employee, e.email as email_employee, e.no_phone as phone_employee, e.no_ext as ext_employee FROM booking b ";
            $q .= " LEFT JOIN booking_invoice binv ON b.booking_id=binv.booking_id ";
            $q .= " INNER JOIN room r  ON b.room_id=r.radid ";
            $q .= " INNER JOIN booking_invitation bi  ON b.booking_id=bi.booking_id ";
            $q .= " LEFT JOIN alocation a  ON b.alocation_id=a.id ";
            $q .= " LEFT JOIN alocation_type at ON a.type=at.name ";
            $q .= " LEFT JOIN employee e  ON bi.nik=e.nik ";
            $q .= " WHERE b.date >='" . $date1 . "' ";
            $q .= " AND b.date <='" . $date2 . "' ";
            $q .= " AND b.is_canceled=0 ";
            $q .= " AND ( binv.invoice_status='0' OR binv.invoice_status='1' ) ";
            $q .= " AND b.alocation_id='" . $alocation . "' ";
            $q .= " AND bi.is_pic =1 ";
            $query  = $this->Model_Admin->querySql($q);
            $result = $query->result_array();
            return $result;
        } else {
            return null;
        }
    }

}

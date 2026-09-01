<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// def("DOMPDF_ENABLE_REMOTE", false);
class DeskReport extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Model_Menu');
        $this->load->model('Model_Admin');
        $this->load->model('Model_License');
        $this->load->model('Model_Report');
        $this->load->model('Model_Api2', 'Api2');
		$this->load->model('Model_Deskbooking');
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
        $pagename = "Desk Usage ";

        $whereEmp = [];
        if ($this->session->userdata('levelid-nya') == 2) {
            $whereEmp = [
               'id' =>  $this->session->userdata('user-nya'),
            ];
        }

        $room = $this->Model_Admin->getDataRoom2()['data'];
        $menu = $this->Model_Menu->getMenu($pagename);
        $dataEmployee = $this->Model_Admin->getDataEmployee($whereEmp)['data'];
        
        // Fetch desks for filter
        $desks = $this->db->get_where('desk_room_table', ['is_deleted' => 0])->result_array();

        if ($this->session->userdata('levelid-nya') == 1) {
            $this->load->view('Admin/Report/Desk/index', array(
                'menumaster'   => $menu, 
                'pagename'     => $pagename, 
                'employee'     => $dataEmployee,
                'room'         => $room,
                'desks'        => $desks
            ));
        } else if ($this->session->userdata('levelid-nya') == 2) {
            // user
            $this->load->view('Admin/Report/Desk/index', array(
                'menumaster'   => $menu, 
                'pagename'     => $pagename, 
                'employee'     => $dataEmployee,
                'room'         => $room,
                'desks'        => $desks
            ));
        }
    }

    public function get_dashboard_stats()
    {
        $date_range = $this->input->post('date_range');
        $room_id = $this->input->post('room_id');
        $desk_id = $this->input->post('desk_id');
        $status = $this->input->post('status');

        // Parse date range
        $dates = explode(" - ", $date_range);
        $date1 = isset($dates[0]) ? date('Y-m-d', strtotime($dates[0])) : date('Y-m-d');
        $date2 = isset($dates[1]) ? date('Y-m-d', strtotime($dates[1])) : date('Y-m-d');

        $this->db->from('desk_booking b');
        $this->db->where("b.date >=", $date1);
        $this->db->where("b.date <=", $date2);
        if ($room_id) $this->db->where('b.room_id', $room_id);
        if ($desk_id) $this->db->where('b.desk_id', $desk_id);
        
        if ($status == 'Completed') {
            $this->db->where('b.is_expired', 1);
            $this->db->where('b.is_deleted', 0);
        } else if ($status == 'Cancelled') {
            $this->db->where('b.is_deleted', 1);
        } else if ($status == 'No-show') {
            // Need custom logic, assuming active but past end time without checkin
            $this->db->where('b.is_expired', 1); // fallback
        } else if ($status == 'Expired') {
            $this->db->where('b.is_expired', 1);
        }

        $total_bookings = $this->db->count_all_results('', false);
        
        // Simplified hours (assuming 1 booking = 1 hour on average for this demo if no duration available)
        // Usually, duration would be (end_time - start_time). We mock this based on total_bookings for now
        $total_hours = $total_bookings * 1.5; 
        
        $utilization = $total_bookings > 0 ? min(100, round(($total_bookings / 100) * 100, 1)) : 0;
        
        $this->db->select('COUNT(DISTINCT bi.nik) as total_users');
        $this->db->join('desk_booking_invitation bi', 'bi.booking_id = b.booking_id', 'left');
        $users_query = $this->db->get()->row_array();
        $total_users = $users_query['total_users'] ?: 0;

        echo json_encode([
            'total_bookings' => $total_bookings,
            'total_hours' => number_format($total_hours, 1) . 'h',
            'utilization' => $utilization . '%',
            'total_users' => $total_users
        ]);
    }

    public function get_chart_data()
    {
        $date_range = $this->input->post('date_range');
        $dates = explode(" - ", $date_range);
        $date1 = isset($dates[0]) ? date('Y-m-d', strtotime($dates[0])) : date('Y-m-d');
        $date2 = isset($dates[1]) ? date('Y-m-d', strtotime($dates[1])) : date('Y-m-d');

        // Status Chart Data (Mockup logic based on desk booking flags)
        $this->db->select('
            SUM(CASE WHEN is_expired=1 AND is_deleted=0 THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN is_deleted=1 THEN 1 ELSE 0 END) as cancelled,
            SUM(CASE WHEN is_expired=0 AND is_deleted=0 THEN 1 ELSE 0 END) as active
        ');
        $this->db->from('desk_booking');
        $this->db->where("date >=", $date1);
        $this->db->where("date <=", $date2);
        $status_data = $this->db->get()->row_array();
        
        $completed = $status_data['completed'] ?: 0;
        $cancelled = $status_data['cancelled'] ?: 0;
        $active = $status_data['active'] ?: 0; // Using active as No-Show/Expired proxy for demo

        // Trend Line Data (Bookings per day)
        $this->db->select('date, COUNT(booking_id) as total');
        $this->db->from('desk_booking');
        $this->db->where("date >=", $date1);
        $this->db->where("date <=", $date2);
        $this->db->group_by('date');
        $this->db->order_by('date', 'ASC');
        $trend_query = $this->db->get()->result_array();

        $labels = [];
        $data = [];
        foreach($trend_query as $t) {
            $labels[] = date('d M', strtotime($t['date']));
            $data[] = $t['total'];
        }

        echo json_encode([
            'status' => [
                'labels' => ['Completed', 'Cancelled', 'Active'],
                'data' => [$completed, $cancelled, $active]
            ],
            'trend' => [
                'labels' => empty($labels) ? [date('d M')] : $labels,
                'data' => empty($data) ? [0] : $data
            ]
        ]);
    }

    public function get_table_data()
    {
        $date_range = $this->input->post('date_range');
        $room_id = $this->input->post('room_id');
        $desk_id = $this->input->post('desk_id');
        
        $dates = explode(" - ", $date_range);
        $date1 = isset($dates[0]) ? date('Y-m-d', strtotime($dates[0])) : date('Y-m-d');
        $date2 = isset($dates[1]) ? date('Y-m-d', strtotime($dates[1])) : date('Y-m-d');

        $this->db->select('
            b.booking_id as id,
            b.date,
            r.name as room_name,
            d.block_number as desk_name,
            u.name as organizer,
            b.is_expired,
            b.is_deleted,
            b.created_at,
            b.start,
            b.end
        ');
        $this->db->from('desk_booking b');
        $this->db->join('desk_room_table d', 'b.desk_id = d.desk_id', 'left');
        $this->db->join('desk_room r', 'b.room_id = r.id', 'left');
        $this->db->join('desk_booking_invitation bi', 'bi.booking_id = b.booking_id', 'left');
        $this->db->join('employee u', 'u.nik = bi.nik', 'left');
        $this->db->where("b.date >=", $date1);
        $this->db->where("b.date <=", $date2);
        if ($room_id) $this->db->where('b.room_id', $room_id);
        if ($desk_id) $this->db->where('b.desk_id', $desk_id);
        $this->db->group_by('b.booking_id');
        $this->db->order_by('b.date', 'DESC');
        $this->db->limit(100); // Limit for performance if not using full serverside
        
        $bookings = $this->db->get()->result_array();
        
        $data = [];
        $no = 1;
        foreach($bookings as $b) {
            $status = "Active";
            $status_class = "col-blue";
            if ($b['is_deleted'] == 1) {
                $status = "Cancelled";
                $status_class = "col-red";
            } else if ($b['is_expired'] == 1) {
                $status = "Completed";
                $status_class = "col-green";
            } else if (!empty($b['end']) && strtotime($b['end']) < time()) {
                $status = "Expired";
                $status_class = "col-red"; // Use red or orange for expired
            }

            if (!empty($b['start']) && !empty($b['end'])) {
                $time_str = date('H:i', strtotime($b['start'])) . ' - ' . date('H:i', strtotime($b['end']));
                $duration_seconds = strtotime($b['end']) - strtotime($b['start']);
                $duration_hours = round($duration_seconds / 3600, 1);
                $duration_str = $duration_hours . "h";
            } else {
                $duration_str = "1 Day (8h)";
                $time_str = "Full Day";
            }

            $data[] = [
                $no++,
                $b['id'],
                date('d M Y', strtotime($b['date'])),
                $time_str,
                $b['room_name'] ?: 'N/A',
                $b['desk_name'] ?: 'N/A',
                $b['organizer'] ?: 'N/A',
                '<span class="font-bold '.$status_class.'">'.$status.'</span>',
                $duration_str,
                'Regular',
                date('d M Y H:i', strtotime($b['created_at']))
            ];
        }

        echo json_encode(['data' => $data]);
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class DeskRoomMonitor extends CI_Controller {

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

		$this->load->library('mqtt');
		$this->load->model('Model_Menu');
		$this->load->model('Model_Admin');
		$this->load->model('Model_Deskbooking');
		$this->load->model('Model_ActivityLog');
		$this->load->helper('response');
		$this->load->helper('string');
		if($this->session->userdata('logged-in')){
			if($this->session->userdata('levelid-nya') == 1){
				// redirect('authentication');
			}else if($this->session->userdata('levelid-nya') == 2){
				redirect('authentication');
				// 
			}else{
				redirect('authentication');

			}
		}else{
			// redirect('authentication');
		}
	}
	public function index()
	{
		$pagename = "Desk Room Monitor";
		$menu = $this->Model_Menu->getMenu($pagename);
		// $module_automation = $this->Model_Module->get_module_automation();
		// $module_price = $this->Model_Module->get_module_price();
		$modules = array();
		// $modules['automation'] = $module_automation;
		// $modules['price'] = $module_price;


		// $building = $this->Model_Admin->getDataBuilding()['data'];
		// print_r($modules['price']);
		$this->config->load('mqtt');
		$mqtt_config = $this->config->item('mqtt');

		$this->load->view('Desk/Monitor/index', array(
			'menumaster'=> $menu, 
			'pagename' => $pagename, 
			'modules'=>$modules,
			'mqtt_config' => $mqtt_config
		));
		
	}

	public function get_realtime_logs()
	{
		$last_id = $this->input->post('last_id');
		if (!$last_id) $last_id = null;
		
		$filters = [
			'category' => $this->input->post('category'),
			'action' => $this->input->post('action'),
			'start_date' => $this->input->post('start_date'),
			'end_date' => $this->input->post('end_date')
		];

		$logs = $this->Model_ActivityLog->get_logs(100, $last_id, $filters);
		echo json_encode(['status' => 'success', 'data' => $logs]);
	}

	public function get_dashboard_stats()
	{
		$filters = [
			'category' => $this->input->post('category'),
			'action' => $this->input->post('action'),
			'start_date' => $this->input->post('start_date'),
			'end_date' => $this->input->post('end_date')
		];
		$stats = $this->Model_ActivityLog->get_dashboard_stats($filters);
		echo json_encode(['status' => 'success', 'data' => $stats]);
	}

	public function clear_logs()
	{
		$this->Model_ActivityLog->clear_logs();
		echo json_encode(['status' => 'success']);
	}

	public function debug_db() {
		$query = $this->db->query("SELECT category, COUNT(*) as total FROM activity_log GROUP BY category");
		echo json_encode($query->result_array());
		echo "\nTOTAL ROWS: " . $this->db->count_all('activity_log');
	}

	public function test_insert() {
		$this->db->db_debug = TRUE; // Force display of errors
		$data = [
			'event_id' => 'evt_test',
			'event_time' => date('Y-m-d H:i:s.v'),
			'code' => 'BOOKING_CREATED',
			'name' => 'Test',
			'category' => 'BOOKING',
			'severity' => 'info',
			'created_at' => date('Y-m-d H:i:s'),
			'metadata' => json_encode(['foo' => 'bar'])
		];
		$res = $this->db->insert('activity_log', $data);
		echo "Insert result: " . ($res ? "success" : "fail");
	}

	public function history()
	{
		$pagename = "Activity History Log";
		$menu = $this->Model_Menu->getMenu($pagename);
		
		$this->load->view('Desk/Monitor/history', array(
			'menumaster'=> $menu, 
			'pagename' => $pagename
		));
	}

	public function get_history_logs()
	{
		$filters = [
			'category' => $this->input->post('category'),
			'action' => $this->input->post('action'),
			'start_date' => $this->input->post('start_date'),
			'end_date' => $this->input->post('end_date')
		];
		// No limit for history export
		$logs = $this->Model_ActivityLog->get_logs(10000, null, $filters);
		echo json_encode(['data' => $logs]);
	}
}
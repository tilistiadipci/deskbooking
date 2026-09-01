<?php  

date_default_timezone_set("Asia/Jakarta");
class Notification extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if($this->session->userdata('levelid-nya') == 1){
				// redirect('authentication');
		}else if($this->session->userdata('levelid-nya') == 2){
				// 
		}else{
				redirect('authentication');

		}
	}
	public function get_notify(){
		if ($this->session->userdata('levelid-nya') == 1) {
			$notif = $this->Model_Notif->get_notification();
			echo json_encode($notif);
		}else{
			echo json_encode(array());
		}
		
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class Guestbook extends CI_Controller {

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

		$this->load->model('Model_Api2');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
		
	}
	public function index()
	{
		
		
	}
	public function Scan()
	{
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);
		// if(isset($post['id']) && isset($post['pinroom']){

		// }
		if(isset($post['id']) && isset($post['pinroom']) ){
			$data = $this->Model_Api2->getScanData($post['id'] ,$post['pinroom']);
			$dataPic = $this->Model_Api2->getBookingPic($post['id']);
			$dataUserPic = [];
			if(isset($dataPic['nik'])){
				$resPIC = $this->Model_Api2->getEmployeeByNIk($dataPic['nik']);
				if(count($resPIC) > 0){
					$dataUserPic = $resPIC[0];
				}
			}
			if($data != null){
				$res= $data;
				if(count($res) > 0){
					$row = $res[0];
					if($row['internal'] == 1 ){
						$nik = $row['nik'];
						$dataNik = $this->Model_Api2->getEmployeeByNIk($nik);
						$resNik= $dataNik;
						if(count($resNik) > 0){
							$row1 = $resNik[0];
							$room_name = $row['building_name'] . " - " .$row['room_name'] ;
							$r = array(
								'name' => $row1['name'],
								'room' => $room_name,
								'company' => $row1['company'],
								'position' => "",
								'pin_room' => $row['pin_room'],
								'email' => $row1['email'],
								'booking_id' => $row['booking_id'],
								'pic' =>$dataUserPic,
							);
							echo response("success", $r, "Get success");
							die();
						}else{
							echo response("fail", array(), "Data not exist");
							die();
						}
					}else{
						$room_name = $row['building_name'] . " - " .$row['room_name'] ;
						$r = array(
							'name' => $row['name'],
							'room' => $room_name,
							'company' => $row['company'],
							'position' => $row['position'],
							'pin_room' => $row['pin_room'],
							'email' => $row['email'],
							'booking_id' => $row['booking_id'],
							'pic' =>$dataUserPic,

						);
						echo response("success", $r, "Get success");
					}
					die();
				}else{
					echo response("fail", array(), "Data not exist");
					die();
				}
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
			die();
		}else{
			$response = response("fail", array(), "Failed restrict access");
			echo $response ;
			die();
		}		
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		if(isset($post['username']) && $post['username'] != ""){
			$data = $this->Model_Api->getDataDisplay($post );
			if($data['error'] == null){
				echo response("success", $data['data'], "Get success");
			}else{
				echo response("fail", $data, "Get failed");
			}
			die();
		}else{
			$response = response("fail", array(), "Failed restrict access");
			echo $response ;
			die();
		}		
	}
	
	
}

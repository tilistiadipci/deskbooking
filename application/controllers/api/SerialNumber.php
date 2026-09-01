<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set(APP_GMT);
class SerialNumber extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Api');
		$this->load->model('Model_Api2');
		$this->load->helper('response');
		$this->output->set_content_type('application/json');
	}

	public function generateDeviceSerialDisplayMeetingAndroid(){
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$brand = $post['brand'];
		$model = $post['model'];
		$hardware_id = $post['id'];
		$device_data = $this->Model_Api2->checkHardwareDevice($hardware_id);
		if(isset($device_data['hardware_id']) ){
			$data = [
				'version' => $device_data['version'],
				'os' => $device_data['os'],
				'mac' => $device_data['mac'],
				'type' => $device_data['type'],
				'serial' =>$device_data['serial'],
				'uuid' => $device_data['uuid'],
				'id' => $device_data['id'],
				'info' => $device_data['info'],
				'hardware_id' => $device_data['hardware_id'],
				'available' => true,
			];
			echo response("success", $data, "Get success");
			die();
		}
		$generator = $this->generateDeviceSerialDisplayMeeting();
		$id = "ADSMR".uniqidReal(6); //ANDROID DISPLAY SMR
		$info = $brand ."_".$model;
		$type = "display_smr";
		$mac = "";
		$os = "android";
		$version = "";
		$data = [
			'version' => $version,
			'os' => $os,
			'mac' => $mac,
			'type' => $type,
			'serial' => $generator['serial'],
			'uuid' => $generator['uuid'],
			'id' => $id,
			'info' => $info,
			'hardware_id' => $hardware_id,
		];
		$dt = $data;
		$dt ['is_deleted'] = 0;
		$dt ['is_actived'] = 0;
		$this->Model_Api->insertData('device_player_integration',$dt);
		echo response("success", $data, "Get success");
		
	}
	public function generateDeviceSerialDisplayMeetingMac(){
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$brand = $post['brand'];
		$model = $post['model'];
		$hardware_id = $post['id'];
		$device_data = $this->Model_Api2->checkHardwareDevice($hardware_id);
		if(isset($device_data['hardware_id']) ){
			$data = [
				'version' => $device_data['version'],
				'os' => $device_data['os'],
				'mac' => $device_data['mac'],
				'type' => $device_data['type'],
				'serial' =>$device_data['serial'],
				'uuid' => $device_data['uuid'],
				'id' => $device_data['id'],
				'info' => $device_data['info'],
				'hardware_id' => $device_data['hardware_id'],
				'available' => true,
			];
			echo response("success", $data, "Get success");
			die();
		}
		$generator = $this->generateDeviceSerialDisplayMeeting();
		$id = "MDSMR".uniqidReal(6); //MACBOOK DISPLAY SMR
		$info = $brand ."_".$model;
		$type = "display_smr";
		$mac = "";
		$os = "mac";
		$version = "";
		$data = [
			'version' => $version,
			'os' => $os,
			'mac' => $mac,
			'type' => $type,
			'serial' => $generator['serial'],
			'uuid' => $generator['uuid'],
			'id' => $id,
			'info' => $info,
			'hardware_id' => $hardware_id,
		];
		$dt = $data;
		$dt ['is_deleted'] = 0;
		$dt ['is_actived'] = 0;
		$this->Model_Api->insertData('device_player_integration',$dt);
		echo response("success", $data, "Get success");
		
	}
	public function generateDeviceSerialDisplayMeetingWindows(){
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$brand = $post['brand'];
		$model = $post['model'];
		$hardware_id = $post['id'];
		$device_data = $this->Model_Api2->checkHardwareDevice($hardware_id);
		if(isset($device_data['hardware_id']) ){
			$data = [
				'version' => $device_data['version'],
				'os' => $device_data['os'],
				'mac' => $device_data['mac'],
				'type' => $device_data['type'],
				'serial' =>$device_data['serial'],
				'uuid' => $device_data['uuid'],
				'id' => $device_data['id'],
				'info' => $device_data['info'],
				'hardware_id' => $device_data['hardware_id'],
				'available' => true,
			];
			echo response("success", $data, "Get success");
			die();
		}
		$generator = $this->generateDeviceSerialDisplayMeeting();
		$id = "WDSMR".uniqidReal(6); //WINDOWS DISPLAY SMR
		$info = $brand ."_".$model;
		$type = "display_smr";
		$mac = "";
		$os = "windows";
		$version = "";
		$data = [
			'version' => $version,
			'os' => $os,
			'mac' => $mac,
			'type' => $type,
			'serial' => $generator['serial'],
			'uuid' => $generator['uuid'],
			'id' => $id,
			'info' => $info,
			'hardware_id' => $hardware_id,
		];
		$dt = $data;
		$dt ['is_deleted'] = 0;
		$dt ['is_actived'] = 0;
		$this->Model_Api->insertData('device_player_integration',$dt);
		echo response("success", $data, "Get success");
		
	}
	private function generateDeviceSerialDisplayMeeting(){
		$json = file_get_contents("php://input");
		$post = json_decode($json, TRUE);

		$getserial = uniqidReal(6);
		$getuuid = gen_uuid(6);
		return [
			'serial'=> $getserial,
			'uuid'=> $getuuid,
		];
	}
}
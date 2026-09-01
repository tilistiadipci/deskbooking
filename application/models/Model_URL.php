<?php  
date_default_timezone_set("Asia/Jakarta");
class Model_URL extends CI_Model {
	public function __construct(){
		parent::__construct();
		// $this->load->library('dtabase');

	}
	public function URLFRFalcoApi(){
		$arIp = array("http://192.168.0.111:8090/","http://192.168.0.112:8090/","http://192.168.0.113:8090/","http://192.168.0.114:8090/","http://192.168.0.115:8090/","http://192.168.0.115:8090/","http://192.168.0.116:8090/","http://192.168.0.117:8090/","http://192.168.0.118:8090/");
		return $arIp ;
	}
	public function URLApiFalco(){
		// return "http://192.168.0.51/vaultSite/APIwebservice.asmx" ;
		return FALCO_ACCESS_URL;
	}		

	public function URLApiGBPeople(){
		return "http://192.168.0.20:8080/people?" ;
	}
	public function URLApiGuestBookFR(){
		return "http://192.168.0.175/biofr_guestbook/guest/save/api" ;
	}

	

}
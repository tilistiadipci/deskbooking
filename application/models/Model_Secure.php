<?php  
date_default_timezone_set("Asia/Jakarta");
class Model_Secure extends CI_Model {
	public function __construct(){
		parent::__construct();
		$this->load->helper('response');
	}
	public function encCompress($ciphertext){
		$data = substr($ciphertext, 0, 10);
		return $data;
	}
	public function encryptBio($rawtext, $compress = true){

		$initializeEnc =  array(
		                'cipher' => 'aes-256',
		                'mode' => 'ctr',
		                'key' => ENCRYPKEY,
		        );
		$this->encryption->initialize($initializeEnc);
		$ciphertext = $this->encryption->encrypt($rawtext);
		return $ciphertext;
	}
	public function decryptBio($ciphertext){
		$initializeEnc =  array(
		                'cipher' => 'aes-256',
		                'mode' => 'ctr',
		                'key' => ENCRYPKEY,
		        );
		$this->encryption->initialize($initializeEnc);
		$result = $this->encryption->decrypt($ciphertext);
		return $ciphertext;
		
	}
}
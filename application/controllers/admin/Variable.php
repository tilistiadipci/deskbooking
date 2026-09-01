<?php  

date_default_timezone_set(APP_GMT);
class Variable extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Admin');
		$this->load->helper('response');
		$this->load->helper('string');
	}
	public function setting()
	{
		$data = $this->Model_Admin->getVariableSetting();
		if($data['error'] == null){
			echo response("success", $data['data'], "Get success");
		}else{
			echo response("fail", $data, "Get failed");
		}
	}

}
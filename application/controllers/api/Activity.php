<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('activity');
    }

    public function log() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (isset($data['code'])) {
            $code = $data['code'];
            unset($data['code']);
            record_activity($code, $data);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Missing code']);
        }
    }
}

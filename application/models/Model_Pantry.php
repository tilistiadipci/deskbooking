<?php
date_default_timezone_set(APP_GMT);
class Model_Pantry extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Notif');
        $this->load->model('Model_Admin');
        $this->load->model('Model_Api');
        $this->load->model('Model_Api2');
    }
    public function getDataPantry($where){
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('*')
                ->from("pantry")
                ->where($ar)
                ->where($where)
                ->order_by('id', 'ASC')
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->result_array(),
            );
            return $sn;
        } catch (Exception $error) {
            $sn = array(
                "error" => $error,
                "data"  => $this->db->error(),
            );
            return $sn;
        }
    }
    function createPantryOrder($databook, $id = ""){
        $timezone = date_default_timezone_get();
        $modules['pantry'] = $this->Model_Module->get_module_pantry();

        $pantry_package             = empty($databook['pantry_package']) == false ? $databook['pantry_package'] : "" ;
        $pantry_detail              = empty($databook['pantry_detail']) == false ? $databook['pantry_detail'] : array() ;
        $set_pantry_config          = $this->Model_Admin->select_all_data('setting_pantry_config', array(), array(), 'row');
        $pantry_expired             = $set_pantry_config['pantry_expired']; 
        $pantry_max_order_qty       = $set_pantry_config['max_order_qty']; 
        $pantry_before_order_meeting= $set_pantry_config['before_order_meeting']; 
        $set_pantry                 = array();
        $collected_pantry_detail    = array();
        $error_pantry = false;
        
        if($set_pantry_config['status'] == 1 && $modules['pantry']['is_enabled'] == 1 ){
            foreach ($pantry_detail as $key => $value) {
                if($pantry_max_order_qty < $value['qty']){
                    $error_pantry = true;
                    break;
                }
            }
            if($pantry_package != ""){
                $datetimeStr = $databook['date'] ." ". $databook['startStr'];
                $timeVariable = new DateTime($datetimeStr);
                $tglMeeting = $timeVariable->format('Y-m-d');;

                $tanggaltime_order_pantry = $timeVariable->format('Y-m-d H:i:sP');
                $b_time = "-".$pantry_before_order_meeting;

                $tanggaltime_order_pantry_before = date('Y-m-d H:i:sP', strtotime($b_time .'minutes', strtotime($tanggaltime_order_pantry)));
                $tanggal_order_pantry = $databook['date'] ;
                $data_pantry = $this->Model_Admin->getDataPantryPackage($pantry_package)['data'][0];
                $pantry_trs_status = $this->Model_Admin->select_all_data('pantry_transaksi_status', array('id'=>0), array(), 'row');
                $sql_pantry = "SELECT COALESCE(max(order_no), '') as order_no from pantry_transaksi
                            WHERE DATE(order_datetime) = '".$tanggal_order_pantry."'   AND pantry_id=".$data_pantry['pantry_id'] ." "  ;
                $idtrspantry = "METTING-".date('YmdHis').random_string('numeric', 3);
                $row_order_pantry   = $this->Model_Admin->querySql($sql_pantry)->row_array();
                if($row_order_pantry['order_no'] == "" || $row_order_pantry['order_no'] == null){
                    $no_order_pantry = sprintf("%04d", "1");
                }else{
                    $old_no_order_pantry = $row_order_pantry['order_no']-0;
                    $no_sort_order_pantry = $old_no_order_pantry + 1;
                    $no_order_pantry = sprintf("%04d", $no_sort_order_pantry);

                }
                $p_datetime = date('Y-m-d H:i:sP');
                $set_pantry = array(
                    'id' => $idtrspantry,
                    'pantry_id' => $data_pantry['pantry_id'],
                    'order_no' => $no_order_pantry,
                    'employee_id' => $nikPic,
                    'booking_id' => $id,// booking ID
                    'via' => "booking",
                    'datetime' => $p_datetime ,
                    'order_datetime' => $p_datetime ,
                    // 'order_datetime' => $tanggaltime_order_pantry ,
                    'order_datetime_before' => $tanggaltime_order_pantry_before ,
                    'order_st' => 0,
                    'order_st_name' => $pantry_trs_status['name'],
                    'process' => 0 ,
                    'complete' => 0 ,
                    'failed' => 0 ,
                    'done' => 0 ,
                    'note' =>'',
                    'created_at' => $p_datetime,
                    'is_deleted' => 0,
                    'timezone' => $timezone,
                );
                foreach ($pantry_detail as $key => $value) {
                    $d_trs_pantry = array(
                        'transaksi_id' => $idtrspantry,
                        'menu_id' => $value['id'],
                        'qty' => $value['qty']-0,
                        'note_order' => $value['note'],
                        'note_reject' => "",
                        'is_rejected' => 0,
                        'is_deleted' => 0,
                        'status' => $value['status'],
                    );
                    array_push($collected_pantry_detail , $d_trs_pantry);
                }
                // print_r($set_pantry);
            }
            if ($error_pantry) {
                $response = response("fail", array(), "Orders per item exceed. Maximum quantity of ".$pantry_max_order_qty);
                echo $response;
                die();
            }

            // MODULE PANTRY
            if($set_pantry_config['status'] == 1){
                if($pantry_package != ""){
                    if(count($pantry_detail) > 0){
                        $resp2      = $this->Model_Admin->insertData('pantry_transaksi', $set_pantry);
                        $resp2      = $this->Model_Admin->insertDataBatch('pantry_transaksi_d', $collected_pantry_detail);
                    }
                }
            }

        }

    }
}
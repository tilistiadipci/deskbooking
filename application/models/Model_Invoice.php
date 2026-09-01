<?php
date_default_timezone_set("Asia/Jakarta");
class Model_Invoice extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Notif');
        $this->load->model('Model_Admin');
        $this->load->model('Model_Api');
        $this->load->model('Model_Api2');

    }
    function calculateDuration($databook){
        $time1                      = new DateTime($databook['date'].' '. $databook['startStr']);
        $time2                      = new DateTime($databook['date'].' '. $databook['endStr']);
        
        $timediff                   =    $time2->diff($time1);
        $duration_hours             = $timediff->h*60;
        $duration_minute            = $timediff->i;
        $duration                   = $duration_hours+$duration_minute;
        return  $duration ;
    }
    
    function calculateReservationCost($databook, $room = []){
        $settingGeneral             = $this->Model_Api->getSettingDataGeneral();
        $dataSettingGeneral         = $settingGeneral['data'];
        $reservation_cost           = 0;
        $modules['price'] = $this->Model_Module->get_module_price();
        $modules['invoice'] = $this->Model_Module->get_module_invoice();
        $fHour                      = $dataSettingGeneral['duration'];
        $duration                   = $this->calculateDuration($databook);
        $getHoursMeeting            = floor($duration / $fHour);
        $checkHours                 = fmod($duration,$fHour);
        if($checkHours > 0){
            $getHoursMeeting += 1;
        }
        if(($modules['price']['is_enabled']-0) == 1){
            $cost                       = $room['price'] - 0;  // per hours
            $getHoursMeeting            = floor($duration / $fHour);
            $checkHours                 = fmod($duration,$fHour);
            if($checkHours > 0){
                $getHoursMeeting += 1;
            }
            $reservation_cost           = $cost * $getHoursMeeting;
        }
        return  $reservation_cost;
    }
    function createInvoiceOrder($databook, $alocation = [], $room = []){
        $formatInvoice              = "";
        $datetime                   = date('Y-m-d H:i:s');
        $modules['price'] = $this->Model_Module->get_module_price();
        $modules['invoice'] = $this->Model_Module->get_module_invoice();
        $duration                   = $this->calculateDuration($databook);
        $reservation_cost           = $this->calculateReservationCost($databook,$room);
        if (($modules['invoice']['is_enabled']-0) == 1 && $modules['price']['is_enabled'] == 1 && $data['is_alive'] == 1 ) {
            $years          = date('Y', strtotime($databook['date'])); // get tahun from date
            $y_years        = date('y', strtotime($databook['date'])); // get tahun from date
            $months         = date('m', strtotime($databook['date'])); // get tahun from date
            $days           = date('d', strtotime($databook['date'])); // get tahun from date
            $sql_invoice    = "SELECT COALESCE(max(no_order), '') as no_order from booking
                                WHERE YEAR(date) = '".$years."'";
            $resInvoice     = $this->Model_Api->querySql($sql_invoice);
            $rowInvoice     = $resInvoice->row_array();
            $invAlocationID = $alocation ['alocation_id'] . "-E-Meeting";
            if($rowInvoice['no_order'] == "" || $rowInvoice['no_order'] == null ){
                $newNoUrut          = sprintf("%03d", "1");
                $formatInvoice      = $newNoUrut . "/" . $invAlocationID . "/" . $months ."/" .$y_years;
                $formatInvoice2     = $newNoUrut . "/" . $invAlocationID . "/" . $months ."/" .$y_years;
            }else{
                $oldNoInv           = $rowInvoice['no_order'];
                $spOldInv           = explode("/", $oldNoInv);
                $noUrut             = ($spOldInv[0]-0) + 1;
                $newNoUrut          = sprintf("%03d", $noUrut); // returns 001
                $formatInvoice      = $newNoUrut . "/" . $invAlocationID . "/" . $months ."/" .$y_years;
                $formatInvoice2     = $newNoUrut . "/" . $invAlocationID . "/" . $months ."/" .$y_years;
            }
        }
        // MODULE PRICE & INVOICE
        if (($modules['invoice']['is_enabled']-0) == 1 && $modules['price']['is_enabled'] == 1 && $databook['is_alive'] == 1 ) {
            $data_invoice = array(
                "invoice_no"    => $invoice_id,
                "invoice_format"=> $formatInvoice2,
                "booking_id"    => $id, // bookingid
                "rent_cost"     => $reservation_cost,
                "alocation"     => $alocation ['alocation_id'],
                "time_before"   => $datetime,
                "created_at"    => $datetime,
                "created_by"    => $nikPic,
                "invoice_status"    => 0, // before send
            );
            $resp3 = $this->Model_Admin->insertData('booking_invoice', $data_invoice);
        }
        return $formatInvoice;

    }

    function calculateDurationExtend($databook){
        $time1                      = new DateTime($databook['start']);
        $time2                      = new DateTime($databook['end']);
        $extend                     = $databook['extended_duration'] - 0;
        $valueextend =  $databook['extended_value']  == null ? 0 :  $databook['extended_value'] -0;
        
        $timediff                   =  $time2->diff($time1);
        $duration_hours             = $timediff->h*60;
        $duration_minute            = $timediff->i;
        $duration                   = $duration_hours+$duration_minute + $extend  +$valueextend   ;
        return  $duration ;
    }


    function calculateReservationCostExtend($databook){
        $settingGeneral             = $this->Model_Api->getSettingDataGeneral();
        $dataSettingGeneral         = $settingGeneral['data'];
        $reservation_cost           = 0;
        $modules['price'] = $this->Model_Module->get_module_price();
        $modules['invoice'] = $this->Model_Module->get_module_invoice();
        $fHour                      = $dataSettingGeneral['duration'];
        $duration                   = $this->calculateDurationExtend($databook);
        $getHoursMeeting            = floor($duration / $fHour);
        $checkHours                 = fmod($duration,$fHour);
        if($checkHours > 0){
            $getHoursMeeting += 1;
        }
        if(($modules['price']['is_enabled']-0) == 1){
            $cost                       = $room['price'] - 0;  // per hours
            $getHoursMeeting            = floor($duration / $fHour);
            $checkHours                 = fmod($duration,$fHour);
            if($checkHours > 0){
                $getHoursMeeting += 1;
            }
            $reservation_cost           = $cost * $getHoursMeeting;
        }
        return  $reservation_cost;
    }
}
<?php
date_default_timezone_set("Asia/Jakarta");
class Model_Booking extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Notif');
        $this->load->model('Model_Admin');
        $this->load->model('Model_Api');
        $this->load->model('Model_Api2');
        // $this->load->library('database');
    }
    public function makeQueryGetTimeData($timearray = [], $date = "", $roomid = "", $lenTime = 0){
        // pengguna model ini 
        // contorllrt booking -> checkTodayBooking;
        // contorllrt booking -> checkPickerBooking;
        // contorllrt placeroom -> getTimeBookByRoom;

        $room_id = $roomid;
        $sql    = "";
        $sql    .= "SELECT * FROM ( ";
        foreach ($timearray as $key => $value) {
                $timeData = $date." ".$value['time'] .":00";

                if ($lenTime == $key) {
                    $sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
                        COALESCE(b.room_id, '".$room_id."') room_id, 
                        COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
                        FROM booking b 
                        LEFT JOIN room r ON b.room_id=r.radid  
                        WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 
                        AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))";
                }else{
                    $sql .= "SELECT COUNT(*) as book, TIME('".$timeData."') time_array, 
                        COALESCE(b.room_id, '".$room_id."') room_id, 
                        COALESCE(SUM(b.is_canceled),0) canceled, COALESCE(SUM(b.is_expired),0) expired, COALESCE(SUM( b.end_early_meeting),0) endearly
                        FROM booking b 
                        LEFT JOIN room r ON b.room_id=r.radid  
                        WHERE b.date='".$date."' AND b.room_id='".$room_id."'  AND b.is_alive = 1 
                        AND TIME('".$timeData."') BETWEEN TIME(b.start) AND TIME(DATE_ADD(b.end, INTERVAL b.extended_duration-1 MINUTE))";
                    $sql .= " UNION ";
                }
        }
        $sql            .= ") room_time";
        return $sql;

    }
    public function getBookingScheduleByRoom($filter = []){

        $ar = array("b.is_deleted" => 0,"bi.is_pic" => 1,);
        $wrdate = "";
        $room_cari = @$filter['room_cari']; // radid
        $date_start_cari = @$filter['date_start_cari']; // radid
        $date_end_cari = @$filter['date_end_cari']; // radid
        $booking_cari = @$filter['booking_cari']; // radid
        if($room_cari != ""){
            $ar ['b.room_id'] = $room_cari;
        }


        
        if($date_start_cari != ""){
            $wrdate .= " b.date>=DATE('". $date_start_cari."') ";
            $wrdate .= "AND b.date<=DATE('". $date_end_cari."') ";
        }
        if($booking_cari != ""){
            $ar ['b.booking_id'] = $booking_cari;
        }
        if($wrdate == ""){
            $wrdate= [];
        }

        $qselect = 'b.*, r.name as room_name,  price, 
                    bi.email pic_email, bi.name pic_name,bi.nik pic_nik, bi.is_vip pic_vip ,
                    r.description as room_description, 
                    r.location as room_location, 
                    r.capacity as room_capacity, 
                    r.google_map as room_google_map, 
                    bui.name as building_name, 
                    bui.detail_address as building_detail_address, 
                    bui.google_map as building_google_map
                    ';
        $data = $this->db->select( $qselect)
                    ->from("booking b")
                    ->join("room r", "b.room_id=r.radid", 'left')
                    ->join("booking_invitation bi", "bi.booking_id=b.booking_id", 'left')
                    ->join("building bui", "r.building_id=bui.id" , 'left')
                    ->where($ar)
                    ->where($wrdate)
                    ->get();
        return $data->result_array();
    }

    public function MobileFormatBooking($databook){
        $data                       = array();  // DATA BOOKING
        $data['booking_id']         = "";
        $data['no_order']           = "";
        $data['title']              = $databook['title'];
        $data['room_id']            = "";
        $data['date']               = $databook['date'];
        $data['start']              = $databook['date'] ." ". $databook['startStr'];
        $data['end']                = $databook['date'] ." ". $databook['endStr'];
        $data['total_duration']     = 0;
        $data['duration_per_meeting'] = 0 ;
        $data['cost_total_booking'] = 0 ;
        $data['alocation_id']       = "";
        $data['alocation_name']     = "";
        $data['pic']                = "";
        $data['is_alive']           = 1;
        $data['is_meal']            = 0;
        $data['is_deleted']         = 0;
        $data['is_rescheduled']     = 0;
        $data['is_canceled']        = 0;
        $data['is_expired']         = 0;
        $data['is_device']          = 1; // mobile created
        $data['created_at']         = "";
        $data['created_by']         = ""; // mobile created
        $data['external_link']      = isset($databook['link']) ? $databook['link']: "";
        $data['note']               = isset($databook['note']) ? $databook['note'] : "";
        $data['room_name']          = "";
        $data['is_merge']           = 0;
        $data['merge_room_name']    = "";
        $data['merge_room_id']      = "";
        $data['merge_room']         = "";
        $data['is_vip']             = isset($databook['is_vip']) ? ($databook['is_vip']-0) : 0;
        // $data['is_vip']             = 1;
        $data['vip_user']           = isset($databook['vip_user']) ? $databook['vip_user']  : "";
        $data['is_approve']         = 0;  
        $data['user_approval']      = "";
        $data['category']           = isset($databook['category']) ? $databook['category']  : "";
        $data['timezone']           = isset($databook['timezone']) ? $databook['timezone']  : "";
        return  $data;
    }
    public function createInternalBatch($data = [], $dataEmailInternal = [], $nikPic = null, $createQr = false ){
        // include APPPATH.'third_party/phpqrcode/qrlib.php';
        $tmpdir = ASSETS_QR;
        $datetime                   = date('Y-m-d H:i:s');
        $internalBatch = [];
        $nn0 =0;
        $id = $data['booking_id'];
        foreach ($dataEmailInternal as $val) {
            $num_str                        = random_string('numeric', 6);
            $ibatch                         = array();
            $ibatch['booking_id']           = $data['booking_id'];
            $ibatch['nik']                  = $val['nik']; // employee id
            $ibatch['name']                 = $val['name'];
            $ibatch['internal']             = 1;
            $ibatch['attendance_status']    = 0;
            $ibatch['email']                = $val['email'];
            $ibatch['is_pic']               = 0;
            $ibatch['company']              = "";
            $ibatch['pin_room']             = $num_str;
            $ibatch['created_at']           = $datetime;
            $ibatch['created_by']           = $nikPic;
            $ibatch['is_deleted']           = 0;
            $qrnvitation = $id."_".$num_str;
            if($createQr == true){
                QRcode::png($qrnvitation,$tmpdir.$qrnvitation.".png",QR_ECLEVEL_H,10,3);
            }
            array_push($internalBatch, $ibatch);
            $dataEmailInternal[$nn0]['pin_room'] = $num_str;
            $dataEmailInternal[$nn0]['is_pic'] = 0;
            $nn0 ++;
        }
        return array('internalBatch' => $internalBatch, 'dataEmailInternal' => $dataEmailInternal );

    }
    public function createExternalBatch($data = [], $externallist = [],$nikPic = null, $createQr = false ){
        // include APPPATH.'third_party/phpqrcode/qrlib.php';
        $tmpdir = ASSETS_QR;
        $datetime                   = date('Y-m-d H:i:s');
        $eksternalBatch = [];
        $dataEmailEksternal = [];
        $nn0 =0;
        $id = $data['booking_id'];
        
        foreach ($externallist as $val) {
            $num_str                        = random_string('numeric', 6);
            $ibatch = array();
            $ibatch['booking_id']           = $data['booking_id'];
            $ibatch['email']                = $val['email'];
            $ibatch['company']              = $val['company'];
            $ibatch['name']                 = $val['name'];
            $ibatch['is_pic']               = 0;
            $ibatch['pin_room']             = $num_str  ;
            array_push($dataEmailEksternal, $ibatch);
            $ibatch['internal']             = 0;
            $ibatch['attendance_status']    = 0;
            $ibatch['created_at']           = $datetime;
            $ibatch['created_by']           = $nikPic;
            $ibatch['is_deleted']           = 0;
            array_push($eksternalBatch, $ibatch);
            $qrnvitation = $id."_".$num_str;
            if($createQr == true){
                QRcode::png($qrnvitation,$tmpdir.$qrnvitation.".png",QR_ECLEVEL_H,10,3);
            }
        }
        
        return array('eksternalBatch' => $eksternalBatch, 'dataEmailEksternal' => $dataEmailEksternal );

    }

    public function createInvitationPic($data = [], $nikPic = null, $getDataPIC = null){
        $tmpdir = ASSETS_QR;
        $datetime                   = date('Y-m-d H:i:s');
        $invitation_pic = [];
        $invitation_pic['booking_id']           = $data['booking_id'];
        $invitation_pic['nik']                  = $getDataPIC['nik']; // employee id
        $invitation_pic['name']                 = $getDataPIC['name'];
        $invitation_pic['internal']             = 1;
        $invitation_pic['attendance_status']    = 0;
        $invitation_pic['email']                = $getDataPIC['email']; 
        $invitation_pic['is_pic']               = 1;
        $invitation_pic['company']              = "";
        $invitation_pic['pin_room']             = random_string('numeric', 6);
        $invitation_pic['created_at']           = $datetime;
        $invitation_pic['created_by']           = $nikPic;
        $invitation_pic['is_deleted']           = 0;
        return  $invitation_pic;
    }

    public function postSendNotificationMovedMeeting($booking_id)
    {
        $datetime                   = date('Y-m-d H:i:s');
        $modules['pantry']  = $this->Model_Module->get_module_pantry();
        $modules['loker']   = $this->Model_Module->get_module_loker();
        $modules['price']   = $this->Model_Module->get_module_price();
        $modules['invoice'] = $this->Model_Module->get_module_invoice();
        $modules['email']   = $this->Model_Module->get_module_email();
        $post               = [
            'booking_id' => $booking_id,
        ];

        $getBooking         = $this->Model_Api->getDataBookingById($post['booking_id']);
        $getPic             = $this->Model_Admin->getPICByBookingId($post['booking_id']);
        $dataPic            = $getPic['data'];
        $getBookingInv      = $this->Model_Admin->getDataBookingInvById($post['booking_id'])['data'];
        $sqlEmail           = "SELECT * FROM sending_email WHERE booking_id='" . $post['booking_id'] . "' ";
        $settingGeneral     = $this->Model_Admin->getSettingDataGeneral();
        $dataSettingGeneral = $settingGeneral['data'];
        $dataInvitation     = $getBookingInv;
        $datetime           = date("Y-m-d H:i:s");
        $dataBooking        = $getBooking['data'];
        $isMerge            = $dataBooking['is_merge'];
        $notifcollectdata   = array();
        $ddd                = $dataBooking['date'];
        $room_id            = $dataBooking['room_id'];
        $tableEmail         = $this->Model_Admin->querySql($sqlEmail)->result_array();

        $room_name = $dataBooking['room_name'];

        $meeting_date  = $dataBooking['date'];
        $explodeS      = explode(" ", $dataBooking['start']);
        $explodeE      = explode(" ", $dataBooking['end']);
        $meeting_start = $explodeS[1];
        $meeting_end   = $explodeE[1];
        foreach ($dataInvitation as $val) {
            if ($val['internal'] == 1 && $val['is_pic']) {
                // only internal
                $_notif               = array();
                $_notif['datetime']   = $datetime;
                $_notif['nik']        = $val['nik']; // user id
                $_notif['type']       = 1; // booking is 1
                $_notif['value']      = $post['booking_id']; // booking id
                $_notif['title']      = "Meeting Cancelled";
                $_notif['body']       = $dataBooking['title'] . " - " . getformatDate($ddd);
                $_notif['is_sending'] = 0;
                $_notif['is_deleted'] = 0;
                $_notif['created_at'] = $datetime;
                if ($val['is_pic'] == 1) {
                    $_notif['title'] = "Cancel a Meeting";
                    $_notif['body']  = $dataBooking['title'] . " - " . getformatDate($ddd);
                }
                array_push($notifcollectdata, $_notif);
            }
        }
        $dataEmailInternal  = [];
        $dataEmailEksternal = [];
        if (count($tableEmail) > 0) {
            // $tableEmail        = $tableEmail[0];
            $batchemail         = $tableEmail[0]['batch'];
            $dataToSend         = json_decode($batchemail, true);
            $dataEmailInternal  = $dataToSend['internal'];
            $dataEmailEksternal = $dataToSend['eksternal'];
        }
        // print_r($dataEmailInternal);
        if (count($notifcollectdata) > 0) {
            $this->Model_Notif->insertNotifBatch($notifcollectdata);
            $meeting_title = $dataBooking['title'];

            $notification_title = "Meeting Cancelled, " . $meeting_title;
            // $room_name          = $room['name'];
            $notification_body  = $this->Model_Admin->formatDate($meeting_date) . " " . $this->Model_Admin->formatTime($meeting_start) . "-" . $this->Model_Admin->formatTime($meeting_end) . " at " . $room_name;
            $pNotif             = $this->Model_Notif->pushNotification($notification_title, $notification_body, $notifcollectdata);
        }
        // $tableNotif            = $this->Model_Admin->querySql($sqlNotif)->result_array();

        if (count($tableEmail) > 0) {
            // inisil booking
            $emailBooking                      = $dataBooking;
            // print_r( $emailBooking          );
            $emailBooking['format_time_start'] = $this->Model_Admin->formatTime($meeting_start);
            $emailBooking['format_time_end']   = $this->Model_Admin->formatTime($meeting_end);
            $emailBooking['format_date']       = $this->Model_Admin->formatDate($meeting_date);

            if ($modules['email']['is_enabled'] == 1) {
                foreach ($dataEmailInternal as $key => $people) {
                    $pNotif = $this->Model_Notif->sendEmailInternal("cancel", $emailBooking, $people, $dataPic);
                }
                foreach ($dataEmailEksternal as $key => $people) {
                    $pNotif = $this->Model_Notif->sendEmailExternal("cancel", $emailBooking, $people, $dataPic);
                }
                // $pNotif = $this->Model_Notif->sendEmailResponseApproval($emailBooking, $dataPic, 1);
            }
        }

    }

    public function createSendingBatchEmail($booking_id, $batch, $datetime){
        $sending_email      = array(
            "batch"         => $batch,
            "type"          => 1,
            "booking_id"    => $booking_id,
            "pending"       => 0,
            "is_status"     => 0, // direct email php
            // "is_status"  => 1,
            "error_sending" => 0,
            "success"       => 0,
            "created_at"    => $datetime,
            "updated_at"    => $datetime,
            "is_deleted"    => 0
        );
        return $sending_email ;
    }
    public function createSendingBatchNotif($booking_id, $batch, $datetime){
        $sending_notif      = array(
            "batch"         => $batch,
            "type"          => 1,
            "booking_id"    => $booking_id,
            "is_status"     => 1,
            "pending"       => 0,
            "error_sending" => 0,
            "success"       => 0,
            "created_at"    => $datetime,
            "updated_at"    => $datetime,
            "is_deleted"    => 0
        );
        return $sending_notif ;
    }

    public function createNotifikasiCollectData($booking_id, $dataEmailInternal,$data, $datetime){
        $notifcollectdata = [];
        foreach ($dataEmailInternal as $val) {
            $_notif                     = array();
            $_notif['datetime']         = $datetime;
            $_notif['nik']              = $val['nik']; // user id
            $_notif['type']             = 1; // booking is 1
            $_notif['value']            = $booking_id; // booking id
            $_notif['title']            = "Invitation Meeting";
            $_notif['body']             = $data['title'] ." - ". getformatDate($data['date']);
            $_notif['is_sending']       = 0;
            $_notif['is_deleted']       = 0;
            $_notif['created_at']       = $datetime;
            array_push($notifcollectdata, $_notif);
        }
        return $notifcollectdata;
    }

    public function bookingLockerForAttendees($booking_id, $dataEmailInternal,$data, $datetime){
        $dataLockerSystem           = [];
        foreach ($dataEmailInternal as $vlo) {
            array_push($dataLockerSystem, $vlo['card_number']);
        }
        $dataLocker = $this->Model_Admin->querySql('SELECT * FROM locker WHERE 1=1 AND is_deleted=0 AND auto_reserve=1')->row_array();
        if(isset($dataLocker['name'])){
            foreach($dataLockerSystem as $noCard){
                $urlLockerSystem = $dataLocker['ip_locker']; // // http://192.168.1.14/lokerr/
                $this->Model_Admin->uploadDataToLockerSystem($urlLockerSystem,$noCard );
            }
        }
    }



}

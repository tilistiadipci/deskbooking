<?php
class Model_Report extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Api');
        $this->load->model('Model_Api2');

    }
    public function getAttendess($where = "", $bookingId = [])
    {

    	if(count($bookingId) > 0){
    		$this->db->where_in('b.booking_id',$bookingId );	
    	}
    	if($where !=""){
    		$this->db->where($where);	
    	}
        $data = $this->db->select(' COUNT(*) as count')
            ->from("booking_invitation bii ")
            ->join("booking b", "bii.booking_id=b.booking_id" )
            ->join("room r", "b.room_id=r.radid")
            ->join("building bu", "r.building_id=bu.id")
            ->where(" 1=1 ");
            
            // ->order_by('_generate', 'ASC')
         $b = $data->get();
        return  $b->row_array()['count'];
    }
    public function getBooking($where = "", $bookingId = [])
    {

    	if(count($bookingId) > 0){
    		$this->db->where_in('b.booking_id',$bookingId );	
    	}
    	if($where !=""){
    		$this->db->where($where);	
    	}
        $data = $this->db->select(' COUNT(*) as count')
            ->from("booking b ")
            ->join("room r", "b.room_id=r.radid")
            ->join("building bu", "r.building_id=bu.id")
            ->where(" 1=1 ");
            // ->order_by('_generate', 'ASC')
         $b = $data->get();
        return  $b->row_array()['count'];
    }
    public function getTotalBooking($where = "", $bookingId = [])
    {

    	if(count($bookingId) > 0){
    		$this->db->where_in('b.booking_id',$bookingId );	
    	}
    	if($where !=""){
    		$this->db->where($where);	
    	}
        $data = $this->db->select(' SUM(b.total_duration + b.extended_duration) as sum')
            ->from("booking b ")
            ->join("room r", "b.room_id=r.radid")
            ->join("building bu", "r.building_id=bu.id")
            ->where(" 1=1 ");
            // ->order_by('_generate', 'ASC')
         $b = $data->get();
        return  $b->row_array()['sum'];
    }

    public function getBookingReportTotalDurationApps($where = "")
    {
        $listmonth = [1,2,3,4,5,6,7,8,9,10,11,12];
       
        $selectcount_booking = "SELECT ";
        foreach ($listmonth as $key => $month) {
            $selectcount_booking .= "(SELECT IFNULL(SUM(b.total_duration + b.extended_duration),0) FROM booking_invitation bii 
            INNER JOIN booking b  ON bii.booking_id=b.booking_id 
            INNER JOIN room r  ON b.room_id=r.radid 
            INNER JOIN building bu ON r.building_id=bu.id
            WHERE b.is_alive != 0 AND MONTH(b.date)=".$month." AND internal=1 ".$where."   )  as '".$month."',";
        }
        $query = substr_replace($selectcount_booking ,"", -1);
        $query .= " " ;
        $data = $this->db->query($query)->result_array();
        return  $data;
       
    }
    public function getBookingReportTotalDurationSavedApps($where = "")
    {
        $listmonth = [1,2,3,4,5,6,7,8,9,10,11,12];
       
        $selectcount_booking = "SELECT ";
        foreach ($listmonth as $key => $month) {
            $selectcount_booking .= "(SELECT IFNULL(SUM(b.duration_saved_release),0) FROM booking_invitation bii 
            INNER JOIN booking b  ON bii.booking_id=b.booking_id 
            INNER JOIN room r  ON b.room_id=r.radid 
            INNER JOIN building bu ON r.building_id=bu.id
            WHERE b.is_alive != 0 AND MONTH(b.date)=".$month." AND internal=1 ".$where."   )  as '".$month."',";
        }
        $query = substr_replace($selectcount_booking ,"", -1);
        $query .= " " ;
        $data = $this->db->query($query)->result_array();
        return  $data;
       
    }

    public function getBookingReportTotalMeetingApps($where = "" )
    {
        $listmonth = [1,2,3,4,5,6,7,8,9,10,11,12];
        $selectcount_booking = "SELECT ";
        foreach ($listmonth as $key => $month) {
            $selectcount_booking .= "(SELECT IFNULL(COUNT(*),0) FROM booking_invitation bii 
            INNER JOIN booking b  ON bii.booking_id=b.booking_id 
            INNER JOIN room r  ON b.room_id=r.radid 
            INNER JOIN building bu ON r.building_id=bu.id
            WHERE b.is_alive != 0 AND MONTH(b.date)=".$month." AND internal=1 ".$where."   )  as '".$month."',";
        }
        $query = substr_replace($selectcount_booking ,"", -1);
        $query .= " " ;
        $data = $this->db->query($query)->result_array();
        return  $data;
    }

}

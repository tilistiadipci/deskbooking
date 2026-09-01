<?php
date_default_timezone_set("Asia/Jakarta");
class Model_Admin extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertData($table, $data)
    {
        $dta = $this->db->insert($table, $data);
        return $dta;
    }
    public function insertDataBatch($table, $data)
    {
        $dta = $this->db->insert_batch($table, $data);
        return $dta;
    }
    public function updateData($table, $data, $where = array())
    {
        $this->db->where($where);
        $dta = $this->db->update($table, $data);
        return $dta;
    }
    public function deleteData($table, $where = array())
    {
        $this->db->where($where);
        $dta = $this->db->delete($table, $where);
        return $dta;
    }
    public function querySql($query)
    {
        $dta = $this->db->query($query);
        return $dta;
    }
    public function procedure($query, $data)
    {
        $dta = $this->db->query($query, $data);
        return $dta->result_array();
    }
    public function logActivity($action, $description = "")
    {
        $ip      = $this->input->ip_address();
        $cur_url = current_url();
        $time    = date("Y-m-d H:i:s");
        $lq      = $this->db->last_query();
        $user_id = $this->session->userdata('user-nya');
        $data    = array(
            'nik'                 => $user_id,
            'access_ip'           => $ip,
            'access_url'          => $cur_url,
            'access_time '        => $time,
            'access_action '      => $action,
            'access_description ' => $description,
            'access_query'        => $lq,
        );
        $dta = $this->db->insert('log_activity', $data);
        return $dta;
    }
    public function select_all_data($table, $where, $field = array(), $result = 'result')
    {
        if (count($field) > 0) {
            $f  = implode(",", $field);
            $db = $this->db->select($f)
                ->from($table)
                ->where($where)->get();
        } else {
            $db = $this->db->select('*')
                ->from($table)
                ->where($where)->get();
        }
        if ($result == "row") {
            return $db->row_array();
        } else {
            return $db->result_array();
        }
    }
    // ===========================================================
    // PROFILE
    // ===========================================================
    public function getProfile($nik)
    {
        try {
            $ar = array(
                // "is_deleted" => 0,
                "nik" => $nik,
            );
            $data = $this->db->select('*')
                ->from("employee")
                ->where($ar)
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function checkProfileUsername($username)
    {
        $where_email = array(
            'e.email'       => $username,
            'e.is_deleted'  => 0,
            'is_disactived' => 0,
        );
        $where_nik = array(
            'e.nik'           => $username,
            'e.is_deleted'    => 0,
            'u.is_disactived' => 0,
        );
        $where_card = array(
            'e.card_number'   => $username,
            'e.is_deleted'    => 0,
            'u.is_disactived' => 0,
        );
        $where_username = array(
            'username'        => $username,
            'e.is_deleted'    => 0,
            'u.is_disactived' => 0,
        );

        $qemail = $this->db->select(' e.*, level_id, u.username  ')
            ->from('user u')
            ->join('employee e', 'u.employee_id = e.nik', 'left')
            ->where($where_email)
            ->get();
        $qnik = $this->db->select(' e.* , level_id, u.username ')
            ->from('user u')
            ->join('employee e', 'u.employee_id = e.nik', 'left')
            ->where($where_nik)
            ->get();
        $qcard = $this->db->select(' e.* , level_id, u.username ')
            ->from('user u')
            ->join('employee e', 'u.employee_id = e.nik', 'left')
            ->where($where_card)
            ->get();
        $qusername = $this->db->select(' e.* , level_id, u.username ')
            ->from('user u')
            ->join('employee e', 'u.employee_id = e.nik', 'left')
            ->where($where_username)
            ->get();

        $ret             = array();
        $ret['email']    = $qemail;
        $ret['nik']      = $qnik;
        $ret['card']     = $qcard;
        $ret['username'] = $qusername;
        return $ret;
    }
    // ===========================================================
    // COMPANY
    // ===========================================================

    public function getVariableSetting()
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $variable_duration = $this->db->select('*')
                ->from("variable_time_duration")
                ->get();
            $variable_time_extend = $this->db->select('*')
                ->from("variable_time_extend")
                ->get();
            $sn = array(
                "error" => null,
                "data"  => array(
                    "duration"    => $variable_duration->result_array(),
                    "time_extend" => $variable_time_extend->result_array(),
                ),
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

    public function getDataCompany()
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('*')
                ->from("company")
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    // ===========================================================
    // DASHBOARD
    // ===========================================================
    // public function getDataCompany(){
    //     try{
    //         $ar = array(
    //             "is_deleted" => 0
    //         );
    //         $data = $this->db->select('*')
    //                 ->from("company")
    //                 ->get();
    //         $sn = array(
    //             "error" => null,
    //             "data" => $data->row_array()
    //         );
    //         return $sn;
    //     }catch(Exception $error){
    //         $sn = array(
    //             "error" => $error,
    //             "data" => $this->db->error()
    //         );
    //         return $sn;
    //     }
    // }
    // ===========================================================
    // AUTOMATION
    // ===========================================================
    public function getDataAutomation()
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('*')
                ->from("room_automation")
                ->where($ar)
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
    public function getEditAutomation($id)
    {
        try {
            $ar = array(
                "is_deleted" => 0,
                "id"         => $id,
            );
            $data = $this->db->select('*')
                ->from("room_automation")
                ->where($ar)
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    // ==============================================
    // BUILDING
    // ==============================================
    public function getDataBuilding($where = array())
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            // $count_room = "select count() from room rr where rr.is_deleted = 0 "
            $data = $this->db->select('*,
				(select count(rr.radid) from room rr where rr.is_deleted = 0 AND building.id=rr.building_id)  as count_room,
				(select count(ff.id) from beacon_floor ff where ff.is_deleted = 0 AND building.id=ff.building_id)  as count_floor,
				(select count(ddd.desk_id) from desk_room_table ddd INNER JOIN desk_room dr ON ddd.desk_room_id=dr.id where ddd.is_deleted = 0 AND building.id=dr.building_id)  as count_desk,
				 ')
                ->from("building")
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
    public function getDataFloor($where = array())
    {
        try {
            $ar = array(
                "f.is_deleted" => 0,
                "b.is_deleted" => 0,
            );
            $data = $this->db->select('f.*')
                ->from("building_floor f")
                ->join("building b", "f.building_id=b.id")
                ->where($ar)
                ->where($where)
                ->order_by('f.position', 'ASC')
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
    public function getDataFloorLastFloor($where = array())
    {
        try {
            $ar = array(
                "f.is_deleted" => 0,
                "b.is_deleted" => 0,
            );
            $data = $this->db->select('f.*')
                ->from("building_floor f")
                ->join("building b", "f.building_id=b.id")
                ->where($ar)
                ->where($where)
                ->order_by('f.position', 'DESC')
                ->limit(1)
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
    // ==============================================
    // ROOM
    // ==============================================
    public function getDataRoom($whereString = "")
    {
        try {
            $ar = array(
                "r.is_deleted"  => 0,
                "r.is_disabled" => 0,
            );
            if ($whereString == "") {
                $data = $this->db->select('r.*, ra.name as ra_name, ra.id as ra_id,
					b.name as building_name,
					b.detail_address as building_detail,
					b.google_map as building_google_map
					')
                    ->from("room r")
                    ->join("room_automation ra", "r.automation_id=ra.id", 'left')
                    ->join("building b", "r.building_id=b.id", 'left')
                    ->where($ar)

                    ->order_by("name", "ASC")
                    ->get();
            } else {
                $data = $this->db->select('r.*, ra.name as ra_name, ra.id as ra_id,
					b.name as building_name,
					b.detail_address as building_detail,
					b.google_map as building_google_map
					')
                    ->from("room r")
                    ->join("room_automation ra", "r.automation_id=ra.id", 'left')
                    ->join("building b", "r.building_id=b.id", 'left')
                    ->where($ar)
                    ->where($whereString)
                    ->order_by("name", "ASC")
                    ->get();
            }

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
    public function getDataRoom2()
    {
        try {
            $ar = array(
                "r.is_deleted" => 0,
            );
            $data = $this->db->select('r.*, ra.name as ra_name, ra.id as ra_id,
					b.name as building_name,
					b.detail_address as building_detail,
					b.google_map as building_google_map
					')
                ->from("room r")
                ->join("room_automation ra", "r.automation_id=ra.id", 'left')
                ->join("building b", "r.building_id=b.id", 'left')
                ->where($ar)

                ->order_by("name", "ASC")
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
    public function getDataSingleRoom()
    {
        try {
            $ar = array(
                "is_deleted" => 0,
                "type_room"  => 'single',
            );
            $data = $this->db->select('*')
                ->from("room ")
                ->where($ar)
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

    public function getDataMergeRoom($room_id)
    {
        try {
            $ar = array(
                "room_id" => $room_id,
            );
            $data = $this->db->select('*')
                ->from("room_merge_detail ")
                ->where($ar)
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
    public function getDataMergeRoomBooking($room_id)
    {
        try {
            $ar = array(
                "rm.room_id"   => $room_id,
                "r.is_deleted" => 0,
            );
            $data = $this->db->select('r.*, ra.name as ra_name, ra.id as ra_id,
					b.name as building_name,
					b.detail_address as building_detail,
					b.google_map as building_google_map
					')
                ->from("room_merge_detail rm")
                ->join("room r", "rm.merge_room_id=r.radid")
                ->join("room_automation ra", "r.automation_id=ra.id", 'left')
                ->join("building b", "r.building_id=b.id", 'left')
                ->where($ar)
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
    public function getEditRoom($id)
    {
        try {
            $ar = array(
                "is_deleted" => 0,
                "id"         => $id,
            );
            $data = $this->db->select('*')
                ->from("room")
                ->where($ar)
                ->order_by('id', 'ASC')
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getRoomRadid($id)
    {
        try {
            $ar = array(
                "is_deleted" => 0,
                "radid"      => $id,
            );
            $data = $this->db->select('*')
                ->from("room")
                ->where($ar)
                ->order_by('radid', 'ASC')
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
    public function removeRoomWhereIn($datain)
    {
        $data = array(
            "is_deleted" => 0,
        );
        $this->db->where_in('radid', $datain);
        $dta = $this->db->update("room", $data);
        return $dta;
    }
    // ==============================================
    // PANTRY
    // ==============================================
    public function getDataPantry()
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('*')
                ->from("pantry")
                ->where($ar)
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
    public function getDataPantryPackage($id = "")
    {
        try {
            $ar = array(
                "pm.is_deleted" => 0,
                "p.is_deleted"  => 0,
            );
            $w = array();
            if ($id != "") {
                $w['pm.id'] = $id;
            }

            $data = $this->db->select('pm.id, pm.name, pm.pantry_id, p.name pantry_name')
                ->from("pantry_menu_paket pm")
                ->join("pantry p", "pm.pantry_id=p.id")
                ->where($ar)
                ->where($w)
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
    public function getDataPantryPackageDetail($id = "")
    {
        try {
            $ar = array(
                "pm.is_deleted"  => 0,
                "pmd.is_deleted" => 0,
                "pd.is_deleted"  => 0,
            );
            $w          = array();
            $w['pm.id'] = $id;

            $data = $this->db->select('pmd._generate, pmd.menu_id id, pd.name')
                ->from("pantry_menu_paket pm")
                ->join("pantry_menu_paket_d pmd", "pm.id=pmd.package_id", 'left')
                ->join("pantry_detail pd", "pmd.menu_id=pd.id", 'left')
                ->where($ar)
                ->where($w)
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
    public function getEditPantry($id)
    {
        try {
            $ar = array(
                "is_deleted" => 0,
                "id"         => $id,
            );
            $data = $this->db->select('*')
                ->from("pantry")
                ->where($ar)
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    // ==============================================
    // FACILITY
    // ==============================================
    public function getDataFacility()
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('*')
                ->from("facility")
                ->where($ar)
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
    // ==============================================
    // ACCESS ROOM
    // ==============================================
    public function getDataAccess()
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('ac.*, (SELECT count(*) FROM access_integrated ai
				INNER JOIN room r ON ai.room_id=r.radid
				WHERE ai.access_id=ac.id AND ai.is_deleted=0 AND r.is_deleted=0 ) as room ')
                ->from("access_control ac")
                ->where($ar)
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
    public function getDataAccessEdit($id)
    {
        try {
            $ar = array(
                "ac.is_deleted" => 0,
                "ac.id"         => $id,
            );
            $data = $this->db->select('ac.*,acf.unit_no falco_unit_no, acf.group_access falco_group_access, (SELECT count(*) FROM access_integrated WHERE access_id=ac.id AND is_deleted=0) as room ')
                ->from("access_control ac")
                ->join("access_controller_falco acf", 'ac.id=acf.access_id', 'left')
                ->where($ar)
                ->order_by('id', 'ASC')
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function checkIntegrated($where)
    {
        try {

            $data = $this->db->select('*')
                ->from("access_integrated")
                ->where($where)
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
    public function getDataIntegrated($id)
    {
        try {
            $ar = array(
                "access_id"  => $id,
                "is_deleted" => 0,
            );
            $data = $this->db->select('*')
                ->from("access_integrated")
                ->where($ar)
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
    public function getDataChannel()
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('*')
                ->from("access_channel")
                ->where($ar)
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
    // ==============================================
    // DISPLAY ROOM
    // ==============================================
    public function getDataDisplay()
    {
        try {
            $ar = array(
                "r.is_deleted" => 0,
                "rd.is_deleted" => 0,
            );
            $data = $this->db->select('rd.*, r.name as room_name')
                ->from("room_display rd")
                ->join("room r", "rd.room_id=r.radid")
                ->where($ar)
                ->order_by('r.id', 'ASC')
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

    public function getDataRoomDisplayByListID($listId = [])
    {
        try {
            $ar = array(
                "r.is_deleted" => 0,
            );
            $data = $this->db->select('r.*')
                ->from("room r")
                ->where($ar)
                ->where_in('radid',$listId)
                ->order_by('r.name', 'ASC')
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
    public function getDataRoomDisplay()
    {
        try {
            $ar = array(
                "r.is_deleted" => 0,
                // "rd.is_deleted" => 0,
            );
            $data = $this->db->select('r.*, background,
                bui.name as building_name, 
                    bui.detail_address as building_detail_address, 
                    bui.google_map as building_google_map')
                ->from("room r")
                ->join("room_display rd", "r.radid=rd.room_id", "left")
                ->join("building bui", "r.building_id=bui.id" , 'left')
                ->where($ar)
                ->order_by('r.name', 'ASC')
                ->get();
            //
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
    // ==============================================
    // USER
    // ==============================================
    public function getDataNotUser()
    {
        try {
            $ar = array(
                "username"     => null,
                "e.is_deleted" => 0,
            );
            $arIfExist = array(
                "username" => null,
            );

            $data1 = $this->db->select('e.*')
                ->from("employee e")
                ->join('user u', 'e.id = u.employee_id', 'left')
                ->where($ar)
                ->get();
            $result = $data1->result_array();
            $QUERY2 = 'select DISTINCT e.*
						FROM
						employee e
						LEFT JOIN user u ON e.id = u.employee_id
						WHERE u.is_deleted=1 AND e.is_deleted=0 AND
							(select COUNT(*)
								FROM user
								WHERE employee_id=u.employee_id
								AND is_deleted=0 )
							< 1';
            $data2   = $this->db->query($QUERY2);
            $result2 = $data2->result_array();
            foreach ($result2 as $key) {
                array_push($result, $key);
            }
            $sn = array(
                "error" => null,
                "data"  => $result,
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
    public function getGroupDetail($id)
    {
        try {
            $ar = array(
                "ld.level_id"   => $id,
                "ld.is_deleted" => 0,
                "l.is_deleted"  => 0,
            );
            $data = $this->db->select('ld.*, name')
                ->from("level_descriptiion ld ")
                ->join("level l ", 'ld.level_id=l.id')
                ->where($ar)
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getDataGroupUser()
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('*')
                ->from("level u")
                ->where($ar)
                ->order_by("sort_level", "ASC")
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
    public function getDataUser()
    {
        try {
            $ar = array(
                "u.is_deleted" => 0,
            );
            $data = $this->db->select('u.*, l.name as group_name, e.name name_emp ')
                ->from("user u")
                ->join("level l", "u.level_id=l.id", "left")
                ->join("employee e", "u.employee_id=e.id")
                ->where($ar)
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
    public function getEditUser($id)
    {
        try {
            $ar = array(
                "is_deleted" => 0,
                "id"         => $id,
            );
            $data = $this->db->select('*')
                ->from("user")
                ->where($ar)
                ->order_by('id', 'ASC')
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    // ===========================================================
    // EMPLOYEE
    // ===========================================================

    public function getDataEmployeeWithQrByQrcode($code)
    {
        try {
            $ar = array(
                "e.is_deleted" => 0,
            );
            $data = $this->db->select('username, real_password')
                ->from("user u")
                ->like('secure_qr', $code, 'both') 
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
    public function getDataEmployeeWithQr($w = array())
    {
        try {
            $ar = array(
                "e.is_deleted" => 0,
            );
            $data = $this->db->select('secure_qr,u.username, e.*, at.name company_name, a.name department_name')
                ->from("employee e")
                ->join("alocation_type at", "e.company_id=at.id", 'left')
                ->join("alocation a", "e.department_id=a.id", 'left')
                ->join("user u", "e.id=u.employee_id", 'left')
                ->where($ar)
                ->where($w)
                ->order_by('_generate', 'ASC')
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
    public function getDataEmployee($w = array())
    {
        try {
            $ar = array(
                "e.is_deleted" => 0,
            );
            $data = $this->db->select('e.*, at.name company_name, a.name department_name')
                ->from("employee e")
                ->join("alocation_type at", "e.company_id=at.id", 'left')
                ->join("alocation a", "e.department_id=a.id", 'left')
                ->where($ar)
                ->where($w)
                ->order_by('_generate', 'ASC')
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
    public function getEditEmployeeByID($id)
    {
        try {
            $ar = array(
                "is_deleted" => 0,
                "id"         => $id,
            );
            $data = $this->db->select('*')
                ->from("employee")
                ->where($ar)
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getEditEmployee($nik)
    {
        try {
            $ar = array(
                "is_deleted" => 0,
                "nik"        => $nik,
            );
            $data = $this->db->select('*')
                ->from("employee")
                ->where($ar)
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getDataEmployeeWhereInNik($nikArray)
    {
        if (count($nikArray) <= 0) {
            return array(
                "error" => $error,
                "data"  => "",
            );
        }
        try {

            $data = $this->db->select('name, email, id as employee_id, nik, card_number, no_phone,no_ext')
                ->from("employee")
                ->where_in('nik', $nikArray)
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
    public function getDataEmployeeApproval($w = array())
    {
        try {

            $data = $this->db->select('e.name, email, e.id as employee_id, nik, card_number, no_phone,no_ext, u.access_id')
                ->from("employee e")
                ->join("user u", "e.id=u.employee_id", "left")
                ->where('access_id LIKE "%4%" ')
                ->where($w)
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
    // ===========================================================
    // BOOKING
    // ===========================================================

    public function checkBookingByTime($room, $date, $start, $end, $type = "", $isApproval = false)
    {
        try {
            $ar = array(
                "b.room_id"    => $room,
                "b.date"       => $date,
                "b.is_deleted" => 0,
                "bi.is_pic" => 1,
            );
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
            if ($type == "start") {
                $data = $this->db->select($qselect)
                    ->from("booking b")
                    ->join("room r", "b.room_id=r.radid", 'left')
                    ->join("booking_invitation bi", "bi.booking_id=b.booking_id", 'left')
                    ->join("building bui", "r.building_id=bui.id" , 'left')
                    ->where($ar)
                    ->where(" TIME(b.server_start) BETWEEN TIME('" . $start . "') AND TIME('" . $end . "')  ")
                    ->get();
            } else {
                $data = $this->db->select($qselect)
                    ->from("booking b")
                    ->join("room r", "b.room_id=r.radid", 'left')
                    ->join("booking_invitation bi", "bi.booking_id=b.booking_id", 'left')
                    ->join("building bui", "r.building_id=bui.id" , 'left')
                    ->where($ar)
                    ->where(" TIME(DATE_ADD(b.server_end, INTERVAL b.extended_duration-1 MINUTE))  BETWEEN TIME('" . $start . "') AND TIME('" . $end . "')  ")
                    ->get();
            }

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
    public function checkBookingByTimeRe($room, $date, $start, $end, $bookingid, $type = "", $isApproval = false)
    {
        try {
            $ar = array(
                "b.room_id"    => $room,
                "b.date"       => $date,
                "b.is_deleted" => 0,
                "bi.is_pic" => 1,
            );
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

            if ($type == "start") {
                $data = $this->db->select( $qselect)
                    ->from("booking b")
                    ->join("room r", "b.room_id=r.radid", 'left')
                    ->join("booking_invitation bi", "bi.booking_id=b.booking_id", 'left')
                    ->join("building bui", "r.building_id=bui.id" , 'left')
                    ->where($ar)
                    ->where('b.booking_id <>"' . $bookingid . '" ')
                    ->where(" TIME(b.server_start) BETWEEN TIME('" . $start . "') AND TIME('" . $end . "')  ")
                    ->get();
            } else {
                $data = $this->db->select( $qselect)
                    ->from("booking b")
                    ->join("room r", "b.room_id=r.radid", 'left')
                    ->join("booking_invitation bi", "bi.booking_id=b.booking_id", 'left')
                    ->join("building bui", "r.building_id=bui.id" , 'left')
                    ->where($ar)
                    ->where('b.booking_id <>"' . $bookingid . '" ')
                    ->where(" TIME(DATE_ADD(b.server_end, INTERVAL b.extended_duration-1 MINUTE))  BETWEEN TIME('" . $start . "') AND TIME('" . $end . "')  ")
                    ->get();
            }
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

    public function checkFeedMeetingMerge($listexist, $listnew)
    {
        $list = $listexist;
        $dd   = [];
        foreach ($listexist as $key => $vexist) {
            foreach ($listnew as $key => $vnew) {
                if ($vnew['booking_id'] == $vexist['booking_id']) {
                    continue;
                }
                array_push($dd, $vnew);
            }
        }
        foreach ($dd as $key => $value) {
            array_push($list, $value);
        }
        return $list;

    }
    public function ckMeetingVipAccess($list_bookingId)
    {	 // check jika meeting yang ada memeiliki akses vip
    	$ar = [
    		'b.is_vip' => 1
    	];
    	try {
    		$data = $this->db->select('b.*, e.vip_approve_bypass, e.vip_limit_cap_bypass, e.vip_lock_room')
            ->from("booking b")
            ->join("employee e", 'b.vip_user=e.nik', 'left')
            ->where($ar)
            ->where_in('booking_id',$list_bookingId)
            ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->result_array(),
            );
            return $sn;
    	}catch(Exception $e){
    		$sn = array(
                "error" => $error,
                "data"  => $this->db->error(),
            );
            return $sn;
            
    	}
        
        

    }
    public function checkKondisiBookingPerRuangan($room, $date, $start, $end, $isVip = false)
    {
        $checkBookingByTime    = $this->checkBookingByTime($room, $date, $start, $end, "start", $isVip)['data'];
        // echo $this->db->last_query();
        $checkBookingByTimeEnd = $this->checkBookingByTime($room, $date, $start, $end, "end", $isVip)['data'];

        $cCheckdata                     = array();
        $cColectionExisting             = array();
        $cColectionExistingMeetingMoved = array();
        
        // print_r($date);
        // print_r($checkBookingByTimeEnd);

        if (count($checkBookingByTime) > 0) {
            foreach ($checkBookingByTime as $key => $value) {
                if ($value['is_alive'] == 0 || $value['is_canceled'] == 1 || $value['is_expired'] == 1 || $value['end_early_meeting'] == 1) {
                    array_push($cCheckdata, $key);
                }
            }
            foreach ($cCheckdata as $key => $value) {
                unset($checkBookingByTime[$value]);
            }
            if (count($checkBookingByTime) > 0) {
                if ($isVip == false) {
                    $response = response("fail", array(), "The schedule have been created by other  ");
                    echo $response;
                    die();
                } else {
                    foreach ($checkBookingByTime as $key => $value) {
                        array_push($cColectionExisting, $value['booking_id']);
                        array_push($cColectionExistingMeetingMoved, $value);
                    }

                }

            }
        }
        if (count($checkBookingByTimeEnd) > 0) {
            foreach ($checkBookingByTimeEnd as $key => $value) {
                if ($value['is_alive'] == 0 || $value['is_canceled'] == 1 || $value['is_expired'] == 1 || $value['end_early_meeting'] == 1) {
                    array_push($cCheckdata, $key);
                }
            }
            foreach ($cCheckdata as $key => $value) {
                unset($checkBookingByTimeEnd[$value]);
            }
            if (count($checkBookingByTimeEnd) > 0) {
                if ($isVip == false) {
                    $response = response("fail", array(), "The schedule have been created by other  ");
                    echo $response;
                    die();
                } else {
                    foreach ($checkBookingByTimeEnd as $key => $value) {
                        if (!in_array($value['booking_id'], $cColectionExisting)) {
                            array_push($cColectionExisting, $value['booking_id']);
                            array_push($cColectionExistingMeetingMoved, $value);
                        }

                    }
                    // true VVIP
                    // print_r($checkBookingByTime);
                    // die();
                }

            }
        }


        return $cColectionExistingMeetingMoved;

    }
    public function checkKondisiBookingPerRuanganRes($room, $date, $start, $end, $bookingid, $isApprovalEnable = false)
    {
        $checkBookingByTime    = $this->checkBookingByTimeRe($room, $date, $start, $end, $bookingid, "start", $isApprovalEnable)['data'];
        $checkBookingByTimeEnd = $this->checkBookingByTimeRe($room, $date, $start, $end, $bookingid, "end", $isApprovalEnable)['data'];
        $cCheckdata            = array();
        if (count($checkBookingByTime) > 0) {
            foreach ($checkBookingByTime as $key => $value) {
                if ($value['is_alive'] == 0 || $value['is_canceled'] == 1 || $value['is_expired'] == 1 || $value['end_early_meeting'] == 1) {
                    array_push($cCheckdata, $key);
                }
            }
            foreach ($cCheckdata as $key => $value) {
                unset($checkBookingByTime[$value]);
            }
            if (count($checkBookingByTime) > 0) {
                $response = response("fail", array(), "The schedule have been created by other ");
                echo $response;
                die();
            }
        }
        if (count($checkBookingByTimeEnd) > 0) {
            foreach ($checkBookingByTimeEnd as $key => $value) {
                if ($value['is_alive'] == 0 || $value['is_canceled'] == 1 || $value['is_expired'] == 1 || $value['end_early_meeting'] == 1) {
                    array_push($cCheckdata, $key);
                }
            }
            foreach ($cCheckdata as $key => $value) {
                unset($checkBookingByTimeEnd[$value]);
            }
            if (count($checkBookingByTimeEnd) > 0) {
                $response = response("fail", array(), "The schedule have been created by other ");
                echo $response;
                die();
            }
        }
    }
    public function getFilterScheduleBooking($wbooking)
    {
        try {
            $ar = array(
                "b.is_deleted" => 0,
            );
            $data = $this->db
            // ->distinct()
                ->select(' b.*, title,r.name as room_name2, (SELECT bii2.nik FROM booking_invitation bii2 WHERE bii2.booking_id=b.booking_id AND is_pic=1 LIMIT 1 ) as nik_pic ')
                ->from("booking b")
                ->join("room r", "b.room_id=r.radid")
                ->join("building bu", "r.building_id=bu.id", "left")
                ->join("booking_invitation bi", "b.booking_id=bi.booking_id")
                ->where($ar)
                ->where($wbooking)
                ->group_by('b.booking_id')
                ->order_by("DATE(b.date)", "DESC")
            // ->order_by("start", 'desc')
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


    public function getDataBooking($date1, $date2)
    {
        try {
            $ar = array(
                "b.is_deleted" => 0,

            );
            $data = $this->db
            // ->distinct()
                ->select(' b.*, title,r.name as room_name2')
                ->from("booking b")
                ->join("room r", "b.room_id=r.radid")
                ->where("b.date >=", $date1)
                ->where("b.date <=", $date2)
                ->where($ar)
                ->group_by('b.booking_id')
                ->order_by("DATE(b.date)", "desc")
            // ->order_by("start", 'desc')
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
    public function getPICByBookingId($booking_id)
    {
        try {
            $ar = array(
                "bi.booking_id" => $booking_id,
                "bi.is_pic"     => 1,
                "bi.internal"   => 1,

            );
            $data = $this->db->select('bi.*')
                ->from("booking_invitation bi")
                ->where($ar)
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getDataBookingByNik($date1, $date2, $nik)
    {
        try {
            $ar = array(
                "b.is_deleted" => 0,
                "bi.internal"  => 1,
                "bi.nik"       => $nik,
            );
            $data = $this->db->select('b.*, r.name as room_name2, bi.is_pic')
                ->from("booking b")
                ->join("room r", "b.room_id=r.radid")
                ->join("booking_invitation bi", "b.booking_id=bi.booking_id")
                ->where("b.date >=", $date1)
                ->where("b.date <=", $date2)
                ->where($ar)
                ->group_by('b.booking_id')
                ->order_by("DATE(b.date)", "desc")
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
    public function getDataBookingByOther($date1, $date2, $nik)
    {
        try {
            $ar = array(
                "b.is_deleted" => 0,
                "bi.is_pic"    => 1,
            );
            $data = $this->db->select('b.*, r.name as room_name')
                ->from("booking b")
                ->join("room r", "b.room_id=r.radid")
                ->join("booking_invitation bi", "b.booking_id=bi.booking_id")
                ->where("b.date >=", $date1)
                ->where("b.date <=", $date2)
                ->where($ar)
                ->where("bi.nik <>'" . $nik . "' ")
                ->order_by("DATE(b.date)", "desc")
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
    public function getDataBookingPartisipant($p)
    {
        try {
            $ar1 = array(
                "biv.booking_id" => $p['booking_id'],
                "biv.internal"   => 1,
            );
            $ar2 = array(
                "biv.booking_id" => $p['booking_id'],
                "biv.internal"   => 0,
            );
            $data1 = $this->db->select('biv.*, emp.name as emp_name, emp.no_phone as emp_phone, emp.email as emp_email ')
                ->from("booking_invitation biv")
            // ->join("booking_invitation biv", "b.booking_id=biv.booking_id" , "left")
                ->join("employee emp", "biv.nik=emp.nik", "left")
                ->where($ar1)
                ->get();
            $data2 = $this->db->select('biv.* ')
            // ->from("booking b")
                ->from("booking_invitation biv")
            // ->join("booking_invitation biv", "b.booking_id=biv.booking_id")
                ->where($ar2)
                ->get();
            $datab = array(
                "internal"  => $data1->result_array(),
                "eksternal" => $data2->result_array(),
            );
            $sn = array(
                "error" => null,
                "data"  => $datab,
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
    public function getDataBookingById($id)
    {
        try {
            $ar = array(
                "b.booking_id" => $id,
            );
            $data = $this->db->select('b.*, r.name as room_name2, price')
                ->from("booking b")
                ->join("room r", "b.room_id=r.radid", 'left')
                ->where($ar)
                ->group_by('b.booking_id')
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getDataBookingInvById($id)
    {
        try {
            $ar = array(
                "bi.is_deleted" => 0,
                "bi.booking_id" => $id,
            );
            $data = $this->db->select('bi.*, e.name as name2, e.email as email2, e.no_phone')
                ->from("booking_invitation bi")
                ->join("employee e", "bi.nik=e.nik", 'left')
                ->where($ar)
            // ->group_by('nom_dept');
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
    public function getDataBookingNow($date)
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('*')
                ->from("booking")
                ->where("date", $date)
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
    public function getBookingAlocationPIC($nik)
    {
        try {
            $ar = array(
                "a.is_deleted" => 0,
                "am.nik"       => $nik,
            );
            $data = $this->db->select('am.*, at.name as name_company ,a.name, type, at.invoice_status as alocation_type_invoice_status, a.invoice_status ')
                ->from("alocation_matrix am")
                ->join("alocation a", "am.alocation_id=a.id", "left")
                ->join("alocation_type at", "a.type=at.id", "left")
                ->where($ar)
                ->order_by('a.id', 'ASC')
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

    public function getEmployeeBooking($data)
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('name, division_id, email, id as employee_id, nik')
                ->from("employee")
                ->where_in("nik", $data)
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
    public function checkBookingAlocationData($id)
    {
        try {
            $ar = array(
                "a.is_deleted" => 0,
                "a.id"         => $id,
            );
            $data = $this->db->select('a.*, at.invoice_status as invoice')
                ->from("alocation a")
                ->join("alocation_type at", "a.type=at.name", "left")
                ->where($ar)
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function checkBookingRoom($date, $start, $end, $room)
    {
        try {
            $wh = array(
                "room_id"     => $room,
                "is_canceled" => 0,
                "date"        => $date,
            );
            $data2 = $this->db->select('*')
                ->from("booking")
                ->where($wh)
                ->where("start BETWEEN TIME('$start') AND TIME('$end')")
                ->or_where("end BETWEEN TIME('$start') AND TIME('$end')")
            ;
            $data  = $data2->get();
            $print = $this->db->last_query();
            // echo $print ;
            // die();
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
    public function insertToBooking($data)
    {

        // $dta = $this->db->insert(", $data);
    }
    // ===========================================================
    // REPORT
    // ===========================================================
    public function getReportAll($start, $end)
    {
        try {

            if ($start == $end) {
                $wh = array(
                    "date" => $start,
                );
                $data2 = $this->db->select('b.*, r.name as room_name, r.facility_room ')
                    ->from("booking b")
                    ->join("room r", "b.room_id=r.id")
                    ->where($wh);
            } else {
                $data2 = $this->db->select('b.*, r.name as room_name, r.facility_room ')
                    ->from("booking b")
                    ->join("room r", "b.room_id=r.id")
                    ->where("date BETWEEN DATE('$start') AND DATE('$end')")
                    ->or_where("date=DATE('$start') ");
            }

            $data = $data2->get();
            $sn   = array(
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
    public function getReportEmDetail($id)
    {
        try {
            $wh = array(
                "id" => $id,
            );
            $data2 = $this->db->select('name, division_id, nik, email')
                ->from("employee")
                ->where($wh);
            $data = $data2->get();
            $sn   = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getReportEmployee($start, $end, $id)
    {
        try {
            $wh = array(
                // "room_id" => $room,
                "bi.employee_id" => $id,
                "bi.internal"    => 1,
            );
            if ($start == $end) {
                $wh['date'] = $start;
                $data2      = $this->db->select('b.*, r.name as room_name, r.facility_room , e.name as employee_name, e.division_id')
                    ->from("booking b")
                    ->join("booking_invitation bi", "b.booking_id=bi.booking_id")
                    ->join("employee e", "bi.employee_id=e.id")
                    ->join("room r", "b.room_id=r.id")
                    ->where($wh)
                // ->or_where("date=DATE('$start') ")
                ;
            } else {
                $data2 = $this->db->select('b.*, r.name as room_name, r.facility_room , e.name as employee_name, e.division_id')
                    ->from("booking b")
                    ->join("booking_invitation bi", "b.booking_id=bi.booking_id")
                    ->join("employee e", "bi.employee_id=e.id")
                    ->join("room r", "b.room_id=r.id")
                    ->where($wh)
                    ->where("date BETWEEN DATE('$start') AND DATE('$end')")
                // ->or_where("date=DATE('$start') ")
                ;
            }

            $data = $data2->get();
            $sn   = array(
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
    public function getReportInviEmp($id = "")
    {
        try {
            $wh = array(
                "b.booking_id" => $id,
                "internal"     => 1,
            );
            $data2 = $this->db->select('b.*, e.name, e.nik, e.division_id,e.email')
                ->from("booking_invitation b")
                ->join("employee e", "b.employee_id=e.id")
                ->where($wh)
            ;
            $data = $data2->get();
            $sn   = array(
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
    public function getReportInviEks($id = "")
    {
        try {
            $wh = array(
                "b.booking_id" => $id,
                "internal"     => 0,
            );
            $data2 = $this->db->select('b.*')
                ->from("booking_invitation b")
                ->where($wh)
            ;
            $data = $data2->get();
            $sn   = array(
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
    public function getInvoiceDetail($id = "")
    {
        try {
            $wh = array(
                "invoice_id" => $id,
            );
            $data2 = $this->db->select('*')
                ->from("booking_invoice_generate b")
                ->where($wh)
            ;
            $data = $data2->get();
            $sn   = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getDataBookingInvoiceAlocation($month1, $month2, $year)
    {
        try {
            $sqlalocation = $this->db->select('al.*, at.name as name_type, at.invoice_status as invoice_status_type ')
                ->from("alocation al")
                ->join("alocation_type at ", "al.type=at.name", "left")
                ->where("al.is_deleted = 0")
                ->get();
            $rowalocation = $sqlalocation->result_array();
            $sql_invoice  = "SELECT COALESCE(max(invoice_format), '') as invoice_format from booking_invoice_generate WHERE invoice_years= " . $year . " AND is_deleted=0 ";
            $resInvoice   = $this->db->query($sql_invoice);
            $rowInvoice   = $resInvoice->row_array();
            $sql_invgen   = "SELECT * from booking_invoice_generate WHERE invoice_years= " . $year . " AND is_deleted=0 ";
            $resultquery  = $this->db->query($sql_invgen);
            $resultdata   = $resultquery->result_array();
            $nomor        = 0;
            if ($rowInvoice['invoice_format'] == "" || $rowInvoice['invoice_format'] == null) {

            } else {
                $oldNoInv = $rowInvoice['invoice_format'];
                $spOldInv = explode("/", $oldNoInv);
                $nomor    = ($spOldInv[0] - 0);
            }
            $collect                    = array();
            $collectBookingInvoice      = array();
            $collectBookingInvoiceWhere = array();
            foreach ($rowalocation as $key => $value) {
                $before    = array();
                $invmonth1 = $month1;
                $invmonth2 = $month2;
                foreach ($resultdata as $oo => $v) {
                    if ($v['alocation_id'] == $value['id']) {
                        $before = $v;
                    }
                }
                if (count($before) > 0) {
                    $invmonth1 = $before['invoice_month2'] + 1;
                    if ($invmonth1 > 12) {
                        // continue;
                    }
                }

                $ssql = $this->db->select('binv.*, duration_per_meeting, total_duration, extended_duration ')
                    ->from("booking_invoice binv")
                    ->join("booking b ", "binv.booking_id=b.booking_id", "left")
                    ->where('alocation_id="' . $value['id'] . '" ')
                    ->where('MONTH(b.date) >= "' . $invmonth1 . '" ')
                    ->where('MONTH(b.date) <= "' . $invmonth2 . '" ')
                    ->where('YEAR(b.date) = ' . $year . ' ')
                    ->where('b.is_canceled=0 ') // cancel no
                    ->get();
                // print_r($ssql->num_rows());

                if ($ssql->num_rows() > 0) {
                    $datainvresult = $ssql->result_array();
                    $datetime      = date("Y-m-d H:i:s"); //date generate
                    $now_month     = date("m"); //date generate
                    $nomor++;
                    $noUrut             = sprintf("%03d", $nomor);
                    $mmmm               = sprintf("%02d", $now_month);
                    $randoom_id         = random_string('numeric', 5);
                    $invoice_id         = "INV-" . $randoom_id;
                    $invoice_format     = $noUrut . "/" . $invoice_id . "/" . $mmmm . "/" . $year;
                    $datetime           = date("Y-m-d H:i:s"); //date generate
                    $usermake           = $this->session->userdata('user-nya');
                    $total_cost_meeting = 0;
                    $total_meeting      = $ssql->num_rows();
                    $total_duration     = 0;
                    if ($value['invoice_status'] == 0) {
                        $status = "N/A";
                    } else {
                        $status = "0";
                    }
                    foreach ($datainvresult as $iii => $invrow) {
                        $fHour           = $invrow['duration_per_meeting'];
                        $duration        = ($invrow['total_duration'] + $invrow['extended_duration']);
                        $getHoursMeeting = floor($duration / $fHour);
                        $checkHours      = fmod($duration, $fHour);
                        if ($checkHours > 0) {
                            $getHoursMeeting += 1;
                        }
                        $total_duration += $getHoursMeeting;
                        $total_cost_meeting += $invrow['rent_cost'];
                        $array_invoice = array(
                            'invoice_generate_no' => $invoice_id,
                            'invoice_status'      => $status,
                        );
                        $array_invoice = array(
                            'invoice_generate_no' => $invoice_id,
                            'invoice_status'      => $status,
                            'where'               => array(
                                'invoice_no' => $invrow['invoice_no'],
                            ),
                        );
                        array_push($collectBookingInvoice, $array_invoice);
                        # code...
                    }
                    $array_generate = array(
                        'invoice_id'     => $invoice_id,
                        'invoice_format' => $invoice_format,
                        'invoice_month1' => $invmonth1,
                        'invoice_month2' => $invmonth2,
                        'invoice_years'  => $year,
                        'alocation_id'   => $value['id'],
                        'total_cost'     => $total_cost_meeting,
                        'total_meeting'  => $total_meeting,
                        'total_duration' => $total_duration,
                        'status'         => $status,
                        'date_generate'  => $datetime,
                        'generate_by'    => $usermake,
                        'created_by'     => $usermake,
                        'created_at'     => $datetime,
                        'is_deleted'     => 0,
                    );
                    array_push($collect, $array_generate);

                    // print_r($array_generate);

                }
            }
            // print_r($collect);
            // print_r($collectBookingInvoice);
            // die();
            if (count($collect) > 0) {
                $this->db->insert_batch('booking_invoice_generate', $collect);
                foreach ($collectBookingInvoice as $kk => $vr) {
                    $wh = $vr['where'];
                    unset($vr['where']);
                    $this->db->update('booking_invoice', $vr, $wh);
                }
                $retname = array(
                    "msg" => "Generate some data",
                );
            } else {
                $retname = array(
                    "msg" => "No generate",
                );
            }
            $sn = array(
                "error" => null,
                "data"  => $retname,
            );
            return $sn;
        } catch (Exception $error) {
            $sn = array(
                "error" => $error,
                "data"  => array(),
            );
            return $sn;
        }
    }

    public function getDataBookingInvoiceWithYears($years)
    {
        try {
            $sqlalocation = $this->db->select('*')
                ->from("booking_invoice_generate")
                ->where("invoice_years <= " . $years)
                ->get();
            $rowalocation = $sqlalocation->result_array();
            // print_r($rowalocation);

            $sn = array(
                "error" => null,
                "data"  => $rowalocation,
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
    public function checkBookingInvoiceWithYears($years)
    {
        try {
            $sqlalocation = $this->db->select('COUNT(*) as invoice')
                ->from("booking_invoice_generate")
                ->where("invoice_years = " . $years)
                ->get();
            $rowalocation = $sqlalocation->row_array();
            $sn           = array(
                "error" => null,
                "data"  => $rowalocation,
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
    public function getDetailInvoiceById($id)
    {
        try {

            $sqlalocation = $this->db->select('*')
                ->from("booking_invoice_detail")
                ->where("invoice_id", $id)
                ->get();
            $rowalocation = $sqlalocation->result_array();
            $sn           = array(
                "error" => null,
                "data"  => $rowalocation,
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
    public function getDetailInvoiceByAlocatio($inv, $alocation)
    {
        try {

            $sqlalocation = $this->db->select('ind.*, invoice_years, ')
                ->from("booking_invoice_detail ind")
                ->join("booking_invoice_generate in", "ind.invoice_id=in.invoice_id")
                ->where("ind.invoice_id", $inv)
                ->where("ind.alocation_id", $alocation)
                ->get();
            $rowalocation = $sqlalocation->result_array();
            $sn           = array(
                "error" => null,
                "data"  => $rowalocation,
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
    public function generateInvoiceYears($years)
    {
        try {
            $datetime     = date('Y-m-d H:i:s');
            $invoice_id   = "INV-" . random_string('numeric', 5);
            $sqlalocation = $this->db->select('al.*, at.name as name_type, at.invoice_status as type_invoice_status')
                ->from("alocation al")
                ->join("alocation_type at ", "al.type=at.name", "left")
                ->get();
            $rowalocation       = $sqlalocation->result_array();
            $total_meeting      = 0;
            $total_cost         = 0;
            $total_duration     = 0;
            $insertbatchInvoice = [];
            $numberrand         = 0;
            foreach ($rowalocation as $key => $value) {
                $sql = ' SELECT
					(SELECT SUM(total_duration)+SUM(extended_duration) as total_duration from booking b
					WHERE alocation_id="' . $value['id'] . '" AND YEAR(b.date) = "' . $years . '"
					) as total_duration,
					(
					SELECT SUM(cost_total_booking) as total_alocation from booking b
					WHERE alocation_id="' . $value['id'] . '" AND YEAR(b.date) = "' . $years . '"
					) as total_alocation_cost,
					(SELECT COUNT(b.booking_id) as total_alocation from booking b
					WHERE alocation_id="' . $value['id'] . '" AND YEAR(b.date) <= "' . $years . '"
					) as total_meeting
				';
                $rawdata = $this->db->query($sql);

                $rowalocation[$key]['data'] = $rawdata->row_array();
                $total_meeting += $rowalocation[$key]['data']['total_meeting'] == null
                ? 0 : $rowalocation[$key]['data']['total_meeting'];
                $total_cost += $rowalocation[$key]['data']['total_alocation_cost'] == null
                ? 0 : $rowalocation[$key]['data']['total_alocation_cost'];
                $total_duration += $rowalocation[$key]['data']['total_duration'] == null
                ? 0 : $rowalocation[$key]['data']['total_duration'];
                $varTotalCost = $rowalocation[$key]['data']['total_alocation_cost'] == null
                ? 0 : $rowalocation[$key]['data']['total_alocation_cost'];

                if ($varTotalCost > 0) {
                    $numberrand++;
                    $years                    = date('Y', strtotime($datetime)); // get tahun from date
                    $y_years                  = date('y', strtotime($datetime)); // get tahun from date
                    $months                   = date('m', strtotime($datetime)); // get tahun from date
                    $days                     = date('d', strtotime($datetime)); // get tahun from date
                    $newNoUrut                = sprintf("%05d", $numberrand);
                    $formatInvoice            = $newNoUrut . "/" . $invoice_id . "/" . $months . "/" . $y_years;
                    $ibatch                   = array();
                    $ibatch['invoice_id']     = $invoice_id;
                    $ibatch['no_invoice']     = $formatInvoice;
                    $ibatch['no_urut']        = $newNoUrut;
                    $ibatch['alocation_id']   = $value['id'];
                    $ibatch['alocation_name'] = $value['name'];
                    $ibatch['total_meeting']  = $rowalocation[$key]['data']['total_meeting'] == null
                    ? 0 : $rowalocation[$key]['data']['total_meeting'];
                    $ibatch['total_duration'] = $rowalocation[$key]['data']['total_duration'] == null
                    ? 0 : $rowalocation[$key]['data']['total_duration'];
                    $ibatch['total_cost'] = $rowalocation[$key]['data']['total_alocation_cost'] == null
                    ? 0 : $rowalocation[$key]['data']['total_alocation_cost'];
                    $invoice_status = "";
                    if ($value['type_invoice_status'] == 1) {
                        if ($value['invoice_status'] == 1) {
                            $invoice_status = 0;
                        } else {
                            $invoice_status = "N/A";
                        }
                    } else {
                        if ($value['invoice_status'] == 1) {
                            $invoice_status = 0;
                        } else {
                            $invoice_status = "N/A";
                        }
                        // 0
                        //
                    }
                    $ibatch['outstanding_status'] = 1;
                    $ibatch['invoice_status']     = $invoice_status;
                    $ibatch['alocation_type']     = $value['name_type'];
                    $ibatch['created_by']         = $value['name_type'];
                    $ibatch['created_at']         = $datetime;
                    array_push($insertbatchInvoice, $ibatch);
                }
            }
            $insert = array(
                'invoice_id'     => $invoice_id,
                'invoice_years'  => $years,
                'total_cost'     => $total_cost,
                'total_meeting'  => $total_meeting,
                'total_duration' => $total_duration,
                'created_by'     => $this->session->userdata('user-nya'),
                'created_at'     => $datetime,
            );
            // echo "<pre>";
            // print_r($insertbatchInvoice);
            // die();
            $respP = $this->insertDataBatch('booking_invoice_detail', $insertbatchInvoice);
            $respP = $this->insertData('booking_invoice_generate', $insert);
            $sn    = array(
                "error" => null,
                "data"  => $rowalocation,
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
    public function getDataBookingInvoiceAlocationWithDateDetail($year, $alocation)
    {
        try {
            $sqlform = 'SELECT
					b.duration_per_meeting as dur_meeting,
					al.name as alocation_name, b.total_duration, b.extended_duration,
					b.*, r.location, b.pic ,bi.nik
					FROM booking b
					LEFT JOIN booking_invitation bi ON b.booking_id=bi.booking_id
					LEFT JOIN room r ON b.room_id=r.radid
					LEFT JOIN alocation al ON b.alocation_id=al.id
					WHERE bi.is_pic=1 AND alocation_id="' . $alocation . '" AND YEAR(b.date) = ' . $year;
            $rawdata      = $this->db->query($sqlform);
            $rowalocation = $rawdata->result_array();

            $sn = array(
                "error" => null,
                "data"  => $rowalocation,
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
    public function getDataBookingInvoice()
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('*')
                ->from("booking")
                ->where("is_deleted", 0)
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
    public function getDataBookingInvoiceDetail($id)
    {
        try {
            $ar = array(
                "b.is_deleted" => 0,
                "b.booking_id" => $id,
            );
            $data = $this->db->select('b.*, r.name as room_name, r.facility_room')
                ->from("booking b")
                ->join("room r", "b.room_id=r.id")
                ->where($ar)
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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

    // ==============================================
    // ALOCATION
    // ==============================================
    public function getDataAssignAlocation($id)
    {
        try {
            $ar = array(
                "alocation_id" => $id,
            );
            $data = $this->db->select('*')
                ->from("alocation_matrix")
                ->where($ar)
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
    public function checkAssignAlocation($where)
    {
        try {

            $data = $this->db->select('*')
                ->from("alocation_matrix")
                ->where($where)
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
    public function getDataAlocationType()
    {
        try {
            $ar = array(
                "is_deleted" => 0,
            );
            $data = $this->db->select('*')
                ->from("alocation_type")
                ->where($ar)
                ->order_by('name', 'ASC')
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
    public function getDataAlocationData($w = array())
    {
        try {
            $ar = array(
                "a.is_deleted"  => 0,
                "at.is_deleted" => 0,
            );

            $data = $this->db->select('a.*, at.name type_name')
                ->from("alocation a")
                ->join("alocation_type at", "a.type=at.id", 'left')
                ->where($ar)
                ->where($w)
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
    public function getAlocationWithTypeUser($userid)
    {
        try {
            $ar = array(
                "a.is_deleted" => 0,
                "am.nik"       => $userid,
            );
            $data = $this->db->select('a.*, at.name as name_type, at.invoice_status as type_invoice_status')
                ->from("alocation a")
                ->join("alocation_type at", "a.type=at.name", "left")
                ->join("alocation_matrix am", "a.id=am.alocation_id", "left")
                ->where($ar)
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
    public function getAlocationWithType()
    {
        try {
            $ar = array(
                "a.is_deleted" => 0,
            );
            $data = $this->db->select('a.*, at.name as name_type, at.invoice_status as type_invoice_status')
                ->from("alocation a")
                ->join("alocation_type at", "a.type=at.name", "left")
                ->where($ar)
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
    public function getAlocationWithId($id)
    {
        try {
            $ar = array(
                "a.is_deleted" => 0,
                "a.id"         => $id,
            );
            $data = $this->db->select('a.*, at.name as name_type, at.invoice_status as type_invoice_status')
                ->from("alocation a")
                ->join("alocation_type at", "a.type=at.name", "left")
                ->where($ar)
                ->order_by('id', 'ASC')
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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

    // ==============================================
    // INVOICE
    // ==============================================
    public function getInvStatusName()
    {
        try {
            $ar = array(
                // "is_deleted" => 0
            );
            $data = $this->db->select('*')
                ->from("setting_invoice_text")
                ->where($ar)
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

    // ==============================================
    // SETTING
    // ==============================================
    public function getSettingDataGeneral()
    {
        try {

            $data = $this->db->select('*')
                ->from("setting_rule_deskbooking")
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getTimeSchedule($duration)
    {
        $table = "time_schedule_" . $duration;
        try {

            $data = $this->db->select('*')
                ->from($table)
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
    public function getSettingInvoiceConfig()
    {
        try {

            $data = $this->db->select('*')
                ->from("setting_invoice_config")
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getListSettingEmailSMTPData()
    {
        try {

            $data = $this->db->select('*')
                ->from("setting_smtp")
                ->where(['is_deleted' => 0])
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
    public function getSettingEmailSMTPData()
    {
        try {

            $data = $this->db->select('*')
                ->from("setting_smtp")
                ->where(['is_deleted' => 0])
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getSettingEmailTemplateData()
    {
        try {

            $data = $this->db->select('*')
                ->from("setting_email_template")
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

    // ==============================================
    // PARTISIPANT
    // ==============================================
    public function getDataAttendanceInvitationInternal($bookingId, $nik)
    {
        try {
            $wr = array(
                "bi.booking_id" => $bookingId,
                "bi.nik"        => $nik,
                "bi.internal"   => 1,
            );
            $data = $this->db->select('bi.*, title, date, r.name as room_name,location, b.start, b.end ')
                ->from("booking_invitation bi")
                ->join("booking b ", "bi.booking_id=b.booking_id", "left")
                ->join("room r ", "b.room_id=r.radid", "left")
                ->where($wr)
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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
    public function getDataAttendanceInvitationEksternal($bookingId, $email)
    {
        try {
            $wr = array(
                "bi.booking_id" => $bookingId,
                "bi.email"      => $email,
                "bi.internal"   => 0,
            );
            $data = $this->db->select('bi.*, title, date, r.name as room_name,location, b.start, b.end ')
                ->from("booking_invitation bi")
                ->join("booking b ", "bi.booking_id=b.booking_id", "left")
                ->join("room r ", "b.room_id=r.radid", "left")
                ->where($wr)
                ->get();
            $sn = array(
                "error" => null,
                "data"  => $data->row_array(),
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

    // ==============================================
    // PANTRY TRANSACTION
    // ==============================================
    public function getDataPantryTransactin($id, $nik = "", $start = "", $end = "", $result = "result")
    {
        try {
            $wn = array();
            if ($id != "") {$wn['pt.id'] = $id;}
            if ($nik != "") {$wn['pt.employee_id'] = $nik;}
            $wr = array(
                "pt.is_deleted" => 0,
            );
            // print_r(" order_datetime<='".$end."' AND  order_datetime>='".$start."' ");
            // die();
            // if()
            $data = $this->db->select('pt.*, title, b.date date_booking, r.name as room_name,location, b.start, b.end, e.name emp_name, a.name department_name, pts.name order_status ')
                ->from("pantry_transaksi pt")
                ->join("booking b ", "pt.booking_id=b.booking_id", "left")
                ->join("room r ", "b.room_id=r.radid", "left")
                ->join("employee e ", "pt.employee_id=e.nik", "left")
                ->join("alocation_matrix am ", "e.department_id=am.nik", "left")
                ->join("alocation a ", "am.alocation_id=a.id", "left")
                ->join("pantry_transaksi_status pts ", "pt.order_st=pts.id", "left")
                ->where($wr)
                ->where($wn)
            // ->where(" order_datetime<='".$end."' AND  order_datetime>='".$start."' ")
                ->get();
            if ($result == "row") {
                $sn = array(
                    "error" => null,
                    "data"  => $data->row_array(),
                );
            } else {
                $sn = array(
                    "error" => null,
                    "data"  => $data->result_array(),
                );
            }

            return $sn;
        } catch (Exception $error) {
            $sn = array(
                "error" => $error,
                "data"  => $this->db->error(),
            );
            return $sn;
        }
    } // getDataPantryTransaction

    // ==============================================
    // BLIVE TRANSACTION
    // ==============================================
    public function getBliveHelpgdeskMonitor($start, $end)
    {
        try {
            $wr = array(
                'hm.is_deleted' => 0,
            );
            $data = $this->db->select('hm.*, r.name room_name')
                ->from("helpdesk_monitor hm")
                ->join("room r ", "hm.room_id=r.radid", "left")
                ->where($wr)
                ->where(" DATE(datetime)<='" . $end . "' AND DATE(datetime)>='" . $start . "' ")
                ->order_by("_generate", "DESC")
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
    public function getBliveHelpdeskMobile($start, $end, $serial)
    {
        try {
            $wr = array(
                'hm.is_deleted' => 0,
                'hm.action'     => 1,
                'r.radid'       => $serial,
            );
            $data = $this->db->select('hm.*, r.name room_name')
                ->from("helpdesk_monitor hm")
                ->join("room r ", "hm.room_id=r.radid", "left")
                ->where($wr)
                ->where(" DATE(datetime)<='" . $end . "' AND DATE(datetime)>='" . $start . "' ")
                ->order_by("_generate", "DESC")
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
    public function getBliveHelpgdeskMonitorDetail($wr = array())
    {
        try {
            $data = $this->db->select('hm.*, r.name room_name')
                ->from("helpdesk_monitor hm")
                ->join("room r ", "hm.room_id=r.radid", "left")
                ->where($wr)
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

    public function uploadDataToLockerSystem($ip, $nokartu)
    {
        $u   = $ip . 'api/formapi/apiregistercabin';
        $url = $u;
        $ch  = curl_init($url);
        // $json = json_encode($dt);
        $payload = json_encode(array('no_kartu' => $nokartu));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-formurlencoded'));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function formatDate($string)
    {
        $nM = array('','Jan','Feb','Mar', 'Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        $d = explode("-", $string);
        $y = $d[0];
        $m = $d[1]-0;
        $day = $d[2];
        return $day . " ". $nM[$m] . " ".$y;
    }
    public function formatTime($string)
    {

        $nM = array(
            '00'=> '00',
            '01'=> '01',
            '02'=> '02',
            '03'=> '03',
            '04'=> '04',
            '05'=> '05',
            '06'=> '06',
            '07'=> '07',
            '08'=> '08',
            '09'=> '09',
            '10'=> '10',
            '11'=> '11',
            '12'=> '12',
            '13'=> '13',
            '14'=> '14',
            '15'=> '15',
            '16'=> '16',
            '17'=> '17',
            '18'=> '18',
            '19'=> '19',
            '20'=> '20',
            '21'=> '21',
            '22'=> '22',
            '23'=> '23',
            '24'=> '24',
        );
        $d = explode(":", $string);
        $h = $d[0];
        $m = $d[1];
        $s = $d[2];
        // $formatH = ( ($m-0) > 12 ) ? "PM":"AM";
        $formatH = "";
        return $nM[$h] . ":". $m . " ".$formatH;
    }

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_ActivityLog extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Insert a new activity log
     *
     * @param array $data
     * @return bool
     */
    public function insert_log($data) {
        return $this->db->insert('activity_log', $data);
    }

    private function _apply_filters($filters) {
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('created_at >=', $filters['start_date'] . ' 00:00:00');
            $this->db->where('created_at <=', $filters['end_date'] . ' 23:59:59');
        }
        if (!empty($filters['category'])) {
            $this->db->where('category', $filters['category']);
        }
        if (!empty($filters['action'])) {
            $this->db->where('code', $filters['action']);
        }
        if (!empty($filters['actor_nik'])) {
            $this->db->where('actor_nik', $filters['actor_nik']);
        }
    }

    /**
     * Get recent logs
     *
     * @param int $limit
     * @param int|null $last_id
     * @param array $filters
     * @return array
     */
    public function get_logs($limit = 100, $last_id = null, $filters = []) {
        $this->db->select('*');
        $this->db->from('activity_log');
        
        $this->_apply_filters($filters);
        
        if ($last_id) {
            $this->db->where('id >', $last_id);
            $this->db->order_by('id', 'ASC'); // For polling, we usually want oldest-to-newest of the new ones
        } else {
            $this->db->order_by('id', 'DESC'); // Initial load: get newest 100
        }
        
        $this->db->limit($limit);
        $query = $this->db->get();
        
        $result = $query->result_array();

        if (!$last_id) {
            // Reverse so they are chronological for initial render
            $result = array_reverse($result);
        }

        return $result;
    }

    /**
     * Get dashboard stats
     */
    public function get_dashboard_stats($filters = []) {
        $this->db->select('category, COUNT(*) as total');
        $this->db->from('activity_log');
        $this->_apply_filters($filters);
        $this->db->group_by('category');
        $query = $this->db->get();
        $results = $query->result_array();
        
        $stats = [
            'total_events' => 0,
            'booking_events' => 0,
            'desk_events' => 0,
            'system_events' => 0
        ];
        
        foreach($results as $r) {
            $stats['total_events'] += $r['total'];
            if($r['category'] == 'BOOKING') $stats['booking_events'] = $r['total'];
            if($r['category'] == 'DESK') $stats['desk_events'] = $r['total'];
            if($r['category'] == 'SYSTEM') $stats['system_events'] = $r['total'];
        }
        
        return $stats;
    }

    /**
     * Clear all logs (truncate table)
     *
     * @return bool
     */
    public function clear_logs() {
        return $this->db->truncate('activity_log');
    }
}

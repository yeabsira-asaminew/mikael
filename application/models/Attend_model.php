<?php
class Attend_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_subcategories_with_current_schedule() {
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
        
        // Calculate time range (45-55 minutes from now)
        $min_time = date('H:i:s', strtotime('+45 minutes', strtotime($current_time)));
        $max_time = date('H:i:s', strtotime('+55 minutes', strtotime($current_time)));
        
        $this->db->select('sc.id as sub_category_id');
        $this->db->from('sub_category sc');
        $this->db->join('sub_category_schedule scs', 'scs.sub_category_id = sc.id');
        $this->db->join('schedule s', 's.id = scs.schedule_id');
        $this->db->where('s.date', $current_date);
        $this->db->where('s.time >=', $min_time);
        $this->db->where('s.time <=', $max_time);
        
        return $this->db->get()->result();
    }

    public function get_students_without_attendance($sub_category_id) {
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
        $one_hour_ago = date('H:i:s', strtotime('-1 hour', strtotime($current_time)));
        
        $this->db->select('s.student_id');
        $this->db->from('student s');
        $this->db->where("(
            s.age_category_id = $sub_category_id OR 
            s.curriculum_id = $sub_category_id OR 
            s.department_id = $sub_category_id OR 
            s.choir_id = $sub_category_id
        )");
        $this->db->group_start();
        $this->db->where('s.last_attendance_date !=', $current_date);
        $this->db->or_where('s.last_attendance_time <', $one_hour_ago);
        $this->db->group_end();
        
        return $this->db->get()->result();
    }

    public function check_all_attendance_recorded($sub_category_id) {
        $current_date = date('Y-m-d');
        
        $this->db->select('COUNT(*) as total_students');
        $this->db->from('student s');
        $this->db->where("(
            s.age_category_id = $sub_category_id OR 
            s.curriculum_id = $sub_category_id OR 
            s.department_id = $sub_category_id OR 
            s.choir_id = $sub_category_id
        )");
        
        $total_students = $this->db->get()->row()->total_students;
        
        $this->db->select('COUNT(*) as attended_students');
        $this->db->from('attendance a');
        $this->db->join('student s', 's.id = a.student_id');
        $this->db->where("(
            s.age_category_id = $sub_category_id OR 
            s.curriculum_id = $sub_category_id OR 
            s.department_id = $sub_category_id OR 
            s.choir_id = $sub_category_id
        )");
        $this->db->where('a.date', $current_date);
        
        $attended_students = $this->db->get()->row()->attended_students;
        
        return ($total_students == $attended_students);
    }

    public function record_attendance($student_id, $status, $date = null) {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        
        $data = array(
            'student_id' => $student_id,
            'status' => $status,
            'date' => $date,
            'recorded_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->insert('attendance', $data);
        
        // Update student's last attendance info
        $this->db->where('id', $student_id);
        $this->db->update('students', array(
            'last_attendance_date' => $date,
            'last_attendance_time' => date('H:i:s')
        ));
        
        return $this->db->affected_rows() > 0;
    }
}
?>
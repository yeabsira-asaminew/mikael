<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{


    public function get_student_count_by_year_range($startYear, $endYear)
    {
        return $this->db
            ->select("SUM(CASE WHEN sex = 'male' THEN 1 ELSE 0 END) as male_count,
                  SUM(CASE WHEN sex = 'female' THEN 1 ELSE 0 END) as female_count,
                  COUNT(*) as total_count")
            ->from('student')
            ->where("YEAR(registration_date) >=", $startYear)
            ->where("YEAR(registration_date) <=", $endYear)
            ->get()
            ->row_array();
    }

    public function get_monthly_registration_by_year($start_date, $end_date)
    {
        return $this->db->select("MONTH(st.registration_date) as month, 
                              SUM(CASE WHEN st.sex = 'male' THEN 1 ELSE 0 END) as male_count, 
                              SUM(CASE WHEN st.sex = 'female' THEN 1 ELSE 0 END) as female_count, 
                              COUNT(*) as total_count")
            ->from('student st')
            ->where('st.registration_date >=', $start_date)
            ->where('st.registration_date <=', $end_date)
            ->group_by('MONTH(st.registration_date)')
            ->order_by('MONTH(st.registration_date)', 'ASC')
            ->get()
            ->result_array();
    }

    public function get_students_by_age_group()
    {
        $this->db->select("
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 7 AND sex = 'male' THEN 1 ELSE 0 END) AS under_7_male,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 7 AND sex = 'female' THEN 1 ELSE 0 END) AS under_7_female,

        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 7 AND 10 AND sex = 'male' THEN 1 ELSE 0 END) AS between_7_11_male,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 7 AND 10 AND sex = 'female' THEN 1 ELSE 0 END) AS between_7_11_female,

        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 11 AND 15 AND sex = 'male' THEN 1 ELSE 0 END) AS between_11_16_male,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 11 AND 15 AND sex = 'female' THEN 1 ELSE 0 END) AS between_11_16_female,

        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 16 AND 18 AND sex = 'male' THEN 1 ELSE 0 END) AS between_16_19_male,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 16 AND 18 AND sex = 'female' THEN 1 ELSE 0 END) AS between_16_19_female,

        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 19 AND 34 AND sex = 'male' THEN 1 ELSE 0 END) AS young_male,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 19 AND 34 AND sex = 'female' THEN 1 ELSE 0 END) AS young_female,

        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= 35 AND sex = 'male' THEN 1 ELSE 0 END) AS adult_male,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= 35 AND sex = 'female' THEN 1 ELSE 0 END) AS adult_female
    ");
        $this->db->where('status', 1); // Only active students
        return $this->db->get('student')->row_array();
    }

    public function get_students_by_status()
    {
        $this->db->select("
        status,
        SUM(CASE WHEN sex = 'male' THEN 1 ELSE 0 END) AS male,
        SUM(CASE WHEN sex = 'female' THEN 1 ELSE 0 END) AS female,
        COUNT(*) AS total
    ");
        $this->db->group_by('status');
        return $this->db->get('student')->result_array();
    }

    public function get_students_by_apostolic()
    {
        $this->db->select("
        apostolic_category.name AS apostolic_name,
        SUM(CASE WHEN student.sex = 'male' AND student.apostolic_id IS NOT NULL THEN 1 ELSE 0 END) AS male,
        SUM(CASE WHEN student.sex = 'female' AND student.apostolic_id IS NOT NULL THEN 1 ELSE 0 END) AS female,
        SUM(CASE WHEN student.apostolic_id IS NOT NULL THEN 1 ELSE 0 END) AS total
    ");
        $this->db->from('apostolic_category');
        $this->db->join('student', 'student.apostolic_id = apostolic_category.id', 'left');
        $this->db->group_by('apostolic_category.id');
        return $this->db->get()->result_array();
    }

    public function get_students_by_choir()
{
    $this->db->select("
        sub_category.name AS choir_name,
        SUM(CASE WHEN student.sex = 'male' AND student.choir_id IS NOT NULL THEN 1 ELSE 0 END) AS male,
        SUM(CASE WHEN student.sex = 'female' AND student.choir_id IS NOT NULL THEN 1 ELSE 0 END) AS female,
        SUM(CASE WHEN student.choir_id IS NOT NULL THEN 1 ELSE 0 END) AS total
    ");
    $this->db->from('sub_category');
    $this->db->join('student', 'student.choir_id = sub_category.id', 'left');
    $this->db->where('sub_category.category_id', 4);
    $this->db->group_by('sub_category.id');
    return $this->db->get()->result_array();
}

public function get_students_by_curriculum()
{
    $this->db->select("
        sub_category.name AS curriculum_name,
        SUM(CASE WHEN student.sex = 'male' AND student.curriculum_id IS NOT NULL THEN 1 ELSE 0 END) AS male,
        SUM(CASE WHEN student.sex = 'female' AND student.curriculum_id IS NOT NULL THEN 1 ELSE 0 END) AS female,
        SUM(CASE WHEN student.curriculum_id IS NOT NULL THEN 1 ELSE 0 END) AS total
    ");
    $this->db->from('sub_category');
    $this->db->join('student', 'student.curriculum_id = sub_category.id', 'left');
    $this->db->where('sub_category.category_id', 2);
    $this->db->group_by('sub_category.id');
    return $this->db->get()->result_array();
}

public function get_students_by_department()
{
    $this->db->select("
        sub_category.name AS department_name,
        SUM(CASE WHEN student.sex = 'male' AND student.department_id IS NOT NULL THEN 1 ELSE 0 END) AS male,
        SUM(CASE WHEN student.sex = 'female' AND student.department_id IS NOT NULL THEN 1 ELSE 0 END) AS female,
        SUM(CASE WHEN student.department_id IS NOT NULL THEN 1 ELSE 0 END) AS total
    ");
    $this->db->from('sub_category');
    $this->db->join('student', 'student.department_id = sub_category.id', 'left');
    $this->db->where('sub_category.category_id', 3);
    $this->db->group_by('sub_category.id');
    return $this->db->get()->result_array();
}





}

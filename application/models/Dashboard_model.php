<?php

class Dashboard_model extends CI_Model
{
    // for dashboard charts
    public function get_students_by_sex()
    {
        // Use $this->db->select() with raw SQL for the CASE statement
        $this->db->select('
        CASE 
            WHEN student.sex = "Male" THEN "ወንድ"
            WHEN student.sex = "Female" THEN "ሴት"
        END AS sex_amharic,
        COUNT(*) as count
    ', false); // The `false` parameter prevents CodeIgniter from escaping the CASE statement

        $this->db->group_by('sex_amharic'); // Group by the computed field
        return $this->db->get('student')->result();
    }

    public function get_students_by_status()
    {
        $this->db->select('status, COUNT(*) as count');
        $this->db->group_by('status');
        return $this->db->get('student')->result();
    }

    public function get_students_by_age_group()
    {
        $this->db->select("
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 7 THEN 1 ELSE 0 END) AS under_7,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 7 AND 10 THEN 1 ELSE 0 END) AS between_7_11,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 11 AND 15 THEN 1 ELSE 0 END) AS between_11_16,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 16 AND 18 THEN 1 ELSE 0 END) AS between_16_19,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 19 AND 34 THEN 1 ELSE 0 END) AS young,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= 35 THEN 1 ELSE 0 END) AS adult
    ");
        return $this->db->get('student')->row();
    }

    // Get number of students by year
    public function get_students_by_year()
    {
        // Fetch data from the database
        $this->db->select('YEAR(registration_date) as gregorian_year, COUNT(*) as count');
        $this->db->group_by('YEAR(registration_date)');
        return $this->db->get('student')->result();
    }

    // Get number of students by month for all years
    public function get_students_by_month()
    {
        $this->db->select('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count');
        $this->db->group_by('YEAR(created_at), MONTH(created_at)');
        return $this->db->get('student')->result();
    }

    public function get_attendance_analysis()
    {
        $this->db->select("
            SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent,
            SUM(CASE WHEN created_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND status = 'Present' THEN 1 ELSE 0 END) as present_last_month,
            SUM(CASE WHEN created_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND status = 'Absent' THEN 1 ELSE 0 END) as absent_last_month,
            SUM(CASE WHEN created_date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND status = 'Present' THEN 1 ELSE 0 END) as present_last_week,
            SUM(CASE WHEN created_date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND status = 'Absent' THEN 1 ELSE 0 END) as absent_last_week
        ");
        return $this->db->get('attendance')->row();
    }

    public function get_students_by_apostolic()
    {
        $this->db->select('apostolic_category.name as apostolic_name, COUNT(student.id) as count');
        $this->db->from('apostolic_category');
        $this->db->join('student', 'student.apostolic_id = apostolic_category.id', 'left');
        $this->db->group_by('apostolic_category.id');
        return $this->db->get()->result();
    }
    /*
    public function get_students_by_apostolic()
    {
        $this->db->select('apostolic_category.name as apostolic_name, COUNT(student.id) as count');
        $this->db->from('student');
        $this->db->join('apostolic_category', 'student.apostolic_id = apostolic_category.id', 'left');
        $this->db->group_by('student.apostolic_id');
        return $this->db->get()->result();
    }
*/
    public function get_students_by_choir()
    {
        $this->db->select('sub_category.name as choir_name, COUNT(student.id) as count');
        $this->db->from('sub_category');
        $this->db->join('student', 'student.choir_id = sub_category.id', 'left');
        $this->db->where('sub_category.category_id', 4);
        $this->db->group_by('sub_category.id');
        return $this->db->get()->result();
    }

    public function get_students_by_curriculum()
    {
        $this->db->select('sub_category.name as curriculum_name, COUNT(student.id) as count');
        $this->db->from('sub_category');
        $this->db->join('student', 'student.curriculum_id = sub_category.id', 'left');
        $this->db->where('sub_category.category_id', 2);
        $this->db->group_by('sub_category.id');
        return $this->db->get()->result();
    }

    public function get_students_by_department()
    {
        $this->db->select('sub_category.name as department_name, COUNT(student.id) as count');
        $this->db->from('sub_category');
        $this->db->join('student', 'student.department_id = sub_category.id', 'left');
        $this->db->where('sub_category.category_id', 3);
        $this->db->group_by('sub_category.id');
        return $this->db->get()->result();
    }

    public function get_students_by_occupation()
    {
        // Get distinct occupation values
        $occupations = $this->db->distinct()->select('occupation')->get('student')->result_array();

        $result = [];
        foreach ($occupations as $occ) {
            if (!empty($occ['occupation'])) {
                $this->db->where('occupation', $occ['occupation']);
                $count = $this->db->count_all_results('student');

                $result[] = [
                    'occupation' => $occ['occupation'],
                    'count' => $count
                ];
            }
        }
        return $result;
    }

    public function get_students_by_education_level()
    {
        // Get distinct education_level values
        $education_levels = $this->db->distinct()->select('education_level')->get('student')->result_array();

        $result = [];
        foreach ($education_levels as $edu) {
            if (!empty($edu['education_level'])) {
                $this->db->where('education_level', $edu['education_level']);
                $count = $this->db->count_all_results('student');

                $result[] = [
                    'education_level' => $edu['education_level'],
                    'count' => $count
                ];
            }
        }
        return $result;
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_model extends CI_Model
{

    public function get_student($student_id)
    {
        return $this->db->get_where('student', ['id' => $student_id])->row();
    }


    public function get_student_with_categories($student_id)
    {
        $this->db->select('student.*');
        $this->db->from('student');
        $this->db->where('student.student_id', $student_id);
        $query = $this->db->get();
        return $query->row();
    }


    public function get_schedules_by_categories($categories, $day)
    {
        // First get all schedule_ids that match any of the student's categories
        $this->db->select('schedule_id');
        $this->db->from('sub_category_schedule');
        $this->db->group_start();

        foreach ($categories as $column => $value) {
            if (!empty($value)) {
                $this->db->or_where('sub_category_id', $value);
            }
        }

        $this->db->group_end();
        $sub_query = $this->db->get_compiled_select();

        // Now get schedules that have matching sub_categories and are for today's day
        $this->db->select('schedule.*');
        $this->db->from('schedule');
        $this->db->join("($sub_query) as matching_categories", 'schedule.id = matching_categories.schedule_id');
        $this->db->where('schedule.day', $day);
        $this->db->order_by('schedule.time', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_sub_category_id_by_schedule($schedule_id)
    {
        $this->db->select('sub_category_id');
        $this->db->from('sub_category_schedule');
        $this->db->where('schedule_id', $schedule_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->sub_category_id;
        }

        return null;
    }

    public function get_students_by_subcategory($subcategory_id, $exclude_student_id = null)
    {
        $this->db->select('student_id, status, last_attendance_date');
        $this->db->from('student');
        $this->db->where('status', 1);

        $this->db->group_start();
        $this->db->or_where('age_category_id', $subcategory_id);
        $this->db->or_where('curriculum_id', $subcategory_id);
        $this->db->or_where('department_id', $subcategory_id);
        $this->db->or_where('choir_id', $subcategory_id);
        $this->db->group_end();

        if ($exclude_student_id !== null) {
            $this->db->where('student_id !=', $exclude_student_id);
        }

        return $this->db->get()->result();
    }

    public function get_schedules_by_day($day)
    {
        return $this->db->where('day', $day)->order_by('time', 'asc')
            ->get('schedule')
            ->result();
    }

    public function get_subcategories_by_schedule($schedule_id)
    {
        $result = $this->db->select('sub_category_id')
            ->where('schedule_id', $schedule_id)
            ->get('sub_category_schedule')
            ->result();

        return array_column($result, 'sub_category_id'); // returns array of IDs
    }


    public function get_students_to_mark_absent($sub_category_ids, $date, $schedule_time)
    {
        $limit_time = $schedule_time + (65 * 60); // 1 hour and 5 minutes
        $limit_time_str = date('H:i:s', $limit_time);

        $this->db->select('id, student_id, last_attendance_date, last_attendance_time')
            ->from('student')
            ->where('status', 1)
            ->group_start()
            ->or_where_in('age_category_id', $sub_category_ids)
            ->or_where_in('curriculum_id', $sub_category_ids)
            ->or_where_in('department_id', $sub_category_ids)
            ->or_where_in('choir_id', $sub_category_ids)
            ->group_end()
            ->group_start()
            ->where('last_attendance_date !=', $date) // never attended today
            ->or_group_start()
            ->where('last_attendance_date', $date)
            ->where('last_attendance_time <', $limit_time_str) // attended, but not for this schedule
            ->group_end()
            ->group_end();

        return $this->db->get()->result();
    }


    public function record_attendance($student_id, $status, $date, $time)
    {
        // Update last attendance date and time in students table
        $update_data = [
            'last_attendance_date' => $date,
            'last_attendance_time' => $time
        ];
        $this->db->where('student_id', $student_id);
        $this->db->update('student', $update_data);

        // Insert attendance record
        $data = [
            'student_id' => $student_id,
            'status' => $status,
            'created_date' => $date,
            'created_time' => $time
        ];
        $this->db->insert('attendance', $data);
        return $this->db->insert_id();
    }


    public function get_schedules_for_day($section_id, $day)
    {
        $this->db->select('schedule.time');
        $this->db->from('section_schedule');
        $this->db->join('schedule', 'schedule.id = section_schedule.schedule_id');
        $this->db->where('section_schedule.section_id', $section_id);
        $this->db->where('schedule.day', $day);
        return $this->db->get()->result();
    }

    // Get all sections with schedules for the current day
    public function get_sections_with_schedules_for_day($current_day)
    {
        $this->db->distinct();
        $this->db->select('section_schedule.section_id, schedule.time');
        $this->db->from('schedule');
        $this->db->join('section_schedule', 'schedule.id = section_schedule.schedule_id');
        $this->db->where('schedule.day', $current_day);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_absent_students($section_id, $current_date)
    {
        $this->db->select('id');
        $this->db->from('student');
        $this->db->where('section_id', $section_id);
        $this->db->where('last_attendance_date !=', $current_date);
        $query = $this->db->get();
        return $query->result();
    }

    // Get attendance summary (Total, Present, Absent)
    public function get_attendance_summary($student_id)
    {
        $this->db->select("COUNT(id) as total, 
                           SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present, 
                           SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent");
        $this->db->where('student_id', $student_id);
        $query = $this->db->get('attendance');
        return $query->row_array();
    }
    /*
    // Get schedule-based attendance
    public function get_schedule_attendance($student_id)
    {
        // Day translations
        $dayTranslations = [
            "Monday"    => "ሰኞ",
            "Tuesday"   => "ማክሰኞ",
            "Wednesday" => "ረቡዕ",
            "Thursday"  => "ሐሙስ",
            "Friday"    => "አርብ",
            "Saturday"  => "ቅዳሜ",
            "Sunday"    => "እሁድ"
        ];

        // Fetch section_id for the student
        $this->db->select('section_id');
        $this->db->where('id', $student_id);
        $student = $this->db->get('student')->row_array();

        if (!$student) return [];

        // Fetch schedule days for the section
        $this->db->select('schedule.day');
        $this->db->from('schedule');
        $this->db->join('section_schedule', 'section_schedule.schedule_id = schedule.id');
        $this->db->where('section_schedule.section_id', $student['section_id']);
        $query = $this->db->get();
        $schedules = $query->result_array();

        // Map attendance to schedule days
        $attendanceData = [];
        foreach ($schedules as $schedule) {
            $day = $schedule['day'];

            // Get attendance records for this day
            $this->db->select("SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present, 
                               SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent");
            $this->db->where('student_id', $student_id);
            $this->db->where("DAYNAME(created_date)", $day);
            $dayQuery = $this->db->get('attendance')->row_array();

            // Translate day to Amharic
            $translatedDay = $dayTranslations[$day] ?? $day;

            // Store translated attendance data
            $attendanceData[$translatedDay] = [
                'present' => $dayQuery['present'] ?? 0,
                'absent' => $dayQuery['absent'] ?? 0
            ];
        }

        return $attendanceData;
    }
*/

    public function get_attendances($date)
    {
        $this->db->select('
        a.*, 
        student.*, 
        age_category.name AS age_category_name,
        curriculum.name AS curriculum_name,
        department.name AS department_name,
        choir.name AS choir_name,
        CASE 
            WHEN a.status = "present" THEN "ተገኝቷል"
            WHEN a.status = "absent" THEN "ቀሪ"
        END AS status_text,
        a.created_date as attendance_date
    ', false);
        $this->db->from('attendance a');
        $this->db->join('student', 'a.student_id = student.id');
        $this->db->join('sub_category AS age_category', 'age_category.id = student.age_category_id', 'left');
        $this->db->join('sub_category AS curriculum', 'curriculum.id = student.curriculum_id', 'left');
        $this->db->join('sub_category AS department', 'department.id = student.department_id', 'left');
        $this->db->join('sub_category AS choir', 'choir.id = student.choir_id', 'left');
        $this->db->where('a.created_date', $date); // Use DATE() function to match date part only
        $this->db->order_by('a.created_date', 'DESC');

        return $this->db->get()->result_array();
    }
    /*
    // fetching the attendance for listing
    public function get_attendances($date)
    {
        $this->db->select('
        a.*, student.*, 
        age_category.name AS age_category_name,
        curriculum.name AS curriculum_name,
        department.name AS department_name,
        choir.name AS choir_name,
    CASE 
        WHEN a.status = "present" THEN "ተገኝቷል"
        WHEN a.status = "absent" THEN "ቀሪ"
    END AS status_text
', false);
        $this->db->from('attendance a');
        $this->db->join('student', 'a.student_id = student.id');
        $this->db->join('sub_category AS age_category', 'age_category.id = student.age_category_id', 'left');
        $this->db->join('sub_category AS curriculum', 'curriculum.id = student.curriculum_id', 'left');
        $this->db->join('sub_category AS department', 'department.id = student.department_id', 'left');
        $this->db->join('sub_category AS choir', 'choir.id = student.choir_id', 'left');
        $this->db->where('a.created_date', $date);

        $query = $this->db->get();
        return $query->result_array();
    }

*/
    public function delete_attendance($id)
    {
        $this->db->delete('attendance', ['id' => $id]);
    }

    public function update_attendance_status($id, $status)
    {
        $this->db->set('status', $status)
            ->where('id', $id)
            ->update('attendance');

        return $this->db->affected_rows() > 0;
    }

    public function get_attendance_status($id)
    {
        return $this->db->select('status')
            ->from('attendance')
            ->where('id', $id)
            ->get()
            ->row()->status;
    }
}

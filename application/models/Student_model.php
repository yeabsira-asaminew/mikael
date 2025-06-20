<?php
class Student_model extends CI_Model
{
    // fetchs the student data without the names of the foreign keys - just id numbers of each
    public function get_student($id)
    {
        return $this->db->get_where('student', ['id' => $id])->row();
    }

    // Save personal information and return student ID
    public function save_personal_info($data)
    {
        $this->db->insert('student', $data);
        // Get the auto-increment ID
        $auto_increment_id = $this->db->insert_id();

        if ($auto_increment_id > 0) {
            // Format the student ID with MKAH prefix
            $student_id = 'MKAH' . str_pad($auto_increment_id, 4, '0', STR_PAD_LEFT);

            // Update the student_id column with senbet's code in it
            $this->db->where('id', $auto_increment_id);
            $this->db->update('student', ['student_id' => $student_id]);

            // Return auto-incremented ID (not the formatted student_id)
            return $auto_increment_id;
        }

        return false;
    }

    public function update_age_category($student_id, $age_category_id)
    {
        $this->db->where('id', $student_id);
        return $this->db->update('student', ['age_category_id' => $age_category_id]);
    }

    public function save_academic_info($student_id, $data)
    {
        $this->db->where('id', $student_id);
        return $this->db->update('student', $data);
    }

    public function student_exists($student_id)
    {
        $this->db->where('id', $student_id);
        $query = $this->db->get('student');
        return $query->num_rows() > 0;
    }
    // gets occupations which are enum value in the column
    public function get_occupations()
    {
        $query = $this->db->query("SHOW COLUMNS FROM student LIKE 'occupation'");
        $row = $query->row();

        if ($row) {
            preg_match("/^enum\(\'(.*)\'\)$/", $row->Type, $matches);
            if (isset($matches[1])) {
                return explode("','", $matches[1]);
            }
        }
        return [];
    }
    // gets education levels which are enum value in the column
    public function get_education_levels()
    {
        $query = $this->db->query("SHOW COLUMNS FROM student LIKE 'education_level'");
        $row = $query->row();

        if ($row) {
            preg_match("/^enum\(\'(.*)\'\)$/", $row->Type, $matches);
            if (isset($matches[1])) {
                return explode("','", $matches[1]);
            }
        }
        return [];
    }

    public function get_age_categories()
    {
        // Assuming 'age_category_id' in category table is known and constant (e.g. 3)
        $age_category_main_id = 1;

        $this->db->select('id, name');
        $this->db->from('sub_category');
        $this->db->where('category_id', $age_category_main_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result(); // returns an array of objects: ->id, ->name
        }

        return [];
    }


    // updates/ insertes qr code the generated for the student
    public function update_qr_code($student_id, $qr_code)
    {
        $this->db->where('id', $student_id);
        return $this->db->update('student', ['qr_code' => $qr_code]);
    }

    public function update_student($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('student', $data);
    }


    // only active=1 students are fetched for active students list
    public function get_students($search = '', $sort_by = 'id', $sort_order = 'asc', $per_page = 10, $page = 0)
    {
        $this->db->select('
        student.id,
        student.student_id,
        student.fname, 
        student.mname, 
        student.lname, 
        CASE 
            WHEN student.sex = "Male" THEN "ወንድ"
            WHEN student.sex = "Female" THEN "ሴት"
        END AS sex_amharic,
        apostolic_category.name AS apostolic_name, 
        age_category.name AS age_category_name,
        curriculum.name AS curriculum_name,
        department.name AS department_name,
        choir.name AS choir_name
    ', false);

        $this->db->from('student');

        // Join for apostolic category
        $this->db->join('apostolic_category', 'apostolic_category.id = student.apostolic_id', 'left');

        // Join for age category (from sub_category table)
        $this->db->join('sub_category AS age_category', 'age_category.id = student.age_category_id', 'left');

        $this->db->join('sub_category AS curriculum', 'curriculum.id = student.curriculum_id', 'left');

        $this->db->join('sub_category AS department', 'department.id = student.department_id', 'left');

        // Join for choir (from sub_category table)
        $this->db->join('sub_category AS choir', 'choir.id = student.choir_id', 'left');
        $this->db->where('student.status', '1');

        if ($search) {
            $this->db->group_start();
            $this->db->like('student.fname', $search);
            $this->db->or_like('student.mname', $search);
            $this->db->or_like('student.lname', $search);
            $this->db->or_like('apostolic_category.name', $search);
            $this->db->or_like('age_category.name', $search);
            $this->db->or_like('curriculum.name', $search);
            $this->db->or_like('department.name', $search);
            $this->db->or_like('choir.name', $search);
            $this->db->group_end();
        }

        $this->db->order_by('student.' . $sort_by, $sort_order);
        $this->db->limit($per_page, $page);

        return $this->db->get()->result_array();
    }


    public function get_students_count($search = '')
    {
        $this->db->from('student');
        $this->db->join('apostolic_category', 'apostolic_category.id = student.apostolic_id', 'left');
        $this->db->join('sub_category AS age_category', 'age_category.id = student.age_category_id', 'left');
        $this->db->join('sub_category AS curriculum', 'curriculum.id = student.curriculum_id', 'left');
        $this->db->join('sub_category AS department', 'department.id = student.department_id', 'left');
        $this->db->join('sub_category AS choir', 'choir.id = student.choir_id', 'left');
        $this->db->where('student.status', '1');

        if ($search) {
            $this->db->group_start();
            $this->db->like('student.fname', $search);
            $this->db->or_like('student.mname', $search);
            $this->db->or_like('student.lname', $search);
            $this->db->or_like('apostolic_category.name', $search);
            $this->db->or_like('age_category.name', $search);
            $this->db->or_like('curriculum.name', $search);
            $this->db->or_like('department.name', $search);
            $this->db->or_like('choir.name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }


    // only inactive=0 students are fetched for inactive students list
    public function get_inactive_students($search = '', $sort_by = 'id', $sort_order = 'asc', $per_page = 10, $page = 0)
    {
        $this->db->select('
        student.id,
        student.student_id,
        student.fname, 
        student.mname, 
        student.lname, 
        CASE 
            WHEN student.sex = "Male" THEN "ወንድ"
            WHEN student.sex = "Female" THEN "ሴት"
        END AS sex_amharic,
        apostolic_category.name AS apostolic_name, 
        age_category.name AS age_category_name,
        curriculum.name AS curriculum_name,
        department.name AS department_name,
        choir.name AS choir_name
    ', false);

        $this->db->from('student');

        $this->db->join('apostolic_category', 'apostolic_category.id = student.apostolic_id', 'left');
        $this->db->join('sub_category AS age_category', 'age_category.id = student.age_category_id', 'left');
        $this->db->join('sub_category AS curriculum', 'curriculum.id = student.curriculum_id', 'left');
        $this->db->join('sub_category AS department', 'department.id = student.department_id', 'left');
        $this->db->join('sub_category AS choir', 'choir.id = student.choir_id', 'left');
        $this->db->where('student.status', '0');

        if ($search) {
            $this->db->group_start();
            $this->db->like('student.fname', $search);
            $this->db->or_like('student.mname', $search);
            $this->db->or_like('student.lname', $search);
            $this->db->or_like('apostolic_category.name', $search);
            $this->db->or_like('age_category.name', $search);
            $this->db->or_like('curriculum.name', $search);
            $this->db->or_like('department.name', $search);
            $this->db->or_like('choir.name', $search);
            $this->db->group_end();
        }

        $this->db->order_by('student.' . $sort_by, $sort_order);
        $this->db->limit($per_page, $page);

        return $this->db->get()->result_array();
    }

    public function get_inactive_students_count($search = '')
    {
        $this->db->from('student');

        $this->db->join('apostolic_category', 'apostolic_category.id = student.apostolic_id', 'left');
        $this->db->join('sub_category AS age_category', 'age_category.id = student.age_category_id', 'left');
        $this->db->join('sub_category AS curriculum', 'curriculum.id = student.curriculum_id', 'left');
        $this->db->join('sub_category AS department', 'department.id = student.department_id', 'left');
        $this->db->join('sub_category AS choir', 'choir.id = student.choir_id', 'left');
        $this->db->where('student.status', '0');

        if ($search) {
            $this->db->group_start();
            $this->db->like('student.fname', $search);
            $this->db->or_like('student.mname', $search);
            $this->db->or_like('student.lname', $search);
            $this->db->or_like('apostolic_category.name', $search);
            $this->db->or_like('age_category.name', $search);
            $this->db->or_like('curriculum.name', $search);
            $this->db->or_like('department.name', $search);
            $this->db->or_like('choir.name', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // export data of the students as excel file
    public function get_all_students_with_sub_category_names()
    {
        // Use $this->db->select() with raw SQL for the CASE statements
        $this->db->select('
        student.*,
        apostolic_category.name AS apostolic_name, 
        age_category.name AS age_category_name,
        curriculum.name AS curriculum_name,
        department.name AS department_name,
        choir.name AS choir_name,
        CASE 
            WHEN student.status = 1 THEN "ንቁ"
            WHEN student.status = 0 THEN "ንቁ ያልሆነ"
        END as status_text,
        CASE 
            WHEN student.sex = "Male" THEN "ወንድ"
            WHEN student.sex = "Female" THEN "ሴት"
        END AS sex_amharic
    ', false); // The `false` parameter prevents CodeIgniter from escaping the CASE statements

        $this->db->from('student');
        $this->db->join('apostolic_category', 'apostolic_category.id = student.apostolic_id', 'left');
        $this->db->join('sub_category AS age_category', 'age_category.id = student.age_category_id', 'left');
        $this->db->join('sub_category AS curriculum', 'curriculum.id = student.curriculum_id', 'left');
        $this->db->join('sub_category AS department', 'department.id = student.department_id', 'left');
        $this->db->join('sub_category AS choir', 'choir.id = student.choir_id', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }

    // import excel
    public function import_employee($data)
    {
        $this->db->insert_batch('student', $data);
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function deactivate_student($id, $active)
    {
        $this->db->set([
            'status' => $active,
        ])
            ->where('id', $id)
            ->update('student');

        return $this->db->affected_rows() > 0;
    }

    public function activate_student($id, $active)
    {
        $this->db->set('status', $active)
            ->where('id', $id)
            ->update('student');

        return $this->db->affected_rows() > 0;
    }

    // for viewing student's details
    public function get_student_by_id($id)
    {
        $this->db->select('
        student.*, 
        student.student_id as stud_id,
        CASE 
            WHEN student.sex = "Male" THEN "ወንድ"
            WHEN student.sex = "Female" THEN "ሴት"
        END AS sex_amharic,
        apostolic_category.name AS apostolic_name, 
        age_category.name AS age_category_name,
        curriculum.name AS curriculum_name,
        department.name AS department_name,
        choir.name AS choir_name,
        schedule.day AS schedule_day,
        TIME_FORMAT(schedule.time, "%h:%i %p") AS schedule_time
    ', false);

        $this->db->from('student');
        $this->db->join('apostolic_category', 'apostolic_category.id = student.apostolic_id', 'left');
        $this->db->join('sub_category AS age_category', 'age_category.id = student.age_category_id', 'left');
        $this->db->join('sub_category AS curriculum', 'curriculum.id = student.curriculum_id', 'left');
        $this->db->join('sub_category AS department', 'department.id = student.department_id', 'left');
        $this->db->join('sub_category AS choir', 'choir.id = student.choir_id', 'left');
        $this->db->join('sub_category_schedule', 'sub_category_schedule.sub_category_id = choir.id', 'left'); // Join with choir schedule
        $this->db->join('schedule', 'schedule.id = sub_category_schedule.schedule_id', 'left');
        $this->db->where('student.id', $id);

        $query = $this->db->get();
        $student = $query->row_array();

        if ($student) {
            // Calculate age
            if (!empty($student['dob'])) {
                $dob = new DateTime($student['dob']);
                $now = new DateTime();
                $student['age'] = $now->diff($dob)->y;
            } else {
                $student['age'] = 'N/A';
            }

            // Day translation to Amharic
            $dayTranslations = [
                "Monday"    => "ሰኞ",
                "Tuesday"   => "ማክሰኞ",
                "Wednesday" => "ረቡዕ",
                "Thursday"  => "ሐሙስ",
                "Friday"    => "አርብ",
                "Saturday"  => "ቅዳሜ",
                "Sunday"    => "እሁድ"
            ];

            // Create schedule_datetime string
            if (!empty($student['schedule_day']) && !empty($student['schedule_time'])) {
                $amharicDay = $dayTranslations[$student['schedule_day']] ?? $student['schedule_day'];
                $student['schedule_datetime'] = $amharicDay . ' ' . $student['schedule_time'];
            } else {
                $student['schedule_datetime'] = 'ምንም የመርሀ ግብር የለም'; // "No schedule" in Amharic
            }

            return $student;
        }

        return false;
    }

    public function delete_student($id)
    {
        // Delete attendances associated with the student
        $this->db->where('student_id', $id);
        $this->db->delete('attendance');

        // Delete the student record
        $this->db->where('student_id', $id);
        return $this->db->delete('student');
    }

    //attendance record by listing students
    public function get_students_by_filters($filters = [])
    {
        $this->db->select('
            student.*,
             age_category.name AS age_category_name,
        curriculum.name AS curriculum_name,
        department.name AS department_name,
        choir.name AS choir_name,
        ');
        $this->db->from('student');

        // Join with subcategory tables
        $this->db->join('sub_category AS age_category', 'age_category.id = student.age_category_id', 'left');
        $this->db->join('sub_category AS curriculum', 'curriculum.id = student.curriculum_id', 'left');
        $this->db->join('sub_category AS department', 'department.id = student.department_id', 'left');
        $this->db->join('sub_category AS choir', 'choir.id = student.choir_id', 'left');

        // Apply filters
        if (!empty($filters['age_category_id'])) {
            $this->db->where('student.age_category_id', $filters['age_category_id']);
        }
        if (!empty($filters['curriculum_id'])) {
            $this->db->where('student.curriculum_id', $filters['curriculum_id']);
        }
        if (!empty($filters['department_id'])) {
            $this->db->where('student.department_id', $filters['department_id']);
        }
        if (!empty($filters['choir_id'])) {
            $this->db->where('student.choir_id', $filters['choir_id']);
        }

        $this->db->order_by('student.student_id', 'ASC');
        return $this->db->get()->result();
    }
}

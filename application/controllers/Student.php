<?php
defined('BASEPATH') or exit('No direct script access allowed');

require FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Andegna\DateTime as EthiopianDateTime;
use Andegna\DateTimeFactory;
use Andegna\Constants;
use Andegna\Exception\InvalidDateException;

class Student extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('uid')) {
            redirect('login');
        }
        require_once(APPPATH . 'third_party/phpqrcode/qrlib.php');
        $this->load->model('Student_model', 'Student');
        $this->load->model('Academic_model');
        $this->load->model('Attendance_model');
        $this->load->model('Category_model', 'category');
    }
    public function add_multiple()
    {
        $this->load->view('admin/add_multiple_students');
    }
    // First step - personal information
    public function add_personal_info()
    {
        $this->load->view('admin/add_student_personal');
    }

    public function save_personal_info()
    {
        // Set validation rules
        $this->form_validation->set_rules('fname', 'First Name', 'required');
        $this->form_validation->set_rules('mname', 'Middle Name', 'required');
        $this->form_validation->set_rules('lname', 'Last Name', 'required');
        $this->form_validation->set_rules('mother_name', 'Mother Name', 'required');
        $this->form_validation->set_rules('sex', 'Sex', 'required');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'required');
        $this->form_validation->set_rules('pob', 'Place of Birth', 'required');
        $this->form_validation->set_rules('christian_name', 'Christian Name', 'required');
        $this->form_validation->set_rules('God_father', 'God Father');
        $this->form_validation->set_rules('repentance_father', 'Repentance Father');
        $this->form_validation->set_rules('repentance_father_church', 'Repentance Father Church');
        $this->form_validation->set_rules('phone1', 'Phone 1', 'required');
        $this->form_validation->set_rules('phone2', 'Phone 2');
        $this->form_validation->set_rules('address', 'Address');

        if ($this->form_validation->run() === FALSE) {
            // Validation failed
            $this->session->set_flashdata('student_message', ['type' => 'error', 'text' => 'ፎርም ላይ የተሞሉ መረጃዎች ላይ ስህተት አለ ፣ እባክዎ አስተካክለው ይሞክሩ!']);
            $this->load->view('admin/add_student_personal');
            return;
        }

        // Convert Ethiopian dates to Gregorian
        try {
            $dob_eth = $this->input->post('dob');
            $dob_greg = Andegna\DateTimeFactory::of(
                (int)explode('/', $dob_eth)[2],
                (int)explode('/', $dob_eth)[1],
                (int)explode('/', $dob_eth)[0]
            )->toGregorian()->format('Y-m-d');
        } catch (\Andegna\Exception\InvalidDateException $e) {
            $this->session->set_flashdata('error', 'ያስገቡት ቀን ልክ አይደለም። እባክዎ ያስተካክሉ!');
            redirect('student/add_personal_info');
            return;
        }

        // Student photo
        $photo = null; // Set default as null

        if (!empty($_FILES['photo']['name'])) {
            $this->load->library('upload');
            $config['upload_path'] = 'uploads/photos/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 5124; // 5MB max size
            $config['file_name'] = 'Stud-' . date('YmdHms') . '-' . rand(1, 999999);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('photo')) {
                $uploaded = $this->upload->data();
                $photo = $uploaded['file_name']; // Save the uploaded file name
            }
        }

        // Prepare data
        $personal_data = array(
            'fname' => $this->input->post('fname'),
            'mname' => $this->input->post('mname'),
            'lname' => $this->input->post('lname'),
            'mother_name' => $this->input->post('mother_name'),
            'sex' => $this->input->post('sex'),
            'dob' => $dob_greg,
            'pob' => $this->input->post('pob'),
            'christian_name' => $this->input->post('christian_name'),
            'God_father' => $this->input->post('God_father'),
            'repentance_father' => $this->input->post('repentance_father'),
            'repentance_father_church' => $this->input->post('repentance_father_church'),
            'phone1' => $this->input->post('phone1'),
            'phone2' => $this->input->post('phone2'),
            'address' => $this->input->post('address'),
            'photo' => $photo
        );

        // Save to database
        $student_id = $this->Student->save_personal_info($personal_data);

        if ($student_id !== false && !empty($student_id)) {
            $this->session->set_flashdata('student_message', ['type' => 'success', 'text' => 'የተማሪው ግላዊ መረጃ በተሳካ ሁኔታ ተመዝቧል!']);
            redirect('student/add_academic_info/' . $student_id);
        } else {
            $this->session->set_flashdata('student_message', ['type' => 'error', 'text' => 'የተማሪውን ግላዊ መረጃ መመዝገብ አልተቻለም። እባክዎ በድጋሚ ይሞክሩ!']);
            redirect('student/add_personal_info');
        }
    }

    // Second step - academic information
    public function add_academic_info($student_id)
    {
        // Get student data
        $student_data = $this->Student->get_student($student_id);

        // Verify student exists
        if (!$this->Student->student_exists($student_id)) {
            show_404();
        }
        // Calculate age from DOB
        $dob = new DateTime($student_data->dob);
        $now = new DateTime();
        $age = $now->diff($dob)->y;

        // Determine age category
        $age_category_id = $this->determine_age_category($age);

        // Update student's age category if needed
        if ($student_data->age_category_id != $age_category_id) {
            $this->Student->update_age_category($student_id, $age_category_id);
            $student_data->age_category_id = $age_category_id;
        }

        // Get categories based on age
        $data = $this->get_categories_based_on_age($age_category_id);

        $data['student'] = $student_data;
        $data['age'] = $age;
        $data['age_category_id'] = $age_category_id;

        $data['student_id'] = $student_id;
        $data['occupations'] = $this->Student->get_occupations();
        $data['education_levels'] = $this->Student->get_education_levels();


        $this->load->view('admin/add_student_academic', $data);
    }

    private function determine_age_category($age)
    {
        if ($age < 7) return 1;
        if ($age < 11) return 2;
        if ($age < 16) return 3;
        if ($age < 19) return 4;
        if ($age < 35) return 5;
        return 6;
    }

    private function get_categories_based_on_age($age_category_id)
    {
        $data = [];

        // Show/Hide apostolic and service department forms
        $data['show_apostolic'] = ($age_category_id == 5);
        $data['show_service_dept'] = ($age_category_id == 5);

        // Get sub categories based on age category
        if ($age_category_id == 5) {
            // Only show sub category 7 for age category 5
            $data['curriculums'] = $this->category->get_sub_categories_by_ids([7]);
        } elseif (in_array($age_category_id, [1, 2, 3, 4])) {
            // Show sub categories 8-19 for age categories 1-4
            $ids = range(8, 19);
            $data['curriculums'] = $this->category->get_sub_categories_by_ids($ids);
        }

        // Map age categories to choir sub_category IDs
        $choir_mapping = [
            2 => 29,  // Age category 1 → sub_category 30
            3 => 30,  // Age category 2 → sub_category 31
            4 => 31,  // Age category 3 → sub_category 32
            5 => 32,  // Age category 4 → sub_category 33
            6 => 33  // Age category 5 → sub_category 34
        ];

        // Handle choir options
        if ($age_category_id == 1) {
            // Explicitly show "የለም" for age category 1
            $data['choirs'] = [(object)['id' => '', 'name' => 'የለም']];
        } elseif (isset($choir_mapping[$age_category_id])) {
            $choir_sub_category_id = $choir_mapping[$age_category_id];
            $data['choirs'] = $this->category->get_sub_categories_by_ids([$choir_sub_category_id])
                ?: [(object)['id' => '', 'name' => 'የለም']];
        } else {
            $data['choirs'] = [(object)['id' => '', 'name' => 'የለም']];
        }

        // Get other categories needed for the forms
        $data['apostolic_categories'] = $this->category->get_apostolic_categories();
        // $data['curriculums'] = $this->category->get_curriculums();
        $data['departments'] = $this->category->get_departments();
        // $data['choirs'] = $this->category->get_choirs();

        return $data;
    }

    public function save_academic_info($student_id)
    {
        // Verify student exists
        if (!$this->Student->student_exists($student_id)) {
            show_404();
        }

        // Set validation rules
        $this->form_validation->set_rules('apostolic_id', 'Apostolic Category');
        $this->form_validation->set_rules('curriculum_id', 'Curriculum');
        $this->form_validation->set_rules('department_id', 'Department');
        $this->form_validation->set_rules('choir_id', 'Choir');
        $this->form_validation->set_rules('occupation', 'Occupation');
        $this->form_validation->set_rules('education_level', 'Education Level', 'required');
        $this->form_validation->set_rules('academic_field', 'Academic Field');
        $this->form_validation->set_rules('workplace', 'Workplace');
        $this->form_validation->set_rules('registration_date', 'Registration Date', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->add_academic_info($student_id);
            return;
        }

        // Convert Ethiopian date to Gregorian for registration date
        try {
            $reg_date_eth = $this->input->post('registration_date');
            $reg_date_greg = Andegna\DateTimeFactory::of(
                (int)explode('/', $reg_date_eth)[2],
                (int)explode('/', $reg_date_eth)[1],
                (int)explode('/', $reg_date_eth)[0]
            )->toGregorian()->format('Y-m-d');
        } catch (\Andegna\Exception\InvalidDateException $e) {
            $this->session->set_flashdata('error', 'ያስገቡት ቀን ልክ አይደለም። እባክዎ ያስተካክሉ!');
            $this->add_academic_info($student_id);
            return;
        }

        // Prepare data
        $academic_data = array(
            'apostolic_id' => $this->input->post('apostolic_id'),
            'curriculum_id' => $this->input->post('curriculum_id'),
            'department_id' => $this->input->post('department_id'),
            'choir_id' => $this->input->post('choir_id'),
            'occupation' => $this->input->post('occupation'),
            'education_level' => $this->input->post('education_level'),
            'academic_field' => $this->input->post('academic_field'),
            'workplace' => $this->input->post('workplace'),
            'registration_date' => $reg_date_greg // Using converted Gregorian date
        );

        // Save to database
        if ($this->Student->save_academic_info($student_id, $academic_data)) {

            // generates qr code for the student automatically
            $this->generate_qr($student_id);
            $this->session->set_flashdata('student_message', ['type' => 'success', 'text' => 'የተማሪው መረጃ በተሳካ ሁኔታ ተመዝግቧል!']);
            redirect('student/list');
        } else {
            $this->session->set_flashdata('student_message', ['type' => 'error', 'text' => 'የተማሪውን ትምህርት ነክ መረጃ መመዝገብ አልተቻለም። እባክዎ በድጋሚ ይሞክሩ!']);
            redirect('student/add_academic_info/' . $student_id);
        }
    }
    // this is used in student view page
    public function generate_new_qr($student_id)
    {
        $this->generate_qr($student_id);
        redirect('student/view/' . $student_id);
    }

    // Generate QR code for a student
    private function generate_qr($student_id)
    {
        if ($this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }

        $student = $this->Student->get_student_by_id($student_id);

        if (!$student) {
            echo "ተማሪው የመረጃ ቋት ውስጥ አልተገኘም!";
            return; // Exit if student not found
        }

        // Ensure the uploads directory exists
        $upload_dir = FCPATH . 'uploads/qr_codes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate unique QR content
        $student_id = $student['student_id'];
        $encrypted_id = encrypt_id($student_id); // Encrypt the ID

        // Generate unique file name
        $file_name = 'Stud-' . $student_id . '-QR-' . date('YmdHis') . '-' . rand(10000, 99999) . '.png';
        $file_path = $upload_dir . $file_name;

        // Delete old QR code file (if it exists)
        if (!empty($student['qr_code'])) {
            $old_qr_path = $upload_dir . $student['qr_code'];
            if (file_exists($old_qr_path)) {
                unlink($old_qr_path);
            }
        }

        // Generate QR code
        QRcode::png($encrypted_id, $file_path, QR_ECLEVEL_L, 10);

        // Ensure the file is actually created
        if (!file_exists($file_path)) {
            $this->session->set_flashdata('qr_message', [
                'type' => 'error',
                'text' => 'QR Code መፍጠር አልተቻለም። እባክዎ በድጋሚ ይሞክሩ!'
            ]);
            redirect('student/list');
            return;
        }

        $this->Student->update_qr_code($student['id'], $file_name);
    }

    public function list()
    {
        $search = $this->input->get('search');
        $sort_by = $this->input->get('sort_by') ?: 'id';
        $sort_order = $this->input->get('sort_order') ?: 'asc';
        $limit = $this->input->get('limit') ?: 10; // Default to 10 rows per page
        $offset = $this->input->get('offset') ?: 0;

        // Calculate the current page
        $page = ($offset / $limit) + 1;

        $data['students'] = $this->Student->get_students($search, $sort_by, $sort_order, $limit, $offset);
        $data['total_rows'] = $this->Student->get_students_count($search);
        $data['search'] = $search;
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        $data['page'] = $page;

        // Pass the current limit value to the view
        $data['per_page'] = $limit;

        $this->load->view('admin/list-student', $data);
    }

    // list deactivated employees
    public function list_inactive()
    {

        if ($this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }

        $search = $this->input->get('search');
        $sort_by = $this->input->get('sort_by') ?: 'id';
        $sort_order = $this->input->get('sort_order') ?: 'asc';
        $limit = $this->input->get('limit') ?: 10; // Default to 10 rows per page
        $offset = $this->input->get('offset') ?: 0;

        // Calculate the current page
        $page = ($offset / $limit) + 1;

        $data['students'] = $this->Student->get_inactive_students($search, $sort_by, $sort_order, $limit, $offset);
        $data['total_rows'] = $this->Student->get_inactive_students_count($search);
        $data['search'] = $search;
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        $data['page'] = $page;

        // Pass the current limit value to the view
        $data['per_page'] = $limit;

        $this->load->view('admin/list-inactive-student', $data);
    }

    public function view($id)
    {
        $student = $this->Student->get_student_by_id($id);

        if (!empty($student['dob'])) {
            $gregorianDate = new DateTime($student['dob']);
            $ethiopianDate = new Andegna\DateTime($gregorianDate);

            $student['dob'] = $ethiopianDate->format('F d' . '፣ Y ዓ.ም');
        } else {
            $student['dob'] = 'ዕለቱ አልተመዘገበም';
        }


        if (!empty($student['registration_date'])) {
            $gregorianDateCreated = new DateTime($student['registration_date']);
            $ethiopianDateCreated = new Andegna\DateTime($gregorianDateCreated);
            $student['registration_date'] = $ethiopianDateCreated->format('F d' . '፣ Y ዓ.ም');
        } else {
            $student['registration_date'] = 'ዕለቱ አልተመዘገበም';
        }

        $data['student'] =  $student;
        $data['student_id'] = $id;
        $data['attendance'] = $this->Attendance_model->get_attendance_summary($id);
        // $data['schedule'] = $this->Attendance_model->get_schedule_attendance($id);


        $this->load->view('admin/view-student', $data);
    }

    private function get_categories_based_on_age_for_edit($age_category_id, $current_choir_id = null)
    {
        $data = [
            'show_apostolic' => false,
            'show_service_dept' => false,
            'curriculums' => [],
            'choirs' => [],
            'apostolic_categories' => [],
            'departments' => []
        ];

        // Choir mapping based on age category
        $choir_mapping = [
            2 => 29,
            3 => 30,
            4 => 31,
            5 => 32,
            6 => 33
        ];

        // Rule 1: if age_category_id = 1 then only curriculum_id drop down option should be active but the others should be disabled.
        if ($age_category_id == 1) {
            $ids = range(8, 19); // Assuming these are the curriculum IDs for age category 1
            $data['curriculums'] = $this->category->get_sub_categories_by_ids($ids);
            $data['choirs'] = [(object)['id' => '', 'name' => 'የለም']]; // "No" option
            // apostolic and department remain disabled by default
        }
        // Rule 2 & 3: if age_category_id = 2 or 3 or 4 then curriculum_id and choir_id(with only their mapped choir option) drop down option should be active but the others should be disabled.
        // Rule 5: if age_category_id = 5 then all drop down option should be active notice that choir_id should only show 1 option with the mapped data i will provide
        elseif (in_array($age_category_id, [2, 3, 4, 5, 6])) {
            if ($age_category_id != 6) {
                $ids = range(8, 19);
                $data['curriculums'] = $this->category->get_sub_categories_by_ids($ids);
            } else {
                $data['curriculums'] = []; // Hide curriculum for age category 6
            }

            // Handle choir
            if (isset($choir_mapping[$age_category_id])) {
                $choir_sub_category_id = $choir_mapping[$age_category_id];
                $data['choirs'] = $this->category->get_sub_categories_by_ids([$choir_sub_category_id]);
                // If no specific choir found, default to "የለም"
                if (empty($data['choirs'])) {
                    $data['choirs'] = [(object)['id' => '', 'name' => 'የለም']];
                }
            } else {
                $data['choirs'] = [(object)['id' => '', 'name' => 'የለም']];
            }

            // Rule 5 specific additions: show apostolic and service dept for age_category_id = 5
            if ($age_category_id == 5) {
                $data['show_apostolic'] = true;
                $data['show_service_dept'] = true;
                $data['apostolic_categories'] = $this->category->get_apostolic_categories();
                $data['departments'] = $this->category->get_departments();
            }
        }

        // Ensure current selected choir is available if it's not in the age-mapped list
        if ($current_choir_id && !empty($data['choirs'])) {
            $found_choir = false;
            foreach ($data['choirs'] as $choir_option) {
                if ($choir_option->id == $current_choir_id) {
                    $found_choir = true;
                    break;
                }
            }
            if (!$found_choir && $current_choir_id != '') {
                $current_choir = $this->category->get_sub_category_by_id($current_choir_id);
                if ($current_choir) {
                    $data['choirs'][] = $current_choir;
                }
            }
        } elseif ($current_choir_id && empty($data['choirs']) && $current_choir_id != '') {
            $current_choir = $this->category->get_sub_category_by_id($current_choir_id);
            if ($current_choir) {
                $data['choirs'][] = $current_choir;
            }
        }


        return $data;
    }

    public function edit($id)
    {
        $student = $this->Student->get_student_by_id($id);

        if (!$student) {
            show_404();
        }

        // Convert dates to Ethiopian for display
        try {
            $gregorianDate = new DateTime($student['dob']);
            $ethiopianDate = new Andegna\DateTime($gregorianDate);
            $student['dob_eth'] = $ethiopianDate->format('j/m/Y');

            $gregorianRegDate = new DateTime($student['registration_date']);
            $ethiopianRegDate = new Andegna\DateTime($gregorianRegDate);
            $student['registration_date_eth'] = $ethiopianRegDate->format('j/m/Y');
        } catch (Exception $e) {
            log_message('error', 'Date conversion error: ' . $e->getMessage());
            $student['dob_eth'] = ''; // Fallback for invalid date
            $student['registration_date_eth'] = ''; // Fallback for invalid date
        }


        $age_categories = $this->category->get_all_age_categories();

        // Pass the current student's choir_id to ensure it's available in the dropdown
        $age_based_data = $this->get_categories_based_on_age_for_edit($student['age_category_id'], $student['choir_id']);

        $occupations = $this->Student->get_occupations();
        $education_levels = $this->Student->get_education_levels();

        $data = [
            'student' => $student,
            'age_categories' => $age_categories,
            'show_apostolic' => $age_based_data['show_apostolic'],
            'show_service_dept' => $age_based_data['show_service_dept'],
            'apostolic_categories' => $age_based_data['apostolic_categories'],
            'curriculums' => $age_based_data['curriculums'],
            'departments' => $age_based_data['departments'],
            'choirs' => $age_based_data['choirs'],
            'occupations' => $occupations,
            'education_levels' => $education_levels
        ];

        $this->load->view('admin/edit-student', $data);
    }


    public function update($id)
    {
        $student = $this->Student->get_student_by_id($id);

        if (!$student) {
            show_404();
        }

        // Set validation rules
        $this->form_validation->set_rules('fname', 'First Name', 'required');
        $this->form_validation->set_rules('mname', 'Middle Name', 'required');
        $this->form_validation->set_rules('lname', 'Last Name', 'required');
        $this->form_validation->set_rules('mother_name', 'Mother Name', 'required');
        $this->form_validation->set_rules('sex', 'Sex', 'required');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'required');
        $this->form_validation->set_rules('pob', 'Place of Birth', 'required');
        $this->form_validation->set_rules('christian_name', 'Christian Name', 'required');
        $this->form_validation->set_rules('education_level', 'Education Level', 'required');
        $this->form_validation->set_rules('registration_date', 'Registration Date', 'required');
        $this->form_validation->set_rules('age_category_id', 'Age Category', 'required');


        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            // Convert Ethiopian dates to Gregorian
            try {
                $dob_eth = $this->input->post('dob');
                $dob_greg = DateTimeFactory::of(
                    (int)explode('/', $dob_eth)[2],
                    (int)explode('/', $dob_eth)[1],
                    (int)explode('/', $dob_eth)[0]
                )->toGregorian()->format('Y-m-d');

                $reg_date_eth = $this->input->post('registration_date');
                $reg_date_greg = DateTimeFactory::of(
                    (int)explode('/', $reg_date_eth)[2],
                    (int)explode('/', $reg_date_eth)[1],
                    (int)explode('/', $reg_date_eth)[0]
                )->toGregorian()->format('Y-m-d');
            } catch (InvalidDateException $e) {
                $this->session->set_flashdata('error', 'ያስገቡት ቀን ልክ አይደለም። እባክዎ ያስተካክሉ!');
                redirect("student/edit/$id");
                return;
            }

            // Handle photo upload
            $photo = $student['photo'] ?? 'default_photo.jpg';
            if (!empty($_FILES['photo']['name'])) {
                $this->load->library('upload');
                $config['upload_path'] = 'uploads/photos/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 10000;
                $config['file_name'] = 'Stud-' . date('YmdHms') . '-' . rand(1, 999999);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('photo')) {
                    $uploaded = $this->upload->data();
                    $photo = $uploaded['file_name'];

                    // Delete old photo if it's not the default
                    if ($student['photo'] && $student['photo'] != 'default_photo.jpg') {
                        @unlink('uploads/photos/' . $student['photo']);
                    }
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect("student/edit/$id");
                    return;
                }
            }

            $age_category_id = $this->input->post('age_category_id');
            // Get age-based data to conditionally set values
            $age_based_data = $this->get_categories_based_on_age_for_edit($age_category_id);
            $this->clearInvalidCategoryFields($id, $age_category_id);

            // Prepare data for update
            $update_data = [
                'fname' => $this->input->post('fname'),
                'mname' => $this->input->post('mname'),
                'lname' => $this->input->post('lname'),
                'mother_name' => $this->input->post('mother_name'),
                'sex' => $this->input->post('sex'),
                'dob' => $dob_greg,
                'pob' => $this->input->post('pob'),
                'christian_name' => $this->input->post('christian_name'),
                'God_father' => $this->input->post('God_father'),
                'repentance_father' => $this->input->post('repentance_father'),
                'repentance_father_church' => $this->input->post('repentance_father_church'),
                'phone1' => $this->input->post('phone1'),
                'phone2' => $this->input->post('phone2'),
                'address' => $this->input->post('address'),
                'age_category_id' => $age_category_id,
                'occupation' => $this->input->post('occupation'),
                'education_level' => $this->input->post('education_level'),
                'academic_field' => $this->input->post('academic_field'),
                'workplace' => $this->input->post('workplace'),
                'registration_date' => $reg_date_greg,
                'photo' => $photo
            ];

            // Conditionally set apostolic_id, curriculum_id, department_id, and choir_id based on age category
            if ($age_based_data['show_apostolic']) {
                $update_data['apostolic_id'] = $this->input->post('apostolic_id');
            } else {
                $update_data['apostolic_id'] = null; // Set to null if not applicable
            }

            // Curriculum is always active for age_category_id 1, 2, 3, 4, 5
            // So we can directly take the post value. If curriculum is disabled in the frontend,
            // its value won't be posted, so we should check for that.
            if ($age_category_id == 1 || in_array($age_category_id, [2, 3, 4, 5])) {
                $update_data['curriculum_id'] = $this->input->post('curriculum_id');
            } else {
                $update_data['curriculum_id'] = null;
            }


            if ($age_based_data['show_service_dept']) {
                $update_data['department_id'] = $this->input->post('department_id');
            } else {
                $update_data['department_id'] = null; // Set to null if not applicable
            }

            // Choir logic is more complex as it's active for some age categories but not all
            if (in_array($age_category_id, [2, 3, 4, 5])) { // Choir is active for these
                $update_data['choir_id'] = $this->input->post('choir_id');
            } else {
                $update_data['choir_id'] = null;
            }


            // Update student record
            if ($this->Student->update_student($id, $update_data)) {
                $this->session->set_flashdata('student_message', ['type' => 'success', 'text' => 'የተማሪው መረጃ በተሳካ ሁኔታ ተስተካክሏል!']);
                redirect('student/list');
            } else {
                $this->session->set_flashdata('student_message', ['type' => 'error', 'text' => 'የተማሪው መረጃ አልተስተካከለም። እባክዎ በድጋሚ ይሞክሩ!']);
                redirect("student/edit/$id");
            }
        }
    }

    private function clearInvalidCategoryFields($student_id, $age_category_id)
    {
        $update_data = [];

        // Determine which fields to nullify based on age category
        switch ($age_category_id) {
            case 1: // For category 1
                $update_data = [
                    'apostolic_id' => null,
                    'department_id' => null,
                    'choir_id' => null
                ];
                break;

            case 2:
            case 3:
            case 4: // For categories 2-4
                $update_data = [
                    'apostolic_id' => null,
                    'department_id' => null
                ];
                break;

            case 6: // For category 6
                $update_data = [
                    'apostolic_id' => null,
                    'curriculum_id' => null,
                    'department_id' => null
                ];
                break;
        }

        // Only update if we have fields to nullify
        if (!empty($update_data)) {
            $this->Student->update_student($student_id, $update_data);
        }
    }

    // AJAX endpoint to get dependent fields when age category changes
    public function get_age_dependent_fields()
    {
        $age_category_id = $this->input->post('age_category_id');
        $data = $this->get_categories_based_on_age_for_edit($age_category_id);

        // Prepare response
        $response = [
            'show_apostolic' => $data['show_apostolic'],
            'show_service_dept' => $data['show_service_dept'],
            'curriculums' => $data['curriculums'],
            'choirs' => $data['choirs'],
            'apostolic_categories' => [], // Initialize to empty
            'departments' => [] // Initialize to empty
        ];

        // If apostolic is shown, include apostolic categories
        if ($data['show_apostolic']) {
            $response['apostolic_categories'] = $data['apostolic_categories'];
        }

        // If service dept is shown, include departments
        if ($data['show_service_dept']) {
            $response['departments'] = $data['departments'];
        }

        echo json_encode($response);
    }

    public function deactivate_student($id)
    {
        if ($this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }

        $active = '0';

        if ($this->Student->deactivate_Student($id, $active)) {
            // Success message
            $this->session->set_flashdata('student_message', ['type' => 'success', 'text' => 'የተማሪው ትምህርት በተሳካ ሁኔታ ተቋርጧል!']);
        } else {
            // Error message
            $this->session->set_flashdata('student_message', ['type' => 'error', 'text' => 'የተማሪው ትምህርት አልተቋረጠም. እባክዎ በድጋሚ ይሞክሩ!']);
        }

        redirect('student/list');
    }

    public function activate_student($id)
    {
        if ($this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }

        $active = '1';

        if ($this->Student->activate_student($id, $active)) {
            // Success message
            $this->session->set_flashdata('student_message', ['type' => 'success', 'text' => 'የተማሪው ትምህርት በተሳካ ሁኔታ ነቅቷል!']);
        } else {
            // Error message
            $this->session->set_flashdata('student_message', ['type' => 'error', 'text' => 'የተማሪው ትምህርት አልነቃም። እባክዎ በድጋሚ ይሞክሩ!']);
        }

        redirect('student/list_inactive');
    }

    public function delete($id)
    {
        if ($this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }

        // Check if the ID is valid
        if (empty($id)) {
            $this->session->set_flashdata('student_message', [
                'type' => 'error',
                'text' => 'የተማሪው መታወቂያ ቁጥር አልተገኘም!'
            ]);
            redirect('student/list_inactive');
        }

        // Attempt to delete the student
        $result = $this->Student->delete_student($id);

        if ($result) {
            // Success message
            $this->session->set_flashdata('student_message', [
                'type' => 'success',
                'text' => 'የተማሪው መረጃዎች በተሳካ ሁኔታ ተሰርዟል!'
            ]);
        } else {
            // Error message
            $this->session->set_flashdata('student_message', [
                'type' => 'error',
                'text' => 'የተማሪው መረጃዎች አልተሰረዘም. እባክዎ በድጋሚ ይሞክሩ!'
            ]);
        }

        // Redirect to the student list page
        redirect('student/list_inactive');
    }



    // Generate ID card for a specific student
    public function generate_id($student_id)
    {
        if ($this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }

        $student = $this->Student->get_student_by_id($student_id);
        $data['student'] = $student;

        $gregorianCurrentDate = new DateTime(date('Y-m-d'));
        $ethiopianCurrentDate = new Andegna\DateTime($gregorianCurrentDate);

        $gregorianRegistrationDate = new DateTime($student['registration_date']);
        $registration_date_in_ethiopian_calendar = new Andegna\DateTime($gregorianRegistrationDate);

        $data['ethiopian_current_date'] = $ethiopianCurrentDate->format('d/m/Y ዓ.ም');
        $data['registration_date_in_ethiopian_calendar'] =  $registration_date_in_ethiopian_calendar->format('d/m/Y ዓ.ም');

        // Load the view with student data and Ethiopian date
        $this->load->view('admin/id-card-modal', $data);
    }

   /*
    // excel
    // Functions for Importing and Exporting Employee Lists 
    // format of employee excel file
    public function excel_format()
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="የተማሪዎች_መመዝገቢያ_ፎርማት.xlsx"');

        ob_get_clean();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'መ.ቁ.');
        $sheet->setCellValue('B1', 'ስም');
        $sheet->setCellValue('C1', 'የአባት ስም');
        $sheet->setCellValue('D1', 'የአያት ስም');
        $sheet->setCellValue('E1', 'የእናት ስም');
        $sheet->setCellValue('F1', 'ጾታ');
        $sheet->setCellValue('G1', 'የትውልድ ዘመን');
        $sheet->setCellValue('H1', 'የትውልድ ቦታ');
        $sheet->setCellValue('I1', 'የክርስትና ስም');
        $sheet->setCellValue('J1', 'የክርስትና አባት ስም');
        $sheet->setCellValue('K1', 'የንስሃ አባት');
        $sheet->setCellValue('L1', 'የንስሃ አባት ቤተክርስቲያን');
        $sheet->setCellValue('M1', 'ስልክ ቁ. 1');
        $sheet->setCellValue('N1', 'ስልክ ቁ. 2');
        $sheet->setCellValue('O1', 'አድራሻ');
        $sheet->setCellValue('P1', 'ዕድሜ ምድብ');
        $sheet->setCellValue('Q1', 'ሐዋርያዊ ምድብ');
        $sheet->setCellValue('R1', 'ስርአተ ት/ት');
        $sheet->setCellValue('S1', 'የአገልግሎት ክፍል');
        $sheet->setCellValue('T1', 'የመዝሙር ክፍል');
        $sheet->setCellValue('U1', 'ስራ');
        $sheet->setCellValue('V1', 'የትምህርት ደረጃ');
        $sheet->setCellValue('W1', 'የትምህርት መስክ');
        $sheet->setCellValue('X1', 'የስራ ቦታ');
        $sheet->setCellValue('Y1', 'የተመዘገበበት ቀን');
        $sheet->setCellValue('Z1', 'ሁኔታ');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function import_excel()
    {
        $import_file = $_FILES['import_file']['name'];
        $extension = pathinfo($import_file, PATHINFO_EXTENSION);
        if ($extension == 'csv') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else if ($extension == 'xls') {
            $reader = new PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $reader->load($_FILES['import_file']['tmp_name']);
        $sheetdata = $spreadsheet->getActiveSheet()->toArray();
        $sheetcount = count($sheetdata);

        if ($sheetcount > 1) {
            $data = array();
            for ($i = 1; $i < $sheetcount; $i++) {

                $fname = $sheetdata[$i][1];
                $mname = $sheetdata[$i][2];
                $lname = $sheetdata[$i][3];
                $christian_name = $sheetdata[$i][4];
                $repentance_father = $sheetdata[$i][5];
                $god_father = $sheetdata[$i][6];
                $sex = $sheetdata[$i][7];
                $dob = $sheetdata[$i][8];
                $pob = $sheetdata[$i][9];
                $phone1 = $sheetdata[$i][10];
                $phone2 = $sheetdata[$i][8];
                //  $section_id = $sheetdata[$i][9];
                //  $department_id = $sheetdata[$i][10];
                $registration_date = $sheetdata[$i][9];


                $data[] = array(
                    'fname' => $fname,
                    'mname' => $mname,
                    'lname' => $lname,
                    'christian_name' => $christian_name,
                    'repentance_father' => $repentance_father,
                    'god_father' => $god_father,
                    'sex' => $sex,
                    'dob' => $dob,
                    'pob' => $pob,
                    'phone1' => $phone1,
                    'phone2' => $phone2,
                    // 'section_id' => $section_id,
                    // 'department' => $department_id,
                    'registration_date' => $registration_date
                );
            }

            $insert = $this->Student->import_employee($data);
            if ($insert) {
                $this->session->set_flashdata('student_message', ['type' => 'success', 'text' => 'የተማሪዎቹ በተሳካ ሁኔታ ተመዝግበዋል!']);
                redirect('student/list');
            } else {
                $this->session->set_flashdata('student_message', ['type' => 'error', 'text' => 'የሆነ ችግር ተፈጥሯል እባክዎ እንደገና ይሞክሩ!']);
                redirect('student/list');
            }
        }
    }
*/
    public function export_students()
    {
        if ($this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Setting the headers with all columns from the list
        $headers = [
            'A' => 'መ.ቁ.',
            'B' => 'ስም',
            'C' => 'የአባት ስም',
            'D' => 'የአያት ስም',
            'E' => 'የእናት ስም',
            'F' => 'ጾታ',
            'G' => 'የትውልድ ዘመን',
            'H' => 'የትውልድ ቦታ',
            'I' => 'የክርስትና ስም',
            'J' => 'የክርስትና አባት ስም',
            'K' => 'የንስሃ አባት',
            'L' => 'የንስሃ አባት ቤተክርስቲያን',
            'M' => 'ስልክ ቁ. 1',
            'N' => 'ስልክ ቁ. 2',
            'O' => 'አድራሻ',
            'P' => 'ዕድሜ ምድብ',
            'Q' => 'ሐዋርያዊ ምድብ',
            'R' => 'ስርአተ ት/ት',
            'S' => 'የአገልግሎት ክፍል',
            'T' => 'የመዝሙር ክፍል',
            'U' => ' ስራ',
            'V' => 'የትምህርት ደረጃ',
            'W' => 'የትምህርት መስክ',
            'X' => 'የስራ ቦታ',
            'Y' => 'የተመዘገበበት ቀን',
            'Z' => 'ሁኔታ'
        ];

        foreach ($headers as $column => $header) {
            $sheet->setCellValue($column . '1', $header);
        }

        // Set custom column widths (in points)
        $columnWidths = [
            'A' => 8,   // ID
            'B' => 20,  // First name
            'C' => 20,  // Middle name
            'D' => 20,  // Last name
            'E' => 20,  // Mother's name
            'F' => 12,  // Gender
            'G' => 15,  // Date of birth
            'H' => 20,  // Place of birth
            'I' => 20,  // Christian name
            'J' => 20,  // God father
            'K' => 25,  // Repentance father
            'L' => 25,  // Repentance father church
            'M' => 15,  // Phone 1
            'N' => 15,  // Phone 2
            'O' => 30,  // Address
            'P' => 15,  // Age category
            'Q' => 20,  // Occupation
            'R' => 20,  // Education level
            'S' => 20,  // Academic field
            'T' => 25,  // Workplace
            'U' => 20,  // Section
            'V' => 20,  // Department
            'W' => 20,  // Created at
            'X' => 15,   // Status
            'Y' => 15,  // Created at
            'Z' => 12   // Status
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        // Make header row bold and add background color
        $sheet->getStyle('A1:X1')
            ->getFont()->setBold(true);
        $sheet->getStyle('A1:X1')
            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('DDDDDD');

        // Freeze the header row so it's always visible when scrolling
        $sheet->freezePane('A2');

        $students = $this->Student->get_all_students_with_sub_category_names();

        // Populate the spreadsheet with student data
        $row = 2; // Start from the second row since the first row is for headers
        foreach ($students as $student) {
            $data = [
                'A' => $student['student_id'] ?? '-',
                'B' => $student['fname'] ?? '-',
                'C' => $student['mname'] ?? '-',
                'D' => $student['lname'] ?? '-',
                'E' => $student['mother_name'] ?? '-',
                'F' => $student['sex_amharic'] ?? '-',
                'G' => $student['dob'] ?? '-',
                'H' => $student['pob'] ?? '-',
                'I' => $student['christian_name'] ?? '-',
                'J' => $student['God_father'] ?? '-',
                'K' => $student['repentance_father'] ?? '-',
                'L' => $student['repentance_father_church'] ?? '-',
                'M' => $student['phone1'] ?? '-',
                'N' => $student['phone2'] ?? '-',
                'O' => $student['address'] ?? '-',
                'P' => $student['age_category_name'] ?? '-',
                'Q' => $student['apostolic_name'] ?? '-',
                'R' => $student['curriculum_name'] ?? '-',
                'S' => $student['department_name'] ?? '-',
                'T' => $student['choir_name'] ?? '-',
                'U' => $student['occupation'] ?? '-',
                'V' => $student['education_level'] ?? '-',
                'W' => $student['academic_field'] ?? '-',
                'X' => $student['workplace'] ?? '-',
                'Y' => $student['registration_date'] ?? '-',
                'Z' => $student['status_text'] ?? '-'
            ];

            foreach ($data as $column => $value) {
                $sheet->setCellValue($column . $row, $value);
            }
            $row++;
        }

        // Set text alignment to top for all cells
        $sheet->getStyle('A1:X' . ($row - 1))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        // Enable text wrapping for address and similar long fields
        $sheet->getStyle('O2:O' . ($row - 1)) // Address column
            ->getAlignment()
            ->setWrapText(true);

        // Save the file and output
        $writer = new Xlsx($spreadsheet);
        $filename = 'የተማሪዎች-ዝርዝር.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

require FCPATH . 'vendor/autoload.php';

use Andegna\DateTime as EthiopianDateTime;
use Andegna\DateTimeFactory;
use Andegna\Constants;

class Attendance extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('uid')) {
            redirect('login');
        }

        $this->load->model('Attendance_model');
        $this->load->model('Student_model');
        $this->load->model('Category_model');
    }

    public function scanner()
    {
        $this->load->view('admin/scan_qr');
    }

    public function record()
    {
        $encrypted_id = $this->input->post('student_id');
        $student_id = decrypt_id($encrypted_id);

        if (!$student_id) {
            $response = [
                'status' => 'error',
                'message' => '❌ የተማሪው መታወቂያ ቁጥር ልክ አይደለም!'
            ];
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }

        $student = $this->Attendance_model->get_student_with_categories($student_id);

        if (!$student) {
            $response = [
                'status' => 'error',
                'student_id' => $student_id,
                'message' => '❌ ተማሪው መረጃ ቋት ውስጥ አልተገኘም!'
            ];
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }

        // Check if student is inactive
        if ($student->status == 0) {
            $response = [
                'status' => 'error',
                'student_id' => $student_id,
                'message' => '❌ የተማሪው ትምህርት ተቋርጧል(Inactive Student)'
            ];
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }

        $current_date = date('Y-m-d');
        $current_time = time();
        $current_day = date('l');

        // Check if attendance already recorded today within the last hour and 5 minutes
        if ($student->last_attendance_date == $current_date) {
            $last_attendance_time = strtotime($student->last_attendance_time);
            $time_diff = ($current_time - $last_attendance_time) / 60;

            if ($time_diff < 65) {
                $response = [
                    'status' => 'error',
                    'student_id' => $student_id,
                    'message' => 'የተማሪው አቴንዳስ ቀድሞ ተመዝግቧል!'
                ];
                $this->output->set_content_type('application/json')->set_output(json_encode($response));
                return;
            }
        }

        // Get student categories
        $categories = [
            'age_category_id' => $student->age_category_id,
            'curriculum_id' => $student->curriculum_id,
            'department_id' => $student->department_id,
            'choir_id' => $student->choir_id
        ];

        // Check if all categories are null
        if (empty(array_filter($categories))) {
            $response = [
                'status' => 'error',
                'student_id' => $student_id,
                'message' => '❌ ተማሪው ምንም ምድብ ውስጥ አልተገኘም!'
            ];
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }

        // Get schedules for student's categories for today's day
        $schedules = $this->Attendance_model->get_schedules_by_categories($categories, $current_day);

        if (empty($schedules)) {
            $response = [
                'status' => 'error',
                'student_id' => $student_id,
                'message' => '⚠️ ተማሪው መርሐግብሩ ዛሬ አይደለም!'
            ];
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }

        $attendance_recorded = false;
        $status = 'present';
        $message = '✅ የተማሪው አቴንዳንስ በተሳካ ሁኔታ ተመዝግቧል!';

        foreach ($schedules as $schedule) {
            $schedule_time = strtotime($schedule->time);
            $start_time = $schedule_time - 300; // 5 minutes before
            $end_present_time = $schedule_time + 2700; // 45 minutes after
            $end_absent_time = $schedule_time + 3600; // 60 minutes after

            if ($current_time >= $start_time && $current_time <= $end_present_time) {
                $this->Attendance_model->record_attendance(
                    $student_id,
                    'present',
                    $current_date,
                    date('H:i:s')
                );
                $response = [
                    'status' => 'success',
                    'student_id' => $student_id,
                    'message' => '✅ የተማሪው አቴንዳንስ በተሳካ ሁኔታ ተመዝግቧል!'
                ];
                break;
            } elseif ($current_time > $end_present_time && $current_time <= $end_absent_time) {
                // 1. Record absent for scanned student
                $this->Attendance_model->record_attendance(
                    $student_id,
                    'absent',
                    $current_date,
                    date('H:i:s')
                );

                // 2. Get the sub_category_id for this schedule
                $sub_category_id = $this->Attendance_model->get_sub_category_id_by_schedule($schedule->id);

                if ($sub_category_id) {
                    // 3. Fetch other students with matching sub_category_id in any of the 4 fields
                    $students_in_subcategory = $this->Attendance_model->get_students_by_subcategory($sub_category_id, $student_id);

                    foreach ($students_in_subcategory as $other_student) {
                        if ($other_student->status != 1) continue;

                        if (
                            !empty($other_student->last_attendance_date) &&
                            $other_student->last_attendance_date == $current_date
                        ) {
                            continue;
                        }

                        $this->Attendance_model->record_attendance(
                            $other_student->student_id,
                            'absent',
                            $current_date,
                            date('H:i:s')
                        );
                    }
                }

                $response = [
                    'status' => 'success',
                    'student_id' => $student_id,
                    'message' => '✅ ተማሪው እና ከተማሪው ጋር በተመሳሳይ መርሐግብር ያሉ ተማሪዎች ቀሪ ተብለው ተመዝግበዋል።'
                ];
                $attendance_recorded = true;
                break;
            }
        }


        if (!$attendance_recorded) {
            $response = [
                'status' => 'error',
                'student_id' => $student_id,
                'message' => '⚠️ ተማሪው መርሐግብሩ አልፏል ወይም ገና አልደረሰም!'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function record_absent_for_all()
    {
        $current_day = date('l'); // e.g., "Monday"
        $current_date = date('Y-m-d');
        $current_time = time(); // current time in seconds

        // 1. Get all schedules for current day
        $schedules = $this->Attendance_model->get_schedules_by_day($current_day);

        if (empty($schedules)) {
            $response = [
                'status' => 'error',
                'message' => '⚠️ ዛሬ ምንም የትምህርት መርሐግብር የለም!'
            ];
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }

        $absent_recorded = 0;

        foreach ($schedules as $schedule) {
            $schedule_time = strtotime($schedule->time);
            $end_present_time = $schedule_time + (45 * 60); // 45 mins
            $end_absent_time = $schedule_time + (60 * 60);  // 1 hour

            if ($current_time > $end_present_time && $current_time <= $end_absent_time) {
                $sub_category_ids = $this->Attendance_model->get_subcategories_by_schedule($schedule->id);

                if (!empty($sub_category_ids)) {
                    $students = $this->Attendance_model->get_students_to_mark_absent(
                        $sub_category_ids,
                        $current_date,
                        $schedule_time
                    );

                    foreach ($students as $student) {
                        $this->Attendance_model->record_attendance(
                            $student->student_id,
                            'absent',
                            $current_date,
                            date('H:i:s')
                        );
                        $absent_recorded++;
                    }
                }
            }
        }

        $response = [
            'status' => $absent_recorded > 0 ? 'success' : 'error',
            'message' => $absent_recorded > 0
                ? "✅ ለ {$absent_recorded} ተማሪዎች ቀሪ ተብሎ ተመዝግቧል!"
                : "የሁሉም ተማሪዎች አቴንዳንስ ተመዝግቧል ወይም ምንም ተማሪዎች ቋት ውስጥ አልተገኙም!"
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function list()
    {
        if ($this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }
        // Get the Ethiopian date from the input
        $selectedDate = $this->input->get('date');

        // If no date is provided, use the current Ethiopian date
        if (empty($selectedDate)) {
            // Get the current Gregorian date
            $gregorianDate = new DateTime();

            // Convert the Gregorian date to Ethiopian
            $ethiopianDate = \Andegna\DateTimeFactory::fromDateTime($gregorianDate);

            // Format the Ethiopian date as "d/m/Y"
            $selectedDate = $ethiopianDate->format('d/m/Y');
        }

        try {
            // Split the Ethiopian date into day, month, and year
            $selectedDateParts = explode('/', $selectedDate);
            if (count($selectedDateParts) !== 3) {
                throw new \Andegna\Exception\InvalidDateException("ያስገቡት የቀን ፎርማት ልክ አይደለም። እባክዎ ያስተካክሉ!");
            }

            // Create an Ethiopian date object using DateTimeFactory
            $ethiopianDate = \Andegna\DateTimeFactory::of(
                (int)$selectedDateParts[2], // Year
                (int)$selectedDateParts[1], // Month
                (int)$selectedDateParts[0]  // Day
            );

            // Convert Ethiopian date to Gregorian
            $gregorianDate = $ethiopianDate->toGregorian()->format('Y-m-d');
        } catch (\Andegna\Exception\InvalidDateException $e) {
            // Handle invalid date input
            $this->session->set_flashdata('attendance_message', [
                'type' => 'error',
                'text' => 'ያስገቡት ቀን ልክ አይደለም። እባክዎ ያስተካክሉ!'
            ]);
            redirect('attendance/list');
            return;
        }

        // Fetch attendances for the selected Gregorian date
        $data['attendances'] = $this->Attendance_model->get_attendances($gregorianDate);

        // Convert Gregorian dates in the attendance data to Ethiopian for display
        foreach ($data['attendances'] as &$attendance) {
            if (!empty($attendance['created_date'])) {
                $gregorianDate = new DateTime($attendance['created_date']);
                $ethiopianDate = \Andegna\DateTimeFactory::fromDateTime($gregorianDate);
                $attendance['ethiopian_date'] = $ethiopianDate->format('F d፣ Y ዓ.ም'); // Format as "Month Day, Year"

                $gregorianTime = new DateTime($attendance['created_time']);
                // Subtract 6 hours to convert from Gregorian to Ethiopian time approximation
                $gregorianTime->modify('-6 hours');
                $time = $gregorianTime->format('h:i A');
                // Replace AM with ቀን and PM with ማታ
                $time = str_replace(['AM', 'PM'], ['ቀን', 'ማታ'], $time);
                $attendance['ethiopian_time'] = $time;
                /*
                $gregorianTime = new DateTime($attendance['created_at']);
                $ethiopianTime = \Andegna\DateTimeFactory::fromDateTime($gregorianTime);
                $time = $ethiopianTime->format('h:i A');
                $attendance['ethiopian_time'] = $time;
                */
            } else {
                $attendance['ethiopian_date'] = 'ዕለቱ አልተመዘገበም'; // "Date not recorded"
            }
        }

        // Pass the selected Ethiopian date and attendances to the view
        $data['selected_date'] = $selectedDate;
        $this->load->view('admin/list-attendance', $data);
    }


     // Update attendance status
    public function update_status() {
        $attendance_id = $this->input->post('attendance_id');
        $new_status = $this->input->post('new_status');
        
        if ($this->Attendance_model->update_attendance($attendance_id, $new_status)) {
            $this->session->set_flashdata('attendance_message', [
                'type' => 'success',
                'text' => 'አቴንዳንሱ በተሳካ ሁኔታ ተቀይሯል!'
            ]);
        } else {
            $this->session->set_flashdata('attendance_message', [
                'type' => 'error',
                'text' => 'እንደገና ይሞክሩ!'
            ]);
        }
        
        redirect('attendance/list');
    }

    // Delete attendance record
    public function delete($id) {
        if ($this->Attendance_model->delete_attendance($id)) {
            $this->session->set_flashdata('attendance_message', [
                'type' => 'success',
                'text' => 'አቴንዳንሱ በተሳካ ሁኔታ ተሰርዟል!'
            ]);
        } else {
            $this->session->set_flashdata('attendance_message', [
                'type' => 'error',
                'text' => 'እንደገና ይሞክሩ!'
            ]);
        }
        
        redirect('attendance/list');
    }

    public function list_and_record()
    {
        if ($this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }
        // Get filter parameters
        $filters = [
            'age_category_id' => $this->input->get('age_category_id'),
            'curriculum_id' => $this->input->get('curriculum_id'),
            'department_id' => $this->input->get('department_id'),
            'choir_id' => $this->input->get('choir_id')
        ];

        // Get data for view
        $data['categories'] = [
            'age' => $this->Category_model->get_subcategories(1),
            'curriculum' => $this->Category_model->get_subcategories(2),
            'department' => $this->Category_model->get_subcategories(3),
            'choir' => $this->Category_model->get_subcategories(4)
        ];

        $data['students'] = $this->Student_model->get_students_by_filters($filters);
        $data['current_filters'] = $filters;

        $this->load->view('admin/list-and-record-attendance', $data);
    }

    public function mark_attendance()
    {
        $student_id = $this->input->post('student_id');
        $status = $this->input->post('status');
        $date = $this->input->post('date');
        $time = $this->input->post('time');

        $gregorianTime = date('H:i:s', strtotime($time) + 6 * 3600); 
        try {
            // Convert Ethiopian date to Gregorian
            $selectedDateParts = explode('/', $date);
            if (count($selectedDateParts) !== 3) {
                throw new Exception("ያስገቡት ቀን ልክ አይደለም");
            }

            $ethiopianDate = \Andegna\DateTimeFactory::of(
                (int)$selectedDateParts[2], // Year
                (int)$selectedDateParts[1], // Month
                (int)$selectedDateParts[0]  // Day
            );

            $gregorianDate = $ethiopianDate->toGregorian()->format('Y-m-d');

            // Record attendance
            $this->Attendance_model->record_attendance($student_id, $status, $gregorianDate, $gregorianTime);

            $this->session->set_flashdata('attendance_message', [
                'type' => 'success',
                'text' => 'አቴንዳንሱ በተሳካ ሁኔታ ተመዝግቧል!'
            ]);
        } catch (\Exception $e) {
            $this->session->set_flashdata('attendance_message', [
                'type' => 'error',
                'text' => 'Error: ' . $e->getMessage()
            ]);
        }

        redirect($this->input->post('redirect_url'));
    }
}

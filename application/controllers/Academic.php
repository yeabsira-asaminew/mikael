
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Academic extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Academic_model');

        if (!$this->session->userdata('uid')) {
            redirect('login');
        }
    }

    public function schedule()
    {
        // Fetch schedules and sections
        $schedules = $this->Academic_model->get_schedules();
        $data['categories'] = $this->Academic_model->get_categories();

        // Adjust time to Ethiopian time (GMT-3)
        foreach ($schedules as &$schedule) {
            // Convert the time to a timestamp, subtract 6 hours, and format it back to a time string
            $time = date('h:i A', strtotime($schedule['time']) - 6 * 3600);

            // Replace AM with ቀን and PM with ማታ
            $time = str_replace(['AM', 'PM'], ['ቀን', 'ማታ'], $time);

            $schedule['time'] = $time;
        }

        $data['schedules'] = $schedules;

        $this->load->view('admin/schedule', $data);
    }

    public function get_sub_categories()
    {
        $category_id = $this->input->post('category_id');
        $sub_categories = $this->Academic_model->get_sub_categories_by_category($category_id);
        echo json_encode($sub_categories);
    }

    public function add_schedule()
    {
        // Get input data
        $day = $this->input->post('day');
        $time = $this->input->post('time');
        $sub_categories = $this->input->post('sub_categories'); // Get selected subcategories
        $description = $this->input->post('description');

        // Validate input
        if (empty($day) || empty($time) || empty($sub_categories)) {
            $this->session->set_flashdata('academic_message', [
                'type' => 'error',
                'text' => 'ሁሉንም መረጃዎች ማስገባት ያስፈልጋል!'
            ]);
            redirect('academic/schedule');
            return;
        }
        
        // Adjust time to GMT+3 before saving
        $gmt_time = date('H:i:s', strtotime($time) + 6 * 3600); // Add 6 hours

        $data = [
            'day' => $day,
            'time' => $gmt_time,
            'description' => $description
        ];

        if ($this->Academic_model->add_schedule($data, $sub_categories)) {
            $this->session->set_flashdata('academic_message', [
                'type' => 'success',
                'text' => 'መርሐግብሩ በተሳካ ሁኔታ ታክሏል!'
            ]);
        } else {
            $this->session->set_flashdata('academic_message', [
                'type' => 'error',
                'text' => 'መርሐግብሩ አልታከለም። እባኮትን እንደገና ይሞክሩ!'
            ]);
        }

        redirect('academic/schedule');
    }

    public function update_schedule($id = null)
    {
        // Ensure ID is provided
        if (empty($id)) {
            $this->session->set_flashdata('academic_message', [
                'type' => 'error',
                'text' => 'የመርሐግብሩ መለያ ቁጥር ልክ አይደለም!'
            ]);
            redirect('academic/schedule');
            return;
        }

        // Get input data
        $day = $this->input->post('day');
        $time = $this->input->post('time');
        $sub_categories = $this->input->post('sub_categories'); // Get selected subcategories
        $description = $this->input->post('description');

        // Validate input
        if (empty($day) || empty($time) || empty($sub_categories)) {
            $this->session->set_flashdata('academic_message', [
                'type' => 'error',
                'text' => 'ሁሉንም መረጃዎች ማስገባት ያስፈልጋል!'
            ]);
            redirect('academic/schedule');
            return;
        }

        // Adjust time to GMT+3 before saving
        $gmt_time = date('H:i:s', strtotime($time) + 6 * 3600); // Add 6 hours

        $data = [
            'day' => $day,
            'time' => $gmt_time,
            'description' => $description
        ];

        if ($this->Academic_model->update_schedule($id, $data, $sub_categories)) {
            $this->session->set_flashdata('academic_message', [
                'type' => 'success',
                'text' => 'መርሐግብሩ በተሳካ ሁኔታ ተሻሽሏል!'
            ]);
        } else {
            $this->session->set_flashdata('academic_message', [
                'type' => 'error',
                'text' => 'መርሐግብሩ አልተሻሻለም። እባኮትን እንደገና ይሞክሩ!'
            ]);
        }

        redirect('academic/schedule');
    }

    public function delete_schedule($id = null)
    {
        // Ensure ID is provided
        if (empty($id)) {
            $this->session->set_flashdata('academic_message', [
                'type' => 'error',
                'text' => 'የመርሐግብሩ መለያ ቁጥር ልክ አይደለም!'
            ]);
            redirect('academic/schedule');
            return;
        }

        if ($this->Academic_model->delete_schedule($id)) {
            $this->session->set_flashdata('academic_message', [
                'type' => 'success',
                'text' => 'መርሐግብሩ በተሳካ ሁኔታ ተሰርዟል!'
            ]);
        } else {
            $this->session->set_flashdata('academic_message', [
                'type' => 'error',
                'text' => 'መርሐግብሩ አልተሰረዘም። እባኮትን እንደገና ይሞክሩ!'
            ]);
        }

        redirect('academic/schedule');
    }

}
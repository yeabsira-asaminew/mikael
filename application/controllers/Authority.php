<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';

use Andegna\DateTime as EthiopianDateTime;
use Andegna\DateTimeFactory;
use Andegna\Constants;

class Authority extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('uid')) {
            redirect('login');
        } elseif ($this->session->userdata('role') !== 'superadmin') {
            redirect('login');
        }

        $this->load->model('Auth_model');
        $this->load->library('form_validation');
        $this->load->helper('ethiopian_date');
    }

    // Handle Admin Submission
    public function save_auth()
    {
        // Form Validation Rules
        $this->form_validation->set_rules(
            'email',
            'Email',
            'required|valid_email|is_unique[auth.email]',
            array(
                'is_unique' => 'ኢ-ሜይሉ ከዚህ በፊት የተመዘገበ ነው!'
            )
        );
        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Reload form with validation errors
            $this->load->view('admin/add-authority');
        } else {
            // Get Input Data
            $email = $this->input->post('email');
            $password = '$2y$10$BCduCxSUODAN.UopDrLHVeakzyoYJdJ36mbU8uMTYY/H9alEg5Oza';
            $role = $this->input->post('role');


            // Prepare Data for Insertion
            $data = array(
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'created_at' => date('Y-m-d H:i:s') // Register current date and time
            );

            // Insert into Database
            $insert_id = $this->Auth_model->add_admin($data);

            if ($insert_id) {
                $this->session->set_flashdata('auth_message', ['type' => 'success', 'text' => 'አስተዳዳሪው በተሳካ ሁኔታ ተመዝግቧል!']);
                redirect('authority/list');
            } else {
                $this->session->set_flashdata('auth_message', ['type' => 'error', 'text' => 'አስተዳዳሪው አልተመዘገበም። እባክዎ በድጋሚ ይሞክሩ!']);
                redirect('authority/list');
            }
        }
    }

    public function list()
    {
        // Fetch authorities
        $authorities = $this->Auth_model->get_authorities();

        // Adjust last_login and created_at to Ethiopian time (GMT-3) and format them
        foreach ($authorities as &$authority) {
            // Format last_login
            if (!empty($authority['last_login'])) {
                $gregorianDate = new DateTime($authority['last_login']);
                $ethiopianDate = new Andegna\DateTime($gregorianDate);

             

                $ethiopianTime = \Andegna\DateTimeFactory::fromDateTime($gregorianDate);
                $time = $ethiopianTime->format('h:i A');


                $authority['last_login'] = $ethiopianDate->format('F d' . '፣ Y ዓ.ም') .
                    ' | ' . $time;
            } else {
                $authority['last_login'] = 'የለም'; // "None" in Amharic
            }

            // Format created_at (only date)
            if (!empty($authority['created_at'])) {
                $gregorianDateCreated = new DateTime($authority['created_at']);
                $ethiopianDateCreated = new Andegna\DateTime($gregorianDateCreated);
                $authority['created_at'] = $ethiopianDateCreated->format('F d' . '፣ Y ዓ.ም');
            } else {
                $authority['created_at'] = 'የለም';
            }
        }

        $data['authorities'] = $authorities;
        $this->load->view('admin/list-authority', $data);
    }


    public function delete($id)
    {
        $result = $this->Auth_model->delete($id);
        if ($result) {
            $this->session->set_flashdata('success', 'አስተዳዳሪው በተሳካ ሁኔታ ተሰርዟል!');
        } else {
            $this->session->set_flashdata('error', 'አስተዳዳሪው አልተሰረዘም። እባክዎ በድጋሚ ይሞክሩ!');
        }

        redirect('authority/list');
    }


    public function edit_profile()
    {
        $user_id = $this->session->userdata('uid');
        $data['profile'] = $this->Auth_model->get_profile($user_id);

        $this->load->view('admin/edit-profile', $data);
    }

    // Handle Admin Submission
    public function update_profile()
    {
        $user_id = $this->session->userdata('uid');
        $profile = $this->Auth_model->get_profile($user_id);

        // Form validation rules
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'matches[password]');


        if ($this->form_validation->run() == FALSE) {
            // Reload form with validation errors
            $data['profile'] = $profile;
            $this->load->view('admin/edit-profile', $data);
        } else {
            // Prepare update data
            $update_data = [
                'email' => $this->input->post('email'),
            ];

            // If password is provided, hash it before updating
            $password = $this->input->post('password');
            if (!empty($password)) {
                $update_data['password'] = password_hash($password, PASSWORD_BCRYPT);
            }

            // Insert into Database
            $update = $this->Auth_model->update_profile($user_id, $update_data);

            if ($update) {
                $this->session->set_flashdata('auth_message', ['type' => 'success', 'text' => 'መረጃው በተሳካ ሁኔታ ተስተካክሏል!']);
            } else {
                $this->session->set_flashdata('auth_message', ['type' => 'error', 'text' => 'መረጃው አልተስተካከለም። እባክዎ በድጋሚ ይሞክሩ!']);
            }
            redirect('login/logout');
            redirect('login');
            //redirect('Authority/edit_profile');
        }
    }


    // reset passwords to 12345678
    public function reset_password($id)
    {
        $password = '$2y$10$BCduCxSUODAN.UopDrLHVeakzyoYJdJ36mbU8uMTYY/H9alEg5Oza';

        // Insert into Database
        $update = $this->Auth_model->update_profile($id, $password);

        if ($update) {
            $this->session->set_flashdata('auth_message', ['type' => 'success', 'text' => 'የይለፍ ቃል በተሳካ ሁኔታ ተቀይሯል!']);
        } else {
            $this->session->set_flashdata('auth_message', ['type' => 'error', 'text' => 'የይለፍ ቃል መቀየር አልተቻለም። እባክዎ በድጋሚ ይሞክሩ!']);
        }
        redirect('authority/list');
    }
}

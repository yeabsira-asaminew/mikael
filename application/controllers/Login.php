<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Login_model');
        
    }

    public function index() {
        $this->load->view('admin/login');
    }

    public function validate_credentials() {
        // Form Validation Rules
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
    
        if ($this->form_validation->run()) {
            // Get Input Values
            $email = $this->input->post('email');
            $password = $this->input->post('password');
    
            // Validate User Credentials in the Database
            $user = $this->Login_model->validate($email);
    
            if ($user && password_verify($password, $user['password'])) {
                // Update last login time 
                $this->Login_model->update_last_login($user['id']);
                
                // Set session data
                $this->session->set_userdata([
                    'uid' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]);
    
                redirect('dashboard');

            } else {
                // Invalid credentials
                $this->session->set_flashdata('login_message', ['type' => 'error', 'text' => 'የተሳሳተ የይለፍ ቃል ወይም ኢ-ሜይል ተጠቅመዋል!']);
                redirect('login');
            }
        } else {
            // Reload the login view with validation errors
            $this->load->view('admin/login');
        }
    }
    
    
     //Function for logout
     public function logout()
     {
         $this->session->unset_userdata('user_id');
         $this->session->sess_destroy();
         return redirect('login');
     }
}
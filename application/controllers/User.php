<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
    }
 
    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('User/Signin');
        } else {
            redirect('User/Profile');
        }
    }
    
    public function Register()
    {
        if ($this->ion_auth->logged_in()) {
            redirect('User/Profile');
        } else {
            $this->load->helper('form');
            $this->render('user/register');
        }
    }

    public function Register_Action()
    {
        if ($this->ion_auth->logged_in()) {
            redirect('User/Profile');
        } else {
            $this->form_validation->set_rules('username', 'ชื่อผู้ใช้', 'trim|required|is_unique[users.username]');
            $this->form_validation->set_rules('first_name', 'ชื่อ', 'trim|required');
            $this->form_validation->set_rules('last_name', 'นามสกุล', 'trim|required');
            $this->form_validation->set_rules('email', 'อีเมล', 'trim|valid_email|required|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'รหัสผ่าน', 'trim|min_length[8]|max_length[64]|required');
            $this->form_validation->set_rules('confirm_password', 'ยืนยันรหัสผ่าน', 'trim|matches[password]|required');
 
            if ($this->form_validation->run()===false) {
                $errors = validation_errors();
                echo json_encode(['error'=>$errors]);
            } else {
                $first_name = $this->input->post('first_name');
                $last_name = $this->input->post('last_name');
                $username = $this->input->post('username');
                $email = $this->input->post('email');
                $password = $this->input->post('password');
 
                $additional_data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'key' => bin2hex(openssl_random_pseudo_bytes(16))
            );
 
                if ($this->ion_auth->register($username, $password, $email, $additional_data)) {
                    echo json_encode(['success'=>'การลงทะเบียนเสร็จสิ้น ท่านสามารถเข้าสู่ระบบได้ทันที']);

                    $_SESSION['auth_message'] = 'การลงทะเบียนเสร็จสิ้น ท่านสามารถเข้าสู่ระบบได้ทันที';
                    $_SESSION['auth_message_type'] = 'success';
                    $this->session->mark_as_flash('auth_message');
                } else {
                    $errors = $this->ion_auth->errors();
                    ;
                    echo json_encode(['error'=>$errors]);
                }
            }
        }
    }
 
    public function Signin()
    {
        if ($this->ion_auth->logged_in()) {
            redirect('User/Profile');
        } else {
            $this->load->helper('form');
            $this->render('user/signin');
        }
    }

    public function Signin_Action()
    {
        if ($this->ion_auth->logged_in()) {
            redirect('User/Profile');
        } else {
            $this->form_validation->set_rules('username', 'ชื่อผู้ใช้', 'trim|required');
            $this->form_validation->set_rules('password', 'รหัสผ่าน', 'trim|min_length[8]|max_length[64]|required');
            if ($this->form_validation->run() === false) {
                $errors = validation_errors();
                echo json_encode(['error'=>$errors]);
            } else {
                $remember = (bool) $this->input->post('remember');
                if ($this->ion_auth->login($this->input->post('username'), $this->input->post('password'), $remember)) {
                    echo json_encode(['success'=>"ผู้ใช้งาน ".$this->input->post('username')." เข้าสู่ระบบสำเร็จ !"]);
                
                    $_SESSION['result_message'] = "ผู้ใช้งาน ".$this->input->post('username')." เข้าสู่ระบบสำเร็จ !";
                    $_SESSION['result_message_type'] = 'success';
                    $this->session->mark_as_flash('result_message');
                } else {
                    $errors = $this->ion_auth->errors();
                    
                    echo json_encode(['error'=>$errors]);
                }
            }
        }
    }

    public function Profile()
    {
        if ($this->ion_auth->logged_in()) {
            $this->render('user/profile');
        } else {
            redirect('User/Signin');
        }
    }

    public function Profile_Edit()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->helper('form');
            $this->render('user/profile_edit');
        } else {
            redirect('User/Signin');
        }
    }

    public function Profile_Edit_Action()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('first_name', 'ชื่อ', 'trim|required');
            $this->form_validation->set_rules('last_name', 'นามสกุล', 'trim|required');

            if ($this->form_validation->run()===false) {
                $errors = validation_errors();
                echo json_encode(['error'=>$errors]);
            } else {
                $first_name = $this->input->post('first_name');
                $last_name = $this->input->post('last_name');

                $user = $this->ion_auth->user()->row();

                $data = array(
                    'id' => $user->id,
                    'first_name' => $first_name,
                    'last_name' => $last_name
                );

                $this->db->where('id', $user->id);
                $this->db->update('users', $data);

                echo json_encode(['success'=>'แก้ไขข้อมูลส่วนตัวสำเร็จแล้ว !']);

                $_SESSION['result_message'] = 'แก้ไขข้อมูลส่วนตัวสำเร็จแล้ว !';
                $_SESSION['result_message_type'] = 'success';
                $this->session->mark_as_flash('result_message');
            }
        } else {
            redirect('User/Signin');
        }
    }

    public function Change_Password()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->helper('form');
            $this->render('user/change_password');
        } else {
            redirect('User/Signin');
        }
    }

    public function Change_Password_Action()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('new_password', 'รหัสผ่านใหม่', 'trim|min_length[8]|max_length[64]|required');
            $this->form_validation->set_rules('confirm_new_password', 'ยืนยันรหัสผ่านใหม่', 'trim|matches[new_password]|required');

            if ($this->form_validation->run()===false) {
                $errors = validation_errors();
                echo json_encode(['error'=>$errors]);
            } else {
                $new_password = $this->input->post('new_password');

                $user = $this->ion_auth->user()->row();

                $data = array(
                    'password' => $new_password
                );

                if ($this->ion_auth->update($user->id, $data)) {
                    echo json_encode(['success'=>'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว !']);
                    
                    $_SESSION['result_message'] = 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว !';
                    $_SESSION['result_message_type'] = 'success';
                    $this->session->mark_as_flash('result_message');
                } else {
                    echo json_encode(['error'=>$this->ion_auth->errors()]);
                }
            }
        } else {
            redirect('User/Signin');
        }
    }
 
    public function Signout()
    {
        $this->ion_auth->logout();
        redirect('User/Signin');
    }
}

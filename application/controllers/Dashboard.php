<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Dashboard extends Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->library('ciqrcode');
        $this->load->helper(array('form', 'url', 'file'));
    }
    
    public function index()
    {
        $this->render('dashboard/index_view');
    }

    public function Bike_Add()
    {
        $this->render('dashboard/bikeadd_view');
    }

    public function Bike_Add_Action()
    {
        $this->form_validation->set_rules('plate', 'หมายเลขทะเบียนรถ', 'trim|required|is_unique[bike.plate]');
        $this->form_validation->set_rules('model', 'ยี่ห้อ รุ่น', 'trim|required');
        $this->form_validation->set_rules('color', 'สี', 'trim|required');

        if ($this->form_validation->run()===false) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        } else {
            $user = $this->ion_auth->user()->row();

            $data = array(
                'user' => $user->id,
                'key' => bin2hex(openssl_random_pseudo_bytes(16)),
                'plate' => $_POST['plate'],
                'model' => $_POST['model'],
                'color' => $_POST['color'],
                'reg_date' => date('Y-m-d H:i:s'),
                'status' => '1'
            );
            $this->db->insert('bike', $data);

            echo json_encode(['success'=>'เพิ่มข้อมูลรถจักรยานยนต์ หมายเลขทะเบียน '.$_POST['plate'].' แล้ว !']);

            $_SESSION['result_message'] = 'เพิ่มข้อมูลรถจักรยานยนต์ หมายเลขทะเบียน '.$_POST['plate'].' แล้ว !';
            $_SESSION['result_message_type'] = 'success';
            $this->session->mark_as_flash('result_message');
        }
    }

    public function Bike_Edit($bike_key)
    {
        $user = $this->ion_auth->user()->row();
        
        $this->db->select(array('key','user'));
        $this->db->from('bike');
        $this->db->where('key', $bike_key);
    
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $query1 = $row->key;
            $query2 = $row->user;
        }

        if ($query2 == null) {
            redirect('Dashboard');
        } elseif ($user->id==$query2) {
            $_SESSION['bike_key']=$bike_key;
                
            $this->render('dashboard/bikeedit_view');
        }
    }

    public function Bike_Edit_Action($bike_key)
    {
        $user = $this->ion_auth->user()->row();
        
        $this->db->select(array('key','user'));
        $this->db->from('bike');
        $this->db->where('key', $bike_key);
    
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $query1 = $row->key;
            $query2 = $row->user;
        }

        if ($query2 == null) {
            redirect('Dashboard');
        } elseif ($user->id==$query2) {
            $this->form_validation->set_rules('model', 'ยี่ห้อ รุ่น', 'trim|required');
            $this->form_validation->set_rules('color', 'สี', 'trim|required');
    
            if ($this->form_validation->run()===false) {
                $errors = validation_errors();
                echo json_encode(['error'=>$errors]);
            } else {
                $user = $this->ion_auth->user()->row();

                $this->db->select(array('id', 'plate'));
                $this->db->from('bike');
                $this->db->where('key', $bike_key);

                $query2 = $this->db->get();
    
                $data = array(
                    'id' => $query2->row()->id,
                    'key' => $bike_key,
                    'model' => $_POST['model'],
                    'color' => $_POST['color']
                );
                $this->db->where('id', $query2->row()->id);
                $this->db->update('bike', $data);

                echo json_encode(['success'=>'แก้ไขข้อมูลรถจักรยานยนต์หมายเลขทะเบียน '.$query2->row()->plate.' แล้ว !']);
    
                $_SESSION['result_message'] = 'แก้ไขข้อมูลรถจักรยานยนต์หมายเลขทะเบียน '.$query2->row()->plate.' แล้ว !';
                $_SESSION['result_message_type'] = 'success';
                $this->session->mark_as_flash('result_message');

                $_SESSION['bike_key']='';
            }
        }
    }

    public function Bike_Switch($bike_key)
    {
        $user = $this->ion_auth->user()->row();
        
        $this->db->select(array('key','user','status'));
        $this->db->from('bike');
        $this->db->where('key', $bike_key);
    
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $query1 = $row->key;
            $query2 = $row->user;
            $query3 = $row->status;
        }

        if ($query2 == null) {
            redirect('Dashboard');
        } elseif ($user->id==$query2) {
            if ($query3=='1') {
                $new_status = '0';
                $new_status_desc = 'ปิดใช้งาน';
            } elseif ($query3=='0') {
                $new_status = '1';
                $new_status_desc = 'เปิดใช้งาน';
            }

            $user = $this->ion_auth->user()->row();

            $this->db->select(array('id', 'key', 'plate', 'status'));
            $this->db->from('bike');
            $this->db->where('key', $bike_key);

            $query2 = $this->db->get();
    
            $data = array(
                'id' => $query2->row()->id,
                'status' => $new_status
            );
            $this->db->where('id', $query2->row()->id);
            $this->db->update('bike', $data);

            $_SESSION['result_message'] = $new_status_desc.'รถจักรยานยนต์หมายเลขทะเบียน '.$query2->row()->plate.' แล้ว !';
            $_SESSION['result_message_type'] = 'success';
            $this->session->mark_as_flash('result_message');

            redirect('Dashboard');
        }
    }

    public function Bike_PicUpload($bike_key)
    {
        $user = $this->ion_auth->user()->row();
        
        $this->db->select(array('key','user'));
        $this->db->from('bike');
        $this->db->where('key', $bike_key);
    
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $query1 = $row->key;
            $query2 = $row->user;
        }

        if ($query2 == null) {
            redirect('Dashboard');
        } elseif ($user->id==$query2) {
            $_SESSION['bike_key']=$bike_key;

            $this->render('dashboard/bikepicupload_view');
        }
    }

    public function Bike_uploadImage($bike_key)
    {
        header('Content-Type: application/json');
        $config['upload_path']   = './uploads/bike/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = $bike_key;
        $config['overwrite']     = true;
        $this->load->library('upload', $config);
  
        if (! $this->upload->do_upload('image')) {
            $_SESSION['result_message'] = $this->upload->display_errors();
            $_SESSION['result_message_type'] = 'danger';
            $this->session->mark_as_flash('result_message');

            redirect('Dashboard/Bike_PicUpload/'.$bike_key);
        } else {
            $data = $this->upload->data();
            $this->Bike_resizeImage($data['file_name']);

            $this->db->select(array('id', 'plate', 'image_name'));
            $this->db->from('bike');
            $this->db->where('key', $bike_key);

            $query = $this->db->get();

            if ($query->row()->image_name==null) {
            } elseif ($query->row()->image_name!=$data['file_name']) {
                unlink('./uploads/bike/'.$query->row()->image_name);
                unlink('./uploads/bike/thumbnail/'.$query->row()->image_name);
            }
    
            $data = array(
                'id' => $query->row()->id,
                'image_name' => $data['file_name']
            );
            $this->db->where('id', $query->row()->id);
            $this->db->update('bike', $data);

            $_SESSION['result_message'] = 'อัพโหลดรูปรถจักรยานยนต์หมายเลขทะเบียน '.$query->row()->plate.' แล้ว !';
            $_SESSION['result_message_type'] = 'success';
            $this->session->mark_as_flash('result_message');

            $_SESSION['bike_key']='';

            redirect('Dashboard');
        }
    }
  
    public function Bike_resizeImage($filename)
    {
        $source_path = './uploads/bike/' . $filename;
        $target_path = './uploads/bike/thumbnail/';
        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $target_path,
            'maintain_ratio' => true,
            'create_thumb' => true,
            'thumb_marker' => '',
            'width' => 300,
            'height' => 300
        );
  
        $this->load->library('image_lib', $config_manip);
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
  
        $this->image_lib->clear();
    }

    public function Bike_PicRemove($bike_key)
    {
        $user = $this->ion_auth->user()->row();
        
        $this->db->select(array('key','user'));
        $this->db->from('bike');
        $this->db->where('key', $bike_key);
    
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $query1 = $row->key;
            $query2 = $row->user;
        }

        if ($query2 == null) {
            redirect('Dashboard');
        } elseif ($user->id==$query2) {
            $this->db->select(array('id', 'plate', 'image_name'));
            $this->db->from('bike');
            $this->db->where('key', $bike_key);

            $query = $this->db->get();

            if ($query->row()->image_name==null) {
                $_SESSION['result_message'] = 'ไม่มีรูปรถจักรยานยนต์หมายเลขทะเบียน '.$query->row()->plate.' อยู่ในระบบ';
                $_SESSION['result_message_type'] = 'danger';
                $this->session->mark_as_flash('result_message');

                redirect('Dashboard');
            } else {
                unlink('./uploads/bike/'.$query->row()->image_name);
                unlink('./uploads/bike/thumbnail/'.$query->row()->image_name);

                $data = array(
                    'id' => $query->row()->id,
                    'image_name' => null
                );
                $this->db->where('id', $query->row()->id);
                $this->db->update('bike', $data);
    
                $_SESSION['result_message'] = 'ลบรูปรถจักรยานยนต์หมายเลขทะเบียน '.$query->row()->plate.' แล้ว !';
                $_SESSION['result_message_type'] = 'warning';
                $this->session->mark_as_flash('result_message');
    
                redirect('Dashboard');
            }
        }
    }

    public function Bike_Remove($bike_key)
    {
        $user = $this->ion_auth->user()->row();
        
        $this->db->select(array('key','user'));
        $this->db->from('bike');
        $this->db->where('key', $bike_key);
    
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $query1 = $row->key;
            $query2 = $row->user;
        }

        if ($query2 == null) {
            redirect('Dashboard');
        } elseif ($user->id==$query2) {
            $this->db->delete('bike', array(key => $bike_key));

            $_SESSION['result_message'] = 'ลบรถจักรยานยนต์แล้ว !';
            $_SESSION['result_message_type'] = 'danger';
            $this->session->mark_as_flash('result_message');

            redirect('Dashboard');
        }
    }

    public function LINENotify_Edit()
    {
        $_SESSION['token_tmp']='';
        $_SESSION['otp']='';
        $_SESSION['otp_ref']='';
            
        $this->render('dashboard/linenotifyedit_view');
    }

    public function LINENotify_Edit_Action()
    {
        $this->form_validation->set_rules('token', 'โทเค็นไลน์โนติฟาย', 'trim|required|callback_linenoti_test');
        if ($this->form_validation->run()===false) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        } else {
            $token = $this->input->post('token');
            $_SESSION['token_tmp'] = $token;

            echo json_encode(['success'=>'']);
        }
    }

    public function LINENotify_Verification()
    {
        if ($_SESSION['token_tmp']=='') {
            redirect('Dashboard/LINENotify_Edit');
        }

        $token = $_SESSION['token_tmp'];
            
        if ($_SESSION['otp']==''&&$_SESSION['otp_ref']=='') {
            $otp = sprintf("%06d", rand(1, 999999));
            $otp_ref = strtoupper(bin2hex(openssl_random_pseudo_bytes(2)));
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_ref'] = $otp_ref;
        }

        $this->Line_notify_model->notify($token, "[Track My Bikes] กรอก OTP ".$_SESSION['otp']." ในระบบ เพื่อยืนยันความเป็นเจ้าของไลน์นี้ (รหัสอ้างอิง : ".$_SESSION['otp_ref'].")");

        $this->render('dashboard/linenotifyverification_view');
    }

    public function LINENotify_Verification_Action($type)
    {
        if ($type=='1') {
            if ($this->input->post('resend')=='1') {
                $token = $_SESSION['token_tmp'];

                $this->Line_notify_model->notify($token, "[Track My Bikes] กรอก OTP ".$_SESSION['otp']." ในระบบ เพื่อยืนยันความเป็นเจ้าของไลน์นี้");
                echo json_encode(['success'=>'ระบบได้ส่งรหัสผ่านครั้งเดียวให้ท่านอีกครั้งแล้ว !']);
            }
        } elseif ($type=='0') {
            $this->form_validation->set_rules('otp', 'รหัสผ่านครั้งเดียว', 'trim|required|min_length[6]|max_length[6]');
            if ($this->form_validation->run()===false) {
                $errors = validation_errors();
                echo json_encode(['error'=>$errors]);
            } else {
                if ($this->input->post('otp')==$_SESSION['otp']) {
                    $user = $this->ion_auth->user()->row();
    
                    $data = array(
                        'id' => $user->id,
                        'lineapi_key' => $_SESSION['token_tmp']
                    );
                    $this->db->where('id', $user->id);
                    $this->db->update('users', $data);
    
                    echo json_encode(['success'=>'แก้ไขข้อมูลโทเค็นไลน์โนติฟายแล้ว !']);
    
                    $_SESSION['result_message'] = 'แก้ไขข้อมูลโทเค็นไลน์โนติฟายแล้ว !';
                    $_SESSION['result_message_type'] = 'success';
                    $this->session->mark_as_flash('result_message');
    
                    $this->Line_notify_model->notify($_SESSION['token_tmp'], "[Track My Bikes] ผู้ใช้งาน ".$user->username." แก้ไขข้อมูลโทเค็นไลน์โนติฟายแล้ว !");
    
                    $_SESSION['token_tmp']='';
                    $_SESSION['otp']='';
                    $_SESSION['otp_ref']='';
                } else {
                    echo json_encode(['error'=>'รหัสผ่านครั้งเดียวไม่ถูกต้อง โปรดตรวจสอบและกรอกอีกครั้ง']);
                }
            }
        }
    }

    public function LINENotify_Remove($tkn)
    {
        $user = $this->ion_auth->user()->row();

        $this->db->select(array('username', 'lineapi_key'));
        $this->db->from('users');
        $this->db->where('id', $user->id);
    
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $token_tmp = $row->lineapi_key;
            $username = $row->username;
        }
        if ($tkn==$token_tmp) {
            $this->Line_notify_model->notify($token_tmp, "[Track My Bikes] ผู้ใช้งาน ".$username." ลบโทเค็นไลน์โนติฟายนี้ออกจากระบบแล้ว หากต้องการใช้งานการแจ้งเตือน โปรดเพิ่มข้อมูลโทเค็นใหม่อีกครั้ง");

            $token = null;
    
            $data = array(
                'id' => $user->id,
                'lineapi_key' => $token
            );
            $this->db->where('id', $user->id);
            $this->db->update('users', $data);
    
            $_SESSION['result_message'] = 'ลบข้อมูลโทเค็นไลน์โนติฟายแล้ว !';
            $_SESSION['result_message_type'] = 'warning';
            $this->session->mark_as_flash('result_message');
    
            redirect('Dashboard');
        } else {
            redirect('Dashboard');
        }
    }

    // custom form-validation for line notify test
    public function linenoti_test($token)
    {
        if ($this->Line_notify_model->verification($token)=='200') {
            return true;
        } elseif ($token==null) {
            $this->form_validation->set_message('linenoti_test', 'จำเป็นต้องกรอกช่องโทเค็น');
            return false;
        } else {
            $this->form_validation->set_message('linenoti_test', 'ไม่สามารถทดสอบการส่งแจ้งเตือนไปยังไลน์ด้วยโทเค็น '.$token.' โปรดตรวจสอบข้อมูลของท่านอีกครั้ง');
            return false;
        }
    }

    public function genqrcode($bike_key, $size='x1')
    {
        $user = $this->ion_auth->user()->row();
        
        $this->db->select(array('key','user'));
        $this->db->from('bike');
        $this->db->where('key', $bike_key);
    
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $query1 = $row->key;
            $query2 = $row->user;
        }

        $this->db->select(array('id','key'));
        $this->db->from('users');
        $this->db->where('id', $query2);

        $querytwo = $this->db->get();
        foreach ($querytwo->result() as $row) {
            $query3 = $row->key;
        }

        if ($query2 == null) {
            redirect('Dashboard');
        } elseif ($user->id==$query2) {
            $data = array('customerId'=>$query3,'bikeId'=>$query1);

            header('Content-Type: image/png');

            if ($size=='x1'||$size=='x1.png') {
                return QRcode::png(json_encode($data), 'php://output', QR_ECLEVEL_L, 7);
            } elseif ($size=='x2'||$size=='x2.png') {
                return QRcode::png(json_encode($data), 'php://output', QR_ECLEVEL_L, 14);
            }
        }
    }
}
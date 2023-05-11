<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Maps extends Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->helper('date');
    }
    
    public function index()
    {
        $this->render('maps/index_view');
    }

    public function View($bike, $start_date, $end_date)
    {
        $user = $this->ion_auth->user()->row();
        
        $this->db->select(array('key','user'));
        $this->db->from('bike');
        $this->db->where('key', $bike);
    
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $query1 = $row->key;
            $query2 = $row->user;
        }

        if ($query2 == null) {
            redirect('Dashboard');
        } elseif ($user->id==$query2) {
            $_SESSION['user_key']=$user->key;
            $_SESSION['bike']=$bike;
            $_SESSION['start_date']=$start_date;
            $_SESSION['end_date']=$end_date;
                
            $this->render('maps/maps_view', 'maps_master');
        }
    }

    public function View_Action()
    {
        if (isset($_POST['bike'])&&isset($_POST['start_date'])&&isset($_POST['end_date'])) {
            redirect('Maps/View/'.$_POST['bike'].'/'.strtotime($_POST['start_date']).'/'.strtotime($_POST['end_date']).'/');
        } else {
            redirect('Maps');
        }
    }
}
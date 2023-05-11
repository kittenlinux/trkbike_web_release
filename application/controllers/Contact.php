<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contact extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
    }
    
    public function index()
    {
        $this->render('contact');
    }
}

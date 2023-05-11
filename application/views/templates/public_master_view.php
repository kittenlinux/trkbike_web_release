<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    $this->load->view('core/header');
?>
<?php
    echo $the_view_content;
?>
<?php
    $this->load->view('core/footer');
?>
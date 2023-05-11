<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="container">
    <div class="row">
        <div class="col-md-7">
            <!-- Start Contact Info -->
            <ul class="contact-details">
                <li><a href="#"><i class="fa fa-envelope-o"></i> info-bike@trackmycars.net</a></li>
            </ul>
            <!-- End Contact Info -->
        </div>
        <!-- .col-md-6 -->
        <div class="col-md-5">

        </div>
        <!-- Start Social Links -->
        <ul class="social-list">
            <li><i class="fa fa-user"></i> สวัสดี
                <?php
                    $user = $this->ion_auth->user()->row();
                    echo $user->first_name.' '.$user->last_name;
                ?>
            </li>
        </ul>
        <!-- End Social Links -->
        <!-- .col-md-6 -->
    </div>
    <!-- .row -->
</div>
<!-- .container -->
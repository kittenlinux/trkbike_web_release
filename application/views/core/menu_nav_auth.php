<?php
defined('BASEPATH') or exit('No direct script access allowed');
?> <div class="container">
    <div class="navbar-header">
        <!-- Stat Toggle Nav Link For Mobiles -->
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <i class="fa fa-bars"></i>
        </button>
        <!-- End Toggle Nav Link For Mobiles -->
        <a class="navbar-brand" href="<?php echo base_url(); ?>">
            TRACK MY BIKES
        </a>
    </div>
    <div class="navbar-collapse collapse">
        <!-- Start Navigation List -->
        <ul class="nav navbar-nav navbar-right">
            <li>
                <a id="nav-bar-home" href="<?php echo base_url(); ?>">หน้าหลัก</a>
            </li>
            <li>
                <a id="nav-bar-maps" href="<?php echo base_url(); ?>Maps">ติดตาม</a>
            </li>
            <li>
                <a id="nav-bar-dashboard" href="<?php echo base_url(); ?>Dashboard">จัดการข้อมูล</a>
            </li>
            <!-- <li>
                <a id="nav-bar-contact" href="<?php echo base_url(); ?>Contact">ติดต่อเรา</a>
            </li> -->
            <li>
                <a id="nav-bar-member" href="<?php echo base_url(); ?>User">ผู้ใช้งาน</a>
                <ul class="dropdown">
                    <li><a href="<?php echo base_url(); ?>User/Profile">โปรไฟล์ของฉัน</a></li>
                    <li><a href="<?php echo base_url(); ?>User/Signout">ออกจากระบบ</a></li>
                </ul>
            </li>
        </ul>
        <!-- End Navigation List -->
    </div>
</div>

<!-- Mobile Menu Start -->
<ul class="wpb-mobile-menu">
    <li>
        <a id="nav-bar-home" href="<?php echo base_url(); ?>">หน้าหลัก</a>
    </li>
    <li>
        <a id="nav-bar-maps" href="<?php echo base_url(); ?>Maps">ติดตาม</a>
    </li>
    <li>
        <a id="nav-bar-dashboard" href="<?php echo base_url(); ?>Dashboard">จัดการข้อมูล</a>
    </li>
    <!-- <li>
        <a id="nav-bar-contact" href="<?php echo base_url(); ?>Contact">ติดต่อเรา</a>
    </li> -->
    <li>
        <a id="nav-bar-member" href="<?php echo base_url(); ?>User">ผู้ใช้งาน</a>
        <ul class="dropdown">
            <li><a href="<?php echo base_url(); ?>User/Profile">โปรไฟล์ของฉัน</a></li>
            <li><a href="<?php echo base_url(); ?>User/Signout">ออกจากระบบ</a></li>
        </ul>
    </li>
</ul>
<!-- Mobile Menu End -->

<script>
$(document).ready(function() {
    if (nav_bar_active != undefined) {
        $("#" + nav_bar_active).addClass("active");
    }
});
</script>
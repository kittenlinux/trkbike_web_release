<?php
  defined('BASEPATH') or exit('No direct script access allowed');

  $user = $this->ion_auth->user()->row();

  $this->db->from('bike');
  $this->db->where('user', $user->id);

  $count = $this->db->count_all_results();

  $this->db->select();
  $this->db->from('bike');
  $this->db->where('user', $user->id);

  $query = $this->db->get()->result_array();
?>

<section id="maps">
    <!-- Start Page Banner -->
    <div class="page-banner" style="padding:40px 0; background: #f9f9f9;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>เรียกดูการติดตาม</h2>
                    <p>เรียกดูการติดตามของรถจักรยานยนต์แต่ละคันในช่วงเวลาที่ต้องการ ย้อนหลังได้ไม่เกิน 30 วัน</p>
                </div>
                <div class="col-md-6">
                    <ul class="breadcrumbs">
                        <li><a href="<?php echo base_url(); ?>">หน้าหลัก</a></li>
                        <li>เรียกดูการติดตาม</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Banner -->

    <!-- Set active navigator bar menu -->
    <script>
    var nav_bar_active = 'nav-bar-maps';
    </script>

    <!-- Start Content -->
    <div id="content">
        <div class="container">
            <div class="page-content">
                <div class="row">
                    <?php echo isset($_SESSION['result_message']) ? "<div class=\"alert alert-".$_SESSION['result_message_type']."\">".$_SESSION['result_message']."</div>" : false; ?>
                    <div class="col-md-8">
                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>รายละเอียดข้อมูลการติดตาม</span></h4>

                        <!-- Start Contact Form -->
                        <form method="post" accept-charset="utf-8" role="form" class="contact-form"
                            action=<?php echo base_url()."Maps/View_Action/"?>>
                            <div class="alert alert-danger print-error-msg" style="display:none"></div>
                            <div class="col-md-12">
                                <label for="bike">รถจักรยานยนต์ :</label>
                                <div class="form-group">
                                    <div class="controls">
                                        <select class="form-control" id="bike" name="bike" onchange="enable_submit()" onfocus="enable_submit()">
                                            <option value="0" disabled selected>เลือกรถจักรยานยนต์</option>
                                            <?php foreach ($query as $bike) {
    echo "<option value=\"".$bike['key']."\">".$bike['plate']."</option>";
}
                                    ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="start_date">วันและเวลาเริ่มต้น :</label>
                                <div class="form-group">
                                    <div class="controls">
                                        <div class='input-group date' id='datetimepicker_startdate'>
                                            <input type='text' class="form-control" id="start_date" name="start_date"
                                                required onchange="enable_submit()" onfocus="enable_submit()" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date">วันและเวลาสิ้นสุด :</label>
                                <div class="form-group">
                                    <div class="controls">
                                        <div class='input-group date' id='datetimepicker_enddate'>
                                            <input type='text' class="form-control" id="end_date" name="end_date"
                                                required onchange="enable_submit()" onfocus="enable_submit()" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn btn-success"
                                    disabled>ยืนยันและแสดงข้อมูลการติดตาม</button>
                            </div>
                        </form>
                        <!-- End Contact Form -->

                        <!-- Divider -->
                        <div class="hr1" style="margin-bottom:15px;"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- End content -->
</section>

<script>
var newTitle = "เลือกดูการติดตาม | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}

function enable_submit() {
    var e = document.getElementById("bike");
    if (e.options[e.selectedIndex].value !== "0") {
        $('#submit').removeAttr('disabled');
    }
}

$(document).ready(function() {

});

var todayDate = new Date().getDate();
var todaySeconds = new Date().getSeconds();
var start_date = new Date(new Date().setDate(todayDate - 1))
$(function() {
    $('#datetimepicker_startdate').datetimepicker({
        format: "YYYY-MM-DD HH:mm:ss",
        useCurrent: false,
        defaultDate: new Date(start_date.setSeconds(todaySeconds + 1)),
        minDate: new Date(new Date().setDate(todayDate - 30)),
        maxDate: new Date()
    });
    $('#datetimepicker_enddate').datetimepicker({
        format: "YYYY-MM-DD HH:mm:ss",
        useCurrent: false,
        defaultDate: new Date(new Date().setDate(todayDate)),
        minDate: new Date(new Date().setDate(todayDate - 30)),
        maxDate: new Date()
    });
});
</script>
<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    $user = $this->ion_auth->user()->row();

    $this->db->select('lineapi_key');
    $this->db->from('users');
    $this->db->where('id', $user->id);

    $query = $this->db->get();
    foreach ($query->result() as $row) {
        $query1 = $row->lineapi_key;
    }
?>
<section id="dashboard">
    <!-- Start Page Banner -->
    <div class="page-banner" style="padding:40px 0; background: #f9f9f9;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>ข้อมูลโทเค็น</h2>
                    <p>เพิ่มหรือแก้ไขข้อมูลโทเค็นไลน์โนติฟาย</p>
                </div>
                <div class="col-md-6">
                    <ul class="breadcrumbs">
                        <li><a href="<?php echo base_url(); ?>">หน้าหลัก</a></li>
                        <li><a href="<?php echo base_url(); ?>Dashboard">จัดการข้อมูล</a></li>
                        <li>ข้อมูลโทเค็น</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Banner -->

    <!-- Set active navigator bar menu -->
    <script>
    var nav_bar_active = 'nav-bar-dashboard';
    </script>

    <!-- Start Content -->
    <div id="content">
        <div class="container">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>โทเค็นไลน์โนติฟาย</span></h4>

                        <!-- Start Contact Form -->
                        <form accept-charset="utf-8" role="form" class="contact-form" id="contact-form">
                            <div class="form-group">
                                <p><?php
                      if (!$query1) {
                          echo "หากต้องการใช้งานการส่งการแจ้งเตือนผ่านไลน์ ท่านจำเป็นต้องเพิ่มข้อมูลโทเค็นไลน์โนติฟายก่อน";
                      } else {
                          echo "โทเค็นไลน์โนติฟายปัจจุบันของคุณคือ <em>".$query1."</em>";
                      }
              ?></p>
                            </div>
                            <div class="alert alert-danger print-error-msg" style="display:none"></div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="โทเค็นไลน์โนติฟาย" name="token"
                                        required value="<?php echo $query1; ?>">
                                </div>
                            </div>
                            <button type="submit" id="submit"
                                class="btn-submit btn-system btn-large">ยืนยันข้อมูล</button>
                        </form>
                        <!-- End Contact Form -->
                        <!-- Divider -->
                        <div class="hr1" style="margin-bottom:15px;"></div>
                    </div>
                    <div class="col-md-6">
                        <!-- Accordion -->
                        <div class="panel-group" id="accordion">

                            <!-- Start Accordion 1 -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-one">
                                            <i class="fa fa-angle-up control-icon"></i>
                                            <i class="fa fa-desktop"></i> การรับโทเค็นไลน์โนติฟาย
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-one" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        ทำตามขั้นตอนใน<a href="https://jackrobotics.me/%E0%B9%81%E0%B8%88%E0%B9%89%E0%B8%87%E0%B9%80%E0%B8%95%E0%B8%B7%E0%B8%AD%E0%B8%99-line-notify-%E0%B9%80%E0%B8%95%E0%B8%B7%E0%B8%AD%E0%B8%99%E0%B8%87%E0%B9%88%E0%B8%B2%E0%B8%A2%E0%B9%84%E0%B8%94%E0%B9%89%E0%B9%83%E0%B8%88-7328125083ec" target=”_blank”>บทความนี้</a> และนำโทเค็นที่ได้จากระบบมากรอกที่ช่องโทเค็นไลน์โนติฟาย
                                    </div>
                                </div>
                            </div>
                            <!-- End Accordion 1 -->
                        </div>
                        <!-- End Accordion -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End content -->
</section>

<script type="text/javascript">
var newTitle = "ข้อมูลโทเค็น | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}

$(document).ready(function() {
    $(".btn-submit").click(function(e) {
        e.preventDefault();

        var token = $("input[name='token']").val();

        $.ajax({
            url: "<?php echo base_url(); ?>Dashboard/LINENotify_Edit_Action",
            type: 'POST',
            dataType: "json",
            data: {
                token: token
            },
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    $(".print-error-msg").css('display', 'none');
                    location.href =
                        '<?php echo base_url(); ?>Dashboard/LINENotify_Verification';
                } else {
                    $(".print-error-msg").css('display', 'block');
                    $(".print-error-msg").html(data.error);
                }
            }
        });
    });
});
</script>
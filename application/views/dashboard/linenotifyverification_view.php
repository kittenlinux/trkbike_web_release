<?php
    defined('BASEPATH') or exit('No direct script access allowed');
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
                    <div class="col-md-7">
                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>โทเค็นไลน์โนติฟาย</span></h4>

                        <!-- Some Text -->

                        <!-- Start Contact Form -->
                        <form accept-charset="utf-8" role="form" class="contact-form" id="contact-form">
                            <div class="form-group">
                                <p>กรอกหมายเลขยืนยันตัวตน (รหัสผ่านครั้งเดียว หรือ OTP) 6 หลัก ที่ได้รับจากไลน์โนติฟาย (รหัสอ้างอิง : <?php echo $_SESSION['otp_ref'] ?>)
                                </p>
                            </div>
                            <div class="alert alert-danger print-error-msg" style="display:none"></div>
                            <div class="alert alert-success print-success-msg" style="display:none"></div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="รหัสผ่านครั้งเดียว" name="otp"
                                        required maxlength="6">
                                </div>
                            </div>
                            <button type="submit" id="submit" class="btn-submit btn-system btn-large">ตรวจสอบ</button>
                        </form>
                        <!-- End Contact Form -->
                        <!-- Divider -->
                        <div class="hr1" style="margin-bottom:15px;"></div>
                    </div>
                    <div class="col-md-5">
                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>หากท่านไม่ได้รับส่งรหัสผ่านครั้งเดียว</span></h4>

                        <!-- Some Text -->

                        <!-- Start Contact Form -->
                        <form accept-charset="utf-8" role="form" class="contact-form" id="contact-form">
                            <div class="form-group">
                                <p>หากต้องการส่งรหัสผ่านครั้งเดียวไปยังไลน์อีกครั้ง คลิกที่ปุ่มด้านล่าง</p>
                            </div>
                            <input type="hidden" id="resend" name="resend" value="1">
                            <button type="submit" id="submit"
                                class="btn-resend btn-system btn-large">ส่งอีกครั้ง</button>
                        </form>
                        <!-- End Contact Form -->
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

        var otp = $("input[name='otp']").val();

        $.ajax({
            url: "<?php echo base_url(); ?>Dashboard/LINENotify_Verification_Action/0",
            type: 'POST',
            dataType: "json",
            data: {
                otp: otp
            },
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    $(".print-error-msg").css('display', 'none');
                    location.href = '<?php echo base_url(); ?>Dashboard';
                } else {
                    $(".print-success-msg").css('display', 'none');
                    $(".print-error-msg").css('display', 'block');
                    $(".print-error-msg").html(data.error);
                }
            }
        });
    });

    $(".btn-resend").click(function(e) {
        e.preventDefault();

        var resend = $("input[name='resend']").val();

        $.ajax({
            url: "<?php echo base_url(); ?>Dashboard/LINENotify_Verification_Action/1",
            type: 'POST',
            dataType: "json",
            data: {
                resend: resend
            },
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    $(".print-error-msg").css('display', 'none');
                    $(".print-success-msg").css('display', 'block');
                    $(".print-success-msg").html(data.success);
                }
            }
        });
    });
});
</script>
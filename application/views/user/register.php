<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<section id="register">
    <!-- Start Page Banner -->
    <div class="page-banner" style="padding:40px 0; background: #f9f9f9;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>ลงทะเบียน</h2>
                    <p>ก่อนการเข้าใช้งานครั้งแรก กรุณาลงทะเบียนผู้ใช้งาน</p>
                </div>
                <div class="col-md-6">
                    <ul class="breadcrumbs">
                        <li><a href="<?php echo base_url(); ?>">หน้าหลัก</a></li>
                        <li>ลงทะเบียน</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Banner -->

    <!-- Set active navigator bar menu -->
    <script>
    var nav_bar_active = 'nav-bar-member';
    </script>

    <!-- Start Content -->
    <div id="content">
        <div class="container">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>สำหรับผู้ใช้ใหม่</span></h4>

                        <!-- Start Contact Form -->
                        <form accept-charset="utf-8" role="form" class="contact-form" id="contact-form">
                            <div class="form-group">
                                <p>กรอกข้อมูลผู้ใช้งาน ตรวจสอบความถูกต้องแล้วกดปุ่ม "ลงทะเบียน"</p>
                            </div>
                            <div class="alert alert-danger print-error-msg" style="display:none"></div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="ชื่อผู้ใช้" name="username"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="ชื่อ" name="first_name"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="นามสกุล" name="last_name"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="อีเมล" name="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="password" class="form-control" placeholder="รหัสผ่าน" name="password"
                                        required minlength="8" maxlength="64">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="password" class="form-control" placeholder="ยืนยันรหัสผ่าน"
                                        name="confirm_password" required minlength="8" maxlength="64">
                                </div>
                            </div>
                            <button type="submit" id="submit" class="btn-submit btn-system btn-large">ลงทะเบียน</button>
                        </form>
                        <!-- End Contact Form -->

                        <!-- Divider -->
                        <div class="hr1" style="margin-bottom:15px;"></div>
                    </div>

                    <div class="col-md-4">
                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>สำหรับผู้ใช้ปัจจุบัน</span></h4>

                        <!-- Some Info -->
                        <p>คลิกที่ลิงก์ด้านล่างเพื่อทำการเข้าสู่ระบบ</p>
                        <p><a href="<?php echo base_url(); ?>User/Signin">เข้าสู่ระบบ</a></p>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- End content -->

</section>

<script type="text/javascript">
var newTitle = "ลงทะเบียน | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}

$(document).ready(function() {
    $(".btn-submit").click(function(e) {
        e.preventDefault();

        var username = $("input[name='username']").val();
        var first_name = $("input[name='first_name']").val();
        var last_name = $("input[name='last_name']").val();
        var email = $("input[name='email']").val();
        var password = $("input[name='password']").val();
        var confirm_password = $("input[name='confirm_password']").val();

        $.ajax({
            url: "<?php echo base_url(); ?>User/Register_Action",
            type: 'POST',
            dataType: "json",
            data: {
                username: username,
                first_name: first_name,
                last_name: last_name,
                email: email,
                password: password,
                confirm_password: confirm_password
            },
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    $(".print-error-msg").css('display', 'none');
                    location.href = '<?php echo base_url(); ?>User/Signin'
                } else {
                    $(".print-error-msg").css('display', 'block');
                    $(".print-error-msg").html(data.error);
                }
            }
        });
    });
});
</script>
<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<section id="signin">
    <!-- Start Page Banner -->
    <div class="page-banner" style="padding:40px 0; background: #f9f9f9;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>เข้าสู่ระบบ</h2>
                    <p>กรุณาเข้าสู่ระบบก่อนใช้งาน</p>
                </div>
                <div class="col-md-6">
                    <ul class="breadcrumbs">
                        <li><a href="<?php echo base_url(); ?>">หน้าหลัก</a></li>
                        <li>เข้าสู่ระบบ</li>
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
                        <h4 class="classic-title"><span>สำหรับผู้ใช้ปัจจุบัน</span></h4>
                        <?php echo isset($_SESSION['auth_message']) ? "<div class=\"alert alert-".$_SESSION['auth_message_type']."\">".$_SESSION['auth_message']."</div>" : false; ?>
                        <form accept-charset="utf-8" role="form" class="contact-form" id="contact-form">
                            <div class="form-group">
                                <p>กรอกข้อมูลให้ถูกต้องแล้วกดปุ่ม "เข้าสู่ระบบ"</p>
                            </div>
                            <div class="alert alert-danger print-error-msg" style="display:none"></div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="ชื่อผู้ใช้" name="username"
                                        required
                                        value="<?php echo isset($_POST["username"]) ? htmlentities($_POST["username"]) : ''; ?>">
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
                                    <p><label for="remember"><input type="checkbox" id="remember" name="remember"
                                                value="1" /> อยู่ในระบบตลอดไปจนกว่ามีการเข้าใช้งานจากเครื่องอื่น</label>
                                    </p>
                                </div>
                            </div>
                            <button type="submit" id="submit"
                                class="btn-submit btn-system btn-large">เข้าสู่ระบบ</button>
                        </form>

                        <!-- Divider -->
                        <div class="hr1" style="margin-bottom:15px;"></div>
                    </div>

                    <div class="col-md-4">

                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>สำหรับผู้ใช้ใหม่</span></h4>

                        <!-- Some Info -->
                        <p>ลงทะเบียนได้<b>ฟรี !</b> หลังการลงทะเบียนสามารถใช้งานระบบได้ทันที เพียงคลิกที่ลิงก์ด้านล่าง
                        </p>
                        <p><a href="<?php echo base_url(); ?>User/Register">ลงทะเบียน</a></p>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- End content -->

</section>

<script type="text/javascript">
var newTitle = "เข้าสู่ระบบ | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}

$(document).ready(function() {
    $(".btn-submit").click(function(e) {
        e.preventDefault();

        var username = $("input[name='username']").val();
        var password = $("input[name='password']").val();
        var remember = $("input[name='remember']").val();

        $.ajax({
            url: "<?php echo base_url(); ?>User/Signin_Action",
            type: 'POST',
            dataType: "json",
            data: {
                username: username,
                password: password,
                remember: remember
            },
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    $(".print-error-msg").css('display', 'none');
                    location.href = '<?php echo base_url(); ?>Dashboard';
                } else {
                    $(".print-error-msg").css('display', 'block');
                    $(".print-error-msg").html(data.error);
                }
            }
        });
    });
});
</script>
<?php
    defined('BASEPATH') or exit('No direct script access allowed');
?>

<section id="profile_edit">
    <!-- Start Page Banner -->
    <div class="page-banner" style="padding:40px 0; background: #f9f9f9;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>เปลี่ยนรหัสผ่าน</h2>
                    <p>เปลี่ยนรหัสผ่านที่ใช้ในการเข้าสู่ระบบ</p>
                </div>
                <div class="col-md-6">
                    <ul class="breadcrumbs">
                        <li><a href="<?php echo base_url(); ?>">หน้าหลัก</a></li>
                        <li><a href="<?php echo base_url(); ?>User/Profile">โปรไฟล์ของฉัน</a></li>
                        <li>เปลี่ยนรหัสผ่าน</li>
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
                    <?php echo isset($_SESSION['result_message']) ? "<div class=\"alert alert-".$_SESSION['result_message_type']."\">".$_SESSION['result_message']."</div>" : false; ?>
                    <div class="col-md-8">
                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>รายละเอียดผู้ใช้งาน</span></h4>

                        <!-- Start Contact Form -->
                        <form method="post" accept-charset="utf-8" role="form" class="contact-form" id="contact-form">
                            <div class="alert alert-danger print-error-msg" style="display:none"></div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="password" class="form-control" placeholder="รหัสผ่านใหม่"
                                        name="new_password" required minlength="8" maxlength="64">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="password" class="form-control" placeholder="ยืนยันรหัสผ่านใหม่"
                                        name="confirm_new_password" required minlength="8" maxlength="64">
                                </div>
                            </div>
                            <button type="submit" id="submit"
                                class="btn-submit btn-system btn-large">เปลี่ยนรหัสผ่าน</button>
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

<script type="text/javascript">
var newTitle = "เปลี่ยนรหัสผ่าน | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}

$(document).ready(function() {
    $(".btn-submit").click(function(e) {
        e.preventDefault();

        var new_password = $("input[name='new_password']").val();
        var confirm_new_password = $("input[name='confirm_new_password']").val();

        $.ajax({
            url: "<?php echo base_url(); ?>User/Change_Password_Action",
            type: 'POST',
            dataType: "json",
            data: {
                new_password: new_password,
                confirm_new_password: confirm_new_password
            },
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    $(".print-error-msg").css('display', 'none');
                    location.href = '<?php echo base_url(); ?>User/Profile'
                } else {
                    $(".print-error-msg").css('display', 'block');
                    $(".print-error-msg").html(data.error);
                }
            }
        });
    });
});
</script>
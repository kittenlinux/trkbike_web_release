<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    $user = $this->ion_auth->user()->row();
?>
<section id="dashboard">
    <!-- Start Page Banner -->
    <div class="page-banner" style="padding:40px 0; background: #f9f9f9;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>เพิ่มรถจักรยานยนต์</h2>
                    <p>เพิ่มข้อมูลรถจักรยานยนต์ ประกอบไปด้วย หมายเลขทะเบียน ยี่ห้อ รุ่น สี</p>
                </div>
                <div class="col-md-6">
                    <ul class="breadcrumbs">
                        <li><a href="<?php echo base_url(); ?>">หน้าหลัก</a></li>
                        <li><a href="<?php echo base_url(); ?>Dashboard">จัดการข้อมูล</a></li>
                        <li>เพิ่มรถจักรยานยนต์</li>
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
                    <div class="col-md-12">
                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>ข้อมูลรถจักรยานยนต์</span></h4>

                        <!-- Start Contact Form -->
                        <form accept-charset="utf-8" role="form" class="contact-form" id="contact-form">
                            <div class="form-group">
                                <p>กรอกข้อมูลรถจักรยานยนต์ ตรวจสอบความถูกต้องแล้วกดปุ่ม "เพิ่มรถ"</p>
                            </div>
                            <div class="alert alert-danger print-error-msg" style="display:none"></div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="หมายเลขทะเบียน" name="plate"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="ยี่ห้อ รุ่น" name="model"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="สี" name="color" required>
                                </div>
                            </div>
                            <button type="submit" id="submit" class="btn-submit btn-system btn-large">เพิ่มรถ</button>
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
var newTitle = "เพิ่มรถจักรยานยนต์ | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}

$(document).ready(function() {
    $(".btn-submit").click(function(e) {
        e.preventDefault();

        var plate = $("input[name='plate']").val();
        var model = $("input[name='model']").val();
        var color = $("input[name='color']").val();

        $.ajax({
            url: "<?php echo base_url(); ?>Dashboard/Bike_Add_Action",
            type: 'POST',
            dataType: "json",
            data: {
                plate: plate,
                model: model,
                color: color
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
<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    $user = $this->ion_auth->user()->row();

    $this->db->select(array('first_name', 'last_name'));
    $this->db->from('users');
    $this->db->where('id', $user->id);

    $query = $this->db->get();
    foreach ($query->result() as $row) {
        $query1 = $row->first_name;
        $query2 = $row->last_name;
    }
?>

<section id="profile_edit">
    <!-- Start Page Banner -->
    <div class="page-banner" style="padding:40px 0; background: #f9f9f9;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>แก้ไขโปรไฟล์</h2>
                    <p>จัดการแก้ไขข้อมูลส่วนตัว ประกอบไปด้วย ชื่อ และนามสกุล</p>
                </div>
                <div class="col-md-6">
                    <ul class="breadcrumbs">
                        <li><a href="<?php echo base_url(); ?>">หน้าหลัก</a></li>
                        <li><a href="<?php echo base_url(); ?>User/Profile">โปรไฟล์ของฉัน</a></li>
                        <li>แก้ไขโปรไฟล์</li>
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
                        <form accept-charset="utf-8" role="form" class="contact-form" id="contact-form">
                            <div class="alert alert-danger print-error-msg" style="display:none"></div>
                            <label for="first_name">ชื่อ :</label>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="ชื่อ" id="first_name"
                                        name="first_name" required value="<?php echo $query1; ?>">
                                </div>
                            </div>
                            <label for="last_name">นามสกุล :</label>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="นามสกุล" id="last_name"
                                        name="last_name" required value="<?php echo $query2; ?>">
                                </div>
                            </div>
                            <button type="submit" id="submit"
                                class="btn-submit btn-system btn-large">แก้ไขข้อมูล</button>
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
var newTitle = "แก้ไขโปรไฟล์ | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}

$(document).ready(function() {
    $(".btn-submit").click(function(e) {
        e.preventDefault();

        var first_name = $("input[name='first_name']").val();
        var last_name = $("input[name='last_name']").val();

        $.ajax({
            url: "<?php echo base_url(); ?>User/Profile_Edit_Action",
            type: 'POST',
            dataType: "json",
            data: {
                first_name: first_name,
                last_name: last_name
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
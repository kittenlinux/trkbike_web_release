<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    $user = $this->ion_auth->user()->row();

    $this->db->select(array('plate', 'model', 'color', 'key'));
    $this->db->from('bike');
    $this->db->where('key', $_SESSION['bike_key']);

    $query = $this->db->get();
    foreach ($query->result() as $row) {
        $query1 = $row->model;
        $query2 = $row->color;
        $query3 = $row->plate;
        $query4 = $row->key;
    }
?>
<section id="dashboard">
    <!-- Start Page Banner -->
    <div class="page-banner" style="padding:40px 0; background: #f9f9f9;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>แก้ไขข้อมูลรถจักรยานยนต์<br />หมายเลขทะเบียน <?php echo $query3; ?></h2>
                    <p>แก้ไขข้อมูลรถจักรยานยนต์ ยี่ห้อ รุ่น สี</p>
                </div>
                <div class="col-md-6">
                    <ul class="breadcrumbs">
                        <li><a href="<?php echo base_url(); ?>">หน้าหลัก</a></li>
                        <li><a href="<?php echo base_url(); ?>Dashboard">จัดการข้อมูล</a></li>
                        <li>แก้ไขข้อมูลรถจักรยานยนต์</li>
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
                        <h4 class="classic-title"><span>ข้อมูลรถจักรยานยนต์</span></h4>

                        <!-- Start Contact Form -->
                        <form accept-charset="utf-8" role="form" class="contact-form" id="contact-form">
                            <div class="form-group">
                                <p>กรอกข้อมูลรถจักรยานยนต์ ตรวจสอบความถูกต้องแล้วกดปุ่ม "แก้ไขข้อมูล"</p>
                            </div>
                            <div class="alert alert-danger print-error-msg" style="display:none"></div>
                            <label for="model">ยี่ห้อ รุ่น :</label>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="ยี่ห้อ รุ่น" id="model"
                                        name="model" required value="<?php echo $query1; ?>">
                                </div>
                            </div>
                            <label for="color">สี :</label>
                            <div class="form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="สี" id="color" name="color"
                                        required value="<?php echo $query2; ?>">
                                </div>
                            </div>
                            <button type="submit" id="submit"
                                class="btn-submit btn-system btn-large">แก้ไขข้อมูล</button>
                        </form>
                        <!-- End Contact Form -->

                        <!-- Divider -->
                        <div class="hr1" style="margin-bottom:15px;"></div>
                    </div>
                    <div class="col-md-5">
                        <!-- Classic Heading -->
                        <h4 class="classic-title"><span>หากท่านต้องการลบรถจักรยานยนต์</span></h4>
                        <p>การลบรถจักรยานยนต์ออกจากระบบ
                            ข้อมูลการติดตามรถจักรยานยนต์จะหายไปจากระบบอย่าง<strong>ถาวร</strong>
                            ติ๊กถูกช่องด้านล่างเพื่อยืนยัน และคลิกที่ปุ่มด้านล่างเพื่อดำเนินการต่อ</p>
                        <p><label for="del_bike_checkbox"><input id="del_bike_checkbox" type="checkbox" />
                                <strong>ต้องการลบรถจักรยานยนต์ออกจากระบบ</strong></label>
                            <p><button type="button" class="btn btn-danger" id="del_bike" disabled
                                    onclick="Bike_delconfirm('<?php echo $query4; ?>', '<?php echo $query3; ?>')">ลบรถจักรยานยนต์</button>
                            </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End content -->
</section>

<script type="text/javascript">
var newTitle = "แก้ไขข้อมูลรถจักรยานยนต์ | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}

function Bike_delconfirm(bike_key, bike_plate) {
    Swal.fire({
        title: 'ยืนยันการลบรถจักรยานยนต์ ???',
        text: "หากท่านต้องการลบรถคันนี้ออกจากระบบ ข้อมูลการติดตามรถจักรยานยนต์จะหายไปจากระบบอย่างถาวร ยืนยันการลบรถจักรยานยนต์ หมายเลขทะเบียน " +
        bike_plate + " ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ลบรถจักรยานยนต์ !!!',
        cancelButtonText: 'ยกเลิก',
        allowEnterKey: 'false'
    }).then(function (result) {
        if (result.value) {
            // Swal.fire(
            //     title: 'สำเร็จ!',
            //     text: 'ลบโทเค็นเรียบร้อยแล้ว.',
            //     icon: 'success'
            // )
            window.location.href = "<?php echo base_url().'Dashboard/Bike_Remove/'; ?>" + bike_key;
        }
    })
}

$(document).ready(function() {
    $(".btn-submit").click(function(e) {
        e.preventDefault();

        var model = $("input[name='model']").val();
        var color = $("input[name='color']").val();

        $.ajax({
            url: "<?php echo base_url(); ?>Dashboard/Bike_Edit_Action/<?php echo $_SESSION['bike_key']; ?>",
            type: 'POST',
            dataType: "json",
            data: {
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

    $('#del_bike_checkbox').click(function() {
        $('#del_bike').prop("disabled", !$("#del_bike_checkbox").prop("checked"));
    });
});
</script>
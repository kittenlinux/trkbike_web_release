<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    $user = $this->ion_auth->user()->row();

    $this->db->select(array('plate', 'model', 'color', 'key', 'image_name'));
    $this->db->from('bike');
    $this->db->where('key', $_SESSION['bike_key']);

    $query = $this->db->get();
    foreach ($query->result() as $row) {
        $query1 = $row->model;
        $query2 = $row->color;
        $query3 = $row->plate;
        $query4 = $row->key;
        $query5 = $row->image_name;
    }
?>
<section id="dashboard">
    <!-- Start Page Banner -->
    <div class="page-banner" style="padding:40px 0; background: #f9f9f9;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>อัพโหลดรูปรถจักรยานยนต์<br />หมายเลขทะเบียน <?php echo $query3; ?></h2>
                    <p>เพื่อความสะดวกในการระบุตัวรถจักรยานยนต์</p>
                </div>
                <div class="col-md-6">
                    <ul class="breadcrumbs">
                        <li><a href="<?php echo base_url(); ?>">หน้าหลัก</a></li>
                        <li><a href="<?php echo base_url(); ?>Dashboard">จัดการข้อมูล</a></li>
                        <li>อัพโหลดรูปรถจักรยานยนต์</li>
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
                        <h4 class="classic-title"><span>รูปรถจักรยานยนต์</span></h4>
                        <?php echo isset($_SESSION['result_message']) ? "<div class=\"alert alert-".$_SESSION['result_message_type']."\">".$_SESSION['result_message']."</div>" : false; ?>
                        <!-- Start Contact Form -->
                        <?php echo form_open_multipart('Dashboard/Bike_uploadImage/'.$query4); ?>
                        <label for="image">เลือกไฟล์รูปเพื่ออัพโหลด :</label>
                        <div class="form-group">
                            <div class="controls">
                                <input type="file" class="form-control" id="image" name="image" required />
                            </div>
                        </div>
                        <p class="help-block"><em>ขนาดไฟล์สูงสุดที่สามารถอัพโหลดได้ 2 MB รองรับไฟล์นามสกุล JPG, JPEG,
                                PNG และ GIF เท่านั้น</em></p>
                        <div class="form-group">
                            <div class="controls">
                                <button type="submit" name="upload" id="upload"
                                    class="image-upload btn-system btn-large">อัพโหลด</button>
                            </div>
                        </div>
                        </form>
                        <!-- End Contact Form -->
                        <!-- Divider -->
                        <div class="hr1" style="margin-bottom:15px;"></div>
                    </div>

                    <?php if ($query5) {
    echo '<div class="col-md-5">
            <!-- Classic Heading -->
            <h4 class="classic-title"><span>หากท่านต้องการลบรูปปัจจุบัน</span></h4>
            <p><button type="button" class="btn btn-danger" id="del_bike" onclick="Bike_delpicconfirm(\''.$query4.'\', \''.$query3.'\')">ลบรูปรถจักรยานยนต์</button></p></div>';
}?>
                </div>
            </div>
        </div>
    </div>
    <!-- End content -->
</section>

<script type="text/javascript">
var newTitle = "อัพโหลดรูปรถจักรยานยนต์ | Track My Bikes";
if (document.title != newTitle) {
    document.title = newTitle;
}

function Bike_delpicconfirm(bike_key, bike_plate) {
    Swal.fire({
        title: 'ยืนยันการลบรูปรถจักรยานยนต์ ?',
        text: "ยืนยันการลบรูปรถจักรยานยนต์ หมายเลขทะเบียน " + bike_plate + " ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ลบรูปรถจักรยานยนต์',
        cancelButtonText: 'ยกเลิก',
        allowEnterKey: 'false'
    }).then(function (result) {
        if (result.value) {
            // Swal.fire(
            //     title: 'สำเร็จ!',
            //     text: 'ลบโทเค็นเรียบร้อยแล้ว.',
            //     icon: 'success'
            // )
            window.location.href = "<?php echo base_url().'Dashboard/Bike_PicRemove/' ?>" + bike_key;
        }
    })
}
</script>